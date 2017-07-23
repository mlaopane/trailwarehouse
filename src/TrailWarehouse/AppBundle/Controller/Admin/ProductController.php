<?php

namespace TrailWarehouse\AppBundle\Controller\Admin;

use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as EasyAdminController;

class ProductController extends EasyAdminController
{
  public function preUpdateEntity($entity)
  {
    $entity->generateName();
    $entity->generateRef();
  }
}
