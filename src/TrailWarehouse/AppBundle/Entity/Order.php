<?php

namespace TrailWarehouse\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use TrailWarehouse\AppBundle\Entity\User;
use TrailWarehouse\AppBundle\Entity\Product;
use TrailWarehouse\AppBundle\Entity\Address;
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
     * @ORM\ManyToOne(targetEntity="TrailWarehouse\AppBundle\Entity\User", inversedBy="orders")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @var string $lastname
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $lastname;

    /**
     * @var string $lastname
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $firstname;

    /**
     * @var string $street
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $street;

    /**
     * @var string $zipcode
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $zipcode;

    /**
     * @var string $city
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $city;

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
     * @var float
     *
     * @ORM\Column(name="reduction", type="decimal", precision=4, scale=2, nullable=true)
     */
    private $reduction;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="decimal", precision=8, scale=2)
     */
    private $total;

    /**
     * toString
     */
    public function __toString()
    {
      return $this->id . " - " . $this->creationDate->format('Y F d H:i:s');
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
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Order
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Order
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set street
     *
     * @param string $street
     *
     * @return Order
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     *
     * @return Order
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Order
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
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
     * Set reduction
     *
     * @param string $reduction
     *
     * @return Order
     */
    public function setReduction($reduction)
    {
        $this->reduction = $reduction;

        return $this;
    }

    /**
     * Get reduction
     *
     * @return string
     */
    public function getReduction()
    {
        return $this->reduction;
    }

    /**
     * Set total
     *
     * @param string $total
     *
     * @return Order
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return string
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set user
     *
     * @param \TrailWarehouse\AppBundle\Entity\User $user
     *
     * @return Order
     */
    public function setUser(\TrailWarehouse\AppBundle\Entity\User $user = null)
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
