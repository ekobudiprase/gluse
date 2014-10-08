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
    
    public function check_time_notover_limit($id_timespace, $timespace, $period_waktu){
        $sts = true;
        // $this->CI->load->model('Penjadwalan_model');
        // $maks_jam_sabtu = $this->CI->Penjadwalan_model->getJamMaksSabtu();

        // trapping jika undefined maka lgsg false, cari id_timespace yg lain
        if (!isset($timespace[$id_timespace])) {
            $sts = false;
            return $sts;
        }

        $hari_kls = ($timespace[$id_timespace]['waktu_hari']);
        $waktu_jam_mulai_kls = strtotime($timespace[$id_timespace]['waktu_jam_mulai']);
        $lama_menit_kelas = $period_waktu * 50;
        $waktu_jam_selesai_kls = date('H:i:s', strtotime('+'.$lama_menit_kelas.' minutes', $waktu_jam_mulai_kls));

        if (
            strtotime($waktu_jam_selesai_kls) > strtotime('17:20:00')
            OR ($hari_kls == 'jumat' 
                AND ($waktu_jam_mulai_kls) < strtotime('11:20:00')
                AND strtotime($waktu_jam_selesai_kls) > strtotime('11:20:00')
            )
            /*OR ($hari_kls == 'sabtu' 
                AND ($waktu_jam_mulai_kls) < strtotime($maks_jam_sabtu)
                AND strtotime($waktu_jam_selesai_kls) > strtotime($maks_jam_sabtu)
            )*/
        ) {
            $sts = false;
        }
        
        return $sts;
    }


    public function check_timespace_class_samepacket_not_sametime($kromosom, $timespace_utama, $individu_classprodi, $value, $id_timespace, $timespace, $prodi){
        $sts = true;

        // trapping jika undefined maka lgsg false, cari id_timespace yg lain
        if (!isset($timespace[$id_timespace])) {
            $sts = false;
            return $sts;
        }
   
        $value_prodi = explode('|', $value['kelas_prodi']);

        if (!empty($individu_classprodi['uni'])) {
            foreach ($individu_classprodi['uni'] as $i => $item) {
                if (
                    $kromosom[$item['id_kromosom']]['paket_smt'] == $value['paket_smt'] 
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
                        
                        if (
                            $kromosom[$item['id_kromosom']]['paket_smt'] == $value['paket_smt'] 
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



    public function check_separatesameclass_not_sameday($kromosom, $timespace_utama, $individu, $value, $id_timespace, $timespace){
        $sts = true;

        // trapping jika undefined maka lgsg false, cari id_timespace yg lain
        if (!isset($timespace[$id_timespace])) {
            $sts = false;
            return $sts;
        }

        foreach ($individu as $i => $item) {
            if ($kromosom[$item['id_kromosom']]['id_kelas'] == $value['id_kelas'] 
                AND $timespace_utama[$item['id_timespace']]['waktu_hari'] == $timespace[$id_timespace]['waktu_hari']) {
                $sts = false;
                break;
            }            
        }

        return $sts;
    }

    public function check_lecture_class_not_sametime($kromosom, $timespace_utama, $individu, $value, $id_timespace, $timespace){
        $sts = true;

        // trapping jika undefined maka lgsg false, cari id_timespace yg lain
        if (!isset($timespace[$id_timespace])) {
            $sts = false;
            return $sts;
        }        
        
        foreach ($individu as $i => $item) {
            if (!empty($kromosom[$item['id_kromosom']]['dosen'])) {
                $sama = 0;
                foreach ($kromosom[$item['id_kromosom']]['dosen'] as $j => $item_dsn) {
                    if (!empty($value['dosen'])) {
                        foreach ($value['dosen'] as $k => $item_dsn_current_class) {
                            if ($item_dsn == $item_dsn_current_class
                            ) {
                                $sama++;
                            }
                        }
                    }
                }

                // Jika mata kuliah paralel dosen sama, maka aplikasi error
                if(count($kromosom[$item['id_kromosom']]['dosen']) == $sama AND $timespace_utama[$item['id_timespace']]['id_waktu'] == $timespace[$id_timespace]['id_waktu']){
                    $sts = false;
                    break;
                }
            }
        }
        
        return $sts;
    }   

    public function check_capacity_class_ok($id_timespace, $timespace, $value){
        $sts = true;

        // trapping jika undefined maka lgsg false, cari id_timespace yg lain
        if (!isset($timespace[$id_timespace])) {
            $sts = false;
            return $sts;
        }

        if ($value['jml_peserta_kls'] > $timespace[$id_timespace]['kap_ruang']) {
            $sts = false;
        }

        return $sts;
    }
    
    public function check_timespace_paralelclass_is_sametime($kromosom, $timespace_utama, $individu, $value, $id_timespace, $timespace ){
        $sts = false;

        // trapping jika undefined maka lgsg false, cari id_timespace yg lain
        if (!isset($timespace[$id_timespace])) {
            $sts = false;
            return $sts;
        }

        foreach ($individu as $i => $item) {

            if ($kromosom[$item['id_kromosom']]['id_mkkur'] == $value['id_mkkur'] 
                AND $timespace_utama[$item['id_timespace']]['id_waktu'] == $timespace[$id_timespace]['id_waktu']
            ) {
                $sts = true;
                break;
            }            
        }

        return $sts;
    }

}
