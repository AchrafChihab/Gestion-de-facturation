<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220315095406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lignedevis ADD datede DATETIME DEFAULT NULL, ADD datea DATETIME DEFAULT NULL, DROP date_de, DROP date_a');
        $this->addSql('ALTER TABLE service CHANGE type type TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lignedevis ADD date_de DATETIME DEFAULT NULL, ADD date_a DATETIME DEFAULT NULL, DROP datede, DROP datea');
        $this->addSql('ALTER TABLE service CHANGE type type TINYINT(1) DEFAULT NULL');
    }
}
