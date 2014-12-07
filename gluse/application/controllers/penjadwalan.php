<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package		Gluse
 * @subpackage	penjadwalan
 * @author		Eko Budi Prasetyo
 * @version		0.0.0
 * @since		May 30, 2014
 */

class Penjadwalan extends CI_Controller {
	var $data;

	function __construct() {
        parent::__construct();
        $this->load->library('bantu');
        $this->data['parse_breadcrumbs'] = $this->bantu->getBreadcrumbs();
		$this->data['parse_uri_root'] = $this->bantu->getRootAddress();
		$this->load->model('penjadwalan_model');
    }

	/**
	 * @author		Eko Budi Prasetyo
	 * @version		0.0.0
	 * @since		3juni, 2014
	 * @usedfor		-
	 */
	public function index(){
		/**********processRequest**********/
		// $logproses_algen = $this->bantu->getDataLogproses('algen_penjadwalan');
		// echo '<pre>'; print_r($logproses_algen); echo '</pre>'; exit();
		
		// $param['judul_halaman'] = "Halaman home";
		$jml_kelas = $this->penjadwalan_model->cek_kelas_ada();
		$param['display_warning_nokelas'] = $jml_kelas==0?'':'style="display:none"';

		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');  
		$filter['semester_aktif'] = $this->bantu->getConfig('semester_aktif');
		$param['filter'] = $filter;

		$param['data'] = $this->penjadwalan_model->get_matakuliah($param['filter']);
		$param['jumlah_data'] = $this->penjadwalan_model->get_count_matakuliah($param['filter']);

		$url_this = $this->bantu->getRootAddress().'index.php/penjadwalan/index/';
		$param['paging'] = $this->bantu->getPaging($url_this, $param['jumlah_data']);

		$param['url_generate_class'] = $this->bantu->getRootAddress().'index.php/penjadwalan/generate_kelas';
		$param['url_list_class'] = $this->bantu->getRootAddress().'index.php/penjadwalan/kelas';
		
		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif_ins_kls');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
		}

		$template_konten = 'penjadwalan/makul_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);

	}

	function generate_kelas(){
		/**********processRequest**********/

		$param['url_matakuliah'] =$this->bantu->getRootAddress().'index.php/penjadwalan/';
		$param['url_submit'] = $this->bantu->getRootAddress().'index.php/proses_penjadwalan/generating_kelas';

		
		$template_konten = 'penjadwalan/generate_kelas_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	/**
	 * @author		Eko Budi Prasetyo
	 * @version		0.0.0
	 * @since		4juni, 2014
	 * @usedfor		-
	 */
	public function kelas(){
		/**********processRequest**********/	
		$dosen_lengkap = $this->penjadwalan_model->cek_dosen_kelas_lengkap();
		$param['display_warning_dosenkelas'] = !$dosen_lengkap?'':'style="display:none"';
		$param['display_buat_jadwal'] = !$dosen_lengkap?'style="display:none"':'';

		$jadwal = $this->penjadwalan_model->get_all_jadwal_kuliah();
		$param['display_list_jadwal'] = count($jadwal)==0?'style="display:none"':'';

		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');  
		$param['filter'] = $filter;

		$param['data'] = $this->penjadwalan_model->get_kelas($param['filter']);
		$param['jumlah_data'] = $this->penjadwalan_model->get_count_kelas($param['filter']);

		$url_this = $this->bantu->getRootAddress().'index.php/penjadwalan/kelas/';
		$param['paging'] = $this->bantu->getPaging($url_this, $param['jumlah_data']);

		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
		}

		$param['url_pilih_dosen'] = $this->bantu->getRootAddress().'index.php/penjadwalan/get_dosen';
		$param['url_proses_jadwal'] = $this->bantu->getRootAddress().'index.php/penjadwalan/proses_jadwal';
		$param['url_list_jadwal'] = $this->bantu->getRootAddress().'index.php/penjadwalan/jadwal_kuliah';

		$template_konten = 'penjadwalan/kelas_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);

	}

	public function get_dosen(){
		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');  
		$filter['idkls'] = $_POST['idkls'];
		$param['filter'] = $filter;

		$param['data_dosen'] = $this->penjadwalan_model->get_dosen($param['filter']);
		$param['jumlah_data_dosen'] = $this->penjadwalan_model->get_count_dosen($param['filter']);

		$url_this = $this->bantu->getRootAddress().'index.php/penjadwalan/get_dosen/';
		$param['paging_dosen'] = $this->bantu->getPaging($url_this, $param['jumlah_data_dosen']);

		$param['id_kelas'] = $_POST['idkls'];
		$param['url_submit'] = $this->bantu->getRootAddress().'index.php/proses_penjadwalan/save_dosen_kelas';

		$template_konten = 'penjadwalan/ajax_dosen_view';
		echo $this->load->view($template_konten, $param, true);
	}	

	function proses_jadwal(){
		/**********processRequest**********/

		$param['url_kelas'] =$this->bantu->getRootAddress().'index.php/penjadwalan/kelas';
		$param['url_submit'] = $this->bantu->getRootAddress().'index.php/proses_penjadwalan/lakukan_penjadwalan';
		$param['display'] = 'style="display:none"';
		
		$template_konten = 'penjadwalan/proses_jadwal_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}

	function jadwal_kuliah(){		
		// $kelas = $this->penjadwalan_model->get_all_kelas();
		$ruang = $this->penjadwalan_model->get_all_ruang();
		$waktu = $this->penjadwalan_model->get_all_waktu();
		$jadwal = $this->penjadwalan_model->get_all_jadwal_kuliah();

		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
		}

		$grup_hari = array('senin','selasa','rabu','kamis','jumat');
		$waktu_transform = array();
		foreach ($grup_hari as $key => $value) {
			$waktu_transform[$key]['hari'] = $value;
			$waktu_transform[$key]['data'] = array();
			$jam_ke = 1;
			foreach ($waktu as $i => $item) {

				if ($value == $item['waktu_hari'] AND $item['waktu_hari'] == 'jumat' AND $jam_ke == 5) {
					$jam_ke = $jam_ke + 2;
				}
				if ($value == $item['waktu_hari']) {
					$item['jam_ke'] = $jam_ke++;
					$waktu_transform[$key]['data'][] = $item;
				}
			}
		}


		$table_header = '<thead style="position:relative;"><tr >';
		$table_header .= '<th style="vertical-align:middle;width:100px;" rowspan="2">RUANG/WAKTU</th>';
		$table_header2 = '<tr>';
		$jml_kolom_data_header = count($waktu);
		foreach ($waktu_transform as $key => $value) {
			$table_header .= '<th colspan="10" >'.$value['hari'].'</th>';
			foreach ($value['data'] as $i => $item) {
				$table_header2 .= '<th style="width:300px;">'.$item['jam_ke'].'</th>';
			}
		}
		$table_header2 .= '</tr>';
		$table_header .= '</tr>';
		$table_header .= $table_header2;
		$table_header .= '</thead>';

		$table_body = '<tbody>';
		foreach ($ruang as $key => $value) {
			$table_body .= '<tr style="height:80px;">';
			$table_body .= '<td>'.$value['ru_nama'].'</td>';
			$period = 0;
			// echo $value['ru_id'].' : ';
			foreach ($waktu as $i => $time) {
				$label = '';
				$colspan = '';
				$style = '';
				if ($period == 0) {
					foreach ($jadwal as $j => $item) {
						if ($value['ru_id'] == $item['jk_ru_id'] AND $time['waktu_id'] == $item['jk_wkt_id']) {
							$label = $item['kls_nama'].'<br>'.$item['nama_makul'].'<br>'.$item['jk_label'];
							$colspan = 'colspan="'.$item['jk_period'].'"';
							$style = 'style="background-color: #F0F0C5;border-color: #E0E08D;"';
							$period = $item['jk_period']-1;
							// echo $i.', ';
							
						}
					}
					$table_body .= '<td '.$colspan.' '.$style.' >'.$label.'</td>';
				}else{
					$period--;
				}
				
			}
			
			$table_body .= '</tr>';
		}
		$table_body .= '</tbody>';

		$param['table_header'] = $table_header;
		$param['table_body'] = $table_body;
		$param['url_cetak_excel'] =$this->bantu->getRootAddress().'index.php/penjadwalan/cetak_xls';

		$template_konten = 'penjadwalan/jadwal_view';

		/**********parseTemplate**********/
		$this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		$this->load->view('template_view', $this->data);
	}


	function cetak_xls(){
		$this->load->library('phpexcel');
		$this->load->library('PHPExcel/iofactory');

		$data = $this->penjadwalan_model->get_jadwal_to_export();

		$objPHPExcel = new Phpexcel();
		$objPHPExcel->getProperties()->setTitle("title")
		                 ->setDescription("description");
		
		// Assign cell values
		$objPHPExcel->setActiveSheetIndex(0);
		$worksheet = $objPHPExcel->getActiveSheet();

		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')
                                          ->setSize(10);
		$styleThinBlackBorderOutline = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('argb' => 'FF000000'),
				),
			),
		);
		
		$idx = 4;
		$hari = array('senin','selasa','rabu','kamis','jumat');

		for ($i=0; $i < count($hari); $i++) { 
			$worksheet->setCellValueByColumnAndRow($i+9, $idx, $hari[$i] );
			$columnStr = PHPExcel_Cell::stringFromColumnIndex($i+9);
			$worksheet->getStyle($columnStr.$idx)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$worksheet->getStyle($columnStr.$idx)->applyFromArray($styleThinBlackBorderOutline);
			$worksheet->getColumnDimension($columnStr)->setWidth(15);

		}
		$strKolomMax = $worksheet->getHighestColumn();
		$worksheet->mergeCells('J3:'.$strKolomMax.'3');
		$worksheet->setCellValue('J3', 'Jadwal Tatap Muka');
		$worksheet->getStyle('J3:'.$strKolomMax.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$worksheet->getStyle('J3:'.$strKolomMax.'3')->applyFromArray($styleThinBlackBorderOutline);

		$worksheet->mergeCells('A3:A4');
		$worksheet->setCellValue('A3', 'No');
		$worksheet->getStyle('A3:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$worksheet->getStyle('A3:A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$worksheet->getStyle('A3:A4')->applyFromArray($styleThinBlackBorderOutline);
		$worksheet->getColumnDimension('A')->setWidth(5);

		$worksheet->mergeCells('B3:C3');
		$worksheet->setCellValue('B3', 'Mata Kuliah');
		$worksheet->setCellValue('B4', 'Kode');
		$worksheet->setCellValue('C4', 'Nama');
		$worksheet->getColumnDimension('B')->setWidth(10);
		$worksheet->getColumnDimension('C')->setWidth(35);
		$worksheet->getStyle('B3:C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$worksheet->getStyle('B3:C4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$worksheet->getStyle('B3:C3')->applyFromArray($styleThinBlackBorderOutline);
		$worksheet->getStyle('B4')->applyFromArray($styleThinBlackBorderOutline);
		$worksheet->getStyle('C4')->applyFromArray($styleThinBlackBorderOutline);

		$worksheet->mergeCells('D3:D4');
		$worksheet->setCellValue('D3', 'Paket smt');
		$worksheet->getStyle('D3:D4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$worksheet->getStyle('D3:D4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$worksheet->getStyle('D3:D4')->applyFromArray($styleThinBlackBorderOutline);
		$worksheet->getColumnDimension('D')->setWidth(10);

		$worksheet->mergeCells('E3:E4');
		$worksheet->setCellValue('E3', 'Nama kelas');
		$worksheet->getStyle('E3:E4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$worksheet->getStyle('E3:E4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$worksheet->getStyle('E3:E4')->applyFromArray($styleThinBlackBorderOutline);
		$worksheet->getColumnDimension('E')->setWidth(25);

		$worksheet->mergeCells('F3:F4');
		$worksheet->setCellValue('F3', 'SKS');
		$worksheet->getStyle('F3:F4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$worksheet->getStyle('F3:F4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$worksheet->getStyle('F3:F4')->applyFromArray($styleThinBlackBorderOutline);
		$worksheet->getColumnDimension('F')->setWidth(5);

		$worksheet->mergeCells('G3:G4');
		$worksheet->setCellValue('G3', 'Dosen');
		$worksheet->getStyle('G3:G4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$worksheet->getStyle('G3:G4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$worksheet->getStyle('G3:G4')->applyFromArray($styleThinBlackBorderOutline);
		$worksheet->getColumnDimension('G')->setWidth(35);

		$worksheet->mergeCells('H3:H4');
		$worksheet->setCellValue('H3', 'Ruang');
		$worksheet->getStyle('H3:H4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$worksheet->getStyle('H3:H4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$worksheet->getStyle('H3:H4')->applyFromArray($styleThinBlackBorderOutline);
		$worksheet->getColumnDimension('H')->setWidth(10);

		$worksheet->mergeCells('I3:I4');
		$worksheet->setCellValue('I3', 'Jumlah Peserta');
		$worksheet->getStyle('I3:I4')->getAlignment()->setWrapText(true); 
		$worksheet->getStyle('I3:I4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$worksheet->getStyle('I3:I4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$worksheet->getStyle('I3:I4')->applyFromArray($styleThinBlackBorderOutline);
		$worksheet->getColumnDimension('I')->setWidth(10);

		$idx = 5;		
		foreach ($data as $key => $value) {
			
			$jam_selesai = date("H:i:s", strtotime('+'.$value['durasi_menit'].' minutes', strtotime($value['jam_mulai'])));
			$label_jadwal_jam = $value['jam_mulai'].'-'.$jam_selesai;
			
			$worksheet->getStyle('G'.$idx)->getAlignment()->setWrapText(true); 
			$worksheet->getStyle('J'.$idx)->getAlignment()->setWrapText(true); 
			$worksheet->getStyle('K'.$idx)->getAlignment()->setWrapText(true); 
			$worksheet->getStyle('L'.$idx)->getAlignment()->setWrapText(true); 
			$worksheet->getStyle('M'.$idx)->getAlignment()->setWrapText(true); 
			$worksheet->getStyle('N'.$idx)->getAlignment()->setWrapText(true); 

			$worksheet->setCellValue('A'.$idx, ++$key);
			$worksheet->setCellValue('B'.$idx, $value['makul_kode']);
			$worksheet->setCellValue('C'.$idx, $value['makul_nama']);
			$worksheet->setCellValue('D'.$idx, $value['paket_sem']);
			$worksheet->setCellValue('E'.$idx, $value['kls_nama']);
			$worksheet->setCellValue('F'.$idx, $value['sks']);
			$worksheet->setCellValue('G'.$idx, $value['dosen_kelas']);
			$worksheet->setCellValue('H'.$idx, $value['ru_nama']);
			$worksheet->setCellValue('I'.$idx, $value['jml_peserta']);

			$worksheet->setCellValue('J'.$idx, ($value['senin']=='1'?$label_jadwal_jam:'') );
			$worksheet->setCellValue('K'.$idx, ($value['selasa']=='1'?$label_jadwal_jam:'') );
			$worksheet->setCellValue('L'.$idx, ($value['rabu']=='1'?$label_jadwal_jam:'') );
			$worksheet->setCellValue('M'.$idx, ($value['kamis']=='1'?$label_jadwal_jam:'') );
			$worksheet->setCellValue('N'.$idx, ($value['jumat']=='1'?$label_jadwal_jam:'') );
			
			$worksheet->getStyle('A'.$idx)->applyFromArray($styleThinBlackBorderOutline);
			$worksheet->getStyle('B'.$idx)->applyFromArray($styleThinBlackBorderOutline);
			$worksheet->getStyle('C'.$idx)->applyFromArray($styleThinBlackBorderOutline);
			$worksheet->getStyle('D'.$idx)->applyFromArray($styleThinBlackBorderOutline);
			$worksheet->getStyle('E'.$idx)->applyFromArray($styleThinBlackBorderOutline);
			$worksheet->getStyle('F'.$idx)->applyFromArray($styleThinBlackBorderOutline);
			$worksheet->getStyle('G'.$idx)->applyFromArray($styleThinBlackBorderOutline);
			$worksheet->getStyle('H'.$idx)->applyFromArray($styleThinBlackBorderOutline);
			$worksheet->getStyle('I'.$idx)->applyFromArray($styleThinBlackBorderOutline);
			$worksheet->getStyle('J'.$idx)->applyFromArray($styleThinBlackBorderOutline);
			$worksheet->getStyle('K'.$idx)->applyFromArray($styleThinBlackBorderOutline);
			$worksheet->getStyle('L'.$idx)->applyFromArray($styleThinBlackBorderOutline);
			$worksheet->getStyle('M'.$idx)->applyFromArray($styleThinBlackBorderOutline);
			$worksheet->getStyle('N'.$idx)->applyFromArray($styleThinBlackBorderOutline);

			$idx++;
		}

		$worksheet->getStyle('A5:N'.$idx)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$worksheet->getStyle('A5:N'.$idx)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		// HEADER EXCEL
		$worksheet->mergeCells('A1:'.$strKolomMax.'2');
		$worksheet->setCellValue('A1', 'JADWAL KULIAH');
		$worksheet->getStyle('A1:'.$strKolomMax.'4')->getFont()->setBold(true);
		$worksheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$worksheet->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$worksheet->getStyle('A1:'.$strKolomMax.'2')->applyFromArray($styleThinBlackBorderOutline);

		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="jadwal_kuliah_'.(date('Y')).'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = Iofactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}

}