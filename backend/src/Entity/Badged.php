<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BadgedRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BadgedRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get",
        "post" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN') or object.user == user"],
    ],
    itemOperations: [
        "get",
        "patch" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN') or (object.user == user and previous_object.user == user)"],
        "delete" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN')"]
    ],
)]
class Badged
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: Badge::class)]
    #[ORM\JoinColumn(nullable: false)]
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
