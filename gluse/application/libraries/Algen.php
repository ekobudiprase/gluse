<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

ini_set('max_execution_time', 0);
// set_error_handler("var_dump");

/**
 * @package     Gluse
 * @subpackage  lib/bantu
 * @author      Eko Budi Prasetyo
 * @version     0.0.0
 * @since       May 30, 2014
 */

class Algen {
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
    var $status_rule = '';
    var $log_proses = array();
    /**
    * @author   Eko Budi Prasetyo
    * @version    0.0.0
    * @since    May 31, 2014
    * @usedfor    -
    */
    public function __construct(){
        $this->CI =& get_instance(); // for accessing the model of CI later
        $this->CI->load->library('bantu');
        $this->CI->load->library('aturan_jadwal');
        $this->CI->load->library('session');
    }

    /*
    Outline of the Basic Genetic Algorithm

    [Start] Generate random population of n chromosomes (suitable solutions for the problem)
    [Fitness] Evaluate the fitness f(x) of each chromosome x in the population
    [New population] Create a new population by repeating following steps until the new population is complete
        [Selection] Select two parent chromosomes from a population according to their fitness (the better fitness, the bigger chance to be selected)
        [Crossover] With a crossover probability cross over the parents to form a new offspring (children). If no crossover was performed, offspring is an exact copy of parents.
        [Mutation] With a mutation probability mutate new offspring at each locus (position in chromosome).
        [Accepting] Place new offspring in a new population 
    [Replace] Use new generated population for a further run of algorithm
    [Test] If the end condition is satisfied, stop, and return the best solution in current population
    [Loop] Go to step 2 
    */


    /**
    * @author       Eko Budi Prasetyo
    * @since        June 14, 2014
    */
    public function initialize($kelas, $ruang, $waktu, $post, $prodi, $min_prosen_capacity){
        $this->kelas = $kelas;
        $this->ruang = $ruang;
        $this->waktu = $waktu;
        $this->post = $post;
        $this->pc = $post['pc'];
        $this->pm = $post['pm'];
        $this->prodi = $prodi;
        $this->min_prosen_capacity = $min_prosen_capacity;

        /*menginisiasi matriks timespace yg berisi ruang, hari, dan jam*/
        $i = 0;
        foreach ($this->ruang as $key => $value) {
            foreach ($this->waktu as $a => $item) {
                $this->timespace[] = array(
                    'id_timespace' => $i++,
                    'id_ruang' => $value['ru_id'],
                    'id_waktu' => $item['waktu_id'],
                    'waktu_hari' => $item['waktu_hari'],
                    'waktu_jam_mulai' => $item['waktu_jam_mulai'],
                    // 'label' => $value['ru_nama'].', '.$item['waktu_hari'].' '.$item['waktu_jam_mulai'].'-'.$item['waktu_jam_selesai'],
                    'label' => $value['ru_nama'].', '.$item['waktu_hari'].' '.$item['waktu_jam_mulai'].'-',
                    'kap_ruang' => $value['ru_kapasitas'],
                    'status' => '',
                    'status_nested' => ''
                );
            }
        }
        
    }

    /**
    * @author       Eko Budi Prasetyo
    * @since        May 30, 2014
    */
    public function generate_population(){
        $this->kromosom = $this->create_information_class(); // buat individu
        $this->log_proses['kromosom'] = $this->kromosom;
        // $jml = 0;
        // foreach ($this->kromosom as $i => $item) {
        //     $jml = $jml + $item['period'];
        // }
        // echo '<pre>'; print_r($jml); 
        // echo '<pre>'; print_r($this->kromosom); exit();
        /*
        bangkitkan populasi yang terdiri dari sejumlah individu
        */
        $this->classinfo = array();
        $this->populasi = array(); // empty population each generation
        for ($i=0; $i < $this->post['jml_individu']; $i++) { 
            $individu = $this->create_individu(); // buat individu
            
            // $individu = $this->repair_kelas_on_hardrule($individu);

            $this->populasi[] = $individu; 
            // break;
        }

        // echo '<pre>jumlah kromosom: '; print_r(count($this->kromosom)); echo ', </pre>';
        // foreach ($this->populasi as $key => $value) {
        // 	echo '<pre>'; print_r(count($value)); echo '</pre>';
        // }
        // exit();

    }

    public function create_information_class(){
        $class = array();
        $id_individu = 0;
        foreach ($this->kelas as $key => $value) {
            // echo '<pre>'; print_r($slot); echo '</pre>';
            if (!empty($value['format_jadwal'])) {
                $period = explode('-', $value['format_jadwal']);
                foreach ($period as $i => $item) {
                    $period_waktu = $item;
                    $arr_data = compact('class','period_waktu','value', 'id_individu');
                    $ret_data = $this->make_class($arr_data);

                    extract($ret_data);                    
                }
            }else{
                $period_waktu = $value['sks'];
                $arr_data = compact('class','period_waktu','value', 'id_individu');
                $ret_data = $this->make_class($arr_data);

                extract($ret_data); 
            }         

            /*if ($key == 0) {
                break;
            }*/

        }

        return $class;
    }
    
    /*
    Aturan umum : 
    1. Kelas mata kuliah yang sama harus waktu yang sama.
    2. Kelas mata kuliah yang satu paket harus beda waktu.
    3. Kapasitas ruang >= jumlah peserta.
    4. Dosen tidak mengajar kelas pada waktu yang sama.
    5. kelas makul sama yg dipecah sks nya diadakan pada hari yang berbeda.
    6. Kelas makul paket wajib berdekatan jenis smt harus beda waktu.
    */
    public function create_individu(){
        $individu = array(); // untuk menampung sejumlah individu yang mewakili jadwal
        $makul_grup = array(); // untuk mengelompokan kelas berdasar matakuliahnya.
        $waktudistinct_grup = array(); // untuk menampung waktu_id hasil pengelompokan kelas berdasar makulnya.
        $timespace = $this->timespace; // matriks data ruang, hari, dan waktu
        $grup_kemipaan = array();
        $waktudistinct_grup_kemipaan = array();
        $status_reset_individu = false;
        // echo '<pre>'; print_r($timespace); echo '</pre>'; exit();

        foreach ($this->kromosom as $key => $value) {
            $arr_data = compact('timespace','individu','value', 'makul_grup', 'waktudistinct_grup', 'grup_kemipaan','waktudistinct_grup_kemipaan', 'status_reset_individu');
            
            $ret_data = $this->get_feasible_individu($arr_data);
            if ($status_reset_individu) {
                unset($individu);
                unset($grup_kemipaan);
                unset($waktudistinct_grup_kemipaan);
                unset($makul_grup);
                unset($waktudistinct_grup);
                unset($timespace);

            	return $this->create_individu();
            }
            // if ($value['id_individu'] == 235) { 
            //     break;
            // }

            extract($ret_data);
        }

        // unset($makul_grup);
        // unset($waktudistinct_grup);
        // unset($timespace);
        // unset($ret_data);

        // $this->CI->bantu->debugPreviewJadwal($individu); 
        // exit();
        return $individu;
    }

    public function get_feasible_individu($arr_data){
        extract($arr_data);
        
        /*
        menentukan jadwal ruang & waktu untuk kelas diwakili oleh id_timespace
        cek apakah kelas makul yang sama sudah ada sebelumnya di kelas terjadwal
        kelas makul yang sama adalah kelas paralel yang makulnya sama
        jika tidak maka id_timespace bisa diambil dari matriks ruang & waktu 
        jika iya maka id_timespace diambil dari matriks ruang & waktu yg lebih spesifik, 
        yakni yg waktunya sama dgn kls terjdwal sebelumnya yg makul sama. Karna ada aturan
        kelas paralel diadakan dalam waktu yg sama.
        */
        $period_waktu = $value['period'];
        $individu_classprodi = $this->break_individu_prodi($individu); // uni atau bukan

        $kode_kemipaan = $value['id_mkkur'].'-'.$period_waktu.'-'.$value['kls_jadwal_merata'].'-'.$value['kls_id_grup_jadwal'];
        $status_kemipaan = !in_array($kode_kemipaan, $grup_kemipaan) && ($value['kls_jadwal_merata'] == '1');
        
        if ($value['kls_jadwal_merata'] == '1') {
            if ( $status_kemipaan) {
                $id_timespace = $this->getRandomTimespace($individu_classprodi, $individu, $value, $timespace, $period_waktu, 0);

                $waktudistinct_grup_kemipaan[] = $timespace[$id_timespace]['id_waktu'];
                $grup_kemipaan[] = $kode_kemipaan;
            }else{
                $id_timespace = $this->get_random_local($individu_classprodi, $individu, $value, $timespace, $grup_kemipaan, $waktudistinct_grup_kemipaan, $period_waktu);

            }
            
        }else{
            
            if ( !in_array($value['id_mkkur'].'-'.$period_waktu, $makul_grup) ) {

                $id_timespace = $this->getRandomTimespace($individu_classprodi, $individu, $value, $timespace, $period_waktu, 0);

                $waktudistinct_grup[] = $timespace[$id_timespace]['id_waktu'];
                $makul_grup[] = $value['id_mkkur'].'-'.$period_waktu;
            }else{

                $id_timespace = $this->get_random_local($individu_classprodi, $individu, $value, $timespace, $makul_grup, $waktudistinct_grup, $period_waktu);

            }
            
        }
        if ($id_timespace == 'nochance') {
        	$status_reset_individu = true;
        }else{
        	/*
	        menyimpan hasil ruang & waktu untuk kelas, beserta periodenya
	        */        
	        $waktu_jam_selesai_kls = $this->get_jam_selesai_kelas($timespace[$id_timespace]['waktu_jam_mulai'], $period_waktu);
	        $individu[] = array(
	            'id_kromosom' => $value['id_individu'],
	            'nama_kelas' => $value['nama_kelas'],
	            'id_timespace' => $timespace[$id_timespace]['id_timespace'],
	            'id_waktu' => $timespace[$id_timespace]['id_waktu'],
	            'period' => $value['period'],
	            'waktu_hari' => $timespace[$id_timespace]['waktu_hari'],
	            'id_ruang' => $timespace[$id_timespace]['id_ruang'],
	            'waktu_jam_mulai' => $timespace[$id_timespace]['waktu_jam_mulai'],
	            'label_timespace' => $timespace[$id_timespace]['label'].$waktu_jam_selesai_kls,
	            'kap_ruang' => $timespace[$id_timespace]['kap_ruang'],
	            'waktu_jam_selesai_kls' => $waktu_jam_selesai_kls
	        );

	        // membuat grup kelas per matakuliah secara dinamis
	        /*if (!in_array($value['id_mkkur'].'-'.$period_waktu, $makul_grup)) {
	            
	            $waktudistinct_grup[] = $timespace[$id_timespace]['id_waktu'];
	        }*/
	        
	        
	        // menghapus index beserta nilainya untuk data ruang & waktu yg dipakai kelas untuk jadwal
	        for ($t=0; $t < $period_waktu; $t++) {
	            $id_timespace += $t;
	            $timespace[$id_timespace]['status'] = 1;
	            // unset($timespace[$id_timespace]);
	        }
        }
        // echo "<br>";
        // echo '<pre>'; print_r($makul_grup); 
        // if ($value['id_individu'] == 230) { 

        //     echo '<pre>'; print_r($individu); 
        //     exit();
        // }
        
        
        // $timespace = array_values($timespace); // set ulang index matriks ruang & waktu 

        $ret_data = compact('timespace','individu','value', 'makul_grup', 'waktudistinct_grup', 'grup_kemipaan', 'waktudistinct_grup_kemipaan', 'status_reset_individu');
        return $ret_data;

    }

    /*
    fungsi ini digunakan untuk mencari id_timespace yg nanti dipakai kelas paralel matakuliah yg sama
    */
    function get_random_local($individu_classprodi, $individu, $kelas, $timespace, $makul_grup, $waktudistinct_grup, $period_waktu, $id_ts=null){
        
        /*
        membuat matriks ruang & waktu timespace_grup_waktu khusus untuk makul yang sama,
        matriks ini waktunya sama, hanya ruang yang beda
        */
        $period_waktu = $kelas['period'];
        if (!empty($makul_grup)) {
            if ($kelas['kls_jadwal_merata']==1) {
                $kode_kemipaan = $kelas['id_mkkur'].'-'.$period_waktu.'-'.$kelas['kls_jadwal_merata'].'-'.$kelas['kls_id_grup_jadwal'];
                foreach ($makul_grup as $a => $mk) {
                    if ($mk == $kode_kemipaan) {
                        $timespace_grup_waktu = $this->get_id_timespace_for_sametime($timespace, $waktudistinct_grup[$a]);
                    }
                }
            }else{
                foreach ($makul_grup as $a => $mk) {
                    if ($mk == $kelas['id_mkkur'].'-'.$period_waktu) {
                        $timespace_grup_waktu = $this->get_id_timespace_for_sametime($timespace, $waktudistinct_grup[$a]);
                    }
                }
            }
            
        }        
        
        if ($id_ts != null) {
            $id_timespace = $this->getRandomTimespace($individu_classprodi, $individu, $kelas, $timespace, $period_waktu, 0, $timespace_grup_waktu, $id_ts);
        }else{
            $id_timespace = $this->getRandomTimespace($individu_classprodi, $individu, $kelas, $timespace, $period_waktu, 0, $timespace_grup_waktu);
        	
        }

        return $id_timespace;
    }    

    function get_id_timespace_for_sametime($timespace, $id_waktu){
        $temp = array();
        foreach ($timespace as $key => $value) {
            if ((isset($value['id_waktu']) and ($value['id_waktu'] == $id_waktu)) and $value['status'] != 1) {
                $temp[] = array(
                    'id_timespace' => $key,
                    'data' => $value
                );
            }
            
        }
        
        return $temp;
        // unset($temp);
    }

    function cekPeluang($timespace, $timespace_grup_waktu=null){
    	$jml=0;
        if ($timespace_grup_waktu != null) {
	        foreach ($timespace_grup_waktu as $i => $item) {
	        	if ($item['data']['status_nested']==1) {
	        		$jml = $jml +1;
	        	}
	        }
	        if ($jml == count($timespace_grup_waktu)) {
	        	echo "peluang 0%"; 
	        	echo '<pre>'; print_r($value); echo '</pre>';
	        	exit();
	        }
        }else{
	        foreach ($timespace as $i => $item) {
	        	if ($item['status_nested']==1) {
	        		$jml = $jml +1;
	        	}
	        }
	        if ($jml == count($timespace)) {
	        	echo "peluang 0%"; 
	        	echo '<pre>'; print_r($value); echo '</pre>';
	        	exit();
	        }
        }
    }

    function getRandomTimespace($individu_classprodi, $individu, $value, $timespace, $period_waktu, $iteration, $timespace_grup_waktu=null, $id_ts=null ){
        $iteration++;
        // echo 'iterasi ke-'.$iteration.' : ';  

        // $this->cekPeluang($timespace, $timespace_grup_waktu);

        if ($iteration == 10000) {
        	return 'nochance';
        	// return $this->create_individu();
        	// echo '<pre>'; print_r($this->status_rule); echo '</pre>';
        	// echo '<pre>'; print_r($value); echo '</pre>';
         //    echo "Iterasi ke-33333"; exit();
        }else{
        	 // echo ''.$iteration.', ';  
        }
        
        if ($id_ts==null) {
            if ($timespace_grup_waktu != null) { // timespace lokal
                $id_timespace_grup_waktu = mt_rand(0,(count($timespace_grup_waktu)-1));
                $id_timespace = $timespace_grup_waktu[$id_timespace_grup_waktu]['id_timespace'];
            }else{ // timespace global
                $id_timespace = mt_rand(0,(count($timespace)-1));
            } 
        }else{
            $id_timespace = $id_ts;
        }
        
        

        $sts = true;
        for ($t=0; $t < $period_waktu; $t++) {
            $ts_ok = true;
            $id_ts = $id_timespace + $t; 
            // if (!isset($timespace[$id_ts]) OR $timespace[$id_ts]['status'] == 1 OR $timespace[$id_ts]['status_nested']==1) {
            if (!isset($timespace[$id_ts]) OR $timespace[$id_ts]['status'] == 1 ) {
                $ts_ok = false;
            }
            /*else{
            	$timespace[$id_ts]['status_nested'] = 1;
            }*/
            $sts = $sts && $ts_ok;
        }
        // if ($value['id_individu'] == 158) { 
        //     echo '<pre>timespace_grup_waktu: '; print_r($timespace_grup_waktu); 
        // }
        // if ($value['id_individu'] == 16) { 
            // echo '<pre>timespace_grup_waktu: '; print_r($timespace);
            // exit(); 
        // }
        // echo $id_timespace.', ';
        // echo '<pre>'; print_r($timespace[$id_timespace]); echo '</pre>';

        /*kondisi apakah cari timespace lokal atau global*/
        if ($timespace_grup_waktu != null) {
            if (!$sts) {
                return $this->getRandomTimespace($individu_classprodi, $individu, $value, $timespace, $period_waktu, $iteration, $timespace_grup_waktu);
            }else{

                $rule_ok = $this->check_on_hardrule_for_paralelclass($individu_classprodi, $individu, $value, $id_timespace, $timespace, $iteration);
                
                if ($rule_ok) {
                    return $id_timespace;
                }else{
                    return $this->getRandomTimespace($individu_classprodi, $individu, $value, $timespace, $period_waktu, $iteration, $timespace_grup_waktu);
                }

            } 
        }else{

            if (!$sts) {
                return $this->getRandomTimespace($individu_classprodi, $individu, $value, $timespace, $period_waktu, $iteration);
            }else{
                $rule_ok = $this->check_on_hardrule($individu_classprodi, $individu, $value, $id_timespace, $timespace, $period_waktu, $iteration);
                if ($rule_ok) {
                    return $id_timespace;
                }else{
                    return $this->getRandomTimespace($individu_classprodi, $individu, $value, $timespace, $period_waktu, $iteration);
                }
            }     
        }
           
    }


    // =====================================================================================================================
    /*
    pada kelas paralel yg mata kuliah sama, waktu harus sama dan di tempat yang berbeda
    */

    public function check_on_hardrule($individu_classprodi, $individu, $value, $id_timespace, $timespace, $period_waktu, $iteration=null){
        // if (!empty($individu)) {

        
            $sts = true;
            // $sts = $sts && $this->check_timespace_paralelclass_is_sametime($id_timespace, $individu, $timespace, $value);
            $stsrule_1 = $this->CI->aturan_jadwal->check_time_notover_limit($id_timespace, $timespace, $period_waktu);
            $stsrule_2 = $this->CI->aturan_jadwal->check_timespace_class_samepacket_not_sametime($this->kromosom, $this->timespace, $individu_classprodi, $value, $id_timespace, $timespace, $this->prodi);
            $stsrule_3 = $this->CI->aturan_jadwal->check_capacity_class_ok($id_timespace, $timespace, $value);
            $stsrule_4 = $this->CI->aturan_jadwal->check_lecture_class_not_sametime($this->kromosom, $this->timespace, $individu, $value, $id_timespace, $timespace);
            $stsrule_5 = $this->CI->aturan_jadwal->check_separatesameclass_not_sameday($this->kromosom, $this->timespace, $individu, $value, $id_timespace, $timespace);
            $stsrule_6 = $this->CI->aturan_jadwal->check_neighborpacketclass_not_sametime($this->kromosom, $this->timespace, $individu_classprodi, $value, $id_timespace, $timespace, $this->prodi);

            // $sts = $stsrule_1 && $stsrule_2 && $stsrule_3 && $stsrule_4 && $stsrule_5 &&  $stsrule_6;
            $sts = $stsrule_1 && $stsrule_2 && $stsrule_3 && $stsrule_4 && $stsrule_5 && $stsrule_6 ;

            // $this->status_rule[$value['id_individu']][$iteration]['ket'] = '';
            // if (!$stsrule_1) {
            //     $this->status_rule[$value['id_individu']][$iteration]['ket'] .= "stsrule_1, ";
            // }
            // if (!$stsrule_2) {
            //     $this->status_rule[$value['id_individu']][$iteration]['ket'] .= "stsrule_2, ";
            // }
            // if (!$stsrule_3) {
            //     $this->status_rule[$value['id_individu']][$iteration]['ket'] .= "stsrule_3, ";
            // }
            // if (!$stsrule_4) {
            //     $this->status_rule[$value['id_individu']][$iteration]['ket'] .= "stsrule_4, ";
            // }
            // if (!$stsrule_5) {
            //     $this->status_rule[$value['id_individu']][$iteration]['ket'] .= "stsrule_5, ";
            // }
            // if (!$stsrule_6) {
            //     $this->status_rule[$value['id_individu']][$iteration]['ket'] .= "stsrule_6, ";
            // }
             

            return $sts;
        /*}else{
            return true;
        }*/
        
    }

    function cek_kebenaran($param){
        foreach ($param as $key => $value) {
            if (!$value) {
                echo 'rule_idx_'.$key;
                // return $key;
                // break;
            }
        }

    }

    public function check_on_hardrule_for_paralelclass($individu_classprodi, $individu, $value, $id_timespace, $timespace, $iteration=null){
        if (!empty($individu)) {
            $sts = true;
            
            $stsrule_1 = $this->CI->aturan_jadwal->check_timespace_paralelclass_is_sametime($this->kromosom, $this->timespace, $individu, $value, $id_timespace, $timespace);
            
            $stsrule_2 = $this->CI->aturan_jadwal->check_capacity_class_ok($id_timespace, $timespace, $value);
            
            $stsrule_3 = $this->CI->aturan_jadwal->check_lecture_class_not_sametime($this->kromosom, $this->timespace, $individu, $value, $id_timespace, $timespace);
            
            $stsrule_4 = $this->CI->aturan_jadwal->check_separatesameclass_not_sameday($this->kromosom, $this->timespace, $individu, $value, $id_timespace, $timespace);
            $stsrule_5 = $this->CI->aturan_jadwal->check_neighborpacketclass_not_sametime($this->kromosom, $this->timespace, $individu_classprodi, $value, $id_timespace, $timespace, $this->prodi);
            
            $sts = $stsrule_1 && $stsrule_2 && $stsrule_3 && $stsrule_4 && $stsrule_5 ;

            // $this->status_rule[$value['id_individu']][$iteration]['ket'] = '';
            // if (!$stsrule_1) {
            //     $this->status_rule[$value['id_individu']][$iteration]['ket'] .= "stsrulepar_1, ";
            // }
            // if (!$stsrule_2) {
            //     $this->status_rule[$value['id_individu']][$iteration]['ket'] .= "stsrulepar_2, ";
            // }
            // if (!$stsrule_3) {
            //     $this->status_rule[$value['id_individu']][$iteration]['ket'] .= "stsrulepar_3, ";
            // }
            // if (!$stsrule_4) {
            //     $this->status_rule[$value['id_individu']][$iteration]['ket'] .= "stsrulepar_4, ";
            // }
            // if (!$stsrule_5) {
            //     $this->status_rule[$value['id_individu']][$iteration]['ket'] .= "stsrulepar_5, ";
            // }
            
            // $sts = $sts && $this->check_timespace_class_samepacket_not_sametime($id_timespace, $individu, $timespace, $value);

            return $sts;
        }else{
            return true;
        }
        
    }

    //===================================================================================================================


    public function get_jam_selesai_kelas($waktu_jam_mulai, $period_waktu){
    	$waktu_jam_mulai_kls = strtotime($waktu_jam_mulai);
        $lama_menit_kelas = $period_waktu * 50;
        $waktu_jam_selesai_kls = date('H:i:s', strtotime('+'.$lama_menit_kelas.' minutes', $waktu_jam_mulai_kls));

        return $waktu_jam_selesai_kls;
    }

    function break_individu_prodi($individu){
        // echo '<pre>'; print_r($individu); 
        $u = null;
        $p = null;
        foreach ($individu as $t => $ind) {
            // echo '<pre>'; print_r($this->kromosom[$ind['id_kromosom']]); 
            if ($this->kromosom[$ind['id_kromosom']]['is_universal'] == '1') {
                $u[] = $ind;
            }else{
                foreach ($this->prodi as $t => $pr) {
                    $kelas_prodi = explode('|', $this->kromosom[$ind['id_kromosom']]['kelas_prodi']);
                    if (!empty($kelas_prodi) AND in_array($pr['prodi_id'],$kelas_prodi)) {
                        $p[$t][] = $ind;
                    }
                }
                
            }
        }

        $return = array(
            "uni" => $u,
            "pro" => $p,
        );

        return $return;
    }

    public function make_class($arr_data){
        extract($arr_data);
        /*
        menyimpan hasil ruang & waktu untuk kelas, beserta periodenya
        */
        $class[] = array(
            'id_individu' => $id_individu,
            'id_kelas' => $value['id'],
            'id_mkkur' => $value['mkkur_id'],
            'period' => $period_waktu,
            'nama_kelas' => $value['nama_kelas'],
            'jml_peserta_kls' => $value['jml_peserta_kls'],
            'format_jadwal' => $value['format_jadwal'],
            'paket_smt' => $value['paket_smt'],
            'sifat_makul' => $value['sifat_makul'],
            'dosen' => $value['dosen'],
            'ruang_blok_prodi' => $value['ruang_blok_prodi'],
            'kelas_prodi' => $value['kelas_prodi'],
            'alternatif_waktu_ajar' => $value['alternatif_waktu_ajar'],
            'is_universal' => $value['is_universal'],
            'kls_jadwal_merata' => $value['kls_jadwal_merata'],
            'kls_id_grup_jadwal' => $value['kls_id_grup_jadwal']
        );

        $id_individu++;

        $ret_data = compact('class','period_waktu','value', 'id_individu');
        return $ret_data;
    }

    function cek_langgar_jam($individu){
    	$jam_akhir_kampus = '17:20:00';
        $hari_waktu_jumatan = 'jumat';
        $jam_waktu_jumatan = '11:20:00';
    	foreach ($individu as $key => $value) {
    		$hari_kls = ($this->timespace[$value['id_kromosom']]['waktu_hari']);
	        $waktu_jam_mulai_kls = strtotime($this->timespace[$value['id_kromosom']]['waktu_jam_mulai']);
	        $lama_menit_kelas = $this->kromosom[$value['id_kromosom']]['period'] * 50;
	        $waktu_jam_selesai_kls = date('H:i:s', strtotime('+'.$lama_menit_kelas.' minutes', $waktu_jam_mulai_kls));
	    	echo 'Kelas : '.$this->kromosom[$value['id_kromosom']]['id_kelas'].' => '.$this->timespace[$value['id_kromosom']]['id_ruang'].' '.$this->timespace[$value['id_kromosom']]['waktu_hari'].', '.$this->timespace[$value['id_kromosom']]['waktu_jam_mulai'].'-'.$waktu_jam_selesai_kls.' ';
	    	if (
	            strtotime($waktu_jam_selesai_kls) > strtotime($jam_akhir_kampus)
	            OR ($hari_kls == $hari_waktu_jumatan 
	                AND ($waktu_jam_mulai_kls) < strtotime($jam_waktu_jumatan)
	                AND strtotime($waktu_jam_selesai_kls) > strtotime($jam_waktu_jumatan)
	            )
	        ) {
	            echo '[bukti!!!!]';
	        }
	        echo '<br>';
    	}
    }

    function bentrok_sametime($kls, $kls_compare) {      
        
        $sts = $this->timespace[$kls['id_timespace']]['waktu_hari'] == $this->timespace[$kls_compare['id_timespace']]['waktu_hari'];

        $start_time = strtotime($this->timespace[$kls['id_timespace']]['waktu_jam_mulai']);
        $waktu_jam_selesai_kls = $this->get_jam_selesai_kelas($this->timespace[$kls['id_timespace']]['waktu_jam_mulai'], $this->kromosom[$kls['id_kromosom']]['period']);
        $end_time = strtotime($waktu_jam_selesai_kls);

        $start_compare_time = strtotime($this->timespace[$kls_compare['id_timespace']]['waktu_jam_mulai']);
        $waktu_jam_selesai_kls_compare = $this->get_jam_selesai_kelas($this->timespace[$kls_compare['id_timespace']]['waktu_jam_mulai'], $this->kromosom[$kls_compare['id_kromosom']]['period']);
        $end_compare_time = strtotime($waktu_jam_selesai_kls_compare);

        $sts_time_between = ( ( ($start_time >= $start_compare_time) && ($start_time <= $end_compare_time) OR ( ($end_time >= $start_compare_time) && ($end_time <= $end_compare_time) ) ) );
        $sts = $sts && $sts_time_between;

        return $sts;
    }

    public function separate_kelas_makul_wajib_pil($individu){

        foreach ($individu as $key => $value) {
            if (strtolower($this->kromosom[$value['id_kromosom']]['sifat_makul']) == 'w') {
                $makul_wajib[] = $value;
            }
            if (strtolower($this->kromosom[$value['id_kromosom']]['sifat_makul']) == 'p') {
                $makul_pil[] = $value;
            }
        }

        $separate_makul = array(
            'makul_wajib' => $makul_wajib,
            'makul_pil' => $makul_pil
        );

        // unset($makul_wajib);
        // unset($makul_pil);

        return $separate_makul;
    }

    public function count_fitness_based_rule_kelasmakul_pilihan_wajib_not_sametime($individu){
    	$ind_classprodi = $this->break_individu_prodi($individu);    	

    	foreach ($ind_classprodi['pro'] as $i => $arr_kromosom) {			
			foreach ($arr_kromosom as $k => $kr_pr) {
				$data_perprod[$i][] = $kr_pr;
			}
			foreach ($ind_classprodi['uni'] as $j => $kr_un) {
				$data_perprod[$i][] = $kr_un;
				
			}
		}

		$jumlah_perprod = 0;
		$jumlah_makulpil = 0;
		foreach ($data_perprod as $key => $indi_pr) {
			$separate_makul = $this->separate_kelas_makul_wajib_pil($indi_pr);
			$jumlah = 0;
	        foreach ($separate_makul['makul_pil'] as $i => $pil) {
	            $smt_neighbor = $this->CI->aturan_jadwal->get_neighbor_sametype_semester($this->kromosom[$pil['id_kromosom']]['paket_smt']);

	            $bentrok[$i] = 0;
	            $bentrok_ket[$i] = '';

	            $sts_bentrok = false;
	            foreach ($separate_makul['makul_wajib'] as $j => $wjb) {
	                if (in_array($this->kromosom[$wjb['id_kromosom']]['paket_smt'],$smt_neighbor) AND ($this->bentrok_sametime($pil,$wjb)) ) {
	                    $bentrok[$i]++;
	                    $bentrok_ket[$i] .= $this->kromosom[$wjb['id_kromosom']]['id_kelas'].', ';
	                    $sts_bentrok = true;
	                }
	            }

	            $separate_makul['makul_pil'][$i]['bentrok'] = $bentrok[$i];
	            $separate_makul['makul_pil'][$i]['bentrok_ket'] = $bentrok_ket[$i];

	            if ($sts_bentrok) {
	                $jumlah++;
	            }
	        }
	        $jumlah_makulpil = $jumlah_makulpil + count($separate_makul['makul_pil']);
	        $jumlah_perprod = $jumlah_perprod + $jumlah;
		}

		$fitness = $jumlah_perprod / $jumlah_makulpil;        

        // unset($separate_makul);
        // unset($smt_neighbor);
        // unset($i);
        // unset($bentrok);
        // unset($bentrok_ket);
        // unset($pil);
        // unset($wjb);

        return $fitness;
    }

    public function count_fitness_based_rule_kelasmakul_on_ruangblokprodi($individu){
        // echo '<pre>'; print_r($individu); 

        $score = 0;
        $i = 0;
        foreach ($individu as $key => $value) {
            $arr_ruangblokprodi[$key] = explode('|', $this->kromosom[$value['id_kromosom']]['ruang_blok_prodi']);
            if (!empty($arr_ruangblokprodi[$key]) AND !in_array($this->timespace[$value['id_timespace']]['id_ruang'], $arr_ruangblokprodi[$key])) {
                $score++;
            }
            if ( !empty($arr_ruangblokprodi[$key]) ) {
                $i++;
            }
            
        }

        // echo '<pre>'; print_r($arr_ruangblokprodi); 
        // echo '<pre>'; print_r(count($total_alt_kelas_blok)); 
        // echo '<pre>'; print_r($score); 
        // echo '<pre>'; print_r($i); 

        $fitness = $score / $i;

        // unset($arr_ruangblokprodi);

        return $fitness;
    }

    public function count_fitness_based_rule_kelasmakulsepaket_max_8_sks_sehari($individu){
        $arr_hari = array('senin', 'selasa', 'rabu', 'kamis', 'jumat');

        $total_langgar = 0;
        foreach ($arr_hari as $i => $hari) {
            $arr_grup_hari[$i] = array(
                'hari' => $hari,
                'data_prodi' => array()
            );
            $jml_prodi_langgar = 0;
            foreach ($this->prodi as $j => $prodi) {
                $arr_grup_hari[$i]['data_prodi'][$j] = array(
                    'prodi_id' => $prodi['prodi_id'],
                    'data_semester' => array()
                );
                $jml_smt_langgar = 0;
                for ($l=1; $l <= 8; $l++) { 
                    $arr_grup_hari[$i]['data_prodi'][$j]['data_semester'][$l] = array(
                        'paket_semester' => $l,
                        'data_kelas' => array()
                    );
                    $total_sks = 0;
                    foreach ($individu as $k => $kelas_terjadwal) {
                        $arr_kelas_prodi = explode('|', $this->kromosom[$kelas_terjadwal['id_kromosom']]['kelas_prodi']);                       
                        if ($hari == $this->timespace[$kelas_terjadwal['id_timespace']]['waktu_hari'] AND in_array($prodi['prodi_id'], $arr_kelas_prodi) AND $this->kromosom[$kelas_terjadwal['id_kromosom']]['paket_smt'] == $l) {
                            $total_sks += $this->kromosom[$kelas_terjadwal['id_kromosom']]['period'];
                            $arr_grup_hari[$i]['data_prodi'][$j]['data_semester'][$l]['data_kelas'][] = $kelas_terjadwal;                               
                        }                       
                    }
                    $arr_grup_hari[$i]['data_prodi'][$j]['data_semester'][$l]['total_sks'] = $total_sks;
                    if ($total_sks > 8) {
                        $jml_smt_langgar++;
                    }
                        
                }
                $arr_grup_hari[$i]['data_prodi'][$j]['jml_smt_langgar'] = $jml_smt_langgar;
                if ($jml_smt_langgar > 0) {
                    $jml_prodi_langgar++;
                }
            }
            $arr_grup_hari[$i]['jml_prodi_langgar'] = $jml_prodi_langgar;
            $total_langgar += $jml_prodi_langgar;
            // echo '<pre>'; print_r($jml_prodi_langgar); echo '</pre>';
        }
        // echo '<pre>'; print_r($arr_grup_hari); 

        $jml_semesta_himp = count($arr_hari) * count($this->prodi);
        $fitness = $total_langgar / $jml_semesta_himp;
        // echo '<pre>'; print_r($total_langgar); echo '</pre>';

        // unset($arr_hari);
        // unset($arr_grup_hari);
        // unset($i);
        // unset($hari);
        // unset($prodi);
        // unset($arr_kelas_prodi);
        // unset($kelas_terjadwal);
        // unset($total_langgar);
        // unset($jml_semesta_himp);

        return $fitness;

    }

    public function count_fitness_based_rule_kelas_filled_min_prosen_capacity($individu){
        // echo '<pre>'; print_r($individu); echo '</pre>';
        $melanggar = 0;
        foreach ($individu as $key => $value) {
            $harapan_jml = ($this->min_prosen_capacity / 100) * $this->timespace[$value['id_timespace']]['kap_ruang'];
            $individu[$key]['harapan_jml'] = ceil($harapan_jml);
            $individu[$key]['melanggar'] = 0;
            if ($this->kromosom[$value['id_kromosom']]['jml_peserta_kls'] < ceil($harapan_jml)) {
                $individu[$key]['melanggar'] = 1;
                $melanggar++;
                
            }
        }

        // echo '<pre>'; print_r($melanggar); echo '</pre>';
        // echo '<pre>'; print_r($individu); echo '</pre>';
        $fitness = $melanggar / count($individu);

        // unset($individu);
        // unset($harapan_jml);
        // unset($key);
        // unset($value);

        return $fitness;
        
    }

    public function count_fitness_based_rule_kelas_dosen_choose_their_time($individu){
        $jml_langgar = 0;
        foreach ($individu as $key => $value) {
            $arr_alternatif_waktu_ajar = explode('|', $this->kromosom[$value['id_kromosom']]['alternatif_waktu_ajar']);
            if (!in_array($this->timespace[$value['id_timespace']]['id_waktu'], $arr_alternatif_waktu_ajar)) {
                $jml_langgar++;
            }
        }

        $fitness = $jml_langgar / count($individu);
        // echo '<pre>'; print_r($fitness); 

        // unset($arr_alternatif_waktu_ajar);

        return $fitness;
    }

    public function transform_populasi(){
        foreach ($this->populasi as $key => $individu) {
            $this->populasi_breeding[$key]['fitness'] = 0;
            foreach ($individu as $i => $gen) {
                /*$this->populasi_breeding[$key]['arr_gen'][$i] = array(
                    'id_kelas' => $gen['id_kelas'],
                    'id_timespace' => $gen['id_timespace'],
                    'period' => $gen['period']
                );*/
                $this->populasi_breeding[$key]['arr_gen'][$i] = $gen;
            }
        }

        // echo '<pre>'; print_r($this->populasi_breeding); 
    }

    public function count_fitness(){
        
        $this->transform_populasi();
        $this->total_fitness = 0;
        foreach ($this->populasi as $i => $individu) {
            $populasi[$i]['fitness_rule_1'] = $this->count_fitness_based_rule_kelasmakul_pilihan_wajib_not_sametime($individu);
            $populasi[$i]['fitness_rule_2'] = $this->count_fitness_based_rule_kelasmakul_on_ruangblokprodi($individu);
            $populasi[$i]['fitness_rule_3'] = $this->count_fitness_based_rule_kelasmakulsepaket_max_8_sks_sehari($individu);
            $populasi[$i]['fitness_rule_4'] = $this->count_fitness_based_rule_kelas_filled_min_prosen_capacity($individu);
            $populasi[$i]['fitness_rule_5'] = $this->count_fitness_based_rule_kelas_dosen_choose_their_time($individu);

            $populasi[$i]['fitness'] = 1-(($populasi[$i]['fitness_rule_1'] + $populasi[$i]['fitness_rule_2'] + $populasi[$i]['fitness_rule_3'] + $populasi[$i]['fitness_rule_4'] + $populasi[$i]['fitness_rule_5']) / 5);
            /*foreach ($individu as $j => $kelas) {
                $this->populasi[$i][$j]['fitness'] = $this->count_gen_score_for_fitness($kelas, $individu);
            } */           

            /*if ($i == 0) {
                break;
            }*/
            $this->populasi_breeding[$i]['fitness'] = $populasi[$i]['fitness'];
            $this->total_fitness += $populasi[$i]['fitness'];
        }
        $this->log_proses['populasi_awal'] = $this->populasi_breeding;
        
        unset($populasi);

        // echo '<pre>'; print_r($populasi); 
        // echo '<pre>'; print_r($this->populasi); 
        // echo '<pre>'; print_r($this->populasi_breeding); 
    }

    public function roulette_wheel_selection(){
        $populasi_breeding = $this->populasi_breeding;
        foreach ($populasi_breeding as $key => $value) {
            $prob = $value['fitness'] / $this->total_fitness;
            $populasi_breeding[$key]['idx'] = $key;
            $populasi_breeding[$key]['prob'] = round($prob,5);

            if ($key == 0) {
                $rentangan[$key]['awal'] = 0;
            }else{
                $rentangan[$key]['awal'] = $rentangan[($key-1)]['akhir'] + 0.00001;
            }
            
            $rentangan[$key]['akhir'] = $rentangan[$key]['awal'] + $populasi_breeding[$key]['prob'];
            $random_number[$key] = mt_rand(0,100000)/100000;

        }
        // echo '<pre>'; print_r($random_number); 

        $pick_individu = array();
        foreach ($random_number as $i => $val) {
            foreach ($rentangan as $j => $vale) {
                // if ($val >= $vale['awal'] and $val <= $vale['akhir'] and !in_array($j, $pick_individu)) {
                if ($val >= $vale['awal'] and $val <= $vale['akhir'] ) {
                    $pick_individu[] = $j;
                }
            }
        }

        // foreach ($pick_individu as $key => $value) {
        for ($i=0; $i < $this->post['jml_individu']; $i++) { 
            $populasi_breeding_selected[] = $populasi_breeding[$pick_individu[$i]];
        }

        foreach ($populasi_breeding_selected as $key => $value) {
            $populasi_breeding_selected[$key]['val_random'] = mt_rand(0,100000)/100000; // for selecting on crossover
        }

        $this->total_fitness = 0; // set total fitness 0 karna sudah digunakan 
        $this->populasi_breeding_selected = $populasi_breeding_selected;

        // unset($populasi_breeding_selected);
        // unset($pick_individu);
        // unset($populasi_breeding);
        // unset($random_number);
        // unset($rentangan);
    }

    public function repair_duplikasi_kelas_timespace($individu){
        $arr_kelas = array();
        $arr_id_timespace = array();
        $kelas_duplikasi = array();
        $timespace = $this->timespace;

        foreach ($individu as $key => $value) {
            if (!in_array($value['id_timespace'], $arr_id_timespace)) {
                $arr_id_timespace[] = $value['id_timespace'];
                $arr_kelas[] = $value;
            }else{
                $kelas_duplikasi[] = array(
                    'idx' => $key,
                    'data' => $value
                );
            }
        }

        $makul_grup = array();
        $waktudistinct_grup = array();
        foreach ($arr_kelas as $key => $value) {
            foreach ($timespace as $i => $item) {
                if ( $value['id_timespace'] == $i) {
                    for ($j=0; $j < $this->kromosom[$value['id_kromosom']]['period']; $j++) { 
                        unset($timespace[$j+$i]);
                    }
                    
                } 
            }
            // membuat grup kelas per matakuliah secara dinamis
            if (!in_array($this->kromosom[$value['id_kromosom']]['id_mkkur'].'-'.$this->kromosom[$value['id_kromosom']]['period'], $makul_grup)) {
                $makul_grup[] = $this->kromosom[$value['id_kromosom']]['id_mkkur'].'-'.$this->kromosom[$value['id_kromosom']]['period'];
                $waktudistinct_grup[] = $this->timespace[$value['id_kromosom']]['id_waktu'];
            }
        }
        $timespace = array_values($timespace);

    	if (!empty($kelas_duplikasi)) {
    		foreach ($kelas_duplikasi as $key => $value) {
	            $id_timespace = mt_rand(0,(count($timespace)-1));

	            $individu[$value['idx']]['id_timespace'] = $id_timespace;
	            // $individu[$value['idx']]['id_waktu'] = $timespace[$id_timespace]['id_waktu'];
	            // $individu[$value['idx']]['id_ruang'] = $timespace[$id_timespace]['id_ruang'];
	            // $individu[$value['idx']]['label_timespace'] = $timespace[$id_timespace]['label'];
	            // $individu[$value['idx']]['kap_ruang'] = $timespace[$id_timespace]['kap_ruang'];
	            // $individu[$value['idx']]['waktu_hari'] = $timespace[$id_timespace]['waktu_hari'];
	            // $individu[$value['idx']]['waktu_jam_mulai'] = $timespace[$id_timespace]['waktu_jam_mulai'];
	        }
    	}
        

        // unset($timespace);
        // unset($value);
        // unset($key);
        // unset($waktudistinct_grup);
        // unset($makul_grup);
        // unset($arr_kelas);
        // unset($arr_id_timespace);
        // unset($kelas_duplikasi);

        return $individu;
    }

    public function repair_kelas_on_hardrule($individu){
        $individu_temp = array(); // untuk menampung sejumlah individu yang mewakili jadwal
        $makul_grup = array(); // untuk mengelompokan kelas berdasar matakuliahnya.
        $waktudistinct_grup = array(); // untuk menampung waktu_id hasil pengelompokan kelas berdasar makulnya.
        $timespace = $this->timespace; // matriks data ruang, hari, dan waktu
        $grup_kemipaan = array();
        $waktudistinct_grup_kemipaan = array();
        $status_reset_individu = false;        


        foreach ($this->kromosom as $key => $value) {
            $individu_classprodi = $this->break_individu_prodi($individu_temp);

            $id_mkkur = $value['id_mkkur'];
            $period_waktu = $value['period'];
            $kls_jadwal_merata = $value['kls_jadwal_merata'];
            $kls_id_grup_jadwal = $value['kls_id_grup_jadwal'];

            $kode_kemipaan = $id_mkkur.'-'.$period_waktu.'-'.$kls_jadwal_merata.'-'.$kls_id_grup_jadwal;
            $status_kemipaan = !in_array($kode_kemipaan, $grup_kemipaan) && ($kls_jadwal_merata == '1');

            if ($kls_jadwal_merata == '1') {
                if ( $status_kemipaan) {
                    $id_timespace = $this->getRandomTimespace($individu_classprodi, $individu_temp, $value, $timespace, $period_waktu, 0, null, $individu[$value['id_individu']]['id_timespace']);

                    $waktudistinct_grup_kemipaan[] = $timespace[$id_timespace]['id_waktu'];
                    $grup_kemipaan[] = $kode_kemipaan;
                }else{
                    $id_timespace = $this->get_random_local($individu_classprodi, $individu_temp, $value, $timespace, $grup_kemipaan, $waktudistinct_grup_kemipaan, $period_waktu, $individu[$value['id_individu']]['id_timespace']);
                }
                
            }else{                
                if ( !in_array($value['id_mkkur'].'-'.$period_waktu, $makul_grup) ) {
                    $id_timespace = $this->getRandomTimespace($individu_classprodi, $individu_temp, $value, $timespace, $period_waktu, 0, null, $individu[$value['id_individu']]['id_timespace']);

                    $waktudistinct_grup[] = $timespace[$id_timespace]['id_waktu'];
                    $makul_grup[] = $value['id_mkkur'].'-'.$period_waktu;
                }else{
                    $id_timespace = $this->get_random_local($individu_classprodi, $individu_temp, $value, $timespace, $makul_grup, $waktudistinct_grup, $period_waktu, $individu[$value['id_individu']]['id_timespace']);
                }
            }

            /*
            menyimpan hasil ruang & waktu untuk kelas, beserta periodenya
            */
            if ($id_timespace == 'nochance') {
                unset($individu_temp);
                unset($grup_kemipaan);
                unset($waktudistinct_grup_kemipaan);
                unset($makul_grup);
                unset($waktudistinct_grup);
                unset($timespace);

                $individu_temp = $this->create_individu();
                break;
            }else{
                /*
                menyimpan hasil ruang & waktu untuk kelas, beserta periodenya
                */        
                $waktu_jam_selesai_kls = $this->get_jam_selesai_kelas($timespace[$id_timespace]['waktu_jam_mulai'], $period_waktu);
                $individu_temp[] = array(
                    'id_kromosom' => $value['id_individu'],
                    'nama_kelas' => $value['nama_kelas'],
                    'id_timespace' => $timespace[$id_timespace]['id_timespace'],
                    'id_waktu' => $timespace[$id_timespace]['id_waktu'],
                    'period' => $value['period'],
                    'waktu_hari' => $timespace[$id_timespace]['waktu_hari'],
                    'id_ruang' => $timespace[$id_timespace]['id_ruang'],
                    'waktu_jam_mulai' => $timespace[$id_timespace]['waktu_jam_mulai'],
                    'label_timespace' => $timespace[$id_timespace]['label'].$waktu_jam_selesai_kls,
                    'kap_ruang' => $timespace[$id_timespace]['kap_ruang'],
                    'waktu_jam_selesai_kls' => $waktu_jam_selesai_kls
                );

                for ($t=0; $t < $period_waktu; $t++) {
                    $id_timespace += $t;
                    $timespace[$id_timespace]['status'] = 1;
                }
            }
            
        }

        return $individu_temp;
    }

    public function repairing_individu($individu){

        // $individu = $this->repair_duplikasi_kelas_timespace($individu);

        $individu = $this->repair_kelas_on_hardrule($individu);

        // unset($str);
        // unset($str2);
        // unset($str3);
        // unset($value);
        // unset($key);
        
        return $individu;
    }
    
    public function build_offspring_population_crossover_twopoint($parent_1, $parent_2, $point_random){
        
        // echo '<pre>'; print_r($point_random); 
        
        $jumlah_gen = count($parent_1['arr_gen']);
        $i = 0;

        $arr_gen_1 = $parent_1['arr_gen'];
        $arr_gen_2 = $parent_2['arr_gen'];
        // $str = '';
        while ($jumlah_gen>0) {
            if (!in_array($i, $point_random) ) {
                $off_1[] = $arr_gen_1[$i];
                $off_2[] = $arr_gen_2[$i];

                // unset($arr_gen_1[$i]);
                // unset($arr_gen_2[$i]);
                $i++;
                $jumlah_gen--;
            }else{
                $point_random = array_diff($point_random, array($i));
                $point_random = array_values($point_random);

                $temp_1 = $arr_gen_1;
                $temp_2 = $arr_gen_2;
                $arr_gen_1 = $temp_2;
                $arr_gen_2 = $temp_1;
                // break;
            }
        }

        $offspring[] = array(
            'parent' => $parent_1,
            'offspring' => $off_1
        );

        $offspring[] = array(
            'parent' => $parent_2,
            'offspring' => $off_2
        );

        /*foreach ($offspring as $key => $value) {
            $offspring[$key]['offspring'] = $this->repairing_individu($value['offspring']);
        }*/
        foreach ($offspring as $key => $value) {
            $offspring[$key]['fitness_rule_1'] = $this->count_fitness_based_rule_kelasmakul_pilihan_wajib_not_sametime($value['offspring']);
            $offspring[$key]['fitness_rule_2'] = $this->count_fitness_based_rule_kelasmakul_on_ruangblokprodi($value['offspring']);
            $offspring[$key]['fitness_rule_3'] = $this->count_fitness_based_rule_kelasmakulsepaket_max_8_sks_sehari($value['offspring']);
            $offspring[$key]['fitness_rule_4'] = $this->count_fitness_based_rule_kelas_filled_min_prosen_capacity($value['offspring']);
            $offspring[$key]['fitness_rule_5'] = $this->count_fitness_based_rule_kelas_dosen_choose_their_time($value['offspring']);
            
            
            $offspring[$key]['fitness'] = 1-(($offspring[$key]['fitness_rule_1'] + $offspring[$key]['fitness_rule_2'] + $offspring[$key]['fitness_rule_3'] + $offspring[$key]['fitness_rule_4'] + $offspring[$key]['fitness_rule_5'] ) / 5);
            $offspring[$key]['randvalmut'] = mt_rand(0,1);
        }
        
        // echo '<pre>'; print_r($offspring); 

        $this->individu_breed[] = $offspring;

        $this->log_proses['crossover'] = array(
        	'parent_1' => $parent_1,
        	'parent_1' => $parent_2,
        	'point_random' => $point_random,
        	'offspring' => $offspring
        );

        // unset($offspring);
        // unset($off_1);
        // unset($off_2);
        // unset($arr_gen_1);
        // unset($arr_gen_2);

    }

    public function crossover(){
        $populasi_breeding_crossover_selected = array();
        foreach ($this->populasi_breeding_selected as $key => $value) {
            if ($value['val_random'] <= $this->pc) {
                $populasi_breeding_crossover_selected[] = $value;
            }
        }

        $n_gen = count($populasi_breeding_crossover_selected[0]['arr_gen']);
        $n_ind = count($populasi_breeding_crossover_selected);
        
        $point_random = array(mt_rand(2,$n_gen-1), mt_rand(2,$n_gen-1) );
        for ($i=0; $i < $n_ind-1 ; $i++) { 
            $this->build_offspring_population_crossover_twopoint($populasi_breeding_crossover_selected[$i], $populasi_breeding_crossover_selected[$i+1], $point_random);
            
        }
        $this->build_offspring_population_crossover_twopoint($populasi_breeding_crossover_selected[($n_ind-1)], $populasi_breeding_crossover_selected[0], $point_random);

    }

    public function mutasi_kromosom($individu){
        $timespace = $this->timespace;

        $pos_mutasi = mt_rand(0,count($individu)-1);
        $id_timespace = mt_rand(0,(count($timespace)-1));

        $gen = $individu[$pos_mutasi];


        $waktu_jam_selesai_kls = $this->get_jam_selesai_kelas($timespace[$id_timespace]['waktu_jam_mulai'], $this->kromosom[$gen['id_kromosom']]['period']);
        
    	$individu[$pos_mutasi] = array(
            'id_kromosom' => $gen['id_kromosom'],
            'id_timespace' => $timespace[$id_timespace]['id_timespace']
            // 'id_waktu' => $timespace[$id_timespace]['id_waktu'],
            // 'id_ruang' => $timespace[$id_timespace]['id_ruang'],
            // 'label_timespace' => $timespace[$id_timespace]['label'].$waktu_jam_selesai_kls,
            // 'kap_ruang' => $timespace[$id_timespace]['kap_ruang'],
            // 'waktu_hari' => $timespace[$id_timespace]['waktu_hari'],
            // 'waktu_jam_mulai' => $timespace[$id_timespace]['waktu_jam_mulai'],
            // 'waktu_jam_selesai_kls' => $waktu_jam_selesai_kls
        );

    	$this->log_proses['mutasi'] = array(
    		'individu' => $individu,
    		'pos_mutasi' => $pos_mutasi,
    		'gen_mutatasi' => $gen,
    		'gen_hasil_mutasi' => $individu[$pos_mutasi]
    	);

        $individu = $this->repairing_individu($individu);
        // $individu = $this->cek_kelas_on_hardrule($individu);
        return $individu;
    }

    public function cek_kelas_on_hardrule($individu){
        $individu_temp = array();
        $timespace = $this->timespace;
        $makul_grup = array();
        $waktudistinct_grup = array();
        $individu_classprodi = $this->break_individu_prodi($individu);
        foreach ($individu as $key => $value) {
            $id_timespace = $value['id_timespace'];

            if ( !in_array($this->kromosom[$value['id_kromosom']]['id_mkkur'].'-'.$this->kromosom[$value['id_kromosom']]['period'],$makul_grup) ) {
                $break_rule = true;
                
                while ($break_rule) {            
                    $rule_ok = $this->check_on_hardrule($individu_classprodi, $individu_temp, $this->kromosom[$value['id_kromosom']], $id_timespace, $timespace, $this->kromosom[$value['id_kromosom']]['period']);
                    if ($rule_ok) {
                        $break_rule = false;
                    }else{
                        $sts = true;
            
                        $stsrule_1 = $this->CI->aturan_jadwal->check_time_notover_limit($id_timespace, $timespace, $this->kromosom[$value['id_kromosom']]['period']);
                        $stsrule_2 = $this->CI->aturan_jadwal->check_timespace_class_samepacket_not_sametime($this->kromosom, $this->timespace, $individu_classprodi, $this->kromosom[$value['id_kromosom']], $id_timespace, $timespace, $this->prodi);
                        $stsrule_3 = $this->CI->aturan_jadwal->check_capacity_class_ok($id_timespace, $timespace, $this->kromosom[$value['id_kromosom']]);
                        $stsrule_4 = $this->CI->aturan_jadwal->check_lecture_class_not_sametime($this->kromosom, $this->timespace, $individu_temp, $this->kromosom[$value['id_kromosom']], $id_timespace, $timespace);
                        $stsrule_5 = $this->CI->aturan_jadwal->check_separatesameclass_not_sameday($this->kromosom, $this->timespace, $individu_temp, $this->kromosom[$value['id_kromosom']], $id_timespace, $timespace);
                        $stsrule_6 = $this->CI->aturan_jadwal->check_neighborpacketclass_not_sametime($this->kromosom, $this->timespace, $individu_classprodi, $this->kromosom[$value['id_kromosom']], $id_timespace, $timespace, $this->prodi);

                        $sts = $stsrule_1 && $stsrule_2 && $stsrule_3 && $stsrule_4 && $stsrule_5 && $stsrule_6 ;

			            /*if (!$sts) {
			                $param = array($stsrule_1, $stsrule_2, $stsrule_3, $stsrule_4, $stsrule_5, $stsrule_6);
			                $idx = $this->cek_kebenaran($param);
			                echo '<pre>'; print_r($idx);
			            }
                        echo 'Test';

                        echo '<pre>'; print_r($individu_temp); 
                        echo '<pre>'; print_r($value);
                        exit();*/
                    }
                }

            }else{
                // break;
                echo 'par<pre>'; print_r($individu_temp); 
                echo '<pre>'; print_r($value); 

                exit();
                $id_timespace = $this->get_random_local($individu_classprodi, $individu_temp, $this->kromosom[$value['id_kromosom']], $timespace, $makul_grup, $waktudistinct_grup, $this->kromosom[$value['id_kromosom']]['period']);
                

            }
            
            $waktu_jam_selesai_kls = $this->get_jam_selesai_kelas($this->timespace[$value['id_timespace']]['waktu_jam_mulai'], $this->kromosom[$value['id_kromosom']]['period']);
            $individu_temp[] = array(
                'id_kromosom' => $value['id_kromosom'],
                // 'nama_kelas' => $value['nama_kelas'],
                'id_timespace' => $timespace[$id_timespace]['id_timespace'],
                // 'period' => $value['period'],
                'id_waktu' => $timespace[$id_timespace]['id_waktu'],
                'id_ruang' => $timespace[$id_timespace]['id_ruang'],
                // 'label_timespace' => $value['nama_kelas'].','.$timespace[$id_timespace]['label'].$waktu_jam_selesai_kls
                // 'kap_ruang' => $timespace[$id_timespace]['kap_ruang'],
                // 'waktu_hari' => $timespace[$id_timespace]['waktu_hari'],
                // 'waktu_jam_mulai' => $timespace[$id_timespace]['waktu_jam_mulai'],
                // 'waktu_jam_selesai_kls' => $waktu_jam_selesai_kls
            );

            // membuat grup kelas per matakuliah secara dinamis
            if (!in_array($this->kromosom[$value['id_kromosom']]['id_mkkur'].'-'.$this->kromosom[$value['id_kromosom']]['period'], $makul_grup)) {
                $makul_grup[] = $this->kromosom[$value['id_kromosom']]['id_mkkur'].'-'.$this->kromosom[$value['id_kromosom']]['period'];
                $waktudistinct_grup[] = $timespace[$id_timespace]['id_waktu'];
            }

            // menghapus index beserta nilainya untuk data ruang & waktu yg dipakai kelas untuk jadwal
            for ($t=0; $t < $this->kromosom[$value['id_kromosom']]['period']; $t++) { 
                $id_timespace += $t;
                $timespace[$id_timespace]['status'] = 1;
                // unset($timespace[$id_timespace]);
            }
            
            // $timespace = array_values($timespace); // set ulang index matriks ruang & waktu

        } 
    }

    public function mutation(){
        foreach ($this->individu_breed as $key => $value) {
            foreach ($value as $i => $item) {
                // echo '<pre>'; print_r($item['randvalmut']); 
                if ($item['randvalmut'] < $this->pm) {                    
                    $this->individu_breed[$key][$i]['offspring'] = $this->mutasi_kromosom($item['offspring']);
                }

                $this->individu_update_calon[] = $this->individu_breed[$key][$i];
            }
        }
        // echo '<pre>'; print_r($this->individu_update_calon); echo '</pre>'; exit();

        // unset($key);
        // unset($value);
        $this->individu_breed = null;
    }

    public function count_total_fitness_populasi_breeding(){
        $total = 0;
        foreach ($this->populasi_breeding as $key => $value) {
            $total += $value['fitness'];
        }

        return $total;

    }

    public function update_selection(){
        $populasi_breeding = $this->populasi_breeding;
        $this->populasi_breeding = array();
        foreach ($populasi_breeding as $key => $value) {
            $this->populasi_breeding[] = array(
                'fitness' => $value['fitness'],
                'arr_gen' => $value['arr_gen']
            );
        }

        foreach ($this->individu_update_calon as $key => $value) {
            $this->populasi_breeding[] = array(
                'fitness' => $value['fitness'],
                'arr_gen' => $value['offspring']
            );
        }

        $this->total_fitness = $this->count_total_fitness_populasi_breeding();        

        $this->roulette_wheel_selection();

        foreach ($this->populasi_breeding_selected as $key => $value) {
            $this->populasi_baru[] = $value['arr_gen'];
        }

        $this->individu_update_calon = null;
        // unset($populasi_breeding);
        // unset($key);
        // unset($value);

    }

    public function update_population(){
        $this->populasi = array();
        foreach ($this->populasi_baru as $key => $value) {
            // $this->populasi[] = $this->repairing_individu($value);
            $this->populasi[] = $value;
        }
        $this->populasi_baru = null;

    }

    public function get_solution(){
        // echo '<pre>'; print_r($this->populasi_breeding_selected);
        $max_fitness = 0;
        $idx = null;
        foreach ($this->populasi_breeding_selected as $key => $value) {
            if ($value['fitness'] > $max_fitness) {
                $max_fitness = $value['fitness'];
                $idx = $key;
            }
        }

        $this->log_proses['populasi_akhir_generasi'] = $this->populasi_breeding_selected;

        foreach ($this->populasi_breeding_selected[$idx]['arr_gen'] as $key => $value) {
        	$this->populasi_breeding_selected[$idx]['arr_gen'][$key]['id_kelas'] = $this->kromosom[$value['id_kromosom']]['id_kelas'];
        	$this->populasi_breeding_selected[$idx]['arr_gen'][$key]['id_waktu'] = $this->timespace[$value['id_timespace']]['id_waktu'];
        	$this->populasi_breeding_selected[$idx]['arr_gen'][$key]['id_ruang'] = $this->timespace[$value['id_timespace']]['id_ruang'];
        	$this->populasi_breeding_selected[$idx]['arr_gen'][$key]['period'] = $this->kromosom[$value['id_kromosom']]['period'];

        	$waktu_jam_selesai_kls = $this->get_jam_selesai_kelas($this->timespace[$value['id_timespace']]['waktu_jam_mulai'], $this->kromosom[$value['id_kromosom']]['period'] );
        	$this->populasi_breeding_selected[$idx]['arr_gen'][$key]['jam_selesai'] = $waktu_jam_selesai_kls;
        	$this->populasi_breeding_selected[$idx]['arr_gen'][$key]['label_timespace'] = $this->timespace[$value['id_timespace']]['label'].$waktu_jam_selesai_kls;
        }

        // exit();
        $sts = $this->set_log_proses();

        return $this->populasi_breeding_selected[$idx];
    }

    function set_log_proses(){    	
        $data = base64_encode(serialize($this->log_proses));
        $sts = $this->CI->bantu->simpan_log_proses('algen_penjadwalan', $data);
        // echo '<pre>'; print_r($this->log_proses); echo '</pre>'; exit();
        // $this->CI->session->set_userdata('log_proses', $this->log_proses);
        return $sts;
    }

    

}

/* End of file Someclass.php */