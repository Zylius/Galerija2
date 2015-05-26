<?php

namespace Visciukai\GalerijaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $albums = $this->getDoctrine()->getRepository('VisciukaiGalerijaBundle:Album')->findAll();
        return $this->render('VisciukaiGalerijaBundle:Default:index.html.twig',
            array(
                'albums' => $albums
            ));
    }
}
