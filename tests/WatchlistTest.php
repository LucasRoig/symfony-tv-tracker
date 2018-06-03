<?php

namespace App\Tests;


class WatchlistTest extends FunctionalTest {

    /** @test */
    function an_authenticated_user_can_add_a_show_to_his_watch_list(){
        Factories::storeShow($this->entityManager);
        $user = $this->createUser();
        $this->login($user);
        $this->client->request('POST','/show/1/watchlist');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $user->getWatchlist()->count());
    }

    /** @test */
    function an_unauthenticated_user_can_not_add_a_show_to_his_watch_list(){
        $this->client->request('POST', '/show/1/watchlist');
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

}