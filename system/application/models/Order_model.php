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
    public function getDvvc()
    {
        $this->db->select('tblorders_shop.dvvc as dvvc')->distinct();
        $this->db->from('tblorders_shop');
        $dvvc = $this->db->get()->result_array();
        return $dvvc;
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
        $result = [];
        $this->db->select('color,name');
        $this->db->from('tbldeclare');
        $region = $this->db->get()->result_array();

        foreach ($region as $value){

            $result[$value['name']]=$value['color'];
        }
        return $result;
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
        WHERE shop.id >= 0 ";
//WHERE id IN (33, 34, 45)
        ($data->code_order !="")?$sql.="AND shop.code_supership LIKE '%$data->code_order%'":"";
        ($data->code_order !="")?$sql.="OR shop.code_orders LIKE '%$data->code_order%'":"";
        ($data->code_order !="")?$sql.="OR shop.code_ghtk LIKE '%$data->code_order%'":"";
        ($data->code_order !="")?$sql.="OR shop.phone LIKE '%$data->code_order%'":"";
        ($data->id !="")?$sql.="AND shop.id IN ($data->id)":"";
        ($data->code_request !="")?$sql.="AND create_order.required_code LIKE '%$data->code_request%'":"";
        $strStatus = "";
        foreach($data->status as $key => $status){
            $strStatus.="'$status'";
            if($key+1 < count($data->status)){
                $strStatus.=",";
            }
        }
        ($status !="")?$sql.="AND shop.status IN ($strStatus)":"";
        if($data->type_date){
            if($data->type_date ==1){
                ($data->date_form !="")?$sql.="AND shop.date_create BETWEEN '$data->date_form 00:00:00' AND '$data->date_to 23:59:59'":"";
            }elseif ($data->type_date ==2){
                ($data->date_form !="")?$sql.="AND shop.date_debits BETWEEN '$data->date_form 00:00:00' AND '$data->date_to 23:59:59'":"";
            }elseif ($data->type_date ==3){
                ($data->date_form !="")?$sql.="AND shop.control_date BETWEEN '$data->date_form 00:00:00' AND '$data->date_to 23:59:59'":"";
            }
        }
        ($data->is_hd_branch !="")?$sql.="AND shop.is_hd_branch ='$data->is_hd_branch'":"";
        ($data->dvvc !="")?$sql.="AND shop.dvvc ='$data->dvvc'":"";
        ($data->city !="")?$sql.="AND shop.city LIKE '%$data->city%'":"";
        ($data->district !="")?$sql.="AND shop.district LIKE '%$data->district%'":"";
        ($data->customer !="")?$sql.="AND shop.`shop` LIKE '%$data->customer%'":"";
        $sql.= 'ORDER BY shop.date_create DESC';
        $query = $this->db->query($sql)->result();

        return $query;
    }

    public function getOrderMultiStatus($data)
    {
        $sql = "
        SELECT shop.*,create_order.note_private,create_order.required_code,create_order.id as create_order_id,declared_region.name_region  FROM `tblorders_shop` as shop
        LEFT JOIN tbl_create_order as create_order ON create_order.orders_shop_id= shop.id
        LEFT JOIN tbldeclared_region as declared_region ON declared_region.id= shop.region_id
        WHERE shop.id >= 0 ";
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
        $sql.= 'ORDER BY shop.date_create DESC';
        $query = $this->db->query($sql)->result();
        return $query;
    }
    public function getOrderTab4($data)
    {
        $sql = "
        SELECT shop.* FROM `tbl_create_order` as shop
        WHERE shop.id >= 0 ";
//WHERE id IN (33, 34, 45)
        ($data->code_order !="")?$sql.="AND shop.code_supership LIKE '%$data->code_order%'":"";
        ($data->code_order !="")?$sql.="OR shop.code_orders LIKE '%$data->code_order%'":"";
        ($data->code_order !="")?$sql.="OR shop.code_ghtk LIKE '%$data->code_order%'":"";
        ($data->code_order !="")?$sql.="OR shop.phone LIKE '%$data->code_order%'":"";
        ($data->code_request !="")?$sql.="AND shop.required_code LIKE '%$data->code_request%'":"";
        ($data->date_form !="")?$sql.="AND shop.created BETWEEN '$data->date_form 00:00:00' AND '$data->date_to 23:59:59'":"";
        $sql.="AND shop.dvvc = '' ";
        ($data->city !="")?$sql.="AND shop.city LIKE '%$data->city%'":"";
        ($data->district !="")?$sql.="AND shop.district LIKE '%$data->district%'":"";
        ($data->customer !="")?$sql.="AND shop.`customer_id` LIKE '%$data->customer%'":"";
        $query = $this->db->query($sql)->result();
        return $query;
    }
    public function getReturnTableDetail($data)
    {
        $sql = "
        SELECT order_returns.code_return,order_returns.created_at,order_returns.date_return,orders_shop.*   FROM `tbl_order_returns` as order_returns
        LEFT JOIN tblorders_shop as orders_shop ON orders_shop.id= order_returns.order_shop_id
        WHERE order_returns.id >0";
        ($data->code_return !="")?$sql.=" AND order_returns.code_return = '$data->code_return'":"";
        ($data->created_date_from !="")?$sql.=" AND order_returns.created_at BETWEEN '$data->created_date_from 00:00:00' AND '$data->created_date_to 23:59:59'":"";
        ($data->return_date_from !="")?$sql.=" AND order_returns.date_return BETWEEN '$data->return_date_from 00:00:00' AND '$data->return_date_to 23:59:59'":"";
        ($data->customer !="")?$sql.=" AND orders_shop.`shop` LIKE '%$data->customer%'":"";
        ($data->code_super_ship !="")?$sql.=" AND orders_shop.`code_supership` LIKE '%$data->code_super_ship%'":"";
        ($data->code_return !="")?$sql.=" AND order_returns.code_return = '$data->code_return'":"";
        $sql.= 'ORDER BY order_returns.created_at DESC';
        $query = $this->db->query($sql)->result();

        return $query;
    }
    public function getReturnBranchTableDetail($data)
    {
        $sql = "
        SELECT order_returns.code_return,order_returns.created_at,orders_shop.*   FROM `tblreturn_branch` as order_returns
        LEFT JOIN tblorders_shop as orders_shop ON orders_shop.id= order_returns.order_shop_id
        WHERE order_returns.id >0";
        ($data->code_return !="")?$sql.=" AND order_returns.code_return = '$data->code_return'":"";
        ($data->created_date_from !="")?$sql.=" AND order_returns.created_at BETWEEN '$data->created_date_from 00:00:00' AND '$data->created_date_to 23:59:59'":"";
        ($data->customer !="")?$sql.=" AND orders_shop.`shop` LIKE '%$data->customer%'":"";
        ($data->code_super_ship !="")?$sql.=" AND orders_shop.`code_supership` LIKE '%$data->code_super_ship%'":"";
        ($data->code_return !="")?$sql.=" AND order_returns.code_return = '$data->code_return'":"";
        $sql.= 'ORDER BY order_returns.created_at DESC';
        $query = $this->db->query($sql)->result();

        return $query;
    }
    //payment

    public function getPayment($data)
    {
        $result = [];
        $arrStatusCancel = array('Hủy', 'Huỷ');
        $arrStatus = array('Đã Chuyển Kho Trả Một Phần', 'Đã Chuyển Kho Trả Toàn Bộ');

        $sql = "
        SELECT shop.*  FROM `tblorders_shop` as shop
        WHERE shop.id >= 0 ";

        foreach ($data as $key => $value) {
            $condition = "OR";
            if ($key == 0) {
                $condition = "AND";
            }
            $sql .= " $condition shop.code_supership LIKE '%$value%'";
        }
        $query = $this->db->query($sql)->result();
        $result['table0'] = [];
        $result['table1'] = [];

        $array = [];
        foreach ($query as $value) {
            $array[] = explode('.', $value->code_supership)[1];
            if ($value->is_hd_branch == 1 && !in_array($value->status, $arrStatusCancel) && in_array($value->status, $arrStatus)) {
                $result['table1'][] = $value;
            } else {
                $result['table0'][] = $value->code_supership;
            }
        }

        $result['table0'] = array_merge(array_diff($data, $array), $result['table0']);
        return $result;
    }
//payment

    public function getMaVachImports($data)
    {
        $result = [];
        $arrStatusCancel = array('Hủy', 'Huỷ');

        $sql = "
        SELECT shop.*  FROM `tblorders_shop` as shop
        WHERE shop.id >= 0 ";

        foreach ($data as $key => $value) {
            $condition = "OR";
            if ($key == 0) {
                $condition = "AND";
            }
            $sql .= " $condition shop.code_supership LIKE '%$value%'";
        }
        $query = $this->db->query($sql)->result();
        $result['table0'] = [];
        $result['table1'] = [];

        $array = [];
        foreach ($query as $value) {
            $array[] = explode('.', $value->code_supership)[1];
            if ($value->is_hd_branch == 1 && !in_array($value->status, $arrStatusCancel) ) {
                $result['table1'][] = $value;
            } else {
                $result['table0'][] = $value->code_supership;
            }
        }

        $result['table0'] = array_merge(array_diff($data, $array), $result['table0']);
        return $result;
    }
	 public function createOrderReturn($codeReturn, $orderShopId)
    {
        //get ordershop
        //get id shop
        $this->db->where('id', $orderShopId);
        $this->db->from('tblorders_shop');
        $orderShop = $this->db->get()->result()[0];

        $dateNow = date('Y-m-d H:i:s');
        $sql = "INSERT INTO tbl_order_returns (shop,order_shop_id, code_return,created_at) VALUES ('$orderShop->shop',$orderShopId, '$codeReturn','$dateNow')";
        $result = $this->db->query($sql);

        if ($result) {
            if ($orderShop->status == 'Đã Chuyển Kho Trả Một Phần') {
                $data['status'] = 'Đã Trả Hàng Một Phần';
            } elseif ($orderShop->status == 'Đã Chuyển Kho Trả Toàn Bộ') {
                $data['status'] = 'Đã Trả Hàng Toàn Bộ';
            }

            // Update

            $this->db->where('id', $orderShop->id);
            $this->db->update('tblorders_shop', $data);

            //them đơn trả về tblpickuppoint

        }
        return $result;
    }
    public function getCustomerByShop($orderShop){
        $this->db->select('id');
        $this->db->like('customer_shop_code', $orderShop, 'both');
        $this->db->from('tblcustomers');
        $customer = $this->db->get()->row();
        return $customer->id;
    }
    public function updateOrderShopImport($codeReturn,$orderShopId){
        //create tblreturn_branch
        $this->db->where('id', $orderShopId);
        $this->db->from('tblorders_shop');
        $orderShop = $this->db->get()->result()[0];

        $dateNow = date('Y-m-d H:i:s');
        $sql = "INSERT INTO tblreturn_branch (shop,order_shop_id, code_return,created_at) VALUES ('$orderShop->shop',$orderShopId, '$codeReturn','$dateNow')";
        $result = $this->db->query($sql);

        //get order shop
        $data=[];
        $this->db->where('id', $orderShopId);
        $this->db->from('tblorders_shop');
        $timeNow = date('Y-m-d H:i:s');
        $orderShop = $this->db->get()->row();
        if($orderShop->status == "Đã Đối Soát Giao Hàng" || $orderShop->status == "Đã Chuyển Kho Trả Một Phần"|| $orderShop->status == "Đã Trả Hàng Một Phần"|| $orderShop->status == "Đã Giao Hàng Một Phần"){
            $status = "Đã Chuyển Kho Trả Một Phần";
        }else{
            $status = "Đã Chuyển Kho Trả Toàn Bộ";
        }

        // Update

        $sql = "UPDATE tblorders_shop SET status = '$status' , last_time_updated = '$timeNow' ";
        if($orderShop->date_debits ==null){
            $sql .= ", date_debits = '$timeNow'";
        }
        $sql .= "WHERE id = $orderShopId";
        $this->db->query($sql);


    }

    public function createShipper($orderReturn){
        $staffId=$this->session->userdata['staff_user_id'];
        foreach ($orderReturn as $value){
            $date = date('Y-m-d H:i:s',strtotime($value->created_at));
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
                   '$value->total',
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
    public function getOrderReturnBranch($codeReturn){

        $sql = "
        SELECT count(*) as total ,customer.customer_phone,customer.id as customer_id,  order_returns.code_return,order_returns.created_at,orders_shop.*   FROM `tblreturn_branch` as order_returns
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
    public function getMaDonReturnBranch($codeReturn,$shop){
        $sql = "
        SELECT  order_returns.code_return,order_returns.created_at,orders_shop.code_supership   FROM `tblreturn_branch` as order_returns
        LEFT JOIN tblorders_shop as orders_shop ON orders_shop.id= order_returns.order_shop_id
        WHERE order_returns.code_return = '$codeReturn' AND orders_shop.shop = '$shop'";
        $query = $this->db->query($sql)->result();

        return $query;
    }
    public function getAddressList(){
        $this->db->from('address_list');
        $query = $this->db->get()->result();

        return $query;
    }
    public function addAddressList($result){
        $this->db->where('id >',0 );
        $this->db->delete('tbladdress_list');
        foreach ($result as $data){
            $this->db->insert('tbladdress_list', $data);

        }


    }
}