<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PollOptionChoiceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @\App\Validator\Constraints\PollOptionChoiced
 */
#[ORM\Entity(repositoryClass: PollOptionChoiceRepository::class)]
#[ApiResource(
    attributes: [
        'denormalization_context' => ['groups' => ['write:pollOptionChoice']],
    ],
    collectionOperations: [
        'post' => [
            'security_post_denormalize' => 'object.user == user',
        ],
    ],
    itemOperations: [
        'get',
        'delete' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN') or object.user == user"],
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
    #[Groups(['write:pollOptionChoice'])]
    private $pollOption;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['write:pollOptionChoice'])]
    public $user;

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
