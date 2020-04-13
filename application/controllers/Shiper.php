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
    // $_GET['limit'];
    $this->db->select('*');
    $this->db->select('tblpickuppoint.id as id');
    $this->db->select('tblstaff.firstname as firstname');
    $this->db->select('tblstaff.lastname as lastname');
    $this->db->where('status' , 1);
    $this->db->from('tblpickuppoint');
    // $this->db->join('tblcustomers', 'tblcustomers.id = tblpickuppoint.customer_id');
    $this->db->join('tblstaff', 'tblstaff.staffid = tblpickuppoint.user_geted' , 'left');
    if ($_GET['limit'] !== 'all') {
      $this->db->limit($_GET['limit']);
    }

    $this->db->order_by('modified', 'DESC');
    $geteds1 = $this->db->get()->result();


    $geteds = array_merge($geteds1);

    echo json_encode($geteds);
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



    $data['waits'] = $waits;
    $data['regs'] = $reg;
    $data['user_display'] = $user_display;
    $data['tab2'] = false;
    if (!empty($_GET)) {
      if ($_GET['tab'] === '1') {
        $data['tab1'] = true;
      }
    }
    $this->load->view('shiper/index.php',$data);

  }



  public function confirm($id_point) {

    $date = new DateTime();
    $currentUser = $this->staff_model->get(get_staff_user_id());
    $this->db->set('user_geted', $currentUser->staffid);
    $this->db->set('status', 1);
    $this->db->set('number_order_get', $_POST['order_get']);
    $this->db->set('modified', $date->format('Y-m-d H:i:s'));
    $this->db->where('id', $id_point);
    $this->db->update('tblpickuppoint');
    $this->session->set_flashdata('success',1);
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
