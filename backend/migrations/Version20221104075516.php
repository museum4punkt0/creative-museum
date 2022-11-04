<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221104075516 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cms_content ADD media_object_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cms_content ADD CONSTRAINT FK_A0293FB864DE5A5 FOREIGN KEY (media_object_id) REFERENCES media_object (id)');
        $this->addSql('CREATE INDEX IDX_A0293FB864DE5A5 ON cms_content (media_object_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cms_content DROP FOREIGN KEY FK_A0293FB864DE5A5');
        $this->addSql('DROP INDEX IDX_A0293FB864DE5A5 ON cms_content');
        $this->addSql('ALTER TABLE cms_content DROP media_object_id');
    }
}
