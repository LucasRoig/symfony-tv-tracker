<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WatchlistController extends Controller
{
    /**
     * @Route("/watchlist", name="watchlist_index")
     */
    public function index()
    {
        return $this->render('watchlist/index.html.twig', [
            'controller_name' => 'WatchlistController',
        ]);
    }
}
