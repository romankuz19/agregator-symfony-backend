<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241229235845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE categories_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE categories (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE services ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE services DROP username');
        $this->addSql('ALTER TABLE services DROP category');
        $this->addSql('ALTER TABLE services ADD CONSTRAINT FK_7332E16912469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7332E16912469DE2 ON services (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE services DROP CONSTRAINT FK_7332E16912469DE2');
        $this->addSql('DROP SEQUENCE categories_id_seq CASCADE');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP INDEX IDX_7332E16912469DE2');
        $this->addSql('ALTER TABLE services ADD username VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE services ADD category VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE services DROP category_id');
    }
}
