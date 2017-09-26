<?php

namespace TrailWarehouse\AppBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as EasyAdminController;

class BackendController extends EasyAdminController
{
  public function preUpdateEntity($entity)
  {
    // $this->updateSlug($entity);
  }

  private function updateSlug($entity)
  {
    if (method_exists($entity, 'setSlug'))
    {
      switch (true)
      {
        case method_exists($entity, 'getName') :
          $base = $entity->getName();
          break;
        case method_exists($entity, 'getTitle') :
          $base = $entity->getTitle();
          break;
        default :
          break;
      }
      $entity->setSlug($this->get('app.slugger')->slugify($base));
    }
  }

}
