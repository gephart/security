<?php

namespace Gephart\Security\Provider;

use Gephart\Security\Configuration\SecurityConfiguration;
use Gephart\Security\Entity\User;
use Gephart\Sessions\Sessions;

class StaticProvider implements ProviderInterface
{

    /**
     * @var SecurityConfiguration
     */
    private $security_configuration;

    /**
     * @var Sessions
     */
    private $sessions;

    public function __construct(SecurityConfiguration $security_configuration, Sessions $sessions)
    {
        $this->security_configuration = $security_configuration;
        $this->sessions = $sessions;
    }

    public function authorise(string $username, string $password)
    {
        $users = $this->security_configuration->get("users");
        $provider = $this->security_configuration->get("provider")[get_class($this)];

        if (empty($users) || empty($users[$username])) {
            throw new \Exception("User '$username' not found.");
        }

        if (!empty($provider["salt"])) {
            $password .= $provider["salt"];
        }

        if (!password_verify($password, $users[$username]["password"])) {
            throw new \Exception("Password is bad.");
        }

        $user_data = $users[$username];

        $user = new $provider["entity"]();
        $user
            ->setPassword($user_data["password"])
            ->setRoles($user_data["roles"])
            ->setUsername($username);

        $this->setUser($user);
    }
    
    public function getUser()
    {

        return $this->sessions->get("user");
    }

    private function setUser($user)
    {
        $this->sessions->set("user", $user);
    }

}