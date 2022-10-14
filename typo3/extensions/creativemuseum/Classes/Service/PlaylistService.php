<?php
declare(strict_types=1);

namespace JWIED\Creativemuseum\Service;

use JWIED\Creativemuseum\Domain\Model\Dto\AwardDto;

class PlaylistService extends CmApiService
{
    const ENDPOINT = 'v1/playlists';

    public function getPlaylist(int $playlistId): ?array
    {
        return $this->getSingle($playlistId);
    }

    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }
}