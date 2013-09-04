<?php

namespace Application\Sonata\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 */
class Tag
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $pages;
    
    /**
     * @var array
     */
    private $snapshot;    
    
    /**
     * @var array
     */
    private $media;
    
    private $parent;
    
    private $position;    
    
    private $hidden;        


    /**
     * @var array
     */
    private $accommodation;    
    
    private $attraction;
    
    private $event;
    
    private $restaurant;
    
    private $article;

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
     * Set name
     *
     * @param string $name
     * @return Tag
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {

        if($this->getParentName()) return $this->name . ' \ ' . $this->getParentName();
        return $this->name;
    }
    
    public function getParentName()
    {
        //Check if parent == self and return false
        
        if(null != $this->getParent()) return $this->getParent()->getName();
        return false;
    }
    
    public function getSingleName()
    {
        return $this->name;
    }

    /**
     * Set pages
     *
     * @param array $pages
     * @return Tag
     */
    public function setPages($pages)
    {
        $this->pages = $pages;
    
        return $this;
    }

    /**
     * Get pages
     *
     * @return array 
     */
    public function getPages()
    {
        return $this->pages;
    }
    
    
    /**
     * Set media
     *
     * @param array $media
     * @return Tag
     */
    public function setMedia($media)
    {
        $this->media = $media;
    
        return $this;
    }

    /**
     * Get media
     *
     * @return array 
     */
    public function getMedia()
    {
        return $this->media;
    }    

    
    public function setAccommodation($accommodation)
    {
        $this->accommodation = $accommodation;
    
        return $this;
    }


    public function getAccommodation()
    {
        return $this->accommodation;
    }    
    
    
    public function getParent() {
        return $this->parent;
    }

    public function setParent($parent) {
        $this->parent = $parent;
    }

    public function getPosition() {
        return $this->position;
    }

    public function setPosition($position) {
        $this->position = $position;
    }

    public function getHidden() {
        return $this->hidden;
    }

    public function setHidden($hidden) {
        $this->hidden = $hidden;
    }

    
    public function __toString() {
        return $this->getName();
    }
    
    public function getAttraction() {
        return $this->attraction;
    }

    public function setAttraction($attraction) {
        $this->attraction = $attraction;
    }

    public function getEvent() {
        return $this->event;
    }

    public function setEvent($event) {
        $this->event = $event;
    }

    public function getRestaurant() {
        return $this->restaurant;
    }

    public function setRestaurant($restaurant) {
        $this->restaurant = $restaurant;
    }

    public function getArticle() {
        return $this->article;
    }

    public function setArticle($article) {
        $this->article = $article;
    }

}
