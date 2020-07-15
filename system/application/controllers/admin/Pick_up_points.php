<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pick_up_points extends AdminController {


    public function get_code_hd() {


      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.mysupership.vn/v1/partner/areas/province",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "Accept: */*",
        ),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
        echo "cURL Error #:" . $err;
      } else {
        $result = json_decode($response)->results;
        for ($i=0; $i < sizeof($result); $i++) {

          if ($result[$i]->name === "Tỉnh Hải Dương") {
            return $result[$i]->code;
          }
        }
      }
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
            $data['order_shop_id']=$orderShopId->order_shop_id;
            $data['image_return']=$dest;
            $this->db->insert('tblorder_images', $data);

        }

        redirect('admin/pick_up_points');
    }
    public function get_district_by_hd($code) {

      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.mysupership.vn/v1/partner/areas/district?province=".$code,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "Accept: */*",
        ),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);
      if ($err) {

      } else {
        $result = json_decode($response)->results;
        return $result;
      }

    }

    public function get_commune_by_hd($code)
    {
        $this->db->select('commune as code,commune as name')->distinct();
        $this->db->from('tbladdress_list');
        $this->db->where('district_id', $code);

        $purchases = $this->db->get()->result();

        echo json_encode($purchases);

    }
    public function get_commune_by_hd_view($code)
    {
        $this->db->select('commune as code,commune as name')->distinct();
        $this->db->from('tbladdress_list');
        $this->db->where('district_id', $code);

        $purchases = $this->db->get()->result();

        return $purchases;

    }
      public function indexPicked() {
        if ($this->input->is_ajax_request()) {
          $this->app->get_table_data('pick_up_points_picked');
        }
      }
    /* In case if user go only on /payments */
    public function index() {
      
      $code_hd = $this->get_code_hd();
      $district_hd = $this->get_district_by_hd($code_hd);


      if ($this->input->is_ajax_request()) {
        $this->app->get_table_data('pick_up_points');
      }

      $data = array();

      $data['custommer'] = $this->db->get_where('tblcustomers',array('active' => 1))->result();

      for ($i=0; $i < 5; $i++) {
        $data_mockup = new \stdClass;
        $data_mockup->name_repo = 'Demo '.$i;
        $data_mockup->id = $i;
        $data['repo'][] = $data_mockup;
      }

      $data['district_hd'] = $district_hd;
      $data['area_hd'] =  $this->get_commune_by_hd_view($district_hd[0]->code);
      $data['tblstaff'] = $this->db->get('tblstaff')->result();
      $this->load->view('admin/pick_up_points/index.php',$data);
    }
    public function danh_sach_don($id){
        $list =[];
        $this->db->select('*');
         $this->db->where('id' , $id);
        $this->db->from('tblpickuppoint');
        $val = $this->db->get()->row();

        $this->db->select('tbl_order_returns.*,tblorders_shop.code_supership');
        $this->db->from('tbl_order_returns');
        $this->db->join('tblorders_shop', 'tblorders_shop.id = tbl_order_returns.order_shop_id');

        $this->db->where('tbl_order_returns.code_return' , $val->code_return);
        $this->db->like('tbl_order_returns.shop', $val->display_name, 'both');
        $orderReturn = $this->db->get()->result();
        foreach ($orderReturn as $value){
            $list[]=$value->code_supership;
        }
        echo json_encode($list);




    }
    public function getCustomer($id) {
      $cus = $this->db->get_where('tblcustomers', array('id' => $id))->result()[0];
      echo json_encode($cus);
    }


    public function edit($id){

      if ($_POST['type_customer'] === 'old') {
        unset($_POST['search_customer']);
        unset($_POST['type_customer']);
        unset($_POST['phone_customer_new']);
        unset($_POST['district']);
        unset($_POST['area_hd']);
        unset($_POST['free_address']);





        $date = new DateTime();
        $date->format('Y-m-d H:i:s');
        $_POST['modified'] = $date->format('Y-m-d H:i:s');
        $_POST['created'] = date('Y/m/d',strtotime($_POST['created']));

        $this->db->where('id', $id);
        $this->db->update('tblpickuppoint', $_POST);
        $this->session->set_flashdata('success_edit',1);
        redirect(admin_url('pick_up_points'));
      }else {
        unset($_POST['type_customer']);
        unset($_POST['search_customer']);

        $date = new DateTime();
        $date->format('Y-m-d H:i:s');
        $_POST['modified'] = $date->format('Y-m-d H:i:s');
        $_POST['created'] = date('Y/m/d',strtotime($_POST['created']));
        $_POST['phone_customer'] = $_POST['phone_customer_new'];


        $_POST['repo_customer'] = $_POST['free_address'].', '.json_decode($_POST['area_hd'])->name.', '.json_decode($_POST['district'])->name;
        $_POST['district_code'] = $_POST['district'];
        $_POST['areas_code'] = $_POST['area_hd'];
        unset($_POST['district']);
        unset($_POST['area_hd']);
        unset($_POST['free_address']);
        unset($_POST['phone_customer_new']);

        $this->db->where('id', $id);
        $this->db->update('tblpickuppoint', $_POST);
        $this->session->set_flashdata('success_edit',1);
        redirect(admin_url('pick_up_points'));
      }


    }

    public function add(){
      if (sizeof($_POST) == 0) {
        redirect(admin_url('Pick_up_points'));
      }


      if ($_POST['type_customer'] === 'old') {

        $_POST['display_name'] = $_POST['search_customer'];
        unset($_POST['search_customer']);
        unset($_POST['type_customer']);
        unset($_POST['phone_customer_new']);
        unset($_POST['district']);
        unset($_POST['area_hd']);
        unset($_POST['free_address']);




        $date = new DateTime();
        $date->format('Y-m-d H:i:s');
        $_POST['modified'] = $date->format('Y-m-d H:i:s');
        $_POST['created'] = date('Y/m/d',strtotime($_POST['created']));

        $id = $this->db->insert('tblpickuppoint', $_POST);


        if ($id) {
          $this->session->set_flashdata('success',1);
        }
        redirect(admin_url('pick_up_points'));
      }else {
        unset($_POST['type_customer']);
        unset($_POST['search_customer']);
        $_POST['display_name'] = $_POST['name_customer_new'];




        $date = new DateTime();
        $date->format('Y-m-d H:i:s');
        $_POST['modified'] = $date->format('Y-m-d H:i:s');
        $_POST['created'] = date('Y/m/d',strtotime($_POST['created']));

        $_POST['phone_customer'] = $_POST['phone_customer_new'];


        $_POST['repo_customer'] = $_POST['free_address'].', '.json_decode($_POST['area_hd'])->name.', '.json_decode($_POST['district'])->name;
        $_POST['district_code'] = $_POST['district'];
        $_POST['areas_code'] = $_POST['area_hd'];
        unset($_POST['district']);
        unset($_POST['area_hd']);
        unset($_POST['free_address']);
        unset($_POST['phone_customer_new']);
        $id = $this->db->insert('tblpickuppoint', $_POST);


        if ($id) {
          $this->session->set_flashdata('success',1);
        }
        redirect(admin_url('pick_up_points'));
      }

    }

    public function delete($id){
      $tables = array('tblpickuppoint');
      $this->db->where('id', $id);
      $delete = $this->db->delete($tables);
      $this->session->set_flashdata('delete_ok',1);
      redirect(admin_url('/pick_up_points'));
    }

    public function getDataEdit($id) {
      $data = $this->db->get_where('tblpickuppoint', array('id' => $id))->result()[0];
      echo json_encode($data);
    }



    public function edit_status(){

      $this->db->where('id', $_POST['id']);

      if ($_POST['status'] === 'true') {
        $_POST['status'] = 1;
      }else {
        $_POST['status'] = 0;
      }
      $date = new DateTime();
      $date->format('Y-m-d H:i:s');
      $_POST['modified'] = $date->format('Y-m-d H:i:s');
      $_POST['user_geted'] = get_staff_user_id();

      if ($_POST['status'] !== 'true' && $_POST['mod']) {
        $_POST['user_geted'] = null;
        $_POST['user_reg'] = null;
        unset($_POST['mod']);
      }


      $update = $this->db->update('tblpickuppoint', $_POST);
      if ($update) {

        echo $_POST['status'];
      }
      die();
    }


    public function edit_status_staff(){

      $this->db->where('id', $_POST['id']);

      if ($_POST['status'] === 'true') {
        $_POST['status'] = 1;
      }else {
        $_POST['status'] = 0;
      }
      $date = new DateTime();
      $date->format('Y-m-d H:i:s');
      $_POST['modified'] = $date->format('Y-m-d H:i:s');


      if ($_POST['status'] !== 'true' && $_POST['mod']) {
        $_POST['user_reg'] = null;
        unset($_POST['mod']);
      }


      $update = $this->db->update('tblpickuppoint', $_POST);
      if ($update) {

        echo $_POST['status'];
      }
      die();
    }


    public function edit_status_only_number(){




      $date = new DateTime();
      $date->format('Y-m-d H:i:s');
      $_POST['modified'] = $date->format('Y-m-d H:i:s');
      unset($_POST['mod']);


      $this->db->where('id', $_POST['id']);
      $update = $this->db->update('tblpickuppoint', $_POST);


      if ($update) {

        echo $_POST['status'];
      }
      die();
    }


  public function curlGetRepo() {

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.mysupership.vn/v1/partner/warehouses",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Accept: */*",
        "Authorization: Bearer ".$_POST['token'],
        "Cache-Control: no-cache",
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
      die();
    } else {

      echo json_encode(json_decode($response)->results);
      die();
    }

  }


}
