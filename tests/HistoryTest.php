<?php

namespace App\Tests;


class HistoryTest extends FunctionalTestCase {

    /** @test */
    function an_authenticated_user_can_add_an_episode_to_his_history(){
        $user = $this->createUser();
        $this->login($user);
        $episode = Factories::storeEpisode($this->entityManager);

        $this->client->request('post','/show/'.$episode->getTvShow()->getTmdbId()
            .'/season/'.$episode->getSeasonNumber()
            .'/episode/'.$episode->getEpisodeNumber().'/history');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->entityManager->refresh($user);
        $this->assertEquals(1, $user->getHistory()->count());
    }

    /** @test */
    function an_unauthenticated_user_can_not_add_an_episode_to_his_history(){
        $episode = Factories::storeEpisode($this->entityManager);
        $this->client->request('post','/show/'.$episode->getTvShow()->getTmdbId()
            .'/season/'.$episode->getSeasonNumber()
            .'/episode/'.$episode->getEpisodeNumber().'/history');
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    /** @test */
    function an_authenticated_user_can_delete_an_episode_from_his_history(){
        $user = $this->createUser();
        $this->login($user);
        $episode = Factories::storeEpisode($this->entityManager);

        $user->addToHistory($episode);
        $this->entityManager->flush();
        $this->entityManager->refresh($user);
        $this->assertEquals(1, $user->getHistory()->count());

        $this->client->request('delete','/show/'.$episode->getTvShow()->getTmdbId()
            .'/season/'.$episode->getSeasonNumber()
            .'/episode/'.$episode->getEpisodeNumber().'/history');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->entityManager->refresh($user);
        $this->assertEquals(0, $user->getHistory()->count());
    }

    /** @test */
    function an_unauthenticated_user_can_not_delete_an_episode_from_his_history(){
        $episode = Factories::storeEpisode($this->entityManager);
        $this->client->request('delete','/show/'.$episode->getTvShow()->getTmdbId()
            .'/season/'.$episode->getSeasonNumber()
            .'/episode/'.$episode->getEpisodeNumber().'/history');
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
    }
    
    /** @test */
    function an_authenticated_user_can_add_a_whole_season_to_his_history(){
        $user = $this->createUser();
        $this->login($user);
        $episode = Factories::storeEpisode($this->entityManager);
        $season = $episode->getSeason();
        $this->entityManager->refresh($season);

        $this->client->request('post', '/show/' . $season->getShow()->getTmdbId() .
            '/season/' . $season->getSeasonNumber() . '/history');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->entityManager->refresh($user);
        $this->assertEquals(1, $user->getHistory()->count());
    }

    /** @test */
    function an_unauthenticated_user_can_not_add_a_whole_season_to_his_history(){
        $episode = Factories::storeEpisode($this->entityManager);
        $season = $episode->getSeason();

        $this->client->request('post', '/show/' . $season->getShow()->getTmdbId() .
            '/season/' . $season->getSeasonNumber() . '/history');
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    /** @test */
    function an_authenticated_user_can_delete_a_whole_season_to_his_history(){
        $user = $this->createUser();
        $this->login($user);
        $episode = Factories::storeEpisode($this->entityManager);
        $season = $episode->getSeason();
        $this->entityManager->refresh($season);

        $user->addToHistory($episode);
        $this->entityManager->flush();
        $this->entityManager->refresh($user);

        $this->assertEquals(1, $user->getHistory()->count());

        $this->client->request('delete', '/show/' . $season->getShow()->getTmdbId() .
            '/season/' . $season->getSeasonNumber() . '/history');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->entityManager->refresh($user);
        $this->assertEquals(0, $user->getHistory()->count());
    }

    /** @test */
    function an_unauthenticated_user_can_not_delete_a_whole_season_to_his_history(){
        $episode = Factories::storeEpisode($this->entityManager);
        $season = $episode->getSeason();

        $this->client->request('delete', '/show/' . $season->getShow()->getTmdbId() .
            '/season/' . $season->getSeasonNumber() . '/history');
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    /** @test */
    function an_authenticated_user_can_add_a_whole_show_to_his_history(){
        $user = $this->createUser();
        $this->login($user);
        $episode = Factories::storeEpisode($this->entityManager);
        $show = $episode->getTvShow();

        $this->client->request('post', '/show/' . $show->getTmdbId() . '/history');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->entityManager->refresh($user);
        $this->assertEquals(1, $user->getHistory()->count());
    }

    /** @test */
    function an_unauthenticated_user_can_not_add_a_whole_show_to_his_history(){
        $episode = Factories::storeEpisode($this->entityManager);
        $show = $episode->getTvShow();

        $this->client->request('post', '/show/' . $show->getTmdbId() . '/history');
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    /** @test */
    function an_authenticated_user_can_delete_a_whole_show_from_his_history(){
        $user = $this->createUser();
        $this->login($user);
        $episode = Factories::storeEpisode($this->entityManager);
        $show = $episode->getTvShow();

        $user->addToHistory($episode);
        $this->entityManager->flush();
        $this->entityManager->refresh($user);
        $this->assertEquals(1, $user->getHistory()->count());

        $this->client->request('delete', '/show/' . $show->getTmdbId() . '/history');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->entityManager->refresh($user);
        $this->assertEquals(0, $user->getHistory()->count());
    }

    /** @test */
    function an_unauthenticated_user_can_not_delete_a_whole_show_from_his_history(){
        $episode = Factories::storeEpisode($this->entityManager);
        $show = $episode->getTvShow();

        $this->client->request('delete', '/show/' . $show->getTmdbId() . '/history');
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
    }
}