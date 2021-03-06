<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(){
        //render lives inside the abstract controller.
        return $this->render('home/index.html.twig');
        // The first parameter is where the template can be found. Home is the folder. The rest is the name of the file.
        //Second parameter
    }


    /**
     * @Route("/custom/{name?}", name="custom")
     * @param Request $request
     * @return Response
     */

    public function custom(Request $request){
        $name = $request ->get('name');

        return $this->render('home/custom.html.twig', [
            'name' => $name
        ]);
    }

}
