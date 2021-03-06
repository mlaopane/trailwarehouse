<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface as TknStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken as Tkn;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface as PW_Encoder;
use Doctrine\ORM\{EntityRepository, EntityManagerInterface};
use TrailWarehouse\AppBundle\Entity\{Role, User, Address};
use TrailWarehouse\AppBundle\Form\{SignupType, SigninType};
use TrailWarehouse\AppBundle\Service\RepositoryManager;
use TrailWarehouse\AppBundle\Service\UserMailer;

class UserController extends TrailWarehouseController
{
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
        parent::__construct();
        $this->em = $em;
        $this->repo = [
            'user' => $rm->get('User'),
            'role' => $rm->get('Role'),
        ];
        $role_user = $this->repo['role']->findOneByName('ROLE_USER');
        $this->user = new User($role_user);
    }

    /**
     * @param UserInterface $user
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function signupAction(UserInterface $user = NULL)
    {
        if ($user) {
            return $this->redirectToRoute('app_account');
        }

        $form = $this->createForm(SignupType::class, $this->user);

        return $this->renderForm('signup', $form);
    }

    /**
     * @param Request $request
     * @param SessionInterface $session
     * @param TknStorage $tokenStorage
     * @param PW_Encoder $passwordEncoder
     * @param UserMailer $userMailer
     * @param UserInterface|NULL $user
     */
    public function signupProcessAction(Request $request, SessionInterface $session, TknStorage $tokenStorage, PW_Encoder $passwordEncoder, UserMailer $userMailer, UserInterface $user = NULL)
    {
        if ($user) {
            return $this->redirectToRoute('app_account');
        }

        $form = $this->createForm(SignupType::class, $this->user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->registerUser($passwordEncoder);
            $this->autoLoginUser($tokenStorage, $session);
            $userMailer->sendWelcome($this->user);
            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm('signup', $form);
    }

    /**
     * @param AuthenticationUtils $authUtils
     * @param UserInterface|NULL $user
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function signoutAction()
    {
        return $this->render('TrailWarehouseAppBundle:Home:index.html.twig');
    }

    /* -------------------------- */
    /* *** Additional Methods *** */
    /* -------------------------- */

    /**
     * Register the User into the database
     *
     * @param PW_Encoder $passwordEncoder
     */
    private function registerUser(PW_Encoder $passwordEncoder): void
    {
        if ($this->user->getEmail() == 'mlaopane@gmail.com') {
            $role = $this->getRole('ROLE_SUPER_ADMIN');
            $this->user->setRole($role);
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
     *
     * @param TknStorage $tokenStorage
     * @param SessionInterface $session
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

    /**
     * @param  string $roleName
     *
     * @return Role
     */
    private function getRole(string $roleName): Role
    {
        $role = $this->repo['role']->findOneByName($roleName);
        if (null === $role) {
            $em = $this->getDoctrine()->getEntityManager();
            $role = new Role($roleName);
            $em->persist($role);
            $em->flush();
        }
        return $role;
    }

}
