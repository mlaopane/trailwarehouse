<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use TrailWarehouse\AppBundle\Entity\User;
use TrailWarehouse\AppBundle\Form\SignupType;
use TrailWarehouse\AppBundle\Form\SigninType;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;

class UserController extends Controller
{
  /* --------------------- */
  /* *** Initilization *** */
  /* --------------------- */

  protected $user;

  public function __construct() {
    $this->user = new User();
  }

  /* -------------- */
  /* *** Routes *** */
  /* -------------- */

  /**
   * 'signup' route
   * @param Request $request
   */
  public function signupAction(Request $request)
  {
    $form = $this->createForm(SignupType::class, $this->user);
    $form->handleRequest($request);
    // Form submitted ?
    if ($form->isSubmitted() AND $form->isValid()) {
      // Super Admin ?
      if ($this->user->getEmail() == 'mlaopane@gmail.com') {
        $this->user->setRole('ROLE_SUPER_ADMIN');
      }
      $manager = $this->getDoctrine()->getManager();
      $manager->persist($this->user);
      $manager->flush();
      // Redirect to the Shop
      return $this->redirectToRoute('app_shop');
    }
    // Display the form to sign up
    $data = [
      'signup_form' => $form->createView(),
    ];
    return $this->render('TrailWarehouseAppBundle:User:signup.html.twig', $data);
  }

  /**
   * 'signin' route
   */
  public function signinAction(Request $request)
  {
    $form = $this->get('form.factory')->create(SigninType::class, $this->user);
    // Envoi du formulaire
    if ($form->isSubmitted())
    {
      $form->handleRequest($request);
      // Formulaire non valide
      if (!$form->isValid()) {
        $request->getSession()->getFlashBag()->add('warning', 'Formulaire non valide !');
      }
      // Formulaire valide
      else {
        $manager = $this->getDoctrine()->getManager();
        $repository = $manager->getRepository('TrailWarehouseAppBundle:User');
        $db_user = $repository->findOneBy([
          'email'    => $this->user->getEmail(),
          'password' => $this->user->getPassword(),
        ]);
        // Identifiants erronés
        if ($db_user == NULL) {
          return $this->redirectToRoute('app_user_signin');
        }
        $request->getSession()->getFlashBag()->add('notice', 'Bienvenue '. $this->user->getFirstname());
        return $this->redirectToRoute('app_shop');
      }
    }
    $data = [
      'signin_form' => $form->createView(),
    ];
    return $this->render('TrailWarehouseAppBundle:User:signin.html.twig', $data);
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

}
