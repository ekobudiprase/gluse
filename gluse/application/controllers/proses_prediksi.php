<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package		Gluse
 * @subpackage	prediksi
 * @author		Eko Budi Prasetyo
 * @version		0.0.0
 * @since		May 30, 2014
 */

class Proses_prediksi extends CI_Controller {
	var $data;

	function __construct() {
        parent::__construct();
        $this->load->library('bantu');
        $this->data['parse_breadcrumbs'] = $this->bantu->getBreadcrumbs();
		$this->data['parse_uri_root'] = $this->bantu->getRootAddress();

    }

	/**
	 * @author		Eko Budi Prasetyo
	 * @version		0.0.0
	 * @since		May 30, 2014
	 * @usedfor		-
	 */
	public function proses_import_rekap_matakuliah(){
		/**********processRequest**********/
		
		$url_rekap_matakuliah = $this->bantu->getRootAddress().'index.php/prediksi/rekap_matakuliah';
		if (isset($_POST['back'])) {
			redirect($url_rekap_matakuliah, 'refresh');
		}
		
		$this->load->model('Prediksi_model');
		$this->load->library('phpexcel');
		$this->load->library('PHPExcel/iofactory');
		$this->load->library('bantu');
		$this->load->library('session');

		// bug : dir upload didn't change for the first upload, fix later
		$objPHPExcel = Iofactory::load($_FILES['file']['name']);
		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

		// echo '<pre>'; print_r($sheetData); exit();
		$periode_tahun_prediksi = $this->bantu->getConfig('periode_tahun_prediksi');
		// echo '<pre>'; print_r($periode_tahun_prediksi); 
		$matakuliah = $this->Prediksi_model->get_all_matakuliah();
		$jumlah_matakuliah = count($matakuliah);

		// data excel transformation for ready to ins
		for ($i=0; $i < $jumlah_matakuliah; $i++) { 
			$dataxls[$i]['kode'] = $sheetData[($i+5)]['A'];
			$dataxls[$i]['id_mk'] = $this->Prediksi_model->get_idmakul_by_kodemakul($dataxls[$i]['kode']);
			for ($j=0; $j < $periode_tahun_prediksi; $j++) { 
				$columnStr[$j] = PHPExcel_Cell::stringFromColumnIndex($j+3);
				$columnVal[$j] = $sheetData[($i+5)][$columnStr[$j]];
				$columnTahun[$j] = date('Y')-$periode_tahun_prediksi+$j;

			}
			// $dataxls[$i]['col'] = $columnStr;
			$dataxls[$i]['tahun'] = $columnTahun;
			$dataxls[$i]['val'] = $columnVal;
		}

		// echo '<pre>'; print_r($dataxls); 
		// exit();

		$sts = true;
		if (!empty($dataxls)) {
			$this->db->trans_start();
			
			$sts = $sts && $this->Prediksi_model->truncate_matakuliahrekap();
			foreach ($dataxls as $a => $item) {
				foreach ($item['tahun'] as $b => $item_chd) {
					$param = array(
						'id_mk' => $dataxls[$a]['id_mk'],
						'jml_peminat' => $dataxls[$a]['val'][$b],
						'tahun' => $dataxls[$a]['tahun'][$b],
					);
					
					$sts = $sts && $this->Prediksi_model->ins_matakuliahrekap($param);
				}
			}
			$this->db->trans_complete();

		}

		if ($this->db->trans_status() === true){
			$notif = array(
				'style' => 'success',
				'msg' => '<strong>Congratulation</strong> Data rekap mata kuliah berhasil ditambahkan.'
			);
		}else{
		    $notif = array(
				'style' => 'error',
				'msg' => '<strong>Warning</strong> Data rekap mata kuliah gagal ditambahkan.'
			);
		}
		$this->session->set_flashdata('notif_ins_rkpmkk', $notif);
		// $this->session->keep_flashdata('notif_ins_rkpmkk');
		// echo $this->session->flashdata('notif_ins_rkpmkk');
		// echo '<pre>'; print_r($notif); 
		// echo '<pre>'; print_r($flash_message); 
		// echo '<pre>'; print_r($_SESSION); 
		// echo '<pre>'; print_r($this->session->all_userdata()); 
		// exit();
		
		redirect($url_rekap_matakuliah);
		exit();

	}

	/**
	 * @author		Eko Budi Prasetyo
	 * @version		0.0.0
	 * @since		May 30, 2014
	 * @usedfor		-
	 */
	public function lakukan_prediksi(){
		/**********processRequest**********/
		
		$url_rekap_matakuliah = $this->bantu->getRootAddress().'index.php/prediksi/rekap_matakuliah';
		if (isset($_POST['back'])) {
			redirect($url_rekap_matakuliah, 'refresh');
		}
		
		$this->load->library('backpropagation');
		$this->load->model('Prediksi_model');
		
		$layer_formation = explode('-', $_POST['layer_formation']);
		$numLayers = count($layer_formation);
		$jml_unit_in = $layer_formation[0];
		$jml_unit_out = $layer_formation[($numLayers-1)];
		$length_input_data_train = $jml_unit_in + $jml_unit_out;

		$periode_tahun_prediksi = $this->bantu->getConfig('periode_tahun_prediksi');

		$mkk = $this->Prediksi_model->get_listid_matakuliah();

		$d = 0;
		foreach ($mkk as $key => $value) {
			$rekap_makul[$key] = $this->Prediksi_model->get_matakuliahrekap_by_makulid($value['mkk_id']);
			// echo '<pre>'; print_r($rekap_makul[0]); 
			$i = 0;
			
			for ($i=0; $i < $periode_tahun_prediksi; $i++) { 

				if ($i+($length_input_data_train-1) < $periode_tahun_prediksi ) {
					// echo "main- ".$i."<br>";
					for ($j=$i; $j <= $i+($length_input_data_train-1) ; $j++) { 
						// echo $j.', ';
				    	// $data[$d][] = $rekap_makul[$key][$j]['interpolasi'];
				    	$max_lokal = $rekap_makul[$key][$j]['max_lokal'];
				    	$min_lokal = $rekap_makul[$key][$j]['min_lokal'];
				    	$data_raw[$key][$i][] = $rekap_makul[$key][$j]['jml_peminat'];
				    	// $data[$key][$i][] = $rekap_makul[$key][$j]['interpolasi'];
				    	$data[$key][$i][] = $rekap_makul[$key][$j]['interpolasi_0'];
				    	// $data_uninterpolated[$key][$i][] = $this->unInterpolasi($rekap_makul[$key][$j]['interpolasi']);
				    }
				    // echo "<br>";
				}
			}
			

			for ($k=($periode_tahun_prediksi-$jml_unit_in); $k < $periode_tahun_prediksi; $k++) { 
				// echo "main- ".$k."<br>";
		    	// $data[$d][] = $rekap_makul[$key][$j]['interpolasi'];
		    	// $testDataUji[$key][] = $rekap_makul[$key][$k]['interpolasi'];
		    	// $testDataUji[$key][] = $rekap_makul[$key][$k]['interpolasi_0'];
		    	$testDataUji[$key][] = $rekap_makul[$key][$k]['jml_peminat'];
		    }

		    $arrData[] = array(
		    	'id' => $value['mkk_id'],
		    	'kode' => $value['kode'],
		    	'nama' => $value['nama'],
		    	// 'dataTrain_raw' => $data_raw[$key],
		    	// 'dataTrain_uninterpolated' => $data_uninterpolated[$key],
		    	// 'dataTrain' => $data[$key],
		    	'dataTrain' => $data_raw[$key],
		    	'dataTestUji' => array($testDataUji[$key]),
		    	'max_lokal' => $max_lokal,
		    	'min_lokal' => $min_lokal
		    );


		}
		// echo '<pre>'; print_r($arrData); 
		// exit();

		foreach ($arrData as $a => $item) {
			foreach ($item['dataTrain'] as $b => $itemm) {
				for ($i=0; $i < count($itemm)-1; $i++) { 
					$arrData[$a]['dataTest'][$b][] = $itemm[$i];
				}

			}	
		}

		// echo '<pre>'; print_r($arrData);
		// $coba = 0.0000;
		// $coba = $this->unInterpolasi($coba); 
		// echo '<pre>'; print_r($coba); 
		// exit();


		$ret = array();
		$maxmin = $this->Prediksi_model->get_maxmin_jmlpeminat_mkkrekap();
		// echo '<pre>'; print_r($arrData); exit();
		if (!empty($arrData)) {
			
			foreach ($arrData as $key => $value) {
				// echo '<pre>'; print_r($value); 

				$this->backpropagation->set($numLayers,$layer_formation, $_POST['beta'], $_POST['alpha'],$maxmin[0]['mins'],$maxmin[0]['maks'], $_POST['epoch'],$_POST['treshold']);
				$this->backpropagation->createWeight();
				$result = $this->backpropagation->run($value);
				$log_proses[] = $result['log_proses'];
				$ret[] = $result;
				/*if ($key == 5) {
					break;
				}*/
			}
		}

		$sts_save_log = $this->set_log_proses($log_proses);
		// $_SESSION['hasil_prediksi'] = $ret;
		// echo '<pre>'; print_r($log_proses); 
		// exit();


		$this->db->trans_start();
		$sts = true && $sts_save_log;
		if (!empty($ret)) {
			foreach ($ret as $key => $value) {
				$param = array(
					'pre_jml_peminat' => $value['hasil_prediksi'],
					// 'pre_jml_peminat_normal' => $this->unInterpolasi($value['hasil_prediksi']),
					'pre_jml_peminat_normal' => $value['hasil_prediksi'],
					'id_mk' => $value['id']
				);
				// echo '<pre>'; print_r($param); 
				

				$sts = $sts && $this->Prediksi_model->up_predjmlpnt_matakuliah($param);
			}
		}

		$this->db->trans_complete();
		// exit();
		
		if ($this->db->trans_status() === true){
			$notif = array(
				'style' => 'success',
				'msg' => '<strong>Congratulation</strong> Prediksi jumlah peminat mata kuliah telah disimpan.'
			);
		}else{
		    $notif = array(
				'style' => 'error',
				'msg' => '<strong>Warning</strong> Prediksi jumlah peminat mata kuliah gagal disimpan.'
			);
		}
		$this->session->set_flashdata('notif_up_mkk', $notif);

		$url_matakuliah = $this->bantu->getRootAddress().'index.php/prediksi/index';
		redirect($url_matakuliah);
		exit();

	}

	function set_log_proses($log_proses){
        $data = base64_encode(serialize($log_proses));
        $sts = $this->bantu->simpan_log_proses('jst_prediksi', $data);

        return $sts;
    }

	function unInterpolasi($x2){
		$this->load->model('Prediksi_model');
		$maxmin = $this->Prediksi_model->get_maxmin_jmlpeminat_mkkrekap();

		// $x1 = (($x2 - 0.1) * ($maxmin[0]['maks']-$maxmin[0]['mins'])/0.8) + $maxmin[0]['mins'];
		$x1_0 = (($x2 ) * ($maxmin[0]['maks']-$maxmin[0]['mins']) ) + $maxmin[0]['mins'];
		$x1 = round($x1_0,0);

		return $x1;

	}

	
}