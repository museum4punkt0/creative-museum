<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220929102912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE awarded MODIFY giver_id INT NULL');
        $this->addSql('ALTER TABLE notification MODIFY award_giver_id INT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE awarded MODIFY giver_id INT NOT NULL');
        $this->addSql('ALTER TABLE notification MODIFY award_giver_id INT NOT NULL');
    }
}
