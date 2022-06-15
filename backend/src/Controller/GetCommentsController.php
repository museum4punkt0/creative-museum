<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetCommentsController extends AbstractController
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @param int $id
     * @return array|\Doctrine\Common\Collections\Collection
     */
    public function __invoke(int $id)
    {
        $post = $this->postRepository->find($id);

        if ($post) {
            return $post->getComments();
        }

        return [];
    }
}