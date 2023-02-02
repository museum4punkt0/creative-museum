<?php

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class PlaylistPost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['user:me:read', 'post:read', 'playlist:read', 'users:read'])]
    private $id;

    #[ORM\ManyToOne(targetEntity: Playlist::class, inversedBy: "posts")]
    #[ORM\JoinColumn(name: "playlist_id", referencedColumnName: "id")]
    private $playlist;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy:"playlist")]
    #[ORM\JoinColumn(name: "post_id", referencedColumnName: "id")]
    private $post;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;
        return $this;
    }
}