<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SeasonController extends Controller
{
    /**
     * @Route("show/{showId}/season/{seasonNumber}", name="season_show")
     */
    public function index($showId,$seasonNumber)
    {
        return $this->render('season/show.html.twig', [

        ]);
    }
}
