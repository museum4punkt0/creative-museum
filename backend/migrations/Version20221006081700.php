<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221006081700 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE badge ADD short_description LONGTEXT NOT NULL, ADD link VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE badge MODIFY description LONGTEXT NULL');
        $this->addSql('ALTER TABLE award MODIFY description LONGTEXT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE badge DROP short_description, DROP link');
    }
}
