<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220708133845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE award CONVERT TO CHARACTER SET UTF8MB4 COLLATE utf8mb4_unicode_ci");
        $this->addSql("ALTER TABLE awarded CONVERT TO CHARACTER SET UTF8MB4 COLLATE utf8mb4_unicode_ci");
        $this->addSql("ALTER TABLE badge CONVERT TO CHARACTER SET UTF8MB4 COLLATE utf8mb4_unicode_ci");
        $this->addSql("ALTER TABLE badged CONVERT TO CHARACTER SET UTF8MB4 COLLATE utf8mb4_unicode_ci");
        $this->addSql("ALTER TABLE campaign CONVERT TO CHARACTER SET UTF8MB4 COLLATE utf8mb4_unicode_ci");
        $this->addSql("ALTER TABLE campaign_feedback_option CONVERT TO CHARACTER SET UTF8MB4 COLLATE utf8mb4_unicode_ci");
        $this->addSql("ALTER TABLE campaign_member CONVERT TO CHARACTER SET UTF8MB4 COLLATE utf8mb4_unicode_ci");
        $this->addSql("ALTER TABLE media_object CONVERT TO CHARACTER SET UTF8MB4 COLLATE utf8mb4_unicode_ci");
        $this->addSql("ALTER TABLE messenger_messages CONVERT TO CHARACTER SET UTF8MB4 COLLATE utf8mb4_unicode_ci");
        $this->addSql("ALTER TABLE notification CONVERT TO CHARACTER SET UTF8MB4 COLLATE utf8mb4_unicode_ci");
        $this->addSql("ALTER TABLE partner CONVERT TO CHARACTER SET UTF8MB4 COLLATE utf8mb4_unicode_ci");
        $this->addSql("ALTER TABLE playlist CONVERT TO CHARACTER SET UTF8MB4 COLLATE utf8mb4_unicode_ci");
        $this->addSql("ALTER TABLE playlist_post CONVERT TO CHARACTER SET UTF8MB4 COLLATE utf8mb4_unicode_ci");
        $this->addSql("ALTER TABLE poll_option CONVERT TO CHARACTER SET UTF8MB4 COLLATE utf8mb4_unicode_ci");
        $this->addSql("ALTER TABLE poll_option_choice CONVERT TO CHARACTER SET UTF8MB4 COLLATE utf8mb4_unicode_ci");
        $this->addSql("ALTER TABLE post CONVERT TO CHARACTER SET UTF8MB4 COLLATE utf8mb4_unicode_ci");
        $this->addSql("ALTER TABLE post_feedback CONVERT TO CHARACTER SET UTF8MB4 COLLATE utf8mb4_unicode_ci");
        $this->addSql("ALTER TABLE post_media_object CONVERT TO CHARACTER SET UTF8MB4 COLLATE utf8mb4_unicode_ci");
        $this->addSql("ALTER TABLE post_playlist CONVERT TO CHARACTER SET UTF8MB4 COLLATE utf8mb4_unicode_ci");
        $this->addSql("ALTER TABLE refresh_token CONVERT TO CHARACTER SET UTF8MB4 COLLATE utf8mb4_unicode_ci");
        $this->addSql("ALTER TABLE user CONVERT TO CHARACTER SET UTF8MB4 COLLATE utf8mb4_unicode_ci");
        $this->addSql("ALTER TABLE user_post CONVERT TO CHARACTER SET UTF8MB4 COLLATE utf8mb4_unicode_ci");
        $this->addSql("ALTER TABLE votes CONVERT TO CHARACTER SET UTF8MB4 COLLATE utf8mb4_unicode_ci");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE award CONVERT TO CHARACTER SET UTF8 COLLATE utf8_unicode_ci");
        $this->addSql("ALTER TABLE awarded CONVERT TO CHARACTER SET UTF8 COLLATE utf8_unicode_ci");
        $this->addSql("ALTER TABLE badge CONVERT TO CHARACTER SET UTF8 COLLATE utf8_unicode_ci");
        $this->addSql("ALTER TABLE badged CONVERT TO CHARACTER SET UTF8 COLLATE utf8_unicode_ci");
        $this->addSql("ALTER TABLE campaign CONVERT TO CHARACTER SET UTF8 COLLATE utf8_unicode_ci");
        $this->addSql("ALTER TABLE campaign_feedback_option CONVERT TO CHARACTER SET UTF8 COLLATE utf8_unicode_ci");
        $this->addSql("ALTER TABLE campaign_member CONVERT TO CHARACTER SET UTF8 COLLATE utf8_unicode_ci");
        $this->addSql("ALTER TABLE media_object CONVERT TO CHARACTER SET UTF8 COLLATE utf8_unicode_ci");
        $this->addSql("ALTER TABLE messenger_messages CONVERT TO CHARACTER SET UTF8 COLLATE utf8_unicode_ci");
        $this->addSql("ALTER TABLE notification CONVERT TO CHARACTER SET UTF8 COLLATE utf8_unicode_ci");
        $this->addSql("ALTER TABLE partner CONVERT TO CHARACTER SET UTF8 COLLATE utf8_unicode_ci");
        $this->addSql("ALTER TABLE playlist CONVERT TO CHARACTER SET UTF8 COLLATE utf8_unicode_ci");
        $this->addSql("ALTER TABLE playlist_post CONVERT TO CHARACTER SET UTF8 COLLATE utf8_unicode_ci");
        $this->addSql("ALTER TABLE poll_option CONVERT TO CHARACTER SET UTF8 COLLATE utf8_unicode_ci");
        $this->addSql("ALTER TABLE poll_option_choice CONVERT TO CHARACTER SET UTF8 COLLATE utf8_unicode_ci");
        $this->addSql("ALTER TABLE post CONVERT TO CHARACTER SET UTF8 COLLATE utf8_unicode_ci");
        $this->addSql("ALTER TABLE post_feedback CONVERT TO CHARACTER SET UTF8 COLLATE utf8_unicode_ci");
        $this->addSql("ALTER TABLE post_media_object CONVERT TO CHARACTER SET UTF8 COLLATE utf8_unicode_ci");
        $this->addSql("ALTER TABLE post_playlist CONVERT TO CHARACTER SET UTF8 COLLATE utf8_unicode_ci");
        $this->addSql("ALTER TABLE refresh_token CONVERT TO CHARACTER SET UTF8 COLLATE utf8_unicode_ci");
        $this->addSql("ALTER TABLE user CONVERT TO CHARACTER SET UTF8 COLLATE utf8_unicode_ci");
        $this->addSql("ALTER TABLE user_post CONVERT TO CHARACTER SET UTF8 COLLATE utf8_unicode_ci");
        $this->addSql("ALTER TABLE votes CONVERT TO CHARACTER SET UTF8 COLLATE utf8_unicode_ci");
    }
}