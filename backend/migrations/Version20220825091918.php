<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220825091918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_object ADD type TEXT NOT NULL');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_518B7ACFEBB4B8AD');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_518B7ACF4B89032C');
        $this->addSql('DROP INDEX idx_518b7acfebb4b8ad ON vote');
        $this->addSql('CREATE INDEX IDX_5A108564EBB4B8AD ON vote (voter_id)');
        $this->addSql('DROP INDEX idx_518b7acf4b89032c ON vote');
        $this->addSql('CREATE INDEX IDX_5A1085644B89032C ON vote (post_id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_518B7ACFEBB4B8AD FOREIGN KEY (voter_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_518B7ACF4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_object DROP type');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A108564EBB4B8AD');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A1085644B89032C');
        $this->addSql('DROP INDEX idx_5a1085644b89032c ON vote');
        $this->addSql('CREATE INDEX IDX_518B7ACF4B89032C ON vote (post_id)');
        $this->addSql('DROP INDEX idx_5a108564ebb4b8ad ON vote');
        $this->addSql('CREATE INDEX IDX_518B7ACFEBB4B8AD ON vote (voter_id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A108564EBB4B8AD FOREIGN KEY (voter_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A1085644B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
    }
}
