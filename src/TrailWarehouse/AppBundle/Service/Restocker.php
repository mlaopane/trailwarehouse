<?php

namespace TrailWarehouse\AppBundle\Service;

use TrailWarehouse\AppBundle\Service\WhatDate;
use TrailWarehouse\AppBundle\Entity\Action;
use Doctrine\ORM\EntityManagerInterface;

/**
 *
 */
class Restocker
{
    /**
     * Doctrine's entity manager
     *
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->whatDate = new WhatDate();
    }

    /**
     *
     * @param array products
     * @param int stock (default = 20)
     *
     * @return self
     */
    public function restock(array $products, $stock = 20): Restocker
    {
        foreach ($products as $product) {
            $this->em->persist($product->setStock($stock));
        }

        $this->em->persist(
            (new Action('restock'))
                ->setDate((new WhatDate(new \DateTime()))->getDateTime())
        );

        $this->em->flush();

        return $this;
    }

}
