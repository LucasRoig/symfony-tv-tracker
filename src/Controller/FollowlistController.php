<?php

namespace App\Controller;

use App\Repository\MediaRepository;
use App\Repository\ShowRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FollowlistController extends Controller
{
    /**
     * @Route("/followlist", name="followlist_index")
     * @Security("has_role('ROLE_USER')")
     */
    public function index(MediaRepository $mediaRepository)
    {
        $shows = $this->getUser()->getFollowList();

        $shows->map(function ($s) use ($mediaRepository){
            return $mediaRepository->getShowByTmdbId($s->getTmdbId());
        });
        $iterator = $shows->getIterator();
        $iterator->uasort(function ($first, $second){
            if ($first->isHot() && $second->isHot()){
                return $first->getNextAiredEpisode()->getAirDate() > $second->getNextAiredEpisode()->getAirDate();
            }
            if ($first->isHot()) return -1;
            if ($second->isHot()) return 1;

            if($first->getInProduction() && $second->getInProduction()){
                return $first->getLastAirDate() < $second->getLastAirDate();
            }
            if($first->getInProduction()) return -1;
            if($second->getInProduction()) return 1;

            return $first->getLastAirDate() < $second->getLastAirDate();

        });
        return $this->render('followlist/index.html.twig', [
            'shows' => $iterator,
        ]);
    }

    /**
     * @Route("/show/{tmdbId}/follow", name="followlist_store")
     * @Method("POST")
     * @Security("has_role('ROLE_USER')")
     */
    public function store($tmdbId, ShowRepository $showRepository){
        $user = $this->getUser();
        $show = $showRepository->findOneBy(["tmdb_id" => $tmdbId]);
        $user->addToFollowList($show);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute("show_show",["showId"=>$tmdbId]);
    }

    /**
     * @Route("/show/{tmdbId}/follow", name="followlist_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     * @param $tmdbId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete($tmdbId, Request $request){
        $user = $this->getUser();
        foreach ($user->getFollowlist() as $show){
            if ($show->getTmdbId() == $tmdbId){
                $user->removeFromFollowlist($show);
            }
        }
        $this->getDoctrine()->getManager()->flush();

        $redirect = $request->request->get('redirect');
        if(isset($redirect)){
            return $this->redirect($redirect);
        }

        return $this->redirectToRoute("show_show",["showId"=>$tmdbId]);
    }
}
