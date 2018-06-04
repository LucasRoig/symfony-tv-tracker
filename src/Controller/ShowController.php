<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\MediaRepository;
use App\Repository\ShowRepository;
use App\Repository\TmdbRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ShowController extends Controller
{
    /**
     * @Route("/show/{showId}", name="show_show")
     */
    public function show($showId, TmdbRepository $tmdbRepository, MediaRepository $mediaRepository, AuthorizationCheckerInterface $authChecker)
    {
        $user = new User();
        $show = $mediaRepository->getShowByTmdbId($showId);

        $isInWatchlist = false;
        $isInFollowList = false;
        $isInHistory = false;
        if($authChecker->isGranted('ROLE_USER')){
            foreach ($this->getUser()->getWatchlist() as $s){
                if ($s->getTmdbId() == $showId){
                    $isInWatchlist = true;
                    break;
                }
            }
            foreach ($this->getUser()->getFollowlist() as $s){
                if ($s->getTmdbId() == $showId){
                    $isInFollowList = true;
                    break;
                }
            }
            $historyList = $this->getUser()->getHistory();
            $historyList = $historyList->filter(function ($e) use ($showId) {
               return $e->getTvShow()->getTmdbId() == $showId;
            });
            $isInHistory = $historyList->count() == $show->getEpisodes()->count();
        }

        return $this->render('show/show.html.twig', [
            'show'=>$show,
            'isInWatchlist' => $isInWatchlist,
            'isInFollowlist' => $isInFollowList,
            'isInHistory' => $isInHistory
        ]);
    }
}
