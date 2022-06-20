<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SetCommentController extends AbstractController
{
    /**
     * @var PostRepository
     */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(Post $data): Post
    {
        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }
}