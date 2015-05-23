<?php

namespace Visciukai\GalerijaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Visciukai\GalerijaBundle\Entity\Comment;
use Visciukai\GalerijaBundle\Form\CommentType;
use Visciukai\ImagesBundle\Entity\Image;
use Visciukai\LogsBundle\Entity\UserAction;

/**
 * Comment controller.
 *
 */
class CommentController extends Controller
{

    public function reportAction(Request $request, $id)
    {
        $query = $this->getDoctrine()->getEntityManager()
            ->createQuery(
                'SELECT u FROM VisciukaiGalerijaBundle:User u WHERE u.roles LIKE :role'
            )->setParameter('role', '%"ROLE_SUPER_ADMIN"%');

        $users = $query->getResult();

        foreach($users as $user) {
            $message = \Swift_Message::newInstance()
                ->setSubject('Comment Reported')
                ->setFrom('trader.kursinis@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'VisciukaiGalerijaBundle:Comment:report.html.twig',
                        array('id' => $id)
                    ),
                    'text/html'
                )
            ;
            $this->get('mailer')->send($message);
        }
        return $this->redirect($request->headers->get('referer'));
    }
    /**
     * Lists all Comment entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VisciukaiGalerijaBundle:Comment')->findAll();

        return $this->render('VisciukaiGalerijaBundle:Comment:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function showImageAction($imageId)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VisciukaiImagesBundle:Image')->find($imageId)->getComments();

        return $this->render('VisciukaiGalerijaBundle:Comment:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Comment entity.
     *
     */
    public function createAction(Request $request, $imageId)
    {
        if ($this->getUser() === null) {
            $this->addFlash('error', 'Tiktai prisijungę vartotojai gali atlikti šią operaciją.');
            return $this->redirect($request->headers->get('referer'));
        }

        $entity = new Comment();
        $entity->setUser($this->getUser());
        $entity->setImage($this->getDoctrine()->getRepository('VisciukaiImagesBundle:Image')->find($imageId));
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $log = new UserAction();
            $log->setUser($this->getUser());
            $log->setAction("Pakomentavo nuotrauką, kurios id - {$entity->getImage()->getId()}.");
            $this->getDoctrine()->getEntityManager()->persist($log);

            $em->persist($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('images_show', array('id' => $imageId)));
    }

    /**
     * Creates a form to create a Comment entity.
     *
     * @param Comment $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Comment $entity)
    {
        $form = $this->createForm(new CommentType(), $entity, array(
            'action' => $this->generateUrl('comment_create', ['imageId' => $entity->getImage()->getId()]),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Komentuoti'));

        return $form;
    }

    /**
     * Displays a form to create a new Comment entity.
     *
     */
    public function newAction($imageId)
    {
        $entity = new Comment();
        $entity->setImage($this->getDoctrine()->getRepository('VisciukaiImagesBundle:Image')->find($imageId));
        $form   = $this->createCreateForm($entity);

        return $this->render('VisciukaiGalerijaBundle:Comment:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Comment entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VisciukaiGalerijaBundle:Comment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comment entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('VisciukaiGalerijaBundle:Comment:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Comment entity.
     *
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VisciukaiGalerijaBundle:Comment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comment entity.');
        }

        $securityContext = $this->container->get('security.context');
        if ($this->getUser() !== $entity->getUser() && !$securityContext->isGranted('ROLE_SUPER_ADMIN')) {
            $this->addFlash('error', 'Tiktai super administratorius arba kurėjas gali atlikti šią operaciją.');
            return $this->redirect($request->headers->get('referer'));
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('VisciukaiGalerijaBundle:Comment:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Comment entity.
    *
    * @param Comment $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Comment $entity)
    {
        $form = $this->createForm(new CommentType(), $entity, array(
            'action' => $this->generateUrl('comment_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Atnaujinti'));

        return $form;
    }
    /**
     * Edits an existing Comment entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VisciukaiGalerijaBundle:Comment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comment entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $log = new UserAction();
            $log->setUser($this->getUser());
            $log->setAction("Pakeitė komentarą {$entity->getId()} nuotraukai {$entity->getImage()->getId()}.");
            $this->getDoctrine()->getEntityManager()->persist($log);

            $this->getDoctrine()->getEntityManager()->flush();
        }

        return $this->redirect($this->generateUrl('images_show', array('id' => $entity->getImage()->getId())));
    }
    /**
     * Deletes a Comment entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('VisciukaiGalerijaBundle:Comment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Image entity.');
        }

        $securityContext = $this->container->get('security.context');
        if ($this->getUser() !== $entity->getUser() && !$securityContext->isGranted('ROLE_SUPER_ADMIN')) {
            $this->addFlash('error', 'Tiktai super administratorius arba kurėjas gali atlikti šią operaciją.');
            return $this->redirect($request->headers->get('referer'));
        }

        $log = new UserAction();
        $log->setUser($this->getUser());
        $log->setAction("Ištrynė komentarą {$entity->getId()} nuotraukoje {$entity->getImage()->getId()}.");
        $this->getDoctrine()->getEntityManager()->persist($log);

        $this->getDoctrine()->getEntityManager()->flush();

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('images_show', array('id' => $entity->getImage()->getId())));
    }

    /**
     * Creates a form to delete a Comment entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('comment_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
