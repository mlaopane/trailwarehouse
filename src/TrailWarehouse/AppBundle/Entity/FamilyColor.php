<?php

namespace TrailWarehouse\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use TrailWarehouse\AppBundle\Entity\Family;
use TrailWarehouse\AppBundle\Entity\Color;


/**
 * FamilyColor
 *
 * @ORM\Table(name="`family_color`")
 * @ORM\Entity(repositoryClass="TrailWarehouse\AppBundle\Repository\FamilyColorRepository")
 * @UniqueEntity(
 *  fields = {"family", "color"},
 *  message = "Cette Couleur existe déjà pour cette Famille"
 * )
 * @Vich\Uploadable
 */
class FamilyColor
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
     * @var Family
     *
     * @ORM\ManyToOne(targetEntity="Family", inversedBy="visuels")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $family;

    /**
     * @var Color
     *
     * @ORM\ManyToOne(targetEntity="Color")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $color;

    /**
    * @var string
    *
    * @ORM\Column(type="string", length=255)
    */
    private $imageName;

    /**
    * @var File
    * @Vich\UploadableField(mapping="family_color_image", fileNameProperty="imageName")
    */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedDate;


    /**
     * toString
     */
    public function __toString()
    {
      return $this->family->getSlug() . "_" . $this->color->getSlug() . "_" . $this->id;
    }


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
     * Set family
     *
     * @param \TrailWarehouse\AppBundle\Entity\Family $family
     *
     * @return FamilyColor
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
     * @param Color $color
     *
     * @return FamilyColor
     */
    public function setColor(Color $color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return Color
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * setImageFile
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedDate = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
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
     * @return string|null
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Set updatedDate
     *
     * @param \DateTime $updatedDate
     *
     * @return Brand
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->$updatedDate = $updatedDate;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }
}
