﻿--
-- Script was generated by Devart dbForge Studio 2019 for MySQL, Version 8.2.23.0
-- Product home page: http://www.devart.com/dbforge/mysql/studio
-- Script date 1/27/2022 10:29:42 AM
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
-- Drop table `bill_ligne_billcard`
--
DROP TABLE IF EXISTS bill_ligne_billcard;

--
-- Drop table `bill_ligne_transfert`
--
DROP TABLE IF EXISTS bill_ligne_transfert;

--
-- Drop table `bill_produit_site`
--
DROP TABLE IF EXISTS bill_produit_site;

--
-- Drop table `bill_produit`
--
DROP TABLE IF EXISTS bill_produit;

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
AUTO_INCREMENT = 4,
AVG_ROW_LENGTH = 4096,
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
AUTO_INCREMENT = 5,
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
AUTO_INCREMENT = 4,
AVG_ROW_LENGTH = 5461,
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
AVG_ROW_LENGTH = 1489,
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
  PRIMARY KEY (id)
)
ENGINE = INNODB,
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
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 3,
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
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 6,
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
AUTO_INCREMENT = 2,
AVG_ROW_LENGTH = 8192,
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
AUTO_INCREMENT = 25,
AVG_ROW_LENGTH = 1820,
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
-- Create table `bill_ligne_sortie`
--
CREATE TABLE bill_ligne_sortie (
  id INT(11) NOT NULL AUTO_INCREMENT,
  sortie_id INT(11) NOT NULL,
  produit_site_id INT(11) NOT NULL,
  ligne_billcard_id INT(11) NOT NULL,
  quantite DOUBLE NOT NULL,
  observation VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 5,
AVG_ROW_LENGTH = 8192,
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
-- Create index `UNIQ_64252CB1C79BF382` on table `bill_ligne_sortie`
--
ALTER TABLE bill_ligne_sortie 
  ADD UNIQUE INDEX UNIQ_64252CB1C79BF382(ligne_billcard_id);

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
  ADD CONSTRAINT FK_64252CB1C79BF382 FOREIGN KEY (ligne_billcard_id)
    REFERENCES bill_ligne_billcard(id);

--
-- Create foreign key
--
ALTER TABLE bill_ligne_sortie 
  ADD CONSTRAINT FK_64252CB1CC72D953 FOREIGN KEY (sortie_id)
    REFERENCES bill_sortie(id);

--
-- Create table `bill_ligne_entree`
--
CREATE TABLE bill_ligne_entree (
  id INT(11) NOT NULL AUTO_INCREMENT,
  produit_site_id INT(11) NOT NULL,
  entree_id INT(11) NOT NULL,
  ligne_billcard_id INT(11) NOT NULL,
  produit_id INT(11) NOT NULL,
  quantite DOUBLE NOT NULL,
  observation VARCHAR(255) DEFAULT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 16,
AVG_ROW_LENGTH = 5461,
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
-- Create index `UNIQ_19988E5C79BF382` on table `bill_ligne_entree`
--
ALTER TABLE bill_ligne_entree 
  ADD UNIQUE INDEX UNIQ_19988E5C79BF382(ligne_billcard_id);

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
  ADD CONSTRAINT FK_19988E5C79BF382 FOREIGN KEY (ligne_billcard_id)
    REFERENCES bill_ligne_billcard(id);

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
  ligne_billcard_id INT(11) DEFAULT NULL,
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
-- Create index `UNIQ_F86F923FC79BF382` on table `bill_ligne_controle`
--
ALTER TABLE bill_ligne_controle 
  ADD UNIQUE INDEX UNIQ_F86F923FC79BF382(ligne_billcard_id);

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
  ADD CONSTRAINT FK_F86F923FC79BF382 FOREIGN KEY (ligne_billcard_id)
    REFERENCES bill_ligne_billcard(id);

-- 
-- Restore previous SQL mode
-- 
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

-- 
-- Enable foreign keys
-- 
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;