<?php

include_once __DIR__ . "/../vendor/autoload.php";

class SecurityConfigurationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Gephart\DependencyInjection\Container
     */
    private $container;
    
    public function setUp()
    {
        $container = new \Gephart\DependencyInjection\Container();
        
        $this->container = $container;
    }

    public function testInitConfiguration()
    {
        $this->setConfiguration();

        $is_not_exception = false;

        try {
            $security_configuration = $this->container->get(\Gephart\Security\Configuration\SecurityConfiguration::class);
            $is_not_exception = true;
        } catch (Exception $e) {}

        $this->assertTrue($is_not_exception);
    }

    public function testInitConfigurationException()
    {
        $this->setBadConfiguration();

        $is_exception = false;

        try {
            $security_configuration = $this->container->get(\Gephart\Security\Configuration\SecurityConfiguration::class);
        } catch (Exception $e) {
            $is_exception = true;
        }

        $this->assertTrue($is_exception);
    }

    public function testGet()
    {
        $this->setConfiguration();

        $security_configuration = $this->container->get(\Gephart\Security\Configuration\SecurityConfiguration::class);
        $role = $security_configuration->get("role");

        $this->assertTrue(isset($role["ROLE_ADMIN"]));
    }

    private function setConfiguration()
    {
        /** @var \Gephart\Configuration\Configuration $configuration */
        $configuration = $this->container->get(\Gephart\Configuration\Configuration::class);
        $configuration->setDirectory(__DIR__ . "/../configurations/config");
    }

    private function setBadConfiguration()
    {
        /** @var \Gephart\Configuration\Configuration $configuration */
        $configuration = $this->container->get(\Gephart\Configuration\Configuration::class);
        $configuration->setDirectory(__DIR__ . "/../configurations/config_bad");
    }
}