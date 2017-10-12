<?php

namespace TrailWarehouse\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class TestController
 *
 * @package TrailWarehouse\AppBundle\Controller
 */
class TestController extends TrailWarehouseController
{
    /**
     * @param UserInterface $user
     *
     * @Route("/welcome", name="test_welcome")
     */
    public function welcomeAction(UserInterface $user)
    {
        return $this->renderTwig('welcome', ['user' => $user]);
    }

    /**
     * @param UserInterface $user
     *
     * @Route("/password", name="test_password")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function passwordAction(UserInterface $user)
    {
        return $this->renderTwig('password', ['user' => $user]);
    }

}
