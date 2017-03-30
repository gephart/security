<?php

namespace Gephart\Security\Provider;

interface ProviderInterface
{

    public function getUser(string $username);

}