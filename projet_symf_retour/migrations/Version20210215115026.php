<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210215115026 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profil CHANGE archivage archivage TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE referentiel ADD presentation VARCHAR(255) DEFAULT NULL, ADD programme LONGBLOB DEFAULT NULL, ADD ce VARCHAR(255) NOT NULL, ADD ca VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profil CHANGE archivage archivage TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE referentiel DROP presentation, DROP programme, DROP ce, DROP ca');
    }
}
