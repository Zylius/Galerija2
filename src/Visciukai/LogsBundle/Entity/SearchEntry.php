<?php

namespace Visciukai\LogsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Visciukai\GalerijaBundle\Entity\User;

/**
 * SearchEntry
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class SearchEntry
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
     * @ORM\Column(name="input", type="string", length=255)
     */
    private $input;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\Visciukai\GalerijaBundle\Entity\User", inversedBy="searches")
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
     * Set input
     *
     * @param string $input
     * @return SearchEntry
     */
    public function setInput($input)
    {
        $this->input = $input;

        return $this;
    }

    /**
     * Get input
     *
     * @return string 
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return SearchEntry
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
