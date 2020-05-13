<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200513124930 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE award ADD movie_id INT DEFAULT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE name label VARCHAR(255) NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE award ADD CONSTRAINT FK_8A5B2EE78F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('CREATE INDEX IDX_8A5B2EE78F93B6FC ON award (movie_id)');
        $this->addSql('ALTER TABLE movie CHANGE category_id category_id INT DEFAULT NULL, CHANGE director_id director_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE award MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE award DROP FOREIGN KEY FK_8A5B2EE78F93B6FC');
        $this->addSql('DROP INDEX IDX_8A5B2EE78F93B6FC ON award');
        $this->addSql('ALTER TABLE award DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE award DROP movie_id, CHANGE id id INT NOT NULL, CHANGE label name VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE movie CHANGE category_id category_id INT DEFAULT NULL, CHANGE director_id director_id INT DEFAULT NULL');
    }
}
