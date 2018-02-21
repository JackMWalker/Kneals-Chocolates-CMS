<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180201063204 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE preview_item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE preview_allergies (preview_id INT NOT NULL, allergy_id INT NOT NULL, INDEX IDX_5B085081CDE46FDB (preview_id), INDEX IDX_5B085081DBFD579D (allergy_id), PRIMARY KEY(preview_id, allergy_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, price INT NOT NULL, cost INT NOT NULL, weight INT NOT NULL, description VARCHAR(255) NOT NULL, is_live TINYINT(1) NOT NULL, postage_price INT DEFAULT 350 NOT NULL, postage_cost INT NOT NULL, stock INT DEFAULT 5 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_allergies (product_id INT NOT NULL, allergy_id INT NOT NULL, INDEX IDX_E3B909964584665A (product_id), INDEX IDX_E3B90996DBFD579D (allergy_id), PRIMARY KEY(product_id, allergy_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_previews (product_id INT NOT NULL, preview_id INT NOT NULL, INDEX IDX_F6DE101F4584665A (product_id), INDEX IDX_F6DE101FCDE46FDB (preview_id), PRIMARY KEY(product_id, preview_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_image (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_64617F034584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_option (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, price INT NOT NULL, cost INT NOT NULL, weight INT NOT NULL, postage_price INT DEFAULT 350 NOT NULL, postage_cost INT NOT NULL, stock INT DEFAULT 5 NOT NULL, INDEX IDX_38FA41144584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE option_allergies (option_id INT NOT NULL, allergy_id INT NOT NULL, INDEX IDX_5C82AF9BA7C41D6F (option_id), INDEX IDX_5C82AF9BDBFD579D (allergy_id), PRIMARY KEY(option_id, allergy_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE preview_allergies ADD CONSTRAINT FK_5B085081CDE46FDB FOREIGN KEY (preview_id) REFERENCES preview_item (id)');
        $this->addSql('ALTER TABLE preview_allergies ADD CONSTRAINT FK_5B085081DBFD579D FOREIGN KEY (allergy_id) REFERENCES allergy (id)');
        $this->addSql('ALTER TABLE product_allergies ADD CONSTRAINT FK_E3B909964584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_allergies ADD CONSTRAINT FK_E3B90996DBFD579D FOREIGN KEY (allergy_id) REFERENCES allergy (id)');
        $this->addSql('ALTER TABLE product_previews ADD CONSTRAINT FK_F6DE101F4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_previews ADD CONSTRAINT FK_F6DE101FCDE46FDB FOREIGN KEY (preview_id) REFERENCES preview_item (id)');
        $this->addSql('ALTER TABLE product_image ADD CONSTRAINT FK_64617F034584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_option ADD CONSTRAINT FK_38FA41144584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE option_allergies ADD CONSTRAINT FK_5C82AF9BA7C41D6F FOREIGN KEY (option_id) REFERENCES product_option (id)');
        $this->addSql('ALTER TABLE option_allergies ADD CONSTRAINT FK_5C82AF9BDBFD579D FOREIGN KEY (allergy_id) REFERENCES allergy (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CBB142B55E237E06 ON allergy (name)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE preview_allergies DROP FOREIGN KEY FK_5B085081CDE46FDB');
        $this->addSql('ALTER TABLE product_previews DROP FOREIGN KEY FK_F6DE101FCDE46FDB');
        $this->addSql('ALTER TABLE product_allergies DROP FOREIGN KEY FK_E3B909964584665A');
        $this->addSql('ALTER TABLE product_previews DROP FOREIGN KEY FK_F6DE101F4584665A');
        $this->addSql('ALTER TABLE product_image DROP FOREIGN KEY FK_64617F034584665A');
        $this->addSql('ALTER TABLE product_option DROP FOREIGN KEY FK_38FA41144584665A');
        $this->addSql('ALTER TABLE option_allergies DROP FOREIGN KEY FK_5C82AF9BA7C41D6F');
        $this->addSql('DROP TABLE preview_item');
        $this->addSql('DROP TABLE preview_allergies');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_allergies');
        $this->addSql('DROP TABLE product_previews');
        $this->addSql('DROP TABLE product_image');
        $this->addSql('DROP TABLE product_option');
        $this->addSql('DROP TABLE option_allergies');
        $this->addSql('DROP INDEX UNIQ_CBB142B55E237E06 ON allergy');
    }
}
