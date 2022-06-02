<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class IdpAuthenticator extends AbstractAuthenticator
{
    const AUTH_HEADER_NAME = 'authorization';

    private JWTTokenManagerInterface $jwtManager;

    private EntityManagerInterface $entityManager;

    public function __construct(JWTTokenManagerInterface $jwtManager, EntityManagerInterface $entityManager)
    {
        $this->jwtManager = $jwtManager;
        $this->entityManager = $entityManager;
    }

    public function supports(Request $request): ?bool
    {
        return false;
        if (null === $request->headers->get(self::AUTH_HEADER_NAME)) {
            return false;
        }

        return str_starts_with($request->headers->get(self::AUTH_HEADER_NAME), 'Bearer');
    }

    public function authenticate(Request $request): Passport
    {
        $bearer = $request->headers->get(self::AUTH_HEADER_NAME);
        $token = $this->jwtManager->parse(str_replace('Bearer ', '', $bearer));

        return new SelfValidatingPassport(
            new UserBadge($token['sub'], function () use ($token) {

                /** @var User $existingUser */
                $existingUser = $this->entityManager->getRepository(User::class)
                    ->findOneBy(['uuid' => $token['sub']]);

                if ($existingUser) {
                    if (! $existingUser->getActive()) {
                        throw new AuthenticationException('User is inactive');
                    }
                    return $existingUser;
                }

                $user = new User();
                $user->setUuid($token['sub']);
                $user->setActive(true);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                return $user;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());
        return new Response($message, Response::HTTP_FORBIDDEN);
    }
}