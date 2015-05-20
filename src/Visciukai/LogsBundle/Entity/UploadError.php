<?php

namespace Visciukai\LogsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Visciukai\GalerijaBundle\Entity\User;

/**
 * UploadError
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class UploadError
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
     * @var integer
     *
     * @ORM\Column(name="errorCode", type="integer")
     */
    private $errorCode;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\Visciukai\GalerijaBundle\Entity\User", inversedBy="uploadErrors")
     */
    private $user;

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
     * Set errorCode
     *
     * @param integer $errorCode
     * @return UploadError
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * Get errorCode
     *
     * @return integer 
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return UploadError
     */
    public function setUser(User $user = null)
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
}
