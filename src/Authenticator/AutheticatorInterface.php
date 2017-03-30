<?php

namespace Gephart\Security\Autheticator;

use Gephart\Security\Provider\ProviderInterface;

interface AutheticatorInterface
{

    public function getUser();
    public function setProvider(ProviderInterface $provider);

}