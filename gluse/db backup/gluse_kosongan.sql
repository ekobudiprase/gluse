/*
SQLyog Ultimate v8.8 
MySQL - 5.6.17 : Database - gluse_kosongan
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

insert  into `config`(`conf_id`,`conf_name`,`conf_value`,`conf_tipe`) values (1,'periode_tahun_prediksi','6',NULL),(2,'item_per_page','100',NULL),(3,'semester_aktif','ganjil',NULL),(4,'min_persen_kelas','75',NULL);

/*Table structure for table `dosen` */

DROP TABLE IF EXISTS `dosen`;

CREATE TABLE `dosen` (
  `dsn_id` int(11) NOT NULL AUTO_INCREMENT,
  `dsn_nip` varchar(20) DEFAULT NULL,
  `dsn_nama` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`dsn_id`),
  UNIQUE KEY `dsn_nama_key` (`dsn_nama`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `dosen` */

/*Table structure for table `dosen_kelas` */

DROP TABLE IF EXISTS `dosen_kelas`;

CREATE TABLE `dosen_kelas` (
  `dsnkls_id` int(11) NOT NULL AUTO_INCREMENT,
  `dsnkls_dsn_id` int(11) DEFAULT NULL,
  `dsnkls_kls_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`dsnkls_id`),
  KEY `dsnkls_key` (`dsnkls_dsn_id`,`dsnkls_kls_id`),
  KEY `FK_dosen_kelas_2` (`dsnkls_kls_id`),
  CONSTRAINT `FK_dosen_kelas_dsn` FOREIGN KEY (`dsnkls_dsn_id`) REFERENCES `dosen` (`dsn_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_dosen_kelas_kls` FOREIGN KEY (`dsnkls_kls_id`) REFERENCES `kelas` (`kls_id`) ON DELETE CASCADE ON UPDATE CASCADE
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
  CONSTRAINT `FK_dosen_waktu_dsn` FOREIGN KEY (`dsnwkt_dsn_id`) REFERENCES `dosen` (`dsn_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_dosen_waktu_wkt` FOREIGN KEY (`dsnwkt_wkt_id`) REFERENCES `waktu` (`waktu_id`) ON DELETE CASCADE ON UPDATE CASCADE
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
  CONSTRAINT `FK_jadwal_kuliah_kls` FOREIGN KEY (`jk_kls_id`) REFERENCES `kelas` (`kls_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_jadwal_kuliah_ru` FOREIGN KEY (`jk_ru_id`) REFERENCES `ruang` (`ru_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_jadwal_kuliah_wkt` FOREIGN KEY (`jk_wkt_id`) REFERENCES `waktu` (`waktu_id`) ON DELETE CASCADE ON UPDATE CASCADE
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
  CONSTRAINT `FK_kelas_mk` FOREIGN KEY (`kls_mkkur_id`) REFERENCES `mata_kuliah_kurikulum` (`mkkur_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `kelas` */

/*Table structure for table `log_proses` */

DROP TABLE IF EXISTS `log_proses`;

CREATE TABLE `log_proses` (
  `logproses_id` int(5) NOT NULL AUTO_INCREMENT,
  `logproses_kode` varchar(80) DEFAULT NULL,
  `logproses_data` longtext,
  PRIMARY KEY (`logproses_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `log_proses` */

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
  CONSTRAINT `FK_mata_kuliah_kurikulum_rekap_mk` FOREIGN KEY (`mkkurrkp_mkkur_id`) REFERENCES `mata_kuliah_kurikulum` (`mkkur_id`) ON DELETE CASCADE ON UPDATE CASCADE
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
  CONSTRAINT `FK_mkkur_prodi_mk` FOREIGN KEY (`mkkprod_mkkur_id`) REFERENCES `mata_kuliah_kurikulum` (`mkkur_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_mkkur_prodi_prd` FOREIGN KEY (`mkkprod_prodi_id`) REFERENCES `program_studi` (`prodi_id`) ON DELETE CASCADE ON UPDATE CASCADE
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `program_studi` */

/*Table structure for table `ruang` */

DROP TABLE IF EXISTS `ruang`;

CREATE TABLE `ruang` (
  `ru_id` int(11) NOT NULL AUTO_INCREMENT,
  `ru_kode` varchar(6) DEFAULT NULL,
  `ru_nama` varchar(20) DEFAULT NULL,
  `ru_kapasitas` int(6) DEFAULT NULL,
  `ru_is_cadangan` tinyint(1) DEFAULT '0' COMMENT '1=is_cadangan',
  PRIMARY KEY (`ru_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `ruang` */

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
  CONSTRAINT `FK_ruang_prodi_prd` FOREIGN KEY (`ruprd_prodi_id`) REFERENCES `program_studi` (`prodi_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `ruang_prodi` */

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
