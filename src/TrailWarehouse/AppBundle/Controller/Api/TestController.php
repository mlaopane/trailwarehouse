<?php

namespace TrailWarehouse\AppBundle\Controller\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TrailWarehouse\AppBundle\Controller\Api\CommonController;
use TrailWarehouse\AppBundle\Entity\Brand;
use TrailWarehouse\AppBundle\Entity\Category;
use TrailWarehouse\AppBundle\Entity\Family;
use TrailWarehouse\AppBundle\Entity\Product;
use TrailWarehouse\AppBundle\Entity\Review;

class TestController extends Controller
{
  public function getAction()
  {
    $manager = $this->getDoctrine()->getManager();
    $builder = $manager->createQueryBuilder()
      ->addSelect('family.id', 'family.name')
      ->addSelect('AVG(review.rating) AS average')
      ->from('TrailWarehouseAppBundle:Family', 'family')
      ->leftJoin('family.reviews', 'review')
      ->groupBy('review.family')
      ->orderBy('review.rating', 'desc')
      ->setMaxResults(5);
    ;
    $response = $builder
      ->getQuery()
      ->getArrayResult()
    ;
    return new JsonResponse($response);
  }

  protected function createQueryBuilder(string $alias)
  {
    $manager = $this->getDoctrine()->getManager();
    $builder = $manager->createQueryBuilder('');
    return $builder->select($alias)->from('TrailWarehouseAppBundle:'.ucfirst(strtolower($alias)), $alias);
  }
}
