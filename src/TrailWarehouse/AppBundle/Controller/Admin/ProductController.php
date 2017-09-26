<?php

namespace TrailWarehouse\AppBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as EasyAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintValidator;

class ProductController extends EasyAdminController
{
  private $repository;

  protected function initialize(Request $request)
  {
    parent::initialize($request);
    $this->repository = $this->em->getRepository('TrailWarehouseAppBundle:Product');
  }

  public function preUpdateEntity($product)
  {
    // $product->generateName();
    // $product->generateRef();
  }

  public function restockAction()
  {
    $id = $this->request->query->get('id');
    $entity = $this->repository->find($id);
    $entity->setStock($entity->getStock() + 10);
    $this->em->flush();

    return $this->redirectToRoute('easyadmin', [
      'action' => 'list',
      'entity' => $this->request->query->get('entity'),
    ]);
  }
}
