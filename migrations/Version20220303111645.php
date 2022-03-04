<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220303111645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, statue_id INT DEFAULT NULL, client_id INT NOT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_FE8664101E8508FE (statue_id), INDEX IDX_FE86641019EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lignefacture (id INT AUTO_INCREMENT NOT NULL, statue_id INT DEFAULT NULL, service_id INT DEFAULT NULL, facture_id INT DEFAULT NULL, qte VARCHAR(255) NOT NULL, prix VARCHAR(255) NOT NULL, INDEX IDX_216F1FD51E8508FE (statue_id), INDEX IDX_216F1FD5ED5CA9E6 (service_id), INDEX IDX_216F1FD57F2DEE08 (facture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE8664101E8508FE FOREIGN KEY (statue_id) REFERENCES statue (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641019EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE lignefacture ADD CONSTRAINT FK_216F1FD51E8508FE FOREIGN KEY (statue_id) REFERENCES statue (id)');
        $this->addSql('ALTER TABLE lignefacture ADD CONSTRAINT FK_216F1FD5ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE lignefacture ADD CONSTRAINT FK_216F1FD57F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lignefacture DROP FOREIGN KEY FK_216F1FD57F2DEE08');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE lignefacture');
    }
}
