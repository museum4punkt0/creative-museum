<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Service;

use App\Entity\Campaign;
use App\Entity\CampaignMember;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\CampaignMemberRepository;

class CampaignMemberService
{
    private CampaignMemberRepository $campaignMemberRepository;

    public function __construct(CampaignMemberRepository $campaignMemberRepository)
    {
        $this->campaignMemberRepository = $campaignMemberRepository;
    }

    /**
     * @param Post $post
     */
    public function isCampaignMember(Campaign $campaign, User $user): bool
    {
        $result = $this->campaignMemberRepository->findBy([
            'campaign' => $campaign->getId(),
            'user' => $user->getId(),
        ]);

        return !empty($result);
    }

    public function createCampaignMember(Campaign $campaign, User $user)
    {
        $campaignMember = new CampaignMember();
        $campaignMember
            ->setCampaign($campaign)
            ->setUser($user);

        $this->campaignMemberRepository->add($campaignMember);
    }
}
