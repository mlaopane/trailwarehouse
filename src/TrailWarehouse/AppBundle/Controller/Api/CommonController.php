<?php

namespace TrailWarehouse\AppBundle\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

abstract class CommonController extends Controller
{
    private $serializer;
    private $entity_name;
    private $entity;

    public function __construct() {
      // Serializer
      $normalizers = [new ObjectNormalizer()];
      $encoders = [new JsonEncoder()];
      $this->serializer = new Serializer($normalizers, $encoders);

      // Entity Name
      $search = [__NAMESPACE__, '\\', 'Controller'];
      $this->entity_name = str_replace($search, '', static::class);

      // Entity
      $full_entity_name = '\\TrailWarehouse\\AppBundle\\Entity\\'. $this->entity_name;
      $this->entity = new $full_entity_name();
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

    /**
     * @access protected
     *
     * @return String encoded object
     */
    protected function serialize($object) {
      return $this->serializer->serialize($object, 'json');
    }
}
