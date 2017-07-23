<?php

namespace TrailWarehouse\AppBundle\Controller\Admin;

use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as EasyAdminController;

class UserController extends EasyAdminController
{
  public function preUpdateEntity($entity)
  {
    $entity->generateHash();
  }
}
