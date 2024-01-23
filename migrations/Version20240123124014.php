<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240123124014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE personnel ADD employe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE personnel ADD CONSTRAINT FK_A6BCF3DE1B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A6BCF3DE1B65292 ON personnel (employe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE personnel DROP FOREIGN KEY FK_A6BCF3DE1B65292');
        $this->addSql('DROP INDEX UNIQ_A6BCF3DE1B65292 ON personnel');
        $this->addSql('ALTER TABLE personnel DROP employe_id');
    }
}
