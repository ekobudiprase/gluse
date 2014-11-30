<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package		Gluse
 * @subpackage	prediksi
 * @author		Eko Budi Prasetyo
 * @version		0.0.0
 * @since		May 30, 2014
 * @updated		June 12, 2014
 */

class Proses_penjadwalan extends CI_Controller {
	var $data;

	function __construct() {
        parent::__construct();
        $this->load->library('bantu');
        $this->data['parse_breadcrumbs'] = $this->bantu->getBreadcrumbs();
		$this->data['parse_uri_root'] = $this->bantu->getRootAddress();

    }

    /**
     * @package		Gluse
     * @subpackage	penjadwalan
     * @author		Eko Budi Prasetyo
     * @version		0.0.0
     * @since		june 12, 2014
     */
	/*
    Aturan pembuatan kelas
    1. Kondisional: jika kurang dari min (A), jika diantara range (B), jika melebihi (C)
    2. A : kelas tidak diadakan, return null
    3. B : cek makul tipe universal
    - jika universal atau tdk spesifik diadakan di prodi tnt, mk nama kelas = nama makul
    - else, nama kelas prefixnya semua kode_prodi dan kelas dijadikan satu krn < min
    4. C : cek makul tipe universal
    - jika universal, nama_kelas lgsg di prefix urutan, tanpa memperhatikan prodi
    - else, cek prodi makul
    - jika ada prodi makul, lakukan penamaan kelas dengan memperhatikan gabungan prodi atau independen prodi
    dan juga memperhatikan rasio kelas msg2 prodi jika 1 makul untuk bnyak prodi, kemudian lakukan klasifikasi untuk seri
    kelasnya dengam alfabet.
    - else tdk ada prodi makul, lgsg lakukan klasifikasi dengan nama kelas = nama makul yg ditambah suffix urutan jika
    lebih dari satu kelas.
    */
    function build_kelas_per_makul($param){
    	$this->load->model('Penjadwalan_model');
		$mkprodi = $this->Penjadwalan_model->get_base_mkprodid_by_mkid($param['idmk']);

    	$kelas = array();
    	if ($param['pred_jml_peminat'] < $param['batas_jml_kelas_min']) {
			if ($param['sifat'] == 'W') {
				$kelas[] = array(
					'kelas' => null,
					'nama_kelas' => $param['kode'],
					'jumlah_per_kelas' => $param['pred_jml_peminat'],
					'kode_makul' => $param['kode'],
					'nama_makul' => $param['nama'],
					'id_makul' => $param['idmk'],
					'kls_jadwal_merata' => $param['is_bersama'],
					'kls_id_grup_jadwal' => null
				);
			}else{
				$kelas[] = null;
			}
			
		}elseif($param['pred_jml_peminat'] >= $param['batas_jml_kelas_min'] && $param['pred_jml_peminat'] <= $param['batas_jml_kelas']){
			if ($param['is_universal']==1 OR count($mkprodi)==0) {
				$nama_kelas = $param['kode'];

				$kelas[] = array(
					'kelas' => null,
					'nama_kelas' => $param['kode'],
					'jumlah_per_kelas' => $param['pred_jml_peminat'],
					'kode_makul' => $param['kode'],
					'nama_makul' => $param['nama'],
					'id_makul' => $param['idmk'],
					'kls_jadwal_merata' => 1,
					'kls_id_grup_jadwal' => null
				);
			}else{
				if ($param['is_bersama']==1) {
					$nama_kelas = 'KEMIPAAN';
				}else{
					$prodi_makul = $mkprodi[0]['prodi_kode'];
					foreach ($mkprodi as $key => $value) {
						if ($key>0) {		
							$param_prd = array(
				                "prodi_id" => $value['mkkprod_id'],
				                "prodi_kode" => $value['prodi_kode'],
				                "prodi_nama" => $value['prodi_nama']
				            );
				            $prodi[$key] = $this->Penjadwalan_model->get_concat_data_prodi($param_prd);	
				            $prodi_makul .= '-'.$prodi[$key]['prodi_kode'];	
			            }	            
			        }	
			        $nama_kelas = $prodi_makul.'-'.$param['kode'];
				}
				

		        $kelas[] = array(
					'kelas' => null,
					'nama_kelas' => $nama_kelas,
					'jumlah_per_kelas' => $param['pred_jml_peminat'],
					'kode_makul' => $param['kode'],
					'nama_makul' => $param['nama'],
					'id_makul' => $param['idmk'],
					'kls_jadwal_merata' => $param['is_bersama'],
					'kls_id_grup_jadwal' => null
				);
			}
			
		}else{
			// echo '<pre>'; print_r($mkprodi); 
			if ($param['is_universal']==1 ) {

				$prodi_makul = '';
				$param_klsf = array(
	            	"jml_porsi" => $param['pred_jml_peminat'],
	            	"batas_jml_kelas" => $param['batas_jml_kelas'],
	            	"prodi_makul" => $prodi_makul,
	            	"kode_makul" => $param['kode'],
	            	"nama_makul" => $param['nama'],
	            	"id_makul" => $param['idmk'],
	            	"uni" => true,
	            	"bersama" => $param['is_bersama']
	            );

	            $kelas = $this->klasifikasi($param_klsf);
			}else{
				if (count($mkprodi)>0) {
					foreach ($mkprodi as $key => $value) {
						$param_prd = array(
			                "prodi_id" => $value['mkkprod_id'],
			                "prodi_kode" => $value['prodi_kode'],
			                "prodi_nama" => $value['prodi_nama']
			            );
			            $prodi[$key] = $this->Penjadwalan_model->get_concat_data_prodi($param_prd);	
			            $prodi_makul = $prodi[$key]['prodi_kode'];

			            // echo '<pre>'; print_r($prodi[$key]);
			            if ($key == 0) {
			            	$value['jml_porsi'] += $value['sisa']; //krn pembagian integer, maka sisanya dikasih ke pertama
			            }
			            $param_klsf = array(
			            	"jml_porsi" => $value['jml_porsi'],
			            	"batas_jml_kelas" => $param['batas_jml_kelas'],
			            	"prodi_makul" => $prodi_makul,
			            	"kode_makul" => $param['kode'],
			            	"nama_makul" => $param['nama'],
			            	"id_makul" => $param['idmk'],
			            	"uni" => false,
	            			"bersama" => $param['is_bersama']
			            );

			            $kelas_temp = $this->klasifikasi($param_klsf);
			            foreach ($kelas_temp as $i => $item) {
			            	$kelas[] = $item;
			            }

					}	
				}else{
					$prodi_makul = '';
					$param_klsf = array(
		            	"jml_porsi" => $param['pred_jml_peminat'],
		            	"batas_jml_kelas" => $param['batas_jml_kelas'],
		            	"prodi_makul" => $prodi_makul,
		            	"kode_makul" => $param['kode'],
		            	"nama_makul" => $param['nama'],
		            	"id_makul" => $param['idmk'],
		            	"uni" => false,
	            		"bersama" => $param['is_bersama']
		            );

		            $kelas = $this->klasifikasi($param_klsf);
				}				
			}
		}

		return $kelas;    	
    }

    function buildGroupJadwalKemipaan($jumlah_kelas){
    	$jml_per_hari = ceil($jumlah_kelas/5);
    	$penuh_hari = floor($jumlah_kelas/$jml_per_hari);
    	$sisa = $jumlah_kelas%$jml_per_hari;
    	$id = 1;
    	for ($i=0; $i < $penuh_hari; $i++) { 
    		for ($j=0; $j < $jml_per_hari; $j++) { 
    			$t[] = $id;
    		}
    		$id++;
    	}
    	if ($sisa>0) {
    		$t[] = $id;
    	}

    	return $t;
    }

    /**
     * @package		Gluse
     * @subpackage	penjadwalan
     * @author		Eko Budi Prasetyo
     * @version		0.0.0
     * @since		june 12, 2014
     */
    function klasifikasi($param){
    	$kelas_bagi = ceil($param['jml_porsi']/$param['batas_jml_kelas']);
    	$arr_id_group_jadwal_kemipaan = array();
    	if ($param['bersama']==1 OR $param['uni']) {
    		$arr_id_group_jadwal_kemipaan = $this->buildGroupJadwalKemipaan($kelas_bagi);
    	}
		$mod = $param['jml_porsi'] % $kelas_bagi;
		for ($i=0; $i < $kelas_bagi; $i++) { 
			$jumlah_per_kelas = floor($param['jml_porsi'] / $kelas_bagi);
			
			if ($mod > 0 and $i==0) {
				$jumlah_per_kelas = ( floor($param['jml_porsi'] / $kelas_bagi)) + $mod;
			}
			$id_group_jadwal_kemipaan = null;
			$kelas_nama = '';
			if ($param['uni']) {
				if ($kelas_bagi>1) {
					$kelas_nama = $i+1;
				}
				$nama_kelas = $param['kode_makul'].'-'.$kelas_nama;
				$kls_jadwal_merata = 1;
				$id_group_jadwal_kemipaan = $arr_id_group_jadwal_kemipaan[$i];
			}else{
				if ($param['bersama'] == 1) {
					if ($kelas_bagi>1) {
						$kelas_nama = $i+1;
					}
					$nama_kelas = 'KEMIPAAN-'.$kelas_nama;
					$id_group_jadwal_kemipaan = $arr_id_group_jadwal_kemipaan[$i];
				}else{
					if ($kelas_bagi>1) {
						$kelas_nama = chr($i+65);
					}
					if ($param['prodi_makul'] == '') {
						$nama_kelas = $param['kode_makul'].'-'.($kelas_nama!=''?$kelas_nama:'');
					}else{
						$nama_kelas = $param['prodi_makul'].'-'.($kelas_nama!=''?$kelas_nama.'-'.$param['kode_makul']:$param['kode_makul']);
					}
				}
				$kls_jadwal_merata = $param['bersama'];
				
			}
			$kelas[] = array(
				'kelas' => $kelas_nama,
				'nama_kelas' => $nama_kelas,
				'jumlah_per_kelas' => $jumlah_per_kelas,
				'kode_makul' => $param['kode_makul'],
				'nama_makul' => $param['nama_makul'],
				'id_makul' => $param['id_makul'],
				'kls_jadwal_merata' => $kls_jadwal_merata,
				'kls_id_grup_jadwal' => $id_group_jadwal_kemipaan
			);

		}

		return $kelas;
    }
	
	/**
	 * @author		Eko Budi Prasetyo
	 * @version		0.0.0
	 * @since		May 30, 2014
	 * @usedfor		-
	 */
	public function generating_kelas(){
		/**********processRequest**********/
		
		$url_makul = $this->bantu->getRootAddress().'index.php/penjadwalan/';
		if (isset($_POST['back'])) {
			redirect($url_makul, 'refresh');
		}

		$this->load->model('Penjadwalan_model');
		$this->load->library('bantu');
		$semester_aktif = $this->bantu->getConfig('semester_aktif');
		
		$data_makul = $this->Penjadwalan_model->get_data_makul($semester_aktif);
		// echo '<pre>'; print_r($_POST); echo '</pre>';
		// echo '<pre>'; print_r($data_makul); echo '</pre>'; exit();
		if (!empty($data_makul)) {
			foreach ($data_makul as $key => $value) {
				$maks_kelas = ($value['maks_kelas']!=null)?$value['maks_kelas']:$_POST['batas_jml_kelas'];
				$is_bersama = substr($value['kode'], 2, 1)=='B'?1:0;
				$param = array(
					'idmk' => $value['id'],
					'kode' => $value['kode'],
					'nama' => $value['nama'],
					'pred_jml_peminat' => $value['pred_jml_peminat'],
					'batas_jml_kelas_min' => $_POST['batas_jml_kelas_min'],
					'batas_jml_kelas' => $maks_kelas,
					'is_universal' => $value['is_universal'],
					'sifat' => $value['sifat'],
					'is_bersama' => $is_bersama
				);
				// if ($value['id']=='12') {
				// 	$arr_kelas[] = $this->build_kelas_per_makul($param);
				// 	exit();
				// }
				$arr_kelas[] = $this->build_kelas_per_makul($param);
			}

			// echo 'daftar kelas : <pre>'; print_r($arr_kelas); 
			// exit();
		}

		if (!empty($arr_kelas)) {
			foreach ($arr_kelas as $key => $value) {
				if (!empty($value[0])) {
					foreach ($value as $i => $item) {
						$arr_kelas_new[] = $item;
					}
				}
			}
		}

		// echo '<pre>'; print_r($data_makul); 
		// echo '<pre>'; print_r($arr_kelas_new); 
		// exit();

		$sts = true;
		if (!empty($arr_kelas_new)) {
			$this->db->trans_start();

			$sts = $sts && $this->Penjadwalan_model->del_dsnkelas_ref_kelas();
			$sts = $sts && $this->Penjadwalan_model->del_record_kelas();
			foreach ($arr_kelas_new as $a => $item) {
				$sts = $sts && $this->Penjadwalan_model->ins_kelas($item);
			}

			$this->db->trans_complete();

		}

		if ($this->db->trans_status() === true){
			$notif = array(
				'style' => 'success',
				'msg' => '<strong>Congratulation</strong> Kelas berhasil dibuat.'
			);
		}else{
		    $notif = array(
				'style' => 'error',
				'msg' => '<strong>Warning</strong> Kelas gagal dibuat.'
			);
		}
		$this->session->set_flashdata('notif', $notif);

		$url_matakuliah = $this->bantu->getRootAddress().'index.php/penjadwalan/kelas';
		redirect($url_matakuliah);
		exit();

	}


	public function save_dosen_kelas(){
		$this->load->model('Penjadwalan_model');

		$sts = true;
		$this->db->trans_start();
		$sts = $sts && $this->Penjadwalan_model->del_dsnkelas_by_idkelas($_POST['id_kelas']);
		if (!empty($_POST['dosen'])) {			
			foreach ($_POST['dosen'] as $a => $item) {
				$param = array(
					'id_dosen' => $item,
					'id_kelas' => $_POST['id_kelas']
				);
				$sts = $sts && $this->Penjadwalan_model->ins_dosenkelas($param);
			}
			$this->db->trans_complete();
		}

		if ($this->db->trans_status() === true){
			$notif = array(
				'style' => 'success',
				'msg' => '<strong>Congratulation</strong> Dosen kelas berhasil disimpan.'
			);
		}else{
		    $notif = array(
				'style' => 'error',
				'msg' => '<strong>Warning</strong> Dosen kelas gagal disimpan.'
			);
		}
		$this->session->set_flashdata('notif', $notif);

		$url_kelas = $this->bantu->getRootAddress().'index.php/penjadwalan/kelas';
		redirect($url_kelas	);
		exit();
	}

	function lakukan_penjadwalan(){
    	$this->load->library('algen');
		$this->load->model('Penjadwalan_model');
        $this->load->library('bantu');
		
		$kelas = $this->Penjadwalan_model->get_all_kelas();
		$ruang = $this->Penjadwalan_model->get_all_ruang();
		$waktu = $this->Penjadwalan_model->get_all_waktu();
		$prodi = $this->Penjadwalan_model->get_all_prodi();
		

		if (!empty($kelas) && !empty($ruang) && !empty($waktu) ) {
			foreach ($kelas as $key => $value) {
				$dosen = $this->Penjadwalan_model->get_iddosen_by_idkelas($value['id']);
				$kelas[$key]['dosen'] = $dosen;
			}
			
			$min_prosen_capacity = $this->bantu->getConfig('min_persen_kelas');
			
			/*
			** PROSES ALGORITMA GENETIKA
			*/
			$waktu_start = microtime(true);
			$this->algen->initialize($kelas, $ruang, $waktu, $_POST, $prodi, $min_prosen_capacity);
			for ($i=0; $i < $_POST['generation']; $i++) {
				if ($i == 0) {
					$this->algen->generate_population();
				}else{
					$this->algen->update_population();
				}
				
				$this->algen->count_fitness();
				$this->algen->roulette_wheel_selection();
				$this->algen->crossover();
				$this->algen->mutation();
				$this->algen->update_selection();

			}
			$solusi = $this->algen->get_solution();
			$solusi = $solusi['arr_gen'];
			// $total_waktu = microtime(true) - $waktu_start;
			$total_waktu = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];

			$result = $this->bantu->getDataLogproses('algen_penjadwalan');
			// echo 'max_fitness: <pre>'; print_r($result['max_fitness']); echo '</pre>';
			// echo 'total_waktu: <pre>'; print_r($total_waktu); echo '</pre>';
			// exit();
			// echo '<pre>'; print_r($solusi); echo '</pre>';
		}


    	// echo '<pre>'; print_r($_POST); 
    	// exit();

    	$sts = true;
		$this->db->trans_start();
		$sts = $sts && $this->Penjadwalan_model->del_jadwalkuliah();
		if (!empty($solusi)) {			
			foreach ($solusi as $a => $item) {
				$param = array(
					'id_kelas' => $item['id_kelas'],
					'id_waktu' => $item['id_waktu'],
					'id_ruang' => $item['id_ruang'],
					'period' => $item['period'],
					'jam_selesai' => $item['jam_selesai'],
					'label' => $item['label_timespace']
				);
				$sts = $sts && $this->Penjadwalan_model->ins_jadwalkuliah($param);
			}
			$this->db->trans_complete();
		}

    	if ($this->db->trans_status() === true){
			$notif = array(
				'style' => 'success',
				'msg' => '<strong>Congratulation</strong> Jadwal kuliah berhasil disimpan. Waktu pemrosesan algoritma genetika <strong>'.$total_waktu.'</strong> detik.'
			);
		}else{
		    $notif = array(
				'style' => 'error',
				'msg' => '<strong>Warning</strong> Jadwal kuliah gagal disimpan.'
			);
		}
		$this->session->set_flashdata('notif', $notif);

		$url_kelas = $this->bantu->getRootAddress().'index.php/penjadwalan/jadwal_kuliah';
		redirect($url_kelas	);
		exit();
    }

	
}