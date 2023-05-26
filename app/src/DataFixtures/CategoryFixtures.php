<?php
/**
 * Category fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use DateTimeImmutable;

/**
 * Class CategoryFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class CategoryFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        /** TODO moze jakies ladniejsze purgowanie */

        $connection = $this->manager->getConnection();
        $connection->beginTransaction();
        $connection->exec('SET FOREIGN_KEY_CHECKS = 0');
        $connection->executeStatement('TRUNCATE TABLE tag');
        $connection->executeStatement('TRUNCATE TABLE notes_tags');
        $connection->executeStatement('TRUNCATE TABLE users');
        $connection->executeStatement('TRUNCATE TABLE todo_item');
        $connection->executeStatement('TRUNCATE TABLE notes');
        $connection->executeStatement('TRUNCATE TABLE category');
        $connection->exec('SET FOREIGN_KEY_CHECKS = 1');
        //$connection->executeStatement('ALTER TABLE category AUTO_INCREMENT = 1');

        $this->createMany(20, 'categories', function (int $i) {
            $category = new Category();
            $category->setTitle($this->faker->unique()->word);

            return $category;
        });

        $this->manager->flush();
    }
}
