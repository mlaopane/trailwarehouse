<?php

namespace TrailWarehouse\AppBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as EasyAdminController;

class FamilyColorController extends EasyAdminController
{
  public function postUpdateEntity($entity)
  {
    $em = $this->getDoctrine()->getManager();
    $repo['product'] = $this->getDoctrine()->getRepository('TrailWarehouseAppBundle:Product');
    $products = $repo['product']->findBy(['family' => $entity->getFamily(), 'color' => $entity->getColor()]);
  }
}
