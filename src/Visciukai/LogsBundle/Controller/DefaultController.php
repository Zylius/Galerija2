<?php

namespace Visciukai\LogsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('VisciukaiLogsBundle:Default:index.html.twig', array('name' => $name));
    }
}
