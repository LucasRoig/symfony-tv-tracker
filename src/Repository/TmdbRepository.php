<?php

namespace App\Repository;




class TmdbRepository {
    private $searchApi;
    private $showApi;
    public function __construct ($tmdbApiKey) {
        $token = new \Tmdb\ApiToken($tmdbApiKey);
        $client = new \Tmdb\Client($token);
        $this->searchApi = $client->getSearchApi();
        $this->showApi = $client->getTvApi();
    }

    public function search($query){
        return $this->searchApi->searchTv($query);
    }

    public function getShow($showId){
        return $this->showApi->getTvshow($showId);
    }
}