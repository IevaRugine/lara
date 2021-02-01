<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210129092050 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE outfits ADD CONSTRAINT FK_6C3BFD0B13B3DB11 FOREIGN KEY (master_id) REFERENCES masters (id)');
        $this->addSql('CREATE INDEX IDX_6C3BFD0B13B3DB11 ON outfits (master_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE outfits DROP FOREIGN KEY FK_6C3BFD0B13B3DB11');
        $this->addSql('DROP INDEX IDX_6C3BFD0B13B3DB11 ON outfits');
    }
}
