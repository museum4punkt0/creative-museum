<?php
declare(strict_types=1);

namespace JWIED\Creativemuseum\Service;

use JWIED\Creativemuseum\Domain\Model\Dto\UserDto;
use JWIED\Creativemuseum\Domain\Model\Dto\UserListFilterDto;

class UserService extends CmApiService
{
    const ENDPOINT = 'v1/users';

    const RECORDS_PER_PAGE = 30;

    public function getUsers(UserListFilterDto $filter): ?array
    {
        $qsTmpl = '?or[fullName]=%s&or[email]=%s&or[username]=%s&page=%d';

        $queryString = sprintf(
            $qsTmpl,
            $filter->getSearchString(),
            $filter->getSearchString(),
            $filter->getSearchString(),
            $filter->getPage()
        );

        return $this->get($queryString);
    }

    public function getUser(string $uuid): ?array
    {
        return $this->getSingle($uuid);
    }

    public function updateUser(string $uuid, array $data): bool
    {
        return $this->patch(['id' => $uuid] + $data);
    }

    public function convert(array $userArray): UserDto
    {
        $user = new UserDto();
        $user
            ->setUuid($userArray['uuid'] ?? '')
            ->setFirstName($userArray['firstName'] ?? '')
            ->setLastName($userArray['lastName'] ?? '')
            ->setEmail($userArray['email'] ?? '')
            ->setUsername($userArray['username'] ?? '')
            ->setDescription($userArray['description'] ?? '')
            ->setActive($userArray['active'] ?? false)
            ->setDeleted($userArray['deleted'] ?? false)
            ->setLastLogin(new \DateTime($userArray['lastLogin'] ?? 'now'));

        return $user;
    }

    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }
}