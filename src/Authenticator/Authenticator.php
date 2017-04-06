<?php

namespace Gephart\Security\Authenticator;

use Gephart\DependencyInjection\Container;
use Gephart\Security\Configuration\SecurityConfiguration;
use Gephart\Security\Entity\UserInterface;
use Gephart\Security\Provider\ProviderInterface;

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

    public function __construct(SecurityConfiguration $security_configuration, Container $container)
    {
        $this->security_configuration = $security_configuration;
        $this->container = $container;
    }

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

    public function authorise(string $user, string $password)
    {
        $providers = $this->security_configuration->get("provider");

        foreach ($providers as $provider_class => $provider_settings) {
            /** @var ProviderInterface $provider */
            $provider = $this->container->get($provider_class);

            try {
                $provider->authorise($user, $password);
                return true;
            } catch (\Exception $exception) {
                throw $exception;
            }
        }
    }

    public function isGranted(string $role): bool
    {
        /** @var UserInterface $user */
        $user = $this->getUser();

        if (!$user) {
            return false;
        }

        $user_roles = $user->getRoles();
        $cascade_roles = $this->getCascadeRoles($role);

        foreach ($user_roles as $user_role) {
            $user_cascade_roles = $this->getCascadeRoles($user_role);
            $intersect = array_intersect($user_cascade_roles, $cascade_roles);

            if (count($intersect) > 0) {
                return true;
            }
        }

        return false;
    }

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