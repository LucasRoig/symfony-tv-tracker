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
            'overview' => 'blilbu'
        ];
    }
}