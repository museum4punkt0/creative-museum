<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\User;
use App\Repository\CampaignRepository;
use App\Service\AwardService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

class GetAvailableAwardsController extends AbstractController
{
    public function __construct
    (
        private readonly AwardService $awardService,
        private readonly CampaignRepository $campaignRepository,
        private readonly Security $security
    )
    {
    }

    public function __invoke(int $campaign)
    {
        $campaign = $this->campaignRepository->find($campaign);

        $user = $this->security->getUser();

        if (!$user instanceof User || !$campaign instanceof Campaign) {
            return null;
        }

        $unawarded = $this->awardService->getUnawarded($campaign,$user);

        return $unawarded;
    }
}