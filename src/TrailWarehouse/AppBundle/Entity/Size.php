<?php

namespace TrailWarehouse\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use TrailWarehouse\AppBundle\Entity\Category;

/**
 * Size
 *
 * @ORM\Table(name="size")
 * @ORM\Entity(repositoryClass="TrailWarehouse\AppBundle\Repository\SizeRepository")
 * @UniqueEntity("value")
 */
class Size
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
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="TrailWarehouse\AppBundle\Entity\Category")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=100)
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="unit", type="string", length=50, nullable=true)
     */
    private $unit;

    /**
     * @var string
     *
     * @ORM\Column(name="unit_shortcut", type="string", length=5, nullable=true)
     */
    private $unitShortcut;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"value"})
     * @ORM\Column(name="slug", type="string", length=191, unique=true)
     *
     */
    private $slug;

    /**
     * toString
     */
    public function __toString()
    {
      return $this->value . html_entity_decode("&nbsp;") . (string) $this->unitShortcut;
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
     * Set value
     *
     * @param string $value
     *
     * @return Size
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set unit
     *
     * @param string $unit
     *
     * @return Size
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set unitShortcut
     *
     * @param string $unitShortcut
     *
     * @return Size
     */
    public function setUnitShortcut($unitShortcut)
    {
        $this->unitShortcut = $unitShortcut;

        return $this;
    }

    /**
     * Get unitShortcut
     *
     * @return string
     */
    public function getUnitShortcut()
    {
        return $this->unitShortcut;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Size
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
}
