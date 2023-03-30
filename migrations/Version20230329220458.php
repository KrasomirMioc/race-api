<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230329220458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE race (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, race_date DATETIME NOT NULL, avg_time_for_medium_distance VARCHAR(255) DEFAULT NULL, avg_time_for_long_distance VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE result (id INT AUTO_INCREMENT NOT NULL, race_id INT DEFAULT NULL, full_name VARCHAR(255) NOT NULL, finish_time VARCHAR(255) NOT NULL, distance VARCHAR(255) NOT NULL, age_category VARCHAR(255) NOT NULL, overall_place INT NOT NULL, age_category_place INT NOT NULL, INDEX IDX_136AC1136E59D40D (race_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1136E59D40D FOREIGN KEY (race_id) REFERENCES race (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC1136E59D40D');
        $this->addSql('DROP TABLE race');
        $this->addSql('DROP TABLE result');
    }
}
