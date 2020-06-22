<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200618114623 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commentaires ADD skatepark_id INT NOT NULL, ADD username_id INT NOT NULL, ADD note INT NOT NULL, DROP post_id, DROP user_pseudo, DROP notes');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C489600404 FOREIGN KEY (skatepark_id) REFERENCES skate_parks (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4ED766068 FOREIGN KEY (username_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_D9BEC0C489600404 ON commentaires (skatepark_id)');
        $this->addSql('CREATE INDEX IDX_D9BEC0C4ED766068 ON commentaires (username_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C489600404');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4ED766068');
        $this->addSql('DROP INDEX IDX_D9BEC0C489600404 ON commentaires');
        $this->addSql('DROP INDEX IDX_D9BEC0C4ED766068 ON commentaires');
        $this->addSql('ALTER TABLE commentaires ADD post_id INT NOT NULL, ADD user_pseudo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD notes INT NOT NULL, DROP skatepark_id, DROP username_id, DROP note');
    }
}
