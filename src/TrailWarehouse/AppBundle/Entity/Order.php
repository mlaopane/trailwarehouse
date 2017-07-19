<?php

namespace TrailWarehouse\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use TrailWarehouse\AppBundle\Entity\User;
use TrailWarehouse\AppBundle\Entity\Product;
use TrailWarehouse\AppBundle\Entity\Coordinate;
use TrailWarehouse\AppBundle\Entity\OrderProduct;

/**
 * Order
 *
 * @ORM\Table(name="`order`")
 * @ORM\Entity(repositoryClass="TrailWarehouse\AppBundle\Repository\OrderRepository")
 */
class Order
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
     * @var User $user
     *
     * @ORM\ManyToOne(targetEntity="TrailWarehouse\AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var Coordinate $coordinate
     *
     * @ORM\ManyToOne(targetEntity="TrailWarehouse\AppBundle\Entity\Coordinate")
     * @ORM\JoinColumn(nullable=false)
     */
    private $coordinate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime")
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sending_date", type="datetime", nullable=true)
     */
    private $sendingDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="delivery_date", type="datetime", nullable=true)
     */
    private $deliveryDate;

    /**
     * @var Promo
     * @ORM\OneToOne(targetEntity="TrailWarehouse\AppBundle\Entity\Promo")
     * @ORM\JoinColumn(nullable=true)
     */
    private $promo;

    /**
     * @var int
     *
     * @ORM\Column(name="total", type="integer")
     */
    private $total;


    public function __construct()
    {
        $this->creationDate = new \DateTime();
    }

    /* ---------- Getters & Setters ---------- */

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
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Purchase
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Order
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Order
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
     * Set sendingDate
     *
     * @param \DateTime $sendingDate
     *
     * @return Order
     */
    public function setSendingDate($sendingDate)
    {
        $this->sendingDate = $sendingDate;

        return $this;
    }

    /**
     * Get sendingDate
     *
     * @return \DateTime
     */
    public function getSendingDate()
    {
        return $this->sendingDate;
    }

    /**
     * Set deliveryDate
     *
     * @param \DateTime $deliveryDate
     *
     * @return Order
     */
    public function setDeliveryDate($deliveryDate)
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    /**
     * Get deliveryDate
     *
     * @return \DateTime
     */
    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }

    /**
     * Set total
     *
     * @param int $total
     *
     * @return Order
     */
    public function setTotal(int $total)
    {
        $this->total = $total;
        return $this;
    }

    /**
     * Get total
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set promo
     *
     * @param \TrailWarehouse\AppBundle\Entity\Promo $promo
     *
     * @return Order
     */
    public function setPromo(\TrailWarehouse\AppBundle\Entity\Promo $promo = null)
    {
        $this->promo = $promo;

        return $this;
    }

    /**
     * Get promo
     *
     * @return \TrailWarehouse\AppBundle\Entity\Promo
     */
    public function getPromo()
    {
        return $this->promo;
    }

    /**
     * Set coordinate
     *
     * @param \TrailWarehouse\AppBundle\Entity\Coordinate $coordinate
     *
     * @return Order
     */
    public function setCoordinate(\TrailWarehouse\AppBundle\Entity\Coordinate $coordinate)
    {
        $this->coordinate = $coordinate;

        return $this;
    }

    /**
     * Get coordinate
     *
     * @return \TrailWarehouse\AppBundle\Entity\Coordinate
     */
    public function getCoordinate()
    {
        return $this->coordinate;
    }
}
