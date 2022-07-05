<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Votes;
use App\Event\NewPostVoteEvent;
use App\Repository\PostRepository;
use App\Repository\VotesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

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

    /**
     * @var Security
     */
    private Security $security;

    /**
     * @var PostRepository
     */
    private PostRepository $postRepository;

    public function __construct
    (
        VotesRepository          $votesRepository,
        EntityManagerInterface   $entityManager,
        EventDispatcherInterface $eventDispatcher,
        Security $security,
        PostRepository $postRepository
    )
    {
        $this->votesRepository = $votesRepository;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->security = $security;
        $this->postRepository = $postRepository;
    }

    public function __invoke(Votes $data): Votes|array
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            return [];
        }

        $dbVote = $this->votesRepository->findOneBy([
            'voter' => $user->getId(),
            'post' => $data->getPost()->getId()
        ]);
        $switched = false;
        $data->setVoter($user);

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

        $result = [
           [
               'vote' => $dbVote,
               'votesTotal' => $this->postRepository->find($data->getPost())->getVotestotal()
           ]
        ];

        return $result;
    }
}