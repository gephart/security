<?php

namespace Gephart\Security\Provider;

use Gephart\Security\Configuration\SecurityConfiguration;
use Gephart\Security\Entity\User;

class StaticProvider implements ProviderInterface
{

    /**
     * @var SecurityConfiguration
     */
    private $security_configuration;

    public function __construct(SecurityConfiguration $security_configuration)
    {
        $this->security_configuration = $security_configuration;
    }
    
    public function getUser(string $username): User
    {
        $users = $this->security_configuration->get("users");

        if (empty($users) || empty($users[$username])) {
            throw new \Exception("User '$username' not found.");
        }

        $user_data = $users[$username];

        $user = new User();
        $user
            ->setPassword($user_data["password"])
            ->setRoles($user_data["roles"])
            ->setUsername($username);

        return $user;
    }

}