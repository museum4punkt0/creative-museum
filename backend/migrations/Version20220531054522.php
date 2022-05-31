<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220531054522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE awarded_award');
        $this->addSql('ALTER TABLE awarded ADD award_id INT NOT NULL');
        $this->addSql('ALTER TABLE awarded ADD CONSTRAINT FK_DE65CAE33D5282CF FOREIGN KEY (award_id) REFERENCES award (id)');
        $this->addSql('CREATE INDEX IDX_DE65CAE33D5282CF ON awarded (award_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE awarded_award (awarded_id INT NOT NULL, award_id INT NOT NULL, INDEX IDX_B8F94426C1280104 (awarded_id), INDEX IDX_B8F944263D5282CF (award_id), PRIMARY KEY(awarded_id, award_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE awarded_award ADD CONSTRAINT FK_B8F94426C1280104 FOREIGN KEY (awarded_id) REFERENCES awarded (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE awarded_award ADD CONSTRAINT FK_B8F944263D5282CF FOREIGN KEY (award_id) REFERENCES award (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE awarded DROP FOREIGN KEY FK_DE65CAE33D5282CF');
        $this->addSql('DROP INDEX IDX_DE65CAE33D5282CF ON awarded');
        $this->addSql('ALTER TABLE awarded DROP award_id');
    }
}
