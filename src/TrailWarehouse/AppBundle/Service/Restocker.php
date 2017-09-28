<?php

namespace TrailWarehouse\AppBundle\Service;

use TrailWarehouse\AppBundle\Service\WhatDate;
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
        $actual_month = $this->whatDate->getMonth();
        $product_whatDate = new WhatDate();

        foreach ($products as $product) {
            $product_whatDate->setDateTime($product->getLastStockUpdate());
            if ((int) $product_whatDate->getMonth() < (int) $actual_month) {
                $product->setStock($stock);
                $this->em->persist($product);
            }
        }
        $this->em->flush();

        return $this;
    }

}
