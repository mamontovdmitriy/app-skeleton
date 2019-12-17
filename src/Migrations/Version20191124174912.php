<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191124174912 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status INT DEFAULT 0 NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, email VARCHAR(64) DEFAULT \'\' NOT NULL, google_id VARCHAR(64) DEFAULT \'\' NOT NULL, fb_id VARCHAR(64) DEFAULT \'\' NOT NULL, vk_id VARCHAR(64) DEFAULT \'\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX user_status_idx ON users (status)');
        $this->addSql('CREATE INDEX user_google_idx ON users (google_id)');
        $this->addSql('CREATE INDEX user_fb_idx ON users (fb_id)');
        $this->addSql('CREATE INDEX user_vk_idx ON users (vk_id)');
        $this->addSql('COMMENT ON COLUMN users.created IS \'Дата создания\'');
        $this->addSql('COMMENT ON COLUMN users.updated IS \'Дата изменения\'');
        $this->addSql('COMMENT ON COLUMN users.status IS \'Статус пользователя\'');
        $this->addSql('COMMENT ON COLUMN users.name IS \'Имя пользователя\'');
        $this->addSql('COMMENT ON COLUMN users.email IS \'Email пользователя\'');
        $this->addSql('COMMENT ON COLUMN users.google_id IS \'Google ID\'');
        $this->addSql('COMMENT ON COLUMN users.fb_id IS \'Facebook ID\'');
        $this->addSql('COMMENT ON COLUMN users.vk_id IS \'VKontakte ID\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

//        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('DROP TABLE users');
    }
}
