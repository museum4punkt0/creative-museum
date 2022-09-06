<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220727133850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post ADD linked_playlist_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DEE94C1A0 FOREIGN KEY (linked_playlist_id) REFERENCES playlist (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DEE94C1A0 ON post (linked_playlist_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DEE94C1A0');
        $this->addSql('DROP INDEX IDX_5A8A6C8DEE94C1A0 ON post');
        $this->addSql('ALTER TABLE post DROP linked_playlist_id');
    }
}
