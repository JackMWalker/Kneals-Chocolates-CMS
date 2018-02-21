<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180215073808 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE product_previews');
        $this->addSql('ALTER TABLE product ADD selection_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADE48EFE78 FOREIGN KEY (selection_id) REFERENCES selection (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADE48EFE78 ON product (selection_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product_previews (product_id INT NOT NULL, preview_id INT NOT NULL, INDEX IDX_F6DE101F4584665A (product_id), INDEX IDX_F6DE101FCDE46FDB (preview_id), PRIMARY KEY(product_id, preview_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_previews ADD CONSTRAINT FK_F6DE101F4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_previews ADD CONSTRAINT FK_F6DE101FCDE46FDB FOREIGN KEY (preview_id) REFERENCES preview_item (id)');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADE48EFE78');
        $this->addSql('DROP INDEX IDX_D34A04ADE48EFE78 ON product');
        $this->addSql('ALTER TABLE product DROP selection_id');
    }
}
