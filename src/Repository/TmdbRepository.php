<?php

namespace App\Repository;




class TmdbRepository {
    private $searchRepository;

    public function __construct ($tmdbApiKey) {
        $token = new \Tmdb\ApiToken($tmdbApiKey);
        $client = new \Tmdb\Client($token);
        $this->searchRepository = $client->getSearchApi();
    }

    public function search($query){
        return $this->searchRepository->searchTv($query);
    }
}