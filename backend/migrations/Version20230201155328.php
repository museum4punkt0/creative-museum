<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230201155328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_playlist DROP FOREIGN KEY FK_686F988C4B89032C');
        $this->addSql('ALTER TABLE post_playlist DROP FOREIGN KEY FK_686F988C6BBD148');
        $this->addSql('DROP TABLE post_playlist');
        $this->addSql('ALTER TABLE playlist_post DROP FOREIGN KEY FK_FA935BEF4B89032C');
        $this->addSql('ALTER TABLE playlist_post DROP FOREIGN KEY FK_FA935BEF6BBD148');
        $this->addSql('ALTER TABLE playlist_post ADD id INT AUTO_INCREMENT NOT NULL, CHANGE playlist_id playlist_id INT DEFAULT NULL, CHANGE post_id post_id INT DEFAULT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE playlist_post ADD CONSTRAINT FK_FA935BEF4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE playlist_post ADD CONSTRAINT FK_FA935BEF6BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post_playlist (post_id INT NOT NULL, playlist_id INT NOT NULL, INDEX IDX_686F988C4B89032C (post_id), INDEX IDX_686F988C6BBD148 (playlist_id), PRIMARY KEY(post_id, playlist_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE post_playlist ADD CONSTRAINT FK_686F988C4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_playlist ADD CONSTRAINT FK_686F988C6BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE playlist_post MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE playlist_post DROP FOREIGN KEY FK_FA935BEF6BBD148');
        $this->addSql('ALTER TABLE playlist_post DROP FOREIGN KEY FK_FA935BEF4B89032C');
        $this->addSql('DROP INDEX `PRIMARY` ON playlist_post');
        $this->addSql('ALTER TABLE playlist_post DROP id, CHANGE playlist_id playlist_id INT NOT NULL, CHANGE post_id post_id INT NOT NULL');
        $this->addSql('ALTER TABLE playlist_post ADD CONSTRAINT FK_FA935BEF6BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE playlist_post ADD CONSTRAINT FK_FA935BEF4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE playlist_post ADD PRIMARY KEY (playlist_id, post_id)');
    }
}
