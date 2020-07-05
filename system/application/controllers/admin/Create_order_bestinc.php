<?php

class Create_order_bestinc extends AdminController
{
    private $table_default_value = 'tbl_default_mass_volume_vnc';

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            if ($_POST['enable_filter']) {
                $this->app->get_table_data('_create_order_filter_bestinc');
            } else {
                $this->app->get_table_data('_create_order_bestinc');
            }
        }

        $province = $this->get_province();
//        pre($province);

        // $district_hd = $this->get_district_by_hd($code_hd);

        // $dataCustomers = $this->db->get_where('tblcustomers', array('active' => 1))->result();

        $default_data = $query = $this->db->get('tbl_default_mass_volume_vnc')->result();

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
        $this->db->where('dvvc', 'VNC');
        $data['list_status_order'] = $this->db->get('tblstatus_order')->result();


        // warehouse_send
        $data['warehouse_send_list'] = $this->db->get('tbl_warehouse_send')->row();

        $this->load->view('admin/create_order/index_bestinc', $data);

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


        redirect(admin_url('create_order_bestinc'));

    }

    public function add_vnc()
    {
        $data_default = $this->db->get('tbl_default_mass_volume_vnc')->row();
        $dataLogin = array(
            "USERNAME" => $data_default->username,
            "PASSWORD" => base64_decode($data_default->password)
        );

        $token = loginVNC($dataLogin, URL_VNC . 'User/Login');

        $shop = $this->input->post('shop');
        $id_default = $this->input->post('id_default');
        unset($_POST['token']);

        unset($_POST['address_id']);
        unset($_POST['voucher']);
        unset($_POST['shop']);
        unset($_POST['id_default']);


        // Thêm mới vào bảng tblorders_shop
        $data_orders_shop = array(
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
            'DVVC' => 'VNC',
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

        $this->db->insert('tblorders_shop', $data_orders_shop);

        $id_order_shop = $this->db->insert_id();

        $_POST['user_created'] = get_staff_user_id();

        $_POST['orders_shop_id'] = $id_order_shop;

        $_POST['dvvc'] = 'VNC';

        $this->db->insert('tbl_create_order', $_POST);

        $id = $this->db->insert_id();

        if ($id) {
            $codeNew = CODE_VNC . randerCode(2) . code(4);
            $this->db->where('id', $id_default);
            $warehouser = $this->db->get('tbl_warehouse_send')->row();

			$value = $_POST['value'];
            if ($value < 3000000) {
                $valueNew = rand(2500000, 3000000);
                if ($valueNew > $value) {
                    $number2 = substr($valueNew, 3,4);
                    $value = $valueNew - $number2;
                }
            }

            $dataAPI = array(
                'Code' => $codeNew,
                'ProductName' => $this->input->post('product'),
                'CollectAmount' => $this->input->post('amount'),
                'JourneyType' => 1,
                'ServiceId' => $this->input->post('service') == 1 ? 12491 : 12490,
                'Weight' => $this->input->post('mass_fake'),
                'Note' => $this->input->post('note'),
                'NumberOfProducts' => 1,
                'SourceCity' => $warehouser->province,
                'SourceDistrict' => $warehouser->district,
                'SourceWard' => $warehouser->commune,
                'SourceAddress' => $warehouser->nameAddress,
                'SourceName' => $shop,
                'SourcePhoneNumber' => $warehouser->phone,
				
				'ReturnCity' => $warehouser->province,
                'ReturnDistrict' => $warehouser->district,
                'ReturnWard' => $warehouser->commune,
                'ReturnAddress' => $warehouser->nameAddress,
                'ReturnName' => $shop,
                'ReturnPhoneNumber' => $warehouser->phone,
				
                'DestCity' => $this->input->post('province'),
                'DestDistrict' => $this->input->post('district'),
                'DestWard' => $this->input->post('commune'),
                'DestAddress' => $this->input->post('commune') . ', ' . $this->input->post('district') . ', ' . $this->input->post('province'),
                'DestName' => $this->input->post('name'),
                'DestPhoneNumber' => $this->input->post('phone'),
                'Width' => 0,
                'Height' => 0,
                'Length' => 0,
				'ProductPrice' => $value
            );

            $resultArr = $this->_api_vnc($dataAPI, $token, URL_VNC . 'Order/Add');

            if (empty($resultArr)) {
                echo 'Lỗi không tạo đươc đơn!';
                fnLog('Dữ liệu rỗng. Vui lòng kiểm tra lại dữ liệu gửi lên hoặc đường dẫn.');
                die();
            }


            if ($resultArr['Result'] === 2) {

                $this->db->delete('tbl_create_order', ['id' => $id]);

                $this->db->delete('tblorders_shop', ['id' => $id_order_shop]);
                fnLog(json_encode($resultArr));
                echo json_encode($resultArr);

                die();

            }
            $code = $codeNew . '.' . $resultArr['Code'];
            $today = date('Y-m-d H:i:s');


            $this->db->set('created', $today);

            $this->db->set('code', $code);

            $this->db->where('id', $id);

            $update = $this->db->update('tbl_create_order');


            // Insert table tblorders_shop


            $this->db->set('code_supership', $code);

            $this->db->set('code_ghtk', $resultArr['Code']);

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

    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->where('dvvc', 'VNC');
        $info = $this->db->get('tbl_create_order')->row();

        if (!$info) {
            echo 'Không có đơn nào';
            die();
        }

        // Data
        $data_default = $this->db->get('tbl_default_mass_volume_vnc')->row();
        $dataLogin = array(
            "USERNAME" => $data_default->username,
            "PASSWORD" => base64_decode($data_default->password)
        );

        $token = loginVNC($dataLogin, URL_VNC . 'User/Login');

        $data = array(
            'Code' => explode('.', $info->code)[0]
        );

        $result = $this->_api_vnc($data, $token, URL_VNC . 'Order/Cancel');

        if ($result['Result'] == 1) {
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
        redirect(admin_url('create_order_bestinc'));
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
    private function _api_vnc($data, $token, $url)
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
                "Authorization: Bearer $token"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

}
