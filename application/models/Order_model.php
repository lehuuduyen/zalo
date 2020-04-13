<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Order_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
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
    public function getOrder($data)
    {
        $sql = "
        SELECT shop.*,create_order.note_private,create_order.required_code,create_order.id as create_order_id,declared_region.name_region  FROM `tblorders_shop` as shop
        LEFT JOIN tbl_create_order as create_order ON create_order.orders_shop_id= shop.id
        LEFT JOIN tbldeclared_region as declared_region ON declared_region.id= shop.region_id
        WHERE shop.value >= 0 ";
//WHERE id IN (33, 34, 45)
        ($data->code_order !="")?$sql.="AND shop.code_supership LIKE '%$data->code_order%'":"";
        ($data->code_order !="")?$sql.="OR shop.code_orders LIKE '%$data->code_order%'":"";
        ($data->code_order !="")?$sql.="OR shop.code_ghtk LIKE '%$data->code_order%'":"";
        ($data->code_order !="")?$sql.="OR shop.phone LIKE '%$data->code_order%'":"";
        ($data->id !="")?$sql.="AND shop.id IN ($data->id)":"";
        ($data->code_request !="")?$sql.="AND create_order.required_code LIKE '%$data->code_request%'":"";
        ($data->status !="")?$sql.="AND shop.`status` LIKE '%$data->status%'":"";
        ($data->date_form !="")?$sql.="AND shop.date_create BETWEEN '$data->date_form 00:00:00' AND '$data->date_to 23:59:59'":"";
        ($data->is_hd_branch !="")?$sql.="AND shop.is_hd_branch ='$data->is_hd_branch'":"";
        ($data->dvvc !="")?$sql.="AND shop.dvvc ='$data->dvvc'":"";
        ($data->city !="")?$sql.="AND shop.city LIKE '%$data->city%'":"";
        ($data->district !="")?$sql.="AND shop.district LIKE '%$data->district%'":"";
        ($data->customer !="")?$sql.="AND shop.`shop` LIKE '%$data->customer%'":"";
        $query = $this->db->query($sql)->result();
        return $query;
    }
    public function getReturnTableDetail($data)
    {
        $sql = "
        SELECT  order_returns.code_return,order_returns.created_at,order_returns.date_return,orders_shop.*   FROM `tbl_order_returns` as order_returns
        LEFT JOIN tblorders_shop as orders_shop ON orders_shop.id= order_returns.order_shop_id
        WHERE order_returns.id >0";
        ($data->code_return !="")?$sql.=" AND order_returns.code_return = '$data->code_return'":"";
        ($data->created_date_from !="")?$sql.=" AND order_returns.created_at BETWEEN '$data->created_date_from 00:00:00' AND '$data->created_date_to 23:59:59'":"";
        ($data->return_date_from !="")?$sql.=" AND order_returns.date_return BETWEEN '$data->return_date_from 00:00:00' AND '$data->return_date_to 23:59:59'":"";
        ($data->customer !="")?$sql.=" AND orders_shop.`shop` LIKE '%$data->customer%'":"";
        ($data->code_super_ship !="")?$sql.=" AND orders_shop.`code_supership` LIKE '%$data->code_super_ship%'":"";
        ($data->code_return !="")?$sql.=" AND order_returns.code_return = '$data->code_return'":"";

        $query = $this->db->query($sql)->result();

        return $query;
    }
    //payment

    public function getPayment($data)
    {
        $result= [];
        $sql = "
        SELECT shop.*  FROM `tblorders_shop` as shop
        WHERE shop.value >= 0 ";

//WHERE id IN (33, 34, 45)
        foreach($data as $key => $value){
            $condition = "OR";
            if($key==0){
                $condition ="AND";
            }
            $sql.=" $condition shop.code_supership LIKE '%$value%'";
        }
        $query = $this->db->query($sql)->result();
        $result['table0'] =[];
        $result['table1'] =[];

        $array = [];
        foreach ($query as $value){
            $array[]=explode('.',$value->code_supership)[1];
            if($value->is_hd_branch ==1){
                $result['table1'][]=$value;
            }else{
                $result['table0'][]=explode('.',$value->code_supership)[1];
            }
        }

        $result['table0'] = array_merge(array_diff($data,$array),$result['table0']);
        return $result;
    }
    public function createOrderReturn($codeReturn,$orderShopId){
        $sql = "INSERT INTO tbl_order_returns (order_shop_id, code_return) VALUES ($orderShopId, '$codeReturn')";
        $result = $this->db->query($sql);
        return $result;
    }
    public function createShipper($orderReturn){
        $staffId=$this->session->userdata['staff_user_id'];
        foreach ($orderReturn as $value){
            $date = date('Y-m-d',strtotime($value->created_at));
            $array = explode(',',$value->warehouses);
            $district_filter = $array[count($array)-1];
            $commune_filter = $array[count($array)-3];
            unset($array[count($array)-1]);
            unset($array[count($array)-2]);
            $address_filter = implode(", ",$array);
            $sql = "INSERT INTO tblpickuppoint (customer_id, repo_customer,created,status,receive_or_pay,phone_customer,user_geted,user_created,user_reg,district_code,areas_code,modified,district_filter,commune_filter,address_filter,number_order_get,display_name,from_customer,code_return) 
            VALUES (
                    $value->customer_id,
                   '$value->warehouses',
                   '$date',
                   0,
                   1,
                   $value->customer_phone,
                   null,
                   $staffId,
                   0,
                   null,
                   null,
                   '$value->created_at',
                   '$district_filter',
                   '$commune_filter',
                   '$address_filter',
                   0,
                   '$value->shop',
                   0,
                   '$value->code_return'
                   )";
            $result = $this->db->query($sql);
        }
    }
    public function getOrderReturn($codeReturn){
        $sql = "
        SELECT count(*) as total ,customer.customer_phone,customer.id as customer_id,  order_returns.code_return,order_returns.created_at,order_returns.date_return,orders_shop.*   FROM `tbl_order_returns` as order_returns
        LEFT JOIN tblorders_shop as orders_shop ON orders_shop.id= order_returns.order_shop_id
        LEFT JOIN tblcustomers as customer ON customer.customer_shop_code= orders_shop.shop
        WHERE order_returns.code_return = '$codeReturn' GROUP BY orders_shop.shop";
        $query = $this->db->query($sql)->result();

        return $query;
    }
    public function getMaDon($codeReturn,$shop){
        $sql = "
        SELECT  order_returns.code_return,order_returns.created_at,order_returns.date_return,orders_shop.code_supership   FROM `tbl_order_returns` as order_returns
        LEFT JOIN tblorders_shop as orders_shop ON orders_shop.id= order_returns.order_shop_id
        WHERE order_returns.code_return = '$codeReturn' AND orders_shop.shop = '$shop'";
        $query = $this->db->query($sql)->result();

        return $query;
    }
}