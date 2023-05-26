<?php
/**
 * TodoItem fixtures.
 */

namespace App\DataFixtures;

use App\Entity\TodoItem;
use DateTimeImmutable;

/**
 * Class TodoItemFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class TodoItemFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        $this->createMany(20, 'todoitem', function (int $i) {
            $todoItem = new TodoItem();
            $todoItem->setTitle($this->faker->sentence);
            $todoItem->setCreatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $todoItem->setUpdatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $CompletedPercentage = $this->faker->boolean(70);
            $todoItem->setCompleted($CompletedPercentage);

            return $todoItem;
        });

        /** TODO */
        /** @var User $author */
        //$author = $this->getRandomReference('users');
        //$task->setAuthor($author);
        $this->manager->flush();
    }
}
