<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220303140546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE devis (id INT AUTO_INCREMENT NOT NULL, statue_id INT DEFAULT NULL, client_id INT NOT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_8B27C52B1E8508FE (statue_id), INDEX IDX_8B27C52B19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lignedevis (id INT AUTO_INCREMENT NOT NULL, statue_id INT DEFAULT NULL, service_id INT DEFAULT NULL, devis_id INT DEFAULT NULL, qte VARCHAR(255) NOT NULL, prix VARCHAR(255) NOT NULL, INDEX IDX_CC0A89B21E8508FE (statue_id), INDEX IDX_CC0A89B2ED5CA9E6 (service_id), INDEX IDX_CC0A89B241DEFADA (devis_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B1E8508FE FOREIGN KEY (statue_id) REFERENCES statue (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE lignedevis ADD CONSTRAINT FK_CC0A89B21E8508FE FOREIGN KEY (statue_id) REFERENCES statue (id)');
        $this->addSql('ALTER TABLE lignedevis ADD CONSTRAINT FK_CC0A89B2ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE lignedevis ADD CONSTRAINT FK_CC0A89B241DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lignedevis DROP FOREIGN KEY FK_CC0A89B241DEFADA');
        $this->addSql('DROP TABLE devis');
        $this->addSql('DROP TABLE lignedevis');
    }
}
