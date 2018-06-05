<?php

namespace App\Repository;


use App\Entity\Episode;
use App\Entity\Season;
use App\Entity\Show;
use Doctrine\ORM\EntityManagerInterface;

class ShowUpdater {
    /**
     * @var \App\Repository\TmdbRepository
     */
    private $tmdbRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ShowRepository
     */
    private $showRepository;

    public function __construct (TmdbRepository $tmdbRepository, EntityManagerInterface $entityManager) {
        $this->tmdbRepository = $tmdbRepository;
        $this->entityManager = $entityManager;
        $this->showRepository = $this->entityManager->getRepository(Show::class);
    }

    public function updateShow($tmdbId){
        $oldShow = $this->showRepository->findOneBy(['tmdb_id' => $tmdbId]);
        $tmdbShow = $this->tmdbRepository->getShow($tmdbId);
        $show = isset($oldShow) ? $oldShow : new Show();
        $show->setBackdropPath($tmdbShow['backdrop_path'])
            ->setName($tmdbShow['name'])
            ->setFirstAirDate(new \DateTime($tmdbShow['first_air_date']))
            ->setStatus($tmdbShow['status'])
            ->setOverview($tmdbShow['overview'])
            ->setPosterPath($tmdbShow['poster_path'])
            ->setTmdbId($tmdbShow['id'])
            ->setInProduction($tmdbShow['in_production'])
            ->setLastAirDate(new \DateTime($tmdbShow['last_air_date']));
        $this->entityManager->persist($show);
        foreach ($tmdbShow['seasons'] as $season){
            if ($season['season_number'] > 0){
                $this->updateSeasons($show,$season['season_number']);
            }
        }
        $this->entityManager->flush();
        $this->entityManager->refresh($show);
        $this->entityManager->clear();

        return $show;
    }

    /**
     * @param $show Show
     * @param $seasonNumber
     */
    private function updateSeasons($show,$seasonNumber){
        $season = null;
        foreach ($show->getSeasons() as $s) {
            if ($s->getSeasonNumber() == $seasonNumber){
                $season = $s;
                break;
            }
        }

        $tmdbSeason = $this->tmdbRepository->getSeason($show->getTmdbId(),$seasonNumber);
        $season = isset($season) ? $season : new Season();
        $season->setName($tmdbSeason['name'])
            ->setPosterPath($tmdbSeason['poster_path'])
            ->setOverview($tmdbSeason['overview'])
            ->setAirDate(new \DateTime($tmdbSeason['air_date']))
            ->setSeasonNumber($tmdbSeason['season_number'])
            ->setTmdbShowId($show->getTmdbId())
            ->setEpisodeCount(count($tmdbSeason['episodes']))
            ->setShow($show);
        foreach ($tmdbSeason['episodes'] as $episode){
            $this->updateEpisode($show,$season,$episode['episode_number']);
        }
        $this->entityManager->persist($season);
    }

    /**
     * @param $show Show
     * @param $season Season
     * @param $episodeNumber
     */
    private function updateEpisode($show, $season, $episodeNumber){
        $episode = null;
        foreach ($season->getEpisodes() as $e){
            if($e->getEpisodeNumber() == $episodeNumber){
                $episode = $e;
                break;
            }
        }
        $tmdbEpisode = $this->tmdbRepository->getEpisode($show->getTmdbId(),$season->getSeasonNumber(),$episodeNumber);
        $episode = isset($episode) ? $episode : new Episode();
        $episode->setSeasonNumber($season->getSeasonNumber())
            ->setSeason($season)
            ->setAirDate(new \DateTime($tmdbEpisode['air_date']))
            ->setName($tmdbEpisode['name'])
            ->setOverview($tmdbEpisode['overview'])
            ->setStillPath($tmdbEpisode['still_path'])
            ->setEpisodeNumber($episodeNumber)
            ->setTvShow($show);
        $this->entityManager->persist($episode);
    }
}