<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package		Gluse
 * @subpackage	prediksi
 * @author		Eko Budi Prasetyo
 * @version		0.0.0
 * @since		May 30, 2014
 * @updated		June 12, 2014
 */

class Proses_pengelolaan extends CI_Controller {
	var $data;

	function __construct() {
        parent::__construct();
        $this->load->library('bantu');
    	$this->load->model('pengelolaan_model');
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('prodi_model');
		$this->load->model('dosen_model');
		$this->load->model('ruang_model');

    }

    /**
     * @since		june 12, 2014
     */
    function simpan_konfigurasi(){
		$sts = true;
		$this->db->trans_start();

		if (!empty($_POST['id'])) {			
			$param = array(
				'nilai' => $_POST['nilai'],
				'id' => $_POST['id']
			);
			$sts = $sts && $this->pengelolaan_model->update_konfigurasi($param);
			$this->db->trans_complete();
		}

		if ($this->db->trans_status() === true){
			$notif = array(
				'style' => 'success',
				'msg' => '<strong>Congratulation</strong> proses berhasil.'
			);
		}else{
		    $notif = array(
				'style' => 'error',
				'msg' => '<strong>Warning</strong> proses gagal.'
			);
		}
		$this->session->set_flashdata('notif', $notif);

		$url_kelas = $this->bantu->getRootAddress().'index.php/pengelolaan/konfigurasi';
		redirect($url_kelas	);
		exit(); 	
    }

    /**
     * @since		june 12, 2014
     */
    function simpan_matakuliah(){
		$sts = true;
		$this->db->trans_start();

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('kode', 'Kode mata kuliah', 'required');

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('nama', 'Nama mata kuliah', 'required');

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('sks', 'SKS', 'required');

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('smt', 'Semester', 'required');

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('paket', 'Paket semester', 'required');

		if ($this->form_validation->run() == FALSE){
			$notif = array(
				'style' => 'error',
				'msg' => '<strong>Warning</strong> '.validation_errors(),
				'post' => $_POST
			);
			if (empty($_POST['id'])) {	
				$url_ret = $this->bantu->getRootAddress().'index.php/pengelolaan/tambah_matakuliah';
			}else{
				$url_ret = $this->bantu->getRootAddress().'index.php/pengelolaan/edit_matakuliah?id='.$_POST['id'];
			}
		}else{
			if (!empty($_POST['id'])) {	
				$param = array(
					'kode' => $_POST['kode'],
					'nama' => $_POST['nama'],
					'sks' => $_POST['sks'],
					'smt' => $_POST['smt'],
					'sifat' => (isset($_POST['sifat'])?$_POST['sifat']:null),
					'paket' => $_POST['paket'],
					'jml_pert' => $_POST['jml_pert'],
					'is_univr' => (isset($_POST['is_univr'])?$_POST['is_univr']:null),
					'format' => $_POST['format'],
					'maks_kelas' => $_POST['maks_kelas'],
					'id' => $_POST['id']
				);
				$sts = $sts && $this->pengelolaan_model->update_matakuliah($param);
				$this->db->trans_complete();
			}else {
				$param = array(
					'kode' => $_POST['kode'],
					'nama' => $_POST['nama'],
					'sks' => $_POST['sks'],
					'smt' => $_POST['smt'],
					'sifat' => (isset($_POST['sifat'])?$_POST['sifat']:null),
					'paket' => $_POST['paket'],
					'jml_pert' => $_POST['jml_pert'],
					'is_univr' => (isset($_POST['is_univr'])?$_POST['is_univr']:null),
					'format' => $_POST['format'],
					'maks_kelas' => $_POST['maks_kelas']
				);
				$sts = $sts && $this->pengelolaan_model->add_matakuliah($param);
				$this->db->trans_complete();
			}

			if ($this->db->trans_status() === true){
				$notif = array(
					'style' => 'success',
					'msg' => '<strong>Congratulation</strong> Mata kuliah berhasil disimpan.',
					'post' => $_POST
				);
			}else{
			    $notif = array(
					'style' => 'error',
					'msg' => '<strong>Warning</strong> Mata kuliah gagal disimpan.',
					'post' => $_POST
				);
			}

			$url_ret = $this->bantu->getRootAddress().'index.php/pengelolaan/mata_kuliah';
		}
		$this->session->set_flashdata('notif', $notif);

		
		redirect($url_ret);
		exit(); 	
    }

    /**
     * @since		june 12, 2014
     */
    function del_konfigurasi(){
		$sts = true;
		$this->db->trans_start();

		if (!empty($_POST['id'])) {			
			$param = array(
				'id' => $_POST['id']
			);
			$sts = $sts && $this->pengelolaan_model->del_konfigurasi($param);
			$this->db->trans_complete();
		}

		if ($this->db->trans_status() === true){
			$notif = array(
				'style' => 'success',
				'msg' => '<strong>Congratulation</strong> Konfigurasi berhasil dihapus.'
			);
		}else{
		    $notif = array(
				'style' => 'error',
				'msg' => '<strong>Warning</strong> Konfigurasi gagal dihapus.'
			);
		}
		$this->session->set_flashdata('notif', $notif);

		$url_kelas = $this->bantu->getRootAddress().'index.php/pengelolaan/konfigurasi';
		redirect($url_kelas	);
		exit(); 	
    }

    /**
     * @since		june 12, 2014
     */
    function del_matakuliah(){
		$sts = true;
		$this->db->trans_start();

		if (!empty($_POST['id'])) {			
			$param = array(
				'id' => $_POST['id']
			);
			$sts = $sts && $this->pengelolaan_model->del_matakuliah($param);
			$this->db->trans_complete();
		}

		if ($this->db->trans_status() === true){
			$notif = array(
				'style' => 'success',
				'msg' => '<strong>Congratulation</strong> Mata kuliah berhasil dihapus.'
			);
		}else{
		    $notif = array(
				'style' => 'error',
				'msg' => '<strong>Warning</strong> Mata kuliah gagal dihapus.'
			);
		}
		$this->session->set_flashdata('notif', $notif);

		$url_kelas = $this->bantu->getRootAddress().'index.php/pengelolaan/mata_kuliah';
		redirect($url_kelas	);
		exit(); 	
    }

    /**
     * @since june 12, 2014
     */
    function simpan_program_studi(){
		$sts = true;
		$this->db->trans_start();

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('kode', 'Kode program studi', 'required');

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('nama', 'Nama program studi', 'required');

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('akronim', 'Akronim', 'required');

		if ($this->form_validation->run() == FALSE){
			$notif = array(
				'style' => 'error',
				'msg' => '<strong>Warning</strong> '.validation_errors(),
				'post' => $_POST
			);
			if (empty($_POST['id'])) {	
				$url_ret = $this->bantu->getRootAddress().'index.php/pengelolaan/tambah_program_studi';
			}else{
				$url_ret = $this->bantu->getRootAddress().'index.php/pengelolaan/edit_program_studi?id='.$_POST['id'];
			}
		}else{
			if (!empty($_POST['id'])) {	
				$param = array(
					'kode' => $_POST['kode'],
					'nama' => $_POST['nama'],
					'akronim' => $_POST['akronim'],
					'id' => $_POST['id']
				);
				$sts = $sts && $this->prodi_model->update_program_studi($param);
				$this->db->trans_complete();
			}else {
				$param = array(
					'kode' => $_POST['kode'],
					'nama' => $_POST['nama'],
					'akronim' => $_POST['akronim']
				);
				$sts = $sts && $this->prodi_model->add_program_studi($param);
				$this->db->trans_complete();
			}

			if ($this->db->trans_status() === true){
				$notif = array(
					'style' => 'success',
					'msg' => '<strong>Congratulation</strong> Program studi berhasil disimpan.',
					'post' => $_POST
				);
			}else{
			    $notif = array(
					'style' => 'error',
					'msg' => '<strong>Warning</strong> Program studi gagal disimpan.',
					'post' => $_POST
				);
			}

			$url_ret = $this->bantu->getRootAddress().'index.php/pengelolaan/program_studi';
		}
		$this->session->set_flashdata('notif', $notif);

		
		redirect($url_ret);
		exit(); 	 	
    }
    
    /**
     * @since		june 12, 2014
     */
    function del_program_studi(){
		$sts = true;
		$this->db->trans_start();

		if (!empty($_POST['id'])) {			
			$param = array(
				'id' => $_POST['id']
			);
			$sts = $sts && $this->prodi_model->del_program_studi($param);
			$this->db->trans_complete();
		}

		if ($this->db->trans_status() === true){
			$notif = array(
				'style' => 'success',
				'msg' => '<strong>Congratulation</strong> Program studi berhasil dihapus.'
			);
		}else{
		    $notif = array(
				'style' => 'error',
				'msg' => '<strong>Warning</strong> Program studi gagal dihapus.'
			);
		}
		$this->session->set_flashdata('notif', $notif);

		$url_kelas = $this->bantu->getRootAddress().'index.php/pengelolaan/program_studi';
		redirect($url_kelas	);
		exit(); 	
    }

    /**
     * @since june 12, 2014
     */
    function simpan_dosen(){
		$sts = true;
		$this->db->trans_start();

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('nip', 'NIP dosen', 'required');

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('nama', 'Nama dosen', 'required');

		if ($this->form_validation->run() == FALSE){
			$notif = array(
				'style' => 'error',
				'msg' => '<strong>Warning</strong> '.validation_errors(),
				'post' => $_POST
			);
			if (empty($_POST['id'])) {	
				$url_ret = $this->bantu->getRootAddress().'index.php/pengelolaan/tambah_dosen';
			}else{
				$url_ret = $this->bantu->getRootAddress().'index.php/pengelolaan/edit_dosen?id='.$_POST['id'];
			}
		}else{
			if (!empty($_POST['id'])) {	
				$param = array(
					'nip' => $_POST['nip'],
					'nama' => $_POST['nama'],
					'id' => $_POST['id']
				);
				$sts = $sts && $this->dosen_model->update_dosen($param);
				$this->db->trans_complete();
			}else {
				$param = array(
					'nip' => $_POST['nip'],
					'nama' => $_POST['nama']
				);
				$sts = $sts && $this->dosen_model->add_dosen($param);
				$this->db->trans_complete();
			}

			if ($this->db->trans_status() === true){
				$notif = array(
					'style' => 'success',
					'msg' => '<strong>Congratulation</strong> Dosen berhasil disimpan.',
					'post' => $_POST
				);
			}else{
			    $notif = array(
					'style' => 'error',
					'msg' => '<strong>Warning</strong> Dosen gagal disimpan.',
					'post' => $_POST
				);
			}

			$url_ret = $this->bantu->getRootAddress().'index.php/pengelolaan/dosen';
		}
		$this->session->set_flashdata('notif', $notif);

		
		redirect($url_ret);
		exit(); 	 	
    }
    
    /**
     * @since		june 12, 2014
     */
    function del_dosen(){
		$sts = true;
		$this->db->trans_start();

		if (!empty($_POST['id'])) {			
			$param = array(
				'id' => $_POST['id']
			);
			$sts = $sts && $this->dosen_model->del_dosen($param);
			$this->db->trans_complete();
		}

		if ($this->db->trans_status() === true){
			$notif = array(
				'style' => 'success',
				'msg' => '<strong>Congratulation</strong> Dosen berhasil dihapus.'
			);
		}else{
		    $notif = array(
				'style' => 'error',
				'msg' => '<strong>Warning</strong> Dosen gagal dihapus.'
			);
		}
		$this->session->set_flashdata('notif', $notif);

		$url_kelas = $this->bantu->getRootAddress().'index.php/pengelolaan/dosen';
		redirect($url_kelas	);
		exit(); 	
    }

    /**
     * @since june 12, 2014
     */
    function simpan_ruang(){
		$sts = true;
		$this->db->trans_start();

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('kode', 'Kode ruang', 'required');

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('nama', 'Nama ruang', 'required');

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('kapasitas', 'Kapasitas', 'required');

		if ($this->form_validation->run() == FALSE){
			$notif = array(
				'style' => 'error',
				'msg' => '<strong>Warning</strong> '.validation_errors(),
				'post' => $_POST
			);
			if (empty($_POST['id'])) {	
				$url_ret = $this->bantu->getRootAddress().'index.php/pengelolaan/tambah_ruang';
			}else{
				$url_ret = $this->bantu->getRootAddress().'index.php/pengelolaan/edit_ruang?id='.$_POST['id'];
			}
		}else{
			if (!empty($_POST['id'])) {	
				$param = array(
					'kode' => $_POST['kode'],
					'nama' => $_POST['nama'],
					'kapasitas' => $_POST['kapasitas'],
					'is_cad' => (isset($_POST['is_cad'])?$_POST['is_cad']:null),
					'id' => $_POST['id']
				);
				$sts = $sts && $this->ruang_model->update_ruang($param);
				$this->db->trans_complete();
			}else {
				$param = array(
					'kode' => $_POST['kode'],
					'nama' => $_POST['nama'],
					'kapasitas' => $_POST['kapasitas'],
					'is_cad' => (isset($_POST['is_cad'])?$_POST['is_cad']:null)
				);
				$sts = $sts && $this->ruang_model->add_ruang($param);
				$this->db->trans_complete();
			}

			if ($this->db->trans_status() === true){
				$notif = array(
					'style' => 'success',
					'msg' => '<strong>Congratulation</strong> Ruang berhasil disimpan.',
					'post' => $_POST
				);
			}else{
			    $notif = array(
					'style' => 'error',
					'msg' => '<strong>Warning</strong> Ruang gagal disimpan.',
					'post' => $_POST
				);
			}

			$url_ret = $this->bantu->getRootAddress().'index.php/pengelolaan/ruang';
		}
		$this->session->set_flashdata('notif', $notif);

		
		redirect($url_ret);
		exit(); 	 	
    }

    /**
     * @since june 12, 2014
     */
    function simpan_waktu_dosen(){
		$sts = true;
		$this->db->trans_start();

		$_POST['waktu'] = array_unique($_POST['waktu']);

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('id_dosen', 'Dosen', 'required');

		$this->form_validation->set_message('required', '%s harus dipilih');
		$this->form_validation->set_rules('waktu', 'Waktu', 'required');

		if ($this->form_validation->run() == FALSE){
			$notif = array(
				'style' => 'error',
				'msg' => '<strong>Warning</strong> '.validation_errors(),
				'post' => $_POST
			);
			if (empty($_POST['id'])) {	
				$url_ret = $this->bantu->getRootAddress().'index.php/pengelolaan/tambah_waktu_dosen';
			}else{
				$url_ret = $this->bantu->getRootAddress().'index.php/pengelolaan/edit_waktu_dosen?id='.$_POST['id'];
			}
		}else{

			$sts = $sts && $this->dosen_model->delete_dosen_waktu_by_id($_POST['id_dosen']);
			foreach ($_POST['waktu'] as $key => $value) {
				$param = array(
					'id_dosen' => $_POST['id_dosen'],
					'id_waktu' => $value
				);
				$sts = $sts && $this->dosen_model->add_dosen_waktu($param);
			}				
			$this->db->trans_complete();

			if ($this->db->trans_status() === true){
				$notif = array(
					'style' => 'success',
					'msg' => '<strong>Congratulation</strong> Waktu dosen berhasil disimpan.',
					'post' => $_POST
				);
			}else{
			    $notif = array(
					'style' => 'error',
					'msg' => '<strong>Warning</strong> Waktu dosen gagal disimpan.',
					'post' => $_POST
				);
			}

			$url_ret = $this->bantu->getRootAddress().'index.php/pengelolaan/waktu_dosen';
		}
		$this->session->set_flashdata('notif', $notif);

		
		redirect($url_ret);
		exit(); 	 	
    }
    
    /**
     * @since		june 12, 2014
     */
    function del_ruang(){
		$sts = true;
		$this->db->trans_start();

		if (!empty($_POST['id'])) {			
			$param = array(
				'id' => $_POST['id']
			);
			$sts = $sts && $this->ruang_model->del_ruang($param);
			$this->db->trans_complete();
		}

		if ($this->db->trans_status() === true){
			$notif = array(
				'style' => 'success',
				'msg' => '<strong>Congratulation</strong> Ruang berhasil dihapus.'
			);
		}else{
		    $notif = array(
				'style' => 'error',
				'msg' => '<strong>Warning</strong> Ruang gagal dihapus.'
			);
		}
		$this->session->set_flashdata('notif', $notif);

		$url_kelas = $this->bantu->getRootAddress().'index.php/pengelolaan/ruang';
		redirect($url_kelas	);
		exit(); 	
    }    

	public function save_prodi_ru(){
		$sts = true;
		$this->db->trans_start();
		$sts = $sts && $this->prodi_model->del_prodiru_by_idru($_POST['id']);
		if (!empty($_POST['prodi'])) {			
			foreach ($_POST['prodi'] as $a => $item) {
				$param = array(
					'id_prodi' => $item,
					'id' => $_POST['id']
				);
				$sts = $sts && $this->prodi_model->ins_prodiru($param);
			}
			$this->db->trans_complete();
		}

		if ($this->db->trans_status() === true){
			$notif = array(
				'style' => 'success',
				'msg' => '<strong>Congratulation</strong> Prodi ruang berhasil disimpan.'
			);
		}else{
		    $notif = array(
				'style' => 'error',
				'msg' => '<strong>Warning</strong> Prodi ruang gagal disimpan.'
			);
		}
		$this->session->set_flashdata('notif', $notif);

		$url_kelas = $this->bantu->getRootAddress().'index.php/pengelolaan/ruang_prodi';
		redirect($url_kelas	);
		exit();
	}

	public function simpan_program_studi_on_makul_prodi(){
		$sts = true;
		$this->db->trans_start();

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('prodi', 'Program studi', 'required');

		if ($this->form_validation->run() == FALSE){
			$notif = array(
				'style' => 'error',
				'msg' => '<strong>Warning</strong> '.validation_errors(),
				'post' => $_POST
			);
			if(empty($_POST['id'])) {
				$url_ret = $this->bantu->getRootAddress().'index.php/pengelolaan/tambah_program_studi_on_makul_prodi?id='.$_POST['idmk'];
			}else{
				$url_ret = $this->bantu->getRootAddress().'index.php/pengelolaan/edit_program_studi_on_makul_prodi?id='.$_POST['idmk'].'&idjoin='.$_POST['id'];
			}
		}else{
			if (!empty($_POST['id'])) {	
				$param = array(
					'porsi' => $_POST['porsi'],
					'id' => $_POST['id']
				);
				$sts = $sts && $this->prodi_model->update_program_studi_on_makul_prodi($param);
				$this->db->trans_complete();
			}else {
				$param = array(
					'prodi' => $_POST['prodi'],
					'idmk' => $_POST['idmk'],
					'porsi' => $_POST['porsi'],
					'prodi_parent' => (isset($_POST['prodi_parent'])?$_POST['prodi_parent']:null)
				);
				$sts = $sts && $this->prodi_model->add_program_studi_on_makul_prodi($param);
				$this->db->trans_complete();
			}

			if ($this->db->trans_status() === true){
				$notif = array(
					'style' => 'success',
					'msg' => '<strong>Congratulation</strong> Data berhasil disimpan.',
					'post' => $_POST
				);
			}else{
			    $notif = array(
					'style' => 'error',
					'msg' => '<strong>Warning</strong> Data gagal disimpan.',
					'post' => $_POST
				);
			}

			$url_ret = $this->bantu->getRootAddress().'index.php/pengelolaan/edit_makul_prodi?id='.$_POST['idmk'];
		}
		$this->session->set_flashdata('notif', $notif);

		redirect($url_ret);
		exit();
	}
    
    /**
     * @since		june 12, 2014
     */
    function del_program_studi_on_makul_prodi(){
		$sts = true;
		$this->db->trans_start();
		
		if (!empty($_POST['id'])) {			
			$param = array(
				'id' => $_POST['id']
			);
			$sts = $sts && $this->prodi_model->del_program_studi_on_makul_prodi($param);
			$this->db->trans_complete();
		}

		if ($this->db->trans_status() === true){
			$notif = array(
				'style' => 'success',
				'msg' => '<strong>Congratulation</strong> Data berhasil dihapus.'
			);
		}else{
		    $notif = array(
				'style' => 'error',
				'msg' => '<strong>Warning</strong> Data gagal dihapus.'
			);
		}
		$this->session->set_flashdata('notif', $notif);

		$url_kelas = $this->bantu->getRootAddress().'index.php/pengelolaan/edit_makul_prodi?id='.$_GET['id'];
		redirect($url_kelas	);
		exit(); 	
    }    
    
    /**
     * @since		june 12, 2014
     */
    function del_waktu_dosen(){
		$sts = true;
		$this->db->trans_start();
		
		if (!empty($_POST['id'])) {	
			$sts = $sts && $this->dosen_model->delete_dosen_waktu_by_id($_POST['id']);
			$this->db->trans_complete();
		}

		if ($this->db->trans_status() === true){
			$notif = array(
				'style' => 'success',
				'msg' => '<strong>Congratulation</strong> Data berhasil dihapus.'
			);
		}else{
		    $notif = array(
				'style' => 'error',
				'msg' => '<strong>Warning</strong> Data gagal dihapus.'
			);
		}
		$this->session->set_flashdata('notif', $notif);

		$url_kelas = $this->bantu->getRootAddress().'index.php/pengelolaan/waktu_dosen';
		redirect($url_kelas	);
		exit(); 	
    }    

	
}