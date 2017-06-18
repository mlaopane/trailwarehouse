<?php

namespace TrailWarehouse\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use TrailWarehouse\AppBundle\Entity\Item;

/**
 * Cart
 */
class Cart
{
    /**
     * @var ArrayCollection
     */
    private $items;

    /**
     * @var integer $total
     *
     */
    private $total = 0;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
    }


    /* ---------- Getters & Setters ---------- */

    /**
     * Add item
     *
     * @param Item $item
     *
     * @return Cart
     */
    public function addItem(Item $item)
    {
        // Update Cart Items
        $item->setTotal($item->getProduct()->getPrice() * $item->getQuantity());
        $this->items[] = $item;

        // Update Cart total
        $this->total += $item->getTotal();

        return $this;
    }

    /**
     * Remove item
     *
     * @param Item $item
     */
    public function removeItem(Item $item)
    {
        $this->items->removeElement($item);
        $this->total -= $item->getTotal();
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set total
     *
     * @param integer $total
     *
     * @return Cart
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
