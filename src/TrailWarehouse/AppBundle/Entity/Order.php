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

    /**
     * toString
     */
    public function __toString()
    {
      return $this->id . " - " . $this->creationDate->format('Y F d H:i:s');
    }

    public function __construct()
    {
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
     * Set address
     *
     * @param \TrailWarehouse\AppBundle\Entity\Address $address
     *
     * @return Order
     */
    public function setAddress(\TrailWarehouse\AppBundle\Entity\Address $address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return \TrailWarehouse\AppBundle\Entity\Address
     */
    public function getAddress()
    {
        return $this->address;
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
}
