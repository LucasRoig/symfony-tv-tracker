<?php

namespace App\Repository;


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
            ->setTmdbId($tmdbShow['id']);
        $this->entityManager->persist($show);
        foreach ($tmdbShow['seasons'] as $season){
            if ($season['season_number'] > 0){
                $this->updateSeasons($show,$season['season_number']);
            }
        }
        $this->entityManager->flush();
        $this->entityManager->refresh($show);
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
        $this->entityManager->persist($season);
    }
}