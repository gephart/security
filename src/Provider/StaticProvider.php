<?php

namespace Gephart\Security\Provider;

use Gephart\Security\Configuration\SecurityConfiguration;
use Gephart\Security\Entity\User;
use Gephart\Security\Entity\UserInterface;
use Gephart\Sessions\Sessions;

/**
 * Security reader
 *
 * @package Gephart\Security\Provider
 * @author Michal Katuščák <michal@katuscak.cz>
 * @since 0.3
 */
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

    /**
     * @param SecurityConfiguration $security_configuration
     * @param Sessions $sessions
     */
    public function __construct(SecurityConfiguration $security_configuration, Sessions $sessions)
    {
        $this->security_configuration = $security_configuration;
        $this->sessions = $sessions;
    }

    /**
     * @param string $username
     * @param string $password
     * @throws \Exception
     */
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

        /** @var UserInterface $user */
        $user = new $provider["entity"]();
        $user
            ->setPassword($user_data["password"])
            ->setRoles($user_data["roles"])
            ->setUsername($username);

        $this->setUser($user);
    }

    /**
     * Remove user from session
     */
    public function unauthorise()
    {
        $this->sessions->set("user", false);
    }

    /**
     * Get user from session
     *
     * @return bool|UserInterface
     */
    public function getUser()
    {
        return $this->sessions->get("user");
    }

    /**
     * Add user to session
     *
     * @param UserInterface $user
     */
    private function setUser(UserInterface $user)
    {
        $this->sessions->set("user", $user);
    }
}
