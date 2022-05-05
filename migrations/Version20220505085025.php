<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505085025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE award (id INT AUTO_INCREMENT NOT NULL, campaign_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price INT NOT NULL, INDEX IDX_8A5B2EE7F639F774 (campaign_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE awarded (id INT AUTO_INCREMENT NOT NULL, giver_id INT NOT NULL, winner_id INT NOT NULL, INDEX IDX_DE65CAE375BD1D29 (giver_id), INDEX IDX_DE65CAE35DFCD4B8 (winner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE awarded_award (awarded_id INT NOT NULL, award_id INT NOT NULL, INDEX IDX_B8F94426C1280104 (awarded_id), INDEX IDX_B8F944263D5282CF (award_id), PRIMARY KEY(awarded_id, award_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE badge (id INT AUTO_INCREMENT NOT NULL, campaign_id INT NOT NULL, threshold INT NOT NULL, type TEXT NOT NULL, post_type TEXT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_FEF0481DF639F774 (campaign_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE campaign (id INT AUTO_INCREMENT NOT NULL, active TINYINT(1) NOT NULL, created DATETIME NOT NULL, start DATETIME NOT NULL, stop DATETIME NOT NULL, updated_at DATETIME NOT NULL, title VARCHAR(255) NOT NULL, short_description VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE campaign_feedback_option (id INT AUTO_INCREMENT NOT NULL, campaign_id INT NOT NULL, text VARCHAR(255) NOT NULL, INDEX IDX_1A7DBBEBF639F774 (campaign_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE campaign_member (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, campaign_id INT NOT NULL, score INT NOT NULL, INDEX IDX_ADA783D5A76ED395 (user_id), INDEX IDX_ADA783D5F639F774 (campaign_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, receiver_id INT NOT NULL, post_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, INDEX IDX_BF5476CACD53EDB6 (receiver_id), INDEX IDX_BF5476CA4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partner (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, campaign_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_312B3E164B89032C (post_id), INDEX IDX_312B3E16F639F774 (campaign_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE playlist (id INT AUTO_INCREMENT NOT NULL, creator_id INT NOT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_D782112D61220EA6 (creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE playlist_post (playlist_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_FA935BEF6BBD148 (playlist_id), INDEX IDX_FA935BEF4B89032C (post_id), PRIMARY KEY(playlist_id, post_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poll_option (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_B68343EB4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poll_option_choice (id INT AUTO_INCREMENT NOT NULL, poll_option_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_7EACEC3A6C13349B (poll_option_id), INDEX IDX_7EACEC3AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_feedback (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, post_id INT NOT NULL, selection_id INT NOT NULL, INDEX IDX_6DC4CDF9A76ED395 (user_id), INDEX IDX_6DC4CDF94B89032C (post_id), UNIQUE INDEX UNIQ_6DC4CDF9E48EFE78 (selection_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE votes (id INT AUTO_INCREMENT NOT NULL, voter_id INT NOT NULL, post_id INT NOT NULL, direction TEXT NOT NULL, INDEX IDX_518B7ACFEBB4B8AD (voter_id), INDEX IDX_518B7ACF4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE award ADD CONSTRAINT FK_8A5B2EE7F639F774 FOREIGN KEY (campaign_id) REFERENCES campaign (id)');
        $this->addSql('ALTER TABLE awarded ADD CONSTRAINT FK_DE65CAE375BD1D29 FOREIGN KEY (giver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE awarded ADD CONSTRAINT FK_DE65CAE35DFCD4B8 FOREIGN KEY (winner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE awarded_award ADD CONSTRAINT FK_B8F94426C1280104 FOREIGN KEY (awarded_id) REFERENCES awarded (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE awarded_award ADD CONSTRAINT FK_B8F944263D5282CF FOREIGN KEY (award_id) REFERENCES award (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE badge ADD CONSTRAINT FK_FEF0481DF639F774 FOREIGN KEY (campaign_id) REFERENCES campaign (id)');
        $this->addSql('ALTER TABLE campaign_feedback_option ADD CONSTRAINT FK_1A7DBBEBF639F774 FOREIGN KEY (campaign_id) REFERENCES campaign (id)');
        $this->addSql('ALTER TABLE campaign_member ADD CONSTRAINT FK_ADA783D5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE campaign_member ADD CONSTRAINT FK_ADA783D5F639F774 FOREIGN KEY (campaign_id) REFERENCES campaign (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CACD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE partner ADD CONSTRAINT FK_312B3E164B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE partner ADD CONSTRAINT FK_312B3E16F639F774 FOREIGN KEY (campaign_id) REFERENCES campaign (id)');
        $this->addSql('ALTER TABLE playlist ADD CONSTRAINT FK_D782112D61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE playlist_post ADD CONSTRAINT FK_FA935BEF6BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE playlist_post ADD CONSTRAINT FK_FA935BEF4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE poll_option ADD CONSTRAINT FK_B68343EB4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE poll_option_choice ADD CONSTRAINT FK_7EACEC3A6C13349B FOREIGN KEY (poll_option_id) REFERENCES poll_option (id)');
        $this->addSql('ALTER TABLE poll_option_choice ADD CONSTRAINT FK_7EACEC3AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post_feedback ADD CONSTRAINT FK_6DC4CDF9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post_feedback ADD CONSTRAINT FK_6DC4CDF94B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post_feedback ADD CONSTRAINT FK_6DC4CDF9E48EFE78 FOREIGN KEY (selection_id) REFERENCES campaign_feedback_option (id)');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACFEBB4B8AD FOREIGN KEY (voter_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACF4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post CHANGE type type TEXT NOT NULL');
        $this->addSql('ALTER TABLE user ADD notification_settings TEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE awarded_award DROP FOREIGN KEY FK_B8F944263D5282CF');
        $this->addSql('ALTER TABLE awarded_award DROP FOREIGN KEY FK_B8F94426C1280104');
        $this->addSql('ALTER TABLE award DROP FOREIGN KEY FK_8A5B2EE7F639F774');
        $this->addSql('ALTER TABLE badge DROP FOREIGN KEY FK_FEF0481DF639F774');
        $this->addSql('ALTER TABLE campaign_feedback_option DROP FOREIGN KEY FK_1A7DBBEBF639F774');
        $this->addSql('ALTER TABLE campaign_member DROP FOREIGN KEY FK_ADA783D5F639F774');
        $this->addSql('ALTER TABLE partner DROP FOREIGN KEY FK_312B3E16F639F774');
        $this->addSql('ALTER TABLE post_feedback DROP FOREIGN KEY FK_6DC4CDF9E48EFE78');
        $this->addSql('ALTER TABLE playlist_post DROP FOREIGN KEY FK_FA935BEF6BBD148');
        $this->addSql('ALTER TABLE poll_option_choice DROP FOREIGN KEY FK_7EACEC3A6C13349B');
        $this->addSql('DROP TABLE award');
        $this->addSql('DROP TABLE awarded');
        $this->addSql('DROP TABLE awarded_award');
        $this->addSql('DROP TABLE badge');
        $this->addSql('DROP TABLE campaign');
        $this->addSql('DROP TABLE campaign_feedback_option');
        $this->addSql('DROP TABLE campaign_member');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE partner');
        $this->addSql('DROP TABLE playlist');
        $this->addSql('DROP TABLE playlist_post');
        $this->addSql('DROP TABLE poll_option');
        $this->addSql('DROP TABLE poll_option_choice');
        $this->addSql('DROP TABLE post_feedback');
        $this->addSql('DROP TABLE votes');
        $this->addSql('ALTER TABLE post CHANGE type type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user DROP notification_settings');
    }
}
