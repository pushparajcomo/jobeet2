<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\PageBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

use Sonata\PageBundle\Exception\PageNotFoundException;
use Sonata\PageBundle\Exception\InternalErrorException;
use Sonata\PageBundle\Model\PageInterface;
use Sonata\PageBundle\Model\PageManagerInterface;
use Sonata\PageBundle\Model\SiteManagerInterface;

use Sonata\CacheBundle\Cache\CacheManagerInterface;

use Sonata\AdminBundle\Route\RouteCollection;

use Knp\Menu\ItemInterface as MenuItemInterface;

/**
 * Admin definition for the Page class
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class PageAdmin extends Admin
{
    /**
     * @var PageManagerInterface
     */
    protected $pageManager;

    /**
     * @var SiteManagerInterface
     */
    protected $siteManager;

    /**
     * @var CacheManagerInterface
     */
    protected $cacheManager;
    
    /**
     *
     * @var type int
     */
    protected $userSite = null;

    /**
     * Default Datagrid values
     *
     * @var array
     */
    protected $datagridValues = array(
        '_page' => 1, // Display the first page (default = 1)
        '_sort_order' => 'DESC', // Descendant ordering (default = 'ASC')
        '_sort_by' => 'createdAt' // name of the ordered field (default = the model id field, if any)
        // the '_sort_by' key can be of the form 'mySubModel.mySubSubModel.myField'.
    );

    /**
     * Force filter pages by current user's site
     * @param type $context
     * @return type
     */
    public function createQuery($context = 'list') 
    { 
        $query = parent::createQuery($context); 
        
        if(null != $this->userSite)
        {        
            $query->andWhere('o.site = :userSite');
            $query->setParameter('userSite', $this->userSite);
        }
        
        return $query;         
    } 
    
    public function setUserSite($siteId){
        $this->userSite = $siteId;
    }
    
    /**
     * Set page type filter as CMS by default
     * @return type
     */
    public function getFilterParameters()
    {
        $this->datagridValues = array_merge(array(
                'hybrid' => array(
                    'value' => 'cms',
                )
            ),
            $this->datagridValues

        );
        return parent::getFilterParameters();
    }    
    
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('blockMediaSave', 'blockMediaSave');
        $collection->add('blockGallerySave', 'blockGallerySave');
        $collection->add('storeMediaSettings', 'storeMediaSettings');
        $collection->add('saveSubscriber', 'saveSubscriber');
        $collection->add('CKEditorSave', 'CKEditorSave'); 
    }    

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('site')
            ->add('routeName')
            ->add('pageAlias')
            ->add('type')
            ->add('enabled')
            ->add('decorate')
            ->add('name')
            ->add('slug')
            ->add('customUrl')
            ->add('edited')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('hybrid', 'text', array('template' => 'SonataPageBundle:PageAdmin:field_hybrid.html.twig'))
            ->addIdentifier('name')
            ->add('templateCode')
            ->add('tags')
            ->add('site')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array('template' => 'SonataAdminBundle:CRUD:list__action_edit.html.twig'),
                    'delete' => array('template' => 'SonataAdminBundle:CRUD:list__action_delete.html.twig')                    
                )
            ));
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('site')
            ->add('name')
            ->add('type', null, array('field_type' => 'sonata_page_type_choice'))
            ->add('pageAlias')
            ->add('parent')
            ->add('edited')
            ->add('hybrid', 'doctrine_orm_callback', array(
                'callback' => function($queryBuilder, $alias, $field, $data) {
                    if (in_array($data['value'], array('hybrid', 'cms'))) {
                        $queryBuilder->andWhere(sprintf('%s.routeName %s :routeName', $alias, $data['value'] == 'cms' ? '=' : '!='));
                        $queryBuilder->setParameter('routeName', PageInterface::PAGE_ROUTE_CMS_NAME);
                    }
                },
                'field_options' => array(
                    'required' => false,
                    'choices'  => array(
                        'hybrid'  => $this->trans('hybrid'),
                        'cms'     => $this->trans('cms'),
                    )
                ),
                'field_type' => 'choice'
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with($this->trans('form_page.group_main_label'))
                ->add('name')
                ->add('templateCode', 'sonata_page_template', array('required' => true))
                ->add('tags') //, null, array('expanded'=>true)
//                ->add('bodyCopy', 'textarea', array(
//                        'attr' => array(
//                            'class' => 'tinymce',
//                            'data-theme' => 'advanced' // simple, advanced, bbcode
//                        ),
//                        'required' => false
//                    ))
            ->end();
        
        if (!$this->getSubject() || (!$this->getSubject()->isInternal() && !$this->getSubject()->isError())) {
            $formMapper
                ->with($this->trans('form_page.group_advanced_label'))
                    ->add('url', 'text', array('attr' => array('readonly' => 'readonly')))
                ->end()
            ;
        }


        if ($this->hasSubject() && !$this->getSubject()->isInternal()) {
            $formMapper
                ->with($this->trans('form_page.group_advanced_label'))
                    ->add('type', 'sonata_page_type_choice', array('required' => false))
                ->end()
            ;
        }

        $formMapper
            ->with($this->trans('form_page.group_advanced_label')) 
                ->add('site', null, array('required' => true))
                ->add('parent', 'sonata_page_selector', array(
                    'page'          => $this->getSubject() ?: null,
                    'site'          => $this->getSubject() ? $this->getSubject()->getSite() : null,
                    'model_manager' => $this->getModelManager(),
                    'class'         => $this->getClass(),
                    'required'      => false
                ))                                
                ->add('enabled', null, array('required' => false))
                ->add('position')
            ->end()
        ;

        if (!$this->getSubject() || !$this->getSubject()->isDynamic()) {
            $formMapper
                ->with($this->trans('form_page.group_advanced_label'))
                    ->add('pageAlias', null, array('required' => false))
                    ->add('target', 'sonata_page_selector', array(
                        'page'          => $this->getSubject() ?: null,
                        'site'          => $this->getSubject() ? $this->getSubject()->getSite() : null,
                        'model_manager' => $this->getModelManager(),
                        'class'         => $this->getClass(),
                        'filter_choice' => array('request_method' => 'all'),
                        'required'      => false
                    ))
                ->end()
            ;
        }

        if (!$this->getSubject() || !$this->getSubject()->isHybrid()) {
            $formMapper
                ->with($this->trans('form_page.group_seo_label'))
                    ->add('slug', 'text',  array('required' => false))
                    ->add('customUrl', 'text', array('required' => false))
                ->end()
            ;
        }

        $formMapper
            ->with($this->trans('form_page.group_seo_label'), array('collapsed' => true))
                ->add('title', null, array('required' => false))
                ->add('metaKeyword', 'textarea', array('required' => false))
                ->add('metaDescription', 'textarea', array('required' => false))
            ->end()
        ;

        if ($this->hasSubject() && !$this->getSubject()->isCms()) {
            $formMapper
                ->with($this->trans('form_page.group_advanced_label'), array('collapsed' => true))
                    ->add('decorate', null,  array('required' => false))
                ->end();
        }

        $formMapper
            ->with($this->trans('form_page.group_advanced_label'), array('collapsed' => true))
                ->add('javascript', null,  array('required' => false))
                ->add('stylesheet', null, array('required' => false))
                ->add('rawHeaders', null, array('required' => false))
            ->end()
        ;

        $formMapper->setHelps(array(
            'name' => $this->trans('help_page_name')
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if (!$childAdmin && !in_array($action, array('edit'))) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;

        $id = $admin->getRequest()->get('id');

        $menu->addChild(
            $this->trans('sidemenu.link_edit_page'),
            array('uri' => $admin->generateUrl('edit', array('id' => $id)))
        );
        
        if (!$this->getSubject()->isHybrid()) {
            $url = ($this->getSubject()->getUrl()=='/')?'':$this->getSubject()->getUrl();
            try {
                $menu->addChild(
                    $this->trans('view_page'),
                    array('uri' => 'http://'.$this->getSubject()->getSite()->getHost().$url)
                );
            } catch (RouteNotFoundException $e) {
                // avoid crashing the admin if the route is not setup correctly
//                throw $e;
            }
        }        

        $menu->addChild(
            $this->trans('sidemenu.link_list_blocks'),
            array('uri' => $admin->generateUrl('sonata.page.admin.block.list', array('id' => $id)))
        );

        $menu->addChild(
            $this->trans('sidemenu.link_list_snapshots'),
            array('uri' => $admin->generateUrl('sonata.page.admin.snapshot.list', array('id' => $id)))
        );

    }

    /**
     * {@inheritdoc}
     */
    public function postUpdate($object)
    {
        if ($this->cacheManager) {
            $this->cacheManager->invalidate(array(
                'page_id' => $object->getId()
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function update($object)
    {
        $object->setEdited(true);

        $this->preUpdate($object);
        $this->pageManager->save($object);
        $this->postUpdate($object);
    }

    /**
     * {@inheritdoc}
     */
    public function create($object)
    {

        $object->setEdited(true);

        $this->prePersist($object);
        $this->pageManager->save($object);
        $this->postPersist($object);
    }

    /**
     * @param \Sonata\PageBundle\Model\PageManagerInterface $pageManager
     */
    public function setPageManager(PageManagerInterface $pageManager)
    {
        $this->pageManager = $pageManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewInstance()
    {
        $instance = parent::getNewInstance();

        if (!$this->hasRequest()) {
            return $instance;
        }

        if ($site = $this->getSite()) {
            $instance->setSite($site);
        }

        if ($site) {
            //Existing page
            if($this->getRequest()->get('url')) {
                $slugs = explode('/', $this->getRequest()->get('url'));
                $slug  = array_pop($slugs);

                try {
                    $parent = $this->pageManager->getPageByUrl($site, implode('/', $slugs));
                } catch (PageNotFoundException $e) {
                    try {
                        $parent = $this->pageManager->getPageByUrl($site, '/');
                    } catch (PageNotFoundException $e) {
                        throw new InternalErrorException('Unable to find the root url, please create a route with url = /');
                    }
                }

                $instance->setSlug(urldecode($slug));
                $instance->setParent($parent ?: null);
                $instance->setName(urldecode($slug));   
            }
            else {
                $instance->setParent($this->pageManager->getPageByUrl($site, '/'));
            }
        }
        $instance->setType('sonata.page.service.default');
        $instance->setEnabled(true);

        return $instance;
    }

    /**
     * @return SiteInterface
     *
     * @throws \RuntimeException
     */
    public function getSite()
    {
        if (!$this->hasRequest()) {
            return false;
        }

        $siteId = null;

        if ($this->getRequest()->getMethod() == 'POST') {
            $values = $this->getRequest()->get($this->getUniqid());
            $siteId = isset($values['site']) ? $values['site'] : null;
        }

        $siteId = (null !== $siteId) ? $siteId : $this->getRequest()->get('siteId');

        if ($siteId) {
            $site = $this->siteManager->findOneBy(array('id' => $siteId));

            if (!$site) {
                throw new \RuntimeException('Unable to find the site with id=' . $this->getRequest()->get('siteId'));
            }

            return $site;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getBatchActions()
    {
        $actions = parent::getBatchActions();

        $actions['snapshot'] = array(
            'label'            => $this->trans('create_snapshot'),
            'ask_confirmation' => false
        );

        return $actions;
    }

    /**
     * @param \Sonata\PageBundle\Model\SiteManagerInterface $siteManager
     */
    public function setSiteManager(SiteManagerInterface $siteManager)
    {
        $this->siteManager = $siteManager;
    }

    /**
     * @return array
     */
    public function getSites()
    {
        return $this->siteManager->findBy();
    }

    /**
     * @param \Sonata\CacheBundle\Cache\CacheManagerInterface $cacheManager
     */
    public function setCacheManager(CacheManagerInterface $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }
    
    public function postPersist($object) {
        
        parent::postPersist($object);
    }
}
