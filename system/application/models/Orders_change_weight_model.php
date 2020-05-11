<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Orders_change_weight_model extends CI_Model
{
    protected $table;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'tbl_orders_change_weight';
        $this->table_money = 'tbl_orders_change_money';
    }

    public function insert($data)
    {
        if (!empty($data)) {
            $this->db->insert($this->table, $data);
            if ($this->db->affected_rows() > 0) {
                return $this->db->insert_id();
            }
        }
        return false;
    }

    public function insert_money($data)
    {
        if (!empty($data)) {
            $this->db->insert($this->table_money, $data);
            if ($this->db->affected_rows() > 0) {
                return $this->db->insert_id();
            }
        }
        return false;
    }
}