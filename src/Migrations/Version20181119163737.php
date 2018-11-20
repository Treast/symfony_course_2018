<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181119163737 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE movie CHANGE image_url image_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE movie_list MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE movie_list DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE movie_list ADD uuid VARCHAR(255) NOT NULL, ADD user_uuid_id VARCHAR(255) NOT NULL, DROP id');
        $this->addSql('ALTER TABLE movie_list ADD CONSTRAINT FK_B7AED91599B8CBC0 FOREIGN KEY (user_uuid_id) REFERENCES user (uuid)');
        $this->addSql('CREATE INDEX IDX_B7AED91599B8CBC0 ON movie_list (user_uuid_id)');
        $this->addSql('ALTER TABLE movie_list ADD PRIMARY KEY (uuid)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE movie CHANGE image_url image_url VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE movie_list DROP FOREIGN KEY FK_B7AED91599B8CBC0');
        $this->addSql('DROP INDEX IDX_B7AED91599B8CBC0 ON movie_list');
        $this->addSql('ALTER TABLE movie_list DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE movie_list ADD id INT AUTO_INCREMENT NOT NULL, DROP uuid, DROP user_uuid_id');
        $this->addSql('ALTER TABLE movie_list ADD PRIMARY KEY (id)');
    }
}
