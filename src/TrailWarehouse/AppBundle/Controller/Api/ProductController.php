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
   * getBy
   * @param int family
   * @param int color
   * @param int size
   */
   public function getByAction(int $family, int $color, int $size)
   {
     $args_name = ['family', 'color', 'size'];
     $ids = func_get_args();

    foreach ($ids as $i => $id) {
      $field = $args_name[$i];
      $args[$field] = $this->getManager()
        ->getRepository('TrailWarehouseAppBundle:'.ucfirst($field))
        ->find($id)
      ;
     }
     $response = $this->getRepository()->getBy($args);
     return new JsonResponse($response);
   }

    /**
     * Add Category
     *
     * [POST]
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
    public function modifyAction(Request $request, int $id)
    {
      $entity = $this->getRepository()->find($id);
      if (empty($entity)) {
        return new JsonResponse(false);
      }
      else {
        foreach ($request->request as $field => $value) {
          $field = ucfirst($field);
          $method = 'set'.$field;
          if ($field == 'Family' OR $field == 'Color' OR $field == 'Size') {
            $repository = $this->getManager()->getRepository('TrailWarehouseAppBundle:'.$field);
            $value = $repository->find($value);
          }
          $entity->$method($value);
        }
        $entity->generateRef();
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
