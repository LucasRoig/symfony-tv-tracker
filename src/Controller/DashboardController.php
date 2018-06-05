<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    /**
     * @Route("/", name="dashboard_index")
     * @Security("has_role('ROLE_USER')")
     */
    public function index()
    {
        $airingSoon = $this->getUser()->getFollowlist()->slice(0,2);
        $airingSoon = array_filter($airingSoon,function($s){
            return $s->isHot();
        });

        $epHistory = $this->getUser()->getHistory();
        $showHistory = $epHistory->map(function ($e){
            return $e->getTvShow();
        });
        $showUnique = array();
        foreach ($showHistory as $show){
            $showUnique[$show->getId()] = $show;
        }

        //dd($showUnique);
        $showHistory = array_values($showUnique);
        $notCompletedShows = array();
        foreach ($showHistory as $show){
            if(!$this->getUser()->hasCompletedShow($show)){
                $notCompletedShows[] = $show;
            }
        }
        $notCompletedShows = array_slice($notCompletedShows,0,2);

        return $this->render('dashboard/index.html.twig', [
            'airingSoon' => $airingSoon,
            'nextToWatch' => $notCompletedShows
        ]);
    }
}
