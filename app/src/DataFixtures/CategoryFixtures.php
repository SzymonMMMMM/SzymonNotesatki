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
        $connection = $this->manager->getConnection();
        $connection->beginTransaction();
        $connection->executeStatement('TRUNCATE TABLE notes');
        $connection->executeStatement('ALTER TABLE category AUTO_INCREMENT = 1');

        $this->createMany(20, 'categories', function (int $i) {
            $category = new Category();
            $category->setTitle($this->faker->unique()->word);

            return $category;
        });

        $this->manager->flush();
    }
}
