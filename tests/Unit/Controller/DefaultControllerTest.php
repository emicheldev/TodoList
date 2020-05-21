<?php

namespace App\Tests\Unit\Controller;

use http\Client;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @covers \App\Controller\DefaultController
 * @covers \App\Controller\SecurityController
 */
class DefaultControllerTest extends WebTestCase
{
	private $client = null;

	protected function setUp(): void
	{
		$this->client = static::createClient();
	}

	/**
	 * Test method indexAction before redirect login
	 * 
	 * @test
	 * @return void
	 * 
	 */
	public function textIndexActionBeforeRedirectLogin()
	{
		$this->client->request('GET', '/');
		$uri = $this->client->getRequest()->getUri();
		$this->assertEquals(302, $this->client->getResponse()->getStatusCode());
		$this->assertTrue($this->client->getResponse()->isRedirect($uri . 'login'));
	}

	/**
	 * Test method indexAction after redirect login
	 * 
	 * @test
	 * @return void
	 * 
	 */
	public function textIndexActionAfterRedirectLogin()
	{
		$this->client->request('GET', '/');
		$this->client->followRedirect();
		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
	}
}