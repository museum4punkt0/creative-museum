<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221104072243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE cms_content (id INT AUTO_INCREMENT NOT NULL, identifier VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('INSERT INTO cms_content (identifier, content) VALUES (\'about\', \'Lorem ipsum\')');
        $this->addSql('INSERT INTO cms_content (identifier, content) VALUES (\'portrait\', \'Lorem ipsum\')');
        $this->addSql('INSERT INTO cms_content (identifier, content) VALUES (\'simpleLanguage\', \'Lorem ipsum\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cms_content');
    }
}
