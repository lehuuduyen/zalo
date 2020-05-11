<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Max_time_status_model extends App_Model
{

    protected $table;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'tbl_max_time_status';
    }

    public function get_all(){
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    public function update_batch($data){
        if (!empty($data)) {
            $rows =  $this->db->update_batch($this->table, $data, 'id');
            return $rows;
        } else{
            return false;
        }
    }
}