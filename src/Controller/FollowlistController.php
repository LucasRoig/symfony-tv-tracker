<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FollowlistController extends Controller
{
    /**
     * @Route("/followlist", name="followlist_show")
     */
    public function index()
    {
        return $this->render('followlist/index.html.twig', [
        ]);
    }
}
