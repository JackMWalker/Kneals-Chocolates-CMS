<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180226070024 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE basket_transaction (id INT AUTO_INCREMENT NOT NULL, price DOUBLE PRECISION NOT NULL, postage DOUBLE PRECISION NOT NULL, total_price DOUBLE PRECISION NOT NULL, uniqid VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_paypal_transaction (id INT AUTO_INCREMENT NOT NULL, transaction_id INT DEFAULT NULL, address_line1 VARCHAR(255) NOT NULL, address_line2 VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, payer_id VARCHAR(255) NOT NULL, payment_status VARCHAR(255) NOT NULL, payment_id VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, fee DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_462388552FC0CB0F (transaction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payment_paypal_transaction ADD CONSTRAINT FK_462388552FC0CB0F FOREIGN KEY (transaction_id) REFERENCES basket_transaction (id)');
        $this->addSql('ALTER TABLE basket_item ADD transaction_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE basket_item ADD CONSTRAINT FK_D4943C2B2FC0CB0F FOREIGN KEY (transaction_id) REFERENCES basket_transaction (id)');
        $this->addSql('CREATE INDEX IDX_D4943C2B2FC0CB0F ON basket_item (transaction_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE basket_item DROP FOREIGN KEY FK_D4943C2B2FC0CB0F');
        $this->addSql('ALTER TABLE payment_paypal_transaction DROP FOREIGN KEY FK_462388552FC0CB0F');
        $this->addSql('DROP TABLE basket_transaction');
        $this->addSql('DROP TABLE payment_paypal_transaction');
        $this->addSql('DROP INDEX IDX_D4943C2B2FC0CB0F ON basket_item');
        $this->addSql('ALTER TABLE basket_item DROP transaction_id');
    }
}
