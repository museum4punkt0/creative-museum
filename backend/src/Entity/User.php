<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Enum\NotificationType;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get"
    ],
    itemOperations: [
        "get",
        "me" => [
            "method" => "GET",
            "path" => "/users/me",
            "defaults" => [
                "id" => 0,
            ],
        ],
        "patch" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN') or (object.author == user and previous_object.author == user)"],
        "delete" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN') or (object.author == user and previous_object.author == user)"]
    ],
)]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'uuid', nullable: true)]
    private $uuid;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Post::class, orphanRemoval: true)]
    private $posts;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Playlist::class, orphanRemoval: true)]
    private $playlists;

    #[ORM\Column(type: 'notficationtype')]
    private NotificationType $notificationSettings = NotificationType::ALL;

    #[ORM\Column(type: 'boolean')]
    private $tutorial = false;

    #[ORM\Column(type: 'boolean')]
    private $active = true;

    #[ORM\Column(type: 'integer')]
    private $score = 0;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CampaignMember::class, orphanRemoval: true)]
    private $memberships;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Badge::class, orphanRemoval: true)]
    private $achievements;

    #[ORM\ManyToMany(targetEntity: Post::class)]
    private $bookmarks;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->playlists = new ArrayCollection();
        $this->memberships = new ArrayCollection();
        $this->achievements = new ArrayCollection();
        $this->bookmarks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->uuid;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function setUuid($uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Playlist>
     */
    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }

    public function addPlaylist(Playlist $playlist): self
    {
        if (!$this->playlists->contains($playlist)) {
            $this->playlists[] = $playlist;
            $playlist->setCreator($this);
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): self
    {
        if ($this->playlists->removeElement($playlist)) {
            // set the owning side to null (unless already changed)
            if ($playlist->getCreator() === $this) {
                $playlist->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return NotificationType
     */
    public function getNotificationSettings(): NotificationType
    {
        return $this->notificationSettings;
    }

    /**
     * @param NotificationType $notificationSettings
     */
    public function setNotificationSettings(NotificationType $notificationSettings): self
    {
        $this->notificationSettings = $notificationSettings;

        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getTutorial(): ?bool
    {
        return $this->tutorial;
    }

    public function setTutorial(bool $tutorial): self
    {
        $this->tutorial = $tutorial;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    /**
     * @return Collection<int, CampaignMember>
     */
    public function getMemberships(): Collection
    {
        return $this->memberships;
    }

    public function addMembership(CampaignMember $membership): self
    {
        if (!$this->memberships->contains($membership)) {
            $this->memberships[] = $membership;
            $membership->setUser($this);
        }

        return $this;
    }

    public function removeMembership(CampaignMember $membership): self
    {
        if ($this->memberships->removeElement($membership)) {
            // set the owning side to null (unless already changed)
            if ($membership->getUser() === $this) {
                $membership->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Badge>
     */
    public function getAchievements(): Collection
    {
        return $this->achievements;
    }

    public function addAchievement(Badge $achievement): self
    {
        if (!$this->achievements->contains($achievement)) {
            $this->achievements[] = $achievement;
            $achievement->setUser($this);
        }

        return $this;
    }

    public function removeAchievement(Badge $achievement): self
    {
        if ($this->achievements->removeElement($achievement)) {
            // set the owning side to null (unless already changed)
            if ($achievement->getUser() === $this) {
                $achievement->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getBookmarks(): Collection
    {
        return $this->bookmarks;
    }

    public function addBookmark(Post $bookmark): self
    {
        if (!$this->bookmarks->contains($bookmark)) {
            $this->bookmarks[] = $bookmark;
        }

        return $this;
    }

    public function removeBookmark(Post $bookmark): self
    {
        $this->bookmarks->removeElement($bookmark);

        return $this;
    }
}
