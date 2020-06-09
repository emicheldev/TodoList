<?php

namespace App\Tests\Unit\Controller;

use App\Tests\LogUtils;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @covers \App\Controller\DefaultController
 * @covers \App\Controller\SecurityController
 * @covers \App\Controller\UserController
 * @covers \App\Controller\TaskController
 * @covers \App\Entity\User
 * @covers \App\Entity\Task
 */
class SecurityControllerTest extends WebTestCase
{
	private $client;
	private $logUtils;

	public function setUp(): void
	{
		$this->client = static::createClient();
		$this->logUtils = new LogUtils($this->client);
	}

	/**
	 * Test access route when unauthenticated
	 * 
	 * @return void
	 */
	public function testAccessRouteWhenUnauthenticated()
	{
		$this->client->request('GET', "/tasks");
		$this->assertEquals('302', $this->client->getResponse()->getStatusCode());
	}

	/**
	 * Test access route when authenticated with admin role
	 * 
	 * @return void
	 */
	public function testAccessRouteWhenAuthenticatedWithAdminRole()
	{
		$this->logUtils->login('michel');
		$this->client->request('GET', "/tasks");
		$this->assertEquals('200', $this->client->getResponse()->getStatusCode());
	}

	/**
	 * Test access route when authenticated with user role
	 * 
	 * @return void
	 */
	public function testAccessRouteWhenAuthenticatedWithUserRole()
	{
		$this->logUtils->login('user');
		$this->client->request('GET', "/tasks");
		$this->assertEquals('200', $this->client->getResponse()->getStatusCode());
	}

	
	/**
	 * Test access user management with user role
	 * 
	 * @return void
	 */
	public function testAccessUserManagementWithUserRole()
	{
		$this->logUtils->login('user');
		$this->client->request('GET', "/users");
		$this->assertEquals('302', $this->client->getResponse()->getStatusCode());
	}

	/**
	 * Test access user management with admin role
	 * 
	 * @return void
	 */
	public function testAccessUserManagementWithAdminRole()
	{
		$this->logUtils->login('michel');
		$this->client->request('GET', "/users");
		$this->assertEquals('200', $this->client->getResponse()->getStatusCode());
	}


}