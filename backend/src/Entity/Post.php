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
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\AddPostToPlaylistController;
use App\Controller\SetBookmarkController;
use App\Controller\SetCommentController;
use App\Enum\PostType;
use App\Repository\PostRepository;
use App\Validator\Constraints\PlaylistType;
use App\Validator\Constraints\PollType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Secured resource.
 *
 * @PlaylistType
 * @PollType
 * @\App\Validator\Constraints\PostBodyLength
 * @\App\Validator\Constraints\CampaignInactive
 */
#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ApiResource(
    order: ['created' => 'DESC'],
    collectionOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['post:read']],
        ],
        'post' => [
            'security_post_denormalize' => "is_granted('ROLE_ADMIN') or object.author == user",
            'denormalization_context' => ['groups' => ['post:write']],
            'normalization_context' => ['groups' => ['post:read']],
        ],
        'post_comment' => [
            'method' => 'POST',
            'path' => '/posts/{id}/comments',
            'requirements' => ['id' => "\d+", 'comment' => 'array'],
            'controller' => SetCommentController::class,
            'normalization_context' => ['groups' => 'post:comment:write'],
        ],
    ],
    subresourceOperations: [
        'api_posts_comments_get_subresource' => [
            'method' => 'GET',
            'normalization_context' => [
                'groups' => ['post:read'],
            ],
            'maxDepth' => 2,
            'order' => ['created' => 'ASC'],
        ],
        'api_users_bookmarks_get_subresource' => [
            'normalization_context' => [
                'groups' => ['post:read'],
            ],
        ],
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['post:read']],
        ],
        'add_post_to_playlist' => [
            'method' => 'GET',
            'path' => '/playlists/{playlistId}/add-post/{postId}',
            'read' => false,
            'controller' => AddPostToPlaylistController::class,
        ],
        'bookmark' => [
            'method' => 'GET',
            'path' => '/posts/{id}/bookmark',
            'requirements' => ['id' => "\d+"],
            'controller' => SetBookmarkController::class,
            'normalization_context' => ['groups' => 'post:bookmarks:write'],
        ],
        'patch' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN') or (object.author == user and previous_object.author == user)"],
        'delete' => [
            'security_post_denormalize' => "is_granted('ROLE_ADMIN') or (object.author == user and previous_object.author == user)",
            'normalization_context' => ['groups' => ['post:delete']],
        ],
    ]
)]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'campaign' => 'exact',
        'type' => 'exact',
        'reported' => 'exact',
        'author' => 'exact',
        'leadingFeedbackOption' => 'exact',
    ]
)]
#[ApiFilter(
    OrderFilter::class,
    properties: ['created', 'votestotal', 'votesSpread', 'leadingFeedbackCount', 'commentCount'],
    arguments: ['orderParameterName' => 'order']
)]
#[ORM\HasLifecycleCallbacks]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['post:read', 'playlist:read'])]
    private $id;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['post:write', 'post:read', 'post:comment:write'])]
    private $created;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['post:write', 'post:read', 'post:comment:write'])]
    private $updatedAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['post:write', 'post:read', 'post:comment:write', 'playlist:read'])]
    public $author;

    #[ORM\Column(type: 'posttype')]
    #[Groups(['post:write', 'post:read', 'playlist:read'])]
    public PostType $type = PostType::TEXT;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['post:write', 'post:read', 'playlist:read'])]
    private $title;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['post:write', 'post:read', 'post:comment:write', 'playlist:read'])]
    private $body;

    #[ORM\Column(type: 'integer')]
    #[Groups(['post:write', 'post:read', 'post:comment:write', 'playlist:read'])]
    private $upvotes = 0;

    #[ORM\Column(type: 'integer')]
    #[Groups(['post:write', 'post:read', 'post:comment:write', 'playlist:read'])]
    private $downvotes = 0;

    #[ORM\Column(type: 'integer')]
    #[Groups(['post:write', 'post:read', 'post:comment:write', 'vote:write', 'vote:read', 'playlist:read'])]
    private $votestotal = 0;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: PollOption::class, cascade: ['persist', 'remove'])]
    #[Groups(['post:write', 'post:read', 'playlist:read'])]
    #[Assert\Valid]
    private $pollOptions;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'comments', )]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['post:comment:write', 'post:delete'])]
    #[Ignore]
    private $parent;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: Post::class, cascade: ['persist', 'remove'])]
    #[ApiSubresource]
    #[ORM\OrderBy(['created' => 'ASC'])]
    private $comments;

    #[ORM\ManyToOne(targetEntity: Campaign::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['post:write', 'post:read', 'post:comment:write', 'playlist:read'])]
    private $campaign;

    #[ORM\ManyToMany(targetEntity: Playlist::class, cascade: ['persist', 'remove'])]
    #[Groups(['post:write', 'post:read'])]
    private $playlist;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['post:write', 'post:read', 'playlist:read'])]
    #[Assert\Length(max: 100)]
    private $question;

    #[ORM\ManyToMany(targetEntity: MediaObject::class)]
    #[Groups(['post:write', 'post:read', 'playlist:read'])]
    private $files;

    #[ORM\Column(type: 'integer')]
    #[Groups(['post:read', 'playlist:read'])]
    private $commentCount = 0;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['post:write', 'post:read'])]
    private $blocked = false;

    #[Groups(['post:read'])]
    private $bookmarked = false;

    #[Groups(['post:read'])]
    private $rated = false;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['post:write', 'post:read'])]
    private $reported = false;

    #[ORM\ManyToOne(targetEntity: Playlist::class)]
    #[Groups(['post:write', 'post:read'])]
    private $linkedPlaylist;

    #[Groups(['post:read'])]
    private $myVote = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['post:read'])]
    private $votesSpread = 0;

    #[ORM\ManyToOne(targetEntity: CampaignFeedbackOption::class)]
    private $leadingFeedbackOption;

    #[ORM\Column(type: 'integer')]
    private $leadingFeedbackCount = 0;

    #[Groups(['post:read'])]
    private $choicesTotal = 0;

    #[Groups(['post:read'])]
    private $userChoiced = false;

    public function __construct()
    {
        $this->pollOptions = new ArrayCollection();
        $this->awards = new ArrayCollection();
        $this->partners = new ArrayCollection();
        $this->playlist = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    #[ORM\PrePersist]
    public function setCreated(): self
    {
        $this->created = new \DateTimeImmutable();

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getPostType(): PostType
    {
        return $this->type;
    }

    #[SerializedName('postType')]
    #[Groups(['post:write'])]
    public function setPostType(PostType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getUpvotes(): ?int
    {
        return $this->upvotes;
    }

    public function setUpvotes(int $upvotes): self
    {
        $this->upvotes = $upvotes;

        return $this;
    }

    public function getDownvotes(): ?int
    {
        return $this->downvotes;
    }

    public function setDownvotes(int $downvotes): self
    {
        $this->downvotes = $downvotes;

        return $this;
    }

    public function getVotestotal(): ?int
    {
        return $this->votestotal;
    }

    public function setVotestotal(int $votestotal): self
    {
        $this->votestotal = $votestotal;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created = new \DateTime('now');
        $this->updatedAt = $this->created;
        $this->upvotes = 0;
        $this->downvotes = 0;
        $this->votestotal = 0;
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTime('now');
    }

    /**
     * @return Collection<int, PollOption>
     */
    public function getPollOptions(): Collection
    {
        return $this->pollOptions;
    }

    public function addPollOption(PollOption $pollOption): self
    {
        if (!$this->pollOptions->contains($pollOption)) {
            $this->pollOptions[] = $pollOption;
            $pollOption->setPost($this);
        }

        return $this;
    }

    public function removePollOption(PollOption $pollOption): self
    {
        if ($this->pollOptions->removeElement($pollOption)) {
            // set the owning side to null (unless already changed)
            if ($pollOption->getPost() === $this) {
                $pollOption->setPost(null);
            }
        }

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(self $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setParent($this);
        }

        return $this;
    }

    public function removeComment(self $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getParent() === $this) {
                $comment->setParent(null);
            }
        }

        return $this;
    }

    public function getCampaign(): ?Campaign
    {
        return $this->campaign;
    }

    public function setCampaign(?Campaign $campaign): self
    {
        $this->campaign = $campaign;

        return $this;
    }

    /**
     * @return Collection<int, Playlist>
     */
    public function getPlaylist(): Collection
    {
        return $this->playlist;
    }

    public function addPlaylist(Playlist $playlist): self
    {
        if (!$this->playlist->contains($playlist)) {
            $this->playlist[] = $playlist;
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): self
    {
        $this->playlist->removeElement($playlist);

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(?string $question): self
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return Collection<int, MediaObject>
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(MediaObject $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
        }

        return $this;
    }

    public function removeFile(MediaObject $file): self
    {
        $this->files->removeElement($file);

        return $this;
    }

    public function getCommentCount(): ?int
    {
        return $this->commentCount;
    }

    public function setCommentCount(int $commentCount): self
    {
        $this->commentCount = $commentCount;

        return $this;
    }

    public function increaseCommentCount(): self
    {
        ++$this->commentCount;

        return $this;
    }

    public function getBlocked(): ?bool
    {
        return $this->blocked;
    }

    public function setBlocked(bool $blocked): self
    {
        $this->blocked = $blocked;

        return $this;
    }

    public function getBookmarked(): ?bool
    {
        return $this->bookmarked;
    }

    public function setBookmarked(bool $bookmarked): self
    {
        $this->bookmarked = $bookmarked;

        return $this;
    }

    public function getRated(): ?bool
    {
        return $this->rated;
    }

    public function setRated(bool $rated): self
    {
        $this->rated = $rated;

        return $this;
    }

    public function getReported(): ?bool
    {
        return $this->reported;
    }

    public function setReported(bool $reported): self
    {
        $this->reported = $reported;

        return $this;
    }

    public function getLinkedPlaylist(): ?Playlist
    {
        return $this->linkedPlaylist;
    }

    public function setLinkedPlaylist(?Playlist $linkedPlaylist): self
    {
        $this->linkedPlaylist = $linkedPlaylist;

        return $this;
    }

    /**
     * @return ?Vote
     */
    public function getMyVote(): ?Vote
    {
        return $this->myVote;
    }

    /**
     * @return Post
     */
    public function setMyVote(Vote $myVote): self
    {
        $this->myVote = $myVote;

        return $this;
    }

    public function getVotesSpread(): ?int
    {
        return $this->votesSpread;
    }

    public function setVotesSpread(?int $votesSpread): self
    {
        $this->votesSpread = $votesSpread;

        return $this;
    }

    public function getLeadingFeedbackOption(): ?CampaignFeedbackOption
    {
        return $this->leadingFeedbackOption;
    }

    public function setLeadingFeedbackOption(?CampaignFeedbackOption $leadingFeedbackOption): self
    {
        $this->leadingFeedbackOption = $leadingFeedbackOption;

        return $this;
    }

    public function getLeadingFeedbackCount(): int
    {
        return $this->leadingFeedbackCount;
    }

    public function setLeadingFeedbackCount(int $leadingFeedbackCount): self
    {
        $this->leadingFeedbackCount = $leadingFeedbackCount;

        return $this;
    }

    public function getChoicesTotal(): int
    {
        return $this->choicesTotal;
    }

    public function setChoicesTotal(int $choicesTotal): self
    {
        $this->choicesTotal = $choicesTotal;

        return $this;
    }

    public function getUserChoiced(): bool
    {
        return $this->userChoiced;
    }

    public function setUserChoiced(bool $userChoiced): self
    {
        $this->userChoiced = $userChoiced;

        return $this;
    }
}
