<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220831234526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image (id INT UNSIGNED AUTO_INCREMENT NOT NULL, reference VARCHAR(255) NOT NULL, source VARCHAR(50) DEFAULT NULL, name VARCHAR(100) DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_image (tag_id INT UNSIGNED NOT NULL, image_id INT UNSIGNED NOT NULL, INDEX IDX_E7D11D59BAD26311 (tag_id), INDEX IDX_E7D11D593DA5256D (image_id), PRIMARY KEY(tag_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tag_image ADD CONSTRAINT FK_E7D11D59BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_image ADD CONSTRAINT FK_E7D11D593DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tag_image DROP FOREIGN KEY FK_E7D11D59BAD26311');
        $this->addSql('ALTER TABLE tag_image DROP FOREIGN KEY FK_E7D11D593DA5256D');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_image');
    }
}
