<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PollOptionChoiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PollOptionChoiceRepository::class)]
#[ApiResource(
    attributes: [
        "security" => "is_granted('ROLE_ADMIN')"
    ],
    collectionOperations: [
        "get",
        "post" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get",
        "patch" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN')"],
        "delete" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN')"],
    ],
)]
class PollOptionChoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: PollOption::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $pollOption;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPollOption(): ?PollOption
    {
        return $this->pollOption;
    }

    public function setPollOption(?PollOption $pollOption): self
    {
        $this->pollOption = $pollOption;

        return $this;
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
}
