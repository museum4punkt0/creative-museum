<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AwardedRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Secured resource.
 *
 * @\App\Validator\Constraints\Awarded
 */
#[ORM\Entity(repositoryClass: AwardedRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get',
        'post' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN') or object.giver == user"],
    ],
    itemOperations: [
        'get',
        'patch' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN') or (object.giver == user and previous_object.giver == user)"],
        'delete' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN')"],
    ],
)]
class Awarded
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    public $giver;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $winner;

    #[ORM\ManyToOne(targetEntity: Award::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $award;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAward(): ?Award
    {
        return $this->award;
    }

    public function setAward(?Award $award): self
    {
        $this->award = $award;

        return $this;
    }
}
