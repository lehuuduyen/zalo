<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Manual_customers_model extends CI_Model
{
    protected $table;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'tbl_manual_customers';
    }

    public function insert_multiple_records($data){
        if (!empty($data)) {
            $this->db->insert_batch($this->table, $data);
            $first_id = $this->db->insert_id();
            $affected_rows = $this->db->affected_rows();
            $ids = array();
            for ($i = 0; $i < $affected_rows; $i++){
                array_push($ids, $first_id + $i);
            }
            return $ids;
        } else{
            return false;
        }
    }

    public function get_user_by_phone($phone){
        $query = $this->db->where('phone', $phone)->get($this->table);
        if($query->num_rows() > 0){
            return $query->first_row('array');
        } else{
            return false;
        }
    }
}