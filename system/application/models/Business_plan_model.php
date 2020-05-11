<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Business_plan_model extends App_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function insertBusinessPlan($data)
    {
    	$this->db->insert('tbl_business_plan', $data);
    	return $this->db->insert_id();
    }

    public function updateBusinessPlan($id, $data)
    {
    	$this->db->where('id', $id);
    	return $this->db->update('tbl_business_plan', $data);
    }

    public function deleteBusinessPlan($id)
    {
    	$this->db->where('id', $id);
    	return $this->db->delete('tbl_business_plan');
    }

    public function insertBusinessPlanItems($data)
    {
    	$this->db->insert('tbl_business_plan_items', $data);
    	return $this->db->insert_id();
    }

    public function updateBusinessPlanItems($id, $data)
    {
    	$this->db->where('id', $id);
    	return $this->db->update('tbl_business_plan_items', $data);
    }

    public function insertBusinessPlanItemsDate($data)
    {
    	$this->db->insert('tbl_business_plan_items_date', $data);
    	return $this->db->insert_id();
    }

    public function deleteBusinessPlanItemsDateBusinessPlanItemsId($business_plan_items_id)
    {
    	$this->db->where('business_plan_items_id', $business_plan_items_id);
    	return $this->db->delete('tbl_business_plan_items_date');
    }

    public function insertBatchBusinessPlanItemsDate($data)
    {
    	return $this->db->insert_batch('tbl_business_plan_items_date', $data);
    }

    public function rowBusinessPlanById($id)
    {
    	$this->db->select('*');
    	$this->db->from('tbl_business_plan');
    	$this->db->where('tbl_business_plan.id', $id);
    	return $this->db->get()->row_array();
    }

    public function getBusinessPlanItemsByBusinessPlanId($business_plan_id)
    {
    	$this->db->select('tbl_business_plan_items.*, tbl_products.images as images');
    	$this->db->from('tbl_business_plan_items');
    	$this->db->join('tbl_products', 'tbl_products.id = tbl_business_plan_items.items_id AND tbl_business_plan_items.type_items = "products"', 'left');
    	$this->db->where('tbl_business_plan_items.business_plan_id', $business_plan_id);
    	return $this->db->get()->result_array();
    }

    public function getBusinessPlanItemsDateByBusinessPlanItemsId($business_plan_items_id)
    {
    	$this->db->select('*');
    	$this->db->from('tbl_business_plan_items_date');
    	$this->db->where('business_plan_items_id', $business_plan_items_id);
    	return $this->db->get()->result_array();
    }

    public function getBusinessPlanItemsByNotArrId($arr_id, $business_plan_id) {
    	$this->db->select('*');
    	$this->db->from('tbl_business_plan_items');
        if (!empty($arr_id))
        {
            $this->db->where_not_in('tbl_business_plan_items.id', $arr_id);
        }
    	$this->db->where('tbl_business_plan_items.business_plan_id', $business_plan_id);
    	return $this->db->get()->result_array();
    }

    public function getBusinessPlanItems($business_plan_id) {
    	$this->db->select('tbl_business_plan_items.*');
    	$this->db->from('tbl_business_plan_items');
    	$this->db->where('tbl_business_plan_items.business_plan_id', $business_plan_id);
    	return $this->db->get()->result_array();
    }

    public function deleteBusinessPlanItems($id)
    {
    	$this->db->where('id', $id);
    	return $this->db->delete('tbl_business_plan_items');
    }

    public function updateBusinessPlanByProductionPlan($productions_plan_id, $data)
    {
        $this->db->where('productions_plan_id', $productions_plan_id);
        return $this->db->update('tbl_business_plan', $data);
    }
}