<?php

namespace Gephart\Security\Provider;

interface ProviderInterface
{

    public function getUser();
    public function authorise(string $user, string $password);
    public function unauthorise();

}