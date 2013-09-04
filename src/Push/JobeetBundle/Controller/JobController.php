<?php

namespace Push\JobeetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Push\JobeetBundle\Entity\Job;
use Push\JobeetBundle\Form\JobType;

/**
 * Job controller.
 *
 */
class JobController extends Controller
{
    /**
     * Lists all Job entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
          $categories = $em->getRepository('PushJobeetBundle:Category')->getWithJobs();
//        $entities = $em->getRepository('PushJobeetBundle:Job')->getActiveJobs();;
//        $query = $em->createQuery(
//        'SELECT j FROM PushJobeetBundle:Job j WHERE j.created_at > :date'
//        )->setParameter('date', date('Y-m-d H:i:s', time() - 86400 * 30));
//        $entities = $query->getResult();
//        echo date('Y-m-d H:i:s', time() - 86400 * 30); exit;.
        foreach($categories as $category)
        {
            $category->setActiveJobs($em->getRepository('PushJobeetBundle:Job')->getActiveJobs($category->getId(), $this->container->getParameter('max_jobs_on_homepage')));
            $category->setMoreJobs($em->getRepository('PushJobeetBundle:Job')->countActiveJobs($category->getId()) - $this->container->getParameter('max_jobs_on_homepage'));
            
        }
        $format = $this->getRequest()->getRequestFormat();
 
//return $this->render('EnsJobeetBundle:Job:index.'.$format.'.twig', array(
//    'categories' => $categories
//));
//        return $this->render('PushJobeetBundle:Job:index.html.twig', array(
////            'entities' => $entities,
//             'categories' => $categories
//        ));
        return $this->render('PushJobeetBundle:Job:index.'.$format.'.twig', array(
            'categories' => $categories,
            'lastUpdated' => $em->getRepository('PushJobeetBundle:Job')->getLatestPost()->getCreatedAt()->format(DATE_ATOM),
            'feedId' => sha1($this->get('router')->generate('push_job', array('_format'=> 'atom'), true)),
        ));
    }

    /**
     * Finds and displays a Job entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PushJobeetBundle:Job')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Job entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PushJobeetBundle:Job:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Job entity.
     *
     */
//    public function newAction()
//    {
//        $entity = new Job();
//        $form   = $this->createForm(new JobType(), $entity);
//
//        return $this->render('PushJobeetBundle:Job:new.html.twig', array(
//            'entity' => $entity,
//            'form'   => $form->createView(),
//        ));
//    }
    public function newAction()
    {
      $entity = new Job();
      $entity->setType('full-time');
      $form   = $this->createForm(new JobType(), $entity);

      return $this->render('PushJobeetBundle:Job:new.html.twig', array(
        'entity' => $entity,
        'form'   => $form->createView()
      ));
    }

    /**
     * Creates a new Job entity.
     *
     */
    public function createAction()
    {
      $entity  = new Job();
      $request = $this->getRequest();
      $form    = $this->createForm(new JobType(), $entity);
      $form->bind($request);

      if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
//
//            $entity->file->move(__DIR__.'/../../../../web/uploads/jobs', $entity->file->getClientOriginalName());
//            $entity->setLogo($entity->file->getClientOriginalName());

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('push_job_preview', array(
                'company' => $entity->getCompanySlug(),
                'location' => $entity->getLocationSlug(),
                'token' => $entity->getToken(),
                'position' => $entity->getPositionSlug()
            )));
        }

      return $this->render('PushJobeetBundle:Job:new.html.twig', array(
        'entity' => $entity,
        'form'   => $form->createView()
      ));
    }

    /**
     * Displays a form to edit an existing Job entity.
     *
     */
    public function editAction($token)
    {
      $em = $this->getDoctrine()->getEntityManager();

      $entity = $em->getRepository('PushJobeetBundle:Job')->findOneByToken($token);

      if (!$entity) {
        throw $this->createNotFoundException('Unable to find Job entity.');
      }

      $editForm = $this->createForm(new JobType(), $entity);
      $deleteForm = $this->createDeleteForm($token);

      return $this->render('PushJobeetBundle:Job:edit.html.twig', array(
        'entity'      => $entity,
        'edit_form'   => $editForm->createView(),
        'delete_form' => $deleteForm->createView(),
      ));
    }

    public function updateAction($token)
    {
      $em = $this->getDoctrine()->getEntityManager();

      $entity = $em->getRepository('PushJobeetBundle:Job')->findOneByToken($token);

      if (!$entity) {
        throw $this->createNotFoundException('Unable to find Job entity.');
      }

      $editForm   = $this->createForm(new JobType(), $entity);
      $deleteForm = $this->createDeleteForm($token);

      $request = $this->getRequest();

      $editForm->bind($request);

      if ($editForm->isValid()) {
        $em->persist($entity);
        $em->flush();

//        return $this->redirect($this->generateUrl('push_job_edit', array('token' => $token)));
        return $this->redirect($this->generateUrl('push_job_preview', array(
        'company' => $entity->getCompanySlug(),
        'location' => $entity->getLocationSlug(),
        'token' => $entity->getToken(),
        'position' => $entity->getPositionSlug()
        )));
      }

      return $this->render('PushJobeetBundle:Job:edit.html.twig', array(
        'entity'      => $entity,
        'edit_form'   => $editForm->createView(),
        'delete_form' => $deleteForm->createView(),
      ));
    }

    public function deleteAction($token)
    {
      $form = $this->createDeleteForm($token);
      $request = $this->getRequest();

      $form->bindRequest($request);

      if ($form->isValid()) {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('PushJobeetBundle:Job')->findOneByToken($token);

        if (!$entity) {
          throw $this->createNotFoundException('Unable to find Job entity.');
        }

        $em->remove($entity);
        $em->flush();
      }

      return $this->redirect($this->generateUrl('push_job'));
    }

    private function createDeleteForm($token)
    {
      return $this->createFormBuilder(array('token' => $token))
        ->add('token', 'hidden')
        ->getForm()
      ;
    }
    
    public function previewAction($token)
    {
      $em = $this->getDoctrine()->getEntityManager();

      $entity = $em->getRepository('PushJobeetBundle:Job')->findOneByToken($token);

      if (!$entity) {
        throw $this->createNotFoundException('Unable to find Job entity.');
      }

      $deleteForm = $this->createDeleteForm($entity->getId());
      $publishForm = $this->createPublishForm($entity->getToken());
 
        return $this->render('PushJobeetBundle:Job:show.html.twig', array(
          'entity'      => $entity,
          'delete_form' => $deleteForm->createView(),
          'publish_form' => $publishForm->createView(),
        ));
    }
    
    public function publishAction($token)
    {
      $form = $this->createPublishForm($token);
      $request = $this->getRequest();

      $form->bind($request);

      if ($form->isValid()) {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('PushJobeetBundle:Job')->findOneByToken($token);

        if (!$entity) {
          throw $this->createNotFoundException('Unable to find Job entity.');
        }

        $entity->publish();
        $em->persist($entity);
        $em->flush();

        $this->get('session')->getFlashBag('notice', 'Your job is now online for 30 days.');
      }

      return $this->redirect($this->generateUrl('push_job_preview', array(
        'company' => $entity->getCompanySlug(),
        'location' => $entity->getLocationSlug(),
        'token' => $entity->getToken(),
        'position' => $entity->getPositionSlug()
      )));
    }

    private function createPublishForm($token)
    {
      return $this->createFormBuilder(array('token' => $token))
        ->add('token', 'hidden')
        ->getForm()
      ;
    }
    
}
