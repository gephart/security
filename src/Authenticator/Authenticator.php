<?php

namespace Gephart\Security\Authenticator;

use Gephart\DependencyInjection\Container;
use Gephart\Security\Configuration\SecurityConfiguration;
use Gephart\Security\Entity\UserInterface;
use Gephart\Security\Provider\ProviderInterface;

/**
 * Basic authentificator
 *
 * @package Gephart\Security\Authenticator
 * @author Michal Katuščák <michal@katuscak.cz>
 * @since 0.3
 */
class Authenticator implements AuthenticatorInterface
{

    /**
     * @var SecurityConfiguration
     */
    private $security_configuration;

    /**
     * @var Container
     */
    private $container;

    /**
     * @var ProviderInterface
     */
    private $provider;

    /**
     * @param SecurityConfiguration $security_configuration
     * @param Container $container
     */
    public function __construct(SecurityConfiguration $security_configuration, Container $container)
    {
        $this->security_configuration = $security_configuration;
        $this->container = $container;
    }

    /**
     * @return bool|UserInterface
     */
    public function getUser()
    {
        $providers = $this->security_configuration->get("provider");

        foreach ($providers as $provider_class => $provider_settings) {
            $provider = $this->container->get($provider_class);
            if ($user = $provider->getUser()) {
                return $user;
            }
        }

        return false;
    }

    /**
     * @param string $user
     * @param string $password
     * @return bool
     * @throws \Exception
     */
    public function authorise(string $user, string $password)
    {
        $providers = $this->security_configuration->get("provider");

        foreach ($providers as $provider_class => $provider_settings) {
            /** @var ProviderInterface $provider */
            $provider = $this->container->get($provider_class);

            try {
                if ($provider->authorise($user, $password)) {
                    $this->provider = $provider;
                    return true;
                }
            } catch (\Exception $exception) {
                throw $exception;
            }
        }
    }

    /**
     * @return bool
     */
    public function unauthorise()
    {
        $providers = $this->security_configuration->get("provider");

        foreach ($providers as $provider_class => $provider_settings) {
            $provider = $this->container->get($provider_class);
            if ($provider->unauthorise()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $role
     * @return bool
     */
    public function isGranted(string $role): bool
    {
        /** @var UserInterface $user */
        $user = $this->getUser();

        if (!$user) {
            return false;
        }

        $user_roles = $user->getRoles();

        foreach ($user_roles as $user_role) {
            $user_cascade_roles = $this->getCascadeRoles($user_role);

            if (in_array($role, $user_cascade_roles)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $role
     * @return array
     */
    private function getCascadeRoles(string $role): array
    {
        $roles = [$role];

        $defined_roles = $this->security_configuration->get("role");

        if (isset($defined_roles[$role]) && is_array($defined_roles[$role])) {
            foreach ($defined_roles[$role] as $subrole) {
                $subroles = $this->getCascadeRoles($subrole);
                $roles = array_merge($roles, $subroles);
            }
        }

        return $roles;
    }
}
