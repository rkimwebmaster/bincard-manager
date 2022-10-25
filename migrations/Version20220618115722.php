<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220618115722 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bill_verrou_mouvement (id INT AUTO_INCREMENT NOT NULL, site_id INT NOT NULL, rapport_mensuel_id INT NOT NULL, created_at DATETIME NOT NULL, mois INT NOT NULL, annee INT NOT NULL, INDEX IDX_E1A0E244F6BD1646 (site_id), UNIQUE INDEX UNIQ_E1A0E24482F4A7C5 (rapport_mensuel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bill_verrou_mouvement ADD CONSTRAINT FK_E1A0E244F6BD1646 FOREIGN KEY (site_id) REFERENCES bill_site (id)');
        $this->addSql('ALTER TABLE bill_verrou_mouvement ADD CONSTRAINT FK_E1A0E24482F4A7C5 FOREIGN KEY (rapport_mensuel_id) REFERENCES bill_rmensuel (id)');
        $this->addSql('DROP TABLE bill_rannuel_site');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bill_rannuel_site (id INT AUTO_INCREMENT NOT NULL, pn_id INT NOT NULL, r_mensuel_site_id INT NOT NULL, quantite_initiale DOUBLE PRECISION NOT NULL, quantite_entree DOUBLE PRECISION NOT NULL, quantite_entree_speciale DOUBLE PRECISION NOT NULL, quantite_entree_transfert DOUBLE PRECISION NOT NULL, quantite_entree_reuse DOUBLE PRECISION NOT NULL, sortie_client DOUBLE PRECISION NOT NULL, sortie_speciale VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, quantite_sortie_transfert DOUBLE PRECISION NOT NULL, quantite_finale DOUBLE PRECISION NOT NULL, sortie_damage DOUBLE PRECISION NOT NULL, INDEX IDX_B7FD20797D098BC0 (pn_id), INDEX IDX_B7FD2079CB603871 (r_mensuel_site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE bill_rannuel_site ADD CONSTRAINT FK_B7FD20797D098BC0 FOREIGN KEY (pn_id) REFERENCES bill_produit_site (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE bill_rannuel_site ADD CONSTRAINT FK_B7FD2079CB603871 FOREIGN KEY (r_mensuel_site_id) REFERENCES bill_rmensuel (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE bill_verrou_mouvement');
    }
}
