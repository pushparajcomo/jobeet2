<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Application\Sonata\PageBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ArticleMediaAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {                
        $formMapper
           ->add('name')
           ->add('mediaItem', 'operator_media_list', array('required' => false, 'model_manager' => $this->getModelManager(), 'class' => 'Application\Sonata\MediaBundle\Entity\Media'), array(
                'link_parameters' => array('provider' => 'sonata.media.provider.image', 'context'  => 'default')
            ))
           ->add('caption')
           ->add('position')
           ->add('article')
        ;         
    }
    
}