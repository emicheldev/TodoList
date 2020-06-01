# Contributing to Todo List & Co

This application is based on the symfony 4.4 frameword and is therefore mainly written in PHP and Twig (template).

To contribute to the project, it is essential to know how to use Git and the GitHub client (the project being hosted on Github).

To learn more about Git, you can go to the official documentation or to the documentation offered by GitHub.

To learn more about GitHub, nothing better than official documentation !

To contribute to this project, you must respect the following steps:

## 1. Consult the issues already created

Have you noticed a problem in the application? Before creating an issue, please verify that your problem has not already been submitted and / or addressed. To do this, go to the project issues and delete the default filters to display all the exits.

If your problem is not already one of those raised, go to step 2 !

## 2. Create an issue

To create an issue, go to the project issues and create a new issue. Make sure you follow these steps:

Use the labels made available on this project
Be as precise and concise as possible in the description of the issue (bug, question, new functionality...)

## 3. Clone the project

The third step is to clone our project on your local workspace. So do the following command:

git clone https://github.com/emicheldev/TodoList.git
Then follow the project configuration steps.

Want to contribute to our Wiki?
git clone https://github.com/emicheldev/TodoList.wiki.git

## 4. Create a branch

To create a branch, do, at the root of the project:
git checkout -b <branch-name>
git push -u origin <branch-name>
## 5. Let's coding
Develop what you want to set up. Please note that your work will not be accepted if it does not comply with the following rules:
Respect the standards PSR-1, PSR-2, PSR-12 from the PHP language. These recommendations are part of the coding standards to be respected.
Respect the good practices of Symfony 4.4
Respect the structure of the files put in place as well as the logic of the application. For example, do not communicate directly with the database in a controller.

## 6. Run the tests

Last thing before submitting your work, please check that your code does not break our application. To verify, run PHPUnit:

php bin/console doctrine:fixtures:load && php bin/phpunit
If the tests show you errors, please correct them. In any case, our team tests your work. If is contains errors, it will ignore.

## 7. Submitting a pull request

To be able to submit a pull request, make sure you have followed step 4 and that you have sent your local modifications to our repository, via your development branch.

Then go to the Pull Requests section of our project and create your pull request.

For more information on how to open Pull request, you can go to the following link