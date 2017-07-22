<?php

namespace TrailWarehouse\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use TrailWarehouse\AppBundle\Entity\Order;
use TrailWarehouse\AppBundle\Entity\Coordinate;
use TrailWarehouse\AppBundle\Entity\Review;

/**
* User
*
* @ORM\Table(name="`user`")
* @ORM\Entity(repositoryClass="TrailWarehouse\AppBundle\Repository\UserRepository")
* @UniqueEntity(fields={"email"}, message="Adresse électronique déjà utilisée")
* @ORM\HasLifecycleCallbacks()
*/
class User implements AdvancedUserInterface, \Serializable
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
    * @Assert\NotBlank()
    * @Assert\Email(
    *   message = "Adresse électronique non valide"
    * )
    *
    * @ORM\Column(name="email", type="string", length=191, unique=true)
    */
    private $email;

    /**
    * @var string
    *
    * @ORM\Column(name="password", type="string", length=128)
    */
    private $password;

    /**
    * @var string
    *
    * @Assert\NotBlank()
    * @Assert\Length(
    *   min = 6,
    *   max = 128,
    *   minMessage = "Le mot de passe doit contenir au moins 6 caractères",
    *   maxMessage = "Le mot de passe ne peut dépasser 128 catactères",
    * )
    */
    private $plainPassword;


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
    * @ORM\Column(name="role", type="string", length=255)
    */
    private $role = 'ROLE_USER';

    /**
    * @var bool
    *
    * @ORM\Column(name="is_active", type="boolean")
    */
    private $isActive = false;

    /**
    * @var \DateTime
    *
    * @ORM\Column(name="creation_date", type="datetime")
    * @Assert\DateTime
    */
    private $creationDate;

    /**
    * @var ArrayCollection
    *
    * @ORM\OneToMany(targetEntity="TrailWarehouse\AppBundle\Entity\Coordinate", mappedBy="user")
    */
    private $coordinates;

    /**
    * @var ArrayCollection
    *
    * @ORM\OneToMany(targetEntity="TrailWarehouse\AppBundle\Entity\Order", mappedBy="user")
    */
    private $orders;

    /**
    * @var ArrayCollection
    *
    * @ORM\OneToMany(targetEntity="TrailWarehouse\AppBundle\Entity\Review", mappedBy="user")
    */
    private $reviews;

    /**
    * Constructor
    */
    public function __construct()
    {
        $this->coordinates  = new ArrayCollection();
        $this->orders       = new ArrayCollection();
        $this->reviews      = new ArrayCollection();
        $this->creationDate = new \DateTime();
        $this->isActive = true; // DELETE this ASA e-mail activation is working
    }


    /* ---------- AdvancedUserInterface ---------- */

    public function getRoles() {
        return [$this->role];
    }

    public function getSalt() {
        return null;
    }

    public function eraseCredentials() {
        return null;
    }

    public function isAccountNonExpired() {
        return true;
    }

    public function isAccountNonLocked() {
        return true;
    }

    public function isCredentialsNonExpired() {
        return true;
    }

    /**
     * @return bool
     */
    public function isEnabled() {
        return $this->isActive;
    }

    /* ---------- Serializable ---------- */

    public function serialize() {
        return serialize ([
            $this->id,
            $this->email,
            $this->password,
            $this->isActive,
        ]);
    }

    public function unserialize($serialized) {
        list (
            $this->id,
            $this->email,
            $this->password,
            $this->isActive,
        ) = unserialize($serialized);
    }

    /* ---------- Callbacks ---------- */

    /**
     * @ORM\PrePersist
     */
    public function generateHash()
    {
        $hash = password_hash($this->plainPassword, PASSWORD_BCRYPT);
        $this->setPassword($hash);
    }

    /**
     * @ORM\PrePersist
     */
    public function autoSetRole()
    {
      // Super Admin ?
      if ($this->email == 'mlaopane@gmail.com') {
        $this->role = 'ROLE_SUPER_ADMIN';
        $this->isActive = true;
      }
      // Admin ?
      if ($this->email == 'mykel.chang@gmail.com' OR $this->email == 'mykel.1337@gmail.com') {
        $this->role = 'ROLE_ADMIN';
        $this->isActive = true;
      }
    }

    /* ---------- Other Methods ---------- */

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
     * get the email (which stands for the username)
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
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
     * Set plainPassword
     *
     * @param string $plainPassword
     *
     * @return User
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * Get plainPassword
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
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
     * Set role
     *
     * @param string $role
     *
     * @return User
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
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return User
     */
    public function setCreationDate($creationDate)
    {
        $this->creation = $creationDate;

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
     * Add coordinate
     *
     * @param \TrailWarehouse\AppBundle\Entity\Coordinate $coordinate
     *
     * @return User
     */
    public function addCoordinate(Coordinate $coordinate)
    {
        $this->coordinates[] = $coordinate;

        return $this;
    }

    /**
     * Remove coordinate
     *
     * @param \TrailWarehouse\AppBundle\Entity\Coordinate $coordinate
     */
    public function removeCoordinate(Coordinate $coordinate)
    {
        $this->coordinates->removeElement($coordinate);
    }

    /**
     * Get coordinates
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * Add order
     *
     * @param Order $order
     */
    public function addOrder(Order $order)
    {
        $this->orders[] = $order;
        return $this;
    }

    /**
     * Remove order
     *
     * @param Order $order
     */
    public function removeOrder(Order $order)
    {
        $this->orders->removeElement($order);
        return $this;
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Add review
     *
     * @param \TrailWarehouse\AppBundle\Entity\Review $review
     *
     * @return User
     */
    public function addReview(Review $review)
    {
        $this->reviews[] = $review;

        return $this;
    }

    /**
     * Remove review
     *
     * @param \TrailWarehouse\AppBundle\Entity\Review $review
     */
    public function removeReview(Review $review)
    {
        $this->reviews->removeElement($review);
    }

    /**
     * Get reviews
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
    * @Assert\Email(message = "E-mail non valide")
    */
    public function isEmailValid() {
      return $this->email;
    }

    /**
     * @Assert\IsTrue(message = "E-mail et mot de passe doivent être différents")
     */
    public function isPasswordLegal()
    {
        return $this->email !== $this->plainPassword;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
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
     * @return User
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
     * Get fullaname
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->firstname . " " . $this->lastname;
    }
}
