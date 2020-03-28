<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* ZYA CBT
* Achmad Lutfi
* achmdlutfi@gmail.com
* achmadlutfi.wordpress.com
*/
class Cbt_user_model extends CI_Model{
	public $table = 'cbt_user';
	
	function __construct(){
        parent::__construct();
    }
	
    function save($data){
        $this->db->insert($this->table, $data);
    }
    
    function delete($kolom, $isi){
        $this->db->where($kolom, $isi)
                 ->delete($this->table);
    }
    
    function update($kolom, $isi, $data){
        $this->db->where($kolom, $isi)
                 ->update($this->table, $data);
    }
    
    function count_by_kolom($kolom, $isi){
        $this->db->select('COUNT(*) AS hasil')
                 ->where($kolom, $isi)
                 ->from($this->table);
        return $this->db->get();
    }
	
	function get_by_kolom($kolom, $isi){
        $this->db->select('user_id,user_grup_id,user_name,user_password,user_email,user_firstname,user_regdate')
                 ->where($kolom, $isi)
                 ->from($this->table);
        return $this->db->get();
    }
	
	function get_by_kolom_limit($kolom, $isi, $limit){
        $this->db->select('user_id,user_grup_id,user_name,user_password,user_email,user_firstname,user_regdate')
                 ->where($kolom, $isi)
                 ->from($this->table)
				 ->limit($limit);
        return $this->db->get();
    }

    function count_by_username_password($username, $password){
        $this->db->select('COUNT(*) AS hasil')
                 ->where('(user_name="'.$username.'" AND user_password="'.$password.'")')
                 ->from($this->table);
        return $this->db->get()->row()->hasil;  
    }

    function get_by_username($username){
        $this->db->join('cbt_user_grup', 'cbt_user.user_grup_id = cbt_user_grup.grup_id')
                 ->where('user_name',$username)
                 ->limit(1);
        $query = $this->db->get($this->table);
        return ($query->num_rows() > 0) ? $query->row() : FALSE;
    }
	
	function get_datatable($start, $rows, $kolom, $isi, $group){
        $query = '';
        if($group!='semua'){
            $query = 'AND user_grup_id='.$group;
        }
		$this->db->where('('.$kolom.' LIKE "%'.$isi.'%" '.$query.')')
                 ->from($this->table)
				 ->order_by($kolom, 'ASC')
                 ->limit($rows, $start);
        return $this->db->get();
	}
    
    function get_datatable_count($kolom, $isi, $group){
        $query = '';
        if($group!='semua'){
            $query = 'AND user_grup_id='.$group;
        }
		$this->db->select('COUNT(*) AS hasil')
                 ->where('('.$kolom.' LIKE "%'.$isi.'%" '.$query.')')
                 ->from($this->table);
        return $this->db->get();
	}
}