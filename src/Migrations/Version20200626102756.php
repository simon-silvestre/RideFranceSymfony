<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200626102756 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE favoris ADD skatepark_id INT NOT NULL');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C43289600404 FOREIGN KEY (skatepark_id) REFERENCES skate_parks (id)');
        $this->addSql('CREATE INDEX IDX_8933C43289600404 ON favoris (skatepark_id)');
        $this->addSql('ALTER TABLE skate_parks DROP FOREIGN KEY FK_2CF70951E8871B');
        $this->addSql('DROP INDEX IDX_2CF70951E8871B ON skate_parks');
        $this->addSql('ALTER TABLE skate_parks DROP favoris_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C43289600404');
        $this->addSql('DROP INDEX IDX_8933C43289600404 ON favoris');
        $this->addSql('ALTER TABLE favoris DROP skatepark_id');
        $this->addSql('ALTER TABLE skate_parks ADD favoris_id INT NOT NULL');
        $this->addSql('ALTER TABLE skate_parks ADD CONSTRAINT FK_2CF70951E8871B FOREIGN KEY (favoris_id) REFERENCES favoris (id)');
        $this->addSql('CREATE INDEX IDX_2CF70951E8871B ON skate_parks (favoris_id)');
    }
}
