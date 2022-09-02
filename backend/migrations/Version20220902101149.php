<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220902101149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification ADD award_id INT DEFAULT NULL, ADD badge_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA3D5282CF FOREIGN KEY (award_id) REFERENCES award (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAF7A2C2FC FOREIGN KEY (badge_id) REFERENCES badge (id)');
        $this->addSql('CREATE INDEX IDX_BF5476CA3D5282CF ON notification (award_id)');
        $this->addSql('CREATE INDEX IDX_BF5476CAF7A2C2FC ON notification (badge_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA3D5282CF');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAF7A2C2FC');
        $this->addSql('DROP INDEX IDX_BF5476CA3D5282CF ON notification');
        $this->addSql('DROP INDEX IDX_BF5476CAF7A2C2FC ON notification');
        $this->addSql('ALTER TABLE notification DROP award_id, DROP badge_id');
    }
}
