<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package     Gluse
 * @subpackage  home
 * @author      Eko Budi Prasetyo
 * @version     0.0.0
 * @since       May 31, 2014
 */

class Config_model extends CI_Model {

    /**
    * @author    Eko Budi Prasetyo
    * @version   0.0.0
    * @since     May 31, 2014
    * @usedfor   -
    */
    function __construct(){
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }    

    /**
    * @author    Eko Budi Prasetyo
    * @version   0.0.0
    * @since     May 31, 2014
    * @usedfor   -
    */
    function get_configval_by_name($name_config){
        $str = 'AND c.`conf_name` = "'.$name_config.'"';

        $query = '
            SELECT 
                c.`conf_value` AS val
            FROM config c
            WHERE 1=1
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val[0]['val'];
    }

}

?>