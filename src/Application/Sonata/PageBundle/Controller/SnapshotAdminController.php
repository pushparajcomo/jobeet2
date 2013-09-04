<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\PageBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Snapshot Admin Controller
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class SnapshotAdminController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function createAction()
    {
        if (false === $this->admin->isGranted('CREATE')) {
            throw new AccessDeniedException();
        }

        $class = $this->get('sonata.page.manager.snapshot')->getClass();

        $pageManager = $this->get('sonata.page.manager.page');

        $snapshot = new $class;

        if ($this->getRequest()->getMethod() == 'GET' && $this->getRequest()->get('pageId')) {
            $page = $pageManager->findOne(array('id' => $this->getRequest()->get('pageId')));
        } elseif ($this->admin->isChild()) {
            $page = $this->admin->getParent()->getSubject();
        } else {
            $page = null; // no page selected ...
        }

        $snapshot->setPage($page);

        $form = $this->createForm('sonata_page_create_snapshot', $snapshot);

        if ($this->getRequest()->getMethod() == 'POST') {

            $form->bindRequest($this->getRequest());

            if ($form->isValid()) {
                $snapshotManager = $this->get('sonata.page.manager.snapshot');

                $page = $form->getData()->getPage();
                $page->setEdited(false);

                $snapshot = $snapshotManager->create($page);

                $snapshotManager->save($snapshot);
                $pageManager->save($page);

                $snapshotManager->enableSnapshots(array($snapshot));
            }

            return $this->redirect($this->admin->generateUrl('edit', array(
                'id' => $snapshot->getId()
            )));
        }

        return $this->render('SonataPageBundle:SnapshotAdmin:create.html.twig', array(
            'action'  => 'create',
            'form'    => $form->createView()
        ));
    }

    public function editAction($id = null)
    {
        // the key used to lookup the template
        $templateKey = 'edit';

        $id = $this->get('request')->get($this->admin->getIdParameter());

        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        if (false === $this->admin->isGranted('EDIT', $object)) {
            throw new AccessDeniedException();
        }

        $this->admin->setSubject($object);

        /** @var $form \Symfony\Component\Form\Form */
        $form = $this->admin->getForm();
        $form->setData($object);

        if ($this->get('request')->getMethod() == 'POST') {
            $form->bind($this->get('request'));

            $isFormValid = $form->isValid();

            // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved())) {
                $snapshot = $form->getData();
                                
                $em = $this->getDoctrine()->getManager();
                $snapshots = $em->getRepository('Application\Sonata\PageBundle\Entity\Snapshot')->findByPage($object->getPage()->getId());
                foreach ($snapshots as $snapshotItem)
                {
                    if($snapshotItem->getId() != $snapshot->getId())
                    {
                        $snapshotItem->setEnabled(0);
                        $em->persist($snapshotItem);
                        $em->flush();
                    }
                }
                
                $this->admin->update($object);

                $this->get('session')->setFlash('sonata_flash_success', 'flash_edit_success');

                if ($this->isXmlHttpRequest()) {
                    return $this->renderJson(array(
                        'result'    => 'ok',
                        'objectId'  => $this->admin->getNormalizedIdentifier($object)
                    ));
                }

                // redirect to edit mode
                return $this->redirectTo($object);
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                if (!$this->isXmlHttpRequest()) {
                    $this->get('session')->setFlash('sonata_flash_error', 'flash_edit_error');
                }
            } elseif ($this->isPreviewRequested()) {
                // enable the preview template if the form was valid and preview was requested
                $templateKey = 'preview';
            }
        }

        $view = $form->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->admin->getFormTheme());

        return $this->render($this->admin->getTemplate($templateKey), array(
            'action' => 'edit',
            'form'   => $view,
            'object' => $object,
        ));
    }
    /**
     * @param mixed $query
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function batchActionToggleEnabled($query)
    {
        if (!$this->get('security.context')->isGranted('ROLE_SONATA_PAGE_ADMIN_BLOCK_EDIT')) {
            throw new AccessDeniedException();
        }

        $snapshotManager = $this->get('sonata.page.manager.snapshot');
        foreach ($query->getQuery()->iterate() as $snapshot) {
            $snapshot[0]->setEnabled(!$snapshot[0]->getEnabled());
            $snapshotManager->save($snapshot[0]);
        }

        return new RedirectResponse($this->admin->generateUrl('list'));
    }
}