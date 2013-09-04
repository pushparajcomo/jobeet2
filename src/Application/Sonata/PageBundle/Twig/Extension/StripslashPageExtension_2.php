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
class StripslashPageExtension extends \Twig_Extension
{   

    public function getFilters()
    {
        return array(
            'stripslash'        => new \Twig_Filter_Method($this, 'stripslashText'),
        );
    }
    
    public function stripslashText($txt){

        return str_replace("\\",'',$txt);
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
        return 'stripslashText';
    }
}