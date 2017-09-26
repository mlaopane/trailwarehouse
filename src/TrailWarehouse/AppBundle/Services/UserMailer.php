<?php

namespace TrailWarehouse\AppBundle\Services;
use TrailWarehouse\AppBundle\Entity\User;

/**
 *
 */
class UserMailer
{

  private $mailer;

  public function __construct(\Swift_Mailer $mailer)
  {
    $this->mailer = $mailer;
  }

  public function sendSignupNotification(User $user)
  {
    $subject = "Inscription prise en compte";
    $body    =
      "Bonjour ". ucfirst($user->getFirstname()) .
      "\nBienvenue chez TrailWarehouse, votre spécialiste en équipement de trail." .
      "\nVous pouvez dès à présent passer commande sur notre boutique";
    $message = (new \Swift_Message($subject, $body))
      ->addTo($user->getEmail())
      ->addFrom("noreply@trailwarehouse.fr");

    $this->mailer->send($message);

    return $this;
  }

  public static function getAuthor()
  {
    return "Mickaël LAO-PANE";
  }
}
