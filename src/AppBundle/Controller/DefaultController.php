<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/support", name="supportPage")
     */
    public function aboutUsAction(){

            return $this->render('aboutus/Support.html.twig');
    }
    /**
     * @Route("/table", name="tableHoliday")
     */
    public function viewHolidayPlan(){

        return $this->render('personal/table.html.twig');
    }

    /**
     * @Route("/cleims", name="viewClaims")
     */
    public function viewClaims(){

        return $this->render('claims/allClaims.html.twig');
    }

    /**
     * @Route("/agenda", name="viewAgenda")
     */
    public function viewAgenda(){

        return $this->render('agenda/allAppointments.html.twig');
    }

}
