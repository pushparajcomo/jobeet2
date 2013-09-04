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

class ArticleAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('name')
                ->add('title')
                ->add('author')
                ->add('showAuthor')
                ->add('tags', null, array('required' => false))
                ->add('body', 'textarea', array(
                        'attr' => array(
                            'class' => 'tinymce span6',
                            'data-theme' => 'advanced'
                        )
                    ))
                ->add('sources', 'textarea', array('required' => false, 'attr'=>array('class'=>'span6')))
                ->add('notes', 'textarea', array('required' => false, 'attr'=>array('class'=>'alert span6')))
            ->end();
        
        if(null != $this->getSubject()->getId())
        {
            $formMapper
                ->with('Slideshow', array('collapsed' => true))
                    ->add('media', 'sonata_type_collection', array(
                            'by_reference' => false,                        
                            'label' => 'Media List'
                        ), array(
                            'edit' => 'inline',
                            'inline' => 'table',
                            'sortable'  => 'position',
                            'link_parameters' => array('context' => 'default')                        
                        )
                    )
                ->end();           
        }
        
        $formMapper
            ->with('Options')
                ->add('publishDate','date',array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'required' => false,
                'attr' => array('class' => 'date')
             ))
                ->add('expiryDate','date',array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'required' => false,
                'attr' => array('class' => 'date')
             ))
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('author')    
            ->add('tags')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('tags')
        ;
    }
    
    public function getNewInstance() {

        return parent::getNewInstance();  
        
    }
}