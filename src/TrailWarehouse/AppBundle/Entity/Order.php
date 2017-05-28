<?php

namespace TrailWarehouse\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use TrailWarehouse\AppBundle\Entity\Member;
use TrailWarehouse\AppBundle\Entity\Product;
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
     * @var Member $member
     *
     * @ORM\ManyToOne(targetEntity="TrailWarehouse\AppBundle\Entity\Member")
     * @ORM\JoinColumn(nullable=false)
     */
    private $member;

    /**
     * @var int $total
     *
     * @ORM\Column(name="total", type="integer")
     */
    private $total;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation", type="datetime")
     */
    private $creation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sending", type="datetime")
     */
    private $sending;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="delivery", type="datetime")
     */
    private $delivery;


    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="TrailWarehouse\AppBundle\Entity\OrderProduct", mappedBy="order")
     */
    private $orderProducts;


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
     * Set member
     *
     * @param Member $member
     *
     * @return Order
     */
    public function setMember(Member $member)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return Member
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
    * Add orderProduct
    *
    * @param OrderProduct
    */
    public function addOrderProduct(OrderProduct $orderProduct)
    {
        $this->orderProducts[] = $orderProduct;

        return $this;
    }

    /**
    * Remove orderProduct
    *
    * @param OrderProduct $orderProduct
    */
    public function removeOrderProduct(OrderProduct $orderProduct)
    {
        $this->orderProducts->removeElement($orderProduct);

        return $this;
    }

    /**
    * Get orderProducts
    *
    * @return ArrayCollection
    */
    public function getOrderProducts()
    {
        return $this->orderProducts;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->orderProducts = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set creation
     *
     * @param \DateTime $creation
     *
     * @return Order
     */
    public function setCreation($creation)
    {
        $this->creation = $creation;

        return $this;
    }

    /**
     * Get creation
     *
     * @return \DateTime
     */
    public function getCreation()
    {
        return $this->creation;
    }

    /**
     * Set sending
     *
     * @param \DateTime $sending
     *
     * @return Order
     */
    public function setSending($sending)
    {
        $this->sending = $sending;

        return $this;
    }

    /**
     * Get sending
     *
     * @return \DateTime
     */
    public function getSending()
    {
        return $this->sending;
    }

    /**
     * Set delivery
     *
     * @param \DateTime $delivery
     *
     * @return Order
     */
    public function setDelivery($delivery)
    {
        $this->delivery = $delivery;

        return $this;
    }

    /**
     * Get delivery
     *
     * @return \DateTime
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * Set total
     *
     * @param integer $total
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
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }
}
