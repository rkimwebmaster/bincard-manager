--
-- Script was generated by Devart dbForge Studio 2019 for MySQL, Version 8.2.23.0
-- Product home page: http://www.devart.com/dbforge/mysql/studio
-- Script date 5/2/2022 3:59:30 PM
-- Server version: 8.0.18
-- Client version: 4.1
--

-- 
-- Disable foreign keys
-- 
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

-- 
-- Set SQL mode
-- 
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 
-- Set character set the client will use to send SQL statements to the server
--
SET NAMES 'utf8';

--
-- Set default database
--
USE billstock_db;

--
-- Drop table `bill_ligne_ranuelgeneral`
--
DROP TABLE IF EXISTS bill_ligne_ranuelgeneral;

--
-- Drop table `bill_ranuelgeneral`
--
DROP TABLE IF EXISTS bill_ranuelgeneral;

--
-- Drop table `bill_ligne_rannuel_site`
--
DROP TABLE IF EXISTS bill_ligne_rannuel_site;

--
-- Drop table `bill_ligne_rmensuel`
--
DROP TABLE IF EXISTS bill_ligne_rmensuel;

--
-- Drop table `bill_ligne_billcard`
--
DROP TABLE IF EXISTS bill_ligne_billcard;

--
-- Drop table `bill_ligne_controle`
--
DROP TABLE IF EXISTS bill_ligne_controle;

--
-- Drop table `bill_ligne_entree`
--
DROP TABLE IF EXISTS bill_ligne_entree;

--
-- Drop table `bill_ligne_sortie`
--
DROP TABLE IF EXISTS bill_ligne_sortie;

--
-- Drop table `bill_ligne_stock_controle`
--
DROP TABLE IF EXISTS bill_ligne_stock_controle;

--
-- Drop table `bill_ligne_transfert`
--
DROP TABLE IF EXISTS bill_ligne_transfert;

--
-- Drop table `bill_rannuel_site`
--
DROP TABLE IF EXISTS bill_rannuel_site;

--
-- Drop table `bill_produit_site`
--
DROP TABLE IF EXISTS bill_produit_site;

--
-- Drop table `bill_produit`
--
DROP TABLE IF EXISTS bill_produit;

--
-- Drop table `bill_ranuelsite`
--
DROP TABLE IF EXISTS bill_ranuelsite;

--
-- Drop table `bill_rmensuel`
--
DROP TABLE IF EXISTS bill_rmensuel;

--
-- Drop table `bill_controle`
--
DROP TABLE IF EXISTS bill_controle;

--
-- Drop table `bill_entree`
--
DROP TABLE IF EXISTS bill_entree;

--
-- Drop table `bill_sortie`
--
DROP TABLE IF EXISTS bill_sortie;

--
-- Drop table `bill_transfert`
--
DROP TABLE IF EXISTS bill_transfert;

--
-- Drop table `user_site`
--
DROP TABLE IF EXISTS user_site;

--
-- Drop table `bill_user`
--
DROP TABLE IF EXISTS bill_user;

--
-- Drop table `bill_site`
--
DROP TABLE IF EXISTS bill_site;

--
-- Drop table `bill_client`
--
DROP TABLE IF EXISTS bill_client;

--
-- Drop table `bill_fournisseur`
--
DROP TABLE IF EXISTS bill_fournisseur;

--
-- Drop table `bill_identite`
--
DROP TABLE IF EXISTS bill_identite;

--
-- Drop table `bill_ville`
--
DROP TABLE IF EXISTS bill_ville;

--
-- Set default database
--
USE billstock_db;

--
-- Create table `bill_ville`
--
CREATE TABLE bill_ville (
  id INT(11) NOT NULL AUTO_INCREMENT,
  designation VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 2,
AVG_ROW_LENGTH = 8192,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `UNIQ_75338C5D8947610D` on table `bill_ville`
--
ALTER TABLE bill_ville 
  ADD UNIQUE INDEX UNIQ_75338C5D8947610D(designation);

--
-- Create table `bill_identite`
--
CREATE TABLE bill_identite (
  id INT(11) NOT NULL AUTO_INCREMENT,
  ville_id INT(11) NOT NULL,
  telephone VARCHAR(255) NOT NULL,
  adresse VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_F3687B4AA73F0036` on table `bill_identite`
--
ALTER TABLE bill_identite 
  ADD INDEX IDX_F3687B4AA73F0036(ville_id);

--
-- Create foreign key
--
ALTER TABLE bill_identite 
  ADD CONSTRAINT FK_F3687B4AA73F0036 FOREIGN KEY (ville_id)
    REFERENCES bill_ville(id);

--
-- Create table `bill_fournisseur`
--
CREATE TABLE bill_fournisseur (
  id INT(11) NOT NULL AUTO_INCREMENT,
  ville_id INT(11) NOT NULL,
  telephone VARCHAR(255) NOT NULL,
  adresse VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  code VARCHAR(255) NOT NULL,
  noms VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 2,
AVG_ROW_LENGTH = 8192,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_4781723FA73F0036` on table `bill_fournisseur`
--
ALTER TABLE bill_fournisseur 
  ADD INDEX IDX_4781723FA73F0036(ville_id);

--
-- Create index `UNIQ_4781723F77153098` on table `bill_fournisseur`
--
ALTER TABLE bill_fournisseur 
  ADD UNIQUE INDEX UNIQ_4781723F77153098(code);

--
-- Create foreign key
--
ALTER TABLE bill_fournisseur 
  ADD CONSTRAINT FK_4781723FA73F0036 FOREIGN KEY (ville_id)
    REFERENCES bill_ville(id);

--
-- Create table `bill_client`
--
CREATE TABLE bill_client (
  id INT(11) NOT NULL AUTO_INCREMENT,
  ville_id INT(11) NOT NULL,
  noms VARCHAR(255) NOT NULL,
  code VARCHAR(255) NOT NULL,
  is_site TINYINT(1) NOT NULL,
  telephone VARCHAR(255) NOT NULL,
  adresse VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 4,
AVG_ROW_LENGTH = 4096,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_D0C54A43A73F0036` on table `bill_client`
--
ALTER TABLE bill_client 
  ADD INDEX IDX_D0C54A43A73F0036(ville_id);

--
-- Create index `UNIQ_D0C54A4377153098` on table `bill_client`
--
ALTER TABLE bill_client 
  ADD UNIQUE INDEX UNIQ_D0C54A4377153098(code);

--
-- Create foreign key
--
ALTER TABLE bill_client 
  ADD CONSTRAINT FK_D0C54A43A73F0036 FOREIGN KEY (ville_id)
    REFERENCES bill_ville(id);

--
-- Create table `bill_site`
--
CREATE TABLE bill_site (
  id INT(11) NOT NULL AUTO_INCREMENT,
  ville_id INT(11) NOT NULL,
  site_client_id INT(11) NOT NULL,
  telephone VARCHAR(255) NOT NULL,
  adresse VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  designation VARCHAR(255) NOT NULL,
  code VARCHAR(255) NOT NULL,
  is_warehouse TINYINT(1) NOT NULL,
  validation_attendu INT(11) NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 5,
AVG_ROW_LENGTH = 4096,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_9A5C614EA73F0036` on table `bill_site`
--
ALTER TABLE bill_site 
  ADD INDEX IDX_9A5C614EA73F0036(ville_id);

--
-- Create index `UNIQ_9A5C614E77153098` on table `bill_site`
--
ALTER TABLE bill_site 
  ADD UNIQUE INDEX UNIQ_9A5C614E77153098(code);

--
-- Create index `UNIQ_9A5C614E7FE11F54` on table `bill_site`
--
ALTER TABLE bill_site 
  ADD UNIQUE INDEX UNIQ_9A5C614E7FE11F54(site_client_id);

--
-- Create index `UNIQ_9A5C614E8947610D` on table `bill_site`
--
ALTER TABLE bill_site 
  ADD UNIQUE INDEX UNIQ_9A5C614E8947610D(designation);

--
-- Create foreign key
--
ALTER TABLE bill_site 
  ADD CONSTRAINT FK_9A5C614E7FE11F54 FOREIGN KEY (site_client_id)
    REFERENCES bill_client(id);

--
-- Create foreign key
--
ALTER TABLE bill_site 
  ADD CONSTRAINT FK_9A5C614EA73F0036 FOREIGN KEY (ville_id)
    REFERENCES bill_ville(id);

--
-- Create table `bill_user`
--
CREATE TABLE bill_user (
  id INT(11) NOT NULL AUTO_INCREMENT,
  site_actif_id INT(11) DEFAULT NULL,
  username VARCHAR(180) NOT NULL,
  roles JSON NOT NULL,
  password VARCHAR(255) NOT NULL,
  nom VARCHAR(255) NOT NULL,
  postnom VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  adresse VARCHAR(255) NOT NULL,
  telephone VARCHAR(255) NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 5,
AVG_ROW_LENGTH = 4096,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_7E8CBEE34CAC78F8` on table `bill_user`
--
ALTER TABLE bill_user 
  ADD INDEX IDX_7E8CBEE34CAC78F8(site_actif_id);

--
-- Create index `UNIQ_7E8CBEE3E7927C74` on table `bill_user`
--
ALTER TABLE bill_user 
  ADD UNIQUE INDEX UNIQ_7E8CBEE3E7927C74(email);

--
-- Create index `UNIQ_7E8CBEE3F85E0677` on table `bill_user`
--
ALTER TABLE bill_user 
  ADD UNIQUE INDEX UNIQ_7E8CBEE3F85E0677(username);

--
-- Create foreign key
--
ALTER TABLE bill_user 
  ADD CONSTRAINT FK_7E8CBEE34CAC78F8 FOREIGN KEY (site_actif_id)
    REFERENCES bill_site(id);

--
-- Create table `user_site`
--
CREATE TABLE user_site (
  user_id INT(11) NOT NULL,
  site_id INT(11) NOT NULL,
  PRIMARY KEY (user_id, site_id)
)
ENGINE = INNODB,
AVG_ROW_LENGTH = 1170,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_13C2452DA76ED395` on table `user_site`
--
ALTER TABLE user_site 
  ADD INDEX IDX_13C2452DA76ED395(user_id);

--
-- Create index `IDX_13C2452DF6BD1646` on table `user_site`
--
ALTER TABLE user_site 
  ADD INDEX IDX_13C2452DF6BD1646(site_id);

--
-- Create foreign key
--
ALTER TABLE user_site 
  ADD CONSTRAINT FK_13C2452DA76ED395 FOREIGN KEY (user_id)
    REFERENCES bill_user(id) ON DELETE CASCADE;

--
-- Create foreign key
--
ALTER TABLE user_site 
  ADD CONSTRAINT FK_13C2452DF6BD1646 FOREIGN KEY (site_id)
    REFERENCES bill_site(id) ON DELETE CASCADE;

--
-- Create table `bill_transfert`
--
CREATE TABLE bill_transfert (
  id INT(11) NOT NULL AUTO_INCREMENT,
  user_id INT(11) NOT NULL,
  site_envoie_id INT(11) DEFAULT NULL,
  site_reception_id INT(11) DEFAULT NULL,
  date DATE NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  code VARCHAR(255) NOT NULL,
  is_validee TINYINT(1) NOT NULL,
  mois INT(11) NOT NULL,
  annee INT(11) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 2,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_F2A0A25324564208` on table `bill_transfert`
--
ALTER TABLE bill_transfert 
  ADD INDEX IDX_F2A0A25324564208(site_envoie_id);

--
-- Create index `IDX_F2A0A253A76ED395` on table `bill_transfert`
--
ALTER TABLE bill_transfert 
  ADD INDEX IDX_F2A0A253A76ED395(user_id);

--
-- Create index `IDX_F2A0A253B79E42E1` on table `bill_transfert`
--
ALTER TABLE bill_transfert 
  ADD INDEX IDX_F2A0A253B79E42E1(site_reception_id);

--
-- Create foreign key
--
ALTER TABLE bill_transfert 
  ADD CONSTRAINT FK_F2A0A25324564208 FOREIGN KEY (site_envoie_id)
    REFERENCES bill_site(id);

--
-- Create foreign key
--
ALTER TABLE bill_transfert 
  ADD CONSTRAINT FK_F2A0A253A76ED395 FOREIGN KEY (user_id)
    REFERENCES bill_user(id);

--
-- Create foreign key
--
ALTER TABLE bill_transfert 
  ADD CONSTRAINT FK_F2A0A253B79E42E1 FOREIGN KEY (site_reception_id)
    REFERENCES bill_site(id);

--
-- Create table `bill_sortie`
--
CREATE TABLE bill_sortie (
  id INT(11) NOT NULL AUTO_INCREMENT,
  client_id INT(11) DEFAULT NULL,
  user_id INT(11) NOT NULL,
  site_envoie_id INT(11) NOT NULL,
  date DATE NOT NULL,
  idd_number VARCHAR(255) DEFAULT NULL,
  machine_number VARCHAR(255) DEFAULT NULL,
  is_damage TINYINT(1) NOT NULL,
  code VARCHAR(255) NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  is_sortie_speciale TINYINT(1) NOT NULL,
  observation LONGTEXT DEFAULT NULL,
  mois INT(11) NOT NULL,
  annee INT(11) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 23,
AVG_ROW_LENGTH = 5461,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_2BBE9DE419EB6921` on table `bill_sortie`
--
ALTER TABLE bill_sortie 
  ADD INDEX IDX_2BBE9DE419EB6921(client_id);

--
-- Create index `IDX_2BBE9DE424564208` on table `bill_sortie`
--
ALTER TABLE bill_sortie 
  ADD INDEX IDX_2BBE9DE424564208(site_envoie_id);

--
-- Create index `IDX_2BBE9DE4A76ED395` on table `bill_sortie`
--
ALTER TABLE bill_sortie 
  ADD INDEX IDX_2BBE9DE4A76ED395(user_id);

--
-- Create foreign key
--
ALTER TABLE bill_sortie 
  ADD CONSTRAINT FK_2BBE9DE419EB6921 FOREIGN KEY (client_id)
    REFERENCES bill_client(id);

--
-- Create foreign key
--
ALTER TABLE bill_sortie 
  ADD CONSTRAINT FK_2BBE9DE424564208 FOREIGN KEY (site_envoie_id)
    REFERENCES bill_site(id);

--
-- Create foreign key
--
ALTER TABLE bill_sortie 
  ADD CONSTRAINT FK_2BBE9DE4A76ED395 FOREIGN KEY (user_id)
    REFERENCES bill_user(id);

--
-- Create table `bill_entree`
--
CREATE TABLE bill_entree (
  id INT(11) NOT NULL AUTO_INCREMENT,
  site_reception_id INT(11) NOT NULL,
  fournisseur_id INT(11) DEFAULT NULL,
  user_id INT(11) NOT NULL,
  date DATE NOT NULL,
  numero_bon_fournisseur VARCHAR(255) DEFAULT NULL,
  code VARCHAR(255) NOT NULL,
  is_reuse TINYINT(1) NOT NULL,
  is_entree_speciale TINYINT(1) NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  observation LONGTEXT DEFAULT NULL,
  mois INT(11) NOT NULL,
  annee INT(11) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 28,
AVG_ROW_LENGTH = 1820,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_4E0239B0670C757F` on table `bill_entree`
--
ALTER TABLE bill_entree 
  ADD INDEX IDX_4E0239B0670C757F(fournisseur_id);

--
-- Create index `IDX_4E0239B0A76ED395` on table `bill_entree`
--
ALTER TABLE bill_entree 
  ADD INDEX IDX_4E0239B0A76ED395(user_id);

--
-- Create index `IDX_4E0239B0B79E42E1` on table `bill_entree`
--
ALTER TABLE bill_entree 
  ADD INDEX IDX_4E0239B0B79E42E1(site_reception_id);

--
-- Create index `UNIQ_4E0239B077153098` on table `bill_entree`
--
ALTER TABLE bill_entree 
  ADD UNIQUE INDEX UNIQ_4E0239B077153098(code);

--
-- Create foreign key
--
ALTER TABLE bill_entree 
  ADD CONSTRAINT FK_4E0239B0670C757F FOREIGN KEY (fournisseur_id)
    REFERENCES bill_fournisseur(id);

--
-- Create foreign key
--
ALTER TABLE bill_entree 
  ADD CONSTRAINT FK_4E0239B0A76ED395 FOREIGN KEY (user_id)
    REFERENCES bill_user(id);

--
-- Create foreign key
--
ALTER TABLE bill_entree 
  ADD CONSTRAINT FK_4E0239B0B79E42E1 FOREIGN KEY (site_reception_id)
    REFERENCES bill_site(id);

--
-- Create table `bill_controle`
--
CREATE TABLE bill_controle (
  id INT(11) NOT NULL AUTO_INCREMENT,
  user_id INT(11) NOT NULL,
  site_id INT(11) NOT NULL,
  date DATE NOT NULL,
  code VARCHAR(255) NOT NULL,
  observation_finale LONGTEXT NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  is_validee TINYINT(1) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 3,
AVG_ROW_LENGTH = 16384,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_83C5F7AFA76ED395` on table `bill_controle`
--
ALTER TABLE bill_controle 
  ADD INDEX IDX_83C5F7AFA76ED395(user_id);

--
-- Create index `IDX_83C5F7AFF6BD1646` on table `bill_controle`
--
ALTER TABLE bill_controle 
  ADD INDEX IDX_83C5F7AFF6BD1646(site_id);

--
-- Create foreign key
--
ALTER TABLE bill_controle 
  ADD CONSTRAINT FK_83C5F7AFA76ED395 FOREIGN KEY (user_id)
    REFERENCES bill_user(id);

--
-- Create foreign key
--
ALTER TABLE bill_controle 
  ADD CONSTRAINT FK_83C5F7AFF6BD1646 FOREIGN KEY (site_id)
    REFERENCES bill_site(id);

--
-- Create table `bill_rmensuel`
--
CREATE TABLE bill_rmensuel (
  id INT(11) NOT NULL AUTO_INCREMENT,
  site_id INT(11) NOT NULL,
  mois INT(11) NOT NULL,
  annee INT(11) NOT NULL,
  designation_mois VARCHAR(255) NOT NULL,
  is_cloture TINYINT(1) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 11,
AVG_ROW_LENGTH = 5461,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_E5CFEAFDF6BD1646` on table `bill_rmensuel`
--
ALTER TABLE bill_rmensuel 
  ADD INDEX IDX_E5CFEAFDF6BD1646(site_id);

--
-- Create foreign key
--
ALTER TABLE bill_rmensuel 
  ADD CONSTRAINT FK_E5CFEAFDF6BD1646 FOREIGN KEY (site_id)
    REFERENCES bill_site(id);

--
-- Create table `bill_ranuelsite`
--
CREATE TABLE bill_ranuelsite (
  id INT(11) NOT NULL AUTO_INCREMENT,
  site_id INT(11) NOT NULL,
  annee INT(11) NOT NULL,
  is_cloture TINYINT(1) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_C3DEB328F6BD1646` on table `bill_ranuelsite`
--
ALTER TABLE bill_ranuelsite 
  ADD INDEX IDX_C3DEB328F6BD1646(site_id);

--
-- Create foreign key
--
ALTER TABLE bill_ranuelsite 
  ADD CONSTRAINT FK_C3DEB328F6BD1646 FOREIGN KEY (site_id)
    REFERENCES bill_site(id);

--
-- Create table `bill_produit`
--
CREATE TABLE bill_produit (
  id INT(11) NOT NULL AUTO_INCREMENT,
  pn VARCHAR(255) DEFAULT NULL,
  description VARCHAR(255) NOT NULL,
  quantite DOUBLE NOT NULL,
  prix_vente DOUBLE NOT NULL,
  stock_alerte DOUBLE NOT NULL,
  is_danger_stock TINYINT(1) NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 3,
AVG_ROW_LENGTH = 8192,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create table `bill_produit_site`
--
CREATE TABLE bill_produit_site (
  id INT(11) NOT NULL AUTO_INCREMENT,
  produit_id INT(11) NOT NULL,
  site_id INT(11) NOT NULL,
  quantite DOUBLE NOT NULL,
  is_produit_depot TINYINT(1) NOT NULL,
  stock_alerte DOUBLE NOT NULL,
  is_danger_stock TINYINT(1) NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 5,
AVG_ROW_LENGTH = 3276,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_3E1B170EF347EFB` on table `bill_produit_site`
--
ALTER TABLE bill_produit_site 
  ADD INDEX IDX_3E1B170EF347EFB(produit_id);

--
-- Create index `IDX_3E1B170EF6BD1646` on table `bill_produit_site`
--
ALTER TABLE bill_produit_site 
  ADD INDEX IDX_3E1B170EF6BD1646(site_id);

--
-- Create foreign key
--
ALTER TABLE bill_produit_site 
  ADD CONSTRAINT FK_3E1B170EF347EFB FOREIGN KEY (produit_id)
    REFERENCES bill_produit(id);

--
-- Create foreign key
--
ALTER TABLE bill_produit_site 
  ADD CONSTRAINT FK_3E1B170EF6BD1646 FOREIGN KEY (site_id)
    REFERENCES bill_site(id);

--
-- Create table `bill_rannuel_site`
--
CREATE TABLE bill_rannuel_site (
  id INT(11) NOT NULL AUTO_INCREMENT,
  pn_id INT(11) NOT NULL,
  r_mensuel_site_id INT(11) NOT NULL,
  quantite_initiale DOUBLE NOT NULL,
  quantite_entree DOUBLE NOT NULL,
  quantite_entree_speciale DOUBLE NOT NULL,
  quantite_entree_transfert DOUBLE NOT NULL,
  quantite_entree_reuse DOUBLE NOT NULL,
  sortie_client DOUBLE NOT NULL,
  sortie_speciale VARCHAR(255) NOT NULL,
  quantite_sortie_transfert DOUBLE NOT NULL,
  quantite_finale DOUBLE NOT NULL,
  sortie_damage DOUBLE NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_B7FD20797D098BC0` on table `bill_rannuel_site`
--
ALTER TABLE bill_rannuel_site 
  ADD INDEX IDX_B7FD20797D098BC0(pn_id);

--
-- Create index `IDX_B7FD2079CB603871` on table `bill_rannuel_site`
--
ALTER TABLE bill_rannuel_site 
  ADD INDEX IDX_B7FD2079CB603871(r_mensuel_site_id);

--
-- Create foreign key
--
ALTER TABLE bill_rannuel_site 
  ADD CONSTRAINT FK_B7FD20797D098BC0 FOREIGN KEY (pn_id)
    REFERENCES bill_produit_site(id);

--
-- Create foreign key
--
ALTER TABLE bill_rannuel_site 
  ADD CONSTRAINT FK_B7FD2079CB603871 FOREIGN KEY (r_mensuel_site_id)
    REFERENCES bill_rmensuel(id);

--
-- Create table `bill_ligne_transfert`
--
CREATE TABLE bill_ligne_transfert (
  id INT(11) NOT NULL AUTO_INCREMENT,
  produit_site_id INT(11) NOT NULL,
  transfert_id INT(11) NOT NULL,
  quantite DOUBLE NOT NULL,
  observation VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 4,
AVG_ROW_LENGTH = 4096,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_2D49B7230469D3F` on table `bill_ligne_transfert`
--
ALTER TABLE bill_ligne_transfert 
  ADD INDEX IDX_2D49B7230469D3F(produit_site_id);

--
-- Create index `IDX_2D49B723C9C4BAD` on table `bill_ligne_transfert`
--
ALTER TABLE bill_ligne_transfert 
  ADD INDEX IDX_2D49B723C9C4BAD(transfert_id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_transfert 
  ADD CONSTRAINT FK_2D49B7230469D3F FOREIGN KEY (produit_site_id)
    REFERENCES bill_produit_site(id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_transfert 
  ADD CONSTRAINT FK_2D49B723C9C4BAD FOREIGN KEY (transfert_id)
    REFERENCES bill_transfert(id);

--
-- Create table `bill_ligne_stock_controle`
--
CREATE TABLE bill_ligne_stock_controle (
  id INT(11) NOT NULL AUTO_INCREMENT,
  site_id INT(11) NOT NULL,
  produit_site_id INT(11) NOT NULL,
  ligne_transfert_id INT(11) DEFAULT NULL,
  ligne_stock_controle_id INT(11) DEFAULT NULL,
  date DATE NOT NULL,
  machine_number VARCHAR(255) DEFAULT NULL,
  delivery_note_number VARCHAR(255) NOT NULL,
  quantity_received DOUBLE DEFAULT NULL,
  supplier VARCHAR(255) NOT NULL,
  customer VARCHAR(255) NOT NULL,
  ijd_number VARCHAR(255) NOT NULL,
  quantity_sold DOUBLE DEFAULT NULL,
  total_balance DOUBLE NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  numero_dnn INT(11) NOT NULL,
  numero_mouvement INT(11) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 59,
AVG_ROW_LENGTH = 862,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_DEF547E530469D3F` on table `bill_ligne_stock_controle`
--
ALTER TABLE bill_ligne_stock_controle 
  ADD INDEX IDX_DEF547E530469D3F(produit_site_id);

--
-- Create index `IDX_DEF547E584420B62` on table `bill_ligne_stock_controle`
--
ALTER TABLE bill_ligne_stock_controle 
  ADD INDEX IDX_DEF547E584420B62(ligne_transfert_id);

--
-- Create index `IDX_DEF547E5F6BD1646` on table `bill_ligne_stock_controle`
--
ALTER TABLE bill_ligne_stock_controle 
  ADD INDEX IDX_DEF547E5F6BD1646(site_id);

--
-- Create index `UNIQ_DEF547E5F11D9B2F` on table `bill_ligne_stock_controle`
--
ALTER TABLE bill_ligne_stock_controle 
  ADD UNIQUE INDEX UNIQ_DEF547E5F11D9B2F(ligne_stock_controle_id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_stock_controle 
  ADD CONSTRAINT FK_DEF547E530469D3F FOREIGN KEY (produit_site_id)
    REFERENCES bill_produit_site(id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_stock_controle 
  ADD CONSTRAINT FK_DEF547E584420B62 FOREIGN KEY (ligne_transfert_id)
    REFERENCES bill_ligne_transfert(id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_stock_controle 
  ADD CONSTRAINT FK_DEF547E5F11D9B2F FOREIGN KEY (ligne_stock_controle_id)
    REFERENCES bill_ligne_stock_controle(id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_stock_controle 
  ADD CONSTRAINT FK_DEF547E5F6BD1646 FOREIGN KEY (site_id)
    REFERENCES bill_site(id);

--
-- Create table `bill_ligne_sortie`
--
CREATE TABLE bill_ligne_sortie (
  id INT(11) NOT NULL AUTO_INCREMENT,
  sortie_id INT(11) NOT NULL,
  produit_site_id INT(11) NOT NULL,
  ligne_stock_controle_id INT(11) NOT NULL,
  quantite DOUBLE NOT NULL,
  observation VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 23,
AVG_ROW_LENGTH = 4096,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_64252CB130469D3F` on table `bill_ligne_sortie`
--
ALTER TABLE bill_ligne_sortie 
  ADD INDEX IDX_64252CB130469D3F(produit_site_id);

--
-- Create index `IDX_64252CB1CC72D953` on table `bill_ligne_sortie`
--
ALTER TABLE bill_ligne_sortie 
  ADD INDEX IDX_64252CB1CC72D953(sortie_id);

--
-- Create index `UNIQ_64252CB1F11D9B2F` on table `bill_ligne_sortie`
--
ALTER TABLE bill_ligne_sortie 
  ADD UNIQUE INDEX UNIQ_64252CB1F11D9B2F(ligne_stock_controle_id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_sortie 
  ADD CONSTRAINT FK_64252CB130469D3F FOREIGN KEY (produit_site_id)
    REFERENCES bill_produit_site(id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_sortie 
  ADD CONSTRAINT FK_64252CB1CC72D953 FOREIGN KEY (sortie_id)
    REFERENCES bill_sortie(id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_sortie 
  ADD CONSTRAINT FK_64252CB1F11D9B2F FOREIGN KEY (ligne_stock_controle_id)
    REFERENCES bill_ligne_stock_controle(id);

--
-- Create table `bill_ligne_entree`
--
CREATE TABLE bill_ligne_entree (
  id INT(11) NOT NULL AUTO_INCREMENT,
  produit_site_id INT(11) NOT NULL,
  entree_id INT(11) NOT NULL,
  produit_id INT(11) NOT NULL,
  ligne_stock_controle_id INT(11) NOT NULL,
  quantite DOUBLE NOT NULL,
  observation VARCHAR(255) DEFAULT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 30,
AVG_ROW_LENGTH = 1820,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_19988E530469D3F` on table `bill_ligne_entree`
--
ALTER TABLE bill_ligne_entree 
  ADD INDEX IDX_19988E530469D3F(produit_site_id);

--
-- Create index `IDX_19988E5AF7BD910` on table `bill_ligne_entree`
--
ALTER TABLE bill_ligne_entree 
  ADD INDEX IDX_19988E5AF7BD910(entree_id);

--
-- Create index `IDX_19988E5F347EFB` on table `bill_ligne_entree`
--
ALTER TABLE bill_ligne_entree 
  ADD INDEX IDX_19988E5F347EFB(produit_id);

--
-- Create index `UNIQ_19988E5F11D9B2F` on table `bill_ligne_entree`
--
ALTER TABLE bill_ligne_entree 
  ADD UNIQUE INDEX UNIQ_19988E5F11D9B2F(ligne_stock_controle_id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_entree 
  ADD CONSTRAINT FK_19988E530469D3F FOREIGN KEY (produit_site_id)
    REFERENCES bill_produit_site(id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_entree 
  ADD CONSTRAINT FK_19988E5AF7BD910 FOREIGN KEY (entree_id)
    REFERENCES bill_entree(id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_entree 
  ADD CONSTRAINT FK_19988E5F11D9B2F FOREIGN KEY (ligne_stock_controle_id)
    REFERENCES bill_ligne_stock_controle(id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_entree 
  ADD CONSTRAINT FK_19988E5F347EFB FOREIGN KEY (produit_id)
    REFERENCES bill_produit(id);

--
-- Create table `bill_ligne_controle`
--
CREATE TABLE bill_ligne_controle (
  id INT(11) NOT NULL AUTO_INCREMENT,
  controle_id INT(11) NOT NULL,
  produit_site_id INT(11) NOT NULL,
  ligne_stock_controle_id INT(11) DEFAULT NULL,
  quantite_physique DOUBLE NOT NULL,
  observation VARCHAR(255) DEFAULT NULL,
  quantite_billcard DOUBLE NOT NULL,
  manquant DOUBLE NOT NULL,
  surplus DOUBLE NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 6,
AVG_ROW_LENGTH = 4096,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_F86F923F30469D3F` on table `bill_ligne_controle`
--
ALTER TABLE bill_ligne_controle 
  ADD INDEX IDX_F86F923F30469D3F(produit_site_id);

--
-- Create index `IDX_F86F923F758926A6` on table `bill_ligne_controle`
--
ALTER TABLE bill_ligne_controle 
  ADD INDEX IDX_F86F923F758926A6(controle_id);

--
-- Create index `IDX_F86F923FF11D9B2F` on table `bill_ligne_controle`
--
ALTER TABLE bill_ligne_controle 
  ADD INDEX IDX_F86F923FF11D9B2F(ligne_stock_controle_id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_controle 
  ADD CONSTRAINT FK_F86F923F30469D3F FOREIGN KEY (produit_site_id)
    REFERENCES bill_produit_site(id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_controle 
  ADD CONSTRAINT FK_F86F923F758926A6 FOREIGN KEY (controle_id)
    REFERENCES bill_controle(id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_controle 
  ADD CONSTRAINT FK_F86F923FF11D9B2F FOREIGN KEY (ligne_stock_controle_id)
    REFERENCES bill_ligne_stock_controle(id);

--
-- Create table `bill_ligne_billcard`
--
CREATE TABLE bill_ligne_billcard (
  id INT(11) NOT NULL AUTO_INCREMENT,
  site_id INT(11) NOT NULL,
  produit_site_id INT(11) NOT NULL,
  ligne_transfert_id INT(11) DEFAULT NULL,
  date DATE NOT NULL,
  machine_number VARCHAR(255) NOT NULL,
  delivery_note_number VARCHAR(255) NOT NULL,
  quantity_received DOUBLE NOT NULL,
  supplier VARCHAR(255) NOT NULL,
  customer VARCHAR(255) NOT NULL,
  id_number VARCHAR(255) NOT NULL,
  quantity_sold DOUBLE NOT NULL,
  total_balance DOUBLE NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 2,
AVG_ROW_LENGTH = 8192,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_B30C86A30469D3F` on table `bill_ligne_billcard`
--
ALTER TABLE bill_ligne_billcard 
  ADD INDEX IDX_B30C86A30469D3F(produit_site_id);

--
-- Create index `IDX_B30C86A84420B62` on table `bill_ligne_billcard`
--
ALTER TABLE bill_ligne_billcard 
  ADD INDEX IDX_B30C86A84420B62(ligne_transfert_id);

--
-- Create index `IDX_B30C86AF6BD1646` on table `bill_ligne_billcard`
--
ALTER TABLE bill_ligne_billcard 
  ADD INDEX IDX_B30C86AF6BD1646(site_id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_billcard 
  ADD CONSTRAINT FK_B30C86A30469D3F FOREIGN KEY (produit_site_id)
    REFERENCES bill_produit_site(id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_billcard 
  ADD CONSTRAINT FK_B30C86A84420B62 FOREIGN KEY (ligne_transfert_id)
    REFERENCES bill_ligne_transfert(id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_billcard 
  ADD CONSTRAINT FK_B30C86AF6BD1646 FOREIGN KEY (site_id)
    REFERENCES bill_site(id);

--
-- Create table `bill_ligne_rmensuel`
--
CREATE TABLE bill_ligne_rmensuel (
  id INT(11) NOT NULL AUTO_INCREMENT,
  pn_id INT(11) NOT NULL,
  r_mensuel_site_id INT(11) NOT NULL,
  quantite_initiale DOUBLE NOT NULL,
  quantite_entree DOUBLE NOT NULL,
  quantite_entree_speciale DOUBLE NOT NULL,
  quantite_entree_transfert DOUBLE NOT NULL,
  quantite_entree_reuse DOUBLE NOT NULL,
  sortie_client DOUBLE NOT NULL,
  sortie_speciale VARCHAR(255) NOT NULL,
  quantite_sortie_transfert DOUBLE NOT NULL,
  quantite_finale DOUBLE NOT NULL,
  sortie_damage DOUBLE NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 12,
AVG_ROW_LENGTH = 2730,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_9E658F6D7D098BC0` on table `bill_ligne_rmensuel`
--
ALTER TABLE bill_ligne_rmensuel 
  ADD INDEX IDX_9E658F6D7D098BC0(pn_id);

--
-- Create index `IDX_9E658F6DCB603871` on table `bill_ligne_rmensuel`
--
ALTER TABLE bill_ligne_rmensuel 
  ADD INDEX IDX_9E658F6DCB603871(r_mensuel_site_id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_rmensuel 
  ADD CONSTRAINT FK_9E658F6D7D098BC0 FOREIGN KEY (pn_id)
    REFERENCES bill_produit_site(id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_rmensuel 
  ADD CONSTRAINT FK_9E658F6DCB603871 FOREIGN KEY (r_mensuel_site_id)
    REFERENCES bill_rmensuel(id);

--
-- Create table `bill_ligne_rannuel_site`
--
CREATE TABLE bill_ligne_rannuel_site (
  id INT(11) NOT NULL AUTO_INCREMENT,
  pn_id INT(11) NOT NULL,
  rapport_id INT(11) NOT NULL,
  quantite_initiale DOUBLE NOT NULL,
  quantite_entree DOUBLE NOT NULL,
  quantite_entree_speciale DOUBLE NOT NULL,
  quantite_entree_transfert DOUBLE NOT NULL,
  quantite_entree_reuse DOUBLE NOT NULL,
  sortie_client DOUBLE NOT NULL,
  sortie_speciale VARCHAR(255) NOT NULL,
  quantite_sortie_transfert DOUBLE NOT NULL,
  quantite_finale DOUBLE NOT NULL,
  sortie_damage DOUBLE NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_D79E34C11DFBCC46` on table `bill_ligne_rannuel_site`
--
ALTER TABLE bill_ligne_rannuel_site 
  ADD INDEX IDX_D79E34C11DFBCC46(rapport_id);

--
-- Create index `IDX_D79E34C17D098BC0` on table `bill_ligne_rannuel_site`
--
ALTER TABLE bill_ligne_rannuel_site 
  ADD INDEX IDX_D79E34C17D098BC0(pn_id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_rannuel_site 
  ADD CONSTRAINT FK_D79E34C11DFBCC46 FOREIGN KEY (rapport_id)
    REFERENCES bill_ranuelsite(id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_rannuel_site 
  ADD CONSTRAINT FK_D79E34C17D098BC0 FOREIGN KEY (pn_id)
    REFERENCES bill_produit_site(id);

--
-- Create table `bill_ranuelgeneral`
--
CREATE TABLE bill_ranuelgeneral (
  id INT(11) NOT NULL AUTO_INCREMENT,
  annee INT(11) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create table `bill_ligne_ranuelgeneral`
--
CREATE TABLE bill_ligne_ranuelgeneral (
  id INT(11) NOT NULL AUTO_INCREMENT,
  pn_id INT(11) NOT NULL,
  rapport_id INT(11) NOT NULL,
  qte_initiale DOUBLE NOT NULL,
  qte_entree_fournisseur DOUBLE NOT NULL,
  qte_entree_reuse DOUBLE NOT NULL,
  qte_sortie_client DOUBLE NOT NULL,
  qte_sortie_damage DOUBLE NOT NULL,
  qte_solde DOUBLE NOT NULL,
  qte_entree_speciale DOUBLE NOT NULL,
  qte_sortie_speciale DOUBLE NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_3D2A8A51DFBCC46` on table `bill_ligne_ranuelgeneral`
--
ALTER TABLE bill_ligne_ranuelgeneral 
  ADD INDEX IDX_3D2A8A51DFBCC46(rapport_id);

--
-- Create index `IDX_3D2A8A57D098BC0` on table `bill_ligne_ranuelgeneral`
--
ALTER TABLE bill_ligne_ranuelgeneral 
  ADD INDEX IDX_3D2A8A57D098BC0(pn_id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_ranuelgeneral 
  ADD CONSTRAINT FK_3D2A8A51DFBCC46 FOREIGN KEY (rapport_id)
    REFERENCES bill_ranuelgeneral(id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_ranuelgeneral 
  ADD CONSTRAINT FK_3D2A8A57D098BC0 FOREIGN KEY (pn_id)
    REFERENCES bill_produit(id);

-- 
-- Restore previous SQL mode
-- 
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

-- 
-- Enable foreign keys
-- 
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;