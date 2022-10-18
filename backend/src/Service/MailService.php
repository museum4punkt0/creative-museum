<?php

namespace App\Service;

use App\Entity\User;
use App\Enum\NotificationType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailService
{
    public function __construct
    (
        private readonly string $fromMail,
        private readonly MailerInterface $mailer
    ){}

    public function sendMail(string $identifier, User $receiver, array $parameters = [])
    {
        if ($receiver->getDeleted()){
            return;
        }

        $parameters['receiver'] = $receiver;
        //@todo Woher nehmen wir das Subject
        $parameters['subject'] = '???';

        $email = (new TemplatedEmail())
            ->from($this->fromMail)
            ->to(new Address($receiver->getEmail(),'Creative Museum'))
            ->subject($parameters['subject'])
            ->htmlTemplate("emails/{$identifier}.html.twig")
            ->context($parameters);

        //@todo system mails beachten

        try {
            if ($receiver->getNotificationSettings() !== NotificationType::NONE){
                $this->mailer->send($email);
            }
        } catch (TransportExceptionInterface $e){
            //@todo evt loggen ?
        }
    }
}