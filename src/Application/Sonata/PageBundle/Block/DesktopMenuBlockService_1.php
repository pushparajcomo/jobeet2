<?php
/*
 * This file is part of the TNE Platform Project.
 *
 * (c) Muhammadali Shaduli <shaduli.vanimal@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\PageBundle\Block;

use Application\Sonata\PageBundle\Block\MenuBlockService;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;

class DesktopMenuBlockService extends MenuBlockService
{

    /**
     * @param BlockInterface $block
     * @param Response $response
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
            $response = $this->renderResponse(
                'SonataPageBundle:Block:block_menu_desktop.html.twig',
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

}
