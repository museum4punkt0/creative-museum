<?php
declare(strict_types=1);

namespace JWIED\Creativemuseum\Service;

use JWIED\Creativemuseum\Domain\Model\Dto\PostDto;
use JWIED\Creativemuseum\Domain\Model\Dto\PostListFilterDto;

class PostService extends CmApiService
{
    const ENDPOINT = 'v1/posts';

    const RECORDS_PER_PAGE = 30;

    public function getPosts(PostListFilterDto $filter): ?array
    {
        $qsTmpl = '?page=%d';

        $queryString = sprintf(
            $qsTmpl,
            $filter->getPage()
        );

        return $this->get($queryString);
    }

    public function getPost(string $id): ?array
    {
        return $this->getSingle($id);
    }

    public function updatePost(string $id, array $data): bool
    {
        return $this->patch(['id' => $id] + $data);
    }

    public function deletePost(string $id)
    {
        return $this->delete($id);
    }

    public function convert(array $postArray): PostDto
    {
        $post = new PostDto();
        $post
            ->setId((string) $postArray['id'] ?? '')
            ->setAuthor($postArray['author']['username'] ?? '')
            ->setType($this->convertPostType($postArray['type']))
            ->setCampaignTitle($postArray['campaign']['title'] ?? '')
            ->setUpvotes($postArray['upvotes'])
            ->setDownvotes($postArray['downvotes'])
            ->setCommentCount($postArray['commentCount'])
            ->setReported($postArray['reported'])
            ->setTitle($postArray['title'] ?? '')
            ->setBody($postArray['body'] ?? '')
            ->setCreated(new \DateTime($postArray['created'] ?? 'now'));

        return $post;
    }

    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    private function convertPostType(string $postType): string
    {
        return match ($postType) {
            'playlist' => 'Playlist',
            'image' => 'Bild',
            'audio' => 'Audio',
            'video' => 'Video',
            'poll' => 'Umfrage',
            default => 'Text',
        };
    }
}