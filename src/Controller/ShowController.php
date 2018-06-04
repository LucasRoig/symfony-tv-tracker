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
        }
        return $this->render('show/show.html.twig', [
            'show'=>$show,
            'isInWatchlist' => $isInWatchlist,
            'isInFollowlist' => $isInFollowList
        ]);
    }
}
