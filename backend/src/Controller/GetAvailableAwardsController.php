<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\User;
use App\Repository\CampaignRepository;
use App\Service\AwardService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

class GetAvailableAwardsController extends AbstractController
{
    public function __construct(
        private readonly AwardService $awardService,
        private readonly CampaignRepository $campaignRepository,
        private readonly Security $security
    ) {
    }

    public function __invoke(int $campaign = 0)
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            return null;
        }

        if (0 === $campaign) {
            return $this->awardService->getAllAvailable($user);
        }

        $campaign = $this->campaignRepository->find($campaign);

        if ($campaign->getClosed() || !$campaign->getActive()) {
            return null;
        }

        return $this->awardService->getAvailableByCampaign($campaign, $user);
    }
}
