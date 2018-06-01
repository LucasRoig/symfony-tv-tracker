<?php

namespace App\Controller;

use App\Repository\TmdbRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EpisodeController extends Controller
{
    /**
     * @Route("show/{showId}/season/{seasonNumber}/episode/{episodeNumber}", name="episode_show")
     */
    public function index($showId, $seasonNumber, $episodeNumber, TmdbRepository $tmdbRepository)
    {
        $episode = $tmdbRepository->getEpisode($showId,$seasonNumber,$episodeNumber);
        $season = $tmdbRepository->getSeason($showId,$seasonNumber);
        $show = $tmdbRepository->getShow($showId);
        return $this->render('episode/show.html.twig', [
            "episode" => $episode,
            "season" => $season,
            "show" => $show
        ]);
    }
}
