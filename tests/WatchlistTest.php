<?php

namespace App\Tests;


class WatchlistTest extends FunctionalTestCase {

    /** @test */
    function an_authenticated_user_can_add_a_show_to_his_watch_list(){
        Factories::storeShow($this->entityManager);
        $user = $this->createUser();
        $this->login($user);
        $this->client->request('POST','/show/1/watchlist');
        //dd($this->client->getResponse()->getContent());
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $user->getWatchlist()->count());
    }

    /** @test */
    function an_unauthenticated_user_can_not_add_a_show_to_his_watch_list(){
        $this->client->request('POST', '/show/1/watchlist');
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    /** @test */
    function an_authenticated_user_can_delete_a_show_from_his_watchlist(){
        $show = Factories::storeShow($this->entityManager);
        $user = $this->createUser();
        $user->addWatchlist($show);
        $this->entityManager->flush();
        $this->entityManager->refresh($user);
        $this->assertEquals(1, $user->getWatchlist()->count());
        $this->login($user);
        $this->client->request('DELETE', '/show/1/watchlist');
        //dd($this->client->getResponse()->getContent());
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->entityManager->refresh($user);
        $this->assertEquals(0, $user->getWatchlist()->count());
    }

    /** @test */
    function an_unauthenticated_user_can_not_delete_a_show_from_his_watchlist(){
        $this->client->request('DELETE', '/show/1/watchlist');
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
    }
}