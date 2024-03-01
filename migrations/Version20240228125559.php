<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240228125559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB9F675F31B');
        $this->addSql('DROP TABLE teacher');
        $this->addSql('DROP INDEX IDX_169E6FB9F675F31B ON course');
        $this->addSql('ALTER TABLE course ADD created_by_id INT DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD image_name VARCHAR(255) DEFAULT NULL, DROP author_id');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_169E6FB9B03A8386 ON course (created_by_id)');
        $this->addSql('ALTER TABLE user DROP picture_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE teacher (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB9B03A8386');
        $this->addSql('DROP INDEX IDX_169E6FB9B03A8386 ON course');
        $this->addSql('ALTER TABLE course ADD author_id INT NOT NULL, DROP created_by_id, DROP updated_at, DROP image_name');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9F675F31B FOREIGN KEY (author_id) REFERENCES teacher (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_169E6FB9F675F31B ON course (author_id)');
        $this->addSql('ALTER TABLE user ADD picture_user VARCHAR(255) DEFAULT NULL');
    }
}
