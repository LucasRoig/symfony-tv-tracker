<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShowController extends Controller
{
    /**
     * @Route("/show/{showId}", name="show_show")
     */
    public function show($showId)
    {
        return $this->render('show/show.html.twig', [
            'controller_name' => 'ShowController',
        ]);
    }
}
