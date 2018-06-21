<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180621160309 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO `grupy` (`id`, `nazwa`, `uprawnienia`, `status`) VALUES ('1', 'ADMINSITRATO', '{}', '1'), ('2', 'UZYTKOWNIK', '{}', '1')");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM `grupy` WHERE `grupy`.`id` IN (1,2);");
    }
}
