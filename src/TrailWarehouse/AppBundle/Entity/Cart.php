<?php

namespace TrailWarehouse\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use TrailWarehouse\AppBundle\Entity\Item;
use TrailWarehouse\AppBundle\Entity\Promo;

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
     * @var Promo
     *
     */
    private $promo;

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
        $item->setTotal($item->getProduct()->getPrice() * $item->getQuantity());
        $this->items[] = $item;
        $this->updateTotal();
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
        $this->updateTotal();
        return $this;
    }

    /**
     * Get Items
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

    /**
     * Set promo
     *
     * @param Promo $promo
     *
     * @return Cart
     */
    public function setPromo(Promo $promo)
    {
        $this->promo = $promo;
        $this->recalcTotal();
        return $this;
    }

    /**
     * Get promo
     *
     * @return Promo
     */
    public function getPromo()
    {
        return $this->promo;
    }

    public function updateTotal()
    {
      // Reset the total
      $this->total = 0;

      // IF the Cart has items
      if (!empty($this->items)) {
        $iterator = $this->items->getIterator();

        // Update the total with the items' totals
        while ($iterator->valid()) {
          $this->total += $iterator->current()->getTotal();
          $iterator->next();
        }

        // IF the cart has a promo code
        if (!empty($this->promo)) {
          $this->total *= $this->promo->getValue();
        }
      }

      return $this;
    }

}
