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
        if (empty($entities[$field])) {
          return new JsonResponse($field ." ". $request->request->get($field) ." doesn't exists !");
        }
      }
      $entity
        ->setFamily($entities['family'])
        ->setColor($entities['color'])
        ->setSize($entities['size'])
        ->setPrice($request->request->get('price'))
        ->setStock($request->request->get('stock'))
        ->generateRef()
      ;
      $entity_not_found = empty($db_entity = $this->getRepository()->getOneBy('ref', $entity->getRef()));
      if ($entity_not_found) {
        $this->persistOne($entity);
        return new JsonResponse(["message" => "Product added"]);
      }
      return new JsonResponse(["message" => "Product already exists"]);
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
      $product_not_found = empty($product = $this->getRepository()->find($id));
      // If product not found => Do nothing
      if ($product_not_found) {
        return new JsonResponse("Product not found");
      }
      // Else...
      else {
        // Loop over the POST data
        foreach ($request->request as $field => $value) {
          $field = ucfirst($field);
          $set = 'set'.$field;
          // If the provided field match with a setter
          if (method_exists($product, $set)) {
            // If the field is an entity
            if ($field == 'Family' OR $field == 'Color' OR $field == 'Size') {
              $value = $this->getManager()->getRepository('TrailWarehouseAppBundle:'.$field)->find($value);
            }
            $product->$set($value); // Using the setter
          }
        }
        $product->generateRef(); // Update the ref
        $product_already_exists = !empty($this->getRepository()->findByRef($product->getRef()));
        if($product_already_exists) {
          return new JsonResponse("Product already exists | Réf = '". $product->getRef() ."'");
        }
        $this->persistOne($product);
        return new JsonResponse("Product successfully modified");
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
