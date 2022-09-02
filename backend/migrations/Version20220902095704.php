<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220902095704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE badge DROP price');
        $this->addSql('DROP INDEX IF EXISTS campaign_collection_index ON campaign');
        $this->addSql('CREATE INDEX IF NOT EXISTS campaign_collection_index ON campaign (active, notified, start, stop)');
        $this->addSql('ALTER TABLE notification ADD created DATETIME NOT NULL');
        $this->addSql('UPDATE notification SET created = CURDATE()');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE badge ADD price INT DEFAULT NULL');
        $this->addSql('DROP INDEX IF EXISTS campaign_collection_index ON campaign');
        $this->addSql('CREATE INDEX IF NOT EXISTS campaign_collection_index ON campaign (start, stop, active)');
        $this->addSql('ALTER TABLE notification DROP created');
    }
}
