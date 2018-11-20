<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181119152301 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE actors_movies (movie_uuid VARCHAR(255) NOT NULL, actor_uuid VARCHAR(255) NOT NULL, INDEX IDX_B3012DC0221971AB (movie_uuid), INDEX IDX_B3012DC0E1376255 (actor_uuid), PRIMARY KEY(movie_uuid, actor_uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE actors_movies ADD CONSTRAINT FK_B3012DC0221971AB FOREIGN KEY (movie_uuid) REFERENCES movie (uuid)');
        $this->addSql('ALTER TABLE actors_movies ADD CONSTRAINT FK_B3012DC0E1376255 FOREIGN KEY (actor_uuid) REFERENCES actor (uuid)');
        $this->addSql('ALTER TABLE movie CHANGE image_url image_url VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE actors_movies');
        $this->addSql('ALTER TABLE movie CHANGE image_url image_url VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
