<?php

namespace TrailWarehouse\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TrailWarehouse\AppBundle\Entity\Color;
use TrailWarehouse\AppBundle\Entity\Family;
use TrailWarehouse\AppBundle\Entity\Size;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="TrailWarehouse\AppBundle\Repository\ProductRepository")
 */
class Product
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
     * @var string
     *
     * @ORM\Column(name="ref", type="string", length=191, unique=true)
     */
    private $ref;

    /**
     * @ORM\ManyToOne(targetEntity="TrailWarehouse\AppBundle\Entity\Family", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $family;

    /**
     * @ORM\ManyToOne(targetEntity="TrailWarehouse\AppBundle\Entity\Color", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $color;

    /**
     * @ORM\ManyToOne(targetEntity="TrailWarehouse\AppBundle\Entity\Size", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $size;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=7, scale=2)
     */
    private $price = 0.00;

    /**
     * @var int
     *
     * @ORM\Column(name="stock", type="integer")
     */
    private $stock = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="visuel", type="string", length=255, nullable=true)
     */
    private $visuel;


    /* ----- Events ----- */

    /**
     * Generate ref
     *
     * @return Product
     */
    public function generateRef()
    {
      $slug['family'] = $this->getFamily()->getSlug();
      $slug['size'] = $this->getSize()->getSlug();
      $slug['color'] = $this->getColor()->getSlug();
      $ref = $slug['family'];
      $ref .= empty($slug['size']) ? '' : '-'.$slug['size'];
      $ref .= empty($slug['color']) ? '' : '-'.$slug['color'];
      $this->setRef($ref);

      return $this;
    }

    /* ----- Getters & Setters ----- */

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
     * Set ref
     *
     * @param string $ref
     *
     * @return Product
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * Get ref
     *
     * @return string
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set stock
     *
     * @param integer $stock
     *
     * @return Product
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return integer
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set visuel
     *
     * @param string $visuel
     *
     * @return Product
     */
    public function setVisuel($visuel)
    {
        $this->visuel = $visuel;

        return $this;
    }

    /**
     * Get visuel
     *
     * @return string
     */
    public function getVisuel()
    {
        return $this->visuel;
    }

    /**
     * Set family
     *
     * @param \TrailWarehouse\AppBundle\Entity\Family $family
     *
     * @return Product
     */
    public function setFamily(Family $family)
    {
        $this->family = $family;

        return $this;
    }

    /**
     * Get family
     *
     * @return \TrailWarehouse\AppBundle\Entity\Family
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * Set color
     *
     * @param \TrailWarehouse\AppBundle\Entity\Color $color
     *
     * @return Product
     */
    public function setColor(Color $color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return \TrailWarehouse\AppBundle\Entity\Color
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set size
     *
     * @param \TrailWarehouse\AppBundle\Entity\Size $size
     *
     * @return Product
     */
    public function setSize(Size $size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return \TrailWarehouse\AppBundle\Entity\Size
     */
    public function getSize()
    {
        return $this->size;
    }
}
