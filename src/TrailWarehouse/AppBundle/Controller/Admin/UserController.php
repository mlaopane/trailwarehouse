<?php

namespace TrailWarehouse\AppBundle\Controller\Admin;

use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as EasyAdminController;
use Doctrine\ORM\EntityManagerInterface;
use TrailWarehouse\AppBundle\Entity\User;

class UserController extends EasyAdminController
{
  public function createNewEntity()
  {
    $em = $this->getDoctrine()->getManager();
    $default_role = $em->getRepository('TrailWarehouseAppBundle:Role')->findOneByName('ROLE_USER');
    return new User($default_role);
  }

  public function preUpdateEntity($entity)
  {
    $entity->generateHash();
  }
}
