<?php
declare(strict_types=1);

namespace JWIED\Creativemuseum\Service;

use JWIED\Creativemuseum\Domain\Model\Dto\MediaObjectDto;
use JWIED\Creativemuseum\Domain\Model\Dto\PollOptionDto;
use JWIED\Creativemuseum\Domain\Model\Dto\PostDto;
use JWIED\Creativemuseum\Domain\Model\Dto\PostListFilterDto;
use JWIED\Creativemuseum\Property\TypeConverter\PostDtoConverter;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;

class PostService extends CmApiService
{
    const ENDPOINT = 'v1/posts';

    const RECORDS_PER_PAGE = 30;

    private PlaylistService $playlistService;

    /**
     * @param ExtensionConfiguration $config
     * @param PlaylistService $playlistService
     */
    public function __construct(
        ExtensionConfiguration $config,
        PlaylistService $playlistService
    ) {
        parent::__construct($config);
        $this->playlistService = $playlistService;
    }

    public function getPosts(PostListFilterDto $filter): ?array
    {
        $qsTmpl = '?page=%d&exists[author]=true';

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

        if ($postArray['type'] === 'poll') {
            $postArray['title'] = $postArray['question'];
        }

        $post = new PostDto();
        $post
            ->setId((string) $postArray['id'] ?? '')
            ->setAuthor($postArray['author']['username'] ?? '')
            ->setAuthorId($postArray['author']['uuid'] ?? null)
            ->setType($this->convertPostType($postArray['type']))
            ->setCampaignTitle($postArray['campaign']['title'] ?? '')
            ->setUpvotes($postArray['upvotes'])
            ->setDownvotes($postArray['downvotes'])
            ->setCommentCount($postArray['commentCount'])
            ->setReported($postArray['reported'])
            ->setTitle($postArray['title'] ?? '')
            ->setBody($postArray['body'] ?? '')
            ->setCreated(new \DateTime($postArray['created'] ?? 'now'));

        if (in_array($postArray['type'], ['image', 'audio', 'video'])) {
            if (!empty($postArray['files'])) {
                $file = new MediaObjectDto();
                $file->setId($postArray['files'][0]['id']);
                $file->setContentUrl($postArray['files'][0]['contentUrl']);
                $file->setDescription($postArray['files'][0]['description'] ?? '');
                $file->setMediaType($postArray['files'][0]['type']);

                if ($file->getMediaType() === 'image') {
                    $post->setPictureObject($file);
                } else {
                    $post->setAudioVideoObject($file);
                }
                if (isset($postArray['files'][1])) {
                    $file = new MediaObjectDto();
                    $file->setId($postArray['files'][1]['id']);
                    $file->setContentUrl($postArray['files'][1]['contentUrl']);
                    $file->setDescription($postArray['files'][1]['description'] ?? '');
                    $file->setMediaType($postArray['files'][1]['type']);

                    if ($file->getMediaType() === 'image') {
                        $post->setPictureObject($file);
                    } else {
                        $post->setAudioVideoObject($file);
                    }
                }
            }
        }

        if ($postArray['type'] === 'playlist') {
            $post->setTitle($postArray['linkedPlaylist']['title']);
            $playlist = $this->playlistService->getSingle($postArray['linkedPlaylist']['id']);
            if (count($playlist['posts']) > 0) {
                foreach ($playlist['posts'] as $plPost) {
                    $post->addPlaylistPost($this->convert($plPost));
                }
            }
        }

        if ($postArray['type'] === 'poll' && count($postArray['pollOptions']) > 0) {
            foreach ($postArray['pollOptions'] as $pollOption) {
                $option = new PollOptionDto();
                $option->setId((string) $pollOption['id']);
                $option->setTitle($pollOption['title'] ?? '');
                $option->setVotes($pollOption['votes'] ?? 0);

                $post->addPollOption($option);
            }
        }

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