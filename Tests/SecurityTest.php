<?php

include_once __DIR__ . "/../vendor/autoload.php";

include_once __DIR__ . "/Controller/TestController.php";

class SecurityTest extends \PHPUnit\Framework\TestCase
{
    
    private $container;
    
    public function setUp()
    {
        $container = new \Gephart\DependencyInjection\Container();
        
        /** @var \Gephart\Configuration\Configuration $configuration */
        $configuration = $container->get(\Gephart\Configuration\Configuration::class);
        $configuration->setDirectory(__DIR__ . "/configurations/config");
        
        $this->container = $container;
    }
    
    public function testUser()
    {
        /** @var \Gephart\Security\SecurityReader $security_reader */
        $security_reader = $this->container->get(\Gephart\Security\SecurityReader::class);

        /** @var \Gephart\Security\Configuration\SecurityConfiguration $security_configuration */
        $security_configuration = $this->container->get(\Gephart\Security\Configuration\SecurityConfiguration::class);

        $provider_name = $security_configuration->get("provider")[0];
        $provider = $this->container->get($provider_name);

        $muse_have_role = $security_reader->getMustHaveRole(TestController::class, "roleUserAction");
        $user = $provider->getUser("admin");

        var_dump($user);
    }

}