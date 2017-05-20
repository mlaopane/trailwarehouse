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

class ClientController extends Controller
{

  /**
   * 'signup' route
   */
  public function signupAction(Request $request)
  {
    $data = [];

    // Create a Member
    $member = new Member();

    // Create the form
    $form = $this->get('form.factory')->createBuilder(FormType::class, $member)
      ->add('firstname', TextType::class)
      ->add('lastname', TextType::class)
      ->add('email', EmailType::class)
      ->add('password', PasswordType::class)
      ->add('signup', SubmitType::class)
      ->getForm()
    ;

    // IF XMLHttpRequest
    if ($request->isXmlHttpRequest())
    {
      // Return
      return new JsonResponse($data);
    }

    // IF the form has been submitted
    if ($request->isMethod('post'))
    {
      $form->handleRequest($request);

      // IF the form is OK
      if ($form->isValid()) {
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($member);
        $manager->flush();

        $request->getSession()->getFlashBag()->add('notice', 'Votre inscription a été prise en compte');

        return $this->redirectToRoute('shop');
      }
      else {
        $request->getSession()->getFlashBag()->add('failure', 'Formulaire non valide');
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
    $data = [];

    // Return
    if ($request->isXmlHttpRequest()) {
      return new JsonResponse($data);
    }
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

}
