<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Event\NewUserRegisteredEvent;
use App\OauthProvider\IdpOauthProvider;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Uid\Uuid;

class IdpAuthenticator extends AbstractAuthenticator
{
    const AUTH_HEADER_NAME = 'authorization';

    private JWTTokenManagerInterface $jwtManager;

    private EntityManagerInterface $entityManager;

    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher

    ) {
        $this->jwtManager = $jwtManager;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function supports(Request $request): ?bool
    {
        if (null === $request->headers->get(self::AUTH_HEADER_NAME)) {
            return false;
        }

        return str_starts_with($request->headers->get(self::AUTH_HEADER_NAME), 'Bearer');
    }

    public function authenticate(Request $request): Passport
    {
        $bearer = $request->headers->get(self::AUTH_HEADER_NAME);

        try {
            $token = $this->jwtManager->parse(str_replace('Bearer ', '', $bearer));
        } catch (JWTDecodeFailureException $e) {
            throw new AuthenticationException('Token invalid or expired');
        }

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

                if ($token['sub'] === '' && in_array('api', $token['scopes'])) {
                    $apiUser = new User();
                    $apiUser->setUuid(Uuid::v6());
                    $apiUser->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

                    return $apiUser;
                }

                $provider = new IdpOauthProvider([
                    'clientId' => $_ENV['IDP_OAUTH_CLIENT_ID'],
                    'clientSecret' => $_ENV['IDP_OAUTH_CLIENT_SECRET'],
                    'verify' => 'dev' !== $_ENV['APP_ENV']
                ]);

                $idpToken = $provider->getAccessToken(
                    'client_credentials', [ 'scope' => 'api' ]
                );

                $req = $provider->getAuthenticatedRequest(
                    Request::METHOD_GET,
                    str_replace('{uuid}', $token['sub'], $_ENV['IDP_OAUTH_USERINFO_ENDPOINT']),
                    $idpToken
                );

                $response = $provider->getParsedResponse($req);

                $user = new User();
                $user->setUuid($token['sub']);
                $user->setFirstName($response['firstName']);
                $user->setLastName($response['lastName']);
                $user->setEmail($response['email']);
                $user->setActive(true);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $userRegisteredEvent = new NewUserRegisteredEvent($user->getId());
                $this->eventDispatcher->dispatch($userRegisteredEvent, NewUserRegisteredEvent::NAME);

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