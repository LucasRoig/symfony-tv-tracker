<?php

namespace App\Controller;

use App\Repository\MediaRepository;
use App\Repository\ShowRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/show/{tmdbId}/watchlist",name="watchlist_store")
     * @Method("POST")
     * @Security("has_role('ROLE_USER')")
     */
    public function store($tmdbId,ShowRepository $showRepository){
        $show = $showRepository->findOneBy(['tmdb_id' => $tmdbId]);
        $user = $this->getUser();
        $user->addWatchlist($show);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('show_show',['showId'=>$tmdbId]);
    }

    /**
     * @Route("/show/{tmdbId}/watchlist",name="watchlist_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function delete($tmdbId, Request $request){
        $user = $this->getUser();
        foreach ($user->getWatchlist() as $show){
            if ($show->getTmdbId() == $tmdbId){
                $user->removeWatchlist($show);
                break;
            }
        }
        $this->getDoctrine()->getManager()->flush();

        $redirect = $request->request->get('redirect');
        if(isset($redirect)){
            return $this->redirect($redirect);
        }
        return $this->redirectToRoute('show_show',['showId'=>$tmdbId]);
    }
}
