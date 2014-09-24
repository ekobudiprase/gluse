<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package		Gluse
 * @subpackage	lib
 * @author		Eko Budi Prasetyo
 * @version		0.0.0
 * @since		Sept 24, 2014
 */

class Aturan_jadwal {
    var $CI;
    var $populasi = array();
    var $pc = null;
    var $pm = null;
    var $kelas = null;
    var $ruang = null;
    var $waktu = null;
    var $timespace = null;
    var $post = null;
    var $prodi = null;
    var $min_prosen_capacity = null;
    var $populasi_breeding = array();
    var $populasi_breeding_selected = array();
    var $total_fitness = 0;
    var $individu_breed = array();
    var $individu_update_calon = array();
    var $populasi_baru = array();
    var $kromosom = array();
    var $err_msg = '';

    /**
    * @author   Eko Budi Prasetyo
    * @version    0.0.0
    * @since    Sept 24, 2014
    * @usedfor    -
    */
    public function __construct(){
        $this->CI =& get_instance(); // for accessing the model of CI later
    }

    /**
    * @author		Eko Budi Prasetyo
    * @version		0.0.0
    * @since		Sept 24, 2014
    * @usedfor		-
    */
    public function test(){
    	echo 'tes';
    }


    public function check_neighborpacketclass_not_sametime($kromosom, $timespace_utama, $individu_classprodi, $value, $id_timespace, $timespace, $prodi){
        $sts = true;

        // trapping jika undefined maka lgsg false, cari id_timespace yg lain
        if (!isset($timespace[$id_timespace])) {
            $sts = false;
            return $sts;
        }

        $smt_neighbor = $this->get_neighbor_sametype_semester($value['paket_smt']);
        
        $value_prodi = explode('|', $value['kelas_prodi']);

        if (!empty($individu_classprodi['uni'])) {
            foreach ($individu_classprodi['uni'] as $i => $item) {
                if ($kromosom[$item['id_kromosom']]['sifat_makul'] == $value['sifat_makul'] 
                    AND $value['sifat_makul'] == 'W' 
                    AND (in_array($kromosom[$item['id_kromosom']]['paket_smt'], $smt_neighbor)) 
                    AND $timespace_utama[$item['id_timespace']]['id_waktu'] == $timespace[$id_timespace]['id_waktu']
                ) {
                    $sts = false;
                    break;
                }            
            } 
        }

        if ($value['is_universal'] == '0' && !empty($individu_classprodi['pro'])) {
            foreach ($prodi as $t => $pr) {
                if (isset($individu_classprodi['pro'][$t]) && !empty($individu_classprodi['pro'][$t]) AND in_array($pr['prodi_id'],$value_prodi)) {
                    foreach ($individu_classprodi['pro'][$t] as $i => $item) {
                        if ($kromosom[$item['id_kromosom']]['sifat_makul'] == $value['sifat_makul'] 
                            AND $value['sifat_makul'] == 'W' 
                            AND (in_array($kromosom[$item['id_kromosom']]['paket_smt'], $smt_neighbor)) 
                            AND $timespace_utama[$item['id_timespace']]['id_waktu'] == $timespace[$id_timespace]['id_waktu']
                        ) {
                            $sts = false;
                            break;
                        }            
                    }
                }                
            }
        }

        return $sts;
    }



    function get_neighbor_sametype_semester($smt){
        $arr_smt_ganjil = array(1,3,5,7);
        $arr_smt_genap = array(2,4,6,8);
        $arr_neighbor = array();

        if ( in_array($smt, $arr_smt_ganjil)) {
            foreach ($arr_smt_ganjil as $key => $value) {
                if ( ($smt == $value) ) {
                    if ( isset($arr_smt_ganjil[$key-1]) ) {
                        $arr_neighbor[] = $arr_smt_ganjil[$key-1];
                    }
                    if ( isset($arr_smt_ganjil[$key+1]) ) {
                        $arr_neighbor[] = $arr_smt_ganjil[$key+1];
                    }
                    
                }
            }
        }else{
            foreach ($arr_smt_genap as $key => $value) {
                if ( ($smt == $value) ) {
                    if ( isset($arr_smt_genap[$key-1]) ) {
                        $arr_neighbor[] = $arr_smt_genap[$key-1];
                    }
                    if ( isset($arr_smt_genap[$key+1]) ) {
                        $arr_neighbor[] = $arr_smt_genap[$key+1];
                    }
                    
                }
            }
        }

        return $arr_neighbor;
    }
}
