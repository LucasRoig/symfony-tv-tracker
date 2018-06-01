<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180601151649 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tv_shows (id INT AUTO_INCREMENT NOT NULL, backdrop_path VARCHAR(255) DEFAULT NULL, poster_path VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, first_air_date DATETIME NOT NULL, status VARCHAR(255) NOT NULL, overview LONGTEXT DEFAULT NULL, tmdb_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE `show`');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE `show` (id INT AUTO_INCREMENT NOT NULL, backdrop_path VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, poster_path VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, first_air_date DATETIME NOT NULL, status VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, overview LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, tmdb_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE tv_shows');
    }
}
