/*
SQLyog Ultimate v8.8 
MySQL - 5.6.17 : Database - gluse_init_db
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
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

insert  into `config`(`conf_id`,`conf_name`,`conf_value`,`conf_tipe`) values (1,'periode_tahun_prediksi','6',NULL),(2,'item_per_page','100',NULL),(3,'semester_aktif','genap',NULL),(4,'min_persen_kelas','75',NULL);

/*Table structure for table `dosen` */

DROP TABLE IF EXISTS `dosen`;

CREATE TABLE `dosen` (
  `dsn_id` int(11) NOT NULL AUTO_INCREMENT,
  `dsn_nip` varchar(20) DEFAULT NULL,
  `dsn_nama` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`dsn_id`),
  UNIQUE KEY `dsn_nama_key` (`dsn_nama`)
) ENGINE=InnoDB AUTO_INCREMENT=221 DEFAULT CHARSET=latin1;

/*Data for the table `dosen` */

insert  into `dosen`(`dsn_id`,`dsn_nip`,`dsn_nama`) values (1,NULL,'ABDUL RAHMAN SIREGAR, S.Si., M.Biotech\r'),(2,NULL,'ABDUL ROUF, Drs., M.I.Kom.\r'),(3,NULL,'ABDURAKHMAN, S.Si., M.Si., Dr.\r'),(4,NULL,'ADE ANGGRAINI, S.Si., M.Si.\r'),(5,NULL,'ADHITASARI SURATMAN, S.Si., M.Si., Dr.rer.nat.\r'),(6,NULL,'ADHITYA RONNIE EFFENDIE, M.Si., M.Sc., Dr.\r'),(7,NULL,'AFIF RAKHMAN, S.Si., M.Si.\r'),(8,NULL,'AGFIANTO EKO PUTRA, M.Si., Dr.\r'),(9,NULL,'AGUNG BAMBANG SETIO UTOMO, Dr., Prof.\r'),(10,NULL,'AGUNG DWI SAPUTRO, A.Md.\r'),(11,NULL,'AGUS HARJOKO, Drs., M.Sc., Ph.D.\r'),(12,NULL,'AGUS KUNCAKA, Dr., DEA.\r'),(13,NULL,'AGUS SIHABUDDIN, ., M.Kom.\r'),(14,NULL,'AHMAD ASHARI, Drs., M.I.Kom., Dr.tech.\r'),(15,NULL,'AHMAD KUSUMA ATMAJA, S.Si.\r'),(16,NULL,'AHMAD TAWFIEQURAHMAN YULIANSYAH, ST., MT.,\r'),(17,NULL,'AINA MUSDHOLIFAH, M.Sc., Dr.\r'),(18,NULL,'AKHMAD SYOUFIAN, S.Si., Ph.D.,\r'),(19,NULL,'ALROSYID, S.Si.\r'),(20,NULL,'ANDI DHARMAWAN, S.Si., M.Cs.\r'),(21,NULL,'ANIFUDDIN AZIS, ., M.Kom.\r'),(22,NULL,'ANINDITA SURYARASMI, S.Kom., M.Cs.\r'),(23,NULL,'ANNY KARTIKA SARI, M.Sc.,Dr\r'),(24,NULL,'APRI MULYANI, S.S,\r'),(25,NULL,'ARI DWI NUGRAHENI, S.Si.\r'),(26,NULL,'ARI SETIAWAN, Drs., M.Si., Dr.Ing.\r'),(27,NULL,'ARI SUPARWANTO, M.Si., Dr.rer.nat.\r'),(28,NULL,'ARIEF HERMANTO, S.U., M.Sc., Dr.\r'),(29,NULL,'ARIES BAGUS SASONGKO, S.Si., M.Biotech,\r'),(30,NULL,'ARIESTA MARTININGTYAS H, S.Si.,\r'),(31,NULL,'ARIF NURWIDYANTORO, S.Kom, M.Cs.\r'),(32,NULL,'ATOK ZULIJANTO, S.Si., M.Si., Ph.D.\r'),(33,NULL,'AZHARI, M.T., Dr.\r'),(34,NULL,'BAKHTIAR ALLDINO ARDI SUMBODO, S.Si.\r'),(35,NULL,'BAMBANG NURCAHYO PRASTOWO, Drs., M.Sc.\r'),(36,NULL,'BAMBANG PURWONO, Drs., M.Sc., Ph.D.\r'),(37,NULL,'BAMBANG RUSDIARSO, Dr., DEA., Prof.\r'),(38,NULL,'BAMBANG SETIAJI, Dr., Prof.\r'),(39,NULL,'BAMBANG SOEDIJONO, Dr., Prof.\r'),(40,NULL,'BILAL MA\'RUF, S.T., M.T.\r'),(41,NULL,'BUDI EKA NURCAHYA, Drs., M.Si.\r'),(42,NULL,'BUDI SUMANTO, S.Si.\r'),(43,NULL,'BUDI SURODJO, M.Si., Dr.\r'),(44,NULL,'CATUR ATMAJI, S.Si.\r'),(45,NULL,'CHAIRIL ANWAR, Dr.\r'),(46,NULL,'CHRISTIANA RINI INDRATI, M.Si., Dr.\r'),(47,NULL,'CISYA DEWANTARA NUGRAHA, M.A.\r'),(48,NULL,'DANANG LELONO, S.Si., M.T.\r'),(49,NULL,'DANARDONO, Drs., MPH., Ph.D.\r'),(50,NULL,'DEDI ROSADI, M.Sc., Dr.rer.nat., Prof.\r'),(51,NULL,'DIAN BUDI SANTOSO, SKM\r'),(52,NULL,'DWI SISWANTA, M.Hum.,\r'),(53,NULL,'DWI SISWANTO, Drs., M.Eng., Ph.D.\r'),(54,NULL,'DWI UMI SISWANTI, S.Si., M.Sc.,\r'),(55,NULL,'EDDY KRISTIYONO, A.Md.PK, SKM\r'),(56,NULL,'EDI SUHARYADI, S.Si., M.Eng., Dr.Eng.\r'),(57,NULL,'EDI WINARKO, Drs., M.Sc., Ph.D.\r'),(58,NULL,'Eko Agus Suyono, S.Si., M.App.Sc.,\r'),(59,NULL,'EKO SRI KUNARTI, Dra., M.Si., Ph.D.\r'),(60,NULL,'EKO SUGIHARTO, Dr., DEA.\r'),(61,NULL,'EKO TRI SULISTYANI, Dra., M.Sc.\r'),(62,NULL,'ELIDA LAILIA ISTIQOMAH, S.Si., M.Sc.\r'),(63,NULL,'ENDANG TRI WAHYUNI, M.S., Dr., Prof.\r'),(64,NULL,'FAHRUDIN NUGROHO, S.Si., M.Si.\r'),(65,NULL,'FAIZAH, S.Kom., M.Sc.\r'),(66,NULL,'FAJAR ADI KUSUMO, S.Si., M.Si., Dr.\r'),(67,NULL,'FARID ISHARTOMO, S.Si.\r'),(68,NULL,'FITRI DAMAYANTI BERUTU, M.M.\r'),(69,NULL,'GEDE BAYU SUPARTA, Drs., M.S., Ph.D.\r'),(70,NULL,'GP. DALIJO, Drs., Dipl.Comp.\r'),(71,NULL,'GUNARDI, M.Si., Dr.\r'),(72,NULL,'GUNTUR MARUTO, Drs., S.U., Dr.\r'),(73,NULL,'HAMDANI MURDA, S.Si.,M.SE\r'),(74,NULL,'HARDJONO SASTROHAMIDJOJO, Dr., Prof.\r'),(75,NULL,'HARNO DWI PRANOWO, M.S., Dr.rer.nat., Prof.\r'),(76,NULL,'HARSOJO, S.U., M.Sc., Dr.\r'),(77,NULL,'HELIDA NURCAHAYANI, SST., M.Si.\r'),(78,NULL,'HERNI UTAMI, S.Si., M.Si.\r'),(79,NULL,'HERNI UTAMI, S.Si., M.Si.,\r'),(80,NULL,'I GEDE MUJIYATNA, ., M.Kom.\r'),(81,NULL,'IBNU MARDIYOKO, M.M.\r'),(82,NULL,'IIP IZUL FALAH, Dr., Prof.\r'),(83,NULL,'IKA CANDRADEWI, S.Si.\r'),(84,NULL,'IKHSAN SETIAWAN, S.Si., M.Si.,\r'),(85,NULL,'IMAM SOLEKHUDIN, S.Si., M.Si., Ph.D.\r'),(86,NULL,'IMAM SUYANTO, Drs., M.Si.\r'),(87,NULL,'IMAM SUYANTO, Drs., M.Si.,\r'),(88,NULL,'IMAN SANTOSO, S.Si., M.Sc., Ph.D\r'),(89,NULL,'INDAH EMILIA WIJAYANTI, M.Si., Dr.rer.nat.\r'),(90,NULL,'INDARSIH, S.Si., M.Si.\r'),(91,NULL,'INDRIANA KARTINI, S.Si., M.Si., Ph.D.\r'),(92,NULL,'IQMAL TAHIR, Drs., M.Si.\r'),(93,NULL,'IRWAN ENDRAYANTO A., S.Si., M.Sc., Dr.\r'),(94,NULL,'ISNAN NUR RIFAI, S.Si.\r'),(95,NULL,'IVA ARIANI, DR\r'),(96,NULL,'JANOE HENDARTO, Drs., M.I.Kom.\r'),(97,NULL,'JUMINA, Drs., Ph.D., Prof.\r'),(98,NULL,'KARLINA DENISTIA, S.S., M.A.\r'),(99,NULL,'KARNA WIJAYA, Drs., M.Eng., Dr.rer.nat., Prof.\r'),(100,NULL,'KARTINI, Dra., M.Hum.\r'),(101,NULL,'KARYONO, S.U., Dr., Prof.\r'),(102,NULL,'KHABIB MUSTOFA, M.Kom., Dr.tech.\r'),(103,NULL,'KIRBANI SRI BROTOPUSPITO, Dr., Prof.\r'),(104,NULL,'KUSMINARTO, Dr., Prof.\r'),(105,NULL,'KUWAT TRIYANA, M.Si., Dr. Eng.\r'),(106,NULL,'LINA ARYATI, Dra., M.S., Dr.rer.nat.\r'),(107,NULL,'Lisna Hidayati, S.Si., M.Biotech.\r'),(108,NULL,'LUKMAN HERYAWAN, S.T., M.T.\r'),(109,NULL,'M. FARCHANI ROSYID, Drs., M.Si., Dr.rer.nat.\r'),(110,NULL,'MARDANI, S.E., M.T.\r'),(111,NULL,'MARDHANI RIASETIAWAN, M.T.\r'),(112,NULL,'MARDOTO, KOL. SUS.\r'),(113,NULL,'MASIRAN, M.Si.\r'),(114,NULL,'MEDI, Drs., M.Kom.\r'),(115,NULL,'MHD. REZA M.I. PULUNGAN, S.Si., M.Sc., Dr.Ing.\r'),(116,NULL,'MIRZA SATRIAWAN, S.Si., M.Si., Ph.D.\r'),(117,NULL,'MITRAYANA, S.Si., M.Si., Dr.\r'),(118,NULL,'MOCHAMMAD NUKMAN, S.T., M.Sc.\r'),(119,NULL,'MOCHAMMAD TARI, Drs., M.Si.\r'),(120,NULL,'MOCHAMMAD UTORO YAHYA, Dr., Prof.\r'),(121,NULL,'MOH. ALI JOKO WASONO, M.S., Dr.\r'),(122,NULL,'MUDASIR, Drs., M.Eng., Ph.D., Prof.\r'),(123,NULL,'MUH. FAKHURRIFQI, S.Kom., M.Cs.\r'),(124,NULL,'MUHAMMAD DARWIS UMAR, S.SI.M.SI\r'),(125,NULL,'NARSITO, Dr., Prof.\r'),(126,NULL,'NIA GELLA AUGOESTIEN, S.Si.\r'),(127,NULL,'NIKEN ANGRAENI, S.S.\r'),(128,NULL,'NUR ENDAH NUGRAHENI, S.S.,M.S\r'),(129,NULL,'NUR ROKHMAN, S.Si., M.Kom.\r'),(130,NULL,'NURUL HIDAYAT A., S.Si., M.Si., Dr.rer.nat.\r'),(131,NULL,'NURYATI, MPH\r'),(132,NULL,'NURYONO, Drs., M.S., Dr.rer.nat., Prof.\r'),(133,NULL,'PANGGIH BASUKI, Drs., M.Si.\r'),(134,NULL,'PEKIK NURWANTORO, Drs., M.S., Ph.D.\r'),(135,NULL,'PRIATMOKO, Drs., M.S.\r'),(136,NULL,'PRIJONO NUGROHO, MSP., Ph.D.\r'),(137,NULL,'PRODI\r'),(138,NULL,'PUJIHARTO, Dr., S.S., M.Hum.\r'),(139,NULL,'RADEN SANJOYO, A.Md., S.Kom.\r'),(140,NULL,'RADEN SUMIHARTO, S.Si., M.Kom.\r'),(141,NULL,'RAKHMAT SOLEH, S.S, M.Hum.\r'),(142,NULL,'RATNA UDAYA WIDODO, Dra., MLS.\r'),(143,NULL,'RAWI MIHARTI, Dra., MPH.\r'),(144,NULL,'RESPATI TRI SWASONO, S.Si., M.Phil., Ph.D.\r'),(145,NULL,'RETANTYO WARDOYO, Drs., M.Sc., Ph.D.\r'),(146,NULL,'RIA ARMUNANTO, S.Si., M.Si., Dr.rer.nat.\r'),(147,NULL,'RIANTI SISWI UTAMI, S.Si.\r'),(148,NULL,'RINTO ANUGRAHA NQZ, S.Si., M.Si., Ph.D.\r'),(149,NULL,'ROBBY NOOR CAHYONO, S.Si., M.Sc.\r'),(150,NULL,'ROCHIM BAKTI CAHYONO, ST., M.Sc.\r'),(151,NULL,'ROTO, Drs., M.Eng., Ph.D.\r'),(152,NULL,'SABIRIN MATSJEH, Dr., Prof.\r'),(153,NULL,'SALMAH, M.Si., Dr.\r'),(154,NULL,'SARDJONO, Drs., S.U.\r'),(155,NULL,'SARI DARMASIWI, S.Si., M. Biotech.,\r'),(156,NULL,'SARI LESTARI, Dra., M.A.\r'),(157,NULL,'SAVITRI CITRA BUDI, MPH\r'),(158,NULL,'SETIADJI, Prof.\r'),(159,NULL,'SIDIK BUDI W., LETKOL SUS., S.Si., M.Si.\r'),(160,NULL,'SIGIT PRIYANTA, S.Si., M.Kom.\r'),(161,NULL,'SISMANTO, M.Si., Dr., Prof.\r'),(162,NULL,'SITI SUSANTI, Dra., S.U.\r'),(163,NULL,'SLAMET SUTRISNO, Drs.,M.Si\r'),(164,NULL,'SRI HARTATI, Dra., M.Sc., Ph.D.\r'),(165,NULL,'SRI HARYATMI, M.Sc., Dr., Prof.\r'),(166,NULL,'SRI JUARI SANTOSA, Drs., M.Eng., Ph.D., Prof.\r'),(167,NULL,'SRI MULYANA, Drs., M.Kom.\r'),(168,NULL,'SRI WAHYUNI, S.U., Dr., Prof.\r'),(169,NULL,'SRI WIDAYANTI, Dra., M.S.\r'),(170,NULL,'SUBANAR, Drs., Ph.D., Prof.\r'),(171,NULL,'SUDARYANTO, Drs., M.Hum.\r'),(172,NULL,'SUDIARTONO, Drs., M.S.\r'),(173,NULL,'SUGENG TRIONO, S.Si., M.Si.\r'),(174,NULL,'SUGENG, A.Md.\r'),(175,NULL,'SUHARTO, Dr.\r'),(176,NULL,'SUMARDI, M.Si., Dr.\r'),(177,NULL,'SUMARNI DWI W., Dr., M.Kes.\r'),(178,NULL,'SUMARYO, Ir., M.Si\r'),(179,NULL,'SUNARTA, Drs., M.S.\r'),(180,NULL,'SUPAMA, M.Si., Dr., Prof.\r'),(181,NULL,'SUPRAPTO, Drs., M.Ikom.\r'),(182,NULL,'SURYO NUGROHO MARKUS, S.E., MPH.\r'),(183,NULL,'SUTARNO, M.Si., Dr.\r'),(184,NULL,'SUTOPO, S.Si., M.Si.\r'),(185,NULL,'SUWONO, dr.\r'),(186,NULL,'SUYANTA, M.Si., Dr.\r'),(187,NULL,'SUYOKO, KOL., ADM.\r'),(188,NULL,'SYAMSUL BARRY, S.Sn., M.Hum.\r'),(189,NULL,'TARSISIUS ARIS SUNANTYO, Ir., Dr.\r'),(190,NULL,'TAUFIK ABDILLAH NATSIR, S.Si., M.Sc.\r'),(191,NULL,'TERIA ANARGHATI, S.S.,MA\r'),(192,NULL,'TOTO SUDARGO, Dr.,\r'),(193,NULL,'TRI JOKO RAHARJO, S.Si., M.Si., Ph.D.\r'),(194,NULL,'TRI KUNTORO PRIYAMBODO, Dr., M.Sc.\r'),(195,NULL,'TRI WAHYU SUPARDI, S.Si.\r'),(196,NULL,'TRIYOGATAMA WAHYU WIDODO, S.Kom., M.Kom.\r'),(197,NULL,'TRIYONO, S.U., Dr., Prof.\r'),(198,NULL,'TUTIK DWI WAHYUNINGSIH, Dra., M.Si., Ph.D.\r'),(199,NULL,'VEMMIE NASTITI LESTARI, M.Sc.\r'),(200,NULL,'WAGINI R., Drs., M.S.\r'),(201,NULL,'WAHYUDI ISTIONO, dr., M.Kes.,\r'),(202,NULL,'WAHYUDI, M.S., Dr.\r'),(203,NULL,'WALUYO, Drs., M.Sc., Ph.D.\r'),(204,NULL,'WEGA TRISUNARYANTI, M.S., Ph.D., Prof.\r'),(205,NULL,'WIDHI SULISTYO, S.Kom.\r'),(206,NULL,'WIDODO PRIJODIPRODJO, M.Sc.\r'),(207,NULL,'WINARTO HARYADI, M.Si., Dr.\r'),(208,NULL,'WIWIT SURYANTO, S.Si., M.Si., Dr.rer.nat.\r'),(209,NULL,'WORO ANINDITO SRI TUNJUNG, M.Sc., Ph.D.\r'),(210,NULL,'YATEMAN ARRYANTO, Dr.\r'),(211,NULL,'YENI SUSANTI, S.Si., M.Si.\r'),(212,NULL,'YOHANES SUYANTO, Drs., M.I.Kom.\r'),(213,NULL,'YULIANINGSIH RISWAN, M.A.\r'),(214,NULL,'YUNITA WULAN SARI, S.Si., M.Sc.\r'),(215,NULL,'YUSRIL YUSUF, S.Si., M.Eng., Dr.Eng.\r'),(216,NULL,'YUSRON FUADI, M.Sn.\r'),(217,NULL,'YUSUF, Drs., M.A. Math.\r'),(218,NULL,'ZULAELA, Drs., Dipl.Med.Stats., M.Si.\r'),(219,NULL,'NUR KHUSNUSSA\'ADAH, S.Si., M.Sc.'),(220,NULL,'DIAH JUNIA EKSI PALUPI, Dra., S.U.');

/*Table structure for table `dosen_kelas` */

DROP TABLE IF EXISTS `dosen_kelas`;

CREATE TABLE `dosen_kelas` (
  `dsnkls_id` int(11) NOT NULL AUTO_INCREMENT,
  `dsnkls_dsn_id` int(11) DEFAULT NULL,
  `dsnkls_kls_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`dsnkls_id`),
  KEY `dsnkls_key` (`dsnkls_dsn_id`,`dsnkls_kls_id`),
  KEY `FK_dosen_kelas_2` (`dsnkls_kls_id`),
  CONSTRAINT `FK_dosen_kelas` FOREIGN KEY (`dsnkls_dsn_id`) REFERENCES `dosen` (`dsn_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_dosen_kelas_kelas` FOREIGN KEY (`dsnkls_kls_id`) REFERENCES `kelas` (`kls_id`)
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `dosen_waktu` */

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
  CONSTRAINT `FK_jadwal_kuliah_` FOREIGN KEY (`jk_wkt_id`) REFERENCES `waktu` (`waktu_id`),
  CONSTRAINT `FK_jadwal_kuliah_2` FOREIGN KEY (`jk_ru_id`) REFERENCES `ruang` (`ru_id`),
  CONSTRAINT `FK_jadwal_kuliah_kelas` FOREIGN KEY (`jk_kls_id`) REFERENCES `kelas` (`kls_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `jadwal_kuliah` */

/*Table structure for table `kelas` */

DROP TABLE IF EXISTS `kelas`;

CREATE TABLE `kelas` (
  `kls_id` int(11) NOT NULL AUTO_INCREMENT,
  `kls_mkkur_id` int(11) DEFAULT NULL,
  `kls_nama` varchar(20) DEFAULT NULL,
  `kls_kode_paralel` varchar(6) DEFAULT NULL,
  `kls_jml_peserta_prediksi` int(6) DEFAULT NULL,
  `kls_jadwal_merata` int(1) DEFAULT '0',
  `kls_id_grup_jadwal` int(2) DEFAULT NULL,
  PRIMARY KEY (`kls_id`),
  KEY `kls_mkkur_id_key` (`kls_mkkur_id`),
  CONSTRAINT `FK_kelas_mk` FOREIGN KEY (`kls_mkkur_id`) REFERENCES `mata_kuliah_kurikulum` (`mkkur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `kelas` */

/*Table structure for table `log_proses` */

DROP TABLE IF EXISTS `log_proses`;

CREATE TABLE `log_proses` (
  `logproses_id` int(5) NOT NULL AUTO_INCREMENT,
  `logproses_kode` varchar(80) DEFAULT NULL,
  `logproses_data` longtext,
  PRIMARY KEY (`logproses_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `log_proses` */

insert  into `log_proses`(`logproses_id`,`logproses_kode`,`logproses_data`) values (1,'jst_prediksi',NULL),(2,'klasifikasi',NULL),(3,'algen_penjadwalan',NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `mata_kuliah_kurikulum` */

/*Table structure for table `mata_kuliah_kurikulum_rekap` */

DROP TABLE IF EXISTS `mata_kuliah_kurikulum_rekap`;

CREATE TABLE `mata_kuliah_kurikulum_rekap` (
  `mkkurrkp_id` int(11) NOT NULL AUTO_INCREMENT,
  `mkkurrkp_mkkur_id` int(11) DEFAULT NULL,
  `mkkurrkp_jml_peminat` int(6) DEFAULT NULL,
  `mkkurrkp_tahun` year(4) DEFAULT NULL,
  PRIMARY KEY (`mkkurrkp_id`),
  KEY `mkkurrkp_mkkur_id_key` (`mkkurrkp_mkkur_id`),
  CONSTRAINT `FK_mata_kuliah_kurikulum_rekap_mk` FOREIGN KEY (`mkkurrkp_id`) REFERENCES `mata_kuliah_kurikulum` (`mkkur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `mata_kuliah_kurikulum_rekap` */

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
  CONSTRAINT `FK_mkkur_prodi_FK2` FOREIGN KEY (`mkkprod_prodi_id`) REFERENCES `program_studi` (`prodi_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_mkkur_prodi_mk` FOREIGN KEY (`mkkprod_id`) REFERENCES `mata_kuliah_kurikulum` (`mkkur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `mkkur_prodi` */

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
