<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220607111735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE badged (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, badge_id INT NOT NULL, INDEX IDX_FB25D65DA76ED395 (user_id), INDEX IDX_FB25D65DF7A2C2FC (badge_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE badged ADD CONSTRAINT FK_FB25D65DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE badged ADD CONSTRAINT FK_FB25D65DF7A2C2FC FOREIGN KEY (badge_id) REFERENCES badge (id)');
        $this->addSql('ALTER TABLE badge DROP FOREIGN KEY FK_FEF0481DA76ED395');
        $this->addSql('DROP INDEX IDX_FEF0481DA76ED395 ON badge');
        $this->addSql('ALTER TABLE badge DROP user_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE badged');
        $this->addSql('ALTER TABLE badge ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE badge ADD CONSTRAINT FK_FEF0481DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FEF0481DA76ED395 ON badge (user_id)');
    }
}
