<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Serializer\Normalizer;

use App\Entity\Post;
use App\Entity\PostFeedback;
use App\Entity\User;
use App\Entity\Vote;
use App\Enum\PostType;
use App\Repository\PollOptionChoiceRepository;
use App\Repository\PollOptionRepository;
use App\Repository\PostFeedbackRepository;
use App\Repository\PostRepository;
use App\Repository\VoteRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PostNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private ObjectNormalizer $normalizer;

    private PostRepository $postRepository;

    private PostFeedbackRepository $feedbackRepository;

    private VoteRepository $votesRepository;

    private PollOptionChoiceRepository $pollOptionChoiceRepository;

    private PollOptionRepository $pollOptionRepository;

    private Security $security;

    public function __construct(
        ObjectNormalizer           $normalizer,
        PostRepository             $postRepository,
        PostFeedbackRepository     $feedbackRepository,
        VoteRepository             $votesRepository,
        PollOptionChoiceRepository $pollOptionChoiceRepository,
        PollOptionRepository       $pollOptionRepository,
        Security                   $security
    )
    {
        $this->normalizer = $normalizer;
        $this->postRepository = $postRepository;
        $this->security = $security;
        $this->feedbackRepository = $feedbackRepository;
        $this->pollOptionChoiceRepository = $pollOptionChoiceRepository;
        $this->pollOptionRepository = $pollOptionRepository;
        $this->votesRepository = $votesRepository;
    }

    public function normalize($object, $format = null, array $context = []): array
    {
        $postId = $object->getId();
        $data = $this->normalizer->normalize($object, $format, $context);
        $comments = $this->postRepository->getRecentPostComments($postId);

        if (!empty($comments)) {
            $data['comments'] = [];
            foreach ($comments as $comment) {
                array_unshift($data['comments'], $this->normalizer->normalize($comment, $format, $context));
            }
        }

        if ($this->security->getUser() !== null) {
            /** @var User $user */
            $user = $this->security->getUser();

            if ($user->getBookmarks()->contains($object)) {
                $data['bookmarked'] = true;
            }

            $myVote = $this->votesRepository->findOneBy(['voter' => $user, 'post' => $object]);
            if ($myVote instanceof Vote) {
                $data['my_vote'] = $this->normalizer->normalize($myVote, $format, $context);
            }

            /** @var PostFeedback $feedback */
            $feedback = $this->feedbackRepository->findOneBy(['user' => $user, 'post' => $object]);
            if (null !== $feedback) {
                $data['rated'] = true;
                $data['my_feedback'] = $this->normalizer->normalize($feedback->getSelection(), $format, $context);
            }
        }

        if (isset($data['type']) && $data['type'] === PostType::POLL->value) {
            $choicesTotal = 0;
            $pollOptions = $this->pollOptionRepository->findBy(
                [
                    'post' => $data['id']
                ]
            );

            foreach ($pollOptions as $index => $pollOption) {
                $choices = $this->pollOptionChoiceRepository->findBy([
                    'pollOption' => $pollOption->getId()
                ]);
                $optionChoicesCount = count($choices);
                $pollOption->setVotes($optionChoicesCount);
                $choicesTotal += $optionChoicesCount;

                if (!is_null($this->security->getUser())) {
                    $choiced = $this->pollOptionChoiceRepository->userVotedForPollOption(
                        $this->security->getUser()->getId(),
                        $pollOption->getId()
                    );

                    if ($choiced){
                        $data['userChoiced'] = true;
                    }

                    $pollOption->setMyChoice($choiced);
                }
                $pollOptions[$index] = $this->normalizer->normalize($pollOption,$format,$context);
            }

            $data['choicesTotal'] = $choicesTotal;
            $data['pollOptions'] = $pollOptions;
        }

        return $data;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Post;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
