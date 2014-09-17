<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package     Gluse
 * @subpackage  home
 * @author      Eko Budi Prasetyo
 * @version     0.0.0
 * @since       May 30, 2014
 */

class Build_data_model extends CI_Model {

    function __construct(){
        // Call the Model constructor
        parent::__construct();
    }

    function get_mkprodi(){
        
        $query = '
            SELECT
                ps.`prodi_prefix_mk` AS PRENAME,
                ps.`prodi_id` AS PRODI_ID,
                mkk.`mkkur_id` AS MK_ID,
                mkk.`mkkur_kode` AS MK_KODE,
                mkk.`mkkur_nama` AS MK_NAMA
            FROM program_studi ps
            LEFT JOIN mata_kuliah_kurikulum mkk ON prodi_prefix_mk = SUBSTR(mkkur_kode,1,3)
            ORDER BY prodi_id
        ';

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val;
    }

    function ins_mkprodi($mkid, $prodi_id){
        $sql = "
            INSERT INTO `mkkur_prodi`(
            `mkkprod_mkkur_id`, `mkkprod_prodi_id`)
            VALUES (?, ?);
        ";

        // echo '<pre>'; print_r($sql); 
        
        return $this->db->query($sql, array($mkid, $prodi_id)); 
    }


}

?>