<?php

namespace Gephart\Security\Entity;

interface UserInterface
{

    public function getUsername(): string;
    public function setUsername(string $username);

    public function getPassword(): string;
    public function setPassword(string $password);

    public function getRoles(): ?array;
    public function setRoles(?array $roles);
}
