<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package     Gluse
 * @subpackage  home
 * @author      Eko Budi Prasetyo
 * @version     0.0.0
 * @since       June 30, 2014
 */

class Dosen_model extends CI_Model {

    /**
    * @since    30may, 2014
    */
    function __construct(){
        // Call the Model constructor
        parent::__construct();
        // $this->load->database();
    }

    /**
    * @author   Eko Budi Prasetyo
    * @version  0.0.0
    * @since    30may, 2014
    * @usedfor  -
    */
    function get_count_dosen($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT count(dsn_id) as total
            FROM dosen ps
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
    function get_dosen($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        
        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                  `dsn_id` AS id,
                  `dsn_nip` AS nip,
                  `dsn_nama` AS nama
            FROM `dosen`
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
    * @since  2 July, 2014
    */
    function get_count_waktu($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT count(waktu_id) as total
            FROM waktu ps
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val[0]['total'];
    }

    /**
    * @since 2 July, 2014
    */
    function get_waktu($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        
        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                  `waktu_id` AS id,
                  `waktu_hari` AS hari,
                  concat(waktu_jam_mulai," - ",waktu_jam_selesai) AS jam
            FROM `waktu`
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
    function get_dosen_by_id($filter){
        if (is_array($filter))
            extract($filter);
        $str = ''; 

        if (!empty($id)) {
            $str = "AND dsn_id = $id";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                  `dsn_id` AS id,
                  `dsn_nip` AS nip,
                  `dsn_nama` AS nama
            FROM `dosen`
            WHERE 1=1
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret[0];
    }

    function update_dosen($param){
        if (is_array($param))
            extract($param);

        $sql = "
            UPDATE `dosen`
            SET
                  `dsn_nip` = ?,
                  `dsn_nama` = ?
            WHERE `dsn_id` = ?
        ";

        return $this->db->query($sql, array($nip, $nama, $id));
    }

    function add_dosen($param){
        if (is_array($param))
            extract($param);

        $sql = "
            INSERT INTO `dosen`
            (`dsn_nip`,`dsn_nama`)
            VALUES (?,?)
        ";

        return $this->db->query($sql, array($nip, $nama));
    }

    function del_dosen($param){
        if (is_array($param))
            extract($param);

        $sql = "
            DELETE FROM dosen
            WHERE dsn_id = ?
        ";

        return $this->db->query($sql, array($id)); 
    }

    /**
    * @since 01 july, 2014
    */
    function get_dosen_waktu($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        
        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
              `dsn_id` AS id,
              `dsn_nip` AS nip,
              `dsn_nama` AS nama
            FROM dosen_waktu dw
            LEFT JOIN `dosen` d ON dw.`dsnwkt_dsn_id` = d.`dsn_id`
            --search--
            GROUP BY dsn_id
            --limit--
        ';

        $query = str_replace('--search--', $str, $query);
        $query = str_replace('--limit--', $limit, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret;
    }

    function get_count_dosen_waktu($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT COUNT(*) AS total FROM (
                SELECT COUNT(dsn_id)
                FROM dosen_waktu dw
                LEFT JOIN `dosen` d ON dw.`dsnwkt_dsn_id` = d.`dsn_id`
                --search--
                GROUP BY dsn_id
            ) t
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val[0]['total'];
    }

    function add_dosen_waktu($param){
        if (is_array($param))
            extract($param);

        $sql = "
            INSERT INTO `dosen_waktu`
            (`dsnwkt_dsn_id`,`dsnwkt_wkt_id`)
            VALUES (?,?)
        ";

        return $this->db->query($sql, array($id_dosen, $id_waktu));
    }

    /**
    * @since    30may, 2014
    */
    function get_waktu_dosen_by_id($filter){
        if (is_array($filter))
            extract($filter);
        $str = ''; 

        if (!empty($id)) {
            $str = "AND dsnwkt_dsn_id = $id";   
        }

        $query = '
            SELECT
                  dsnwkt_id AS id,
                  dsnwkt_dsn_id AS id_dosen,
                  dsnwkt_wkt_id AS id_waktu,
                  w.`waktu_hari` AS hari,
                  CONCAT(w.`waktu_jam_mulai`," - ",w.`waktu_jam_selesai`) AS jam
            FROM dosen_waktu dw
            LEFT JOIN waktu w ON dw.`dsnwkt_wkt_id` = w.`waktu_id`
            where 1=1 
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret;
    }

    function delete_dosen_waktu_by_id($id){

        $sql = "
            DELETE FROM `dosen_waktu`
            WHERE dsnwkt_dsn_id = ?
        ";

        return $this->db->query($sql, array($id)); 
    }

}

?>
