<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220161538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, courses_id INT DEFAULT NULL, value INT DEFAULT NULL, INDEX IDX_CFBDFA1467B3B43D (users_id), INDEX IDX_CFBDFA14F9295384 (courses_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_course (user_id INT NOT NULL, course_id INT NOT NULL, INDEX IDX_73CC7484A76ED395 (user_id), INDEX IDX_73CC7484591CC992 (course_id), PRIMARY KEY(user_id, course_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA1467B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14F9295384 FOREIGN KEY (courses_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE user_course ADD CONSTRAINT FK_73CC7484A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_course ADD CONSTRAINT FK_73CC7484591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D60322AC');
        $this->addSql('DROP INDEX IDX_8D93D649D60322AC ON user');
        $this->addSql('ALTER TABLE user CHANGE role_id role_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6491BA3766E FOREIGN KEY (role_user_id) REFERENCES role (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6491BA3766E ON user (role_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA1467B3B43D');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14F9295384');
        $this->addSql('ALTER TABLE user_course DROP FOREIGN KEY FK_73CC7484A76ED395');
        $this->addSql('ALTER TABLE user_course DROP FOREIGN KEY FK_73CC7484591CC992');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE user_course');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491BA3766E');
        $this->addSql('DROP INDEX IDX_8D93D6491BA3766E ON user');
        $this->addSql('ALTER TABLE user CHANGE role_user_id role_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8D93D649D60322AC ON user (role_id)');
    }
}
