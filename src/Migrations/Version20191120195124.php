<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191120195124 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE activechatroom (idChatRoom INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, PRIMARY KEY(idChatRoom)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE userhasgroupe MODIFY idUserHasGroupe INT NOT NULL');
        $this->addSql('ALTER TABLE userhasgroupe DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE userhasgroupe DROP droitUser, CHANGE iduserhasgroupe idUtilisateurHasGroupe INT AUTO_INCREMENT NOT NULL, CHANGE userref utlisateurREF INT NOT NULL');
        $this->addSql('ALTER TABLE userhasgroupe ADD PRIMARY KEY (idUtilisateurHasGroupe)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE activechatroom');
        $this->addSql('ALTER TABLE userhasgroupe MODIFY idUtilisateurHasGroupe INT NOT NULL');
        $this->addSql('ALTER TABLE userhasgroupe DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE userhasgroupe ADD droitUser LONGTEXT DEFAULT \'member\' NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:simple_array)\', CHANGE idutilisateurhasgroupe idUserHasGroupe INT AUTO_INCREMENT NOT NULL, CHANGE utlisateurref userREF INT NOT NULL');
        $this->addSql('ALTER TABLE userhasgroupe ADD PRIMARY KEY (idUserHasGroupe)');
    }
}
