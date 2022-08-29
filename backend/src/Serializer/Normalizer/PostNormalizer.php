<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Serializer\Normalizer;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Vote;
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

    private Security $security;

    public function __construct(
        ObjectNormalizer $normalizer,
        PostRepository $postRepository,
        PostFeedbackRepository $feedbackRepository,
        VoteRepository $votesRepository,
        Security $security
    ) {
        $this->normalizer = $normalizer;
        $this->postRepository = $postRepository;
        $this->security = $security;
        $this->feedbackRepository = $feedbackRepository;
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

        if (null !== $this->security->getUser()) {
            /** @var User $user */
            $user = $this->security->getUser();

            if ($user->getBookmarks()->contains($object)) {
                $data['bookmarked'] = true;
            }

            $myVote = $this->votesRepository->findOneBy(['voter' => $user, 'post' => $object]);
            if ($myVote instanceof Vote) {
                $data['my_vote'] = $this->normalizer->normalize($myVote, $format, $context);
            }

            $feedback = $this->feedbackRepository->findBy(['user' => $user, 'post' => $object]);
            if (count($feedback)) {
                $data['rated'] = true;
            }
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
