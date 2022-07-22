<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Votes;
use App\Enum\PointsReceivedType;
use App\Enum\VoteDirection;
use App\Event\CampaignPointsReceivedEvent;
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
        Security                 $security,
        PostRepository           $postRepository,
    )
    {
        $this->votesRepository = $votesRepository;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->security = $security;
        $this->postRepository = $postRepository;
    }

    public function __invoke(Votes $data): array
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            return [];
        }

        $dbVote = $this->votesRepository->findOneBy([
            'voter' => $user->getId(),
            'post' => $data->getPost()->getId()
        ]);
        $data->setVoter($user);
        $oldDirection = null;

        if (!$dbVote) {
            $this->votesRepository->add($data);
            $dbVote = $data;
            $campaignPointsEvent = new CampaignPointsReceivedEvent(
                $data->getPost()->getCampaign()->getId(),
                $data->getVoter()->getId(),
                PointsReceivedType::UPVOTE->value
            );
            $this->eventDispatcher->dispatch($campaignPointsEvent, CampaignPointsReceivedEvent::NAME);
        }elseif ($dbVote->getDirection()->value === $data->getDirection()->value) {
            $oldDirection = $dbVote->getDirection()->value;
            $dbVote->setDirection(VoteDirection::NONE);
            $this->entityManager->persist($dbVote);
            $this->entityManager->flush();
        } else {
            $oldDirection = $dbVote->getDirection()->value;
            $dbVote->setDirection($data->getDirection());
            $this->entityManager->persist($dbVote);
            $this->entityManager->flush();
        }

        $voteEvent = new NewPostVoteEvent($data->getPost()->getId(), $dbVote->getDirection()->value, $oldDirection);
        $this->eventDispatcher->dispatch($voteEvent, NewPostVoteEvent::NAME);

        $result = [
            'vote' => $dbVote,
            'votestotal' => $this->postRepository->find($data->getPost())->getVotestotal()
        ];

        return $result;
    }
}