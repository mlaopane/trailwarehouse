<?php

namespace TrailWarehouse\AppBundle\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use TrailWarehouse\AppBundle\Entity\Category;

class CategoryController extends Controller
{
  private $serializer;

  public function __construct() {
    $normalizers = [new ObjectNormalizer()];
    $encoders = [new JsonEncoder()];
    $this->serializer = new Serializer($normalizers, $encoders);
  }

    /**
     * GET Category
     *
     * @return JsonResponse
     */
    public function getAllAction()
    {
      $repository = $this->getRepository();
      $entities = $repository->findAll();
      return new JsonResponse($this->serialize($entities));
    }

    /**
     * GET Category
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function getAction(Category $category)
    {
      return new JsonResponse($this->serialize($category));
    }

    /**
     * PUT Category
     *
     * @return JsonResponse
     */
    public function addAction()
    {
      $entity = new Category();
      if (true) {
        return new JsonResponse(false);
      }
      else {
        $manager = $this->getManager();
        $manager->persist($entity);
        $manager->flush();
        return new JsonResponse(true);
      }
    }

    /**
     * POST Category
     *
     * @return JsonResponse
     */
    public function modifyAction(Category $category)
    {
      if (empty($category)) {
        return new JsonResponse(false);
      }
      else {
        $manager = $this->getManager();
        $manager->persist($category);
        $manager->flush();
        return new JsonResponse(true);
      }
    }

    /**
     * DELETE Category
     *
     * @return JsonResponse
     */
    public function removeAction(Category $category)
    {
      if (empty($category)) {
        return new JsonResponse(false);
      }
      else {
        $manager = $this->getManager();
        $manager->remove($category);
        $manager->flush();
        return new JsonResponse(true);
      }
    }

    /**
     * getManager
     *
     * @access private
     *
     * @return EntityManager
     */
    private function getManager()
    {
      return $this
        ->getDoctrine()
        ->getManager()
      ;
    }

    /**
     * getRepository
     *
     * @access private
     *
     * @return Repository
     */
    private function getRepository()
    {
      return $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('TrailWarehouseAppBundle:Category')
      ;
    }

    public function serialize($object) {
      return $this->serializer->serialize($object, 'json');
    }
}
