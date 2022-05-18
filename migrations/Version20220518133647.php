<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220518133647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE campaign_feedback_option_user');
        $this->addSql('ALTER TABLE post_feedback DROP INDEX UNIQ_6DC4CDF9E48EFE78, ADD INDEX IDX_6DC4CDF9E48EFE78 (selection_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE campaign_feedback_option_user (campaign_feedback_option_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_A9C598DFA76ED395 (user_id), INDEX IDX_A9C598DFEA738099 (campaign_feedback_option_id), PRIMARY KEY(campaign_feedback_option_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE campaign_feedback_option_user ADD CONSTRAINT FK_A9C598DFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE campaign_feedback_option_user ADD CONSTRAINT FK_A9C598DFEA738099 FOREIGN KEY (campaign_feedback_option_id) REFERENCES campaign_feedback_option (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_feedback DROP INDEX IDX_6DC4CDF9E48EFE78, ADD UNIQUE INDEX UNIQ_6DC4CDF9E48EFE78 (selection_id)');
    }
}
