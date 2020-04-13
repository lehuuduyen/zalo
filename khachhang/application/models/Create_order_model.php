<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Create_order_model extends CI_Model {
    function __construct()
    {
        parent::__construct();

        // define primary table
        $this->_db = 'settings';
    }

    public function get_orders_by_time($user_id, $params)
    {
        if(!empty($user_id)){
            $sub_query = $this->db->select('customer_shop_code as shop')->from('tblcustomers')->where('id', $user_id)->get_compiled_select();
        }
        $this->db->select('id, date_create, code_supership ,collect, hd_fee_stam, receiver, phone, address, district, city, mass');
        $this->db->from('tblorders_shop');
        if(!empty($user_id)){
            $this->db->where("shop IN ($sub_query)");
        }
        if(!empty($params['startDate'])){
            $this->db->where('date_create >=' , date('Y-m-d',strtotime(str_replace('-', '-', $params['startDate']))));
        }
        if(!empty($params['endDate'])){
            $this->db->where('date_create <=' , date('Y-m-d',strtotime(str_replace('-', '-', $params['endDate']))));
        }
        $this->db->where("status NOT LIKE 'Hủy'");
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result_array();
        } else{
            return [];
        }
    }

    public function get_customer_by_id($id){
        $this->db->where('id', $id);
        $query = $this->db->get('tblcustomers');
        if($query->num_rows() > 0){
            return $query->first_row('array');
        } else{
            return [];
        }
    }
}
