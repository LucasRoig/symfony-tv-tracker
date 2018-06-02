<?php

namespace App\Tests;


use App\Repository\EpisodeRepository;
use App\Repository\MediaRepository;
use App\Repository\SeasonRepository;
use App\Repository\ShowRepository;
use App\Repository\ShowUpdater;
use App\Repository\TmdbRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MediaRepositoryTest extends KernelTestCase {

    /**
     * @var \App\Repository\ShowRepository
     */
    protected $showRepository;

    /**
     * @var SeasonRepository
     */
    protected $seasonRepository;
    /**
     * @var EpisodeRepository
     */
    protected $episodeRepository;

    /**
     * @var \App\Repository\ShowUpdater
     */
    protected $showUpdater;

    /**
     * @var MediaRepository
     */
    protected $mediaRepository;

    protected function setUp(){
        $kernel = self::bootKernel();
        DatabasePrimer::prime(self::$kernel);
        $registry = $kernel->getContainer()->get('doctrine');
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
//        $registry->getConnection()->getConfiguration()->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());

        $tmdbRepositoryMock = $this->createMock(TmdbRepository::class);
        $tmdbRepositoryMock = Factories::configureShowUpdaterMock($tmdbRepositoryMock);

        $this->episodeRepository = new EpisodeRepository($registry);
        $this->seasonRepository = new SeasonRepository($registry);
        $this->showRepository = new ShowRepository($registry);
        $this->showUpdater = new ShowUpdater($tmdbRepositoryMock, $entityManager);
        $this->mediaRepository = new MediaRepository($this->showRepository,$this->showUpdater);
    }

    /** @test */
    function getting_a_show_that_is_not_in_db_add_it_to_the_db(){
        $show = $this->mediaRepository->getShowByTmdbId(1);
        $this->assertNotNull($show);
        $this->assertEquals(1, $this->showRepository->count([]));
    }

    /** @test */
    function getting_a_season_create_the_show_and_the_season(){
        $this->mediaRepository->getSeasonByTmdbId(1,1);
        $this->assertEquals(1,$this->showRepository->count([]));
        $this->assertEquals(1,$this->seasonRepository->count([]));
    }

    /** @test */
    function getting_an_episode_create_the_show_and_the_season(){
        $this->mediaRepository->getEpisodeByTmdbId(1,1,1);
        $this->assertEquals(1,$this->showRepository->count([]));
        $this->assertEquals(1,$this->seasonRepository->count([]));
        $this->assertEquals(1,$this->episodeRepository->count([]));
    }
}