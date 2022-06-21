<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\GetCommentsController;
use App\Controller\SetCommentController;
use App\Enum\PostType;
use App\Repository\PostRepository;
use App\Validator\Constraints\PollType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Validator\Constraints\PlaylistType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Secured resource.
 * @PlaylistType
 * @PollType
 */
#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get" => [
            "normalization_context" => ["groups" => ["read:post"]]
        ],
        "post" => [
            "security_post_denormalize" => "is_granted('ROLE_ADMIN') or object.author == user",
            "denormalization_context" => ["groups" => ["write:post"]],
            "normalization_context" => ["groups" => ["read:post"]]
            ],
        "get_comments" => [
            "method" => "GET",
            "path" => "/posts/{id}/comments",
            "requirements" => ["id" => "\d+"],
            "controller" => GetCommentsController::class
        ],
        "post_comment" => [
            "method" => "POST",
            "path" => "/posts/{id}/comment",
            "requirements" => ["id" => "\d+", "comment" => "array"],
            "controller" => SetCommentController::class,
            'normalization_context' => ['groups' => 'write:comment'],
        ],
    ],
    itemOperations: [
        "get" => [
            "normalization_context" => ["groups" => ["read:post"]]
        ],
        "patch" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN') or (object.author == user and previous_object.author == user)"],
        "delete" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN') or (object.author == user and previous_object.author == user)"],
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['campaign' => 'exact'])]
#[ORM\HasLifecycleCallbacks]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $created;

    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["write:post", "read:post", "write:comment"])]
    public $author;

    #[ORM\Column(type: 'posttype')]
    #[Groups(["write:post", "read:post"])]
    private PostType $type = PostType::TEXT;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(["write:post", "read:post","write:comment"])]
    private $body;

    #[ORM\Column(type: 'integer')]
    #[Groups(["write:post", "read:post","write:comment"])]
    private $upvotes = 0;

    #[ORM\Column(type: 'integer')]
    #[Groups(["write:post", "read:post","write:comment"])]
    private $downvotes = 0;

    #[ORM\Column(type: 'integer')]
    #[Groups(["write:post", "read:post","write:comment"])]
    private $votestotal = 0;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: PollOption::class, cascade: ["persist"])]
    #[Groups(["write:post", "read:post"])]
    #[Assert\Valid]
    private $pollOptions;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(["write:comment"])]
    private $parent;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private $comments;

    #[ORM\ManyToOne(targetEntity: Campaign::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["write:post", "read:post", "write:comment"])]
    private $campaign;

    #[ORM\ManyToMany(targetEntity: Playlist::class)]
    #[Groups(["write:post", "read:post"])]
    private $playlist;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["write:post", "read:post"])]
    private $question;

    #[ORM\ManyToMany(targetEntity: MediaObject::class)]
    #[Groups(["write:post", "read:post"])]
    private $files;

    #[ORM\Column(type: 'integer')]
    #[Groups(["read:post"])]
    private $commentCount = 0;

    #[ORM\Column(type: 'boolean')]
    #[Groups(["write:post", "read:post"])]
    private $blocked = false;

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

    /**
     * @return PostType
     */
    public function getPostType(): PostType
    {
        return $this->type;
    }

    /**
     * @param PostType $type
     */
    #[SerializedName('postType')]
    #[Groups(["write:post"])]
    public function setPostType(PostType $type): self
    {
        $this->type = $type;

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
        $this->created = new \DateTime("now");
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
        $this->updatedAt = new \DateTime("now");
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
        $this->commentCount++;
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
}
