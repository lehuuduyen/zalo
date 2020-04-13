<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends AdminController
{
    /**
     * Codeigniter Instance
     * Expenses detailed report filters use $ci
     * @var object
     */
    private $ci;

    public function __construct()
    {
        parent::__construct();
        if (!has_permission('reports', '', 'view')) {
            access_denied('reports');
        }
        $this->ci = &get_instance();
        $this->load->model('reports_model');
    }

    public function get_calc_debts_customer($data)
    {
        $dataReturn = 0;
        for ($i= sizeof($data) - 1; $i >= 0; $i--) {
            $dataReturn = $dataReturn + ((int)$data[$i]['ps_in'] - (int)$data[$i]['ps_de']);
        }
        return $dataReturn;
    }

    public function get_calc_debts_customer_ps_in($data='')
    {
        $dataReturn = 0;
        foreach ($data as $key => $value) {
            $dataReturn = $dataReturn + $value['ps_in'];
        }
        return $dataReturn;
    }
    public function get_calc_debts_customer_ps_de($data='')
    {
        $dataReturn = 0;
        foreach ($data as $key => $value) {
            $dataReturn = $dataReturn + $value['ps_de'];
        }
        return $dataReturn;
    }

    public function formatDataCustomer($data)
    {
        foreach ($data['top_data'] as $key => $value) {
            $data['top_data'][$key]->ps_de = 0;
            $data['top_data'][$key]->ps_in = 0;
        }

        foreach ($data['data'] as $key => $value) {
            if ($value['status_debts'] == '0') {
                $data['data'][$key]['status_debts'] = "Đã Thu";
                $data['data'][$key]['ps_de'] = 0;
            }
            if ($value['status_debts'] == '1') {
                $data['data'][$key]['status_debts'] = "Đã Chi";
                $data['data'][$key]['ps_in'] = 0;
            }
            if ($value['status_debts'] == "ĐCCN") {
                $data['data'][$key]['status_debts'] = "Đã Điều Chỉnh";
                if ((int)$data['data'][$key]['ps_in'] > 0) {
                    $data['data'][$key]['ps_de'] = 0;
                } else {
                    $data['data'][$key]['ps_in'] = 0;
                    $data['data'][$key]['ps_de'] = (int)$data['data'][$key]['ps_de']*(-1);
                }
            }
        }

        foreach ($data['Old_data'] as $key => $value) {
            if ($value['status_debts'] == '0') {
                $data['Old_data'][$key]['status_debts'] = "Đã Thu";
                $data['Old_data'][$key]['ps_de'] = 0;
            }
            if ($value['status_debts'] == '1') {
                $data['Old_data'][$key]['status_debts'] = "Đã Chi";
                $data['Old_data'][$key]['ps_in'] = 0;
            }
            if ($value['status_debts'] == "ĐCCN") {
                $data['Old_data'][$key]['status_debts'] = "Đã Điều Chỉnh";
                if (!empty($data_aaDATA[0]['Old_data']) && (int)$data_aaDATA[0]['Old_data'][$key]['ps_in'] > 0) {
                    $data['Old_data'][$key]['ps_de'] = 0;
                } else {
                    $data['Old_data'][$key]['ps_in'] = 0;
                    $data['Old_data'][$key]['ps_de'] = (int)$data['Old_data'][$key]['ps_de']*(-1);
                }
            }
        }

        return $data;
    }

    //báo cáo công nợ Khach Hang ------------------------------------------------------
    public function debts_porters_customer()
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



        $filter_yes = false;

        if ($id_customer == "") {
            $customerLoad = $customer;
            $filter_yes = true;
        } else {
            foreach ($customer as $key => $value) {
                if ($id_customer == $value['id']) {
                    $customerLoad[] = $value;
                }
            }
        }


        $data_aaDATA;
        foreach ($customerLoad as $key => $value) {
            $sql_order_date_null = "SELECT id , date_debits , date_create as created , status_debts , code_supership AS code_display , status , collect AS ps_in , hd_fee_stam AS ps_de , note , mass , receiver , city , district FROM tblorders_shop WHERE shop = ? AND date_debits IS NULL AND status != 'Huỷ' ORDER BY date_create DESC";



            $paramSQL = array($value['customer_shop_code']);

            $dataPush['top_data'] = $this->db->query($sql_order_date_null, $paramSQL)->result();

            $dataPush['Old_data'] = $this->detail_debts_customer_calc(null, $start_date, $value['customer_shop_code'], $value['id']);

            for ($i=0; $i < sizeof($dataPush['Old_data']) ; $i++) {
                if ($dataPush['Old_data'][$i]['status'] == "Đã Đối Soát Trả Hàng") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }

                if ($dataPush['Old_data'][$i]['status'] == "Xác Nhận Hoàn") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }

                if ($dataPush['Old_data'][$i]['status'] == "Hoãn Trả Hàng") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }

                if ($dataPush['Old_data'][$i]['status'] == "Đã Trả Hàng") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }

                if ($dataPush['Old_data'][$i]['status'] == "Không Giao Được") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }

                if ($dataPush['Old_data'][$i]['status'] == "Đang Trả Hàng") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }
                if ($dataPush['Old_data'][$i]['status'] == "Đang Chuyển Kho Trả") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }

                if ($dataPush['Old_data'][$i]['status'] == "Đã Chuyển Kho Trả") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }
            }

            $dataPush['data'] = $this->detail_debts_customer_calc($start_date, $start_end, $value['customer_shop_code'], $value['id']);

            for ($i=0; $i < sizeof($dataPush['data']) ; $i++) {
                if ($dataPush['data'][$i]['status'] == "Đã Đối Soát Trả Hàng") {
                    $dataPush['data'][$i]['ps_in'] = 0;
                }


                if ($dataPush['data'][$i]['status'] == "Xác Nhận Hoàn") {
                    $dataPush['data'][$i]['ps_in'] = 0;
                }

                if ($dataPush['data'][$i]['status'] == "Hoãn Trả Hàng") {
                    $dataPush['data'][$i]['ps_in'] = 0;
                }

                if ($dataPush['data'][$i]['status'] == "Đã Trả Hàng") {
                    $dataPush['data'][$i]['ps_in'] = 0;
                }

                if ($dataPush['data'][$i]['status'] == "Không Giao Được") {
                    $dataPush['data'][$i]['ps_in'] = 0;
                }

                if ($dataPush['data'][$i]['status'] == "Đang Trả Hàng") {
                    $dataPush['data'][$i]['ps_in'] = 0;
                }
                if ($dataPush['data'][$i]['status'] == "Đang Chuyển Kho Trả") {
                    $dataPush['data'][$i]['ps_in'] = 0;
                }

                if ($dataPush['data'][$i]['status'] == "Đã Chuyển Kho Trả") {
                    $dataPush['data'][$i]['ps_in'] = 0;
                }
            }

            $dataPush['customer_shop_code'] = $value['customer_shop_code'];
            $dataPush['id'] = $value['id'];
            $data_aaDATA[] = $dataPush;
        }


        foreach ($data_aaDATA as $key => $value) {
            $data_aaDATA[$key] = $this->formatDataCustomer($data_aaDATA[$key]);
        }

        $aColumns     = array(
        'id',
        'name',
        'previous_debt',
        'ps_in',
        'ps_de',
        'next_debt',
        'control_schedule'
      );
        $dataTable = [];

        foreach ($data_aaDATA as $value) {
            $dataTableValue['id'] = $value['id'];
            $dataTableValue['name'] = $value['customer_shop_code'];
            $dataTableValue['previous_debt'] = $this->get_calc_debts_customer($value['Old_data']);
            $dataTableValue['ps_in'] = $this->get_calc_debts_customer_ps_in($value['data']);
            $dataTableValue['ps_de'] = $this->get_calc_debts_customer_ps_de($value['data']);
            $dataTableValue['next_debt'] = (int)$this->get_calc_debts_customer($value['data']) + (int)$this->get_calc_debts_customer($value['Old_data']);


            $this->db->where(customer_id, $value['id']);
            $get_customer_policy = $this->db->get('tblcustomer_policy')->row();
            $dataTableValue['control_schedule'] = $get_customer_policy->control_schedule;
            $dataTable[] = $dataTableValue;
        }

        if ($filter_yes) {
            if ($id_rows_customer === "1") {
                foreach ($dataTable as $key => $value) {
                    if ((int)$value['next_debt'] < 0 || (int)$value['next_debt'] === 0) {
                        unset($dataTable[$key]);
                    }
                }
            } elseif ($id_rows_customer === "2") {
                foreach ($dataTable as $key => $value) {
                    if ((int)$value['next_debt'] > 0 || (int)$value['next_debt'] === 0) {
                        unset($dataTable[$key]);
                    }
                }
            }
        }


        usort($dataTable, function ($item1, $item2) {
            if ($item1['next_debt'] == $item2['next_debt']) {
                return 0;
            }
            return $item1['next_debt'] > $item2['next_debt'] ? -1 : 1;
        });



        $dataTableInit = [
        "aaData" => $this->object_to_array_customer($dataTable, $aColumns),
        "draw" =>  $_POST['draw'],
        "iTotalDisplayRecords" => sizeof($dataTable),
        "iTotalRecords"=> sizeof($dataTable)
      ];

        echo json_encode($dataTableInit);
    }

    public function get_id_customer($shop)
    {
        $this->db->select('id');
        $this->db->where(array('customer_shop_code' => $shop));
        return $this->db->get('tblcustomers')->row()->id;
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
                $icon_get_data = "<a style='padding: 3px;' class='btn btn-default btn-icon get_data_debits' href='javascript:;' data-debits='". $aRow['name'] . "' data-id='" .$aRow['id']."'". ">"."
                                   <i class='fa fa-eye'></i>
                                </a>";
                $name_shop = "'".$aRow['name']."'";
                $icon_get_data .= "<a style='padding: 3px;' class='btn btn-default btn-icon exportExcelCustomer' href='javascript:;' data-debits='". $aRow['name'] . "' data-id='" .$aRow['id']."'". ">
                                   <i class='fa fa-print'></i>
                                </a>";

                $row[] = $icon_get_data.'</div>';
            } else {
                $row[] = '';
            }

            $row[2] = number_format($row[2]);
            $row[3] = number_format($row[3]);
            $row[4] = number_format($row[4]);
            $row[5] = number_format($row[5]);
            $row[6] = ($row[6]);

            $data_aaDATA[] =  $row;
        }
        return $data_aaDATA;
    }


    public function object_to_array_customer_detail($data, $aColumns)
    {
        $j=0;
        $data_aaDATA = [];
        foreach ($data as $aRow) {
            $row = array();
            $j++;


            $aRow = json_decode(json_encode($aRow), true);

            for ($i = 0; $i < count($aColumns); $i++) {
                $_data = !empty($aRow[$aColumns[$i]]) ? $aRow[$aColumns[$i]] : '';
                $row[] = $_data;
            }

            if (is_admin()) {
                $icon_get_data = '<a style="padding: 3px;" class="btn btn-default btn-icon get_data_debits" href="javascript:;"" data-debits="'. (!empty($aRow['name']) ? utf8_encode($aRow['name']) : '') . '" data-id="' .$aRow['id'].'"'. '>'.'
                                    <i class="fa fa-eye" ></i>
                                </a>';

                $row[] =$icon_get_data.'</div>';
            } else {
                $row[] = '';
            }

            if ($row[1] != '1970') {
                if ($row[1]) {
                    $row[1] = date("d/m/Y", strtotime($row[1]));
                }
                if ($row[2]) {
                    $row[2] = date("d/m/Y", strtotime($row[2]));
                }
            } else {
                $row[1] = "";
                $row[2] = "";
            }

            $row6 = number_format_data($row[6]);
            if ($row[1] == '') {
                $row[6] = 0;
            } else {
                $row[6] = (!empty($row[6]) ? $row[6] : 0)  - (!empty($row[7]) ? $row[7] : 0);
            }


            if ((int)$row[6] >= 0) {
                $row[6] = "+".number_format_data($row[6]);
            } else {
                $row[6] = number_format_data($row[6]);
            }
            if ($row[1] == '1970') {
                $row[6] = '';
            }

            $row[7] = number_format_data($row[7]);


            $row[8] = number_format_data($row[8]);


            if ($row[3] == "Đơn Hàng") {
                $row[3] = "ĐH đã tính công nợ";
                $row[9] = 'Thu hộ:' . $row6 . ', Phí:'. $row[7] .
            ' ( KL:'. $row[10] .', '.  $row[11]  .' - '.  $row[12]  .' - '.  $row[13]  . ' )';
            }

            if ($row[3] == "ĐH chưa đối soát") {
                $row[9] = 'Thu hộ:' . $row6 . ', Phí:'. $row[7] .
            ' ( KL:'. $row[10] .', '.  $row[11]  .' - '.  $row[12]  .' - '.  $row[13]  . ' )';

                $row[3] = "ĐH chưa tính công nợ";
            }





            $data_aaDATA[] =  $row;
        }
        return $data_aaDATA;
    }



    public function detail_debts_customer_calc($start_date, $start_end, $shop_name, $id_customer)
    {
        if ($start_date !== null) {
            $sql = "

        (SELECT id , date_debits AS date_create , date_create as created , status_debts , code_supership AS code_display , status , collect AS ps_in , hd_fee AS ps_de , note , mass , receiver , city , district FROM tblorders_shop WHERE shop = ? AND date_debits BETWEEN ? AND ? AND status != 'Huỷ')

        UNION ALL

        (SELECT id , date_control AS date_create , date as created , type AS status_debts , code AS code_display , status , price AS ps_in , price AS ps_de , note , mass , receiver , city , district FROM tblcash_book WHERE  groups = 5 AND id_object = ? AND staff_id = ? AND date BETWEEN ? AND ?)

        UNION ALL

        (SELECT id , date AS date_create , date_create as created , status_debts , code AS code_display , status , price AS ps_in , price AS ps_de , note , mass , receiver , city , district FROM tbldebit_object WHERE id_object = ? AND staff_id = ? AND date BETWEEN ? AND ?)
        ORDER BY date_create DESC
        "
        ;


            $paramSQL = array($shop_name , $start_date , $start_end , 'tblcustomers' , $id_customer ,  $start_date , $start_end , 'tblcustomers' , $id_customer  ,  $start_date , $start_end);
        } else {
            $sql = "

        (SELECT id , date_debits AS date_create , date_create as created , status_debts , code_supership AS code_display , status , collect AS ps_in , hd_fee AS ps_de , note , mass , receiver , city , district FROM tblorders_shop WHERE shop = ? AND 	date_debits < ? AND status != 'Huỷ')
        UNION ALL

        (SELECT id , date_control AS date_create , date as created , type AS status_debts , code AS code_display , status , price AS ps_in , price AS ps_de , note , mass , receiver , city , district FROM tblcash_book WHERE groups = 5 AND id_object = ? AND staff_id = ? AND date < ?)

        UNION ALL

        (SELECT id , date AS date_create , date_create as created , status_debts , code AS code_display , status , price AS ps_in , price AS ps_de , note , mass , receiver , city , district FROM tbldebit_object WHERE id_object = ? AND staff_id = ? AND date < ?)
        ORDER BY date_create DESC
        "
        ;


            $paramSQL = array($shop_name , $start_end , 'tblcustomers' , $id_customer ,   $start_end , 'tblcustomers' , $id_customer  ,  $start_end);
        }
        // $this->db->query($sql, $paramSQL)->result();
        // echo "<pre>";
        //
        // var_dump($this->db->last_query());
        // die();
        // $this->db->query($sql, $paramSQL);
        // var_dump($this->db->last_query());
        // die();
        return json_decode(json_encode($this->db->query($sql, $paramSQL)->result()), true);
    }


    public function add_to_array_8($data)
    {
        return array_slice($data, 0, 7, true) +
      array("sps_con_no" => 0) +
      array_slice($data, 7, count($data) - 1, true) ;
    }

    public function detail_debts_customer()
    {
        $start_date = $this->input->post('start_detail_customer');
        $start_end = $this->input->post('end_detail_customer');
        $date_1970 = $this->input->post('start_detail_customer');
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

        $start_date = $start_date . ' 00:00:00';
        $start_end = $start_end . ' ' . date('23:59:59');

        $id_customer = $this->input->post('id_customer');
        $id_rows_customer = $this->input->post('id_rows_customer');
        $customer = get_table_where('tblcustomers');



        if ($start_date == "") {
            $start_date = null;
        }
        if ($start_end == "") {
            $start_end = null;
        }

        $id_customer = $this->input->post('id_shop_detail');
        $id_rows_customer = $this->input->post('id_rows_customer');

        $customerLoad =  $this->db->get_where('tblcustomers', array('id' => $id_customer))->result();

        $data_aaDATA;
        foreach ($customerLoad as $key => $value) {
            $dataPush['Old_data'] = $this->detail_debts_customer_calc(null, $start_date, $value->customer_shop_code, $value->id);

            for ($i=0; $i < sizeof($dataPush['Old_data']) ; $i++) {
                if ($dataPush['Old_data'][$i]['status'] == "Xác Nhận Hoàn") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }
                if ($dataPush['Old_data'][$i]['status'] == "Không Giao Được") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }
                if ($dataPush['Old_data'][$i]['status'] == "Hoãn Trả Hàng") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }
                if ($dataPush['Old_data'][$i]['status'] == "Đã Trả Hàng") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }
                if ($dataPush['Old_data'][$i]['status'] == "Đang Trả Hàng") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }
                if ($dataPush['Old_data'][$i]['status'] == "Đang Chuyển Kho Trả") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }
                if ($dataPush['Old_data'][$i]['status'] == "Đã Đối Soát Trả Hàng") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }
                if ($dataPush['Old_data'][$i]['status'] == "Đã Chuyển Kho Trả") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }
            }


            //

            $dataPush['data'] = $this->detail_debts_customer_calc($start_date, $start_end, $value->customer_shop_code, $value->id);

            for ($i=0; $i < sizeof($dataPush['data']) ; $i++) {
                if ($dataPush['data'][$i]['status'] == "Xác Nhận Hoàn") {
                    $dataPush['data'][$i]['ps_in'] = 0;
                }

                if ($dataPush['data'][$i]['status'] == "Hoãn Trả Hàng") {
                    $dataPush['data'][$i]['ps_in'] = 0;
                }

                if ($dataPush['data'][$i]['status'] == "Đã Trả Hàng") {
                    $dataPush['data'][$i]['ps_in'] = 0;
                }

                if ($dataPush['data'][$i]['status'] == "Không Giao Được") {
                    $dataPush['data'][$i]['ps_in'] = 0;
                }

                if ($dataPush['data'][$i]['status'] == "Đang Trả Hàng") {
                    $dataPush['data'][$i]['ps_in'] = 0;
                }
                if ($dataPush['data'][$i]['status'] == "Đang Chuyển Kho Trả") {
                    $dataPush['data'][$i]['ps_in'] = 0;
                }
                if ($dataPush['data'][$i]['status'] == "Đã Đối Soát Trả Hàng") {
                    $dataPush['data'][$i]['ps_in'] = 0;
                }
                if ($dataPush['data'][$i]['status'] == "Đã Chuyển Kho Trả") {
                    $dataPush['data'][$i]['ps_in'] = 0;
                }
            }


            $sql_order_date_null = "SELECT id , date_debits as date_create , date_create as created , status_debts , code_supership AS code_display , status , collect AS ps_in , hd_fee_stam AS ps_de , note , mass , receiver , city , district FROM tblorders_shop WHERE shop = ? AND date_debits IS NULL AND status != 'Huỷ' ORDER BY date_create DESC";



            $paramSQL = array($value->customer_shop_code);

            $dataPush['top_data'] = $this->db->query($sql_order_date_null, $paramSQL)->result();
            for ($i=0; $i < sizeof($dataPush['top_data']); $i++) {
                $dataPush['top_data'][$i]->status_debts = 'ĐH chưa đối soát';
            }

            $dataPush['customer_shop_code'] = $value->customer_shop_code;
            $dataPush['id'] = $value->id;
            $data_aaDATA[] = $dataPush;
        }


        $dataSoFar[0]['id'] = 1970;
        $dataSoFar[0]['date_create'] = "1970";
        $dataSoFar[0]['created'] = "1970";
        $dataSoFar[0]['status_debts'] = "Nợ trước Ngày";
        $dataSoFar[0]['code_display'] = $date_1970;
        $dataSoFar[0]['status'] = "-";
        $dataSoFar[0]['ps_in'] = "-";
        $dataSoFar[0]['ps_de'] = "-";
        $dataSoFar[0]['note'] = '-';
        $dataSoFar[0]['mass'] = '-';
        $dataSoFar[0]['receiver'] = '-';
        $dataSoFar[0]['city'] = '-';
        $dataSoFar[0]['district'] = '-';

        $dataSoFar[0] = $this->add_to_array_8($dataSoFar[0]);


        foreach ($data_aaDATA[0]['top_data'] as $key => $value) {
            $data_aaDATA[0]['top_data'][$key]->collect = $value->ps_in;
            // $data_aaDATA[0]['top_data'][$key]->ps_de = 0;
        // $data_aaDATA[0]['top_data'][$key]->ps_in = 0;
        }


        foreach ($data_aaDATA[0]['data'] as $key => $value) {
            if ($value['status_debts'] == '0') {
                $data_aaDATA[0]['data'][$key]['status_debts'] = "Đã Thu";
                $data_aaDATA[0]['data'][$key]['ps_de'] = 0;
            }
            if ($value['status_debts'] == '1') {
                $data_aaDATA[0]['data'][$key]['status_debts'] = "Đã Chi";
                $data_aaDATA[0]['data'][$key]['ps_in'] = 0;
            }
            if ($value['status_debts'] == "ĐCCN") {
                $data_aaDATA[0]['data'][$key]['status_debts'] = "Đã Điều Chỉnh";

                if ($data_aaDATA[0]['data'][$key]['ps_in'] > 0) {
                    $data_aaDATA[0]['data'][$key]['ps_de'] = 0;
                } else {
                    $data_aaDATA[0]['data'][$key]['ps_in'] = 0;
                    $data_aaDATA[0]['data'][$key]['ps_de'] = (int)$data_aaDATA[0]['data'][$key]['ps_de']*(-1);
                }
            }
        }

        foreach ($data_aaDATA[0]['Old_data'] as $key => $value) {
            if ($value['status_debts'] == '0') {
                $data_aaDATA[0]['Old_data'][$key]['status_debts'] = "Đã Thu";
                $data_aaDATA[0]['Old_data'][$key]['ps_de'] = 0;
            }
            if ($value['status_debts'] == '1') {
                $data_aaDATA[0]['Old_data'][$key]['status_debts'] = "Đã Chi";
                $data_aaDATA[0]['Old_data'][$key]['ps_in'] = 0;
            }
            if ($value['status_debts'] == "ĐCCN") {
                $data_aaDATA[0]['Old_data'][$key]['status_debts'] = "Đã Điều Chỉnh";
                if ((int)$data_aaDATA[0]['Old_data'][$key]['ps_in'] > 0) {
                    $data_aaDATA[0]['Old_data'][$key]['ps_de'] = 0;
                } else {
                    $data_aaDATA[0]['Old_data'][$key]['ps_in'] = 0;
                    $data_aaDATA[0]['Old_data'][$key]['ps_de'] = (int)$data_aaDATA[0]['Old_data'][$key]['ps_de']*(-1);
                }
            }
        }




        foreach ($data_aaDATA[0]['Old_data'] as $key => $value) {
            $data_aaDATA[0]['Old_data'][$key] = $this->add_to_array_8($value);
        }
        foreach ($data_aaDATA[0]['data'] as $key => $value) {
            $data_aaDATA[0]['data'][$key] = $this->add_to_array_8($value);
        }

        $data_aaDATA[0]['top_data'] = json_decode(json_encode($data_aaDATA[0]['top_data']), true);


        $dataSoFar[0]['sps_con_no'] = $this->get_calc_debts_customer($data_aaDATA[0]['Old_data']);


        $dataReturn = array_merge($data_aaDATA[0]['top_data'], $data_aaDATA[0]['data'], $dataSoFar);

        //cập nhật sps còn nợ

        for ($i= sizeof($dataReturn) - 1; $i > 0; $i--) {
            if ($dataReturn[$i - 1]['date_create'] != null) {
                $dataReturn[$i - 1]['sps_con_no'] = $dataReturn[$i]['sps_con_no'] + ($dataReturn[$i - 1]['ps_in'] - $dataReturn[$i - 1]['ps_de']);
            } else {
                $dataReturn[$i - 1]['sps_con_no'] = $dataReturn[$i]['sps_con_no'];
            }
        }





        $aColumns     = array(
        'id',
        'date_create',
        'created',
        'status_debts',
        'code_display',
        'status',
        'ps_in',
        'ps_de',
        'sps_con_no',
        'note',
        'mass',
        'receiver',
        'city',
        'district',
        'collect'
      );



        $id_customer = $this->input->post('id_shop_detail');
        $id_rows_customer = $this->input->post('id_rows_customer');

        $customerLoadC =  $this->db->get_where('tblcustomers', array('id' => $id_customer))->row();

        $this->db->select('sum(collect - hd_fee_stam) as total');
        $this->db->where('shop', $customerLoadC->customer_shop_code);
        $this->db->where('date_debits is null');
        $this->db->where('status !=', 'Huỷ');
        $total_wating = $this->db->get('tblorders_shop')->row();



        $dataTableInit = [
        "aaData" => $this->object_to_array_customer_detail($dataReturn, $aColumns),
        "draw" =>  $_POST['draw'],
        "total_wating" =>  !empty($total_wating->total) ? $total_wating->total : 0,
        "iTotalDisplayRecords" => sizeof($dataReturn),
        "iTotalRecords"=> sizeof($dataReturn)
      ];
        echo json_encode($dataTableInit);
    }





    /* No access on this url */
    public function index()
    {
        redirect(admin_url());
    }

    /* See knowledge base article reports*/
    public function knowledge_base_articles()
    {
        $this->load->model('knowledge_base_model');
        $data['groups'] = $this->knowledge_base_model->get_kbg();
        $data['title']  = _l('kb_reports');
        $this->load->view('admin/reports/knowledge_base_articles', $data);
    }

    /*
        public function tax_summary(){
           $this->load->model('taxes_model');
           $this->load->model('payments_model');
           $this->load->model('invoices_model');
           $data['taxes'] = $this->db->query("SELECT DISTINCT taxname,taxrate FROM ".db_prefix()."item_tax WHERE rel_type='invoice'")->result_array();
            $this->load->view('admin/reports/tax_summary',$data);
        }*/
    /* Repoert leads conversions */
    public function leads()
    {
        $type = 'leads';
        if ($this->input->get('type')) {
            $type                       = $type . '_' . $this->input->get('type');
            $data['leads_staff_report'] = json_encode($this->reports_model->leads_staff_report());
        }
        $this->load->model('leads_model');
        $data['statuses']               = $this->leads_model->get_status();
        $data['leads_this_week_report'] = json_encode($this->reports_model->leads_this_week_report());
        $data['leads_sources_report']   = json_encode($this->reports_model->leads_sources_report());
        $this->load->view('admin/reports/' . $type, $data);
    }

    /* Sales reportts */
    public function sales()
    {
        $data['mysqlVersion'] = $this->db->query('SELECT VERSION() as version')->row();
        $data['sqlMode']      = $this->db->query('SELECT @@sql_mode as mode')->row();

        if (is_using_multiple_currencies() || is_using_multiple_currencies(db_prefix() . 'creditnotes') || is_using_multiple_currencies(db_prefix() . 'estimates') || is_using_multiple_currencies(db_prefix() . 'proposals')) {
            $this->load->model('currencies_model');
            $data['currencies'] = $this->currencies_model->get();
        }
        $this->load->model('invoices_model');
        $this->load->model('estimates_model');
        $this->load->model('proposals_model');
        $this->load->model('credit_notes_model');

        $data['credit_notes_statuses'] = $this->credit_notes_model->get_statuses();
        $data['invoice_statuses']      = $this->invoices_model->get_statuses();
        $data['estimate_statuses']     = $this->estimates_model->get_statuses();
        $data['payments_years']        = $this->reports_model->get_distinct_payments_years();
        $data['estimates_sale_agents'] = $this->estimates_model->get_sale_agents();

        $data['invoices_sale_agents'] = $this->invoices_model->get_sale_agents();

        $data['proposals_sale_agents'] = $this->proposals_model->get_sale_agents();
        $data['proposals_statuses']    = $this->proposals_model->get_statuses();

        $data['invoice_taxes']     = $this->distinct_taxes('invoice');
        $data['estimate_taxes']    = $this->distinct_taxes('estimate');
        $data['proposal_taxes']    = $this->distinct_taxes('proposal');
        $data['credit_note_taxes'] = $this->distinct_taxes('credit_note');


        $data['title'] = _l('sales_reports');
        $this->load->view('admin/reports/sales', $data);
    }

    /* Customer report */
    public function customers_report()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $select = [
                get_sql_select_client_company(),
                '(SELECT COUNT(clientid) FROM ' . db_prefix() . 'invoices WHERE ' . db_prefix() . 'invoices.clientid = ' . db_prefix() . 'clients.userid AND status != 5)',
                '(SELECT SUM(subtotal) - SUM(discount_total) FROM ' . db_prefix() . 'invoices WHERE ' . db_prefix() . 'invoices.clientid = ' . db_prefix() . 'clients.userid AND status != 5)',
                '(SELECT SUM(total) FROM ' . db_prefix() . 'invoices WHERE ' . db_prefix() . 'invoices.clientid = ' . db_prefix() . 'clients.userid AND status != 5)',
            ];

            $custom_date_select = $this->get_where_report_period();
            if ($custom_date_select != '') {
                $i = 0;
                foreach ($select as $_select) {
                    if ($i !== 0) {
                        $_temp = substr($_select, 0, -1);
                        $_temp .= ' ' . $custom_date_select . ')';
                        $select[$i] = $_temp;
                    }
                    $i++;
                }
            }
            $by_currency = $this->input->post('report_currency');
            $currency    = $this->currencies_model->get_base_currency();
            if ($by_currency) {
                $i = 0;
                foreach ($select as $_select) {
                    if ($i !== 0) {
                        $_temp = substr($_select, 0, -1);
                        $_temp .= ' AND currency =' . $by_currency . ')';
                        $select[$i] = $_temp;
                    }
                    $i++;
                }
                $currency = $this->currencies_model->get($by_currency);
            }
            $aColumns     = $select;
            $sIndexColumn = 'userid';
            $sTable       = db_prefix() . 'clients';
            $where        = [];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where, [
                'userid',
            ]);
            $output  = $result['output'];
            $rResult = $result['rResult'];
            $x       = 0;
            foreach ($rResult as $aRow) {
                $row = [];
                for ($i = 0; $i < count($aColumns); $i++) {
                    if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
                        $_data = $aRow[strafter($aColumns[$i], 'as ')];
                    } else {
                        $_data = $aRow[$aColumns[$i]];
                    }
                    if ($i == 0) {
                        $_data = '<a href="' . admin_url('clients/client/' . $aRow['userid']) . '" target="_blank">' . $aRow['company'] . '</a>';
                    } elseif ($aColumns[$i] == $select[2] || $aColumns[$i] == $select[3]) {
                        if ($_data == null) {
                            $_data = 0;
                        }
                        $_data = app_format_money($_data, $currency->name);
                    }
                    $row[] = $_data;
                }
                $output['aaData'][] = $row;
                $x++;
            }
            echo json_encode($output);
            die();
        }
    }

    public function payments_received()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $this->load->model('payment_modes_model');
            $payment_gateways = $this->payment_modes_model->get_payment_gateways(true);
            $select           = [
                db_prefix() . 'invoicepaymentrecords.id',
                db_prefix() . 'invoicepaymentrecords.date',
                'invoiceid',
                get_sql_select_client_company(),
                'paymentmode',
                'transactionid',
                'note',
                'amount',
            ];
            $where = [
                'AND status != 5',
            ];

            $custom_date_select = $this->get_where_report_period(db_prefix() . 'invoicepaymentrecords.date');
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }

            $by_currency = $this->input->post('report_currency');
            if ($by_currency) {
                $currency = $this->currencies_model->get($by_currency);
                array_push($where, 'AND currency=' . $by_currency);
            } else {
                $currency = $this->currencies_model->get_base_currency();
            }

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'invoicepaymentrecords';
            $join         = [
                'JOIN ' . db_prefix() . 'invoices ON ' . db_prefix() . 'invoices.id = ' . db_prefix() . 'invoicepaymentrecords.invoiceid',
                'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'invoices.clientid',
                'LEFT JOIN ' . db_prefix() . 'payment_modes ON ' . db_prefix() . 'payment_modes.id = ' . db_prefix() . 'invoicepaymentrecords.paymentmode',
            ];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
                'number',
                'clientid',
                db_prefix() . 'payment_modes.name',
                db_prefix() . 'payment_modes.id as paymentmodeid',
                'paymentmethod',
                'deleted_customer_name',
            ]);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            $footer_data['total_amount'] = 0;
            foreach ($rResult as $aRow) {
                $row = [];
                for ($i = 0; $i < count($aColumns); $i++) {
                    if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
                        $_data = $aRow[strafter($aColumns[$i], 'as ')];
                    } else {
                        $_data = $aRow[$aColumns[$i]];
                    }
                    if ($aColumns[$i] == 'paymentmode') {
                        $_data = $aRow['name'];
                        if (is_null($aRow['paymentmodeid'])) {
                            foreach ($payment_gateways as $gateway) {
                                if ($aRow['paymentmode'] == $gateway['id']) {
                                    $_data = $gateway['name'];
                                }
                            }
                        }
                        if (!empty($aRow['paymentmethod'])) {
                            $_data .= ' - ' . $aRow['paymentmethod'];
                        }
                    } elseif ($aColumns[$i] == db_prefix() . 'invoicepaymentrecords.id') {
                        $_data = '<a href="' . admin_url('payments/payment/' . $_data) . '" target="_blank">' . $_data . '</a>';
                    } elseif ($aColumns[$i] == db_prefix() . 'invoicepaymentrecords.date') {
                        $_data = _d($_data);
                    } elseif ($aColumns[$i] == 'invoiceid') {
                        $_data = '<a href="' . admin_url('invoices/list_invoices/' . $aRow[$aColumns[$i]]) . '" target="_blank">' . format_invoice_number($aRow['invoiceid']) . '</a>';
                    } elseif ($i == 3) {
                        if (empty($aRow['deleted_customer_name'])) {
                            $_data = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '" target="_blank">' . $aRow['company'] . '</a>';
                        } else {
                            $row[] = $aRow['deleted_customer_name'];
                        }
                    } elseif ($aColumns[$i] == 'amount') {
                        $footer_data['total_amount'] += $_data;
                        $_data = app_format_money($_data, $currency->name);
                    }

                    $row[] = $_data;
                }
                $output['aaData'][] = $row;
            }

            $footer_data['total_amount'] = app_format_money($footer_data['total_amount'], $currency->name);
            $output['sums']              = $footer_data;
            echo json_encode($output);
            die();
        }
    }



    public function proposals_report()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $this->load->model('proposals_model');

            $proposalsTaxes    = $this->distinct_taxes('proposal');
            $totalTaxesColumns = count($proposalsTaxes);

            $select = [
                'id',
                'subject',
                'proposal_to',
                'date',
                'open_till',
                'subtotal',
                'total',
                'total_tax',
                'discount_total',
                'adjustment',
                'status',
            ];

            $proposalsTaxesSelect = array_reverse($proposalsTaxes);

            foreach ($proposalsTaxesSelect as $key => $tax) {
                array_splice($select, 8, 0, '(
                    SELECT CASE
                    WHEN discount_percent != 0 AND discount_type = "before_tax" THEN ROUND(SUM((qty*rate/100*' . db_prefix() . 'item_tax.taxrate) - (qty*rate/100*' . db_prefix() . 'item_tax.taxrate * discount_percent/100)),' . get_decimal_places() . ')
                    WHEN discount_total != 0 AND discount_type = "before_tax" THEN ROUND(SUM((qty*rate/100*' . db_prefix() . 'item_tax.taxrate) - (qty*rate/100*' . db_prefix() . 'item_tax.taxrate * (discount_total/subtotal*100) / 100)),' . get_decimal_places() . ')
                    ELSE ROUND(SUM(qty*rate/100*' . db_prefix() . 'item_tax.taxrate),' . get_decimal_places() . ')
                    END
                    FROM ' . db_prefix() . 'itemable
                    INNER JOIN ' . db_prefix() . 'item_tax ON ' . db_prefix() . 'item_tax.itemid=' . db_prefix() . 'itemable.id
                    WHERE ' . db_prefix() . 'itemable.rel_type="proposal" AND taxname="' . $tax['taxname'] . '" AND taxrate="' . $tax['taxrate'] . '" AND ' . db_prefix() . 'itemable.rel_id=' . db_prefix() . 'proposals.id) as total_tax_single_' . $key);
            }


            $where              = [];
            $custom_date_select = $this->get_where_report_period();
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }

            if ($this->input->post('proposal_status')) {
                $statuses  = $this->input->post('proposal_status');
                $_statuses = [];
                if (is_array($statuses)) {
                    foreach ($statuses as $status) {
                        if ($status != '') {
                            array_push($_statuses, $status);
                        }
                    }
                }
                if (count($_statuses) > 0) {
                    array_push($where, 'AND status IN (' . implode(', ', $_statuses) . ')');
                }
            }

            if ($this->input->post('proposals_sale_agents')) {
                $agents  = $this->input->post('proposals_sale_agents');
                $_agents = [];
                if (is_array($agents)) {
                    foreach ($agents as $agent) {
                        if ($agent != '') {
                            array_push($_agents, $agent);
                        }
                    }
                }
                if (count($_agents) > 0) {
                    array_push($where, 'AND assigned IN (' . implode(', ', $_agents) . ')');
                }
            }


            $by_currency = $this->input->post('report_currency');
            if ($by_currency) {
                $currency = $this->currencies_model->get($by_currency);
                array_push($where, 'AND currency=' . $by_currency);
            } else {
                $currency = $this->currencies_model->get_base_currency();
            }

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'proposals';
            $join         = [];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
                'rel_id',
                'rel_type',
                'discount_percent',
            ]);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            $footer_data = [
                'total'          => 0,
                'subtotal'       => 0,
                'total_tax'      => 0,
                'discount_total' => 0,
                'adjustment'     => 0,
            ];

            foreach ($proposalsTaxes as $key => $tax) {
                $footer_data['total_tax_single_' . $key] = 0;
            }

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = '<a href="' . admin_url('proposals/list_proposals/' . $aRow['id']) . '" target="_blank">' . format_proposal_number($aRow['id']) . '</a>';

                $row[] = '<a href="' . admin_url('proposals/list_proposals/' . $aRow['id']) . '" target="_blank">' . $aRow['subject'] . '</a>';

                if ($aRow['rel_type'] == 'lead') {
                    $row[] = '<a href="#" onclick="init_lead(' . $aRow['rel_id'] . ');return false;" target="_blank" data-toggle="tooltip" data-title="' . _l('lead') . '">' . $aRow['proposal_to'] . '</a>' . '<span class="hide">' . _l('lead') . '</span>';
                } elseif ($aRow['rel_type'] == 'customer') {
                    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['rel_id']) . '" target="_blank" data-toggle="tooltip" data-title="' . _l('client') . '">' . $aRow['proposal_to'] . '</a>' . '<span class="hide">' . _l('client') . '</span>';
                } else {
                    $row[] = '';
                }

                $row[] = _d($aRow['date']);

                $row[] = _d($aRow['open_till']);

                $row[] = app_format_money($aRow['subtotal'], $currency->name);
                $footer_data['subtotal'] += $aRow['subtotal'];

                $row[] = app_format_money($aRow['total'], $currency->name);
                $footer_data['total'] += $aRow['total'];

                $row[] = app_format_money($aRow['total_tax'], $currency->name);
                $footer_data['total_tax'] += $aRow['total_tax'];

                $t = $totalTaxesColumns - 1;
                $i = 0;
                foreach ($proposalsTaxes as $tax) {
                    $row[] = app_format_money(($aRow['total_tax_single_' . $t] == null ? 0 : $aRow['total_tax_single_' . $t]), $currency->name);
                    $footer_data['total_tax_single_' . $i] += ($aRow['total_tax_single_' . $t] == null ? 0 : $aRow['total_tax_single_' . $t]);
                    $t--;
                    $i++;
                }

                $row[] = app_format_money($aRow['discount_total'], $currency->name);
                $footer_data['discount_total'] += $aRow['discount_total'];

                $row[] = app_format_money($aRow['adjustment'], $currency->name);
                $footer_data['adjustment'] += $aRow['adjustment'];

                $row[]              = format_proposal_status($aRow['status']);
                $output['aaData'][] = $row;
            }

            foreach ($footer_data as $key => $total) {
                $footer_data[$key] = app_format_money($total, $currency->name);
            }

            $output['sums'] = $footer_data;
            echo json_encode($output);
            die();
        }
    }

    public function estimates_report()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $this->load->model('estimates_model');

            $estimateTaxes     = $this->distinct_taxes('estimate');
            $totalTaxesColumns = count($estimateTaxes);

            $select = [
                'number',
                get_sql_select_client_company(),
                'invoiceid',
                'YEAR(date) as year',
                'date',
                'expirydate',
                'subtotal',
                'total',
                'total_tax',
                'discount_total',
                'adjustment',
                'reference_no',
                'status',
            ];

            $estimatesTaxesSelect = array_reverse($estimateTaxes);

            foreach ($estimatesTaxesSelect as $key => $tax) {
                array_splice($select, 9, 0, '(
                    SELECT CASE
                    WHEN discount_percent != 0 AND discount_type = "before_tax" THEN ROUND(SUM((qty*rate/100*' . db_prefix() . 'item_tax.taxrate) - (qty*rate/100*' . db_prefix() . 'item_tax.taxrate * discount_percent/100)),' . get_decimal_places() . ')
                    WHEN discount_total != 0 AND discount_type = "before_tax" THEN ROUND(SUM((qty*rate/100*' . db_prefix() . 'item_tax.taxrate) - (qty*rate/100*' . db_prefix() . 'item_tax.taxrate * (discount_total/subtotal*100) / 100)),' . get_decimal_places() . ')
                    ELSE ROUND(SUM(qty*rate/100*' . db_prefix() . 'item_tax.taxrate),' . get_decimal_places() . ')
                    END
                    FROM ' . db_prefix() . 'itemable
                    INNER JOIN ' . db_prefix() . 'item_tax ON ' . db_prefix() . 'item_tax.itemid=' . db_prefix() . 'itemable.id
                    WHERE ' . db_prefix() . 'itemable.rel_type="estimate" AND taxname="' . $tax['taxname'] . '" AND taxrate="' . $tax['taxrate'] . '" AND ' . db_prefix() . 'itemable.rel_id=' . db_prefix() . 'estimates.id) as total_tax_single_' . $key);
            }

            $where              = [];
            $custom_date_select = $this->get_where_report_period();
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }

            if ($this->input->post('estimate_status')) {
                $statuses  = $this->input->post('estimate_status');
                $_statuses = [];
                if (is_array($statuses)) {
                    foreach ($statuses as $status) {
                        if ($status != '') {
                            array_push($_statuses, $status);
                        }
                    }
                }
                if (count($_statuses) > 0) {
                    array_push($where, 'AND status IN (' . implode(', ', $_statuses) . ')');
                }
            }

            if ($this->input->post('sale_agent_estimates')) {
                $agents  = $this->input->post('sale_agent_estimates');
                $_agents = [];
                if (is_array($agents)) {
                    foreach ($agents as $agent) {
                        if ($agent != '') {
                            array_push($_agents, $agent);
                        }
                    }
                }
                if (count($_agents) > 0) {
                    array_push($where, 'AND sale_agent IN (' . implode(', ', $_agents) . ')');
                }
            }

            $by_currency = $this->input->post('report_currency');
            if ($by_currency) {
                $currency = $this->currencies_model->get($by_currency);
                array_push($where, 'AND currency=' . $by_currency);
            } else {
                $currency = $this->currencies_model->get_base_currency();
            }

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'estimates';
            $join         = [
                'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'estimates.clientid',
            ];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
                'userid',
                'clientid',
                db_prefix() . 'estimates.id',
                'discount_percent',
                'deleted_customer_name',
            ]);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            $footer_data = [
                'total'          => 0,
                'subtotal'       => 0,
                'total_tax'      => 0,
                'discount_total' => 0,
                'adjustment'     => 0,
            ];

            foreach ($estimateTaxes as $key => $tax) {
                $footer_data['total_tax_single_' . $key] = 0;
            }

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = '<a href="' . admin_url('estimates/list_estimates/' . $aRow['id']) . '" target="_blank">' . format_estimate_number($aRow['id']) . '</a>';

                if (empty($aRow['deleted_customer_name'])) {
                    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['userid']) . '" target="_blank">' . $aRow['company'] . '</a>';
                } else {
                    $row[] = $aRow['deleted_customer_name'];
                }

                if ($aRow['invoiceid'] === null) {
                    $row[] = '';
                } else {
                    $row[] = '<a href="' . admin_url('invoices/list_invoices/' . $aRow['invoiceid']) . '" target="_blank">' . format_invoice_number($aRow['invoiceid']) . '</a>';
                }

                $row[] = $aRow['year'];

                $row[] = _d($aRow['date']);

                $row[] = _d($aRow['expirydate']);

                $row[] = app_format_money($aRow['subtotal'], $currency->name);
                $footer_data['subtotal'] += $aRow['subtotal'];

                $row[] = app_format_money($aRow['total'], $currency->name);
                $footer_data['total'] += $aRow['total'];

                $row[] = app_format_money($aRow['total_tax'], $currency->name);
                $footer_data['total_tax'] += $aRow['total_tax'];

                $t = $totalTaxesColumns - 1;
                $i = 0;
                foreach ($estimateTaxes as $tax) {
                    $row[] = app_format_money(($aRow['total_tax_single_' . $t] == null ? 0 : $aRow['total_tax_single_' . $t]), $currency->name);
                    $footer_data['total_tax_single_' . $i] += ($aRow['total_tax_single_' . $t] == null ? 0 : $aRow['total_tax_single_' . $t]);
                    $t--;
                    $i++;
                }

                $row[] = app_format_money($aRow['discount_total'], $currency->name);
                $footer_data['discount_total'] += $aRow['discount_total'];

                $row[] = app_format_money($aRow['adjustment'], $currency->name);
                $footer_data['adjustment'] += $aRow['adjustment'];


                $row[] = $aRow['reference_no'];

                $row[] = format_estimate_status($aRow['status']);

                $output['aaData'][] = $row;
            }
            foreach ($footer_data as $key => $total) {
                $footer_data[$key] = app_format_money($total, $currency->name);
            }
            $output['sums'] = $footer_data;
            echo json_encode($output);
            die();
        }
    }

    private function get_where_report_period($field = 'date')
    {
        $months_report      = $this->input->post('report_months');
        $custom_date_select = '';
        if ($months_report != '') {
            if (is_numeric($months_report)) {
                // Last month
                if ($months_report == '1') {
                    $beginMonth = date('Y-m-01', strtotime('first day of last month'));
                    $endMonth   = date('Y-m-t', strtotime('last day of last month'));
                } else {
                    $months_report = (int) $months_report;
                    $months_report--;
                    $beginMonth = date('Y-m-01', strtotime("-$months_report MONTH"));
                    $endMonth   = date('Y-m-t');
                }

                $custom_date_select = 'AND (' . $field . ' BETWEEN "' . $beginMonth . '" AND "' . $endMonth . '")';
            } elseif ($months_report == 'this_month') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' . date('Y-m-01') . '" AND "' . date('Y-m-t') . '")';
            } elseif ($months_report == 'this_year') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' .
                date('Y-m-d', strtotime(date('Y-01-01'))) .
                '" AND "' .
                date('Y-m-d', strtotime(date('Y-12-31'))) . '")';
            } elseif ($months_report == 'last_year') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' .
                date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01'))) .
                '" AND "' .
                date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31'))) . '")';
            } elseif ($months_report == 'custom') {
                $from_date = to_sql_date($this->input->post('report_from'));
                $to_date   = to_sql_date($this->input->post('report_to'));
                if ($from_date == $to_date) {
                    $custom_date_select = 'AND ' . $field . ' = "' . $from_date . '"';
                } else {
                    $custom_date_select = 'AND (' . $field . ' BETWEEN "' . $from_date . '" AND "' . $to_date . '")';
                }
            }
        }

        return $custom_date_select;
    }

    public function items()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $v = $this->db->query('SELECT VERSION() as version')->row();
            // 5.6 mysql version don't have the ANY_VALUE function implemented.

            if ($v && strpos($v->version, '5.7') !== false) {
                $aColumns = [
                        'ANY_VALUE(description) as description',
                        'ANY_VALUE((SUM(' . db_prefix() . 'itemable.qty))) as quantity_sold',
                        'ANY_VALUE(SUM(rate*qty)) as rate',
                        'ANY_VALUE(AVG(rate*qty)) as avg_price',
                    ];
            } else {
                $aColumns = [
                        'description as description',
                        '(SUM(' . db_prefix() . 'itemable.qty)) as quantity_sold',
                        'SUM(rate*qty) as rate',
                        'AVG(rate*qty) as avg_price',
                    ];
            }

            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'itemable';
            $join         = ['JOIN ' . db_prefix() . 'invoices ON ' . db_prefix() . 'invoices.id = ' . db_prefix() . 'itemable.rel_id'];

            $where = ['AND rel_type="invoice"', 'AND status != 5', 'AND status=2'];

            $custom_date_select = $this->get_where_report_period();
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }
            $by_currency = $this->input->post('report_currency');
            if ($by_currency) {
                $currency = $this->currencies_model->get($by_currency);
                array_push($where, 'AND currency=' . $by_currency);
            } else {
                $currency = $this->currencies_model->get_base_currency();
            }

            if ($this->input->post('sale_agent_items')) {
                $agents  = $this->input->post('sale_agent_items');
                $_agents = [];
                if (is_array($agents)) {
                    foreach ($agents as $agent) {
                        if ($agent != '') {
                            array_push($_agents, $agent);
                        }
                    }
                }
                if (count($_agents) > 0) {
                    array_push($where, 'AND sale_agent IN (' . implode(', ', $_agents) . ')');
                }
            }

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [], 'GROUP by description');

            $output  = $result['output'];
            $rResult = $result['rResult'];

            $footer_data = [
                'total_amount' => 0,
                'total_qty'    => 0,
            ];

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = $aRow['description'];
                $row[] = $aRow['quantity_sold'];
                $row[] = app_format_money($aRow['rate'], $currency->name);
                $row[] = app_format_money($aRow['avg_price'], $currency->name);
                $footer_data['total_amount'] += $aRow['rate'];
                $footer_data['total_qty'] += $aRow['quantity_sold'];
                $output['aaData'][] = $row;
            }

            $footer_data['total_amount'] = app_format_money($footer_data['total_amount'], $currency->name);

            $output['sums'] = $footer_data;
            echo json_encode($output);
            die();
        }
    }

    public function credit_notes()
    {
        if ($this->input->is_ajax_request()) {
            $credit_note_taxes = $this->distinct_taxes('credit_note');
            $totalTaxesColumns = count($credit_note_taxes);

            $this->load->model('currencies_model');

            $select = [
                'number',
                'date',
                get_sql_select_client_company(),
                'reference_no',
                'subtotal',
                'total',
                'total_tax',
                'discount_total',
                'adjustment',
                '(SELECT ' . db_prefix() . 'creditnotes.total - (
                  (SELECT COALESCE(SUM(amount),0) FROM ' . db_prefix() . 'credits WHERE ' . db_prefix() . 'credits.credit_id=' . db_prefix() . 'creditnotes.id)
                  +
                  (SELECT COALESCE(SUM(amount),0) FROM ' . db_prefix() . 'creditnote_refunds WHERE ' . db_prefix() . 'creditnote_refunds.credit_note_id=' . db_prefix() . 'creditnotes.id)
                  )
                ) as remaining_amount',
                'status',
            ];

            $where = [];

            $credit_note_taxes_select = array_reverse($credit_note_taxes);

            foreach ($credit_note_taxes_select as $key => $tax) {
                array_splice($select, 5, 0, '(
                    SELECT CASE
                    WHEN discount_percent != 0 AND discount_type = "before_tax" THEN ROUND(SUM((qty*rate/100*' . db_prefix() . 'item_tax.taxrate) - (qty*rate/100*' . db_prefix() . 'item_tax.taxrate * discount_percent/100)),' . get_decimal_places() . ')
                    WHEN discount_total != 0 AND discount_type = "before_tax" THEN ROUND(SUM((qty*rate/100*' . db_prefix() . 'item_tax.taxrate) - (qty*rate/100*' . db_prefix() . 'item_tax.taxrate * (discount_total/subtotal*100) / 100)),' . get_decimal_places() . ')
                    ELSE ROUND(SUM(qty*rate/100*' . db_prefix() . 'item_tax.taxrate),' . get_decimal_places() . ')
                    END
                    FROM ' . db_prefix() . 'itemable
                    INNER JOIN ' . db_prefix() . 'item_tax ON ' . db_prefix() . 'item_tax.itemid=' . db_prefix() . 'itemable.id
                    WHERE ' . db_prefix() . 'itemable.rel_type="credit_note" AND taxname="' . $tax['taxname'] . '" AND taxrate="' . $tax['taxrate'] . '" AND ' . db_prefix() . 'itemable.rel_id=' . db_prefix() . 'creditnotes.id) as total_tax_single_' . $key);
            }

            $custom_date_select = $this->get_where_report_period();

            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }

            $by_currency = $this->input->post('report_currency');

            if ($by_currency) {
                $currency = $this->currencies_model->get($by_currency);
                array_push($where, 'AND currency=' . $by_currency);
            } else {
                $currency = $this->currencies_model->get_base_currency();
            }

            if ($this->input->post('credit_note_status')) {
                $statuses  = $this->input->post('credit_note_status');
                $_statuses = [];
                if (is_array($statuses)) {
                    foreach ($statuses as $status) {
                        if ($status != '') {
                            array_push($_statuses, $status);
                        }
                    }
                }
                if (count($_statuses) > 0) {
                    array_push($where, 'AND status IN (' . implode(', ', $_statuses) . ')');
                }
            }

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'creditnotes';
            $join         = [
                'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'creditnotes.clientid',
            ];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
                'userid',
                'clientid',
                db_prefix() . 'creditnotes.id',
                'discount_percent',
                'deleted_customer_name',
            ]);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            $footer_data = [
                'total'            => 0,
                'subtotal'         => 0,
                'total_tax'        => 0,
                'discount_total'   => 0,
                'adjustment'       => 0,
                'remaining_amount' => 0,
            ];

            foreach ($credit_note_taxes as $key => $tax) {
                $footer_data['total_tax_single_' . $key] = 0;
            }
            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = '<a href="' . admin_url('credit_notes/list_credit_notes/' . $aRow['id']) . '" target="_blank">' . format_credit_note_number($aRow['id']) . '</a>';

                $row[] = _d($aRow['date']);

                if (empty($aRow['deleted_customer_name'])) {
                    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '">' . $aRow['company'] . '</a>';
                } else {
                    $row[] = $aRow['deleted_customer_name'];
                }

                $row[] = $aRow['reference_no'];

                $row[] = app_format_money($aRow['subtotal'], $currency->name);
                $footer_data['subtotal'] += $aRow['subtotal'];

                $row[] = app_format_money($aRow['total'], $currency->name);
                $footer_data['total'] += $aRow['total'];

                $row[] = app_format_money($aRow['total_tax'], $currency->name);
                $footer_data['total_tax'] += $aRow['total_tax'];

                $t = $totalTaxesColumns - 1;
                $i = 0;
                foreach ($credit_note_taxes as $tax) {
                    $row[] = app_format_money(($aRow['total_tax_single_' . $t] == null ? 0 : $aRow['total_tax_single_' . $t]), $currency->name);
                    $footer_data['total_tax_single_' . $i] += ($aRow['total_tax_single_' . $t] == null ? 0 : $aRow['total_tax_single_' . $t]);
                    $t--;
                    $i++;
                }

                $row[] = app_format_money($aRow['discount_total'], $currency->name);
                $footer_data['discount_total'] += $aRow['discount_total'];

                $row[] = app_format_money($aRow['adjustment'], $currency->name);
                $footer_data['adjustment'] += $aRow['adjustment'];

                $row[] = app_format_money($aRow['remaining_amount'], $currency->name);
                $footer_data['remaining_amount'] += $aRow['remaining_amount'];

                $row[] = format_credit_note_status($aRow['status']);

                $output['aaData'][] = $row;
            }

            foreach ($footer_data as $key => $total) {
                $footer_data[$key] = app_format_money($total, $currency->name);
            }

            $output['sums'] = $footer_data;
            echo json_encode($output);
            die();
        }
    }

    public function invoices_report()
    {
        if ($this->input->is_ajax_request()) {
            $invoice_taxes     = $this->distinct_taxes('invoice');
            $totalTaxesColumns = count($invoice_taxes);

            $this->load->model('currencies_model');
            $this->load->model('invoices_model');

            $select = [
                'number',
                get_sql_select_client_company(),
                'YEAR(date) as year',
                'date',
                'duedate',
                'subtotal',
                'total',
                'total_tax',
                'discount_total',
                'adjustment',
                '(SELECT COALESCE(SUM(amount),0) FROM ' . db_prefix() . 'credits WHERE ' . db_prefix() . 'credits.invoice_id=' . db_prefix() . 'invoices.id) as credits_applied',
                '(SELECT total - (SELECT COALESCE(SUM(amount),0) FROM ' . db_prefix() . 'invoicepaymentrecords WHERE invoiceid = ' . db_prefix() . 'invoices.id) - (SELECT COALESCE(SUM(amount),0) FROM ' . db_prefix() . 'credits WHERE ' . db_prefix() . 'credits.invoice_id=' . db_prefix() . 'invoices.id))',
                'status',
            ];

            $where = [
                'AND status != 5',
            ];

            $invoiceTaxesSelect = array_reverse($invoice_taxes);

            foreach ($invoiceTaxesSelect as $key => $tax) {
                array_splice($select, 8, 0, '(
                    SELECT CASE
                    WHEN discount_percent != 0 AND discount_type = "before_tax" THEN ROUND(SUM((qty*rate/100*' . db_prefix() . 'item_tax.taxrate) - (qty*rate/100*' . db_prefix() . 'item_tax.taxrate * discount_percent/100)),' . get_decimal_places() . ')
                    WHEN discount_total != 0 AND discount_type = "before_tax" THEN ROUND(SUM((qty*rate/100*' . db_prefix() . 'item_tax.taxrate) - (qty*rate/100*' . db_prefix() . 'item_tax.taxrate * (discount_total/subtotal*100) / 100)),' . get_decimal_places() . ')
                    ELSE ROUND(SUM(qty*rate/100*' . db_prefix() . 'item_tax.taxrate),' . get_decimal_places() . ')
                    END
                    FROM ' . db_prefix() . 'itemable
                    INNER JOIN ' . db_prefix() . 'item_tax ON ' . db_prefix() . 'item_tax.itemid=' . db_prefix() . 'itemable.id
                    WHERE ' . db_prefix() . 'itemable.rel_type="invoice" AND taxname="' . $tax['taxname'] . '" AND taxrate="' . $tax['taxrate'] . '" AND ' . db_prefix() . 'itemable.rel_id=' . db_prefix() . 'invoices.id) as total_tax_single_' . $key);
            }

            $custom_date_select = $this->get_where_report_period();
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }

            if ($this->input->post('sale_agent_invoices')) {
                $agents  = $this->input->post('sale_agent_invoices');
                $_agents = [];
                if (is_array($agents)) {
                    foreach ($agents as $agent) {
                        if ($agent != '') {
                            array_push($_agents, $agent);
                        }
                    }
                }
                if (count($_agents) > 0) {
                    array_push($where, 'AND sale_agent IN (' . implode(', ', $_agents) . ')');
                }
            }

            $by_currency              = $this->input->post('report_currency');
            $totalPaymentsColumnIndex = (12 + $totalTaxesColumns - 1);

            if ($by_currency) {
                $_temp = substr($select[$totalPaymentsColumnIndex], 0, -2);
                $_temp .= ' AND currency =' . $by_currency . ')) as amount_open';
                $select[$totalPaymentsColumnIndex] = $_temp;

                $currency = $this->currencies_model->get($by_currency);
                array_push($where, 'AND currency=' . $by_currency);
            } else {
                $currency                          = $this->currencies_model->get_base_currency();
                $select[$totalPaymentsColumnIndex] = $select[$totalPaymentsColumnIndex] .= ' as amount_open';
            }

            if ($this->input->post('invoice_status')) {
                $statuses  = $this->input->post('invoice_status');
                $_statuses = [];
                if (is_array($statuses)) {
                    foreach ($statuses as $status) {
                        if ($status != '') {
                            array_push($_statuses, $status);
                        }
                    }
                }
                if (count($_statuses) > 0) {
                    array_push($where, 'AND status IN (' . implode(', ', $_statuses) . ')');
                }
            }

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'invoices';
            $join         = [
                'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'invoices.clientid',
            ];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
                'userid',
                'clientid',
                db_prefix() . 'invoices.id',
                'discount_percent',
                'deleted_customer_name',
            ]);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            $footer_data = [
                'total'           => 0,
                'subtotal'        => 0,
                'total_tax'       => 0,
                'discount_total'  => 0,
                'adjustment'      => 0,
                'applied_credits' => 0,
                'amount_open'     => 0,
            ];

            foreach ($invoice_taxes as $key => $tax) {
                $footer_data['total_tax_single_' . $key] = 0;
            }

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = '<a href="' . admin_url('invoices/list_invoices/' . $aRow['id']) . '" target="_blank">' . format_invoice_number($aRow['id']) . '</a>';

                if (empty($aRow['deleted_customer_name'])) {
                    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['userid']) . '" target="_blank">' . $aRow['company'] . '</a>';
                } else {
                    $row[] = $aRow['deleted_customer_name'];
                }

                $row[] = $aRow['year'];

                $row[] = _d($aRow['date']);

                $row[] = _d($aRow['duedate']);

                $row[] = app_format_money($aRow['subtotal'], $currency->name);
                $footer_data['subtotal'] += $aRow['subtotal'];

                $row[] = app_format_money($aRow['total'], $currency->name);
                $footer_data['total'] += $aRow['total'];

                $row[] = app_format_money($aRow['total_tax'], $currency->name);
                $footer_data['total_tax'] += $aRow['total_tax'];

                $t = $totalTaxesColumns - 1;
                $i = 0;
                foreach ($invoice_taxes as $tax) {
                    $row[] = app_format_money(($aRow['total_tax_single_' . $t] == null ? 0 : $aRow['total_tax_single_' . $t]), $currency->name);
                    $footer_data['total_tax_single_' . $i] += ($aRow['total_tax_single_' . $t] == null ? 0 : $aRow['total_tax_single_' . $t]);
                    $t--;
                    $i++;
                }

                $row[] = app_format_money($aRow['discount_total'], $currency->name);
                $footer_data['discount_total'] += $aRow['discount_total'];

                $row[] = app_format_money($aRow['adjustment'], $currency->name);
                $footer_data['adjustment'] += $aRow['adjustment'];

                $row[] = app_format_money($aRow['credits_applied'], $currency->name);
                $footer_data['applied_credits'] += $aRow['credits_applied'];

                $amountOpen = $aRow['amount_open'];
                $row[]      = app_format_money($amountOpen, $currency->name);
                $footer_data['amount_open'] += $amountOpen;

                $row[] = format_invoice_status($aRow['status']);

                $output['aaData'][] = $row;
            }

            foreach ($footer_data as $key => $total) {
                $footer_data[$key] = app_format_money($total, $currency->name);
            }

            $output['sums'] = $footer_data;
            echo json_encode($output);
            die();
        }
    }

    public function expenses($type = 'simple_report')
    {
        $this->load->model('currencies_model');
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $data['currencies']    = $this->currencies_model->get();

        $data['title'] = _l('expenses_report');
        if ($type != 'simple_report') {
            $this->load->model('expenses_model');
            $data['categories'] = $this->expenses_model->get_category();
            $data['years']      = $this->expenses_model->get_expenses_years();

            if ($this->input->is_ajax_request()) {
                $aColumns = [
                    'category',
                    'amount',
                    'expense_name',
                    'tax',
                    'tax2',
                    '(SELECT taxrate FROM ' . db_prefix() . 'taxes WHERE id=' . db_prefix() . 'expenses.tax)',
                    'amount as amount_with_tax',
                    'billable',
                    'date',
                    get_sql_select_client_company(),
                    'invoiceid',
                    'reference_no',
                    'paymentmode',
                ];
                $join = [
                    'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'expenses.clientid',
                    'LEFT JOIN ' . db_prefix() . 'expenses_categories ON ' . db_prefix() . 'expenses_categories.id = ' . db_prefix() . 'expenses.category',
                ];
                $where  = [];
                $filter = [];
                include_once(APPPATH . 'views/admin/tables/includes/expenses_filter.php');
                if (count($filter) > 0) {
                    array_push($where, 'AND (' . prepare_dt_filter($filter) . ')');
                }

                $by_currency = $this->input->post('currency');
                if ($by_currency) {
                    $currency = $this->currencies_model->get($by_currency);
                    array_push($where, 'AND currency=' . $by_currency);
                } else {
                    $currency = $this->currencies_model->get_base_currency();
                }

                $sIndexColumn = 'id';
                $sTable       = db_prefix() . 'expenses';
                $result       = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
                    db_prefix() . 'expenses_categories.name as category_name',
                    db_prefix() . 'expenses.id',
                    db_prefix() . 'expenses.clientid',
                    'currency',
                ]);
                $output  = $result['output'];
                $rResult = $result['rResult'];
                $this->load->model('currencies_model');
                $this->load->model('payment_modes_model');

                $footer_data = [
                    'tax_1'           => 0,
                    'tax_2'           => 0,
                    'amount'          => 0,
                    'total_tax'       => 0,
                    'amount_with_tax' => 0,
                ];

                foreach ($rResult as $aRow) {
                    $row = [];
                    for ($i = 0; $i < count($aColumns); $i++) {
                        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
                            $_data = $aRow[strafter($aColumns[$i], 'as ')];
                        } else {
                            $_data = $aRow[$aColumns[$i]];
                        }
                        if ($aRow['tax'] != 0) {
                            $_tax = get_tax_by_id($aRow['tax']);
                        }
                        if ($aRow['tax2'] != 0) {
                            $_tax2 = get_tax_by_id($aRow['tax2']);
                        }
                        if ($aColumns[$i] == 'category') {
                            $_data = '<a href="' . admin_url('expenses/list_expenses/' . $aRow['id']) . '" target="_blank">' . $aRow['category_name'] . '</a>';
                        } elseif ($aColumns[$i] == 'expense_name') {
                            $_data = '<a href="' . admin_url('expenses/list_expenses/' . $aRow['id']) . '" target="_blank">' . $aRow['expense_name'] . '</a>';
                        } elseif ($aColumns[$i] == 'amount' || $i == 6) {
                            $total = $_data;
                            if ($i != 6) {
                                $footer_data['amount'] += $total;
                            } else {
                                if ($aRow['tax'] != 0 && $i == 6) {
                                    $total += ($total / 100 * $_tax->taxrate);
                                }
                                if ($aRow['tax2'] != 0 && $i == 6) {
                                    $total += ($aRow['amount'] / 100 * $_tax2->taxrate);
                                }
                                $footer_data['amount_with_tax'] += $total;
                            }

                            $_data = app_format_money($total, $currency->name);
                        } elseif ($i == 9) {
                            $_data = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '">' . $aRow['company'] . '</a>';
                        } elseif ($aColumns[$i] == 'paymentmode') {
                            $_data = '';
                            if ($aRow['paymentmode'] != '0' && !empty($aRow['paymentmode'])) {
                                $payment_mode = $this->payment_modes_model->get($aRow['paymentmode'], [], false, true);
                                if ($payment_mode) {
                                    $_data = $payment_mode->name;
                                }
                            }
                        } elseif ($aColumns[$i] == 'date') {
                            $_data = _d($_data);
                        } elseif ($aColumns[$i] == 'tax') {
                            if ($aRow['tax'] != 0) {
                                $_data = $_tax->name . ' - ' . app_format_number($_tax->taxrate) . '%';
                            } else {
                                $_data = '';
                            }
                        } elseif ($aColumns[$i] == 'tax2') {
                            if ($aRow['tax2'] != 0) {
                                $_data = $_tax2->name . ' - ' . app_format_number($_tax2->taxrate) . '%';
                            } else {
                                $_data = '';
                            }
                        } elseif ($i == 5) {
                            if ($aRow['tax'] != 0 || $aRow['tax2'] != 0) {
                                if ($aRow['tax'] != 0) {
                                    $total = ($total / 100 * $_tax->taxrate);
                                    $footer_data['tax_1'] += $total;
                                }
                                if ($aRow['tax2'] != 0) {
                                    $total += ($aRow['amount'] / 100 * $_tax2->taxrate);
                                    $footer_data['tax_2'] += $total;
                                }
                                $_data = app_format_money($total, $currency->name);
                                $footer_data['total_tax'] += $total;
                            } else {
                                $_data = app_format_number(0);
                            }
                        } elseif ($aColumns[$i] == 'billable') {
                            if ($aRow['billable'] == 1) {
                                $_data = _l('expenses_list_billable');
                            } else {
                                $_data = _l('expense_not_billable');
                            }
                        } elseif ($aColumns[$i] == 'invoiceid') {
                            if ($_data) {
                                $_data = '<a href="' . admin_url('invoices/list_invoices/' . $_data) . '">' . format_invoice_number($_data) . '</a>';
                            } else {
                                $_data = '';
                            }
                        }
                        $row[] = $_data;
                    }
                    $output['aaData'][] = $row;
                }

                foreach ($footer_data as $key => $total) {
                    $footer_data[$key] = app_format_money($total, $currency->name);
                }

                $output['sums'] = $footer_data;
                echo json_encode($output);
                die;
            }
            $this->load->view('admin/reports/expenses_detailed', $data);
        } else {
            if (!$this->input->get('year')) {
                $data['current_year'] = date('Y');
            } else {
                $data['current_year'] = $this->input->get('year');
            }


            $data['export_not_supported'] = ($this->agent->browser() == 'Internet Explorer' || $this->agent->browser() == 'Spartan');

            $this->load->model('expenses_model');

            $data['chart_not_billable'] = json_encode($this->reports_model->get_stats_chart_data(_l('not_billable_expenses_by_categories'), [
                'billable' => 0,
            ], [
                'backgroundColor' => 'rgba(252,45,66,0.4)',
                'borderColor'     => '#fc2d42',
            ], $data['current_year']));

            $data['chart_billable'] = json_encode($this->reports_model->get_stats_chart_data(_l('billable_expenses_by_categories'), [
                'billable' => 1,
            ], [
                'backgroundColor' => 'rgba(37,155,35,0.2)',
                'borderColor'     => '#84c529',
            ], $data['current_year']));

            $data['expense_years'] = $this->expenses_model->get_expenses_years();

            if (count($data['expense_years']) > 0) {
                // Perhaps no expenses in new year?
                if (!in_array_multidimensional($data['expense_years'], 'year', date('Y'))) {
                    array_unshift($data['expense_years'], ['year' => date('Y')]);
                }
            }

            $data['categories'] = $this->expenses_model->get_category();

            $this->load->view('admin/reports/expenses', $data);
        }
    }

    public function expenses_vs_income($year = '')
    {
        $_expenses_years = [];
        $_years          = [];
        $this->load->model('expenses_model');
        $expenses_years = $this->expenses_model->get_expenses_years();
        $payments_years = $this->reports_model->get_distinct_payments_years();

        foreach ($expenses_years as $y) {
            array_push($_years, $y['year']);
        }
        foreach ($payments_years as $y) {
            array_push($_years, $y['year']);
        }

        $_years = array_map('unserialize', array_unique(array_map('serialize', $_years)));

        if (!in_array(date('Y'), $_years)) {
            $_years[] = date('Y');
        }

        rsort($_years, SORT_NUMERIC);
        $data['report_year'] = $year == '' ? date('Y') : $year;

        $data['years']                           = $_years;
        $data['chart_expenses_vs_income_values'] = json_encode($this->reports_model->get_expenses_vs_income_report($year));
        $data['title']                           = _l('als_expenses_vs_income');
        $this->load->view('admin/reports/expenses_vs_income', $data);
    }

    /* Total income report / ajax chart*/
    public function total_income_report()
    {
        echo json_encode($this->reports_model->total_income_report());
    }

    public function report_by_payment_modes()
    {
        echo json_encode($this->reports_model->report_by_payment_modes());
    }

    public function report_by_customer_groups()
    {
        echo json_encode($this->reports_model->report_by_customer_groups());
    }

    /* Leads conversion monthly report / ajax chart*/
    public function leads_monthly_report($month)
    {
        echo json_encode($this->reports_model->leads_monthly_report($month));
    }

    private function distinct_taxes($rel_type)
    {
        return $this->db->query('SELECT DISTINCT taxname,taxrate FROM ' . db_prefix() . "item_tax WHERE rel_type='" . $rel_type . "' ORDER BY taxname ASC")->result_array();
    }







    // công bổ sung
    public function debts()
    {
        $data['suppliers'] = get_table_where('tblsuppliers');
        $data['customer'] = get_table_where('tblcustomers');
        $data['rack'] = get_table_where('tblracks');
        $data['porters'] = get_table_where('tblporters');
        $data['clients'] = get_table_where('tblclients');
        $data['other_object'] = get_table_where('tblother_object');

//        $data['business'] = get_table_where('tblbusiness');
        $data['province'] = get_table_where('province');
//        $data['type_client'] = get_table_where('tbltype_clients');

//        $data['materials'] = get_table_where('tblitems', array('rel_type' => 'materials'));

        $data['object'] = array('tblstaff' => 'nhân viên',
            'tblclients' => 'Khách hàng',
            'tblsuppliers' => 'Nhà cung cấp',
            'tblracks' => 'Lái xe',
            'tblporters' => 'Bốc vác',
            'tblother_object' => 'Vay-mượn',
            '' => 'Khác'
        );
        $data['date_end'] = date('Y-m-d');
        $date = new DateTime($data['date_end']);
        date_sub($date, date_interval_create_from_date_string('30 days'));
        $data['date_start'] = date_format($date, 'Y-m-d');
//        $data['type']=get_table_where('tbltype_clients');
        $data['title'] = _l('debts_reports');
        $this->load->view('admin/reports/debts', $data);
    }




    public function debts_client_report()
    {
        if ($this->input->is_ajax_request()) {
            ini_set('max_execution_time', 300);
            //Thu

            $mounth_report = $this->input->post('report_months');
            $start_date = $this->input->post('date_start_client');
            $start_end = $this->input->post('date_end_client');

            $start_cooperative_day = to_sql_date($this->input->post('start_cooperative_day'));
            $end_cooperative_day = to_sql_date($this->input->post('end_cooperative_day'));

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
            $start_date = $start_date . ' 00:00:00';
            $start_end = $start_end . ' ' . date('23:59:59');

            $select = array(
                'company',
                'tblclients.phonenumber',
                'city',
                'debt',
                '1',
                '2',
                '3',
                '4',
            );


            $where = array();
            if ($this->input->post('id_client')) {
                $where[] = 'AND userid=' . $this->input->post('id_client');
            }
            if ($this->input->post('start_cooperative_day')) {
                $where[] = 'AND cooperative_day>="' . $start_cooperative_day.'"';
            }
            if ($this->input->post('end_cooperative_day')) {
                $where[] = 'AND cooperative_day<="' . $end_cooperative_day.'"';
            }
            if ($this->input->post('province_client')) {
                $where[] = 'AND city=' . $this->input->post('province_client');
            }
            if (is_numeric($this->input->post('check_active_client'))) {
                $where[] = 'AND check_active=' . $this->input->post('check_active_client');
            }

            $aColumns = $select;
            $sIndexColumn = "userid";
            $sTable = 'tblclients';
            $join = array();
            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, array(
                'userid',
            ));
            $output = $result['output'];
            $rResult = $result['rResult'];

            $x = 0;

            $footer_data = array(
                'SPSN' => 0,
                'SPSC' => 0,
                'ST' => 0
            );
            $total_type_0 = 0;
            $total_type_1 = 0;

            $array_total=array();
            $array_total['total_debt']=0;

            $array_total['total_dimished']=0;
            $array_total['total_up']=0;
            $array_total['total_last']=0;
            $array_total['total_new']=0;
            $array_total['total_remaining']=0;
            $array_total['total_sl']=0;
            foreach ($rResult as $aRow) {
                $total_end=0;
                $total_bill=0;
                $debt_end = getClientDebt($aRow['userid'], null, $start_end);
                if ($this->input->post('id_rows_client')) {
                    if ($this->input->post('id_rows_client') == 1) {
                        if ($debt_end < 0) {
                            continue;
                        }
                    } elseif ($this->input->post('id_rows_client') == 2) {
                        if ($debt_end > 0) {
                            continue;
                        }
                    } elseif (empty($this->input->post('id_rows_client'))||$this->input->post('id_rows_client')==0) {
                        continue;
                    }
                }
                $row = array();
                $totalDebt = 0;
                for ($i = 0; $i < count($aColumns); $i++) {
                    $total = 0;
                    if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
                        $_data = $aRow[strafter($aColumns[$i], 'as ')];
                    } else {
                        $_data = $aRow[$aColumns[$i]];
                    }
                    if ($aColumns[$i] == 'company') {
                        $_data = '<a href="' . admin_url('clients/client/' . $aRow['userid']) . '">' . $aRow[$aColumns[$i]] . '</a>';
                    }

                    if ($aColumns[$i] == 'debt') {
                        $debit = getClientDebt($aRow['userid'], null, $startdauky);
                        $_data = number_format_data($debit);
                        $array_total['total_debt']+=$debit;
                    } elseif ($aColumns[$i] == '1') {
                        $total_dimished=getClientDebt($aRow['userid'], $start_date, $start_end, '-') * (-1);
                        $_data = number_format_data($total_dimished);
                        $array_total['total_dimished']+=$total_dimished;
                    } elseif ($aColumns[$i] == '2') {
                        $total_up=getClientDebt($aRow['userid'], $start_date, $start_end, '+');
                        $_data = number_format_data($total_up);
                        $array_total['total_up']+=$total_up;
                    } elseif ($aColumns[$i] == 'staff_sale') {
                        $business=get_table_where('tblbusiness', array('id'=>$aRow['staff_sale']), '', 'row');
                        $_data = $business->name;
                    } elseif ($aColumns[$i] == 'city') {
                        $province=get_table_where('province', array('provinceid'=>$aRow['city']), '', 'row');
                        $_data = $province->name;
                    } elseif ($aColumns[$i] == '3') {
                        $total_end=round($debt_end);
                        $_data = number_format_data(round($debt_end));
                        if ($total_end > 0) {
                            $total_type_0 += number_format_data($_data, false);
                        } else {
                            $total_type_1 += number_format_data($_data, false);
                        }
                        $array_total['total_last']+=$total_end;
                    } elseif ($aColumns[$i] == '4') {
                        $_data = '<button  onclick="view_detail_clients(' . $aRow['userid'] . ')" class="btn btn-default btn-icon"><i class="fa fa-eye"></i></button>';
                        $_data .= '<button  onclick="view_update_clients(' . $aRow['userid'] . ',this)" class="btn btn-info btn-icon"><i class="fa fa-edit"></i></button>';
                    }

                    $row[] = $_data;
                }


                $output['aaData'][] = $row;
                $x++;
            }
            foreach ($array_total as $k=>$v) {
                $array_total[$k]=number_format_data($v);
            }
            $output['array_total']=$array_total;
            $output['draw'] = 0;
            $output['total_type'] = array(
                'total_type_0' => number_format_data($total_type_0),
                'total_type_1' => number_format_data($total_type_1),
                'total_type_2' => number_format_data($total_type_0 + $total_type_1)
            );
            echo json_encode($output);
            die();
        }
    }



    public function detail_debts_client()
    {
        $id_client = $this->input->post('id_client_detail');
        if (is_numeric($id_client)) {
            $start = $this->input->post('start_detail_client');
            $end = $this->input->post('end_detail_client');
            if ($start == "") {
                $start = null;
            }
            if ($end == "") {
                $end = null;
            }
            if (isset($end) && isset($start)) {
                $start = to_sql_date($start);
                $end = to_sql_date($end);
            } elseif ((!isset($start)) && isset($end)) {
                $end = to_sql_date($end);
                $date = new DateTime($end);
                date_sub($date, date_interval_create_from_date_string('30 days'));
                $start = date_format($date, 'Y-m-d');
            } elseif (isset($start) && !isset($end)) {
                $start = to_sql_date($start);
                $date = new DateTime($start);
                $end = date("Y-m-d", strtotime("$date +30 day"));
            } elseif (!isset($start) && !isset($end)) {
                $end = date('Y-m-d');
                $date = new DateTime($end);
                ;
                date_sub($date, date_interval_create_from_date_string('30 days'));
                $start = date_format($date, 'Y-m-d');
            }
            if (!empty($start)) {
                $date = new DateTime($start);
                ;
                date_sub($date, date_interval_create_from_date_string('1 days'));
                $startdauky = date_format($date, 'Y-m-d'). ' ' . date('23:59:59');
            }
            $start = $start . ' 00:00:00';
            $end = $end . ' ' . date('23:59:59');
            $output = array();
            $output['aaData'] = array();
            $footer_data = array(
                'total' => 0,
                'payment' => 0,
                'left' => 0
            );
            foreach ($footer_data as $key => $total) {
                $footer_data[$key] = _format_number($total);
            }
            $output['sums'] = $footer_data;

            $client=get_table_where('tblclients', array('userid'=>$id_client), '', 'row');

            $row = array();
            for ($i = 0; $i < 9; $i++) {
                $row[] = '-';
            }
            $row[1] = 'Dư đầu kỳ';
            $row[8] = number_format_data(getClientDebt($id_client, null, $startdauky));


            $output['aaData'][] = $row;

//            //quỷ------------------------------------------------------------------------------------
            if ($start && $end) {
                $this->db->where('date>=', $start);
                $this->db->where('date<=', $end);
            } elseif ($start == null && $end) {
                $this->db->where('date<=', $end);
            }
            $cash_thu = $this->db->get_where('tblcash_book', array('groups!='=>14,'id_object' => 'tblclients', 'staff_id' => $id_client))->result_array();
            foreach ($cash_thu as $key => $value) {
                $row = array();
                $row[0] = _dt($value['date']);
                if ($value['type'] == 1) {
                    $row[1] = 'Phiếu chi';

                    $row[3] = $client->company;
                    $row[4] = '-';
                    $row[5] = '-';

                    $row[6] = '-';
                    $row[7] = ($value['price']);
                    $row[8] = 0;
                    $row[9] = $value['note'];
                } else {
                    $row[1] = 'Phiếu thu';
                    $row[3] = $client->company;
                    $row[4] = '-';
                    $row[5] = '-';

                    $row[6] = ($value['price']);
                    $row[7] = '-';
                    $row[8] = 0;
                    $row[9] = $value['note'];
                }
                $row[2] = '<a target="_blank" href="' . admin_url('staff/redirect/cash_book/add_cash_book-_-' . $value['id'] . '-__-') . '">'  . $value['code'] . '</a>';
                $output['aaData'][] = $row;
//                $row[5] = 0;
            }
            //end quỹ-----------------------------------------------------------------------------------------------

            //điều chỉnh công nợ-------------------------------------------------------------------------------------
            if ($start && $end) {
                $this->db->where('date>=', $start);
                $this->db->where('date<=', $end);
            } elseif ($start == null && $end) {
                $this->db->where('date<=', $end);
            }
            $this->db->where('id_object', 'tblclients');
            $this->db->where('status', '2');
            $debit_object = $this->db->get_where('tbldebit_object', array('staff_id' => $id_client))->result_array();
            foreach ($debit_object as $key => $value) {
                $row = array();
                $row[0] = _dt($value['date']);
                $row[1] = 'Điều chỉnh công nợ(Khách hàng)';
                $row[2] = '<a target="_blank" href="' . admin_url('staff/redirect/debit_object/add_debit_object-_-' . $value['id'] . '-__-') . '">' . $value['code'] . '</a>';
                $row[3] =$client->company;
                $row[4] ='-';
                $row[5] ='-';
                $row[6] = ($value['price'] < 0) ? (($value['price'] * (-1))) : '-';
                $row[7] = $value['price'] >= 0 ? (($value['price'])) : '-';
                $row[8] = 0;
                $row[9] = $value['note'];
                $output['aaData'][] = $row;
            }

            //nhập hàng trả về
            if ($start && $end) {
                $this->db->where('date>=', $start);
                $this->db->where('date<=', $end);
            } elseif ($start == null && $end) {
                $this->db->where('date<=', $end);
            }
            $this->db->where('client', $id_client);
            $this->db->where('status', 2);
            $this->db->where('type', 0);
            $debit_object = $this->db->get_where('tblreturn_product', array())->result_array();
            foreach ($debit_object as $key => $value) {
                $row = array();
                $row[0] = _dt($value['date']);
                $row[1] = 'Nhập hàng trả về';
                $row[2] = '<a target="_blank" href="' . admin_url('return_product/detail/'. $value['id']) . '">' . $value['code'] . '</a>';
                $row[3] =$client->company;
                $row[4] ='-';
                $row[5] ='-';
                $row[6] = $value['total_price'] >= 0 ? (($value['total_price'])) : '-';
                $row[7] = ($value['total_price'] < 0) ? (($value['total_price'] * (-1))) : '-';
                $row[8] = 0;
                $row[9] = $value['note'];
                $output['aaData'][] = $row;
            }


            if ($start && $end) {
                $this->db->where('date>=', $start);
                $this->db->where('date<=', $end);
            } elseif ($start == null && $end) {
                $this->db->where('date<=', $end);
            }
            $this->db->where('client', $id_client);
            $this->db->where('status', 2);
            $bill_return_product = $this->db->get_where('tblbill_return_product', array())->result_array();
            foreach ($bill_return_product as $key => $value) {
                $row = array();
                $row[0] = _dt($value['date']);
                $row[1] = 'Phiếu nhập hàng trả về';
                $row[2] = '<a target="_blank" href="' . admin_url('return_product/detail_bill/'. $value['id'].'?type=products') . '">' . $value['code'] . '</a>';
                $row[3] =$client->company;
                $row[4] ='-';
                $row[5] ='-';
                $row[6] = $value['total_bill'];
                $row[7] = '-';
                $row[8] = 0;
                $row[9] = $value['note'];
                $output['aaData'][] = $row;
            }


            $this->db->select('tblbill.*');
            if ($start && $end) {
                $this->db->where('tblbill.date>=', $start);
                $this->db->where('tblbill.date<=', $end);
            } elseif ($start == null && $end) {
                $this->db->where('tblbill.date<=', $end);
            }
            $this->db->where('client', $id_client);
            $this->db->join('tblexports', 'tblexports.id=tblbill.id_export');
            $this->db->where('tblexports.status', 2);
            $bill=$this->db->get('tblbill')->result_array();
            foreach ($bill as $key=>$value) {
                $row = array();
                $row[0] = _dt($value['date']);
                $row[1] = 'Hóa đơn';
                $row[2] = '<a target="_blank" href="' . admin_url('bill/detail/' . $value['id'] . '?type=products') . '">' . $value['code'] . '</a>';
                $row[3] =$client->company;
                $row[4] ='-';
                $row[5] ='-';
                $row[6] = '-';
                $row[7] = $value['total_bill'];
                $row[8] = 0;
                $row[9] = $value['note'];
                $pen_money=get_table_where('tblbill_incurred', array('id_bill'=>$value['id'],'type'=>2));
                foreach ($pen_money as $k=>$v) {
                    $row[7]+=$v['price'];
                }

                $output['aaData'][] = $row;
            }




            for ($i = 0; $i < count($output['aaData']); $i++) {
                for ($j = $i; $j < count($output['aaData']); $j++) {
                    if ($this->input->post('order')[0]['dir'] == 'desc') {
                        if (strtotime(to_sql_date_time($output['aaData'][$i][0])) > strtotime(to_sql_date_time($output['aaData'][$j][0]))) {
                            $tam = $output['aaData'][$j];
                            $output['aaData'][$j] = $output['aaData'][$i];
                            $output['aaData'][$i] = $tam;
                        }
                    } else {
                        if (strtotime(to_sql_date_time($output['aaData'][$i][0])) < strtotime(to_sql_date_time($output['aaData'][$j][0]))) {
                            $tam = $output['aaData'][$j];
                            $output['aaData'][$j] = $output['aaData'][$i];
                            $output['aaData'][$i] = $tam;
                        }
                    }
                }
            }
            if ($this->input->post('order')[0]['dir'] == 'desc') {
                $debit = getClientDebt($id_client, null, $startdauky);
                foreach ($output['aaData'] as $key => $value) {
                    $output['aaData'][$key][0] = _d(to_sql_date_time($value[0]));
                    $output['aaData'][$key][3]='<p class="text-center">'.$output['aaData'][$key][3].'</p>';
                    $output['aaData'][$key][8]=number_format_data($debit);
                    if ($value[6] != '-') {
                        $debit -= ($value[6]);
                        $output['aaData'][$key][6] = number_format_data($value[6]);
                        $output['aaData'][$key][8] = number_format_data($debit);
                    }
                    if ($value[7] != '-') {
                        $debit += ($value[7]);
                        $output['aaData'][$key][7] = number_format_data($value[7]);
                        $output['aaData'][$key][8] = number_format_data($debit);
                    }
                    if (empty($output['aaData'][$key][9])) {
                        $output['aaData'][$key][9]='-';
                    }
                }
            } else {
                $debit = getClientDebt($id_client, null, $startdauky);
                for ($i = count($output['aaData']) - 1; $i >= 0; $i--) {
                    $output['aaData'][$i][0] = _d(to_sql_date_time($output['aaData'][$i][0]));

                    $output['aaData'][$i][3]='<p class="text-center">'.$output['aaData'][$i][3].'</p>';
                    $output['aaData'][$i][8] = number_format_data($debit);
                    if ($output['aaData'][$i][6] != '-') {
                        $debit -= number_format_data($output['aaData'][$i][6], false);
                        $output['aaData'][$i][6] = number_format_data($output['aaData'][$i][6]);
                        $output['aaData'][$i][8] = number_format_data($debit);
                    }
                    if ($output['aaData'][$i][7] != '-') {
                        $debit += number_format_data($output['aaData'][$i][7], false);
                        $output['aaData'][$i][7] = number_format_data($output['aaData'][$i][7]);
                        $output['aaData'][$i][8] = number_format_data($debit);
                    }
                    if (empty($output['aaData'][$i][9])) {
                        $output['aaData'][$i][9]='-';
                    }
                }
            }
            $output['draw'] = 0;
            $output['iTotalRecords'] = count($output['aaData']);
            $output['iTotalDisplayRecords'] = count($output['aaData']);
            $output['clients'] = get_table_where('tblclients', array('userid' => $id_client), '', 'row')->company;
            echo json_encode($output);
            die();
        }
    }

    //end công nợ khách hàng

    public function debts_porters_report()
    {
        $start_date = $this->input->post('date_start_porters');
        $start_end = $this->input->post('date_end_porters');
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
        $start_date = $start_date . ' 00:00:00';
        $start_end = $start_end . ' ' . date('23:59:59');
        if ($this->input->is_ajax_request()) {
            $select = array(
                'name',
                'phone',
                '1',
                '2',
                '3',
                '4'
            );

            $where = array();
            if ($this->input->post('id_porter')) {
                $where[] = 'AND id=' . $this->input->post('id_porter');
            }
            $where[] = 'AND (supplier is NULL or supplier=0)';
            $aColumns = $select;
            $sIndexColumn = "id";
            $sTable = 'tblporters';
            $join = array();
            $footer_data = array(
                'total' => 0,
                'payment' => 0,
                'left' => 0
            );
            foreach ($footer_data as $key => $total) {
                $footer_data[$key] = _format_number($total);
            }

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, array('id'));

            $output = $result['output'];
            $rResult = $result['rResult'];
            $footer_data['total_amount'] = 0;

            $array_total=array();
            $array_total['money_debt']=0;
            $array_total['money_tang']=0;
            $array_total['money_giam']=0;
            $array_total['money_total']=0;
            foreach ($rResult as $key => $aRow) {
                $row = array();
                $total_end=round(getprice_porters($aRow['id'], null, $start_end));
                if ($this->input->post('id_rows_porters')) {
                    if ($this->input->post('id_rows_porters') == 1) {
                        if ($total_end < 0) {
                            continue;
                        }
                    } else {
                        if ($total_end > 0) {
                            continue;
                        }
                    }
                }
                if ($total_end == 0) {
                    continue;
                }
                for ($i = 0; $i < count($aColumns); $i++) {
                    if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
                        $_data = $aRow[strafter($aColumns[$i], 'as ')];
                    } else {
                        $_data = $aRow[$aColumns[$i]];
                    }
                    if ($aColumns[$i] == '1') {
                        $money_debt=getprice_porters($aRow['id'], null, $startdauky);
                        $_data = number_format_data($money_debt);
                        $array_total['money_debt']+=$money_debt;
                    } elseif ($aColumns[$i] == '2') {
                        $money_giam=getprice_porters($aRow['id'], $start_date, $start_end, '-') * (-1);
                        $_data = number_format_data($money_giam);
                        $array_total['money_giam']+=$money_giam;
                    } elseif ($aColumns[$i] == '3') {
                        $money_tang=getprice_porters($aRow['id'], $start_date, $start_end, '+');
                        $_data = number_format_data($money_tang);
                        $array_total['money_tang']+=$money_tang;
                    } elseif ($aColumns[$i] == '4') {
                        $_data = number_format_data($total_end);
                        $array_total['money_total']+=$total_end;
                    }

                    $row[] = $_data;
                }
                $option = '<button  onclick="view_detail_porter(' . $aRow['id'] . ')" class="btn btn-default btn-icon"><i class="fa fa-eye"></i></button>';
                $option .= '<button  onclick="view_update_porters(' . $aRow['id'] . ')" class="btn btn-info btn-icon"><i class="fa fa-edit"></i></button>';
                $row[] = $option;
                $output['aaData'][] = $row;
            }

            $footer_data['total_amount'] = format_money($footer_data['total_amount'], $currency_symbol);
            $output['sums'] = $footer_data;
            foreach ($array_total as $key=>$value) {
                $array_total[$key]=number_format_data($value);
            }
            $output['array_total'] = $array_total;
            echo json_encode($output);
            die();
        }
    }




    public function detail_debts_porters()
    {
        $id_porters = $this->input->post('id_porter_detail');
        if (is_numeric($id_porters)) {
            $start = $this->input->post('start_detail_porters');
            $end = $this->input->post('end_detail_porters');
            if ($start == "") {
                $start = null;
            }
            if ($end == "") {
                $end = null;
            }
            if (isset($end) && isset($start)) {
                $start = to_sql_date($start);
                $end = to_sql_date($end);
            } elseif ((!isset($start)) && isset($end)) {
                $end = to_sql_date($end);
                $date = new DateTime($end);
                date_sub($date, date_interval_create_from_date_string('30 days'));
                $start = date_format($date, 'Y-m-d');
            } elseif (isset($start) && !isset($end)) {
                $start = to_sql_date($start);
                $date = new DateTime($start);
                $end = date("Y-m-d", strtotime("$date +30 day"));
            } elseif (!isset($start) && !isset($end)) {
                $end = date('Y-m-d');
                $date = new DateTime($end);
                ;
                date_sub($date, date_interval_create_from_date_string('30 days'));
                $start = date_format($date, 'Y-m-d');
            }
            if (!empty($start)) {
                $date = new DateTime($start);
                ;
                date_sub($date, date_interval_create_from_date_string('1 days'));
                $startdauky = date_format($date, 'Y-m-d'). ' ' . date('23:59:59');
            }
            $start = $start . ' 00:00:00';
            $end = $end . ' ' . date('23:59:59');
            $output = array();
            $output['aaData'] = array();
            $footer_data = array(
                'total' => 0,
                'payment' => 0,
                'left' => 0
            );
            foreach ($footer_data as $key => $total) {
                $footer_data[$key] = _format_number($total);
            }
            $output['sums'] = $footer_data;
            $row = array();
            for ($i = 0; $i < 9; $i++) {
                $row[] = '-';
            }
            $row[1] = 'Dư đầu kỳ';
            $row[8] = number_format_data(getprice_porters($id_porters, null, $startdauky));
            $output['aaData'][] = $row;

            //phiếu nhập-------------------------------------------------------------------------
            $this->db->select('tblimports.id,
            tblimports.date,
            tblimports.code,
            tblimports.prefix,
            tblimports.rel_type,
            tblitems.name,
            tblimport_items.product_id,
            tblimport_items.price_porders,
            tblimport_items.total_porders,
            tblimport_items.quantity');
            $this->db->where('id_porters', $id_porters);
            if ($start && $end) {
                $this->db->where('tblimports.date>=', $start);
                $this->db->where('tblimports.date<=', $end);
            } elseif ($start == null && $end) {
                $this->db->where('tblimports.date<=', $end);
            }
            $this->db->where('id_porters', $id_porters);
            $this->db->join('tblimport_items', 'tblimport_items.import_id=tblimports.id');
            $this->db->join('tblporters_import_item', 'tblporters_import_item.id_item=tblimport_items.id');
            $this->db->join('tblitems', 'tblitems.id=tblimport_items.product_id');
            $import = $this->db->get('tblimports')->result_array();
            foreach ($import as $key => $value) {
                $row = array();
                $row[0] = _dt($value['date']);
                $row[1] = ($value['rel_type'] == 'transfer' ? 'Phiếu chuyển kho' : 'Phiếu nhập');
                $row[2] = '<a target="_blank" href="' . admin_url(($value['rel_type'] == 'transfer' ? 'imports/transfer_detail/' : 'imports/internal_detail/') . $value['id']) . '">' . $value['prefix'] . $value['code'] . '</a>';
                $row[3] = $value['name'];
                $row[4] = number_format_data($value['quantity']);
                $row[5] = number_format_data($value['price_porders']);
                $row[6] = $value['total_porders']<0?number_format_data($value['total_porders']*(-1)):'-';
                $row[7] = $value['total_porders']>=0?number_format_data($value['total_porders']):'-';
                $row[8] = 0;
                $output['aaData'][] = $row;
            }

            $this->db->select('tblimports.id,
            tblimports.date,
            tblimports.code,
            tblimports.prefix,
            tblimports.rel_type,
            tblitems.name,
            tblimport_items.product_id,
            tblimport_items.quantity');
            if ($start && $end) {
                $this->db->where('tblimports.date>=', $start);
                $this->db->where('tblimports.date<=', $end);
            } elseif ($start == null && $end) {
                $this->db->where('tblimports.date<=', $end);
            }
            $this->db->join('tblimport_items', 'tblimport_items.import_id=tblimports.id');
            $this->db->join('tblitems', 'tblitems.id=tblimport_items.product_id');
            $import = $this->db->get('tblimports')->result_array();
            foreach ($import as $key => $value) {
                // nhập bổ sung
                $this->db->where('id_import', $value['id']);
                $this->db->where('porters', $id_porters);
                $import_porters=$this->db->get('tblimport_porters_add')->result_array();
                foreach ($import_porters as $kk=>$vk) {
                    $row = array();
                    $row[0] = _dt($value['date']);
                    $row[1] = 'Phiếu nhập (Bốc vác bổ sung thêm)';
                    $row[2] = '<a target="_blank" href="' . admin_url('imports/internal_detail/' . $value['id']) . '">' .  $value['prefix'] . $value['code']. '</a>';
                    $row[3] = $value['name'];
                    $row[4] = number_format_data($value['quantity']);
                    $row[5] = $vk['price_porders']>0?number_format_data($vk['price_porders']):number_format_data($vk['price_porders']*(-1));
                    $row[6] = $vk['total_porders']<0?number_format_data($vk['total_porders']*(-1)):'-';
                    $row[7] = $vk['total_porders']>=0?number_format_data($vk['total_porders']):'-';
                    $row[8] = 0;
                    $row[9] = '-';
                    $output['aaData'][] = $row;
                }
            }
            //end phiếu nhập---------------------------------------------------------------------


            //nhập hàng trả về

            //PHIẾU NHẬP HÀNG TRẢ VỀ
            $this->db->select('tblreturn_product.*,tblreturn_product_items.*,tblreturn_product.id,tblitems.name,tblreturn_product.type');
            if ($start && $end) {
                $this->db->where('tblreturn_product.date>=', $start);
                $this->db->where('tblreturn_product.date<=', $end);
            } elseif ($start == null && $end) {
                $this->db->where('tblreturn_product.<=', $end);
            }
            $this->db->where('status', 2);
            $this->db->where('id_porters', $id_porters);
            $this->db->join('tblreturn_product_items', 'tblreturn_product_items.product_items_id=tblreturn_product.id');
            $this->db->join('tblporters_return_product_item', 'tblporters_return_product_item.id_return_product_item=tblreturn_product_items.id');
            $this->db->join('tblitems', 'tblitems.id=tblreturn_product_items.product_id');
            $return_product = $this->db->get('tblreturn_product')->result_array();
            foreach ($return_product as $key => $value) {
                $row = array();
                $row[0] = _dt($value['date']);
                $row[1] = ($value['type']==0?'Nhập hàng trả về':'Phiếu trả về do xuất thừa');
                $row[2] = '<a  target="_blank" href="' . admin_url('return_product/'.($value['type']?'detail/':'detail_rack') . $value['id']) . '">' . $value['prefix'] . $value['code'] . '</a>';

                $row[3] = $value['name'];
                $row[4] = number_format_data($value['quantity']);
                $row[5] = $value['price_porders']>=0?number_format_data($value['price_porders']):number_format_data($value['price_porders']*(-1));

                $row[6] = ($value['total_porders']<0?($value['total_porders']*(-1)):'-');
                $row[7] =($value['total_porders']>=0?$value['total_porders']:'-');
                $row[8] = '-';
                $row[9] = '-';
                $output['aaData'][] = $row;
            }


            $this->db->select('tblbill_return_product.*');
            if ($start && $end) {
                $this->db->where('tblbill_return_product.date>=', $start);
                $this->db->where('tblbill_return_product.date<=', $end);
            } elseif ($start == null && $end) {
                $this->db->where('tblbill_return_product.date<=', $end);
            }
            $this->db->where('status', 2);
            $this->db->where('porters', $id_porters);
            $bill_return_product = $this->db->get('tblbill_return_product')->result_array();
            foreach ($bill_return_product as $key => $value) {
                $row = array();
                $row[0] = _dt($value['date']);
                $row[1] = 'Phiếu nhập hàng trả về';
                $row[2] = '<a  target="_blank" href="' . admin_url('return_product/detail_bill/' . $value['id'].'?type=products') . '">' . $value['code'] . '</a>';

                $row[3] = '-';
                $row[4] = number_format_data($value['total_quantity']);
                $row[5] = $value['price_porters']>=0?number_format_data($value['price_porters']):number_format_data($value['price_porters']*(-1));

                $row[6] = $value['price_porters']*$value['total_quantity']<0?number_format_data($value['price_porters']*$value['total_quantity']*(-1)):'-';
                $row[7] = $value['price_porters']*$value['total_quantity']>=0?number_format_data($value['price_porters']*$value['total_quantity']):'-';
                $row[8] = '-';
                $row[9] = '-';
                $output['aaData'][] = $row;
            }

            //end nhập hàng trả về

            //bốc vác xuất nguyên vật liệu
            $this->db->select('tblexports_nvl.date,tblexports_nvl.code,tblexports_nvl.prefix,tblexports_nvl.id,quantity,tblitems.name,price_porders,total_porders');
            $this->db->join('tblexport_nvl_items', 'tblexport_nvl_items.export_id=tblexports_nvl.id');
            if ($start && $end) {
                $this->db->where('tblexports_nvl.date>=', $start);
                $this->db->where('tblexports_nvl.date<=', $end);
            } elseif ($start == null && $end) {
                $this->db->where('tblexports_nvl.date<=', $end);
            }
            $this->db->where('porters', $id_porters);
            $this->db->join('tblitems', 'tblitems.id=tblexport_nvl_items.product_id');
            $export_nvl = $this->db->get('tblexports_nvl')->result_array();
            foreach ($export_nvl as $key => $value) {
                $row = array();
                $row[0] = _dt($value['date']);
                $row[1] = 'Phiếu xuất NVL';
                $row[2] = '<a target="_blank" href="' . admin_url('exports_nvl/export_nvl_detail/' . $value['id']) . '">' . $value['prefix'] . $value['code'] . '</a>';
                $row[3] = $value['name'];
                $row[4] = number_format_data($value['quantity']);
                $row[5] = $value['price_porders']>=0?number_format_data($value['price_porders']):number_format_data($value['price_porders']*(-1));
                $row[6] = $value['total_porders']<0?number_format_data($value['total_porders']*(-1)):'-';
                $row[7] = $value['total_porders']>=0?number_format_data($value['total_porders']):'-';
                $row[8] = 0;
                $row[9] = '-';
                $output['aaData'][] = $row;
            }


            ///  //điều chỉnh công nợ-------------------------------------------------------------------------------------
            if ($start && $end) {
                $this->db->where('date>=', $start);
                $this->db->where('date<=', $end);
            } elseif ($start == null && $end) {
                $this->db->where('date<=', $end);
            }
            $this->db->where('id_object', 'tblporters');
            $this->db->where('status', '2');
            $debit_object = $this->db->get_where('tbldebit_object', array('staff_id' => $id_porters))->result_array();
            foreach ($debit_object as $key => $value) {
                $row = array();
                $row[0] = _dt($value['date']);
                $row[1] = 'Điều chỉnh công nợ';
                $row[2] = '<a target="_blank" href="' . admin_url('staff/redirect/debit_object/add_debit_object-_-' . $value['id'] . '-__-') . '">' . $value['code'] . '</a>';
                $row[3]="-";
                $row[4]="-";
                $row[5]="-";
                $row[6] = $value['price'] < 0 ? (number_format_data($value['price'] * (-1))) : '-';
                $row[7] = $value['price'] >= 0 ? (number_format_data($value['price'])) : '-';
                $row[8] = 0;
                $row[9] = $value['note'];
                $output['aaData'][] = $row;
            }

            //điều chỉnh công nợ--------------------------------------------------------------------------------

            //Bốc vác sang bao
            $this->db->select('tblproduction_orders_tag.date,tblproduction_orders_tag.id,total_porders,price_porders,quantity_from,tblproduction_orders_tag_detail.style,tblitems.name');
            $this->db->join('tblproduction_orders_tag_detail', 'tblproduction_orders_tag_detail.id_production_order_tag=tblproduction_orders_tag.id');
            if ($start && $end) {
                $this->db->where('tblproduction_orders_tag.date>=', $start);
                $this->db->where('tblproduction_orders_tag.date<=', $end);
            } elseif ($start == null && $end) {
                $this->db->where('tblproduction_orders_tag.date<=', $end);
            }
            $this->db->join('tblitems', 'tblitems.id=tblproduction_orders_tag_detail.id_product_from');
            $this->db->where('porters', $id_porters);
            $orders_tag = $this->db->get('tblproduction_orders_tag')->result_array();
            foreach ($orders_tag as $key => $value) {
                $row = array();
                $row[0] = _dt($value['date']);
                $row[1] = 'Đơn hàng sang bao';
                $row[2] = '<a target="_blank" href="' . admin_url('production_orders_tag/order_detail/' . $value['id']) . '">' . _d($value['date']) . '</a>';
                $row[3] = $value['name'];
                $row[4] = number_format_data($value['quantity_from']*$value['style']);
                $row[5] = $value['price_porders']>=0?number_format_data($value['price_porders']):($value['price_porders']*(-1));
                $row[6] = ($value['quantity_from']*$value['style']*$value['price_porders'])<0?number_format_data($value['quantity_from']*$value['style']*$value['price_porders']*(-1)):'-';
                $row[7] = ($value['quantity_from']*$value['style']*$value['price_porders'])>=0?number_format_data($value['quantity_from']*$value['style']*$value['price_porders']):'-';
                $row[8] = 0;
                $row[9] = '-';
                $output['aaData'][] = $row;
            }



            //quỷ------------------------------------------------------------------------------------
            if ($start && $end) {
                $this->db->where('date>=', $start);
                $this->db->where('date<=', $end);
            } elseif ($start == null && $end) {
                $this->db->where('date<=', $end);
            }
            $cash_thu = $this->db->get_where('tblcash_book', array('id_object' => 'tblporters', 'staff_id' => $id_porters))->result_array();
            foreach ($cash_thu as $key => $value) {
                $row = array();
                $row[0] = _dt($value['date']);
                if ($value['type'] == 1) {
                    $row[1] = 'Phiếu chi';
                    $row[3] = '-';
                    $row[4] = '-';
                    $row[5] = '-';
                    $row[6] = number_format_data($value['price']);
                    $row[7] = '-';
                    $row[8] = 0;
                } else {
                    $row[1] = 'Phiếu thu';
                    $row[3] = '-';
                    $row[4] = '-';
                    $row[5] = '-';
                    $row[6] = '-';
                    $row[7] = number_format_data($value['price']);
                    $row[8] = 0;
                }
                $row[2] = '<a target="_blank" href="' . admin_url('staff/redirect/cash_book/add_cash_book-_-' . $value['id'] . '-__-') . '">'  . $value['code'] . '</a>';
                $output['aaData'][] = $row;
//                    $row[5] = 0;
            }
            for ($i = 0; $i < count($output['aaData']); $i++) {
                for ($j = $i; $j < count($output['aaData']); $j++) {
                    if ($this->input->post('order')[0]['dir'] == 'desc') {
                        if (strtotime(to_sql_date_time($output['aaData'][$i][0])) > strtotime(to_sql_date_time($output['aaData'][$j][0]))) {
                            $tam = $output['aaData'][$j];
                            $output['aaData'][$j] = $output['aaData'][$i];
                            $output['aaData'][$i] = $tam;
                        }
                    } else {
                        if (strtotime(to_sql_date_time($output['aaData'][$i][0])) < strtotime(to_sql_date_time($output['aaData'][$j][0]))) {
                            $tam = $output['aaData'][$j];
                            $output['aaData'][$j] = $output['aaData'][$i];
                            $output['aaData'][$i] = $tam;
                        }
                    }
                }
            }

            if ($this->input->post('order')[0]['dir'] == 'desc') {
                $debit = getprice_porters($id_porters, null, $startdauky);
                foreach ($output['aaData'] as $key => $value) {
                    $output['aaData'][$key][0] = _d(to_sql_date_time($value[0]));
                    if ($value[6] != '-') {
                        $debit -= number_format_data($value[6], false);
                        $output['aaData'][$key][8] = number_format_data($debit);
                    }
                    if ($value[7] != '-') {
                        $debit += number_format_data($value[7], false);
                        $output['aaData'][$key][8] = number_format_data($debit);
                    }
                }
            } else {
                $debit = getprice_porters($id_porters, null, $startdauky);
                for ($i = count($output['aaData']) - 1; $i >= 0; $i--) {
                    $output['aaData'][$i][0] = _d(to_sql_date_time($output['aaData'][$i][0]));
                    if ($output['aaData'][$i][6] != '-') {
                        $debit -= number_format_data($output['aaData'][$i][6], false);
                        $output['aaData'][$i][8] = number_format_data($debit);
                    }
                    if ($output['aaData'][$i][7] != '-') {
                        $debit += number_format_data($output['aaData'][$i][7], false);
                        $output['aaData'][$i][8] = number_format_data($debit);
                    }
                }
            }
            $output['draw'] = 0;
            $output['iTotalRecords'] = count($output['aaData']);
            $output['iTotalDisplayRecords'] = count($output['aaData']);
            $output['porters'] = get_table_where('tblporters', array('id' => $id_porters), '', 'row')->name;

            echo json_encode($output);
            die();
        }
    }


    //báo cáo công nợ lái xe------------------------------------------------------------------------------------------------------------------
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    public function debts_rack_report()
    {
        $start_date = $this->input->post('date_start_racks');
        $start_end = $this->input->post('date_end_racks');
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
        $start_date = $start_date . ' 00:00:00';
        $start_end = $start_end . ' ' . date('23:59:59');
        if ($this->input->is_ajax_request()) {
            $select = array(
                'rack',
                'note',
                '1',
                '2',
                '3',
                '4'
            );

            $where = array();
            if ($this->input->post('id_racks')) {
                $where[] = "AND rackid=" . $this->input->post('id_racks');
            }

            $aColumns = $select;
            $sIndexColumn = "rackid";
            $sTable = 'tblracks';
            $join = array();
            $footer_data = array(
                'total' => 0,
                'payment' => 0,
                'left' => 0
            );
            foreach ($footer_data as $key => $total) {
                $footer_data[$key] = _format_number($total);
            }

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, array('rackid'));
            $output = $result['output'];
            $rResult = $result['rResult'];
            $footer_data['total_amount'] = 0;
            $array_total=array();
            $array_total['money_debt']=0;
            $array_total['money_tang']=0;
            $array_total['money_giam']=0;
            $array_total['money_total']=0;
            foreach ($rResult as $key => $aRow) {
                $row = array();
                $end_debt_rack=round(getprice_rack($aRow['rackid'], null, $start_end));
                if ($this->input->post('id_rows_racks')) {
                    if ($this->input->post('id_rows_racks') == 1) {
                        if ($end_debt_rack < 0) {
                            continue;
                        }
                    } else {
                        if ($end_debt_rack > 0) {
                            continue;
                        }
                    }
                }
                if ($end_debt_rack == 0) {
                    continue;
                }
                for ($i = 0; $i < count($aColumns); $i++) {
                    if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
                        $_data = $aRow[strafter($aColumns[$i], 'as ')];
                    } else {
                        $_data = $aRow[$aColumns[$i]];
                    }
                    if ($aColumns[$i] == '1') {
                        $debt_dauky=getprice_rack($aRow['rackid'], null, $startdauky);
                        $_data = number_format_data($debt_dauky);
                        $array_total['money_debt']+=$debt_dauky;
                    } elseif ($aColumns[$i] == '2') {
                        $debt_giam=getprice_rack($aRow['rackid'], $start_date, $start_end, '-') * (-1);
                        $_data = number_format_data($debt_giam);
                        $array_total['money_giam']+=$debt_giam;
                    } elseif ($aColumns[$i] == '3') {
                        $debt_tang=getprice_rack($aRow['rackid'], $start_date, $start_end, '+');
                        $_data = number_format_data($debt_tang);
                        $array_total['money_tang']+=$debt_tang;
                    } elseif ($aColumns[$i] == '4') {
                        $_data = number_format_data($end_debt_rack);
                        $array_total['money_total']+=$end_debt_rack;
                    }

                    $row[] = $_data;
                }
                $option = '<button  onclick="view_detail_rack(' . $aRow['rackid'] . ')" class="btn btn-default btn-icon"><i class="fa fa-eye"></i></button>';
                $option .= '<button  onclick="view_update_rack(' . $aRow['rackid'] . ')" class="btn btn-default btn-icon"><i class="fa fa-edit"></i></button>';
                $row[] = $option;
                $output['aaData'][] = $row;
            }

            $currency_symbol=null;
            $footer_data['total_amount'] = format_money($footer_data['total_amount'], $currency_symbol);
            $output['sums'] = $footer_data;
            foreach ($array_total as $key=>$value) {
                $array_total[$key]=number_format_data($value);
            }
            $output['array_total'] = $array_total;
            echo json_encode($output);
            die();
        }
    }

    public function detail_debts_rack()
    {
        $id_rack = $this->input->post('id_rack_detail');
        if (is_numeric($id_rack)) {
            $start = $this->input->post('start_detail_rack');
            $end = $this->input->post('end_detail_rack');

            if ($start == "") {
                $start = null;
            }
            if ($end == "") {
                $end = null;
            }
            if (isset($end) && isset($start)) {
                $start = to_sql_date($start);
                $end = to_sql_date($end);
            } elseif ((!isset($start)) && isset($end)) {
                $end = to_sql_date($end);
                $date = new DateTime($end);
                date_sub($date, date_interval_create_from_date_string('30 days'));
                $start = date_format($date, 'Y-m-d');
            } elseif (isset($start) && !isset($end)) {
                $start = to_sql_date($start);
                $date = new DateTime($start);
                $end = date("Y-m-d", strtotime("$date +30 day"));
            } elseif (!isset($start) && !isset($end)) {
                $end = date('Y-m-d');
                $date = new DateTime($end);
                ;
                date_sub($date, date_interval_create_from_date_string('30 days'));
                $start = date_format($date, 'Y-m-d');
            }
            if (!empty($start)) {
                $date = new DateTime($start);
                ;
                date_sub($date, date_interval_create_from_date_string('1 days'));
                $startdauky = date_format($date, 'Y-m-d'). ' ' . date('23:59:59');
            }
            $start = $start . ' 00:00:00';
            $end = $end . ' ' . date('23:59:59');
            $output = array();
            $output['aaData'] = array();
            $footer_data = array(
                'total' => 0,
                'payment' => 0,
                'left' => 0
            );
            foreach ($footer_data as $key => $total) {
                $footer_data[$key] = _format_number($total);
            }
            $output['sums'] = $footer_data;
            $row = array();
            for ($i = 0; $i < 9; $i++) {
                $row[] = '-';
            }
            $row[1] = 'Dư đầu kỳ';
            $row[8] = number_format_data(getprice_rack($id_rack, null, $startdauky));
            $row[9] = '-';
            $output['aaData'][] = $row;


            //quỷ------------------------------------------------------------------------------------
            if ($start && $end) {
                $this->db->where('date>=', $start);
                $this->db->where('date<=', $end);
            } elseif ($start == null && $end) {
                $this->db->where('date<=', $end);
            }
            $cash_thu = $this->db->get_where('tblcash_book', array('id_object' => 'tblracks', 'staff_id' => $id_rack))->result_array();
            foreach ($cash_thu as $key => $value) {
                $row = array();
                $row[0] = _dt($value['date']);
                if ($value['type'] == 1) {
                    $row[1] = 'Phiếu chi';
                    $row[3] = '-';
                    $row[4] = '-';
                    $row[5] = '-';
                    $row[6] = number_format_data($value['price']);
                    $row[7] = '-';
                    $row[8] = 0;
                    $row[9] = $value['note'];
                } else {
                    $row[1] = 'Phiếu thu';
                    $row[3] = '-';
                    $row[4] = '-';
                    $row[5] = '-';
                    $row[6] = '-';
                    $row[7] = number_format_data($value['price']);
                    $row[8] = 0;
                    $row[9] = $value['note'];
                }
                $row[2] = '<a target="_blank" href="' . admin_url('staff/redirect/cash_book/add_cash_book-_-' . $value['id'] . '-__-') . '">' . $value['code'] . '</a>';

                $output['aaData'][] = $row;
            }
            //end quỹ-----------------------------------------------------------------------------------------------

            //start xuất thành phẩm
            if ($start && $end) {
                $this->db->where('date>=', $start);
                $this->db->where('date<=', $end);
            } elseif ($start == null && $end) {
                $this->db->where('date<=', $end);
            }
            $this->db->where('racks', $id_rack);
            $this->db->where('status', 2);
            $export = $this->db->get('tblexports')->result_array();
            foreach ($export as $key => $value) {
                $this->db->select('tblclients.company,quantity,price');
                $this->db->where('id_exports', $value['id']);
                $this->db->join('tblclients', 'tblclients.userid=tblfreight_charges.client');
                $freight_charges=$this->db->get('tblfreight_charges')->result_array();
                foreach ($freight_charges as $k=>$v) {
                    $row = array();
                    $row[0] = _dt($value['date']);
                    $row[1] = 'Phiếu xuất kho TP ';
                    $row[2] = '<a target="_blank" href="' . admin_url('exports/export_detail/' . $value['id']) . '">' . $value['prefix'] . $value['code'] . '</a>';
                    $row[3]=    'KH-'.$v['company'];
                    $row[4]=    number_format_data($v['quantity']);
                    $row[5]=    number_format_data($v['price']);
                    if ($v['quantity']*$v['price']==0) {
                        $row = array();
                        continue;
                    }

                    $row[7] = number_format_data($v['quantity']*$v['price']);
                    $row[6] = '-';
                    $row[8] = 0;
                    $row[9] = '-';
                    $output['aaData'][] = $row;
                }
            }

            //end xuất thành phẩm
            //

            ////phiếu xuất kho nvl
            if ($start && $end) {
                $this->db->where('date>=', $start);
                $this->db->where('date<=', $end);
            } elseif ($start == null && $end) {
                $this->db->where('date<=', $end);
            }
            $this->db->where('racks', $id_rack);
            $export = $this->db->get('tblexports_nvl')->result_array();
            foreach ($export as $key => $value) {
                $row = array();
                $row[0] = _dt($value['date']);
                $row[1] = ($value['type'] == 'not' ? 'Phiếu xuất kho cho vay' : 'Phiếu xuất kho NVL');
                $row[2] = '<a target="_blank" href="' . admin_url('exports_nvl/' . ($value['type'] == 'not' ? 'export_nvl_detail_not_type' : 'export_nvl_detail') . '/' . $value['id']) . '">' . $value['prefix'] . $value['code'] . '</a>';

                $get_company=get_table_where($value['id_object'], array('userid'=>$value['customer_id']), '', 'row');
                $row[3]=(!empty($get_company)?$get_company->company:'');
                $row[4]=$value['rack_price'];
                $row[5]=$value['total_quantity'];
                $row[6] = '-';

                if ($value['total_quantity'] * $value['rack_price']==0) {
                    $row = array();
                    continue;
                }

                $row[7] = number_format_data(($value['total_quantity'] * $value['rack_price'] ? $value['total_quantity'] * $value['rack_price'] : 0));
                $row[8] = 0;
                $row[9] = '-';

                $output['aaData'][] = $row;
            }

            //end xuất kho nvl
            //
            ////phiếu sang bao
            if ($start && $end) {
                $this->db->where('date>=', $start);
                $this->db->where('date<=', $end);
            } elseif ($start == null && $end) {
                $this->db->where('date<=', $end);
            }
            $this->db->where('racks', $id_rack);
            $this->db->where('type', 1);
            $export = $this->db->get('tblproduction_orders_tag')->result_array();
            foreach ($export as $key => $value) {
                $row = array();
                $row[0] = _dt($value['date']);
                $row[1] = 'Đơn hàng sang bao NCC';
                $row[2] = '<a target="_blank" href="' . admin_url('production_orders_tag/order_detail_supplier/' . $value['id']) . '">' . 'Phiếu ngày(' . $value['date'] . ')</a>';
                $get_company=get_table_where('tblsuppliers', array('userid'=>$value['supplier_id']), '', 'row');
                $row[3] ='NCC:'.(!empty($get_company)?$get_company->company:'');
                $row[4] = number_format_data($value['total_quantity']);
                $row[5] = number_format_data($value['rack_price']);
                $row[6] = '-';
                if (($value['total_quantity']*$value['rack_price'])==0) {
                    continue;
                }
                $row[7] = number_format_data(($value['total_quantity'] * $value['rack_price'] ? $value['total_quantity'] * $value['rack_price'] : 0));
                $row[8] = 0;
                $row[9] = '-';

                $output['aaData'][] = $row;
            }

            //end xuất kho nvl

            //nhập kho

            if ($start && $end) {
                $this->db->where('date>=', $start);
                $this->db->where('date<=', $end);
            } elseif ($start == null && $end) {
                $this->db->where('date<=', $end);
            }
            $this->db->where('rel_type!=', 'transfer');
            $this->db->where('racks', $id_rack);
            $export = $this->db->get('tblimports')->result_array();
            foreach ($export as $key => $value) {
                $row = array();
                if ($value['rel_type'] == 'internal') {
                    if ($value['type'] == 'not') {
                        $name = "Phiếu nhập trả NVL";
                        $link = "imports/internal_detail_not_type/" . $value['id'];
                    } else {
                        $name = "Phiếu nhập kho NVL";
                        $link = "imports/internal_detail/" . $value['id'];
                    }
                }
                $row[0] = _dt($value['date']);
                $row[1] = $name;
                $row[2] = '<a target="_blank" href="' . admin_url($link) . '">' . $value['prefix'] . $value['code'] . '</a>';
                $get_company=get_table_where('tblsuppliers', array('userid'=>$value['supplier_id']), '', 'row');
                $row[3] = 'NCC:'.(!empty($get_company)?$get_company->company:'');
                $row[4] = number_format_data($value['total_quantity']);
                $row[5] = number_format_data($value['rack_price']);
                $row[6] = '-';
                if (($value['total_quantity'] * $value['rack_price'])==0) {
                    continue;
                }
                $row[7] = number_format_data(($value['total_quantity'] * $value['rack_price'] ? $value['total_quantity'] * $value['rack_price'] : 0));
                $row[8] = 0;
                $row[9] = '-';
                $output['aaData'][] = $row;
            }
            //end nhập kho
            //
            ///
            //phiếu chuyển kho

            if ($start && $end) {
                $this->db->where('date>=', $start);
                $this->db->where('date<=', $end);
            } elseif ($start == null && $end) {
                $this->db->where('date<=', $end);
            }
            $this->db->where('rel_type', 'transfer');
            $this->db->where('racks', $id_rack);
            $export = $this->db->get('tblimports')->result_array();
            foreach ($export as $key => $value) {
                $this->db->select('rack_price_type,quantity');
                $this->db->where('id_import', $value['id']);
                $racks_transfers = $this->db->get('tblracks_transfers')->result_array();
                if ($value['rel_type'] == 'transfer') {
                    $name = "Phiếu chuyển kho";
                    $link = "imports/transfer_detail/" . $value['id'];
                } else {
                    continue;
                }
                foreach ($racks_transfers as $k=>$v) {
                    $row = array();
                    $row[0] = _dt($value['date']);
                    $row[1] = $name;
                    $row[2] = '<a target="_blank" href="' . admin_url($link) . '">' . $value['prefix'] . $value['code'] . '</a>';

                    $row[3] = '-';
                    $row[4] = number_format_data($v['quantity']);
                    $row[5] = number_format_data($v['rack_price_type']);

                    $row[6] = '-';
                    if (($v['quantity']*$v['rack_price_type'])==0) {
                        continue;
                    }
                    $row[7] = number_format_data(($v['quantity']*$v['rack_price_type'] ? $v['quantity']*$v['rack_price_type'] : 0));
                    $row[8] = 0;
                    $row[9] = '-';
                    $output['aaData'][] = $row;
                }
            }
            //end nhập kho
            //
            /// //nhập kho trả về

            if ($start && $end) {
                $this->db->where('date>=', $start);
                $this->db->where('date<=', $end);
            } elseif ($start == null && $end) {
                $this->db->where('date<=', $end);
            }
            $this->db->where('status', 2);
            $this->db->where('racks', $id_rack);
            $export = $this->db->get('tblreturn_product')->result_array();
            foreach ($export as $key => $value) {
                $row = array();
                $row[0] = _dt($value['date']);
                $row[1] = ($value['type']==0?'Phiếu nhập hàng trả về':'Hàng trả về do xuất thừa');
                $row[2] = '<a target="_blank" href="' . admin_url('return_product/detail/' . $value['id']) . '">' . $value['prefix'] . $value['code'] . '</a>';
                $get_company=get_table_where('tblclients', array('userid'=>$value['client']), '', 'row');
                $row[3] = 'KH: '.(!empty($get_company)?$get_company->company:'');
                $row[4] = number_format_data($value['total_quantity']);
                $row[5] = number_format_data($value['rack_price']);
                $row[6] = '-';
                if (($value['total_quantity'] * $value['rack_price'])==0) {
                    continue;
                }
                $row[7] = number_format_data(($value['total_quantity'] * $value['rack_price'] ? $value['total_quantity'] * $value['rack_price'] : 0));
                $row[8] = 0;
                $row[9] = '-';
                $output['aaData'][] = $row;
            }

            if ($start && $end) {
                $this->db->where('date>=', $start);
                $this->db->where('date<=', $end);
            } elseif ($start == null && $end) {
                $this->db->where('date<=', $end);
            }
            $this->db->where('status', 2);
            $this->db->where('racks', $id_rack);
            $export = $this->db->get('tblbill_return_product')->result_array();
            foreach ($export as $key => $value) {
                $row = array();
                $row[0] = _dt($value['date']);
                $row[1] = 'Phiếu nhập hàng trả về';
                $row[2] = '<a target="_blank" href="' . admin_url('return_product/detail_bill/' . $value['id']) . '?type=products">' . $value['code'] . '</a>';
                $get_company=get_table_where('tblclients', array('userid'=>$value['client']), '', 'row');
                $row[3] = 'KH: '.(!empty($get_company)?$get_company->company:'');
                $row[4] = number_format_data($value['total_quantity']);
                $row[5] = number_format_data($value['rack_price']);
                $row[6] = '-';
                if (($value['total_quantity'] * $value['rack_price'])==0) {
                    continue;
                }
                $row[7] = number_format_data(($value['total_quantity'] * $value['rack_price'] ? $value['total_quantity'] * $value['rack_price'] : 0));
                $row[8] = 0;
                $row[9] = '-';
                $output['aaData'][] = $row;
            }
            //end nhập kho

            ///
            ///  //điều chỉnh công nợ-------------------------------------------------------------------------------------
            if ($start && $end) {
                $this->db->where('date>=', $start);
                $this->db->where('date<=', $end);
            } elseif ($start == null && $end) {
                $this->db->where('date<=', $end);
            }
            $this->db->where('id_object', 'tblracks');
            $this->db->where('status', '2');
            $debit_object = $this->db->get_where('tbldebit_object', array('staff_id' => $id_rack))->result_array();
            foreach ($debit_object as $key => $value) {
                $row = array();
                $row[0] = _dt($value['date']);
                $row[1] = 'Điều chỉnh công nợ';
                $row[2] = '<a target="_blank" href="' . admin_url('staff/redirect/debit_object/add_debit_object-_-' . $value['id'] . '-__-') . '">' . $value['code'] . '</a>';

                $row[3]='-';
                $row[4]='-';
                $row[5]='-';
                if (($value['price'])==0) {
                    continue;
                }

                $row[6] = $value['price'] < 0 ? (number_format_data($value['price'] * (-1))) : '-';
                $row[7] = $value['price'] > 0 ? (number_format_data($value['price'])) : '-';
                $row[8] = 0;
                $row[9] = $value['note'];
                $output['aaData'][] = $row;
            }
            //điều chỉnh công nợ--------------------------------------------------------------------------------

            for ($i = 0; $i < count($output['aaData']); $i++) {
                for ($j = $i; $j < count($output['aaData']); $j++) {
                    if ($this->input->post('order')[0]['dir'] == 'desc') {
                        if (strtotime(to_sql_date_time($output['aaData'][$i][0])) > strtotime(to_sql_date_time($output['aaData'][$j][0]))) {
                            $tam = $output['aaData'][$j];
                            $output['aaData'][$j] = $output['aaData'][$i];
                            $output['aaData'][$i] = $tam;
                        }
                    } else {
                        if (strtotime(to_sql_date_time($output['aaData'][$i][0])) < strtotime(to_sql_date_time($output['aaData'][$j][0]))) {
                            $tam = $output['aaData'][$j];
                            $output['aaData'][$j] = $output['aaData'][$i];
                            $output['aaData'][$i] = $tam;
                        }
                    }
                }
            }
            if ($this->input->post('order')[0]['dir'] == 'desc') {
                $debit = getprice_rack($id_rack, null, $startdauky);
                foreach ($output['aaData'] as $key => $value) {
                    $output['aaData'][$key][0] = _d(to_sql_date_time($value[0]));
                    if ($value[6] != '-') {
                        $debit -= number_format_data($value[6], false);
                        $output['aaData'][$key][8] = number_format_data($debit);
                    }
                    if ($value[7] != '-') {
                        $debit += number_format_data($value[7], false);
                        $output['aaData'][$key][8] = number_format_data($debit);
                    }
                }
            } else {
                $debit = getprice_rack($id_rack, null, $startdauky);
                for ($i = count($output['aaData']) - 1; $i >= 0; $i--) {
                    $output['aaData'][$i][0] = _d(to_sql_date_time($output['aaData'][$i][0]));
                    if ($output['aaData'][$i][6] != '-') {
                        $debit -= number_format_data($output['aaData'][$i][6], false);
                        $output['aaData'][$i][8] = number_format_data($debit);
                    }
                    if ($output['aaData'][$i][7] != '-') {
                        $debit += number_format_data($output['aaData'][$i][7], false);
                        $output['aaData'][$i][8] = number_format_data($debit);
                    }
                }
            }
            $output['draw'] = 0;
            $output['iTotalRecords'] = count($output['aaData']);
            $output['iTotalDisplayRecords'] = count($output['aaData']);
            $output['rack'] = get_table_where('tblracks', array('rackid' => $id_rack), '', 'row')->rack;

            echo json_encode($output);
            die();
        }
    }



    //báo cáo công nợ cá nhan-------------------------------------------------------------------------------------------
    //--------------------------
    //---------------------------
    //------------------------------
    public function debts_personal_report()
    {
        $start_date = $this->input->post('date_start_personal');
        $start_end = $this->input->post('date_end_personal');

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

        $start_date = $start_date . ' 00:00:00';
        $start_end = $start_end . ' ' . date('23:59:59');
        $total_type_0 = 0;
        $total_type_1 = 0;
        if ($this->input->is_ajax_request()) {
            $select = array(
                'id',
                'name',
                '1',
                '2',
                '3',
                '4'
            );

            $where = array();
            if ($this->input->post('id_other_object')) {
                $where[] = "AND id=" . $this->input->post('id_other_object');
            }

            $aColumns = $select;
            $sIndexColumn = "id";
            $sTable = 'tblother_object';
            $join = array();
            $footer_data = array(
                'total' => 0,
                'payment' => 0,
                'left' => 0
            );
            foreach ($footer_data as $key => $total) {
                $footer_data[$key] = _format_number($total);
            }

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, array());

            $output = $result['output'];
            $rResult = $result['rResult'];
            $footer_data['total_amount'] = 0;



            foreach ($rResult as $key => $aRow) {
                $row = array();


                $price_personal = getprice_personal($aRow['id'], null, $start_end);

                if ($this->input->post('id_rows_personal')) {
                    if ($this->input->post('id_rows_personal') != 3) {
                        if ($this->input->post('id_rows_personal') == 1) {
                            if ($price_personal < 0) {
                                continue;
                            }
                        } else {
                            if ($price_personal > 0) {
                                continue;
                            }
                        }
                    }
                }


                if ($price_personal == 0) {
                    if ($this->input->post('id_rows_personal') != 3) {
                        continue;
                    }
                }
                for ($i = 0; $i < count($aColumns); $i++) {
                    if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
                        $_data = $aRow[strafter($aColumns[$i], 'as ')];
                    } else {
                        $_data = $aRow[$aColumns[$i]];
                    }
                    if ($aColumns[$i] == 'id') {
                        $_data = '<a target="_blank"  href="' . admin_url('staff/redirect/other_object/view_init_department-_-' . $aRow['id'] . '-__-') . '">' . $aRow['name'] . '</a>';
                    }
                    if ($aColumns[$i] == 'name') {
                        $_data = 'Vay-mượn';
                    }
                    if ($aColumns[$i] == '1') {
                        $_data = number_format_data(getprice_personal($aRow['id'], null, $startdauky));
                    } elseif ($aColumns[$i] == '2') {
                        $_data = number_format_data(getprice_personal($aRow['id'], $start_date, $start_end, '-') * (-1));
                    } elseif ($aColumns[$i] == '3') {
                        $_data = number_format_data(getprice_personal($aRow['id'], $start_date, $start_end, '+'));
                    } elseif ($aColumns[$i] == '4') {
                        $_data = number_format_data(round($price_personal));
                        if (number_format_data($_data, false) > 0) {
                            $total_type_0 += number_format_data($_data, false);
                        } else {
                            $total_type_1 += number_format_data($_data, false);
                        }
                    }

                    $row[] = $_data;
                }
                $option = '<button  onclick="view_detail_personal(\'\' ,\'' . $aRow['id'] . '\')" class="btn btn-default btn-icon"><i class="fa fa-eye"></i></button>';
//                if ($aRow['id_object'] == 'tblother_object') {
//                    $option .= '<button  onclick="view_update_other_object(' . $aRow['staff_id'] . ')" class="btn btn-default btn-icon"><i class="fa fa-edit"></i></button>';
//                }
                $row[] = $option;
                $output['aaData'][] = $row;
            }


            $output['sums'] = $footer_data;
            $output['total_type'] = array(
                'total_type_0' => number_format_data($total_type_0),
                'total_type_1' => number_format_data($total_type_1),
                'total_type_2' => number_format_data($total_type_0 + $total_type_1)
            );
            echo json_encode($output);
            die();
        }
    }

    public function detail_debts_personal()
    {
        $id_object = $this->input->post('id_object_detail');
        $staff_id = $this->input->post('staff_id_detail');
        if ($staff_id) {
            $start = $this->input->post('start_detail_personal');
            $end = $this->input->post('end_detail_personal');
            if ($start == "") {
                $start = null;
            }
            if ($end == "") {
                $end = null;
            }
            if (isset($end) && isset($start)) {
                $start = to_sql_date($start);
                $end = to_sql_date($end);
            } elseif ((!isset($start)) && isset($end)) {
                $end = to_sql_date($end);
                $date = new DateTime($end);
                date_sub($date, date_interval_create_from_date_string('30 days'));
                $start = date_format($date, 'Y-m-d');
            } elseif (isset($start) && !isset($end)) {
                $start = to_sql_date($start);
                $date = new DateTime($start);
                $end = date("Y-m-d", strtotime("$date +30 day"));
            } elseif (!isset($start) && !isset($end)) {
                $end = date('Y-m-d');
                $date = new DateTime($end);
                ;
                date_sub($date, date_interval_create_from_date_string('30 days'));
                $start = date_format($date, 'Y-m-d');
            }
            if (!empty($start)) {
                $date = new DateTime($start);
                ;
                date_sub($date, date_interval_create_from_date_string('1 days'));
                $startdauky = date_format($date, 'Y-m-d'). ' ' . date('23:59:59');
            }
            $start = $start . ' 00:00:00';
            $end = $end . ' ' . date('23:59:59');
            $output = array();
            $output['aaData'] = array();

            $footer_data = array(
                'total' => 0,
                'payment' => 0,
                'left' => 0
            );
            foreach ($footer_data as $key => $total) {
                $footer_data[$key] = _format_number($total);
            }
            $output['sums'] = $footer_data;
            $row = array();
            for ($i = 0; $i < 7; $i++) {
                $row[] = '-';
            }
            $row[1] = 'Tiền vay mượn trước đó';
            $row[5] = number_format_data(getprice_personal($staff_id, null, $startdauky));
            $row[6]="-";
            $output['aaData'][] = $row;

            //quỷ------------------------------------------------------------------------------------
            if ($start && $end) {
                $this->db->where('date>=', $start);
                $this->db->where('date<=', $end);
            } elseif ($start == null && $end) {
                $this->db->where('date<=', $end);
            }
            $cash_thu = $this->db->get_where('tblcash_book', array('id_object' => 'tblother_object', 'groups' => 8, 'staff_id' => $staff_id))->result_array();
            foreach ($cash_thu as $key => $value) {
                $row = array();
                $row[0] = _dt($value['date']);
                if ($value['type'] == 1) {
                    $row[1] = 'Phiếu chi';
                    $row[2] = '<a target="_blank" href="' . admin_url('staff/redirect/cash_book/add_cash_book-_-' . $value['id'] . '-__-') . '">' . $value['code'] . '</a>';
                    $row[3] = number_format_data($value['price']);
                    $row[4] = '-';
                    $row[5] = 0;
                    $row[6] = $value['note'];
                } else {
                    $row[1] = 'Phiếu thu';
                    $row[2] = '<a target="_blank" href="' . admin_url('staff/redirect/cash_book/add_cash_book-_-' . $value['id'] . '-__-') . '">' . $value['code'] . '</a>';
                    $row[3] = '-';
                    $row[4] = number_format_data($value['price']);
                    $row[5] = 0;
                    $row[6]=$value['note'];
                }
                $output['aaData'][] = $row;
            }
            //end quỹ-----------------------------------------------------------------------------------------------
            ///
            ///  //điều chỉnh công nợ-------------------------------------------------------------------------------------
            if ($start && $end) {
                $this->db->where('date>=', $start);
                $this->db->where('date<=', $end);
            } elseif ($start == null && $end) {
                $this->db->where('date<=', $end);
            }
            $this->db->where('id_object', 'tblother_object');
            $this->db->where('status', '2');
            $debit_object = $this->db->get_where('tbldebit_object', array('staff_id' => $staff_id))->result_array();
            foreach ($debit_object as $key => $value) {
                $row = array();
                $row[0] = _dt($value['date']);
                $row[1] = 'Điều chỉnh công nợ';
                $row[2] = '<a target="_blank" href="' . admin_url('staff/redirect/debit_object/add_debit_object-_-' . $value['id'] . '-__-') . '">' . $value['code'] . '</a>';
                $row[3] = $value['price'] < 0 ? (number_format_data($value['price'] * (-1))) : '-';
                $row[4] = $value['price'] >= 0 ? (number_format_data($value['price'])) : '-';
                $row[5] = 0;
                $row[6] = $value['note'];
                $output['aaData'][] = $row;
            }
            //điều chỉnh công nợ--------------------------------------------------------------------------------

            for ($i = 0; $i < count($output['aaData']); $i++) {
                for ($j = $i; $j < count($output['aaData']); $j++) {
                    if ($this->input->post('order')[0]['dir'] == 'desc') {
                        if (strtotime(to_sql_date($output['aaData'][$i][0], true)) > strtotime(to_sql_date($output['aaData'][$j][0], true))) {
                            $tam = $output['aaData'][$j];
                            $output['aaData'][$j] = $output['aaData'][$i];
                            $output['aaData'][$i] = $tam;
                        }
                    } else {
                        if (strtotime(to_sql_date($output['aaData'][$i][0], true)) < strtotime(to_sql_date($output['aaData'][$j][0], true))) {
                            $tam = $output['aaData'][$j];
                            $output['aaData'][$j] = $output['aaData'][$i];
                            $output['aaData'][$i] = $tam;
                        }
                    }
                }
            }
            if ($this->input->post('order')[0]['dir'] == 'desc') {
                $debit = getprice_personal($staff_id, null, $startdauky);
                foreach ($output['aaData'] as $key => $value) {
                    $output['aaData'][$key][0] = _d(to_sql_date($value[0], true));
                    if ($value[3] != '-') {
                        $debit -= number_format_data($value[3], false);
                        $output['aaData'][$key][5] = number_format_data($debit);
                    }
                    if ($value[4] != '-') {
                        $debit += number_format_data($value[4], false);
                        $output['aaData'][$key][5] = number_format_data($debit);
                    }
                }
            } else {
                $debit = getprice_personal($staff_id, null, $startdauky);
                for ($i = count($output['aaData']) - 1; $i >= 0; $i--) {
                    $output['aaData'][$i][0] = _d(to_sql_date($output['aaData'][$i][0], true));
                    if ($output['aaData'][$i][3] != '-') {
                        $debit -= number_format_data($output['aaData'][$i][3], false);
                        $output['aaData'][$i][5] = number_format_data($debit);
                    }
                    if ($output['aaData'][$i][4] != '-') {
                        $debit += number_format_data($output['aaData'][$i][4], false);
                        $output['aaData'][$i][5] = number_format_data($debit);
                    }
                }
            }
            $output['draw'] = 0;
            $output['iTotalRecords'] = count($output['aaData']);
            $output['iTotalDisplayRecords'] = count($output['aaData']);
            $output['personal'] = 'Vay-mượn';
            $output['personal_name'] = $staff_id;
            if (is_numeric($staff_id)) {
                $object = get_table_where('tblother_object', array('id' => $staff_id), '', 'row');
                $output['personal'] .= ' :<a target="_blank" href="' . admin_url('staff/redirect/other_object/view_init_department-_-' . $object->id . '-__-') . '">' . $object->name . '</a>';
                $output['personal_name'] = $object->name;
            }

            echo json_encode($output);
            die();
        }
    }


    public function debts_report_control()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('report_debt_control');
        }
    }






    // xuất excel
    public function detail_debts_customer_excel()
    {
        $start_date = $this->input->get('start');
        $start_end = $this->input->get('end');

        $start_date_title = $this->input->get('start');
        $start_end_title = $this->input->get('end');

        $id_customer = $this->input->get('customer');
        $filter_debits = $this->input->get('shop');

        $id_rows_customer = $this->input->post('id_rows_customer');

        $where_orders = '';
        $where_cash = '';
        $where_debt = '';
        if (!empty($start_date)) {
            $start_date = to_sql_date($start_date);
            $where_orders.= ' AND DATE_FORMAT(date_debits,"%Y-%m-%d") >= "'.$start_date.'"';
            $where_cash.= 'AND DATE_FORMAT(date_control,"%Y-%m-%d") >= "'.$start_date.'"';
            $where_debt.= 'AND DATE_FORMAT(date,"%Y-%m-%d") >= "'.$start_date.'"';
        }
        if (!empty($start_end)) {
            $start_end = to_sql_date($start_end);
            $where_orders.= ' AND DATE_FORMAT(date_debits,"%Y-%m-%d") <= "'.$start_end.'"';
            $where_cash.= 'AND DATE_FORMAT(date_control,"%Y-%m-%d") <= "'.$start_end.'"';
            $where_debt.= 'AND DATE_FORMAT(date,"%Y-%m-%d") <= "'.$start_end.'"';
        }

        $this->db->where('id', $id_customer);
        $getShop = $this->db->get('tblcustomers')->row();
        if(!empty($getShop)) {
        	$filter_debits = $getShop->customer_shop_code;
        }

        $data_aaDATA;
        $sql = '
                (
                    SELECT
                        id ,
                        date_debits AS date_create ,
                        DATE_FORMAT(date_create, "%Y-%m-%d") as created ,
                        status_debts ,
                        code_supership AS code_display ,
                        status ,
                        collect AS ps_in ,
                        hd_fee AS ps_de ,
                        note ,
                        mass ,
                        receiver ,
                        city ,
                        district,
                        2 as type,
                        phone
                    FROM tblorders_shop
                    WHERE shop = "'.$filter_debits.'"
                        '.$where_orders.'
                        AND status != "Huỷ"
                )
                UNION ALL
                (
                    SELECT
                         id ,
                         date_control AS date_create ,
                         DATE_FORMAT(date, "%Y-%m-%d") as created ,
                         type AS status_debts ,
                         code AS code_display ,
                         status ,
                         price AS ps_in ,
                         price AS ps_de ,
                         note ,
                         mass ,
                         receiver ,
                         city ,
                         district,
                         type,
                         0 as phone
                     FROM tblcash_book
                     WHERE id_object = "tblcustomers" and groups = 5
                          AND staff_id = "'.$id_customer.'"
                          '.$where_cash.'
               )
               UNION ALL
               (
                    SELECT
                        id ,
                        date AS date_create ,
                        DATE_FORMAT(date_create, "%Y-%m-%d") as created ,
                        status_debts ,
                        code AS code_display ,
                        "Điều Chỉnh Công Nợ" as status ,
                        price AS ps_in ,
                        price AS ps_de ,
                        note ,
                        mass ,
                        receiver ,
                        city ,
                        district,
                        2 as type,
                        0 as phone
                    FROM tbldebit_object
                    WHERE id_object = "tblcustomers"
                        AND staff_id = "'.$id_customer.'"
                        '.$where_debt.'
               )
                    ORDER BY date_create DESC
                ';

        $dataPush = $this->db->query($sql)->result();
        $aColumns     = array(
            'id',
            'date_create',
            'created',
            'status_debts',
            'code_display',
            'status',
            'ps_in',
            'ps_de',
            'sps_con_no',
            'note',
            'mass',
            'receiver',
            'city',
            'district',
            'collect',
            'type',
            'phone',
        );
        $dataPush = $this->getDetailCustomer_Order($dataPush, $aColumns);
        $colums=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC');
        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
        $this->load->library('PHPExcel');
        $objPHPExcel = new PHPExcel();
        for ($i=0; $i< 4; $i++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($colums[$i])->setAutoSize(true);
        }

        $objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.2); // ~ 1.78cm
        $objPHPExcel->getActiveSheet()->getPageMargins()->setHeader(0.2); // ~1.02cm
        $objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.2); // ~
        $objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.2); // ~1.78cm
        $objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.2); // ~1.73cm
        $objPHPExcel->getActiveSheet()->getPageMargins()->setFooter(0); // ~1.02cm

        //end caách lề phiếu in

        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

        //định dạng kiểu in ngang giấy A4

        $BStyle_not_border = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '111112'),
                'size'  => 10,
                'name'  => 'Times New Roman'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );
        $BStyle = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '111112'),
                'size'  => 10,
                'name'  => 'Times New Roman'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $BStyle_not_center = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '111112'),
                'size'  => 10,
                'name'  => 'Times New Roman'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );
        $BStyle_not_header = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'font'  => array(
                'bold'  => false,
                'color' => array('rgb' => '111112'),
                'size'  => 10,
                'name'  => 'Times New Roman'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $BStyle_header = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'font'  => array(
                'bold'  => false,
                'color' => array('rgb' => '111112'),
                'size'  => 10,
                'name'  => 'Times New Roman'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );
        $BStyle_not_header_left = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'font'  => array(
                'bold'  => false,
                'color' => array('rgb' => '111112'),
                'size'  => 10,
                'name'  => 'Times New Roman'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );
        $BStyle_not_header_right = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'font'  => array(
                'bold'  => false,
                'color' => array('rgb' => '111112'),
                'size'  => 10,
                'name'  => 'Times New Roman'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $Background_style= array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'F9F400')
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'font'  => array(
                'bold'  => false,
                'color' => array('rgb' => '111112'),
                'size'  => 10,
                'name'  => 'Times New Roman'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        for ($row = 0; $row <= 100; $row++) {
            $styleArray = [
                'font' => [
                    'size' => 12
                ]
            ];
            $objPHPExcel->getActiveSheet()
                ->getStyle("A2:P2")
                ->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(5);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(5);


            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(7);

            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);

            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);

            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(22);

            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);

            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);

            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(75);


            $TITLE = 'BẢNG ĐỐI SOÁT TIỀN HÀNG KHÁCH HÀNG: '.mb_strtoupper($filter_debits, 'UTF-8').' TỪ NGÀY '.$start_date_title.' ĐẾN NGÀY '.$start_end_title;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, $TITLE)->mergeCells('A1:G1')->getStyle('A1')->applyFromArray($BStyle_not_border);

            $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'STT')->getStyle('A2')->applyFromArray($Background_style);
            $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'Ngày Tính Nợ')->getStyle('B2')->applyFromArray($Background_style);

            $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Ngày Tạo')->getStyle('C2')->applyFromArray($Background_style);
            $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'Mã Đơn Hàng')->getStyle('D2')->applyFromArray($Background_style);

            $objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Trạng Thái Đơn Hàng')->getStyle('E2')->applyFromArray($Background_style);

            $objPHPExcel->getActiveSheet()->SetCellValue('F2', 'Trả Shop')->getStyle('F2')->applyFromArray($Background_style);
            $objPHPExcel->getActiveSheet()->SetCellValue('G2', 'Nội Dung')->getStyle('G2')->applyFromArray($Background_style);
        }
        $j = 3;
        $total = 0;
        foreach ($dataPush as $rom => $item) {

            
            $item[6] = str_replace('+', '', $item[6]);
            $item[7] = str_replace('+', '', $item[7]);
            if($item[5] == "Điều Chỉnh Công Nợ") {
                $item[6] = $item[7];
            }
            $objPHPExcel->getActiveSheet()->setCellValue('A'.($j), ($rom + 1))->getStyle('A'.$j)->applyFromArray($BStyle_not_header);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.($j), _dC($item[1]))->getStyle('B'.$j)->applyFromArray($BStyle_not_header_left);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.($j), _dC($item[2]))->getStyle('C'.$j)->applyFromArray($BStyle_not_header_left);

            $objPHPExcel->getActiveSheet()->setCellValue('D'.($j), ($item[4]))->getStyle('D'.$j)->applyFromArray($BStyle_not_header);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.($j), ($item[5]))->getStyle('E'.$j)->applyFromArray($BStyle_not_header_left);

            $objPHPExcel->getActiveSheet()->setCellValue('F'.($j), ((float)$item[6]))->getStyle('F'.$j)->applyFromArray($BStyle_not_header)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->setCellValue('G'.($j), ($item[9]))->getStyle('G'.$j)->applyFromArray($BStyle_not_header_left);
            $j++;
            $total += (float)$item[6];
        }

        $objPHPExcel->getActiveSheet()->SetCellValue('A'.($j), '')->getStyle('A'.($j))->applyFromArray($Background_style);
        $objPHPExcel->getActiveSheet()->SetCellValue('B'.($j), '')->getStyle('B'.($j))->applyFromArray($Background_style);
        $objPHPExcel->getActiveSheet()->SetCellValue('C'.($j), '')->getStyle('C'.($j))->applyFromArray($Background_style);
        $objPHPExcel->getActiveSheet()->SetCellValue('D'.($j), '')->getStyle('D'.($j))->applyFromArray($Background_style);
        $objPHPExcel->getActiveSheet()->SetCellValue('E'.($j), '')->getStyle('E'.($j))->applyFromArray($Background_style);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $j, 'Tổng')->mergeCells('A'.$j.':E'.$j)->getStyle('A'.$j)->getNumberFormat()->setFormatCode('#,##0')->applyFromArray($Background_style);
        $objPHPExcel->getActiveSheet()->SetCellValue('F'.($j), '=SUM(F3:F'.(($j - 1) >= 3 ? ($j - 1) : 4).')')->getStyle('F'.($j))->applyFromArray($Background_style)->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->SetCellValue('G'.($j), '')->getStyle('G'.($j))->applyFromArray($Background_style);



        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($j+2), 'Số tài khoản: ')->mergeCells('A'.($j+2).':B'.($j+2))->getStyle('A'.($j+2));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($j+2), ' '.(!empty($getShop->customer_number_bank) ? $getShop->customer_number_bank : ''))->mergeCells('C'.($j+2).':D'.($j+2));
//        $objPHPExcel->getActiveSheet()->SetCellValue('C'.($j+2), ' '.$getShop->customer_number_bank)->getStyle('C'.($j+2));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($j+3), 'Tên tài khoản: ')->mergeCells('A'.($j+3).':B'.($j+3));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($j+3), ' '.(!empty($getShop->customer_id_bank) ? $getShop->customer_id_bank : ''))->mergeCells('C'.($j+3).':D'.($j+3));

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($j+4), 'Tên ngân hàng: ')->mergeCells('A'.($j+4).':B'.($j+4));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($j+4), ' '.(!empty($getShop->customer_name_bank) ? $getShop->customer_name_bank : ''))->mergeCells('C'.($j+4).':D'.($j+4));

//        var_dump(mb_strtoupper($filter_debits, 'UTF-8').' '.$start_date_title.'-'.$start_end_title);die();

        $objPHPExcel->getActiveSheet()->freezePane('A1');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.mb_strtoupper($filter_debits, 'UTF-8').' '.$start_date_title.'-'.$start_end_title.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
        exit();
    }

    public function getDetailCustomer_Order($data, $aColumns)
    {
        $j=0;
        $data_aaDATA = [];
        foreach ($data as $aRow) {
            $row = array();
            $j++;


            $aRow = json_decode(json_encode($aRow), true);

            for ($i = 0; $i < count($aColumns); $i++) {
                $_data = !empty($aRow[$aColumns[$i]]) ? $aRow[$aColumns[$i]] : '';
                $row[] = $_data;
            }

            if (is_admin()) {
                $icon_get_data = "<a style='padding: 3px;' class='btn btn-default btn-icon get_data_debits' href='javascript:;' data-debits='". (!empty($aRow['name']) ? $aRow['name'] : '') . "' data-id='" .$aRow['id']."'". ">"."
                                    <i class='fa fa-eye' ></i>
                                </a>";

                $row[] =$icon_get_data.'</div>';
            } else {
                $row[] = '';
            }


            $row6 = $row[6];
            if ($row[1] == '') {
                $row[6] = 0;
            } else {
                $row[6] = (!empty($row[6]) ? $row[6] : 0)  - (!empty($row[7]) ? $row[7] : 0);
            }


            if ((int)$row[6] >= 0) {
                $row[6] = ($row[6]);
            } else {
                $row[6] = ($row[6]);
            }




            if ($row[3] == "Đơn Hàng") {
                $row[3] = "ĐH đã tính công nợ";
                $row[9] = 'Thu hộ:' . $row6 . ', Phí:'. $row[7] .
                    ' ( KL:'. $row[10] .', '.  $row[11] .', '. $row[16]  .' - '.  $row[12]  .' - '.  $row[13]  . ' )';
            }

            if ($row[3] == "ĐH chưa đối soát") {
                $row[9] = 'Thu hộ:' . $row6 . ', Phí:'. $row[7] .
                    ' ( KL:'. $row[10] .', '.  $row[11] .', '. $row[16]  .' - '.  $row[12]  .' - '.  $row[13]  . ' )';

                $row[3] = "ĐH chưa tính công nợ";
            }

            if ($row[5] == 'Chờ Lấy Hàng'
                || $row[5] == 'Đã Nhập Kho'
                || $row[5] == 'Đang Chuyển Kho Giao'
                || $row[5] == 'Đang Vận Chuyển'
                || $row[5] == 'Đã Chuyển Kho Giao'
                || $row[5] == 'Hoãn Giao Hàng') {
                $row[5] = 'Đơn Đang Giao';
            } elseif ($row[5] == 'Không Giao Được'
                || $row[5] == 'Xác Nhận Hoàn'
                || $row[5] == 'Đang Trả Hàng'
                || $row[5] == 'Hoãn Trả Hàng'
                || $row[5] == 'Đã Trả Hàng'
                || $row[5] == 'Đang Chuyển Kho Trả'
                || $row[5] == 'Đã Đối Soát Trả Hàng'
                || $row[5] == 'Đã Chuyển Kho Trả') {
                $row[5] = 'Đơn Giao Thất Bại';
                $row[6] = $row[7] * (-1);
            } elseif ($row[5] == 'Đã Giao Hàng Một Phần'
                || $row[5] == 'Đã Giao Hàng Toàn Bộ'
                || $row[5] == 'Đã Đối Soát Giao Hàng') {
                $row[5] = 'Đơn Giao Thành Công';
            }


            if ($row[15] == 1) {
                $row[6] = $row6*(-1);
            } elseif ($row[15] == 0) {
                $row[6] = $row6;
            }





            $data_aaDATA[] =  $row;
        }
        return $data_aaDATA;
    }
}
