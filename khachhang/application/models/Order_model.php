<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Order_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getOrderTab4($data)
    {
        $sql = "
        SELECT shop.* ,tblorders_shop.code_orders as code_order,tblorders_shop.city as city,declared_region.name_region FROM `tbl_create_order` as shop
        LEFT JOIN tbldeclared_region as declared_region ON declared_region.id= shop.region_id
        LEFT JOIN tblorders_shop  ON tblorders_shop.id= shop.orders_shop_id
        WHERE shop.value >= 0 ";
//WHERE id IN (33, 34, 45)
        ($data->code_order !="")?$sql.="AND shop.soc LIKE '%$data->code_order%'":"";
        ($data->code_request !="")?$sql.="AND shop.required_code LIKE '%$data->code_request%'":"";
        ($data->code_request !="")?$sql.="OR shop.phone LIKE '%$data->code_request%'":"";
        ($data->date_form !="")?$sql.="AND shop.created BETWEEN '$data->date_form 00:00:00' AND '$data->date_to 23:59:59'":"";
        $sql.="AND shop.dvvc IS NULL ";
        ($data->city !="")?$sql.="AND shop.province LIKE '%$data->city%'":"";
        ($data->district !="")?$sql.="AND shop.district LIKE '%$data->district%'":"";
        ($data->customer !="")?$sql.="AND shop.`customer_id` = '$data->customer'":"";
        $sql.="AND shop.`status_cancel` = 0";
        $sql.=" ORDER BY shop.created DESC";

        $query = $this->db->query($sql)->result();
        return $query;
    }
    public function get($id = '')
    {
        $this->db->select('tbladjusted.*,wareid.name as namewareide')->distinct();
        $this->db->from('tbladjusted');
        $this->db->join('tblwarehouse wareid', 'wareid.id = tbladjusted.warehouse_id', 'left');
        $this->db->where('tbladjusted.id', $id);
        $purchases = $this->db->get()->row();
        $purchases->items = $this->get_items_inventory($id);
        return $purchases;
    }

    public function getCustomer()
    {
        $this->db->select('tblorders_shop.shop as name')->distinct();
        $this->db->from('tblorders_shop');
        $customer = $this->db->get()->result_array();
        return $customer;
    }

    public function getCity()
    {
        $this->db->select('city')->distinct();
        $this->db->from('tblregion_excel');
        $city = $this->db->get()->result_array();

        return $city;
    }

    public function getDistrict($city)
    {
        $this->db->select('district,');
        $this->db->where('city', $city);
        $this->db->from('tblregion_excel');
        $district = $this->db->get()->result_array();

        return $district;
    }

    public function getStatus()
    {
        $array = [
            "Chờ Duyệt",
            "Chờ Lấy Hàng",
            "Đang Lấy Hàng",
            "Đã Lấy Hàng",
            "Hoãn Lấy Hàng",
            "Không Lấy Được",
            "Đang Nhập Kho",
            "Đã Nhập Kho",
            "Đang Chuyển Kho Giao",
            "Đã Chuyển Kho Giao",
            "Đang Giao Hàng",
            "Đã Giao Hàng Toàn Bộ",
            "Đã Giao Hàng Một Phần",
            "Hoãn Giao Hàng",
            "Không Giao Được",
            "Đã Đối Soát Giao Hàng",
            "Đã Đối Soát Trả Hàng",
            "Đang Chuyển Kho Trả",
            "Đã Chuyển Kho Trả",
            "Đang Trả Hàng",
            "Đã Trả Hàng",
            "Hoãn Trả Hàng",
            "Huỷ",
            "Đang Vận Chuyển",
            "Xác Nhận Hoàn",
            "Đã Trả Hàng Một Phần"
        ];
        return $array;
    }

    public function getRegion()
    {
        $this->db->select('name_region');
        $this->db->from('tbldeclared_region');
        $region = $this->db->get()->result_array();

        return $region;
    }
    public function updateOrder($data){
        $sql = "UPDATE tblorders_shop SET note_delay = ? WHERE id = ?";
        $this->db->query($sql, array($data['note'], $data['id']));
        return true;
    }
    public function updateCreateOrder($data){
        $sql = "UPDATE tbl_create_order SET note = ? WHERE id = ?";
        $this->db->query($sql, array($data['note'], $data['id']));
        return true;
    }
    public function getShopByRegion($data){
        $sql = "
        SELECT DISTINCT create_order.orders_shop_id   FROM `tbl_create_order` as create_order
        LEFT JOIN tbldeclared_region as declared_region ON declared_region.id= create_order.region_id
        WHERE declared_region.`name_region` LIKE '%$data->region%'";
        $query = $this->db->query($sql)->result_array();
        $listId = array_map (function($value){
            return $value['orders_shop_id'];
        } , $query);
        $data->id = implode(",",$listId);;
        return $data;
    }
    public function getCreateOrderById($data){
        $stringId =implode(",",$data);
        $sql = "
        SELECT create_order.*,tblorders_shop.shop as name_shop ,tblorders_shop.code_orders as code_order   FROM `tbl_create_order` as create_order
        LEFT JOIN tblorders_shop  ON tblorders_shop.id= create_order.orders_shop_id
        WHERE create_order.`id` IN ($stringId)";
        $query = $this->db->query($sql)->result_array();

        return $query;
    }
    public function getOrder($data)
    {
        $sql = "
        SELECT shop.*,create_order.note_private,create_order.id as create_order_id,declared_region.name_region  FROM `tblorders_shop` as shop
        LEFT JOIN tbl_create_order as create_order ON create_order.orders_shop_id= shop.id
        LEFT JOIN tbldeclared_region as declared_region ON declared_region.id= shop.region_id
        WHERE shop.`shop` LIKE '%$data->customer%'";
//WHERE id IN (33, 34, 45)
        ($data->code_order !="")?$sql.="AND shop.code_supership LIKE '%$data->code_order%'":"";
        ($data->code_order !="")?$sql.="OR shop.required_code LIKE '%$data->code_order%'":"";
        ($data->code_order !="")?$sql.="OR shop.code_ghtk LIKE '%$data->code_order%'":"";
        ($data->code_order !="")?$sql.="OR shop.phone LIKE '%$data->code_order%'":"";
        ($data->id !="")?$sql.="AND shop.id IN ($data->id)":"";
        ($data->code_request !="")?$sql.="AND shop.code_orders LIKE '%$data->code_request%'":"";
        ($data->status !="")?$sql.="AND shop.`status` LIKE '%$data->status%'":"";
        ($data->date_form !="")?$sql.="AND shop.date_create BETWEEN '$data->date_form 00:00:00' AND '$data->date_to 23:59:59'":"";
        ($data->is_hd_branch !="")?$sql.="AND shop.is_hd_branch ='$data->is_hd_branch'":"";
        ($data->dvvc !="")?$sql.="AND shop.dvvc ='$data->dvvc'":"";
        ($data->city !="")?$sql.="AND shop.city LIKE '%$data->city%'":"";
        ($data->district !="")?$sql.="AND shop.district LIKE '%$data->district%'":"";
        $sql.="AND shop.`status` <> 'Hủy'";
        $sql.="AND shop.`status` <> 'Huỷ'";
        $sql.=" ORDER BY shop.date_create DESC";
        $query = $this->db->query($sql)->result();

        return $query;
    }
    public function getOrderShop($id)
    {
        $this->db->select('tblorders_shop.required_code,tblorders_shop.phone');
        $this->db->where('tblorders_shop.id', $id);
        $this->db->from('tblorders_shop');
        $OrderShop = $this->db->get()->result_array();
        return $OrderShop;
    }
}