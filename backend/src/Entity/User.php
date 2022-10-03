<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\AnonymizeUserController;
use App\Controller\MeController;
use App\Enum\NotificationType;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Metaclass\FilterBundle\Filter\FilterLogic;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    collectionOperations: [
        'me' => [
            'method' => 'GET',
            'path' => '/users/me',
            'normalization_context' => ['groups' => ['user:me:read']],
            'controller' => MeController::class,
            'output_formats' => [
                'json' => 'application/json',
            ],
        ],
        'get' => [
            'normalization_context' => ['groups' => ['users:read']],
        ],
        'anonymize' => [
            'method' => 'PATCH',
            'path' => '/users/me/anonymize',
            'controller' => AnonymizeUserController::class,
        ],
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['users:read']],
        ],
        'patch' => [
            'security_post_denormalize' => "is_granted('ROLE_ADMIN') or object == user",
            'denormalization_context' => ['groups' => ['write:me']],
            'normalization_context' => ['groups' => ['user:me:read']],
        ],
        'delete' => [
            'security_post_denormalize' => "is_granted('ROLE_ADMIN') or (object == user and previous_object == user)",
        ],
    ],
)]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'username' => 'partial',
        'fullName' => 'partial',
        'email' => 'partial',
        'deleted' => 'exact'
    ]
)]
#[ApiFilter(FilterLogic::class)]
#[UniqueEntity('username')]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['user:me:read', 'users:read'])]
    #[ApiProperty(identifier: false)]
    private int $id;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'uuid', nullable: true)]
    #[Groups(['user:me:read', 'post:read', 'users:read', 'playlist:read'])]
    #[ApiProperty(identifier: true)]
    private string $uuid;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Post::class, cascade: ['remove'], orphanRemoval: true)]
    private Collection $posts;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Playlist::class,cascade: ['remove'] ,orphanRemoval: true)]
    #[Groups(['user:me:read', 'users:read'])]
    private Collection $playlists;

    #[ORM\Column(type: 'notficationtype')]
    #[Groups(['user:me:read', 'write:me'])]
    private NotificationType $notificationSettings = NotificationType::ALL;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['user:me:read', 'write:me'])]
    private bool $tutorial = false;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['users:me:read', 'users:read', 'write:me'])]
    private bool $active = true;

    #[ORM\Column(type: 'integer')]
    #[Groups(['user:me:read'])]
    private int $score = 0;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CampaignMember::class,cascade: ['remove'], orphanRemoval: true)]
    #[Groups(['user:me:read', 'users:read'])]
    private Collection $memberships;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Badged::class, cascade: ['remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['id' => 'DESC'])]
    #[Groups(['user:me:read', 'users:read'])]
    private Collection $achievements;

    #[ORM\ManyToMany(targetEntity: Post::class,cascade: ['remove'])]
    #[ORM\JoinTable(name: 'user_bookmark')]
    #[ApiSubresource(maxDepth: 1)]
    private Collection $bookmarks;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:me:read', 'write:me', 'users:read', 'playlist:read'])]
    private string $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:me:read', 'write:me', 'users:read', 'playlist:read'])]
    private string $lastName;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['user:me:read', 'write:me', 'post:read', 'users:read', 'playlist:read', 'notifications:read', 'campaign:result:get'])]
    private ?string $username;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:me:read', 'users:read'])]
    private string $email;

    #[ORM\OneToOne(targetEntity: MediaObject::class, cascade: ['persist', 'remove'])]
    #[Groups(['post:read', 'write:me', 'user:me:read', 'users:read', 'playlist:read'])]
    private $profilePicture;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(max: 100)]
    #[Groups(['user:me:read', 'write:me', 'playlist:read', 'users:read'])]
    private $description;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['write:me', 'user:me:read', 'users:read'])]
    private $lastLogin;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:me:read', 'write:me', 'users:read'])]
    private $fullName;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['user:me:read', 'write:me', 'users:read', 'post:read', 'notifications:read','campaign:result:get'])]
    private $deleted = 0;

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
        return (string)$this->uuid;
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

    public function getUuid(): string
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

    public function getNotificationSettings(): NotificationType
    {
        return $this->notificationSettings;
    }

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
     * @return Collection<int, Badged>
     */
    public function getAchievements(): Collection
    {
        return $this->achievements;
    }

    public function addAchievement(Badged $achievement): self
    {
        if (!$this->achievements->contains($achievement)) {
            $this->achievements[] = $achievement;
            $achievement->setUser($this);
        }

        return $this;
    }

    public function removeAchievement(Badged $achievement): self
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getProfilePicture(): ?MediaObject
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?MediaObject $profilePicture): self
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(\DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setFullName(): self
    {
        $this->fullName = $this->getFirstName() . ' ' . $this->getLastName();

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }
}
