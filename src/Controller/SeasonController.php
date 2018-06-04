<?php

namespace App\Controller;

use App\Repository\MediaRepository;
use App\Repository\TmdbRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SeasonController extends Controller
{
    /**
     * @Route("show/{showId}/season/{seasonNumber}", name="season_show")
     * @param $showId
     * @param $seasonNumber
     * @param MediaRepository $mediaRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index($showId,$seasonNumber, MediaRepository $mediaRepository, AuthorizationCheckerInterface $authChecker)
    {
        $season = $mediaRepository->getSeasonByTmdbId($showId,$seasonNumber);
        $isInHistory = false;
        if($authChecker->isGranted('ROLE_USER')) {
            $isInHistory = $this->getUser()->getWatchedEpisodeCountForSeason($showId, $seasonNumber) == $season->getEpisodes()->count();
        }
        return $this->render('season/show.html.twig', [
            'season' => $season,
            'isInHistory' => $isInHistory
        ]);
    }
}
