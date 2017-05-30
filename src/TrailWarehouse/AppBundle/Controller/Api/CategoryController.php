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
     * PUT Category
     *
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
      $category_name = $request->request->get('name');
      $entity = $this->getRepository()->getOneBy('name', $category_name);
      if (!empty($entity)) {
        return new JsonResponse(false);
      }
      $entity = $this->getEntity();
      $entity->setName($category_name);

      $manager = $this->getManager();
      $manager->persist($entity);
      $manager->flush();
      return new JsonResponse(true);
    }

    /**
     * POST Category
     *
     * @param Request $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function modifyAction(Request $request, int $id)
    {
      $category_name = $request->request->get('name');
      $entity = $this->getRepository()->findOneBy('name', $category_name);
      if (empty($entity)) {
        return new JsonResponse(false);
      }
      else {
        $entity->setName($request->request->get('name'));
        $manager = $this->getManager();
        $manager->flush();
        return new JsonResponse(true);
      }
    }

    /**
     * DELETE Category
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function removeAction(int $id)
    {
      $entity = $this->getRepository()->find($id);
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
