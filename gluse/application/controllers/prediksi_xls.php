<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package		Gluse
 * @subpackage	prediksi
 * @author		Eko Budi Prasetyo
 * @version		0.0.0
 * @since		May 30, 2014
 */

class Prediksi_xls extends CI_Controller {
	var $data;

	function __construct() {
        parent::__construct();
        $this->load->library('bantu');
        $this->data['parse_breadcrumbs'] = $this->bantu->getBreadcrumbs();
		$this->data['parse_uri_root'] = $this->bantu->getRootAddress();
    }

	function format_function(){
		/**********processRequest**********/
		// $this->load->model('Prediksi_model');

		// $filter['start'] = 0;
		// $filter['display'] = 10; 
		// $param['filter'] = $filter;
		
		// $template_konten = 'prediksi/makul_view';

		/**********parseTemplate**********/
		// $this->data['parse_template_konten'] = $this->load->view($template_konten, $param, true);		
		// $this->load->view('template_view', $this->data);
	}

	function download_format_rekap_matakuliah(){
		$this->load->library('phpexcel');
		$this->load->library('PHPExcel/iofactory');
		$this->load->model('Prediksi_model');
		$this->load->library('bantu');

		$semester_aktif = $this->bantu->getConfig('semester_aktif');
		$matakuliah = $this->Prediksi_model->get_all_matakuliah($semester_aktif);
		$jmlTahun = $this->bantu->getConfig('periode_tahun_prediksi');

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
		
		// $column = 'C';
		// $columnIndex = PHPExcel_Cell::columnIndexFromString($column);
		// $columnStr = PHPExcel_Cell::stringFromColumnIndex(3);
		// echo '<pre>'; print_r($columnStr); echo '</pre>';
		// exit();
		
		$idx = 4;

		for ($i=0; $i < $jmlTahun; $i++) { 
			$worksheet->setCellValueByColumnAndRow($i+3, $idx, (date('Y')-$jmlTahun)+$i );
			$columnStr = PHPExcel_Cell::stringFromColumnIndex($i+3);
			$worksheet->getStyle($columnStr.$idx)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$worksheet->getStyle($columnStr.$idx)->applyFromArray($styleThinBlackBorderOutline);
		}
		$strKolomMax = $worksheet->getHighestColumn();
		$worksheet->mergeCells('D3:'.$strKolomMax.'3');
		$worksheet->setCellValue('D3', 'Jumlah Peminat');
		$worksheet->getStyle('D3:'.$strKolomMax.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$worksheet->getStyle('D3:'.$strKolomMax.'3')->applyFromArray($styleThinBlackBorderOutline);

		$worksheet->mergeCells('A3:A4');
		$worksheet->setCellValue('A3', 'Kode Mata Kuliah');
		$worksheet->getStyle('A3:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$worksheet->getStyle('A3:A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$worksheet->getStyle('A3:A4')->applyFromArray($styleThinBlackBorderOutline);
		$worksheet->getColumnDimension('A')->setWidth(25);

		$worksheet->mergeCells('B3:B4');
		$worksheet->setCellValue('B3', 'Nama Mata Kuliah');
		$worksheet->getStyle('B3:B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$worksheet->getStyle('B3:B4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$worksheet->getStyle('B3:B4')->applyFromArray($styleThinBlackBorderOutline);
		$worksheet->getColumnDimension('B')->setWidth(35);

		$worksheet->mergeCells('C3:C4');
		$worksheet->setCellValue('C3', 'Semester');
		$worksheet->getStyle('C3:C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$worksheet->getStyle('C3:C4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$worksheet->getStyle('C3:C4')->applyFromArray($styleThinBlackBorderOutline);
		$worksheet->getColumnDimension('C')->setWidth(15);

		$idx = 5;		
		foreach ($matakuliah as $key => $value) {
			$worksheet->setCellValue('A'.$idx, $value['kode']);
			$worksheet->setCellValue('B'.$idx, $value['nama']);
			$worksheet->setCellValue('C'.$idx, $value['smt']);
			$worksheet->getStyle('A'.$idx)->applyFromArray($styleThinBlackBorderOutline);
			$worksheet->getStyle('B'.$idx)->applyFromArray($styleThinBlackBorderOutline);
			$worksheet->getStyle('C'.$idx)->applyFromArray($styleThinBlackBorderOutline);
			for ($j=0; $j < $jmlTahun; $j++) { 
				$columnStrj = PHPExcel_Cell::stringFromColumnIndex($j+3);
				$worksheet->getStyle($columnStrj.$idx)->applyFromArray($styleThinBlackBorderOutline);
			}
			$idx++;
		}

		// HEADER EXCEL
		$worksheet->mergeCells('A1:'.$strKolomMax.'2');
		$worksheet->setCellValue('A1', 'Rekap Mata Kuliah');
		$worksheet->getStyle('A1:'.$strKolomMax.'4')->getFont()->setBold(true);
		$worksheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$worksheet->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$worksheet->getStyle('A1:'.$strKolomMax.'2')->applyFromArray($styleThinBlackBorderOutline);
		
		// $worksheet->getColumnDimension("A")->setWidth(40);
		// $worksheet->getColumnDimension("A")->setAutoSize(true);
		// $worksheet->getColumnDimension("B")->setAutoSize(true);
		// $worksheet->getColumnDimension("C")->setAutoSize(true);
			

		/*$worksheet->getStyle('A3:'.$strKolomMax.'3')->applyFromArray(
				array(
					'font' => array('bold' => true),
					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
					'borders' => array(
						'top'     => array('style' => PHPExcel_Style_Border::BORDER_THIN),
		 				'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
					)
				)
		);*/


		// $worksheet->freezePane('A4');

		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="form_rekap_mata_kuliah_'.(date('Y')).'.xls"');
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

?>
