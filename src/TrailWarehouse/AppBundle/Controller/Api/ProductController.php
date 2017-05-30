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
      $entity_not_found = empty($db_entity = $this->getRepository()->findByRef($entity->getRef()));
      if ($entity_not_found) {
        $this->persistOne($entity);
        return new JsonResponse(true);
      }
      return new JsonResponse(false);
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
      $entity_not_found = empty($entity = $this->getRepository()->find($id));
      // If entity not found => Do nothing
      if ($entity_not_found) {
        return new JsonResponse(false);
      }
      // Else...
      else {
        // Loop over the POST data
        foreach ($request->request as $field => $value) {
          $field = ucfirst($field);
          $set = 'set'.$field;
          // If the provided field match with a setter
          if (method_exists($this, $set)) {
            // If the field is an entity
            if ($field == 'Family' OR $field == 'Color' OR $field == 'Size') {
              $value = $this->getManager()->getRepository('TrailWarehouseAppBundle:'.$field)->find($value);
            }
            $entity->$set($value); // Using the setter
          }
        }
        $entity->generateRef(); // Update the ref
        $this->persistOne($entity);
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
      $entity_not_found = empty($entity = $this->getRepository()->find($id));
      if ($entity_not_found) {
        return new JsonResponse(false);
      }
      else {
        $this->removeOne($entity);
        return new JsonResponse(true);
      }
    }
}
