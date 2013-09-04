<?php

namespace Application\Sonata\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * OperatorMedia
 */
class ArticleMedia
{
    /**
     * @var integer
     */
    protected $id;
    
    protected $name;
    
    protected $caption;
    
    protected $position = 0;

    protected $mediaItem;
    
    protected $article;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function getMediaItem() {
        return $this->mediaItem;
    }

    public function setMediaItem($mediaItem) {
        $this->mediaItem = $mediaItem;
    }
    
    public function getArticle() {
        return $this->article;
    }

    public function setArticle($article) {
        $this->article = $article;
    }
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getCaption() {
        return $this->caption;
    }

    public function setCaption($caption) {
        $this->caption = $caption;
    }

    public function getPosition() {
        return $this->position;
    }

    public function setPosition($position) {
        $this->position = $position;
    }

    public function __toString() {
        return $this->getName();
    }
    
 
}
