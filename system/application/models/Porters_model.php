<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Porters_model extends App_Model
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
    public function get_roles()
    {
        $is_admin = is_admin();
        $roles = $this->db->get('tblroles')->result_array();
        return $roles;
    }
    public function add_porters($data)
    {
        $data['name']=mb_convert_case($data['name'], MB_CASE_TITLE, "UTF-8");
        if (is_admin()) {
            $this->db->insert('tblporters',$data);
            if ($this->db->affected_rows() >0) {
                return true;
            }
            return false;
        }
        return false;
    }
    public function update_porters($data_vestion,$id)
    {
        if (is_admin()) {
            $data_vestion['name']=mb_convert_case($data_vestion['name'], MB_CASE_TITLE, "UTF-8");
            $this->db->where('id',$id);
            $this->db->update('tblporters',$data_vestion);
            if ($this->db->affected_rows() >0) {
                return true;
            }
            return false;
        }
        return false;
    }
    public function delete_porters($id)
    {
        if (is_admin()) {
            $this->db->where('id', $id);
            $this->db->delete('tblporters');
            if ($this->db->affected_rows() > 0) {
                return true;
            }
            return false;
        }
        return false;
    }
    public function get_row_porters($id)
    {
        if (is_admin()) {
            $this->db->select('tblporters.*');
            $this->db->where('tblporters.id', $id);
            return $this->db->get('tblporters')->row();
        }
    }

}
