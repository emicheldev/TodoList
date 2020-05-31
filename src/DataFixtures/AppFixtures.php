<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Task;
use App\Repository\UserRepository;
use App\Repository\TaskRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

	public function __construct(TaskRepository $taskRepository, UserRepository $userRepository, UserPasswordEncoderInterface $encoder)
	{
		$this->userRepository = $userRepository;
		$this->taskRepository = $taskRepository;
		$this->encoder = $encoder;
	}

	public function load(ObjectManager $manager)
	{
		$faker = Factory::create('fr_FR');

		$users = [
			[
				"username" => "michel",
				"email" => "admin@todo.fr",
				"roles" => ["ROLE_ADMIN"],
				"password" => "michel",
			],
			[
				"username" => "anonyme",
				"email" => "anonyme@todo.fr",
				"role" => ["ROLE_USER"],
				"password" => "anonyme",
			],
			[
				"username" => "user",
				"email" => "user@todo.fr",
				"roles" => ["ROLE_USER"],
				"password" => "user",
			],
			[
				"username" => "user2",
				"email" => "user2@todo.fr",
				"roles" => ["ROLE_USER"],
				"password" => "user2",
			],
			[
				"username" => "user3",
				"email" => "user3@todo.fr",
				"roles" => ["ROLE_USER"],
				"password" => "user3",
			],
		];

        $usersObj = [];

		foreach ($users as $item) {
			$user = new User();
			$user->setUsername($item['username']);
			$user->setEmail($item['email']);
			$user->setRoles(["ROLE_USER"]);
			$user->setPassword($this->encoder->encodePassword($user, $item['password']));

            $manager->persist($user);
            array_push($usersObj, $user);

        }
        $manager->flush();

		for ($i = 1; $i <= 15; $i++) {
			$isDone = random_int(0, 1);
			$subTitle = $isDone ? ' réalisée' : ' à faire';

			$task = new Task();
			$user = $usersObj[1];
			$date = new \DateTime();

			$task->setTitle('Tâche numéro ' . $i . $subTitle);
			$task->setContent($faker->sentence(5, true));
			$task->toggle($isDone);
			$task->setAuthor($user);

			$manager->persist($task);
		}

		$manager->flush();
	}
}