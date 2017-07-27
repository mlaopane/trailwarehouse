<?php

namespace TrailWarehouse\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
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
 * @ORM\HasLifecycleCallbacks()
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="TrailWarehouse\AppBundle\Entity\User", inversedBy="orders")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $user;

    /**
     * @var Address
     *
     * @ORM\ManyToOne(targetEntity="TrailWarehouse\AppBundle\Entity\Address")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * @Assert\NotNull()
     */
    private $address;

    /**
     * @var Promo
     *
     * @ORM\ManyToOne(targetEntity="TrailWarehouse\AppBundle\Entity\Promo")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $promo;

    /**
     * @var Vat
     *
     */
    private $vat;

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
     * @var float
     *
     * @ORM\Column(name="vat_value", type="decimal", precision=5, scale=2)
     */
    private $vatValue;

    /**
     * @var float
     *
     * @ORM\Column(name="promo_value", type="decimal", precision=4, scale=2, nullable=true)
     */
    private $promoValue;

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
     * @ORM\Column(name="base_total", type="decimal", precision=8, scale=2)
     */
    private $baseTotal;

    /**
     * @var float
     *
     * @ORM\Column(name="final_total", type="decimal", precision=8, scale=2)
     */
    private $finalTotal;

    /**
     * toString
     */
    public function __toString()
    {
      return $this->id . " - " . $this->creationDate->format('Y F d H:i:s');
    }


    /* ---------- Callbacks ---------- */

    /**
     * @ORM\PrePersist
     */
    public function updateUserData()
    {
      $this->firstname = $this->user->getFirstname();
      $this->lastname  = $this->user->getLastname();
      $this->email     = $this->user->getEmail();
    }

    /**
     * @ORM\PrePersist
     */
    public function updateAddressData()
    {
      $this->street  = $this->address->getStreet();
      $this->zipcode = $this->address->getZipcode();
      $this->city    = $this->address->getCity();

    }

    /**
     * @ORM\PrePersist
     */
    public function updatePromoData()
    {
      if (!empty($this->promo)) {
        $this->promoValue = $this->promo->getValue();
      }
    }

    /**
     * @ORM\PrePersist
     */
    public function updateVatData()
    {
      if (!empty($this->vat)) {
        $this->vatValue = $this->vat->getValue();
      }
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
     * Set vatValue
     *
     * @param string $vatValue
     *
     * @return Order
     */
    public function setVatValue($vatValue)
    {
        $this->vatValue = $vatValue;

        return $this;
    }

    /**
     * Get vatValue
     *
     * @return string
     */
    public function getVatValue()
    {
        return $this->vatValue;
    }

    /**
     * Set promoValue
     *
     * @param string $promoValue
     *
     * @return Order
     */
    public function setPromoValue($promoValue)
    {
        $this->promoValue = $promoValue;

        return $this;
    }

    /**
     * Get promoValue
     *
     * @return string
     */
    public function getPromoValue()
    {
        return $this->promoValue;
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
     * Set baseTotal
     *
     * @param string $baseTotal
     *
     * @return Order
     */
    public function setBaseTotal($baseTotal)
    {
        $this->baseTotal = $baseTotal;

        return $this;
    }

    /**
     * Get baseTotal
     *
     * @return string
     */
    public function getBaseTotal()
    {
        return $this->baseTotal;
    }

    /**
     * Set finalTotal
     *
     * @param string $finalTotal
     *
     * @return Order
     */
    public function setFinalTotal($finalTotal)
    {
        $this->finalTotal = $finalTotal;

        return $this;
    }

    /**
     * Get finalTotal
     *
     * @return string
     */
    public function getFinalTotal()
    {
        return $this->finalTotal;
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

    /**
     * Set address
     *
     * @param \TrailWarehouse\AppBundle\Entity\Address $address
     *
     * @return Order
     */
    public function setAddress(\TrailWarehouse\AppBundle\Entity\Address $address = null)
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
     * Set vat
     *
     * @param \TrailWarehouse\AppBundle\Entity\Vat $vat
     *
     * @return Order
     */
    public function setVat(\TrailWarehouse\AppBundle\Entity\Vat $vat = null)
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * Get vat
     *
     * @return \TrailWarehouse\AppBundle\Entity\Vat
     */
    public function getVat()
    {
        return $this->vat;
    }
}
