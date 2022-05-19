<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505093128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE badge ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE badge ADD CONSTRAINT FK_FEF0481DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FEF0481DA76ED395 ON badge (user_id)');
        $this->addSql('ALTER TABLE post ADD user_id INT NOT NULL, ADD parent_id INT DEFAULT NULL, ADD campaign_id INT NOT NULL, ADD playlist_id INT NOT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D727ACA70 FOREIGN KEY (parent_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF639F774 FOREIGN KEY (campaign_id) REFERENCES campaign (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D6BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DA76ED395 ON post (user_id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D727ACA70 ON post (parent_id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DF639F774 ON post (campaign_id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D6BBD148 ON post (playlist_id)');
        $this->addSql('ALTER TABLE user ADD username VARCHAR(255) NOT NULL, ADD password VARCHAR(255) NOT NULL, ADD tutorial TINYINT(1) NOT NULL, ADD active TINYINT(1) NOT NULL, ADD score INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE badge DROP FOREIGN KEY FK_FEF0481DA76ED395');
        $this->addSql('DROP INDEX IDX_FEF0481DA76ED395 ON badge');
        $this->addSql('ALTER TABLE badge DROP user_id');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D727ACA70');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DF639F774');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D6BBD148');
        $this->addSql('DROP INDEX IDX_5A8A6C8DA76ED395 ON post');
        $this->addSql('DROP INDEX IDX_5A8A6C8D727ACA70 ON post');
        $this->addSql('DROP INDEX IDX_5A8A6C8DF639F774 ON post');
        $this->addSql('DROP INDEX IDX_5A8A6C8D6BBD148 ON post');
        $this->addSql('ALTER TABLE post DROP user_id, DROP parent_id, DROP campaign_id, DROP playlist_id');
        $this->addSql('ALTER TABLE user DROP username, DROP password, DROP tutorial, DROP active, DROP score');
    }
}
