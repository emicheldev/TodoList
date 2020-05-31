<?php

namespace App\Tests\Functional;

use App\Tests\LogUtils;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @covers \App\Controller\DefaultController
 * @covers \App\Controller\SecurityController
 * @covers \App\Entity\User
 */
class LoginPageTest extends WebTestCase
{
	private $client;

	public function setUp(): void
	{
		$this->client = static::createClient();
	}

	/**
	 * Test submit wrong authentication
	 * 
	 * @return void
	 */
	public function testSubmitWrongAuthentication()
	{
		$crawler = $this->client->request('GET', '/login');
		$loginForm = $crawler->selectButton("Connexion")->form();

		$this->assertNotEquals(null, $loginForm);

		$loginForm['username'] = 'xxx';
		$loginForm['password'] = 'xxx';

		$crawler = $this->client->submit($loginForm);
		$crawler = $this->client->followRedirect();

		$this->assertStringContainsString('Username could not be found.', $crawler->text(null,false));
	}

	/**
	 * Test submit correct authentication
	 * 
	 * @return void
	 */
	public function testSubmitCorrectAuthentication()
	{
		$crawler = $this->client->request('GET', '/login');
		$loginForm = $crawler->selectButton("Connexion")->form();

		$this->assertNotEquals(null, $loginForm);

		$loginForm['username'] = 'michel';
		$loginForm['password'] = 'michel';


		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
	}
}