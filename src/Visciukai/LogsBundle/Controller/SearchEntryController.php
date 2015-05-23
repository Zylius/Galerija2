<?php

namespace Visciukai\LogsBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Visciukai\LogsBundle\Entity\SearchEntry;

/**
 * SearchEntry controller.
 *
 */
class SearchEntryController extends Controller
{

    /**
     * Lists all SearchEntry entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VisciukaiLogsBundle:SearchEntry')->findAll();

        return $this->render('VisciukaiLogsBundle:SearchEntry:index.html.twig', array(
            'entities' => $entities,
        ));
    }
}
