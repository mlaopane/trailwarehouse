<?php

namespace TrailWarehouse\AppBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use TrailWarehouse\AppBundle\Entity\Product;
use TrailWarehouse\AppBundle\Controller\Api\CommonController;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class ProductController extends CommonController
{

  public function __construct(EntityManagerInterface $em)
  {
    $this->repo = [
      'product' => $em->getRepository('TrailWarehouseAppBundle:Product'),
    ];
  }

  /**
   * getBy
   * @param int family
   * @param int color
   * @param int size
   */
  public function getByAction(int $family, int $color = NULL, int $size = NULL, EntityManagerInterface $em) {
    $args_name = ['family', 'color', 'size'];
    $ids = func_get_args();

    foreach ($ids as $i => $id) {
      $field = $args_name[$i];
      $args[$field] = $em
        ->getRepository('TrailWarehouseAppBundle:'.ucfirst($field))
        ->find($id)
      ;
    }
    $response = $this->getRepository()->getByArray($args);
    return new JsonResponse($response);
  }

  /**
   * Get by Family
   * @param int family
   */
  public function getByFamilyAction(int $family) {
    $parameters = array_combine(['family'], func_get_args());
    return new JsonResponse($this->repo['product']->getByArray($parameters));
  }

  /**
   * Get by Family & Color
   * @param int family
   * @param int color
   */
  public function getByColorAction(int $family, int $color) {
   $parameters = array_combine(['family', 'color'], func_get_args());
   return new JsonResponse($this->repo['product']->getByArray($parameters));
  }

  /**
   * Get by Family & Size
   * @param int family
   * @param int size
   */
  public function getBySizeAction(int $family, int $size) {
    $parameters = array_combine(['family', 'size'], func_get_args());
    return new JsonResponse($this->repo['product']->getByArray($parameters));
  }



  /**
   * getBy
   */
  public function getOneRandAction() {
    $response = $this->getRepository()->getOneRand();
    return new JsonResponse($response);
  }

  /**
   * Get the best product of a family
   *
   * [GET]
   *
   * @param int $family_id
   */
  public function getBestAction(int $family_id, EntityManagerInterface $em) {
    $family = $em->getRepository('TrailWarehouseAppBundle:Family')->find($family_id);
    $response = $this->getRepository()->getBest($family);
    return new JsonResponse($response);
  }

  /**
   * Add Category
   *
   * [POST]
   *
   * @return JsonResponse
   */
  public function addAction(Request $request, EntityManagerInterface $em) {
    $entity = $this->getEntity();
    $entities = [];
    $fields = ['family', 'color', 'size'];
    foreach ($fields as $field) {
      $entities[$field] = $em
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
  public function modifyAction(Request $request, int $id, EntityManagerInterface $em) {
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
            $value = $em->getRepository('TrailWarehouseAppBundle:'.$field)->find($value);
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
  public function removeAction(int $id, EntityManagerInterface $em) {
    $entity_not_found = empty($entity = $this->getRepository()->find($id));
    if ($entity_not_found) {
      return new JsonResponse(false);
    }
    else {
      $this->removeOne($entity);
      return new JsonResponse(true);
    }
  }

  /* ----- Protected Methods ----- */

}
