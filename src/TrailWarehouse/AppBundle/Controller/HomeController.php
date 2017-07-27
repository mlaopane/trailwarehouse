<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends Controller
{
  private $repo;

  public function __construct(EntityManagerInterface $em)
  {
    $this->repo = [
      'brand'    => $em->getRepository('TrailWarehouseAppBundle:Brand'),
      'category' => $em->getRepository('TrailWarehouseAppBundle:Category'),
      'family'   => $em->getRepository('TrailWarehouseAppBundle:Family'),
    ];
  }

  public function indexAction(EntityManagerInterface $em)
  {
    $data['brands'] = $this->repo['brand']->findAll(['brand' => 'asc']);
    $data['categories'] = $this->repo['category']->findAll(['category' => 'asc']);
    $data['selections'] = [];
    foreach ($data['categories'] as $category)
    {
      $data['selections'][$category->getSlug()] = $this->repo['family']->getBestOfCategory($category, 3, false);
    }

    dump($data['selections']);

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
