<?php

namespace TrailWarehouse\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TrailWarehouse\AppBundle\Entity\Product;
use TrailWarehouse\AppBundle\Entity\CartProduct;

/**
 * Cart
 *
 */
class Cart
{
    /**
     * @var Array $cartProducts
     *
     */
    private $cartProducts;

    /**
     * @var int $total
     *
     */
    private $total;
}
