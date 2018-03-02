<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180225034400 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE basket_item (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, user_id VARCHAR(255) NOT NULL, quantity INT NOT NULL, status VARCHAR(255) NOT NULL, date_added DATETIME NOT NULL, INDEX IDX_D4943C2B4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE basket_item_selections (basket_item_id INT NOT NULL, preview_item_id INT NOT NULL, INDEX IDX_A238DCF9D6C84247 (basket_item_id), INDEX IDX_A238DCF989B54625 (preview_item_id), PRIMARY KEY(basket_item_id, preview_item_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE basket_item ADD CONSTRAINT FK_D4943C2B4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE basket_item_selections ADD CONSTRAINT FK_A238DCF9D6C84247 FOREIGN KEY (basket_item_id) REFERENCES basket_item (id)');
        $this->addSql('ALTER TABLE basket_item_selections ADD CONSTRAINT FK_A238DCF989B54625 FOREIGN KEY (preview_item_id) REFERENCES preview_item (id)');
        $this->addSql('DROP TABLE cart_current');
        $this->addSql('DROP TABLE cart_master');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE basket_item_selections DROP FOREIGN KEY FK_A238DCF9D6C84247');
        $this->addSql('CREATE TABLE cart_current (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, user_id TINYTEXT DEFAULT NULL COLLATE latin1_swedish_ci, quantity INT DEFAULT NULL, selection TINYTEXT DEFAULT NULL COLLATE latin1_swedish_ci, date_added DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX product_id (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart_master (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, user_id TINYTEXT DEFAULT NULL COLLATE latin1_swedish_ci, quantity INT DEFAULT NULL, selection TINYTEXT DEFAULT NULL COLLATE latin1_swedish_ci, item_status INT DEFAULT NULL, date_added DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX product_id (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart_current ADD CONSTRAINT cart_current_ibfk_1 FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE cart_master ADD CONSTRAINT cart_master_ibfk_1 FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('DROP TABLE basket_item');
        $this->addSql('DROP TABLE basket_item_selections');
    }
}
