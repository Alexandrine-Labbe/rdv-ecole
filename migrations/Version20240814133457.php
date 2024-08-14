<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240814133457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appointment (id UUID NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, begin_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, teacher_id UUID NOT NULL, child_id UUID DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FE38F84441807E1D ON appointment (teacher_id)');
        $this->addSql('CREATE INDEX IDX_FE38F844DD62C21B ON appointment (child_id)');
        $this->addSql('CREATE TABLE appointment_guardian (appointment_id UUID NOT NULL, guardian_id UUID NOT NULL, PRIMARY KEY(appointment_id, guardian_id))');
        $this->addSql('CREATE INDEX IDX_516CEB9DE5B533F9 ON appointment_guardian (appointment_id)');
        $this->addSql('CREATE INDEX IDX_516CEB9D11CC8B0A ON appointment_guardian (guardian_id)');
        $this->addSql('CREATE TABLE child (id UUID NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE guardian (id UUID NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64486055E7927C74 ON guardian (email)');
        $this->addSql('CREATE TABLE teacher (id UUID NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, grade VARCHAR(255) NOT NULL, is_available_for_appointment BOOLEAN DEFAULT false NOT NULL, password_need_reset BOOLEAN DEFAULT false NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON teacher (email)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F84441807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844DD62C21B FOREIGN KEY (child_id) REFERENCES child (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE appointment_guardian ADD CONSTRAINT FK_516CEB9DE5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE appointment_guardian ADD CONSTRAINT FK_516CEB9D11CC8B0A FOREIGN KEY (guardian_id) REFERENCES guardian (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE appointment DROP CONSTRAINT FK_FE38F84441807E1D');
        $this->addSql('ALTER TABLE appointment DROP CONSTRAINT FK_FE38F844DD62C21B');
        $this->addSql('ALTER TABLE appointment_guardian DROP CONSTRAINT FK_516CEB9DE5B533F9');
        $this->addSql('ALTER TABLE appointment_guardian DROP CONSTRAINT FK_516CEB9D11CC8B0A');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('DROP TABLE appointment_guardian');
        $this->addSql('DROP TABLE child');
        $this->addSql('DROP TABLE guardian');
        $this->addSql('DROP TABLE teacher');
    }
}
