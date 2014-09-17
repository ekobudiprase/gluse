<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package		Gluse
 * @subpackage	penjadwalan
 * @author		Eko Budi Prasetyo
 * @version		0.0.0
 * @since		May 30, 2014
 */

class Pengelolaan extends CI_Controller {
	var $data;

	function __construct() {
        parent::__construct();
        $this->load->library('bantu');
        $this->data['parse_breadcrumbs'] = $this->bantu->getBreadcrumbs();
		$this->data['parse_uri_root'] = $this->bantu->getRootAddress();
		$this->load->model('pengelolaan_model');
		$this->load->model('prodi_model');
		$this->load->model('dosen_model');
		$this->load->model('ruang_model');
    }

	/**
	 * @author		Eko Budi Prasetyo
	 * @version		0.0.0
	 * @since		3juni, 2014
	 * @usedfor		-
	 */
	public function index(){
		/**********processRequest**********/	
		$param['url_konfigurasi'] =$this->bantu->getRootAddress().'index.php/pengelolaan/konfigurasi';
		$param['url_matakuliah'] =$this->bantu->getRootAddress().'index.php/pengelolaan/mata_kuliah';
		$param['url_programstudi'] =$this->bantu->getRootAddress().'index.php/pengelolaan/program_studi';
		$param['url_dosen'] =$this->bantu->getRootAddress().'index.php/pengelolaan/dosen';
		$param['url_ruang'] =$this->bantu->getRootAddress().'index.php/pengelolaan/ruang';
		$param['url_waktu_dosen'] =$this->bantu->getRootAddress().'index.php/pengelolaan/waktu_dosen';
		$param['url_makul_prodi'] =$this->bantu->getRootAddress().'index.php/pengelolaan/makul_prodi';
		$param['url_ruang_prodi'] =$this->bantu->getRootAddress().'index.php/pengelolaan/ruang_prodi';
		$template_konten = 'pengelolaan/pilihan_menu_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);

	}

	function konfigurasi(){
		/**********processRequest**********/	
		
		// $param['judul_halaman'] = "Halaman home";

		$param['filter'] = null;
		$param['data'] = $this->pengelolaan_model->get_konfigurasi($param['filter']);

		$param['jumlah_data'] = $this->pengelolaan_model->get_count_konfigurasi($param['filter']);
		
		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
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
		$param['url_edit'] = $this->bantu->getRootAddress().'index.php/pengelolaan/edit_konfigurasi';
		$param['url_del'] = $this->bantu->getRootAddress().'index.php/pengelolaan/del_konfigurasi';

		$template_konten = 'pengelolaan/konfigurasi_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function edit_konfigurasi(){
		/**********processRequest**********/
		$filter['id'] = $_GET['id'];
		$param['filter'] = $filter;
		$param['data'] = $this->pengelolaan_model->get_konfigurasi($param['filter']);

		$param['url_pengelolaan'] =$this->bantu->getRootAddress().'index.php/pengelolaan/konfigurasi';
		$param['url_submit'] = $this->bantu->getRootAddress().'index.php/proses_pengelolaan/simpan_konfigurasi';
		$param['display'] = 'style="display:none"';
		
		
		$template_konten = 'pengelolaan/edit_konfigurasi_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function del_konfigurasi(){
		$filter['id'] = $_POST['id'];
		$param['filter'] = $filter;
		$param['data'] = $this->pengelolaan_model->get_konfigurasi($param['filter']);

		$param['url_submit'] = $this->bantu->getRootAddress().'index.php/proses_pengelolaan/del_konfigurasi';

        $template_konten = 'confirm_hapus_view';
        echo $this->load->view($template_konten, $param, true);
	}

	
	function mata_kuliah(){
		/**********processRequest**********/	
		
		$param['judul_halaman'] = "Halaman home";

		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');  
		$param['filter'] = $filter;
		$param['data'] = $this->pengelolaan_model->get_matakuliah($param['filter']);

		$param['jumlah_data'] = $this->pengelolaan_model->get_count_matakuliah($param['filter']);

		$url_this = $this->bantu->getRootAddress().'index.php/pengelolaan/mata_kuliah/';
		$param['paging'] = $this->bantu->getPaging($url_this, $param['jumlah_data']);

		$param['url_tambah'] = $this->bantu->getRootAddress().'index.php/pengelolaan/tambah_matakuliah';
		$param['url_edit'] = $this->bantu->getRootAddress().'index.php/pengelolaan/edit_matakuliah';
		$param['url_del'] = $this->bantu->getRootAddress().'index.php/pengelolaan/del_matakuliah';
		
		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
		}

		$template_konten = 'pengelolaan/makul_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function alter_matakuliah($id){

		$filter['id'] = $id;
		$param['filter'] = $filter;


		$param['data']['kode'] = null;
		$param['data']['nama'] = null;
		$param['data']['sks'] = null;
		$param['data']['jml_pert'] = null;
		$param['data']['format'] = null;
		$param['data']['smt'] = null;
		$param['data']['sifat'] = null;
		$param['data']['paket'] = null;
		$param['data']['is_univers'] = null;
		$param['data']['maks_kelas'] = null;
		$param['judul'] = "Tambah";
		if ($id != null) {			
			$param['data'] = $this->pengelolaan_model->get_matakuliah_by_id($param['filter']);
			$param['judul'] = "Edit";
		}

		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			// echo 'test<pre>'; print_r($param['display']); 
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
			$param['data'] = $flash_message['post'];
		}

		$arr_semester = array(
			array('id' => '', 'label' => '--pilih--'),
			array('id' => 'ganjil', 'label' => 'Ganjil'),
			array('id' => 'genap', 'label' => 'Genap'),
		);
		$param['cb_smt'] = $this->bantu->combobox($arr_semester, 'smt', $param['data']['smt']);

		$arr_semester = array(
			array('id' => 'W', 'label' => 'Wajib'),
			array('id' => 'P', 'label' => 'Pilihan')
		);
		$param['rd_sft'] = $this->bantu->radio($arr_semester, 'sifat', isset($param['data']['sifat'])?$param['data']['sifat']:null);
		
		$arr_paket = array(
			array('id' => '', 'label' => '--pilih--'),
			array('id' => '1', 'label' => 'Semester 1'),
			array('id' => '2', 'label' => 'Semester 2'),
			array('id' => '3', 'label' => 'Semester 3'),
			array('id' => '4', 'label' => 'Semester 4'),
			array('id' => '5', 'label' => 'Semester 5'),
			array('id' => '6', 'label' => 'Semester 6'),
			array('id' => '7', 'label' => 'Semester 7'),
			array('id' => '8', 'label' => 'Semester 8'),
		);
		$param['cb_paket'] = $this->bantu->combobox($arr_paket, 'paket', $param['data']['paket']);

		$arr_univr = array(
			array('id' => '0', 'label' => 'Tidak'),
			array('id' => '1', 'label' => 'Ya')
		);
		$param['rd_univers'] = $this->bantu->radio($arr_univr, 'is_univr', isset($param['data']['is_univers'])?$param['data']['is_univers']:null);
		
		$param['url_mata_kuliah'] =$this->bantu->getRootAddress().'index.php/pengelolaan/mata_kuliah';
		$param['url_submit'] = $this->bantu->getRootAddress().'index.php/proses_pengelolaan/simpan_matakuliah';

		return $param;
	}

	function tambah_matakuliah(){
		/**********processRequest**********/		
		$param = $this->alter_matakuliah(null);
		
		$template_konten = 'pengelolaan/edit_matakuliah_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function edit_matakuliah(){
		/**********processRequest**********/
		$param = $this->alter_matakuliah($_GET['id']);
		
		$template_konten = 'pengelolaan/edit_matakuliah_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function del_matakuliah(){
		$filter['id'] = $_POST['id'];
		$param['filter'] = $filter;
		$param['data'] = $this->pengelolaan_model->get_matakuliah_by_id($param['filter']);

		$param['url_submit'] = $this->bantu->getRootAddress().'index.php/proses_pengelolaan/del_matakuliah';

        $template_konten = 'confirm_hapus_view';
        echo $this->load->view($template_konten, $param, true);
	}

	
	function program_studi(){
		/**********processRequest**********/	
		
		$param['judul_halaman'] = "Halaman home";

		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');  
		$param['filter'] = $filter;
		$param['data'] = $this->prodi_model->get_program_studi($param['filter']);
		
		$param['jumlah_data'] = $this->prodi_model->get_count_program_studi($param['filter']);

		$url_this = $this->bantu->getRootAddress().'index.php/pengelolaan/program_studi/';
		$param['paging'] = $this->bantu->getPaging($url_this, $param['jumlah_data']);

		$param['url_tambah'] = $this->bantu->getRootAddress().'index.php/pengelolaan/tambah_program_studi';
		$param['url_edit'] = $this->bantu->getRootAddress().'index.php/pengelolaan/edit_program_studi';
		$param['url_del'] = $this->bantu->getRootAddress().'index.php/pengelolaan/del_program_studi';
		
		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
		}

		$template_konten = 'pengelolaan/prodi_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function alter_program_studi($id){

		$filter['id'] = $id;
		$param['filter'] = $filter;


		$param['data']['kode'] = null;
		$param['data']['nama'] = null;
		$param['data']['akronim'] = null;
		$param['judul'] = "Tambah";
		if ($id != null) {			
			$param['data'] = $this->prodi_model->get_program_studi_by_id($param['filter']);
			$param['judul'] = "Edit";
		}

		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			// echo 'test<pre>'; print_r($param['display']); 
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
			$param['data'] = $flash_message['post'];
		}
		
		$param['url_program_studi'] =$this->bantu->getRootAddress().'index.php/pengelolaan/program_studi';
		$param['url_submit'] = $this->bantu->getRootAddress().'index.php/proses_pengelolaan/simpan_program_studi';

		return $param;
	}

	function tambah_program_studi(){
		/**********processRequest**********/		
		$param = $this->alter_program_studi(null);
		
		$template_konten = 'pengelolaan/edit_program_studi_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function edit_program_studi(){
		/**********processRequest**********/
		$param = $this->alter_program_studi($_GET['id']);
		
		$template_konten = 'pengelolaan/edit_program_studi_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function del_program_studi(){
		$filter['id'] = $_POST['id'];
		$param['filter'] = $filter;
		$param['data'] = $this->prodi_model->get_program_studi_by_id($param['filter']);

		$param['url_submit'] = $this->bantu->getRootAddress().'index.php/proses_pengelolaan/del_program_studi';

        $template_konten = 'confirm_hapus_view';
        echo $this->load->view($template_konten, $param, true);
	}

	
	function dosen(){
		/**********processRequest**********/	
		
		$param['judul_halaman'] = "Halaman home";

		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');  
		$param['filter'] = $filter;
		$param['data'] = $this->dosen_model->get_dosen($param['filter']);

		$param['jumlah_data'] = $this->dosen_model->get_count_dosen($param['filter']);

		$url_this = $this->bantu->getRootAddress().'index.php/pengelolaan/dosen/';
		$param['paging'] = $this->bantu->getPaging($url_this, $param['jumlah_data']);

		$param['url_tambah'] = $this->bantu->getRootAddress().'index.php/pengelolaan/tambah_dosen';
		$param['url_edit'] = $this->bantu->getRootAddress().'index.php/pengelolaan/edit_dosen';
		$param['url_del'] = $this->bantu->getRootAddress().'index.php/pengelolaan/del_dosen';
		
		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
		}

		$template_konten = 'pengelolaan/dosen_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function alter_dosen($id){

		$filter['id'] = $id;
		$param['filter'] = $filter;


		$param['data']['nip'] = null;
		$param['data']['nama'] = null;
		$param['judul'] = "Tambah";
		if ($id != null) {			
			$param['data'] = $this->dosen_model->get_dosen_by_id($param['filter']);
			$param['judul'] = "Edit";
		}

		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			// echo 'test<pre>'; print_r($param['display']); 
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
			$param['data'] = $flash_message['post'];
		}

		$param['url_dosen'] =$this->bantu->getRootAddress().'index.php/pengelolaan/dosen';
		$param['url_submit'] = $this->bantu->getRootAddress().'index.php/proses_pengelolaan/simpan_dosen';

		return $param;
	}

	function tambah_dosen(){
		/**********processRequest**********/		
		$param = $this->alter_dosen(null);
		
		$template_konten = 'pengelolaan/edit_dosen_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function edit_dosen(){
		/**********processRequest**********/
		$param = $this->alter_dosen($_GET['id']);
		
		$template_konten = 'pengelolaan/edit_dosen_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function del_dosen(){
		$filter['id'] = $_POST['id'];
		$param['filter'] = $filter;
		$param['data'] = $this->dosen_model->get_dosen_by_id($param['filter']);

		$param['url_submit'] = $this->bantu->getRootAddress().'index.php/proses_pengelolaan/del_dosen';

        $template_konten = 'confirm_hapus_view';
        echo $this->load->view($template_konten, $param, true);
	}

	
	function ruang(){
		/**********processRequest**********/	
		
		$param['judul_halaman'] = "Halaman home";

		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');  
		$param['filter'] = $filter;
		$param['data'] = $this->ruang_model->get_ruang($param['filter']);

		$param['jumlah_data'] = $this->ruang_model->get_count_ruang($param['filter']);

		$url_this = $this->bantu->getRootAddress().'index.php/pengelolaan/ruang/';
		$param['paging'] = $this->bantu->getPaging($url_this, $param['jumlah_data']);

		$param['url_tambah'] = $this->bantu->getRootAddress().'index.php/pengelolaan/tambah_ruang';
		$param['url_edit'] = $this->bantu->getRootAddress().'index.php/pengelolaan/edit_ruang';
		$param['url_del'] = $this->bantu->getRootAddress().'index.php/pengelolaan/del_ruang';
		
		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
		}

		$template_konten = 'pengelolaan/ruang_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function alter_ruang($id){

		$filter['id'] = $id;
		$param['filter'] = $filter;


		$param['data']['kode'] = null;
		$param['data']['nama'] = null;
		$param['data']['kapasitas'] = null;
		$param['data']['is_cad'] = null;
		$param['judul'] = "Tambah";
		if ($id != null) {			
			$param['data'] = $this->ruang_model->get_ruang_by_id($param['filter']);
			$param['judul'] = "Edit";
		}

		$arr_univr = array(
			array('id' => '0', 'label' => 'Tidak'),
			array('id' => '1', 'label' => 'Ya')
		);
		$param['rd_iscad'] = $this->bantu->radio($arr_univr, 'is_cad', isset($param['data']['is_cad'])?$param['data']['is_cad']:null);
		

		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			// echo 'test<pre>'; print_r($param['display']); 
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
			$param['data'] = $flash_message['post'];
		}

		$param['url_ruang'] =$this->bantu->getRootAddress().'index.php/pengelolaan/ruang';
		$param['url_submit'] = $this->bantu->getRootAddress().'index.php/proses_pengelolaan/simpan_ruang';

		return $param;
	}

	function tambah_ruang(){
		/**********processRequest**********/		
		$param = $this->alter_ruang(null);
		
		$template_konten = 'pengelolaan/edit_ruang_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function edit_ruang(){
		/**********processRequest**********/
		$param = $this->alter_ruang($_GET['id']);
		
		$template_konten = 'pengelolaan/edit_ruang_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function del_ruang(){
		$filter['id'] = $_POST['id'];
		$param['filter'] = $filter;
		$param['data'] = $this->ruang_model->get_ruang_by_id($param['filter']);

		$param['url_submit'] = $this->bantu->getRootAddress().'index.php/proses_pengelolaan/del_ruang';

        $template_konten = 'confirm_hapus_view';
        echo $this->load->view($template_konten, $param, true);
	}


	
	function waktu_dosen(){
		/**********processRequest**********/	
		
		$param['judul_halaman'] = "Halaman home";

		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');  
		$param['filter'] = $filter;
		$param['data'] = $this->dosen_model->get_dosen_waktu($param['filter']);

		$param['jumlah_data'] = $this->dosen_model->get_count_dosen_waktu($param['filter']);

		$url_this = $this->bantu->getRootAddress().'index.php/pengelolaan/waktu_dosen/';
		$param['paging'] = $this->bantu->getPaging($url_this, $param['jumlah_data']);

		$param['url_tambah'] = $this->bantu->getRootAddress().'index.php/pengelolaan/tambah_waktu_dosen';
		$param['url_edit'] = $this->bantu->getRootAddress().'index.php/pengelolaan/edit_waktu_dosen';
		$param['url_del'] = $this->bantu->getRootAddress().'index.php/pengelolaan/del_waktu_dosen';
		
		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
		}

		$template_konten = 'pengelolaan/waktu_dosen_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function alter_waktu_dosen($id){

		$filter['id'] = $id;
		$param['filter'] = $filter;


		$param['data']['id_dosen'] = null;
		$param['data']['nama'] = null;
		$param['judul'] = "Tambah";

		$tabel_waktu = '';
		if ($id != null) {			
			$dosen = $this->dosen_model->get_dosen_by_id($param['filter']);
			$param['data']['id_dosen'] = $dosen['id'];
			$param['data']['nama'] = $dosen['nama'];

			$arr_waktu = $this->dosen_model->get_waktu_dosen_by_id($param['filter']);

			foreach ($arr_waktu as $key => $value) {
				$tabel_waktu .= "<tr class='".$value['id_waktu']."'>";
				$tabel_waktu .= "<td>".$value['id_waktu']."</td>";
				$tabel_waktu .= "<td>".$value['hari']."</td>";
				$tabel_waktu .= "<td>".$value['jam']."</td>";
				$tabel_waktu .= "<td>";
				$tabel_waktu .= "<a href='' class='btn btn-danger remove'><i class='icon-trash icon-white'></i></a>";
				$tabel_waktu .= "<input type='hidden' name='waktu[]' value='".$value['id_waktu']."' >";
				$tabel_waktu .= "</td>";
				$tabel_waktu .= "</tr>";
			}

			
			$param['judul'] = "Edit";
		}
		$param['tabel_waktu'] = $tabel_waktu;

		$arr_univr = array(
			array('id' => '0', 'label' => 'Tidak'),
			array('id' => '1', 'label' => 'Ya')
		);
		$param['rd_iscad'] = $this->bantu->radio($arr_univr, 'is_cad', isset($param['data']['is_cad'])?$param['data']['is_cad']:null);
		

		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			// echo 'test<pre>'; print_r($param['display']); 
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
			$param['data'] = $flash_message['post'];
		}

		$param['url_pilih_dosen'] = $this->bantu->getRootAddress().'index.php/pengelolaan/get_dosen';
		$param['url_pilih_waktu'] = $this->bantu->getRootAddress().'index.php/pengelolaan/get_waktu';
		$param['url_waktu_dosen'] =$this->bantu->getRootAddress().'index.php/pengelolaan/waktu_dosen';
		$param['url_submit'] = $this->bantu->getRootAddress().'index.php/proses_pengelolaan/simpan_waktu_dosen';

		return $param;
	}

	public function get_dosen(){
		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');  
		// $filter['idkls'] = $_POST['idkls'];
		$param['filter'] = $filter;

		$param['data_dosen'] = $this->dosen_model->get_dosen($param['filter']);
		// echo '<pre>'; print_r($param['data_dosen']); 
		// exit();

		$param['jumlah_data_dosen'] = $this->dosen_model->get_count_dosen($param['filter']);

		$url_this = $this->bantu->getRootAddress().'index.php/pengelolaan/get_dosen/';
		$param['paging_dosen'] = $this->bantu->getPaging($url_this, $param['jumlah_data_dosen']);

		// $param['id_kelas'] = $_POST['idkls'];
		$param['url_submit'] = $this->bantu->getRootAddress().'index.php/proses_pengelolaan/save_dosen_kelas';

		$template_konten = 'pengelolaan/ajax_dosen_view';
		echo $this->load->view($template_konten, $param, true);
	}

	public function get_waktu(){
		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');  
		// $filter['idkls'] = $_POST['idkls'];
		$param['filter'] = $filter;
		$param['data_waktu'] = $this->dosen_model->get_waktu($param['filter']);
		
		$param['jumlah_data_waktu'] = $this->dosen_model->get_count_waktu($param['filter']);

		$url_this = $this->bantu->getRootAddress().'index.php/pengelolaan/get_waktu/';
		$param['paging_waktu'] = $this->bantu->getPaging($url_this, $param['jumlah_data_waktu']);

		// $param['id_kelas'] = $_POST['idkls'];
		$param['url_submit'] = $this->bantu->getRootAddress().'index.php/proses_pengelolaan/save_dosen_kelas';

		$template_konten = 'pengelolaan/ajax_waktu_view';
		echo $this->load->view($template_konten, $param, true);
	}

	function tambah_waktu_dosen(){
		/**********processRequest**********/		
		$param = $this->alter_waktu_dosen(null);
		
		$template_konten = 'pengelolaan/edit_waktu_dosen_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function edit_waktu_dosen(){
		/**********processRequest**********/
		$param = $this->alter_waktu_dosen($_GET['id']);
		
		$template_konten = 'pengelolaan/edit_waktu_dosen_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function del_waktu_dosen(){
		$filter['id'] = $_POST['id'];
		$param['filter'] = $filter;
		$param['data'] = $this->dosen_model->get_dosen_by_id($param['filter']);


		$param['url_submit'] = $this->bantu->getRootAddress().'index.php/proses_pengelolaan/del_waktu_dosen';

        $template_konten = 'confirm_hapus_view';
        echo $this->load->view($template_konten, $param, true);
	}


	
	function makul_prodi(){
		/**********processRequest**********/	
		
		$param['judul_halaman'] = "Halaman home";

		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');  
		$param['filter'] = $filter;
		$param['data'] = $this->prodi_model->get_makul_prodi($param['filter']);

		$param['jumlah_data'] = $this->prodi_model->get_count_makul_prodi($param['filter']);

		$url_this = $this->bantu->getRootAddress().'index.php/pengelolaan/makul_prodi/';
		$param['paging'] = $this->bantu->getPaging($url_this, $param['jumlah_data']);

		$param['url_tambah'] = $this->bantu->getRootAddress().'index.php/pengelolaan/tambah_makul_prodi';
		$param['url_edit'] = $this->bantu->getRootAddress().'index.php/pengelolaan/edit_makul_prodi';
		$param['url_del'] = $this->bantu->getRootAddress().'index.php/pengelolaan/del_makul_prodi';
		
		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
		}

		$template_konten = 'pengelolaan/makul_prodi_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	public function get_program_studi(){
		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');

		if (isset($_POST['id_mk'])) {
			$filter['from'] = 'id_mk';
			$submit = 'save_prodi_mk';
			$filter['id_mk'] = $_POST['id_mk'];
			$id = $_POST['id_mk'];
		}
		if (isset($_POST['id_ru'])) {
			$filter['from'] = 'id_ru';
			$submit = 'save_prodi_ru';
			$filter['id_ru'] = $_POST['id_ru'];
			$id = $_POST['id_ru'];
		}
		
		$param['filter'] = $filter;

		$param['data'] = $this->prodi_model->get_prodi($param['filter']);
		$param['jumlah_data'] = $this->prodi_model->get_count_prodi($param['filter']);

		

		$url_this = $this->bantu->getRootAddress().'index.php/pengelolaan/get_program_studi/';
		$param['paging_data'] = $this->bantu->getPaging($url_this, $param['jumlah_data']);

		$param['id'] = $id;
		$param['url_submit'] = $this->bantu->getRootAddress().'index.php/proses_pengelolaan/'.$submit;

		$template_konten = 'pengelolaan/ajax_prodi_view';
		echo $this->load->view($template_konten, $param, true);
	}	

	function edit_makul_prodi(){
		/**********processRequest**********/	
		
		$param['judul_halaman'] = "Halaman home";
		$filter['id'] = $_GET['id'];
		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 

		$param['filter'] = $filter;

		$param['makul'] = $this->pengelolaan_model->get_matakuliah_by_id($param['filter']);
		$param['data'] = $this->prodi_model->get_makul_prodi_by_id($param['filter']);

		$url_this = $this->bantu->getRootAddress().'index.php/pengelolaan/program_studi/';

		$param['url_tambah'] = $this->bantu->getRootAddress().'index.php/pengelolaan/tambah_program_studi_on_makul_prodi?id='.$_GET['id'];
		$param['url_edit'] = $this->bantu->getRootAddress().'index.php/pengelolaan/edit_program_studi_on_makul_prodi?id='.$_GET['id'];
		$param['url_del'] = $this->bantu->getRootAddress().'index.php/pengelolaan/del_program_studi_on_makul_prodi?id='.$_GET['id'];
		$param['url_back'] = $this->bantu->getRootAddress().'index.php/pengelolaan/makul_prodi';
		
		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
		}

		$template_konten = 'pengelolaan/edit_makul_prodi_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function alter_program_studi_on_makul_prodi($idmk, $id){
		$arr = array('id' => $idmk);
		$param['makul'] = $this->pengelolaan_model->get_matakuliah_by_id($arr);

		$filter['id'] = $id;
		$param['filter'] = $filter;

		$param['idmk'] = $idmk;
		$param['data']['id'] = '';
		$param['data']['idmk'] = null;
		$param['data']['prodi_id'] = null;
		$param['data']['rel_id'] = null;
		$param['data']['porsi'] = null;
		$param['judul'] = "Tambah";
		if ($id != null) {			
			$param['data'] = $this->prodi_model->get_makul_prodi_by_idjoin($param['filter']);
			$param['judul'] = "Edit";
			$input_prodi = '<input type="hidden" name="prodi" value="'.$param['data']['prodi_id'].'" /> ';
			$param['cb_prodi'] = $input_prodi.'<span class="input-xlarge uneditable-input">'.$param['data']['nama'].'</span>';
			$param['cb_prodi_parent'] = '<span class="input-xlarge uneditable-input">'.$param['data']['program_studi_parent'].'</span>';
		}else{
			$arr_prodi = $this->prodi_model->get_program_studi_except_in_mkid($idmk);
			$param['cb_prodi'] = $this->bantu->combobox($arr_prodi, 'prodi', $param['data']['id']);

			$arr_prodi_parent_last = $this->prodi_model->get_makul_prodi_by_id_for_cb_parent($idmk);
			$param['cb_prodi_parent'] = $this->bantu->combobox($arr_prodi_parent_last, 'prodi_parent', $param['data']['rel_id']);

		}

		

		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			// echo 'test<pre>'; print_r($param['display']); 
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
			$param['data'] = $flash_message['post'];
		}

		$param['url_back'] =$this->bantu->getRootAddress().'index.php/pengelolaan/edit_makul_prodi?id='.$idmk;
		$param['url_submit'] = $this->bantu->getRootAddress().'index.php/proses_pengelolaan/simpan_program_studi_on_makul_prodi';

		return $param;
	}

	function tambah_program_studi_on_makul_prodi(){
		/**********processRequest**********/
		$idmk = $_GET['id'];	
		$param = $this->alter_program_studi_on_makul_prodi($idmk, null);
		
		$template_konten = 'pengelolaan/edit_program_studi_on_makul_prodi_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}


	function edit_program_studi_on_makul_prodi(){
		/**********processRequest**********/
		$idmk = $_GET['id'];	
		$idjoin = $_GET['idjoin'];	
		$param = $this->alter_program_studi_on_makul_prodi($idmk, $idjoin);
		
		$template_konten = 'pengelolaan/edit_program_studi_on_makul_prodi_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function del_program_studi_on_makul_prodi(){

		$filter['id'] = $_GET['idjoin'];
		$param['filter'] = $filter;
		$param['data'] = $this->prodi_model->get_makul_prodi_by_idjoin($param['filter']);

		$param['url_submit'] = $this->bantu->getRootAddress().'index.php/proses_pengelolaan/del_program_studi_on_makul_prodi?id='.$_GET['id'];

        $template_konten = 'confirm_hapus_view';
        echo $this->load->view($template_konten, $param, true);
	}


	
	function ruang_prodi(){
		/**********processRequest**********/	
		
		$param['judul_halaman'] = "Halaman home";

		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');  
		$param['filter'] = $filter;
		$param['data'] = $this->prodi_model->get_ruang_prodi($param['filter']);

		$param['jumlah_data'] = $this->prodi_model->get_count_ruang_prodi($param['filter']);

		$url_this = $this->bantu->getRootAddress().'index.php/pengelolaan/ruang_prodi/';
		$param['paging'] = $this->bantu->getPaging($url_this, $param['jumlah_data']);

		$param['url_tambah'] = $this->bantu->getRootAddress().'index.php/pengelolaan/tambah_ruang_prodi';
		$param['url_edit'] = $this->bantu->getRootAddress().'index.php/pengelolaan/edit_ruang_prodi';
		$param['url_del'] = $this->bantu->getRootAddress().'index.php/pengelolaan/del_ruang_prodi';
		$param['url_pilih_prodi'] = $this->bantu->getRootAddress().'index.php/pengelolaan/get_program_studi';
		
		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
		}

		$template_konten = 'pengelolaan/ruang_prodi_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function alter_ruang_prodi($id){

		$filter['id'] = $id;
		$param['filter'] = $filter;


		$param['data']['kode'] = null;
		$param['data']['nama'] = null;
		$param['data']['kapasitas'] = null;
		$param['data']['is_cad'] = null;
		$param['judul'] = "Tambah";
		if ($id != null) {			
			$param['data'] = $this->ruang_model->get_ruang_prodi_by_id($param['filter']);
			$param['judul'] = "Edit";
		}

		$arr_univr = array(
			array('id' => '0', 'label' => 'Tidak'),
			array('id' => '1', 'label' => 'Ya')
		);
		$param['rd_iscad'] = $this->bantu->radio($arr_univr, 'is_cad', isset($param['data']['is_cad'])?$param['data']['is_cad']:null);
		

		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			// echo 'test<pre>'; print_r($param['display']); 
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
			$param['data'] = $flash_message['post'];
		}

		$param['url_ruang_prodi'] =$this->bantu->getRootAddress().'index.php/pengelolaan/ruang_prodi';
		$param['url_submit'] = $this->bantu->getRootAddress().'index.php/proses_pengelolaan/simpan_ruang_prodi';

		return $param;
	}

	function tambah_ruang_prodi(){
		/**********processRequest**********/		
		$param = $this->alter_ruang_prodi(null);
		
		$template_konten = 'pengelolaan/edit_ruang_prodi_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function edit_ruang_prodi(){
		/**********processRequest**********/
		$param = $this->alter_ruang_prodi($_GET['id']);
		
		$template_konten = 'pengelolaan/edit_ruang_prodi_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function del_ruang_prodi(){
		$filter['id'] = $_POST['id'];
		$param['filter'] = $filter;
		$param['data'] = $this->prodi_model->get_ruang_prodi_by_id($param['filter']);

		$param['url_submit'] = $this->bantu->getRootAddress().'index.php/proses_pengelolaan/del_ruang_prodi';

        $template_konten = 'confirm_hapus_view';
        echo $this->load->view($template_konten, $param, true);
	}


}