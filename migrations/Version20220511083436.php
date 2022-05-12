<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220511083436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post_playlist (post_id INT NOT NULL, playlist_id INT NOT NULL, INDEX IDX_686F988C4B89032C (post_id), INDEX IDX_686F988C6BBD148 (playlist_id), PRIMARY KEY(post_id, playlist_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_playlist ADD CONSTRAINT FK_686F988C4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_playlist ADD CONSTRAINT FK_686F988C6BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D6BBD148');
        $this->addSql('DROP INDEX IDX_5A8A6C8D6BBD148 ON post');
        $this->addSql('ALTER TABLE post DROP playlist_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE post_playlist');
        $this->addSql('ALTER TABLE post ADD playlist_id INT NOT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D6BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D6BBD148 ON post (playlist_id)');
    }
}
