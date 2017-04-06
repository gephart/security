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

        /** @var \Gephart\Security\Authenticator\Authenticator $authenticator */
        $authenticator = $this->container->get(\Gephart\Security\Authenticator\Authenticator::class);

        $muse_have_role = $security_reader->getMustHaveRole(TestController::class, "roleUserAction");
        $this->assertEquals($muse_have_role, "ROLE_USER");

        $test = false;
        try {
            $authenticator->authorise("admin", "admin.123");

            if ($authenticator->isGranted($muse_have_role)) {
                $test = true;
            }
        } catch (Exception $exception) {}

        $this->assertTrue($test);
    }

}