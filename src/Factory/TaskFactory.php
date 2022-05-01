<?php

namespace App\Factory;

use App\Entity\Task;
use App\Repository\TaskRepository;
use DateTimeImmutable;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Task>
 *
 * @method static Task|Proxy createOne(array $attributes = [])
 * @method static Task[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Task|Proxy find(object|array|mixed $criteria)
 * @method static Task|Proxy findOrCreate(array $attributes)
 * @method static Task|Proxy first(string $sortedField = 'id')
 * @method static Task|Proxy last(string $sortedField = 'id')
 * @method static Task|Proxy random(array $attributes = [])
 * @method static Task|Proxy randomOrCreate(array $attributes = [])
 * @method static Task[]|Proxy[] all()
 * @method static Task[]|Proxy[] findBy(array $attributes)
 * @method static Task[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Task[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static TaskRepository|RepositoryProxy repository()
 * @method Task|Proxy create(array|callable $attributes = [])
 */
final class TaskFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'content' => self::faker()->paragraphs(3, true),
            'createdAt' => new DateTimeImmutable(),
            'isDone' => self::faker()->boolean(),
            'title' => self::faker()->words(5, true),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Task $task): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Task::class;
    }
}
