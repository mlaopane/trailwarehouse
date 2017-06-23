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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use TrailWarehouse\AppBundle\Entity\User;
use TrailWarehouse\AppBundle\Form\SignupType;
use TrailWarehouse\AppBundle\Form\SigninType;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;

class ClientController extends Controller
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
   */
  public function signupAction(Request $request)
  {
    $form = $this->createForm(SignupType::class, $this->user);
    // Form submitted
    if ($form->isSubmitted()) {
      $form->handleRequest($request);
      if ($form->isValid()) {
        // Register the User
        $this->registerUser($this->user);
        $request->getSession()->getFlashBag()->add('notice', 'Votre inscription a été prise en compte');
        // Redirect to the Shop
        return $this->redirectToRoute('app_shop');
      }
      else {
        $request->getSession()->getFlashBag()->add('danger', 'L\'inscription a échoué');
      }
    }
    // Display the form to register
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
          return $this->redirectToRoute('app_client_signin');
        }
        $request->getSession()->getFlashBag()->add('notice', 'Bienvenue '. $this->user->getFirstname());
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
   * @param User $user
   * @param string $hash
   * Register a user into the database
   */
  protected function registerUser($user, $hash) {
    $user
      ->setRole('user')
      ->setIsActive(false)
      ->setDateCreation(new \DateTime())
    ;
    $manager = $this->getDoctrine()->getManager();
    $manager->persist($user);
    $manager->flush();
  }

}
