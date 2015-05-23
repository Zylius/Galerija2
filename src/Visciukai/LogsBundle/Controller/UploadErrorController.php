<?php

namespace Visciukai\LogsBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Visciukai\LogsBundle\Entity\UploadError;

/**
 * UploadError controller.
 *
 */
class UploadErrorController extends Controller
{

    /**
     * Lists all UploadError entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VisciukaiLogsBundle:UploadError')->findAll();

        return $this->render('VisciukaiLogsBundle:UploadError:index.html.twig', array(
            'entities' => $entities,
        ));
    }

}
