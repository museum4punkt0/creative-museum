<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PlaylistRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get',
        'post' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN') or object.creator == user"],
    ],
    itemOperations: [
        'get' => ['normalization_context' => ['groups' => ['playlist:read']]],
        'patch' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN') or (object.creator == user and previous_object.creator == user)"],
        'delete' => [
            'security_post_denormalize' => "is_granted('ROLE_ADMIN') or (object.creator == user and previous_object.creator == user)",
            'normalization_context' => ['groups' => ['playlist:delete']],
        ],
    ]
)]
class Playlist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['user:me:read', 'post:read', 'playlist:read', 'users:read'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:me:read', 'post:read', 'playlist:read', 'users:read'])]
    private $title;

    #[ORM\OneToMany(targetEntity: PlaylistPost::class, mappedBy: 'playlist',cascade: ["persist", "remove"])]
    #[Groups(['playlist:read'])]
    #[ORM\OrderBy(['id' => 'DESC'])]
    private $playlistPosts;

    #[Groups(['playlist:read'])]
    private $posts;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'playlists')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['playlist:read'])]
    public $creator;

    public function __construct()
    {
        $this->playlistPosts = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection|PlaylistPost[]
     */
    public function getPlaylistPosts(): Collection
    {
        return $this->playlistPosts;
    }

    /**
     * @param PlaylistPost $playlistPost
     * @return Playlist
     */
    public function addPlaylistPost(PlaylistPost $playlistPost): self
    {
        if (!$this->playlistPosts->contains($playlistPost)) {
            $this->playlistPosts[] = $playlistPost;
            $playlistPost->setPlaylist($this);
        }
        return $this;
    }

    /**
     * @param PlaylistPost $playlistPost
     * @return Playlist
     */
    public function removePlaylistPost(PlaylistPost $playlistPost): self
    {
        if ($this->playlistPosts->contains($playlistPost)) {
            $this->playlistPosts->removeElement($playlistPost);
            if ($playlistPost->getPlaylist() === $this) {
                $playlistPost->setPlaylist(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        $posts = new ArrayCollection();

        foreach ($this->playlistPosts as $playlistPost) {
            $posts->add($playlistPost->getPost());
        }

        return $posts;
    }

    public function addPost(Post $post): self
    {
        foreach ($this->playlistPosts as $playlistPost) {
            if ($playlistPost->getPost() === $post) {
                return $this;
            }
        }

        $playlistPost = new PlaylistPost();
        $playlistPost->setPlaylist($this);
        $playlistPost->setPost($post);
        $this->playlistPosts[] = $playlistPost;

        return $this;
    }


    public function removePost(Post $post): self
    {
        foreach ($this->playlistPosts as $key => $playlistPost) {
            if ($playlistPost->getPost() === $post) {
                $this->playlistPosts->remove($key);
                break;
            }
        }

        return $this;
    }


    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    #[Groups(['user:me:read'])]
    public function getPostCount(): int
    {
        return $this->getPosts()->count();
    }
}
