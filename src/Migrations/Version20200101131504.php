<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200101131504 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE avatar_image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE avatar_image (id INT NOT NULL, user_id INT DEFAULT NULL, original_name VARCHAR(255) NOT NULL, mime_type VARCHAR(255) DEFAULT NULL, size INT DEFAULT 0 NOT NULL, extension VARCHAR(255) DEFAULT \'\' NOT NULL, active BOOLEAN DEFAULT \'true\' NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D4AEAB68A76ED395 ON avatar_image (user_id)');
        $this->addSql('COMMENT ON COLUMN avatar_image.original_name IS \'Оригинальное имя файла\'');
        $this->addSql('COMMENT ON COLUMN avatar_image.mime_type IS \'Mime-тип\'');
        $this->addSql('COMMENT ON COLUMN avatar_image.size IS \'Размер файла\'');
        $this->addSql('COMMENT ON COLUMN avatar_image.extension IS \'Расширение файла\'');
        $this->addSql('COMMENT ON COLUMN avatar_image.active IS \'Выбран по-умолчанию\'');
        $this->addSql('COMMENT ON COLUMN avatar_image.deleted IS \'Маркер удаления\'');
        $this->addSql('COMMENT ON COLUMN avatar_image.created_at IS \'Дата создания\'');
        $this->addSql('COMMENT ON COLUMN avatar_image.updated_at IS \'Дата изменения\'');
        $this->addSql('ALTER TABLE avatar_image ADD CONSTRAINT FK_D4AEAB68A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE avatar_image_id_seq CASCADE');
        $this->addSql('DROP TABLE avatar_image');
    }
}
