<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package     Gluse
 * @subpackage  home
 * @author      Eko Budi Prasetyo
 * @version     0.0.0
 * @since       june 13, 2014
 */

class Sc_model extends CI_Model {

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

    public function get_all_dosen(){
        $query = '
            SELECT 
                dsn_id
            FROM dosen d
        ';

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        return $ret;
    }

    public function get_all_kelas(){
        $query = '
            SELECT 
                kls_id
            FROM kelas d
        ';

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        return $ret;
    }

    public function get_all_mk(){
        $query = '
            SELECT 
                *
            FROM mata_kuliah_kurikulum m
        ';

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        return $ret;
    }

    public function get_all_prodi(){
        $query = '
            SELECT 
                *
            FROM program_studi p
        ';

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        return $ret;
    }

    function del_dsnkelas(){
        $query = '
            DELETE FROM dosen_kelas
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
        
        return $this->db->query($sql, array($dsn_id, $kls_id)); 
    }

    function ins_mkkur_prodi($param){

        $sql = "
            INSERT INTO mkkur_prodi
            (`mkkprod_mkkur_id`, `mkkprod_prodi_id`)
            VALUES (?,?);
        ";
        
        return $this->db->query($sql, $param); 
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

        $query = '
            SELECT count(mkkur_id) as total
            FROM mata_kuliah_kurikulum mkk
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
                IF(mkkur_pred_jml_peminat IS NULL,"Belum diketahui",mkkur_pred_jml_peminat) AS pred_jml_peminat
            FROM mata_kuliah_kurikulum mkk
            -- LEFT JOIN program_studi ps ON mkk.`mkkur_prodi_id` = ps.`prodi_id`
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

    function get_data_makul(){
        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                mkk.`mkkur_id` AS id,
                mkk.`mkkur_kode` AS kode,
                mkk.`mkkur_nama` AS nama,
                mkk.`mkkur_semester` AS smt,
                IF(mkkur_pred_jml_peminat IS NULL,0,mkkur_pred_jml_peminat) AS pred_jml_peminat,
                mkk.`mkkur_is_universal` AS is_universal
                -- ps.`prodi_kode` AS kode_prodi
        FROM mata_kuliah_kurikulum mkk
        -- LEFT JOIN program_studi ps ON mkk.`mkkur_prodi_id` = ps.`prodi_id`
        ';
        
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
            (`kls_mkkur_id`,`kls_nama`,`kls_kode_paralel`,kls_jml_peserta_prediksi)
            VALUES (?,?,?,?);
        ";
        
        return $this->db->query($sql, array($id_makul, $nama_kelas, $kelas, $jumlah_per_kelas)); 
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

    function set_null_prediksi(){
        $query = '
            UPDATE `mata_kuliah_kurikulum`
            SET `mkkur_pred_jml_peminat` = 0
        ';

        $ret = $this->db->query($query);
        return $ret;
    }

    
}

?>