<?php
/**
 * TodoItem fixtures.
 */

namespace App\DataFixtures;

use App\Entity\TodoItem;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use DateTimeImmutable;


/**
 * Class TodoItemFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class TodoItemFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
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

            /** @var User $author */
            $author = $this->getRandomReference('users');
            $todoItem->setAuthor($author);

            return $todoItem;
        });

        $this->manager->flush();
    }
    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
