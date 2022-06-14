<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220614094651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D63379586');
        $this->addSql('DROP INDEX IDX_5A8A6C8D63379586 ON post');
        $this->addSql('ALTER TABLE post ADD comments VARCHAR(255) DEFAULT NULL, DROP comments_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post ADD comments_id INT DEFAULT NULL, DROP comments');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D63379586 FOREIGN KEY (comments_id) REFERENCES post (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D63379586 ON post (comments_id)');
    }
}
