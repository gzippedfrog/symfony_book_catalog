<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240413042806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BDAFD8C8A9D1C132C808BA5AE42A05AB ON author (first_name, last_name, patronymic)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CBE5A3312B36786BCC1CF4E6 ON book (title, isbn)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CBE5A3312B36786BBB827337 ON book (title, year)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_BDAFD8C8A9D1C132C808BA5AE42A05AB ON author');
        $this->addSql('DROP INDEX UNIQ_CBE5A3312B36786BCC1CF4E6 ON book');
        $this->addSql('DROP INDEX UNIQ_CBE5A3312B36786BBB827337 ON book');
    }
}
