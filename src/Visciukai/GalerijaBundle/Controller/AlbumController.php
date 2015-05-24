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
        $em = $this->getDoctrine()->getManager();
        $album = $this->getDoctrine()->getRepository('VisciukaiGalerijaBundle:Album')->find($id);
        $form = $this->createForm(new AlbumType(false), $album);
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
        $em = $this->getDoctrine()->getManager();
        $album = $this->getDoctrine()->getRepository('VisciukaiGalerijaBundle:Album')->find($id);
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
