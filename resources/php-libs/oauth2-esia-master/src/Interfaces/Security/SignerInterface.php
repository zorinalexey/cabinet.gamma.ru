<?php

namespace Ekapusta\OAuth2Esia\Interfaces\Security;

interface SignerInterface
{
    /**
     * @param  string  $message
     * @return string
     *
     * @throws \Ekapusta\OAuth2Esia\Security\Signer\Exception\SignException
     */
    public function sign($message);
}
