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

      $curl = curl_init();
      //
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.mysupership.vn/v1/partner/areas/commune?district=".$code,
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
      //
      $response = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);
      if ($this->input->is_ajax_request()) {
        echo json_encode(json_decode($response)->results);
        die();
      }else {
        return json_decode($response)->results;
      }


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
      $data['area_hd'] =  $this->get_commune_by_hd($district_hd[0]->code);
      $data['tblstaff'] = $this->db->get('tblstaff')->result();
      $this->load->view('admin/pick_up_points/index.php',$data);
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
