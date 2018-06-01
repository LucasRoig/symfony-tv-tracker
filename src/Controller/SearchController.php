<?php

namespace App\Controller;

use App\Repository\TmdbRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SearchController extends Controller
{
    /**
     * @Route("/search", name="search_index")
     */
    public function index(Request $request, TmdbRepository $tmdbRepository)
    {
        $query = $request->query->get('query');
        $results = $tmdbRepository->search($query);
        return $this->render('search/index.html.twig', [
            'results' => $results,
            'query' => $query,
        ]);
    }
}
