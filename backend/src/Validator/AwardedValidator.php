<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Validator;

use App\Entity\Award;
use App\Entity\Awarded;
use App\Entity\Campaign;
use App\Entity\User;
use App\Repository\AwardedRepository;
use App\Repository\CampaignMemberRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
final class AwardedValidator extends ConstraintValidator
{
    public function __construct(
        private readonly CampaignMemberRepository $campaignMemberRepository,
        private readonly AwardedRepository $awardedRepository,
        private readonly Security $security
    ) {}

    /**
     * @param Awarded $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof Awarded) {
            return;
        }

        /**
         * @var Awarded $value
         * @var Constraints\Awarded $constraint
         */

        $campaign = $value->getAward()->getCampaign();

        if ($value->getWinner() === $this->security->getUser()) {
            $this->context->buildViolation($constraint->canNotAwardSelf)->addViolation();
            return;
        }

        if (! $this->isCampaignMember($campaign, $value->getGiver())) {
            $this->context->buildViolation($constraint->giverNotCampaignMember)->addViolation();
            return;
        }

        if ($value->getAward()->getPrice() > $this->getCampaignScore($campaign, $value->getGiver())) {
            $this->context->buildViolation($constraint->notEnoughPoints)->addViolation();
            return;
        }

        if ($this->hasAlreadyAwarded($value->getAward(), $value->getGiver())) {
            $this->context->buildViolation($constraint->alreadyAwarded)->addViolation();
        }
    }

    private function isCampaignMember(Campaign $campaign, User $user): bool
    {
        $result = $this->campaignMemberRepository->findBy([
            'user' => $user->getId(),
            'campaign' => $campaign->getId(),
        ]);

        return !empty($result);
    }

    private function getCampaignScore(Campaign $campaign, User $user): int
    {
        $result = $this->campaignMemberRepository->findOneBy([
            'user' => $user->getId(),
            'campaign' => $campaign->getId(),
        ]);

        return $result->getScore();
    }

    private function hasAlreadyAwarded(Award $award, User $user): bool
    {
        $awarded = $this->awardedRepository->findOneBy([
            'giver' => $user->getId(),
            'award' => $award->getId(),
        ]);

        return $awarded !== null;
    }
}
