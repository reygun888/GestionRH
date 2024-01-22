<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240122140320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE absence DROP FOREIGN KEY FK_765AE0C9325980C0');
        $this->addSql('ALTER TABLE absence DROP FOREIGN KEY FK_765AE0C96F6EB679');
        $this->addSql('DROP INDEX IDX_765AE0C96F6EB679 ON absence');
        $this->addSql('DROP INDEX IDX_765AE0C9325980C0 ON absence');
        $this->addSql('ALTER TABLE absence ADD type_absence_id INT DEFAULT NULL, ADD employe_id INT DEFAULT NULL, DROP type_absence_id_id, DROP employe_id_id');
        $this->addSql('ALTER TABLE absence ADD CONSTRAINT FK_765AE0C930FCF5AA FOREIGN KEY (type_absence_id) REFERENCES type_absence (id)');
        $this->addSql('ALTER TABLE absence ADD CONSTRAINT FK_765AE0C91B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
        $this->addSql('CREATE INDEX IDX_765AE0C930FCF5AA ON absence (type_absence_id)');
        $this->addSql('CREATE INDEX IDX_765AE0C91B65292 ON absence (employe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE absence DROP FOREIGN KEY FK_765AE0C930FCF5AA');
        $this->addSql('ALTER TABLE absence DROP FOREIGN KEY FK_765AE0C91B65292');
        $this->addSql('DROP INDEX IDX_765AE0C930FCF5AA ON absence');
        $this->addSql('DROP INDEX IDX_765AE0C91B65292 ON absence');
        $this->addSql('ALTER TABLE absence ADD type_absence_id_id INT DEFAULT NULL, ADD employe_id_id INT DEFAULT NULL, DROP type_absence_id, DROP employe_id');
        $this->addSql('ALTER TABLE absence ADD CONSTRAINT FK_765AE0C9325980C0 FOREIGN KEY (employe_id_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE absence ADD CONSTRAINT FK_765AE0C96F6EB679 FOREIGN KEY (type_absence_id_id) REFERENCES type_absence (id)');
        $this->addSql('CREATE INDEX IDX_765AE0C96F6EB679 ON absence (type_absence_id_id)');
        $this->addSql('CREATE INDEX IDX_765AE0C9325980C0 ON absence (employe_id_id)');
    }
}
