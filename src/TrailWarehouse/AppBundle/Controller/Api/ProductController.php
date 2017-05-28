<?php

namespace TrailWarehouse\AppBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use TrailWarehouse\AppBundle\Entity\Product;
use TrailWarehouse\AppBundle\Controller\Api\CommonController;

class ProductController extends CommonController
{

    /**
     * GET All Categories
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
     * @param Product $entity
     *
     * @return JsonResponse
     */
    public function getAction(Product $entity)
    {
      return new JsonResponse($this->serialize($entity));
    }

    /**
     * PUT Category
     *
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
      $entity = $this->getEntity();
      $entities = [];
      $fields = ['family', 'color', 'size'];
      foreach ($fields as $field) {
        $entities[$field] = $this->getManager()
          ->getRepository('TrailWarehouseAppBundle:'. ucfirst($field))
          ->find($request->request->get($field))
        ;
      }
      $entity
        ->setFamily($entities['family'])
        ->setColor($entities['color'])
        ->setSize($entities['size'])
        ->setPrice($request->request->get('price'))
        ->setStock($request->request->get('stock'))
        ->generateRef()
      ;
      $db_entity = $this->getRepository()->findByRef($entity->getRef());
      if (empty($db_entity)) {
        $manager = $this->getManager();
        $manager->persist($entity);
        $manager->flush();
      }
      return new JsonResponse(true);
    }

    /**
     * POST Category
     *
     * @param Product $entity
     *
     * @return JsonResponse
     */
    public function modifyAction(Product $entity)
    {
      if (empty($entity)) {
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
     * DELETE Category
     *
     * @param Product $entity
     *
     * @return JsonResponse
     */
    public function removeAction(Product $entity)
    {
      if (empty($entity)) {
        return new JsonResponse(false);
      }
      else {
        $manager = $this->getManager();
        $manager->remove($entity);
        $manager->flush();
        return new JsonResponse(true);
      }
    }
}
