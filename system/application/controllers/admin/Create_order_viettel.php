<?php

class Create_order_viettel extends AdminController
{
    private $table_default_value = 'tbl_default_mass_volume_vpost';

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            if ($_POST['enable_filter']) {
                $this->app->get_table_data('_create_order_filter_viettelpost');
            } else {
                $this->app->get_table_data('_create_order_viettelpost');
            }
        }

        $province = $this->get_province();
//        pre($province);

        // $district_hd = $this->get_district_by_hd($code_hd);

        // $dataCustomers = $this->db->get_where('tblcustomers', array('active' => 1))->result();

        $default_data = $query = $this->db->get('tbl_default_mass_volume_vpost')->result();

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

        date_sub($date, date_interval_create_from_date_string('30 days'));

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
        $this->db->where('dvvc','VTP');
        $data['list_status_order'] = $this->db->get('tblstatus_order')->result();


        $this->load->view('admin/create_order/index_viettelpost', $data);

    }

    public function add_default()
    {
        $_POST['mass_default'] = str_replace(',', '', $_POST['mass_default']);

        $_POST['volume_default'] = str_replace(',', '', $_POST['volume_default']);
        $_POST['mass_fake'] = str_replace(',', '', $_POST['mass_fake']);
        $_POST['password'] = base64_encode($_POST['password']);

        //add NEw

        if ($_POST['id_default'] == '') {

            unset($_POST['id_default']);


            $id = $this->db->insert($this->table_default_value, $_POST);


            if ($id) {

                $this->session->set_flashdata('success_default', 1);

            }

        } else {

            $this->db->where('id', $_POST['id_default']);

            unset($_POST['id_default']);

            $update = $this->db->update($this->table_default_value, $_POST);


            if ($update) {

                $this->session->set_flashdata('success_default', 1);

            }

        }


        redirect(admin_url('/create_order_viettel'));

    }

    public function add_vpost()
    {
        $data_default = $this->db->get('tbl_default_mass_volume_vpost')->row();
        $dataLogin = array(
            "USERNAME" => $data_default->username,
            "PASSWORD" => base64_decode($data_default->password)
        );
        $token = loginVP($dataLogin);
        $address_id = $_POST['address_id'];

        $shop = $this->input->post('shop');
        $voucher = $this->input->post('voucher');

        unset($_POST['token']);

        unset($_POST['address_id']);
        unset($_POST['voucher']);

        unset($_POST['shop']);
        if (empty($address_id)) {
            echo json_encode(['success' => 'false']);
            die();
        }

        // Thêm mới vào bảng tblorders_shop
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
            'DVVC' => 'VTP',
            'is_hd_branch' => 1,
            'payer' => ($this->input->post('payer') == 1) ? 'Người Gửi' : '',
            'sale' => 0,
            'pack_data' => ($this->input->post('service') == 1) ? 'Tiết Kiệm' : 'Tốc Hành',
            'pay_refund' => 0,
            'status_over' => '',
            'status_delay' => $this->input->post('note_private'),
            'warehouse_send' => get_option('warehouse_send'),
            'region_id' => $this->input->post('region_id'),
            'mass_fake' => $this->input->post('mass_fake')
        );

        $this->db->insert('tblorders_shop', $data);

        $id_order_shop = $this->db->insert_id();

        $_POST['user_created'] = get_staff_user_id();

        $_POST['orders_shop_id'] = $id_order_shop;

        $_POST['dvvc'] = 'VTP';

        $this->db->insert('tbl_create_order', $_POST);

        $id = $this->db->insert_id();

        if ($id) {
            $provinces = $this->_get_administrative('https://partner.viettelpost.vn/v2/categories/listProvinceById?provinceId=-1');
            //SENDER_PROVINCE
            $str_city = str_replace(array('tỉnh', 'thành phố'), '', mb_strtolower($_POST['pickup_province']));
            foreach ($provinces as $province) {
                $provinceName = mb_strtolower($province['PROVINCE_NAME']);
                if (stripos($str_city, $provinceName) !== false) {
                    $sender_province = $province['PROVINCE_ID'];
                    break;
                }
            }
            //SENDER_DISTRICT
            $districts = $this->_get_administrative('https://partner.viettelpost.vn/v2/categories/listDistrict?provinceId=' . $sender_province);
            $str_district = str_replace(array('huyện', 'quận', 'thành phố', 'thị xã'), '', mb_strtolower($_POST['pickup_district']));
            foreach ($districts as $district) {
                $districtName = str_replace(array('huyện', 'quận', 'thành phố', 'thị xã', 'tp'), '', mb_strtolower($district['DISTRICT_NAME']));
                if (stripos($str_district, $districtName) !== false) {
                    $sender_district = $district['DISTRICT_ID'];
                    break;
                }
            }

            //SENDER_WARD
            $wards = $this->_get_administrative('https://partner.viettelpost.vn/v2/categories/listWards?districtId=' . $sender_district);
            $str_ward = str_replace(array('phường', 'xã', 'thị trấn'), '', mb_strtolower(trim(explode(',', $_POST['pickup_address'])[1])));
            foreach ($wards as $ward) {
                $wardName = str_replace(array('phường', 'xã', 'thị trấn'), '', mb_strtolower($ward['WARDS_NAME']));
                if (stripos($str_ward, $wardName) !== false) {
                    $sender_ward = $ward['WARDS_ID'];
                    break;
                }
            }


            //RECEIVER_PROVINCE
            $str_receiver_province = str_replace(array('tỉnh', 'thành phố'), '', mb_strtolower($_POST['province']));
            foreach ($provinces as $province) {
                $provinceName = mb_strtolower($province['PROVINCE_NAME']);
                // RECEIVER_PROVINCE
                if (stripos($str_receiver_province, $provinceName) !== false) {
                    $recevincer_province = $province['PROVINCE_ID'];
                    break;
                }
            }

            // RECEIVER_DISTRICT
            $receiver_districts = $this->_get_administrative('https://partner.viettelpost.vn/v2/categories/listDistrict?provinceId=' . $recevincer_province);
            $str_district_receiver = convert_vi_to_en(str_replace(array('huyện', 'quận', 'thành phố', 'thị xã'), '', mb_strtolower($_POST['district'])));
            foreach ($receiver_districts as $district) {
                $districtReceiverName = convert_vi_to_en(str_replace(array('huyện', 'quận', 'thành phố', 'thị xã', 'tp'), '', mb_strtolower($district['DISTRICT_NAME'])));
                if (strpos($districtReceiverName, trim($str_district_receiver)) !== false) {
                    $receiver_district = $district['DISTRICT_ID'];
                }
            }

            // RECEIVER_WARD
            $receiver_wards = $this->_get_administrative('https://partner.viettelpost.vn/v2/categories/listWards?districtId=' . $receiver_district);
            $str_ward_receiver = convert_vi_to_en(str_replace(array('phường', 'xã', 'thị trấn'), '', mb_strtolower($_POST['commune'])));
            foreach ($receiver_wards as $ward) {
                $wardName = convert_vi_to_en(str_replace(array('phường', 'xã', 'thị trấn'), '', mb_strtolower($ward['WARDS_NAME'])));
                if (stripos($wardName, $str_ward_receiver) !== false) {
                    $receiver_ward = $ward['WARDS_ID'];
                    break;
                }
            }

            $ORDER_SERVICE = 'VTK';

            if($_POST['service'] == 2)
                $ORDER_SERVICE= 'SCOD';
            elseif ($_POST['service'] == 3)
                $ORDER_SERVICE = 'PHS';

            // Khởi tạo dữ liệu gửi lên API Viettel Post
            $dataAPI = array(
                'ORDER_NUMBER' => $id,
                'GROUPADDRESS_ID' => $address_id,
                'CUS_ID' => '',
                'SENDER_FULLNAME' => $shop,
                'SENDER_ADDRESS' => $_POST['pickup_address'],
                'SENDER_PHONE' => $_POST['pickup_phone'],
                'SENDER_EMAIL' => '',
                'SENDER_WARD' => $sender_ward,
                'SENDER_DISTRICT' => $sender_district,
                'SENDER_PROVINCE' => $sender_province,
                'RECEIVER_FULLNAME' => $_POST['name'],
                'RECEIVER_ADDRESS' => $_POST['address'],
                'RECEIVER_PHONE' => $_POST['phone'],
                'RECEIVER_EMAIL' => '',
                'RECEIVER_WARD' => $receiver_ward,
                'RECEIVER_DISTRICT' => $receiver_district,
                'RECEIVER_PROVINCE' => $recevincer_province,
                'ORDER_PAYMENT' => 3,
                'ORDER_SERVICE' => $ORDER_SERVICE,
                'ORDER_SERVICE_ADD' => '',
                'ORDER_VOUCHER' => $voucher,
                'ORDER_NOTE' => $_POST['note'],
                'MONEY_COLLECTION' => $_POST['amount'],
                'MONEY_TOTALFEE' => '',
                'MONEY_FEECOD' => 0,
                'MONEY_FEEVAS' => 0,
                'MONEY_FEEINSURRANCE' => 0,
                'MONEY_FEE' => 0,
                'MONEY_FEEOTHER' => 0,
                'MONEY_TOTALVAT' => 0,
                'MONEY_TOTAL' => 0,
                'PRODUCT_TYPE' => 'HH',
                'PRODUCT_NAME' => $_POST['product'],
                'PRODUCT_DESCRIPTION' => '',
                'PRODUCT_WEIGHT' => $_POST['mass_fake'],
                'PRODUCT_QUANTITY' => 1,
                'PRODUCT_PRICE' => (empty($_POST['value'])) ? rand(2000000, 3000000) : $_POST['value']
            );

            $result = $this->_api_viettel($dataAPI, $token, 'https://partner.viettelpost.vn/v2/order/createOrder');

            if ($result['status'] != 200) {
                fnLog(json_encode($result));
                $this->db->delete('tbl_create_order', ['id' => $id]);
                $this->db->delete('tblorders_shop', ['id' => $id_order_shop]);
                echo $result['message'];
                die();
            }
            $resultData = $result['data'];
            $code = 'SPSVTP.' . $resultData['ORDER_NUMBER'];
            $today = date('Y-m-d H:i:s');

            $dataUpdateCreate = array(
                'code' => $code,
                'supership_value' => $resultData['MONEY_TOTAL'],
                'created' => $today
            );

            $dataUpdateShop = array(
                'code_supership' => $code,
                'pay_transport' => $resultData['MONEY_TOTAL'],
                'code_ghtk' => $resultData['ORDER_NUMBER']
            );

            $this->db->where('id', $id);
            $update = $this->db->update('tbl_create_order', $dataUpdateCreate);

            $this->db->where('id', $id_order_shop);
            $update_shop = $this->db->update('tblorders_shop', $dataUpdateShop);

            if ($update && $update_shop) {
                echo json_encode(['success' => 'ok', 'id' => $id, 'code' => $code]);
                die();
            }
        }

    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->where('dvvc', 'VTP');
        $info = $this->db->get('tbl_create_order')->row();
        if (!$info) {
            echo 'Không có đơn nào';
            die();
        }

        // Data
        $data_default = $this->db->get('tbl_default_mass_volume_vpost')->row();
        $dataLogin = array(
            "USERNAME" => $data_default->username,
            "PASSWORD" => base64_decode($data_default->password)
        );
        $token = loginVP($dataLogin);

        $data = array(
            'TYPE' => 4,
            'ORDER_NUMBER' => explode('.', $info->code)[1],
            'NOTE' => 'Thông tin đơn bị lỗi'
        );

        $result = $this->_api_viettel($data, $token, 'https://partner.viettelpost.vn/v2/order/UpdateOrder');

        if ($result['status'] == 200) {
            $dataUpdateCreate = array(
                'status_cancel' => 1
            );
            $this->db->where('id', $id);
            $update = $this->db->update('tbl_create_order', $dataUpdateCreate);

            $dataUpdateShop = array(
                'status' => 'Hủy'
            );
            $this->db->where('id', $info->orders_shop_id);
            $updateShop = $this->db->update('tblorders_shop', $dataUpdateShop);

            if ($update && $updateShop) {
                $this->session->set_flashdata('delete_order', 1);
            } else {
                fnLog('Hủy đơn có id ' . $id . ' thất bại');
            }

        }
        fnLog(json_encode($result));
        redirect(admin_url('/create_order_viettel'));
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

        $this->db->select(array('address_id_vpost', 'customer_shop_code'));

        $this->db->where('id', $id);

        $info_shop = $this->db->get('tblcustomers')->result();

        $search_result[0]->address_id_vpost = $info_shop[0]->address_id_vpost;

        $search_result[0]->customer_shop_code = $info_shop[0]->customer_shop_code;

        echo json_encode($search_result[0]);

        die();


        //

    }


    public function get_province()
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.mysupership.vn/v1/partner/areas/province',
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

        $response = curl_exec($curl);

        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo 'cURL Error #:' . $err;
        } else {
            if ($this->input->is_ajax_request()) {

                echo json_encode(json_decode($response)->results);

            }

            $result = json_decode($response)->results;

            return $result;

        }

    }

    public function get_district_by_hd($code)

    {

        $curl = curl_init();


        curl_setopt_array($curl, [

            CURLOPT_URL => 'https://api.mysupership.vn/v1/partner/areas/district?province=' . $code,

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


        $response = curl_exec($curl);

        $err = curl_error($curl);

        curl_close($curl);


        if ($err) {

        } else {

            $result = json_decode($response)->results;


            if ($this->input->is_ajax_request()) {

                echo json_encode($result);

                die();

            }


            return $result;

        }

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

    // =========================================================
    private function _api_viettel($data, $token, $url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Token: $token"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

    private function _get_administrative($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);
        if ($response['status'] == 200) {
            return $response['data'];
        }
        fnLog($response['message']);
        return false;
    }

}
