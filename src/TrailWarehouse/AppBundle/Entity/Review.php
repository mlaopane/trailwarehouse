<?php

namespace TrailWarehouse\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TrailWarehouse\AppBundle\Entity\Family;

/**
 * Rating
 *
 * @ORM\Table(name="review")
 * @ORM\Entity(repositoryClass="TrailWarehouse\AppBundle\Repository\ReviewRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Review
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="TrailWarehouse\AppBundle\Entity\User", cascade={"persist"}, inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var Family
     *
     * @ORM\ManyToOne(targetEntity="TrailWarehouse\AppBundle\Entity\Family", cascade={"persist"}, inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private $family;

    /**
     * @var string
     *
     * @ORM\Column(name="commentary", type="string", length=255)
     */
    private $commentary;

    /**
     * @var int
     *
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime")
     */
    private $creationDate;


    /**
     * @ORM\PostPersist
     */
    public function updateFamily()
    {
        $this->getFamily()->updateAverageRating();
    }


    /* ----- GETTERS & SETTERS ----- */

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
     * Set commentary
     *
     * @param string $commentary
     *
     * @return Review
     */
    public function setCommentary($commentary)
    {
        $this->commentary = $commentary;

        return $this;
    }

    /**
     * Get commentary
     *
     * @return string
     */
    public function getCommentary()
    {
        return $this->commentary;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     *
     * @return Review
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Review
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set family
     *
     * @param \TrailWarehouse\AppBundle\Entity\Family $family
     *
     * @return Review
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
     * Set user
     *
     * @param \TrailWarehouse\AppBundle\Entity\User $user
     *
     * @return Review
     */
    public function setUser(\TrailWarehouse\AppBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \TrailWarehouse\AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
