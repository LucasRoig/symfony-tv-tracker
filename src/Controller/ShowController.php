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
        if($authChecker->isGranted('ROLE_USER')){
            foreach ($this->getUser()->getWatchlist()->getValues() as $show){
                if ($show->getTmdbId() == $showId){
                    $isInWatchlist = true;
                    break;
                }
            }
        }
        return $this->render('show/show.html.twig', [
            'show'=>$show,
            'isInWatchlist' => $isInWatchlist
        ]);
    }
}
