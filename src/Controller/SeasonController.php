<?php

namespace App\Controller;

use App\Repository\MediaRepository;
use App\Repository\TmdbRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SeasonController extends Controller
{
    /**
     * @Route("show/{showId}/season/{seasonNumber}", name="season_show")
     * @param $showId
     * @param $seasonNumber
     * @param MediaRepository $mediaRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index($showId,$seasonNumber, MediaRepository $mediaRepository)
    {
        $season = $mediaRepository->getSeasonByTmdbId($showId,$seasonNumber);
        return $this->render('season/show.html.twig', [
            'season' => $season,
        ]);
    }
}
