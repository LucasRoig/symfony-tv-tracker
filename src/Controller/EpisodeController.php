<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EpisodeController extends Controller
{
    /**
     * @Route("show/{showId}/season/{seasonNumber}/episode/{episodeNumber}", name="episode_show")
     */
    public function index($showId, $seasonNumber, $episodeNumber)
    {
        return $this->render('episode/show.html.twig', [
        ]);
    }
}
