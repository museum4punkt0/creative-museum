<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220829135246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post CHANGE leading_feedback_count leading_feedback_count INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD full_name VARCHAR(255) NOT NULL');
        $this->addSql('UPDATE user SET full_name = CONCAT(first_name, " ", last_name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post CHANGE leading_feedback_count leading_feedback_count INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE user DROP full_name');
    }
}
