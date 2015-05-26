<?php

namespace Visciukai\GalerijaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Visciukai\GalerijaBundle\Entity\Album;
use Visciukai\GalerijaBundle\Form\AlbumType;

class AlbumController extends Controller
{
    /**
     * Creates a new album entity.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function AddAction(Request $request)
    {
        if ($this->getUser() === null) {
            $this->addFlash('error', 'Tiktai prisijungę vartotojai gali atlikti šią operaciją.');
            return $this->redirect($this->generateUrl('visciukai_galerija_homepage'));
        }

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new AlbumType(), null);
        $form->handleRequest($request);
        if($form->isValid()){
            $album = $form->getData();
            $album->setUser($this->getUser());
            $album->setCreatedOn(new \Datetime());
            $em->persist($album);
            $em->flush();

            return $this->redirect($this->generateUrl('visciukai_galerija_homepage'));
        }
        return $this->render(
            'VisciukaiGalerijaBundle:Album:Add.html.twig',
            array('form' => $form->createView()));
    }

    /**
     * Changes album entity.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function EditAction(Request $request, $id)
    {
        if ($this->getUser() === null) {
            $this->addFlash('error', 'Tiktai prisijungę vartotojai gali atlikti šią operaciją.');
            return $this->redirect($this->generateUrl('visciukai_galerija_homepage'));
        }

        $em = $this->getDoctrine()->getManager();
        $album = $this->getDoctrine()->getRepository('VisciukaiGalerijaBundle:Album')->find($id);

        $securityContext = $this->container->get('security.context');
        if ($this->getUser() != $album->getUser() && !$securityContext->isGranted('ROLE_SUPER_ADMIN')) {
            $this->addFlash('error', 'Jus neturite teisės redaguoti šitą albumą.');
            return $this->redirect($this->generateUrl('visciukai_galerija_homepage'));
        }

        $form = $this->createForm(new AlbumType(true), $album);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('visciukai_galerija_homepage'));
        }
        return $this->render(
            'VisciukaiGalerijaBundle:Album:Edit.html.twig',
            array(
                'form' => $form->createView(),
                'album_title' => $album->getTitle()
            )
        );
    }

    /**
     * @Route("/Delete")
     * @Template()
     */
    public function DeleteAction($id)
    {
        if ($this->getUser() === null) {
            $this->addFlash('error', 'Tiktai prisijungę vartotojai gali atlikti šią operaciją.');
            return $this->redirect($this->generateUrl('visciukai_galerija_homepage'));
        }

        $em = $this->getDoctrine()->getManager();
        $album = $this->getDoctrine()->getRepository('VisciukaiGalerijaBundle:Album')->find($id);

        $securityContext = $this->container->get('security.context');
        if ($this->getUser() != $album->getUser() && !$securityContext->isGranted('ROLE_SUPER_ADMIN')) {
            $this->addFlash('error', 'Jus neturite teisės ištrynti šitą albumą.');
            return $this->redirect($this->generateUrl('visciukai_galerija_homepage'));
        }

        $images = $album->getImages();

        foreach($images as $image){
            $em->remove($image);
            $em->flush();
        }

        $em->remove($album);
        $em->flush();

        return $this->redirect($this->generateUrl('visciukai_galerija_homepage'));
    }
}
