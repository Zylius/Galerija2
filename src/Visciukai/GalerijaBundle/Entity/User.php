<?php

namespace Visciukai\GalerijaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Visciukai\ImagesBundle\Entity\Image;
use Visciukai\LogsBundle\Entity\SearchEntry;
use Visciukai\LogsBundle\Entity\UploadError;
use Visciukai\LogsBundle\Entity\UserAction;
use Visciukai\GalerijaBundle\Entity\Comment;

/**
 * @ORM\Entity
 * @ORM\Table(name="vartotojas")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Image[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="\Visciukai\ImagesBundle\Entity\Image", mappedBy="user")
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $images;

    /**
     * @var SearchEntry[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="\Visciukai\LogsBundle\Entity\SearchEntry", mappedBy="user")
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $searches;

    /**
     * @var UploadError[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="\Visciukai\LogsBundle\Entity\UploadError", mappedBy="user")
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $uploadErrors;

    /**
     * @var UserAction[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="\Visciukai\LogsBundle\Entity\UserAction", mappedBy="user")
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $actions;


    /**
     * @var Album[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Album", mappedBy="user")
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $albums;


    /**
     * @var Comment[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="user")
     */
    private $comments;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->images = new ArrayCollection();
        $this->searches = new ArrayCollection();
        $this->uploadErrors = new ArrayCollection();
        $this->actions = new ArrayCollection();
        $this->comments = new ArrayCollection();
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
     * Add images
     *
     * @param Image $images
     * @return User
     */
    public function addImage(Image $images)
    {
        $this->images[] = $images;

        return $this;
    }

    /**
     * Remove images
     *
     * @param Image $images
     */
    public function removeImage(Image $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Add searches
     *
     * @param SearchEntry $searches
     * @return User
     */
    public function addSearch(SearchEntry $searches)
    {
        $this->searches[] = $searches;

        return $this;
    }

    /**
     * Remove searches
     *
     * @param SearchEntry $searches
     */
    public function removeSearch(SearchEntry $searches)
    {
        $this->searches->removeElement($searches);
    }

    /**
     * Get searches
     *
     * @return Collection
     */
    public function getSearches()
    {
        return $this->searches;
    }

    /**
     * Add uploadErrors
     *
     * @param UploadError $uploadErrors
     * @return User
     */
    public function addUploadError(UploadError $uploadErrors)
    {
        $this->uploadErrors[] = $uploadErrors;

        return $this;
    }

    /**
     * Remove uploadErrors
     *
     * @param UploadError $uploadErrors
     */
    public function removeUploadError(UploadError $uploadErrors)
    {
        $this->uploadErrors->removeElement($uploadErrors);
    }

    /**
     * Get uploadErrors
     *
     * @return Collection
     */
    public function getUploadErrors()
    {
        return $this->uploadErrors;
    }

    /**
     * Add actions
     *
     * @param UserAction $actions
     * @return User
     */
    public function addAction(UserAction $actions)
    {
        $this->actions[] = $actions;

        return $this;
    }

    /**
     * Remove actions
     *
     * @param UserAction $actions
     */
    public function removeAction(UserAction $actions)
    {
        $this->actions->removeElement($actions);
    }

    /**
     * Get actions
     *
     * @return Collection
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Add albums
     *
     * @param \Visciukai\GalerijaBundle\Entity\Album $albums
     * @return User
     */
    public function addAlbum(\Visciukai\GalerijaBundle\Entity\Album $albums)
    {
        $this->albums[] = $albums;

        return $this;
    }

    /**
     * Remove albums
     *
     * @param \Visciukai\GalerijaBundle\Entity\Album $albums
     */
    public function removeAlbum(\Visciukai\GalerijaBundle\Entity\Album $albums)
    {
        $this->albums->removeElement($albums);
    }

    /**
     * Get albums
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAlbums()
    {
        return $this->albums;
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }
}
