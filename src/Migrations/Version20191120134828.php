<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191120134828 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE paiement (idPaiement INT AUTO_INCREMENT NOT NULL, montant DOUBLE PRECISION NOT NULL, type VARCHAR(45) NOT NULL, datePaiement DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, noRecu INT NOT NULL, destination VARCHAR(45) NOT NULL, PRIMARY KEY(idPaiement)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE retrait (idRetrait INT AUTO_INCREMENT NOT NULL, montant INT NOT NULL, monnaie VARCHAR(45) NOT NULL, lieu VARCHAR(100) NOT NULL, noTransaction INT NOT NULL, date DATE NOT NULL, PRIMARY KEY(idRetrait)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateurhasgroupe (idUtilisateurHasGroupe INT AUTO_INCREMENT NOT NULL, utlisateurREF INT NOT NULL, groupeREF INT NOT NULL, PRIMARY KEY(idUtilisateurHasGroupe)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE retrait');
        $this->addSql('DROP TABLE utilisateurhasgroupe');
    }
}
