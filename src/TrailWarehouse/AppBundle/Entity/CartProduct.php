<?php

namespace TrailWarehouse\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TrailWarehouse\AppBundle\Entity\Product;

/**
 * Cart
 *
 */
class CartProduct
{
    /**
     * @var Product $product
     *
     */
    private $product;

    /**
     * @var int $quantity
     *
     */
    private $quantity;

    /**
     * @var int $total
     *
     */
    private $total;
}
