<?php
declare(strict_types=1);

namespace JWIED\Creativemuseum\Service;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class NotificationService extends CmApiService
{
    const ENDPOINT = 'v1/notifications';

    public function sendGlobalNotification(string $message): bool
    {
        if (empty($message)) {
            return false;
        }
        return $this->sendNotification(['text' => $message]);
    }

    public function sendCampaignNotification(int $campaignId, string $message): bool
    {
        if (empty($message)) {
            return false;
        }
        return $this->sendNotification(['text' => $message, 'campaign' => "/v1/campaigns/{$campaignId}"]);
    }

    public function sendUserNotification(string $userUuid, string $message): bool
    {
        if (empty($message) || empty($userUuid)) {
            return false;
        }

        return $this->sendNotification(['text' => $message, 'user' => "/v1/users/{$userUuid}"]);
    }

    public function sendNotification(array $data)
    {
        /** @var RequestFactory $request */
        $request = GeneralUtility::makeInstance(RequestFactory::class);

        try {
            $token = $this->getOauthClient()->getAccessToken('client_credentials')->getToken();
        } catch (IdentityProviderException $e) {
            return false;
        }

        $options = [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ]
        ];

        $options['json'] = $data;

        $response = $request->request(
            $this->configuration['baseUrl'] . '/' . $this->getEndpoint() . '/editor',
            Request::METHOD_POST,
            $options
        );

        return $response->getStatusCode() === Response::HTTP_CREATED;
    }

    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }
}