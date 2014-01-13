<?php

namespace Bigfoot\Bundle\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Bigfoot\Bundle\UserBundle\Model\BigfootUser as BaseUser;

use Serializable;

/**
 * BigfootUser
 *
 * @ORM\Table(name="bigfoot_user")
 * @ORM\Entity(repositoryClass="Bigfoot\Bundle\UserBundle\Entity\BigfootUserRepository")
 */
class BigfootUser extends BaseUser implements Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=255)
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=6)
     */
    private $locale;

    /**
     * @ORM\ManyToMany(targetEntity="BigfootRole", inversedBy="users")
     * @ORM\JoinTable(name="bigfoot_userRoles")
     */
    private $userRoles;

    public function __toString()
    {
        return $this->username;
    }

    public function __construct()
    {
        $this->salt      = base64_encode(mcrypt_create_iv(24, MCRYPT_DEV_URANDOM));
        $this->userRoles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->salt      = base64_encode(mcrypt_create_iv(24, MCRYPT_DEV_URANDOM));
    }

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
     * Set username
     *
     * @param string $username
     * @return BigfootUser
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return BigfootUser
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
     * Set full name
     *
     * @param string $fullName
     * @return BigfootUser
     */
    public function setFullname($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get full name
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->fullName;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return BigfootUser
     */

    public function setPassword($password)
    {
        if (!$password) {
            return $this;
        }

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
     * Set salt
     *
     * @param string $salt
     * @return BigfootUser
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set locale
     *
     * @param string $locale
     * @return BigfootUser
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set User Roles
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $roles
     * @return BigfootUser
     */
    public function setUserRoles(ArrayCollection $userRoles)
    {
        $this->userRoles = $userRoles;

        return $this;
    }

    /**
     * Adds a User Role.
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $roles
     * @return BigfootUser
     */
    public function addUserRole(BigfootRole $userRole)
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles->add($userRole);
        }

        return $this;
    }

    /**
     * Get User Roles
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    /**
     * Get roles as array
     *
     * @return \Bigfoot\Bundle\UserBundle\Entity\BigfootRole[]
     */
    public function getRoles()
    {
        return $this->userRoles->toArray();
    }

    /**
     * Remove a User Role.
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $roles
     * @return BigfootUser
     */
    public function removeUserRole(BigfootRole $userRole)
    {
        $this->userRoles->removeElement($userRole);
        return $this;
    }

    /**
     * Remove a User Role
     */
    public function removeRole($role)
    {
        $userRole = new BigfootRole();
        $userRole->setName($role);
        $this->removeUserRole($userRole);
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     *
     * @return void
     */
    public function eraseCredentials() {}

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->salt,
            $this->password,
            $this->locale,
        ));
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->salt,
            $this->password,
            $this->locale,
        ) = unserialize($serialized);
    }
}
