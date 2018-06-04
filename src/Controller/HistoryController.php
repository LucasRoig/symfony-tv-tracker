<?php

namespace App\Controller;

use App\Repository\EpisodeRepository;
use App\Repository\SeasonRepository;
use App\Repository\ShowRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HistoryController extends Controller
{
    /**
     * @Route("/history", name="history_index")
     * @Security("has_role('ROLE_USER')")
     */
    public function index()
    {
        $user = $this->getUser();
        $episodes = $user->getHistory();
        //dd(count($episodes));
        $shows = $episodes->map(function ($e){
            return $e->getTvShow();
        });
        $showsUnique = array();
        foreach ($shows as $show){
            $showsUnique[$show->getId()] = $show;
        }
        $shows = array_values($showsUnique);

        $completedShows = array();
        $notCompletedShows = array();

        foreach ($shows as $show){
            if($user->hasCompletedShow($show)){
                $completedShows[] = $show;
            }else{
                $notCompletedShows[] = $show;
            }
        }

        return $this->render('history/index.html.twig', [
            'notCompletedShows' => $notCompletedShows,
            'completedShows' => $completedShows,
        ]);
    }

    /**
     * @Route("/show/{tmdbId}/season/{seasonNumber}/episode/{episodeNumber}/history",name="history_store_episode")
     * @Method("POST")
     * @Security("has_role('ROLE_USER')")
     */
    public function storeEpisode($tmdbId, $seasonNumber, $episodeNumber, EpisodeRepository $episodeRepository, Request $request){
        $episode = $episodeRepository->findOneBy([
            "tv_show" => $tmdbId,
            "season_number" => $seasonNumber,
            "episode_number" => $episodeNumber
        ]);
        $user = $this->getUser();
        $user->addToHistory($episode);
        $this->getDoctrine()->getManager()->flush();

        $redirect = $request->request->get('redirect');
        if(isset($redirect)){
            return $this->redirect($redirect);
        }

        return $this->redirectToRoute("episode_show", [
            "showId" => $episode->getTvShow()->getTmdbId(),
            "seasonNumber" => $episode->getSeasonNumber(),
            "episodeNumber" => $episode->getEpisodeNumber()
        ]);
    }

    /**
     * @Route("/show/{tmdbId}/season/{seasonNumber}/episode/{episodeNumber}/history",name="history_delete_episode")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteEpisode($tmdbId, $seasonNumber, $episodeNumber, EpisodeRepository $episodeRepository, Request $request){
        $episode = $episodeRepository->findOneBy([
            "tv_show" => $tmdbId,
            "season_number" => $seasonNumber,
            "episode_number" => $episodeNumber
        ]);
        $user = $this->getUser();
        $user->removeFromHistory($episode);
        $this->getDoctrine()->getManager()->flush();

        $redirect = $request->request->get('redirect');
        if(isset($redirect)){
            return $this->redirect($redirect);
        }

        return $this->redirectToRoute("episode_show", [
            "showId" => $episode->getTvShow()->getTmdbId(),
            "seasonNumber" => $episode->getSeasonNumber(),
            "episodeNumber" => $episode->getEpisodeNumber()
        ]);
    }

    /**
     * @Route("/show/{tmdbId}/season/{seasonNumber}/history",name="history_store_season")
     * @Method("POST")
     * @Security("has_role('ROLE_USER')")
     */
    public function storeSeason($tmdbId,$seasonNumber, SeasonRepository $seasonRepository, Request $request){
        $season = $seasonRepository->findOneBy([
            "tmdb_show_id" => $tmdbId,
            "season_number" => $seasonNumber
        ]);
        $user = $this->getUser();
        foreach ($season->getEpisodes() as $episode){
            $user->addToHistory($episode);
        }
        $this->getDoctrine()->getManager()->flush();

        $redirect = $request->request->get('redirect');
        if(isset($redirect)){
            return $this->redirect($redirect);
        }

        return $this->redirectToRoute("season_show",[
            "showId" => $tmdbId,
            "seasonNumber" => $seasonNumber
        ]);
    }

    /**
     * @Route("/show/{tmdbId}/season/{seasonNumber}/history",name="history_delete_season")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteSeason($tmdbId,$seasonNumber, SeasonRepository $seasonRepository, Request $request){
        $season = $seasonRepository->findOneBy([
            "tmdb_show_id" => $tmdbId,
            "season_number" => $seasonNumber
        ]);
        $user = $this->getUser();
        foreach ($season->getEpisodes() as $episode){
            $user->removeFromHistory($episode);
        }
        $this->getDoctrine()->getManager()->flush();

        $redirect = $request->request->get('redirect');
        if(isset($redirect)){
            return $this->redirect($redirect);
        }

        return $this->redirectToRoute("season_show",[
            "showId" => $tmdbId,
            "seasonNumber" => $seasonNumber
        ]);
    }

    /**
     * @Route("/show/{tmdbId}/history",name="history_store_show")
     * @Method("POST")
     * @Security("has_role('ROLE_USER')")
     */
    public function storeShow($tmdbId, ShowRepository $showRepository, Request $request){
        $show = $showRepository->findOneBy(["tmdb_id" => $tmdbId]);
        $user = $this->getUser();
        foreach ($show->getSeasons() as $season){
            foreach ($season->getEpisodes() as $episode){
                $user->addToHistory($episode);
            }
        }
        $this->getDoctrine()->getManager()->flush();

        $redirect = $request->request->get('redirect');
        if(isset($redirect)){
            return $this->redirect($redirect);
        }

        return $this->redirectToRoute("show_show", [
            "showId" => $tmdbId
        ]);
    }

    /**
     * @Route("/show/{tmdbId}/history",name="history_delete_show")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteShow($tmdbId, ShowRepository $showRepository, Request $request){
        $show = $showRepository->findOneBy(["tmdb_id" => $tmdbId]);
        $user = $this->getUser();
        foreach ($show->getSeasons() as $season){
            foreach ($season->getEpisodes() as $episode){
                $user->removeFromHistory($episode);
            }
        }
        $this->getDoctrine()->getManager()->flush();

        $redirect = $request->request->get('redirect');
        if(isset($redirect)){
            return $this->redirect($redirect);
        }

        return $this->redirectToRoute("show_show", [
            "showId" => $tmdbId
        ]);
    }
}
