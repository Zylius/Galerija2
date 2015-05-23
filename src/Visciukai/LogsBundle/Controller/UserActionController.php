<?php

namespace Visciukai\LogsBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Visciukai\LogsBundle\Entity\UserAction;

/**
 * UserAction controller.
 *
 */
class UserActionController extends Controller
{

    /**
     * Lists all UserAction entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VisciukaiLogsBundle:UserAction')->findAll();

        return $this->render('VisciukaiLogsBundle:UserAction:index.html.twig', array(
            'entities' => $entities,
        ));
    }
}
