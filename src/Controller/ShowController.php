<?php

namespace App\Controller;

use App\Repository\TmdbRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShowController extends Controller
{
    /**
     * @Route("/show/{showId}", name="show_show")
     */
    public function show($showId, TmdbRepository $tmdbRepository)
    {
        $show = $tmdbRepository->getShow($showId);
        return $this->render('show/show.html.twig', [
            'show'=>$show,
        ]);
    }
}
