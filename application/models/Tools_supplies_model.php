<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tools_supplies_model extends App_Model
{
	private $contact_columns;

    public function __construct()
    {
        parent::__construct();

        // $this->contact_columns = hooks()->apply_filters('contact_columns', ['firstname', 'lastname', 'email', 'phonenumber', 'title', 'password', 'send_set_password_email', 'donotsendwelcomeemail', 'permissions', 'direction', 'invoice_emails', 'estimate_emails', 'credit_note_emails', 'contract_emails', 'task_emails', 'project_emails', 'ticket_emails', 'is_primary']);

        // $this->load->model(['client_vault_entries_model', 'client_groups_model', 'statement_model']);
    }

    public function insertCategoryToolsSupplies($data)
    {
    	$this->db->insert('tbl_category_tools_supplies', $data);
    	return $this->db->insert_id();
    }

    public function rowCategoryToolsSupplies($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_category_tools_supplies');
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

    public function updateCategoryToolsSupplies($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tbl_category_tools_supplies', $data);
    }

    public function deleteCategoryToolsSupplies($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('tbl_category_tools_supplies');
    }

    public function searchCategoryToolsSupplies($q, $limit = 50)
    {
        $this->db->select('tbl_category_tools_supplies.id as id, tbl_category_tools_supplies.name as name', false);
        $this->db->from('tbl_category_tools_supplies');
        if (!empty($q))
        {
            $this->db->group_start();
            $this->db->like('tbl_category_tools_supplies.code', $q);
            $this->db->or_like('tbl_category_tools_supplies.name', $q);
            $this->db->group_end();
        }
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function insertToolsSupplies($data)
    {
        $this->db->insert('tbl_tools_supplies', $data);
        return $this->db->insert_id();
    }

    public function updateToolsSupplies($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tbl_tools_supplies', $data);
    }

    public function deleteToolsSupplies($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('tbl_tools_supplies');
    }

    public function rowToolsSupplies($id)
    {
        $this->db->select('tbl_tools_supplies.*, tbl_category_tools_supplies.name as category_name', false);
        $this->db->from('tbl_tools_supplies');
        $this->db->join('tbl_category_tools_supplies', 'tbl_category_tools_supplies.id = tbl_tools_supplies.category_id', 'left');
        $this->db->where('tbl_tools_supplies.id', $id);
        return $this->db->get()->row_array();
    }

    public function checkExistCategory($id)
    {
        $this->db->from('tbl_tools_supplies');
        $this->db->where('category_id', $id);
        $this->db->limit(1);
        return $this->db->get()->num_rows();
    }

    public function searchMaterials($q, $limit = 50)
    {
        $this->db->select('tbl_materials.id as id, tbl_materials.name as name', false);
        $this->db->from('tbl_materials');
        if (!empty($q))
        {
            $this->db->group_start();
            $this->db->like('tbl_materials.code', $q);
            $this->db->or_like('tbl_materials.name', $q);
            $this->db->group_end();
        }
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function checkCategoryToolsSuppliesByCode($code)
    {
        $this->db->from('tbl_category_tools_supplies');
        $this->db->where('tbl_category_tools_supplies.code', $code);
        $this->db->limit(1);
        return $this->db->get()->num_rows();
    }

    public function checkToolsSuppliesByCode($code)
    {
        $this->db->from('tbl_tools_supplies');
        $this->db->where('tbl_tools_supplies.code', $code);
        $this->db->limit(1);
        return $this->db->get()->num_rows();
    }

    public function rowCategoryToolsSuppliesByCode($code, $select, $option)
    {
        $this->db->select($select);
        $this->db->from('tbl_category_tools_supplies');
        if ($option == "like") {
            $this->db->like('tbl_category_tools_supplies.code', $code);
        } else if ($option == "where") {
            $this->db->where('tbl_category_tools_supplies.code', $code);
        }
        return $this->db->get()->row_array();
    }
}