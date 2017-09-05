<?php

namespace Gephart\Security\Configuration;

use Gephart\Configuration\Configuration;

/**
 * Security configuration
 *
 * @package Gephart\Security\Configuration
 * @author Michal Katuščák <michal@katuscak.cz>
 * @since 0.3
 */
class SecurityConfiguration
{

    private $security_configuration;

    /**
     * @param Configuration $configuration
     * @throws \Exception
     */
    public function __construct(Configuration $configuration)
    {
        $security_configuration = $configuration->get("security");

        if (empty($security_configuration["role"])) {
            throw new \Exception("In configuration (security.json) must be specify 'role'");
        }

        $this->security_configuration = $security_configuration;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function get(string $key)
    {
        if (isset($this->security_configuration[$key])) {
            return $this->security_configuration[$key];
        }

        return false;
    }

}