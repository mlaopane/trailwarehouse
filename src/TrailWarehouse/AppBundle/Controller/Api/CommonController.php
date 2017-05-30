<?php

namespace TrailWarehouse\AppBundle\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

abstract class CommonController extends Controller
{
    private $entity_name;
    private $entity;

    public function __construct() {
      // Entity Name
      $search = [__NAMESPACE__, '\\', 'Controller'];
      $this->entity_name = str_replace($search, '', static::class);

      // Entity
      $full_entity_name = '\\TrailWarehouse\\AppBundle\\Entity\\'. $this->entity_name;
      $this->entity = new $full_entity_name();
    }


    /**
     * GET All Categories
     *
     * @return JsonResponse
     */
    public function getAllAction()
    {
      $response = $this->getRepository()->getAll();
      return new JsonResponse($response);
    }

    /**
     * GET Entity
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function getAction(int $id)
    {
      $response = $this->getRepository()->get($id);
      return new JsonResponse($response);
    }

    /**
     * @access protected
     *
     * @return EntityManager
     */
    protected function getManager() {
      return $this
        ->getDoctrine()
        ->getManager()
      ;
    }

    /**
     * @access protected
     *
     * @return Repository
     */
    protected function getRepository() {
      return $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('TrailWarehouseAppBundle:'. $this->entity_name)
      ;
    }

    /**
     * @access protected
     *
     * @return String
     */
    protected function getEntityName() {
      return $this->entity_name;
    }

    /**
     * @access protected
     *
     * @return Entity
     */
    protected function getEntity() {
      return $this->entity;
    }
    
}
