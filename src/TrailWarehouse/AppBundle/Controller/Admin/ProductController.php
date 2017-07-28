<?php

namespace TrailWarehouse\AppBundle\Controller\Admin;

use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as EasyAdminController;

class ProductController extends EasyAdminController
{
  public function createNewEntity($entity)
  {
    $this->init($entity);
  }

  public function preUpdateEntity($entity)
  {
    $this->init($entity);
  }

  private function init($entity)
  {
    $em = $this->getDoctrine()->getManager();
    $repo['family_color'] = $em->getRepository('TrailWarehouseAppBundle:FamilyColor');
    $family_colors = $repo['family_color']->findBy(['family' => $entity->getFamily(), 'color' => $entity->getColor()]);

    $entity->setImageName($family_colors[0]->getImageName());
    $entity->generateName();
    $entity->generateRef();
    $em->persist($entity);
    $em->flush();
  }
}
