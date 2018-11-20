<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181120112344 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE movie (uuid VARCHAR(255) NOT NULL, genre VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, year INT NOT NULL, image_url VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE actors_movies (movie_uuid VARCHAR(255) NOT NULL, actor_uuid VARCHAR(255) NOT NULL, INDEX IDX_B3012DC0221971AB (movie_uuid), INDEX IDX_B3012DC0E1376255 (actor_uuid), PRIMARY KEY(movie_uuid, actor_uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (uuid VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE playlist (uuid VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, User VARCHAR(255) NOT NULL, INDEX IDX_D782112D2DA17977 (User), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movies_playlists (playlist_uuid VARCHAR(255) NOT NULL, movie_uuid VARCHAR(255) NOT NULL, INDEX IDX_76ECDD5E716667C4 (playlist_uuid), INDEX IDX_76ECDD5E221971AB (movie_uuid), PRIMARY KEY(playlist_uuid, movie_uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE actor (uuid VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_447556F95E237E06 (name), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE actors_movies ADD CONSTRAINT FK_B3012DC0221971AB FOREIGN KEY (movie_uuid) REFERENCES movie (uuid)');
        $this->addSql('ALTER TABLE actors_movies ADD CONSTRAINT FK_B3012DC0E1376255 FOREIGN KEY (actor_uuid) REFERENCES actor (uuid)');
        $this->addSql('ALTER TABLE playlist ADD CONSTRAINT FK_D782112D2DA17977 FOREIGN KEY (User) REFERENCES user (uuid)');
        $this->addSql('ALTER TABLE movies_playlists ADD CONSTRAINT FK_76ECDD5E716667C4 FOREIGN KEY (playlist_uuid) REFERENCES playlist (uuid)');
        $this->addSql('ALTER TABLE movies_playlists ADD CONSTRAINT FK_76ECDD5E221971AB FOREIGN KEY (movie_uuid) REFERENCES movie (uuid)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE actors_movies DROP FOREIGN KEY FK_B3012DC0221971AB');
        $this->addSql('ALTER TABLE movies_playlists DROP FOREIGN KEY FK_76ECDD5E221971AB');
        $this->addSql('ALTER TABLE playlist DROP FOREIGN KEY FK_D782112D2DA17977');
        $this->addSql('ALTER TABLE movies_playlists DROP FOREIGN KEY FK_76ECDD5E716667C4');
        $this->addSql('ALTER TABLE actors_movies DROP FOREIGN KEY FK_B3012DC0E1376255');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE actors_movies');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE playlist');
        $this->addSql('DROP TABLE movies_playlists');
        $this->addSql('DROP TABLE actor');
    }
}
