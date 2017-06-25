<?php

namespace TrailWarehouse\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use TrailWarehouse\AppBundle\Entity\Coordinate;

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
    * @ORM\OneToMany(targetEntity="TrailWarehouse\AppBundle\Entity\Review", mappedBy="user")
    */
    private $reviews;

    /**
    * Constructor
    */
    public function __construct() {
        $this->coordinates  = new ArrayCollection();
        $this->reviews      = new ArrayCollection();
        $this->creationDate = new \DateTime();
    }


    /* ---------- AdvancedUserInterface ---------- */

    public function getRoles() {
        return arary('ROLE_USER');
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
     * @Assert\Callback
     * @ORM\PrePersist
     */
    public function generateHash()
    {
        $hash = password_hash($this->plainPassword, PASSWORD_BCRYPT);
        $this->setPassword($hash);
    }

    /**
     * @Assert\Callback
     * @ORM\PrePersist
     */
    public function activateAdmin()
    {
        if ($this->role === 'ROLE_ADMIN' OR $this->role === 'ROLE_SUPER_ADMIN') {
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
    public function addCoordinate(\TrailWarehouse\AppBundle\Entity\Coordinate $coordinate)
    {
        $this->coordinates[] = $coordinate;

        return $this;
    }

    /**
     * Remove coordinate
     *
     * @param \TrailWarehouse\AppBundle\Entity\Coordinate $coordinate
     */
    public function removeCoordinate(\TrailWarehouse\AppBundle\Entity\Coordinate $coordinate)
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
     * Add review
     *
     * @param \TrailWarehouse\AppBundle\Entity\Review $review
     *
     * @return User
     */
    public function addReview(\TrailWarehouse\AppBundle\Entity\Review $review)
    {
        $this->reviews[] = $review;

        return $this;
    }

    /**
     * Remove review
     *
     * @param \TrailWarehouse\AppBundle\Entity\Review $review
     */
    public function removeReview(\TrailWarehouse\AppBundle\Entity\Review $review)
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
}
