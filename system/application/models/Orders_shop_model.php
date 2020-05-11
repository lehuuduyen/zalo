<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Orders_shop_model extends App_Model
{

    protected $table;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'tblorders_shop';
    }

    public function get_orders_used_api(){

        $this->db->select('code_supership , control_date , id , date_debits, status , shop , city_send , value , city , district , mass');
        $this->db->from($this->table);
        $this->db->where('control_date' , NULL);
        $this->db->where('status !=' , "Huỷ");
        $query = $this->db->get();
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

    public function update_is_hd_branch(){
        $sub_query = $this->db->select('customer_shop_code as shop')->from('tblcustomers')->get_compiled_select();

        $this->db->where('control_date' , NULL);
        $this->db->where('status !=' , "Huỷ");
        $this->db->where("shop IN ($sub_query)");
        $rows = $this->db->update($this->table, array('is_hd_branch' => true));
        return $rows;
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