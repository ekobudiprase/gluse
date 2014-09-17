<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package     Gluse
 * @subpackage  home
 * @author      Eko Budi Prasetyo
 * @version     0.0.0
 * @since       May 30, 2014
 */

class Pengelolaan_model extends CI_Model {

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


    //=========================================================================================================
    
    /**
    * @author   Eko Budi Prasetyo
    * @version  0.0.0
    * @since    30may, 2014
    * @usedfor  -
    */
    function get_count_konfigurasi($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                count(c.`conf_id`) AS total
            FROM config c
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val[0]['total'];
    }

    /**
    * @since    29june, 2014
    **/
    function get_konfigurasi($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        if (!empty($id)) {
            $str = "AND conf_id = $id";   
        }
        
        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                c.`conf_id` AS id,
                c.`conf_name` AS nama,
                c.`conf_value` AS nilai
            FROM config c
            WHERE 1=1
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();       

        return $ret;
    }

    /**
    * @author   Eko Budi Prasetyo
    * @version  0.0.0
    * @since    30may, 2014
    * @usedfor  -
    */
    function get_all_matakuliah(){
        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                mkk.`mkkur_id` AS id,
                mkk.`mkkur_kode` AS kode,
                mkk.`mkkur_nama` AS nama,
                mkk.`mkkur_paket_semester` AS paket,
                mkk.`mkkur_semester` AS smt,
                "null" AS nama_prodi,
                mkk.`mkkur_sks` AS sks,
                "Belum diketahui" AS jml_peminat
            FROM mata_kuliah_kurikulum mkk
            -- LEFT JOIN program_studi ps ON mkk.`mkkur_prodi_id` = ps.`prodi_id`
            ORDER BY mkk.`mkkur_semester`
        ';

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        $prodi = array();
        foreach ($ret as $key => $value) {
            $ret[$key]['nama_prodi'] = $this->get_mkprodid_by_mkid($value['id']);
        }
        

        return $ret;
    }

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
    * @since    30may, 2014
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

        return $ret;
    }

    /**
    * @since    30may, 2014
    */
    function get_matakuliah_by_id($filter){
        if (is_array($filter))
            extract($filter);
        $str = ''; 

        if (!empty($id)) {
            $str = "AND mkkur_id = $id";   
        }

        $query = '
            SELECT
                  `mkkur_id` AS id,
                  `mkkur_kode` AS kode,
                  `mkkur_nama` AS nama,
                  `mkkur_sks` AS sks,
                  `mkkur_semester` AS smt,
                  `mkkur_sifat` AS sifat,
                  `mkkur_paket_semester` AS paket,
                  `mkkur_jumlah_pert` AS jml_pert,
                  `mkkur_is_universal` AS is_univers,
                  `mkkur_format_jadwal` AS format,
                  mkkur_maks_kelas AS maks_kelas
            FROM `mata_kuliah_kurikulum`
            WHERE 1=1
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret[0];
    }

    function update_konfigurasi($param){
        if (is_array($param))
            extract($param);

        $sql = "
            UPDATE config
            SET conf_value = ?
            WHERE conf_id = ?
        ";

        return $this->db->query($sql, array($nilai, $id)); 
    }

    function update_matakuliah($param){
        if (is_array($param))
            extract($param);

        $sql = "
            UPDATE `mata_kuliah_kurikulum`
            SET
                  `mkkur_kode` = ?,
                  `mkkur_nama` = ?,
                  `mkkur_sks` = ?,
                  `mkkur_semester` = ?,
                  `mkkur_sifat` = ?,
                  `mkkur_paket_semester` = ?,
                  `mkkur_jumlah_pert` = ?,
                  `mkkur_is_universal` = ?,
                  `mkkur_format_jadwal` = ?,
                  mkkur_maks_kelas = ?
            WHERE `mkkur_id` = ?
        ";

        return $this->db->query($sql, array($kode, $nama, $sks, $smt, $sifat, $paket, $jml_pert, $is_univr, $format, $maks_kelas, $id));
    }

    function add_matakuliah($param){
        if (is_array($param))
            extract($param);

        $sql = "
            INSERT INTO `mata_kuliah_kurikulum`
            (`mkkur_kode`,`mkkur_nama`,`mkkur_sks`,`mkkur_semester`,`mkkur_sifat`,`mkkur_paket_semester`,`mkkur_jumlah_pert`,`mkkur_is_universal`,`mkkur_format_jadwal`, mkkur_maks_kelas)
            VALUES (?,?,?,?,?,?,?,?,?,?)
        ";

        return $this->db->query($sql, array($kode, $nama, $sks, $smt, $sifat, $paket, $jml_pert, $is_univr, $format));
    }

    function del_konfigurasi($param){
        if (is_array($param))
            extract($param);

        $sql = "
            DELETE FROM config
            WHERE conf_id = ?
        ";

        return $this->db->query($sql, array($id)); 
    }

    function del_matakuliah($param){
        if (is_array($param))
            extract($param);

        $sql = "
            DELETE FROM mata_kuliah_kurikulum
            WHERE mkkur_id = ?
        ";

        return $this->db->query($sql, array($id)); 
    }

}

?>
