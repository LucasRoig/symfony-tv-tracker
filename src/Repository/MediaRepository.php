<?php


namespace App\Repository;


class MediaRepository {

    /**
     * @var ShowRepository
     */
    private $showRepository;
    /**
     * @var ShowUpdater
     */
    private $showUpdater;

    public function __construct (ShowRepository $showRepository, ShowUpdater $showUpdater) {
        $this->showRepository = $showRepository;

        $this->showUpdater = $showUpdater;
    }

    public function getShowByTmdbId($tmdbId){
        $show = $this->showRepository->findOneBy(['tmdb_id' => $tmdbId]);
        if(!isset($show) || $this->needsUpdate($show)){
            $show = $this->showUpdater->updateShow($tmdbId);
        }
        return $show;
    }

    public function getSeasonByTmdbId($tmdbShowId, $seasonNumber){
        $show = $this->getShowByTmdbId($tmdbShowId);
        foreach ($show->getSeasons() as $season){
            if ($season->getSeasonNumber() == $seasonNumber) return $season;
        }
        return null;
    }

    private function needsUpdate($media){
        return true;
    }
}