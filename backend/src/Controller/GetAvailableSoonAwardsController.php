<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\CampaignRepository;
use App\Service\AwardService;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class GetAvailableSoonAwardsController extends AbstractController
{
    public function __construct
    (
        private readonly AwardService $awardService,
        private readonly CampaignRepository $campaignRepository,
        private readonly Security $security
    )
    {
    }

    public function __invoke(int $campaign = 0)
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            return null;
        }

        if ($campaign === 0) {
            return $this->awardService->getAllAvailableSoon($user);
        }

        $campaign = $this->campaignRepository->find($campaign);

        if ($campaign->getClosed() || !$campaign->getActive()){
            return null;
        }

        return $this->awardService->getAvailableSoonByCampaign($campaign, $user);
    }
}