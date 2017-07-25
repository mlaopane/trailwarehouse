<?php

namespace TrailWarehouse\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TrailWarehouse\AppBundle\Entity\Order;
use TrailWarehouse\AppBundle\Entity\Product;

/**
 * OrderProduct
 *
 * @ORM\Table(name="`order_product`")
 * @ORM\Entity(repositoryClass="TrailWarehouse\AppBundle\Repository\OrderProductRepository")
 */
class OrderProduct
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Order $order
     *
     * @ORM\ManyToOne(targetEntity="Order")
     */
    private $order;

    /**
     * @var Product $product
     *
     * @ORM\ManyToOne(targetEntity="Product", cascade={"persist"})
     */
    private $product;

    /**
     * @var int $quantity
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var float $unitPrice
     *
     * @ORM\Column(name="unit_price", type="decimal", precision=7, scale=2)
     */
    private $unitPrice;

    /**
     * @var int $total
     *
     * @ORM\Column(name="total", type="integer")
     */
    private $total;


    /* ---------- Getters & Setters ---------- */

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Order_Product
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
     * Set order
     *
     * @param Order $order
     *
     * @return Order_Product
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set product
     *
     * @param Product $product
     *
     * @return Order_Product
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set total
     *
     * @param integer $total
     *
     * @return OrderProduct
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set unitPrice
     *
     * @param string $unitPrice
     *
     * @return OrderProduct
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * Get unitPrice
     *
     * @return string
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }
}
