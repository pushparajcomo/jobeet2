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

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Validator\ErrorElement;

use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BaseBlockService;

use Sonata\MediaBundle\Model\GalleryManagerInterface;
use Sonata\MediaBundle\Model\GalleryInterface;
use Sonata\MediaBundle\Model\MediaInterface;

use Sonata\MediaBundle\Block\GalleryBlockService as BaseGalleryBlockService;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * PageExtension
 *
 * @author     Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class GalleryBlockService extends BaseGalleryBlockService
{

    /**
     * {@inheritdoc}
     */
    public function execute(BlockInterface $block, Response $response = null)
    {
        // merge settings
        $settings = array_merge($this->getDefaultSettings(), $block->getSettings());

        $gallery = $settings['galleryId'];

        return $this->renderResponse('ApplicationSonataMediaBundle:Block:block_gallery_rutherglen.html.twig', array(
            'gallery'   => $gallery,
            'block'     => $block,
            'settings'  => $settings,
            'elements'  => $gallery ? $this->buildElements($gallery) : array(),
        ), $response);
    }
    
    private function buildElements(GalleryInterface $gallery)
    {
        $elements = array();
        foreach ($gallery->getGalleryHasMedias() as $galleryHasMedia) {
            if (!$galleryHasMedia->getEnabled()) {
                continue;
            }

            if($galleryHasMedia->getMedia())
            {
                $type = $this->getMediaType($galleryHasMedia->getMedia());
            }else{
                $type = "";
            }

            if (!$type) {
                continue;
            }

            $elements[] = array(
                'title'     => $galleryHasMedia->getMedia()->getName(),
                'caption'   => $galleryHasMedia->getMedia()->getDescription(),
                'type'      => $type,
                'media'     => $galleryHasMedia->getMedia(),
            );
        }

        return $elements;
    }
    
    /**
     * @param \Sonata\MediaBundle\Model\MediaInterface $media
     *
     * @return false|string
     */
    private function getMediaType(MediaInterface $media)
    {
        if ($media->getContentType() == 'video/x-flv') {
            return 'video';
        } elseif (substr($media->getContentType(), 0, 5) == 'image') {
            return 'image';
        }

        return false;
    }    

}
