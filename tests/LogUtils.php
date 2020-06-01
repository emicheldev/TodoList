<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;

class LogUtils
{
	private $client;

	public function __construct($client)
	{
		$this->client = $client;
	}

	public function login($type)
	{
		$credentials = ['username' => $type, 'password' => $type];

		// get doctrine
		$entityManager = $this->client->getContainer()
			->get('doctrine')
			->getManager();

		// get a user from database
		$user = $entityManager
			->getRepository(User::class)
			->findOneBy([
				'username' => $credentials['username']
			]);

		$session = $this->client->getContainer()->get('session');

		$firewall = 'main';
		$token = new UsernamePasswordToken($user, $credentials['password'], $firewall, $user->getRoles());

		$session->set('_security_' . $firewall, serialize($token));
		$session->save();

		$cookie = new Cookie($session->getName(), $session->getId());
		$this->client->getCookieJar()->set($cookie);
	}
}