<?php

namespace App\Controller;

use App\Repository\MediaRepository;
use App\Repository\TmdbRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EpisodeController extends Controller
{
    /**
     * @Route("show/{showId}/season/{seasonNumber}/episode/{episodeNumber}", name="episode_show")
     */
    public function index($showId, $seasonNumber, $episodeNumber, MediaRepository $mediaRepository)
    {
        $episode = $mediaRepository->getEpisodeByTmdbId($showId,$seasonNumber,$episodeNumber);
        return $this->render('episode/show.html.twig', [
            "episode" => $episode,
            "season" => $episode->getSeason(),
            "show" => $episode->getTvShow()
        ]);
    }
}
