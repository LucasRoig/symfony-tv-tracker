<?php

namespace App\Controller;

use App\Repository\MediaRepository;
use App\Repository\TmdbRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class EpisodeController extends Controller
{
    /**
     * @Route("show/{showId}/season/{seasonNumber}/episode/{episodeNumber}", name="episode_show")
     */
    public function index($showId, $seasonNumber, $episodeNumber, MediaRepository $mediaRepository, AuthorizationCheckerInterface $authChecker)
    {

        $episode = $mediaRepository->getEpisodeByTmdbId($showId,$seasonNumber,$episodeNumber);
        $isInHistory = false;
        if($authChecker->isGranted('ROLE_USER')) {
            $isInHistory = $this->getUser()->isEpisodeWatched($episode);
        }

        return $this->render('episode/show.html.twig', [
            "episode" => $episode,
            "season" => $episode->getSeason(),
            "show" => $episode->getTvShow(),
            "isInHistory" => $isInHistory
        ]);
    }
}
