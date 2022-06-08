<?php

declare(strict_types=1);

namespace App\OauthProvider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class IdpOauthProvider extends AbstractProvider
{
    use BearerAuthorizationTrait;

    public function getBaseAuthorizationUrl() {
        return null;
    }

    public function getBaseAccessTokenUrl(array $params) {
        return $_ENV['IDP_OAUTH_TOKEN_URL'];
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token) { }

    protected function getDefaultScopes() { 
        return ['api'];
    }

    protected function checkResponse(ResponseInterface $response, $data) { }

    protected function createResourceOwner(array $response, AccessToken $token) { }

    protected function getAllowedClientOptions(array $options)
    {
        return ['timeout', 'proxy', 'verify'];
    }
}