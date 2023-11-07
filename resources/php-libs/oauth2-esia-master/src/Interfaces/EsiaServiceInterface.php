<?php

namespace Ekapusta\OAuth2Esia\Interfaces;

use UnexpectedValueException;

interface EsiaServiceInterface
{
    /**
     * @return string
     */
    public function generateState();

    /**
     * @param  string  $generatedState
     * @return string
     */
    public function getAuthorizationUrl($generatedState);

    /**
     * @param  string  $generatedState
     * @param  string  $passedState
     * @param  string  $passedCode
     * @return array
     *
     * @throws UnexpectedValueException
     */
    public function getResourceOwner($generatedState, $passedState, $passedCode);
}
