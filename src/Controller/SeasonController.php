<?php

namespace App\Controller;

use App\Repository\TmdbRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SeasonController extends Controller
{
    /**
     * @Route("show/{showId}/season/{seasonNumber}", name="season_show")
     */
    public function index($showId,$seasonNumber, TmdbRepository $tmdbRepository)
    {
        $season = $tmdbRepository->getSeason($showId,$seasonNumber);
        $show = $tmdbRepository->getShow($showId);
        return $this->render('season/show.html.twig', [
            'season' => $season,
            'show' => $show,
        ]);
    }
}
