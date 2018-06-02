<?php

namespace App\Tests;


use App\Repository\MediaRepository;
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
        $tmdbRepositoryMock->method('getShow')->willReturn(Factories::getTmdbShow());
        $tmdbRepositoryMock->method('getSeason')->willReturn(Factories::getTmdbSeason());

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
}