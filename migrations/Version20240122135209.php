<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240122135209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE absence (id INT AUTO_INCREMENT NOT NULL, type_absence_id_id INT DEFAULT NULL, employe_id_id INT DEFAULT NULL, date_debut_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_fin_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', statut VARCHAR(255) NOT NULL, INDEX IDX_765AE0C96F6EB679 (type_absence_id_id), INDEX IDX_765AE0C9325980C0 (employe_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conge (id INT AUTO_INCREMENT NOT NULL, type_conge VARCHAR(255) NOT NULL, regles VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employe (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, departement VARCHAR(255) NOT NULL, poste VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE heures_sup (id INT AUTO_INCREMENT NOT NULL, employe_id INT DEFAULT NULL, date DATE NOT NULL, nombre_heures INT NOT NULL, approuve_par VARCHAR(255) NOT NULL, INDEX IDX_89D6876B1B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rapport (id INT AUTO_INCREMENT NOT NULL, periode VARCHAR(255) NOT NULL, type_rapport VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_absence (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE absence ADD CONSTRAINT FK_765AE0C96F6EB679 FOREIGN KEY (type_absence_id_id) REFERENCES type_absence (id)');
        $this->addSql('ALTER TABLE absence ADD CONSTRAINT FK_765AE0C9325980C0 FOREIGN KEY (employe_id_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE heures_sup ADD CONSTRAINT FK_89D6876B1B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE absence DROP FOREIGN KEY FK_765AE0C96F6EB679');
        $this->addSql('ALTER TABLE absence DROP FOREIGN KEY FK_765AE0C9325980C0');
        $this->addSql('ALTER TABLE heures_sup DROP FOREIGN KEY FK_89D6876B1B65292');
        $this->addSql('DROP TABLE absence');
        $this->addSql('DROP TABLE conge');
        $this->addSql('DROP TABLE employe');
        $this->addSql('DROP TABLE heures_sup');
        $this->addSql('DROP TABLE rapport');
        $this->addSql('DROP TABLE type_absence');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
