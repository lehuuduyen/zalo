<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Shiper extends AdminController {

  public function password() {

    if ($this->input->server('REQUEST_METHOD') == 'POST') {

      $currentUser = $this->staff_model->get(get_staff_user_id())->staffid;
      $return = $this->staff_model->change_my_password($_POST , $currentUser);


      if ($return['status'] == false) {

        $this->session->set_flashdata('error_change_pass',1);
        $this->session->set_flashdata('mess_pass',$return['m']);
        redirect('shiper');
      }else {
        $this->session->set_flashdata('sucess_change_pass',1);
        redirect('shiper');
      }
    }else {
      redirect('shiper');
    }
  }

  public function ajax_wait() {
    $this->db->select('*');
    $this->db->from('tblpickuppoint');
    $this->db->select('tblpickuppoint.id as id');
    $this->db->where('status' , 0);
    $this->db->join('tblcustomers', 'tblcustomers.id = tblpickuppoint.customer_id');
    $waits1 = $this->db->get()->result();


    $this->db->select('*');

    $this->db->from('tblpickuppoint');
    $this->db->where('status' , 0);
    $this->db->where('customer_id' , 0);
    $waits2 = $this->db->get()->result();

    $waits = array_merge($waits1 , $waits2);

    if ($waits) {
      usort($waits, function ($a, $b) {
        return strcmp(trim($a->commune_filter), trim($b->commune_filter));
      });

      usort($waits, function ($a, $b) {
        return strcmp(trim($a->district_filter), trim($b->district_filter));
      });
    }
    foreach($waits as $key => $val){
        if($val->code_return){
            $this->db->select('tbl_order_returns.*,tblorders_shop.code_supership');
            $this->db->from('tbl_order_returns');
            $this->db->join('tblorders_shop', 'tblorders_shop.id = tbl_order_returns.order_shop_id');

            $this->db->where('tbl_order_returns.code_return' , $val->code_return);
            $this->db->like('tbl_order_returns.shop', $val->display_name, 'both');
            $orderReturn = $this->db->get()->result();
            $waits[$key]->order_return = $orderReturn;
        }else{
            $waits[$key]->order_return = [];
        }
    }
    echo json_encode($waits);

  }




  // public function replace()
  // {
  //   $this->db->select('*');
  //   $this->db->from('tblpickuppoint');
  //   $data = $this->db->get()->result();
  //   foreach ($data as $key => $value) {
  //
  //     if ($value->customer_id == '0') {
  //       $value->display_name = $value->name_customer_new;
  //     }
  //     else {
  //       $this->db->select('customer_shop_code');
  //       $cus = $this->db->get_where('tblcustomers', array('id' => $value->customer_id))->row();
  //       $value->display_name = $cus->customer_shop_code;
  //
  //     }
  //   }
  //   $this->db->update_batch('tblpickuppoint', $data, 'id');
  // }

  public function geted_ajax($value=''){
      $json = $_GET['jsonData'];
      $data = json_decode($json);
      $array = [];
    // $_GET['limit'];
    $this->db->select('tblpickuppoint.*,tblpickuppoint.modified as date_report');
    $this->db->select('tblpickuppoint.id as id');
    $this->db->select('tblstaff.firstname as firstname');
    $this->db->select('tblstaff.lastname as lastname');
    $this->db->where('status' , 1);
    $this->db->from('tblpickuppoint');
    // $this->db->join('tblcustomers', 'tblcustomers.id = tblpickuppoint.customer_id');
    $this->db->join('tblstaff', 'tblstaff.staffid = tblpickuppoint.user_geted' , 'left');
    $this->db->where('tblstaff.staffid' , $data->staff);
      if ($_GET['limit'] !== 'all') {
      $this->db->limit($_GET['limit']);
    }

    $this->db->order_by('modified', 'DESC');
    $geteds1 = $this->db->get()->result();

    $getDeliveryActive = $this->getDeliveryActive($data->staff);

      $array = array_merge($geteds1,$getDeliveryActive);
//      $array = array_merge($getDeliveryActive);

    echo json_encode($array);
  }
  public function getDeliveryActive($staffId){
      $this->db->select('tbldelivery_nb.orders,tblcustomers.customer_phone,tblcustomers.customer_shop_code,tbldelivery_nb.id as delivery_id,shop.* ,tbldelivery_nb.date_create,tbldelivery_nb.date_report,tbldelivery_nb.code_delivery,tbldelivery_nb.status_report');
      $this->db->join('tblorders_shop as shop','shop.id = tbldelivery_nb.shop','left');
      $this->db->join('tblcustomers ','tblcustomers.id = tbldelivery_nb.customer_id','left');
          $this->db->where('tbldelivery_nb.sman',$staffId);
      $this->db->where('date_report is NOT NULL');
      $this->db->order_by('tbldelivery_nb.orders', 'ASC');

      $this->db->from('tbldelivery_nb');
      $kq = $this->db->get()->result();
      return $kq;
  }



    public function index() {

        $currentUser = $this->staff_model->get(get_staff_user_id())->staffid;
        $user_display = $this->staff_model->get(get_staff_user_id());



        $this->db->select('*');
        $this->db->select('tblpickuppoint.id as id');
        $this->db->select('tblstaff.firstname as firstname');
        $this->db->select('tblstaff.lastname as lastname');
        $this->db->where('status' , 2);
        $this->db->where('user_reg' , $currentUser);
        $this->db->from('tblpickuppoint');
        $this->db->join('tblcustomers', 'tblcustomers.id = tblpickuppoint.customer_id');
        $this->db->join('tblstaff', 'tblstaff.staffid = tblpickuppoint.user_reg');
        $reg1 = $this->db->get()->result();


        $this->db->select('*');
        $this->db->select('tblstaff.firstname as firstname');
        $this->db->select('tblstaff.lastname as lastname');
        $this->db->where('status' , 2);
        $this->db->where('customer_id' , 0);
        $this->db->where('user_reg' , $currentUser);

        $this->db->from('tblpickuppoint');
        $this->db->join('tblstaff', 'tblstaff.staffid = tblpickuppoint.user_reg');
        $reg2 = $this->db->get()->result();




        $this->db->select('*');
        $this->db->from('tblpickuppoint');
        $this->db->select('tblpickuppoint.id as id');
        $this->db->where('status' , 0);
        $this->db->join('tblcustomers', 'tblcustomers.id = tblpickuppoint.customer_id');
        $waits1 = $this->db->get()->result();


        $this->db->select('*');

        $this->db->from('tblpickuppoint');
        $this->db->where('status' , 0);
        $this->db->where('customer_id' , 0);
        $waits2 = $this->db->get()->result();

        $waits = array_merge($waits1 , $waits2);

        $reg  = array_merge($reg1 , $reg2);


        if ($waits) {
            usort($waits, function ($a, $b) {
                return strcmp(trim($a->commune_filter), trim($b->commune_filter));
            });

            usort($waits, function ($a, $b) {
                return strcmp(trim($a->district_filter), trim($b->district_filter));
            });
        }





        if ($reg) {
            usort($reg, function ($a, $b) {
                return strcmp(trim($a->commune_filter), trim($b->commune_filter));
            });

            usort($reg, function ($a, $b) {
                return strcmp(trim($a->district_filter), trim($b->district_filter));
            });
        }

        $listDeliveryFirst = $this->get_frist_delivery($currentUser);
        $data['list_delivery'] = $listDeliveryFirst;
        $data['waits'] = $waits;
        $data['regs'] = $reg;
        $data['user_display'] = $user_display;
        $data['tab2'] = false;
        $data['current_user'] = $currentUser;
        if (!empty($_GET)) {
            if ($_GET['tab'] === '1') {
                $data['tab1'] = true;
            }
        }
        $this->load->view('shiper/index.php',$data);

    }
    public function call_table2(){
        $currentUser = $this->staff_model->get(get_staff_user_id())->staffid;
        $user_display = $this->staff_model->get(get_staff_user_id());



        $this->db->select('*');
        $this->db->select('tblpickuppoint.id as id');
        $this->db->select('tblstaff.firstname as firstname');
        $this->db->select('tblstaff.lastname as lastname');
        $this->db->where('status' , 2);
        $this->db->where('user_reg' , $currentUser);
        $this->db->from('tblpickuppoint');
        $this->db->join('tblcustomers', 'tblcustomers.id = tblpickuppoint.customer_id');
        $this->db->join('tblstaff', 'tblstaff.staffid = tblpickuppoint.user_reg');
        $reg1 = $this->db->get()->result();


        $this->db->select('*');
        $this->db->select('tblstaff.firstname as firstname');
        $this->db->select('tblstaff.lastname as lastname');
        $this->db->where('status' , 2);
        $this->db->where('customer_id' , 0);
        $this->db->where('user_reg' , $currentUser);

        $this->db->from('tblpickuppoint');
        $this->db->join('tblstaff', 'tblstaff.staffid = tblpickuppoint.user_reg');
        $reg2 = $this->db->get()->result();
        $reg  = array_merge($reg1 , $reg2);
        if ($reg) {
            usort($reg, function ($a, $b) {
                return strcmp(trim($a->commune_filter), trim($b->commune_filter));
            });

            usort($reg, function ($a, $b) {
                return strcmp(trim($a->district_filter), trim($b->district_filter));
            });
        }
        foreach($reg as $key => $val){
            if($val->code_return){
                $this->db->select('tbl_order_returns.*,tblorders_shop.code_supership');
                $this->db->from('tbl_order_returns');
                $this->db->join('tblorders_shop', 'tblorders_shop.id = tbl_order_returns.order_shop_id');

                $this->db->where('tbl_order_returns.code_return' , $val->code_return);
                $this->db->like('tbl_order_returns.shop', $val->display_name, 'both');
                $orderReturn = $this->db->get()->result();
                $reg[$key]->order_return = $orderReturn;
            }else{
                $reg[$key]->order_return = [];
            }
        }

        echo json_encode($reg);

    }

    public function test() {


        $this->load->view('shiper/test.php');

    }
    public function saveImage(){

        /*write your own code to save to your database*/
        $newname = "some_name.png";
        file_put_contents( $newname, file_get_contents('php://input') );

    }
    public function get_delivery()
    {
        $json = $_GET['jsonData'];
        $data = json_decode($json);


        $this->db->select('tbldelivery_nb.orders,tblcustomers.customer_phone,tblcustomers.customer_shop_code,tbldelivery_nb.id as delivery_id,shop.*,tbldelivery_nb.date_create,tbldelivery_nb.date_report,tbldelivery_nb.code_delivery');
        $this->db->join('tblorders_shop as shop','shop.id = tbldelivery_nb.shop','left');
        $this->db->join('tblcustomers ','tblcustomers.id = tbldelivery_nb.customer_id','left');
        if(!empty($data->staff)){

            $this->db->where('tbldelivery_nb.sman',$data->staff);
        }
        if(!empty($data->code_supership)){
            $this->db->where('shop.code_supership',$data->code_supership);
        }
        if(!empty($data->date_create)){
            $this->db->where("tbldelivery_nb.date_create BETWEEN '$data->date_create 00:00:00' and '$data->date_create 23:59:59'");
        }
        if(!empty($data->address)){
            $listAddress = explode(' - ',$data->address);
            foreach ($listAddress as $key => $address){
                if($key ==0){
                    $column = "shop.ward";
                }elseif ($key==1){
                    $column = "shop.district";
                }else{
                    $column = "shop.city";
                }
                $this->db->where($column,$address);

            }
        }

        $this->db->where('date_report is NULL');
        $this->db->order_by('tbldelivery_nb.orders', 'ASC');

        $this->db->from('tbldelivery_nb');
        $kq = $this->db->get()->result();

        $result = new stdClass();
        $result->data = $kq;
        header('Content-Type: application/json');
        echo json_encode($result);

    }
    public function get_delivery_table6()
    {
        $json = $_GET['jsonData'];
        $data = json_decode($json);


        $this->db->select('tbldelivery_nb.orders,tbldelivery_nb.id as delivery_id,shop.*,tbldelivery_nb.date_create,tbldelivery_nb.date_report,tbldelivery_nb.code_delivery,tbldelivery_nb.status_report');
        $this->db->join('tblorders_shop as shop','shop.id = tbldelivery_nb.shop','left');
        if(!empty($data->staff)){

            $this->db->where('tbldelivery_nb.sman',$data->staff);
        }

        if(!empty($data->date_create_start)){
            $this->db->where("tbldelivery_nb.date_create BETWEEN '$data->date_create_start 00:00:00' and '$data->date_create_end 23:59:59'");
        }

//        $this->db->where('date_report is NULL', NULL, FALSE);
//        $this->db->order_by('tbldelivery_nb.orders', 'ASC');

        $this->db->from('tbldelivery_nb');
        $kq = $this->db->get()->result();
        $tong_so_don_bao_cao = 0;
        $tong_so_don_chua_bao_cao = 0;
        $tong_so_tien_da_thu = 0;
        $so_don_giao_that_bai = 0;
        $so_don_giao_thanh_cong = 0;
        foreach ($kq as $value){
            if($value->status_report){
                $tong_so_don_bao_cao++;
                if($value->status_report =="Đã Giao Hàng Toàn Bộ" ||$value->status_report =="Đã Giao Hàng Một Phần" ){
                    $so_don_giao_thanh_cong++ ;
                    $tong_so_tien_da_thu +=$value->collect;
                }else{
                    $so_don_giao_that_bai++;
                }
            }else{
                $tong_so_don_chua_bao_cao++;
            }
        }
        $json = new stdClass();
        $json->tong_so_don_bao_cao=$tong_so_don_bao_cao;
        $json->tong_so_don_chua_bao_cao=$tong_so_don_chua_bao_cao;
        $json->tong_so_tien_da_thu=$tong_so_tien_da_thu;
        $json->so_don_giao_that_bai=$so_don_giao_that_bai;
        $json->so_don_giao_thanh_cong=$so_don_giao_thanh_cong;
        $result = new stdClass();
        $result->data = $json;
        header('Content-Type: application/json');
        echo json_encode($result);

    }
    public function get_frist_delivery($staffId)
    {
        $listCodeSupership=[];
        $listDateCreate=[];
        $listAddress=[];
        $this->db->select('tblcustomers.customer_phone,tblcustomers.customer_shop_code,tbldelivery_nb.id as delivery_id,shop.*,tbldelivery_nb.date_create,tbldelivery_nb.date_report,tbldelivery_nb.code_delivery');
        $this->db->join('tblorders_shop as shop','shop.id = tbldelivery_nb.shop','left');
        $this->db->join('tblcustomers ','tblcustomers.id = tbldelivery_nb.customer_id','left');

        $this->db->where('tbldelivery_nb.sman',$staffId);

        $this->db->where('date_report is NULL', NULL, FALSE);

        $this->db->from('tbldelivery_nb');
        $kq = $this->db->get()->result();
        foreach ($kq as $value){
            $dataCreate = date('d/m/Y',strtotime($value->date_create));
            $address    = $value->ward ." - ".$value->district ." - ".$value->city;

            if(!in_array($value->code_supership,$listCodeSupership)){
                $listCodeSupership[]=$value->code_supership;
            }
            if(!in_array($dataCreate,$listDateCreate)){
                $listDateCreate[]=$dataCreate;
            }
            if(!in_array($address,$listAddress)){
                $listAddress[]=$address;
            }
        }


        return ['code_supership'=>$listCodeSupership,'date_create'=>$listDateCreate,'addresss'=>$listAddress];

    }

  public function confirm($id_point) {
      $date = new DateTime();
      $dateNow =$date->format('Y-m-d H:i:s');

    $currentUser = $this->staff_model->get(get_staff_user_id());
    $this->db->set('user_geted', $currentUser->staffid);
    $this->db->set('status', 1);
    $this->db->set('number_order_get', $_POST['order_get']);
    $this->db->set('modified', $dateNow);
    $this->db->where('id', $id_point);
    $this->db->update('tblpickuppoint');

    //get object
      $this->db->where('id', $id_point);
      $this->db->from('tblpickuppoint');
      $tblpickuppoint = $this->db->get()->result()[0];

      //update ngay tra order_returns
      $this->db->set('date_return', $dateNow);
      $this->db->where('code_return', $tblpickuppoint->code_return);
      $this->db->where('shop', $tblpickuppoint->display_name);
      $this->db->update('tbl_order_returns');
    $this->session->set_flashdata('success',1);
    redirect('shiper');
  }
    public function confirm_da_tra($id_point) {
        $array = explode('.', $_FILES['user_file']['name']);
        $extension = end($array);
        $dest='/uploads/da_tra/'.date("Y_m_d")."_".strtotime(date("Y/m/d H:i:s"))."_".$id_point."_trave.". $extension;
        move_uploaded_file( $_FILES['user_file']['tmp_name'], '.'.$dest);

        $date = new DateTime();
        $dateNow =$date->format('Y-m-d H:i:s');

        $currentUser = $this->staff_model->get(get_staff_user_id());
        $this->db->set('user_geted', $currentUser->staffid);
        $this->db->set('status', 1);
        $this->db->set('image', $dest);
        $this->db->set('number_order_get', $_POST['order_get']);
        $this->db->set('modified', $dateNow);
        $this->db->where('id', $id_point);
        $this->db->update('tblpickuppoint');

        //get object
        $this->db->where('id', $id_point);
        $this->db->from('tblpickuppoint');
        $tblpickuppoint = $this->db->get()->result()[0];

        //update ngay tra order_returns
        $this->db->set('date_return', $dateNow);
        $this->db->where('code_return', $tblpickuppoint->code_return);
        $this->db->where('shop', $tblpickuppoint->display_name);
        $this->db->update('tbl_order_returns');
        $this->session->set_flashdata('success',1);


        //update hinh anh tra ve cho shop


        $this->db->select('*');
        $this->db->from('tblpickuppoint');
        $this->db->where('id' , $id_point);
        $data_pickuppoint = $this->db->get()->row();

        $this->db->select('order_shop_id');
        $this->db->from('tbl_order_returns');
        $this->db->where('code_return' , $data_pickuppoint->code_return);
        $this->db->like('shop', $data_pickuppoint->display_name, 'both');
        $list_ordershop = $this->db->get()->result();

        foreach ($list_ordershop as $orderShopId){
            //update hinh anh tra ve cho shop
            $data['order_shop_id']=$orderShopId;
            $data['image_return']=$dest;
            $this->db->insert('tblorder_images', $data);

        }



        redirect('shiper');
    }



  public function checkReg($id_point='')
  {
    $query = $this->db->get_where('tblpickuppoint', array('id' => $id_point))->row();

    if ($query->user_reg === NULL || $query->user_reg === "0") {
      return true;
    }else {
      return false;
    }

  }
  public function reg($id_point) {

    $checkReg = $this->checkReg($id_point);

    if ($checkReg) {
      $date = new DateTime();
      $currentUser = $this->staff_model->get(get_staff_user_id());
      $this->db->set('user_reg', $currentUser->staffid);
      $this->db->set('status', 2);
      $this->db->set('modified', $date->format('Y-m-d H:i:s'));
      $this->db->where('id', $id_point);
      $this->db->update('tblpickuppoint');
      $this->session->set_flashdata('success2',1);
      redirect('shiper');
    }else {
      $this->session->set_flashdata('error3',1);
      redirect('shiper');
    }



  }


  public function un_reg($id_point)
  {
    $date = new DateTime();
    $currentUser = NULL;
    $this->db->set('user_reg', $currentUser);
    $this->db->set('status', 0);
    $this->db->set('modified', $date->format('Y-m-d H:i:s'));
    $this->db->where('id', $id_point);
    $this->db->update('tblpickuppoint');
    $this->session->set_flashdata('success3',1);
    redirect('shiper');
  }
}
