<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200619093548 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4ED766068');
        $this->addSql('DROP INDEX IDX_D9BEC0C4ED766068 ON commentaires');
        $this->addSql('ALTER TABLE commentaires ADD username VARCHAR(255) NOT NULL, DROP username_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commentaires ADD username_id INT NOT NULL, DROP username');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4ED766068 FOREIGN KEY (username_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_D9BEC0C4ED766068 ON commentaires (username_id)');
    }
}
