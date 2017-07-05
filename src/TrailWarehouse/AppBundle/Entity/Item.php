<?php

namespace TrailWarehouse\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TrailWarehouse\AppBundle\Entity\Cart;
use TrailWarehouse\AppBundle\Entity\Product;

/**
 * Item
 *
 */
class Item
{
    /**
     * @var Product $product
     *
     * @ORM\OneToOne(targetEntity="Product")
     */
    private $product;

    /**
     * @var integer $quantity
     */
    private $quantity;

    /**
     * @var integer $total
     */
    private $total;

    /**
     * @var Cart $cart
     *
     * @ORM\ManyToOne(targetEntity="Cart", inversedBy="items")
     */
    private $cart;

    public function __construct(Product $product = null, int $quantity = null) {
      if ($product != null AND $quantity != null) {
        $this->product  = $product;
        $this->quantity = $quantity;
        $this->total    = $product->getPrice() * $quantity;
      }
    }

    /* ---------- Getters & Setters ---------- */

    /**
     * Set product
     *
     * @param Product $product
     *
     * @return Item
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get Product
     *
     * @return integer
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Item
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set total
     *
     * @param integer $total
     *
     * @return Item
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }
}
