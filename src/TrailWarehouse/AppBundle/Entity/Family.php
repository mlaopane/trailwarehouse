<?php

namespace TrailWarehouse\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use TrailWarehouse\AppBundle\Entity\Brand;
use TrailWarehouse\AppBundle\Entity\Category;
use TrailWarehouse\AppBundle\Entity\Product;
use TrailWarehouse\AppBundle\Entity\Color;
use TrailWarehouse\AppBundle\Entity\Image;

/**
 * Family
 *
 * @ORM\Table(name="family")
 * @ORM\Entity(repositoryClass="TrailWarehouse\AppBundle\Repository\FamilyRepository")
 * @UniqueEntity(
 *  fields = {"brand", "name"},
 *  message = "Famille déjà existante pour cette Marque"
 * )
 */
class Family
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="TrailWarehouse\AppBundle\Entity\Brand")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $brand;

    /**
     * @ORM\ManyToOne(targetEntity="TrailWarehouse\AppBundle\Entity\Category")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $category;

    /**
    * @var string
    *
    * @Gedmo\Slug(fields={"name"})
    * @ORM\Column(name="slug", type="string", length=191)
    *
    */
    private $slug;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="FamilyColor", mappedBy="family")
     */
    private $visuels;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Product", mappedBy="family")
     */
    private $products;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Review", mappedBy="family")
     */
    private $reviews;

    /**
     * @var string
     *
     * @ORM\Column(name="average_rating", type="decimal", precision=2, scale=1)
     */
    private $averageRating = 0;

    /**
     * toString
     */
    public function __toString()
    {
      return "[" . $this->brand->getName() . "] " . $this->name;
    }

    /**
    * Constructor
    */
    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->reviews  = new ArrayCollection();
        $this->colors   = new ArrayCollection();
    }

    /**
     * Generate & Set the slug
     *
     * @return Family
     */
    public function generateSlug()
    {
      $slugs['family'] = $this->name;
      $slugs['brand'] = $this->brand->getName();
      $slug = $this->name.'-'.$this->brand->getName();
      $this->setSlug($slug);

      return $this;
    }

    /**
     * Used in 'Review:updateFamily' Callback (PostPersist)
     */
    public function updateAverageRating()
    {
      if (!empty($this->reviews)) {
        $sum = 0;
        foreach ($this->reviews as $review) {
          $sum += $review->getRating();
        }
        $this->averageRating = $sum / count($this->reviews);
      }
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Family
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
    * Set description
    *
    * @param string $description
    *
    * @return Family
    */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
    * Get description
    *
    * @return string
    */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set brand
     *
     * @param Brand $brand
     *
     * @return Family
     */
    public function setBrand(Brand $brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set category
     *
     * @param Category $category
     *
     * @return Family
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \TrailWarehouse\AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }


    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Family
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add product
     *
     * @param Product $product
     *
     * @return Family
     */
    public function addProduct(Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param Product $product
     */
    public function removeProduct(Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Add review
     *
     * @param \TrailWarehouse\AppBundle\Entity\Review $review
     *
     * @return Family
     */
    public function addReview(\TrailWarehouse\AppBundle\Entity\Review $review)
    {
        $this->reviews[] = $review;

        return $this;
    }

    /**
     * Remove review
     *
     * @param \TrailWarehouse\AppBundle\Entity\Review $review
     */
    public function removeReview(\TrailWarehouse\AppBundle\Entity\Review $review)
    {
        $this->reviews->removeElement($review);
    }

    /**
     * Get reviews
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * Set averageRating
     *
     * @param string $averageRating
     *
     * @return Family
     */
    public function setAverageRating($averageRating)
    {
        $this->averageRating = $averageRating;

        return $this;
    }

    /**
     * Get averageRating
     *
     * @return string
     */
    public function getAverageRating()
    {
        return $this->averageRating;
    }

    /**
     * Add visuel
     *
     * @param \TrailWarehouse\AppBundle\Entity\FamilyColor $visuel
     *
     * @return Family
     */
    public function addVisuel(\TrailWarehouse\AppBundle\Entity\FamilyColor $visuel)
    {
        $this->visuels[] = $visuel;

        return $this;
    }

    /**
     * Remove visuel
     *
     * @param \TrailWarehouse\AppBundle\Entity\FamilyColor $visuel
     */
    public function removeVisuel(\TrailWarehouse\AppBundle\Entity\FamilyColor $visuel)
    {
        $this->visuels->removeElement($visuel);
    }

    /**
     * Get visuels
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVisuels()
    {
        return $this->visuels;
    }
}
