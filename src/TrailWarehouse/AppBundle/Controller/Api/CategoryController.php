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
use Doctrine\ORM\EntityManagerInterface;

class CategoryController extends CommonController
{

    /**
     * PUT Category
     *
     * @return JsonResponse
     */
    public function addAction(Request $request, EntityManagerInterface $em)
    {
      $category_name = $request->request->get('name');
      $entity = $this->getRepository()->getOneBy('name', $category_name);
      if (!empty($entity)) {
        return new JsonResponse(false);
      }
      $entity = $this->getEntity();
      $entity->setName($category_name);

      $em->persist($entity);
      $em->flush();
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
    public function modifyAction(Request $request, EntityManagerInterface $em, int $id)
    {
      $category_name = $request->request->get('name');
      $entity = $this->getRepository()->findOneBy('name', $category_name);
      if (empty($entity)) {
        return new JsonResponse(false);
      }
      else {
        $entity->setName($request->request->get('name'));
        $em->flush();
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
    public function removeAction(int $id, EntityManagerInterface $em)
    {
      $entity = $this->getRepository()->find($id);
      if (empty($entity)) {
        return new JsonResponse(false);
      }
      else {
        $em->remove($entity);
        $em->flush();
        return new JsonResponse(true);
      }
    }
}
