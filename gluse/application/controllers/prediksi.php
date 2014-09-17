<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package		Gluse
 * @subpackage	prediksi
 * @author		Eko Budi Prasetyo
 * @version		0.0.0
 * @since		May 30, 2014
 */

class Prediksi extends CI_Controller {
	var $data;

	function __construct() {
        parent::__construct();
        $this->load->library('bantu');
        $this->data['parse_breadcrumbs'] = $this->bantu->getBreadcrumbs();
		$this->data['parse_uri_root'] = $this->bantu->getRootAddress();
		$this->load->model('Prediksi_model');
    }

	/**
	 * @author		Eko Budi Prasetyo
	 * @version		0.0.0
	 * @since		May 30, 2014
	 * @usedfor		-
	 */
	public function index(){
		/**********processRequest**********/		
		$param['judul_halaman'] = "Halaman home";

		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');
		$filter['semester_aktif'] = $this->bantu->getConfig('semester_aktif');		
		$param['filter'] = $filter;

		$param['data'] = $this->Prediksi_model->get_matakuliah($param['filter']);
		$param['jumlah_data'] = $this->Prediksi_model->get_count_matakuliah($param['filter']);

		$url_this = $this->bantu->getRootAddress().'index.php/prediksi/index/';
		$param['paging'] = $this->bantu->getPaging($url_this, $param['jumlah_data']);

		$param['url_rekap_matakuliahe'] = $this->bantu->getRootAddress().'index.php/prediksi/rekap_matakuliah';
		$param['url_rekap_matakuliah'] = $this->bantu->getRootAddress().'index.php/prediksi/rekap_matakuliah';
		
		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif_up_mkk');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
		}

		// if ($sess_notif) {
		// 	$param['display'] = '';
		// 	$param['notif_style'] = $sess_notif['style'];
		// 	$param['notif_message'] = $sess_notif['msg'];
		// }

		$template_konten = 'prediksi/makul_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);

	}

	function rekap_matakuliah(){
		/**********processRequest**********/
		$rekap_inserted = $this->Prediksi_model->cek_rekap_inserted();
		$param['display_warning_kelasnotgenerated'] = !$rekap_inserted?'':'style="display:none"';
		$param['display_button_generatedclass'] = $rekap_inserted?'':'style="display:none"';

		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page'); 
		$filter['semester_aktif'] = $this->bantu->getConfig('semester_aktif');	
		$param['filter'] = $filter;

		$param['data'] = $this->Prediksi_model->get_matakuliah_rekap($param['filter']);
		$param['jumlah_data'] = $this->Prediksi_model->get_count_matakuliah_rekap($param['filter']);
		
		$url_this = $this->bantu->getRootAddress().'index.php/prediksi/rekap_matakuliah';
		$param['paging'] = $this->bantu->getPaging($url_this, $param['jumlah_data']);

		$param['url_proses_prediksi'] = $this->bantu->getRootAddress().'index.php/prediksi/proses_prediksi';
		$param['url_import_rekap_matakuliah'] = $this->bantu->getRootAddress().'index.php/prediksi/import_rekap_matakuliah';

		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif_ins_rkpmkk');
		if ($flash_message) {
			$param['display'] = '';
			$notif = $this->session->flashdata('notif_ins_rkpmkk');
			$param['notif_style'] = $notif['style'];
			$param['notif_message'] = $notif['msg'];
		}

		$periode_tahun_prediksi = $this->bantu->getConfig('periode_tahun_prediksi');
		for ($i=0; $i < $periode_tahun_prediksi; $i++) { 
			$param['data_tahun'][] = array(
				'val' => date('Y')-$periode_tahun_prediksi+$i,
				'label' => date('Y')-$periode_tahun_prediksi+$i
			);
		}
		
		// exit();
		$template_konten = 'prediksi/rekap_matakuliah_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function import_rekap_matakuliah(){
		/**********processRequest**********/

		$param['url_rekap_matakuliah'] =$this->bantu->getRootAddress().'index.php/prediksi/rekap_matakuliah';
		$param['url_submit'] = $this->bantu->getRootAddress().'index.php/proses_prediksi/proses_import_rekap_matakuliah';
		$param['url_download_format_excel'] = $this->bantu->getRootAddress().'index.php/prediksi_xls/download_format_rekap_matakuliah';
		
		$template_konten = 'prediksi/import_rekap_matakuliah_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function proses_prediksi(){
		/**********processRequest**********/

		$param['url_rekap_matakuliah'] =$this->bantu->getRootAddress().'index.php/prediksi/rekap_matakuliah';
		$param['url_submit'] = $this->bantu->getRootAddress().'index.php/proses_prediksi/lakukan_prediksi';
		$param['url_download_format_excel'] = $this->bantu->getRootAddress().'index.php/prediksi_xls/download_format_rekap_matakuliah';
		$param['display'] = 'style="display:none"';
		
		$template_konten = 'prediksi/proses_prediksi_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function build_makulprodi(){
		$this->load->model('Build_data_model');

		$mkprodi = $this->Build_data_model->get_mkprodi();
		// echo '<pre>'; print_r($mkprodi); 

		$sts = true;
		if (!empty($mkprodi)) {
			$this->db->trans_start();
			$total_ins = 0;
			foreach ($mkprodi as $a => $item) {
				$sts = $sts && $this->Build_data_model->ins_mkprodi($item['MK_ID'], $item['PRODI_ID']);
				$total_ins++;
			}

			$this->db->trans_complete();

		}

		if ($this->db->trans_status() === true){
			echo 'Data inserted : '.$total_ins;
		}else{
			echo 'not_ok';
		}

		exit();
	}
}