<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230509133450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attributes (id INT AUTO_INCREMENT NOT NULL, stats_id INT DEFAULT NULL, hair_color VARCHAR(100) DEFAULT NULL, ethnicity VARCHAR(100) DEFAULT NULL, tattoos TINYINT(1) DEFAULT NULL, piercings TINYINT(1) NOT NULL, breast_size SMALLINT DEFAULT NULL, breast_type VARCHAR(10) DEFAULT NULL, gender VARCHAR(20) DEFAULT NULL, orientation VARCHAR(50) DEFAULT NULL, age SMALLINT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_319B9E7070AA3482 (stats_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feed_history (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(20) NOT NULL, site VARCHAR(100) NOT NULL, date DATETIME NOT NULL, items_count INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE porn_star (id INT AUTO_INCREMENT NOT NULL, attributes_id INT DEFAULT NULL, external_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, license VARCHAR(255) DEFAULT NULL, wl_status INT DEFAULT NULL, aliases JSON DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_4B417569F75D7B0 (external_id), UNIQUE INDEX UNIQ_4B41756BAAF4009 (attributes_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stats (id INT AUTO_INCREMENT NOT NULL, subscriptions INT DEFAULT NULL, monthly_searches INT DEFAULT NULL, views INT DEFAULT NULL, videos_count INT DEFAULT NULL, premium_videos_count INT DEFAULT NULL, white_label_video_count INT DEFAULT NULL, stats_rank INT DEFAULT NULL, rank_premium INT DEFAULT NULL, rankwl INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thumbnail (id INT AUTO_INCREMENT NOT NULL, porn_star_id INT DEFAULT NULL, tumbnail_image_id INT DEFAULT NULL, height INT DEFAULT NULL, width INT DEFAULT NULL, type VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C35726E6C7FF6435 (porn_star_id), INDEX IDX_C35726E6FA23C40C (tumbnail_image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thumbnail_image (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_4AAD5FAF47645AE (url), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attributes ADD CONSTRAINT FK_319B9E7070AA3482 FOREIGN KEY (stats_id) REFERENCES stats (id)');
        $this->addSql('ALTER TABLE porn_star ADD CONSTRAINT FK_4B41756BAAF4009 FOREIGN KEY (attributes_id) REFERENCES attributes (id)');
        $this->addSql('ALTER TABLE thumbnail ADD CONSTRAINT FK_C35726E6C7FF6435 FOREIGN KEY (porn_star_id) REFERENCES porn_star (id)');
        $this->addSql('ALTER TABLE thumbnail ADD CONSTRAINT FK_C35726E6FA23C40C FOREIGN KEY (tumbnail_image_id) REFERENCES thumbnail_image (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attributes DROP FOREIGN KEY FK_319B9E7070AA3482');
        $this->addSql('ALTER TABLE porn_star DROP FOREIGN KEY FK_4B41756BAAF4009');
        $this->addSql('ALTER TABLE thumbnail DROP FOREIGN KEY FK_C35726E6C7FF6435');
        $this->addSql('ALTER TABLE thumbnail DROP FOREIGN KEY FK_C35726E6FA23C40C');
        $this->addSql('DROP TABLE attributes');
        $this->addSql('DROP TABLE feed_history');
        $this->addSql('DROP TABLE porn_star');
        $this->addSql('DROP TABLE stats');
        $this->addSql('DROP TABLE thumbnail');
        $this->addSql('DROP TABLE thumbnail_image');
    }
}
