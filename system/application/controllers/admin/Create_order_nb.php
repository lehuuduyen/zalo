<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Create_order_nb extends AdminController
{
    private $table_default_value = 'tbl_default_mass_volume_nb';
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            if ($_POST['enable_filter']) {
                $this->app->get_table_data('_create_order_filter_nb');
            } else {
                $this->app->get_table_data('_create_order_nb');


            }

        }

        $province = $this->get_province();
        // $district_hd = $this->get_district_by_hd($code_hd);
        // $dataCustomers = $this->db->get_where('tblcustomers', array('active' => 1))->result();


        $default_data = $query = $this->db->get('tbl_default_mass_volume_nb')->result();
        $this->db->select('*');
        $this->db->select('tblcustomers.id as id');
        $this->db->from('tblcustomers');
        $this->db->where('active', 1);
        $this->db->join('tblcustomer_policy', 'tblcustomers.id = tblcustomer_policy.customer_id');
        $dataCustomers = $this->db->get()->result_array();


        $data = array('custommer' => $dataCustomers);
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
        $this->db->where('dvvc', 'NB');
        $data['list_status_order'] = $this->db->get('tblstatus_order')->result();


        $this->load->view('admin/create_order/index_nb', $data);
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

    public function add_nb()
    {
        // Thêm mới vào bảng tblorders_shop
        $shop = $this->input->post('shop');
        $code = CODE_NB . '.' . code(8);

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
            'DVVC' => 'NB',
            'is_hd_branch' => 1,
            'payer' => ($this->input->post('payer') == 1) ? 'Người Gửi' : '',
            'sale' => 0,
            'pack_data' => ($this->input->post('service') == 1) ? 'Tiết Kiệm' : 'Tốc Hành',
            'pay_refund' => 0,
            'status_over' => '',
            'status_delay' => $this->input->post('note_private'),
            'warehouse_send' => get_option('warehouse_send'),
            'region_id' => $this->input->post('region_id'),
            'mass_fake' => $this->input->post('mass_fake'),
            'code_supership' => $code
        );

        $this->db->insert('tblorders_shop', $data_orders_shop);

        $id_order_shop = $this->db->insert_id();

        $_POST['user_created'] = get_staff_user_id();

        $_POST['orders_shop_id'] = $id_order_shop;
        $_POST['code'] = $code;
        $_POST['created'] = date('Y-m-d H:i:s');

        $_POST['dvvc'] = 'NB';

        unset($_POST['token']);

        unset($_POST['address_id']);
        unset($_POST['voucher']);
        unset($_POST['shop']);
        unset($_POST['id_default']);

        $this->db->insert('tbl_create_order', $_POST);

        $id = $this->db->insert_id();

        if ($id) {
            echo json_encode(['success' => 'ok', 'id' => $id, 'code' => $code]);

            die();

        }

        echo 'Luư dữ liệu không thành công';

        die();
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->where('dvvc', 'NB');
        $info = $this->db->get('tbl_create_order')->row();

        if (!$info) {
            echo 'Không có đơn nào';
            die();
        }

        // Data
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
        redirect(admin_url('create_order_nb'));
    }

    public function add_default()
    {

        $_POST['mass_default'] = str_replace(',', '', $_POST['mass_default']);

        $_POST['volume_default'] = str_replace(',', '', $_POST['volume_default']);
        $_POST['mass_fake'] = str_replace(',', '', $_POST['mass_fake']);


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


        redirect(admin_url('/create_order_nb'));

    }
}
