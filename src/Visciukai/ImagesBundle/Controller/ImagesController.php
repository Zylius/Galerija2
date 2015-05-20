<?php

namespace Visciukai\ImagesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ImagesController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('VisciukaiImagesBundle:Default:index.html.twig', array('name' => $name));
    }
}
