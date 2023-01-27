<?php

namespace App\Service;

use App\Entity\User;
use App\Enum\MailType;
use App\Enum\NotificationType;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class MailService
{
    public function __construct
    (
        private readonly string $fromMail,
        private readonly MailerInterface $mailer,
        private readonly LoggerInterface $logger,
        private readonly UserRepository $userRepository,
        private readonly RouterInterface $router
    )
    {
    }

    public function sendMail(string $identifier,string|User $receiver = null, array $parameters = [])
    {
        if ($receiver instanceof User && $receiver?->getDeleted()) {
            return;
        }

        if (is_string($receiver)) {
            $receiver = User::createWithEmail($receiver);
        }

        if (is_null($receiver)) {
            $receivers = $this->userRepository->findBy([
                'active' => 1,
                'deleted' => 0
            ]);

            foreach ($receivers as $receiver) {
                $email = $this->prepareMail($identifier, $receiver, $parameters);

                if ($receiver->getNotificationSettings() !== NotificationType::NONE) {
                    $this->send($email);
                }
            }
        } else {
            $email = $this->prepareMail($identifier, $receiver, $parameters);

            if ($receiver->getNotificationSettings() !== NotificationType::NONE) {
                $this->send($email);
            }
        }
    }

    protected function getSubject(string $mailIdentifier, array $parameters = []): string
    {
        $translations = json_decode(file_get_contents(__DIR__ . '/../../../frontend/locales/de.json'), true);
        $subject = $translations['mailings']['subjects'][$mailIdentifier];

        switch ($mailIdentifier) {
            case MailType::AWARD_RECEIVED->value:
                $subject = sprintf($subject, $parameters['awarded']->getAward()->getTitle());
                break;
            case MailType::BADGE_RECEIVED->value:
                $subject = sprintf($subject, $parameters['badged']->getBadge()->getTitle());
                break;
        }

        return $subject;
    }

    protected function prepareMail(string $identifier, User $receiver, array $parameters = []): TemplatedEmail
    {
        $parameters['domain'] = $this->router->generate('start', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $parameters['receiver'] = $receiver;


        $subject = $this->getSubject($identifier, $parameters);

        $email = (new TemplatedEmail())
            ->from(new Address($this->fromMail, 'Creative Museum'))
            ->to(new Address($receiver->getEmail()))
            ->subject($subject)
            ->htmlTemplate("emails/{$identifier}.html.twig")
            ->context($parameters);

        return $email;
    }

    protected function send(TemplatedEmail $email,)
    {
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage());
        }
    }
}