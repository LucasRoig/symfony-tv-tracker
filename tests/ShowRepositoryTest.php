<?php

namespace App\Tests;

use App\Entity\Show;
use App\Repository\SeasonRepository;
use App\Repository\ShowRepository;
use App\Repository\TmdbRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ShowRepositoryTest extends KernelTestCase
{
    /**
     * @var \App\Repository\ShowRepository
     */
    protected $showRepository;
    /**
     * @var \App\Repository\SeasonRepository
     */
    protected $seasonRepository;

    protected function setUp(){
        $kernel = self::bootKernel();
        DatabasePrimer::prime(self::$kernel);
        $registry = $kernel->getContainer()->get('doctrine');
        $tmdbRepositoryMock = $this->createMock(TmdbRepository::class);
        $tmdbRepositoryMock->method('getShow')->willReturn(Factories::getTmdbShow());
        $tmdbRepositoryMock->method('getSeason')->willReturn(Factories::getTmdbSeason());
        $this->seasonRepository = new SeasonRepository($registry);
        $this->showRepository = new ShowRepository($registry,$tmdbRepositoryMock,$this->seasonRepository);

    }

    /** @test */
    function fetch_a_show_that_is_not_in_the_db_should_add_it_to_the_db(){

        $this->assertEquals(0, $this->showRepository->count([]));
        $show = $this->showRepository->findOneByTmdbId(1);
        $this->assertEquals(1, $this->showRepository->count([]));

    }

    /** @test */
    function fetching_a_show_that_is_already_in_the_db_does_not_create_a_new_one(){
        $this->showRepository->findOneByTmdbId(1);
        $this->showRepository->findOneByTmdbId(1);
        $this->assertEquals(1, $this->showRepository->count([]));
    }
    
    /** @test */
    function fetching_a_show_also_fetches_its_seasons(){
        $this->assertEquals(0,$this->seasonRepository->count([]));
        $show = $this->showRepository->findOneByTmdbId(1);
        $this->assertEquals(1,$this->seasonRepository->count([]));
    }

    /** @test */
    function fetching_a_show_which_seasons_are_already_in_db_does_not_create_new_seasons(){
        $this->assertEquals(0,$this->seasonRepository->count([]));
        $this->showRepository->findOneByTmdbId(1);
        $show = $this->showRepository->findOneByTmdbId(1);
        $this->assertEquals(1,$this->seasonRepository->count([]));
    }
}
