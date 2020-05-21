<?php

namespace App\Tests\Functional;

use App\Tests\LogUtils;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @covers \App\Controller\DefaultController
 * @covers \App\Controller\TaskController
 * @covers \App\Entity\User
 * @covers \App\Entity\Task
 */
class HomePageTest extends WebTestCase
{
	private $client;
	private $logUtils;

	public function setUp(): void
	{
		$this->client = static::createClient();
		$this->logUtils = new LogUtils($this->client);
	}
	/**
	 * Test access add task button and check uri
	 * 
	 * @return void
	 */
	public function testAccessAddTaskButton()
	{
		$this->logUtils->login('michel');
		$crawler = $this->client->request('GET', '/');
		$linkAddTask = $crawler->selectLink("Créer une nouvelle tâche")->link()->getUri();
		$crawler = $this->client->request('GET', $linkAddTask);

		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());

		$titlePage = $crawler->filter('h1')->text();
		$this->assertStringContainsString("Bienvenue sur Todo List", $titlePage);
	}

	/**
	 * Test access all tasks button
	 * 
	 * @return void
	 */
	public function testAccessAllTasksButton()
	{
		$this->logUtils->login('michel');
		$crawler = $this->client->request('GET', '/');
		$linkViewTasks = $crawler->selectLink("Consulter la liste des tâches à faire")->link()->getUri();

		$crawler = $this->client->request('GET', $linkViewTasks);

		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
		$task = $crawler->filter('.task')->text(null,false);
		$this->assertNotEquals(null, $task);
	}

	/**
	 * Test access all tasks done button
	 * 
	 * @return void
	 */
	public function testAccessAllTasksDoneButton()
	{
		$this->logUtils->login('michel');
		$crawler = $this->client->request('GET', '/');
		$linkViewDoneTasks = $crawler->selectLink("Consulter la liste des tâches terminées")->link()->getUri();

		$crawler = $this->client->request('GET', $linkViewDoneTasks);

		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());

		$crawler->filter('.caption div.toggle')->each(function (Crawler $node, $i) {
			$taskToggle = filter_var($node->attr('data-is-done'), FILTER_VALIDATE_BOOLEAN);

			$this->assertEquals(true, $taskToggle);
		});
	}
}