<?php

namespace App\Tests\Functional;

use App\Entity\Task;
use App\Tests\LogUtils;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @covers \App\Controller\DefaultController
 * @covers \App\Controller\TaskController
 * @covers \App\Entity\User
 * @covers \App\Entity\Task
 */
class TaskTest extends WebTestCase
{
	private $client;
	private $logUtils;
	private $entityManager;

	public function setUp(): void
	{
		$this->client = static::createClient();
		$this->logUtils = new LogUtils($this->client);
		$this->entityManager = $this->client->getContainer()
			->get('doctrine')
			->getManager();
	}
	/**
	 * Test redirect create task button
	 * 
	 * @return void
	 */
	public function testRedirectCreateTaskButton()
	{
		$this->logUtils->login('michel');
		$crawler = $this->client->request('GET', '/tasks');
		$linkAddTask = $crawler->selectLink("Créer une tâche")->link()->getUri();

		$crawler = $this->client->request('GET', $linkAddTask);

		$titlePage = $crawler->filter('h1')->text();
		$this->assertStringContainsString("Bienvenue sur Todo List", $titlePage);
	}

	/**
	 * Test form add task
	 * 
	 * @return void
	 */
	public function testFormAddTask()
	{
		$this->logUtils->login('michel');
		$crawler = $this->client->request('GET', '/tasks/create');

		$titlePage = $crawler->filter('h1')->text(null,false);
		$this->assertStringContainsString("Bienvenue sur Todo List", $titlePage);

		$createTaskForm = $crawler->selectButton("Ajouter")->form();
		$titleTest = 'Test title task with functionnal testFormAddTask';
		$contentTest = 'Test content task with functionnal testFormAddTask';

		$createTaskForm['task[title]'] = $titleTest;
		$createTaskForm['task[content]'] = $contentTest;

		$crawler = $this->client->submit($createTaskForm);

		$crawler = $this->client->followRedirect();

		$successMessage = $crawler->filter('div.alert.alert-success')->text(null,false);
		$titleTask = $crawler->filter('.caption .portlet-header')->first()->text(null,false);
		$contentTask = $crawler->filter('.caption .inner .content')->first()->text(null,false);

		$this->assertStringContainsString('La tâche a été bien été ajoutée.', $successMessage);
		$this->assertStringContainsString($titleTest, $titleTask);
		$this->assertStringContainsString($contentTest, $contentTask);
	}

	/**
	 * Test redirect after task added
	 * 
	 * @return void
	 */
	public function testRedirectAfterTaskAdded()
	{
		$this->logUtils->login('michel');
		$crawler = $this->client->request('GET', '/tasks/create');

		$titlePage = $crawler->filter('h1')->text();
		$this->assertStringContainsString("Bienvenue sur Todo List", $titlePage);

		$createTaskForm = $crawler->selectButton("Ajouter")->form();
		$titleTest = 'Test title task with functionnal testFormAddTask';
		$contentTest = 'Test content task with functionnal testFormAddTask';

		$createTaskForm['task[title]'] = $titleTest;
		$createTaskForm['task[content]'] = $contentTest;

		$crawler = $this->client->submit($createTaskForm);

		$this->assertEquals(302, $this->client->getResponse()->getStatusCode());
	}

	/**
	 * Test redirect edit task link
	 * 
	 * @return void
	 */
	public function testRedirectEditTaskLink()
	{
		$this->logUtils->login('michel');
		$crawler = $this->client->request('GET', '/tasks');

		$updatedTask = $crawler->filter(".task")->first();

		$title = $updatedTask->filter('.link')->text();
		$content = $updatedTask->filter('.portlet-content.content')->text();
		$link = $updatedTask->filter('.link')->link()->getUri();

		$crawler = $this->client->request('GET', $link);

		$titlePage = $crawler->filter('h1')->text();
		$titleTask = $crawler->filter('input[name="task[title]"]')->extract(array('value'))[0];
		$contentTask = $crawler->filter('textarea[name="task[content]"]')->text();

		$this->assertStringContainsString("Bienvenue sur Todo List", $titlePage);
		$this->assertEquals($title, $titleTask);
		$this->assertEquals($content, $contentTask);
	}

	/**
	 * Test form edit task
	 * 
	 * @return void
	 */
	public function testFormEditTask()
	{
		$this->logUtils->login('michel');
		$crawler = $this->client->request('GET', '/tasks');

		$updatedTask = $crawler->filter(".task")->first();

		$title = $updatedTask->filter('.link')->text(null,false);
		$content = $updatedTask->filter('.portlet-content.content')->text(null,false);
		$link = $updatedTask->filter('.link')->link()->getUri();

		$crawler = $this->client->request('GET', $link);

		$updateTaskForm = $crawler->selectButton("Modifier")->form();

		$updateTaskForm['task[title]'] = 'Update ' . $title;
		$updateTaskForm['task[content]'] = 'Update ' . $content;

		$crawler = $this->client->submit($updateTaskForm);

		$crawler = $this->client->followRedirect();

		$successMessage = $crawler->filter('div.alert.alert-success')->text(null,false);
		$titleTask = $crawler->filter('.caption .portlet-header')->first()->text(null,false);
		$contentTask = $crawler->filter('.caption .inner .content')->first()->text(null,false);

		$this->assertStringContainsString('La tâche a bien été modifiée.', $successMessage);
		$this->assertStringContainsString('Update ' . $title, $titleTask);
		$this->assertStringContainsString('Update ' . $content, $contentTask);
	}

	/**
	 * Test remove task button
	 * 
	 * @return void
	 */
	public function testRemoveTaskButton()
	{
		$this->logUtils->login('michel');
		$crawler = $this->client->request('GET', '/tasks');

		$taskToRemoved = $crawler->filter(".task")->first();
		$id = $taskToRemoved->attr('data-id');
		$removeTaskForm = $taskToRemoved->selectButton("Supprimer")->form();

		$crawler = $this->client->submit($removeTaskForm);

		$crawler = $this->client->followRedirect();

		$successMessage = $crawler->filter('div.alert.alert-success')->text(null,false);

		$task = $this->entityManager
			->getRepository(Task::class)
			->findOneBy(['id' => $id]);

		$this->assertStringContainsString('La tâche a bien été supprimée.', $successMessage);
		$this->assertEquals(null, $task);
	}
}