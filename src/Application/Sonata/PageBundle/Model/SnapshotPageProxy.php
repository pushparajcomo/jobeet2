<?php

namespace Application\Sonata\PageBundle\Model;

use Sonata\PageBundle\Model\PageBlockInterface;
use Sonata\PageBundle\Model\SnapshotPageProxy as BaseProxy;

/**
 * SnapshotProxyExtend
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class SnapshotPageProxy extends BaseProxy
{
    public function getBodyCopy()
    {
       return $this->getPage()->getBodyCopy();
    } 
    
    public function getTaggedMedia()
    {
        $content = $this->getSnapshot()->getContent();

        $taggedMedia = array();
//        foreach ($content['tags'] as $tag) {
////                print_r($tag);
//            $taggedMedia[] = $this->addTags($this->getManager()->loadTags($tag, $this->getPage()));
//        }
        
        foreach ($content['tags'] as $tag) {
//        echo '<pre>';
           $taggedMedia[] =  $this->getManager()->addMedia($tag['media'], $this->getPage());
//           exit;
        } 
        echo '<pre>';
        print_r($taggedMedia); exit;
        return $taggedMedia;            
    }
    
    public function getTaggedOperators()
    {
        $tagItem = array();
        
        if($this->getTags())
        {
            foreach ($this->getTags() as $tag)
            {
                $tagItem[] = $tag;
            }
            
            return $tagItem;
        }
    }    
    
    public function getTags()
    {
        return $this->getPage()->getTags();
    }
    
//    public function addTags(\Application\Sonata\PageBundle\Entity\Tag $tag)
//    {
//        $this->getPage()->addTags($tag);
//    }
//    
//    public function getTaggedSnapshot()
//    {
//        if (!count($this->getPage()->getTags())) {
//
//            $content = $this->snapshot->getContent();
//
//            foreach ($content['tags'] as $tag) {
//                $this->addTags($this->manager->loadTags($tag, $this->getPage()));
//            }
//        }
//
//        return $this->getPage()->getTaggedSnapshot();
//    }
    
//    public function getBlocks()
//    {
//        if (!count($this->getPage()->getBlocks())) {
//
//            $content = $this->snapshot->getContent();
//
//            foreach ($content['blocks'] as $block) {
//                $this->addBlocks($this->manager->loadBlock($block, $this->getPage()));
//            }
//        }
//
//        return $this->getPage()->getBlocks();
//    }
}