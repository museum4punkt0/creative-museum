<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Service;

use InvalidArgumentException;
use League\OAuth2\Client\Provider\GenericProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Http\UploadedFile;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class CmApiService implements SingletonInterface
{
    /**
     * @var array
     */
    protected $configuration;

    /**
     * @return string
     */
    abstract public function getEndpoint(): string;

    /**
     * @param ExtensionConfiguration $config
     */
    public function __construct(
        ExtensionConfiguration $config
    ) {
        try {
            $this->configuration = $config->get('creativemuseum');
        } catch (Exception $e) {

        }
    }

    protected function get()
    {
        $endpoint = $this->getEndpoint();

        /** @var RequestFactory $request */
        $request = GeneralUtility::makeInstance(RequestFactory::class);

        $response = $request->request(
            $this->configuration['baseUrl'] . '/' . $endpoint,
            Request::METHOD_GET,
            [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]
        );

        $body = $response->getBody()->getContents();

        try {
            $decodedBody = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        } catch(\JsonException $e) {
            $decodedBody = null;
        }

        return $decodedBody;
    }

    protected function getSingle($id)
    {
        $endpoint = $this->getEndpoint();

        /** @var RequestFactory $request */
        $request = GeneralUtility::makeInstance(RequestFactory::class);

        $response = $request->request(
            $this->configuration['baseUrl'] . '/' . $endpoint . '/' . $id,
            Request::METHOD_GET,
            [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]
        );

        $body = $response->getBody()->getContents();

        try {
            $decodedBody = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        } catch(\JsonException $e) {
            $decodedBody = null;
        }

        return $decodedBody;
    }

    /**
     * @param ?array $data
     * @param ?UploadedFile $file
     * @return string|null
     */
    protected function post(?array $data, ?UploadedFile $file): ?string
    {
        /** @var RequestFactory $request */
        $request = GeneralUtility::makeInstance(RequestFactory::class);

        $token = $this->getOauthClient()->getAccessToken('client_credentials')->getToken();

        $options = [
            'headers' => [
                'Accept' => 'application/ld+json',
                'Authorization' => 'Bearer ' . $token
            ]
        ];

        if (null !== $data) {
            $options['json'] = $data;
        }

        if (null !== $file) {
            $options['multipart'] = [
                [
                    'name' => 'file',
                    'contents' => $file->getStream()->getContents(),
                    'filename' => $file->getClientFilename()
                ]
            ];
        }

        $response = $request->request(
            $this->configuration['baseUrl'] . '/' . $this->getEndpoint(),
            Request::METHOD_POST,
            $options
        );

        if ($response->getStatusCode() !== Response::HTTP_CREATED) {
            return null;
        }

        try {
            $decodedBody = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch(\JsonException $e) {
            $decodedBody = null;
        }

        return $decodedBody['@id'];
    }

    protected function delete(int $id)
    {
        /** @var RequestFactory $request */
        $request = GeneralUtility::makeInstance(RequestFactory::class);

        $token = $this->getOauthClient()->getAccessToken('client_credentials')->getToken();

        $response = $request->request(
            $this->configuration['baseUrl'] . '/' . $this->getEndpoint() . '/' . $id,
            Request::METHOD_DELETE
        );

        $code = $response->getStatusCode();

        return $code === Response::HTTP_NO_CONTENT;
    }

    protected function patch(array $data)
    {
        if (!isset($data['id'])) {
            throw new InvalidArgumentException('You must provide an item id to patch data via api');
        }

        $id = $data['id'];
        unset($data['id']);

        /** @var RequestFactory $request */
        $request = GeneralUtility::makeInstance(RequestFactory::class);

        $token = $this->getOauthClient()->getAccessToken('client_credentials')->getToken();

        $response = $request->request(
            $this->configuration['baseUrl'] . '/' . $this->getEndpoint() . '/' . $id,
            Request::METHOD_PATCH,
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/merge-patch+json'
                ],
                'json' => $data
            ]
        );

        $code = $response->getStatusCode();

        return $code === Response::HTTP_OK;
    }

    /**
     * @return BackendUserAuthentication
     */
    protected function getBackendUser()
    {
        return $GLOBALS['BE_USER'];
    }

    /**
     * @return GenericProvider
     */
    protected function getOauthClient()
    {
        return new GenericProvider([
            'clientId' => $this->configuration['clientId'],
            'clientSecret' => $this->configuration['clientSecret'],
            'urlAccessToken' => $this->configuration['tokenUrl'],
            'urlAuthorize' => $this->configuration['tokenUrl'],
            'urlResourceOwnerDetails' => $this->configuration['tokenUrl']
        ]);
    }
}