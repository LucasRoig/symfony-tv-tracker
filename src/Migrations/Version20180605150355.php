<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180605150355 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE episodes_watched_by_user (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, episode_id INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_56C1E36FA76ED395 (user_id), INDEX IDX_56C1E36F362B62A0 (episode_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE episodes_watched_by_user ADD CONSTRAINT FK_56C1E36FA76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE episodes_watched_by_user ADD CONSTRAINT FK_56C1E36F362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id)');
        $this->addSql('DROP TABLE user_history');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_history (user_id INT NOT NULL, episode_id INT NOT NULL, INDEX IDX_7FB76E41A76ED395 (user_id), INDEX IDX_7FB76E41362B62A0 (episode_id), PRIMARY KEY(user_id, episode_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_history ADD CONSTRAINT FK_7FB76E41362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_history ADD CONSTRAINT FK_7FB76E41A76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE episodes_watched_by_user');
    }
}
