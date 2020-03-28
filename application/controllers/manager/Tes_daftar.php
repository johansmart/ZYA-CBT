<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* ZYA CBT
* Achmad Lutfi
* achmdlutfi@gmail.com
* achmadlutfi.wordpress.com
*/
class Tes_daftar extends Member_Controller {
	private $kode_menu = 'tes-daftar';
	private $kelompok = 'tes';
	private $url = 'manager/tes_daftar';
	
    function __construct(){
		parent:: __construct();
		$this->load->model('cbt_tes_model');

		parent::cek_akses($this->kode_menu);
	}
	
    public function index($status=null, $pesan=null){
        $data['kode_menu'] = $this->kode_menu;
        $data['url'] = $this->url;

        if(!empty($pesan)){
        	if($status=='0'){
        		$data['pesan_hapus'] = '<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-info"></i> Perhatian</h4>'.$pesan.'</div>';
        	}else{
        		$data['pesan_hapus'] = '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-info"></i> Informasi</h4>'.$pesan.'</div>';
        	}
        }
        
        $this->template->display_admin($this->kelompok.'/tes_daftar_view', 'Daftar Tes', $data);
    }

    function hapus_tes(){
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('hapus-id', 'ID Tes','required|strip_tags');
        $this->form_validation->set_rules('hapus-nama', 'Nama Tes','required|strip_tags');
        $this->form_validation->set_rules('hapus-deskripsi', 'Deskripsi Tes','required|strip_tags');
        
        if($this->form_validation->run() == TRUE){
            $tes_id = $this->input->post('hapus-id', true);

            $this->cbt_tes_model->delete('tes_id', $tes_id);

            $status['status'] = 1;
            $status['pesan'] = 'Tes berhasil dihapus';
        }else{
            $status['status'] = 0;
            $status['pesan'] = validation_errors();
        }
        
        echo json_encode($status);
    }

    function hapus_daftar_tes(){
    	$this->load->library('form_validation');
        
        $this->form_validation->set_rules('edit-tes-id[]', 'ID Tes','required|strip_tags');
        
        $status=0;
        $pesan='';
        if($this->form_validation->run() == TRUE){
        	$tes_id = $this->input->post('edit-tes-id', TRUE);
            $centang = $this->input->post('centang', TRUE);
            if($centang=='1'){
            	foreach( $tes_id as $kunci => $isi ) {
					if($isi=="on"){
						// Memulai transaction mysql
						$this->db->trans_start();

		            	$this->cbt_tes_model->delete('tes_id', $kunci);

		            	// Menutup transaction mysql
						$this->db->trans_complete();
	            	}
	            }
            	$status=1;
        		$pesan='Tes yang dipilih berhasil dihapus';
            }else{
            	$pesan='Centang pernyataan bahwa anda yakin untuk menghapus Tes=';
            }
        }else{
            $pesan='Pilih terlebih dahulu Tes yang akan dihapus';
        }
        
    	$this->index($status, $pesan);
    }
    
    function get_by_id($id=null){
    	$data['data'] = 0;
		if(!empty($id)){
			$query = $this->cbt_tes_model->get_by_kolom('tes_id', $id);
			if($query->num_rows()>0){
				$query = $query->row();
				$data['data'] = 1;
				$data['id'] = $query->tes_id;
				$data['nama'] = $query->tes_nama;
				$data['deskripsi'] = $query->tes_detail;
				$data['waktu'] = $query->tes_duration_time;
	            $data['poin'] = $query->tes_score_right;
	            $data['poin_salah'] = $query->tes_score_wrong;
	            $data['poin_kosong'] = $query->tes_score_unanswered;
	            $data['tunjukkan_hasil'] = $query->tes_results_to_users;
	            $data['token'] = $query->tes_token;
	            $data['rentang_waktu'] = $query->tes_begin_time.' - '.$query->tes_end_time;
			}
		}
		echo json_encode($data);
    }
    
    function get_datatable(){
		// variable initialization
		$search = "";
		$start = 0;
		$rows = 10;

		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = $_GET['sSearch'];
		}

		// limit
		$start = $this->get_start();
		$rows = $this->get_rows();

		// run query to get user listing
		$query = $this->cbt_tes_model->get_datatable($start, $rows, 'tes_nama', $search);
		$iFilteredTotal = $query->num_rows();
		
		$iTotal= $this->cbt_tes_model->get_datatable_count('tes_nama', $search)->row()->hasil;
	    
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
	        "iTotalRecords" => $iTotal,
	        "iTotalDisplayRecords" => $iTotal,
	        "aaData" => array()
	    );

	    // get result after running query and put it in array
		$i=$start;
		$query = $query->result();
	    foreach ($query as $temp) {			
			$record = array();
            
			$record[] = ++$i;
            $record[] = $temp->tes_nama;
            $record[] = $temp->tes_max_score;
            $record[] = $temp->tes_begin_time;
            $record[] = $temp->tes_end_time;
            $record[] = $temp->tes_duration_time.' Menit';
            $record[] = $temp->tes_score_right;

            if($temp->tes_results_to_users==1){
            	$record[] = 'Ya';
            }else{
            	$record[] = 'Tidak';
            }

            if($temp->tes_token==1){
            	$record[] = 'Ya';
            }else{
            	$record[] = 'Tidak';
            }

            
            $record[] = '
            	<a onclick="edit(\''.$temp->tes_id.'\')" style="cursor: pointer;" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-edit"></span></a>
            	<a onclick="hapus(\''.$temp->tes_id.'\')" style="cursor: pointer;" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-remove"></a>
            ';

            $record[] = '<input type="checkbox" name="edit-tes-id['.$temp->tes_id.']" >';

			$output['aaData'][] = $record;
		}
		// format it to JSON, this output will be displayed in datatable
        
		echo json_encode($output);
	}
	
	/**
	* funsi tambahan 
	* 
	* 
*/
	
	function get_start() {
		$start = 0;
		if (isset($_GET['iDisplayStart'])) {
			$start = intval($_GET['iDisplayStart']);

			if ($start < 0)
				$start = 0;
		}

		return $start;
	}

	function get_rows() {
		$rows = 10;
		if (isset($_GET['iDisplayLength'])) {
			$rows = intval($_GET['iDisplayLength']);
			if ($rows < 5 || $rows > 500) {
				$rows = 10;
			}
		}

		return $rows;
	}

	function get_sort_dir() {
		$sort_dir = "ASC";
		$sdir = strip_tags($_GET['sSortDir_0']);
		if (isset($sdir)) {
			if ($sdir != "asc" ) {
				$sort_dir = "DESC";
			}
		}

		return $sort_dir;
	}
}