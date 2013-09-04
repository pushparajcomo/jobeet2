<?php
/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\PageBundle\Block;

use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Block\BlockServiceInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\PageBundle\Model\PageInterface;

class MenuBlockService extends BaseBlockService implements BlockServiceInterface
{
    protected $em;
    public function __construct($name, \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating, \Doctrine\ORM\EntityManager $em) {
        parent::__construct($name, $templating);
        $this->em = $em;
    }
    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $form
     * @param \Sonata\BlockBundle\Model\BlockInterface $block
     * @return void
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'keys' => array(
                array('items', 'items', array('required' => false))
            )
        ));
    }

    /**
     * @param BlockInterface $block
     * @param null|Response $response
     *
     * @return Response $response
     */
    public function execute(BlockInterface $block, Response $response = null)
    {
        $settings = array_merge($this->getDefaultSettings(), $block->getSettings());
        
        
        if (!$response) {
            $response = new Response();
        }
        
        $currentPage = $block->getPage();
        $currentSite = $currentPage->getSite();
        $qb = $this->em->getRepository('ApplicationSonataPageBundle:Page')->createQueryBuilder('j')
                ->select('p.name, p.url', 'p.id')
                ->from('ApplicationSonataPageBundle:Page', 'p')
                ->where('p.site = :site_id')
                ->andWhere('p.routeName = :routeName')
                ->setParameter('site_id', $currentSite->getId())
                ->setParameter('routeName', PageInterface::PAGE_ROUTE_CMS_NAME)
                ->addGroupBy('p.name')
           ;
        
        
        $pages = $qb->getQuery()
                        ->getArrayResult();
        if ($block->getEnabled()) {
            $template_prefix = ($settings['template'] != '')?$settings['template'].'.':'';
            $response = $this->renderResponse(
                    'SonataPageBundle:Block:block_menu.'.$template_prefix.'html.twig',
                    array(
                        'page'      => $currentPage,
                        'block'     => $block,
                        'settings'  => $settings,
                        'pages'     => $pages
                    ), 
                    $response);
        }

        return $response;
    }

    /**
     * @param \Sonata\AdminBundle\Validator\ErrorElement $errorElement
     * @param \Sonata\BlockBundle\Model\BlockInterface $block
     * @return void
     */
    function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        $errorElement
            ->with('settings.content')
                ->assertNotNull(array('items'))
                ->assertNotBlank()
            ->end();
            
    }
    /**
     * @return string
     */
    public function getName()
    {
        return 'Nav';
    } 

    /**
     * Returns the default settings link to the service
     *
     * @return array
     */
    public function getDefaultSettings()
    {
    return array(
        'content' => array('items' => array()),
        'template' => ''
    );
    }

}
