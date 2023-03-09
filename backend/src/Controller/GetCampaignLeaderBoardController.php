<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Campaign;
use App\Repository\CampaignMemberRepository;
use App\Repository\CampaignRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetCampaignLeaderBoardController extends AbstractController
{
    private CampaignMemberRepository $campaignMemberRepository;

    private CampaignRepository $campaignRepository;

    public function __construct(
        CampaignMemberRepository $campaignMemberRepository,
        CampaignRepository $campaignRepository
    ) {
        $this->campaignMemberRepository = $campaignMemberRepository;
        $this->campaignRepository = $campaignRepository;
    }

    public function __invoke(int $campaignId)
    {
        $campaign = $this->campaignRepository->find($campaignId);
        $campaignLeaders = [];

        if ($campaign instanceof Campaign && $campaign->getClosed()) {
            $campaignLeaders = $this->campaignMemberRepository->getCampaignLeaders($campaignId);
        }

        return $campaignLeaders;
    }
}
