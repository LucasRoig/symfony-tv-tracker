<?php

namespace App\Tests;

use App\Entity\Season;
use App\Entity\Show;
use App\Repository\SeasonRepository;
use App\Repository\ShowRepository;
use App\Repository\ShowUpdater;
use App\Repository\TmdbRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ShowUpdaterTest extends KernelTestCase
{
    /**
     * @var \App\Repository\ShowRepository
     */
    protected $showRepository;
    /**
     * @var \App\Repository\SeasonRepository
     */
    protected $seasonRepository;
    /**
     * @var \App\Repository\ShowUpdater
     */
    protected $showUpdater;
    protected function setUp(){
        $kernel = self::bootKernel();
        DatabasePrimer::prime(self::$kernel);
        $registry = $kernel->getContainer()->get('doctrine');
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
//        $registry->getConnection()->getConfiguration()->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());

        $tmdbRepositoryMock = $this->createMock(TmdbRepository::class);
        $tmdbRepositoryMock->method('getShow')->willReturn(Factories::getTmdbShow());
        $tmdbRepositoryMock->method('getSeason')->willReturn(Factories::getTmdbSeason());

        $this->seasonRepository = new SeasonRepository($registry);
        $this->showRepository = new ShowRepository($registry);
        $this->showUpdater = new ShowUpdater($tmdbRepositoryMock, $entityManager);
    }

    /** @test */
    function fetch_a_show_that_is_not_in_the_db_should_add_it_to_the_db(){
        $this->assertEquals(0, $this->showRepository->count([]));
        $this->showUpdater->updateShow(1);
        $this->assertEquals(1, $this->showRepository->count([]));
    }

    /** @test */
    function fetching_a_show_that_is_already_in_the_db_does_not_create_a_new_one(){
        $this->showUpdater->updateShow(1);
        $this->showUpdater->updateShow(1);
        $this->assertEquals(1, $this->showRepository->count([]));
    }
    
    /** @test */
    function fetching_a_show_also_fetches_its_seasons(){
        $this->assertEquals(0,$this->seasonRepository->count([]));
        $show = $this->showUpdater->updateShow(1);
        $this->assertEquals(1,$this->seasonRepository->count([]));
        $season = $show->getSeasons()->get(0);
        $this->assertEquals(Season::class, get_class($season));
    }

    /** @test */
    function fetching_a_show_which_seasons_are_already_in_db_does_not_create_new_seasons(){
        $this->assertEquals(0,$this->seasonRepository->count([]));
        $this->showUpdater->updateShow(1);
        $this->showUpdater->updateShow(1);;
        $this->assertEquals(1,$this->seasonRepository->count([]));
    }

    /** @test */
    function fetching_a_show_does_not_fetch_season_0(){
        $this->showUpdater->updateShow(1);
        $this->assertEquals(1,$this->seasonRepository->count([]));
    }
}
