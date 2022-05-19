<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220511082620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE campaign_feedback_option_user (campaign_feedback_option_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_A9C598DFEA738099 (campaign_feedback_option_id), INDEX IDX_A9C598DFA76ED395 (user_id), PRIMARY KEY(campaign_feedback_option_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_post (user_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_200B2044A76ED395 (user_id), INDEX IDX_200B20444B89032C (post_id), PRIMARY KEY(user_id, post_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE campaign_feedback_option_user ADD CONSTRAINT FK_A9C598DFEA738099 FOREIGN KEY (campaign_feedback_option_id) REFERENCES campaign_feedback_option (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE campaign_feedback_option_user ADD CONSTRAINT FK_A9C598DFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_post ADD CONSTRAINT FK_200B2044A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_post ADD CONSTRAINT FK_200B20444B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE partner DROP FOREIGN KEY FK_312B3E164B89032C');
        $this->addSql('DROP INDEX IDX_312B3E164B89032C ON partner');
        $this->addSql('ALTER TABLE partner DROP post_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE campaign_feedback_option_user');
        $this->addSql('DROP TABLE user_post');
        $this->addSql('ALTER TABLE partner ADD post_id INT NOT NULL');
        $this->addSql('ALTER TABLE partner ADD CONSTRAINT FK_312B3E164B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('CREATE INDEX IDX_312B3E164B89032C ON partner (post_id)');
    }
}
