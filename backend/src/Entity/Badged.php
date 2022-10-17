<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\BadgedRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BadgedRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get',
        'post' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN') or object.user == user"],
    ],
    itemOperations: [
        'get',
        'patch' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN') or (object.user == user and previous_object.user == user)"],
        'delete' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN')"],
    ],
)]
#[UniqueEntity(
    fields: ['user', 'badge'],
    message: 'This user already received this badge.',
    errorPath: 'badge',
)]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'user' => 'exact',
    ]
)]
class Badged
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'achievements')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: Badge::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['user:me:read', 'users:read'])]
    private $badge;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getBadge(): ?Badge
    {
        return $this->badge;
    }

    public function setBadge(?Badge $badge): self
    {
        $this->badge = $badge;

        return $this;
    }
}
