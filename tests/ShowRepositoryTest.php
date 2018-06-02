<?php

namespace App\Tests;

use App\Entity\Show;
use App\Repository\ShowRepository;
use App\Repository\TmdbRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ShowRepositoryTest extends KernelTestCase
{
    /**
     * @var \App\Repository\ShowRepository
     */
    protected $showRepository;

    protected function setUp(){
        $kernel = self::bootKernel();
        DatabasePrimer::prime(self::$kernel);
        $registry = $kernel->getContainer()->get('doctrine');
        $tmdbRepositoryMock = $this->createMock(TmdbRepository::class);
        $tmdbRepositoryMock->method('getShow')->willReturn(Factories::getTmdbShow());
        $this->showRepository = new ShowRepository($registry,$tmdbRepositoryMock);
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
}
