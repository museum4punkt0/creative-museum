<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220906123029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification ADD award_giver_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA772F4EFC FOREIGN KEY (award_giver_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_BF5476CA772F4EFC ON notification (award_giver_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA772F4EFC');
        $this->addSql('DROP INDEX IDX_BF5476CA772F4EFC ON notification');
        $this->addSql('ALTER TABLE notification DROP award_giver_id');
    }
}
