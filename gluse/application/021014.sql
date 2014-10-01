/*
SQLyog Ultimate v8.8 
MySQL - 5.6.12-log : Database - gluse_demo
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`gluse_demo` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `gluse_demo`;

/*Table structure for table `config` */

DROP TABLE IF EXISTS `config`;

CREATE TABLE `config` (
  `conf_id` int(11) NOT NULL AUTO_INCREMENT,
  `conf_name` varchar(200) DEFAULT NULL,
  `conf_value` varchar(100) DEFAULT NULL,
  `conf_tipe` varchar(3) DEFAULT '1' COMMENT '1:text, 2:cbox',
  PRIMARY KEY (`conf_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `config` */

insert  into `config`(`conf_id`,`conf_name`,`conf_value`,`conf_tipe`) values (1,'periode_tahun_prediksi','6',NULL),(2,'item_per_page','50',NULL),(3,'semester_aktif','ganjil',NULL),(4,'min_persen_kelas','75',NULL);

/*Table structure for table `dosen` */

DROP TABLE IF EXISTS `dosen`;

CREATE TABLE `dosen` (
  `dsn_id` int(11) NOT NULL AUTO_INCREMENT,
  `dsn_nip` varchar(20) DEFAULT NULL,
  `dsn_nama` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`dsn_id`),
  UNIQUE KEY `dsn_nama_key` (`dsn_nama`)
) ENGINE=InnoDB AUTO_INCREMENT=337 DEFAULT CHARSET=latin1;

/*Data for the table `dosen` */

insert  into `dosen`(`dsn_id`,`dsn_nip`,`dsn_nama`) values (1,NULL,'ARIDA OETAMI, dr., M.Kes.'),(2,NULL,'GUNTUR MARUTO, Drs., S.U., Dr.'),(3,NULL,'DANANG LELONO, S.Si., M.T.\r\n'),(4,NULL,'CHAIRIL ANWAR, Dr.\r\n'),(5,NULL,'MOH. ALI JOKO WASONO, M.S., Dr.\r\n'),(6,NULL,'MUDASIR, Drs., M.Eng., Ph.D., Prof.\r\n'),(7,NULL,'SUTARNO, M.Si., Dr.\r\n'),(8,NULL,'KAMSUL ABRAHA, Dr., Prof.\r\n'),(9,NULL,'MOH. ALI JOKO WASONO, M.S., Dr.,\r\n'),(10,NULL,'IMAM SOLEKHUDIN, S.Si., M.Si., Ph.D.\r\n'),(11,NULL,'MASIRAN, M.Si.\r\n'),(12,NULL,'AINA MUSDHOLIFAH, S.Kom.,M.Sc.,Dr\r\n'),(13,NULL,'ARI SUPARWANTO, M.Si., Dr.rer.nat.,\r\n'),(14,NULL,'AL. SUTJIJANA, Drs., M.Sc.\r\n'),(15,NULL,'SUTOPO, S.Si., M.Si.\r\n'),(16,NULL,'INDAH EMILIA WIJAYANTI, M.Si., Dr.rer.nat.\r\n'),(17,NULL,'ROTO, Drs., M.Eng., Ph.D.,\r\n'),(18,NULL,'AGUS KUNCAKA, Dr., DEA.\r\n'),(19,NULL,'DWI SISWANTO, Drs., M.Eng., Ph.D.\r\n'),(20,NULL,'ZULAELA, Drs., Dipl.Med.Stats., M.Si.'),(21,NULL,'HERNI UTAMI, S.Si., M.Si.\r\n'),(22,NULL,'BAMBANG SOEDIJONO, Dr., Prof.\r\n'),(23,NULL,'SRI HARYATMI, M.Sc., Dr., Prof.\r\n'),(24,NULL,'ADHITYA RONNIE EFFENDIE, M.Si., M.Sc., Dr.\r\n'),(25,NULL,'SRI PANGESTI, Dra., S.U.\r\n'),(26,NULL,'Imam Prasetyo, Ir, M.Eng.\r\n'),(27,NULL,'BAMBANG MURDAKA EKA JATI, Drs., M.S.\r\n'),(28,NULL,'RIO TRI WIBOWO, S.S.\r\n'),(29,NULL,'SARI LESTARI, Dra., M.A.\r\n'),(30,NULL,'NIKEN ANGRAENI, S.S.\r\n'),(31,NULL,'IRWAN ENDRAYANTO A., S.Si., M.Sc., Dr.\r\n'),(32,NULL,'KIRBANI SRI BROTOPUSPITO, Dr., Prof.\r\n'),(33,NULL,'JAZI EKO ISTYANTO, M.Sc., Ph.D., Prof.\r\n'),(34,NULL,'SRI HARTATI, Dra., M.Sc., Ph.D.\r\n'),(35,NULL,'YUSRIL YUSUF, S.Si., M.Eng., Dr.Eng.,\r\n'),(36,NULL,'ARIEF HERMANTO, S.U., M.Sc., Dr.\r\n'),(37,NULL,'RATNA UDAYA WIDODO, Dra., MLS.\r\n'),(38,NULL,'INDRIANA KARTINI, S.Si., M.Si., Ph.D.\r\n'),(39,NULL,'JUMINA, Drs., Ph.D., Prof.\r\n'),(40,NULL,'EDI WINARKO, Drs., M.Sc., Ph.D.\r\n'),(41,NULL,'ILONA USUMAN, S.Si., M.Kom.\r\n'),(42,NULL,'KHABIB MUSTOFA, S.Si., M.Kom., Dr.tech.\r\n'),(43,NULL,'FAIZAH, S.Kom., M.Sc.\r\n'),(44,NULL,'RADEN SUMIHARTO, S.Si., M.Kom.\r\n'),(45,NULL,'SUHARTO, Dr.\r\n'),(46,NULL,'TRI JOKO RAHARJO, S.Si., M.Si., Ph.D.,\r\n'),(47,NULL,'WINARTO HARYADI, M.Si., Dr.,\r\n'),(48,NULL,'SABIRIN MATSJEH, Dr., Prof.\r\n'),(49,NULL,'DENI PRANOWO, S.Si., M.Si.,\r\n'),(50,NULL,'ENDANG ASTUTI, Dra., M.Si.\r\n'),(51,NULL,'Istriyati, Dr., M.S.\r\n'),(52,NULL,'INDRIANA KARTINI, S.Si., M.Si., Ph.D.,\r\n'),(53,NULL,'ROTO, Drs., M.Eng., Ph.D.\r\n'),(54,NULL,'USI SUKORINI, dr., Sp.PK\r\n'),(55,NULL,'ABDUL KAFI, dr.\r\n'),(56,NULL,'ESTI MAHARANI, dr.\r\n'),(57,NULL,'DANARDONO, Drs., MPH., Ph.D.\r\n'),(59,NULL,'WINARTO HARYADI, M.Si., Dr.\r\n'),(60,NULL,'MEDI, Drs., M.Kom.\r\n'),(61,NULL,'NARSITO, Dr., Prof.\r\n'),(62,NULL,'SRI JUARI SANTOSA, Drs., M.Eng., Ph.D., Prof.\r\n'),(63,NULL,'ARINOBELTA KABAN, S.E.\r\n'),(64,NULL,'NUR ROKHMAN, S.Si., M.Kom.\r\n'),(65,NULL,'DWI SATYA PALUPI, S.Si., M.Si.,\r\n'),(66,NULL,'EKO TRI SULISTYANI, Dra., M.Sc.\r\n'),(68,NULL,'ALROSYID, S.Si.\r\n'),(69,NULL,'AGUS HARJOKO, Drs., M.Sc., Ph.D.\r\n'),(70,NULL,'PANGGIH BASUKI, Drs., M.Si.\r\n'),(72,NULL,'AGFIANTO EKO PUTRA, M.Si., Dr.\r\n'),(73,NULL,'YOHANES SUYANTO, Drs., M.I.Kom.\r\n'),(75,NULL,'FAHRUDIN NUGROHO, S.Si., M.Si.,\r\n'),(76,NULL,'WEGA TRISUNARYANTI, M.S., Ph.D., Prof.\r\n'),(77,NULL,'AKHMAD SYOUFIAN, S.Si., Ph.D.,\r\n'),(78,NULL,'SRI SUDIONO, S.Si., M.Si.\r\n'),(80,NULL,'BAMBANG MURDAKA EKA JATI, Drs., M.S.,\r\n'),(81,NULL,'SUPARWOTO, Drs., M.Si.\r\n'),(82,NULL,'ABDUL ROUF, Drs., M.I.Kom.\r\n'),(83,NULL,'WIDODO PRIJODIPRODJO, M.Sc.\r\n'),(86,NULL,'NAWI NG., dr., MPH\r\n'),(87,NULL,'ENDANG SUPARNIATI, M.Kes., dr.,\r\n'),(88,NULL,'DANANG YULISAKSONO, S.T., M.T.\r\n'),(89,NULL,'ABDURAKHMAN, S.Si., M.Si., Dr.\r\n'),(90,NULL,'GUNARDI, M.Si., Dr.\r\n'),(91,NULL,'GUNTUR MARUTO, Drs., S.U., Dr.\r\n'),(92,NULL,'GP. DALIJO, Drs., Dipl.Comp.\r\n'),(93,NULL,'YOSEF ROBERTUS UTOMO, Drs., S.U.,\r\n'),(94,NULL,'KARYONO, S.U., Dr., Prof.\r\n'),(95,NULL,'MITRAYANA, S.Si., M.Si., Dr.,\r\n'),(98,NULL,'KUSMINARTO, Dr., Prof.,\r\n'),(99,NULL,'SUNARTA, Drs., M.S.\r\n'),(100,NULL,'CHOTIMAH, Dra., M.S.\r\n'),(101,NULL,'WAGINI R., Drs., M.S.\r\n'),(102,NULL,'EKO SULISTYA, Drs., M.Si.\r\n'),(103,NULL,'IMAM SUYANTO, Drs., M.Si.,\r\n'),(104,NULL,'WAHYUDI, M.S., Dr.\r\n'),(106,NULL,'KUSMINARTO, Dr., Prof.\r\n'),(107,NULL,'KUWAT TRIYANA, M.Si., Dr. Eng.,\r\n'),(110,NULL,'AGUNG BAMBANG SETIO UTOMO, Dr., Prof.\r\n'),(111,NULL,'GEDE BAYU SUPARTA, Drs., M.S., Ph.D.,\r\n'),(113,NULL,'SUDARMAJI, S.Si., M.Si.\r\n'),(114,NULL,'ARI SETIAWAN, Drs., M.Si., Dr.Ing.\r\n'),(116,NULL,'KUWAT TRIYANA, M.Si., Dr. Eng.\r\n'),(117,NULL,'SUMARDI, M.Si., Dr.\r\n'),(118,NULL,'NURYONO, Drs., M.S., Dr.rer.nat., Prof.\r\n'),(119,NULL,'SALAHUDDIN HUSEIN, S.T., M.Sc., Ph.D.\r\n'),(120,NULL,'SARJU WINARDI, S.T.,M.T\r\n'),(121,NULL,'IGN. SUDARNO, Ir., M.T.\r\n'),(124,NULL,'MOCHAMMAD TARI, Drs., M.Si.\r\n'),(125,NULL,'WALUYO, Drs., M.Sc., Ph.D.\r\n'),(126,NULL,'JANOE HENDARTO, Drs., M.I.Kom.\r\n'),(127,NULL,'R. SUGANDHI, dr., SF\r\n'),(128,NULL,'ADI HERU SUTOMO, dr., M.Sc., Prof.\r\n'),(129,NULL,'NURYATI, MPH\r\n'),(130,NULL,'SAVITRI CITRA BUDI, MPH\r\n'),(132,NULL,'TRI KUNTORO PRIYAMBODO, Dr., M.Sc.\r\n'),(133,NULL,'TUTIK DWI WAHYUNINGSIH, Dra., M.Si., Ph.D.,\r\n'),(135,NULL,'ANIFUDDIN AZIS, S.Si., M.Kom.\r\n'),(136,NULL,'I GEDE MUJIYATNA, S.Kom., M.Kom.\r\n'),(137,NULL,'MOH. EDI WIBOWO, S.Kom., M.Kom.\r\n'),(139,NULL,'SALMAH, M.Si., Dr.\r\n'),(140,NULL,'ARI SUPARWANTO, M.Si., Dr.rer.nat.\r\n'),(142,NULL,'YUSUF, Drs., M.A. Math.\r\n'),(143,NULL,'SUPAMA, M.Si., Dr., Prof.\r\n\r\n'),(144,NULL,'CHRISTIANA RINI INDRATI, M.Si., Dr.\r\n'),(145,NULL,'ATOK ZULIJANTO, S.Si., M.Si., Ph.D.\r\n'),(147,NULL,'UMI MAHNUNA HANUNG, S.Si., M.Si.\r\n'),(150,NULL,'SARDJONO, Drs., S.U.\r\n'),(151,NULL,'SUPAMA, M.Si., Dr., Prof.\r\n'),(152,NULL,'SOLIKHATUN, S.Si., M.Si.\r\n'),(153,NULL,'AHMAD ASHARI, Drs., M.I.Kom., Dr.tech.\r\n'),(154,NULL,'SRI MULYANA, Drs., M.Kom.\r\n'),(155,NULL,'ARI DWI NUGRAHENI, S.Si.,\r\n'),(157,NULL,'RIA ARMUNANTO, S.Si., M.Si., Dr.rer.nat.\r\n'),(159,NULL,'MUDASIR, Drs., M.Eng., Ph.D., Prof.,\r\n'),(160,NULL,'WIRANTO, Drs., M.Kes.\r\n'),(165,NULL,'GEDE BAYU SUPARTA, Drs., M.S., Ph.D.\r\n'),(166,NULL,'MARDOTO, KOL. SUS.\r\n'),(167,NULL,'SUYOKO, KOL., ADM.\r\n'),(168,NULL,'HOLIMIN, LETKOL SUS., Dr. Drs., M.Si.\r\n'),(171,NULL,'SISMANTO, M.Si., Dr., Prof.\r\n'),(172,NULL,'ABIDARIN ROSYIDI, Dr.\r\n'),(174,NULL,'MUDJIRAN, Drs. H.\r\n'),(176,NULL,'SRI JUARI SANTOSA, Drs., M.Eng., Ph.D., Prof.,\r\n'),(177,NULL,'BAMBANG RUSDIARSO, Dr., DEA., Prof.\r\n'),(180,NULL,'MUCHALAL, Dr., Prof.\r\n'),(182,NULL,'PRIATMOKO, Drs., M.S.\r\n'),(183,NULL,'TRI JOKO RAHARJO, S.Si., M.Si., Ph.D.\r\n'),(184,NULL,'DENI PRANOWO, S.Si., M.Si.\r\n'),(186,NULL,'MOKHAMMAD FAJAR PRADIPTA, S.Si., M.Eng.,\r\n'),(187,NULL,'BAMBANG SETIAJI, Dr., Prof.\r\n'),(188,NULL,'IIP IZUL FALAH, Dr., Prof.,\r\n'),(189,NULL,'AKHMAD SYOUFIAN, S.Si., Ph.D.\r\n'),(190,NULL,'RIA ARMUNANTO, S.Si., M.Si., Dr.rer.nat.,\r\n'),(192,NULL,'MOCHAMMAD UTORO YAHYA, Dr., Prof.\r\n'),(195,NULL,'ANI SETYOPRATIWI, Dra., M.Si.,\r\n'),(196,NULL,'TRIYONO, S.U., Dr., Prof.\r\n'),(197,NULL,'NURUL HIDAYAT A., S.Si., M.Si., Dr.rer.nat.\r\n'),(199,NULL,'JOKO WINTOKO, M.S.\r\n'),(200,NULL,'HARDJONO SASTROHAMIDJOJO, Dr., Prof.\r\n'),(201,NULL,'SUGENG TRIONO, S.Si., M.Si.,\r\n'),(203,NULL,'HARNO DWI PRANOWO, M.S., Dr.rer.nat., Prof.,\r\n'),(206,NULL,'EKO SUGIHARTO, Dr., DEA.\r\n'),(207,NULL,'ENDANG TRI WAHYUNI, M.S., Dr., Prof.\r\n'),(208,NULL,'EDDY KRISTIYONO, A.Md.PK, SKM\r\n'),(210,NULL,'PEKIK NURWANTORO, Drs., M.S., Ph.D.,\r\n'),(213,NULL,'DIAH JUNIA EKSI PALUPI, Dra., S.U.\r\n'),(214,NULL,'IKHSAN SETIAWAN, S.Si., M.Si.\r\n'),(216,NULL,'SUPRAPTO, Drs., M.Ikom.\r\n'),(217,NULL,'PRIJONO NUGROHO, MSP., Ph.D.\r\n'),(218,NULL,'AZHARI, M.T., Dr.\r\n'),(219,NULL,'SIGIT PRIYANTA, S.Si., M.Kom.\r\n'),(220,NULL,'DEDI ROSADI, M.Sc., Dr.rer.nat., Prof.\r\n'),(225,NULL,'ANI SETYOPRATIWI, Dra., M.Si.\r\n'),(228,NULL,'NURYONO, Drs., M.S., Dr.rer.nat., Prof.,\r\n'),(229,NULL,'KARNA WIJAYA, Drs., M.Eng., Dr.rer.nat., Prof.\r\n'),(231,NULL,'M. FARCHANI ROSYID, Drs., M.Si., Dr.rer.nat.\r\n'),(232,NULL,'YOSEF ROBERTUS UTOMO, Drs., S.U.\r\n'),(233,NULL,'IMAM SUYANTO, Drs., M.Si.\r\n'),(235,NULL,'YATEMAN ARRYANTO, Dr.\r\n'),(236,NULL,'SUDIARTONO, Drs., M.S.\r\n'),(237,NULL,'DANARDONO, Drs., MPH., Ph.D.,\r\n'),(239,NULL,'DEDI ROSADI, M.Sc., Dr.rer.nat., Prof.,\r\n'),(241,NULL,'EDDY HARTANTYO, S.Si., M.Si.,\r\n'),(249,NULL,'RINTANG AWAN ELTRIBAKTI, S.Si.\r\n'),(251,NULL,'QOMARUDIN, Drs., M.Kes.\r\n'),(254,NULL,'JOKO SISWANTO, Dr., M.Hum.\r\n'),(255,NULL,'KARTINI, Dra., M.Hum.\r\n'),(256,NULL,'SUDARYANTO, Drs., M.Hum.\r\n'),(258,NULL,'HARNO DWI PRANOWO, M.S., Dr.rer.nat., Prof.\r\n'),(259,NULL,'AGUS SIHABUDDIN, S.Si., M.Kom.\r\n'),(262,NULL,'SURYO GURITNO, M.Stats., Ph.D., Prof.\r\n'),(263,NULL,'WIDODO, M.S., Dr.rer.nat., Prof.,\r\n'),(264,NULL,'SRI WAHYUNI, S.U., Dr., Prof.\r\n'),(265,NULL,'YENI SUSANTI, S.Si., M.Si., Ph.D.\r\n'),(266,NULL,'SRI WAHYUNI, S.U., Dr., Prof.,\r\n'),(268,NULL,'PRIMASTUTI INDAH SURYANI, S.Si., M.Si.\r\n'),(270,NULL,'LINA ARYATI, Dra., M.S., Dr.rer.nat.\r\n'),(272,NULL,'SUBANAR, Drs., Ph.D., Prof.\r\n'),(273,NULL,'BUDI SURODJO, M.Si., Dr.,\r\n'),(275,NULL,'RETANTYO WARDOYO, Drs., M.Sc., Ph.D.\r\n'),(279,NULL,'SOEPARNA DARMAWIDJAJA, Dr., Prof.\r\n'),(280,NULL,'SYAMSUL BARRY, S.Sn., M.Hum.\r\n'),(281,NULL,'JULIASIH PARTINI, S.Si., M.Si.\r\n'),(282,NULL,'KARNA WIJAYA, Drs., M.Eng., Dr.rer.nat., Prof.,\r\n'),(283,NULL,'WAHYUDI ISTIONO, dr., M.Kes.\r\n'),(294,NULL,'MOCHAMMAD NUKMAN, S.T., M.Sc.\r\n'),(295,NULL,'SUNARTINI HAPSARA, dr., Sp.A., P.Hd.\r\n'),(297,NULL,'INDARSIH, S.Si., M.Si.,\r\n'),(298,NULL,'DWI ERTININGSIH, S.Si., M.Si.\r\n'),(299,NULL,'SINTIA WINDHI NIASARI, S.Si., M.Eng.,\r\n'),(300,NULL,'EKO SRI KUNARTI, Dra., M.Si., Ph.D.,\r\n'),(307,NULL,'EDDY HARTANTYO, S.Si., M.Si.\r\n'),(310,NULL,'INDARSIH, S.Si., M.Si.\r\n'),(311,NULL,'RETNO WIKAN TYASNING, Dra.\r\n'),(312,NULL,'SUMARNI DWI W., Dra., M.Kes.\r\n'),(318,NULL,'BAMBANG NURCAHYO PRASTOWO, Drs., M.Sc.\r\n'),(319,NULL,'FAIZAL MAKHRUS, S.Kom., M.Sc.\r\n'),(322,NULL,'ZULAELA, Drs., Dipl.Med.Stats., M.Si.\r\n'),(323,NULL,'YUNITA WULAN SARI, S.Si., M.Sc.\r\n'),(325,NULL,'BAMBANG PURWONO, Drs., M.Sc., Ph.D.\r\n'),(326,NULL,'RUBAIYATUN RASDAN, Dra.\r\n'),(332,NULL,'MIRZA SATRIAWAN, S.Si., M.Si., Ph.D.,\r\n'),(333,NULL,'EKO SULISTYA, Drs., M.Si.,\r\n'),(334,NULL,'Sri Noegrohati, Prof.Dr., Apt.,\r\n'),(336,NULL,'JULIASIH PARTINI, S.Si., M.Si.,\r\n');

/*Table structure for table `dosen_kelas` */

DROP TABLE IF EXISTS `dosen_kelas`;

CREATE TABLE `dosen_kelas` (
  `dsnkls_id` int(11) NOT NULL AUTO_INCREMENT,
  `dsnkls_dsn_id` int(11) DEFAULT NULL,
  `dsnkls_kls_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`dsnkls_id`),
  KEY `dsnkls_key` (`dsnkls_dsn_id`,`dsnkls_kls_id`),
  KEY `FK_dosen_kelas_2` (`dsnkls_kls_id`),
  CONSTRAINT `FK_dosen_kelas_2` FOREIGN KEY (`dsnkls_kls_id`) REFERENCES `kelas` (`kls_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_dosen_kelas` FOREIGN KEY (`dsnkls_dsn_id`) REFERENCES `dosen` (`dsn_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `dosen_kelas` */

/*Table structure for table `dosen_waktu` */

DROP TABLE IF EXISTS `dosen_waktu`;

CREATE TABLE `dosen_waktu` (
  `dsnwkt_id` int(11) NOT NULL AUTO_INCREMENT,
  `dsnwkt_dsn_id` int(11) DEFAULT NULL,
  `dsnwkt_wkt_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`dsnwkt_id`),
  KEY `dsnwkt_key` (`dsnwkt_dsn_id`,`dsnwkt_wkt_id`),
  KEY `FK_dosen_waktu_2` (`dsnwkt_wkt_id`),
  CONSTRAINT `FK_dosen_waktu` FOREIGN KEY (`dsnwkt_dsn_id`) REFERENCES `dosen` (`dsn_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_dosen_waktu_2` FOREIGN KEY (`dsnwkt_wkt_id`) REFERENCES `waktu` (`waktu_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Data for the table `dosen_waktu` */

insert  into `dosen_waktu`(`dsnwkt_id`,`dsnwkt_dsn_id`,`dsnwkt_wkt_id`) values (9,60,14),(5,60,34),(1,98,1),(3,98,7),(2,98,20),(4,325,34);

/*Table structure for table `jadwal_kuliah` */

DROP TABLE IF EXISTS `jadwal_kuliah`;

CREATE TABLE `jadwal_kuliah` (
  `jk_id` int(11) NOT NULL AUTO_INCREMENT,
  `jk_kls_id` int(11) DEFAULT NULL,
  `jk_wkt_id` int(11) DEFAULT NULL,
  `jk_ru_id` int(11) DEFAULT NULL,
  `jk_period` int(6) DEFAULT NULL,
  `jk_jam_selesai` time DEFAULT NULL,
  `jk_label` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`jk_id`),
  KEY `FK_jadwal_kuliah_` (`jk_wkt_id`),
  KEY `FK_jadwal_kuliah_2` (`jk_ru_id`),
  KEY `FK_jadwal_kuliah` (`jk_kls_id`),
  CONSTRAINT `FK_jadwal_kuliah` FOREIGN KEY (`jk_kls_id`) REFERENCES `kelas` (`kls_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_jadwal_kuliah_` FOREIGN KEY (`jk_wkt_id`) REFERENCES `waktu` (`waktu_id`),
  CONSTRAINT `FK_jadwal_kuliah_2` FOREIGN KEY (`jk_ru_id`) REFERENCES `ruang` (`ru_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `jadwal_kuliah` */

/*Table structure for table `jadwal_ujian` */

DROP TABLE IF EXISTS `jadwal_ujian`;

CREATE TABLE `jadwal_ujian` (
  `ju_id` int(11) NOT NULL AUTO_INCREMENT,
  `ju_kls_id` int(11) DEFAULT NULL,
  `ju_wkt_id` int(11) DEFAULT NULL,
  `ju_ru_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`ju_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `jadwal_ujian` */

/*Table structure for table `kelas` */

DROP TABLE IF EXISTS `kelas`;

CREATE TABLE `kelas` (
  `kls_id` int(11) NOT NULL AUTO_INCREMENT,
  `kls_mkkur_id` int(11) DEFAULT NULL,
  `kls_nama` varchar(20) DEFAULT NULL,
  `kls_kode_paralel` varchar(6) DEFAULT NULL,
  `kls_jml_peserta_prediksi` int(6) DEFAULT NULL,
  PRIMARY KEY (`kls_id`),
  KEY `kls_mkkur_id_key` (`kls_mkkur_id`),
  CONSTRAINT `FK_kelas_key_2` FOREIGN KEY (`kls_mkkur_id`) REFERENCES `mata_kuliah_kurikulum` (`mkkur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `kelas` */

/*Table structure for table `mahasiswa_kelas` */

DROP TABLE IF EXISTS `mahasiswa_kelas`;

CREATE TABLE `mahasiswa_kelas` (
  `mhskrs_id` int(11) NOT NULL AUTO_INCREMENT,
  `mhskrs_niu` varchar(20) DEFAULT NULL,
  `mhskrs_kls_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`mhskrs_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `mahasiswa_kelas` */

/*Table structure for table `mata_kuliah_kurikulum` */

DROP TABLE IF EXISTS `mata_kuliah_kurikulum`;

CREATE TABLE `mata_kuliah_kurikulum` (
  `mkkur_id` int(11) NOT NULL AUTO_INCREMENT,
  `mkkur_kode` varchar(20) DEFAULT NULL,
  `mkkur_nama` varchar(200) DEFAULT NULL,
  `mkkur_sks` int(5) DEFAULT NULL,
  `mkkur_semester` varchar(10) DEFAULT NULL COMMENT 'Genjil, Genap',
  `mkkur_sifat` varchar(3) DEFAULT NULL COMMENT 'W:Wajib,P:Pilihan',
  `mkkur_paket_semester` varchar(5) DEFAULT NULL,
  `mkkur_pred_jml_peminat` int(11) DEFAULT NULL,
  `mkkur_pred_tahun` year(4) DEFAULT NULL,
  `mkkur_jumlah_pert` int(1) DEFAULT '1',
  `mkkur_is_universal` tinyint(1) DEFAULT '0' COMMENT '1:is universal (1 class only)',
  `mkkur_format_jadwal` varchar(11) DEFAULT NULL COMMENT '[1-2],[2-1]',
  `mkkur_maks_kelas` int(11) DEFAULT NULL COMMENT 'maks peserta kelas',
  PRIMARY KEY (`mkkur_id`)
) ENGINE=InnoDB AUTO_INCREMENT=257 DEFAULT CHARSET=latin1;

/*Data for the table `mata_kuliah_kurikulum` */

insert  into `mata_kuliah_kurikulum`(`mkkur_id`,`mkkur_kode`,`mkkur_nama`,`mkkur_sks`,`mkkur_semester`,`mkkur_sifat`,`mkkur_paket_semester`,`mkkur_pred_jml_peminat`,`mkkur_pred_tahun`,`mkkur_jumlah_pert`,`mkkur_is_universal`,`mkkur_format_jadwal`,`mkkur_maks_kelas`) values (1,'UNU1000','AGAMA ISLAM',2,'ganjil','w','1',0,0000,1,1,'',54),(2,'MIK2201','ALGORITMA DAN STRUKTUR DATA II',3,'ganjil','w','3',0,0000,1,0,'',47),(3,'MIK4207','ALGORITMA GENETIKA',3,'ganjil','p','5',0,0000,1,0,'2-1',31),(4,'MFF2025','ALJABAR ABSTRAK DALAM FISIKA TEORETIK',3,'ganjil','p','3',0,0000,1,0,'2-1',19),(5,'MMM1202','ALJABAR LINEAR ELEMENTER',3,'ganjil','w','1',0,0000,1,0,'1-2',43),(6,'MMM3204','ALJABAR LINEAR NUMERIK',2,'ganjil','p','5',0,0000,1,0,'',34),(7,'MMM1207','ALJABAR VEKTOR DAN MATRIKS',2,'ganjil','w','1',0,0000,1,0,'',45),(8,'MIK2203','ANALISIS DAN DESAIN ALGORITMA I',3,'ganjil','w','3',0,0000,1,0,'',50),(9,'MKK3501','ANALISIS INSTRUMENTAL II',2,'ganjil','w','5',0,0000,1,0,'',53),(10,'MKK3841','ANALISIS KIMIA LINGKUNGAN',2,'ganjil','p','1',0,0000,1,0,'',81),(11,'MIE2806','ANALISIS PENGUKURAN FISIS',2,'ganjil','w','3',0,0000,1,0,'',53),(12,'MMS3402','ANALISIS REGRESI TERAPAN',2,'ganjil','w','5',0,0000,1,0,'',66),(13,'MFG3948','ANALISIS SPEKTRUM SINYAL DIGITAL',2,'ganjil','p','5',0,0000,1,0,'',77),(14,'MMS3417','ANALISIS VARIANSI TERAPAN',2,'ganjil','p','5',0,0000,1,0,'',85),(15,'MMM2105','ANALISIS VEKTOR',2,'ganjil','p','3',0,0000,1,0,'',60),(16,'MKK3831','AZAS TEKNIK KIMIA',2,'ganjil','p','1',0,0000,1,0,'',49),(17,'MIE1002','BAHASA INDONESIA',2,'ganjil','w','1',0,0000,1,0,'',48),(18,'MFF1001','BAHASA INGGRIS',2,'ganjil','w','1',0,0000,1,0,'',86),(19,'MIE1001','BAHASA INGGRIS',3,'ganjil','w','1',0,0000,1,0,'',46),(20,'MIK1003','BAHASA INGGRIS',3,'ganjil','w','1',0,0000,1,0,'',39),(21,'MMS1000','BAHASA INGGRIS',2,'ganjil','w','1',0,0000,1,0,'',84),(22,'MFG1000','BAHASA INGGRIS SAINS',2,'ganjil','w','1',0,0000,1,0,'',NULL),(23,'MKK2601','BIOKIMIA I',2,'ganjil','w','3',0,0000,1,0,'',48),(24,'BIU1002','BIOLOGI DASAR',2,'ganjil','w','1',0,0000,1,0,'',55),(25,'MMS3474','BIOSTATISTIKA DAN EPIDEMIOLOGI I',2,'ganjil','p','5',0,0000,1,0,'',51),(26,'MKK2201','DASAR REAKSI ANORGANIK',2,'ganjil','w','3',0,0000,1,0,'',55),(27,'MIK4501','DATA MINING DAN BUSINESS INTELLIGENCE',3,'ganjil','w','5',0,0000,1,0,'',90),(28,'MFG4963','EKSPLORASI PANAS BUMI',3,'ganjil','p','7',0,0000,1,0,'1-2',67),(29,'MKK3301','ELEKTROKIMIA',2,'ganjil','w','5',0,0000,1,0,'',48),(30,'MIE4004','ELEKTROMAGNETIKA',3,'ganjil','p','5',0,0000,1,0,'',41),(31,'MFF3415','ELEKTROMAGNETIKA',3,'ganjil','w','7',0,0000,1,0,'2-1',88),(32,'MIE2808','ELEKTRONIKA ANALOG',3,'ganjil','w','3',0,0000,1,0,'',44),(33,'MIE3816','ELEKTRONIKA LANJUT II',3,'ganjil','w','5',0,0000,1,0,'',45),(34,'MKK3403','ELUSIDASI STRUKTUR ORGANIK',2,'ganjil','w','5',0,0000,1,0,'',54),(35,'MIE4608','EMBEDDED SYSTEM O.S.',3,'ganjil','p','7',0,0000,1,0,'',13),(36,'MIK4503','ENTERPRISE SYSTEM',3,'ganjil','w','7',0,0000,1,0,'',55),(37,'MMS1407','ETIKA PROFESI DAN SUCCESS SKILLS',2,'ganjil','w','1',0,0000,1,0,'',40),(38,'MIK3001','FILSAFAT ILMU KOMPUTER',2,'ganjil','w','5',0,0000,1,0,'',44),(39,'MFF3311','FISIKA ATOM & MOLEKUL LANJUT',3,'ganjil','p','5',0,0000,1,0,'1-2',35),(40,'MFF2871','FISIKA CITRA',3,'ganjil','p','3',0,0000,1,0,'2-1',59),(41,'MFF1011','FISIKA DASAR I',3,'ganjil','w','1',0,0000,1,0,'2-1',60),(42,'MFG4933','FISIKA GUNUNG API',2,'ganjil','w','7',0,0000,1,0,'',97),(43,'MFF3201','FISIKA INTI',3,'ganjil','w','5',0,0000,1,0,'1-2',82),(44,'MFF3701','FISIKA KEDOKTERAN',2,'ganjil','p','7',0,0000,1,0,'',48),(45,'MFF3891','FISIKA LINGKUNGAN',2,'ganjil','p','3',0,0000,1,0,'',52),(46,'MFF3603','FISIKA MATERIAL A',3,'ganjil','p','7',0,0000,1,0,'2-1',20),(47,'MFF4813','FISIKA MATERIAL KOMPUTASI',3,'ganjil','p','3',0,0000,1,0,'2-1',19),(48,'MFF2051','FISIKA STATISTIK',2,'ganjil','w','3',0,0000,1,0,'',73),(49,'MFF3871','FISIKA TOMOGRAFI',3,'ganjil','p','5',0,0000,1,0,'2-1',26),(50,'MFF3601','FISIKA ZAT PADAT',3,'ganjil','w','5',0,0000,1,0,'2-1',94),(51,'MMM3106','FUNGSI VARIABEL KOMPLEKS II',2,'ganjil','w','5',0,0000,1,0,'',50),(52,'MFF3841','GELOMBANG MIKRO',3,'ganjil','p','5',0,0000,1,0,'1-2',68),(53,'MFG3919','GEODINAMIKA',3,'ganjil','w','5',0,0000,1,0,'1-2',62),(54,'MFG4964','GEOFISIKA LINGKUNGAN',2,'ganjil','p','7',0,0000,1,0,'',76),(55,'MKK3245','GEOKIMIA',2,'ganjil','p','5',0,0000,1,0,'',77),(56,'MKK3811','GEOKIMIA',2,'ganjil','p','1',0,0000,1,0,'',41),(57,'MFG2906','GEOLOGI STRUKTUR',2,'ganjil','w','3',0,0000,1,0,'',90),(58,'MMM2113','GEOMETRI',3,'ganjil','p','3',0,0000,1,0,'2-1',22),(59,'MFF3021','GEOMETRI DIFERENSIAL DALAM FISIKA TEORETIK',3,'ganjil','p','5',0,0000,1,0,'1-2',12),(60,'MMM2114','GEOMETRI TRANSFORMASI',2,'ganjil','w','3',0,0000,1,0,'',38),(61,'MFG3946','GLOBAL POSITIONING SYSTEM',2,'ganjil','p','5',0,0000,1,0,'',20),(62,'MIK3203','GRAFIKA KOMPUTER',3,'ganjil','w','5',0,0000,1,0,'',56),(63,'MIE4823','HMI/SCADA DAN DCS',3,'ganjil','p','7',0,0000,1,0,'',68),(64,'MKK2831','INDUSTRI KIMIA',2,'ganjil','p','1',0,0000,1,0,'',82),(65,'MIE3817','INSTRUMENTASI ELEKTRONIK',3,'ganjil','p','5',0,0000,1,0,'',96),(66,'MIK3401','INTERAKSI MANUSIA KOMPUTER',3,'ganjil','w','5',0,0000,1,0,'',37),(67,'MKK3833','JAMINAN MUTU DALAM INDUSTRI',2,'ganjil','p','1',0,0000,1,0,'',78),(68,'MIK4403','JARINGAN SYARAF TIRUAN',3,'ganjil','w','5',0,0000,1,0,'',23),(69,'MMM1106','KALKULUS',3,'ganjil','w','1',0,0000,1,0,'2-1',85),(70,'MMM1101','KALKULUS I',3,'ganjil','w','1',0,0000,1,0,'2-1',54),(71,'MMM2109','KALKULUS MULTIVARIABEL I',2,'ganjil','w','3',0,0000,1,0,'',49),(72,'MFG3950','KAPITA SELEKTA A',2,'ganjil','p','5',0,0000,1,0,'',52),(73,'MFF4811','KAPITA SELEKTA FISIKA MATERIAL',3,'ganjil','p','7',0,0000,1,0,'2-1',26),(74,'MMM4349','KAPITA SELEKTA MATEMATIKA TERAPAN',3,'ganjil','p','7',0,0000,1,0,'',37),(75,'MMS4449','KAPITA SELEKTA STATISTIK',3,'ganjil','p','7',0,0000,1,0,'',60),(76,'MIK4601','KEAMANAN SISTEM DAN JARINGAN',3,'ganjil','w','5',0,0000,1,0,'',87),(77,'MKK3863','KEMOINFORMATIKA',2,'ganjil','p','1',0,0000,1,0,'',68),(78,'MKK3701','KEMOMETRI',2,'ganjil','w','5',0,0000,1,0,'',51),(79,'MIE4010','KEWIRAUSAHAAN DAN MANAJEMEN',2,'ganjil','w','7',0,0000,1,0,'',79),(80,'MKK1501','KIMIA ANALITIK DASAR I',2,'ganjil','w','1',0,0000,1,0,'',74),(81,'MKK2843','KIMIA B3',2,'ganjil','p','1',0,0000,1,0,'',62),(82,'MKK3813','KIMIA BIOANORGANIK',2,'ganjil','p','1',0,0000,1,0,'',75),(83,'MKK1101','KIMIA DASAR I',3,'ganjil','w','1',0,0000,1,0,'1-2',62),(84,'MKK2823','KIMIA HASIL ALAM',2,'ganjil','p','1',0,0000,1,0,'',60),(85,'MKB1000','KIMIA KONTEKSTUAL',2,'ganjil','w','1',0,0000,1,0,'',65),(86,'MKK2841','KIMIA LINGKUNGAN',2,'ganjil','p','1',0,0000,1,0,'',43),(87,'MKK2821','KIMIA MEDISINAL',2,'ganjil','p','1',0,0000,1,0,'',60),(88,'MKK1401','KIMIA ORGANIK DASAR I',2,'ganjil','w','1',0,0000,1,0,'',74),(89,'MKK3401','KIMIA ORGANIK FISIK',2,'ganjil','w','5',0,0000,1,0,'',53),(90,'MKK3821','KIMIA PANGAN',2,'ganjil','p','1',0,0000,1,0,'',51),(91,'MKK2501','KIMIA PEMISAHAN',3,'ganjil','w','3',0,0000,1,0,'2-1',53),(92,'MKK3819','KIMIA POLIMER',2,'ganjil','p','1',0,0000,1,0,'',60),(93,'MKK3815','KIMIA ZAT PADAT',2,'ganjil','p','1',0,0000,1,0,'',50),(94,'MKK2303','KINETIKA KIMIA',3,'ganjil','w','3',0,0000,1,0,'2-1',55),(95,'MKK3865','KOMPUTASI REKAYASA MOLEKULER',2,'ganjil','p','1',0,0000,1,0,'',58),(96,'MMS3415','KOMPUTASI STATISTIK',2,'ganjil','w','5',0,0000,1,0,'',76),(97,'MFF2011','KOMPUTER MULTIMEDIA',2,'ganjil','p','3',0,0000,1,0,'',NULL),(98,'MFB1000','KONSEP FISIKA',2,'ganjil','w','1',0,0000,1,0,'',64),(99,'MIE3819','KONTROL DIGITAL',3,'ganjil','p','5',0,0000,1,0,'',47),(100,'MFF2411','LISTRIK DAN MAGNET',3,'ganjil','w','3',0,0000,1,0,'1-2',77),(101,'MIK4401','LOGIKA FUZZY',3,'ganjil','w','5',0,0000,1,0,'',25),(102,'MFG4935','LOKAKARYA GEOFISIKA LAPANGAN',2,'ganjil','w','7',0,0000,1,0,'',88),(103,'MIK4603','MANAJEMEN JARINGAN',3,'ganjil','w','5',0,0000,1,0,'',71),(104,'MFG2942','MANAJEMEN PROYEK',2,'ganjil','p','3',0,0000,1,0,'',90),(105,'MMS3424','MANAJEMEN RESIKO KUANTITATIF',3,'ganjil','p','5',0,0000,1,0,'',64),(106,'MIK1201','MATEMATIKA DISKRIT I',3,'ganjil','w','1',0,0000,1,0,'',60),(107,'MFF2023','MATEMATIKA FISIKA II',3,'ganjil','w','3',0,0000,1,0,'1-2',52),(108,'MMM3305','MATEMATIKA KOMPUTASI',3,'ganjil','w','5',0,0000,1,0,'',37),(109,'MMB1000','MATEMATIKA KONTEKSTUAL',2,'ganjil','w','1',0,0000,1,0,'',69),(110,'MKK2701','MATEMATIKA UNTUK KIMIA',2,'ganjil','w','3',0,0000,1,0,'',46),(111,'MKK3853','MATERIAL POLIMER DAN KOMPOSIT',2,'ganjil','p','1',0,0000,1,0,'',52),(112,'MFF2403','MEKANIKA ANALITIK',3,'ganjil','w','3',0,0000,1,0,'2-1',71),(113,'MFG4958','MEKANIKA BATUAN',2,'ganjil','p','7',0,0000,1,0,'',19),(114,'MFF2951','MEKANIKA BENDA LANGIT',3,'ganjil','p','3',0,0000,1,0,'2-1',64),(115,'MFF4031','MEKANIKA KUANTUM',3,'ganjil','p','5',0,0000,1,0,'2-1',17),(116,'MKK3201','MEKANISME REAKSI ANORGANIK',2,'ganjil','w','5',0,0000,1,0,'',54),(117,'MIE2811','MEKATRONIKA',3,'ganjil','p','3',0,0000,1,0,'',97),(118,'MIE3008','MET. PENEL. ELEKTRONIKA DAN INSTRUMENTASI',2,'ganjil','w','5',0,0000,1,0,'',12),(119,'MFF3291','METODE DETEKSI NUKLIR & PARTIKEL',2,'ganjil','p','5',0,0000,1,0,'',12),(120,'MFG3920','METODE GEOELEKTRISITAS DAN EM',3,'ganjil','w','5',0,0000,1,0,'2-1',90),(121,'MIK3201','METODE NUMERIK',2,'ganjil','w','5',0,0000,1,0,'',42),(122,'MFF1061','METODE PENGUKURAN FISIKA',2,'ganjil','w','1',0,0000,1,0,'',99),(123,'MFG3917','METODE SEISMIK I',2,'ganjil','w','5',0,0000,1,0,'',90),(124,'MMS1423','METODE STATISTIK I',2,'ganjil','w','3',0,0000,1,0,'',45),(125,'MMS2403','METODE SURVEI SAMPEL',3,'ganjil','w','3',0,0000,1,0,'',45),(126,'MMS3427','METODOLOGI PENELITIAN',3,'ganjil','w','5',0,0000,1,0,'',67),(127,'MFF3063','METODOLOGI PENELITIAN FISIKA',2,'ganjil','w','5',0,0000,1,0,'',85),(128,'MIE2601','MIKROKOMPUTER',3,'ganjil','w','3',0,0000,1,0,'',83),(129,'MIE3607','MIKROKONTROLER',3,'ganjil','p','5',0,0000,1,0,'',34),(130,'MIK4507','MULTIMEDIA',3,'ganjil','p','5',0,0000,1,0,'',26),(131,'MKK3851','NANO DAN BIOMATERIAL',2,'ganjil','p','1',0,0000,1,0,'',50),(132,'MFF2413','OPTIKA',2,'ganjil','w','3',0,0000,1,0,'',47),(133,'MIK4203','PEMODELAN DAN SIMULASI',3,'ganjil','w','5',0,0000,1,0,'',10),(134,'MIK4608','PEMROGRAMAN JARINGAN DAN PIRANTI BERGERAK',3,'ganjil','p','5',0,0000,1,0,'',29),(135,'MIE4610','PEMROSESAN SINYAL DIGITAL II',3,'ganjil','p','7',0,0000,1,0,'',13),(136,'MMM4102','PENG. ANALISIS FUNGSIONAL',3,'ganjil','p','7',0,0000,1,0,'',NULL),(137,'MMS4417','PENG. ANALISIS GARANSI',3,'ganjil','p','7',0,0000,1,0,'',23),(138,'MMM3101','PENG. ANALISIS I',3,'ganjil','w','5',0,0000,1,0,'1-2',42),(139,'MMM4204','PENG. KOMBINATORIK',3,'ganjil','p','7',0,0000,1,0,'1-2',17),(140,'MMM1201','PENG. LOGIKA MATEMATIKA & HIMPUNAN',3,'ganjil','w','1',0,0000,1,0,'1-2',48),(141,'MMS3472','PENG. MATEMATIKA AKTUARIA I',2,'ganjil','p','5',0,0000,1,0,'',45),(142,'MMS2418','PENG. MATEMATIKA FINANSIAL I',3,'ganjil','p','3',0,0000,1,0,'',60),(143,'MMM3303','PENG. MODEL MATEMATIKA',3,'ganjil','w','5',0,0000,1,0,'2-1',73),(144,'MMS2410','PENG. MODEL PROBABILITAS',3,'ganjil','w','3',0,0000,1,0,'',73),(145,'MMM3302','PENG. PERSAMAAN DIFERENSIAL PARSIAL',3,'ganjil','w','5',0,0000,1,0,'2-1',47),(146,'MMS3422','PENG. PROSES STOKASTIK',3,'ganjil','p','5',0,0000,1,0,'',70),(147,'MMS3469','PENG. STATISTIKA MATEMATIK II',3,'ganjil','w','5',0,0000,1,0,'',62),(148,'MMM2201','PENG. STRUKTUR ALJABAR II',3,'ganjil','w','3',0,0000,1,0,'2-1',38),(149,'MMS3416','PENG. TEORI ANTRIAN DAN SIMULASI',2,'ganjil','p','5',0,0000,1,0,'',19),(150,'MMS3476','PENG. TEORI KEPUTUSAN',3,'ganjil','p','5',0,0000,1,0,'',68),(151,'MMM2308','PENG. TEORI PERMAINAN',3,'ganjil','p','3',0,0000,1,0,'',62),(152,'MFF4893','PENGANTAR EKONOFISIKA',3,'ganjil','p','7',0,0000,1,0,'',NULL),(153,'MFF3421','PENGANTAR FISIKA LASER',3,'ganjil','p','5',0,0000,1,0,'1-2',53),(154,'MFG1901','PENGANTAR GEOFISIKA',2,'ganjil','p','3',0,0000,1,0,'',52),(155,'MIE2809','PENGANTAR INSTRUMENTASI',3,'ganjil','w','3',0,0000,1,0,'',42),(156,'MKK2851','PENGANTAR KIMIA MATERIAL',2,'ganjil','p','1',0,0000,1,0,'',83),(157,'MMM2304','PENGANTAR PERSAMAAN DIFERENSIAL',3,'ganjil','w','3',0,0000,1,0,'',71),(158,'MIE4403','PENGENALAN POLA',3,'ganjil','p','7',0,0000,1,0,'',51),(159,'MIK4405','PENGENALAN POLA',3,'ganjil','w','5',0,0000,1,0,'',2),(160,'MMS2423','PENGENDALIAN KUALITAS STATISTIKA',2,'ganjil','p','3',0,0000,1,0,'',62),(161,'MIK4209','PENGOLAHAN CITRA DIGITAL',3,'ganjil','p','5',0,0000,1,0,'',27),(162,'MFG3951','PENYELESAIAN NUMERIK',2,'ganjil','p','5',0,0000,1,0,'',87),(163,'MMS3430','PERAMALAN DATA TIME SERIES',2,'ganjil','p','5',0,0000,1,0,'',55),(164,'MFG2909','PERPETAAN',2,'ganjil','w','3',0,0000,1,0,'',82),(165,'MMM2301','PERSAMAAN DIFERENSIAL ELEMENTER',3,'ganjil','w','3',0,0000,1,0,'1-2',61),(166,'MFG2940','PETROLOGI',2,'ganjil','p','3',0,0000,1,0,'',60),(167,'MIE4822','PLC',3,'ganjil','p','7',0,0000,1,0,'',77),(168,'MMM2302','PROGRAM LINEAR',2,'ganjil','w','3',0,0000,1,0,'',NULL),(169,'MIK3501','REKAYASA PERANGKAT LUNAK',3,'ganjil','w','5',0,0000,1,0,'',NULL),(170,'MIE3401','ROBOTIKA',3,'ganjil','p','5',0,0000,1,0,'',NULL),(171,'MFF4061','SENSOR DAN TRANSDUSER',2,'ganjil','p','5',0,0000,1,0,'',NULL),(172,'MIE3815','SENSOR DAN TRANSDUSER',3,'ganjil','p','7',0,0000,1,0,'',NULL),(173,'MIE4824','SENSOR NETWORKS',3,'ganjil','p','7',0,0000,1,0,'',NULL),(174,'MIE3818','SIMULASI ELEKTRONIKA',3,'ganjil','p','5',0,0000,1,0,'',NULL),(175,'MKK3861','SIMULASI MOLEKULER',2,'ganjil','p','1',0,0000,1,0,'',NULL),(176,'MIK4509','SISTEM INFORMASI GEOGRAFIS',3,'ganjil','p','5',0,0000,1,0,'',NULL),(177,'MIK2601','SISTEM OPERASI',3,'ganjil','w','3',0,0000,1,0,'',NULL),(178,'MIK4609','SISTEM PARALEL',3,'ganjil','p','7',0,0000,1,0,'',NULL),(179,'MIK4605','SISTEM TERDISTRIBUSI',3,'ganjil','w','5',0,0000,1,0,'',NULL),(180,'MKK2401','STEREOKIMIA',2,'ganjil','w','3',0,0000,1,0,'',NULL),(181,'MFG3945','STRATIGRAFI',2,'ganjil','p','5',0,0000,1,0,'',NULL),(182,'MIB1000','TEKNOLOGI INFORMASI KONTEMPORER',2,'ganjil','w','1',0,0000,1,0,'',NULL),(183,'MFG3916','TEKTONIK INDONESIA',2,'ganjil','w','5',0,0000,1,0,'',NULL),(184,'MIK4201','TEORI BAHASA DAN OTOMATA',3,'ganjil','w','5',0,0000,1,0,'',NULL),(185,'MMM3203','TEORI GRUP HINGGA',2,'ganjil','p','5',0,0000,1,0,'',NULL),(186,'MFF2031','TEORI RELATIVITAS',2,'ganjil','w','3',0,0000,1,0,'',NULL),(187,'MMM3205','TEORI SEMIGRUP',3,'ganjil','p','5',0,0000,1,0,'1-2',NULL),(188,'MMM3301','TEORI SISTEM',3,'ganjil','p','5',0,0000,1,0,'',NULL),(189,'MKK2301','TERMODINAMIKA KIMIA',3,'ganjil','w','3',0,0000,1,0,'2-1',NULL),(190,'MIE1801','UNTAI LISTRIK',2,'ganjil','w','1',0,0000,1,0,'',NULL),(256,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,NULL);

/*Table structure for table `mata_kuliah_kurikulum_rekap` */

DROP TABLE IF EXISTS `mata_kuliah_kurikulum_rekap`;

CREATE TABLE `mata_kuliah_kurikulum_rekap` (
  `mkkurrkp_id` int(11) NOT NULL AUTO_INCREMENT,
  `mkkurrkp_mkkur_id` int(11) DEFAULT NULL,
  `mkkurrkp_jml_peminat` int(6) DEFAULT NULL,
  `mkkurrkp_tahun` year(4) DEFAULT NULL,
  PRIMARY KEY (`mkkurrkp_id`),
  KEY `mkkurrkp_mkkur_id_key` (`mkkurrkp_mkkur_id`),
  CONSTRAINT `FK_mata_kuliah_kurikulum_rekap_FK` FOREIGN KEY (`mkkurrkp_mkkur_id`) REFERENCES `mata_kuliah_kurikulum` (`mkkur_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1147 DEFAULT CHARSET=latin1;

/*Data for the table `mata_kuliah_kurikulum_rekap` */

insert  into `mata_kuliah_kurikulum_rekap`(`mkkurrkp_id`,`mkkurrkp_mkkur_id`,`mkkurrkp_jml_peminat`,`mkkurrkp_tahun`) values (1,NULL,0,2008),(2,NULL,0,2009),(3,NULL,0,2010),(4,NULL,0,2011),(5,NULL,0,2012),(6,NULL,0,2013),(7,NULL,3,2008),(8,NULL,0,2009),(9,NULL,3,2010),(10,NULL,0,2011),(11,NULL,0,2012),(12,NULL,0,2013),(13,1,424,2008),(14,1,415,2009),(15,1,426,2010),(16,1,40,2011),(17,1,0,2012),(18,1,0,2013),(19,NULL,2,2008),(20,NULL,6,2009),(21,NULL,4,2010),(22,NULL,0,2011),(23,NULL,0,2012),(24,NULL,0,2013),(25,NULL,8,2008),(26,NULL,5,2009),(27,NULL,10,2010),(28,NULL,0,2011),(29,NULL,0,2012),(30,NULL,0,2013),(31,2,0,2008),(32,2,0,2009),(33,2,0,2010),(34,2,0,2011),(35,2,23,2012),(36,2,5,2013),(37,3,0,2008),(38,3,0,2009),(39,3,0,2010),(40,3,0,2011),(41,3,0,2012),(42,3,0,2013),(43,4,0,2008),(44,4,0,2009),(45,4,0,2010),(46,4,0,2011),(47,4,0,2012),(48,4,0,2013),(49,5,0,2008),(50,5,0,2009),(51,5,0,2010),(52,5,65,2011),(53,5,98,2012),(54,5,111,2013),(55,6,0,2008),(56,6,0,2009),(57,6,0,2010),(58,6,0,2011),(59,6,0,2012),(60,6,0,2013),(61,7,115,2008),(62,7,93,2009),(63,7,170,2010),(64,7,39,2011),(65,7,13,2012),(66,7,2,2013),(67,8,0,2008),(68,8,0,2009),(69,8,0,2010),(70,8,0,2011),(71,8,0,2012),(72,8,0,2013),(73,9,0,2008),(74,9,0,2009),(75,9,0,2010),(76,9,0,2011),(77,9,0,2012),(78,9,0,2013),(79,10,87,2008),(80,10,68,2009),(81,10,65,2010),(82,10,0,2011),(83,10,0,2012),(84,10,0,2013),(85,11,0,2008),(86,11,0,2009),(87,11,0,2010),(88,11,0,2011),(89,11,0,2012),(90,11,0,2013),(91,12,0,2008),(92,12,0,2009),(93,12,0,2010),(94,12,0,2011),(95,12,0,2012),(96,12,0,2013),(97,13,0,2008),(98,13,0,2009),(99,13,0,2010),(100,13,0,2011),(101,13,0,2012),(102,13,0,2013),(103,14,0,2008),(104,14,0,2009),(105,14,0,2010),(106,14,0,2011),(107,14,0,2012),(108,14,0,2013),(109,15,0,2008),(110,15,0,2009),(111,15,0,2010),(112,15,0,2011),(113,15,0,2012),(114,15,0,2013),(115,NULL,3,2008),(116,NULL,1,2009),(117,NULL,2,2010),(118,NULL,0,2011),(119,NULL,11,2012),(120,NULL,9,2013),(121,NULL,0,2008),(122,NULL,6,2009),(123,NULL,7,2010),(124,NULL,0,2011),(125,NULL,15,2012),(126,NULL,5,2013),(127,16,0,2008),(128,16,0,2009),(129,16,0,2010),(130,16,0,2011),(131,16,0,2012),(132,16,0,2013),(133,17,163,2008),(134,17,163,2009),(135,17,157,2010),(136,17,89,2011),(137,17,6,2012),(138,17,0,2013),(139,18,0,2008),(140,18,0,2009),(141,18,0,2010),(142,18,323,2011),(143,18,300,2012),(144,18,258,2013),(145,19,0,2008),(146,19,0,2009),(147,19,0,2010),(148,19,323,2011),(149,19,300,2012),(150,19,258,2013),(151,21,0,2008),(152,21,0,2009),(153,21,0,2010),(154,21,323,2011),(155,21,300,2012),(156,21,258,2013),(157,20,0,2008),(158,20,0,2009),(159,20,0,2010),(160,20,323,2011),(161,20,300,2012),(162,20,258,2013),(163,NULL,15,2008),(164,NULL,0,2009),(165,NULL,9,2010),(166,NULL,0,2011),(167,NULL,0,2012),(168,NULL,0,2013),(169,23,0,2008),(170,23,0,2009),(171,23,0,2010),(172,23,0,2011),(173,23,0,2012),(174,23,0,2013),(175,24,0,2008),(176,24,0,2009),(177,24,0,2010),(178,24,0,2011),(179,24,0,2012),(180,24,0,2013),(181,25,0,2008),(182,25,0,2009),(183,25,0,2010),(184,25,0,2011),(185,25,0,2012),(186,25,0,2013),(187,26,0,2008),(188,26,0,2009),(189,26,0,2010),(190,26,0,2011),(191,26,0,2012),(192,26,0,2013),(193,27,0,2008),(194,27,0,2009),(195,27,0,2010),(196,27,0,2011),(197,27,6,2012),(198,27,18,2013),(199,NULL,0,2008),(200,NULL,0,2009),(201,NULL,0,2010),(202,NULL,0,2011),(203,NULL,0,2012),(204,NULL,0,2013),(205,NULL,0,2008),(206,NULL,0,2009),(207,NULL,0,2010),(208,NULL,0,2011),(209,NULL,0,2012),(210,NULL,0,2013),(211,NULL,0,2008),(212,NULL,0,2009),(213,NULL,0,2010),(214,NULL,0,2011),(215,NULL,0,2012),(216,NULL,0,2013),(217,NULL,0,2008),(218,NULL,0,2009),(219,NULL,0,2010),(220,NULL,0,2011),(221,NULL,0,2012),(222,NULL,0,2013),(223,NULL,0,2008),(224,NULL,0,2009),(225,NULL,0,2010),(226,NULL,0,2011),(227,NULL,0,2012),(228,NULL,0,2013),(229,NULL,0,2008),(230,NULL,0,2009),(231,NULL,0,2010),(232,NULL,0,2011),(233,NULL,0,2012),(234,NULL,0,2013),(235,NULL,0,2008),(236,NULL,0,2009),(237,NULL,0,2010),(238,NULL,0,2011),(239,NULL,0,2012),(240,NULL,0,2013),(241,NULL,0,2008),(242,NULL,0,2009),(243,NULL,0,2010),(244,NULL,27,2011),(245,NULL,0,2012),(246,NULL,0,2013),(247,NULL,0,2008),(248,NULL,0,2009),(249,NULL,0,2010),(250,NULL,0,2011),(251,NULL,0,2012),(252,NULL,0,2013),(253,NULL,0,2008),(254,NULL,0,2009),(255,NULL,0,2010),(256,NULL,0,2011),(257,NULL,0,2012),(258,NULL,0,2013),(259,NULL,0,2008),(260,NULL,0,2009),(261,NULL,0,2010),(262,NULL,0,2011),(263,NULL,0,2012),(264,NULL,0,2013),(265,28,0,2008),(266,28,0,2009),(267,28,0,2010),(268,28,0,2011),(269,28,0,2012),(270,28,0,2013),(271,29,0,2008),(272,29,0,2009),(273,29,0,2010),(274,29,0,2011),(275,29,0,2012),(276,29,0,2013),(277,30,0,2008),(278,30,0,2009),(279,30,0,2010),(280,30,0,2011),(281,30,0,2012),(282,30,0,2013),(283,31,0,2008),(284,31,0,2009),(285,31,0,2010),(286,31,0,2011),(287,31,0,2012),(288,31,0,2013),(289,NULL,0,2008),(290,NULL,0,2009),(291,NULL,0,2010),(292,NULL,64,2011),(293,NULL,79,2012),(294,NULL,83,2013),(295,32,0,2008),(296,32,0,2009),(297,32,0,2010),(298,32,0,2011),(299,32,0,2012),(300,32,0,2013),(301,33,80,2008),(302,33,86,2009),(303,33,93,2010),(304,33,0,2011),(305,33,0,2012),(306,33,0,2013),(307,34,0,2008),(308,34,0,2009),(309,34,0,2010),(310,34,0,2011),(311,34,0,2012),(312,34,0,2013),(313,35,0,2008),(314,35,0,2009),(315,35,0,2010),(316,35,31,2011),(317,35,0,2012),(318,35,0,2013),(319,36,0,2008),(320,36,0,2009),(321,36,0,2010),(322,36,0,2011),(323,36,4,2012),(324,36,12,2013),(325,37,0,2008),(326,37,0,2009),(327,37,0,2010),(328,37,0,2011),(329,37,0,2012),(330,37,0,2013),(331,38,0,2008),(332,38,0,2009),(333,38,0,2010),(334,38,0,2011),(335,38,0,2012),(336,38,0,2013),(337,39,0,2008),(338,39,0,2009),(339,39,0,2010),(340,39,0,2011),(341,39,0,2012),(342,39,0,2013),(343,40,0,2008),(344,40,0,2009),(345,40,0,2010),(346,40,0,2011),(347,40,0,2012),(348,40,0,2013),(349,41,0,2008),(350,41,0,2009),(351,41,0,2010),(352,41,0,2011),(353,41,0,2012),(354,41,0,2013),(355,42,0,2008),(356,42,0,2009),(357,42,0,2010),(358,42,0,2011),(359,42,0,2012),(360,42,0,2013),(361,43,1,2008),(362,43,0,2009),(363,43,0,2010),(364,43,0,2011),(365,43,0,2012),(366,43,0,2013),(367,44,0,2008),(368,44,0,2009),(369,44,0,2010),(370,44,0,2011),(371,44,0,2012),(372,44,0,2013),(373,45,0,2008),(374,45,0,2009),(375,45,0,2010),(376,45,0,2011),(377,45,0,2012),(378,45,0,2013),(379,46,0,2008),(380,46,0,2009),(381,46,0,2010),(382,46,0,2011),(383,46,0,2012),(384,46,0,2013),(385,47,0,2008),(386,47,0,2009),(387,47,0,2010),(388,47,0,2011),(389,47,0,2012),(390,47,0,2013),(391,48,59,2008),(392,48,73,2009),(393,48,68,2010),(394,48,0,2011),(395,48,0,2012),(396,48,0,2013),(397,49,0,2008),(398,49,0,2009),(399,49,0,2010),(400,49,0,2011),(401,49,0,2012),(402,49,0,2013),(403,50,0,2008),(404,50,0,2009),(405,50,0,2010),(406,50,0,2011),(407,50,0,2012),(408,50,0,2013),(409,51,0,2008),(410,51,0,2009),(411,51,0,2010),(412,51,0,2011),(413,51,0,2012),(414,51,0,2013),(415,52,0,2008),(416,52,0,2009),(417,52,0,2010),(418,52,0,2011),(419,52,0,2012),(420,52,0,2013),(421,53,0,2008),(422,53,0,2009),(423,53,0,2010),(424,53,0,2011),(425,53,0,2012),(426,53,0,2013),(427,54,0,2008),(428,54,0,2009),(429,54,0,2010),(430,54,0,2011),(431,54,0,2012),(432,54,0,2013),(433,55,0,2008),(434,55,0,2009),(435,55,0,2010),(436,55,0,2011),(437,55,0,2012),(438,55,0,2013),(439,56,0,2008),(440,56,0,2009),(441,56,0,2010),(442,56,0,2011),(443,56,0,2012),(444,56,0,2013),(445,57,0,2008),(446,57,0,2009),(447,57,0,2010),(448,57,0,2011),(449,57,0,2012),(450,57,0,2013),(451,58,73,2008),(452,58,64,2009),(453,58,84,2010),(454,58,0,2011),(455,58,0,2012),(456,58,0,2013),(457,60,0,2008),(458,60,0,2009),(459,60,0,2010),(460,60,0,2011),(461,60,0,2012),(462,60,0,2013),(463,61,0,2008),(464,61,0,2009),(465,61,0,2010),(466,61,0,2011),(467,61,0,2012),(468,61,0,2013),(469,62,9,2008),(470,62,2,2009),(471,62,0,2010),(472,62,0,2011),(473,62,0,2012),(474,62,0,2013),(475,63,0,2008),(476,63,0,2009),(477,63,0,2010),(478,63,0,2011),(479,63,0,2012),(480,63,0,2013),(481,64,0,2008),(482,64,0,2009),(483,64,0,2010),(484,64,0,2011),(485,64,0,2012),(486,64,0,2013),(487,NULL,0,2008),(488,NULL,0,2009),(489,NULL,0,2010),(490,NULL,92,2011),(491,NULL,85,2012),(492,NULL,137,2013),(493,65,0,2008),(494,65,0,2009),(495,65,0,2010),(496,65,0,2011),(497,65,0,2012),(498,65,0,2013),(499,66,0,2008),(500,66,0,2009),(501,66,0,2010),(502,66,82,2011),(503,66,0,2012),(504,66,0,2013),(505,67,0,2008),(506,67,0,2009),(507,67,0,2010),(508,67,0,2011),(509,67,0,2012),(510,67,0,2013),(511,68,0,2008),(512,68,0,2009),(513,68,0,2010),(514,68,0,2011),(515,68,0,2012),(516,68,0,2013),(517,69,0,2008),(518,69,0,2009),(519,69,0,2010),(520,69,0,2011),(521,69,0,2012),(522,69,0,2013),(523,70,0,2008),(524,70,0,2009),(525,70,0,2010),(526,70,0,2011),(527,70,0,2012),(528,70,0,2013),(529,NULL,0,2008),(530,NULL,0,2009),(531,NULL,0,2010),(532,NULL,0,2011),(533,NULL,0,2012),(534,NULL,0,2013),(535,71,0,2008),(536,71,0,2009),(537,71,0,2010),(538,71,0,2011),(539,71,0,2012),(540,71,0,2013),(541,72,0,2008),(542,72,0,2009),(543,72,0,2010),(544,72,0,2011),(545,72,0,2012),(546,72,0,2013),(547,73,0,2008),(548,73,0,2009),(549,73,0,2010),(550,73,0,2011),(551,73,0,2012),(552,73,0,2013),(553,74,0,2008),(554,74,0,2009),(555,74,0,2010),(556,74,41,2011),(557,74,35,2012),(558,74,5,2013),(559,75,0,2008),(560,75,0,2009),(561,75,0,2010),(562,75,0,2011),(563,75,0,2012),(564,75,0,2013),(565,76,0,2008),(566,76,0,2009),(567,76,0,2010),(568,76,0,2011),(569,76,0,2012),(570,76,5,2013),(571,77,0,2008),(572,77,0,2009),(573,77,0,2010),(574,77,0,2011),(575,77,0,2012),(576,77,0,2013),(577,78,0,2008),(578,78,0,2009),(579,78,0,2010),(580,78,0,2011),(581,78,0,2012),(582,78,0,2013),(583,NULL,265,2008),(584,NULL,259,2009),(585,NULL,200,2010),(586,NULL,324,2011),(587,NULL,282,2012),(588,NULL,526,2013),(589,NULL,265,2008),(590,NULL,259,2009),(591,NULL,200,2010),(592,NULL,324,2011),(593,NULL,282,2012),(594,NULL,526,2013),(595,79,0,2008),(596,79,0,2009),(597,79,0,2010),(598,79,103,2011),(599,79,112,2012),(600,79,119,2013),(601,80,0,2008),(602,80,0,2009),(603,80,0,2010),(604,80,0,2011),(605,80,0,2012),(606,80,0,2013),(607,81,0,2008),(608,81,0,2009),(609,81,0,2010),(610,81,0,2011),(611,81,0,2012),(612,81,0,2013),(613,82,0,2008),(614,82,0,2009),(615,82,0,2010),(616,82,0,2011),(617,82,0,2012),(618,82,0,2013),(619,83,0,2008),(620,83,0,2009),(621,83,0,2010),(622,83,0,2011),(623,83,0,2012),(624,83,0,2013),(625,84,0,2008),(626,84,0,2009),(627,84,0,2010),(628,84,0,2011),(629,84,0,2012),(630,84,0,2013),(631,85,0,2008),(632,85,0,2009),(633,85,0,2010),(634,85,0,2011),(635,85,0,2012),(636,85,0,2013),(637,86,0,2008),(638,86,0,2009),(639,86,0,2010),(640,86,0,2011),(641,86,0,2012),(642,86,0,2013),(643,87,0,2008),(644,87,0,2009),(645,87,0,2010),(646,87,0,2011),(647,87,0,2012),(648,87,0,2013),(649,88,0,2008),(650,88,0,2009),(651,88,0,2010),(652,88,0,2011),(653,88,0,2012),(654,88,0,2013),(655,89,0,2008),(656,89,0,2009),(657,89,0,2010),(658,89,0,2011),(659,89,0,2012),(660,89,0,2013),(661,90,0,2008),(662,90,0,2009),(663,90,0,2010),(664,90,0,2011),(665,90,0,2012),(666,90,0,2013),(667,91,0,2008),(668,91,0,2009),(669,91,0,2010),(670,91,0,2011),(671,91,0,2012),(672,91,0,2013),(673,92,0,2008),(674,92,0,2009),(675,92,0,2010),(676,92,0,2011),(677,92,0,2012),(678,92,0,2013),(679,93,0,2008),(680,93,0,2009),(681,93,0,2010),(682,93,0,2011),(683,93,0,2012),(684,93,0,2013),(685,94,0,2008),(686,94,0,2009),(687,94,0,2010),(688,94,0,2011),(689,94,0,2012),(690,94,0,2013),(691,95,0,2008),(692,95,0,2009),(693,95,0,2010),(694,95,0,2011),(695,95,0,2012),(696,95,0,2013),(697,96,65,2008),(698,96,71,2009),(699,96,56,2010),(700,96,0,2011),(701,96,0,2012),(702,96,0,2013),(703,97,21,2008),(704,97,65,2009),(705,97,52,2010),(706,97,0,2011),(707,97,0,2012),(708,97,0,2013),(709,98,0,2008),(710,98,0,2009),(711,98,0,2010),(712,98,0,2011),(713,98,0,2012),(714,98,0,2013),(715,99,0,2008),(716,99,0,2009),(717,99,0,2010),(718,99,0,2011),(719,99,0,2012),(720,99,0,2013),(721,100,0,2008),(722,100,0,2009),(723,100,0,2010),(724,100,0,2011),(725,100,0,2012),(726,100,0,2013),(727,101,22,2008),(728,101,4,2009),(729,101,0,2010),(730,101,0,2011),(731,101,0,2012),(732,101,0,2013),(733,102,33,2008),(734,102,2,2009),(735,102,6,2010),(736,102,0,2011),(737,102,0,2012),(738,102,0,2013),(739,103,32,2008),(740,103,52,2009),(741,103,67,2010),(742,103,0,2011),(743,103,0,2012),(744,103,5,2013),(745,104,68,2008),(746,104,69,2009),(747,104,37,2010),(748,104,0,2011),(749,104,0,2012),(750,104,0,2013),(751,NULL,0,2008),(752,NULL,0,2009),(753,NULL,0,2010),(754,NULL,40,2011),(755,NULL,53,2012),(756,NULL,101,2013),(757,105,0,2008),(758,105,0,2009),(759,105,0,2010),(760,105,0,2011),(761,105,0,2012),(762,105,0,2013),(763,NULL,0,2008),(764,NULL,0,2009),(765,NULL,0,2010),(766,NULL,0,2011),(767,NULL,0,2012),(768,NULL,0,2013),(769,NULL,0,2008),(770,NULL,0,2009),(771,NULL,0,2010),(772,NULL,92,2011),(773,NULL,76,2012),(774,NULL,65,2013),(775,106,0,2008),(776,106,0,2009),(777,106,0,2010),(778,106,0,2011),(779,106,0,2012),(780,106,16,2013),(781,107,0,2008),(782,107,0,2009),(783,107,0,2010),(784,107,0,2011),(785,107,0,2012),(786,107,0,2013),(787,108,0,2008),(788,108,14,2009),(789,108,32,2010),(790,108,0,2011),(791,108,0,2012),(792,108,0,2013),(793,109,0,2008),(794,109,0,2009),(795,109,0,2010),(796,109,0,2011),(797,109,0,2012),(798,109,0,2013),(799,110,0,2008),(800,110,0,2009),(801,110,0,2010),(802,110,0,2011),(803,110,0,2012),(804,110,0,2013),(805,111,0,2008),(806,111,0,2009),(807,111,0,2010),(808,111,0,2011),(809,111,0,2012),(810,111,0,2013),(811,112,0,2008),(812,112,0,2009),(813,112,0,2010),(814,112,57,2011),(815,112,118,2012),(816,112,151,2013),(817,113,0,2008),(818,113,0,2009),(819,113,0,2010),(820,113,0,2011),(821,113,0,2012),(822,113,0,2013),(823,114,0,2008),(824,114,0,2009),(825,114,0,2010),(826,114,0,2011),(827,114,0,2012),(828,114,0,2013),(829,115,0,2008),(830,115,0,2009),(831,115,0,2010),(832,115,0,2011),(833,115,0,2012),(834,115,0,2013),(835,116,0,2008),(836,116,0,2009),(837,116,0,2010),(838,116,0,2011),(839,116,0,2012),(840,116,0,2013),(841,117,10,2008),(842,117,39,2009),(843,117,0,2010),(844,117,0,2011),(845,117,0,2012),(846,117,0,2013),(847,NULL,0,2008),(848,NULL,0,2009),(849,NULL,0,2010),(850,NULL,0,2011),(851,NULL,0,2012),(852,NULL,0,2013),(853,118,0,2008),(854,118,0,2009),(855,118,0,2010),(856,118,88,2011),(857,118,96,2012),(858,118,110,2013),(859,119,0,2008),(860,119,0,2009),(861,119,0,2010),(862,119,0,2011),(863,119,0,2012),(864,119,0,2013),(865,120,0,2008),(866,120,0,2009),(867,120,0,2010),(868,120,0,2011),(869,120,0,2012),(870,120,0,2013),(871,121,64,2008),(872,121,58,2009),(873,121,85,2010),(874,121,0,2011),(875,121,0,2012),(876,121,0,2013),(877,122,0,2008),(878,122,0,2009),(879,122,0,2010),(880,122,0,2011),(881,122,0,2012),(882,122,0,2013),(883,123,0,2008),(884,123,0,2009),(885,123,0,2010),(886,123,0,2011),(887,123,0,2012),(888,123,0,2013),(889,NULL,0,2008),(890,NULL,0,2009),(891,NULL,0,2010),(892,NULL,0,2011),(893,NULL,0,2012),(894,NULL,0,2013),(895,124,57,2008),(896,124,81,2009),(897,124,63,2010),(898,124,0,2011),(899,124,0,2012),(900,124,0,2013),(901,NULL,59,2008),(902,NULL,93,2009),(903,NULL,79,2010),(904,NULL,98,2011),(905,NULL,103,2012),(906,NULL,87,2013),(907,125,0,2008),(908,125,0,2009),(909,125,0,2010),(910,125,0,2011),(911,125,0,2012),(912,125,0,2013),(913,126,92,2008),(914,126,98,2009),(915,126,88,2010),(916,126,139,2011),(917,126,124,2012),(918,126,212,2013),(919,127,61,2008),(920,127,54,2009),(921,127,62,2010),(922,127,0,2011),(923,127,0,2012),(924,127,0,2013),(925,NULL,0,2008),(926,NULL,0,2009),(927,NULL,0,2010),(928,NULL,59,2011),(929,NULL,61,2012),(930,NULL,106,2013),(931,128,0,2008),(932,128,0,2009),(933,128,0,2010),(934,128,0,2011),(935,128,2,2012),(936,128,0,2013),(937,129,0,2008),(938,129,0,2009),(939,129,0,2010),(940,129,91,2011),(941,129,60,2012),(942,129,0,2013),(943,130,35,2008),(944,130,11,2009),(945,130,0,2010),(946,130,0,2011),(947,130,0,2012),(948,130,0,2013),(949,131,0,2008),(950,131,0,2009),(951,131,0,2010),(952,131,0,2011),(953,131,0,2012),(954,131,0,2013),(955,132,52,2008),(956,132,77,2009),(957,132,48,2010),(958,132,0,2011),(959,132,0,2012),(960,132,0,2013),(961,NULL,75,2008),(962,NULL,62,2009),(963,NULL,62,2010),(964,NULL,390,2011),(965,NULL,385,2012),(966,NULL,314,2013),(967,133,0,2008),(968,133,0,2009),(969,133,0,2010),(970,133,0,2011),(971,133,0,2012),(972,133,0,2013),(973,NULL,0,2008),(974,NULL,0,2009),(975,NULL,0,2010),(976,NULL,0,2011),(977,NULL,0,2012),(978,NULL,0,2013),(979,134,0,2008),(980,134,0,2009),(981,134,0,2010),(982,134,0,2011),(983,134,0,2012),(984,134,0,2013),(985,135,7,2008),(986,135,74,2009),(987,135,43,2010),(988,135,0,2011),(989,135,0,2012),(990,135,0,2013),(991,NULL,0,2008),(992,NULL,0,2009),(993,NULL,0,2010),(994,NULL,36,2011),(995,NULL,46,2012),(996,NULL,37,2013),(997,136,0,2008),(998,136,0,2009),(999,136,0,2010),(1000,136,0,2011),(1001,136,0,2012),(1002,136,0,2013),(1003,137,0,2008),(1004,137,0,2009),(1005,137,0,2010),(1006,137,0,2011),(1007,137,0,2012),(1008,137,0,2013),(1009,138,0,2008),(1010,138,0,2009),(1011,138,0,2010),(1012,138,0,2011),(1013,138,0,2012),(1014,138,0,2013),(1015,NULL,0,2008),(1016,NULL,0,2009),(1017,NULL,0,2010),(1018,NULL,0,2011),(1019,NULL,0,2012),(1020,NULL,31,2013),(1021,139,0,2008),(1022,139,0,2009),(1023,139,0,2010),(1024,139,0,2011),(1025,139,0,2012),(1026,139,0,2013),(1027,140,0,2008),(1028,140,0,2009),(1029,140,0,2010),(1030,140,0,2011),(1031,140,0,2012),(1032,140,0,2013),(1033,141,0,2008),(1034,141,0,2009),(1035,141,0,2010),(1036,141,0,2011),(1037,141,0,2012),(1038,141,0,2013),(1039,142,0,2008),(1040,142,0,2009),(1041,142,0,2010),(1042,142,0,2011),(1043,142,0,2012),(1044,142,0,2013),(1045,143,48,2008),(1046,143,53,2009),(1047,143,52,2010),(1048,143,0,2011),(1049,143,0,2012),(1050,143,0,2013),(1051,144,0,2008),(1052,144,0,2009),(1053,144,0,2010),(1054,144,0,2011),(1055,144,0,2012),(1056,144,0,2013),(1057,145,0,2008),(1058,145,0,2009),(1059,145,0,2010),(1060,145,0,2011),(1061,145,0,2012),(1062,145,0,2013),(1063,146,0,2008),(1064,146,0,2009),(1065,146,0,2010),(1066,146,0,2011),(1067,146,0,2012),(1068,146,0,2013),(1069,NULL,39,2008),(1070,NULL,51,2009),(1071,NULL,61,2010),(1072,NULL,94,2011),(1073,NULL,84,2012),(1074,NULL,101,2013),(1075,NULL,97,2008),(1076,NULL,96,2009),(1077,NULL,115,2010),(1078,NULL,179,2011),(1079,NULL,207,2012),(1080,NULL,173,2013),(1081,147,0,2008),(1082,147,0,2009),(1083,147,0,2010),(1084,147,0,2011),(1085,147,0,2012),(1086,147,0,2013),(1087,148,0,2008),(1088,148,0,2009),(1089,148,0,2010),(1090,148,0,2011),(1091,148,0,2012),(1092,148,0,2013),(1093,149,0,2008),(1094,149,0,2009),(1095,149,0,2010),(1096,149,0,2011),(1097,149,0,2012),(1098,149,0,2013),(1099,NULL,12,2008),(1100,NULL,20,2009),(1101,NULL,10,2010),(1102,NULL,9,2011),(1103,NULL,0,2012),(1104,NULL,0,2013),(1105,150,22,2008),(1106,150,24,2009),(1107,150,27,2010),(1108,150,0,2011),(1109,150,0,2012),(1110,150,0,2013),(1111,151,19,2008),(1112,151,23,2009),(1113,151,41,2010),(1114,151,0,2011),(1115,151,0,2012),(1116,151,0,2013),(1117,152,0,2008),(1118,152,0,2009),(1119,152,0,2010),(1120,152,0,2011),(1121,152,0,2012),(1122,152,0,2013),(1123,153,0,2008),(1124,153,0,2009),(1125,153,0,2010),(1126,153,0,2011),(1127,153,0,2012),(1128,153,0,2013),(1129,154,0,2008),(1130,154,0,2009),(1131,154,0,2010),(1132,154,0,2011),(1133,154,0,2012),(1134,154,0,2013),(1135,155,0,2008),(1136,155,0,2009),(1137,155,0,2010),(1138,155,0,2011),(1139,155,0,2012),(1140,155,0,2013),(1141,156,0,2008),(1142,156,0,2009),(1143,156,0,2010),(1144,156,0,2011),(1145,156,0,2012),(1146,156,0,2013);

/*Table structure for table `mkkur_prodi` */

DROP TABLE IF EXISTS `mkkur_prodi`;

CREATE TABLE `mkkur_prodi` (
  `mkkprod_id` int(11) NOT NULL AUTO_INCREMENT,
  `mkkprod_mkkur_id` int(11) DEFAULT NULL,
  `mkkprod_prodi_id` int(11) DEFAULT NULL,
  `mkkprod_related_id` int(11) DEFAULT '0' COMMENT 'if kelas gabung, isi dgn mkkprod_id yg udah ada before',
  `mkkprod_porsi_kelas` int(11) DEFAULT '1' COMMENT 'porsi kelas dari jumlah total peserta makul',
  PRIMARY KEY (`mkkprod_id`),
  UNIQUE KEY `mkprodi_key` (`mkkprod_mkkur_id`,`mkkprod_prodi_id`),
  KEY `FK_mkkur_prodi_FK2` (`mkkprod_prodi_id`),
  CONSTRAINT `FK_mkkur_prodi` FOREIGN KEY (`mkkprod_mkkur_id`) REFERENCES `mata_kuliah_kurikulum` (`mkkur_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_mkkur_prodi_FK2` FOREIGN KEY (`mkkprod_prodi_id`) REFERENCES `program_studi` (`prodi_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=193 DEFAULT CHARSET=latin1;

/*Data for the table `mkkur_prodi` */

insert  into `mkkur_prodi`(`mkkprod_id`,`mkkprod_mkkur_id`,`mkkprod_prodi_id`,`mkkprod_related_id`,`mkkprod_porsi_kelas`) values (1,2,1,NULL,1),(2,3,1,NULL,1),(3,4,6,NULL,1),(4,5,3,NULL,1),(5,6,3,NULL,1),(6,7,3,NULL,1),(7,8,1,NULL,1),(8,9,5,NULL,1),(9,10,5,NULL,1),(10,11,2,NULL,1),(11,12,4,NULL,1),(12,13,7,NULL,1),(13,14,4,NULL,1),(14,15,3,NULL,1),(15,16,5,NULL,1),(16,17,2,NULL,1),(17,18,6,NULL,1),(18,19,2,NULL,1),(19,20,1,NULL,1),(20,21,4,NULL,1),(21,22,7,NULL,1),(22,23,5,NULL,1),(23,25,4,NULL,1),(24,26,5,NULL,1),(25,27,1,NULL,1),(26,28,7,NULL,1),(27,29,5,NULL,1),(28,30,2,NULL,1),(29,31,6,NULL,1),(30,32,2,NULL,1),(31,33,2,NULL,1),(32,34,5,NULL,1),(33,35,2,NULL,1),(34,36,1,NULL,1),(35,37,4,NULL,1),(36,38,1,NULL,1),(37,39,6,NULL,1),(38,40,6,NULL,1),(39,41,6,NULL,1),(40,42,7,NULL,1),(41,43,6,NULL,1),(42,44,6,NULL,1),(43,45,6,NULL,1),(44,46,6,NULL,1),(45,47,6,NULL,1),(46,48,6,NULL,1),(47,49,6,NULL,1),(48,50,6,NULL,1),(49,51,3,NULL,1),(50,52,6,NULL,1),(51,53,7,NULL,1),(52,54,7,NULL,1),(53,55,5,NULL,1),(54,56,5,NULL,1),(55,57,7,NULL,1),(56,58,3,NULL,1),(57,59,6,NULL,1),(58,60,3,NULL,1),(59,61,7,NULL,1),(60,62,1,NULL,1),(61,63,2,NULL,1),(62,64,5,NULL,1),(63,65,2,NULL,1),(64,66,1,NULL,1),(65,67,5,NULL,1),(66,68,1,NULL,1),(67,69,3,NULL,1),(68,70,3,NULL,1),(69,71,3,NULL,1),(70,72,7,NULL,1),(71,73,6,NULL,1),(72,74,3,NULL,1),(73,75,4,NULL,1),(74,76,1,NULL,1),(75,77,5,NULL,1),(76,78,5,NULL,1),(77,79,2,NULL,1),(78,80,5,NULL,1),(79,81,5,NULL,1),(80,82,5,NULL,1),(81,83,5,NULL,1),(82,84,5,NULL,1),(83,86,5,NULL,1),(84,87,5,NULL,1),(85,88,5,NULL,1),(86,89,5,NULL,1),(87,90,5,NULL,1),(88,91,5,NULL,1),(89,92,5,NULL,1),(90,93,5,NULL,1),(91,94,5,NULL,1),(92,95,5,NULL,1),(93,96,4,NULL,1),(94,97,6,NULL,1),(95,99,2,NULL,1),(96,100,6,NULL,1),(97,101,1,NULL,1),(98,102,7,NULL,1),(99,103,1,NULL,1),(100,104,7,NULL,1),(101,105,4,NULL,1),(102,106,1,NULL,1),(103,107,6,NULL,1),(104,108,3,NULL,1),(105,110,5,NULL,1),(106,111,5,NULL,1),(107,112,6,NULL,1),(108,113,7,NULL,1),(109,114,6,NULL,1),(110,115,6,NULL,1),(111,116,5,NULL,1),(112,117,2,NULL,1),(113,118,2,NULL,1),(114,119,6,NULL,1),(115,120,7,NULL,1),(116,121,1,NULL,1),(117,122,6,NULL,1),(118,123,7,NULL,1),(119,124,4,NULL,1),(120,125,4,NULL,1),(121,126,4,NULL,1),(122,127,6,NULL,1),(123,128,2,NULL,1),(124,129,2,NULL,1),(125,130,1,NULL,1),(126,131,5,NULL,1),(127,132,6,NULL,1),(128,133,1,NULL,1),(129,134,1,NULL,1),(130,135,2,NULL,1),(131,136,3,NULL,1),(132,137,4,NULL,1),(133,138,3,NULL,1),(134,139,3,NULL,1),(135,140,3,NULL,1),(136,141,4,NULL,1),(137,142,4,NULL,1),(138,143,3,NULL,1),(139,144,4,NULL,1),(140,145,3,NULL,1),(141,146,4,NULL,1),(142,147,4,NULL,1),(143,148,3,NULL,1),(144,149,4,NULL,1),(145,150,4,NULL,1),(146,151,3,NULL,1),(147,152,6,NULL,1),(148,153,6,NULL,1),(149,154,7,NULL,1),(150,155,2,NULL,1),(151,156,5,NULL,1),(152,157,3,NULL,1),(153,158,2,NULL,1),(154,159,1,NULL,1),(155,160,4,NULL,1),(156,161,1,NULL,1),(157,162,7,NULL,1),(158,163,4,NULL,1),(159,164,7,NULL,1),(160,165,3,NULL,1),(161,166,7,NULL,1),(162,167,2,NULL,1),(163,168,3,NULL,1),(164,169,1,NULL,1),(165,170,2,NULL,1),(166,171,6,NULL,1),(167,172,2,NULL,1),(168,173,2,NULL,1),(169,174,2,NULL,1),(170,175,5,NULL,1),(171,176,1,NULL,1),(172,177,1,NULL,1),(173,178,1,NULL,1),(174,179,1,NULL,1),(175,180,5,NULL,1),(176,181,7,NULL,1),(177,183,7,NULL,1),(178,184,1,NULL,1),(179,185,3,NULL,1),(180,186,6,NULL,1),(181,187,3,NULL,1),(182,188,3,NULL,1),(183,189,5,NULL,1),(184,190,2,NULL,1),(185,24,5,NULL,0),(186,85,5,NULL,0),(187,98,6,NULL,0),(188,109,3,NULL,0),(189,182,1,NULL,0),(191,165,4,160,1),(192,165,1,191,1);

/*Table structure for table `program_studi` */

DROP TABLE IF EXISTS `program_studi`;

CREATE TABLE `program_studi` (
  `prodi_id` int(11) NOT NULL AUTO_INCREMENT,
  `prodi_kode` varchar(20) DEFAULT NULL,
  `prodi_nama` varchar(200) DEFAULT NULL,
  `prodi_prefix_mk` varchar(4) DEFAULT NULL COMMENT 'prefix makul',
  PRIMARY KEY (`prodi_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `program_studi` */

insert  into `program_studi`(`prodi_id`,`prodi_kode`,`prodi_nama`,`prodi_prefix_mk`) values (1,'ILKOM','Ilmu Komputer','MIK'),(2,'ELINS','Elektronika dan Instrumentasi','MIE'),(3,'MAT','Matematika','MMM'),(4,'STAT','Statistika','MMS'),(5,'KIM','Kimia','MKK'),(6,'FIS','Fisika','MFF'),(7,'GEOFIS','Geofisika','MFG');

/*Table structure for table `ruang` */

DROP TABLE IF EXISTS `ruang`;

CREATE TABLE `ruang` (
  `ru_id` int(11) NOT NULL AUTO_INCREMENT,
  `ru_kode` varchar(6) DEFAULT NULL,
  `ru_nama` varchar(20) DEFAULT NULL,
  `ru_kapasitas` int(6) DEFAULT NULL,
  `ru_is_cadangan` tinyint(1) DEFAULT '0' COMMENT '1=is_cadangan',
  PRIMARY KEY (`ru_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

/*Data for the table `ruang` */

insert  into `ruang`(`ru_id`,`ru_kode`,`ru_nama`,`ru_kapasitas`,`ru_is_cadangan`) values (1,'S2.01','S2.01',100,0),(2,'S2.02','S2.02',100,0),(3,'M2.13','M2.13',50,0),(4,'S2.03','S2.03',100,0),(5,'K1','K1',90,0),(6,'S2.505','S2.505',100,0),(7,'S2.506','S2.506',100,0),(8,'M2.10','M2.10',60,0),(9,'M2.14','M2.14',50,0),(10,'K6','K6',50,0),(11,'M2.09','M2.09',100,0),(12,'G3','G3',60,0),(13,'S2.05','S2.05',50,0),(14,'M2.12','M2.12',100,0),(15,'U2.02','U2.02',100,0),(16,'T2.01','T2.01',90,0),(17,'U2.01','U2.01',100,0),(18,'U2.06','U2.06',60,0),(19,'S2.04','S2.04',50,0),(20,'U2.03','U2.03',100,0),(21,'U2.04','U2.04',100,0),(22,'U2.05','U2.05',30,1),(23,'S2.06','S2.06',30,1),(24,'S2.07','S2.07',30,1),(25,'S2.08','S2.08',60,1),(26,'Lab.Da','Lab.Das.',90,1),(27,'S2.507','S2.507',100,1),(28,'Lab.AI','Lab.AI',30,1);

/*Table structure for table `ruang_prodi` */

DROP TABLE IF EXISTS `ruang_prodi`;

CREATE TABLE `ruang_prodi` (
  `ruprd_id` int(11) NOT NULL AUTO_INCREMENT,
  `ruprd_ru_id` int(11) DEFAULT NULL,
  `ruprd_prodi_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`ruprd_id`),
  KEY `ru_prodi_key` (`ruprd_ru_id`,`ruprd_prodi_id`),
  KEY `FK_ruang_prodi_prodi_fk` (`ruprd_prodi_id`),
  CONSTRAINT `FK_ruang_prodi` FOREIGN KEY (`ruprd_ru_id`) REFERENCES `ruang` (`ru_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_ruang_prodi_prodi_fk` FOREIGN KEY (`ruprd_prodi_id`) REFERENCES `program_studi` (`prodi_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

/*Data for the table `ruang_prodi` */

insert  into `ruang_prodi`(`ruprd_id`,`ruprd_ru_id`,`ruprd_prodi_id`) values (36,1,4),(15,2,4),(10,3,3),(16,3,4),(11,4,3),(12,5,3),(18,6,5),(19,7,5),(20,8,5),(21,9,5),(22,10,5),(28,11,7),(29,12,7),(24,13,6),(30,13,7),(25,14,6),(26,15,6),(1,16,1),(2,17,1),(3,18,1),(4,19,1),(6,19,2),(7,20,2),(8,21,2),(13,22,3),(27,23,6),(17,24,4),(5,25,1),(9,25,2),(23,26,5),(31,26,7);

/*Table structure for table `waktu` */

DROP TABLE IF EXISTS `waktu`;

CREATE TABLE `waktu` (
  `waktu_id` int(11) NOT NULL AUTO_INCREMENT,
  `waktu_hari` varchar(20) DEFAULT NULL,
  `waktu_jam_mulai` time DEFAULT NULL,
  `waktu_jam_selesai` time DEFAULT NULL,
  `waktu_is_kuliah` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`waktu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=latin1;

/*Data for the table `waktu` */

insert  into `waktu`(`waktu_id`,`waktu_hari`,`waktu_jam_mulai`,`waktu_jam_selesai`,`waktu_is_kuliah`) values (1,'senin','07:30:00','08:20:00',1),(2,'senin','08:30:00','09:20:00',1),(3,'senin','09:30:00','10:20:00',1),(4,'senin','10:30:00','11:20:00',1),(5,'senin','11:30:00','12:20:00',1),(6,'senin','12:30:00','13:20:00',1),(7,'senin','13:30:00','14:20:00',1),(8,'senin','14:30:00','15:20:00',1),(9,'senin','15:30:00','16:20:00',1),(10,'senin','16:30:00','17:20:00',1),(12,'selasa','07:30:00','08:20:00',1),(13,'selasa','08:30:00','09:20:00',1),(14,'selasa','09:30:00','10:20:00',1),(15,'selasa','10:30:00','11:20:00',1),(16,'selasa','11:30:00','12:20:00',1),(17,'selasa','12:30:00','13:20:00',1),(18,'selasa','13:30:00','14:20:00',1),(19,'selasa','14:30:00','15:20:00',1),(20,'selasa','15:30:00','16:20:00',1),(21,'selasa','16:30:00','17:20:00',1),(22,'rabu','07:30:00','08:20:00',1),(23,'rabu','08:30:00','09:20:00',1),(24,'rabu','09:30:00','10:20:00',1),(25,'rabu','10:30:00','11:20:00',1),(26,'rabu','11:30:00','12:20:00',1),(27,'rabu','12:30:00','13:20:00',1),(28,'rabu','13:30:00','14:20:00',1),(29,'rabu','14:30:00','15:20:00',1),(30,'rabu','15:30:00','16:20:00',1),(31,'rabu','16:30:00','17:20:00',1),(32,'kamis','07:30:00','08:20:00',1),(33,'kamis','08:30:00','09:20:00',1),(34,'kamis','09:30:00','10:20:00',1),(35,'kamis','10:30:00','11:20:00',1),(36,'kamis','11:30:00','12:20:00',1),(37,'kamis','12:30:00','13:20:00',1),(38,'kamis','13:30:00','14:20:00',1),(39,'kamis','14:30:00','15:20:00',1),(40,'kamis','15:30:00','16:20:00',1),(41,'kamis','16:30:00','17:20:00',1),(42,'jumat','07:30:00','08:20:00',1),(43,'jumat','08:30:00','09:20:00',1),(44,'jumat','09:30:00','10:20:00',1),(45,'jumat','10:30:00','11:20:00',1),(48,'jumat','13:30:00','14:20:00',1),(49,'jumat','14:30:00','15:20:00',1),(50,'jumat','15:30:00','16:20:00',1),(51,'jumat','16:30:00','17:20:00',1);

/* Procedure structure for procedure `get_prodi_by_makulid` */

/*!50003 DROP PROCEDURE IF EXISTS  `get_prodi_by_makulid` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `get_prodi_by_makulid`(input_id INT UNSIGNED)
BEGIN
	DECLARE x INT;
	DECLARE str VARCHAR(255);
	SET x = -5;
	SET str = '';

	loop_label: LOOP
	IF x > 0 THEN
	LEAVE loop_label;
	END IF;
	SET str = CONCAT(str,x,',');
	SET x = x + 1;
	ITERATE loop_label;
	END LOOP;

	SELECT str;
    END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
