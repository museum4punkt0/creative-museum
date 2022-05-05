<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Enum\PostType;
use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
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
    private $author;

    #[ORM\Column(type: 'posttype')]
    private PostType $type;

    #[ORM\Column(type: 'text', nullable: true)]
    private $body;

    #[ORM\Column(type: 'integer')]
    private $upvotes;

    #[ORM\Column(type: 'integer')]
    private $downvotes;

    #[ORM\Column(type: 'integer')]
    private $votestotal;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: PollOption::class, orphanRemoval: true)]
    private $pollOptions;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Award::class, orphanRemoval: true)]
    private $awards;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Partner::class, orphanRemoval: true)]
    private $partners;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'bookmarks')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: self::class)]
    private $parent;

    #[ORM\ManyToOne(targetEntity: Campaign::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $campaign;

    #[ORM\ManyToOne(targetEntity: Playlist::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $playlist;

    public function __construct()
    {
        $this->pollOptions = new ArrayCollection();
        $this->awards = new ArrayCollection();
        $this->partners = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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

    /**
     * @return Collection<int, Award>
     */
    public function getAwards(): Collection
    {
        return $this->awards;
    }

    public function addAward(Award $award): self
    {
        if (!$this->awards->contains($award)) {
            $this->awards[] = $award;
            $award->setPost($this);
        }

        return $this;
    }

    public function removeAward(Award $award): self
    {
        if ($this->awards->removeElement($award)) {
            // set the owning side to null (unless already changed)
            if ($award->getPost() === $this) {
                $award->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Partner>
     */
    public function getPartners(): Collection
    {
        return $this->partners;
    }

    public function addPartner(Partner $partner): self
    {
        if (!$this->partners->contains($partner)) {
            $this->partners[] = $partner;
            $partner->setPost($this);
        }

        return $this;
    }

    public function removePartner(Partner $partner): self
    {
        if ($this->partners->removeElement($partner)) {
            // set the owning side to null (unless already changed)
            if ($partner->getPost() === $this) {
                $partner->setPost(null);
            }
        }

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

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

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

    public function getPlaylist(): ?Playlist
    {
        return $this->playlist;
    }

    public function setPlaylist(?Playlist $playlist): self
    {
        $this->playlist = $playlist;

        return $this;
    }
}
