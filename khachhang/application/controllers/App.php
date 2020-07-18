<?php defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Private_Controller
{

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
		date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    public function get_province()
    {


        $this->db->select('province_id as code,province as name')->distinct();
        $this->db->from('tbladdress_list');
        $purchases = $this->db->get()->result();

        return $purchases;
    }


    public function get_commune_by_hd($code)
    {

        $this->db->select('commune as code,commune as name')->distinct();
        $this->db->from('tbladdress_list');
        $this->db->where('district_id', $code);

        $purchases = $this->db->get()->result();

        echo json_encode($purchases);


    }

    public function check_soc($soc = '')
    {
        $this->db->select('*');
        $this->db->where('soc', $soc);
        $search_result = $this->db->get('tbl_create_order')->result();
        echo json_encode($search_result);
    }

    public function search_region()
    {
        $this->db->select('*');
        $this->db->where('city', $_GET['province']);
        $this->db->where('district', $_GET['district']);
        $search_result = $this->db->get('tblregion_excel')->row();


        if ($search_result) {
            $this->db->select('*');
            $this->db->where('id', $search_result->region_id);
            $region = $this->db->get('tbldeclared_region')->row();


            $this->db->select('*');
            $this->db->where('id_policy', $_GET['policy_id']);
            $this->db->where('id_region', $region->id);
            $data_region = $this->db->get('tbldata_region')->row();

            $region->data_region = $data_region;

            echo json_encode($region);
        } else {
            $error = array('error' => true);
            echo json_encode($error);
        }


    }

    public function get_district_by_hd($code)
    {

        $this->db->select('district_id as code,district as name')->distinct();
        $this->db->from('tbladdress_list');
        $this->db->where('province_id', $code);

        $purchases = $this->db->get()->result();

        echo json_encode($purchases);

    }


    /**
     * Default
     */
    function index()
    {
        // setup page header data
        $this->set_title('Công Nợ Khách Hàng');

        $data = $this->includes;

        $content_data['date_end'] = date('d-m-Y');
        $date = new DateTime($content_data['date_end']);
        if ($this->isAppMobile) {
            date_sub($date, date_interval_create_from_date_string('15 days'));
        } else {
            date_sub($date, date_interval_create_from_date_string('30 days'));
        }


        $content_data['date_start'] = date_format($date, 'd-m-Y');
        $content_data['province'] = $this->get_province();

        $content_data['isAppMobile'] = $this->isAppMobile;

        $data['content'] = $this->load->view('app', $content_data, TRUE);



        $this->load->model('Order_model');
        $order_model = new Order_model();
        $data['list_status'] = json_encode($order_model->getStatus());
        //get date
        $now = date('Y-m-d');
        $date = new DateTime($now);
        $days = 7;
        date_sub($date, date_interval_create_from_date_string($days . ' days'));
        $date_from = date_format($date, 'Y-m-d');
        $date_to = date('Y-m-d');
        $data['date_from'] =$date_from;
        $data['date_to'] = $date_to;
        $data['city'] = json_encode($order_model->getCity());
        $data['regions'] = json_encode($order_model->getRegion());
        $domainTracking = $this->getDomainTracking();
        $data['domain_tracking'] = $domainTracking;
        $this->load->view($this->template, $data);
    }
    public function ghtk_tracking(){
        $code = htmlspecialchars($this->input->get('code'));
        $dvvc = htmlspecialchars($this->input->get('dvvc'));
        $domainTracking = $this->getDomainTracking();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "$domainTracking/khachhang/api/tracking?code=$code&dvvc=$dvvc",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Authorization: Bearer " . $_POST['token'],
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
            echo json_encode(json_decode($response));
            die();
        }
    }
    public function getDomainTracking(){
        $this->db->select('domain_webhook');
        $this->db->from('tbl_default_mass_volume_ghtk');
        $this->db->where('id', 1);
        $domain_webhook = $this->db->get()->row()->domain_webhook;
        return $domain_webhook;
    }

    public function get_calc_debts_customer($data)
    {
        $dataReturn = 0;
        for ($i = sizeof($data) - 1; $i >= 0; $i--) {
            $dataReturn = $dataReturn + ((int)$data[$i]['ps_in'] - (int)$data[$i]['ps_de']);
        }
        return $dataReturn;
    }

    public function get_calc_debts_customer_ps_in($data = '')
    {

        $dataReturn = 0;
        foreach ($data as $key => $value) {
            $dataReturn = $dataReturn + $value['ps_in'];
        }
        return $dataReturn;
    }

    public function get_calc_debts_customer_ps_de($data = '')
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
                    $data['data'][$key]['ps_de'] = (int)$data['data'][$key]['ps_de'] * (-1);
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
                if ((int)$data_aaDATA[0]['Old_data'][$key]['ps_in'] > 0) {
                    $data['Old_data'][$key]['ps_de'] = 0;
                } else {
                    $data['Old_data'][$key]['ps_in'] = 0;
                    $data['Old_data'][$key]['ps_de'] = (int)$data['Old_data'][$key]['ps_de'] * (-1);


                }

            }
        }

        return $data;
    }


    public function get_id_customer($shop)
    {
        $this->db->select('id');
        $this->db->where(array('customer_shop_code' => $shop));
        return $this->db->get('tblcustomers')->row()->id;
    }


    function object_to_array_customer($data, $aColumns)
    {

        $j = 0;
        $data_aaDATA = [];
        foreach ($data as $aRow) {
            $row = array();
            $j++;


            $aRow = json_decode(json_encode($aRow), True);

            for ($i = 0; $i < count($aColumns); $i++) {
                $_data = $aRow[$aColumns[$i]];
                $row[] = $_data;
            }

            $icon_get_data = "<a style='padding: 3px;' class='btn btn-default btn-icon get_data_debits' href='javascript:;' data-debits='" . $aRow['name'] . "' data-id='" . $aRow['id'] . "'" . ">" . "
        <i class='fa fa-eye' ></i>
      </a>";

            $row[] = $icon_get_data . '</div>';

            $row[2] = number_format($row[2]);
            $row[3] = number_format($row[3]);
            $row[4] = number_format($row[4]);
            $row[5] = number_format($row[5]);

            $data_aaDATA[] = $row;
        }
        return $data_aaDATA;
    }


    function object_to_array_customer_detail($data, $aColumns)
    {

        $j = 0;
        $data_aaDATA = [];
        foreach ($data as $aRow) {
            $row = array();
            $j++;


            $aRow = json_decode(json_encode($aRow), True);

            for ($i = 0; $i < count($aColumns); $i++) {
                $_data = $aRow[$aColumns[$i]];
                $row[] = $_data;
            }

            $icon_get_data = "<a style='padding: 3px;' class='btn btn-default btn-icon get_data_debits' href='javascript:;' data-debits='" . $aRow['name'] . "' data-id='" . $aRow['id'] . "'" . ">" . "
        <i class='fa fa-eye' ></i>
      </a>";

            $row[] = $icon_get_data . '</div>';


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

            $row6 = number_format((float)$row[6]);
            if ($row[1] == '') {
                $row[6] = 0;
            } else {
                $row[6] = (float)$row[6] - (float)$row[7];
            }


            if ((int)$row[6] >= 0) {

                $row[6] = "+" . number_format($row[6]);

            } else {
                $row[6] = number_format($row[6]);
            }
            if ($row[1] == '1970') {
                $row[6] = '';
            }

            $row[7] = number_format((float)$row[7]);


            $row[8] = number_format((float)$row[8]);
            if($row[15] != ""){
                $row[15]=' - Đt: '.$row[15];
            }

            if ($row[3] == "Đơn Hàng") {
                $row[3] = "ĐH đã tính công nợ";
                $row[9] = 'Thu hộ:' . $row6 . ', Phí:' . $row[7] .
                    ' ( KL:' . $row[10] . ', ' . $row[11] .$row[15]. ' - ' . $row[12] . ' - ' . $row[13] . ' )';
            }

            if ($row[3] == "ĐH chưa đối soát") {

                $row[9] = 'Thu hộ:' . $row6 . ', Phí:' . $row[7] .
                    ' ( KL:' . $row[10] . ', ' . $row[11] .$row[15]. ' - ' . $row[12] . ' - ' . $row[13] . ' )';

                $row[3] = "ĐH chưa tính công nợ";
            }


            $data_aaDATA[] = $row;
        }
        return $data_aaDATA;
    }


    function object_to_array_customer_detail_mobile($data, $aColumns)
    {

        $j = 0;
        $data_aaDATA = [];
        foreach ($data as $aRow) {
            $row = array();
            $j++;


            $aRow = json_decode(json_encode($aRow), True);

            for ($i = 0; $i < count($aColumns); $i++) {
                $_data = $aRow[$aColumns[$i]];
                $row[] = $_data;
            }

            $icon_get_data = "";

            if ($row[1]) {
                $row[1] = date("d/m/Y", strtotime($row[1]));
            }
            if ($row[2]) {
                $row[2] = date("d/m/Y", strtotime($row[2]));
            }
            $row[6] = number_format((float)$row[6]);
            $row[7] = number_format((float)$row[7]);
            $row[8] = number_format((float)$row[8]);

            $row[16] = $row[3];
            if ($row[3] == "Đơn Hàng") {
                $row[3] = "ĐH đã tính công nợ";
                $row[9] = $row[11] . ' - ' . $row[12] . ' - ' . $row[13];

                $row[17] = 'KL:' . $row[10] . ', $Thu hộ:' . $row[6] .
                    ' $Phí:' . $row[7];

            }

            if ($row[3] == "ĐH chưa đối soát") {

                $row[9] = $row[11] . ' - ' . $row[12] . ' - ' . $row[13];


                $row[3] = "ĐH chưa tính công nợ";
                $row[17] = 'KL:' . $row[10] . ', $Thu hộ:' . $row[6] .
                    ' $Phí:' . $row[7];
            }

            $row[] = $icon_get_data . '</div>';

            $data_aaDATA[] = $row;
        }
        return $data_aaDATA;
    }


    function object_to_array_customer_detail_mobile_tab2($data, $aColumns)
    {

        $j = 0;
        $data_aaDATA = [];
        foreach ($data as $aRow) {
            $row = array();
            $j++;

            $aRow = json_decode(json_encode($aRow), True);

            for ($i = 0; $i < count($aColumns); $i++) {
                $_data = $aRow[$aColumns[$i]];
                $row[] = $_data;
            }
            $icon_get_data = "<a style='padding: 3px;' class='btn btn-default btn-icon get_data_debits' href='javascript:;' data-debits='" . $aRow['name'] . "' data-id='" . $aRow['id'] . "'" . ">" . "
        <i class='fa fa-eye' ></i>
      </a>";

            $row[] = $icon_get_data . '</div>';
            if ($row[1]) {
                $row[1] = date("d/m/Y", strtotime($row[1]));
            }
            if ($row[2]) {
                $row[2] = date("d/m/Y", strtotime($row[2]));
            }
            $row[6] = number_format((float)$row[6]);
            $row[7] = number_format((float)$row[7]);
            $row[8] = number_format((float)$row[8]);

//            $row[16] = $row[3];
            if ($row[3] == "Đơn Hàng") {
                $row[3] = "ĐH đã tính công nợ";
                $row[9] = $row[11] . ' - ' . $row[15] . ' - ' . $row[12] . ' - ' . $row[13];


                $row[17] = 'KL:' . $row[10] . ', $Thu hộ:' . $row[6] .
                    ' $Phí:' . $row[7];

            }

            if ($row[3] == "ĐH chưa đối soát") {

                $row[9] = $row[11] . ' - ' . $row[15] . ' - ' . $row[12] . ' - ' . $row[13];


                $row[3] = "ĐH chưa tính công nợ";
                $row[17] = 'KL:' . $row[10] . ', $Thu hộ:' . $row[6] .
                    ' $Phí:' . $row[7];
            }

                              $row[]=$aRow['note'];
                              $row[]=$aRow['required_code'];

            $data_aaDATA[] = $row;
        }
        return $data_aaDATA;
    }

    // public function detail_debts_customer_calc($start_date , $start_end , $shop_name , $id_customer) {
    //
    //   if ($start_date !== NULL) {
    //     $sql = "
    //
    //     (SELECT id , date_debits AS date_create , date_create as created , status_debts , code_supership AS code_display , status , collect AS ps_in , hd_fee AS ps_de , note , mass , receiver , city , district FROM tblorders_shop WHERE shop = ? AND date_debits BETWEEN ? AND ? AND status != 'Huỷ')
    //
    //     UNION ALL
    //
    //     (SELECT id , date AS date_create , date_create as created , type AS status_debts , code AS code_display , status , price AS ps_in , price AS ps_de , note , mass , receiver , city , district FROM tblcash_book WHERE id_object = ? AND staff_id = ? AND date BETWEEN ? AND ?)
    //
    //     UNION ALL
    //
    //     (SELECT id , date AS date_create , date_create as created , status_debts , code AS code_display , status , price AS ps_in , price AS ps_de , note , mass , receiver , city , district FROM tbldebit_object WHERE id_object = ? AND staff_id = ? AND date BETWEEN ? AND ?)
    //     ORDER BY date_create DESC
    //     "
    //     ;
    //
    //
    //     $paramSQL = array($shop_name , $start_date , $start_end , 'tblcustomers' , $id_customer ,  $start_date , $start_end , 'tblcustomers' , $id_customer  ,  $start_date , $start_end);
    //   }else {
    //     $sql = "
    //
    //     (SELECT id , date_debits AS date_create , date_create as created , status_debts , code_supership AS code_display , status , collect AS ps_in , hd_fee AS ps_de , note , mass , receiver , city , district FROM tblorders_shop WHERE shop = ? AND 	date_debits < ? AND status != 'Huỷ')
    //     UNION ALL
    //
    //     (SELECT id , date AS date_create , date_create as created , type AS status_debts , code AS code_display , status , price AS ps_in , price AS ps_de , note , mass , receiver , city , district FROM tblcash_book WHERE id_object = ? AND staff_id = ? AND date < ?)
    //
    //     UNION ALL
    //
    //     (SELECT id , date AS date_create , date_create as created , status_debts , code AS code_display , status , price AS ps_in , price AS ps_de , note , mass , receiver , city , district FROM tbldebit_object WHERE id_object = ? AND staff_id = ? AND date < ?)
    //     ORDER BY date_create DESC
    //     "
    //     ;
    //
    //
    //     $paramSQL = array($shop_name , $start_end , 'tblcustomers' , $id_customer ,   $start_end , 'tblcustomers' , $id_customer  ,  $start_end);
    //   }
    //   // $this->db->query($sql, $paramSQL)->result();
    //   // echo "<pre>";
    //   //
    //   // var_dump($this->db->last_query());
    //   // die();
    //   // $this->db->query($sql, $paramSQL);
    //   // var_dump($this->db->last_query());
    //   // die();
    //   return json_decode(json_encode($this->db->query($sql, $paramSQL)->result()), true);
    //
    // }


    public function detail_debts_customer_calc($start_date, $start_end, $shop_name, $id_customer)
    {

        if ($start_date !== NULL) {
            $sql = "

              (SELECT id , date_debits AS date_create , date_create as created , status_debts , code_supership AS code_display , status , collect AS ps_in , hd_fee AS ps_de , note , mass , receiver , city , district FROM tblorders_shop WHERE shop = ? AND date_debits BETWEEN ? AND ? AND status != 'Huỷ')
        
              UNION ALL
        
        
        
              (SELECT id , date_control AS date_create , date as created , type AS status_debts , code AS code_display , status , price AS ps_in , price AS ps_de , note , mass , receiver , city , district FROM tblcash_book WHERE groups = 5 AND id_object = ? AND staff_id = ? AND date BETWEEN ? AND ?)
        
        
              UNION ALL
        
              (SELECT id , date AS date_create , date_create as created , status_debts , code AS code_display , status , price AS ps_in , price AS ps_de , note , mass , receiver , city , district FROM tbldebit_object WHERE id_object = ? AND staff_id = ? AND date BETWEEN ? AND ?)
              ORDER BY date_create DESC
              ";


            $paramSQL = array($shop_name, $start_date, $start_end, 'tblcustomers', $id_customer, $start_date, $start_end, 'tblcustomers', $id_customer, $start_date, $start_end);
        } else {
            $sql = "
        
              (SELECT id , date_debits AS date_create , date_create as created , status_debts , code_supership AS code_display , status , collect AS ps_in , hd_fee AS ps_de , note , mass , receiver , city , district FROM tblorders_shop WHERE shop = ? AND 	date_debits < ? AND status != 'Huỷ')
              UNION ALL
        
        
        
              (SELECT id , date_control AS date_create , date as created , type AS status_debts , code AS code_display , status , price AS ps_in , price AS ps_de , note , mass , receiver , city , district FROM tblcash_book WHERE  groups = 5 AND id_object = ? AND staff_id = ? AND date < ?)
        
              UNION ALL
        
              (SELECT id , date AS date_create , date_create as created , status_debts , code AS code_display , status , price AS ps_in , price AS ps_de , note , mass , receiver , city , district FROM tbldebit_object WHERE id_object = ? AND staff_id = ? AND date < ?)
              ORDER BY date_create DESC
              ";


            $paramSQL = array($shop_name, $start_end, 'tblcustomers', $id_customer, $start_end, 'tblcustomers', $id_customer, $start_end);
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
            array_slice($data, 7, count($data) - 1, true);
    }

    public function to_sql_date($date)
    {
        return date("Y-m-d", strtotime($date));
    }

    public function detail_debts_customer($start_date, $start_end)
    {

        $start_date = $start_date;
        $start_end = $start_end;
        $date_1970 = $start_date;
        if ($start_date == "") {
            $start_date = NULL;
        }
        if ($start_end == "") {
            $start_end = NULL;
        }
        if (isset($start_end) && isset($start_date)) {
            $start_date = $this->to_sql_date($start_date);
            $start_end = $this->to_sql_date($start_end);
        } else if ((!isset($start_date)) && isset($start_end)) {
            $start_end = $this->to_sql_date($start_end);
            $date = new DateTime($start_end);

            if ($this->isAppMobile) {
                date_sub($date, date_interval_create_from_date_string('15 days'));
            } else {
                date_sub($date, date_interval_create_from_date_string('30 days'));
            }


            $start_date = date_format($date, 'Y-m-d');
        } else if (isset($start_date) && !isset($start_end)) {
            $start_date = $this->to_sql_date($start_date);
            $date = new DateTime($start_date);


            if ($this->isAppMobile) {
                $start_end = date("Y-m-d", strtotime("$date +15 day"));
            } else {
                $start_end = date("Y-m-d", strtotime("$date +30 day"));
            }
        } else if (!isset($start_date) && !isset($start_end)) {
            $start_end = date('Y-m-d');
            $date = new DateTime($start_end);;


            if ($this->isAppMobile) {
                date_sub($date, date_interval_create_from_date_string('15 days'));
            } else {
                date_sub($date, date_interval_create_from_date_string('30 days'));
            }
            $start_date = date_format($date, 'Y-m-d');
        }
        if (!empty($start_date)) {
            $date = new DateTime($start_date);;
            date_sub($date, date_interval_create_from_date_string('1 days'));
            $startdauky = date_format($date, 'Y-m-d') . ' ' . date('23:59:59');
        }

        $start_date = $start_date . ' 00:00:00';
        $start_end = $start_end . ' ' . date('23:59:59');


        if ($start_date == "") {
            $start_date = NULL;
        }
        if ($start_end == "") {
            $start_end = NULL;
        }

        $id_customer = $this->input->post('id_customer');


        $customerLoad = $this->db->get_where('tblcustomers', array('id' => $id_customer))->result();



        foreach ($customerLoad as $key => $value) {

            $dataPush['Old_data'] = $this->detail_debts_customer_calc(NULL, $start_date, $value->customer_shop_code, $value->id);

            for ($i = 0; $i < sizeof($dataPush['Old_data']); $i++) {

                if ($dataPush['Old_data'][$i]['status'] == "Đã Đối Soát Trả Hàng") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }

                if ($dataPush['Old_data'][$i]['status'] == "Xác Nhận Hoàn") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }
                if ($dataPush['Old_data'][$i]['status'] == "Không Giao Được") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }
                if ($dataPush['Old_data'][$i]['status'] == "Xác Nhận Hoàn") {
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
                if ($dataPush['Old_data'][$i]['status'] == "Hoãn Trả Hàng") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }
                if ($dataPush['Old_data'][$i]['status'] == "Đã Trả Hàng") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }

                if ($dataPush['Old_data'][$i]['status'] == "Đã Trả Hàng Toàn Bộ") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }
                if ($dataPush['Old_data'][$i]['status'] == "Đã Chuyển Kho Trả Toàn Bộ") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }
                if ($dataPush['Old_data'][$i]['status'] == "Đang Trả Hàng Toàn Bộ") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }

            }


            $dataPush['data'] = $this->detail_debts_customer_calc($start_date, $start_end, $value->customer_shop_code, $value->id);

            for ($i = 0; $i < sizeof($dataPush['data']); $i++) {

                if ($dataPush['data'][$i]['status'] == "Đã Đối Soát Trả Hàng") {
                    $dataPush['data'][$i]['ps_in'] = 0;
                }


                if ($dataPush['data'][$i]['status'] == "Xác Nhận Hoàn") {

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
                if ($dataPush['data'][$i]['status'] == "Hoãn Trả Hàng") {
                    $dataPush['data'][$i]['ps_in'] = 0;
                }
                if ($dataPush['data'][$i]['status'] == "Đã Trả Hàng") {
                    $dataPush['data'][$i]['ps_in'] = 0;
                }


                if ($dataPush['data'][$i]['status'] == "Đã Trả Hàng Toàn Bộ") {
                    $dataPush['data'][$i]['ps_in'] = 0;
                }
                if ($dataPush['data'][$i]['status'] == "Đã Chuyển Kho Trả Toàn Bộ") {
                    $dataPush['data'][$i]['ps_in'] = 0;
                }
                if ($dataPush['data'][$i]['status'] == "Đang Trả Hàng Toàn Bộ") {
                    $dataPush['data'][$i]['ps_in'] = 0;
                }
//                if ($dataPush['data'][$i]['status'] == "Đã Đối Soát Giao Hàng") {
//                    $dataPush['data'][$i]['ps_in'] = 0;
//                }


            }


            $sql_order_date_null = "SELECT id , date_debits as date_create , date_create as created , status_debts , code_supership AS code_display , status , collect AS ps_in , hd_fee_stam AS ps_de , note , mass , receiver , city , district,required_code,phone FROM tblorders_shop WHERE shop = ? AND date_debits IS NULL AND status != 'Huỷ' ORDER BY date_create DESC";


            $paramSQL = array($value->customer_shop_code);

            $dataPush['top_data'] = $this->db->query($sql_order_date_null, $paramSQL)->result();

            for ($i = 0; $i < sizeof($dataPush['top_data']); $i++) {
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
                    $data_aaDATA[0]['data'][$key]['ps_de'] = (int)$data_aaDATA[0]['data'][$key]['ps_de'] * (-1);

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
                    $data_aaDATA[0]['Old_data'][$key]['ps_de'] = (int)$data_aaDATA[0]['Old_data'][$key]['ps_de'] * (-1);

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
        //
        for ($i = sizeof($dataReturn) - 1; $i > 0; $i--) {

            if ($dataReturn[$i - 1]['date_create'] != NULL) {
                $dataReturn[$i - 1]['sps_con_no'] = $dataReturn[$i]['sps_con_no'] + ($dataReturn[$i - 1]['ps_in'] - $dataReturn[$i - 1]['ps_de']);
            } else {
                $dataReturn[$i - 1]['sps_con_no'] = $dataReturn[$i]['sps_con_no'];
            }


        }


        $aColumns = array(
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
            'phone',
            'required_code'
        );
        $this->load->model('Order_model');
        $order_model = new Order_model();

        if ($this->isAppMobile == true) {
            $date_return=$this->object_to_array_customer_detail_mobile($dataReturn, $aColumns);

            foreach ($date_return as $key => $formatValue){
                if($formatValue[16]=='Đơn Hàng'){
                    $dataFormatNew = $order_model->getOrderShop($formatValue[0]);
                    if(count($dataFormatNew)>0){
                        $explode = explode("-",$formatValue[9]);
                        $date_return[$key][9] = $explode[0].' - '.$dataFormatNew[0]['phone'].' - '.$explode[1].' - '.$explode[2];
                        $date_return[$key][20]=$dataFormatNew[0]['required_code'];
                    }
                }

            }
            $dataTableInit = [
                "aaData" =>$date_return,
                "draw" => $_POST['draw'],
                "iTotalDisplayRecords" => sizeof($dataReturn),
                "iTotalRecords" => sizeof($dataReturn)
            ];
        } else {
            $data = $this->object_to_array_customer_detail($dataReturn, $aColumns);
            $date_return = array();
            foreach ($data as $item) {
                if ($item[5] == 2) {
                    $item[5] = 'Phiếu Điều Chỉnh Công Nợ';
                }

                if (!is_null($item[1])) {
                    array_push($date_return, $item);
                }
            }

            foreach ($date_return as $key => $formatValue){
                $dataFormatNew = $order_model->getOrderShop($formatValue[0]);
                if(count($dataFormatNew)>0){
                    $explode = explode("-",$formatValue[9]);
                    $date_return[$key][3]=$dataFormatNew[0]['required_code'];
                    if($formatValue[10]!='' && $formatValue[10]!='null' && $formatValue[10]!=null){
                        $date_return[$key][9] = $explode[0].' - '.$dataFormatNew[0]['phone'].' - '.$explode[1].' - '.$explode[2];
                     }
                    }
            }
            $dataTableInit = [
                "aaData" => $date_return,
                "draw" => $_POST['draw'],
                "iTotalDisplayRecords" => sizeof($date_return),
                "iTotalRecords" => sizeof($date_return)
            ];
        }


        echo json_encode($dataTableInit);
    }


    //báo cáo công nợ Khach Hang ------------------------------------------------------
    public function debts_porters_customer($start_date, $start_end, $customer_shop_code)
    {
        $start_date = $start_date;
        $start_end = $start_end;


        if ($start_date == "") {
            $start_date = NULL;
        }
        if ($start_end == "") {
            $start_end = NULL;
        }
        if (isset($start_end) && isset($start_date)) {
            $start_date = $this->to_sql_date($start_date);
            $start_end = $this->to_sql_date($start_end);


        } else if ((!isset($start_date)) && isset($start_end)) {
            $start_end = $this->to_sql_date($start_end);
            $date = new DateTime($start_end);
            date_sub($date, date_interval_create_from_date_string('30 days'));
            $start_date = date_format($date, 'Y-m-d');
        } else if (isset($start_date) && !isset($start_end)) {
            $start_date = $this->to_sql_date($start_date);
            $date = new DateTime($start_date);
            $start_end = date("Y-m-d", strtotime("$date +30 day"));
        } else if (!isset($start_date) && !isset($start_end)) {
            $start_end = date('Y-m-d');
            $date = new DateTime($start_end);;
            date_sub($date, date_interval_create_from_date_string('30 days'));
            $start_date = date_format($date, 'Y-m-d');
        }
        if (!empty($start_date)) {
            $date = new DateTime($start_date);;
            date_sub($date, date_interval_create_from_date_string('1 days'));
            $startdauky = date_format($date, 'Y-m-d') . ' ' . date('23:59:59');
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


        $customer = $this->db->get_where('tblcustomers', array('id' => $id_customer))->result();


        $filter_yes = false;

        if ($id_customer == "") {
            $customerLoad = $customer;
            $filter_yes = true;
        } else {
            foreach ($customer as $key => $value) {
                $value = json_decode(json_encode($value), true);

                if ($id_customer == $value['id']) {
                    $customerLoad[] = $value;
                }
            }
        }


        $data_aaDATA;
        foreach ($customerLoad as $key => $value) {

            $sql_order_date_null = "SELECT id , date_debits , date_create as created , status_debts , code_supership AS code_display , status , collect AS ps_in , hd_fee AS ps_de , note , mass , receiver , city , district FROM tblorders_shop WHERE shop = ? AND date_debits IS NULL AND status != 'Huỷ' ORDER BY date_create DESC";


            $paramSQL = array($value['customer_shop_code']);

            $dataPush['top_data'] = $this->db->query($sql_order_date_null, $paramSQL)->result();

            $dataPush['Old_data'] = $this->detail_debts_customer_calc(NULL, $start_date, $value['customer_shop_code'], $value['id']);

            for ($i = 0; $i < sizeof($dataPush['Old_data']); $i++) {

                if ($dataPush['Old_data'][$i]['status'] == "Đã Đối Soát Trả Hàng") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }

                if ($dataPush['Old_data'][$i]['status'] == "Xác Nhận Hoàn") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }
                if ($dataPush['Old_data'][$i]['status'] == "Không Giao Được") {
                    $dataPush['Old_data'][$i]['ps_in'] = 0;
                }
                if ($dataPush['Old_data'][$i]['status'] == "Xác Nhận Hoàn") {
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

            $dataPush['data'] = $this->detail_debts_customer_calc($start_date, $start_end, $value['customer_shop_code'], $value['id']);

            for ($i = 0; $i < sizeof($dataPush['data']); $i++) {

                if ($dataPush['data'][$i]['status'] == "Đã Đối Soát Trả Hàng") {
                    $dataPush['data'][$i]['ps_in'] = 0;
                }


                if ($dataPush['data'][$i]['status'] == "Xác Nhận Hoàn") {

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

            $dataPush['customer_shop_code'] = $value['customer_shop_code'];
            $dataPush['id'] = $value['id'];
            $data_aaDATA[] = $dataPush;
        }


        foreach ($data_aaDATA as $key => $value) {
            $data_aaDATA[$key] = $this->formatDataCustomer($data_aaDATA[$key]);
        }

        $aColumns = array(
            'id',
            'name',
            'previous_debt',
            'ps_in',
            'ps_de',
            'next_debt',
        );
        $dataTable = [];

        foreach ($data_aaDATA as $value) {

            $dataTableValue['id'] = $value['id'];
            $dataTableValue['name'] = $value['customer_shop_code'];
            $dataTableValue['previous_debt'] = $this->get_calc_debts_customer($value['Old_data']);
            $dataTableValue['ps_in'] = $this->get_calc_debts_customer_ps_in($value['data']);
            $dataTableValue['ps_de'] = $this->get_calc_debts_customer_ps_de($value['data']);
            $dataTableValue['next_debt'] = (int)$this->get_calc_debts_customer($value['data']) + (int)$this->get_calc_debts_customer($value['Old_data']);
            $dataTable[] = $dataTableValue;
        }

        if ($filter_yes) {

            if ($id_rows_customer === "1") {

                foreach ($dataTable as $key => $value) {
                    if ((int)$value['next_debt'] < 0 || (int)$value['next_debt'] === 0) {
                        unset($dataTable[$key]);
                    }
                }
            } else if ($id_rows_customer === "2") {
                foreach ($dataTable as $key => $value) {
                    if ((int)$value['next_debt'] > 0 || (int)$value['next_debt'] === 0) {
                        unset($dataTable[$key]);
                    }
                }
            }
        }


        usort($dataTable, function ($item1, $item2) {
            if ($item1['next_debt'] == $item2['next_debt']) return 0;
            return $item1['next_debt'] > $item2['next_debt'] ? -1 : 1;
        });


        $data_render = $this->object_to_array_customer($dataTable, $aColumns);
        $dataTableInit = [
            "aaData" => $data_render,
            "draw" => $_POST['draw'],
            "iTotalDisplayRecords" => sizeof($dataTable),
            "iTotalRecords" => sizeof($dataTable)
        ];

        echo json_encode($dataTableInit);

    }

    public function datatables_ajax()
    {

        $this->debts_porters_customer($_POST['date_start_customer'], $_POST['date_end_customer'], $_POST['customer_shop_code']);
    }


    public function datatables_ajax_detail()
    {
        $this->detail_debts_customer($_POST['date_start_customer'], $_POST['date_end_customer'], $_POST['customer_shop_code']);
    }


    public function datatables_ajax_detail_tab2()
    {
        $date_start_customer_order = htmlspecialchars($this->input->post('date_start_customer'));
        $date_end_customer_order = htmlspecialchars($this->input->post('date_end_customer'));
        $limit = htmlspecialchars($this->input->post('limit'));
        $customer_shop_code = htmlspecialchars($this->input->post('customer_shop_code'));
        if ($limit < 20 && $limit != 'all')
            $limit = 20;
        $province = htmlspecialchars($this->input->post('status'));

        $this->db->select('id , date_debits as date_create , date_create as created , status_debts , code_supership AS code_display ,status , collect AS ps_in , hd_fee_stam AS ps_de , note , mass , receiver , city , district , phone, DVVC,required_code');
        $this->db->from('tblorders_shop');
        $this->db->where('shop', $customer_shop_code);
        $this->db->where('date_debits IS NULL ', NULL);
        $this->db->where('date_create <=', date('Y-m-d 23:59:59', strtotime(str_replace('-', '-', $date_end_customer_order.' 23:59:59'))));
        $this->db->where('date_create >=', date('Y-m-d 00:00:00', strtotime(str_replace('-', '-', $date_start_customer_order.' 00:00:00'))));

        if ($province != 'null') {
            $this->db->where('status', $province);
        }
        $this->db->where('status !=', 'Hủy');
        $this->db->where('status !=', 'Huỷ');
        if ($limit != 'all')
            $this->db->limit($limit);
        $this->db->order_by('created', 'DESC');

        $dataPush['top_data'] = $this->db->get()->result();

        for ($i = 0; $i < sizeof($dataPush['top_data']); $i++) {
            $dataPush['top_data'][$i]->status_debts = 'ĐH chưa đối soát';
        }
        $data_aaDATA = $dataPush;

        foreach ($data_aaDATA['top_data'] as $key => $value) {
            $data_aaDATA['top_data'][$key]->collect = $value->ps_in;
            // $data_aaDATA[0]['top_data'][$key]->ps_de = 0;
            // $data_aaDATA[0]['top_data'][$key]->ps_in = 0;
        }


        $data_aaDATA['top_data'] = json_decode(json_encode($data_aaDATA['top_data']), true);


        $dataReturn = array_merge($data_aaDATA['top_data']);

        //cập nhật sps còn nợ
        //
        for ($i = sizeof($dataReturn) - 1; $i > 0; $i--) {

            if ($dataReturn[$i - 1]['date_create'] != NULL) {
                $dataReturn[$i - 1]['sps_con_no'] = $dataReturn[$i]['sps_con_no'] + ($dataReturn[$i - 1]['ps_in'] - $dataReturn[$i - 1]['ps_de']);
            } else {
                $dataReturn[$i - 1]['sps_con_no'] = $dataReturn[$i]['sps_con_no'];
            }


        }

        $aColumns = array(
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
            'phone',
            'DVVC',
            'required_code'
        );

        $dataTableInit = [
            "aaData" => $this->object_to_array_customer_detail_mobile_tab2($dataReturn, $aColumns),
            "draw" => $_POST['draw'],
            "iTotalDisplayRecords" => sizeof($dataReturn),
            "iTotalRecords" => sizeof($dataReturn)
        ];

        echo json_encode($dataTableInit);


    }

    public function filter_list()
    {
        $listStatus = $this->input->get('province');
        $codeOrder = htmlspecialchars($this->input->get('code_order'));

        $date_start_customer_order = htmlspecialchars($this->input->get('date_start_customer_order'));
        $date_end_customer_order = htmlspecialchars($this->input->get('date_end_customer_order'));
        $limit_geted = htmlspecialchars($this->input->get('limit_geted'));
        if ($limit_geted < 20 && $limit_geted != 'all')
            $limit_geted = 20;
        $logged_in = json_decode(json_encode(json_decode($this->input->cookie('logged_in'))), true);
        $customer =$logged_in['customer_shop_code'];
        $dateFrom =date('Y-m-d', strtotime(str_replace('-', '-', $date_start_customer_order)));
        $dateEnd =date('Y-m-d', strtotime(str_replace('-', '-', $date_end_customer_order)));
        $sql = "
        SELECT shop.*  FROM `tblorders_shop` as shop
        WHERE shop.`shop` LIKE '%$customer%'";
//WHERE id IN (33, 34, 45)
        ($codeOrder !="")?$sql.="AND shop.phone LIKE '%$codeOrder%'":"";
        ($codeOrder !="")?$sql.="OR shop.required_code LIKE '%$codeOrder%'":"";
        ($codeOrder !="")?$sql.="OR shop.code_orders LIKE '%$codeOrder%'":"";
        ($codeOrder !="")?$sql.="OR shop.code_supership LIKE '%$codeOrder%'":"";
        $strStatus = "";
        if($listStatus) {
            foreach ($listStatus as $key => $status) {
                $strStatus .= "'$status'";
                if ($key + 1 < count($listStatus)) {
                    $strStatus .= ",";
                }
            }
            ($strStatus !="")?$sql.="AND shop.status IN ($strStatus)":"";

        }
        ($date_start_customer_order !="")?$sql.="AND shop.date_create BETWEEN '$dateFrom 00:00:00' AND '$dateEnd 23:59:59'":"";

        $sql.="AND shop.`status` <> 'Hủy'";
        $sql.="AND shop.`status` <> 'Huỷ'";

        $sql.=" ORDER BY shop.date_create DESC";
        if ($limit_geted != 'all'){
            $sql.=" LIMIT $limit_geted";
        }
        $list = $this->db->query($sql)->result();

        echo json_encode(array('aaData' => $list, 'status' => true, 'error' => ''));
    }

    //Tab 3


    public function pick_up_mobile($value = '')
    {

        $this->db->select('created , repo_customer , note , status , id , tblstaff.firstname as firstname,tblstaff.lastname as lastname , number_order_get');
        $this->db->where('customer_id', $_GET['customer_id']);
        $this->db->join('tblstaff', 'tblstaff.staffid = tblpickuppoint.user_geted', 'left');
        $this->db->from('tblpickuppoint');

        if ($_GET['limit'] !== 'all') {
            $this->db->limit($_GET['limit']);
        }

        $this->db->order_by('status', 'ASC');
        $this->db->order_by('created', 'DESC');
        $data = $this->db->get()->result();

        // var_dump($this->db->last_query());
        echo json_encode($data);
    }

    public function datatables_ajax_pick_up()
    {
        if ($this->input->is_ajax_request()) {

            $this->load->model('DataTable/M_dataTable_pickup');

            $datatables = $_POST;
            $datatables['table'] = 'tblpickuppoint';
            $datatables['id-table'] = 'id';


            $this->M_dataTable_pickup->Datatables($datatables);
        }
        return;
    }

    public function datatables_ajax_picked($value = '')
    {
        if ($this->input->is_ajax_request()) {

            $this->load->model('DataTable/M_dataTable_pickup');

            $datatables = $_POST;
            $datatables['table'] = 'tblpickuppoint';
            $datatables['id-table'] = 'id';


            $this->M_dataTable_pickup->DatatablesPicked($datatables);
        }
        return;
    }

    public function pick_up_points_delete($id)
    {

        $tables = array('tblpickuppoint');
        $this->db->where('id', $id);
        $delete = $this->db->delete($tables);

        if ($this->db->affected_rows()) {
            echo json_encode(array('status' => true));
        }
        die();

    }

    public function getDataEdit($id)
    {
        $data = $this->db->get_where('tblpickuppoint', array('id' => $id))->result()[0];
        echo json_encode($data);
    }

    public function getCustomer($id)
    {
        $cus = $this->db->get_where('tblcustomers', array('id' => $id))->result()[0];
        echo json_encode($cus);
    }


    public function editPickup()
    {

        $this->db->where('id', $_POST['id']);
        $update = $this->db->update('tblpickuppoint', $_POST);

        if ($update) {
            echo json_encode(array('status' => true));
        } else {
            echo json_encode(array('status' => false));
        }


    }


    public function add_pickup()
    {
        $date = new DateTime();
        $date->format('Y-m-d H:i:s');
        $_POST['modified'] = $date->format('Y-m-d H:i:s');
        $_POST['created'] = $date->format('Y-m-d H:i:s');


        $checkCustomer = $this->db->get_where('tblpickuppoint', array('customer_id' => $_POST['customer_id'], 'from_customer' => "1", 'status !=' => '1'))->result();

        if (sizeof($checkCustomer) == 0) {

            $id = $this->db->insert('tblpickuppoint', $_POST);
            if ($id) {
                echo json_encode(array('status' => true));
            } else {
                echo json_encode(array('status' => false));
            }
        } else {
            echo json_encode(array('status' => false, 'mess' => 'Đang có điểm lấy hàng do bạn tạo chưa lấy'));
        }


    }

    public function curlGetRepo()
    {

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
                "Authorization: Bearer " . $_POST['token'],
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


    //Tab4
    public function get_datatable_ajax_order($value = '')
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('DataTable/M_dataTable_order');
            //
            $datatables = $_POST;
            $datatables['table'] = 'tblpickuppoint';
            $datatables['id-table'] = 'id';
            $this->M_dataTable_order->Datatables($datatables);
        }
        return;
    }


    public function check_customer_policy_exits($id)
    {

        $this->db->select('*');
        $this->db->where('customer_id', $id);
        $search_result = $this->db->get('tblcustomer_policy')->result();

        if (sizeof($search_result) === 0) {

            echo 'custommer_no';
            die();
        } else {
            echo json_encode($search_result[0]);
            die();
        }
        //
    }


    public function api_create_order($data, $token)
    {


        unset($data['pickup_commune']);

        if ($data['value'] === "NaN") {
            $data['value'] = 0;
        }


        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'https://api.mysupership.vn/v1/partner/orders/add');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Authorization: Bearer ' . $token;
        $headers[] = 'Content-Type: application/json';
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);


        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return $err;
        } else {
            return $response;
        }
    }


    public function create_order($value = '')
    {
        $token = $_POST['token'];
        unset($_POST['token']);
        $code = 'YC' . '.' . code(9);
        $_POST['user_created'] = get_staff_user_id();
        $_POST['required_code'] = $code;
        $_POST['created'] = date('Y-m-d H:i:s');

		$_POST['transport'] = $_POST['service'] == 1 ? 'road':'fly';

        $this->db->insert('tbl_create_order', $_POST);
        $id = $this->db->insert_id();

        if ($id) {
            echo json_encode(array('success' => 'ok', 'code' => $code));
            die();

        } else {
            echo 'Luư dữ liệu không thành công';
            die();
        }

    }

    public function delete_order($id = '')
    {
        $query = $this->db->get_where('tbl_create_order', array('id' => $id))->result()[0];
        $code = $query->code;

        if (is_null($query->dvvc)) {
            $this->db->set('status_cancel', 1);
            $this->db->where('id', $id);
            $delete = $this->db->update('tbl_create_order');
            if ($delete)
                echo json_encode(array('success' => 'ok'));

        } else {
            if ($query->dvvc == 'SPS') {
                $customerToken = $this->db->get_where('tblcustomers', array('id' => $query->customer_id))->result()[0]->token_customer;
                $data = array('code' => $code);

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, 'https://api.mysupership.vn/v1/partner/orders/cancel');
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($curl, CURLOPT_POST, 1);
                $headers = array();
                $headers[] = 'Accept: application/json';
                $headers[] = 'Authorization: Bearer ' . $customerToken;
                $headers[] = 'Content-Type: application/json';
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                if ($err) {
                    echo json_encode(array('success' => 'no'));
                } else {
                    $this->db->set('status_cancel', 1);
                    $this->db->where('id', $id);
                    $delete = $this->db->update('tbl_create_order');

                    // Change status on table tblorders_shop
                    $data_order = array(
                        'status' => 'Hủy'
                    );
                    $this->db->where('id', $query->orders_shop_id);
                    $delete_order = $this->db->update('tbl_create_order', $data_order);
                    if ($delete && $delete_order)
                        echo json_encode(array('success' => 'ok'));
                }
            } elseif ($query->dvvc == 'GHTK') {
                // Get token
                $customerToken = $query->token_ghtk;

                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => 'https://services.giaohangtietkiem.vn/services/shipment/cancel/partner_id:' . $id,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_HTTPHEADER => [
                        'Token: ' . $customerToken,
                    ],
                ]);
                $response = curl_exec($curl);
                curl_close($curl);
                if (json_decode($response, true)['success'] == true && json_decode($response, true)['message'] == "") {
                    $this->db->set('status_cancel', 1);
                    $this->db->where('id', $id);
                    $delete = $this->db->update('tbl_create_order');

                    // Change status on table tblorders_shop
                    $data_order = array('status' => 'Hủy');
                    $this->db->where('id', $query->orders_shop_id);
                    $delete_order = $this->db->update('tbl_create_order', $data_order);
                    if ($delete && $delete_order)
                        echo json_encode(array('success' => 'ok'));


                } else {
                    echo json_encode(array('success' => 'no'));
                }
            }
        }


    }

    //TAB 5


    public function get_customer($id)
    {

        $this->db->select('*');
        $this->db->where('id', $id);
        $this->db->join('tblstaff', 'tblstaff.staffid = tblcustomers.customer_monitoring', 'left');
        $this->db->from('tblcustomers');

        $data = $this->db->get()->result();


        echo json_encode($data[0]);
    }

    public function change_pass()
    {

        $this->db->set('customer_password', $_POST['pass']);
        $this->db->where('id', $_POST['id']);
        $update = $this->db->update('tblcustomers');

        if ($update == true) {
            echo json_encode(array('status' => '1'));
        } else {
            echo json_encode(array('status' => '0'));
        }


    }

    public function export_exel_orders()
    {
        $user = json_decode(json_encode(json_decode($this->input->cookie('logged_in'))), true);
        if (!empty($user)) {
            $params = $this->input->get();
            $this->load->model('create_order_model');
            $user = $this->create_order_model->get_customer_by_id($user['id']);
            $data = $this->create_order_model->get_orders_by_time($user['id'], $params);
            if (empty($data)) {
                echo "Không có dữ liệu nào phù hợp";
                die();
            }
            $colums = array('A', 'B', 'C', 'D', 'E', 'F');
            include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
            $this->load->library('PHPExcel');
            $objPHPExcel = new PHPExcel();
            for ($i = 0; $i < 5; $i++) {
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
                'font' => array(
                    'bold' => true,
                    'color' => array('rgb' => '111112'),
                    'size' => 10,
                    'name' => 'Times New Roman'
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
                'font' => array(
                    'bold' => true,
                    'color' => array('rgb' => '111112'),
                    'size' => 10,
                    'name' => 'Times New Roman'
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
                'font' => array(
                    'bold' => true,
                    'color' => array('rgb' => '111112'),
                    'size' => 10,
                    'name' => 'Times New Roman'
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
                'font' => array(
                    'bold' => false,
                    'color' => array('rgb' => '111112'),
                    'size' => 10,
                    'name' => 'Times New Roman'
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
                'font' => array(
                    'bold' => false,
                    'color' => array('rgb' => '111112'),
                    'size' => 10,
                    'name' => 'Times New Roman'
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
                'font' => array(
                    'bold' => false,
                    'color' => array('rgb' => '111112'),
                    'size' => 10,
                    'name' => 'Times New Roman'
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
                'font' => array(
                    'bold' => false,
                    'color' => array('rgb' => '111112'),
                    'size' => 10,
                    'name' => 'Times New Roman'
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                )
            );

            $Background_style = array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'F9F400')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'font' => array(
                    'bold' => false,
                    'color' => array('rgb' => '111112'),
                    'size' => 10,
                    'name' => 'Times New Roman'
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                )
            );

            for ($row = 0; $row <= count($data); $row++) {
                $styleArray = [
                    'font' => [
                        'size' => 12
                    ]
                ];
                $objPHPExcel->getActiveSheet()
                    ->getStyle("A2:E2")
                    ->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(5);
                $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(5);


                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false)->setWidth(7);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(false);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(80);
                $TITLE = "BẢNG ĐƠN HÀNG KHÁCH HÀNG " . mb_strtoupper($user['customer_shop_code'], 'UTF-8') . " TỪ NGÀY {$params['startDate']} ĐẾN NGÀY {$params['endDate']}";
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, $TITLE)->mergeCells('A1:E1')->getStyle('A1')->applyFromArray($BStyle_not_border);

                $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'STT')->getStyle('A2')->applyFromArray($Background_style);
                $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'Ngày tạo')->getStyle('B2')->applyFromArray($Background_style);
                $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Mã đơn hàng')->getStyle('C2')->applyFromArray($Background_style);
                $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'Trả Shop')->getStyle('D2')->applyFromArray($Background_style);
                $objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Nội dung')->getStyle('E2')->applyFromArray($Background_style);
            }
            $j = 3;
            foreach ($data as $rom => $v) {
                $objPHPExcel->getActiveSheet()->setCellValue('A' . ($j), ($rom + 1))->getStyle('A' . $j)->applyFromArray($BStyle_not_header);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . ($j), (date_format(date_create($v['date_create']), "d-m-Y")))->getStyle('B' . $j)->applyFromArray($BStyle_not_header_left);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . ($j), ($v['code_supership']))->getStyle('C' . $j)->applyFromArray($BStyle_not_header_left);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . ($j), (intval($v['collect']) - intval($v['hd_fee_stam'])))->getStyle('D' . $j)->applyFromArray($BStyle_not_header)->getNumberFormat()->setFormatCode('#,##0');
                $objPHPExcel->getActiveSheet()->setCellValue('E' . ($j), ("Thu hộ : " . number_format(intval($v['collect'])) . ", Phí: " . number_format(intval($v['hd_fee_stam'])) . ", (KL:{$v['mass']}, {$v['receiver']} {$v['phone']} - {$v['district']} - {$v['city']})"))->getStyle('E' . $j)->applyFromArray($BStyle_not_header_left);
                $j++;
            }
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . ($j), '')->getStyle('A' . ($j))->applyFromArray($Background_style);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . ($j), '')->getStyle('B' . ($j))->applyFromArray($Background_style);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . ($j), '')->getStyle('C' . ($j))->applyFromArray($Background_style);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . ($j), '')->getStyle('D' . ($j))->applyFromArray($Background_style);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . ($j), '')->getStyle('E' . ($j))->applyFromArray($Background_style);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $j, 'Tổng')->mergeCells('A' . $j . ':C' . $j)->getStyle('A' . $j)->getNumberFormat()->setFormatCode('#,##0')->applyFromArray($Background_style);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . ($j), '=sum(D3:D' . ($j - 1) . ')')->getStyle('D' . ($j))->applyFromArray($Background_style)->getNumberFormat()->setFormatCode('#,##0');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($j + 2), 'Số tài khoản: ')->mergeCells('A' . ($j + 2) . ':B' . ($j + 2))->getStyle('A' . ($j + 2));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($j + 2), ' ' . $user['customer_number_bank'])->mergeCells('C' . ($j + 2) . ':D' . ($j + 2));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($j + 3), 'Tên tài khoản: ')->mergeCells('A' . ($j + 3) . ':B' . ($j + 3));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($j + 3), ' ' . $user['customer_id_bank'])->mergeCells('C' . ($j + 3) . ':D' . ($j + 3));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($j + 4), 'Tên ngân hàng: ')->mergeCells('A' . ($j + 4) . ':B' . ($j + 4));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($j + 4), ' ' . $user['customer_name_bank'])->mergeCells('C' . ($j + 4) . ':D' . ($j + 4));

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="DS Đơn Tạo - ' . $user['customer_shop_code'] . ' Từ ' . $params['startDate'] . ' Đến ' . $params['endDate'] . '.xls"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
            exit();
        } else {
            redirect(base_url());
        }
    }

    // TAB 6
    public function search()
    {
        $key = htmlspecialchars($this->input->get('keyword'));
        $logged_in = json_decode(json_encode(json_decode($this->input->cookie('logged_in'))), true);

        $this->db->from('tblorders_shop');

        $this->db->like('code_supership', $key);
        $this->db->or_like('phone', $key);
        $this->db->where(array('shop' => $logged_in['customer_shop_code'], 'status <>' => 'Hủy'));

        $result = $this->db->get()->result();
        $reponse = array('list_result' => $result);
        echo json_encode($reponse);
    }

    public function tracking()
    {
        $code = htmlspecialchars($this->input->get('code'));
        $id = intval($this->input->get('id'));
        $dvvc = htmlspecialchars($this->input->get('dvvc'));

        $this->db->from('tblorders_shop');
        $this->db->where(array('id' => $id, 'code_supership' => $code));
        $info_order = $this->db->get()->result();

        if ($dvvc == 'SPS') {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.mysupership.vn/v1/partner/orders/info?code=" . $code,
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
        }
        if($dvvc == 'GHTK'){
            $code_ghtk=$info_order[0]->code_ghtk;
            $sql = "
        SELECT webhook.*,status_order.status_change  FROM `tblwebhook_gh` as webhook
        LEFT JOIN tblstatus_order as status_order ON status_order.status_ghtk= webhook.status_id
        WHERE webhook.`label_id` = '$code_ghtk'  group by webhook.action_time ORDER BY webhook.action_time DESC";
            $dataGHTK = $this->db->query($sql)->result();

        }

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $logged_in = json_decode(json_encode(json_decode($this->input->cookie('logged_in'))), true);

            $this->db->select('tblstaff.phonenumber');
            $this->db->where('id', $logged_in['id']);
            $this->db->join('tblstaff', 'tblstaff.staffid = tblcustomers.customer_monitoring', 'left');
            $this->db->from('tblcustomers');

            $data = $this->db->get()->result();

            if ($dvvc == 'SPS') {
                if (empty($response) || empty($info_order)) {
                    echo json_encode(array('status' => false, 'error' => 'Error', 'Message' => 'Xảy ra lỗi.'));
                    die();
                }
//                $res['results']['journeys']
                $res = json_decode($response, true);

                foreach ($res['results']['notes'] as $key => $value){
                    $res['results']['notes'][$key]['time']=$value['created_at'];
                }
                $dataMerge =array_merge($res['results']['journeys'],$res['results']['notes']);
                $result = array('status' => true, 'error' => '', 'info_order' => $info_order[0], 'journeys' => $dataMerge, 'phone' => $data[0]->phonenumber,);
            }
            elseif ($dvvc == 'GHTK'){
                $result = array('status' => true, 'error' => '', 'info_order' => $info_order[0], 'journeys' => $dataGHTK, 'phone' => $data[0]->phonenumber,);

            }
            else {
                $result = array('status' => true, 'error' => '', 'info_order' => $info_order[0], 'phone' => $data[0]->phonenumber);
            }
            echo json_encode($result);
        }

    }

    // TAB 7
    public function order_manager_list()
    {
        $date_start_customer_order = htmlspecialchars($this->input->post('date_start_customer_order'));
        $date_end_customer_order = htmlspecialchars($this->input->post('date_end_customer_order'));
        $status = htmlspecialchars($this->input->post('status'));
        $page = htmlspecialchars($this->input->post('page'));

        $limit = 100 * ($page - 1);

        $logged_in = json_decode(json_encode(json_decode($this->input->cookie('logged_in'))), true);

        $this->db->from('tblorders_shop');
        $this->db->where('shop', $logged_in['customer_shop_code']);
        $this->db->where('date_create <=', date('Y-m-d  23:59:59', strtotime(str_replace('-', '-', $date_end_customer_order.' 23:59:59'))));
        $this->db->where('date_create >=', date('Y-m-d 00:00:00', strtotime(str_replace('-', '-', $date_start_customer_order.' 00:00:00'))));


        if($status != 'null')
            $this->db->where('status', $status);
        $total = $this->db->get()->num_rows();

        $this->db->select('tblorders_shop.*, tbl_create_order.required_code');
        $this->db->from('tblorders_shop');
        $this->db->where('shop', $logged_in['customer_shop_code']);
        $this->db->where('date_create <=', date('Y-m-d  23:59:59', strtotime(str_replace('-', '-', $date_end_customer_order . ' 23:59:59'))));
        $this->db->where('date_create >=', date('Y-m-d 00:00:00', strtotime(str_replace('-', '-', $date_start_customer_order . ' 00:00:00'))));

        $this->db->join('tbl_create_order', 'tbl_create_order.orders_shop_id = tblorders_shop.id');
        if ($status != 'null')
            $this->db->where('status', $status);
        $this->db->limit(100, $limit);
        $this->db->order_by('id', 'DESC');

        $list = $this->db->get()->result();
        echo json_encode(array('list_order' => $list, 'status' => true, 'error' => '','total' => $total,'sql' => $this->db->last_query()));
    }

    public function export_exel()
    {
        $this->load->model('create_order_model');
        $date_start_customer_order = htmlspecialchars($this->input->get('datestart'));
        $date_end_customer_order = htmlspecialchars($this->input->get('datend'));
        $status = htmlspecialchars($this->input->get('status'));
        $limit = htmlspecialchars($this->input->get('limit'));

        $logged_in = json_decode(json_encode(json_decode($this->input->cookie('logged_in'))), true);

        $this->db->from('tblorders_shop');
        $this->db->where('shop', $logged_in['customer_shop_code']);
        $this->db->where('date_create <=', date('Y-m-d', strtotime(str_replace('-', '-', $date_end_customer_order))));
        $this->db->where('date_create >=', date('Y-m-d', strtotime(str_replace('-', '-', $date_start_customer_order))));
        if ($status != 'null')
            $this->db->where('status', $status);
        if (!empty($limit))
            $this->db->limit($limit);
        $this->db->order_by('id', 'DESC');

        $data = $this->db->get()->result();
        $user = $this->create_order_model->get_customer_by_id($logged_in['id']);

        if (!empty($data)) {
            $colums = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H');
            include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
            $this->load->library('PHPExcel');
            $objPHPExcel = new PHPExcel();
            for ($i = 0; $i < 8; $i++) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($colums[$i])->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.2); // ~ 1.78cm
            $objPHPExcel->getActiveSheet()->getPageMargins()->setHeader(0.2); // ~1.02cm
            $objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.2); // ~
            $objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.2); // ~1.78cm
            $objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.2); // ~1.73cm
            $objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.2); // ~1.73cm
            $objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.2); // ~1.73cm
            $objPHPExcel->getActiveSheet()->getPageMargins()->setFooter(0); // ~1.02cm

            //end caách lề phiếu in

            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            //định dạng kiểu in ngang giấy A4

            $BStyle_not_border = array(
                'font' => array(
                    'bold' => true,
                    'color' => array('rgb' => '111112'),
                    'size' => 10,
                    'name' => 'Times New Roman'
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
                'font' => array(
                    'bold' => true,
                    'color' => array('rgb' => '111112'),
                    'size' => 10,
                    'name' => 'Times New Roman'
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
                'font' => array(
                    'bold' => true,
                    'color' => array('rgb' => '111112'),
                    'size' => 10,
                    'name' => 'Times New Roman'
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
                'font' => array(
                    'bold' => false,
                    'color' => array('rgb' => '111112'),
                    'size' => 10,
                    'name' => 'Times New Roman'
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
                'font' => array(
                    'bold' => false,
                    'color' => array('rgb' => '111112'),
                    'size' => 10,
                    'name' => 'Times New Roman'
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
                'font' => array(
                    'bold' => false,
                    'color' => array('rgb' => '111112'),
                    'size' => 10,
                    'name' => 'Times New Roman'
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
                'font' => array(
                    'bold' => false,
                    'color' => array('rgb' => '111112'),
                    'size' => 10,
                    'name' => 'Times New Roman'
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                )
            );

            $Background_style = array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'F9F400')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'font' => array(
                    'bold' => false,
                    'color' => array('rgb' => '111112'),
                    'size' => 10,
                    'name' => 'Times New Roman'
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                )
            );

            for ($row = 0; $row <= count($data); $row++) {
                $styleArray = [
                    'font' => [
                        'size' => 12
                    ]
                ];
                $objPHPExcel->getActiveSheet()
                    ->getStyle("A2:H2")
                    ->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(5);
                $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(5);


                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false)->setWidth(7);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(false);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(false);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(false);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(100);
                $TITLE = "BẢNG ĐƠN HÀNG KHÁCH HÀNG " . mb_strtoupper($logged_in['customer_shop_code'], 'UTF-8') . " TỪ NGÀY {$date_start_customer_order} ĐẾN NGÀY {$date_end_customer_order}";
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, $TITLE)->mergeCells('A1:H1')->getStyle('A1')->applyFromArray($BStyle_not_border);

                $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'STT')->getStyle('A2')->applyFromArray($Background_style);
                $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'Ngày tạo')->getStyle('B2')->applyFromArray($Background_style);
                $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Mã đơn hàng')->getStyle('C2')->applyFromArray($Background_style);
                $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'Trạng thái')->getStyle('D2')->applyFromArray($Background_style);
                $objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Thu hộ')->getStyle('E2')->applyFromArray($Background_style);
                $objPHPExcel->getActiveSheet()->SetCellValue('F2', 'Phí DV')->getStyle('F2')->applyFromArray($Background_style);
                $objPHPExcel->getActiveSheet()->SetCellValue('G2', 'Khối lượng')->getStyle('G2')->applyFromArray($Background_style);
                $objPHPExcel->getActiveSheet()->SetCellValue('H2', 'Thông tin người nhận')->getStyle('H2')->applyFromArray($Background_style);
            }


            $j = 3;
            $index = 0;
            foreach ($data as $rom) {
                if (is_null($rom->hd_fee))
                    $price = $rom->hd_fee_stam;
                else
                    $price = $rom->hd_fee;
                $index++;
                $objPHPExcel->getActiveSheet()->setCellValue('A' . ($j), ($index))->getStyle('A' . $j)->applyFromArray($BStyle_not_header);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . ($j), (date('d-m-Y', strtotime($rom->date_create))))->getStyle('B' . $j)->applyFromArray($BStyle_not_header_left);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . ($j), ($rom->code_supership))->getStyle('C' . $j)->applyFromArray($BStyle_not_header_left);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . ($j), ($rom->status))->getStyle('D' . $j)->applyFromArray($BStyle_not_header_left);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . ($j), (intval($rom->collect)))->getStyle('E' . $j)->applyFromArray($BStyle_not_header_left)->getNumberFormat()->setFormatCode('#,##0');
                $objPHPExcel->getActiveSheet()->setCellValue('F' . ($j), (intval($price)))->getStyle('F' . $j)->applyFromArray($BStyle_not_header_left)->getNumberFormat()->setFormatCode('#,##0');
                $objPHPExcel->getActiveSheet()->setCellValue('G' . ($j), (intval($rom->mass)))->getStyle('G' . $j)->applyFromArray($BStyle_not_header_left)->getNumberFormat()->setFormatCode('#,##0');
                $objPHPExcel->getActiveSheet()->setCellValue('H' . ($j), ($rom->receiver . ' - ' . $rom->phone . ' - ' . $rom->city . ' - ' . $rom->district))->getStyle('H' . $j)->applyFromArray($BStyle_not_header_left);
                $j++;
            }

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . ($j), '')->getStyle('A' . ($j))->applyFromArray($Background_style);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . ($j), '')->getStyle('B' . ($j))->applyFromArray($Background_style);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . ($j), '')->getStyle('C' . ($j))->applyFromArray($Background_style);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . ($j), '')->getStyle('D' . ($j))->applyFromArray($Background_style);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . ($j), '')->getStyle('E' . ($j))->applyFromArray($Background_style);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . ($j), '')->getStyle('F' . ($j))->applyFromArray($Background_style);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . ($j), '')->getStyle('G' . ($j))->applyFromArray($Background_style);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . ($j), '')->getStyle('H' . ($j))->applyFromArray($Background_style);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $j, 'Tổng')->mergeCells('A' . $j . ':D' . $j)->getStyle('A' . $j)->getNumberFormat()->setFormatCode('#,##0')->applyFromArray($Background_style);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . ($j), '=sum(E3:E' . ($j - 1) . ')')->getStyle('E' . ($j))->applyFromArray($Background_style)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . ($j), '=sum(F3:F' . ($j - 1) . ')')->getStyle('F' . ($j))->applyFromArray($Background_style)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . ($j), '=sum(G3:G' . ($j - 1) . ')')->getStyle('G' . ($j))->applyFromArray($Background_style)->getNumberFormat()->setFormatCode('#,##0');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($j + 2), 'Số tài khoản: ')->mergeCells('A' . ($j + 2) . ':B' . ($j + 2))->getStyle('A' . ($j + 2));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($j + 2), ' ' . $user['customer_number_bank'])->mergeCells('C' . ($j + 2) . ':D' . ($j + 2));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($j + 3), 'Tên tài khoản: ')->mergeCells('A' . ($j + 3) . ':B' . ($j + 3));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($j + 3), ' ' . $user['customer_id_bank'])->mergeCells('C' . ($j + 3) . ':D' . ($j + 3));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($j + 4), 'Tên ngân hàng: ')->mergeCells('A' . ($j + 4) . ':B' . ($j + 4));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($j + 4), ' ' . $user['customer_name_bank'])->mergeCells('C' . ($j + 4) . ':D' . ($j + 4));


            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="DS Đơn hàng - ' . $logged_in['customer_shop_code'] . ' Từ ' . $date_start_customer_order . ' Đến ' . $date_end_customer_order . '.xls"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
            exit();
        }
    }


	// Edit order
    public function get_order()
    {
        $id = intval($this->input->get('id'));

        $this->db->select('tbl_create_order.*, tblcustomers.token_customer, tblcustomers.customer_shop_code');
        $this->db->where('tbl_create_order.id', $id);
        $this->db->join('tblcustomers', 'tbl_create_order.customer_id = tblcustomers.id');

        $info_order = $this->db->get('tbl_create_order')->row();
        $info_order = json_encode($info_order);
        $info_order = json_decode($info_order, true);

        $provinces = $this->do_request_to_mysupership('https://api.mysupership.vn/v1/partner/areas/province', 'GET');
        $cities = $provinces['results'];
        foreach ($cities as $province) {
            if (strcmp($province['name'], trim($info_order['province'])) == 0) {
                $info_order['id_province'] = $province['code'];
            }
        }
        if (!empty($info_order['id_province'])) {
            $districts = $this->do_request_to_mysupership('https://api.mysupership.vn/v1/partner/areas/district?province=' . $info_order['id_province'], 'GET');
            $info_order['list_districts'] = $districts['results'];

            if(!empty($districts)){
                $districts = $districts['results'];
                $user['district'] = trim($info_order['district']);

                foreach ($districts as $v){
                    if(strcmp($v['name'], $info_order['district']) == 0){
                        $info_order['id_district'] = $v['code'];
                    }
                }
            }
        }

        if(!empty($info_order['id_district'])){
            $result_areas = $this->do_request_to_mysupership("https://api.mysupership.vn/v1/partner/areas/commune?district=".$info_order['id_district'], "GET");
            if(!empty($result_areas)){
                $info_order['list_areas'] = $result_areas['results'];
            }
        }

        echo json_encode(array('order' => $info_order));
    }
// Edit order error
    public function get_order_error()
    {
        $id = intval($this->input->get('id'));

        $this->db->select('tbl_create_order_error.*, tblcustomers.token_customer, tblcustomers.customer_shop_code');
        $this->db->where('tbl_create_order_error.id', $id);
        $this->db->join('tblcustomers', 'tbl_create_order_error.customer_id = tblcustomers.id');

        $info_order = $this->db->get('tbl_create_order_error')->row();
        $info_order = json_encode($info_order);
        $info_order = json_decode($info_order, true);

        $provinces = $this->do_request_to_mysupership('https://api.mysupership.vn/v1/partner/areas/province', 'GET');
        $cities = $provinces['results'];
        foreach ($cities as $province) {
            if (strcmp($province['name'], trim($info_order['province'])) == 0) {
                $info_order['id_province'] = $province['code'];
            }
        }
        if (!empty($info_order['id_province'])) {
            $districts = $this->do_request_to_mysupership('https://api.mysupership.vn/v1/partner/areas/district?province=' . $info_order['id_province'], 'GET');
            $info_order['list_districts'] = $districts['results'];

            if(!empty($districts)){
                $districts = $districts['results'];
                $user['district'] = trim($info_order['district']);

                foreach ($districts as $v){
                    if(strcmp($v['name'], $info_order['district']) == 0){
                        $info_order['id_district'] = $v['code'];
                    }
                }
            }
        }

        if(!empty($info_order['id_district'])){
            $result_areas = $this->do_request_to_mysupership("https://api.mysupership.vn/v1/partner/areas/commune?district=".$info_order['id_district'], "GET");
            if(!empty($result_areas)){
                $info_order['list_areas'] = $result_areas['results'];
            }
        }

        echo json_encode(array('order' => $info_order));
    }
    public function edit_order()
    {
        unset($_POST['token']);
        $id = $_POST['id'];
        unset($_POST['id']);
        $_POST['user_created'] = get_staff_user_id();

		$this->db->select('tbl_create_order.*, tblcustomers.customer_phone');
        $this->db->where('tbl_create_order.id', $id);
        $this->db->join('tblcustomers', 'tbl_create_order.customer_id = tblcustomers.id');
        $info = $this->db->get('tbl_create_order')->row();
        if(empty($_POST['pickup_phone'])){
            $_POST['pickup_phone'] = $info->customer_phone;
        }

        // $_POST['created'] = date('Y-m-d H:i:s');
        foreach ($_POST as $key => $value)
            $_POST[$key] = trim($value);
        $this->db->where('id', $id);
        $update = $this->db->update('tbl_create_order', $_POST);
        if ($update) {
            echo json_encode(array('success' => 'ok', 'code' => $info->required_code));
            die();
        } else {
            echo 'Luư dữ liệu không thành công';
            die();
        }
    }


public function do_request_to_mysupership($url, $method)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        if (!empty($response)) {
            return json_decode($response, true);
        } else {
            return false;
        }
    }



    public function upload()
    {
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        set_time_limit(300);

        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . 'PHPExcel/PHPExcel.php');
        $this->load->helper('security');

        $result = array('status' => false, 'message' => '');

        if (isset($_FILES['uploadfile'])) {
            $warehouse = htmlspecialchars($this->input->post('warehouse'));
            $id_customer = htmlspecialchars($this->input->post('id_customer'));

            $fullfile = $_FILES['uploadfile']['tmp_name'];
            $extension = strtoupper(pathinfo($_FILES['uploadfile']['name'], PATHINFO_EXTENSION));
            if ($extension != 'XLSX' && $extension != 'XLS') {
                $result['message'] = lang('Không đúng định dạng excel');
                echo json_encode($result);
                die();
            }

            $inputFileType = PHPExcel_IOFactory::identify($fullfile);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load("$fullfile");

            $total_sheets = $objPHPExcel->getSheetCount();
            $allSheetName = $objPHPExcel->getSheetNames();
            $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
            $highestRow = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString('BM');
            $array_colum = array();

            for ($row = 1; $row <= $highestRow; ++$row) {
                for ($col = 0; $col < $highestColumnIndex; ++$col) {
                    $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                    $array_colum[$row - 1][$col] = $value;
                }
            }

//            die;
            // get list province
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
            curl_close($curl);
            $province = json_decode($response)->results;

            $ware = explode(',', $warehouse);

            // get info customer
            $this->db->where('id', $id_customer);
            $info_customer = $this->db->get('tblcustomers')->row();

            // Check policy
            $this->db->select('*');
            $this->db->where('customer_id', $id_customer);
            $policy_result = $this->db->get('tblcustomer_policy')->row();
            if (empty($policy_result)) {
                $result['message'] = 'Khách Hàng Này Chưa Có Chính Sách';
                echo json_encode($result);
                die();
            }

            $data = $data_push =$dataError= $errors = array();

            if (!empty($array_colum)) {
                $this->db->where('customer_id', $id_customer);
                $delete = $this->db->delete('tbl_create_order_error');
                for ($i = 2; $i <= $highestRow; $i++) {
                    if ($array_colum[$i][0] != "" && $array_colum[$i][2] != "" && $array_colum[$i][3] != "" && $array_colum[$i][4] != "" && $array_colum[$i][5] != "" && $array_colum[$i][6] != "" && $array_colum[$i][7] >=0 && $array_colum[$i][8] >= 0) {
                        $data_push['customer_id'] = $id_customer;
                        $data_push['pickup_address'] = $warehouse;
                        $data_push['pickup_province'] = trim($ware[count($ware) - 1]);
                        $data_push['pickup_district'] = trim($ware[count($ware) - 2]);
                        $data_push['pickup_commune'] = '';
                        $data_push['pickup_phone'] = $info_customer->customer_phone;
                        $data_push['name'] = $array_colum[$i][2];
                        $data_push['phone'] = $array_colum[$i][3];
                        $data_push['address'] = $array_colum[$i][4];
                        $data_push['product'] = $array_colum[$i][5];
                        $data_push['weight'] = $array_colum[$i][6];
                        $data_push['cod'] = $array_colum[$i][7];
                        $data_push['value'] = $array_colum[$i][8];
                        $data_push['note'] = $array_colum[$i][9];
                        $data_push['created'] = date('Y-m-d H:i:s');
                        $data_push['required_code'] = 'YC' . '.' . code(3) . code(3) . code(3);
                        $data_push['volume'] = 27000;
                        $data_push['service'] = 1;
                        $data_push['config'] = 1;
                        $data_push['payer'] = 1;
                        $data_push['product_type'] = 1;
                        $data_push['barter'] = 0;
                        $data_push['user_created'] = 0;
                        $data_push['status_cancel'] = 0;
                        $data_push['amount'] = $array_colum[$i][7];
                        $data_push['soc'] = $array_colum[$i][1];
						$data_push['transport'] = 'road';



                        $this->load->model('Convert_model');
                        $Convert_model = new Convert_model();
                        $arrayAddress = $Convert_model->active($data_push['address']);
                        $data_push['commune'] = $arrayAddress->commune;
                        $data_push['province']=$arrayAddress->province;
                        $data_push['district']=$arrayAddress->district;
                        $this->db->select('*');
                        $this->db->where('city', $data_push['province']);
                        $this->db->where('district', $data_push['district']);
                        $search_result = $this->db->get('tblregion_excel')->row();

                        $data_push['region_id'] = $search_result->region_id;

                        if ($search_result) {
                            $this->db->select('*');
                            $this->db->where('id_policy', $policy_result->id);
                            $this->db->where('id_region', $search_result->region_id);
                            $data_region = $this->db->get('tbldata_region')->row();

                            $data_push['supership_value'] = $data_region->price_region;
                            array_push($data, $data_push);

                        }else{
                            array_push($errors, $array_colum[$i][0]);
                            array_push($dataError, $data_push);
                        }


                    } elseif ($array_colum[$i][1] == "" && $array_colum[$i][2] == "" && $array_colum[$i][3] == "" && $array_colum[$i][4] == "" && $array_colum[$i][5] == "" && $array_colum[$i][7] == "" && $array_colum[$i][8] == "" && $array_colum[$i][9] == "") {
                        unset($array_colum[$i]);
                    } else{
                        $data_push=[];
                        $data_push['customer_id'] = $id_customer;
                        $data_push['pickup_address'] = $warehouse;
                        $data_push['pickup_province'] = trim($ware[count($ware) - 1]);
                        $data_push['pickup_district'] = trim($ware[count($ware) - 2]);
                        $data_push['pickup_commune'] = '';
                        $data_push['pickup_phone'] = $info_customer->customer_phone;
                        $data_push['created'] = date('Y-m-d H:i:s');
                        $data_push['required_code'] = 'YC' . '.' . code(3) . code(3) . code(3);
                        $data_push['volume'] = 27000;
                        $data_push['service'] = 1;
                        $data_push['config'] = 1;
                        $data_push['payer'] = 1;
                        $data_push['product_type'] = 1;
                        $data_push['barter'] = 0;
                        $data_push['user_created'] = 0;
                        $data_push['status_cancel'] = 0;
                        $data_push['amount'] = $array_colum[$i][7];
                        $data_push['soc'] = $array_colum[$i][1];
                        $data_push['transport'] = 'road';

                        array_push($dataError, $data_push);
                        array_push($errors, $array_colum[$i][0]);


                    }


                }
            }


            if(!empty($data)){
                if (!$this->db->insert_batch('tbl_create_order', $data)) {
                    $result['message'] = 'Tạo đơn thất bại';
                    echo json_encode($result);
                    die();
                }
                $message = '<p>+ Số đơn tạo thành công: ' . count($data) . '.</p> <p>+ Số đơn tạo thất bại: ' . count($errors) . '<br>';
                if (!empty($errors)) {
                    foreach ($dataError as $dataErr){
                        $this->db->insert('tbl_create_order_error', $dataErr);

                    }

//                    $this->db->insert_batch('tbl_create_order_error', $dataError);

                    $message .= ' (STT: ' . implode(',', $errors) . ').</p>';
                }
            }else{
                $message = 'Tải lên thất bại. Chưa có đơn nào được tạo. Vui lòng kiểm tra lại file và nội dung bên trong.';
            }

            $result['count_error'] = count($errors);
            $result['status'] = true;
            $result['message'] = $message;
            echo json_encode($result);

        } else {
            $result['message'] = 'File upload không được để trống.';
            echo json_encode($result);
        }
    }



    public function get_data($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
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
        curl_close($curl);

        return json_decode($response)->results;

    }

    public function delete_order_error($id){
        $this->db->where('id',$id);
        $this->db->delete('tbl_create_order_error');
    }

}
