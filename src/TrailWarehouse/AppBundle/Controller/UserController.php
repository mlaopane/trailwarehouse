<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface as TknStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken as Tkn;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface as PW_Encoder;
use Symfony\Component\Form\Form;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\ORM\{EntityRepository, EntityManagerInterface};
use TrailWarehouse\AppBundle\Entity\{User, Cart, Address};
use TrailWarehouse\AppBundle\Form\{SignupType, SigninType, AccountType, AddressType};
use TrailWarehouse\AppBundle\Service\RepositoryManager;

class UserController extends Controller
{
    /* ---------------------- */
    /* *** Initialization *** */
    /* ---------------------- */

    /**
     * @var User
     */
    protected $user;

    /**
     * @var EntityRepository
     */
    protected $repo;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $em, RepositoryManager $rm)
    {
        $this->em = $em;
        $this->repo = [
            'user' => $rm->get('User'),
            'role' => $rm->get('Role'),
        ];
        $this->user = new User($this->repo['role']->findOneByName('ROLE_USER'));
    }

    /* -------------- */
    /* *** Routes *** */
    /* -------------- */

    /**
    * 'signup' route
    * @param Request $request
    * @param EntityManagerInterface $em
    */
    public function signupAction(Request $request, SessionInterface $session, TknStorage $tokenStorage, PW_Encoder $passwordEncoder)
    {
        $form = $this->createForm(SignupType::class, $this->user);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $this->registerUser($passwordEncoder);
            $this->autoLoginUser($tokenStorage, $session);
            return $this->redirectToRoute('app_home');
        }

        $data = [
            'signup_form' => $form->createView(),
        ];

        return $this->render('TrailWarehouseAppBundle:User:signup.html.twig', $data);
    }

    /**
     * 'signin' route
     */
    public function signinAction(AuthenticationUtils $authUtils, UserInterface $user = null)
    {
        // IF the user is already authenticated => GOTO app_account
        if ($user) {
            return $this->redirectToRoute('app_account');
        }

        $form = $this->createForm(SigninType::class, $this->user);

        $data = [
            'signin_form'   => $form->createView(),
            'last_username' => $authUtils->getLastUsername(),
            'error'         => $authUtils->getLastAuthenticationError(),
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

    /**
     * Register the User into the database
     */
    private function registerUser(PW_Encoder $passwordEncoder): void
    {
        if ($this->user->getEmail() == 'mlaopane@gmail.com') {
            $this->user->setRole($this->repo['role']->findOneByName('ROLE_SUPER_ADMIN'));
        }
        $this->user->setPassword(
            $passwordEncoder->encodePassword(
                $this->user,
                $this->user->getPlainPassword()
            )
        );
        $this->em->persist($this->user);
        $this->em->flush();
    }

    /**
     * Log the user in automatically after registering
     */
    private function autoLoginUser(TknStorage $tokenStorage, SessionInterface $session): void
    {
        $token = new Tkn(
            $this->user,
            $this->user->getPassword(),
            'main',
            $this->user->getRoles()
        );
        $tokenStorage->setToken($token);
        $session->set('_security_main', serialize($token));
    }

}
