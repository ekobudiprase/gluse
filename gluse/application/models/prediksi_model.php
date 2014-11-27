<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package     Gluse
 * @subpackage  home
 * @author      Eko Budi Prasetyo
 * @version     0.0.0
 * @since       May 30, 2014
 */

class Prediksi_model extends CI_Model {

    /**
    * @author   Eko Budi Prasetyo
    * @version  0.0.0
    * @since    30may, 2014
    * @usedfor  -
    */
    function __construct(){
        // Call the Model constructor
        parent::__construct();
        // $this->load->database();
    }

    private function get_mkprodid_by_mkid($mkid){
        $query = '
            SELECT 
                ps.prodi_nama,
                mp.`mkkprod_id`
            FROM program_studi ps
            LEFT JOIN mkkur_prodi mp ON ps.prodi_id = mp.mkkprod_prodi_id
            WHERE mp.mkkprod_mkkur_id = "'.$mkid.'"
            AND (mp.`mkkprod_related_id` IS NULL or mp.`mkkprod_related_id`="0")
        ';

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        foreach ($ret as $key => $value) {
            $prodi[$key] = $value['prodi_nama'];            
            $perpanjangan = true;
            $id = $value['mkkprod_id'];
            while ($perpanjangan) {
                $query_2 = '
                    SELECT 
                        ps.prodi_nama,
                        mp.`mkkprod_id`,
                        mp.`mkkprod_related_id`
                    FROM program_studi ps
                    LEFT JOIN mkkur_prodi mp ON ps.prodi_id = mp.mkkprod_prodi_id
                    WHERE 1=1
                    --cond--
                    
                ';

                $str = 'and mp.`mkkprod_related_id` = "'.$id.'"';
                $query_2 = str_replace('--cond--', $str, $query_2);

                $ret2 = $this->db->query($query_2);
                $ret2 = $ret2->result_array();
                if (!empty($ret2)) {
                    $id = $ret2[0]['mkkprod_id'];
                    $prodi[$key] .= ', '.$ret2[0]['prodi_nama'];
                    $perpanjangan = true;
                }else{
                    $perpanjangan = false;
                }
            }            
        }        

        $strret = "";
        if (isset($prodi)) {
            foreach ($prodi as $key => $value) {
                $strret .= $value.";<br>";
            }
        }        

        // echo '<pre>'; print_r($strret); 
        return $strret;
    }

    //=========================================================================================================
    
    /**
    * @author   Eko Budi Prasetyo
    * @version  0.0.0
    * @since    30may, 2014
    * @usedfor  -
    */
    function get_count_matakuliah($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        if (!empty($semester_aktif)) {
            $str .= "AND mkkur_semester = '$semester_aktif'";   
        }

        $query = '
            SELECT count(mkkur_id) as total
            FROM mata_kuliah_kurikulum mkk
            WHERE 1=1
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val[0]['total'];
    }

    /**
    * @author   Eko Budi Prasetyo
    * @version  0.0.0
    * @since    30may, 2014
    * @usedfor  -
    */
    function get_matakuliah($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        if (!empty($semester_aktif)) {
            $str .= "AND mkkur_semester = '$semester_aktif'";   
        }

        
        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                mkk.`mkkur_id` AS id,
                mkk.`mkkur_kode` AS kode,
                mkk.`mkkur_nama` AS nama,
                mkk.`mkkur_paket_semester` AS paket,
                mkk.`mkkur_semester` AS smt,
                "null" AS nama_prodi,
                mkk.`mkkur_sks` AS sks,
                IF(mkkur_pred_jml_peminat IS NULL,"Belum diketahui",mkkur_pred_jml_peminat) AS pred_jml_peminat
            FROM mata_kuliah_kurikulum mkk
            -- LEFT JOIN program_studi ps ON mkk.`mkkur_prodi_id` = ps.`prodi_id`
            WHERE 1=1
            --search--
            --limit--
        ';

        $query = str_replace('--search--', $str, $query);
        $query = str_replace('--limit--', $limit, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        $prodi = array();
        foreach ($ret as $key => $value) {
            $ret[$key]['nama_prodi'] = $this->get_mkprodid_by_mkid($value['id']);
        }
        

        return $ret;
    }

    /**
    * @author   Eko Budi Prasetyo
    * @version  0.0.0
    * @since    30may, 2014
    * @usedfor  -
    */
    function get_all_matakuliah($semester_aktif){

        $str = '';
        if (!empty($semester_aktif)) {
            $str .= "AND mkkur_semester = '$semester_aktif'";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                mkk.`mkkur_id` AS id,
                mkk.`mkkur_kode` AS kode,
                mkk.`mkkur_nama` AS nama,
                mkk.`mkkur_paket_semester` AS paket,
                mkk.`mkkur_semester` AS smt,
                "null" AS nama_prodi,
                mkk.`mkkur_sks` AS sks,
                "Belum diketahui" AS jml_peminat
            FROM mata_kuliah_kurikulum mkk
            -- LEFT JOIN program_studi ps ON mkk.`mkkur_prodi_id` = ps.`prodi_id`
            WHERE 1=1
            --search--
            ORDER BY mkk.`mkkur_semester`
        ';

        $query = str_replace('--search--', $str, $query);
        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        $prodi = array();
        foreach ($ret as $key => $value) {
            $ret[$key]['nama_prodi'] = $this->get_mkprodid_by_mkid($value['id']);
        }
        

        return $ret;
    }

    
    function get_matakuliahrekap_by_makulid($id_mkk){
        $query = '
            SELECT
                mkkr.`mkkurrkp_id` AS id,
                mkk.`mkkur_kode` AS kode,
                mkk.`mkkur_nama` AS nama,
                mkkr.`mkkurrkp_tahun` AS tahun,
                mkkr.`mkkurrkp_jml_peminat` AS jml_peminat,
                (   
                SELECT MAX(mkkurrkp_jml_peminat) FROM mata_kuliah_kurikulum_rekap
                )
                AS maks,
                (
                SELECT MIN(mkkurrkp_jml_peminat) FROM mata_kuliah_kurikulum_rekap
                ) AS mins,
                (SELECT MAX(mkkurrkp_jml_peminat) FROM mata_kuliah_kurikulum_rekap)
                -
                (SELECT MIN(mkkurrkp_jml_peminat) FROM mata_kuliah_kurikulum_rekap) AS selisih,
                
                (0.8*(mkkurrkp_jml_peminat - (SELECT MIN(mkkurrkp_jml_peminat) FROM mata_kuliah_kurikulum_rekap)))
                /
                (
                (SELECT MAX(mkkurrkp_jml_peminat) FROM mata_kuliah_kurikulum_rekap)
                -
                (SELECT MIN(mkkurrkp_jml_peminat) FROM mata_kuliah_kurikulum_rekap)
                )
                + 0.1
                 AS interpolasi,  
                               
                ((mkkurrkp_jml_peminat - (SELECT MIN(mkkurrkp_jml_peminat) FROM mata_kuliah_kurikulum_rekap)))
                /
                (
                (SELECT MAX(mkkurrkp_jml_peminat) FROM mata_kuliah_kurikulum_rekap)
                -
                (SELECT MIN(mkkurrkp_jml_peminat) FROM mata_kuliah_kurikulum_rekap)
                )
                 AS interpolasi_0,
                 (
                 SELECT MAX(mkkurrkp_jml_peminat) 
                 FROM mata_kuliah_kurikulum_rekap sdf
                 WHERE sdf.mkkurrkp_mkkur_id = mkkr.mkkurrkp_mkkur_id 
                 )AS max_lokal,
                 (
                 SELECT MIN(mkkurrkp_jml_peminat) 
                 FROM mata_kuliah_kurikulum_rekap sdf
                 WHERE sdf.mkkurrkp_mkkur_id = mkkr.mkkurrkp_mkkur_id 
                 )AS min_lokal
            FROM mata_kuliah_kurikulum_rekap mkkr
            LEFT JOIN mata_kuliah_kurikulum mkk ON mkkr.`mkkurrkp_mkkur_id` = mkk.`mkkur_id`
            WHERE mkkr.`mkkurrkp_mkkur_id` = "'.$id_mkk.'"
            ORDER BY mkkr.`mkkurrkp_tahun` ASC
        ';

        $ret = $this->db->query($query);

        return $ret->result_array();
    }

    function get_listid_matakuliah(){
        $query = '
            SELECT DISTINCT
            mkkurrkp_mkkur_id AS mkk_id,
            mkk.`mkkur_kode` AS kode,
            mkk.`mkkur_nama` AS nama
            FROM mata_kuliah_kurikulum_rekap mkkr
            LEFT JOIN mata_kuliah_kurikulum mkk ON mkkr.`mkkurrkp_mkkur_id` = mkk.`mkkur_id`
        ';

        $ret = $this->db->query($query);

        return $ret->result_array();
    }

    function get_maxmin_jmlpeminat_mkkrekap(){
        $query = '
            SELECT
            (SELECT MAX(mkkurrkp_jml_peminat) FROM mata_kuliah_kurikulum_rekap) AS maks,
            (SELECT MIN(mkkurrkp_jml_peminat) FROM mata_kuliah_kurikulum_rekap) AS mins
        ';

        $ret = $this->db->query($query);

        return $ret->result_array();
    }

    /**
    * @author   Eko Budi Prasetyo
    * @version  0.0.0
    * @since    30may, 2014
    * @usedfor  -
    */
    function get_count_matakuliah_rekap($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        if (!empty($semester_aktif)) {
            $str .= "AND mkkur_semester = '$semester_aktif'";   
        }

        $query = '
            SELECT count(mkkurrkp_id) as total
            FROM mata_kuliah_kurikulum_rekap mkkr
            LEFT JOIN mata_kuliah_kurikulum mkk ON mkkr.`mkkurrkp_mkkur_id` = mkk.`mkkur_id`
            -- LEFT JOIN program_studi ps ON mkk.`mkkur_prodi_id` = ps.`prodi_id`
            WHERE 1=1
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val[0]['total'];
    }

    /**
    * @author   Eko Budi Prasetyo
    * @version  0.0.0
    * @since    30may, 2014
    * @usedfor  -
    */
    function get_matakuliah_rekap($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        if (!empty($semester_aktif)) {
            $str .= "AND mkkur_semester = '$semester_aktif'";   
        }
        
        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";   
        }

        $query = '
            SELECT
                mkkr.`mkkurrkp_id` AS id,
                mkk.mkkur_id AS mk_id,
                mkk.`mkkur_kode` AS kode,
                mkk.`mkkur_nama` AS nama,
                mkk.`mkkur_paket_semester` AS paket,
                mkk.`mkkur_semester` AS smt,
                "null" AS nama_prodi,
                mkk.`mkkur_sks` AS sks,
                mkkr.`mkkurrkp_tahun` AS tahun,
                mkkr.`mkkurrkp_jml_peminat` AS jml_peminat
            FROM mata_kuliah_kurikulum_rekap mkkr
            LEFT JOIN mata_kuliah_kurikulum mkk ON mkkr.`mkkurrkp_mkkur_id` = mkk.`mkkur_id`
            -- LEFT JOIN program_studi ps ON mkk.`mkkur_prodi_id` = ps.`prodi_id`
            WHERE 1=1 
            --search--
            --limit--
        ';

        $query = str_replace('--search--', $str, $query);
        $query = str_replace('--limit--', $limit, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        $prodi = array();
        foreach ($ret as $key => $value) {
            $ret[$key]['nama_prodi'] = $this->get_mkprodid_by_mkid($value['mk_id']);
        }
        

        return $ret;
    }

    /**
    * @author   Eko Budi Prasetyo
    * @version  0.0.0
    * @since    30may, 2014
    * @usedfor  -
    */
    function get_idmakul_by_kodemakul($kode_makul){
        $str = 'AND mkk.`mkkur_kode` = "'.$kode_makul.'"';

        $query = '
            SELECT
                mkk.`mkkur_id` AS id_mkk
            FROM mata_kuliah_kurikulum mkk
            WHERE 1=1
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val[0]['id_mkk'];
    }

    function truncate_matakuliahrekap(){
        return $this->db->truncate('mata_kuliah_kurikulum_rekap'); 
    }

    function ins_matakuliahrekap($param){
        if (is_array($param))
            extract($param);

        $sql = "
            INSERT INTO `mata_kuliah_kurikulum_rekap`
            (`mkkurrkp_mkkur_id`,`mkkurrkp_jml_peminat`,`mkkurrkp_tahun`)
            VALUES (?,'?','?');
        ";
        
        return $this->db->query($sql, array($id_mk, $jml_peminat, $tahun)); 
    }

    function up_predjmlpnt_matakuliah($param){
        if (is_array($param))
            extract($param);

        $sql = "
            UPDATE mata_kuliah_kurikulum
			SET mkkur_pred_jml_peminat= '?', mkkur_pred_tahun = YEAR(NOW())
			WHERE mkkur_id = ?
        ";
        
        return $this->db->query($sql, array($pre_jml_peminat_normal, $id_mk)); 
    }

    function cek_rekap_inserted(){
        $query = '
            SELECT
                IF(COUNT(mkkurrkp_id)=0,FALSE,TRUE) AS rekapmk_ada
            FROM mata_kuliah_kurikulum_rekap mkkr
        ';

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        return $ret[0]['rekapmk_ada'];
    }


}

?>