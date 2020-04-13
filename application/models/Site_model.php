<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Site_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function sumWarehouseItems($id_items, $type_items)
    {
    	$this->db->select('SUM(tblwarehouse_items.product_quantity) as quantity_warehouse', false);
    	$this->db->from('tblwarehouse_items');
    	$this->db->where('tblwarehouse_items.id_items', $id_items);
    	$this->db->where('tblwarehouse_items.type_items', $type_items);
        $this->db->where('tblwarehouse_items.warehouse_id !=', 8);
    	$this->db->group_by('tblwarehouse_items.id_items');
    	return $this->db->get()->row_array();
    }

    public function searchSuppliers($term, $limit)
    {
        $this->db->select('tblsuppliers.id as id, tblsuppliers.company as text', false);
        $this->db->from('tblsuppliers');
        if (!empty($q))
        {
            $this->db->group_start();
            $this->db->like('tblsuppliers.company', $q);
            $this->db->or_like('tblsuppliers.phone', $q);
            $this->db->group_end();
        }
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function rowSupplier($id)
    {
        $this->db->select('*');
        $this->db->from('tblsuppliers');
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

    public function getProcedureClientDetail($type = "materials")
    {
        $this->db->select('tblprocedure_client_detail.*');
        $this->db->from('tblprocedure_client');
        $this->db->join('tblprocedure_client_detail', 'tblprocedure_client_detail.id_detail = tblprocedure_client.id');
        $this->db->where('tblprocedure_client.type', $type);
        return $this->db->get()->result_array();
    }

    public function getWarehouse()
    {
        $this->db->select('*');
        $this->db->from('tblwarehouse');
        return $this->db->get()->result_array();
    }

    public function rowComboboxClient($id)
    {
        $this->db->select('*');
        $this->db->from('tblcombobox_client');
        $this->db->where('tblcombobox_client.id', $id);
        return $this->db->get()->row_array();
    }

    public function rowClientInfoDetailValue($id)
    {
        $this->db->select('*');
        $this->db->from('tblclient_info_detail_value');
        $this->db->where('tblclient_info_detail_value.id', $id);
        return $this->db->get()->row_array();
    }

    public function getProductGroupInfoByProductId($product_id)
    {
        $this->db->select('*');
        $this->db->from('tblproduct_group_info');
        $this->db->where('tblproduct_group_info.product_id', $product_id);
        return $this->db->get()->result_array();
    }

    public function deleteProductGroupInfo($id_product)
    {
        $this->db->where('tblproduct_group_info.id_product', $id_product);
        return $this->db->delete('tblproduct_group_info');
    }

    public function getClientInfoDetailProduct()
    {
        $this->db->select('*');
        $this->db->from('tblclient_info_detail');
        $this->db->where('tblclient_info_detail.view_modal', 1);
        $this->db->where('tblclient_info_detail.show_on_table', 1);
        return $this->db->get()->result_array();
    }

}

?>