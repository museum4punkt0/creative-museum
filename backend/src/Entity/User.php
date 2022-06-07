<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Enum\NotificationType;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\MarkTutorialSeenController;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    collectionOperations: [
        "me" => [
            "method" => "GET",
            "path" => "/users/me",
            "normalization_context" => ["groups" => ["read:me"]]
        ],
        "get",
    ],
    itemOperations: [
        "get" => ["security" => "is_granted('ROLE_ADMIN') or object == user"],
        "patch" => [
            "security_post_denormalize" => "is_granted('ROLE_ADMIN') or object == user",
            "denormalization_context" => ["groups" => ["write:me"]]
        ],
        "delete" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN') or (object == user and previous_object == user)"]
    ],
)]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["read:me"])]
    #[ApiProperty(identifier: false)]
    private $id;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'uuid', nullable: true)]
    #[Groups(["read:me"])]
    #[ApiProperty(identifier: true)]
    private $uuid;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Post::class, orphanRemoval: true)]
    private $posts;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Playlist::class, orphanRemoval: true)]
    private $playlists;

    #[ORM\Column(type: 'notficationtype')]
    private NotificationType $notificationSettings = NotificationType::ALL;

    #[ORM\Column(type: 'boolean')]
    #[Groups(["read:me", "write:me"])]
    private $tutorial = false;

    #[ORM\Column(type: 'boolean')]
    private $active = true;

    #[ORM\Column(type: 'integer')]
    #[Groups(["read:me"])]
    private $score = 0;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CampaignMember::class, orphanRemoval: true)]
    private $memberships;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Badge::class, orphanRemoval: true)]
    #[Groups(["read:me"])]
    private $achievements;

    #[ORM\ManyToMany(targetEntity: Post::class)]
    private $bookmarks;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read:me", "write:me"])]
    private $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read:me", "write:me"])]
    private $lastName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read:me", "write:me"])]
    private $username;

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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }
}
