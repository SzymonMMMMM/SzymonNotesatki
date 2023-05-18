<?php
/**
 * Note fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Note;
use DateTimeImmutable;
use Faker\Factory;
/**
 * Class NoteFixtures.
 */
class NoteFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     */
    public function loadData(): void
    {
        $this->faker = Factory::create();

        $connection = $this->manager->getConnection();
        $connection->beginTransaction();
        $platform = $connection->getDatabasePlatform();
        $connection->executeStatement($platform->getTruncateTableSQL('notes', true));
        $connection->executeStatement('ALTER TABLE notes AUTO_INCREMENT = 1;');

        for ($i = 0; $i < 10; ++$i) {
            $note = new Note();
            $note->setTitle($this->generateRandomTitle());
            $note->setContent($this->faker->sentences(4, true));
            $note->setCreatedAt(
                DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days'))
            );
            $note->setUpdatedAt(
                DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days'))
            );
            $this->manager->persist($note);
        }

        $this->manager->flush();
    }

    /**
     * Get random title.
     *
     * @return string $words words
     */
    private function generateRandomTitle(): string
    {
        $words = $this->faker->words($nb = 3, $asText = true);
        return ucwords($words);
    }

}
