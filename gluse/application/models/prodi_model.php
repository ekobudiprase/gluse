<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package     Gluse
 * @subpackage  home
 * @author      Eko Budi Prasetyo
 * @version     0.0.0
 * @since       June 30, 2014
 */

class Prodi_model extends CI_Model {

    /**
    * @since    30may, 2014
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
    * @since    30may, 2014
    */
    function get_count_makul_prodi($filter){
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
    function get_makul_prodi($filter){
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
                IF(mkk.mkkur_is_universal=1,"display:none;","") AS display
            FROM mata_kuliah_kurikulum mkk
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

    /**
    * @since    30may, 2014
    */
    function get_count_ruang_prodi($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT count(ru_id) as total
            FROM ruang r
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
    function get_count_program_studi($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT count(prodi_id) as total
            FROM program_studi 
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
    function get_ruang_prodi($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        
        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                `ru_id` AS id,
                `ru_kode` AS kode,
                `ru_nama` AS nama,
                `ru_kapasitas` AS kapasitas,
                ru_is_cadangan AS is_cad,
                IF(ru_is_cadangan=0,"","Cadangan") AS is_cad_label,
                (
                SELECT GROUP_CONCAT(prodi_nama SEPARATOR ";<br>")
                FROM ruang_prodi
                LEFT JOIN program_studi ON ruprd_prodi_id = prodi_id
                WHERE ruprd_ru_id = ru_id
                ) AS nama_prodi
            FROM `ruang`
            --search--
            --limit--
        ';

        $query = str_replace('--search--', $str, $query);
        $query = str_replace('--limit--', $limit, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        return $ret;
    }

    public function get_data_prodi_child($arr){        
        $perpanjangan = true;
        $test[] = $arr;
        $id = $arr['id'];

        while ($perpanjangan) {
            $query_2 = '
                SELECT
                    mp.`mkkprod_id` AS id,
                    ps.`prodi_kode` AS kode,
                    ps.`prodi_nama` AS nama,
                    mp.`mkkprod_porsi_kelas` AS porsi,
                    mp.`mkkprod_related_id` AS rel_id
                FROM mkkur_prodi mp
                LEFT JOIN program_studi ps ON mp.`mkkprod_prodi_id` = ps.`prodi_id`
                WHERE 1=1
                --cond--
                
            ';

            $str = 'and mp.`mkkprod_related_id` = "'.$id.'"';
            $query_2 = str_replace('--cond--', $str, $query_2);

            $ret2 = $this->db->query($query_2);
            $ret2 = $ret2->result_array();
            if (!empty($ret2)) {
                $id = $ret2[0]['id'];
                $test[] = $ret2[0];
                $perpanjangan = true;
            }else{
                $perpanjangan = false;
            }
        }

        return $test;
    }

    /**
    * @since    30may, 2014
    */
    function get_makul_prodi_by_id($filter){
        if (is_array($filter))
            extract($filter);

        $query = '
            SELECT
                mp.`mkkprod_id` AS id,
                ps.`prodi_kode` AS kode,
                ps.`prodi_nama` AS nama,
                mp.`mkkprod_porsi_kelas` AS porsi,
                mp.`mkkprod_related_id` AS rel_id
            FROM mkkur_prodi mp
            LEFT JOIN program_studi ps ON mp.`mkkprod_prodi_id` = ps.`prodi_id`
            WHERE mp.mkkprod_mkkur_id = "'.$id.'"
            AND mp.`mkkprod_related_id` IS NULL
        ';

        $ret = $this->db->query($query);
        $ret = $ret->result_array();


        // $ret = $this->get_base_mkprodid_by_mkid($id);
        $arr_ret = array();
        foreach ($ret as $key => $value) {
            $arr_ret[] = $this->get_data_prodi_child($value);
        }

        return $arr_ret;
    }



    public function get_data_prodi_last_child($arr){        
        $perpanjangan = true;
        $test = $arr;
        $id = $arr['id'];

        while ($perpanjangan) {
            $query_2 = '
                SELECT
                    mp.`mkkprod_id` AS id,
                    ps.`prodi_kode` AS label
                FROM mkkur_prodi mp
                LEFT JOIN program_studi ps ON mp.`mkkprod_prodi_id` = ps.`prodi_id`
                WHERE 1=1
                --cond--
                
            ';

            $str = 'and mp.`mkkprod_related_id` = "'.$id.'"';
            $query_2 = str_replace('--cond--', $str, $query_2);

            $ret2 = $this->db->query($query_2);
            $ret2 = $ret2->result_array();
            if (!empty($ret2)) {
                $id = $ret2[0]['id'];
                $test = $ret2[0];
                $perpanjangan = true;
            }else{
                $perpanjangan = false;
            }
        }

        return $test;
    }

    /**
    * @since    30may, 2014
    */
    function get_makul_prodi_by_id_for_cb_parent($id){
        $query = '
            SELECT
                mp.`mkkprod_id` AS id,
                ps.`prodi_kode` AS label
            FROM mkkur_prodi mp
            LEFT JOIN program_studi ps ON mp.`mkkprod_prodi_id` = ps.`prodi_id`
            WHERE mp.mkkprod_mkkur_id = "'.$id.'"
            AND mp.`mkkprod_related_id` IS NULL
        ';

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        $arr_ret = array();
        foreach ($ret as $key => $value) {
            $arr_ret[] = $this->get_data_prodi_last_child($value);
        }

        $ret_cb[] = array(
            "id" => '', "label" => "--pilih--"
        );
        if (!empty($arr_ret)) {
        foreach ($arr_ret as $key => $value) {
                $ret_cb[] = $value;
            } 
        }
        

        return $ret_cb;
    }


    /**
    * @author   Eko Budi Prasetyo
    * @version  0.0.0
    * @since    30may, 2014
    * @usedfor  -
    */
    function get_count_makul_prodi_by_id($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT count(mkkprod_id) as total
            FROM mkkur_prodi mp
            LEFT JOIN program_studi ps ON mp.`mkkprod_prodi_id` = ps.`prodi_id`
            WHERE 1=1
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
    function get_program_studi($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        
        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                  `prodi_id` AS id,
                  `prodi_kode` AS kode,
                  `prodi_nama` AS nama,
                  `prodi_prefix_mk` AS akronim
            FROM `program_studi`
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
    function get_program_studi_by_id($filter){
        if (is_array($filter))
            extract($filter);
        $str = ''; 

        if (!empty($id)) {
            $str = "AND prodi_id = $id";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                  `prodi_id` AS id,
                  `prodi_kode` AS kode,
                  `prodi_nama` AS nama,
                  `prodi_prefix_mk` AS akronim
            FROM `program_studi`
            WHERE 1=1
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret[0];
    }

    /**
    * @since    30may, 2014
    */
    function get_program_studi_except_in_mkid($idmk){
        $query = '
            SELECT 
                prodi_id as id,
                prodi_kode as label,
                prodi_nama
            FROM program_studi
            WHERE prodi_id NOT IN (
            SELECT
                ps.`prodi_id`
            FROM mkkur_prodi mp
            LEFT JOIN program_studi ps ON mp.`mkkprod_prodi_id` = ps.`prodi_id`
            WHERE mp.`mkkprod_mkkur_id` = "'.$idmk.'"
            )
        ';

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        $ret_cb[] = array(
            "id" => '', "label" => "--pilih--"
        );
        foreach ($ret as $key => $value) {
            $ret_cb[] = $value;
        }

        return $ret_cb;
    }

    function update_program_studi($param){
        if (is_array($param))
            extract($param);

        $sql = "
            UPDATE `program_studi`
            SET
                  `prodi_kode` = ?,
                  `prodi_nama` = ?,
                  `prodi_prefix_mk` = ?
            WHERE `prodi_id` = ?
        ";

        return $this->db->query($sql, array($kode, $nama, $akronim, $id));
    }

    function add_program_studi($param){
        if (is_array($param))
            extract($param);

        $sql = "
            INSERT INTO `program_studi`
            (`prodi_kode`,`prodi_nama`,`prodi_prefix_mk`)
            VALUES (?,?,?)
        ";

        return $this->db->query($sql, array($kode, $nama, $akronim));
    }

    function del_program_studi($param){
        if (is_array($param))
            extract($param);

        $sql = "
            DELETE FROM program_studi
            WHERE prodi_id = ?
        ";

        return $this->db->query($sql, array($id)); 
    }



    /**
    * @since    1 july, 2014
    */
    function get_prodi($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        
        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";   
        }

        if (isset($id_mk)) {
            $subquery = '
                SELECT mkkprod_prodi_id
                FROM mkkur_prodi
                WHERE mkkprod_mkkur_id = '.$id_mk
            ;
        }

        if (isset($id_ru)) {
            $subquery = '
                SELECT ruprd_prodi_id
                FROM ruang_prodi
                WHERE ruprd_ru_id = '.$id_ru
            ;
        }

        $query = '
            SELECT
                ps.`prodi_id`,
                ps.`prodi_kode`,
                ps.`prodi_nama`,
                IF(prodi_id IN
                (
                '.$subquery.'
                ),"checked","") AS checked
            FROM program_studi ps
            --search--
            ORDER BY prodi_nama ASC
            --limit--
        ';

        $query = str_replace('--search--', $str, $query);
        $query = str_replace('--limit--', $limit, $query);
        
        $ret = $this->db->query($query);

        return $ret->result_array();
    }

    function get_count_prodi($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT
                count(prodi_id) as total
            FROM program_studi ps
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        return $ret[0]['total'];
    }

    function del_prodiru_by_idru($id){
        $query = '
            DELETE FROM ruang_prodi where ruprd_ru_id = "'.$id.'"
        ';

        $ret = $this->db->query($query);
        return $ret;
    }

    function ins_prodiru($param){
        if (is_array($param))
            extract($param);

        $sql = "
            INSERT INTO ruang_prodi
            (`ruprd_prodi_id`, `ruprd_ru_id`)
            VALUES (?,?);
        ";
        
        return $this->db->query($sql, array($id_prodi, $id)); 
    }

    function add_program_studi_on_makul_prodi($param){
        if (is_array($param))
            extract($param);

        $sql = "
            INSERT INTO `mkkur_prodi`
            (`mkkprod_mkkur_id`,`mkkprod_prodi_id`,`mkkprod_related_id`,`mkkprod_porsi_kelas`)
            VALUES (?,?,?,?);
        ";
        
        return $this->db->query($sql, array($idmk, $prodi, $prodi_parent, $porsi)); 
    }

    function update_program_studi_on_makul_prodi($param){
        if (is_array($param))
            extract($param);

        $sql = "
            UPDATE `mkkur_prodi`
            SET
                  `mkkprod_porsi_kelas` = ?
            WHERE `mkkprod_id` = ?
        ";

        return $this->db->query($sql, array($porsi, $id));
    }

    /**
    * @since    30may, 2014
    */
    function get_makul_prodi_by_idjoin($param){
        $query = '
            SELECT
              `mkkprod_id` AS id,
              `mkkprod_mkkur_id` AS idmk,
              `mkkprod_prodi_id` AS prodi_id,
              `mkkprod_related_id` AS rel_id,
              `mkkprod_porsi_kelas` AS porsi,
              (
              SELECT prodi_nama
              FROM program_studi
              WHERE prodi_id = mkkprod_prodi_id
              ) AS nama,
              (
              SELECT prodi_nama
              FROM program_studi
              WHERE prodi_id = (
                SELECT mkkprod_prodi_id
                FROM mkkur_prodi
                WHERE mkkprod_id = mp.mkkprod_related_id
                )
              ) AS program_studi_parent
            FROM `mkkur_prodi` mp
            WHERE mkkprod_id = "'.$param['id'].'"
        ';

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        return $ret[0];
    }

    function del_program_studi_on_makul_prodi($param){
        $query = '
            DELETE FROM mkkur_prodi where mkkprod_id = "'.$param['id'].'"
        ';

        $ret = $this->db->query($query);
        return $ret;
    }

}

?>
