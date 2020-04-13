<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Stock_model extends App_Model
{
	public function __construct()
    {
        parent::__construct();
    }

    public function searchProductionsOrdersDetailsForStock($term, $limit)
    {
    	$this->db->select('tbl_productions_orders_details.id as id, tbl_productions_orders_details.reference_no as text', false);
        $this->db->from('tbl_productions_orders_details');
        if (!empty($term))
        {
            $this->db->group_start();
            $this->db->like('tbl_productions_orders_details.reference_no', $term);
            $this->db->group_end();
        }
        $this->db->order_by('tbl_productions_orders_details.reference_no', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function searchMaterialProductionsOrders($term, $limit, $productions_orders_detail_id)
    {
    	$this->db->select('
    		tbl_productions_orders_items_sub.unit_id as unit_id,
    		tbl_productions_orders_items_sub.unit_parent_id as unit_parent_id,
    		tbl_productions_orders_items_sub.item_id as item_id,
    		CONCAT("materials__", tbl_productions_orders_items_sub.item_id) as id,
    		CONCAT(tbl_productions_orders_items_sub.item_code, "(", tblunits.unit,")") as text,
    		tblunits.unit as unit_name,
    		tbl_productions_orders_items_sub.item_name as item_name,
    		tbl_productions_orders_items_sub.quantity_exchange as number_exchange,
    		', false);
        $this->db->from('tbl_productions_orders_details');
        $this->db->join('tbl_productions_orders_items_sub', 'tbl_productions_orders_items_sub.productions_orders_items_id = tbl_productions_orders_details.productions_orders_item_id');
        $this->db->join('tblunits', 'tblunits.unitid = tbl_productions_orders_items_sub.unit_id','left');
        if (!empty($term))
        {
            $this->db->group_start();
            $this->db->like('tbl_productions_orders_items_sub.item_code', $term);
            $this->db->or_like('tbl_productions_orders_items_sub.item_name', $term);
            $this->db->group_end();
        }
        $this->db->where('tbl_productions_orders_details.id', $productions_orders_detail_id);
        $this->db->where('tbl_productions_orders_items_sub.type', 'materials');
        $this->db->group_by('tbl_productions_orders_items_sub.item_id, tbl_productions_orders_items_sub.unit_id');
        $this->db->limit($limit);
        // print_arrays($this->db->get_compiled_select(), FALSE);
        return $this->db->get()->result_array();
    }

    public function searchSemiProductProductionsOrders($term, $limit, $productions_orders_detail_id)
    {
    	$this->db->select('
    		tbl_productions_orders_items_sub.unit_id as unit_id,
    		tbl_productions_orders_items_sub.unit_parent_id as unit_parent_id,
    		tbl_productions_orders_items_sub.item_id as item_id,
    		CONCAT("semi_products_outside__", tbl_productions_orders_items_sub.item_id) as id,
    		CONCAT(tbl_productions_orders_items_sub.item_code, "(", tblunits.unit,")") as text,
    		tblunits.unit as unit_name,
    		tbl_productions_orders_items_sub.item_name as item_name,
    		tbl_productions_orders_items_sub.quantity_exchange as number_exchange,
    		', false);
        $this->db->from('tbl_productions_orders_details');
        $this->db->join('tbl_productions_orders_items_sub', 'tbl_productions_orders_items_sub.productions_orders_items_id = tbl_productions_orders_details.productions_orders_item_id');
        $this->db->join('tblunits', 'tblunits.unitid = tbl_productions_orders_items_sub.unit_id','left');
        if (!empty($term))
        {
            $this->db->group_start();
            $this->db->like('tbl_productions_orders_items_sub.item_code', $term);
            $this->db->or_like('tbl_productions_orders_items_sub.item_name', $term);
            $this->db->group_end();
        }
        $this->db->where('tbl_productions_orders_details.id', $productions_orders_detail_id);
        $this->db->where('tbl_productions_orders_items_sub.type', 'semi_products_outside');
        $this->db->group_by('tbl_productions_orders_items_sub.item_id, tbl_productions_orders_items_sub.unit_id');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function checkExistSuggestExportingReferenceStock($reference_no)
    {
        $this->db->from('tbl_suggest_exporting');
        $this->db->where('tbl_suggest_exporting.reference_stock', $reference_no);
        return $this->db->get()->num_rows();
    }

    public function getSuggestExportingItemsForStock($suggest_exporting_id)
    {
        $this->db->select('tbl_suggest_exporting_items.*, tblunits.unit as unit_name');
        $this->db->from('tbl_suggest_exporting_items');
        $this->db->join('tblunits', 'tblunits.unitid = tbl_suggest_exporting_items.unit_id', 'left');
        $this->db->where('tbl_suggest_exporting_items.suggest_exporting_id', $suggest_exporting_id);
        return $this->db->get()->result_array();
    }

    public function rowSuggestExportingItems($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_suggest_exporting_items');
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

    public function getWarehouses()
    {
        $this->db->select('*');
        $this->db->from('tblwarehouse');
        return $this->db->get()->result_array();
    }

    public function getWarehouseItemsByItemIdAndTypeAndWarehouse($id_items, $type_items, $warehouse_id)
    {
        $this->db->select('tblwarehouse_items.*');
        $this->db->from('tblwarehouse_items');
        $this->db->where('tblwarehouse_items.id_items', $id_items);
        $type_items = ($type_items == "materials") ? 'nvl' : 'product';
        $this->db->where('tblwarehouse_items.type_items', $type_items);
        $this->db->where('tblwarehouse_items.warehouse_id', $warehouse_id);
        return $this->db->get()->result_array();
    }

    public function rowWarehouse($id)
    {
        $this->db->select('*');
        $this->db->from('tblwarehouse');
        $this->db->where('tblwarehouse.id', $id);
        return $this->db->get()->row_array();
    }

    public function searchSemiProductsOutside($q, $limit = 50)
    {
        $this->db->select('CONCAT("semi_products_outside__", tbl_products.id) as id, tbl_products.code as text, tbl_products.name as name, tbl_products.unit_id as unit_id', false);
        $this->db->from('tbl_products');
        if (!empty($q))
        {
            $this->db->group_start();
            $this->db->like('tbl_products.code', $q);
            $this->db->or_like('tbl_products.name', $q);
            $this->db->group_end();
        }
        $this->db->where('type_products', 'semi_products_outside');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function searchMaterials($q, $limit = 50)
    {
        $this->db->select('CONCAT("materials__", tbl_materials.id) as id, tbl_materials.code as text, tbl_materials.name as name, tbl_materials.unit_id as unit_id', false);
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
}