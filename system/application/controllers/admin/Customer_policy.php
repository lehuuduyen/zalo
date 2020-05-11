<?php

defined('BASEPATH') or exit('No direct script access allowed');
// views/admin/customer_policy
// /controllers/admin
class Customer_policy extends AdminController
{
  function __construct(){
      parent::__construct();
      $this->load->model('Regions_model');
  }


  public function customer_policy_id($id) {

    $dataPoliCy = $this->db->get_where('customer_policy', array('id' => $id))->result()[0];

    $data_table_region = $this->db->get_where('tbldata_region', array('id_policy' => $dataPoliCy->id))->result();


    for ($i=0; $i < sizeof($data_table_region) ; $i++) {
      $data =  new \stdClass();

      $dataEachRegion = $this->db->get_where('tbldeclared_region', array('id' => $data_table_region[$i]->id_region))->result()[0];

      $data_table_region[$i]->name_region = $dataEachRegion->name_region;
      $data =  $data_table_region[$i];


      $dataPoliCy->data_table_region[] = $data ;
    }
    echo json_encode($dataPoliCy);
    die();

  }

  public function data_edit($id) {

    $dataRegion = $this->db->get_where('declared_region', array('id' => $id))->result();

    echo json_encode($dataRegion);
    die();
  }


  public function check_customer_policy($id) {

    $dataRegion = $this->db->get_where('tblcustomer_policy', array('customer_id' => $id))->result();

    echo json_encode($dataRegion);
    die();
  }

  public function edit_region(){
    if ($this->input->server('REQUEST_METHOD') == 'POST') {
      $_POST['price_region'] = str_replace(',','',$_POST['price_region']);
      $_POST['mass_region'] = str_replace(',','',$_POST['mass_region']);
      $_POST['mass_region_free'] = str_replace(',','',$_POST['mass_region_free']);
      $_POST['volume_region'] = str_replace(',','',$_POST['volume_region']);
      $_POST['volume_region_free'] = str_replace(',','',$_POST['volume_region_free']);
      $_POST['price_over_mass_region'] = str_replace(',','',$_POST['price_over_mass_region']);
      $_POST['price_over_volume_region'] = str_replace(',','',$_POST['price_over_volume_region']);
      $_POST['amount_of_free_insurance'] = str_replace(',','',$_POST['amount_of_free_insurance']);



      $this->db->where('id', $_POST['id_region']);
      unset($_POST['id_region']);

      $update = $this->db->update('tbldeclared_region', $_POST);

      if($update){
        $this->session->set_flashdata('success2',1);
      }

      redirect(admin_url('/customer_policy/declared_region'));

    }
  }


  /* List Polyci */
  public function index() {

    if ($this->input->is_ajax_request()) {
      $this->app->get_table_data('customer_policy');
    }


    $dataRegions = $this->db->get('declared_region')->result();
    $dataCustomers = $this->db->get_where('tblcustomers', array('active' => 1))->result();
    $data = array('dataRegions' => $dataRegions , 'dataCustomers' => $dataCustomers );
    $data['customer'] = get_table_where('tblcustomers');
    $this->load->view('admin/customer_policy/index.php', $data);

  }




  public function declared_region() {
    $data = array();



    if (!is_admin()) {
        access_denied('contracts');
    }


    if ($this->input->is_ajax_request()) {

      $this->app->get_table_data('declared_region');

    }else {
      if ($this->input->server('REQUEST_METHOD') == 'POST') {
        $_POST['price_region'] = str_replace(',','',$_POST['price_region']);
        $_POST['mass_region'] = str_replace(',','',$_POST['mass_region']);
        $_POST['mass_region_free'] = str_replace(',','',$_POST['mass_region_free']);
        $_POST['volume_region'] = str_replace(',','',$_POST['volume_region']);
        $_POST['volume_region_free'] = str_replace(',','',$_POST['volume_region_free']);
        $_POST['price_over_mass_region'] = str_replace(',','',$_POST['price_over_mass_region']);
        $_POST['price_over_volume_region'] = str_replace(',','',$_POST['price_over_volume_region']);
        $_POST['amount_of_free_insurance'] = str_replace(',','',$_POST['amount_of_free_insurance']);


        $id = $this->Regions_model->add_regions($_POST);

        if($id){
          $this->session->set_flashdata('success',1);
        }

        redirect(admin_url('/customer_policy/declared_region'));

      }
    }
    $data['title'] = _l('Vùng Miền');


    $this->load->view('admin/customer_policy/declared_region.php', $data);


  }

  public function delete_region($id) {
    if ($this->input->is_ajax_request()) {
      $this->db->delete('tblregion_excel', array('region_id' => $id));
      $idD = $this->db->delete('tbldata_region', array('id_region' => $id));
      $this->db->delete('tbldeclared_region', array('id' => $id));
    }

  }

  public function add_file_region()
  {

      require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'PHPExcel.php');
      $this->load->helper('security');


      if (!empty($_FILES))
      {

          $count_add = 0;
          $count_update = 0;

          $id_region = $_POST['id'];


          foreach($_FILES['file_region']['name'] as $KF => $VF) {
              $fullfile = $_FILES['file_region']['tmp_name'][$KF];


              $extension = strtoupper(pathinfo($_FILES['file_region']['name'][$KF], PATHINFO_EXTENSION));

              if($extension != 'XLSX' && $extension != 'XLS'){
                  $this->session->set_flashdata('warning', lang('Không đúng định dạng excel'));
                  redirect($_SERVER["HTTP_REFERER"]);
                  return;
              }

              $inputFileType = PHPExcel_IOFactory::identify($fullfile); $objReader = PHPExcel_IOFactory::createReader($inputFileType);

              $objReader->setReadDataOnly(true);
              $objPHPExcel = $objReader->load("$fullfile");
              $total_sheets = $objPHPExcel->getSheetCount();
              $allSheetName = $objPHPExcel->getSheetNames();
              $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
              $highestRow = $objWorksheet->getHighestRow();
              $highestColumn = $objWorksheet->getHighestColumn();
              $highestColumnIndex = PHPExcel_Cell::columnIndexFromString('AC');

              $array_colum = array();
              for ($row = 1; $row <= $highestRow; ++$row)
              {
                  for ($col = 0; $col < $highestColumnIndex; ++$col)
                  {
                      $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                      $array_colum[$row - 1][$col] = $value;
                  }
              }


              if(count($array_colum) > 1)
              {

                  $colum_table = $this->db->list_fields('tblregion_excel');
                  $this->db->delete('tblregion_excel', array('region_id' => $id_region));
                  foreach ($array_colum as $key => $row)
                  {

                      if($key > 0)
                      {

                          $array_add = [];
                          foreach($colum_table as $kColum => $vColum)
                          {


                            if ($kColum === 0) {
                              $array_add['city'] = $row[$kColum];
                            }
                            if ($kColum === 1) {
                              $array_add['District'] = $row[$kColum];
                            }


                          }
                          $array_add['region_id'] = $id_region;

                          $this->db->insert('tblregion_excel', $array_add);

                      }
                  }

              }
          }


          echo json_encode(array('success' => true, 'alert_type' => 'success', 'message' => 'ok'));die();


      }
      echo json_encode(array('success' => false, 'alert_type' => 'danger', 'message' => 'Không tìm thấy file' ));die();
  }

  public function detail_region() {

    if ($this->input->is_ajax_request()) {

      $this->app->get_table_data('region_excel');

    }
  }

  public function updatePoStatus($id , $status)
  {
    $this->db->set('policy', $status);
    $this->db->where('id', $id);
    $update = $this->db->update('tblcustomers');
  }

  public function goUpdate(){
    $query = $this->db->get('tblcustomer_policy')->result();
    if (sizeof($query) > 0) {
      foreach ($query as $key => $value) {
        $this->updatePoStatus($value->customer_id,1);
      }
    }
  }

  public function add_policy_customer() {

    if ($this->input->is_ajax_request()) {


      $data_table_region = $_POST['data_table_region'];

      unset($_POST['data_table_region']);



      $this->updatePoStatus($_POST['customer_id'],1);
      $_POST['control_schedule'] = implode(",",$_POST['control_schedule']);
      $id = $this->Regions_model->add_policy($_POST);






      if ($id) {

        for ($i=0; $i < sizeof($data_table_region['id_region']) ; $i++) {
          $dataAddRegion_policy = new \stdClass();

          $dataAddRegion_policy->id_region = str_replace(',','',$data_table_region['id_region'][$i]);
          $dataAddRegion_policy->insurance_price = str_replace(',','',$data_table_region['insurance_price'][$i]);

          $dataAddRegion_policy->mass_region = str_replace(',','',$data_table_region['mass_region'][$i]);


          $dataAddRegion_policy->mass_region_free =
          str_replace(',','',$data_table_region['mass_region_free'][$i]);

          $dataAddRegion_policy->price_over_mass_region = str_replace(',','',$data_table_region['price_over_mass_region'][$i]);


          $dataAddRegion_policy->price_over_volume_region = str_replace(',','',$data_table_region['price_over_volume_region'][$i]);


          $dataAddRegion_policy->price_region = str_replace(',','',$data_table_region['price_region'][$i]);

          $dataAddRegion_policy->volume_region =
          str_replace(',','',$data_table_region['volume_region'][$i]);


          $dataAddRegion_policy->volume_region_free = str_replace(',','',$data_table_region['volume_region_free'][$i]);


          $dataAddRegion_policy->amount_of_free_insurance =
          str_replace(',','',$data_table_region['amount_of_free_insurance'][$i]);


          $dataAddRegion_policy->fee_back_new =
          str_replace(',','',$data_table_region['fee_back_new'][$i]);

          $dataAddRegion_policy->id_policy = $id;

          $dataPoliCy[] = $dataAddRegion_policy;
        }

        $insert = $this->db->insert_batch('tbldata_region', $dataPoliCy);
        if ($insert) {
          echo json_encode(array('success' => true,'data'=>$_POST));
        }else {
          echo json_encode(array('success' => false));
        }

      }else {
        echo json_encode(array('success' => false));
      }


    }
  }






  public function edit_policy_customer() {

    if ($this->input->is_ajax_request()) {


      $data_table_region = $_POST['data_table_region'];

      unset($_POST['data_table_region']);


      $this->db->where('id' , $_POST['id']);
      $_POST['control_schedule'] = implode(",",$_POST['control_schedule']);
      $update = $this->db->update('tblcustomer_policy', $_POST);



      if ($update === true) {



        for ($i=0; $i < sizeof($data_table_region['id_region']) ; $i++) {
          $dataAddRegion_policy = new \stdClass();
          $dataAddRegion_policy->id = $data_table_region['id'][$i];
          $dataAddRegion_policy->id_region = str_replace(',','',$data_table_region['id_region'][$i]);

          $dataAddRegion_policy->insurance_price = str_replace(',','',$data_table_region['insurance_price'][$i]);



          $dataAddRegion_policy->mass_region = str_replace(',','',$data_table_region['mass_region'][$i]);


          $dataAddRegion_policy->mass_region_free = str_replace(',','',$data_table_region['mass_region_free'][$i]);


          $dataAddRegion_policy->price_over_mass_region = str_replace(',','',$data_table_region['price_over_mass_region'][$i]);



          $dataAddRegion_policy->price_over_volume_region = str_replace(',','',$data_table_region['price_over_volume_region'][$i]);


          $dataAddRegion_policy->price_region = str_replace(',','',$data_table_region['price_region'][$i]);



          $dataAddRegion_policy->volume_region = str_replace(',','',$data_table_region['volume_region'][$i]);


          $dataAddRegion_policy->volume_region_free = str_replace(',','',$data_table_region['volume_region_free'][$i]);

          $dataAddRegion_policy->fee_back_new = str_replace(',','',$data_table_region['fee_back_new'][$i]);

          $dataAddRegion_policy->amount_of_free_insurance = str_replace(',','',$data_table_region['amount_of_free_insurance'][$i]);

          $dataAddRegion_policy->id_policy = $_POST['id'];
          $dataPoliCy[] = $dataAddRegion_policy;
        }


        $update_batch = $this->db->update_batch('tbldata_region', $dataPoliCy, 'id');

        if ($update_batch) {
          echo json_encode(array('success' => true,'data'=>$_POST));
        }else {
          echo json_encode(array('success' => false));
        }

      }else {
        echo json_encode(array('error' => false));
      }


    }
  }


  public function delete($id='') {

    $query = $this->db->get_where('tblcustomer_policy', array('id' => $id));

    $this->updatePoStatus($query->row()->customer_id , 0);
    $idD = $this->db->delete('tblcustomer_policy', array('id' => $id));
    $idD = $this->db->delete('tbldata_region', array('id_policy' => $id));

    if ($idD) {
      $this->session->set_flashdata('success',1);
    }
    redirect(admin_url('/customer_policy'));
    die();
  }

}
