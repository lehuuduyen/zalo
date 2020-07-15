<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Delivery_order_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getTableByDelivery($code){
        $this->db->select('tbldelivery_nb.id,shop.collect,shop.status,tbldelivery_nb.date_create,tbldelivery_nb.date_report,tbldelivery_nb.code_delivery,count(tbldelivery_nb.code_delivery) as tong_don,CONCAT(staffa.firstname," ",staffa.lastname) as fullname', FALSE);
        $this->db->join('tblstaff as staffa','staffa.staffid = tbldelivery_nb.sman','left');
        $this->db->join('tblorders_shop as shop','shop.id = tbldelivery_nb.shop','left');
        $this->db->where('code_delivery',$code);
        $this->db->from('tbldelivery_nb');
        $data = $this->db->get()->result();

        return $data;
    }
    public function sumCollectDaThu($deliveryCode){
        $arrStatus = array('Đã Trả Hàng Một Phần', 'Đã Giao Hàng Toàn Bộ','Đã Giao Hàng Một Phần','Đã Chuyển Kho Trả Một Phần','Đã Đối Soát Giao Hàng','Đang Trả Hàng Một Phần');
        $this->db->select_sum('collect');
        $this->db->join('tblorders_shop as shop','shop.id = tbldelivery_nb.shop','left');
        $this->db->where('tbldelivery_nb.code_delivery',$deliveryCode);
        $this->db->where_in('shop.status',$arrStatus);

        $this->db->from('tbldelivery_nb');
        $data = $this->db->get()->result();
        $sum =0;
        if($data[0]->collect){
            $sum=$data[0]->collect;
        }
        return $sum;
    }
    public function getCountThuHoByDelivery($code){
        $arr=[];
        $count =0;
        $this->db->select('shop');
        $this->db->where('code_delivery',$code);
        $this->db->from('tbldelivery_nb');
        $data = $this->db->get()->result();
        foreach($data as $value){
            $arr[]=$value->shop;
        }
        $this->db->select_sum('collect');
        $this->db->where_in('id',$arr);
        $this->db->from('tblorders_shop');
        $order = $this->db->get()->result();
        if(count($order)>0){
            $count=$order[0]->collect;
        }
        return $count;
    }
    public function getTableAll(){
        $arrStatus = array('Đã Nhập Kho', 'Hoãn Giao Hàng');
        $this->db->where_in('status',$arrStatus);
        $this->db->where('DVVC',"NB");
        $this->db->from('tblorders_shop');
        $result = $this->db->get()->result();

        return $result;
    }

    public function getTable($data){
        $arrStatus = array('Đã Nhập Kho', 'Hoãn Giao Hàng');
        $this->db->where('DVVC',"NB");
        $this->db->where_in('status',$arrStatus);
        if(!empty($data->ma_vach)){
            $this->db->group_start();
            foreach ($data->ma_vach as $key => $value) {
                if ($key == 0) {
                    $this->db->where("code_supership LIKE '%$value%'");
                }else{
                    $this->db->or_where("code_supership LIKE '%$value%'");
                }
            }
            $this->db->group_end();
        }
        if(!empty($data->province)){

            $this->db->group_start();
            foreach ($data->province as $key => $value) {
                if ($key == 0) {
                    $this->db->where("city LIKE '%$value%'");
                }else{
                    $this->db->or_where("city LIKE '%$value%'");
                }
            }
            $this->db->group_end();
        }

        if(!empty($data->district)){

            $this->db->group_start();
            foreach ($data->district as $key => $value) {
                if ($key == 0) {
                    $this->db->where("district LIKE '%$value%'");
                }else{
                    $this->db->or_where("district LIKE '%$value%'");
                }
            }
            $this->db->group_end();
        }
        if(!empty($data->commune)){

            $this->db->group_start();
            foreach ($data->commune as $key => $value) {
                if ($key == 0) {
                    $this->db->where("ward LIKE '%$value%'");
                }else{
                    $this->db->or_where("ward LIKE '%$value%'");
                }
            }
            $this->db->group_end();
        }
        $this->db->order_by("date_create","desc");
        $this->db->from('tblorders_shop');
        $result = $this->db->get()->result();

        return $result;
    }
    public function getTableDetail($code){

        $this->db->select('shop.*,tbldelivery_nb.id as delivery_id,tbldelivery_nb.code_delivery,tbldelivery_nb.status_report', FALSE);
        $this->db->join('tblorders_shop as shop','shop.id = tbldelivery_nb.shop','left');
        $this->db->where('code_delivery',$code);
        $this->db->from('tbldelivery_nb');
        $data = $this->db->get()->result();
        return $data;
    }
    public function addDelivery($result){

        return $this->db->insert('tbldelivery_nb', $result);



    }

}