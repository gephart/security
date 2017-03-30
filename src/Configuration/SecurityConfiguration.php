<?php

namespace Gephart\Security\Configuration;

use Gephart\Configuration\Configuration;

class SecurityConfiguration
{

    private $security_configuration;

    public function __construct(Configuration $configuration)
    {
        $security_configuration = $configuration->get("security");

        if (empty($security_configuration["role"])) {
            throw new \Exception("In configuration (security.json) must be specify 'role'");
        }

        $this->security_configuration = $security_configuration;
    }

    public function get(string $key)
    {
        return $this->security_configuration[$key];
    }

}