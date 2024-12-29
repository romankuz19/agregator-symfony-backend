<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241224200602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE chat_messages_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE chats_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE comments_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE reviews_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE services_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tasks_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE chat_messages (id INT NOT NULL, chat_id INT NOT NULL, sender_id INT NOT NULL, message TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EF20C9A61A9A7125 ON chat_messages (chat_id)');
        $this->addSql('CREATE INDEX IDX_EF20C9A6F624B39D ON chat_messages (sender_id)');
        $this->addSql('CREATE TABLE chats (id INT NOT NULL, first_user_id INT NOT NULL, second_user_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2D68180FB4E2BF69 ON chats (first_user_id)');
        $this->addSql('CREATE INDEX IDX_2D68180FB02C53F8 ON chats (second_user_id)');
        $this->addSql('CREATE TABLE comments (id INT NOT NULL, service_id INT NOT NULL, author_id INT NOT NULL, comment TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5F9E962AED5CA9E6 ON comments (service_id)');
        $this->addSql('CREATE INDEX IDX_5F9E962AF675F31B ON comments (author_id)');
        $this->addSql('CREATE TABLE reviews (id INT NOT NULL, service_id INT NOT NULL, author_id INT NOT NULL, text TEXT NOT NULL, rating INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6970EB0FED5CA9E6 ON reviews (service_id)');
        $this->addSql('CREATE INDEX IDX_6970EB0FF675F31B ON reviews (author_id)');
        $this->addSql('CREATE TABLE services (id INT NOT NULL, author_id INT NOT NULL, username VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, text TEXT NOT NULL, category VARCHAR(255) NOT NULL, img_url VARCHAR(255) DEFAULT NULL, price INT NOT NULL, views INT NOT NULL, rating INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7332E169F675F31B ON services (author_id)');
        $this->addSql('CREATE TABLE tasks (id INT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, date VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, category VARCHAR(255) NOT NULL, price INT NOT NULL, responses INT NOT NULL, status VARCHAR(255) NOT NULL, views INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_50586597F675F31B ON tasks (author_id)');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, email VARCHAR(180) NOT NULL, is_verified BOOLEAN NOT NULL, roles JSON NOT NULL, password VARCHAR(255) DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, second_name VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, phone_number BIGINT DEFAULT NULL, admin BOOLEAN NOT NULL, secret_question VARCHAR(255) DEFAULT NULL, secret_question_answer VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E96B01BC5B ON users (phone_number)');
        $this->addSql('ALTER TABLE chat_messages ADD CONSTRAINT FK_EF20C9A61A9A7125 FOREIGN KEY (chat_id) REFERENCES chats (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chat_messages ADD CONSTRAINT FK_EF20C9A6F624B39D FOREIGN KEY (sender_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chats ADD CONSTRAINT FK_2D68180FB4E2BF69 FOREIGN KEY (first_user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chats ADD CONSTRAINT FK_2D68180FB02C53F8 FOREIGN KEY (second_user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AED5CA9E6 FOREIGN KEY (service_id) REFERENCES services (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AF675F31B FOREIGN KEY (author_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0FED5CA9E6 FOREIGN KEY (service_id) REFERENCES services (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0FF675F31B FOREIGN KEY (author_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE services ADD CONSTRAINT FK_7332E169F675F31B FOREIGN KEY (author_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_50586597F675F31B FOREIGN KEY (author_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE chat_messages_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE chats_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE comments_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE reviews_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE services_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tasks_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('ALTER TABLE chat_messages DROP CONSTRAINT FK_EF20C9A61A9A7125');
        $this->addSql('ALTER TABLE chat_messages DROP CONSTRAINT FK_EF20C9A6F624B39D');
        $this->addSql('ALTER TABLE chats DROP CONSTRAINT FK_2D68180FB4E2BF69');
        $this->addSql('ALTER TABLE chats DROP CONSTRAINT FK_2D68180FB02C53F8');
        $this->addSql('ALTER TABLE comments DROP CONSTRAINT FK_5F9E962AED5CA9E6');
        $this->addSql('ALTER TABLE comments DROP CONSTRAINT FK_5F9E962AF675F31B');
        $this->addSql('ALTER TABLE reviews DROP CONSTRAINT FK_6970EB0FED5CA9E6');
        $this->addSql('ALTER TABLE reviews DROP CONSTRAINT FK_6970EB0FF675F31B');
        $this->addSql('ALTER TABLE services DROP CONSTRAINT FK_7332E169F675F31B');
        $this->addSql('ALTER TABLE tasks DROP CONSTRAINT FK_50586597F675F31B');
        $this->addSql('DROP TABLE chat_messages');
        $this->addSql('DROP TABLE chats');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE reviews');
        $this->addSql('DROP TABLE services');
        $this->addSql('DROP TABLE tasks');
        $this->addSql('DROP TABLE users');
    }
}
