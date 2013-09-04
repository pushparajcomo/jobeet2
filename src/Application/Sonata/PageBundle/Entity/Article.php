<?php

namespace Application\Sonata\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Article
 */
class Article
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
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $body;

    /**
     * @var string
     */
    private $sources;

    /**
     * @var string
     */
    private $notes;

    /**
     * @var string
     */
    private $author;
    
    private $showAuthor = true;



    /**
     *
     * @var type ArrayCollection
     */
    protected $tags;
    
    protected $media;
    
    protected $publishDate;
    
    protected $expiryDate;    
    
    public function __construct() {
        $this->media = new ArrayCollection();
    }

    public function __toString() {
        return $this->getName();
    }
    
    public function getTags() {
        return $this->tags;
    }

    public function setTags($tags) {
        $this->tags = $tags;
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
     * Set name
     *
     * @param string $name
     * @return Article
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
        return $this->name;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Article
     */
    public function setBody($body)
    {
        $this->body = $body;
    
        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set sources
     *
     * @param string $sources
     * @return Article
     */
    public function setSources($sources)
    {
        $this->sources = $sources;
    
        return $this;
    }

    /**
     * Get sources
     *
     * @return string 
     */
    public function getSources()
    {
        return $this->sources;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return Article
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    
        return $this;
    }

    /**
     * Get notes
     *
     * @return string 
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return Article
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }
    
    public function getPublishDate() {
        return $this->publishDate;
    }

    public function setPublishDate($publishDate) {
        $this->publishDate = $publishDate;
    }

    public function getExpiryDate() {
        return $this->expiryDate;
    }

    public function setExpiryDate($expiryDate) {
        $this->expiryDate = $expiryDate;
    }

    public function getMedia() {
        return $this->media;
    }

    public function setMedia($media) {
        $this->media = $media;
        return $this;
    }
    
    public function addMedia($media)
    {        
        $media->setArticle($this);
        $this->media->add($media);        
    }
    
    public function removeMedia($media){
        $this->media->remove($media);
    }             
    
    public function getShowAuthor() {
        return $this->showAuthor;
    }

    public function setShowAuthor($showAuthor) {
        $this->showAuthor = $showAuthor;
    }


}
