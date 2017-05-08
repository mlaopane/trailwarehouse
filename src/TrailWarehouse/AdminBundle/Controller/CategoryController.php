<?php

namespace TrailWarehouse\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TrailWarehouse\AppBundle\Entity\Category;

class CategoryController extends Controller
{
  /**
   *
   */
  public function createAction()
  {
    $manager = $this->getDoctrine()->getManager();

    $category = new Category();
    $category->setName("chaussures");

    $manager->persist($category);
    $manager->flush();

    return $this->redirectToRoute('app_shop');
  }

  /**
   *
   */
  public function readAction($id)
  {
    $category = $this->getDoctrine()->getRepository('TrailWarehouseAppBundle:Category')->find($id);

    if ( $category ) {
      // Do something
    }

    return $this->redirectToRoute('app_shop');
  }

  /**
   *
   */
  public function updateAction($id)
  {
    $manager = $this->getDoctrine()->getManager();

    $category = $manager->getRepository('TrailWarehouseAppBundle:Category')->find($id);

    if ( $category ) {
      $category->setName('Vestes');
      $manager->flush();
    }

    return $this->redirectToRoute('app_shop');
  }

  /**
   *
   */
  public function deleteAction($id)
  {
    $manager = $this->getDoctrine()->getManager();

    $category = $manager->getRepository('TrailWarehouseAppBundle:Category')->find($id);

    if ( $category ) {
      $manager->remove($category);
      $manager->flush();
    }

    return $this->redirectToRoute('app_shop');
  }

}
