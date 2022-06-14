<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220613130631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE media_object (id INT AUTO_INCREMENT NOT NULL, content_url VARCHAR(255) NOT NULL, filepath VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_media_object (post_id INT NOT NULL, media_object_id INT NOT NULL, INDEX IDX_BFE94CE14B89032C (post_id), INDEX IDX_BFE94CE164DE5A5 (media_object_id), PRIMARY KEY(post_id, media_object_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_media_object ADD CONSTRAINT FK_BFE94CE14B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_media_object ADD CONSTRAINT FK_BFE94CE164DE5A5 FOREIGN KEY (media_object_id) REFERENCES media_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE award ADD picture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE award ADD CONSTRAINT FK_8A5B2EE7EE45BDBF FOREIGN KEY (picture_id) REFERENCES media_object (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8A5B2EE7EE45BDBF ON award (picture_id)');
        $this->addSql('ALTER TABLE badge ADD picture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE badge ADD CONSTRAINT FK_FEF0481DEE45BDBF FOREIGN KEY (picture_id) REFERENCES media_object (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FEF0481DEE45BDBF ON badge (picture_id)');
        $this->addSql('ALTER TABLE partner ADD logo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE partner ADD CONSTRAINT FK_312B3E16F98F144A FOREIGN KEY (logo_id) REFERENCES media_object (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_312B3E16F98F144A ON partner (logo_id)');
        $this->addSql('ALTER TABLE user ADD profile_picture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649292E8AE2 FOREIGN KEY (profile_picture_id) REFERENCES media_object (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649292E8AE2 ON user (profile_picture_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE award DROP FOREIGN KEY FK_8A5B2EE7EE45BDBF');
        $this->addSql('ALTER TABLE badge DROP FOREIGN KEY FK_FEF0481DEE45BDBF');
        $this->addSql('ALTER TABLE partner DROP FOREIGN KEY FK_312B3E16F98F144A');
        $this->addSql('ALTER TABLE post_media_object DROP FOREIGN KEY FK_BFE94CE164DE5A5');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649292E8AE2');
        $this->addSql('DROP TABLE media_object');
        $this->addSql('DROP TABLE post_media_object');
        $this->addSql('DROP INDEX UNIQ_8A5B2EE7EE45BDBF ON award');
        $this->addSql('ALTER TABLE award DROP picture_id');
        $this->addSql('DROP INDEX UNIQ_FEF0481DEE45BDBF ON badge');
        $this->addSql('ALTER TABLE badge DROP picture_id');
        $this->addSql('DROP INDEX UNIQ_312B3E16F98F144A ON partner');
        $this->addSql('ALTER TABLE partner DROP logo_id');
        $this->addSql('DROP INDEX UNIQ_8D93D649292E8AE2 ON user');
        $this->addSql('ALTER TABLE user DROP profile_picture_id');
    }
}
