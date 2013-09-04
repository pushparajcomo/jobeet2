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
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

/**
 * Admin definition for the Site class
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class SiteAdmin extends Admin
{
    protected $cmsManager;

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('context')
            ->add('isDefault')
            ->add('enabled')
            ->add('host')
            ->add('locale')
            ->add('relativePath')
            ->add('enabledFrom')
            ->add('enabledTo')
            ->add('title')
            ->add('metaDescription')
            ->add('metaKeywords')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('context')                
            ->add('isDefault')
            ->add('enabled', null, array('editable' => true))
            ->add('host')
            ->add('relativePath')
            ->add('locale')
            ->add('enabledFrom')
            ->add('enabledTo')
            ->add('create_snapshots', 'string', array('template' => 'SonataPageBundle:SiteAdmin:list_create_snapshots.html.twig'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $diff = new \DateTime;
        $formMapper
            ->with($this->trans('form_site.label_general'))
                ->add('name')
                ->add('context')
                ->add('isDefault', null, array('required' => false))
                ->add('enabled', null, array('required' => false))
                ->add('host')
                ->add('locale', null, array(
                    'required' => false
                ))
                ->add('relativePath', null, array('required' => false))
                ->add('enabledFrom')
                ->add('enabledTo')
                ->add('checkout_confirmation_action', null, array('required' => false, 'label'=>'Checkout Confirmation Action'))
            ->end()
            ->with('Contact Details', array('collapsed' => true))
                ->add('contactBody', 'textarea', array(
                    'attr' => array(
                        'source' => 'rawContent',
                        'class' => 'tinymce span6',
                        'data-theme' => 'advanced'
                    ),'required' => false, 'label' => 'Contact Body'
                ))
                ->add('fax', null, array('required' => false, 'label'=>'Fax'))
                ->add('web', null, array('required' => false, 'label'=>'Website'))
                ->add('phone', null, array('required' => false, 'label'=>'Phone'))
                ->add('address', 'textarea', array('required' => false, 'label' => 'Address'))
                ->add('facebookUrl', null, array('required' => false, 'label'=>'Facebook Url'))
                ->add('twitterUrl', null, array('required' => false, 'label'=>'Twitter Url'))
            ->end()

            ->with('Twitter Integration Details', array('collapsed' => true))
                ->add('twitterScreenName', null, array('required' => false, 'label'=>'Twitter Handle'))
                ->add('twitterConsumerKey', null, array('required' => false, 'label'=>'Consumer Key'))
                ->add('twitterConsumerSecret', null, array('required' => false, 'label'=>'Consumer Secret'))
                ->add('twitterAccesstoken', null, array('required' => false, 'label'=>'Access Token'))
                ->add('twitterAccesstokenSecret', null, array('required' => false, 'label'=>'Access Token Secret'))
            ->end()

            ->with('Booking Terms and Conditions', array('collapsed' => true))
                ->add('termsAndConditions', 'textarea', array(
                    'attr' => array(
                        'source' => 'rawContent',
                        'class' => 'tinymce span6',
                        'data-theme' => 'advanced'
                    ),'required' => false, 'label' => 'Terms and Conditions'
                ))
            ->end()
            ->with($this->trans('form_site.label_seo'), array('collapsed' => true))
                ->add('title', null, array('required' => false))
                ->add('metaDescription', 'textarea', array('required' => false))
                ->add('metaKeywords', 'textarea', array('required' => false))
                ->add('gacode', 'textarea', array('required' => false))
                ->add('trip_advisor_key', null, array('required' => true, 'label'=>'Trip Advisor Key'))
            ->end()
            ->with('Media', array('collapsed' => true))
            //->add('gallery', 'sonata_type_model_list', array('required' => false), array('link_parameters' => array('context' => 'default')))
            ->add('backgroundImage', 'operator_media_list', array('required' => false, 'model_manager' => $this->getModelManager(), 'class' => 'Application\Sonata\MediaBundle\Entity\Media',), array(
                'link_parameters' => array('provider' => 'sonata.media.provider.image', 'context'  => 'default')
            ))
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('snapshots', $this->getRouterIdParameter().'/snapshots');
    }
}
