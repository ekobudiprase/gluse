<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package     Gluse
 * @subpackage  home
 * @author      Eko Budi Prasetyo
 * @version     0.0.0
 * @since       May 30, 2014
 */

class Penjadwalan_model extends CI_Model {

    /**
    * @author   Eko Budi Prasetyo
    * @version  0.0.0
    * @since    30may, 2014
    * @usedfor  -
    */
    function __construct(){
        // Call the Model constructor
        parent::__construct();
        // $this->load->database();
    }

    // base = bukan perpanjangan
    public function get_base_mkprodid_by_mkid($mkid){
        $query = '
            SELECT 
                ps.prodi_nama,
                ps.prodi_kode,
                mp.`mkkprod_id`,
                mp.`mkkprod_porsi_kelas`,
                mkk.`mkkur_pred_jml_peminat`,
                (
                SELECT SUM(mpv.mkkprod_porsi_kelas)
                FROM mkkur_prodi mpv
                WHERE mpv.mkkprod_mkkur_id = mp.`mkkprod_mkkur_id`
                AND mpv.`mkkprod_related_id` IS NULL
                GROUP BY mkkprod_mkkur_id
                ) AS t,
                mkkprod_porsi_kelas * (mkk.`mkkur_pred_jml_peminat` DIV
                (
                    SELECT SUM(mpv.mkkprod_porsi_kelas)
                    FROM mkkur_prodi mpv
                    WHERE mpv.mkkprod_mkkur_id = mp.`mkkprod_mkkur_id`
                    AND mpv.`mkkprod_related_id` IS NULL
                    GROUP BY mkkprod_mkkur_id
                )) AS jml_porsi,    
                mkk.`mkkur_pred_jml_peminat` MOD
                (
                    SELECT SUM(mpv.mkkprod_porsi_kelas)
                    FROM mkkur_prodi mpv
                    WHERE mpv.mkkprod_mkkur_id = mp.`mkkprod_mkkur_id`
                    AND mpv.`mkkprod_related_id` IS NULL
                    GROUP BY mkkprod_mkkur_id
                ) AS sisa
            FROM program_studi ps
            LEFT JOIN mkkur_prodi mp ON ps.prodi_id = mp.mkkprod_prodi_id
            LEFT JOIN mata_kuliah_kurikulum mkk ON mp.`mkkprod_mkkur_id` = mkk.`mkkur_id`
            WHERE mp.mkkprod_mkkur_id = "'.$mkid.'"
            AND mp.`mkkprod_related_id` IS NULL           
            AND mp.`mkkprod_porsi_kelas` <> 0
        ';

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        return $ret;
    }

    public function get_concat_data_prodi($param){        
        $perpanjangan = true;
        $id = $param['prodi_id'];
        while ($perpanjangan) {
            $query_2 = '
                SELECT 
                    ps.prodi_nama,
                    ps.prodi_kode,
                    mp.`mkkprod_id`,
                    mp.`mkkprod_related_id`
                FROM program_studi ps
                LEFT JOIN mkkur_prodi mp ON ps.prodi_id = mp.mkkprod_prodi_id
                WHERE 1=1
                --cond--
                
            ';

            $str = 'and mp.`mkkprod_related_id` = "'.$id.'"';
            $query_2 = str_replace('--cond--', $str, $query_2);

            $ret2 = $this->db->query($query_2);
            $ret2 = $ret2->result_array();
            if (!empty($ret2)) {
                $id = $ret2[0]['mkkprod_id'];
                $param['prodi_nama'] .= ', '.$ret2[0]['prodi_nama'];
                $param['prodi_kode'] .= '-'.$ret2[0]['prodi_kode'];
                $perpanjangan = true;
            }else{
                $perpanjangan = false;
            }
        }

        return $param;
    }

    private function get_mkprodid_by_mkid($mkid){
        $ret = $this->get_base_mkprodid_by_mkid($mkid);

        foreach ($ret as $key => $value) {
            $param = array(
                "prodi_id" => $value['mkkprod_id'],
                "prodi_kode" => $value['prodi_kode'],
                "prodi_nama" => $value['prodi_nama']
            );
            $prodi[$key] = $this->get_concat_data_prodi($param);
        }        

        $strret = "";
        if (isset($prodi)) {
            foreach ($prodi as $key => $value) {
                $strret .= $value['prodi_nama'].";<br>";
            }
        }        

        // echo '<pre>'; print_r($strret); 
        return $strret;
    }

    //=========================================================================================================
    
    
    /**
    * @author   Eko Budi Prasetyo
    * @version  0.0.0
    * @since    30may, 2014
    * @usedfor  -
    */
    function get_count_matakuliah($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        if (!empty($semester_aktif)) {
            $str .= "AND mkkur_semester = '$semester_aktif'";   
        }

        $query = '
            SELECT count(mkkur_id) as total
            FROM mata_kuliah_kurikulum mkk
            WHERE 1=1
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val[0]['total'];
    }

    /**
    * @author   Eko Budi Prasetyo
    * @version  0.0.0
    * @since    30may, 2014
    * @usedfor  -
    */
    function get_matakuliah($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        if (!empty($semester_aktif)) {
            $str .= "AND mkkur_semester = '$semester_aktif'";   
        }

        
        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                mkk.`mkkur_id` AS id,
                mkk.`mkkur_kode` AS kode,
                mkk.`mkkur_nama` AS nama,
                mkk.`mkkur_paket_semester` AS paket,
                mkk.`mkkur_semester` AS smt,
                "null" AS nama_prodi,
                mkk.`mkkur_sks` AS sks,
                IF(mkkur_pred_jml_peminat IS NULL,"Belum diketahui",mkkur_pred_jml_peminat) AS pred_jml_peminat,
                mkkur_maks_kelas as maks_kelas
            FROM mata_kuliah_kurikulum mkk
            -- LEFT JOIN program_studi ps ON mkk.`mkkur_prodi_id` = ps.`prodi_id`
            WHERE 1=1
            --search--
            --limit--
        ';

        $query = str_replace('--search--', $str, $query);
        $query = str_replace('--limit--', $limit, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        $prodi = array();
        foreach ($ret as $key => $value) {
            $ret[$key]['nama_prodi'] = $this->get_mkprodid_by_mkid($value['id']);
        }
        

        return $ret;
    }

    function cek_kelas_ada(){
        $query = '
            SELECT 
                COUNT(k.`kls_id`) AS jml_kelas
            FROM kelas k
        ';

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        return $ret[0]['jml_kelas'];
    }

    function get_data_makul($semester_aktif){
        $str = '';
        if (!empty($semester_aktif)) {
            $str .= "AND mkkur_semester = '$semester_aktif'";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                mkk.`mkkur_id` AS id,
                mkk.`mkkur_kode` AS kode,
                mkk.`mkkur_nama` AS nama,
                mkk.`mkkur_semester` AS smt,
                IF(mkkur_pred_jml_peminat IS NULL,0,mkkur_pred_jml_peminat) AS pred_jml_peminat,
                mkk.`mkkur_is_universal` AS is_universal,
                -- ps.`prodi_kode` AS kode_prodi,
                mkk.mkkur_sifat AS sifat,
                mkkur_maks_kelas as maks_kelas
            FROM mata_kuliah_kurikulum mkk
            -- LEFT JOIN program_studi ps ON mkk.`mkkur_prodi_id` = ps.`prodi_id`
            WHERE 1=1
            --search--
        ';
        
        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        $prodi = array();
        foreach ($ret as $key => $value) {
            $ret[$key]['nama_prodi'] = $this->get_mkprodid_by_mkid($value['id']);
        }

        return $ret;
    }

    function del_dsnkelas_ref_kelas(){
        $query = '
            DELETE FROM dosen_kelas WHERE
            `dsnkls_kls_id` IN ( 
            SELECT
                k.`kls_id`
            FROM kelas k)
        ';

        $ret = $this->db->query($query);
        return $ret;
    }

    function del_record_kelas(){
        $query = '
            DELETE FROM kelas
        ';

        $ret = $this->db->query($query);
        return $ret;
    }

    function ins_kelas($param){
        if (is_array($param))
            extract($param);

        $sql = "
            INSERT INTO `kelas`
            (`kls_mkkur_id`,`kls_nama`,`kls_kode_paralel`,kls_jml_peserta_prediksi, kls_jadwal_merata, kls_id_grup_jadwal)
            VALUES (?,?,?,?,?,?);
        ";
        
        return $this->db->query($sql, array($id_makul, $nama_kelas, $kelas, $jumlah_per_kelas, $kls_jadwal_merata, $kls_id_grup_jadwal)); 
    }

    function cek_dosen_kelas_lengkap(){
        $query = '
            SELECT
                IF(COUNT(k.`kls_id`)>0,FALSE,TRUE) AS kelas_dosen_lengkap
            FROM kelas k
            WHERE (
            SELECT COUNT(`dsnkls_id`)
            FROM dosen_kelas dk
            WHERE dk.`dsnkls_kls_id` = k.`kls_id`
            ) = 0
        ';

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        return $ret[0]['kelas_dosen_lengkap'];
    }

    /**
    * @author   Eko Budi Prasetyo
    * @version  0.0.0
    * @since    4june, 2014
    * @usedfor  -
    */
    function get_kelas($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        
        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                k.`kls_id` AS id,
                k.`kls_nama` AS nama_kelas,
                mkk.mkkur_sifat AS sifat,
                mkk.mkkur_paket_semester AS paket_smt,
                mkk.`mkkur_nama` AS nama_makul,
                k.`kls_jml_peserta_prediksi` AS jml_peserta_kls,
                IF((
                SELECT COUNT(`dsnkls_id`)
                FROM dosen_kelas dk
                WHERE dk.`dsnkls_kls_id` = k.`kls_id`
                )>0,                
                (
                SELECT GROUP_CONCAT(dsn_nama SEPARATOR "<br>")
                FROM dosen_kelas dk
                LEFT JOIN dosen d ON dk.`dsnkls_dsn_id` = d.`dsn_id`
                WHERE dk.`dsnkls_kls_id` = k.`kls_id`
                )
                ,"<span class=\"label label-important\">belum ditentukan</span>") AS dosen_kelas
            FROM kelas k
            LEFT JOIN mata_kuliah_kurikulum mkk ON k.`kls_mkkur_id` = mkk.`mkkur_id`
            --search--
            ORDER BY mkkur_sifat DESC
            --limit--
        ';

        $query = str_replace('--search--', $str, $query);
        $query = str_replace('--limit--', $limit, $query);

        $ret = $this->db->query($query);

        return $ret->result_array();
    }

    function get_count_kelas($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT 
                count(k.`kls_id`) AS total
            FROM kelas k
            LEFT JOIN mata_kuliah_kurikulum mkk ON k.`kls_mkkur_id` = mkk.`mkkur_id`
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        return $ret[0]['total'];
    }

    /**
    * @author   Eko Budi Prasetyo
    * @version  0.0.0
    * @since    4june, 2014
    * @usedfor  -
    */
    function get_dosen($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        
        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";   
        }

        $query = '
            SELECT
                d.`dsn_id`,
                d.`dsn_nip`,
                d.`dsn_nama`,
                IF(dsn_id IN
                (
                SELECT dsnkls_dsn_id
                FROM dosen_kelas
                WHERE dsnkls_kls_id = '.$idkls.'
                ),"checked","") AS checked
            FROM dosen d
            --search--
            ORDER BY dsn_nama ASC
            --limit--
        ';

        $query = str_replace('--search--', $str, $query);
        $query = str_replace('--limit--', $limit, $query);
        
        $ret = $this->db->query($query);

        return $ret->result_array();
    }

    function get_count_dosen($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT
                count(d.`dsn_id`) as total
            FROM dosen d
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        return $ret[0]['total'];
    }

    function del_dsnkelas_by_idkelas($id_kelas){
        $query = '
            DELETE FROM dosen_kelas where dsnkls_kls_id = "'.$id_kelas.'"
        ';

        $ret = $this->db->query($query);
        return $ret;
    }

    function ins_dosenkelas($param){
        if (is_array($param))
            extract($param);

        $sql = "
            INSERT INTO dosen_kelas
            (`dsnkls_dsn_id`, `dsnkls_kls_id`)
            VALUES (?,?);
        ";
        
        return $this->db->query($sql, array($id_dosen, $id_kelas)); 
    }

    function get_all_kelas(){
        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                k.`kls_id` AS id,
                mkkur_is_universal as is_universal,
                k.`kls_nama` AS nama_kelas,
                mkkur_id,
                mkk.`mkkur_nama` AS nama_makul,

                (
                SELECT
                    GROUP_CONCAT(ruprd_ru_id SEPARATOR "|")
                FROM program_studi 
                LEFT JOIN ruang_prodi ON prodi_id = ruprd_prodi_id
                WHERE prodi_prefix_mk = SUBSTRING(mkkur_kode,1,3)
                ) AS ruang_blok_prodi,
                (
                SELECT
                    GROUP_CONCAT(mkkprod_prodi_id SEPARATOR "|")
                FROM mkkur_prodi 
                WHERE mkkprod_mkkur_id = mkk.`mkkur_id`
                ) AS kelas_prodi,

                k.`kls_jml_peserta_prediksi` AS jml_peserta_kls,
                IF((
                SELECT COUNT(`dsnkls_id`)
                FROM dosen_kelas dk
                WHERE dk.`dsnkls_kls_id` = k.`kls_id`
                )>0,                
                (
                SELECT GROUP_CONCAT(dsn_nama SEPARATOR "<br>")
                FROM dosen_kelas dk
                LEFT JOIN dosen d ON dk.`dsnkls_dsn_id` = d.`dsn_id`
                WHERE dk.`dsnkls_kls_id` = k.`kls_id`
                )
                ,"<span class=\"label label-important\">belum ditentukan</span>") AS dosen_kelas,
                mkk.mkkur_sks AS sks,
                mkkur_format_jadwal AS format_jadwal,
                mkkur_paket_semester AS paket_smt,
                mkkur_semester AS smt_makul,
                mkkur_sifat AS sifat_makul,                
                (
                SELECT
                GROUP_CONCAT(dsnwkt_wkt_id SEPARATOR "|")
                FROM dosen_waktu
                WHERE dsnwkt_dsn_id IN (
                    SELECT dsn_id
                    FROM dosen_kelas dk
                    LEFT JOIN dosen d ON dk.`dsnkls_dsn_id` = d.`dsn_id`
                    WHERE dk.`dsnkls_kls_id` = k.`kls_id`
                )
                ) AS alternatif_waktu_ajar,
                (
                SELECT COUNT(*) AS cnt 
                FROM kelas k2 
                WHERE k2.kls_mkkur_id = mkk.`mkkur_id`
                ) AS order_col,
				kls_jadwal_merata,
                kls_id_grup_jadwal
            FROM kelas k
            LEFT JOIN mata_kuliah_kurikulum mkk ON k.`kls_mkkur_id` = mkk.`mkkur_id`
            ORDER BY kls_jml_peserta_prediksi DESC, order_col DESC
        ';

        $ret = $this->db->query($query);

        return $ret->result_array();
    }

    function get_all_ruang(){
        $query = '
            SELECT
                r.`ru_id`,
                r.`ru_nama`,
                r.`ru_kapasitas`,
                r.`ru_is_cadangan`
            FROM ruang r
        ';

        $ret = $this->db->query($query);

        return $ret->result_array();
    }

    function get_all_waktu(){
        $query = '
            SELECT
                w.`waktu_id`,
                w.`waktu_hari`,
                w.`waktu_jam_mulai`,
                w.`waktu_jam_selesai`
            FROM waktu w
        ';

        $ret = $this->db->query($query);

        return $ret->result_array();
    }

    function get_all_jadwal_kuliah(){
        $query = '
            SELECT
                jk_id,
                jk_kls_id,
                jk_wkt_id,
                jk_ru_id,
                jk_period,
                jk_label,
				k.kls_nama,				
                mkk.`mkkur_nama` AS nama_makul
            FROM jadwal_kuliah jk          
			LEFT JOIN kelas k ON jk.jk_kls_id = k.kls_id
			LEFT JOIN mata_kuliah_kurikulum mkk ON k.`kls_mkkur_id` = mkk.`mkkur_id`
        ';

        $ret = $this->db->query($query);

        return $ret->result_array();
    }

    function get_all_prodi(){
        $query = '
            SELECT
                ps.`prodi_id`,
                ps.`prodi_kode`,
                ps.`prodi_nama`,
                ps.`prodi_prefix_mk`
            FROM program_studi ps
        ';

        $ret = $this->db->query($query);

        return $ret->result_array();
    }
    
    function get_iddosen_by_idkelas($id_kelas){
        $str = 'AND dk.`dsnkls_kls_id` = "'.$id_kelas.'"';

        $query = '
            SELECT 
                dk.`dsnkls_dsn_id` AS id_dosen
            FROM dosen_kelas dk
            WHERE 1=1
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val;
    }
    
    function getJamMaksSabtu(){

        $query = '
            SELECT 
            MAX(w.`waktu_jam_selesai`) AS maks_jam
            FROM waktu w
            WHERE w.`waktu_hari` = "sabtu"
        ';

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val[0]['maks_jam'];
    }

    function del_jadwalkuliah(){
        $query = '
            DELETE FROM jadwal_kuliah
        ';

        $ret = $this->db->query($query);
        return $ret;
    }

    function ins_jadwalkuliah($param){
        if (is_array($param))
            extract($param);

        $sql = "
            INSERT INTO jadwal_kuliah
            (jk_kls_id, jk_wkt_id, jk_ru_id, jk_period, jk_jam_selesai, jk_label)
            VALUES (?, ?, ?, ?, ?, ?);
        ";
        
        return $this->db->query($sql, array($id_kelas, $id_waktu, $id_ruang, $period, $jam_selesai, $label)); 
    }

    function get_jadwal_to_export(){
        $query = "
            SELECT 
                mkk.`mkkur_kode` AS makul_kode,
                mkk.`mkkur_nama` AS makul_nama,
                mkk.`mkkur_paket_semester` AS paket_sem,
                k.`kls_nama`,
                mkk.`mkkur_sks` AS sks,
                (
                    SELECT GROUP_CONCAT(dsn_nama SEPARATOR '; ')
                    FROM dosen_kelas dk
                    LEFT JOIN dosen d ON dk.`dsnkls_dsn_id` = d.`dsn_id`
                    WHERE dk.`dsnkls_kls_id` = k.`kls_id`                
                    ) AS dosen_kelas,
                jk.`jk_ru_id` AS ru_id,
                r.`ru_nama` AS ru_nama,
                k.`kls_jml_peserta_prediksi` AS jml_peserta,
                IF(w.`waktu_hari` = 'senin',
                    CONCAT(DATE_FORMAT(w.`waktu_jam_mulai`,'%H:%i'),'-',(DATE_FORMAT(DATE_ADD(w.waktu_jam_mulai,INTERVAL (jk.`jk_period`*50) MINUTE),'%H:%i'))),
                    ''
                ) AS senin,
                IF(w.`waktu_hari` = 'selasa',
                    CONCAT(DATE_FORMAT(w.`waktu_jam_mulai`,'%H:%i'),'-',(DATE_FORMAT(DATE_ADD(w.waktu_jam_mulai,INTERVAL (jk.`jk_period`*50) MINUTE),'%H:%i'))),
                    ''
                ) AS selasa,
                IF(w.`waktu_hari` = 'rabu',
                    CONCAT(DATE_FORMAT(w.`waktu_jam_mulai`,'%H:%i'),'-',(DATE_FORMAT(DATE_ADD(w.waktu_jam_mulai,INTERVAL (jk.`jk_period`*50) MINUTE),'%H:%i'))),
                    ''
                ) AS rabu,
                IF(w.`waktu_hari` = 'kamis',
                    CONCAT(DATE_FORMAT(w.`waktu_jam_mulai`,'%H:%i'),'-',(DATE_FORMAT(DATE_ADD(w.waktu_jam_mulai,INTERVAL (jk.`jk_period`*50) MINUTE),'%H:%i'))),
                    ''
                ) AS kamis,
                IF(w.`waktu_hari` = 'jumat',
                    CONCAT(DATE_FORMAT(w.`waktu_jam_mulai`,'%H:%i'),'-',(DATE_FORMAT(DATE_ADD(w.waktu_jam_mulai,INTERVAL (jk.`jk_period`*50) MINUTE),'%H:%i'))),
                    ''
                ) AS jumat,
                jk.`jk_label`
            FROM jadwal_kuliah jk
            LEFT JOIN ruang r ON jk.`jk_ru_id` = r.`ru_id`
            LEFT JOIN waktu w ON jk.`jk_wkt_id` = w.`waktu_id`
            LEFT JOIN kelas k ON jk.`jk_kls_id` = k.`kls_id`
            LEFT JOIN mata_kuliah_kurikulum mkk ON k.`kls_mkkur_id` = mkk.`mkkur_id`
        ";

        $ret = $this->db->query($query);

        return $ret->result_array();
    }

    
}

?>