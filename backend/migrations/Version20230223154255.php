<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230223154255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_object ADD thumbnail_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object ADD CONSTRAINT FK_14D43132FDFF2E92 FOREIGN KEY (thumbnail_id) REFERENCES media_object (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_14D43132FDFF2E92 ON media_object (thumbnail_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_object DROP FOREIGN KEY FK_14D43132FDFF2E92');
        $this->addSql('DROP INDEX UNIQ_14D43132FDFF2E92 ON media_object');
        $this->addSql('ALTER TABLE media_object DROP thumbnail_id');
    }
}
