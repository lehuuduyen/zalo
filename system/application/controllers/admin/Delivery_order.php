<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Delivery_order extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('dashboard_model');
    }

    /* This is admin dashboard view */
    public function view()
    {

        $this->load->model('Order_model');
        $order_model = new Order_model();
        $data['list_status'] = $order_model->getStatus();
        //get date
        $now = date('Y-m-d');
        $date = new DateTime($now);
        $days = 7;
        date_sub($date, date_interval_create_from_date_string($days . ' days'));
        $date_from = date_format($date, 'Y-m-d');
        $date_to = date('Y-m-d');
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        $this->load->view('admin/deliverys/index',$data);
    }
    public function detail($code)
    {
        $fullname="";
        $status="";
        $total=0;
        $this->load->model('Delivery_order_model');
        $Delivery_order_model = new Delivery_order_model();
        $delivery = $Delivery_order_model->getTableByDelivery($code);
        $data['code'] = $code;
        if(count($delivery)>0){
             $fullname=$delivery[0]->fullname;
             if(!empty($delivery[0]->date_report)){
                 $status="Đã báo cáo";
             }else{
                 $status="Chưa báo cáo";
             }
            $total=$delivery[0]->tong_don;
        }
        $data['fullname'] =$fullname;
        $data['status'] =$status;
        $data['total'] =$total;
        $data['so_don_bao_cao'] =$this->get_count_have_delivery_count($code);
        $data['so_don_chua_bao_cao'] =$total - $data['so_don_bao_cao'];
        $data['tong_tien_thu_ho'] =$Delivery_order_model->getCountThuHoByDelivery($code);
        $data['tong_tien_da_thu'] =$Delivery_order_model->sumCollectDaThu($code);
        $this->load->model('Order_model');
        $order_model = new Order_model();
        $data['list_status'] = $order_model->getStatus();


        $this->load->view('admin/deliverys/detail',$data);
    }
    public function index()
    {

        $this->load->model('Order_model');
        $order_model = new Order_model();
        $data['list_status'] = $order_model->getStatus();

        $this->load->view('admin/deliverys/create',$data);
    }
    public function getStaff(){
        $staff =$this->staff_model->get('', ['active' => 1]);
        print_r(json_encode($staff));

    }

    public function get_province()
    {
        $this->db->select('province_id,province')->distinct();
        $this->db->from('tbladdress_list');
        $province = $this->db->get()->result();
        print_r(json_encode($province));

    }
    public function get_district()
    {
        $jsonData = $_GET['list_province'];
        $listProvince = json_decode($jsonData);

        $this->db->select('district_id,district')->distinct();
        $this->db->where_in('province',$listProvince);
        $this->db->from('tbladdress_list');
        $district = $this->db->get()->result();
        print_r(json_encode($district));

    }
    public function get_commune()
    {
        $jsonData = $_GET['list_district'];
        $listDistrict = json_decode($jsonData);

        $this->db->select('commune_id,commune')->distinct();
        $this->db->where_in('district',$listDistrict);
        $this->db->from('tbladdress_list');
        $district = $this->db->get()->result();
        print_r(json_encode($district));

    }
    public function getTableDeliveryDetail($code){

        $this->load->model('Delivery_order_model');
        $Delivery_order_model = new Delivery_order_model();
        $order = $Delivery_order_model->getTableDetail($code);
        $result = new stdClass();
        $result->data = $order;
        header('Content-Type: application/json');
        echo json_encode($result);

    }public function getTableDelivery(){
        $json = $this->input->get('jsonData');
        $data = json_decode($json);
        $this->load->model('Delivery_order_model');
        $Delivery_order_model = new Delivery_order_model();
        $order = $Delivery_order_model->getTable($data);
        $result = new stdClass();
        $result->data = $order;
        header('Content-Type: application/json');
        echo json_encode($result);

    }
    public function getTableDeliveryAll(){
        $this->load->model('Delivery_order_model');
        $Delivery_order_model = new Delivery_order_model();
        $order = $Delivery_order_model->getTableAll();
        $result = new stdClass();
        $result->data = $order;
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function add(){
        $result =[];
        $this->load->model('Delivery_order_model');
        $Delivery_order_model = new Delivery_order_model();
        $listOrderID =$_POST['list_order'];
        $code="";
        if(!empty($listOrderID)){
            $this->db->select('code_supership as code_oders,shop as name,id as shop');
            $this->db->where_in('id',$listOrderID);
            $this->db->from('tblorders_shop');
            $listOrder = $this->db->get()->result();
            $code =$this->generateRandomCodeDelivery();
            foreach ($listOrder as $orderId){
                $customerId=$this->getCustomerByShop($orderId->name);
                $sql = "UPDATE tblorders_shop SET status = ?  WHERE id = ?";
                $this->db->query($sql, array("Đang Giao Hàng", $orderId->shop));
                $orderId->code_delivery=$code;
                $orderId->staff_create=get_staff_user_id();
                $orderId->sman=$_POST['staff'];
                $orderId->customer_id=$customerId;
                unset($orderId->name);
                $result[]=$Delivery_order_model->addDelivery($orderId);
                var_dump($result);die;

            }

        }
        header('Content-Type: application/json');
        echo json_encode($result);

    }
    public function getCustomerByShop($shopName){
        $this->db->select('id');
        $this->db->like('customer_shop_code', $shopName, 'both');
        $this->db->from('tblcustomers');
        $customer = $this->db->get()->row();
        return $customer->id;
    }
    public function updateDelivery($id){
        //get delivery
        $this->db->select_sum('sman');
        $this->db->where('tbldelivery_nb.id',$id);

        $this->db->from('tbldelivery_nb');
        $data = $this->db->get()->row();



        $strtotime = strtotime(date("Y-m-d H:i:s"));
        $dateTime  =date("Y-m-d H:i:s",$strtotime);
        $formatDateTime  =date("d/m/Y H:i:s",$strtotime);
        $status_report = $_GET['status_report'];
        $key = $_GET['key'];
        $shop_id = $_GET['shop_id'];
        if($key =="da_giao_hang"){
            $status_order=$status_report;
            $sql = "UPDATE tblorders_shop SET last_sman = ? ,status = ? , date_debits = ? WHERE id = ?";
            $this->db->query($sql, array($data->sman,$status_order,$dateTime, $shop_id));

            $this->update($id,$status_order,$dateTime);

        }elseif ($key == "hoan_giao"){
            $status_order ="Hoãn Giao Hàng";
            //get order
            $this->db->select('note');
            $this->db->where('tblorders_shop.id',$shop_id);
            $this->db->from('tblorders_shop');
            $order = $this->db->get()->row();
            $note  = $order->note ."\n".$formatDateTime." ".$status_report;

            $sql = "UPDATE tblorders_shop SET last_sman = ? ,status = ? ,note = ? , date_debits = ? WHERE id = ?";
            $this->db->query($sql, array($data->sman,$status_order,$note,$dateTime, $shop_id));

            $this->update($id,$status_order,$dateTime);


        }elseif ($key == "khong_giao_duoc"){
            $status_order ="Không Giao Được";
            //get order
            $this->db->select('note');
            $this->db->where('tblorders_shop.id',$shop_id);
            $this->db->from('tblorders_shop');
            $order = $this->db->get()->row();
            $note  = $order->note ."\n".$formatDateTime." ".$status_report;

            $sql = "UPDATE tblorders_shop SET last_sman = ? ,status = ? ,note = ? , date_debits = ? WHERE id = ?";
            $this->db->query($sql, array($data->sman,$status_order,$note,$dateTime, $shop_id));
            $this->update($id,$status_order,$dateTime);

        }



    }
    public function update($id,$status_report,$dateTime){
        $sql = "UPDATE tbldelivery_nb SET status_report = ? , date_report = ? WHERE id = ?";
        $this->db->query($sql, array($status_report,$dateTime, $id));
        return true;
    }
    public function sumCollect($deliveryCode){
        $this->db->select_sum('collect');
        $this->db->join('tblorders_shop as shop','shop.id = tbldelivery_nb.shop','left');
        $this->db->where('tbldelivery_nb.code_delivery',$deliveryCode);
        $this->db->from('tbldelivery_nb');
        $data = $this->db->get()->result();
        $sum =0;
        if($data[0]->collect){
            $sum=$data[0]->collect;
        }
        $result = new stdClass();
        $result->sum = $sum;
        header('Content-Type: application/json');
        echo json_encode($result);
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
        $result = new stdClass();
        $result->sum = $sum;
        header('Content-Type: application/json');
        echo json_encode($result);
    }
    //giao hang
    public function get_delivery()
    {
        $da_bao_cao =1;
        $chua_bao_cao =2;
        $array =[];
        $checkStatus=[];
        $json = $this->input->get('jsonData');
        $data = json_decode($json);
        $this->db->select('tbldelivery_nb.id,shop.collect,shop.status,tbldelivery_nb.date_create,tbldelivery_nb.date_report,tbldelivery_nb.code_delivery,CONCAT(staffa.firstname," ",staffa.lastname) as fullname');
        $this->db->join('tblstaff as staffa','staffa.staffid = tbldelivery_nb.sman','left');
        $this->db->join('tblorders_shop as shop','shop.id = tbldelivery_nb.shop','left');

        if(!empty($data->staff)){
            $this->db->where('tbldelivery_nb.sman',$data->staff);
        }
        if(!empty($data->staff_create)){
            $this->db->where('tbldelivery_nb.staff_create',$data->staff_create);
        }
        if(!empty($data->date_create_start)){
            $this->db->where("tbldelivery_nb.date_create BETWEEN '$data->date_create_start 00:00:00' and '$data->date_create_end 23:59:59'");
        }
        $this->db->from('tbldelivery_nb');
        $kq = $this->db->get()->result();

        foreach ($kq as $key=> $value){
            if(isset($array[$value->code_delivery])){
                $array[$value->code_delivery]->tong_don = $array[$value->code_delivery]->tong_don + 1;

            }else{
                $array[$value->code_delivery]=$value;
                $array[$value->code_delivery]->tong_don =1;
            }
            if(!$value->date_report){
                $checkStatus[$value->code_delivery]="Chưa báo cáo";
            }
        }
        $listResult =[];
        foreach ($checkStatus as $code => $stat){
            if($data->status == $da_bao_cao){
                unset($array[$code]);
                continue;
            }
            $array[$code]->tinh_trang =$stat;

            if($data->status == $chua_bao_cao){
                $listResult[]=$array[$code];
            }
        }
        if($data->status == $chua_bao_cao){
            $array=$listResult;
        }

        $result = new stdClass();
        $result->data = array_values($array);
        header('Content-Type: application/json');
        echo json_encode($result);

    }
    public function get_count_have_delivery($deliveryCode)
    {
        $this->db->select('count(tbldelivery_nb.code_delivery) as tong_don', FALSE);
        $this->db->where('tbldelivery_nb.date_report IS NOT NULL');
        $this->db->where('tbldelivery_nb.code_delivery',$deliveryCode);
        $this->db->from('tbldelivery_nb');
        $data = $this->db->get()->result();
        $count =0;
        if($data[0]->tong_don){
            $count=$data[0]->tong_don;
        }
        $result = new stdClass();
        $result->count = $count;
        header('Content-Type: application/json');
        echo json_encode($result);



    }
    public function get_count_have_delivery_count($deliveryCode)
    {
        $this->db->select('count(tbldelivery_nb.code_delivery) as tong_don', FALSE);
        $this->db->where('tbldelivery_nb.date_report IS NOT NULL');
        $this->db->where('tbldelivery_nb.code_delivery',$deliveryCode);
        $this->db->from('tbldelivery_nb');
        $data = $this->db->get()->result();
        $count =0;
        if($data[0]->tong_don){
            $count=$data[0]->tong_don;
        }
        return $count;



    }



    function generateRandomCodeDelivery() {
        $charactersNumber = '0123456789';
        $charactersString = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersStringLength = strlen($charactersString);
        $charactersNumberLength = strlen($charactersNumber);
        $randomString = '';
        for ($i = 0; $i < 4; $i++) {
            $randomString .= $charactersString[rand(0, $charactersStringLength - 1)];
        }
        $randomString .=".";
        for ($i = 0; $i < 6; $i++) {
            $randomString .= $charactersNumber[rand(0, $charactersNumberLength - 1)];
        }
        return $randomString;
    }
}
