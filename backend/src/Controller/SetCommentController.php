<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SetCommentController extends AbstractController
{
    /**
     * @var PostRepository
     */
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function __invoke($id, Post $data): Post
    {
        $parent = $this->postRepository->find($id);
        $data->setParent($parent);
        $this->postRepository->add($data);

        return $data;
    }
}