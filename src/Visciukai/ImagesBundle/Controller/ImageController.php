<?php

namespace Visciukai\ImagesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Visciukai\GalerijaBundle\Entity\Album;
use Visciukai\ImagesBundle\Entity\Image;
use Visciukai\ImagesBundle\Form\ImageTagType;
use Visciukai\ImagesBundle\Form\ImageType;

/**
 * Image controller.
 *
 */
class ImageController extends Controller
{

    /**
     * Lists images by tag.
     *
     * @param string $search
     * @param null $albumId
     */
    public function tagAction(Request $request, $albumId = null)
    {
        $search = $request->get('tag');
        $album = null;
        $em = $this->getDoctrine()->getManager();

        if($albumId !== null) {
            $album = $this->getDoctrine()->getRepository('VisciukaiGalerijaBundle:Album')->find($albumId);
        }

        $images = $em->getRepository('VisciukaiImagesBundle:Image')->findByTags($search, $album);

       if ($album === null) {
            $album = new Album();
            $album->setTitle('pagal tagus "' . $search . '".');
        }
        $album->setImages($images);
        return $this->render('VisciukaiImagesBundle:Image:index.html.twig', array(
            'album' => $album,
        ));
    }

    /**
     * Sets this image as the cover photo.
     *
     * @param $id
     */
    public function makeCoverPhotoAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Image $entity */
        $entity = $em->getRepository('VisciukaiImagesBundle:Image')->find($id);
        $securityContext = $this->container->get('security.context');
        if ($this->getUser() !== $entity->getAlbum()->getUser() && !$securityContext->isGranted('ROLE_SUPER_ADMIN')) {
            $this->addFlash('error', 'Tiktai super administratorius arba albumo kurėjas gali atlikti šią operaciją.');
            return $this->redirect($request->headers->get('referer'));
        }

        $entity->getAlbum()->setCoverPhoto($entity);
        $em->flush();
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * Lists all Image entities.
     *
     * @param $albumId
     *
     * @return Response
     */
    public function indexAction($albumId)
    {
        $album = $this->getDoctrine()->getRepository('VisciukaiGalerijaBundle:Album')->find($albumId);
        $entity = new Image();
        $entity->setAlbum($album);
        $form  = $this->createCreateForm($entity);

        return $this->render('VisciukaiImagesBundle:Image:index.html.twig', array(
            'album' => $album,
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }
    /**
     * Creates a new Image entity.
     *
     * @param Request $request
     * @param $albumId
     *
     * @return Response
     */
    public function createAction(Request $request, $albumId)
    {
        $entity = new Image();
        $entity->setUser($this->getUser());
        $entity->setAlbum($this->getDoctrine()->getRepository('VisciukaiGalerijaBundle:Album')->find($albumId));
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

        } else {
            $errors = $form->getErrors(true);
            foreach ($errors as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }

        return $this->redirect($this->generateUrl('images', array('albumId' => $albumId)));
    }

    /**
     * Creates a form to create a Image entity.
     *
     * @param Image $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Image $entity)
    {
        $form = $this->createForm(new ImageType(true), $entity, array(
            'action' => $this->generateUrl('images_create', ['albumId' => $entity->getAlbum()->getId()]),
            'method' => 'POST',
        ));


        return $form;
    }

    /**
     * Finds and displays a Image entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VisciukaiImagesBundle:Image')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Image entity.');
        }

        return $this->render('VisciukaiImagesBundle:Image:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing Image entity.
     *
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VisciukaiImagesBundle:Image')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Image entity.');
        }

        $securityContext = $this->container->get('security.context');
        if ($this->getUser() !== $entity->getUser() && !$securityContext->isGranted('ROLE_SUPER_ADMIN')) {
            $this->addFlash('error', 'Tiktai super administratorius arba kurėjas gali atlikti šią operaciją.');
            return $this->redirect($request->headers->get('referer'));
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('VisciukaiImagesBundle:Image:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Image entity.
    *
    * @param Image $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Image $entity)
    {
        $form = $this->createForm(new ImageType(false), $entity, array(
            'action' => $this->generateUrl('images_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing Image entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VisciukaiImagesBundle:Image')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Image entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('images_edit', array('id' => $id)));
        }

        return $this->render('VisciukaiImagesBundle:Image:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Image entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('VisciukaiImagesBundle:Image')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Image entity.');
        }

        $securityContext = $this->container->get('security.context');
        if ($this->getUser() !== $entity->getUser() && !$securityContext->isGranted('ROLE_SUPER_ADMIN')) {
            $this->addFlash('error', 'Tiktai super administratorius arba kurėjas gali atlikti šią operaciją.');
            return $this->redirect($request->headers->get('referer'));
        }

        foreach ($entity->getTags() as $tag) {
            $em->remove($tag);
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('images', array('albumId' => $entity->getAlbum()->getId())));
    }

    /**
     * Creates a form to delete a Image entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('images_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * Handles tags.
     *
     * @param $request
     * @param $id
     *
     * @return Response
     */
    public function handleTagsAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('VisciukaiImagesBundle:Image')->find($id);
        $em->getRepository('VisciukaiImagesBundle:Tag')->handleTags($request->query->get('tags'), $entity);
        return $this->redirect($request->headers->get('referer'));
    }
}
