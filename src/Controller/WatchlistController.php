<?php

namespace App\Controller;

use App\Repository\MediaRepository;
use App\Repository\ShowRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WatchlistController extends Controller
{
    /**
     * @Route("/watchlist", name="watchlist_index")
     * @Security("has_role('ROLE_USER')")
     */
    public function index(MediaRepository $mediaRepository)
    {
        $shows = $this->getUser()->getWatchlist();
        $shows->map(function ($s) use ($mediaRepository){
            return $mediaRepository->getShowByTmdbId($s->getTmdbId());
        });
        return $this->render('watchlist/index.html.twig', [
            "shows" => $shows
        ]);
    }

    /**
     * @Route("/show/{tmdbId}/watchlist",name="watchlist_store",methods={"POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function store($tmdbId,ShowRepository $showRepository){
        $show = $showRepository->findOneBy(['tmdb_id' => $tmdbId]);
        $user = $this->getUser();
        $user->addWatchlist($show);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('show_show',['showId'=>$tmdbId]);
    }
}
