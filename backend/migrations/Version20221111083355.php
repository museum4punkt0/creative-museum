<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221111083355 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO cms_content (identifier, content) VALUES (\'faq\', \'Lorem ipsum\')');
        $this->addSql('INSERT INTO cms_content (identifier, content) VALUES (\'signLanguage\', \'Lorem ipsum\')');
        $this->addSql('DELETE FROM cms_content WHERE identifier = \'portrait\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM cms_content WHERE identifier = \'faq\'');
        $this->addSql('DELETE FROM cms_content WHERE identifier = \'signLanguage\'');
        $this->addSql('INSERT INTO cms_content (identifier, content) VALUES (\'portrait\', \'Lorem ipsum\')');
    }
}
