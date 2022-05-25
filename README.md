# ToDoList

[![Maintainability](https://api.codeclimate.com/v1/badges/3576837d54702d4b43b5/maintainability)](https://codeclimate.com/github/mrstan3772/ToDoList/maintainability) [![Codacy Badge](https://app.codacy.com/project/badge/Grade/57ca0bd3dd8348a892a269a193c0c0df)](https://www.codacy.com/gh/mrstan3772/ToDoList/dashboard?utm_source=github.com&utm_medium=referral&utm_content=mrstan3772/ToDoList&utm_campaign=Badge_Grade)

The TODOLIST application purpose is to manage his daily tasks.

It was entirely designed in PHP with Symfony framework.
It's provided with a set of resources including this one:

- Installation guidelines
- Documentation
- Third packages libraries and extensions
- Etc.

## Context

You have recently joined a startup whose core business is an application to manage your daily tasks. The company has just been set up, and the application had to be developed very quickly in order to show potential investors how viable the concept is.

The previous developer's choice was to use the Symfony PHP framework, a framework you are already familiar with !

Good news! **ToDo & Co** has finally managed to raise funds to allow the development of the company and especially of the application.

Your mission here is to improve the quality of the application. Quality is a concept that embraces a large number of aspects: we often talk about code quality, but there is also the quality perceived by the user of the application or the quality perceived by the company's employees, and finally the quality that you perceive when you have to work on the project.

Therefore, for this last specialization project, you are in the shoes of an experienced developer in charge of the following tasks:

- implementing new features ;
- fixing some bugs ;
- and the implementation of automated tests.
- You will also be asked to analyze the project using tools that give you an overview of the quality of the code and the various performance areas of the application.

You are not requested to fix the points raised by the code quality and performance audit. However, if time will permit, ToDo & Co will be grateful if you reduce the technical debt of this application.

## Customer Requirement

### Fixing anomalies

- A task must be linked to a user
- Choose a role for a user

### New Features Implementation

- Authorization
- Implementation of automated tests

## Technical Documentation

You are requested to make a documentation explaining how the implementation of the authentication was achieved. This documentation is targeted to the next junior developers who will join the team in a few weeks.

## Code Quality Audit & Application Performance

The company's founders would like to ensure that the application's future development is sustainable. That is why they would like to start by evaluating the application's technical debt.

At the conclusion of your work on the application, you must produce a code audit on the following two points: code quality and performance.

Of course, you are strongly advised to use tools that allow you to have metrics to support your work.

Concerning the performance audit, the use of Blackfire is imperative. It will allow you to produce accurate analyses that are relevant to the future evolution of the project.

## Skills measured

- Implement unit and functional tests
- Implement new features inside an already initiated application following a clear collaboration plan
- Read and describe how a code chunk written by other developers works
- Produce a report on the test execution
- Analyze code quality and application performance
- Make a plan to reduce the technical debt on your application
- Deliver patches when the tests indicate it is necessary
- Suggest a set of enhancements

## Prerequisites:

In order to make this project work, you must:

- Use **PHP 8.0.X | 8.1.X**
- [Download composer](https://getcomposer.org/) to install PHP dependencies
- Extensions (which are installed and enabled by default in most PHP 8 installations): [Ctype](https://www.php.net/book.ctype), [iconv](https://www.php.net/book.iconv), [Session](https://www.php.net/book.session), [SimlpleXML](https://www.php.net/book.simplexml), [Tokenizer](https://www.php.net/book.tokenizer), [PCRE](https://www.php.net/book.pcre)

Optional : [Install Symfony CLI](https://symfony.com/download)

The symfony binary also provides a tool to check if your computer meets all requirements. Open your console terminal and run this command :

`symfony check:requirements`

Without this tool you have to replace in the terminal `symfony` with `php bin/console` and always at the root of the project.

## Deployment

### Application Environment

Edit the `.env` or `.env.local` (create if not exists) file on the root of the directory. On the example below adapt the configuration according to your credentials to `DATABASE_URL` values which concerns the SQL database.

```env
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=x.x.x"
```

Found this example in the root folder under the file name [".env.example"](https://github.com/mrstan3772/ToDoList/blob/main/.env.example)

### Dependencies

Use the command `composer install` **[AFTER EDITING THE .ENV FILE](https://github.com/mrstan3772/ToDoList#application-environment)** from the project root directory(ToDoList). Do not answer questions if you see any during the installation (press enter to skip). Once this step is done you will have all the necessary dependencies for the main project.

## Installation

### Creating tables in the database (MySQL)

From now on, we will focus on creating the tables required to save tasks and users information. All we have to do is type this command and follow :

```bash
#Same name in your .env or .env.local file to replace "db_name"
symfony console doctrine:database:create
symfony console make:migration
#IF ERROR THEN REMOVE DATABASE AND REMOVE ALL MIGRATION FILES IN "migrations" FOLDER AND START AGAIN
symfony console doctrine:migrations:migrate

#OPTIONAL
symfony console doctrine:migrations:diff
symfony console doctrine:schema:update --force
```

And load fixtures data with this command :

`symfony console doctrine:fixtures:load`

More easily, use this command to perform all actions above inside the root project folder(ToDoList) :

`composer run-script prepare-db --dev`

### Run Server

Type this command inside the root folder(ToDoList) to start running web server :

`symfony serve`

An address in the format `127.0.0.1:<port>` is shown on the terminal.

Copy and paste this address in the navigation bar of your browser.

That's all !

### Start testing

Type this command inside the root folder(ToDoList) to execute tests :

`composer run-script phpunit-start-test --dev`

## Version

Version : 1.1.0

We use SemVer for versioning. For more details, see [link](https://semver.org/).

## Authors

**Stanley LOUIS JEAN** - _Web Dev_ - [MrStan](https://github.com/mrstan3772)

## License

![GPL-v3](https://zupimages.net/up/21/46/iarl.png)

## Made With

- Symnfony 6.0
- Doctrine
- Twig
- BlackFire
- Bootstrap
- PHPStan
- Foundry
- PHPUnit
- And more
