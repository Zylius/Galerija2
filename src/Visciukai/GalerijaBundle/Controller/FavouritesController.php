<?php

namespace Visciukai\GalerijaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Visciukai\LogsBundle\Entity\UserAction;

class FavouritesController extends Controller
{
    public function showAction(Request $request)
    {
        if ($this->getUser() === null) {
            $this->addFlash('error', 'Tiktai prisijungę vartotojai gali atlikti šią operaciją.');
            return $this->redirect($request->headers->get('referer'));
        }

        $favourites = $this->getUser()->getFavouriteImages();

        return $this->render('VisciukaiGalerijaBundle:Favourites:show.html.twig', array(
                'favourites' => $favourites
            ));
    }

    public function addAction(Request $request, $imageId)
    {
        if ($this->getUser() === null) {
            $this->addFlash('error', 'Tiktai prisijungę vartotojai gali atlikti šią operaciją.');
            return $this->redirect($request->headers->get('referer'));
        }

        $em = $this->getDoctrine()->getManager();
        $imageToAdd = $em->getRepository('VisciukaiImagesBundle:Image')->find($imageId);

        $user = $this->getUser();

        $user->addFavouriteImage($imageToAdd);

        $em->persist($user);
        $em->flush();

        $log = new UserAction();
        $log->setUser($this->getUser());
        $log->setAction("Pridėjo nuotrauką {$imageToAdd->getId()} į favoritus.");
        $this->getDoctrine()->getEntityManager()->persist($log);

        $this->getDoctrine()->getEntityManager()->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    public function removeAction(Request $request, $imageId)
    {
        if ($this->getUser() === null) {
            $this->addFlash('error', 'Tiktai prisijungę vartotojai gali atlikti šią operaciją.');
            return $this->redirect($request->headers->get('referer'));
        }

        $em = $this->getDoctrine()->getManager();
        $imageToRemove = $em->getRepository('VisciukaiImagesBundle:Image')->find($imageId);

        $user = $this->getUser();

        $user->removeFavouriteImage($imageToRemove);

        $em->persist($user);
        $em->flush();

        $log = new UserAction();
        $log->setUser($this->getUser());
        $log->setAction("Pašalino nuotrauką {$imageToRemove->getId()} iš favoritų.");
        $this->getDoctrine()->getEntityManager()->persist($log);

        $this->getDoctrine()->getEntityManager()->flush();

        return $this->redirect($request->headers->get('referer'));
    }

}
