<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

#[AsController]
class MarkTutorialSeenController extends AbstractController
{
    private Security $security;

    private EntityManagerInterface $entityManager;

    /**
     * @param Security $security 
     * @param EntityManagerInterface $entityManager
     * @return void 
     */
    public function __construct(
        Security $security,
        EntityManagerInterface $entityManager
    ) {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function __invoke(): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $user->setTutorial(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}