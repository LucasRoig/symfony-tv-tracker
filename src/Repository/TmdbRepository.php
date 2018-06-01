<?php

namespace App\Repository;




class TmdbRepository {
    private $searchApi;
    private $showApi;
    private $seasonApi;
    public function __construct ($tmdbApiKey) {
        $token = new \Tmdb\ApiToken($tmdbApiKey);
        $client = new \Tmdb\Client($token);
        $this->searchApi = $client->getSearchApi();
        $this->showApi = $client->getTvApi();
        $this->seasonApi = $client->getTvSeasonApi();
    }

    public function search($query){
        return $this->searchApi->searchTv($query);
    }

    public function getShow($showId){
        return $this->showApi->getTvshow($showId);
    }

    public function getSeason($showId, $seasonNumber){
        return $this->seasonApi->getSeason($showId, $seasonNumber);
    }
}