<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->loadAuthors($manager);
        $this->loadBooks($manager);

        $manager->flush();
    }

    private function loadAuthors(ObjectManager $manager): void
    {
        foreach ($this->getAuthorData() as [$fistName, $lastName, $patronymic]) {
            $author = new Author();
            $author->setFirstName($fistName);
            $author->setLastName($lastName);
            $author->setPatronymic($patronymic);

            $manager->persist($author);
            $this->addReference((string)$author, $author);
        }
    }

    private function loadBooks(ObjectManager $manager): void
    {
        foreach ($this->getBookData() as [$title, $year, $isbn, $pages, $authors]) {
            $book = new Book();
            $book->setTitle($title);
            $book->setYear($year);
            $book->setIsbn($isbn);
            $book->setPages($pages);

            foreach ($authors as $authorName) {
                $book->addAuthor($this->getReference($authorName));
            }

            $manager->persist($book);
        }
    }

    private function getAuthorData(): array
    {
        return [
            // first name, last name, patronymic
            ['Шэйн', 'Уорден', null],
            ['Джеймс', 'Шор', null],
            ['Владстон', 'Феррейра', 'Фило'],
            ['Мото', 'Пиктет', null],
            ['Евгений', 'Понасенков', 'Николаевич'],
        ];
    }

    private function getBookData(): array
    {
        return [
            [
                // title, year, ISBN, pages, authors
                'Искусство Agile-разработки. Теория и практика гибкой разработки ПО',
                '2023',
                '978-5-4461-2386-5',
                944,
                ['Шэйн Уорден', 'Джеймс Шор'],
            ],
            [
                'Теоретический минимум по Computer Science. Сети, криптография и data science (pdf + epub)',
                '2022',
                '978-5-4461-2945-4',
                288,
                ['Владстон Феррейра Фило', 'Мото Пиктет'],
            ],
            [
                'Первая научная история войны 1812 года',
                '2018',
                '978-5-17-120818-9',
                1939,
                ['Евгений Понасенков Николаевич'],
            ],
        ];
    }
}
