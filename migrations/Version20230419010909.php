<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230419010909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD drone_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849552CDF9A FOREIGN KEY (drone_id) REFERENCES drone (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_42C849552CDF9A ON reservation (drone_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849552CDF9A');
        $this->addSql('DROP INDEX UNIQ_42C849552CDF9A ON reservation');
        $this->addSql('ALTER TABLE reservation DROP drone_id');
    }
}
