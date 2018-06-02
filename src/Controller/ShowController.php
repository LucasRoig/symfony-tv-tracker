<?php

namespace App\Controller;

use App\Repository\ShowRepository;
use App\Repository\TmdbRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShowController extends Controller
{
    /**
     * @Route("/show/{showId}", name="show_show")
     */
    public function show($showId, TmdbRepository $tmdbRepository, ShowRepository $showRepository)
    {
        $show = $showRepository->findOneByTmdbId($showId);
        return $this->render('show/show.html.twig', [
            'show'=>$show,
        ]);
    }
}
