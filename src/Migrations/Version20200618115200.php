<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200618115200 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE favoris ADD skatepark_id INT NOT NULL, ADD username_id INT NOT NULL, DROP post_id, DROP user_id');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C43289600404 FOREIGN KEY (skatepark_id) REFERENCES skate_parks (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432ED766068 FOREIGN KEY (username_id) REFERENCES users (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8933C43289600404 ON favoris (skatepark_id)');
        $this->addSql('CREATE INDEX IDX_8933C432ED766068 ON favoris (username_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C43289600404');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432ED766068');
        $this->addSql('DROP INDEX UNIQ_8933C43289600404 ON favoris');
        $this->addSql('DROP INDEX IDX_8933C432ED766068 ON favoris');
        $this->addSql('ALTER TABLE favoris ADD post_id INT NOT NULL, ADD user_id INT NOT NULL, DROP skatepark_id, DROP username_id');
    }
}
