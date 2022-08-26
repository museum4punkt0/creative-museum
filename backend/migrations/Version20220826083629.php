<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220826083629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE award CHANGE description description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE badge CHANGE description description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE campaign CHANGE short_description short_description LONGTEXT NOT NULL, CHANGE description description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE post ADD votes_spread INT DEFAULT NULL, CHANGE body body LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE description description VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE award CHANGE description description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE badge CHANGE description description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE campaign CHANGE short_description short_description TEXT DEFAULT NULL, CHANGE description description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE post DROP votes_spread, CHANGE body body TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE description description TEXT DEFAULT NULL');
    }
}
