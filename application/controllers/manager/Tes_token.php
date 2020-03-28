<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* ZYA CBT
* Achmad Lutfi
* achmdlutfi@gmail.com
* achmadlutfi.wordpress.com
*/
class Tes_token extends Member_Controller {
	private $kode_menu = 'tes-token';
	private $kelompok = 'tes';
	private $url = 'manager/tes_token';
	
    function __construct(){
		parent:: __construct();
		$this->load->model('cbt_modul_model');
		$this->load->model('cbt_tes_token_model');

		parent::cek_akses($this->kode_menu);
	}
	
    public function index($page=null, $id=null){
        $data['kode_menu'] = $this->kode_menu;
        $data['url'] = $this->url;

        $username = $this->access->get_username();
		$user_id = $this->users_model->get_login_info($username)->id;

        $this->cbt_tes_token_model->delete_by_user_date($user_id);
        
        $this->template->display_admin($this->kelompok.'/tes_token_view', 'Token', $data);
    }

    function token(){
        $token = substr(uniqid(), 7);

        $username = $this->access->get_username();
        $user_id = $this->users_model->get_login_info($username)->id;
        $lifetime = $this->input->post('token-lifetime', TRUE);

        $i=1;
        while($i==1){
        	if($this->cbt_tes_token_model->count_by_kolom('token_isi', $token)->row()->hasil==0){
        		$data['token_isi'] = strtoupper($token);
                $data['token_user_id'] = $user_id;
                $data['token_aktif'] = $lifetime;

        		$this->cbt_tes_token_model->save($data);
        		$i=0;
        	}

        	$token = substr(uniqid(), 7);
        }

        $status['status'] = 1;
        $status['token'] = $data['token_isi'];
		$status['pesan'] = 'Token berhasil di generate';

        echo json_encode($status);
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
		$query = $this->cbt_tes_token_model->get_datatable($start, $rows, 'token_isi', $search);
		$iFilteredTotal = $query->num_rows();
		
		$iTotal= $this->cbt_tes_token_model->get_datatable_count('token_isi', $search)->row()->hasil;
	    
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
            $record[] = $temp->token_isi;
            $record[] = $temp->token_ts;
            if($temp->token_aktif==1){
                $record[] = '1 Hari';
            }else{
                $record[] = $temp->token_aktif.' Menit';
            }

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