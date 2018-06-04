<?php

namespace App\Tests;


class FollowListTest extends FunctionalTestCase {
    /** @test */
    function an_authenticated_user_can_add_a_show_to_his_follow_list(){
        $user = $this->createUser();
        $show = Factories::storeShow($this->entityManager);
        $this->login($user);
        $this->client->request('POST','/show/1/follow');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->entityManager->refresh($user);
        $this->assertEquals(1, $user->getFollowlist()->count());
    }

    /** @test */
    function an_unauthenticated_user_can_not_add_a_show_to_his_followlist(){
        $this->client->request('POST','/show/1/follow');
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    /** @test */
    function an_authenticated_user_can_delete_a_show_from_his_followlist(){
        $user = $this->createUser();
        $show = Factories::storeShow($this->entityManager);
        $this->login($user);

        $user->addToFollowList($show);
        $this->entityManager->flush();
        $this->entityManager->refresh($user);
        $this->assertEquals(1, $user->getFollowList()->count());

        $this->client->request('DELETE','/show/1/follow');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->entityManager->refresh($user);
        $this->assertEquals(0, $user->getFollowlist()->count());
    }

    /** @test */
    function an_unauthenticated_user_can_not_delete_a_show_from_his_followlist(){
        $this->client->request('DELETE','/show/1/follow');
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
    }
}