<?php

namespace TrailWarehouse\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use TrailWarehouse\AppBundle\Entity\Color;
use TrailWarehouse\AppBundle\Entity\Family;
use TrailWarehouse\AppBundle\Entity\Size;


/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="TrailWarehouse\AppBundle\Repository\ProductRepository")
 * @UniqueEntity(
 *  fields = {"family", "color", "size"},
 *  message = "Ce produit existe déjà"
 * )
 * @ORM\HasLifecycleCallbacks
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=191)
     */
    private $name;

    /**
     * @var Family
     *
     * @ORM\ManyToOne(targetEntity="TrailWarehouse\AppBundle\Entity\Family", inversedBy="products")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $family;

    /**
     * @var Color
     *
     * @ORM\ManyToOne(targetEntity="TrailWarehouse\AppBundle\Entity\Color")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $color;

    /**
     * @var Size
     *
     * @ORM\ManyToOne(targetEntity="TrailWarehouse\AppBundle\Entity\Size")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $size;

    /**
    * @var string
    *
    * @ORM\Column(type="string", length=255, nullable=true)
    */
    private $imageName;

    /**
     * @var float
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
     * @var \DateTime
     *
     * @ORM\Column(name="last_stock_update", type="datetime")
     */
    private $lastStockUpdate;

    /**
     * toString
     */
    public function __toString()
    {
      return $this->id . " - " . $this->name;
    }

    /* ----- Events ----- */

    /**
     * Generate ref
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @return Product
     */
    public function generateRef()
    {
      $slug = [
        'family' => $this->getFamily()->getSlug(),
        'color'  => (!empty($color = $this->getColor())) ? ($color->getSlug()) : (''),
        'size'   => (!empty($size = $this->getSize())) ? ($size->getSlug()) : (''),
      ];

      $ref = $slug['family'];
      $ref .= empty($slug['size']) ? '' : '-'.$slug['size'];
      $ref .= empty($slug['color']) ? '' : '-'.$slug['color'];
      $this->setRef($ref);

      return $this;
    }

    /**
     * Generate name
     *
     * @ORM\PrePersist
     * @return Product
     */
    public function generateName()
    {
      if (empty($this->name))
      {
        $data = [
          'brand'  => ucfirst($this->getFamily()->getBrand()->getName()),
          'family' => ucfirst($this->getFamily()->getName()),
          'color'  => ucfirst($this->color->getName()),
          'size'   => ucfirst($this->size->getValue()),
          'unit'   => $this->size->getUnitShortcut(),
        ];

        $name = $data['brand']
          . " - " . $data['family']
          . " (" . $data['color'] . " | " . $data['size'] . $data['unit'] .")"
        ;

        $this->setName($name);
      }

      return $this;
    }

    /**
     * Generate name
     *
     * @ORM\PreUpdate
     * @return Product
     */
    public function updateLastStockUpdate()
    {
        $this->lastStockUpdate = new \DateTime();

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
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
    public function setColor(Color $color = NULL)
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
    public function setSize(Size $size = NULL)
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


    /**
     * Set imageName
     *
     * @param string $imageName
     *
     * @return Product
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get imageName
     *
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Get the value of Last Stock Update
     *
     * @return \DateTime
     */
    public function getLastStockUpdate()
    {
        return $this->lastStockUpdate;
    }

    /**
     * Set the value of Last Stock Update
     *
     * @param \DateTime lastStockUpdate
     *
     * @return self
     */
    public function setLastStockUpdate(\DateTime $lastStockUpdate)
    {
        $this->lastStockUpdate = $lastStockUpdate;

        return $this;
    }

}
