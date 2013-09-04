<?php

/*
 * This file is part of sonata-project.
 *
 * (c) 2010 Thomas Rabaix
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\PageBundle\Twig\Extension;

use Sonata\PageBundle\Model\PageBlockInterface;
use Sonata\BlockBundle\Block\BlockServiceManagerInterface;
use Sonata\PageBundle\Model\PageInterface;
use Sonata\PageBundle\CmsManager\CmsManagerSelectorInterface;
use Sonata\PageBundle\Site\SiteSelectorInterface;
use Sonata\PageBundle\Exception\PageNotFoundException;
use Sonata\PageBundle\Model\SnapshotPageProxy;

use Symfony\Component\Routing\RouterInterface;

/**
 * PageExtension
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class CustomPageExtension extends \Twig_Extension
{   
    /**
     * @var \Twig_Environment
     */
    private $environment;
    
    private $blockmanager;
    public function __construct( \Sonata\BlockBundle\Model\BlockManagerInterface $blockmanager)
    {
        
        $this->blockmanager       = $blockmanager;
    }
    public function getFilters()
    {
        return array(
            'sonata_page_render_custome_block'        => new \Twig_Filter_Method($this, 'customeBlockRender'),
        );
    }
    
    public function customeBlockRender($container, $services){
        $container_html = $container;
        if($container_html !=''){
        $p1 = strpos($container, '"');
        $p2 = strpos($container, '"',$p1+1);
        $container = substr($container, $p1+1, ($p2-$p1)-1);
        $container_id = str_replace('cms-block-', '', $container);
        
        $block = $this->blockmanager->findOneBy(array( 'id'=>$container_id, 'type'=>'sonata.page.block.container'));
        $tobecreated = array();
        $flag = true;
        if($block){
            foreach($services as $key=> $service){
                $flag = true;
                foreach ($block->getChildren() as $child){
                    if($service['service'] == $child->getType()){
                        $flag = false;
                    }
                }
                if($flag)
                    $tobecreated[$key] = $service;
            }
        }
        foreach($tobecreated as $key=>$value){

            $newblock = $this->blockmanager->create();

            $newblock->setName($key);
            $newblock->setType($value['service']);
            $newblock->setPage($block->getPage());
            $newblock->setParent($block);
             if($value['service'] == 'sonata.block.service.text')
                $newblock->setSettings(array("content" => "Insert your content here"));
             elseif($value['service'] == 'sonata.media.block.feature_media')
                $newblock->setSettings(array("media" => false,"orientation" => "left","title" => null,"content" => null,"context" => "default","format" => "reference","mediaId" => null));
             elseif($value['service'] == 'sonata.media.admin.gallery')
                $newblock->setSettings(array("gallery" => false,"title" => "etst","context" => "default","format" => "default_big","pauseTime" => 3000,"animSpeed" => 300,"startPaused" => 1,"directionNav" => 1,"progressBar" => 1,"galleryId" => null));
             elseif($value['service'] == 'sonata.block.service.linkedmedia')
                 $newblock->setSettings(array(
                     "media" => false,
                     "orientation" => "left",
                     "title" => null,
                     "content" => null,
                     "context" => "default",
                     "format" => "reference",
                     "mediaId" => null,
                     'url'     => "",
                     'desc'    => "",
                     'overlay_title' => "",
                     'background_color' => "",
                     'opacity' => "",
                     'template' => $value['template']
                 ));
             elseif($value['service'] == 'sonata.block.service.menu'){
                 $newblock->setSettings(
                 array(
                     'content' => array(
                         'items' => array()
                      ),
                     'template' => isset($value['template'])?$value['template']:''
                 ));
             }
            $newblock->setEnabled(1);
            $newblock->setPosition(1);
            $this->blockmanager->save($newblock);


        }
        echo $container_html;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sonata_custom_page';
    }
}