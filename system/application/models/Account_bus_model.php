<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Account_bus_model extends App_Model
{
    private $statuses;
    function __construct()
    {
        parent::__construct();
    }
    /**
     * Get task by id
     * @param  mixed $id task id
     * @return object
     */
    public function add_account_bus($data)
    {
        if (is_admin()) {
            $this->db->insert('tblaccount_business',$data);
            if ($this->db->affected_rows() >0) {
                return true;
            }
            return false;
        }
        return false;
    }
    public function update_account_bus($data_vestion,$id)
    {
        if (is_admin()) {
            // var_dump($data_vestion);die();
            $this->db->where('id',$id);
            $this->db->update('tblaccount_business',$data_vestion);
            if ($this->db->affected_rows() >0) {
                return true;
            }
            return false;
        }
        return false;
    }
    public function delete_account_bus($id)
    {
        if (is_admin()) {
            $this->db->where('id', $id);
            $this->db->delete('tblaccount_business');
            if ($this->db->affected_rows() > 0) {
                return true;
            }
            return false;
        }
        return false;
    }
    public function get_row_account_bus($id)
    {
        if (is_admin()) {
            $this->db->select('tblaccount_business.*');
            $this->db->where('tblaccount_business.id', $id);
            return $this->db->get('tblaccount_business')->row();
        }
    }
    function getaccount_buss()
    {
        $this->db->select('*');
        $this->db->from('tblaccount_business');
        return $this->db->get()->result_array();
    }

    function rowaccount_bus($id)
    {
        $this->db->select('*');
        $this->db->from('tblaccount_business');
        $this->db->where('account_busid', $id);
        return $this->db->get()->row_array();
    }

    function insertaccount_bus($data)
    {
        $this->db->insert('tblaccount_business', $data);
        return $this->db->insert_id();
    }
}
