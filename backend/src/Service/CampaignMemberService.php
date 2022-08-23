<?php

namespace App\Service;

use App\Entity\Campaign;
use App\Entity\CampaignMember;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\CampaignMemberRepository;

class CampaignMemberService
{
    /**
     * @var CampaignMemberRepository
     */
    private CampaignMemberRepository $campaignMemberRepository;

    public function __construct(CampaignMemberRepository $campaignMemberRepository)
    {
        $this->campaignMemberRepository = $campaignMemberRepository;
    }

    /**
     * @param Post $post
     * @return bool
     */
    public function isCampaignMember(Campaign $campaign, User $user): bool
    {
        $result = $this->campaignMemberRepository->findBy([
            'campaign' => $campaign->getId(),
            'user' => $user->getId()
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