<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AwardedRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AwardedRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get",
        "post" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN') or object.giver == user"],
    ],
    itemOperations: [
        "get",
        "patch" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN') or (object.giver == user and previous_object.giver == user)"],
        "delete" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN')"]
    ],
)]
class Awarded
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToMany(targetEntity: Award::class)]
    private $award;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $giver;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $winner;

    public function __construct()
    {
        $this->award = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Award>
     */
    public function getAward(): Collection
    {
        return $this->award;
    }

    public function addAward(Award $award): self
    {
        if (!$this->award->contains($award)) {
            $this->award[] = $award;
        }

        return $this;
    }

    public function removeAward(Award $award): self
    {
        $this->award->removeElement($award);

        return $this;
    }

    public function getGiver(): ?User
    {
        return $this->giver;
    }

    public function setGiver(?User $giver): self
    {
        $this->giver = $giver;

        return $this;
    }

    public function getWinner(): ?User
    {
        return $this->winner;
    }

    public function setWinner(?User $winner): self
    {
        $this->winner = $winner;

        return $this;
    }
}
