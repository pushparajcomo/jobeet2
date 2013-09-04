<?php

namespace Application\Sonata\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    
    
    
    public function typeaheadAction($page_id, $query) {       
        $em = $this->getDoctrine()->getEntityManager();
        $currentPage = $em->getRepository('ApplicationSonataPageBundle:Page')->find($page_id);
        $currentSite = $currentPage->getSite();
        
        $qb = $em->getRepository('ApplicationSonataPageBundle:Page')->createQueryBuilder('j')
                ->select('p.name, p.url', 'p.id')
                ->from('ApplicationSonataPageBundle:Page', 'p')
                ->where('p.site = :site_id')
                ->andWhere('p.name LIKE :query')
                ->setParameter('site_id', $currentSite->getId())
                ->setParameter('query', '%'.$query.'%')
                ->addGroupBy('p.name')
           ;
        
        
        $pages = $qb->getQuery()
                        ->getArrayResult();
        
        $pageRoutes = array();
        $pageUrls = array();
        $pageCollection = array();
        foreach($pages as $key => $page) {
            $pageRoutes[$key] = array('name' => $page['name'], 'value' => $page['id']);
//            $pageUrls[$page['name']] = $page['url'];
//            $pageCollection[$page['name']] = $page['id'];
        }
        
        $response = array( 'options' => $pageRoutes
                         );
        return new Response(json_encode($response));
    }
    
    
    public function addMenuAction(Request $request) 
    {      
        $em = $this->getDoctrine()->getManager();
        $block = $em->getRepository('ApplicationSonataPageBundle:Block')->find($request->request->get('block_id'));
        $settings = array(
                           'content' => array('items' => $request->request->get('content')),
                           'template' => $request->request->get('template')
                          );
        $block->setSettings($settings);
        $em->persist($block);
        $em->flush();
        print_r($block->getSettings()); exit;
    }
    
    public function pageUrlAction($page_id) 
    {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('ApplicationSonataPageBundle:Page')->find($page_id);
        
        $url = $this->generateUrl($page);
        return new Response($url);
        
    }
    
}
