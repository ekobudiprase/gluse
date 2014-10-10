<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package     Gluse
 * @subpackage  home
 * @author      Eko Budi Prasetyo
 * @version     0.0.0
 * @since       Oct 10, 2014
 */

class Bantu_model extends CI_Model {

    function __construct(){
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }

    function up_logproses($param){
        if (is_array($param))
            extract($param);

        $sql = "
            UPDATE log_proses
            SET logproses_data = ?
            WHERE logproses_kode = ?
        ";

        return $this->db->query($sql, array($data, $kode)); 
    }    

    function get_data_logproses($kode){
        $query = "
            SELECT 
			lp.logproses_data
			FROM log_proses lp
			WHERE lp.logproses_kode = '$kode'
        ";

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        return $ret[0]['logproses_data'];
    }

}

?>