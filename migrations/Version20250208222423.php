<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250208222423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vehicule_utilisateur (vehicule_id INT NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_5B29C2504A4A3511 (vehicule_id), INDEX IDX_5B29C250FB88E14F (utilisateur_id), PRIMARY KEY(vehicule_id, utilisateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vehicule_utilisateur ADD CONSTRAINT FK_5B29C2504A4A3511 FOREIGN KEY (vehicule_id) REFERENCES vehicule (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vehicule_utilisateur ADD CONSTRAINT FK_5B29C250FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicule_utilisateur DROP FOREIGN KEY FK_5B29C2504A4A3511');
        $this->addSql('ALTER TABLE vehicule_utilisateur DROP FOREIGN KEY FK_5B29C250FB88E14F');
        $this->addSql('DROP TABLE vehicule_utilisateur');
    }
}
