<?php

namespace App\Tests;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class FunctionalTestCase extends WebTestCase {

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    protected $registry;

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;
    /**
     * @param $user User
     */
    protected function login($user){
        $firewallName = 'main';
        $token = new UsernamePasswordToken($user,null,$firewallName,array('ROLE_USER'));
        $session = $this->client->getContainer()->get('session');
        $session->set('_security_'.$firewallName,serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(),$session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    protected function setUp(){
        $this->client = static::createClient();
        $this->client->disableReboot();
        DatabasePrimer::prime(self::$kernel);
        $this->registry = self::$kernel->getContainer()->get('doctrine');
        $this->entityManager = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    protected function createUser(){
        $user = new User();
        $user->setPassword('password');
        $user->setEmail('test@test.com');
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }
}