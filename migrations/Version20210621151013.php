<?php

declare(strict_types=1);

namespace Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210621151013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $adminPass = password_hash('123', PASSWORD_BCRYPT);
        $this->addSql("INSERT INTO users (username, password, auth_key) VALUES ('admin', '$adminPass', null);");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM users WHERE username = 'admin'");
    }
}
