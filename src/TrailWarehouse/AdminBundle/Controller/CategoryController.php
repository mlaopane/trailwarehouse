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
  public function readAction()
  {
    $repo = $this->getDoctrine()->getRepository('AppBundle:Category');
    $category = new Category();
  }

  /**
   *
   */
  public function updateAction()
  {
    $manager = $this->getDoctrine()->getManager();
    $category = new Category();
  }

  /**
   *
   */
  public function deleteAction()
  {
    $manager = $this->getDoctrine()->getManager();
    $category = new Category();
  }

}
