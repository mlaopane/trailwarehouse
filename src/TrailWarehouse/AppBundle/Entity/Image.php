<?php

namespace TrailWarehouse\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use TrailWarehouse\AppBundle\Entity\Color;
use TrailWarehouse\AppBundle\Entity\Family;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="TrailWarehouse\AppBundle\Repository\ImageRepository")
 */
class Image
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
     * @ORM\Column(name="title", type="string", length=191)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="src", type="string", length=191, unique=true)
     */
    private $src;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=191)
     */
    private $alt;

    /**
     * @var Family
     *
     * @ORM\ManyToOne(targetEntity="Family")
     * @ORM\JoinColumn(nullable=false)
     */
    private $family;

    /**
     * @var Color
     *
     * @ORM\ManyToOne(targetEntity="Color")
     * @ORM\JoinColumn(nullable=false)
     */
    private $color;


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
     * Set title
     *
     * @param string $title
     *
     * @return Image
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set src
     *
     * @param string $src
     *
     * @return Image
     */
    public function setSrc($src)
    {
        $this->src = $src;

        return $this;
    }

    /**
     * Get src
     *
     * @return string
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set families
     *
     * @param \TrailWarehouse\AppBundle\Entity\Family $families
     *
     * @return Image
     */
    public function setFamilies(\TrailWarehouse\AppBundle\Entity\Family $families)
    {
        $this->families = $families;

        return $this;
    }

    /**
     * Get families
     *
     * @return \TrailWarehouse\AppBundle\Entity\Family
     */
    public function getFamilies()
    {
        return $this->families;
    }

    /**
     * Set colors
     *
     * @param \TrailWarehouse\AppBundle\Entity\Color $colors
     *
     * @return Image
     */
    public function setColors(\TrailWarehouse\AppBundle\Entity\Color $colors)
    {
        $this->colors = $colors;

        return $this;
    }

    /**
     * Get colors
     *
     * @return \TrailWarehouse\AppBundle\Entity\Color
     */
    public function getColors()
    {
        return $this->colors;
    }

    /**
     * Set family
     *
     * @param \TrailWarehouse\AppBundle\Entity\Family $family
     *
     * @return Image
     */
    public function setFamily(\TrailWarehouse\AppBundle\Entity\Family $family)
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
     * @return Image
     */
    public function setColor(\TrailWarehouse\AppBundle\Entity\Color $color)
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
}
