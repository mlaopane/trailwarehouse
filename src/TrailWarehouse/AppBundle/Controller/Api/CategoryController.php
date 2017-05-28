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
      $db_entities = $repository->findAll();
      $entities = $this->serializer->serialize($db_entities, 'json');
      $response = [
        'status' => empty($entities) ? false : true,
        'data' => $entities,
      ];
      return new JsonResponse($response);
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
      $category = $this->serialize($category);
      $response = [
        'status' => NULL !== $category ? true : false,
        // 'data' => [
        //   'id' => $category->getId(),
        //   'name' => $category->getName(),
        //   'slug' => $category->getSlug(),
        // ],
        'data' => $category,
      ];
      return new JsonResponse($response);
    }

    /**
     * PUT Category
     *
     * @return JsonResponse
     */
    public function addAction()
    {
      $manager = $this->getManager();
      $entity = new Category();
      if (true) {
        $response['status'] = false;
      }
      else {
        $response['status'] = true;
        $manager->persist($entity);
        $manager->flush();
      }
      return new JsonResponse($response);
    }

    /**
     * POST Category
     *
     * @return JsonResponse
     */
    public function modifyAction(Category $category)
    {
      if (empty($category)) {
        $response['status'] = false;
      }
      else {
        $response['status'] = true;
        $manager = $this->getManager();
        $manager->persist($category);
        $manager->flush();
      }
      return new JsonResponse($response);
    }

    /**
     * DELETE Category
     *
     * @return JsonResponse
     */
    public function removeAction(Category $category)
    {
      if (empty($category)) {
        $response['status'] = false;
      }
      else {
        $response['status'] = true;
        $manager = $this->getManager();
        $manager->remove($category);
        $manager->flush();
      }
      return new JsonResponse($response);
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
