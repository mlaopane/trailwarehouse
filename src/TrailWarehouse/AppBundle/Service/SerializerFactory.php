<?php

namespace TrailWarehouse\AppBundle\Service;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class SerializerFactory
{

    /**
     * [__construct description]
     */
    private function __construct() { }

    /**
     * Returns a Serializer to store entities in the session
     * @param  ObjectNormalizer[]      $normalizers [description]
     * @param  EncoderInterface[]      $encoders    [description]
     * @return Serializer              [description]
     */
    public static function create(array $normalizers = [], array $encoders = []): Serializer
    {
        $normalizer = (new ObjectNormalizer)
            ->setCircularReferenceHandler(function ($object) { return $object->getId(); }
        );

        $normalizers = $normalizers ?? [ $normalizer ];
        $encoders    = $encoders ?? [ new JsonEncoder() ];

        return new Serializer($normalizers, $encoders);
    }
}
