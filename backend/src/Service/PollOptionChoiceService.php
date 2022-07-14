<?php

namespace App\Service;

use App\Entity\PollOption;
use App\Entity\PollOptionChoice;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;

class PollOptionChoiceService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface   $em
    )
    {
        $this->em = $em;
    }

    public function getChoicedByPostAndUser(int $postId, int $userId)
    {
        $qb = $this->em->createQueryBuilder();
        $choiced = $qb->select('choice')
            ->from(PollOptionChoice::class,'choice')
            ->andWhere(
                $qb->expr()->in('option.id','choice.pollOption'),
                $qb->expr()->eq('choice.user', $userId)
            )
            ->leftJoin(
                PollOption::class,
                'option',
                Expr\Join::WITH,
                $qb->expr()->andX(
                    $qb->expr()->eq('option.post',$postId)
                )
            )
            ->getQuery()
            ->execute();

        return $choiced;
    }

}