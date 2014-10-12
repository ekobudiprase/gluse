<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package		Gluse
 * @subpackage	penjadwalan
 * @author		Eko Budi Prasetyo
 * @version		0.0.0
 * @since		Oct 11, 2014
 */

class Log_proses extends CI_Controller {
	var $data;

	function __construct() {
        parent::__construct();
        $this->load->library('bantu');
        $this->data['parse_breadcrumbs'] = $this->bantu->getBreadcrumbs();
		$this->data['parse_uri_root'] = $this->bantu->getRootAddress();
		// $this->load->model('logproses_model');
    }

	public function index(){
		/**********processRequest**********/	
		$param['url_jst_prediksi'] =$this->bantu->getRootAddress().'index.php/log_proses/jst_prediksi';
		$param['url_klasifikasi'] =$this->bantu->getRootAddress().'index.php/log_proses/klasifikasi';
		$param['url_algen_penjadwalan'] =$this->bantu->getRootAddress().'index.php/log_proses/algen_penjadwalan';
		
		$template_konten = 'log_proses/pilihan_menu_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);

	}

	public function jst_prediksi(){
		$this->gotoMenu('jst_prediksi');
	}

	public function algen_penjadwalan(){
		$this->gotoMenu('algen_penjadwalan');
	}

	public function gotoMenu($kode){
		/**********processRequest**********/
		$result = $this->bantu->getDataLogproses($kode);
		$param['data'] = $result;

		$template_konten = 'log_proses/data_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);

	}
}

?>