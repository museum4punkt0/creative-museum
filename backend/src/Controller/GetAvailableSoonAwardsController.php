<?php

namespace App\Controller;

use App\Entity\Campaign;
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
    ){}

    public function __invoke(int $campaign)
    {
        $campaign = $this->campaignRepository->find($campaign);

        $user = $this->security->getUser();

        if (!$user instanceof User || !$campaign instanceof Campaign) {
            return null;
        }

        return $this->awardService->getAvailableSoonByCampaign($campaign,$user);
    }
}