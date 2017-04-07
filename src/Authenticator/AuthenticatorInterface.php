<?php

namespace Gephart\Security\Authenticator;

interface AuthenticatorInterface
{

    public function getUser();
    public function authorise(string $user, string $password);
    public function unauthorise();

}