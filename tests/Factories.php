<?php

namespace App\Tests;


class Factories {
    public static function getTmdbShow(){
        return [
            'backdrop_path' => 'bliblu',
            'name' => 'une serie',
            'first_air_date' => '2018-9-3',
            'poster_path' => 'bliblu',
            'status' => 'returning',
            'id' => 1,
            'overview' => 'blilbu',
            'seasons' => [
                ['season_number' => 1],
                ['season_number' => 0]
            ]
        ];
    }

    public static function getTmdbSeason(){
        return [
            'air_date' => '2017-4-4',
            'name' => 'season 1',
            'overview' => 'Une nouvelle saison',
            'poster_path' => 'sdfgsdfgsfdfg',
            'season_number' => 1,
            'episodes' => [],
        ];
    }
}