<?php

class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function webhook_ghtk()
    {
        $json = file_get_contents('php://input');

    }

    public function cronjob_order_ghtk()
    {
        $default_data = $query = $this->db->get('tbl_default_mass_volume_ghtk')->result()[0];
        $now = date('Y-m-d H:m:s');
        $sql = 'SELECT * FROM 
                    `tblorders_shop` 
                WHERE ((`status` <> "Huỷ" 
                AND `status` <> "Đã Đối Soát Giao Hàng"
                AND `status` <> "Đã Trả Hàng Một Phần"
                AND `status` <> "Đã Trả Hàng")
                OR `status` IS NULL )
                AND `DVVC` = "GHTK"
                AND `date_create` >= "' . date("Y-m-d H:i", strtotime("$now - 90 day")) . '" ORDER BY id DESC';

        $list_order = $this->db->query($sql)->result();
        $errors = array();

        if (!empty($list_order)) {
            foreach ($list_order as $order) {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://services.giaohangtietkiem.vn/services/shipment/v2/" . $order->code_ghtk,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "token: " . $default_data->token_ghtk
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $arr = json_decode($response, true);
                if ($arr['success'] == 1) {
                    $order_ghtk = $arr['order'];

                    $this->db->where('status_ghtk', $order_ghtk['status']);
                    $result_Status = $this->db->get('tblstatus_order')->result()[0];

                    if (!empty($result_Status)) {
                        $dataUpdate = array(
                            'status' => $result_Status->status_change,
                            'value' => $order_ghtk['value'],
                            'insurance' => $order_ghtk['insurance'],
                            'pay_transport' => $order_ghtk['ship_money'],
                            'collect' => $order_ghtk['pick_money'],
                            'mass' => $order_ghtk['weight'],
                            'last_time_updated' => $order_ghtk['modified']
                        );
                        if (!empty($result_Status->status_debit) && is_null($order->date_debits)) {
                            $dataUpdate['date_debits'] = date('Y-m-d H:m:s');
                        }
                        $this->db->where('id', $order->id);
                        $update = $this->db->update('tblorders_shop', $dataUpdate);
                        if (!$update) {
                            array_push($errors, 'Cập nhật thất bại đơn có mã ghtk: ' . $order->code_ghtk);
                        }
                    }
                } else {
                    array_push($errors, 'Không có giá trị đơn có mã ghtk: ' . $order->code_ghtk . ' trên GHTK');
                }
            }

            echo 'Số đơn thành công: ' . count($list_order) - count($errors) . '<br>';
            echo 'Số đơn thất bại: ' . count($errors) . '<br>';
            echo 'Danh sách mã đơn thất bại: ' . var_dump($errors);
        }else
            echo 'Không có đơn nào trong khoảng thời gian này';
    }

    public function add_status()
    {
        $status_ghtk = htmlspecialchars($this->input->post('status_ghtk'));
        $status_change = htmlspecialchars($this->input->post('status_change'));
        $status_debit = intval($this->input->post('status_debit'));
        $active = intval($this->input->post('active'));

        $result = array('status' => false, 'error' => '');

        if (empty($status_change) || empty($status_ghtk)) {
            $result['error'] = 'Error';
            echo json_encode($result);
            die();
        }

        if (empty($status_debit)) {
            $status_debit = 0;
        }

        $data = array(
            'status_ghtk' => $status_ghtk,
            'status_change' => $status_change,
            'status_debit' => $status_debit
        );
        if (empty($active)) {
            if (!$this->db->insert('status_order', $data)) {
                $result['error'] = 'ErrorInsert';
                echo json_encode($result);
                die();
            }
            $result['message'] = 'Thêm thành công';
        } else {
            $this->db->where('id', $active);

            if (!$this->db->update('status_order', $data)) {
                $result['error'] = 'ErrorUpdate';
                echo json_encode($result);
                die();
            }
            $result['message'] = 'Cập nhật thành công';
        }
        $result['status'] = true;
        echo json_encode($result);
    }

    public function getStatus()
    {
        $result = array('status' => false, 'error' => '');
        $id = intval($this->input->post('id'));

        $this->db->where('id', $id);
        $info = $this->db->get('tblstatus_order')->result()[0];
        if (!$info) {
            $result['error'] = 'noStatus';
            echo json_encode($result);
            exit();
        }
        $result['status'] = true;
        $result['info'] = $info;
        echo json_encode($result);
    }

    public function updateOrder()
    {
        $status = htmlspecialchars($this->input->post('status'));
        $price = intval($this->input->post('price'));
        $id = intval($this->input->post('id'));

        $this->db->where('id', $id);
        $info = $this->db->get('tblorders_shop')->result()[0];

        if (!$info) {
            echo 0;
            die();
        }

        $data = array();
        if (!empty($status)) {
            $data['status'] = $status;
            if($status != 'Hủy'){
                if(is_null($info->date_debits)){
                    $data['date_debits'] = date('Y-m-d H:m:s');
                }
            }
        }
        if (!empty($price)) {
            if (is_null($info->hd_fee)) {
                $data['hd_fee_stam'] = $price;
            } else
                $data['hd_fee'] = $price;
        }

        $this->db->where('id', $id);
        $update = $this->db->update('tblorders_shop', $data);
        if (!$update) {
            echo 1;
            die();
        }
        $this->insertOrderHistory(
            $info->code_orders,
            $info->code_supership,
            $info->status,
            $info->hd_fee_stam,
            $info->hd_fee,
            (!empty($data['status']) ? $data['status'] : $info->status),
            (!empty($data['hd_fee_stam']) ? $data['hd_fee_stam'] : NULL),
            (!empty($data['hd_fee']) ? $data['hd_fee'] : NULL)
        );
        echo 2;

    }

    public function search()
    {
        $p = htmlspecialchars($this->input->post('p'));
        $date_start = $this->input->post('date_start');
        $date_end = $this->input->post('date_end');
        if(!empty($p)) {

            $this->db->from('tblorders_shop');

            $this->db->like('code_supership', $p);

            $result = $this->db->get()->result()[0];
        }

        $this->db->select('code_supership,
             status, 
             hd_fee_stam, 
             hd_fee,
             status_new, 
             hd_fee_stam_new, 
             hd_fee_new, 
             DATE_FORMAT(tblorders_shop_history.date_create, "%d/%m/%Y %H:%i:%s") as date_create,
             CONCAT_WS("", lastname, firstname, NULL) AS fullname
         ');
        $this->db->from('tblorders_shop_history');
        $this->db->join('tblstaff', 'tblstaff.staffid = tblorders_shop_history.create_by', 'left');
        $this->db->order_by('id DESC');
        if(!empty($date_start)) {
            $this->db->where(' DATE_FORMAT(tblorders_shop_history.date_create, "%Y-%m-%d") >="'.to_sql_date($date_start).'"');
        }
        if(!empty($date_end)) {
            $this->db->where(' DATE_FORMAT(tblorders_shop_history.date_create, "%Y-%m-%d") <="'.to_sql_date($date_end).'"');
        }

        $result_history = $this->db->get()->result_array();
        $data_view = [];
        if(!empty($result)) {
            $data_view['info'] = $result;
        }
        if(!empty($result_history)) {
            $data_view['info_history'] = $result_history;
        }
        echo json_encode($data_view);
    }
	
    public function insertOrderHistory($code_orders, $code_supership, $status = "", $hd_fee_stam, $hd_fee, $status_new = "", $hd_fee_stam_new, $hd_fee_new) {
        $this->db->insert('tblorders_shop_history', [
            'code_supership' => $code_supership,
            'code_orders' => $code_orders,
            'status' => $status,
            'hd_fee_stam' => $hd_fee_stam,
            'hd_fee' => $hd_fee,
            'status_new' => $status_new,
            'hd_fee_stam_new' => $hd_fee_stam_new,
            'hd_fee_new' => $hd_fee_new,
            'date_create' => date('Y-m-d H:i:s'),
            'create_by' => get_staff_user_id()
        ]);
        return true;
    }


	
    public function updateShop()
    {
        $id = intval($this->input->post('id'));
        $address_id = htmlspecialchars($this->input->post('address_id'));

        $this->db->where('id', $id);
        $info = $this->db->get('tblcustomers')->result()[0];
        if(!$info){
            echo 2;
            exit();
        }

        $data = array('address_id' => $address_id);
        $this->db->where('id', $id);
        $update = $this->db->update('tblcustomers', $data);
        if($update)
            echo 1;
        else
            echo 0;
    }
}
