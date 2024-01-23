<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240123130227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employe DROP FOREIGN KEY FK_F804D3B92DFF238F');
        $this->addSql('DROP INDEX IDX_F804D3B92DFF238F ON employe');
        $this->addSql('ALTER TABLE employe DROP email, CHANGE absence_id personnel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE employe ADD CONSTRAINT FK_F804D3B91C109075 FOREIGN KEY (personnel_id) REFERENCES personnel (id)');
        $this->addSql('CREATE INDEX IDX_F804D3B91C109075 ON employe (personnel_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employe DROP FOREIGN KEY FK_F804D3B91C109075');
        $this->addSql('DROP INDEX IDX_F804D3B91C109075 ON employe');
        $this->addSql('ALTER TABLE employe ADD email VARCHAR(255) NOT NULL, CHANGE personnel_id absence_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE employe ADD CONSTRAINT FK_F804D3B92DFF238F FOREIGN KEY (absence_id) REFERENCES absence (id)');
        $this->addSql('CREATE INDEX IDX_F804D3B92DFF238F ON employe (absence_id)');
    }
}
