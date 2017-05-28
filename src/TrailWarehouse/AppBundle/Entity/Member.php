<?php

namespace TrailWarehouse\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use TrailWarehouse\AppBundle\Entity\Coordinate;

/**
* Member
*
* @ORM\Table(name="member")
* @ORM\Entity(repositoryClass="TrailWarehouse\AppBundle\Repository\MemberRepository")
*/
class Member implements UserInterface
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
    * @ORM\Column(name="firstname", type="string", length=255)
    */
    private $firstname;

    /**
    * @var string
    *
    * @ORM\Column(name="lastname", type="string", length=255)
    */
    private $lastname;

    /**
    * @var string
    *
    * @ORM\Column(name="email", type="string", length=191, unique=true)
    */
    private $email;

    /**
    * @var string
    *
    * @ORM\Column(name="password", type="string", length=255)
    */
    private $password;

    /**
    * @var string
    *
    * @ORM\Column(name="role", type="string", length=255)
    */
    private $role;

    /**
    * @var bool
    *
    * @ORM\Column(name="is_active", type="boolean")
    */
    private $isActive;

    /**
    * @var \DateTime
    *
    * @ORM\Column(name="creation", type="datetime")
    */
    private $creation;

    /**
    * @var ArrayCollection
    *
    * @ORM\OneToMany(targetEntity="TrailWarehouse\AppBundle\Entity\Coordinate", mappedBy="member")
    */
    private $coordinates;


    /**
    * Constructor
    */
    public function __construct()
    {
        $this->coordinates = new ArrayCollection();
    }


    /* ---------- UserInterface ---------- */

    public function getUsername() {
        return null;
    }

    public function getRoles() {
        return null;
    }

    public function getSalt() {
        return null;
    }

    public function eraseCredentials() {
        return null;
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
    * Set firstname
    *
    * @param string $firstname
    *
    * @return Member
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
    * Set lastname
    *
    * @param string $lastname
    *
    * @return Member
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
    * Set email
    *
    * @param string $email
    *
    * @return Member
    */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
    * Get email
    *
    * @return string
    */
    public function getEmail()
    {
        return $this->email;
    }

    /**
    * Set password
    *
    * @param string $password
    *
    * @return Member
    */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
    * Get password
    *
    * @return string
    */
    public function getPassword()
    {
        return $this->password;
    }

    /**
    * Set isActive
    *
    * @param boolean $isActive
    *
    * @return Member
    */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
    * Get isActive
    *
    * @return bool
    */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
    * Add coordinate
    *
    * @param Coordinate $coordinate
    */
    public function addCoordinate(Coordinate $coordinate)
    {
        $this->coordinates[] = $coordinate;
        $coordinate->setMember($this);
    }

    /**
    * Remove coordinate
    *
    * @param Coordinate $coordinate
    */
    public function removeCoordinate(Coordinate $coordinate)
    {
        $this->coordinates->removeElement($coordinate);
    }

    /**
    * Get coordinates
    *
    * @return ArrayCollection
    */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return Member
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set creation
     *
     * @param \DateTime $creation
     *
     * @return Member
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

}
