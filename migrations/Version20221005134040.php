<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221005134040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, prix_total DOUBLE PRECISION NOT NULL, date_creation DATETIME NOT NULL, ip VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_detail (id INT AUTO_INCREMENT NOT NULL, commande_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_2C52844682EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_detail_produit (commande_detail_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_8B316328C8DC59F9 (commande_detail_id), INDEX IDX_8B316328F347EFB (produit_id), PRIMARY KEY(commande_detail_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande_detail ADD CONSTRAINT FK_2C52844682EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commande_detail_produit ADD CONSTRAINT FK_8B316328C8DC59F9 FOREIGN KEY (commande_detail_id) REFERENCES commande_detail (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_detail_produit ADD CONSTRAINT FK_8B316328F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_detail DROP FOREIGN KEY FK_2C52844682EA2E54');
        $this->addSql('ALTER TABLE commande_detail_produit DROP FOREIGN KEY FK_8B316328C8DC59F9');
        $this->addSql('ALTER TABLE commande_detail_produit DROP FOREIGN KEY FK_8B316328F347EFB');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commande_detail');
        $this->addSql('DROP TABLE commande_detail_produit');
    }
}
