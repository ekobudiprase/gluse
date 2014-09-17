<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

ini_set('MAX_EXECUTION_TIME', -1);

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
    var $populasi_breeding_crossover_selected = array();
    var $total_fitness = 0;
    var $individu_breed = array();
    var $individu_update_calon = array();
    var $populasi_baru = array();
    var $kromosom = array();
    /**
    * @author   Eko Budi Prasetyo
    * @version    0.0.0
    * @since    May 31, 2014
    * @usedfor    -
    */
    public function __construct(){
        $this->CI =& get_instance(); // for accessing the model of CI later
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
    * @version      0.0.0
    * @since        June 14, 2014
    * @usedfor      -
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
        foreach ($this->ruang as $key => $value) {
            foreach ($this->waktu as $a => $item) {
                $this->timespace[] = array(
                    "id_ruang" => $value['ru_id'],
                    "id_waktu" => $item['waktu_id'],
                    "waktu_hari" => $item['waktu_hari'],
                    "waktu_jam_mulai" => $item['waktu_jam_mulai'],
                    // "label" => $value['ru_nama'].', '.$item['waktu_hari'].' '.$item['waktu_jam_mulai'].'-'.$item['waktu_jam_selesai'],
                    "label" => $value['ru_nama'].', '.$item['waktu_hari'].' '.$item['waktu_jam_mulai'].'-',
                    "kap_ruang" => $value['ru_kapasitas']
                );
            }
        }
        
    }

    // =====================================================================================================================
    /*
    pada kelas paralel yg mata kuliah sama, waktu harus sama dan di tempat yang berbeda
    */
    public function check_timespace_paralelclass_is_sametime($individu, $value, $id_timespace, $timespace ){
        $sts = false;

        // trapping jika undefined maka lgsg false, cari id_timespace yg lain
        if (!isset($timespace[$id_timespace])) {
            $sts = false;
            return $sts;
        }

        foreach ($individu as $i => $item) {
            if ($item['id_mkkur'] == $value['mkkur_id'] AND $item['id_waktu'] == $timespace[$id_timespace]['id_waktu']) {
                $sts = true;
                break;
            }            
        }

        return $sts;
    }

    public function check_timespace_class_samepacket_not_sametime($individu, $value, $id_timespace, $timespace){
        $sts = true;

        // trapping jika undefined maka lgsg false, cari id_timespace yg lain
        if (!isset($timespace[$id_timespace])) {
            $sts = false;
            return $sts;
        }

        foreach ($individu as $i => $item) {
            if ($item['paket_smt'] == $value['paket_smt'] AND $item['id_waktu'] == $timespace[$id_timespace]['id_waktu']) {
                $sts = false;
                break;
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

    public function check_lecture_class_not_sametime($individu, $value, $id_timespace, $timespace){
        $sts = true;

        // trapping jika undefined maka lgsg false, cari id_timespace yg lain
        if (!isset($timespace[$id_timespace])) {
            $sts = false;
            return $sts;
        }

        foreach ($individu as $i => $item) {
            if (!empty($item['dosen'])) {
                foreach ($item['dosen'] as $j => $item_dsn) {
                    if (!empty($value['dosen'])) {
                        foreach ($value['dosen'] as $k => $item_dsn_current_class) {
                            if ($item_dsn == $item_dsn_current_class AND $item['id_waktu'] == $timespace[$id_timespace]['id_waktu']) {
                                $sts = false;
                                break;

                            }
                        }
                    }
                }
            }        
        }
        
        return $sts;
    }    

    public function check_separatesameclass_not_sameday($individu, $value, $id_timespace, $timespace){
        $sts = true;

        // trapping jika undefined maka lgsg false, cari id_timespace yg lain
        if (!isset($timespace[$id_timespace])) {
            $sts = false;
            return $sts;
        }

        foreach ($individu as $i => $item) {
            if ($item['id_kelas'] == $value['id'] AND $item['waktu_hari'] == $timespace[$id_timespace]['waktu_hari']) {
                $sts = false;
                break;
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

    public function check_neighborpacketclass_not_sametime($individu, $value, $id_timespace, $timespace){
        $sts = true;

        // trapping jika undefined maka lgsg false, cari id_timespace yg lain
        if (!isset($timespace[$id_timespace])) {
            $sts = false;
            return $sts;
        }

        $smt_neighbor = $this->get_neighbor_sametype_semester($value['paket_smt']);
        foreach ($individu as $i => $item) {
            if ($item['sifat_makul'] == $value['sifat_makul'] AND $value['sifat_makul'] == 'W' AND (in_array($item['paket_smt'], $smt_neighbor)) AND $item['id_waktu'] == $timespace[$id_timespace]['id_waktu'] ) {
                $sts = false;
                break;
            }            
        }

        return $sts;
    }

    public function check_time_notover_limit($individu, $value, $id_timespace, $timespace, $period_waktu){
        $sts = true;
        $jam_akhir_kampus = '17:20:00';
        $hari_waktu_jumatan = 'jumat';
        $jam_waktu_jumatan = '11:20:00';

        // $jam_akhir_kampus_cvt = date('H:i:s', strtotime($jam_akhir_kampus) );
        // $jam_waktu_jumatan = date('H:i:s', strtotime($jam_waktu_jumatan) );

        // trapping jika undefined maka lgsg false, cari id_timespace yg lain
        if (!isset($timespace[$id_timespace])) {
            $sts = false;
            return $sts;
        }

        $hari_kls = strtotime($timespace[$id_timespace]['waktu_hari']);
        $waktu_jam_mulai_kls = strtotime($timespace[$id_timespace]['waktu_jam_mulai']);
        $lama_menit_kelas = $period_waktu * 50;
        $waktu_jam_selesai_kls = date("H:i:s", strtotime('+'.$lama_menit_kelas.' minutes', $waktu_jam_mulai_kls));

        /*echo '<pre>'; print_r( strtotime($waktu_jam_selesai_kls) ); 
        echo '<pre>'; print_r( strtotime($jam_akhir_kampus) ); 
        exit();*/

        if (
            strtotime($waktu_jam_selesai_kls) > strtotime($jam_akhir_kampus)
            OR ($hari_kls == $hari_waktu_jumatan 
                AND strtotime($waktu_jam_mulai_kls) < strtotime($jam_waktu_jumatan)
                AND strtotime($waktu_jam_selesai_kls) > strtotime($jam_waktu_jumatan)
            )
        ) {
            $sts = false;
        }
        
        return $sts;
    }

    public function check_on_hardrule($individu, $value, $id_timespace, $timespace, $period_waktu){
        // if (!empty($individu)) {
            $sts = true;
            // $sts = $sts && $this->check_timespace_paralelclass_is_sametime($id_timespace, $individu, $timespace, $value);
            $sts = $sts && $this->check_time_notover_limit($individu, $value, $id_timespace, $timespace, $period_waktu);
            $sts = $sts && $this->check_timespace_class_samepacket_not_sametime($individu, $value, $id_timespace, $timespace);
            $sts = $sts && $this->check_capacity_class_ok($id_timespace, $timespace, $value);
            $sts = $sts && $this->check_lecture_class_not_sametime($individu, $value, $id_timespace, $timespace);
            $sts = $sts && $this->check_separatesameclass_not_sameday($individu, $value, $id_timespace, $timespace);
            $sts = $sts && $this->check_neighborpacketclass_not_sametime($individu, $value, $id_timespace, $timespace);

            return $sts;
        /*}else{
            return true;
        }*/
        
    }

    public function check_on_hardrule_for_paralelclass($individu, $value, $id_timespace, $timespace){
        if (!empty($individu)) {
            $sts = true;
            // echo '<pre>'; print_r($id_timespace); echo '</pre>';
            // echo '<pre>'; print_r($individu); echo '</pre>';
            // echo '<pre>'; print_r($timespace); echo '</pre>';
            // echo '<pre>'; print_r($value); echo '</pre>';
            // exit();
            $sts = $sts && $this->check_timespace_paralelclass_is_sametime($individu, $value, $id_timespace, $timespace);
            $sts = $sts && $this->check_capacity_class_ok($id_timespace, $timespace, $value);
            $sts = $sts && $this->check_lecture_class_not_sametime($individu, $value, $id_timespace, $timespace);
            $sts = $sts && $this->check_separatesameclass_not_sameday($individu, $value, $id_timespace, $timespace);
            $sts = $sts && $this->check_neighborpacketclass_not_sametime($individu, $value, $id_timespace, $timespace);
            // $sts = $sts && $this->check_timespace_class_samepacket_not_sametime($id_timespace, $individu, $timespace, $value);

            return $sts;
        }else{
            return true;
        }
        
    }

    //===================================================================================================================

    /*function set_idspacetime_for_paralelclass(){

        foreach ($individu as $i => $item) {
            if ($item['id_mkkur'] == $value['mkkur_id']) {
                
            }
        }
    }*/

    function get_id_timespace_for_sametime($timespace, $id_waktu){
        $temp = array();
        foreach ($timespace as $key => $value) {
            if ($value['id_waktu'] == $id_waktu) {
                $temp[] = array(
                    'id_timespace' => $key,
                    'data' => $value
                );
            }
            
        }

        unset($key);
        unset($value);

        return $temp;
        unset($temp);
    }

    /*
    fungsi ini digunakan untuk mencari id_timespace yg nanti dipakai kelas paralel matakuliah yg sama
    */
    function get_random_local($individu, $kelas, $timespace, $makul_grup, $waktudistinct_grup, $period_waktu, $id_timespace_cek=null){
        
        /*
        membuat matriks ruang & waktu timespace_grup_waktu khusus untuk makul yang sama,
        matriks ini waktunya sama, hanya ruang yang beda
        */
        if (!empty($makul_grup)) {
            foreach ($makul_grup as $a => $mk) {
                if ($mk == $kelas['mkkur_id'].'-'.$period_waktu) {
                    $timespace_grup_waktu = $this->get_id_timespace_for_sametime($timespace, $waktudistinct_grup[$a]);
                }
            }
        }       

        /*
        mencari ruang & waktu timespace_terpakai yg sudah terpakai oleh kelas sebelumnya yg makulnya sama.
        */
        if (!empty($individu)) {
            foreach ($individu as $i => $item) {
                if ( in_array($item['id_mkkur'], $makul_grup) and ($item['id_mkkur'].'-'.$item['period']) == ($value['mkkur_id'].'-'.$value['period']) and $item['period'] == $period_waktu ) {
                    $timespace_terpakai[] = $item['id_timespace'];
                }
            }
            
        }

        /*
        matrik ruang & waktu timespace_grup_waktu dikurangi data yg terpakai pada timespace_terpakai
        menghasilkan matriks ruang waktu baru untuk alternatif pilihan jadwal kelas
        */
        if (!empty($timespace_terpakai)) {
            foreach ($timespace_grup_waktu as $key => $value) {
                if (in_array($value['id_timespace'], $timespace_terpakai)) {
                    unset($timespace_grup_waktu[$key]);
                }
            }
            $timespace_grup_waktu = array_values($timespace_grup_waktu);
        }

        // $id_timespace_grup_waktu = mt_rand(0,(count($timespace_grup_waktu)-1));
        // $id_timespace = $timespace_grup_waktu[$id_timespace_grup_waktu]['id_timespace'];

        // echo '<pre>'; print_r($timespace_grup_waktu); echo '</pre>';
        // echo '<pre>'; print_r($timespace); echo '</pre>';
        // exit();

        if ($id_timespace_cek != null) {
            $id_timespace = $id_timespace_cek;
        }else{
            $id_timespace_grup_waktu = mt_rand(0,(count($timespace_grup_waktu)-1));
            $id_timespace = $timespace_grup_waktu[$id_timespace_grup_waktu]['id_timespace'];
        }

        /*
        lakukan berulang untuk pencarian id_timespace hingga memenuhi kondisi aturan umum check_on_hardrule_for_paralelclass.
        */
        $break_rule = true;
        while ($break_rule) {
            
            
            
            // echo '<pre>'; print_r($id_timespace); echo '</pre>';
            $rule_ok = $this->check_on_hardrule_for_paralelclass($individu, $kelas, $id_timespace, $timespace);
            if ($rule_ok) {
                $break_rule = false;
            }else{
                $id_timespace_grup_waktu = mt_rand(0,(count($timespace_grup_waktu)-1));
                $id_timespace = $timespace_grup_waktu[$id_timespace_grup_waktu]['id_timespace'];
            }
        }

        // echo '<pre>'; print_r($timespace_grup_waktu); echo '</pre>';
        // echo '<pre>'; print_r($id_timespace_grup_waktu); echo '</pre>';
        // echo '<pre>'; print_r($id_timespace); echo '</pre>';
        return $id_timespace;
    }

    public function get_feasible_individu($arr_data){
        extract($arr_data);
        
        // echo '<pre>'; print_r($value); echo '</pre>';
        // OR !empty($value['format_jadwal']
        /*
        menentukan jadwal ruang & waktu untuk kelas diwakili oleh id_timespace
        cek apakah kelas makul yang sama sudah ada sebelumnya di kelas terjadwal
        kelas makul yang sama adalah kelas paralel yang makulnya sama
        jika tidak maka id_timespace bisa diambil dari matriks ruang & waktu 
        jika iya maka id_timespace diambil dari matriks ruang & waktu yg lebih spesifik, 
        yakni yg waktunya sama dgn kls terjdwal sebelumnya yg makul sama. Karna ada aturan
        kelas paralel diadakan dalam waktu yg sama.
        */

        if ( !in_array($value['mkkur_id'].'-'.$period_waktu,$makul_grup) ) {
            $break_rule = true;
            while ($break_rule) {               
                $id_timespace = mt_rand(0,(count($timespace)-1));

                $rule_ok = $this->check_on_hardrule($individu, $value, $id_timespace, $timespace, $period_waktu);
                if ($rule_ok) {
                    $break_rule = false;
                }                        
            }
            
        }else{
            // $id_timespace = mt_rand(0,(count($timespace)-1));
            // echo '<pre>'; print_r($value); echo '</pre>';
            // echo '<pre>'; print_r($individu); echo '</pre>';
            // echo '<pre>'; print_r($timespace); echo '</pre>';
            // exit();
            $id_timespace = $this->get_random_local($individu, $value, $timespace, $makul_grup, $waktudistinct_grup, $period_waktu);
            
        }        

        /*if ($value['id'] == '3') {
            echo '<pre>'; print_r($individu); echo '</pre>';
            // echo '<pre>'; print_r($makul_grup); echo '</pre>';
            // echo '<pre>'; print_r($waktudistinct_grup); echo '</pre>';
            echo '<pre>'; print_r($id_timespace); echo '</pre>';
            // echo "end";
            // $id_timespace = $this->get_random_local($individu, $timespace, $makul_grup, $waktudistinct_grup, $value['mkkur_id']);
            exit;
        }*/

        /*
        menyimpan hasil ruang & waktu untuk kelas, beserta periodenya
        */
        $waktu_jam_mulai_kls = strtotime($timespace[$id_timespace]['waktu_jam_mulai']);
        $lama_menit_kelas = $period_waktu * 50;
        $waktu_jam_selesai_kls = date("H:i:s", strtotime('+'.$lama_menit_kelas.' minutes', $waktu_jam_mulai_kls));
        $individu[] = array(
            "id_individu" => $id_individu,
            "id_kelas" => $value['id'],
            "nama_kelas" => $value['nama_kelas'],
            "id_mkkur" => $value['mkkur_id'],
            "jml_peserta_kls" => $value['jml_peserta_kls'],
            "format_jadwal" => $value['format_jadwal'],
            "paket_smt" => $value['paket_smt'],
            "sifat_makul" => $value['sifat_makul'],
            "id_timespace" => $id_timespace,
            "id_waktu" => $timespace[$id_timespace]['id_waktu'],
            "id_ruang" => $timespace[$id_timespace]['id_ruang'],
            "label_timespace" => $timespace[$id_timespace]['label'].$waktu_jam_selesai_kls,
            "kap_ruang" => $timespace[$id_timespace]['kap_ruang'],
            "waktu_hari" => $timespace[$id_timespace]['waktu_hari'],
            "waktu_jam_mulai" => $timespace[$id_timespace]['waktu_jam_mulai'],
            "waktu_jam_selesai_kls" => $waktu_jam_selesai_kls,
            "period" => $period_waktu,
            "dosen" => $value['dosen'],
            "ruang_blok_prodi" => $value['ruang_blok_prodi'],
            "kelas_prodi" => $value['kelas_prodi'],
            "alternatif_waktu_ajar" => $value['alternatif_waktu_ajar']
        );

        $id_individu++;

        // membuat grup kelas per matakuliah secara dinamis
        if (!in_array($value['mkkur_id'].'-'.$period_waktu, $makul_grup)) {
            $makul_grup[] = $value['mkkur_id'].'-'.$period_waktu;
            $waktudistinct_grup[] = $timespace[$id_timespace]['id_waktu'];
        }
        
        // menghapus index beserta nilainya untuk data ruang & waktu yg dipakai kelas untuk jadwal
        for ($t=0; $t < $period_waktu; $t++) { 
            $id_timespace = $id_timespace + $t;
            unset($timespace[$id_timespace]);
        }
        
        $timespace = array_values($timespace); // set ulang index matriks ruang & waktu 

        $ret_data = compact('timespace','individu','period_waktu','value', 'makul_grup', 'waktudistinct_grup', 'id_individu');
        return $ret_data;

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

        // echo '<pre>'; print_r($this->kelas); echo '</pre>';
        /*
        lakukan perulangan sejumlah kelas
        cek apakah kelas terdapat nilai format_jadwal
        jika ada kelas dibagi sejumlah format_jadwal dengan periode masing2
        lalu cari jadwal yang memenuhi aturan umum dgn get_feasible_individu
        */
        $id_individu = 0;
        foreach ($this->kelas as $key => $value) {
            // echo '<pre>'; print_r($slot); echo '</pre>';
            if (!empty($value['format_jadwal'])) {
                $period = explode('-', $value['format_jadwal']);
                foreach ($period as $i => $item) {
                    $period_waktu = $item;
                    $arr_data = compact('timespace','individu','period_waktu','value', 'makul_grup', 'waktudistinct_grup', 'id_individu');
                    $ret_data = $this->get_feasible_individu($arr_data);

                    extract($ret_data);                    
                }
            }else{
                $period_waktu = $value['sks'];
                $arr_data = compact('timespace','individu','period_waktu','value', 'makul_grup', 'waktudistinct_grup', 'id_individu');
                $ret_data = $this->get_feasible_individu($arr_data);

                extract($ret_data); 
            }         

            /*if ($key == 0) {
            	break;
            }*/

        }

        unset($makul_grup);
        unset($waktudistinct_grup);
        unset($timespace);
        unset($ret_data);

        // echo '<pre>'; print_r($individu); echo '</pre>';
        return $individu;
    }

    function cek_langgar_jam($individu){
    	$jam_akhir_kampus = '17:20:00';
        $hari_waktu_jumatan = 'jumat';
        $jam_waktu_jumatan = '11:20:00';
    	foreach ($individu as $key => $value) {
    		$hari_kls = strtotime($value['waktu_hari']);
	        $waktu_jam_mulai_kls = strtotime($value['waktu_jam_mulai']);
	        $lama_menit_kelas = $value['period'] * 50;
	        $waktu_jam_selesai_kls = date("H:i:s", strtotime('+'.$lama_menit_kelas.' minutes', $waktu_jam_mulai_kls));
	    	echo 'Kelas : '.$value['id_kelas'].' => '.$value['id_ruang'].' '.$value['waktu_hari'].', '.$value['waktu_jam_mulai'].'-'.$value['waktu_jam_selesai_kls'].'/'.$waktu_jam_selesai_kls.' ';
	    	if (
	            strtotime($value['waktu_jam_selesai_kls']) > strtotime($jam_akhir_kampus)
	            OR ($hari_kls == $hari_waktu_jumatan 
	                AND strtotime($waktu_jam_mulai_kls) < strtotime($jam_waktu_jumatan)
	                AND strtotime($waktu_jam_selesai_kls) > strtotime($jam_waktu_jumatan)
	            )
	        ) {
	            echo "[bukti!!!!]";
	        }
	        echo "<br>";
    	}
    }

    public function make_class($arr_data){
        extract($arr_data);       

        /*
        menyimpan hasil ruang & waktu untuk kelas, beserta periodenya
        */
        $class[] = array(
            "id_individu" => $id_individu,
            "id_kelas" => $value['id'],
            "nama_kelas" => $value['nama_kelas'],
            "id_mkkur" => $value['mkkur_id'],
            "jml_peserta_kls" => $value['jml_peserta_kls'],
            "format_jadwal" => $value['format_jadwal'],
            "paket_smt" => $value['paket_smt'],
            "sifat_makul" => $value['sifat_makul'],
            "period" => $period_waktu,
            "dosen" => $value['dosen'],
            "ruang_blok_prodi" => $value['ruang_blok_prodi'],
            "kelas_prodi" => $value['kelas_prodi'],
            "alternatif_waktu_ajar" => $value['alternatif_waktu_ajar']
        );

        $id_individu++;

        $ret_data = compact('class','period_waktu','value', 'id_individu');
        return $ret_data;
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

    /**
    * @author       Eko Budi Prasetyo
    * @version      0.0.0
    * @since        May 30, 2014
    * @usedfor      -
    */
    public function generate_population(){
        // echo '<pre>'; print_r($this->timespace); echo '</pre>';
        // exit();

        $this->kromosom = $this->create_information_class(); // buat individu

        /*
        bangkitkan populasi yang terdiri dari sejumlah individu
        */
        $this->classinfo = array();
        $this->populasi = array(); // empty population each generation
        for ($i=0; $i < $this->post['jml_individu']; $i++) { 
            $this->populasi[] = $this->create_individu(); // buat individu

            /*if ($i == 0) {
                break;
            }*/
        }

        /*foreach ($this->populasi as $key => $individu) {
        	echo "Individu ke-".($key+1).'<br>';
        	$this->cek_langgar_jam($individu);
        	echo "<br>";
        }*/
        echo '<pre>'; print_r($this->kromosom);
        exit(); 
    }

    /*public function count_gen_score_for_fitness($kelas, $individu){
        echo '<pre>'; print_r($data);
        foreach ($data as $key => $value) {
            
        }

    }*/

    public function separate_kelas_makul_wajib_pil($individu){
        foreach ($individu as $key => $value) {
            if ($value['sifat_makul'] == 'W') {
                $makul_wajib[] = $value;
            }
            if ($value['sifat_makul'] == 'P') {
                $makul_pil[] = $value;
            }
        }

        $separate_makul = array(
            'makul_wajib' => $makul_wajib,
            'makul_pil' => $makul_pil
        );

        unset($makul_wajib);
        unset($makul_pil);

        return $separate_makul;
    }

    function bentrok_sametime($kls, $kls_compare) {
        
        $sts = $kls['waktu_hari'] == $kls_compare['waktu_hari'];

        $start_time = strtotime($kls['waktu_jam_mulai']);
        $end_time = strtotime($kls['waktu_jam_selesai_kls']);
        $start_compare_time = strtotime($kls_compare['waktu_jam_mulai']);
        $end_compare_time = strtotime($kls_compare['waktu_jam_selesai_kls']);

        $sts_time_between = ( ( ($start_time >= $start_compare_time) && ($start_time <= $end_compare_time) OR ( ($end_time >= $start_compare_time) && ($end_time <= $end_compare_time) ) ) );
        $sts = $sts && $sts_time_between;

        return $sts;
    }

    public function count_fitness_based_rule_kelasmakul_pilihan_wajib_not_sametime($individu){
        $separate_makul = $this->separate_kelas_makul_wajib_pil($individu);

        $jumlah = 0;
        foreach ($separate_makul['makul_pil'] as $i => $pil) {
            $smt_neighbor = $this->get_neighbor_sametype_semester($pil['paket_smt']);
            $bentrok[$i] = 0;
            $bentrok_ket[$i] = '';

            $sts_bentrok = false;
            foreach ($separate_makul['makul_wajib'] as $j => $wjb) {
                if (in_array($wjb['paket_smt'],$smt_neighbor) AND ($this->bentrok_sametime($pil,$wjb)) ) {
                    $bentrok[$i]++;
                    $bentrok_ket[$i] .= $wjb['id_kelas'].', ';
                    $sts_bentrok = true;
                }
            }

            $separate_makul['makul_pil'][$i]['bentrok'] = $bentrok[$i];
            $separate_makul['makul_pil'][$i]['bentrok_ket'] = $bentrok_ket[$i];

            if ($sts_bentrok) {
                $jumlah++;
            }
        }
        // echo '<pre>'; print_r($separate_makul); 
        // echo '<pre>'; print_r($jumlah); 
        // echo '<pre>'; print_r(count($separate_makul['makul_pil'])); 

        $fitness = $jumlah / count($separate_makul['makul_pil']);

        unset($separate_makul);
        unset($smt_neighbor);
        unset($i);
        unset($bentrok);
        unset($bentrok_ket);
        unset($pil);
        unset($wjb);

        return $fitness;
    }

    public function count_fitness_based_rule_kelasmakul_on_ruangblokprodi($individu){
        // echo '<pre>'; print_r($individu); 

        $score = 0;
        $i = 0;
        foreach ($individu as $key => $value) {
            $arr_ruangblokprodi[$key] = explode('|', $value['ruang_blok_prodi']);
            if (!empty($arr_ruangblokprodi[$key]) AND !in_array($value['id_ruang'], $arr_ruangblokprodi[$key])) {
                $score++;
            }
            if ( !empty($arr_ruangblokprodi[$key]) ) {
                $i = $i + 1;
            }
            
        }

        // echo '<pre>'; print_r($arr_ruangblokprodi); 
        // echo '<pre>'; print_r(count($total_alt_kelas_blok)); 
        // echo '<pre>'; print_r($score); 
        // echo '<pre>'; print_r($i); 

        $fitness = $score / $i;

        unset($arr_ruangblokprodi);

        return $fitness;
    }

    public function count_fitness_based_rule_kelasmakulsepaket_max_8_sks_sehari($individu){
        $arr_hari = array('senin', 'selasa', 'rabu', 'kamis', 'jumat');

        $total_langgar = 0;
        foreach ($arr_hari as $i => $hari) {
            $arr_grup_hari[$i] = array(
                "hari" => $hari,
                "data_prodi" => array()
            );
            $jml_prodi_langgar = 0;
            foreach ($this->prodi as $j => $prodi) {
                $arr_grup_hari[$i]['data_prodi'][$j] = array(
                    "prodi_id" => $prodi['prodi_id'],
                    "data_semester" => array()
                );
                $jml_smt_langgar = 0;
                for ($l=1; $l <= 8; $l++) { 
                    $arr_grup_hari[$i]['data_prodi'][$j]['data_semester'][$l] = array(
                        "paket_semester" => $l,
                        "data_kelas" => array()
                    );
                    $total_sks = 0;
                    foreach ($individu as $k => $kelas_terjadwal) {
                        $arr_kelas_prodi = explode('|', $kelas_terjadwal['kelas_prodi']);                       
                        if ($hari == $kelas_terjadwal['waktu_hari'] AND in_array($prodi['prodi_id'], $arr_kelas_prodi) AND $kelas_terjadwal['paket_smt'] == $l) {
                            $total_sks = $total_sks + $kelas_terjadwal['period'];
                            $arr_grup_hari[$i]['data_prodi'][$j]['data_semester'][$l]['data_kelas'][] = $kelas_terjadwal;                               
                        }                       
                    }
                    $arr_grup_hari[$i]['data_prodi'][$j]['data_semester'][$l]['total_sks'] = $total_sks;
                    if ($total_sks > 8) {
                        $jml_smt_langgar = $jml_smt_langgar + 1;
                    }
                        
                }
                $arr_grup_hari[$i]['data_prodi'][$j]['jml_smt_langgar'] = $jml_smt_langgar;
                if ($jml_smt_langgar > 0) {
                    $jml_prodi_langgar = $jml_prodi_langgar + 1;
                }
            }
            $arr_grup_hari[$i]['jml_prodi_langgar'] = $jml_prodi_langgar;
            $total_langgar = $total_langgar + $jml_prodi_langgar;
            // echo '<pre>'; print_r($jml_prodi_langgar); echo '</pre>';
        }
        // echo '<pre>'; print_r($arr_grup_hari); 

        $jml_semesta_himp = count($arr_hari) * count($this->prodi);
        $fitness = $total_langgar / $jml_semesta_himp;
        // echo '<pre>'; print_r($total_langgar); echo '</pre>';

        unset($arr_hari);
        unset($arr_grup_hari);
        unset($i);
        unset($hari);
        unset($prodi);
        unset($arr_kelas_prodi);
        unset($kelas_terjadwal);
        unset($total_langgar);
        unset($jml_semesta_himp);

        return $fitness;

    }

    public function count_fitness_based_rule_kelas_filled_min_prosen_capacity($individu){
        // echo '<pre>'; print_r($individu); echo '</pre>';
        $melanggar = 0;
        foreach ($individu as $key => $value) {
            $harapan_jml = ($this->min_prosen_capacity / 100) * $value['kap_ruang'];
            $individu[$key]['harapan_jml'] = ceil($harapan_jml);
            $individu[$key]['melanggar'] = 0;
            if ($value['jml_peserta_kls'] < ceil($harapan_jml)) {
                $individu[$key]['melanggar'] = 1;
                $melanggar = $melanggar + 1;
                
            }
        }

        // echo '<pre>'; print_r($melanggar); echo '</pre>';
        // echo '<pre>'; print_r($individu); echo '</pre>';
        $fitness = $melanggar / count($individu);

        unset($individu);
        unset($harapan_jml);
        unset($key);
        unset($value);

        return $fitness;
        
    }

    public function count_fitness_based_rule_kelas_dosen_choose_their_time($individu){
        $jml_langgar = 0;
        foreach ($individu as $key => $value) {
            $arr_alternatif_waktu_ajar = explode('|', $value['alternatif_waktu_ajar']);
            if (!in_array($value['id_waktu'], $arr_alternatif_waktu_ajar)) {
                $jml_langgar = $jml_langgar + 1;
            }
        }

        $fitness = $jml_langgar / count($individu);
        // echo '<pre>'; print_r($fitness); 

        unset($arr_alternatif_waktu_ajar);

        return $fitness;
    }

    public function transform_populasi(){
        foreach ($this->populasi as $key => $individu) {
            $this->populasi_breeding[$key]['fitness'] = 0;
            foreach ($individu as $i => $gen) {
                /*$this->populasi_breeding[$key]['arr_gen'][$i] = array(
                    "id_kelas" => $gen['id_kelas'],
                    "id_timespace" => $gen['id_timespace'],
                    "period" => $gen['period']
                );*/
                $this->populasi_breeding[$key]['arr_gen'][$i] = $gen;
            }
        }

        // echo '<pre>'; print_r($this->populasi_breeding); 
    }

    public function count_fitness(){
        
        $this->transform_populasi();

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
            $this->total_fitness = $this->total_fitness + $populasi[$i]['fitness'];
        }
        
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

        /*$jml = 0;
        while ($jml<2) {
            $populasi_breeding_selected = array();
            foreach ($populasi_breeding_selected as $key => $value) {
                $populasi_breeding_selected[$key]['val_random'] = mt_rand(0,100000)/100000; // for selecting on crossover
            }
            
            foreach ($populasi_breeding_selected as $key => $value) {
                if ($value['val_random'] <= $this->pc) {
                    $jml++;
                }
            }
        }*/
        

        // echo '<pre>'; print_r($populasi_breeding); 
        // echo '<pre>'; print_r($rentangan); 
        // echo '<pre>'; print_r($random_number); 
        // echo '<pre>'; print_r($pick_individu); 
        // echo '<pre>'; print_r($populasi_breeding_selected);
        $this->total_fitness = 0; // set total fitness 0 karna sudah digunakan 
        $this->populasi_breeding_selected = $populasi_breeding_selected;

        unset($populasi_breeding_selected);
        unset($pick_individu);
        unset($populasi_breeding);
        unset($random_number);
        unset($rentangan);
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
                    "idx" => $key,
                    "data" => $value
                );
            }
        }

        $makul_grup = array();
        $waktudistinct_grup = array();
        foreach ($arr_kelas as $key => $value) {
            foreach ($timespace as $i => $item) {
                if ( $value['id_timespace'] == $i) {
                    for ($j=0; $j < $value['period']; $j++) { 
                        unset($timespace[$j+$i]);
                    }
                    
                } 
            }
            // membuat grup kelas per matakuliah secara dinamis
            if (!in_array($value['id_mkkur'].'-'.$value['period'], $makul_grup)) {
                $makul_grup[] = $value['id_mkkur'].'-'.$value['period'];
                $waktudistinct_grup[] = $value['id_waktu'];
            }
        }
        $timespace = array_values($timespace);

        foreach ($kelas_duplikasi as $key => $value) {
            $id_timespace = mt_rand(0,(count($timespace)-1));

            $individu[$value['idx']]["id_timespace"] = $id_timespace;
            $individu[$value['idx']]["id_waktu"] = $timespace[$id_timespace]['id_waktu'];
            $individu[$value['idx']]["id_ruang"] = $timespace[$id_timespace]['id_ruang'];
            $individu[$value['idx']]["label_timespace"] = $timespace[$id_timespace]['label'];
            $individu[$value['idx']]["kap_ruang"] = $timespace[$id_timespace]['kap_ruang'];
            $individu[$value['idx']]["waktu_hari"] = $timespace[$id_timespace]['waktu_hari'];
            $individu[$value['idx']]["waktu_jam_mulai"] = $timespace[$id_timespace]['waktu_jam_mulai'];
        }

        unset($timespace);
        unset($value);
        unset($key);
        unset($waktudistinct_grup);
        unset($makul_grup);
        unset($arr_kelas);
        unset($arr_id_timespace);
        unset($kelas_duplikasi);

        return $individu;
    }

    public function repair_kelas_on_hardrule($individu){
        $individu_temp = array();
        $timespace = $this->timespace;
        $makul_grup = array();
        $waktudistinct_grup = array();
        foreach ($individu as $key => $value) {
            $id_timespace = $value['id_timespace'];
            $value['id'] = $value['id_kelas'];
            $value['mkkur_id'] = $value['id_mkkur'];
            if ( !in_array($value['id_mkkur'].'-'.$value['period'],$makul_grup) ) {
                $break_rule = true;
                // echo '<pre>'; print_r($individu_temp); 
                // echo '<pre>'; print_r($value); 
                // echo '<pre>'; print_r($id_timespace); 
                // echo '<pre>'; print_r($timespace); 
                // exit();

                while ($break_rule) {            

                    $rule_ok = $this->check_on_hardrule($individu_temp, $value, $id_timespace, $timespace, $value['period']);
                    if ($rule_ok) {
                        $break_rule = false;
                    }else{
                        $id_timespace = mt_rand(0,(count($timespace)-1));
                    }
                }

            }else{
                // break;
                $id_timespace = $this->get_random_local($individu_temp, $value, $timespace, $makul_grup, $waktudistinct_grup, $value['period'], $id_timespace);
                

            }

            /*
            menyimpan hasil ruang & waktu untuk kelas, beserta periodenya
            */
            $waktu_jam_mulai_kls = strtotime($timespace[$id_timespace]['waktu_jam_mulai']);
            $lama_menit_kelas = $value['period'] * 50;
            $waktu_jam_selesai_kls = date("H:i:s", strtotime('+'.$lama_menit_kelas.' minutes', $waktu_jam_mulai_kls));
            
            $individu_temp[] = array(
                "id_kelas" => $value['id_kelas'],
                "nama_kelas" => $value['nama_kelas'],
                "id_mkkur" => $value['id_mkkur'],
                "jml_peserta_kls" => $value['jml_peserta_kls'],
                "format_jadwal" => $value['format_jadwal'],
                "paket_smt" => $value['paket_smt'],
                "sifat_makul" => $value['sifat_makul'],
                "id_timespace" => $id_timespace,
                "id_waktu" => $timespace[$id_timespace]['id_waktu'],
                "id_ruang" => $timespace[$id_timespace]['id_ruang'],
                "label_timespace" => $timespace[$id_timespace]['label'].$waktu_jam_selesai_kls,
                "kap_ruang" => $timespace[$id_timespace]['kap_ruang'],
                "waktu_hari" => $timespace[$id_timespace]['waktu_hari'],
                "waktu_jam_mulai" => $timespace[$id_timespace]['waktu_jam_mulai'],
                "waktu_jam_selesai_kls" => $waktu_jam_selesai_kls,
                "period" => $value['period'],
                "dosen" => $value['dosen'],
                "ruang_blok_prodi" => $value['ruang_blok_prodi'],
                "kelas_prodi" => $value['kelas_prodi'],
                "alternatif_waktu_ajar" => $value['alternatif_waktu_ajar']
            );

            // membuat grup kelas per matakuliah secara dinamis
            if (!in_array($value['id_mkkur'].'-'.$value['period'], $makul_grup)) {
                $makul_grup[] = $value['id_mkkur'].'-'.$value['period'];
                $waktudistinct_grup[] = $timespace[$id_timespace]['id_waktu'];
            }

            // menghapus index beserta nilainya untuk data ruang & waktu yg dipakai kelas untuk jadwal
            for ($t=0; $t < $value['period']; $t++) { 
                $id_timespace = $id_timespace + $t;
                unset($timespace[$id_timespace]);
            }
            
            $timespace = array_values($timespace); // set ulang index matriks ruang & waktu 

            /*if ($key == 10) {
                break;
            }*/
        }

        // echo '<pre>'; print_r($individu_temp); 


        unset($timespace);
        unset($id_timespace);
        unset($value);
        unset($waktudistinct_grup);
        unset($makul_grup);
        unset($individu);


        return $individu_temp;
    }

    public function repairing_individu($individu){
        $str = '';
        foreach ($individu as $key => $value) {
            $str .= $value['id_kelas'].'_'.$value['id_timespace'].', ';
        }

        $individu = $this->repair_duplikasi_kelas_timespace($individu);
        $str2 = '';
        foreach ($individu as $key => $value) {
            $str2 .= $value['id_kelas'].'_'.$value['id_timespace'].', ';
        }

        $individu = $this->repair_kelas_on_hardrule($individu);
        $str3 = '';
        foreach ($individu as $key => $value) {
            $str3 .= $value['id_kelas'].'_'.$value['id_timespace'].', ';
        }
        
        if (false) {
            echo '<pre>'; print_r($str);
            echo '<pre>'; print_r($str2);
            echo '<pre>'; print_r($str3);
            echo '<pre>'; print_r($individu);
            echo '<pre>'; print_r($this->timespace); 
        }

        unset($str);
        unset($str2);
        unset($str3);
        unset($value);
        unset($key);
        
        return $individu;
    }
    
    public function build_offspring_population_crossover_twopoint($parent_1, $parent_2, $point_random){
        
        // echo '<pre>'; print_r($point_random); 
        
        $jumlah_gen = count($parent_1['arr_gen']);
        $i = 0;

        $arr_gen_1 = $parent_1['arr_gen'];
        $arr_gen_2 = $parent_2['arr_gen'];
        $str = '';
        while ($jumlah_gen>0) {
            if (!in_array($i, $point_random) ) {
                $off_1[] = $arr_gen_1[$i];
                $off_2[] = $arr_gen_2[$i];

                unset($arr_gen_1[$i]);
                unset($arr_gen_2[$i]);
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

        foreach ($offspring as $key => $value) {
            $offspring[$key]['offspring'] = $this->repairing_individu($value['offspring']);
        }
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

        unset($offspring);
        unset($off_1);
        unset($off_2);
        unset($arr_gen_1);
        unset($arr_gen_2);

    }

    public function crossover(){
        foreach ($this->populasi_breeding_selected as $key => $value) {
            if ($value['val_random'] <= $this->pc) {
                $this->populasi_breeding_crossover_selected[] = $value;
            }
        }

        // echo '<pre>'; print_r($this->populasi_breeding_crossover_selected); echo '</pre>';
        $n_gen = count($this->populasi_breeding_crossover_selected[0]['arr_gen']);
        $n_ind = count($this->populasi_breeding_crossover_selected);
        // echo '<pre>'; print_r($n_ind); 
        $point_random = array(mt_rand(2,$n_gen-1), mt_rand(2,$n_gen-1) );
        for ($i=0; $i < $n_ind-1 ; $i++) { 
            $this->build_offspring_population_crossover_twopoint($this->populasi_breeding_crossover_selected[$i], $this->populasi_breeding_crossover_selected[$i+1], $point_random);
            /*if ($i == 0) {
                break;
            }*/
        }
        $this->build_offspring_population_crossover_twopoint($this->populasi_breeding_crossover_selected[($n_ind-1)], $this->populasi_breeding_crossover_selected[0], $point_random);


        unset($point_random);
        unset($key);
        unset($value);
        // echo '<pre>'; print_r(count($this->individu_breed)); 

        // echo '<pre>'; print_r(count($this->populasi_breeding_crossover_selected)); 
        // echo '<pre>'; print_r(count($this->individu_breed)); 
    }

    public function mutasi_kromosom($individu){
        $timespace = $this->timespace;

        $pos_mutasi = mt_rand(0,count($individu)-1);
        $id_timespace = mt_rand(0,(count($timespace)-1));

        $gen = $individu[$pos_mutasi];
        // echo '<pre>'; print_r($gen); 

        $waktu_jam_mulai_kls = strtotime($timespace[$id_timespace]['waktu_jam_mulai']);
        $lama_menit_kelas = $gen['period'] * 50;
        $waktu_jam_selesai_kls = date("H:i:s", strtotime('+'.$lama_menit_kelas.' minutes', $waktu_jam_mulai_kls));
        
        $individu[$pos_mutasi] = array(
            "id_kelas" => $gen['id_kelas'],
            "nama_kelas" => $gen['nama_kelas'],
            "id_mkkur" => $gen['id_mkkur'],
            "jml_peserta_kls" => $gen['jml_peserta_kls'],
            "format_jadwal" => $gen['format_jadwal'],
            "paket_smt" => $gen['paket_smt'],
            "sifat_makul" => $gen['sifat_makul'],
            "id_timespace" => $id_timespace,
            "id_waktu" => $timespace[$id_timespace]['id_waktu'],
            "id_ruang" => $timespace[$id_timespace]['id_ruang'],
            "label_timespace" => $timespace[$id_timespace]['label'],
            "kap_ruang" => $timespace[$id_timespace]['kap_ruang'],
            "waktu_hari" => $timespace[$id_timespace]['waktu_hari'],
            "waktu_jam_mulai" => $timespace[$id_timespace]['waktu_jam_mulai'],
            "waktu_jam_selesai_kls" => $waktu_jam_selesai_kls,
            "period" => $gen['period'],
            "dosen" => $gen['dosen'],
            "ruang_blok_prodi" => $gen['ruang_blok_prodi'],
            "kelas_prodi" => $gen['kelas_prodi'],
            "alternatif_waktu_ajar" => $gen['alternatif_waktu_ajar']
        );
        // echo '<pre>'; print_r($individu[$pos_mutasi]); 

		unset($pos_mutasi);
		unset($id_timespace);
		unset($timespace);
		unset($gen);

        $individu = $this->repairing_individu($individu);
        return $individu;
    }

    public function mutation(){
        // echo '<pre>'; print_r($this->individu_breed); 
        foreach ($this->individu_breed as $key => $value) {
            foreach ($value as $i => $item) {
                // echo '<pre>'; print_r($item['randvalmut']); 
                if ($item['randvalmut'] < $this->pm) {                    
                    $this->individu_breed[$key][$i]['offspring'] = $this->mutasi_kromosom($item['offspring']);
                }

                $this->individu_update_calon[] = $this->individu_breed[$key][$i];
            }
        }

        unset($key);
        unset($value);
    }

    public function count_total_fitness_populasi_breeding(){
        $total = 0;
        foreach ($this->populasi_breeding as $key => $value) {
            $total = $total + $value['fitness'];
        }

        return $total;

    }

    public function update_selection(){
        $populasi_breeding = $this->populasi_breeding;
        $this->populasi_breeding = array();
        $n = count($this->individu_update_calon);
        // echo '<pre>'; print_r($n); 
        // echo '<pre>'; print_r($this->individu_update_calon); 
        // echo '<pre>'; print_r($this->populasi_breeding_selected); 
        foreach ($populasi_breeding as $key => $value) {
            $this->populasi_breeding[] = array(
                "fitness" => $value['fitness'],
                "arr_gen" => $value['arr_gen']
            );
        }

        foreach ($this->individu_update_calon as $key => $value) {
            $this->populasi_breeding[] = array(
                "fitness" => $value['fitness'],
                "arr_gen" => $value['offspring']
            );
        }

        $this->total_fitness = $this->count_total_fitness_populasi_breeding();        

        $this->roulette_wheel_selection();

        foreach ($this->populasi_breeding_selected as $key => $value) {
            $this->populasi_baru[] = $value['arr_gen'];
        }

        unset($populasi_breeding);
        unset($key);
        unset($value);
        // echo '<pre>'; print_r($this->populasi_baru); 
    }

    public function update_population(){
        $this->populasi = array();
        foreach ($this->populasi_baru as $key => $value) {
            $this->populasi[] = $this->repairing_individu($value);
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

        // echo '<pre>max fitness : '; print_r($max_fitness); 
        // echo '<pre>max fitness : '; print_r($this->populasi_breeding_selected[$idx]); 

        return $this->populasi_breeding_selected[$idx];
    }

    

}

/* End of file Someclass.php */