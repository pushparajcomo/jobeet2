<?php
 
namespace Application\Sonata\PageBundle\Twig\Extension;
 
class PageUrlTwigExtension extends \Twig_Extension
{
    private $em;
    private $conn;
    private $router;
    public function __construct(\Doctrine\ORM\EntityManager $em, \Symfony\Cmf\Component\Routing\ChainRouter $router) {
        $this->em = $em;
        $this->conn = $em->getConnection();
        $this->router = $router;
    }
 
    public function getFunctions()
    {
        return array(
            'page' => new \Twig_Function_Method($this, 'getPage'),
        );
    }
 
    public function getPage($page_id)
    {
        $page = $this->em->getRepository('ApplicationSonataPageBundle:Page')->find($page_id);
        
        return $page;
    }
 
    public function getName()
    {
        return 'page_url_twig_extension';
    }
}

?>
