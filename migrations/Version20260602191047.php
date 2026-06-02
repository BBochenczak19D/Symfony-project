<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260602191047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE operation_tag (operation_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_1CA8A1BF44AC3583 (operation_id), INDEX IDX_1CA8A1BFBAD26311 (tag_id), PRIMARY KEY (operation_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, author_id INT DEFAULT NULL, INDEX IDX_389B783F675F31B (author_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE operation_tag ADD CONSTRAINT FK_1CA8A1BF44AC3583 FOREIGN KEY (operation_id) REFERENCES operation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE operation_tag ADD CONSTRAINT FK_1CA8A1BFBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B783F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE operation_tag DROP FOREIGN KEY FK_1CA8A1BF44AC3583');
        $this->addSql('ALTER TABLE operation_tag DROP FOREIGN KEY FK_1CA8A1BFBAD26311');
        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B783F675F31B');
        $this->addSql('DROP TABLE operation_tag');
        $this->addSql('DROP TABLE tag');
    }
}
