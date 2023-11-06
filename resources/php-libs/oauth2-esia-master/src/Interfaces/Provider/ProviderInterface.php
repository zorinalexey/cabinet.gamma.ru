<?php

namespace Ekapusta\OAuth2Esia\Interfaces\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;

interface ProviderInterface
{
    /**
     * @return string
     */
    public function generateState();

    /**
     * Builds the authorization URL.
     *
     * @return string Authorization URL
     */
    public function getAuthorizationUrl(array $options = []);

    /**
     * Requests an access token using a specified grant and option set.
     *
     * @param mixed $grant
     *
     * @return AccessToken
     */
    public function getAccessToken($grant, array $options = []);

    /**
     * Requests and returns the resource owner of given access token.
     *
     * @return ResourceOwnerInterface
     */
    public function getResourceOwner(AccessToken $token);
}
