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
use App\Controller\GetPostFeedbackResultsController;
use App\Repository\PostFeedbackRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @\App\Validator\Constraints\FeedbackGiven
 * @\App\Validator\Constraints\CampaignInactive
 */
#[ORM\Entity(repositoryClass: PostFeedbackRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => ['read:feedbacks']]],
        'post' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN') or object.user == user"],
        'get_post_feedback_results' => [
            'method' => 'GET',
            'path' => '/posts/{postId}/feedback_results',
            'read' => false,
            'controller' => GetPostFeedbackResultsController::class,
        ],
    ],
    itemOperations: [
        'get' => ['security' => "is_granted('ROLE_ADMIN') or object.user == user"],
        'patch' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN') or (object.user == user and previous_object.user == user)"],
        'delete' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN') or (object.user == user and previous_object.user == user)"],
    ],
)]
#[ApiFilter(SearchFilter::class, properties: ['post' => 'exact', 'user' => 'exact'])]
class PostFeedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read:feedbacks'])]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    public $user;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'feedbacks')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:feedbacks'])]
    public $post;

    #[ORM\ManyToOne(targetEntity: CampaignFeedbackOption::class, inversedBy: 'votes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:feedbacks'])]
    private $selection;

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

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getSelection(): ?CampaignFeedbackOption
    {
        return $this->selection;
    }

    public function setSelection(?CampaignFeedbackOption $selection): self
    {
        $this->selection = $selection;

        return $this;
    }
}
