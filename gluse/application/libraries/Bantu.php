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

}

/* End of file Someclass.php */