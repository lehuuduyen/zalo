<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Manufactures_model extends App_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function insertProductionsPlan($data)
    {
        $this->db->insert('tbl_productions_plan', $data);
        return $this->db->insert_id();
    }

    public function updateProductionsPlan($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tbl_productions_plan', $data);
    }

    public function insertProductionsPlanItems($data)
    {
        $this->db->insert('tbl_productions_plan_items', $data);
        return $this->db->insert_id();
    }

    public function updateProductionsPlanItems($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tbl_productions_plan_items', $data);
    }

    public function insertProductionsPlanDetails($data)
    {
        $this->db->insert('tbl_productions_plan_details', $data);
        return $this->db->insert_id();
    }

    public function rowProductionsPlan($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_productions_plan');
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

    public function rowProductionsPlanItems($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_productions_plan_items');
        $this->db->where('productions_plan_id', $id);
        return $this->db->get()->row_array();
    }

    public function deleteProductionsPlan($id) {
        $this->db->where('id', $id);
        return $this->db->delete('tbl_productions_plan');
    }

    public function checkExistProductionsPlanByReferenceNo($reference_no)
    {
        $this->db->from('tbl_productions_plan');
        $this->db->where('tbl_productions_plan.reference_no', $reference_no);
        return $this->db->get()->num_rows();
    }

    public function searchProductionsPlan($q, $limit = 50)
    {
        $this->db->select('tbl_productions_plan.id as id, tbl_productions_plan.reference_no as name, DATE_FORMAT(tbl_productions_plan.date, "%d/%m/%Y") as subtext', false);
        $this->db->from('tbl_productions_plan');
        if (!empty($q))
        {
            $this->db->group_start();
            $this->db->like('tbl_productions_plan.reference_no', $q);
            $this->db->group_end();
        }
        $this->db->where('tbl_productions_plan.status', 'approved');
        $this->db->order_by('tbl_productions_plan.date', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function searchProductionsPlanForOrders($q, $limit = 50)
    {
        $this->db->select('tbl_productions_plan.id as id, tbl_productions_plan.reference_no as text, DATE_FORMAT(tbl_productions_plan.date, "%d/%m/%Y") as subtext', false);
        $this->db->from('tbl_productions_plan');
        if (!empty($q))
        {
            $this->db->group_start();
            $this->db->like('tbl_productions_plan.reference_no', $q);
            $this->db->group_end();
        }
        $this->db->where('tbl_productions_plan.status !=', 'un_approved');
        $this->db->where('tbl_productions_plan.productions_orders_id', 0);
        $this->db->order_by('tbl_productions_plan.date', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function rowReferenceProductionsPlanByArrId($arr_id)
    {
        $this->db->simple_query('SET SESSION group_concat_max_len=150000000000');
        $this->db->select('GROUP_CONCAT(tbl_productions_plan.reference_no SEPARATOR ",") as reference_no', false);
        $this->db->from('tbl_productions_plan');
        $this->db->where_in('tbl_productions_plan.id', $arr_id);
        return $this->db->get()->row_array();
    }

    public function checkStatusProductionsPlan($arr_id) {
        $this->db->simple_query('SET SESSION group_concat_max_len=150000000000');
        $this->db->select('GROUP_CONCAT(tbl_productions_plan.reference_no SEPARATOR ",") as reference_no', false);
        $this->db->from('tbl_productions_plan');
        $this->db->where_in('tbl_productions_plan.id', $arr_id);
        $this->db->where('tbl_productions_plan.status !=', 'approved');
        return $this->db->get()->row_array();
    }

    public function insertProductionsCapacity($data)
    {
        $this->db->insert('tbl_productions_capacity', $data);
        return $this->db->insert_id();
    }

    public function insertProductionsCapacityItems($data)
    {
        $this->db->insert('tbl_productions_capacity_items', $data);
        return $this->db->insert_id();
    }

    public function insertBatchProductionsCapacityItemsSub($data)
    {
        $this->db->insert_batch('tbl_productions_capacity_items_sub', $data);
        return $this->db->insert_id();
    }

    public function insertProductionsCapacityItemsSub($data)
    {
        $this->db->insert('tbl_productions_capacity_items_sub', $data);
        return $this->db->insert_id();
    }

    public function rowProductionsCapacity($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_productions_capacity');
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

    public function deleteProductionsCapacity($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('tbl_productions_capacity');
    }

    public function getProductionsCapacityItems($productions_capacity_id)
    {
        $this->db->select('*');
        $this->db->from('tbl_productions_capacity_items');
        $this->db->where('tbl_productions_capacity_items.productions_capacity_id', $productions_capacity_id);
        return $this->db->get()->result_array();
    }

    public function deleteProductionsCapacityItems($productions_capacity_id)
    {
        $this->db->where('productions_capacity_id', $productions_capacity_id);
        return $this->db->delete('tbl_productions_capacity_items');
    }

    public function deleteProductionsCapacityItemsSub($productions_capacity_items_id) {
        $this->db->where('productions_capacity_items_id', $productions_capacity_items_id);
        return $this->db->delete('tbl_productions_capacity_items_sub');
    }

    public function insertProductionsCapacityItemsStages($data)
    {
        $this->db->insert('tbl_productions_capacity_items_stages', $data);
        return $this->db->insert_id();
    }

    public function insertBatchProductionsCapacityItemsStages($data)
    {
        return $this->db->insert_batch('tbl_productions_capacity_items_stages', $data);
    }

    public function deleteProductionsCapacityItemsStages($productions_capacity_items_id) {
        $this->db->where('productions_capacity_items_id', $productions_capacity_items_id);
        return $this->db->delete('tbl_productions_capacity_items_stages');
    }

    public function getProductionsCapacityItemsStages($productions_capacity_items_id) {
        $this->db->select('*');
        $this->db->from('tbl_productions_capacity_items_stages');
        $this->db->where('tbl_productions_capacity_items_stages.productions_capacity_items_id', $productions_capacity_items_id);
        return $this->db->get()->result_array();
    }

    public function getProductionsCapacityItemsSub($productions_capacity_items_id) {
        $this->db->select('tbl_productions_capacity_items_sub.*, tblunits.unit');
        $this->db->from('tbl_productions_capacity_items_sub');
        $this->db->join('tblunits', 'tblunits.unitid = tbl_productions_capacity_items_sub.unit_id', 'left');
        $this->db->where('tbl_productions_capacity_items_sub.productions_capacity_items_id', $productions_capacity_items_id);
        $this->db->where('tbl_productions_capacity_items_sub.type_sub !=', 'element');
        $this->db->where('tbl_productions_capacity_items_sub.parent_id', 0);
        return $this->db->get()->result_array();
    }

    public function updateProductionsCapacity($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tbl_productions_capacity', $data);
    }

    public function getProductionsCapacityItemsForPurchase($productions_capacity_id)
    {
        $this->db->select('tbl_productions_capacity_items_sub.*');
        $this->db->from('tbl_productions_capacity_items');
        $this->db->join('tbl_productions_capacity_items_sub', 'tbl_productions_capacity_items_sub.productions_capacity_items_id = tbl_productions_capacity_items.id', 'inner');
        $this->db->where('tbl_productions_capacity_items.productions_capacity_id', $productions_capacity_id);
        $this->db->where('tbl_productions_capacity_items_sub.type_sub !=', 'element');
        return $this->db->get()->result_array();
    }

    public function insertBatchProductionsCapacityItemsPurchases($data)
    {
        return $this->db->insert_batch('tbl_productions_capacity_items_purchases', $data);
    }

    public function deleteProductionsCapacityPurchases($productions_capacity_id) {
        $this->db->where('productions_capacity_id', $productions_capacity_id);
        return $this->db->delete('tbl_productions_capacity_items_purchases');
    }

    public function getProductionsCapacityItemsPurchases($productions_capacity_id) {
        $this->db->select('tbl_productions_capacity_items_purchases.*, tblunits.unit');
        $this->db->from('tbl_productions_capacity_items_purchases');
        $this->db->join('tblunits', 'tblunits.unitid = tbl_productions_capacity_items_purchases.unit_id', 'left');
        $this->db->where('tbl_productions_capacity_items_purchases.productions_capacity_id', $productions_capacity_id);
        $this->db->where('tbl_productions_capacity_items_purchases.quantity_purchase_sub >', 0);
        return $this->db->get()->result_array();
    }

    public function getProductionsPlanForProductionsOrders($production_plan_id = [])
    {
        $this->db->select('
            tbl_productions_plan_items.product_id,
            tbl_products.code,
            tbl_products.name,
            IF(tbl_products.images IS NOT NULL && tbl_products.images != "", CONCAT("uploads/products/", "", tbl_products.images, ""), "") as images,
            SUM(tbl_productions_plan_items.quantity_total_details) as total_quantity
            ', false);
        $this->db->from('tbl_productions_plan');
        $this->db->join('tbl_productions_plan_items', 'tbl_productions_plan_items.productions_plan_id = tbl_productions_plan.id');
        $this->db->join('tbl_products', 'tbl_products.id = tbl_productions_plan_items.product_id');
        $this->db->where('tbl_productions_plan.status !=', 'un_approved');
        $this->db->where_in('tbl_productions_plan.id', $production_plan_id);
        $this->db->group_by('tbl_productions_plan_items.product_id');
        // print_arrays($this->db->get_compiled_select(), FALSE);
        return $this->db->get()->result_array();
    }

    public function insertProductionsOrders($data) {
        $this->db->insert('tbl_productions_orders', $data);
        return $this->db->insert_id();
    }

    public function insertProductionsOrdersItems($data) {
        $this->db->insert('tbl_productions_orders_items', $data);
        return $this->db->insert_id();
    }

    public function checkStatusProductionsPlanForOrders($arr_id) {
        $this->db->simple_query('SET SESSION group_concat_max_len=150000000000');
        $this->db->select('GROUP_CONCAT(tbl_productions_plan.reference_no SEPARATOR ",") as reference_no', false);
        $this->db->from('tbl_productions_plan');
        $this->db->where_in('tbl_productions_plan.id', $arr_id);
        $this->db->where_not_in('tbl_productions_plan.status', ['approved', 'capacity']);
        $this->db->where('tbl_productions_plan.productions_orders_id !=', 0);
        return $this->db->get()->row_array();
    }

    public function rowProductionsOrdersById($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_productions_orders');
        $this->db->where('tbl_productions_orders.id', $id);
        return $this->db->get()->row_array();
    }

    public function getProductionOrdersItemsAndProducts($production_orders_id)
    {
        $this->db->select('tbl_productions_orders_items.*, tbl_products.images, tbl_products.code as item_code, tbl_products.name as item_name', false);
        $this->db->from('tbl_productions_orders_items');
        $this->db->join('tbl_products', 'tbl_products.id = tbl_productions_orders_items.items_id', 'left');
        $this->db->where('tbl_productions_orders_items.productions_orders_id', $production_orders_id);
        $this->db->where('tbl_productions_orders_items.type_items', 'products');
        return $this->db->get()->result_array();
    }

    public function checkExistProductionsOrdersByReferenceNo($reference_no)
    {
        $this->db->from('tbl_productions_orders');
        $this->db->where('tbl_productions_orders.reference_no', $reference_no);
        return $this->db->get()->num_rows();
    }

    public function deleteProductionsOrdersItems($productions_orders_id)
    {
        $this->db->where('tbl_productions_orders_items.productions_orders_id', $productions_orders_id);
        return $this->db->delete('tbl_productions_orders_items');
    }

    public function updateProductionsOrders($id, $data = [])
    {
        $this->db->where('id', $id);
        return $this->db->update('tbl_productions_orders', $data);
    }

    public function insertBatchProductionsOrdersItems($data = [])
    {
        return $this->db->insert_batch('tbl_productions_orders_items', $data);
    }

    public function deleteProductionsOrders($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('tbl_productions_orders');
    }

    public function updateProductionsPlanByOrders($productions_orders_id, $data = [])
    {
        $this->db->where('productions_orders_id', $productions_orders_id);
        return $this->db->update('tbl_productions_plan', $data);
    }

    public function getProductionsOrdersItems($productions_orders_id)
    {
        $this->db->select('*');
        $this->db->from('tbl_productions_orders_items');
        $this->db->where('tbl_productions_orders_items.productions_orders_id', $productions_orders_id);
        return $this->db->get()->result_array();
    }

    public function insertBatchProductionOrdersItemsSub($data = [])
    {
        return $this->db->insert_batch('tbl_productions_orders_items_sub', $data);
    }

    public function insertBatchProductionOrdersItemsStages($data = [])
    {
        return $this->db->insert_batch('tbl_productions_orders_items_stages', $data);
    }

    public function deleteProductionsOrdersItemsSub($productions_orders_id)
    {
        $this->db->where('productions_orders_id', $productions_orders_id);
        return $this->db->delete('tbl_productions_orders_items_sub');
    }

    public function deleteProductionsOrdersItemsStages($productions_orders_id)
    {
        $this->db->where('productions_orders_id', $productions_orders_id);
        return $this->db->delete('tbl_productions_orders_items_stages');
    }

    public function getProductionsOrdersItemsSubTotalView($productions_orders_id)
    {
        $this->db->select('
            tbl_productions_orders_items_sub.type as type,
            tbl_productions_orders_items_sub.item_id as item_id,
            tbl_productions_orders_items_sub.unit_id as unit_id,
            tblunits.unit as unit,
            tbl_productions_orders_items_sub.item_code as item_code,
            tbl_productions_orders_items_sub.item_name as item_name,
            SUM(tbl_productions_orders_items_sub.quantity) as total_quantity
            ', false);
        $this->db->from('tbl_productions_orders_items_sub');
        $this->db->join('tblunits', 'tblunits.unitid = tbl_productions_orders_items_sub.unit_id', 'left');
        $this->db->where('tbl_productions_orders_items_sub.productions_orders_id', $productions_orders_id);
        $this->db->where('tbl_productions_orders_items_sub.type !=', 'element');
        $this->db->group_by('tbl_productions_orders_items_sub.type, tbl_productions_orders_items_sub.item_id, tbl_productions_orders_items_sub.unit_id');
        return $this->db->get()->result_array();
    }

    public function getProductionsOrdersItemsStagesView($productions_orders_items_id)
    {
        $this->db->select('
            tbl_productions_orders_items_stages.*,
            tbl_stages.name as stage_name,
            tbl_machines.name as machine_name
            ', false);
        $this->db->from('tbl_productions_orders_items_stages');
        $this->db->join('tbl_stages', 'tbl_stages.id = tbl_productions_orders_items_stages.stage_id', 'left');
        $this->db->join('tbl_machines', 'tbl_machines.id = tbl_productions_orders_items_stages.machines', 'left');
        $this->db->where('tbl_productions_orders_items_stages.productions_orders_items_id', $productions_orders_items_id);
        $this->db->where('tbl_productions_orders_items_stages.productions_orders_items_sub_id', 0);
        $this->db->order_by('tbl_productions_orders_items_stages.number ASC');
        return $this->db->get()->result_array();
    }

    public function getProductionsORdersItemsForCreated($productions_orders_id)
    {
        $this->db->select('tbl_productions_orders_items.*');
        $this->db->from('tbl_productions_orders_items');
        $this->db->where('tbl_productions_orders_items.productions_orders_id', $productions_orders_id);
        return $this->db->get()->result_array();
    }

    public function insertProductionsOrdersDetails($data)
    {
        $this->db->insert('tbl_productions_orders_details', $data);
        return $this->db->insert_id();
    }

    public function getProductionsOrdersItemsSubForDetail($productions_orders_items_id)
    {
        $this->db->select('tbl_productions_orders_items_sub.*');
        $this->db->from('tbl_productions_orders_items_sub');
        $this->db->where('tbl_productions_orders_items_sub.productions_orders_items_id', $productions_orders_items_id);
        $this->db->where('tbl_productions_orders_items_sub.type', 'semi_products');
        return $this->db->get()->result_array();
    }

    public function getProductionsCapacityItemsSubBySemiProduct($productions_capacity_id)
    {
        $this->db->select('
            tbl_productions_capacity_items_sub.id,
            tbl_productions_capacity_items_sub.id_sub,
            tbl_productions_capacity_items_sub.quantity_plan_sub,
            tbl_productions_capacity_items_sub.productions_capacity_items_id
            ');
        $this->db->from('tbl_productions_capacity_items');
        $this->db->join('tbl_productions_capacity_items_sub', 'tbl_productions_capacity_items.id = tbl_productions_capacity_items_sub.productions_capacity_items_id');
        $this->db->where('tbl_productions_capacity_items.productions_capacity_id', $productions_capacity_id);
        $this->db->where('tbl_productions_capacity_items_sub.type_sub', 'semi_products');
        return $this->db->get()->result_array();
    }

    /**
     * [totalProposedOfPurchases total proposed]
     * @return [type] [description]
     */
    public function totalProposedOfPurchases($product_id, $type)
    {
        $this->db->select('
            tblpurchases_items.type,
            tblpurchases_items.product_id,
            SUM(tblpurchases_items.quantity_net) as total_proposed
            ', false);
        $this->db->from('tblpurchases');
        $this->db->join('tblpurchases_items', 'tblpurchases.id = tblpurchases_items.purchases_id');
        $this->db->where('tblpurchases_items.product_id', $product_id);
        $this->db->where('tblpurchases_items.type', $type);
        $this->db->where('(
            SELECT count(tblpurchase_order.id)
            FROM tblpurchase_order
            WHERE tblpurchase_order.id_purchases = tblpurchases.id
        ) = 0');
        return $this->db->get()->row_array();
    }

    public function totalOrderingOfPurchaseOrderNotImportAndNotCancel($product_id, $type)
    {
        //lấy đơn hàng chưa hủy và chưa có nhập hàng và không tạo từ yêu cầu mua hàng từ kế hoạch hoạt định
        $this->db->select("
            SUM(tblpurchase_order_items.quantity_suppliers) as total_ordering
            ", false);
        $this->db->from('tblpurchase_order');
        $this->db->join('tblpurchase_order_items', 'tblpurchase_order.id = tblpurchase_order_items.id_purchase_order');
        $this->db->where('tblpurchase_order_items.product_id', $product_id);
        $this->db->where('tblpurchase_order_items.type', $type);
        $this->db->where('tblpurchase_order.cancel', 0);
        $this->db->where('tblpurchase_order.type_plan !=', 1);
        $this->db->where('(
            SELECT count(tblimport.id)
            FROM tblimport
            WHERE tblimport.id_order = tblpurchase_order.id
        ) = 0');
        return $this->db->get()->row_array();
    }

    public function totalOrderingOfPurchaseOrderImportAndNotCancel($product_id, $type)
    {
        //lấy đơn hàng chưa hủy và có nhập hàng một phần
        $this->db->select("
            GROUP_CONCAT(tblpurchase_order.id SEPARATOR ',') as order_id,
            SUM(tblpurchase_order_items.quantity_suppliers) as total_ordering_had_import
            ", false);
        $this->db->from('tblpurchase_order');
        $this->db->join('tblpurchase_order_items', 'tblpurchase_order.id = tblpurchase_order_items.id_purchase_order');
        $this->db->where('tblpurchase_order_items.product_id', $product_id);
        $this->db->where('tblpurchase_order_items.type', $type);
        $this->db->where('tblpurchase_order.cancel', 0);
        $this->db->where('tblpurchase_order.type_plan !=', 1);
        $this->db->where('(
            SELECT count(tblimport.id)
            FROM tblimport
            WHERE tblimport.id_order = tblpurchase_order.id
        ) > 0');
        // if ($product_id == 3) {
        //     print_arrays($this->db->get_compiled_select(), FALSE);
        // }
        return $this->db->get()->row_array();
    }

    public function totalImportOfImportOrderId($order_id = [], $product_id, $type)
    {
        $this->db->select("
            SUM(tblimport_items.quantity_net) as total_import
            ", false);
        $this->db->from('tblimport');
        $this->db->join('tblimport_items', 'tblimport.id = tblimport_items.id_import');
        $this->db->where('tblimport_items.type', $type);
        $this->db->where('tblimport_items.product_id', $product_id);
        $this->db->where_in('tblimport.id_order', $order_id);
        $this->db->where('tblimport.warehouseman_id >', 0);
        return $this->db->get()->row_array();
    }

    public function totalImportNotAgreeWarehouseNotPlan($order_id = [], $product_id, $type) {
        //lấy so luong nhập hàng chưa duyệt kho và không phải là kế hoạch sản xuất
        $this->db->select("
            SUM(tblimport_items.quantity_net) as total_import
            ", false);
        $this->db->from('tblimport');
        $this->db->join('tblimport_items', 'tblimport.id = tblimport_items.id_import');
        $this->db->where('tblimport_items.type', $type);
        $this->db->where('tblimport_items.product_id', $product_id);
        if (!empty($order_id))
        {
            $this->db->where_not_in('tblimport.id_order', $order_id);
        }
        $this->db->where('tblimport.warehouseman_id', 0);
        $this->db->where('tblimport.type_plan', 0);
        return $this->db->get()->row_array();
    }

    //total capacity
    public function totalCapacityNotPurchase($item_id, $type)
    {
        $this->db->select("
            SUM(tbl_productions_capacity_items_purchases.quantity_warehouse_reality) as total_warehouse_reality
            ", false);
        $this->db->from('tbl_productions_capacity');
        $this->db->join('tbl_productions_capacity_items_purchases', 'tbl_productions_capacity_items_purchases.productions_capacity_id = tbl_productions_capacity.id', 'inner');
        $this->db->where('tbl_productions_capacity_items_purchases.id_sub', $item_id);
        $this->db->where('tbl_productions_capacity_items_purchases.type_sub', $type);
        $this->db->where('tbl_productions_capacity.purchases_id', 0);
        return $this->db->get()->row_array();
    }

    //purchase
    public function totalProposedOfPurchasesNotOrder($product_id, $type)
    {
        $this->db->select('
            SUM(tblpurchases_items.quantity_net) as total_proposed
            ', false);
        $this->db->from('tblpurchases');
        $this->db->join('tblpurchases_items', 'tblpurchases.id = tblpurchases_items.purchases_id');
        $this->db->where('tblpurchases_items.product_id', $product_id);
        $this->db->where('tblpurchases_items.type', $type);
        $this->db->where('tblpurchases.id_order', 0);
        $this->db->where('tblpurchases.status !=', 4);
        $this->db->where('(
            SELECT count(tblpurchase_order.id)
            FROM tblpurchase_order
            WHERE tblpurchase_order.id_purchases = tblpurchases.id
        ) = 0');
        $this->db->where('(
            SELECT count(tbl_productions_capacity.id)
            FROM tbl_productions_capacity
            WHERE tbl_productions_capacity.purchases_id = tblpurchases.id
        ) = 0');
        return $this->db->get()->row_array();
    }

    public function totalProposedOfPurchasesApartOrder($product_id, $type)
    {
        $this->db->select("
            GROUP_CONCAT(tblpurchases.id SEPARATOR ',') as purchase_id,
            SUM(tblpurchases_items.quantity_net) as total_proposed
            ", false);
        $this->db->from('tblpurchases');
        $this->db->join('tblpurchases_items', 'tblpurchases.id = tblpurchases_items.purchases_id');
        $this->db->where('tblpurchases_items.product_id', $product_id);
        $this->db->where('tblpurchases_items.type', $type);
        $this->db->where('tblpurchases.id_order', 0);
        $this->db->where('tblpurchases.status !=', 4);
        $this->db->where('(
            SELECT count(tblpurchase_order.id)
            FROM tblpurchase_order
            WHERE tblpurchase_order.id_purchases = tblpurchases.id
        ) > 0');
        $this->db->where('(
            SELECT count(tbl_productions_capacity.id)
            FROM tbl_productions_capacity
            WHERE tbl_productions_capacity.purchases_id = tblpurchases.id
        ) = 0');
        // print_arrays($this->db->get_compiled_select(), FALSE);
        return $this->db->get()->row_array();
    }

    public function totalPurchaseOrderByPurchaseId($purchase_id = [], $product_id, $type)
    {
        $this->db->select('
            SUM(tblpurchase_order_items.quantity_suppliers) as total_ordering
            ', false);
        $this->db->from('tblpurchase_order');
        $this->db->join('tblpurchase_order_items', 'tblpurchase_order_items.id_purchase_order = tblpurchase_order.id');
        $this->db->where('tblpurchase_order_items.product_id', $product_id);
        $this->db->where('tblpurchase_order_items.type', $type);
        $this->db->where_in('tblpurchase_order.id_purchases', $purchase_id);
        return $this->db->get()->row_array();
    }
    //

    public function getProductionsOrdersItemsSubBySemiProduct($productions_orders_id)
    {
        $this->db->select('
            tbl_productions_orders_items_sub.id,
            tbl_productions_orders_items_sub.item_id,
            tbl_productions_orders_items_sub.type,
            tbl_productions_orders_items_sub.quantity,
            tbl_productions_orders_items_sub.productions_orders_items_id
            ', false);
        $this->db->from('tbl_productions_orders_items');
        $this->db->join('tbl_productions_orders_items_sub', 'tbl_productions_orders_items_sub.productions_orders_items_id = tbl_productions_orders_items.id');
        $this->db->where('tbl_productions_orders_items.type_items', 'products');
        $this->db->where('tbl_productions_orders_items_sub.type', 'semi_products');
        $this->db->where('tbl_productions_orders_items.productions_orders_id', $productions_orders_id);
        return $this->db->get()->result_array();
    }

    public function rowProductionsOrdersByDetail($id)
    {
        $this->db
            ->select("
                tbl_productions_orders_details.id as id,
                tbl_productions_orders_details.productions_orders_item_id as productions_orders_item_id,
                tbl_productions_orders.reference_no as reference_no_order,
                tbl_productions_orders_details.reference_no as reference_no,
                tbl_productions_orders_details.deadline as deadline,
                tbldepartments.name as department_name,
                tbl_productions_orders_items.items_code as items_code,
                tbl_productions_orders_items.items_name as items_name,
                tbl_productions_orders_items.quantity as quantity,
                tbl_productions_orders_items.type_items as type_items,
                tblunits.unit as unit_name,
                0 as quantity_finished,
                0 as precent_finished,
                tbl_productions_orders_details.status as status
                ", false)
            ->from('tbl_productions_orders_details')
            ->join('tbl_productions_orders_items', 'tbl_productions_orders_items.id = tbl_productions_orders_details.productions_orders_item_id', 'left')
            ->join('tbl_productions_orders', 'tbl_productions_orders.id = tbl_productions_orders_items.productions_orders_id', 'left')
            ->join('tbl_products', 'tbl_products.id = tbl_productions_orders_items.items_id', 'left')
            ->join('tblunits', 'tblunits.unitid = tbl_products.unit_id', 'left')
            ->join('tbldepartments', 'tbldepartments.departmentid = tbl_productions_orders_details.departments', 'left');

        $this->db->where('tbl_productions_orders_details.id', $id);
        return $this->db->get()->row_array();
    }

    public function getProductionsOrdersItemsSubByDetail($productions_orders_items_id)
    {
        $this->db->select('
            tbl_productions_orders_items_sub.id as id,
            tbl_productions_orders_items_sub.type as type,
            tbl_productions_orders_items_sub.item_id as item_id,
            tbl_productions_orders_items_sub.item_code as item_code,
            tbl_productions_orders_items_sub.item_name as item_name,
            tblunits.unit as unit_name,
            tbl_productions_orders_items_sub.quantity as quantity,
            ', false);
        $this->db->from('tbl_productions_orders_items_sub');
        $this->db->join('tblunits', 'tblunits.unitid = tbl_productions_orders_items_sub.unit_id', 'left');
        $this->db->where('tbl_productions_orders_items_sub.type', 'semi_products');
        $this->db->where('tbl_productions_orders_items_sub.productions_orders_items_id', $productions_orders_items_id);
        return $this->db->get()->result_array();
    }

    //
    public function deleteProductionsOdersDetail($productions_orders_id)
    {
        $this->db->where('productions_orders_id', $productions_orders_id);
        return $this->db->delete('tbl_productions_orders_details');
    }

    public function getProductionsOrdersItemsStagesOfProduct($productions_orders_items_id)
    {
        $this->db->select('
            tbl_productions_orders_items_stages.*,
            tbl_stages.name as stage_name,
            tbl_machines.name as machine_name
            ', false);
        $this->db->from('tbl_productions_orders_items_stages');
        $this->db->join('tbl_stages', 'tbl_stages.id = tbl_productions_orders_items_stages.stage_id', 'left');
        $this->db->join('tbl_machines', 'tbl_machines.id = tbl_productions_orders_items_stages.machines', 'left');
        $this->db->where('tbl_productions_orders_items_stages.productions_orders_items_id', $productions_orders_items_id);
        $this->db->where('tbl_productions_orders_items_stages.productions_orders_items_sub_id', 0);
        return $this->db->get()->result_array();
    }

    public function getProductionsOrdersItemsStagesBySubId($productions_orders_items_id, $productions_orders_items_sub_id)
    {
        $this->db->select('
            tbl_productions_orders_items_stages.*,
            tbl_stages.name as stage_name,
            tbl_machines.name as machine_name
            ', false);
        $this->db->from('tbl_productions_orders_items_stages');
        $this->db->join('tbl_stages', 'tbl_stages.id = tbl_productions_orders_items_stages.stage_id', 'left');
        $this->db->join('tbl_machines', 'tbl_machines.id = tbl_productions_orders_items_stages.machines', 'left');
        $this->db->where('tbl_productions_orders_items_stages.productions_orders_items_id', $productions_orders_items_id);
        $this->db->where('tbl_productions_orders_items_stages.productions_orders_items_sub_id', $productions_orders_items_sub_id);
        return $this->db->get()->result_array();
    }

    public function getMaterialExport($productions_orders_items_id)
    {
        $this->db
            ->select("
                tbl_productions_orders_items_sub.item_id,
                tbl_productions_orders_items_sub.unit_id,
                tbl_productions_orders_items_sub.item_code as item_code,
                tbl_productions_orders_items_sub.item_name as item_name,
                tblunits.unit as unit_name,
            ", false)
            ->from('tbl_productions_orders_items_sub')
            ->join('tblunits', 'tblunits.unitid = tbl_productions_orders_items_sub.unit_id', 'left');

        $this->db->group_by('tbl_productions_orders_items_sub.item_id, tbl_productions_orders_items_sub.unit_id');
        $this->db->where('tbl_productions_orders_items_sub.productions_orders_items_id', $productions_orders_items_id);
        return $this->db->get()->result_array();
    }

    public function getProductionsCapacityItemsPurchasesCal($productions_capacity_id)
    {
        $this->db->select('tbl_productions_capacity_items_purchases.*');
        $this->db->from('tbl_productions_capacity_items_purchases');
        $this->db->where('tbl_productions_capacity_items_purchases.productions_capacity_id', $productions_capacity_id);
        $this->db->where('(tbl_productions_capacity_items_purchases.type_sub = "materials" OR tbl_productions_capacity_items_purchases.type_sub = "semi_products_outside")');
        return $this->db->get()->result_array();
    }

    public function getProductionsCapacityItemsSubForPurchase($productions_capacity_id)
    {
        $this->db->select('
            tbl_productions_capacity_items_sub.type_sub,
            tbl_productions_capacity_items_sub.id_sub,
            SUM(tbl_productions_capacity_items_sub.quantity_plan_sub/tbl_productions_capacity_items_sub.quantity_exchange) as quantity_plan
            ', false);
        $this->db->from('tbl_productions_capacity_items');
        $this->db->join('tbl_productions_capacity_items_sub', 'tbl_productions_capacity_items_sub.productions_capacity_items_id = tbl_productions_capacity_items.id');
        $this->db->where('tbl_productions_capacity_items.productions_capacity_id', $productions_capacity_id);
        $this->db->where('(tbl_productions_capacity_items_sub.type_sub = "semi_products_outside" OR tbl_productions_capacity_items_sub.type_sub = "materials")');
        $this->db->group_by('tbl_productions_capacity_items_sub.type_sub, tbl_productions_capacity_items_sub.id_sub');
        return $this->db->get()->result_array();
    }

    public function updateBatchProductionsCapacityItemsPurchase($data = [])
    {
        return $this->db->update_batch('tbl_productions_capacity_items_purchases', $data, 'id');
    }

    public function getMaterialProductionsForExportSupplies($productions_orders_items_id)
    {
        $this->db->select('
            tbl_productions_orders_items_sub.item_id as item_id,
            tbl_productions_orders_items_sub.unit_id as unit_id,
            tbl_productions_orders_items_sub.type as type_item,
            tbl_productions_orders_items_sub.unit_parent_id as unit_parent_id,
            tbl_productions_orders_items_sub.item_code as item_code,
            tbl_productions_orders_items_sub.item_name as item_name,
            tblunits.unit as unit_name,
            SUM(tbl_productions_orders_items_sub.quantity) as quantity,
            tbl_productions_orders_items_sub.quantity_exchange as quantity_exchange,
            ', false);
        $this->db->from('tbl_productions_orders_items_sub');
        $this->db->join('tblunits', 'tblunits.unitid = tbl_productions_orders_items_sub.unit_id', 'left');
        $this->db->where("(tbl_productions_orders_items_sub.type = 'materials' OR  tbl_productions_orders_items_sub.type = 'semi_products_outside')");
        $this->db->where('tbl_productions_orders_items_sub.productions_orders_items_id', $productions_orders_items_id);
        $this->db->group_by('tbl_productions_orders_items_sub.item_id, tbl_productions_orders_items_sub.unit_id, tbl_productions_orders_items_sub.type');

        return $this->db->get()->result_array();
    }

    public function insertSuggestExporting($data)
    {
        $this->db->insert('tbl_suggest_exporting', $data);
        return $this->db->insert_id();
    }

    public function insertSuggestExportingItems($data = [])
    {
        $this->db->insert('tbl_suggest_exporting', $data);
        return $this->db->insert_id();
    }

    public function insertBatchSuggestExportingItems($data = [])
    {
        return $this->db->insert_batch('tbl_suggest_exporting_items', $data);
    }

    public function rowSuggestExporting($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_suggest_exporting');
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

    public function getSuggestExportingItemsView($suggest_exporting_id)
    {
        $this->db->select('tbl_suggest_exporting_items.*, tblunits.unit as unit_name');
        $this->db->from('tbl_suggest_exporting_items');
        $this->db->join('tblunits', 'tblunits.unitid = tbl_suggest_exporting_items.unit_id', 'left');
        $this->db->where('tbl_suggest_exporting_items.suggest_exporting_id', $suggest_exporting_id);
        return $this->db->get()->result_array();
    }

    public function getSuggestExportingItems($suggest_exporting_id)
    {
        $this->db->select('tbl_suggest_exporting_items.*');
        $this->db->from('tbl_suggest_exporting_items');
        $this->db->where('tbl_suggest_exporting_items.suggest_exporting_id', $suggest_exporting_id);
        return $this->db->get()->result_array();
    }

    public function deleteSuggestExportingById($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('tbl_suggest_exporting');
    }

    public function deleteSuggestExportingItems($suggest_exporting_id)
    {
        $this->db->where('suggest_exporting_id', $suggest_exporting_id);
        return $this->db->delete('tbl_suggest_exporting_items');
    }

    public function deleteSuggestExportingItemsById($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('tbl_suggest_exporting_items');
    }

    public function updateSuggestExportingById($id, $data = [])
    {
        $this->db->where('id', $id);
        return $this->db->update('tbl_suggest_exporting', $data);
    }

    public function updateSuggestExportingItemsById($id, $data = [])
    {
        $this->db->where('id', $id);
        return $this->db->update('tbl_suggest_exporting_items', $data);
    }

    public function updateBatchSuggestExportingItemsById($data = [])
    {
        return $this->db->update_batch('tbl_suggest_exporting_items', $data, 'id');
    }

    public function getSuggestExportingItemsByNotArrId($arr_not_id, $suggest_exporting_id)
    {
        $this->db->select('id');
        $this->db->from('tbl_suggest_exporting_items');
        $this->db->where('tbl_suggest_exporting_items.suggest_exporting_id', $suggest_exporting_id);
        if (!empty($arr_not_id)) {
            $this->db->where_not_in('tbl_suggest_exporting_items.id', $arr_not_id);
        }
        return $this->db->get()->result_array();
    }

    public function checkCoditionDeleteDetail($id)
    {
        $this->db->from('tbl_productions_orders_details');
        $this->db->join('tbl_suggest_exporting', 'tbl_productions_orders_details.id = tbl_suggest_exporting.productions_orders_details_id');
        $this->db->where('tbl_productions_orders_details.productions_orders_id', $id);
        $q = $this->db->get()->num_rows();
        if ($q) {
            return lang('tnh_created_suggest_not_remove');
        }
        return false;
    }

    public function rowProductionsOrdersDetais($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_productions_orders_details');
        $this->db->where('tbl_productions_orders_details.id', $id);
        return $this->db->get()->row_array();
    }

    public function checkExistSuggestExportingReferenceNo($reference_no)
    {
        $this->db->from('tbl_suggest_exporting');
        $this->db->where('tbl_suggest_exporting.reference_no', $reference_no);
        return $this->db->get()->num_rows();
    }

    public function getAllStaff()
    {
        $this->db->select('tblstaff.staffid, tblstaff.lastname, tblstaff.firstname, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) as fullname', false);
        $this->db->from('tblstaff');
        return $this->db->get()->result_array();
    }
}