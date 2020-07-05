<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Create_order_ghtk extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    public function print_order($id = '')
    {
        $default_data = $query = $this->db->get('tbl_default_mass_volume_ghtk')->result();
        header('Content-type:application/pdf');

        $curl = curl_init();

        curl_setopt_array($curl, [

            CURLOPT_URL => 'https://services.giaohangtietkiem.vn/services/label/' . $id,

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_CUSTOMREQUEST => 'GET',

            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

            CURLOPT_HTTPHEADER => [

                'Token: ' . $default_data[0]->token_ghtk,

            ],

        ]);


        $response = curl_exec($curl);


        curl_close($curl);

        echo $response;

        die();

    }


    public function add_new_region()

    {

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $query = $this->db->get_where('tblregion_excel', ['city' => $_POST['city'], 'district' => $_POST['district'], 'region_id' => $_POST['region_id']])->row();


            if ($query === null) {

                $city = $_POST['city_region'];

                $district = $_POST['district_region'];

                $region_id = $_POST['region_id'];


                $data_add = ['city ' => $city, 'district' => $district, 'region_id' => $region_id];


                $id = $this->db->insert('tblregion_excel', $_POST);


                if ($id) {

                    $this->session->set_flashdata('success_default_region', 1);

                }

                redirect(admin_url('/create_order'));

            } else {

                $this->session->set_flashdata('error_default_region', 1);

            }

        }


        $data['tbldeclared_region'] = $query = $this->db->get('tbldeclared_region')->result();


        $this->load->view('admin/create_order/add_new_region', $data);

    }


    public function delete($id)

    {
        $this->db->select('tbl_create_order.*, tblorders_shop.code_ghtk');
        $this->db->join('tblorders_shop','tblorders_shop.id = tbl_create_order.orders_shop_id');
        $query = $this->db->get_where('tbl_create_order', ['tbl_create_order.id' => $id, 'tbl_create_order.dvvc' => 'GHTK'])->result()[0];

        $customerToken = $query->token_ghtk;


        $curl = curl_init();


        curl_setopt_array($curl, [

            CURLOPT_URL => 'https://services.giaohangtietkiem.vn/services/shipment/cancel/' . $query->code_ghtk,

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_CUSTOMREQUEST => 'POST',

            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

            CURLOPT_HTTPHEADER => [

                'Token: ' . $customerToken,

            ],

        ]);


        $response = curl_exec($curl);

        curl_close($curl);


        if (empty($response)) {

            fnLog('Không có kết quả trả về của ghtk');

            $this->session->set_flashdata('delete_order_error', 1);

        } else {

            $response = json_decode($response, true);


            if ($response['success'] == true) {


                // get info order ghtk

                $this->db->from('tbl_create_order');

                $this->db->where('id', $id);

                $info = $this->db->get()->result();


                $this->db->set('status_cancel', 1);

                $this->db->where('id', $id);

                $update = $this->db->update('tbl_create_order');


                if ($update) {

                    // Update status on table tblorder_shop

                    $this->db->where('id', $info[0]->orders_shop_id);

                    $update_order_shop = $this->db->update('tblorders_shop', array('status' => 'Hủy'));

                    if ($update_order_shop) {

                        $this->session->set_flashdata('delete_order', 1);

                    } else

                        fnLog('Cập nhật đơn hàng trên bảng tblorders_shop thất bại với mã đơn là ' . $id);

                } else

                    fnLog('Cập nhật đơn hàng trên bảng tbl_create_order thất bại với mã đơn là ' . $id);

            } else {

                fnLog(json_encode($response));

                $this->session->set_flashdata('delete_order_error', 2);

            }

        }

        redirect(admin_url('/create_order_ghtk'));

    }


    public function add_default()

    {

        $_POST['mass_default'] = str_replace(',', '', $_POST['mass_default']);

        $_POST['volume_default'] = str_replace(',', '', $_POST['volume_default']);
        $_POST['mass_fake'] = str_replace(',', '', $_POST['mass_fake']);


        //add NEw

        if ($_POST['id_default'] == '') {

            unset($_POST['id_default']);


            $id = $this->db->insert('tbl_default_mass_volume_ghtk', $_POST);


            if ($id) {

                $this->session->set_flashdata('success_default', 1);

            }

        } else {

            $this->db->where('id', $_POST['id_default']);

            unset($_POST['id_default']);

            $update = $this->db->update('tbl_default_mass_volume_ghtk', $_POST);


            if ($update) {

                $this->session->set_flashdata('success_default', 1);

            }

        }


        redirect(admin_url('/create_order_ghtk'));

    }


    public function check_soc($soc = '')

    {

        $this->db->select('*');

        $this->db->where('soc', $soc);

        $search_result = $this->db->get('tbl_create_order')->result();

        echo json_encode($search_result);

    }


    public function api_create_order($data, $token)

    {

        unset($data['pickup_commune']);


        if ($data['value'] === 'NaN') {

            $data['value'] = 0;

        }


        $curl = curl_init();


        curl_setopt($curl, CURLOPT_URL, 'https://api.mysupership.vn/v1/partner/orders/add');

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

        curl_setopt($curl, CURLOPT_POST, 1);


        $headers = [];

        $headers[] = 'Accept: application/json';

        $headers[] = 'Authorization: Bearer ' . $token;

        $headers[] = 'Content-Type: application/json';

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);


        $response = curl_exec($curl);

        $err = curl_error($curl);


        curl_close($curl);


        if ($err) {

            return $err;

        }


        return $response;

    }


    public function api_create_order_ghtk($data, $token, $id, $customer_shop_name)

    {

        unset($data['pickup_commune']);

		// Get info warehouse send
        $this->db->where('is_default', true);
        $info_warehouse_send = $this->db->get('tbl_warehouse_send')->row();


        if ($data['value'] === 'NaN') {

            $data['value'] = 0;

        }


		$value = $data['value'];
		if ($value < 3000000) {
			$valueNew = rand(2500000, 3000000);
			if ($valueNew > $value) {
				$number2 = substr($valueNew, 3,4);
				$value = $valueNew - $number2;
			}
		}

        // $product =

        // $data->products = array();

        $data_ghtk = new stdClass();

        $product = new stdClass();

        $data_ghtk->products = [];

        $product->name = $data['product'];

        $product->weight = (float)$data['mass_fake'] / 1000;

        $product->quantity = 1;


        array_push($data_ghtk->products, $product);

        $data_ghtk->order = new stdClass();

        $data_ghtk->order->id = $id;

        // $data_ghtk->order->pick_address_id = $address_id;

        $data_ghtk->order->pick_name = $customer_shop_name;

        $data_ghtk->order->pick_address = $data['pickup_address'];

        $data_ghtk->order->pick_province = $data['pickup_province'];

        $data_ghtk->order->pick_district = $data['pickup_district'];

        $data_ghtk->order->pick_tel = $info_warehouse_send->phone;

        $data_ghtk->order->tel = $data['phone'];

        $data_ghtk->order->name = $data['name'];

        $data_ghtk->order->address = $data['address'];

        $data_ghtk->order->province = $data['province'];

        $data_ghtk->order->district = $data['district'];

        $data_ghtk->order->ward = $data['commune'];

        $data_ghtk->order->is_freeship = 1;

        $data_ghtk->order->pick_money = $data['amount'];

        $data_ghtk->order->note = $data['note'];

        $data_ghtk->order->transport = $data['transport'];

        $data_ghtk->order->use_return_address = 0;
		$data_ghtk->order->value = $value;
			$data_ghtk->order->hamlet = "Hải dương";

		// Warehouse
        $data_ghtk->order->pick_address = $info_warehouse_send->nameAddress;
        $data_ghtk->order->pick_province = $info_warehouse_send->province;
        $data_ghtk->order->pick_district= $info_warehouse_send->district;
        $data_ghtk->order->pick_ward= $info_warehouse_send->commune;


        $curl = curl_init();


        curl_setopt($curl, CURLOPT_URL, 'https://services.giaohangtietkiem.vn/services/shipment/order');

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data_ghtk));

        curl_setopt($curl, CURLOPT_POST, 1);


        $headers = [];

        $headers[] = 'Accept: application/json';

        $headers[] = 'Token:' . $token;

        $headers[] = 'Content-Type: application/json';

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);


        $response = curl_exec($curl);


        $err = curl_error($curl);


        curl_close($curl);


        if ($err) {

            return $err;

        }


        return $response;

    }


    public function add_ghtk()

    {

        $token = $_POST['token'];

        $token_ghtk = $_POST['token_ghtk'];

        $address_id = $_POST['address_id'];

        $shop = $this->input->post('shop');

        unset($_POST['token']);

        unset($_POST['address_id']);

        unset($_POST['shop']);

        // if (empty($address_id)) {

            // echo json_encode(['success' => 'false']);

            // die();

        // } else {

            $data = array(

                'shop' => $shop,

                'code_orders' => $this->input->post('soc'),

                'status' => 'Đã Nhập Kho',

                'date_create' => date('Y-m-d H:i:s'),

                'mass' => $this->input->post('weight'),

                'collect' => $this->input->post('amount'),

                'value' => $this->input->post('value'),

                'prepay' => 0,

                'receiver' => $this->input->post('name'),

                'phone' => $this->input->post('phone'),

                'address' => $this->input->post('address'),

                'ward' => $this->input->post('commune'),

                'district' => $this->input->post('district'),

                'city' => $this->input->post('province'),

                'note' => $this->input->post('note'),

                'warehouses' => $this->input->post('pickup_address'),

                'product' => $this->input->post('product'),

                'city_send' => 'Tỉnh Hải Dương',

                'hd_fee_stam' => $this->input->post('supership_value'),

                'last_time_updated' => date('Y-m-d H:i:s'),

                'DVVC' => 'GHTK',

                'is_hd_branch' => 1,

                'payer' => ($this->input->post('payer') == 1) ? 'Người Gửi' : '',

                'sale' => 0,

                'pack_data' => ($this->input->post('service') == 1) ? 'Tốc Hành' : 'Tiết Kiệm',

                'pay_refund' => 0,

                'status_over' => '',

                'status_delay' => $this->input->post('note_private'),

                'warehouse_send' => get_option('warehouse_send'),
                'region_id' => $this->input->post('region_id'),
                'mass_fake' => $this->input->post('mass_fake')
            );


            $this->db->insert('tblorders_shop', $data);

            $id_order_shop = $this->db->insert_id();

            // unset($_POST['region_id']);

            $_POST['user_created'] = get_staff_user_id();

            $_POST['orders_shop_id'] = $id_order_shop;

            $_POST['dvvc'] = 'GHTK';

            $this->db->insert('tbl_create_order', $_POST);


            $id = $this->db->insert_id();


            if ($id) {
				$default_mass_volume_ghtk = $this->db->get('tbl_default_mass_volume_ghtk')->row();
				$codeNew = $default_mass_volume_ghtk->code . randerCode(6);
				
				$this->db->where('customer_shop_code', $shop);
				$info_customer = $this->db->get('tblcustomers')->row();

                $curl_status = $this->api_create_order_ghtk($_POST, $token_ghtk, $codeNew, $info_customer->customer_shop_code);

                $codeArr = explode('.', json_decode($curl_status)->order->label);

                $code = $codeNew . '.' . $codeArr[count($codeArr) - 1];

                if (json_decode($curl_status)->success === false) {

                    $this->db->delete('tbl_create_order', ['id' => $id]);

                    $this->db->delete('tblorders_shop', ['id' => $id_order_shop]);

                    echo $curl_status;

                    die();

                }

                $today = date('Y-m-d H:i:s');


                $this->db->set('created', $today);

                $this->db->set('code', $code);

                $this->db->where('id', $id);

                $update = $this->db->update('tbl_create_order');


                // Insert table tblorders_shop


                $this->db->set('code_supership', $code);

                $this->db->set('pay_transport', json_decode($curl_status)->order->fee);

                $this->db->set('insurance', json_decode($curl_status)->order->insurance_fee);

                $this->db->set('code_ghtk', json_decode($curl_status)->order->label);

                $this->db->where('id', $id_order_shop);

                $update_order = $this->db->update('tblorders_shop');


                if ($update && $update_order) {

                    echo json_encode(['success' => 'ok', 'id' => $id, 'code' => $code]);

                    die();

                }

                die();

            }

            echo 'Luư dữ liệu không thành công';

            die();

        // }

    }


    public function check_customer_policy_exits($id)

    {

        $this->db->select('*');

        $this->db->where('customer_id', $id);

        $search_result = $this->db->get('tblcustomer_policy')->result();


        if (count($search_result) === 0) {

            echo 'custommer_no';

            die();

        }


        $this->db->select(array('address_id', 'customer_shop_code'));

        $this->db->where('id', $id);

        $info_shop = $this->db->get('tblcustomers')->result();

        $search_result[0]->address_id = $info_shop[0]->address_id;

        $search_result[0]->customer_shop_code = $info_shop[0]->customer_shop_code;

        echo json_encode($search_result[0]);

        die();


        //

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

            $error = ['error' => true];

            echo json_encode($error);

        }

    }


    public function get_province()
    {
        $this->db->select('province_id as code,province as name')->distinct();
        $this->db->from('tbladdress_list');
        $purchases = $this->db->get()->result();

        return $purchases;

    }


    public function get_district_by_hd($code)

    {

        $this->db->select('district_id as code,district as name')->distinct();
        $this->db->from('tbladdress_list');
        $this->db->where('province_id', $code);

        $purchases = $this->db->get()->result();

        echo json_encode($purchases);


    }


    public function get_commune_by_hd($code)

    {

        $curl = curl_init();

        //

        curl_setopt_array($curl, [

            CURLOPT_URL => 'https://api.mysupership.vn/v1/partner/areas/commune?district=' . $code,

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_ENCODING => '',

            CURLOPT_MAXREDIRS => 10,

            CURLOPT_TIMEOUT => 30,

            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

            CURLOPT_CUSTOMREQUEST => 'GET',

            CURLOPT_HTTPHEADER => [

                'Accept: */*',

            ],

        ]);

        //

        $response = curl_exec($curl);

        $err = curl_error($curl);

        curl_close($curl);


        if ($this->input->is_ajax_request()) {

            echo json_encode(json_decode($response)->results);

            die();

        }


        return json_decode($response)->results;

    }


    public function index()

    {

        if ($this->input->is_ajax_request()) {

            if ($_POST['enable_filter']) {

                $this->app->get_table_data('_create_order_filter_ghtk');

            } else {

                $this->app->get_table_data('_create_order_ghtk');

            }

        }


        $province = $this->get_province();

        // $district_hd = $this->get_district_by_hd($code_hd);

        // $dataCustomers = $this->db->get_where('tblcustomers', array('active' => 1))->result();


        $default_data = $query = $this->db->get('tbl_default_mass_volume_ghtk')->result();

        $this->db->select('*');

        $this->db->select('tblcustomers.id as id');

        $this->db->from('tblcustomers');

        $this->db->where('active', 1);

        $this->db->join('tblcustomer_policy', 'tblcustomers.id = tblcustomer_policy.customer_id');

        $dataCustomers = $this->db->get()->result_array();


        $data = ['custommer' => $dataCustomers];

        $data['province'] = $province;


        if ($default_data) {

            $data['default_data'] = $default_data[0];

        } else {

            $data['default_data'] = null;

        }

        $data['tbldeclared_region'] = $query = $this->db->get('tbldeclared_region')->result();


        $this->db->select('staffid,firstname,lastname');

        $this->db->from('tblstaff');

        $data['staffs'] = $staff = $this->db->get()->result();


        $data['date_end'] = date('Y-m-d');

        $date = new DateTime($data['date_end']);

        date_sub($date, date_interval_create_from_date_string('2 days'));

        $data['date_start'] = date_format($date, 'Y-m-d');

        $dataC = get_table_where('tblcustomers');


        foreach ($dataC as $key => $value) {

            $stringCut = $value['customer_shop_code'];


            if (strpos($value['customer_shop_code'], '-') !== false) {

                $dataC[$key]['display_shop_code'] = trim(ltrim(strstr($stringCut, '-', false), '-'));

            } else {

                $dataC[$key]['display_shop_code'] = $value['customer_shop_code'];

            }

        }


        $data['customer'] = $dataC;


        // get list status
        $this->db->where('dvvc','GHTK');
        $data['list_status_order'] = $this->db->get('tblstatus_order')->result();


        $this->load->view('admin/create_order/index_ghtk', $data);

    }


    public function export_exel_orders()

    {

        $params = $this->input->get();

        $this->load->model('create_order_model');

        $data = $this->create_order_model->get_orders_by_time($params);


        if (empty($data)) {

            echo 'Không có dữ liệu nào phù hợp';

            die();

        }

        $colums = ['A', 'B', 'C', 'D', 'E', 'F'];

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


        $BStyle_not_border = [

            'font' => [

                'bold' => true,

                'color' => ['rgb' => '111112'],

                'size' => 10,

                'name' => 'Times New Roman',

            ],

            'alignment' => [

                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

            ],

        ];

        $BStyle = [

            'borders' => [

                'allborders' => [

                    'style' => PHPExcel_Style_Border::BORDER_THIN,

                ],

            ],

            'font' => [

                'bold' => true,

                'color' => ['rgb' => '111112'],

                'size' => 10,

                'name' => 'Times New Roman',

            ],

            'alignment' => [

                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

            ],

        ];


        $BStyle_not_center = [

            'borders' => [

                'allborders' => [

                    'style' => PHPExcel_Style_Border::BORDER_THIN,

                ],

            ],

            'font' => [

                'bold' => true,

                'color' => ['rgb' => '111112'],

                'size' => 10,

                'name' => 'Times New Roman',

            ],

            'alignment' => [

                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

            ],

        ];

        $BStyle_not_header = [

            'borders' => [

                'allborders' => [

                    'style' => PHPExcel_Style_Border::BORDER_THIN,

                ],

            ],

            'font' => [

                'bold' => false,

                'color' => ['rgb' => '111112'],

                'size' => 10,

                'name' => 'Times New Roman',

            ],

            'alignment' => [

                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

            ],

        ];


        $BStyle_header = [

            'borders' => [

                'allborders' => [

                    'style' => PHPExcel_Style_Border::BORDER_THIN,

                ],

            ],

            'font' => [

                'bold' => false,

                'color' => ['rgb' => '111112'],

                'size' => 10,

                'name' => 'Times New Roman',

            ],

            'alignment' => [

                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

            ],

        ];

        $BStyle_not_header_left = [

            'borders' => [

                'allborders' => [

                    'style' => PHPExcel_Style_Border::BORDER_THIN,

                ],

            ],

            'font' => [

                'bold' => false,

                'color' => ['rgb' => '111112'],

                'size' => 10,

                'name' => 'Times New Roman',

            ],

            'alignment' => [

                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

            ],

        ];

        $BStyle_not_header_right = [

            'borders' => [

                'allborders' => [

                    'style' => PHPExcel_Style_Border::BORDER_THIN,

                ],

            ],

            'font' => [

                'bold' => false,

                'color' => ['rgb' => '111112'],

                'size' => 10,

                'name' => 'Times New Roman',

            ],

            'alignment' => [

                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

            ],

        ];


        $Background_style = [

            'fill' => [

                'type' => PHPExcel_Style_Fill::FILL_SOLID,

                'color' => ['rgb' => 'F9F400'],

            ],

            'borders' => [

                'allborders' => [

                    'style' => PHPExcel_Style_Border::BORDER_THIN,

                ],

            ],

            'font' => [

                'bold' => false,

                'color' => ['rgb' => '111112'],

                'size' => 10,

                'name' => 'Times New Roman',

            ],

            'alignment' => [

                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,

            ],

        ];


        for ($row = 0; $row <= count($dataPush); $row++) {

            $styleArray = [

                'font' => [

                    'size' => 12,

                ],

            ];

            $objPHPExcel->getActiveSheet()
                ->getStyle('A2:E2')
                ->applyFromArray($styleArray);

            $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(5);

            $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(5);


            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false)->setWidth(7);

            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);

            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(false);

            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(80);


            if (!empty($params['customer_id'])) {

                $user = $this->create_order_model->get_customer_by_id($params['customer_id']);

            }


            if (!empty($user)) {

                $TITLE = 'BẢNG ĐƠN HÀNG KHÁCH HÀNG ' . mb_strtoupper($user['customer_shop_code'], 'UTF-8') . " TỪ NGÀY {$params['startDate']} ĐẾN NGÀY {$params['endDate']}";

            } else {

                $TITLE = "BẢNG ĐƠN HÀNG KHÁCH HÀNG TỪ NGÀY {$params['startDate']} ĐẾN NGÀY {$params['endDate']}";

            }

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

            $objPHPExcel->getActiveSheet()->setCellValue('B' . ($j), (date_format(date_create($v['date_create']), 'd-m-Y')))->getStyle('B' . $j)->applyFromArray($BStyle_not_header_left);

            $objPHPExcel->getActiveSheet()->setCellValue('C' . ($j), ($v['code_supership']))->getStyle('C' . $j)->applyFromArray($BStyle_not_header_left);

            $objPHPExcel->getActiveSheet()->setCellValue('D' . ($j), (intval($v['collect']) - intval($v['hd_fee_stam'])))->getStyle('D' . $j)->applyFromArray($BStyle_not_header)->getNumberFormat()->setFormatCode('#,##0');

            $objPHPExcel->getActiveSheet()->setCellValue('E' . ($j), ('Thu hộ : ' . number_format(intval($v['collect'])) . ', Phí: ' . number_format(intval($v['hd_fee_stam'])) . ", (KL:{$v['mass']}, {$v['receiver']} {$v['phone']} - {$v['district']} - {$v['city']})"))->getStyle('E' . $j)->applyFromArray($BStyle_not_header_left);

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


        if (!empty($user)) {

            header('Content-Disposition: attachment;filename="DS Đơn Tạo - ' . $user['customer_shop_code'] . ' Từ ' . $params['startDate'] . ' Đến ' . $params['endDate'] . '.xls"');

        } else {

            header('Content-Disposition: attachment;filename="DS Đơn Tạo - Từ ' . $params['startDate'] . ' Đến ' . $params['endDate'] . '.xls"');

        }

        header('Cache-Control: max-age=0');

        $objWriter->save('php://output');

        exit();

    }





//    function print_data_order($idcustomer) {

//

//

//        $this->db->where('id', $idcustomer);

//        $customers = $this->db->get('tblcustomers')->row();

//

//        $invoice_number = $customers->customer_shop_code;

//

//        $pdf            = create_order_ghtk_pdf($customers);

//

//        $type           = 'D';

//        if ($this->input->get('pdf') || $this->input->get('print')) {

//            $type = 'I';

//        }

//        $pdf->Output(mb_strtoupper(slug_it($invoice_number)) . '.pdf', $type);

//    }


    public function print_data_order($id_orders)
    {
        if (!empty($_GET)) {

            if ($_GET['print']) {

                $data['print'] = true;

            }

            if(isset($_GET['dv']) && in_array($_GET['dv'], array('VTP','VNC','NB'))){
                $data['dv'] = $_GET['dv'];
            }

        }

        $this->db->select('tblorders_shop.*,tbl_create_order.soc, tbl_create_order.name, tbl_create_order.province, tbl_create_order.required_code');

        $this->db->where('tbl_create_order.id', $id_orders);

        $this->db->join('tblcustomers', 'tblcustomers.id = tbl_create_order.customer_id');

        $this->db->join('tblorders_shop', 'tblorders_shop.id = tbl_create_order.orders_shop_id');

        $create_order = $this->db->get('tbl_create_order')->row();


        $data['create_order'] = $create_order;

        $this->load->view('admin/create_order/print-out', $data);


    }


    public function delete_status()

    {

        $id = intval($this->uri->rsegment(3));

        $this->db->where('id', $id);

        $info = $this->db->get('tblstatus_order')->result()[0];

        if (!$info) {

            $this->session->set_flashdata('delete_status_order', 2);

            redirect(base_url('admin/create_order_ghtk'));

        }


        $this->db->where('id', $id);

        if ($this->db->delete('tblstatus_order')) {

            $this->session->set_flashdata('delete_status_order', 1);

        } else {

            $this->session->set_flashdata('delete_status_order', 3);

        }

        redirect(base_url('admin/create_order_ghtk'));

    }

}

