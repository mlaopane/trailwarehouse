<?php

namespace TrailWarehouse\AppBundle\Service;

use TrailWarehouse\AppBundle\Entity\User;

class UserMailer
{
    /**
     * @var \Swift_Mailer
     */

    protected $mailer;

    /**
     * @var Templating
     */
    protected $templating;

    /**
     * UserMailer constructor.
     *
     * @param \Swift_Mailer $mailer
     * @param Templating $templating
     */
    public function __construct(\Swift_Mailer $mailer, Templating $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * @param string $subject
     * @param string $content
     * @param User $user
     *
     * @return $this
     */
    public function send(string $subject, string $content, User $user)
    {
        $swiftMessage = (new \Swift_Message($subject))
            ->setFrom('noreply@trailwarehouse.com')
            ->setTo($user->getEmail())
            ->setBody($content, 'text/html')
        ;
        $this->mailer->send($swiftMessage);

        return $this;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function sendWelcome(User $user)
    {
        $body = $this->templating->renderEmail('welcome', ['user' => $user]);
        $subject = 'Bienvenue chez Trail Warehouse';
        $this->send($subject, $body, $user);

        return $this;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function sendPasswordChanged(User $user)
    {
        $body = $this->templating->renderEmail('password', ['user' => $user]);
        $subject = 'Modification du mot de passe validée';
        $this->send($subject, $body, $user);

        return $this;
    }
}
