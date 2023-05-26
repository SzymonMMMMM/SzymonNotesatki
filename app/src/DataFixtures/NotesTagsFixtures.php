<?php

namespace App\DataFixtures;


use App\DataFixtures\NoteFixtures;
use App\DataFixtures\TagFixtures;
use App\Entity\Note;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/** TODO komentarze */
class NotesTagsFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{

    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }
        $this->createMany(200, 'notes_tags', function () {
            $note = $this->getRandomReference('notes');
            $tag = $this->getRandomReference('tags');

            $note->addTag($tag);
            return $note;
        });
        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            NoteFixtures::class,
            TagFixtures::class,
        ];
    }
}

