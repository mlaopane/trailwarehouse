<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use TrailWarehouse\AppBundle\Entity\Member;
use TrailWarehouse\AppBundle\Form\SignupType;
use TrailWarehouse\AppBundle\Form\SigninType;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;

class ClientController extends Controller
{
  /* --------------------- */
  /* *** Initilization *** */
  /* --------------------- */

  protected $member;

  public function __construct() {
    $this->member = new Member();
  }

  /* -------------- */
  /* *** Routes *** */
  /* -------------- */

  /**
   * 'signup' route
   */
  public function signupAction(Request $request)
  {
    $form = $this->get('form.factory')->create(SignupType::class, $this->member);
    if ($form->isSubmitted())
    {
      $form->handleRequest($request);
      if ($form->isValid())
      {
        $this->registerMember($this->member);
        $request->getSession()->getFlashBag()->add('notice', 'Votre inscription a été prise en compte');
        return $this->redirectToRoute('app_shop');
      }
    }
    $data = [
      'form' => $form->createView(),
    ];
    return $this->render('TrailWarehouseAppBundle:Client:signup.html.twig', $data);
  }

  /**
   * 'signin' route
   */
  public function signinAction(Request $request)
  {
    $form = $this->get('form.factory')->create(SigninType::class, $this->member);
    if ($form->isSubmitted())
    {
      $form->handleRequest($request);
      if ($form->isValid())
      {
        $manager = $this->getDoctrine()->getManager();
        $repository = $manager->getRepository('TrailWarehouseAppBundle:Member');
        $db_member = $repository->findOneBy([
          'email' => $this->member->getEmail(),
          'password' => $this->member->getPassword(),
        ]);

        if ($db_member == NULL)
        {
          return $this->redirectToRoute('app_client_signin');
        }
        $request->getSession()->getFlashBag()->add('notice', 'Bienvenue '. $this->member->getFirstname());
        return $this->redirectToRoute('app_shop');
      }
    }
    $data = [
      'form' => $form->createView(),
    ];
    return $this->render('TrailWarehouseAppBundle:Client:signin.html.twig', $data);
  }

  /**
   * 'signout' route
   */
  public function signoutAction(Request $request)
  {
    // Return
    return $this->render('TrailWarehouseAppBundle:Home:index.html.twig');
  }

  /* -------------------------- */
  /* *** Additional Methods *** */
  /* -------------------------- */

  /**
   * @param {Member} $member
   * Register a member into the database
   */
  protected function registerMember($member) {
    $member
      ->setRole('user')
      ->setIsActive(false)
      ->setDateCreation(new \DateTime())
    ;
    $manager = $this->getDoctrine()->getManager();
    $manager->persist($member);
    $manager->flush();
  }

}
