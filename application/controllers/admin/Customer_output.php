<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Customer_output extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    /* View all settings */
    public function index()
    {
        $data['customer'] = get_table_where('tblcustomers');
        $data['date_end'] = date('Y-m-d');
        $date = new DateTime($data['date_end']);
        date_sub($date, date_interval_create_from_date_string('30 days'));
        $data['date_start'] = date_format($date, 'Y-m-d');

        $this->load->view('admin/customer_output/index', $data);
    }

    public function get_order_sucess_by_shop_code($s, $e, $code)
    {
        $status_name = array("Đã Giao Hàng Một Phần", "Đã Giao Hàng Toàn Bộ", "Đã Đối Soát Giao Hàng");
        $this->db->select('COUNT(id) as total_success');
        $this->db->where('shop', $code);
        $this->db->where('date_create >=', $s);
        $this->db->where('date_create <=', $e);

        $this->db->where_in('status', $status_name);
        $data = $this->db->get('tblorders_shop')
      ->row();


        return $data->total_success;
    }

    public function get_order_faill_by_shop_code($s, $e, $code)
    {
        $status_name = array("Không Giao Được", "Xác Nhận Hoàn", "Đang Trả Hàng", "Đang Chuyển Kho Trả", "Đã Đối Soát Trả Hàng", "Đã Chuyển Kho Trả");
        $this->db->select('COUNT(id) as total_faill');
        $this->db->where('shop', $code);
        $this->db->where('date_create >=', $s);
        $this->db->where('date_create <=', $e);

        $this->db->where_in('status', $status_name);
        $data = $this->db->get('tblorders_shop')
      ->row();

        return $data->total_faill;
    }


    public function get_order_shiping_by_shop_code($s, $e, $code)
    {
        $status_name = array("Chờ Lấy Hàng","Đã Nhập Kho","Đang Chuyển Kho Giao", "Đang Vận Chuyển","Đã Chuyển Kho Giao","Đang Giao Hàng","Hoãn Giao Hàng");
        $this->db->select('COUNT(id) as total_shiping');
        $this->db->where('shop', $code);
        $this->db->where('date_create >=', $s);
        $this->db->where('date_create <=', $e);

        $this->db->where_in('status', $status_name);
        $data = $this->db->get('tblorders_shop')
      ->row();

        return $data->total_shiping;
    }


    public function get_order_by_code($s, $e, $code)
    {
        $status_name1 = array("Đã Giao Hàng Một Phần", "Đã Giao Hàng Toàn Bộ", "Đã Đối Soát Giao Hàng");
        $status_name2 = array("Không Giao Được", "Xác Nhận Hoàn", "Đang Trả Hàng", "Đang Chuyển Kho Trả", "Đã Đối Soát Trả Hàng", "Đã Chuyển Kho Trả");
        $status_name3 = array("Chờ Lấy Hàng"," Đã Nhập Kho"," Đang Chuyển Kho Giao", "Đang Vận Chuyển"," Đã Chuyển Kho Giao"," Đang Giao Hàng"," Hoãn Giao Hàng");

        $status_name = array_merge($status_name1, $status_name2, $status_name3);

        $this->db->select('date_create , code_supership , status , note , mass , receiver , city , district , collect , hd_fee');
        $this->db->where('shop', $code);
        $this->db->where('date_create >=', $s);
        $this->db->where('date_create <=', $e);
        $this->db->where_in('status', $status_name);
        $data = $this->db->get('tblorders_shop')->result();

        return $data;
    }


    public function get_order_sum($s, $e)
    {
        $status_name1 = array("Đã Giao Hàng Một Phần", "Đã Giao Hàng Toàn Bộ", "Đã Đối Soát Giao Hàng");
        $status_name2 = array("Không Giao Được", "Xác Nhận Hoàn", "Đang Trả Hàng", "Đang Chuyển Kho Trả", "Đã Đối Soát Trả Hàng", "Đã Chuyển Kho Trả");
        $status_name3 = array("Chờ Lấy Hàng"," Đã Nhập Kho"," Đang Chuyển Kho Giao", "Đang Vận Chuyển"," Đã Chuyển Kho Giao"," Đang Giao Hàng"," Hoãn Giao Hàng");

        $status_name = array_merge($status_name1, $status_name2, $status_name3);

        $this->db->select('*');
        $this->db->where('date_create >=', $s);
        $this->db->where('date_create <=', $e);
        $this->db->where_in('status', $status_name);
        $data = $this->db->get('tblorders_shop')->result();

        return sizeof($data);
    }

    public function status_name_by_array($status)
    {
        $status_name1 = array("Đã Giao Hàng Một Phần", "Đã Giao Hàng Toàn Bộ", "Đã Đối Soát Giao Hàng");
        $status_name2 = array("Không Giao Được", "Xác Nhận Hoàn", "Đang Trả Hàng", "Đang Chuyển Kho Trả", "Đã Đối Soát Trả Hàng", "Đã Chuyển Kho Trả");
        $status_name3 = array("Chờ Lấy Hàng"," Đã Nhập Kho"," Đang Chuyển Kho Giao", "Đang Vận Chuyển"," Đã Chuyển Kho Giao"," Đang Giao Hàng"," Hoãn Giao Hàng");
        $sta = trim($status);
        if (array_search($sta, $status_name1) !== false) {
            return "Đơn Thành Công";
        } elseif (array_search($sta, $status_name2) !== false) {
            return "Đơn Thất Bại";
        } elseif (array_search($sta, $status_name3) !== false) {
            return "Đơn Đang Giao";
        }
    }



    public function object_to_array_customer($data, $aColumns)
    {
        $j=0;
        $data_aaDATA = [];
        foreach ($data as $aRow) {
            $row = array();
            $j++;


            $aRow = json_decode(json_encode($aRow), true);

            for ($i = 0; $i < count($aColumns); $i++) {
                $_data = $aRow[$aColumns[$i]];
                $row[] = $_data;
            }

            if (is_admin()) {
                $icon_get_data = "<a style='padding: 3px;' class='btn btn-default btn-icon get_data_sale_report' href='javascript:;'  data-shop='" .$aRow['customer_shop_code']."'". ">"."
              <i class='fa fa-eye' ></i>
            </a>";

                $row[] =$icon_get_data.'</div>';
            } else {
                $row[] = '';
            }

            $row[1] = number_format($row[1]);
            $row[2] = number_format($row[2]);
            $row[3] = number_format($row[3]);
            $row[4] = number_format($row[4]);


            $data_aaDATA[] =  $row;
        }
        return $data_aaDATA;
    }




    public function get_staff_by_customer($cus='')
    {
        $staff = $this->db->get_where('tblstaff', array('staffid' => $cus['customer_monitoring']))->result()[0];
        return $staff->lastname .' '. $staff->firstname;
    }


    public function load_output_report_customer()
    {
        $start_date = $this->input->post('date_start_customer');
        $start_end = $this->input->post('date_end_customer');


        if ($start_date == "") {
            $start_date = null;
        }
        if ($start_end == "") {
            $start_end = null;
        }
        if (isset($start_end) && isset($start_date)) {
            $start_date = to_sql_date($start_date);
            $start_end = to_sql_date($start_end);
        } elseif ((!isset($start_date)) && isset($start_end)) {
            $start_end = to_sql_date($start_end);
            $date = new DateTime($start_end);
            date_sub($date, date_interval_create_from_date_string('30 days'));
            $start_date = date_format($date, 'Y-m-d');
        } elseif (isset($start_date) && !isset($start_end)) {
            $start_date = to_sql_date($start_date);
            $date = new DateTime($start_date);
            $start_end = date("Y-m-d", strtotime("$date +30 day"));
        } elseif (!isset($start_date) && !isset($start_end)) {
            $start_end = date('Y-m-d');
            $date = new DateTime($start_end);
            ;
            date_sub($date, date_interval_create_from_date_string('30 days'));
            $start_date = date_format($date, 'Y-m-d');
        }
        if (!empty($start_date)) {
            $date = new DateTime($start_date);
            ;
            date_sub($date, date_interval_create_from_date_string('1 days'));
            $startdauky = date_format($date, 'Y-m-d'). ' ' . date('23:59:59');
        }

        if ($start_date == "") {
            $start_date = null;
        }
        if ($start_end == "") {
            $start_end = null;
        }

        $start_date = $start_date . ' 00:00:00';
        $start_end = $start_end . ' ' . date('23:59:59');

        $id_customer = $this->input->post('id_customer');
        $id_rows_customer = $this->input->post('id_rows_customer');
        $customer = get_table_where('tblcustomers');



        if ($id_customer == "") {
            $customerLoad = $customer;
        } else {
            foreach ($customer as $key => $value) {
                if ($id_customer == $value['id']) {
                    $customerLoad[] = $value;
                }
            }
        }

        $data_aaDATA;
        foreach ($customerLoad as $key => $value) {
            $sucess = $this->get_order_sucess_by_shop_code($start_date, $start_end, $value['customer_shop_code']);
            $faill = $this->get_order_faill_by_shop_code($start_date, $start_end, $value['customer_shop_code']);
            $shiping = $this->get_order_shiping_by_shop_code($start_date, $start_end, $value['customer_shop_code']);
            $staff = $this->get_staff_by_customer($value);
            $data_push['customer_shop_code'] = $value['customer_shop_code'];
            $data_push['total_success'] = $sucess;
            $data_push['total_faill'] = $faill;
            $data_push['total_shiping'] = $shiping;
            $data_push['total'] = $shiping + $sucess + $faill;
            $data_push['staff'] = $staff;
            $data_aaDATA[] = $data_push;
        }


        $aColumns     = array(
        'customer_shop_code',
        'total_success',
        'total_faill',
        'total_shiping',
        'total',
        'staff'
      );

        usort($data_aaDATA, function ($item1, $item2) {
            if ((int)$item1['total'] == (int)$item2['total']) {
                return 0;
            }
            return (int)$item1['total'] > (int)$item2['total'] ? -1 : 1;
        });


        $total_cal = 0;
        foreach ($data_aaDATA as $key => $value) {
            $total_cal += $value[total];
        }



        $dataTableInit = [
        "aaData" => $this->object_to_array_customer($data_aaDATA, $aColumns),
        "draw" =>  $_POST['draw'],
        "iTotalDisplayRecords" => sizeof($dataTable),
        "iTotalRecords"=> sizeof($dataTable),
        "calc_total_s_e" => $total_cal
      ];

        echo json_encode($dataTableInit);
    }

    // public function calc_total_s_e( $s , $e) {
    //
    //   $this->db->select('SUM(real_revenue) as total_real_revenue');
    //
    //   $this->db->where('date_debits >=', $s);
    //   $this->db->where('date_debits <=', $e);
    //   $data = $this->db->get('tblorders_shop')
    //   ->row();
    //
    //   if ($data->total_real_revenue === NULL) {
    //     return 0;
    //   }
    //   return round($data->total_real_revenue);
    // }









    public function load_output_report_customer_detail()
    {
        $start_date = $this->input->post('date_start_customer');
        $start_end = $this->input->post('date_end_customer');


        if ($start_date == "") {
            $start_date = null;
        }
        if ($start_end == "") {
            $start_end = null;
        }
        if (isset($start_end) && isset($start_date)) {
            $start_date = to_sql_date($start_date);
            $start_end = to_sql_date($start_end);
        } elseif ((!isset($start_date)) && isset($start_end)) {
            $start_end = to_sql_date($start_end);
            $date = new DateTime($start_end);
            date_sub($date, date_interval_create_from_date_string('30 days'));
            $start_date = date_format($date, 'Y-m-d');
        } elseif (isset($start_date) && !isset($start_end)) {
            $start_date = to_sql_date($start_date);
            $date = new DateTime($start_date);
            $start_end = date("Y-m-d", strtotime("$date +30 day"));
        } elseif (!isset($start_date) && !isset($start_end)) {
            $start_end = date('Y-m-d');
            $date = new DateTime($start_end);
            ;
            date_sub($date, date_interval_create_from_date_string('30 days'));
            $start_date = date_format($date, 'Y-m-d');
        }
        if (!empty($start_date)) {
            $date = new DateTime($start_date);
            ;
            date_sub($date, date_interval_create_from_date_string('1 days'));
            $startdauky = date_format($date, 'Y-m-d'). ' ' . date('23:59:59');
        }

        if ($start_date == "") {
            $start_date = null;
        }
        if ($start_end == "") {
            $start_end = null;
        }

        $start_date = $start_date . ' 00:00:00';
        $start_end = $start_end . ' ' . date('23:59:59');

        $id_customer = $this->input->post('id_customer');
        $id_rows_customer = $this->input->post('id_rows_customer');


        $data_aaDATA = $this->get_order_by_code($start_date, $start_end, $_POST['shop_name']);




        foreach ($data_aaDATA as $key => $value) {
            $data_aaDATA[$key]->status = $this->status_name_by_array($value->status);
        }




        $aColumns     = array(
        'date_create',
        'code_supership',
        'status',
        'note' ,
        'mass' ,
        'receiver' ,
        'city' ,
        'district' ,
        'collect',
        'hd_fee'
      );


        usort($data_aaDATA, function ($item1, $item2) {
            if ($item1->date_create == $item2->date_create) {
                return 0;
            }
            return $item1->date_create > $item2->date_create ? -1 : 1;
        });




        $dataTableInit = [
        "aaData" => $this->object_to_array_customer_detail($data_aaDATA, $aColumns),
        "draw" =>  $_POST['draw'],
        "iTotalDisplayRecords" => sizeof($dataTable),
        "iTotalRecords"=> sizeof($dataTable)
      ];

        echo json_encode($dataTableInit);
    }






    //DELTE HERE
    public function object_to_array_customer_detail($data, $aColumns)
    {
        $j=0;
        $data_aaDATA = [];
        foreach ($data as $aRow) {
            $row = array();
            $j++;


            $aRow = json_decode(json_encode($aRow), true);

            for ($i = 0; $i < count($aColumns); $i++) {
                $_data = $aRow[$aColumns[$i]];
                $row[] = $_data;
            }

            $row[3] = 'Thu hộ:' . number_format($row[8]) . ', Phí:'.number_format($row[9]) .
        ' ( KL:'. $row[4] .', '.  $row[5]  .' - '.  $row[6]  .' - '.  $row[7]  . ' )';



            $data_aaDATA[] =  $row;
        }
        return $data_aaDATA;
    }
}
