<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package		Gluse
 * @subpackage	lib/bantu
 * @author		Eko Budi Prasetyo
 * @version		0.0.0
 * @since		May 30, 2014
 */

class Bantu {
    var $CI;

    /**
    * @author   Eko Budi Prasetyo
    * @version    0.0.0
    * @since    May 31, 2014
    * @usedfor    -
    */
    public function __construct(){
        $this->CI =& get_instance(); // for accessing the model of CI later
    }

    /**
    * @author		Eko Budi Prasetyo
    * @version		0.0.0
    * @since		May 30, 2014
    * @usedfor		-
    */
    public function getRootAddress(){
    	$basedir = str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
	    $baseaddress = "http" . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "s" : "") . "://" . $_SERVER['HTTP_HOST'];
	    $uri_root = $baseaddress.$basedir;

	    return $uri_root;
    }

    /**
    * @author		Eko Budi Prasetyo
    * @version		0.0.0
    * @since		May 30, 2014
    * @usedfor		-
    */
    function getBreadcrumbs(){
        // $crumbs = explode("/",$_SERVER["REQUEST_URI"]);
        $crumbs = $this->CI->uri->segment_array();
        $menu[] = 'gluse';
        foreach ($crumbs as $key => $value) {
          // if (($value != '') and strtolower($value) != 'index.php' AND substr($value, 0, 1)!='?') {
          //   $menu[] = $value;
          // }
            $menu[] = $value;
        }

        $str = '';
        $active_menu = end($menu);        
        $root = $this->getRootAddress().'index.php/';

        foreach ($menu as $a => $item) {
          $url = $item=='gluse'?'':strtolower($item);
          $str .= '<li>';
          if ($item != $active_menu) {
            $str .= '<a href="'.$root.$url.'">'.(ucfirst($item)).'</a>';
            $str .= '<span class="divider">/</span>';
          }else{
            $str .= (ucfirst($item));
          }
          
          
          $str .= '</li>';
        }
        $str = str_replace('Gluse', 'Home', $str);
        // echo '<pre>'; print_r($str); 

        return $str;
	 }

    /**
    * @author    Eko Budi Prasetyo
    * @version   0.0.0
    * @since     May 31, 2014
    * @usedfor   -
    */
    function getConfig($configname){
        $this->CI->load->model('Config_model');
        $val = $this->CI->Config_model->get_configval_by_name($configname);

        return $val;
    }

    /**
    * @author    Eko Budi Prasetyo
    * @version   0.0.0
    * @since     May 31, 2014
    * @usedfor   -
    */
    function getPaging($url, $total_data){
        $this->CI->load->model('Config_model');
        $this->CI->load->library('pagination');

        $config['base_url'] = $url;
        $config['total_rows'] = $total_data;
        $config['per_page'] = $this->getConfig('item_per_page');

        // $config['full_tag_open'] = '<div class="pagination"><ul>';
        // $config['full_tag_close'] = '</ul></div>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li class="pagin" >';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li class="pagin" >';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li class="pagin" >';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li class="pagin" >';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active pagin"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="pagin" >';
        $config['num_tag_close'] = '</li>';

        $this->CI->pagination->initialize($config);
        return $this->CI->pagination->create_links();
    }

    function combobox($arr_data, $name, $nilai=''){
        if (!empty($arr_data)) {
            $str = '<select id="selectError1" class="span2" name="'.$name.'">';          
            foreach ($arr_data as $key => $value) {
                $selected = '';
                if (strtolower($value['id']) == strtolower($nilai)) {
                    $selected = 'selected';
                }
                $str .= '<option value="'.$value['id'].'" '.$selected.'>'.$value['label'].'</option>';
            }
            $str .= '</select>';
        }

        return $str;
    }

    function radio($arr_data, $name, $nilai=''){
        if (!empty($arr_data)) {
            $str = '';     
            foreach ($arr_data as $key => $value) {
                $checked = '';
                if (strtolower($value['id']) == strtolower($nilai)) {
                    $checked = 'checked';
                }
                $str .= '<label class="radio">';
                $str .= '<input type="radio" name="'.$name.'" id="radio'.$key.'" value="'.$value['id'].'" '.$checked.'>'.$value['label'];
                $str .= '</label>';
                $str .= '<div style="clear:both"></div>';
            }
        }

        return $str;
    }

    function debugPreviewJadwal($jadwal){
    	$this->CI->load->model('penjadwalan_model');
    	$ruang = $this->CI->penjadwalan_model->get_all_ruang();
		$waktu = $this->CI->penjadwalan_model->get_all_waktu();
		// $jadwal = $this->CI->penjadwalan_model->get_all_jadwal_kuliah();
		// echo '<pre>'; print_r($jadwal); echo '</pre>';
		// echo '<pre>'; print_r($ind); echo '</pre>'; exit();

        $grup_hari = array('senin','selasa','rabu','kamis','jumat');
    	// $grup_hari = array('senin','selasa','rabu','kamis','jumat','sabtu');
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
		$table_header .= '<th style="vertical-align:middle;width:100px;border: 1px solid black;" rowspan="2">RUANG/WAKTU</th>';
		$table_header2 = '<tr>';
		$jml_kolom_data_header = count($waktu);
		foreach ($waktu_transform as $key => $value) {
			$table_header .= '<th colspan="10" style="border: 1px solid black;">'.$value['hari'].'</th>';
			foreach ($value['data'] as $i => $item) {
				$table_header2 .= '<th style="width:220px;border: 1px solid black;">'.$item['jam_ke'].'</th>';
			}
		}
		$table_header2 .= '</tr>';
		$table_header .= '</tr>';
		$table_header .= $table_header2;
		$table_header .= '</thead>';

		$table_body = '<tbody>';
		foreach ($ruang as $key => $value) {
			$table_body .= '<tr style="height:80px;border: 1px solid black;">';
			$table_body .= '<td style="border: 1px solid black;">'.$value['ru_nama'].'</td>';
			$period = 0;
			// echo $value['ru_id'].' : ';
			foreach ($waktu as $i => $time) {
				$label = '';
				$colspan = '';
				$style = 'style="border: 1px solid black;"';
				if ($period == 0) {
					foreach ($jadwal as $j => $item) {
						if ($value['ru_id'] == $item['id_ruang'] AND $time['waktu_id'] == $item['id_waktu']) {
							$label = $item['nama_kelas'].'<br>'.$item['label_timespace'];
							$colspan = 'colspan="'.$item['period'].'"';
							$style = 'style="background-color: #F0F0C5;border: 1px solid black; padding:0px;"';
							$period = $item['period']-1;
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

		echo '<div style="height:550px; overflow-x: auto;margin-top: 30px;">';
		echo '<table style="max-width:1000%; width:600%;border: 1px solid black; font: 10px sans-serif; ">';
		echo $table_header;
		echo $table_body;
		echo "</table>";
		echo "</div>";

		// echo '<pre>'; print_r($jadwal); echo '</pre>';

    }

    /**
     * @since oct 10, 2014
     */
    function simpan_log_proses($kode, $data){
    	$this->CI->load->model('bantu_model');
    	
		$sts = true;
		$this->CI->db->trans_start();

		$param = array(
			'kode' => $kode,
			'data' => $data
		);
		$sts = $sts && $this->CI->bantu_model->up_logproses($param);
		$this->CI->db->trans_complete();

		return $this->CI->db->trans_status();
    }

}

/* End of file Someclass.php */