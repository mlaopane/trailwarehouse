<?php

namespace TrailWarehouse\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Cart
 *
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
     * @var Vat
     *
     */
    private $vat;

    /**
     * @var float
     *
     */
    private $baseTotal = 0.00;

    /**
     * @var float
     *
     */
    private $finalTotal = 0.00;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function updateTotal()
    {
      // Reset the total & baseTotal
      $this->baseTotal  = 0;
      $this->finalTotal = 0;

      // IF the Cart has items
      if ($this->items)
      {
        $iterator = $this->items->getIterator();
        // Update the total with the items' totals
        while ($iterator->valid()) {
          $this->baseTotal += $iterator->current()->getTotal();
          $iterator->next();
        }
        // IF there is a promo code to apply
        if ($this->promo) {
          $this->baseTotal *= (1 - $this->promo->getValue());
        }
        // Calculate the total including VAT
        $this->finalTotal = $this->baseTotal * (1 + $this->vat->getValue());
      }
      return $this;
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
     * Set promo
     *
     * @param Promo $promo
     *
     * @return Cart
     */
    public function setPromo(Promo $promo)
    {
        $this->promo = $promo;
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

    /**
     * Set promo
     *
     * @param Vat $vat
     *
     * @return Cart
     */
    public function setVat(Vat $vat)
    {
        $this->vat = $vat;
        return $this;
    }

    /**
     * Get vat
     *
     * @return Vat
     */
    public function getVat()
    {
        return $this->vat;
    }


    /**
     * @param float $baseTotal
     * @return Cart
     */
    public function setBaseTotal($baseTotal)
    {
        $this->baseTotal = $baseTotal;
    }


    /**
     * @return float
     */
    public function getBaseTotal()
    {
        return $this->baseTotal;
    }

    /**
     * @param float $finalTotal
     * @return Cart
     */
    public function setFinalTotal($finalTotal)
    {
        $this->finalTotal = $finalTotal;
    }

    /**
     * @return float
     */
    public function getFinalTotal()
    {
        return $this->finalTotal;
    }

}
