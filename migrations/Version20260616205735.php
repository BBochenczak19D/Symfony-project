<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260616205735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, author_id INT DEFAULT NULL, INDEX IDX_64C19C1F675F31B (author_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE operation (id INT AUTO_INCREMENT NOT NULL, amount NUMERIC(10, 2) NOT NULL, created_at DATETIME NOT NULL, description VARCHAR(255) NOT NULL, wallet_id INT NOT NULL, category_id INT DEFAULT NULL, INDEX IDX_1981A66D712520F3 (wallet_id), INDEX IDX_1981A66D12469DE2 (category_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE operation_tag (operation_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_1CA8A1BF44AC3583 (operation_id), INDEX IDX_1CA8A1BFBAD26311 (tag_id), PRIMARY KEY (operation_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, author_id INT DEFAULT NULL, INDEX IDX_389B783F675F31B (author_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE tasks (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE wallet (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, balance NUMERIC(10, 2) NOT NULL, currency VARCHAR(5) NOT NULL, author_id INT DEFAULT NULL, INDEX IDX_7C68921FF675F31B (author_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE operation_tag ADD CONSTRAINT FK_1CA8A1BF44AC3583 FOREIGN KEY (operation_id) REFERENCES operation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE operation_tag ADD CONSTRAINT FK_1CA8A1BFBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B783F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE wallet ADD CONSTRAINT FK_7C68921FF675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1F675F31B');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D712520F3');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D12469DE2');
        $this->addSql('ALTER TABLE operation_tag DROP FOREIGN KEY FK_1CA8A1BF44AC3583');
        $this->addSql('ALTER TABLE operation_tag DROP FOREIGN KEY FK_1CA8A1BFBAD26311');
        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B783F675F31B');
        $this->addSql('ALTER TABLE wallet DROP FOREIGN KEY FK_7C68921FF675F31B');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE operation');
        $this->addSql('DROP TABLE operation_tag');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tasks');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE wallet');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
