<?php

namespace App\Controller;

use App\Entity\Votes;
use App\Event\NewPostVoteEvent;
use App\Repository\VotesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostVoteController extends AbstractController
{
    /**
     * @var VotesRepository
     */
    private VotesRepository $votesRepository;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $eventDispatcher;

    public function __construct
    (
        VotesRepository          $votesRepository,
        EntityManagerInterface   $entityManager,
        EventDispatcherInterface $eventDispatcher,
    )
    {
        $this->votesRepository = $votesRepository;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(Votes $data): Votes|array
    {
        /**
         * @var Votes $dbVote
         */
        $dbVote = $this->votesRepository->findOneBy([
            'voter' => $data->getVoter()->getId(),
            'post' => $data->getPost()->getId()
        ]);
        $switched = false;

        if (!$dbVote) {
            $this->votesRepository->add($data);
            $dbVote = $data;
            $voteDifference = 1;
        } elseif ($dbVote->getDirection()->value === $data->getDirection()->value) {
            $this->entityManager->remove($dbVote);
            $this->entityManager->flush();
            $voteDifference = -1;
            $dbVote = [];
        } else {
            $dbVote->setDirection($data->getDirection());
            $this->entityManager->persist($dbVote);
            $this->entityManager->flush();
            $voteDifference = 1;
            $switched = true;
        }

        $voteEvent = new NewPostVoteEvent($data->getPost()->getId(), $data->getDirection()->value, $voteDifference, $switched);
        $this->eventDispatcher->dispatch($voteEvent, NewPostVoteEvent::NAME);

        return $dbVote;
    }
}