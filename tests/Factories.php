<?php

namespace App\Tests;


use App\Entity\Episode;
use App\Entity\Season;
use App\Entity\Show;

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
            ],
            'in_production' => 'true',
            'last_air_date' => '2018-11-4'
        ];
    }

    public static function getTmdbSeason(){
        return [
            'air_date' => '2017-4-4',
            'name' => 'season 1',
            'overview' => 'Une nouvelle saison',
            'poster_path' => 'sdfgsdfgsfdfg',
            'season_number' => 1,
            'episodes' => [
                ['episode_number' => 1]
            ],
        ];
    }

    public static function getTmdbEpisode(){
        return [
            'seasonNumber' => 1,
            'air_date' => '2016-4-4',
            'name' => 'Episode 1',
            'overview' => 'Un nouvel episode',
            'still_path' => 'fddffsfsdfsdfsdffds',
            'episode_number' => 1
        ];
    }

    public static function configureShowUpdaterMock($mock){
        $mock->method('getShow')->willReturn(Factories::getTmdbShow());
        $mock->method('getSeason')->willReturn(Factories::getTmdbSeason());
        $mock->method('getEpisode')->willReturn(Factories::getTmdbEpisode());
        return $mock;
    }

    public static function storeShow($entityManager){
        $s = new Show();
        $s->setOverview('bliblu')
            ->setName('bliblu')
            ->setPosterPath('sdfsdfdsf')
            ->setTmdbId(1)
            ->setFirstAirDate(new \DateTime())
            ->setBackdropPath('erererer')
            ->setStatus('Ended')
            ->setLastAirDate(new \DateTime())
            ->setInProduction(true);
        $entityManager->persist($s);
        $entityManager->flush();
        return $s;
    }

    public static function storeSeason($entityManager){
        $show = static::storeShow($entityManager);
        $season = new Season();
        $season->setPosterPath('bliblu')
            ->setName('Season 1')
            ->setOverview('Une saison')
            ->setAirDate(new \DateTime())
            ->setSeasonNumber(1)
            ->setEpisodeCount(1)
            ->setTmdbShowId(1)
            ->setShow($show);
        $entityManager->persist($season);
        $entityManager->flush();
        $entityManager->refresh($show);
        return $season;
    }

    public static function storeEpisode($entityManager){
        $season = static::storeSeason($entityManager);
        $episode = new Episode();
        $episode->setSeasonNumber(1)
            ->setSeason($season)
            ->setAirDate(new \DateTime())
            ->setOverview('Un episode')
            ->setName('Episode 1')
            ->setTvShow($season->getShow())
            ->setStillPath('bliblu')
            ->setEpisodeNumber(1);
        $entityManager->persist($episode);
        $entityManager->flush();
        $entityManager->refresh($season);
        return $episode;
    }
}