<?php

namespace TrailWarehouse\AppBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use TrailWarehouse\AppBundle\Entity\Category;
use TrailWarehouse\AppBundle\Controller\Api\CommonController;
use TrailWarehouse\AppBundle\Form\CategoryType;

class CategoryController extends CommonController
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
     * @param Category $entity
     *
     * @return JsonResponse
     */
    public function getAction(Category $entity)
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
      $entity->setName($request->request->get('name'));
      $manager = $this->getManager();
      $manager->persist($entity);
      $manager->flush();
      return new JsonResponse(true);
    }

    /**
     * POST Category
     *
     * @param Category $entity
     *
     * @return JsonResponse
     */
    public function modifyAction(Request $request, Category $entity)
    {
      if (empty($entity)) {
        return new JsonResponse(false);
      }
      else {
        $entity->setName($request->request->get('name'));
        $manager = $this->getManager();
        $manager->persist($entity);
        $manager->flush();
        return new JsonResponse(true);
      }
    }

    /**
     * DELETE Category
     *
     * @param Category $entity
     *
     * @return JsonResponse
     */
    public function removeAction(Request $request, Category $entity)
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
