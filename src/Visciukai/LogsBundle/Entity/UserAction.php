<?php

namespace Visciukai\LogsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Visciukai\GalerijaBundle\Entity\User;

/**
 * UserAction
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Visciukai\LogsBundle\Entity\UserActionRepository")
 */
class UserAction
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
     * @ORM\Column(name="action", type="string", length=256)
     */
    private $action;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\Visciukai\GalerijaBundle\Entity\User", inversedBy="actions")
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
     * Set action
     *
     * @param string $action
     * @return UserAction
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string 
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return UserAction
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
