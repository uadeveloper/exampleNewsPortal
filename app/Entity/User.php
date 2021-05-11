<?php

namespace App\Entity;

use SiteCore\AbstractEntity;

/**
 * Class User
 * @package App\Entity
 */
class User extends AbstractEntity
{

    protected $id;

    protected $login;

    protected $password_hash;

    protected $userPermissions = [];

    protected $created;

    /**
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->password_hash;
    }

    /**
     * @param string $password_hash
     */
    public function setPasswordHash(string $password_hash): void
    {
        $this->password_hash = $password_hash;
    }

    /**
     * @return array
     */
    public function getUserPermissions(): ?array
    {
        return $this->userPermissions;
    }

    /**
     * @param array $userPermissions
     */
    public function setUserPermissions(array $userPermissions): void
    {
        $this->userPermissions = $userPermissions;
    }

    /**
     * @param string $permission
     * @return bool
     */
    public function hasUserPermission(string $permission): bool
    {
        foreach ($this->userPermissions as $permissionEntity) {
            if ($permissionEntity->getPermission() === $permission) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getCreated(): string
    {
        return $this->created;
    }

    /**
     * @param string $created
     */
    public function setCreated(string $created): void
    {
        $this->created = $created;
    }

}