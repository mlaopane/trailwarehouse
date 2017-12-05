<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use TrailWarehouse\AppBundle\Service\RepositoryManager;

class HomeController extends Controller
{
  private $repo;

  public function __construct(RepositoryManager $rm)
  {
    $this->repo = [
      'brand'    => $rm->get('Brand'),
      'category' => $rm->get('Category'),
      'family'   => $rm->get('Family'),
    ];
  }


  public function indexAction(EntityManagerInterface $em)
  {
    return $this->redirectToRoute('app_shop_categories');
    $data['brands'] = $this->repo['brand']->findAll(['brand' => 'asc']);
    $data['categories'] = $this->repo['category']->findAll(['category' => 'asc']);
    $data['selections'] = [];

    foreach ($data['categories'] as $category)
    {
      $data['selections'][$category->getSlug()] = $this->repo['family']->getBestOfCategory($category, 3, false);
    }

    return $this->render('TrailWarehouseAppBundle:Home:index.html.twig', $data);
  }

  public function contactAction()
  {
      $data = [];
      return $this->render('TrailWarehouseAppBundle:Home:contact.html.twig', $data);
  }

  public function aboutAction()
  {
      $data = [];
      return $this->render('TrailWarehouseAppBundle:Home:about.html.twig', $data);
  }

  public function mentionsAction()
  {
      $data = [];
      return $this->render('TrailWarehouseAppBundle:Home:mentions.html.twig', $data);
  }
}
