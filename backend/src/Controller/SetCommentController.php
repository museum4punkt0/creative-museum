<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SetCommentController extends AbstractController
{
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
