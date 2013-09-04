<?php
/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\MediaBundle\Block;

use Sonata\AdminBundle\Admin\Admin;

use Application\Sonata\MediaBundle\Block\FeatureMediaBlockService as BaseFeaturedMediaService;
use Sonata\BlockBundle\Model\BlockInterface;

use Application\Sonata\PageBundle\Block\MediaManager;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * PageExtension
 *
 * @author     Ramkumar
 */
class LinkedMediaService extends BaseFeaturedMediaService
{
    protected $media_manager;
    public function __construct($name, EngineInterface $templating, ContainerInterface $container, MediaManager $media_manager) {
        parent::__construct($name, $templating,$container,$media_manager);
        $this->media_manager = $media_manager;
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Linked Media';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultSettings()
    {
        return array(
            'media'   => false,
            'orientation' => 'left',
            'title'   => false,
            'content' => false,
            'context' => false,
            'format'  => false,
            'url'     => "",
            'desc'    => "",
            'overlay_title' => "",
            'background_color' => "",
            'opacity' => "",
            'template' => 'hero'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockInterface $block, Response $response = null)
    {
        // merge settings
        $settings = array_merge($this->getDefaultSettings(), $block->getSettings());

        $media = $settings['mediaId'];
        $template = $settings['template'];

        return $this->renderResponse('ApplicationSonataMediaBundle:Block:block_linked_media_'.$template.'.html.twig', array(
            'media'     => $media,
            'block'     => $block,
            'settings'  => $settings
        ), $response);
    }
}
