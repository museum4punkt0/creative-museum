<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Domain\Model\Dto;

use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;

class UserDto extends AbstractDomainObject
{
    protected string $uuid;

    protected string $firstName;

    protected string $lastName;

    protected string $email;

    protected string $username;

    protected string $description;

    protected bool $active;

    protected bool $deleted;

    protected \DateTime $lastLogin;

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     * @return UserDto
     */
    public function setUuid(string $uuid): UserDto
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return UserDto
     */
    public function setFirstName(string $firstName): UserDto
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return UserDto
     */
    public function setLastName(string $lastName): UserDto
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return UserDto
     */
    public function setEmail(string $email): UserDto
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return UserDto
     */
    public function setUsername(string $username): UserDto
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return UserDto
     */
    public function setDescription(string $description): UserDto
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return UserDto
     */
    public function setActive(bool $active): UserDto
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * @param bool $deleted
     * @return UserDto
     */
    public function setDeleted(bool $deleted): UserDto
    {
        $this->deleted = $deleted;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastLogin(): \DateTime
    {
        return $this->lastLogin;
    }

    /**
     * @param \DateTime $lastLogin
     * @return UserDto
     */
    public function setLastLogin(\DateTime $lastLogin): UserDto
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    /**
     * @return array
     */
    public function serialize()
    {
        $data = [

        ];

        return $data;
    }
}