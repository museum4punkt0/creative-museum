<?php
declare(strict_types=1);

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Security;

class UserMeDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(private Security $security)
    {
        $this->security = $security;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return 'me' === $operationName && User::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?User
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            return null;
        }

        return $user;
    }
}