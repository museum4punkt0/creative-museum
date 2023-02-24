<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\CreateMediaObjectAction;
use App\Enum\FileType;
use App\Repository\MediaObjectRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable
 */
#[ORM\Entity(repositoryClass: MediaObjectRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get',
        'post' => [
            'controller' => CreateMediaObjectAction::class,
            'deserialize' => false,
            'validation_groups' => ['media_object_create'],
            'openapi_context' => [
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary',
                                    ],
                                    'description' => [
                                        'type' => 'string',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    iri: 'http://schema.org/MediaObject',
    itemOperations: ['get'],
    normalizationContext: ['groups' => ['media_object:read']]
)]
#[ORM\HasLifecycleCallbacks]
class MediaObject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['media_object:read', 'post:read', 'campaigns:read', 'campaign:read', 'awards:read'. 'users:read', 'awarded:read', 'badge:read', 'playlist:read','badged:read'])]
    private ?int $id = null;

    #[ApiProperty(iri: 'http://schema.org/contentUrl')]
    #[Groups(['media_object:read', 'post:read', 'user:me:read', 'campaigns:read', 'campaign:read', 'awards:read', 'users:read', 'awarded:read', 'badge:read', 'playlist:read', 'notifications:read','badged:read'])]
    public ?string $contentUrl = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    public ?string $filepath = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['media_object:read', 'post:read', 'campaigns:read', 'campaign:read', 'awards:read', 'awarded:read', 'badge:read', 'playlist:read','badged:read'])]
    public string $description;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['media_object:read'])]
    public $updatedAt;

    /**
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="filepath")
     */
    #[Assert\NotNull(groups: ['media_object_create'])]
    #[Assert\File(groups: ['media_object_create'])]
    public ?File $file = null;

    #[ORM\Column(type: 'filetype')]
    #[Assert\NotNull(groups: ['media_object_create'])]
    #[Groups(['media_object:read', 'post:read', 'playlist:read'])]
    private FileType $type = FileType::IMAGE;

    #[ORM\OneToOne(targetEntity: MediaObject::class, cascade: ['persist', 'remove'])]
    #[Groups(['media_object:read','media_object_create', 'post:read', 'campaigns:read', 'campaign:read', 'awards:read'. 'users:read', 'awarded:read', 'badge:read', 'playlist:read','badged:read'])]
    public ?MediaObject $thumbnail = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContentUrl(): ?string
    {
        return $this->contentUrl;
    }

    public function setContentUrl(string $contentUrl): MediaObject
    {
        $this->contentUrl = $contentUrl;

        return $this;
    }

    public function getFilepath(): ?string
    {
        return $this->filepath;
    }

    public function setFilepath(?string $filepath): MediaObject
    {
        $this->filepath = $filepath;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): MediaObject
    {
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): MediaObject
    {
        $this->file = $file;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): MediaObject
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): FileType
    {
        return $this->type;
    }

    public function setType(FileType $type): MediaObject
    {
        $this->type = $type;

        return $this;
    }

    public function getThumbnail(): ?MediaObject
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?MediaObject $thumbnail): MediaObject
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }
}
