<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220826111941 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post ADD leading_feedback_option_id INT DEFAULT NULL, ADD leading_feedback_count INT NOT NULL DEFAULT 0');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D61650467 FOREIGN KEY (leading_feedback_option_id) REFERENCES campaign_feedback_option (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D61650467 ON post (leading_feedback_option_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D61650467');
        $this->addSql('DROP INDEX IDX_5A8A6C8D61650467 ON post');
        $this->addSql('ALTER TABLE post DROP leading_feedback_option_id, DROP leading_feedback_count');
    }
}
