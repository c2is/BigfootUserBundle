<?php

namespace Bigfoot\Bundle\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Bigfoot\Bundle\UserBundle\Model\BigfootUser as BaseUser;

/**
 * BigfootUser
 *
 * @ORM\Table(name="bigfoot_user")
 * @ORM\Entity(repositoryClass="Bigfoot\Bundle\UserBundle\Entity\BigfootUserRepository")
 */
class BigfootUser extends BaseUser
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
     * @ORM\Column(name="full_name", type="string", length=255)
     */
    private $fullName;

    /**
     * Construct BigfootUser
     */
    public function __construct()
    {
        parent::__construct();

        $this->setEnabled(true);
        $this->setLastLogin(new \DateTime());
    }

    public function __toString()
    {
        return $this->username;
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
     * (PHP 5 &gt;= 5.1.0)<br/>
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize(
            array(
                $this->id,
                $this->username,
                $this->salt,
                $this->password,
                $this->locale,
            )
        );
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
