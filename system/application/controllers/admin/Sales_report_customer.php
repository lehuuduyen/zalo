<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sales_report_customer extends AdminController {
    public function __construct()
    {
        parent::__construct();
    }

    /* View all settings */
    public function index() {
      $data['customer'] = get_table_where('tblcustomers');
      $data['date_end'] = date('Y-m-d');
      $date = new DateTime($data['date_end']);
      date_sub($date, date_interval_create_from_date_string('30 days'));
      $data['date_start'] = date_format($date, 'Y-m-d');
      $this->load->view('admin/sale_report/index', $data);
    }



    public function load_sales_report_customer_detail(){
      $start_date = $this->input->post('date_start_customer');
      $start_end = $this->input->post('date_end_customer');


      if ($start_date == "") {
          $start_date = NULL;
      }
      if ($start_end == "") {
          $start_end = NULL;
      }
      if (isset($start_end) && isset($start_date)) {
          $start_date = to_sql_date($start_date);
          $start_end = to_sql_date($start_end);
      } else if ((!isset($start_date)) && isset($start_end)) {
          $start_end = to_sql_date($start_end);
          $date = new DateTime($start_end);
          date_sub($date, date_interval_create_from_date_string('30 days'));
          $start_date = date_format($date, 'Y-m-d');
      } else if (isset($start_date) && !isset($start_end)) {
          $start_date = to_sql_date($start_date);
          $date = new DateTime($start_date);
          $start_end = date("Y-m-d", strtotime("$date +30 day"));
      } else if (!isset($start_date) && !isset($start_end)) {
          $start_end = date('Y-m-d');
          $date = new DateTime($start_end);;
          date_sub($date, date_interval_create_from_date_string('30 days'));
          $start_date = date_format($date, 'Y-m-d');
      }
      if(!empty($start_date))
      {
          $date = new DateTime($start_date);;
          date_sub($date, date_interval_create_from_date_string('1 days'));
          $startdauky = date_format($date, 'Y-m-d'). ' ' . date('23:59:59');
      }

      if ($start_date == "") {
          $start_date = NULL;
      }
      if ($start_end == "") {
          $start_end = NULL;
      }

      $start_date = $start_date . ' 00:00:00';
      $start_end = $start_end . ' ' . date('23:59:59');

      $id_customer = $this->input->post('id_customer');
      $id_rows_customer = $this->input->post('id_rows_customer');
      $customer = get_table_where('tblcustomers');


      $data_aaDATA = $this->get_real_revenue_by_shop_code_array($_POST['shop_name'],$start_date ,$start_end);



      $aColumns     = array(
        'date_debits',
        'date_create',
        'code_supership',
        'status',
        'total_real_revenue',
        'note' ,
        'mass' ,
        'receiver' ,
        'city' ,
        'district' ,
        'collect',
        'hd_fee'
      );


      usort($data_aaDATA, function ($item1, $item2) {
          if ($item1->date_debits == $item2->date_debits) return 0;
          return $item1->date_debits > $item2->date_debits ? -1 : 1;
      });




      $dataTableInit = [
        "aaData" => $this->object_to_array_customer_detail($data_aaDATA,$aColumns),
        "draw" =>  $_POST['draw'],
        "iTotalDisplayRecords" => sizeof($dataTable),
        "iTotalRecords"=> sizeof($dataTable)
      ];

      echo json_encode($dataTableInit);
    }

    public function load_sales_report_customer(){
      $start_date = $this->input->post('date_start_customer');
      $start_end = $this->input->post('date_end_customer');


      if ($start_date == "") {
          $start_date = NULL;
      }
      if ($start_end == "") {
          $start_end = NULL;
      }
      if (isset($start_end) && isset($start_date)) {
          $start_date = to_sql_date($start_date);
          $start_end = to_sql_date($start_end);
      } else if ((!isset($start_date)) && isset($start_end)) {
          $start_end = to_sql_date($start_end);
          $date = new DateTime($start_end);
          date_sub($date, date_interval_create_from_date_string('30 days'));
          $start_date = date_format($date, 'Y-m-d');
      } else if (isset($start_date) && !isset($start_end)) {
          $start_date = to_sql_date($start_date);
          $date = new DateTime($start_date);
          $start_end = date("Y-m-d", strtotime("$date +30 day"));
      } else if (!isset($start_date) && !isset($start_end)) {
          $start_end = date('Y-m-d');
          $date = new DateTime($start_end);;
          date_sub($date, date_interval_create_from_date_string('30 days'));
          $start_date = date_format($date, 'Y-m-d');
      }
      if(!empty($start_date))
      {
          $date = new DateTime($start_date);;
          date_sub($date, date_interval_create_from_date_string('1 days'));
          $startdauky = date_format($date, 'Y-m-d'). ' ' . date('23:59:59');
      }

      if ($start_date == "") {
          $start_date = NULL;
      }
      if ($start_end == "") {
          $start_end = NULL;
      }

      $start_date = $start_date . ' 00:00:00';
      $start_end = $start_end . ' ' . date('23:59:59');

      $id_customer = $this->input->post('id_customer');
      $id_rows_customer = $this->input->post('id_rows_customer');
      $customer = get_table_where('tblcustomers');



      if ($id_customer == "") {
        $customerLoad = $customer;

      }else {
        foreach ($customer as $key => $value) {
          if ($id_customer == $value['id']) {
            $customerLoad[] = $value;
          }
        }
      }

      $data_aaDATA;
      foreach ($customerLoad as $key => $value) {

        $data_push['customer_monitoring'] = $this->get_staff($value['customer_monitoring']);
        $data_push['shop'] = $value['customer_shop_code'];
        $get_db_shop = $this->get_real_revenue_by_shop_code($value['customer_shop_code'],$start_date ,$start_end);
        $data_push['real_revenue'] = $get_db_shop;
        $data_aaDATA[] = $data_push;
      }

      $aColumns     = array(
        'customer_monitoring',
        'shop',
        'real_revenue',
      );

      usort($data_aaDATA, function ($item1, $item2) {
          if ($item1['real_revenue'] == $item2['real_revenue']) return 0;
          return $item1['real_revenue'] > $item2['real_revenue'] ? -1 : 1;
      });

      $dataTableInit = [
        "aaData" => $this->object_to_array_customer($data_aaDATA,$aColumns),
        "draw" =>  $_POST['draw'],
        "iTotalDisplayRecords" => sizeof($dataTable),
        "iTotalRecords"=> sizeof($dataTable),
        "calc_total_s_e" => $this->calc_total_s_e($start_date,$start_end)
      ];

      echo json_encode($dataTableInit);
    }

  public function get_staff($id)
  {
    $this->db->select('firstname , lastname');
    $this->db->where('staffid', $id);
    $data = $this->db->get('tblstaff')->row();

    return $data->lastname . ' '. $data->firstname;

  }

  public function get_real_revenue_by_shop_code($code,$s,$e) {
    $this->db->select('SUM(real_revenue) as total_real_revenue');
    $this->db->where('shop', $code);

    $this->db->where('date_debits >=', $s);
    $this->db->where('date_debits <=', $e);
    $data = $this->db->get('tblorders_shop')
    ->row();

    return $data->total_real_revenue;
  }

  function object_to_array_customer_detail($data,$aColumns) {

    $j=0;
    $data_aaDATA = [];
    foreach ($data as $aRow) {
        $row = array();
        $j++;


        $aRow = json_decode(json_encode($aRow), True);

        for ($i = 0; $i < count($aColumns); $i++) {
          $_data = $aRow[$aColumns[$i]];
          $row[] = $_data;
        }
          $row[4] = number_format($row[4]);
        $row[5] = 'Thu hộ:' . number_format($row[10]) . ', Phí:'. $row[11] .
        ' ( KL:'. $row[6] .', '.  $row[7]  .' - '.  $row[8]  .' - '.  $row[9]  . ' )';



        $data_aaDATA[] =  $row;
    }
    return $data_aaDATA;
  }

  public function calc_total_s_e( $s , $e) {

    $this->db->select('SUM(real_revenue) as total_real_revenue');

    $this->db->where('date_debits >=', $s);
    $this->db->where('date_debits <=', $e);
    $data = $this->db->get('tblorders_shop')
    ->row();

    if ($data->total_real_revenue === NULL) {
      return 0;
    }
    return round($data->total_real_revenue);
  }

  public function get_real_revenue_by_shop_code_array($code,$s,$e) {

    $this->db->select('date_debits , date_create  , code_supership , status ,real_revenue as total_real_revenue , note , mass , receiver , city , district , collect , hd_fee');
    $this->db->where('shop', $code);

    $this->db->where('date_debits >=', $s);
    $this->db->where('date_debits <=', $e);
    $data = $this->db->get('tblorders_shop')
    ->result();

    return $data;
  }

  function object_to_array_customer($data,$aColumns) {

    $j=0;
    $data_aaDATA = [];
    foreach ($data as $aRow) {
        $row = array();
        $j++;


        $aRow = json_decode(json_encode($aRow), True);

        for ($i = 0; $i < count($aColumns); $i++) {
          $_data = $aRow[$aColumns[$i]];
          $row[] = $_data;
        }

        if (is_admin()) {
            $icon_get_data = "<a style='padding: 3px;' class='btn btn-default btn-icon get_data_sale_report' href='javascript:;'  data-shop='" .$aRow['shop']."'". ">"."
            <i class='fa fa-eye' ></i>
          </a>";

            $row[] =$icon_get_data.'</div>';

        } else {
            $row[] = '';
        }

        $row[2] = number_format($row[2]);


        $data_aaDATA[] =  $row;
    }
    return $data_aaDATA;
  }


}
