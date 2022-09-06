<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220719114343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_bookmark (user_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_3AEF761DA76ED395 (user_id), INDEX IDX_3AEF761D4B89032C (post_id), PRIMARY KEY(user_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_bookmark ADD CONSTRAINT FK_3AEF761DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_bookmark ADD CONSTRAINT FK_3AEF761D4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE user_post');
        $this->addSql('ALTER TABLE badge CHANGE type type TEXT NOT NULL, CHANGE post_type post_type TEXT NOT NULL');
        $this->addSql('ALTER TABLE post CHANGE type type TEXT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE notification_settings notification_settings TEXT NOT NULL');
        $this->addSql('ALTER TABLE votes CHANGE direction direction TEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_post (user_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_200B2044A76ED395 (user_id), INDEX IDX_200B20444B89032C (post_id), PRIMARY KEY(user_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_post ADD CONSTRAINT FK_200B2044A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_post ADD CONSTRAINT FK_200B20444B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE user_bookmark');
        $this->addSql('ALTER TABLE badge CHANGE type type MEDIUMTEXT NOT NULL, CHANGE post_type post_type MEDIUMTEXT NOT NULL');
        $this->addSql('ALTER TABLE post CHANGE type type MEDIUMTEXT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE notification_settings notification_settings MEDIUMTEXT NOT NULL');
        $this->addSql('ALTER TABLE votes CHANGE direction direction MEDIUMTEXT NOT NULL');
    }
}
