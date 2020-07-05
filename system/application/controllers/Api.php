<?php

class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function webhook_ghtk()
    {
        if (isset($_POST)) {
            $json = file_get_contents('php://input');
            $data = json_decode($json);
			foreach($data as $item){
				$item->created = date('Y-m-d H:i:s');
			}
			
            $insert = $this->db->insert_batch('tbl_product_data', json_decode(json_encode($data), true));
            if (!$insert) {
                echo json_encode(array('status' => false));
            } else {
                echo json_encode(array('status' => true));
            }
        } else {
            echo 'Không hỗ trợ phương thức này';
        }
    }

/**
     * Đây là hàm cập nhật thông tin đơn hàng thông qua API của GHTK
     */
    public function cronjob_order_ghtk()
    {
        $this->load->model('orders_change_weight_model');
        $default_data = $query = $this->db->get('tbl_default_mass_volume_ghtk')->result()[0];
        $now = date('Y-m-d H:m:s');

        // array Status in table tbldeclare
        $arrStatus = getArrDataInTbldeclare();
        $dataArrStatus = array();

        foreach ($arrStatus as $status) {
            if (!in_array($status, $dataArrStatus)) {
                array_push($dataArrStatus, "\"" . $status . "\"");
            }
        }

        $sql = 'SELECT 
                    `tblorders_shop`.`id`,
                    `tblorders_shop`.`status`,
                    `tblorders_shop`.`code_ghtk`,
                    `tblorders_shop`.`date_debits`,
                    `tblorders_shop`.`control_date`,
                    `tblorders_shop`.`shop`,
                    `tblorders_shop`.`code_supership`,
                    `tblorders_shop`.`collect`,
                    `tblorders_shop`.`mass`,
                    `tblcustomers`.`group_debits`
                FROM 
                    `tblorders_shop`
                LEFT JOIN `tblcustomers` ON `tblcustomers`.`customer_shop_code` = `tblorders_shop`.`shop`
                WHERE ((`status` NOT IN (' . implode(',', $dataArrStatus) . '))
                OR `status` IS NULL )
                AND `DVVC` = "GHTK"
                AND `date_create` >= "' . date("Y-m-d H:i:s", strtotime("$now - 90 day")) . '"
				AND `date_create` < "' . date("Y-m-d H:i:s", mktime(date('H'), date('i') - 15, date('s'), date('m'), date('d'), date('Y'))) . '" ORDER BY id DESC';


        $list_order = $this->db->query($sql)->result();

        $errors = $success =$deadlineFail= array();

        if (!empty($list_order)) {
            foreach ($list_order as $order) {
                $this->db->where('id', $order->id);
                $info_order = $this->db->get('tblorders_shop')->row();

                if (!in_array($info_order->status, $arrStatus)) {

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
                                'mass_fake' => $order_ghtk['weight'],
                                'last_time_updated' => $order_ghtk['modified'],
                                'delivery_delay_time' => $order_ghtk['modified']
                            );

                            if ($order->mass < $order_ghtk['weight']) {
                                $dataUpdate['mass'] = $order_ghtk['weight'];
                            }

                            if (empty($order->group_debits)) {
                                if (!empty($result_Status->status_debit) && is_null($order->date_debits)) {
                                    $dataUpdate['date_debits'] = date('Y-m-d H:m:s');
                                }
                            } elseif ($order->group_debits == 1) {
                                if (!empty($result_Status->group_debits) && is_null($order->date_debits)) {
                                    $dataUpdate['date_debits'] = date('Y-m-d H:m:s');
                                }
                            }

                            // control_date
                            $status_text = $order_ghtk['status_text'];
                            if (stripos($status_text, 'Đã đối soát') !== false && is_null($order->control_date)) {
                                $dataUpdate['control_date'] = $order_ghtk['modified'];
                            }


                            $this->db->where('id', $order->id);
                            //update deadline
                            $getDbDeadline = $this->getDeadlineByDVVC('GHTK');
                            $checkDbInsideObj = $this->checkDbInsideObjGHTK($getDbDeadline,$order_ghtk,$order->code_ghtk);
                            if($checkDbInsideObj &&$checkDbInsideObj>0|| $checkDbInsideObj==="0"){
                                $dataUpdate['deadline_order']=date('Y-m-d H:i:s',strtotime("+$checkDbInsideObj hour",strtotime($dataUpdate['last_time_updated'])));
                            }else{
                                $dataUpdate['deadline_order']=null;
                                array_push($deadlineFail, array('code_ghtk' => $order->code_ghtk));

                            }




                            $update = $this->db->update('tblorders_shop', $dataUpdate);
                            if (!$update) {
                                array_push($errors, 'Cập nhật thất bại đơn có mã ghtk: ' . $order->code_ghtk);
                            }

                            array_push($success, $order->code_supership);
                        }

                        if ((float)$order->collect != (float)$order_ghtk['pick_money']) {
                            $data_insert_money = [
                                'order_shop_id' => $order->id,
                                'shop_name' => $order->shop,
                                'code' => $order->code_supership,
                                'old_money' => $order->collect,
                                'new_money' => $order_ghtk['pick_money'],
                                'created_date' => date('Y-m-d H:i:s'),
                            ];
                            $this->orders_change_weight_model->insert_money($data_insert_money);
                        }

                        if ($order->mass < $order_ghtk['weight']) {
                            $data_insert = [
                                'order_shop_id' => $order->id,
                                'shop_name' => $order->shop,
                                'code' => $order->code_supership,
                                'old_weight' => $order->mass,
                                'new_weight' => $order_ghtk['weight'],
                                'created_date' => date('Y-m-d H:i:s'),
                            ];
                            $this->orders_change_weight_model->insert($data_insert);
                        }

                    } else {
                        array_push($errors, 'Thông tin đơn hàng ' . $order->code_supership . 'này không tồn tại trên hệ thống.');
                    }
                }
            }

            echo '<div>Số đơn thành công: ' . count($success) . '</div><br>';
            echo '<div>Số đơn thất bại: ' . count($errors) . '</div><br>';
            echo '<div>Số đơn không cập nhật Deadline: ' . count($deadlineFail) . '</div><br>';
            echo '<div>Danh sách đơn thất bại: </div><br>';
            echo '<pre>';
            print_r($errors);
            echo '</pre><br>';
            echo '<div>Danh sách đơn thành công:</div><br>';
            echo '<pre>';
            print_r($success);
            echo '</pre><br>';
            echo '<div>Danh sách không cập nhật deadline:</div><br>';
            echo '<pre>';
            print_r($deadlineFail);
            echo '</pre>';

        } else
            echo 'Không có đơn nào trong khoảng thời gian này';
    }
    public function getDeadlineByDVVC($dvvc=''){
        $sql = "
        SELECT *   FROM `tbldeadline` 
        WHERE id >= 0 ";
        ($dvvc !="")?$sql.="AND dvvc LIKE '%$dvvc%'":"";
        $result = $this->db->query($sql)->result();
        return $result;
    }
    private function checkDbInsideObjGHTK($data,$obj,$code_ghtk){
        $check =false;
        $time ='';
        $maVung =$this->covertSuperShip($code_ghtk);
        foreach($data as $value){
            $name = $value->name;
            $arrName = explode(',',$name);

            foreach ($arrName as $key=> $nameChild){

                $nameChild=ltrim($nameChild);
                $check=$this->checkTextInsideObjGHTK($obj,$nameChild);
                if($check){
                    if ($maVung == "NM"){
                        $time=$value->time_nm;

                    }elseif ($maVung == "LM"){
                        $time=$value->time_lm;
                    }
                }
                else{
                    break;
                }
            }

            if($check){

                break;
            }
        }
        return ($check)?$time:$check;
    }
    private function covertSuperShip($code_supership){
        $arr =explode('.',$code_supership);
        return (stripos($arr[1],"BO")!== false)?"LM":"NM";
    }
    private function checkTextInsideObjGHTK($array,$string){
        $check = false;
        $key = true;


        if (stripos($string,"<>")!== false ){
            $key =false;
            $string = str_replace("<>","",$string);
        }
        if(stripos($array['status_text'],$string)!== false){
            $check =$key;

        }elseif (stripos($array['status'],$string)!== false){
            $check =$key;

        }else{
            if($key ==false){
                $check = true;
            }
        }


        return $check;
    }
    public function add_status()
    {
        $status_ghtk = htmlspecialchars($this->input->post('status_ghtk'));
        $status_change = htmlspecialchars($this->input->post('status_change'));
        $status_debit = intval($this->input->post('status_debit'));
        $active = intval($this->input->post('active'));
        $dvvc = htmlspecialchars($this->input->post('dvvc'));

        $result = array('status' => false, 'error' => '');

        if ($status_change == "" || $status_ghtk == "") {
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
            'status_debit' => $status_debit,
            'dvvc' => $dvvc
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
        $price = $this->input->post('price');
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
            if ($status != 'Hủy') {
                if (is_null($info->date_debits)) {
                    $data['date_debits'] = date('Y-m-d H:i:s');
                }
                $data['last_time_updated'] = date('Y-m-d H:i:s');

            }
        }

        if(is_numeric($price)){
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
        if (!empty($p)) {

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
        if (!empty($date_start)) {
            $this->db->where(' DATE_FORMAT(tblorders_shop_history.date_create, "%Y-%m-%d") >="' . to_sql_date($date_start) . '"');
        }
        if (!empty($date_end)) {
            $this->db->where(' DATE_FORMAT(tblorders_shop_history.date_create, "%Y-%m-%d") <="' . to_sql_date($date_end) . '"');
        }

        $result_history = $this->db->get()->result_array();
        $data_view = [];
        if (!empty($result)) {
            $data_view['info'] = $result;
        }
        if (!empty($result_history)) {
            $data_view['info_history'] = $result_history;
        }
        echo json_encode($data_view);
    }

    public function insertOrderHistory($code_orders, $code_supership, $status = "", $hd_fee_stam, $hd_fee, $status_new = "", $hd_fee_stam_new, $hd_fee_new)
    {
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
        if (!$info) {
            echo 2;
            exit();
        }

        $data = array('address_id' => $address_id);
        $this->db->where('id', $id);
        $update = $this->db->update('tblcustomers', $data);
        if ($update)
            echo 1;
        else
            echo 0;
    }

    /**
     * Convert order
     */
    // Function search order convert
    public function search_convert_order()
    {
        $codeSuppership = htmlspecialchars($this->input->get('code'));
        $this->db->like('code_supership', $codeSuppership);
        $this->db->or_like('phone', $codeSuppership);

//        $this->db->join('tbl_create_order','tbl_create_order.orders_shop_id = tblorders_shop.id');
//        $this->db->select('tblorders_shop.*, tbl_create_order.transport');

        $info = $this->db->get('tblorders_shop')->result();

        echo json_encode(array('status' => true, 'info' => $info));
    }

    /**
     * Đây là hàm thực hiện chuyển đổi đơn vị vận chuyển
     */
    public function convert_order()
    {
        $resultJSON = array('status' => false, 'error' => '');

        // Thông đơn hàng
        $idOrderShop = $this->input->post('idOrderShop');
        $code = $this->input->post('code');
        $shop = $this->input->post('shop');


        // Thông tin đơn vị chuyển đổi
        $dvvcSource = $this->input->post('dvvcSource');
        $dvvcFinsh = $this->input->post('dvvcFinsh');


        // Thông tin khối lượng
        $mass = $this->input->post('mass');
        $mass_fake = $this->input->post('mass_fake');

        // Transport
        $transport = $this->input->post('transport');


        // Lấy thông tin đơn hàng trên bảng orders_shop
        $this->db->where('id', $idOrderShop);
        $info_orders_shop = $this->db->get('tblorders_shop')->row();

        if (!$info_orders_shop) {
            $resultJSON['error'] = 'Không tồn tại đơn hàng nào liên quan';
            echo json_encode($resultJSON);
            die();
        }


        // Lấy thông tin đơn hàng trên bảng create_order
        $this->db->where('orders_shop_id', $idOrderShop);
        $this->db->where('code', $code);
        $info_create_order = $this->db->get('tbl_create_order')->row();


        if (!$info_create_order) {
            $resultJSON['error'] = 'Không có thông tin đơn hàng liên quan';
            echo json_encode($resultJSON);
            die();
        }


        // Thực hiện chuyển đổi
        $result = $this->convertDvvcAPI($dvvcSource, $dvvcFinsh, $info_orders_shop, $info_create_order, $mass, $mass_fake, $transport);

        switch ($result) {
            case 'Insert_in_tblorders_shop_Failed':
            case 'Insert_in_Insert_in_tbl_create_order_Failed_Failed':
                $resultJSON['error'] = 'Tạo đơn mới thất bại.';
                break;
            case 'DVVCnoSupport':
                $resultJSON['error'] = 'Đơn vị vận chuyển này không được hỗ trợ';
                break;
            case 'dataEmpty':
                $resultJSON['error'] = 'Dữ liệu API trả về rỗng';
                break;
            case 'Create_order_on_SPS_Failed':
                $resultJSON['error'] = 'Tạo đơn trên SPS thất bại';
                break;
            case 'Update_order_Failed':
                $resultJSON['error'] = 'Cập nhật đơn hàng thất bại.';
                break;
            case 'Create_order_on_GHTK_Failed':
                $resultJSON['error'] = 'Tạo đơn trên GHTK thất bại';
                break;
            case 'Create_order_on_VTP_Failed':
                $resultJSON['error'] = 'Tạo đơn trên VTP thất bại';
                break;
            case 'Cancel_Order_on_SPS_Failed':
                $resultJSON['error'] = 'Hủy đơn trên SPS thất bại';
                break;
            case 'Cancel_Order_Systems_Failed':
                $resultJSON['error'] = 'Hủy đơn trên hệ thống thất bại';
                break;
            case 'Cancel_Order_on_GHTK_Failed':
                $resultJSON['error'] = 'Hủy đơn hàng trên GHTK thất bại';
                break;
            case 'Cancel_Order_on_VTP_Failed':
                $resultJSON['error'] = 'Hủy đơn hàng trên VTP thất bại';
                break;
            case 'Cancel_Order_on_VNC_Failed':
                $resultJSON['error'] = 'Hủy đơn hàng trên VNC thất bại';
                break;
            case 'Create_order_on_VNC_Failed':
                $resultJSON['error'] = 'Tạo đơn hàng trên VNC thất bại';
                break;
            default:
                $resultJSON['status'] = true;
                $this->db->where('code', $result);
                $resultJSON['info'] = $this->db->get('tbl_create_order')->row();
                break;
        }

        echo json_encode($resultJSON);

    }

    // Function get history convert
    public function get_history()
    {
        $this->db->limit(100);
        $this->db->order_by('id', 'DESC');
        $list = $this->db->get('tbl_history_change')->result();
        echo json_encode(array('list' => $list));
    }

    // Get data
    public function mix_data()
    {
        $table = 'tbl_create_order_ghtk';
        $list_order_ghtk = $this->db->get($table)->result();
        $data = array();
        foreach ($list_order_ghtk as $order) {
            $dataArr = array(
                'customer_id' => $order->customer_id,
                'supership_value' => $order->supership_value,
                'cod' => $order->cod,
                'pickup_address' => $order->pickup_address,
                'pickup_province' => $order->pickup_province,
                'pickup_district' => $order->pickup_district,
                'pickup_commune' => $order->pickup_commune,
                'pickup_phone' => $order->pickup_phone,
                'name' => $order->name,
                'phone' => $order->phone,
                'sphone' => $order->sphone,
                'address' => $order->address,
                'province' => $order->province,
                'district' => $order->district,
                'commune' => $order->commune,
                'amount' => $order->amount,
                'weight' => $order->weight,
                'volume' => $order->volume,
                'soc' => $order->soc,
                'note' => $order->note,
                'service' => $order->service,
                'config' => $order->config,
                'payer' => $order->payer,
                'product_type' => $order->product_type,
                'product' => $order->product,
                'barter' => $order->barter,
                'value' => $order->value,
                'code' => $order->code,
                'created' => $order->created,
                'user_created' => $order->user_created,
                'status_cancel' => $order->status_cancel,
                'the_fee_bearer' => $order->the_fee_bearer,
                'transport' => $order->transport,
                'ghtk' => $order->ghtk,
                'token_ghtk' => $order->token_ghtk,
                'orders_shop_id' => $order->orders_shop_id,
                'dvvc' => 'GHTK'
            );
            array_push($data, $dataArr);
        }
        if (!$this->db->insert_batch('tbl_create_order', $data)) {
            echo json_encode(array('status' => false));
            die();
        }
        echo json_encode(array('status' => true));
    }

    // Confirm order

/**
     * Đây là hàm dùng xác nhận 1 đơn hàng
     * @author Lediun Software - https://ema.lediun.com/ - Dịch vụ email marketing số 1 tại Việt Nam. Đăng ký ngay nhận ngay ưu đãi.
     */
    public function comfirm_order()
    {
        $id = intval($this->input->post('id'));
        $code = htmlspecialchars($this->input->post('code'));
        $dvvc = htmlspecialchars($this->input->post('dvvc'));
        $mass = intval($this->input->post('mass'));
        $mass_fake = intval($this->input->post('mass_fake'));
        $transport = intval($this->input->post('transpot'));

        $result = array('status' => false, 'error' => '');

        // get info order
        $this->db->where('id', $id);
        $this->db->where('required_code', $code);
        $info_orders = $this->db->get('tbl_create_order')->result();
        if (!$info_orders) {
            $result['error'] = 'noOrder';
            echo json_encode($result);
            die();
        }
        $info_order = $info_orders[0];
        $this->db->where('id', $info_order->customer_id);
        $info_customer = $this->db->get('tblcustomers')->result();

        // data insert table tblorders_shop
        $hd_fee_stam = $this->_checkAndReplaceStam(
            $info_customer[0]->customer_shop_code,
            $info_order->province,
            $info_order->district,
            $mass,
            $info_order->amount,
            $info_order->value,
            $info_order->price
        );
        $data_shop = array(
            'shop' => $info_customer[0]->customer_shop_code,
            'code_orders' => $info_order->soc,
            'status' => 'Đã Nhập Kho',
            'date_create' => date('Y-m-d H:i:s'),
            'mass' => $mass,
            'collect' => $info_order->amount,
            'value' => $info_order->value,
            'prepay' => 0,
            'receiver' => $info_order->name,
            'phone' => $info_order->phone,
            'address' => $info_order->address,
            'ward' => $info_order->commune,
            'district' => $info_order->district,
            'city' => $info_order->province,
            'note' => $info_order->note,
            'warehouses' => $info_order->pickup_address,
            'product' => $info_order->product,
            'city_send' => 'Tỉnh Hải Dương',
            'last_time_updated' => date('Y-m-d H:i:s'),
            'is_hd_branch' => 1,
            'payer' => ($info_order->payer == 1) ? 'Người Gửi' : '',
            'sale' => 0,
            'pack_data' => ($info_order->service == 1) ? 'Tốc Hành' : 'Tiết Kiệm',
            'pay_refund' => 0,
            'status_over' => '',
            'status_delay' => '',
            'warehouse_send' => get_option('warehouse_send'),
            'region_id' => $info_order->region_id,
            'mass_fake' => $mass_fake,
            'hd_fee_stam' => $hd_fee_stam,
            'required_code' => $info_order->required_code
        );

        $value = $info_order->value;
        if ($value < 3000000) {
            $valueNew = rand(2500000, 3000000);
            if ($valueNew > $value) {
                $number2 = substr($valueNew, 3, 4);
                $value = $valueNew - $number2;
            }
        }

        if ($dvvc == 'SPS') {// SPS
            $data_shop['DVVC'] = 'SPS';
            $this->db->insert('tblorders_shop', $data_shop);
            $id_order_shop = $this->db->insert_id();

            if ($id_order_shop) {
                $data_sps = array(
                    'pickup_phone' => $info_customer[0]->customer_phone,
                    'pickup_address' => $info_order->pickup_address,
                    'pickup_province' => $info_order->pickup_province,
                    'pickup_district' => $info_order->pickup_district,
                    'name' => $info_order->name,
                    'phone' => $info_order->phone,
                    'address' => $info_order->address,
                    'province' => $info_order->province,
                    'district' => $info_order->district,
                    'amount' => $info_order->amount,
                    'weight' => $mass_fake,
                    'service' => $info_order->service,
                    'config' => $info_order->config,
                    'payer' => $info_order->payer,
                    'product_type' => $info_order->product_type,
                    'product' => $info_order->product,
                    'sphone' => $info_order->sphone,
                    'commune' => $info_order->commune,
                    'value' => $value,
                    'note' => $info_order->note,
                    'barter' => $info_order->barter,
                    'soc' => randerCode(3) . '-' . $info_order->soc
                );

                $responseJSON_sps = self::api_sps($data_sps, $info_customer[0]->token_customer, true);
                if (empty($responseJSON_sps)) {
                    $result['error'] = 'ConnectionFailed';
                    fnLog('Không có giá trả về của SPS');
                    echo json_encode($result);
                    die();
                }
                $response = json_decode($responseJSON_sps, true);
                if ($response['status'] != 'Success') {
                    // $data_order = array('status_cancel' => 1);

                    // $this->db->where('id', $id);
                    // $this->db->where('required_code', $code);
                    // $this->db->update('tbl_create_order', $data_order);

                    $this->db->delete('tblorders_shop', ['id' => $id_order_shop]);
                    $result['error'] = 'Error';
                    fnLog($responseJSON_sps);
                    echo json_encode($result);
                    die();
                }

                $data_order = array(
                    'orders_shop_id' => $id_order_shop,
                    'dvvc' => 'SPS',
                    'code' => $response['results']['code'],
                    'user_created' => get_staff_user_id(),
                    'mass_fake' => $mass_fake,
                    'weight' => $mass,
                    'supership_value' => $hd_fee_stam,
                );


                $this->db->where('id', $id);
                $this->db->where('required_code', $code);
                $update = $this->db->update('tbl_create_order', $data_order);

                $this->db->where('id', $id_order_shop);
                $update_order = $this->db->update('tblorders_shop', array('code_supership' => $response['results']['code']));

                if ($update && $update_order) {
                    $result['status'] = true;
                    $result['code'] = $response['results']['code'];
                    $result['id'] = $id;
                    echo json_encode($result);
                    die();
                }
                $result['error'] = 'UpdateFailed';
                echo json_encode($result);
                die();
            }

        } elseif ($dvvc == 'GHTK') {// GHTK
            $default_data = $query = $this->db->get('tbl_default_mass_volume_ghtk')->result()[0];

            // Get info warehouse send
            $this->db->where('is_default', true);
            $info_warehouse_send = $this->db->get('tbl_warehouse_send')->row();

            $data_shop['DVVC'] = 'GHTK';
            $this->db->insert('tblorders_shop', $data_shop);
            $id_order_shop = $this->db->insert_id();

            if ($id_order_shop) {
                $default_mass_volume_ghtk = $this->db->get('tbl_default_mass_volume_ghtk')->row();
                $codeNew = $default_mass_volume_ghtk->code . randerCode(6);

                $data_ghtk = new stdClass();
                $product = new stdClass();
                $data_ghtk->products = [];
                $product->name = $info_order->product;
                $product->weight = (float)$mass_fake / 1000;
                $product->quantity = 1;

                array_push($data_ghtk->products, $product);
                $data_ghtk->order = new stdClass();
                $data_ghtk->order->id = $codeNew;
//                $data_ghtk->order->pick_address_id = $info_customer[0]->address_id;
                $data_ghtk->order->pick_name = $info_customer[0]->customer_shop_code;
                // $data_ghtk->order->pick_address = $info_order->pickup_address;
                // $data_ghtk->order->pick_province = $info_order->pickup_province;
                // $data_ghtk->order->pick_district = $info_order->pickup_district;
                $data_ghtk->order->pick_tel = $info_warehouse_send->phone;
                $data_ghtk->order->tel = $info_order->phone;
                $data_ghtk->order->name = $info_order->name;
                $data_ghtk->order->address = $info_order->address;
                $data_ghtk->order->province = $info_order->province;
                $data_ghtk->order->district = $info_order->district;
                $data_ghtk->order->ward = $info_order->commune;
                $data_ghtk->order->is_freeship = 1;
                $data_ghtk->order->pick_money = $info_order->amount;
                $data_ghtk->order->note = $info_order->note;
                $data_ghtk->order->transport = $transport == 1 || $transport == 0 ? 'road' : 'fly';
                $data_ghtk->order->use_return_address = 0;
                $data_ghtk->order->value = $value;

                // More value
                $data_ghtk->order->hamlet = "Hải dương";
                // Warehouse
                $data_ghtk->order->pick_address = $info_warehouse_send->nameAddress;
                $data_ghtk->order->pick_province = $info_warehouse_send->province;
                $data_ghtk->order->pick_district = $info_warehouse_send->district;
                $data_ghtk->order->pick_ward = $info_warehouse_send->commune;

                $responseJSON_ghtk = self::api_ghtk($data_ghtk, $default_data->token_ghtk, true);

                if (empty($responseJSON_ghtk)) {
                    $result['error'] = 'ConnectionFailed';
                    fnLog('Không có giá trả về của GHTK');
                    echo json_encode($result);
                    die();
                }

                $res = json_decode($responseJSON_ghtk, true);
                if ($res['success'] != true) {
                    // $data_order = array('status_cancel' => 1);

                    // $this->db->where('id', $id);
                    // $this->db->where('required_code', $code);
                    // $this->db->update('tbl_create_order', $data_order);

                    $this->db->delete('tblorders_shop', ['id' => $id_order_shop]);
                    $result['error'] = 'Error';
                    fnLog($responseJSON_ghtk);
                    echo json_encode($result);
                    die();
                }
                $codeArr = explode('.', $res['order']['label']);
                $code_order = $codeNew . '.' . $codeArr[count($codeArr) - 1];
                $data_order = array(
                    'orders_shop_id' => $id_order_shop,
                    'dvvc' => 'GHTK',
                    'token_ghtk' => $default_data->token_ghtk,
                    'code' => $code_order,
                    'user_created' => get_staff_user_id(),
                    'mass_fake' => $mass_fake,
                    'weight' => $mass,
                    'supership_value' => $hd_fee_stam
                );

                if (!empty($this->input->post('transpot'))) {
                    $data_order['transport'] = $this->input->post('transpot') == 1 ? 'road' : 'fly';
                }

                $this->db->where('id', $id);
                $this->db->where('required_code', $code);
                $update = $this->db->update('tbl_create_order', $data_order);

                $data_order_shop = array(
                    'code_supership' => $code_order,
                    'pay_transport' => $res['order']['fee'],
                    'insurance' => $res['order']['insurance_fee'],
                    'code_ghtk' => $res['order']['label']
                );

                $this->db->where('id', $id_order_shop);
                $update_order = $this->db->update('tblorders_shop', $data_order_shop);

                if ($update && $update_order) {
                    $result['status'] = true;
                    $result['code'] = $code_order;
                    $result['id'] = $id;
                    echo json_encode($result);
                    die();
                }
                $result['error'] = 'UpdateFailed';
                echo json_encode($result);
                die();

            }
        } elseif ($dvvc == 'VTP') {
            $data_default = $this->db->get('tbl_default_mass_volume_vpost')->row();
            $dataLogin = array(
                "USERNAME" => $data_default->username,
                "PASSWORD" => base64_decode($data_default->password)
            );
            $token = loginVP($dataLogin);

            $commue = explode(',', $info_order->pickup_address);
            $convertData = convertData($info_order->pickup_province, $info_order->pickup_district, $commue[count($commue) - 1], $info_order->province, $info_order->district, $info_order->commune);


            $data_shop['DVVC'] = 'VTP';
            $this->db->insert('tblorders_shop', $data_shop);
            $id_order_shop = $this->db->insert_id();

            if ($id_order_shop) {

                $codeNew = CODE_VTP . randerCode(2) . code(6);

                // SENDER_DISTRICT
                if ($convertData['sender_province'] == $convertData['receiver_province']) {
                    $ORDER_SERVICE = 'PHS';
                } else {
                    $ORDER_SERVICE = ($info_order->service == 1) ? 'VTK' : 'SCOD';
                }

                $dataAPI = array(
                    'ORDER_NUMBER' => $id,
                    'GROUPADDRESS_ID' => $info_customer[0]->address_id_vpost,
                    'CUS_ID' => '',
                    'SENDER_FULLNAME' => $info_customer[0]->customer_shop_code,
                    'SENDER_ADDRESS' => $info_order->pickup_address,
                    'SENDER_PHONE' => $info_order->pickup_phone,
                    'SENDER_EMAIL' => '',
                    'SENDER_WARD' => $convertData['sender_wards'],
                    'SENDER_DISTRICT' => $convertData['sender_district'],
                    'SENDER_PROVINCE' => $convertData['sender_province'],
                    'RECEIVER_FULLNAME' => $info_order->name,
                    'RECEIVER_ADDRESS' => $info_order->address,
                    'RECEIVER_PHONE' => $info_order->phone,
                    'RECEIVER_EMAIL' => '',
                    'RECEIVER_WARD' => $convertData['receiver_ward'],
                    'RECEIVER_DISTRICT' => $convertData['receiver_district'],
                    'RECEIVER_PROVINCE' => $convertData['receiver_province'],
                    'ORDER_PAYMENT' => 3,
                    'ORDER_SERVICE' => $ORDER_SERVICE,
                    'ORDER_SERVICE_ADD' => '',
                    'ORDER_VOUCHER' => '',
                    'ORDER_NOTE' => $info_order->note,
                    'MONEY_COLLECTION' => $info_order->amount,
                    'MONEY_TOTALFEE' => '',
                    'MONEY_FEECOD' => 0,
                    'MONEY_FEEVAS' => 0,
                    'MONEY_FEEINSURRANCE' => 0,
                    'MONEY_FEE' => 0,
                    'MONEY_FEEOTHER' => 0,
                    'MONEY_TOTALVAT' => 0,
                    'MONEY_TOTAL' => 0,
                    'PRODUCT_TYPE' => 'HH',
                    'PRODUCT_NAME' => $info_order->product,
                    'PRODUCT_DESCRIPTION' => '',
                    'PRODUCT_WEIGHT' => $mass_fake,
                    'PRODUCT_QUANTITY' => 1,
                    'PRODUCT_PRICE' => (empty($info_order->value)) ? rand(2000000, 3000000) : $info_order->value
                );

                $resultAPI = $this->_api_viettel($dataAPI, $token, 'https://partner.viettelpost.vn/v2/order/createOrder');

                if ($resultAPI['status'] != 200) {
                    // $data_order = array('status_cancel' => 1);

                    // $this->db->where('id', $id);
                    // $this->db->where('required_code', $code);
                    // $this->db->update('tbl_create_order', $data_order);

                    $this->db->delete('tblorders_shop', ['id' => $id_order_shop]);
                    $result['error'] = 'Error';
                    fnLog(json_encode($resultAPI));
                    echo json_encode($result);
                    die();
                }
                $resultData = $resultAPI['data'];
                $codeVTP = $codeNew . '.' . $resultData['ORDER_NUMBER'];

                $data_order = array(
                    'orders_shop_id' => $id_order_shop,
                    'dvvc' => 'VTP',
                    'code' => $codeVTP,
                    'user_created' => get_staff_user_id(),
                    'mass_fake' => $mass_fake,
                    'weight' => $mass,
                    'supership_value' => $resultData['MONEY_TOTAL']
                );

                $this->db->where('id', $id);
                $this->db->where('required_code', $code);
                $update = $this->db->update('tbl_create_order', $data_order);

                $data_order_shop = array(
                    'code_supership' => $codeVTP,
                    'pay_transport' => $resultData['MONEY_TOTAL'],
                    'code_ghtk' => $resultData['ORDER_NUMBER']
                );

                $this->db->where('id', $id_order_shop);
                $update_order = $this->db->update('tblorders_shop', $data_order_shop);

                if ($update && $update_order) {
                    $result['status'] = true;
                    $result['code'] = $codeVTP;
                    $result['id'] = $id;
                    echo json_encode($result);
                    die();
                }
                $result['error'] = 'UpdateFailed';
                echo json_encode($result);
                die();

            }

        } elseif ($dvvc == 'VNC') {
            $data_default = $this->db->get('tbl_default_mass_volume_vnc')->row();
            $dataLogin = array(
                "USERNAME" => $data_default->username,
                "PASSWORD" => base64_decode($data_default->password)
            );

            $token = loginVNC($dataLogin, URL_VNC . 'User/Login');


            $data_shop['DVVC'] = 'VNC';
            $this->db->insert('tblorders_shop', $data_shop);
            $id_order_shop = $this->db->insert_id();

            if ($id_order_shop) {
                $codeNew = $data_default->code . randerCode(6);

                $warehouser = $this->db->get('tbl_warehouse_send')->row();

                $dataAPI = array(
                    'Code' => $codeNew,
                    'ProductName' => $info_order->product,
                    'CollectAmount' => $info_order->amount,
                    'JourneyType' => 1,
                    'ServiceId' => $info_order->service == 1 ? 12491 : 12490,
                    'Weight' => $mass_fake,
                    'Note' => $info_order->note,
                    'NumberOfProducts' => 1,
                    'SourceCity' => $warehouser->province,
                    'SourceDistrict' => $warehouser->district,
                    'SourceWard' => $warehouser->commune,
                    'SourceAddress' => $warehouser->nameAddress,
                    'SourceName' => $info_customer[0]->customer_shop_code,
                    'SourcePhoneNumber' => $warehouser->phone,
                    'DestCity' => $info_order->province,
                    'DestDistrict' => $info_order->district,
                    'DestWard' => $info_order->commune,
                    'DestAddress' => $info_order->commune . ', ' . $info_order->district . ', ' . $info_order->province,
                    'DestName' => $info_order->name,
                    'DestPhoneNumber' => $info_order->phone,
                    'Width' => 0,
                    'Height' => 0,
                    'Length' => 0,
                    'ProductPrice' => $value
                );

                $resultAPI = $this->_api_vnc($dataAPI, $token, URL_VNC . 'Order/Add');

                if ($resultAPI['Result'] === 2) {
                    $this->db->delete('tblorders_shop', ['id' => $id_order_shop]);
                    $result['error'] = 'Error';
                    fnLog(json_encode($resultAPI));
                    echo json_encode($result);
                    die();
                }

                $codeVTP = $codeNew . '.' . $resultAPI['Code'];

                $data_order = array(
                    'orders_shop_id' => $id_order_shop,
                    'code' => $codeVTP,
                    'dvvc' => 'VNC',
                    'user_created' => get_staff_user_id(),
                    'mass_fake' => $mass_fake,
                    'weight' => $mass
                );

                $this->db->where('id', $id);
                $this->db->where('required_code', $code);
                $update = $this->db->update('tbl_create_order', $data_order);

                $data_order_shop = array(
                    'code_supership' => $codeVTP,
                    'code_ghtk' => $resultAPI['Code']
                );

                $this->db->where('id', $id_order_shop);
                $update_order = $this->db->update('tblorders_shop', $data_order_shop);

                if ($update && $update_order) {
                    $result['status'] = true;
                    $result['code'] = $codeVTP;
                    $result['id'] = $id;
                    echo json_encode($result);
                    die();
                }
                $result['error'] = 'UpdateFailed';
                echo json_encode($result);
                die();

            }

        } elseif ($dvvc == 'NB') {
//            $default_data = $query = $this->db->get('tbl_default_mass_volume_nb')->result()[0];
            $data_shop['DVVC'] = 'NB';
            $this->db->insert('tblorders_shop', $data_shop);
            $id_order_shop = $this->db->insert_id();

            if ($id_order_shop) {
                $codeNew = CODE_NB . '.' . randerCode(2) . code(6);

                $data_order = array(
                    'orders_shop_id' => $id_order_shop,
                    'code' => $codeNew,
                    'dvvc' => 'NB',
                    'user_created' => get_staff_user_id(),
                    'mass_fake' => $mass_fake,
                    'weight' => $mass
                );


                $this->db->where('id', $id);
                $this->db->where('required_code', $code);
                $update = $this->db->update('tbl_create_order', $data_order);

                $data_order_shop = array(
                    'code_supership' => $codeNew
                );

                $this->db->where('id', $id_order_shop);
                $update_order = $this->db->update('tblorders_shop', $data_order_shop);

                if ($update && $update_order) {
                    $result['status'] = true;
                    $result['code'] = $codeNew;
                    $result['id'] = $id;
                    echo json_encode($result);
                    die();
                }
                $result['error'] = 'UpdateFailed';
                echo json_encode($result);
                die();
            }
        }

    }

    public function removeOrder()
    {
        $ids = $this->input->post('ids');
        $dataArr = array();
        foreach ($ids as $id) {
            $data = array(
                'id' => $id,
                'status_cancel' => 1
            );
            array_push($dataArr, $data);
        }
        if (!$this->db->update_batch('tbl_create_order', $dataArr, 'id')) {
            echo json_encode(array('status' => false));
            die();
        }
        echo json_encode(array('status' => true));
    }

    /**
     * Đây là hàm dùng để xác nhận nhiều đơn hàng
     * @author Lediun Software - https://sendingreen.tk
     */
    public function comfirm_orders()
    {
        $ids = htmlspecialchars($this->input->post('id'));
        $dvvc = htmlspecialchars($this->input->post('dvvc'));
        $mass = intval($this->input->post('mass'));
        $mass_fake = intval($this->input->post('mass_fake'));
        $transport = intval($this->input->post('transport'));

        $result = array('status' => false, 'error' => '');

        // get info order
        $sql = 'SELECT `tbl_create_order`.*, `tblcustomers`.`customer_shop_name`,`tblcustomers`.`address_id_vpost`,`tblcustomers`.`customer_shop_code`, `tblcustomers`.`customer_phone`,`tblcustomers`.`token_customer`
                FROM `tbl_create_order`
                JOIN `tblcustomers` ON `tblcustomers`.`id` = `tbl_create_order`.`customer_id`
                WHERE `tbl_create_order`.`id` IN(' . base64_decode($ids) . ')';
        $list_orders = $this->db->query($sql)->result();

        if (empty($list_orders)) {
            $result['error'] = 'noOrders';
            echo json_encode($result);
            die();
        }
        $errors = $success = array();

        if ($dvvc == 'SPS') {
            foreach ($list_orders as $order) {
                $value = $order->value;
                if ($value < 3000000) {
                    $valueNew = rand(2500000, 3000000);
                    if ($valueNew > $value) {
                        $number2 = substr($valueNew, 3, 4);
                        $value = $valueNew - $number2;
                    }
                }

                $hd_fee_stam = $this->_checkAndReplaceStam(
                    $order->customer_shop_code,
                    $order->province,
                    $order->district,
                    $order->weight,
                    $order->amount,
                    $order->value,
                    $order->price
                );

                $data_order_shop = array(
                    'shop' => $order->customer_shop_code,
                    'code_orders' => $order->soc,
                    'status' => 'Đã Nhập Kho',
                    'date_create' => date('Y-m-d H:i:s'),
                    'mass' => $order->weight,
                    'collect' => $order->amount,
                    'value' => $order->value,
                    'prepay' => 0,
                    'receiver' => $order->name,
                    'phone' => $order->phone,
                    'address' => $order->address,
                    'ward' => $order->commune,
                    'district' => $order->district,
                    'city' => $order->province,
                    'note' => $order->note,
                    'warehouses' => $order->pickup_address,
                    'product' => $order->product,
                    'city_send' => 'Tỉnh Hải Dương',
                    'hd_fee_stam' => $hd_fee_stam,
                    'last_time_updated' => date('Y-m-d H:i:s'),
                    'DVVC' => 'SPS',
                    'is_hd_branch' => 1,
                    'payer' => ($order->payer == 1) ? 'Người Gửi' : '',
                    'sale' => 0,
                    'pack_data' => ($order->service == 1) ? 'Tốc Hành' : 'Tiết Kiệm',
                    'pay_refund' => 0,
                    'status_over' => '',
                    'status_delay' => '',
                    'warehouse_send' => get_option('warehouse_send'),
                    'region_id' => $order->region_id,
                    'mass_fake' => $mass_fake,
                    'required_code' => $order->required_code
                );

                $this->db->insert('tblorders_shop', $data_order_shop);
                $id_order_shop = $this->db->insert_id();
                if ($id_order_shop) {
                    $data = array(
                        'pickup_phone' => $order->customer_phone,
                        'pickup_address' => $order->pickup_address,
                        'pickup_province' => $order->pickup_province,
                        'pickup_district' => $order->pickup_district,
                        'name' => $order->name,
                        'phone' => $order->phone,
                        'address' => $order->address,
                        'province' => $order->province,
                        'district' => $order->district,
                        'amount' => $order->amount,
                        'weight' => $mass_fake,
                        'service' => $order->service,
                        'config' => $order->config,
                        'payer' => $order->payer,
                        'product_type' => $order->product_type,
                        'product' => $order->product,
                        'sphone' => $order->sphone,
                        'commune' => $order->commune,
                        'value' => $value,
                        'note' => $order->note,
                        'barter' => $order->barter,
                        'soc' => randerCode(3) . '-' . $order->soc
                    );

                    $res = self::api_sps($data, $order->token_customer, true);
                    $res = json_decode($res, true);
                    if ($res['status'] !== 'Success') {
                        // update order on table tbl_create_order
                        // $data_order = array('status_cancel' => 1);
                        // $this->db->where('id', $order->id);
                        // $this->db->update('tbl_create_order', $data_order);


                        // Delete order on table tblorders_shop
                        $this->db->delete('tblorders_shop', ['id' => $id_order_shop]);
                        fnLog(json_encode($res) . ' với id đơn SPS là ' . $order->id);
                        array_push($errors, 'Created order failed');
                    } else {
                        // update table tbl_create_order
                        $data_order = array(
                            'code' => $res['results']['code'],
                            'dvvc' => 'SPS',
                            'user_created' => get_staff_user_id(),
                            'orders_shop_id' => $id_order_shop,
                            'mass_fake' => $mass_fake,
                            'weight' => $mass,
                            'supership_value' => $hd_fee_stam
                        );
                        $this->db->where('id', $order->id);
                        $update = $this->db->update('tbl_create_order', $data_order);

                        // table tblorders_shop
                        $this->db->where('id', $id_order_shop);
                        $update_order = $this->db->update('tblorders_shop', array('code_supership' => $res['results']['code']));

                        if ($update && $update_order) {
                            array_push($success, $res['results']['code']);
                        }
                    }
                }
            }
            if (!empty($errors)) {
                $result['totalError'] = count($errors);
            }
            if (!empty($success)) {
                $result['totalSuccess'] = count($success);
                $result['codes'] = implode(',', $success);
            }

            $result['status'] = true;
            echo json_encode($result);
        } elseif ($dvvc == 'GHTK') {
            // get token ghtk
            $default_data = $query = $this->db->get('tbl_default_mass_volume_ghtk')->result()[0];

            // Get info warehouse send
            $this->db->where('is_default', true);
            $info_warehouse_send = $this->db->get('tbl_warehouse_send')->row();

            foreach ($list_orders as $order) {

                $value = $order->value;
                if ($value < 3000000) {
                    $valueNew = rand(2500000, 3000000);
                    if ($valueNew > $value) {
                        $number2 = substr($valueNew, 3, 4);
                        $value = $valueNew - $number2;
                    }
                }

                $hd_fee_stam = $this->_checkAndReplaceStam(
                    $order->customer_shop_code,
                    $order->province,
                    $order->district,
                    $order->weight,
                    $order->amount,
                    $order->value,
                    $order->price
                );

                $data_order_shop = array(
                    'shop' => $order->customer_shop_code,
                    'code_orders' => $order->soc,
                    'status' => 'Đã Nhập Kho',
                    'date_create' => date('Y-m-d H:i:s'),
                    'mass' => $order->weight,
                    'collect' => $order->amount,
                    'value' => $order->value,
                    'prepay' => 0,
                    'receiver' => $order->name,
                    'phone' => $order->phone,
                    'address' => $order->address,
                    'ward' => $order->commune,
                    'district' => $order->district,
                    'city' => $order->province,
                    'note' => $order->note,
                    'warehouses' => $order->pickup_address,
                    'product' => $order->product,
                    'city_send' => 'Tỉnh Hải Dương',
                    'hd_fee_stam' => $hd_fee_stam,
                    'last_time_updated' => date('Y-m-d H:i:s'),
                    'DVVC' => 'GHTK',
                    'is_hd_branch' => 1,
                    'payer' => ($order->payer == 1) ? 'Người Gửi' : '',
                    'sale' => 0,
                    'pack_data' => ($order->service == 1) ? 'Tốc Hành' : 'Tiết Kiệm',
                    'pay_refund' => 0,
                    'status_over' => '',
                    'status_delay' => '',
                    'warehouse_send' => get_option('warehouse_send'),
                    'region_id' => $order->region_id,
                    'mass_fake' => $mass_fake,
                    'required_code' => $order->required_code
                );
                $this->db->insert('tblorders_shop', $data_order_shop);
                $id_order_shop = $this->db->insert_id();

                if ($id_order_shop) {

                    $codeNew = $default_data->code . randerCode(6);

                    $data_ghtk = new stdClass();
                    $product = new stdClass();
                    $data_ghtk->products = [];
                    $product->name = $order->product;
                    $product->weight = (float)$mass_fake / 1000;
                    $product->quantity = 1;

                    array_push($data_ghtk->products, $product);
                    $data_ghtk->order = new stdClass();
                    $data_ghtk->order->id = $codeNew;
//                    $data_ghtk->order->pick_address_id = $order->address_id;
                    $data_ghtk->order->pick_name = $order->customer_shop_code;
                    // $data_ghtk->order->pick_address = $order->pickup_address;
                    // $data_ghtk->order->pick_province = $order->pickup_province;
                    // $data_ghtk->order->pick_district = $order->pickup_district;
                    $data_ghtk->order->pick_tel = $info_warehouse_send->phone;
                    $data_ghtk->order->tel = $order->phone;
                    $data_ghtk->order->name = $order->name;
                    $data_ghtk->order->address = $order->address;
                    $data_ghtk->order->province = $order->province;
                    $data_ghtk->order->district = $order->district;
                    $data_ghtk->order->ward = $order->commune;
                    $data_ghtk->order->is_freeship = 1;
                    $data_ghtk->order->pick_money = $order->amount;
                    $data_ghtk->order->note = $order->note;
                    $data_ghtk->order->transport = $transport == 1 || $transport == 0 ? 'road' : 'fly';
                    $data_ghtk->order->use_return_address = 0;
                    $data_ghtk->order->value = $value;

                    // More value
                    $data_ghtk->order->hamlet = "Hải dương";
                    // Warehouse
                    $data_ghtk->order->pick_address = $info_warehouse_send->nameAddress;
                    $data_ghtk->order->pick_province = $info_warehouse_send->province;
                    $data_ghtk->order->pick_district = $info_warehouse_send->district;
					$data_ghtk->order->pick_ward = $info_warehouse_send->commune;

                    $resp = self::api_ghtk($data_ghtk, $default_data->token_ghtk, true);
                    $resp = json_decode($resp, true);
                    if ($resp['success'] !== true) {
                        // update order on table tbl_create_order
                        // $data_order = array('status_cancel' => 1);
                        // $this->db->where('id', $order->id);
                        // $this->db->update('tbl_create_order', $data_order);

                        // Delete order on table tblorders_shop
                        $this->db->delete('tblorders_shop', ['id' => $id_order_shop]);
                        fnLog(json_encode($resp) . ' với id đơn GHTK là ' . $order->id);
                        array_push($errors, 'Created order failed');
                    } else {
                        $codeArr = explode('.', $resp['order']['label']);
                        $code_order = $codeNew . '.' . $codeArr[count($codeArr) - 1];

                        $this->db->set('user_created', get_staff_user_id());
                        $this->db->set('orders_shop_id', $id_order_shop);
                        $this->db->set('code', $code_order);
                        $this->db->set('dvvc', 'GHTK');
                        $this->db->set('mass_fake', $mass_fake);
                        $this->db->set('supership_value', $hd_fee_stam);
                        $this->db->set('token_ghtk', $default_data->token_ghtk);

                        if (!empty($this->input->post('transport'))) {
                            $this->db->set('transport', $this->input->post('transport') == 1 ? 'road' : 'fly');
                        }

                        $this->db->where('id', $order->id);
                        $update = $this->db->update('tbl_create_order');

                        // Update table tblorders_shop

                        $this->db->set('code_supership', $code_order);
                        $this->db->set('pay_transport', $resp['order']['fee']);
                        $this->db->set('insurance', $resp['order']['insurance_fee']);
                        $this->db->set('code_ghtk', $resp['order']['label']);
                        $this->db->where('id', $id_order_shop);
                        $update_order = $this->db->update('tblorders_shop');

                        if ($update && $update_order) {
                            array_push($success, $id_order_shop);
                        }
                    }
                }
            }

            if (!empty($errors)) {
                $result['totalError'] = count($errors);
            }
            if (!empty($success)) {
                $result['totalSuccess'] = count($success);
                $result['codes'] = $ids;
            }

            $result['status'] = true;
            echo json_encode($result);
        } elseif ($dvvc == 'VTP') {
            $data_default = $this->db->get('tbl_default_mass_volume_vpost')->row();
            $dataLogin = array(
                "USERNAME" => $data_default->username,
                "PASSWORD" => base64_decode($data_default->password)
            );

            foreach ($list_orders as $order) {
                $hd_fee_stam = $this->_checkAndReplaceStam(
                    $order->customer_shop_code,
                    $order->province,
                    $order->district,
                    $order->weight,
                    $order->amount,
                    $order->value,
                    $order->price
                );

                $data_order_shop = array(
                    'shop' => $order->customer_shop_code,
                    'code_orders' => $order->soc,
                    'status' => 'Đã Nhập Kho',
                    'date_create' => date('Y-m-d H:i:s'),
                    'mass' => $order->weight,
                    'collect' => $order->amount,
                    'value' => $order->value,
                    'prepay' => 0,
                    'receiver' => $order->name,
                    'phone' => $order->phone,
                    'address' => $order->address,
                    'ward' => $order->commune,
                    'district' => $order->district,
                    'city' => $order->province,
                    'note' => $order->note,
                    'warehouses' => $order->pickup_address,
                    'product' => $order->product,
                    'city_send' => 'Tỉnh Hải Dương',
                    'hd_fee_stam' => $hd_fee_stam,
                    'last_time_updated' => date('Y-m-d H:i:s'),
                    'DVVC' => 'VTP',
                    'is_hd_branch' => 1,
                    'payer' => ($order->payer == 1) ? 'Người Gửi' : '',
                    'sale' => 0,
                    'pack_data' => ($order->service == 1) ? 'Tốc Hành' : 'Tiết Kiệm',
                    'pay_refund' => 0,
                    'status_over' => '',
                    'status_delay' => '',
                    'warehouse_send' => get_option('warehouse_send'),
                    'region_id' => $order->region_id,
                    'mass_fake' => $mass_fake
                );
                $this->db->insert('tblorders_shop', $data_order_shop);
                $id_order_shop = $this->db->insert_id();

                $commue = explode(',', $order->pickup_address);
                $convertData = convertData($order->pickup_province, $order->pickup_district, $commue[count($commue) - 1], $order->province, $order->district, $order->commune);


                if ($id_order_shop) {

                    $codeNew = CODE_VTP . randerCode(2) . code(6);

                    if ($convertData['sender_province'] == $convertData['receiver_province']) {
                        $ORDER_SERVICE = 'PHS';
                    } else {
                        $ORDER_SERVICE = ($order->service == 1) ? 'VTK' : 'SCOD';
                    }

                    $dataAPIVTP = array(
                        'ORDER_NUMBER' => $order->id,
                        'GROUPADDRESS_ID' => $order->address_id_vpost,
                        'CUS_ID' => '',
                        'SENDER_FULLNAME' => $order->customer_shop_code,
                        'SENDER_ADDRESS' => $order->pickup_address,
                        'SENDER_PHONE' => $order->pickup_phone,
                        'SENDER_EMAIL' => '',
                        'SENDER_WARD' => $convertData['sender_wards'],
                        'SENDER_DISTRICT' => $convertData['sender_district'],
                        'SENDER_PROVINCE' => $convertData['sender_province'],
                        'RECEIVER_FULLNAME' => $order->name,
                        'RECEIVER_ADDRESS' => $order->address,
                        'RECEIVER_PHONE' => $order->phone,
                        'RECEIVER_EMAIL' => '',
                        'RECEIVER_WARD' => $convertData['receiver_ward'],
                        'RECEIVER_DISTRICT' => $convertData['receiver_district'],
                        'RECEIVER_PROVINCE' => $convertData['receiver_province'],
                        'ORDER_PAYMENT' => 3,
                        'ORDER_SERVICE' => $ORDER_SERVICE,
                        'ORDER_SERVICE_ADD' => '',
                        'ORDER_VOUCHER' => '',
                        'ORDER_NOTE' => trim($order->note),
                        'MONEY_COLLECTION' => $order->amount,
                        'MONEY_TOTALFEE' => '',
                        'MONEY_FEECOD' => $order->amount,
                        'MONEY_FEEVAS' => 0,
                        'MONEY_FEEINSURRANCE' => 0,
                        'MONEY_FEE' => 0,
                        'MONEY_FEEOTHER' => 0,
                        'MONEY_TOTALVAT' => 0,
                        'MONEY_TOTAL' => 0,
                        'PRODUCT_TYPE' => 'HH',
                        'PRODUCT_NAME' => $order->product,
                        'PRODUCT_DESCRIPTION' => '',
                        'PRODUCT_WEIGHT' => $mass_fake,
                        'PRODUCT_QUANTITY' => 1,
                        'PRODUCT_PRICE' => (empty($order->value)) ? rand(2000000, 3000000) : $order->value
                    );

                    $token = loginVP($dataLogin);

                    $resultJSON = $this->_api_viettel($dataAPIVTP, $token, 'https://partner.viettelpost.vn/v2/order/createOrder');

                    if ($resultJSON['status'] != 200) {
                        // $data_order = array('status_cancel' => 1);

                        // $this->db->where('id', $order->id);
                        // $this->db->where('required_code', $order->required_code);
                        // $this->db->update('tbl_create_order', $data_order);

                        $this->db->delete('tblorders_shop', ['id' => $id_order_shop]);
                        fnLog(json_encode($resultJSON) . ' với id đơn VTP là ' . $order->id);
                        array_push($errors, 'Created order failed');
                    } else {
                        $resultData = $resultJSON['data'];
                        $codeVTP = $codeNew . '.' . $resultData['ORDER_NUMBER'];

                        $data_order = array(
                            'orders_shop_id' => $id_order_shop,
                            'dvvc' => 'VTP',
                            'code' => $codeVTP,
                            'user_created' => get_staff_user_id(),
                            'mass_fake' => $mass_fake,
                            'weight' => $mass,
                            'supership_value' => $resultData['MONEY_TOTAL']
                        );

                        $this->db->where('id', $order->id);
                        $this->db->where('required_code', $order->required_code);
                        $update = $this->db->update('tbl_create_order', $data_order);

                        $data_order_shop = array(
                            'code_supership' => $codeVTP,
                            'pay_transport' => $resultData['MONEY_TOTAL'],
                            'code_ghtk' => $resultData['ORDER_NUMBER']
                        );

                        $this->db->where('id', $id_order_shop);
                        $update_order = $this->db->update('tblorders_shop', $data_order_shop);

                        if ($update && $update_order) {
                            array_push($success, $id_order_shop);
                        }
                    }

                }
            }

            if (!empty($errors)) {
                $result['totalError'] = count($errors);
            }
            if (!empty($success)) {
                $result['totalSuccess'] = count($success);
                $result['codes'] = $ids;
            }

            $result['status'] = true;
            echo json_encode($result);
        } elseif ($dvvc == 'VNC') {
            $data_default = $this->db->get('tbl_default_mass_volume_vnc')->row();
            $dataLogin = array(
                "USERNAME" => $data_default->username,
                "PASSWORD" => base64_decode($data_default->password)
            );

            $token = loginVNC($dataLogin, URL_VNC . 'User/Login');
            $warehouser = $this->db->get('tbl_warehouse_send')->row();

            foreach ($list_orders as $order) {
                $hd_fee_stam = $this->_checkAndReplaceStam(
                    $order->customer_shop_code,
                    $order->province,
                    $order->district,
                    $order->weight,
                    $order->amount,
                    $order->value,
                    $order->price
                );

                $value = $order->value;
                if ($value < 3000000) {
                    $valueNew = rand(2500000, 3000000);
                    if ($valueNew > $value) {
                        $number2 = substr($valueNew, 3, 4);
                        $value = $valueNew - $number2;
                    }
                }

                $data_order_shop = array(
                    'shop' => $order->customer_shop_code,
                    'code_orders' => $order->soc,
                    'status' => 'Đã Nhập Kho',
                    'date_create' => date('Y-m-d H:i:s'),
                    'mass' => $order->weight,
                    'collect' => $order->amount,
                    'value' => $order->value,
                    'prepay' => 0,
                    'receiver' => $order->name,
                    'phone' => $order->phone,
                    'address' => $order->address,
                    'ward' => $order->commune,
                    'district' => $order->district,
                    'city' => $order->province,
                    'note' => $order->note,
                    'warehouses' => $order->pickup_address,
                    'product' => $order->product,
                    'city_send' => 'Tỉnh Hải Dương',
                    'hd_fee_stam' => $hd_fee_stam,
                    'last_time_updated' => date('Y-m-d H:i:s'),
                    'DVVC' => 'VNC',
                    'is_hd_branch' => 1,
                    'payer' => ($order->payer == 1) ? 'Người Gửi' : '',
                    'sale' => 0,
                    'pack_data' => ($order->service == 1) ? 'Tốc Hành' : 'Tiết Kiệm',
                    'pay_refund' => 0,
                    'status_over' => '',
                    'status_delay' => '',
                    'warehouse_send' => get_option('warehouse_send'),
                    'region_id' => $order->region_id,
                    'mass_fake' => $mass_fake
                );
                $this->db->insert('tblorders_shop', $data_order_shop);
                $id_order_shop = $this->db->insert_id();

                if ($id_order_shop) {

                    $codeNew = $data_default->code . randerCode(6);

                    $dataAPI = array(
                        'Code' => $codeNew,
                        'ProductName' => $order->product,
                        'CollectAmount' => $order->amount,
                        'JourneyType' => 1,
                        'ServiceId' => $order->service == 1 ? 12491 : 12490,
                        'Weight' => $mass_fake,
                        'Note' => $order->note,
                        'NumberOfProducts' => 1,
                        'SourceCity' => $warehouser->province,
                        'SourceDistrict' => $warehouser->district,
                        'SourceWard' => $warehouser->commune,
                        'SourceAddress' => $warehouser->nameAddress,
                        'SourceName' => $order->customer_shop_code,
                        'SourcePhoneNumber' => $warehouser->phone,
                        'DestCity' => $order->province,
                        'DestDistrict' => $order->district,
                        'DestWard' => $order->commune,
                        'DestAddress' => $order->commune . ', ' . $order->district . ', ' . $order->province,
                        'DestName' => $order->name,
                        'DestPhoneNumber' => $order->phone,
                        'Width' => 0,
                        'Height' => 0,
                        'Length' => 0,
                        'ProductPrice' => $value
                    );

                    $resultAPI = $this->_api_vnc($dataAPI, $token, URL_VNC . 'Order/Add');

                    if ($resultAPI['Result'] === 2) {
                        $this->db->delete('tblorders_shop', ['id' => $id_order_shop]);
                        $result['error'] = 'Error';
                        fnLog(json_encode($resultAPI));
                        echo json_encode($result);
                        die();
                    }

                    $codeVTP = $codeNew . '.' . $resultAPI['Code'];

                    $data_order = array(
                        'orders_shop_id' => $id_order_shop,
                        'code' => $codeVTP,
                        'dvvc' => 'VNC',
                        'user_created' => get_staff_user_id(),
                        'mass_fake' => $mass_fake,
                        'weight' => $mass
                    );

                    $this->db->where('id', $order->id);
                    $update = $this->db->update('tbl_create_order', $data_order);

                    $data_order_shop = array(
                        'code_supership' => $codeVTP,
                        'code_ghtk' => $resultAPI['Code']
                    );

                    $this->db->where('id', $id_order_shop);
                    $update_order = $this->db->update('tblorders_shop', $data_order_shop);

                    if ($update && $update_order) {
                        array_push($success, $id_order_shop);
                    }

                }

            }

            if (!empty($errors)) {
                $result['totalError'] = count($errors);
            }
            if (!empty($success)) {
                $result['totalSuccess'] = count($success);
                $result['codes'] = $ids;
            }

            $result['status'] = true;
            echo json_encode($result);
        } elseif ($dvvc == 'NB') {
            foreach ($list_orders as $order) {
                $hd_fee_stam = $this->_checkAndReplaceStam(
                    $order->customer_shop_code,
                    $order->province,
                    $order->district,
                    $order->weight,
                    $order->amount,
                    $order->value,
                    $order->price
                );

                $data_order_shop = array(
                    'shop' => $order->customer_shop_code,
                    'code_orders' => $order->soc,
                    'status' => 'Đã Nhập Kho',
                    'date_create' => date('Y-m-d H:i:s'),
                    'mass' => $order->weight,
                    'collect' => $order->amount,
                    'value' => $order->value,
                    'prepay' => 0,
                    'receiver' => $order->name,
                    'phone' => $order->phone,
                    'address' => $order->address,
                    'ward' => $order->commune,
                    'district' => $order->district,
                    'city' => $order->province,
                    'note' => $order->note,
                    'warehouses' => $order->pickup_address,
                    'product' => $order->product,
                    'city_send' => 'Tỉnh Hải Dương',
                    'hd_fee_stam' => $hd_fee_stam,
                    'last_time_updated' => date('Y-m-d H:i:s'),
                    'DVVC' => 'NB',
                    'is_hd_branch' => 1,
                    'payer' => ($order->payer == 1) ? 'Người Gửi' : '',
                    'sale' => 0,
                    'pack_data' => ($order->service == 1) ? 'Tốc Hành' : 'Tiết Kiệm',
                    'pay_refund' => 0,
                    'status_over' => '',
                    'status_delay' => '',
                    'warehouse_send' => get_option('warehouse_send'),
                    'region_id' => $order->region_id,
                    'mass_fake' => $mass_fake,
                    'required_code' => $order->required_code
                );

                $this->db->insert('tblorders_shop', $data_order_shop);
                $id_order_shop = $this->db->insert_id();
                if ($id_order_shop) {
                    $codeNew = CODE_NB . '.' . randerCode(2) . code(6);
                    // update table tbl_create_order
                    $data_order = array(
                        'code' => $codeNew,
                        'dvvc' => 'NB',
                        'user_created' => get_staff_user_id(),
                        'orders_shop_id' => $id_order_shop,
                        'mass_fake' => $mass_fake,
                        'weight' => $mass,
                        'supership_value' => $hd_fee_stam
                    );
                    $this->db->where('id', $order->id);
                    $update = $this->db->update('tbl_create_order', $data_order);

                    // table tblorders_shop
                    $this->db->where('id', $id_order_shop);
                    $update_order = $this->db->update('tblorders_shop', array('code_supership' => $codeNew));

                    if ($update && $update_order) {
                        array_push($success, $codeNew);
                    }
                }
            }

            if (!empty($errors)) {
                $result['totalError'] = count($errors);
            }
            if (!empty($success)) {
                $result['totalSuccess'] = count($success);
                $result['codes'] = $ids;
            }

            $result['status'] = true;
            echo json_encode($result);
        }

    }

    // Viettel Post
    public function get_addressId()
    {
        $data_default = $this->db->get('tbl_default_mass_volume_vpost')->row();
        $result = array('status' => false, 'error' => '');
        $dataLogin = array(
            "USERNAME" => $data_default->username,
            "PASSWORD" => base64_decode($data_default->password)
        );
        $token = loginVP($dataLogin);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://partner.viettelpost.vn/v2/user/listInventory",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Token: " . $token
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);
        if ($response['status'] == 200) {
            $result['status'] = true;
            $result['list'] = $response['data'];
        } else {
            $result['error'] = $response['message'];
        }
        echo json_encode($result);
    }


    public function set_warehouse()
    {
        $id_default = $this->input->post('id_default');
        $address_default = $this->input->post('address_default');
        $phone_default = $this->input->post('phone_default');
        $province_name = $this->input->post('province_name');
        $district_name = $this->input->post('district_name');
        $commune_name = $this->input->post('commune_name');
        $is_default = $this->input->post('is_default');

        $result = array('status' => false, 'error' => '');

        $data = array(
            'nameAddress' => $address_default,
            'phone' => $phone_default,
            'province' => $province_name,
            'district' => $district_name,
            'commune' => $commune_name,
            'is_default' => $is_default
        );

        if (!empty($is_default)) {
            $this->db->where('is_default', 1);
            $info = $this->db->get('tbl_warehouse_send')->row();
            if (!empty($info)) {
                $data_update = array('is_default' => 0);
                $this->db->where('id', $info->id);
                $this->db->update('tbl_warehouse_send', $data_update);
            }
        }

        if (!empty($id_default)) {
            $this->db->where('id', $id_default);
            if (!$this->db->update('tbl_warehouse_send', $data)) {
                $result['error'] = 'Cập nhật thất bại';
                echo json_encode($result);
                die();
            }

            $result['status'] = true;
            $result['message'] = 'Cập nhật thành công';
        } else {
            if (!$this->db->insert('tbl_warehouse_send', $data)) {
                $result['error'] = 'Thêm mới thất bại';
                echo json_encode($result);
                die();
            }
            $result['status'] = true;
            $result['message'] = 'Thêm mới thành công';
        }
        echo json_encode($result);
    }
    /**
     * ===============================================================================
     */
    /**
     * Convert order
     * @param $dvvcSource là tên đơn vị vận chuyển nguồn
     * @param $dvvcFinsh là tên đơn vị vận chuyển sẽ được chuyển đến
     * @param $info_order là thông tin đơn hàng được lấy từ bảng tblorders_shop
     * @param $info_create_order là thông tin đơn hàng được lấy từ bảng tbl_create_order
     * @param $mass là khối lượng thực
     * @param $mass_fake là khối lượng ảo
     * @param $transport là phương thức vận chuyển
     * @author Lediun Software - https://sendingreen.tk/
     * @email: contact@sendingreen.tk
     */
    private function convertDvvcAPI($dvvcSource, $dvvcFinsh, $info_order, $info_create_order, $mass, $mass_fake, $transport)
    {
        // Lấy thông tin mặc định
        $this->db->where('customer_shop_code', $info_order->shop);
        $info_customers = $this->db->get('tblcustomers')->row();


        // Get info warehouse send
        $this->db->where('is_default', true);
        $info_warehouse_send = $this->db->get('tbl_warehouse_send')->row();

        switch ($info_create_order->status_cancel) {
            case 1:

                $required_code = $info_create_order->required_code;

                // Tạo đơn hàng mới trên bảng orders_shop
                // Khởi tạo dữ liệu
                $data_orders_shop = array(
                    'shop' => $info_order->shop,
                    'code_orders' => $info_create_order->soc,
                    'status' => 'Đã Nhập Kho',
                    'date_create' => date('Y-m-d H:i:s'),
                    'mass' => $mass,
                    'collect' => $info_create_order->amount,
                    'value' => $info_create_order->value,
                    'prepay' => 0,
                    'receiver' => $info_create_order->name,
                    'phone' => $info_create_order->phone,
                    'address' => $info_create_order->address,
                    'ward' => $info_create_order->commune,
                    'district' => $info_create_order->district,
                    'city' => $info_create_order->province,
                    'note' => $info_create_order->note,
                    'warehouses' => $info_create_order->pickup_address,
                    'product' => $info_create_order->product,
                    'city_send' => 'Tỉnh Hải Dương',
                    'hd_fee_stam' => $info_create_order->supership_value,
                    'last_time_updated' => date('Y-m-d H:i:s'),
                    'DVVC' => $dvvcFinsh,
                    'is_hd_branch' => 1,
                    'payer' => ($info_create_order->payer == 1) ? 'Người Gửi' : '',
                    'sale' => 0,
                    'pack_data' => ($info_create_order->service == 1) ? 'Tốc Hành' : 'Tiết Kiệm',
                    'pay_refund' => 0,
                    'status_over' => 0,
                    'status_delay' => 0,
                    'warehouse_send' => get_option('warehouse_send'),
                    'mass_fake' => $mass_fake,
                    'required_code' => $required_code,
                    'region_id' => $info_create_order->region_id
                );

                $this->db->insert('tblorders_shop', $data_orders_shop);
                $id_orders_shop = $this->db->insert_id();

                if (!$id_orders_shop) {
                    fnLog('Tạo đơn mới trên bảng tblorders_shop sử dụng chức năng chuyển đổi đơn vị vận chuyển thất bại.');
                    return 'Insert_in_tblorders_shop_Failed';
                }

                // Tạo đơn hàng mới trên bảng create_order
                // Khởi tạo dữ liệu
                $data_create_order = array(
                    'customer_id' => $info_create_order->customer_id,
                    'supership_value' => $info_create_order->supership_value,
                    'cod' => $info_create_order->cod,
                    'pickup_address' => $info_create_order->pickup_address,
                    'pickup_province' => $info_create_order->pickup_province,
                    'pickup_district' => $info_create_order->pickup_district,
                    'pickup_commune' => $info_create_order->pickup_commune,
                    'pickup_phone' => $info_create_order->pickup_phone,
                    'name' => $info_create_order->name,
                    'phone' => $info_create_order->phone,
                    'sphone' => $info_create_order->sphone,
                    'address' => $info_create_order->address,
                    'province' => $info_create_order->province,
                    'district' => $info_create_order->district,
                    'commune' => $info_create_order->commune,
                    'amount' => $info_create_order->amount,
                    'weight' => $mass,
                    'volume' => $info_create_order->volume,
                    'soc' => $info_create_order->soc,
                    'note' => $info_create_order->note,
                    'service' => $info_create_order->service,
                    'config' => $info_create_order->config,
                    'payer' => $info_create_order->payer,
                    'product_type' => $info_create_order->product_type,
                    'product' => $info_create_order->product,
                    'barter' => $info_create_order->barter,
                    'value' => $info_create_order->value,
                    'code' => '',
                    'created' => '',
                    'user_created' => get_staff_user_id(),
                    'status_cancel' => 0,
                    'the_fee_bearer' => $info_create_order->the_fee_bearer,
                    'transport' => $transport == 0 || $transport == 1 ? 'road' : 'fly',
                    'ghtk' => $info_create_order->ghtk,
                    'token_ghtk' => $info_create_order->token_ghtk,
                    'orders_shop_id' => $id_orders_shop,
                    'dvvc' => $dvvcFinsh,
                    'required_code' => $required_code,
                    'mass_fake' => $mass_fake
                );

                $this->db->insert('tbl_create_order', $data_create_order);
                $id_create_order = $this->db->insert_id();

                if (!$id_create_order) {
                    fnLog('Tạo đơn mới trên bảng tbl_create_order sử dụng chức năng chuyển đổi đơn vị vận chuyển thất bại.');
                    return 'Insert_in_tbl_create_order_Failed';
                }

                $value = $info_create_order->value;
                if ($value < 3000000) {
                    $valueNew = rand(2500000, 3000000);
                    if ($valueNew > $value) {
                        $number2 = substr($valueNew, 3, 4);
                        $value = $valueNew - $number2;
                    }
                }

                switch ($dvvcFinsh) {
                    case 'SPS':

                        // Khởi tạo dữ liệu gửi lên API SPS
                        $data_sps = array(
                            'pickup_phone' => $info_customers->customer_phone,
                            'pickup_address' => $info_create_order->pickup_address,
                            'pickup_province' => $info_create_order->pickup_province,
                            'pickup_district' => $info_create_order->pickup_district,
                            'name' => $info_create_order->name,
                            'phone' => $info_create_order->phone,
                            'address' => $info_create_order->address,
                            'province' => $info_create_order->province,
                            'district' => $info_create_order->district,
                            'amount' => $info_create_order->amount,
                            'weight' => $mass_fake,
                            'service' => $info_create_order->service,
                            'config' => $info_create_order->config,
                            'payer' => $info_create_order->payer,
                            'product_type' => $info_create_order->product_type,
                            'product' => $info_create_order->product,
                            'sphone' => $info_create_order->sphone,
                            'commune' => $info_create_order->commune,
                            'value' => $value,
                            'note' => $info_create_order->note,
                            'barter' => $info_create_order->barter,
                            'soc' => randerCode(3) . '-' . $info_create_order->soc
                        );

                        return $this->_create_order_SPS($data_sps, $info_customers->token_customer, $id_create_order, $id_orders_shop, $info_create_order, $mass, $dvvcSource, $dvvcFinsh);
                        break;
                    case 'GHTK':
                        $default_data = $query = $this->db->get('tbl_default_mass_volume_ghtk')->row();
                        $codeNew = $default_data->code . randerCode(6);


                        // Khởi tạo dữ liệu gửi lên API GHTK
                        $data_ghtk = new stdClass();
                        $product = new stdClass();
                        $data_ghtk->products = [];

                        $product->name = $info_create_order->product;
                        $product->weight = (float)$mass_fake / 1000;
                        $product->quantity = 1;
                        array_push($data_ghtk->products, $product);
                        $data_ghtk->order = new stdClass();
                        $data_ghtk->order->id = $codeNew;
//                        $data_ghtk->order->pick_address_id = $info_customers->address_id;
                        $data_ghtk->order->pick_name = $info_customers->customer_shop_code;
                        // $data_ghtk->order->pick_address = $data_create_order['pickup_address'];
                        // $data_ghtk->order->pick_province = $data_create_order['pickup_province'];
                        // $data_ghtk->order->pick_district = $data_create_order['pickup_district'];
                        $data_ghtk->order->pick_tel = $info_warehouse_send->phone;
                        $data_ghtk->order->tel = $data_create_order['phone'];
                        $data_ghtk->order->name = $data_create_order['name'];
                        $data_ghtk->order->address = $data_create_order['address'];
                        $data_ghtk->order->province = $data_create_order['province'];
                        $data_ghtk->order->district = $data_create_order['district'];
                        $data_ghtk->order->ward = $data_create_order['commune'];
                        $data_ghtk->order->is_freeship = 1;
                        $data_ghtk->order->pick_money = $data_create_order['amount'];
                        $data_ghtk->order->note = $data_create_order['note'];
                        $data_ghtk->order->transport = $data_create_order['transport'];
                        $data_ghtk->order->use_return_address = 0;
                        $data_ghtk->order->value = $value;

                        // More value
                        $data_ghtk->order->hamlet = "Hải dương";

                        // Warehouse
                        $data_ghtk->order->pick_address = $info_warehouse_send->nameAddress;
                        $data_ghtk->order->pick_province = $info_warehouse_send->province;
                        $data_ghtk->order->pick_district = $info_warehouse_send->district;
						$data_ghtk->order->pick_ward = $info_warehouse_send->commune;

                        return $this->_create_order_GHTK($data_ghtk, $default_data->token_ghtk, $id_create_order, $id_orders_shop, $info_create_order, $mass, $dvvcSource, $dvvcFinsh, $codeNew);
                        break;
                    case 'VTP':

                        $codeNew = CODE_VTP . randerCode(2) . code(6);

                        $data_default = $this->db->get('tbl_default_mass_volume_vpost')->row();
                        $dataLogin = array(
                            "USERNAME" => $data_default->username,
                            "PASSWORD" => base64_decode($data_default->password)
                        );
                        $token = loginVP($dataLogin);

                        $commue = explode(',', $info_create_order->pickup_address);
                        $convertData = convertData($info_create_order->pickup_province, $info_create_order->pickup_district, $commue[count($commue) - 1], $info_create_order->province, $info_create_order->district, $info_create_order->commune);

                        if ($convertData['sender_province'] == $convertData['receiver_province']) {
                            $ORDER_SERVICE = 'PHS';
                        } else {
                            $ORDER_SERVICE = ($info_create_order->service == 1) ? 'VTK' : 'SCOD';
                        }

                        // Tạo dữ liệu gửi lên API của VTP
                        $dataAPI = array(
                            'ORDER_NUMBER' => $id_create_order,
                            'GROUPADDRESS_ID' => $info_customers->address_id_vpost,
                            'CUS_ID' => '',
                            'SENDER_FULLNAME' => $info_customers->customer_shop_code,
                            'SENDER_ADDRESS' => $info_create_order->pickup_address,
                            'SENDER_PHONE' => $info_create_order->pickup_phone,
                            'SENDER_EMAIL' => '',
                            'SENDER_WARD' => $convertData['sender_wards'],
                            'SENDER_DISTRICT' => $convertData['sender_district'],
                            'SENDER_PROVINCE' => $convertData['sender_province'],
                            'RECEIVER_FULLNAME' => $info_create_order->name,
                            'RECEIVER_ADDRESS' => $info_create_order->address,
                            'RECEIVER_PHONE' => $info_create_order->phone,
                            'RECEIVER_EMAIL' => '',
                            'RECEIVER_WARD' => $convertData['receiver_ward'],
                            'RECEIVER_DISTRICT' => $convertData['receiver_district'],
                            'RECEIVER_PROVINCE' => $convertData['receiver_province'],
                            'ORDER_PAYMENT' => 3,
                            'ORDER_SERVICE' => $ORDER_SERVICE,
                            'ORDER_SERVICE_ADD' => '',
                            'ORDER_VOUCHER' => '',
                            'ORDER_NOTE' => $info_create_order->note,
                            'MONEY_COLLECTION' => $info_create_order->amount,
                            'MONEY_TOTALFEE' => '',
                            'MONEY_FEECOD' => 0,
                            'MONEY_FEEVAS' => 0,
                            'MONEY_FEEINSURRANCE' => 0,
                            'MONEY_FEE' => 0,
                            'MONEY_FEEOTHER' => 0,
                            'MONEY_TOTALVAT' => 0,
                            'MONEY_TOTAL' => 0,
                            'PRODUCT_TYPE' => 'HH',
                            'PRODUCT_NAME' => $info_create_order->product,
                            'PRODUCT_DESCRIPTION' => '',
                            'PRODUCT_WEIGHT' => $mass_fake,
                            'PRODUCT_QUANTITY' => 1,
                            'PRODUCT_PRICE' => $value
                        );

                        return $this->_create_order_VTP($dataAPI, $token, $id_create_order, $id_orders_shop, $info_create_order, $mass, $dvvcSource, $dvvcFinsh, $codeNew);

                        break;
                    case 'VNC':

                        $data_default = $this->db->get('tbl_default_mass_volume_vnc')->row();
                        $dataLogin = array(
                            "USERNAME" => $data_default->username,
                            "PASSWORD" => base64_decode($data_default->password)
                        );

                        $token = loginVNC($dataLogin, URL_VNC . 'User/Login');

                        $codeNew = $data_default->code . randerCode(6);

                        $warehouser = $this->db->get('tbl_warehouse_send')->row();

                        $dataAPI = array(
                            'Code' => $codeNew,
                            'ProductName' => $info_create_order->product,
                            'CollectAmount' => $info_create_order->amount,
                            'JourneyType' => 1,
                            'ServiceId' => $transport == 0 || $transport == 1 ? 12491 : 12490,
                            'Weight' => $mass_fake,
                            'Note' => $info_create_order->note,
                            'NumberOfProducts' => 1,
                            'SourceCity' => $warehouser->province,
                            'SourceDistrict' => $warehouser->district,
                            'SourceWard' => $warehouser->commune,
                            'SourceAddress' => $warehouser->nameAddress,
                            'SourceName' => $info_customers->customer_shop_code,
                            'SourcePhoneNumber' => $warehouser->phone,
                            'DestCity' => $info_create_order->province,
                            'DestDistrict' => $info_create_order->district,
                            'DestWard' => $info_create_order->commune,
                            'DestAddress' => $info_create_order->commune . ', ' . $info_create_order->district . ', ' . $info_create_order->province,
                            'DestName' => $info_create_order->name,
                            'DestPhoneNumber' => $info_create_order->phone,
                            'Width' => 0,
                            'Height' => 0,
                            'Length' => 0,
                            'ProductPrice' => $value
                        );

                        return $this->_create_order_VNC($dataAPI, $token, $id_create_order, $id_orders_shop, $info_create_order, $mass, $dvvcSource, $dvvcFinsh, $codeNew);

                        break;
                    case 'NB':

                        $today = date('Y-m-d H:i:s');
                        $code = CODE_NB.'.'.randerCode(2).code(6);

                        // Tạo dữ liệu cập nhật bảng create_order
                        $data_update_create_order = array(
                            'created' => $today,
                            'code' => $code
                        );

                        $this->db->where('id', $id_create_order);
                        $update = $this->db->update('tbl_create_order', $data_update_create_order);


                        // Khởi tạo biến dữ liệu cập nhật vào bảng tblorders_shop
                        $data_update_orders_shop = array(
                            'code_supership' => $code
                        );
                        $this->db->where('id', $id_orders_shop);
                        $update_orders_shop = $this->db->update('tblorders_shop', $data_update_orders_shop);

                        if ($update && $update_orders_shop) {
                            // Lưu lịch sử chuyển đổi
                            $data_history = array(
                                'date_create' => date('Y-m-d H:i:s'),
                                'code_old' => $info_create_order->code,
                                'code_new' => $code,
                                'mass' => $mass,
                                'dvvc_source' => $dvvcSource,
                                'dvvc_finsh' => $dvvcFinsh,
                                'orders_id' => $id_create_order
                            );

                            $this->db->insert('tbl_history_change', $data_history);
                            fnLog('Mã đơn hàng mới: ' . $code);
                            return $code;
                        }

                        $message = '';
                        if (!$update)
                            $message = 'Cập nhật đơn hàng id ' . $id_create_order . ' trên bảng tbl_create_order thông qua chức năng chuyển đổi đơn hàng thất bại.';
                        if (!$update_orders_shop)
                            $message = 'Cập nhật đơn hàng id ' . $id_orders_shop . ' trên bảng tblorders_shop thông qua chức năng chuyển đổi đơn hàng thất bại.';
                        fnLog($message);
                        return 'Update_order_Failed';

                        break;
                    default:
                        return 'DVVCnoSupport';
                        break;
                }

                break;

            default:

                switch ($dvvcSource) {
                    case 'SPS':

                        // Hủy đơn bên SPS
                        $dataAPI = array('code' => $info_create_order->code);
                        $resultJSON = self::api_sps($dataAPI, $info_customers->token_customer);

                        if (empty($resultJSON)) {
                            fnLog('Dữ liệu API Hủy của SPS trả về rỗng khi sử dụng chức năng chuyển đổi đơn vị vận chuyển. Vui lòng kiểm tra lại');
                            return 'dataEmpty';
                        }

                        $resultArr = json_decode($resultJSON, true);

                        if ($resultArr['status'] != 'Success') {
                            fnLog('Hủy đơn hàng trên SPS bằng chức năng chuyển đổi đơn vị vận chuyển thất bại. Với nội dung trả về như sau: ' . $resultJSON);
                            return 'Cancel_Order_on_SPS_Failed';
                        }

                        // Hủy đơn hàng trên hệ thống
                        $data_cancel_create_order = array('status_cancel' => 1);
                        $this->db->where('id', $info_create_order->id);
                        $cancel = $this->db->update('tbl_create_order', $data_cancel_create_order);

                        $data_cancel_orders_shop = array('status' => 'Hủy');
                        $this->db->where('id', $info_order->id);
                        $cancel_orders_shop = $this->db->update('tblorders_shop', $data_cancel_orders_shop);

                        if ($cancel && $cancel_orders_shop) {
                            $required_code = $info_create_order->required_code;

                            // Tạo đơn hàng mới trên bảng orders_shop
                            // Khởi tạo dữ liệu
                            $data_orders_shop = array(
                                'shop' => $info_order->shop,
                                'code_orders' => $info_create_order->soc,
                                'status' => 'Đã Nhập Kho',
                                'date_create' => date('Y-m-d H:i:s'),
                                'mass' => $mass,
                                'collect' => $info_create_order->amount,
                                'value' => $info_create_order->value,
                                'prepay' => 0,
                                'receiver' => $info_create_order->name,
                                'phone' => $info_create_order->phone,
                                'address' => $info_create_order->address,
                                'ward' => $info_create_order->commune,
                                'district' => $info_create_order->district,
                                'city' => $info_create_order->province,
                                'note' => $info_create_order->note,
                                'warehouses' => $info_create_order->pickup_address,
                                'product' => $info_create_order->product,
                                'city_send' => 'Tỉnh Hải Dương',
                                'hd_fee_stam' => $info_create_order->supership_value,
                                'last_time_updated' => date('Y-m-d H:i:s'),
                                'DVVC' => $dvvcFinsh,
                                'is_hd_branch' => 1,
                                'payer' => ($info_create_order->payer == 1) ? 'Người Gửi' : '',
                                'sale' => 0,
                                'pack_data' => ($info_create_order->service == 1) ? 'Tốc Hành' : 'Tiết Kiệm',
                                'pay_refund' => 0,
                                'status_over' => 0,
                                'status_delay' => 0,
                                'warehouse_send' => get_option('warehouse_send'),
                                'mass_fake' => $mass_fake,
                                'required_code' => $required_code,
                                'region_id' => $info_create_order->region_id
                            );

                            $this->db->insert('tblorders_shop', $data_orders_shop);
                            $id_orders_shop = $this->db->insert_id();

                            if (!$id_orders_shop) {
                                fnLog('Tạo đơn mới trên bảng tblorders_shop sử dụng chức năng chuyển đổi đơn vị vận chuyển thất bại.');
                                return 'Insert_in_tblorders_shop_Failed';
                            }

                            // Tạo đơn hàng mới trên bảng create_order
                            // Khởi tạo dữ liệu
                            $data_create_order = array(
                                'customer_id' => $info_create_order->customer_id,
                                'supership_value' => $info_create_order->supership_value,
                                'cod' => $info_create_order->cod,
                                'pickup_address' => $info_create_order->pickup_address,
                                'pickup_province' => $info_create_order->pickup_province,
                                'pickup_district' => $info_create_order->pickup_district,
                                'pickup_commune' => $info_create_order->pickup_commune,
                                'pickup_phone' => $info_create_order->pickup_phone,
                                'name' => $info_create_order->name,
                                'phone' => $info_create_order->phone,
                                'sphone' => $info_create_order->sphone,
                                'address' => $info_create_order->address,
                                'province' => $info_create_order->province,
                                'district' => $info_create_order->district,
                                'commune' => $info_create_order->commune,
                                'amount' => $info_create_order->amount,
                                'weight' => $mass,
                                'volume' => $info_create_order->volume,
                                'soc' => $info_create_order->soc,
                                'note' => $info_create_order->note,
                                'service' => $info_create_order->service,
                                'config' => $info_create_order->config,
                                'payer' => $info_create_order->payer,
                                'product_type' => $info_create_order->product_type,
                                'product' => $info_create_order->product,
                                'barter' => $info_create_order->barter,
                                'value' => $info_create_order->value,
                                'code' => '',
                                'created' => '',
                                'user_created' => get_staff_user_id(),
                                'status_cancel' => 0,
                                'the_fee_bearer' => $info_create_order->the_fee_bearer,
                                'transport' => $transport == 0 || $transport == 1 ? 'road' : 'fly',
                                'ghtk' => $info_create_order->ghtk,
                                'token_ghtk' => $info_create_order->token_ghtk,
                                'orders_shop_id' => $id_orders_shop,
                                'dvvc' => $dvvcFinsh,
                                'required_code' => $required_code,
                                'mass_fake' => $mass_fake
                            );

                            $this->db->insert('tbl_create_order', $data_create_order);
                            $id_create_order = $this->db->insert_id();

                            if (!$id_create_order) {
                                fnLog('Tạo đơn mới trên bảng tbl_create_order sử dụng chức năng chuyển đổi đơn vị vận chuyển thất bại.');
                                return 'Insert_in_tbl_create_order_Failed';
                            }

                            $value = $info_create_order->value;
                            if ($value < 3000000) {
                                $valueNew = rand(2500000, 3000000);
                                if ($valueNew > $value) {
                                    $number2 = substr($valueNew, 3, 4);
                                    $value = $valueNew - $number2;
                                }
                            }


                            switch ($dvvcFinsh) {

                                case 'SPS':

                                    // Khởi tạo dữ liệu gửi lên API SPS
                                    $data_sps = array(
                                        'pickup_phone' => $info_customers->customer_phone,
                                        'pickup_address' => $info_create_order->pickup_address,
                                        'pickup_province' => $info_create_order->pickup_province,
                                        'pickup_district' => $info_create_order->pickup_district,
                                        'name' => $info_create_order->name,
                                        'phone' => $info_create_order->phone,
                                        'address' => $info_create_order->address,
                                        'province' => $info_create_order->province,
                                        'district' => $info_create_order->district,
                                        'amount' => $info_create_order->amount,
                                        'weight' => $mass_fake,
                                        'service' => $info_create_order->service,
                                        'config' => $info_create_order->config,
                                        'payer' => $info_create_order->payer,
                                        'product_type' => $info_create_order->product_type,
                                        'product' => $info_create_order->product,
                                        'sphone' => $info_create_order->sphone,
                                        'commune' => $info_create_order->commune,
                                        'value' => $value,
                                        'note' => $info_create_order->note,
                                        'barter' => $info_create_order->barter,
                                        'soc' => randerCode(3) . '-' . $info_create_order->soc
                                    );

                                    return $this->_create_order_SPS($data_sps, $info_customers->token_customer, $id_create_order, $id_orders_shop, $info_create_order, $mass, $dvvcSource, $dvvcFinsh);
                                    break;
                                case 'GHTK':
                                    $default_data = $query = $this->db->get('tbl_default_mass_volume_ghtk')->row();
                                    $codeNew = $default_data->code . randerCode(6);

                                    // Khởi tạo dữ liệu gửi lên API GHTK
                                    $data_ghtk = new stdClass();
                                    $product = new stdClass();
                                    $data_ghtk->products = [];

                                    $product->name = $info_create_order->product;
                                    $product->weight = (float)$mass_fake / 1000;
                                    $product->quantity = 1;
                                    array_push($data_ghtk->products, $product);
                                    $data_ghtk->order = new stdClass();
                                    $data_ghtk->order->id = $codeNew;
//                                    $data_ghtk->order->pick_address_id = $info_customers->address_id;
                                    $data_ghtk->order->pick_name = $info_customers->customer_shop_code;
                                    // $data_ghtk->order->pick_address = $data_create_order['pickup_address'];
                                    // $data_ghtk->order->pick_province = $data_create_order['pickup_province'];
                                    // $data_ghtk->order->pick_district = $data_create_order['pickup_district'];
                                    $data_ghtk->order->pick_tel = $info_warehouse_send->phone;
                                    $data_ghtk->order->tel = $data_create_order['phone'];
                                    $data_ghtk->order->name = $data_create_order['name'];
                                    $data_ghtk->order->address = $data_create_order['address'];
                                    $data_ghtk->order->province = $data_create_order['province'];
                                    $data_ghtk->order->district = $data_create_order['district'];
                                    $data_ghtk->order->ward = $data_create_order['commune'];
                                    $data_ghtk->order->is_freeship = 1;
                                    $data_ghtk->order->pick_money = $data_create_order['amount'];
                                    $data_ghtk->order->note = $data_create_order['note'];
                                    $data_ghtk->order->transport = $data_create_order['transport'];
                                    $data_ghtk->order->use_return_address = 0;
                                    $data_ghtk->order->value = $value;

                                    $data_ghtk->order->hamlet = "Hải dương";

                                    // Warehouse
                                    $data_ghtk->order->pick_address = $info_warehouse_send->nameAddress;
                                    $data_ghtk->order->pick_province = $info_warehouse_send->province;
                                    $data_ghtk->order->pick_district = $info_warehouse_send->district;
									$data_ghtk->order->pick_ward = $info_warehouse_send->commune;

                                    return $this->_create_order_GHTK($data_ghtk, $default_data->token_ghtk, $id_create_order, $id_orders_shop, $info_create_order, $mass, $dvvcSource, $dvvcFinsh, $codeNew);
                                    break;
                                case 'VTP':
                                    $codeNew = CODE_VTP . randerCode(2) . code(6);
                                    $data_default = $this->db->get('tbl_default_mass_volume_vpost')->row();
                                    $dataLogin = array(
                                        "USERNAME" => $data_default->username,
                                        "PASSWORD" => base64_decode($data_default->password)
                                    );
                                    $token = loginVP($dataLogin);

                                    $commue = explode(',', $info_create_order->pickup_address);
                                    $convertData = convertData($info_create_order->pickup_province, $info_create_order->pickup_district, $commue[count($commue) - 1], $info_create_order->province, $info_create_order->district, $info_create_order->commune);

                                    if ($convertData['sender_province'] == $convertData['receiver_province']) {
                                        $ORDER_SERVICE = 'PHS';
                                    } else {
                                        $ORDER_SERVICE = ($info_create_order->service == 1) ? 'VTK' : 'SCOD';
                                    }

                                    // Tạo dữ liệu gửi lên API của VTP
                                    $dataAPI = array(
                                        'ORDER_NUMBER' => $id_create_order,
                                        'GROUPADDRESS_ID' => $info_customers->address_id_vpost,
                                        'CUS_ID' => '',
                                        'SENDER_FULLNAME' => $info_customers->customer_shop_code,
                                        'SENDER_ADDRESS' => $info_create_order->pickup_address,
                                        'SENDER_PHONE' => $info_create_order->pickup_phone,
                                        'SENDER_EMAIL' => '',
                                        'SENDER_WARD' => $convertData['sender_wards'],
                                        'SENDER_DISTRICT' => $convertData['sender_district'],
                                        'SENDER_PROVINCE' => $convertData['sender_province'],
                                        'RECEIVER_FULLNAME' => $info_create_order->name,
                                        'RECEIVER_ADDRESS' => $info_create_order->address,
                                        'RECEIVER_PHONE' => $info_create_order->phone,
                                        'RECEIVER_EMAIL' => '',
                                        'RECEIVER_WARD' => $convertData['receiver_ward'],
                                        'RECEIVER_DISTRICT' => $convertData['receiver_district'],
                                        'RECEIVER_PROVINCE' => $convertData['receiver_province'],
                                        'ORDER_PAYMENT' => 3,
                                        'ORDER_SERVICE' => $ORDER_SERVICE,
                                        'ORDER_SERVICE_ADD' => '',
                                        'ORDER_VOUCHER' => '',
                                        'ORDER_NOTE' => $info_create_order->note,
                                        'MONEY_COLLECTION' => $info_create_order->amount,
                                        'MONEY_TOTALFEE' => '',
                                        'MONEY_FEECOD' => 0,
                                        'MONEY_FEEVAS' => 0,
                                        'MONEY_FEEINSURRANCE' => 0,
                                        'MONEY_FEE' => 0,
                                        'MONEY_FEEOTHER' => 0,
                                        'MONEY_TOTALVAT' => 0,
                                        'MONEY_TOTAL' => 0,
                                        'PRODUCT_TYPE' => 'HH',
                                        'PRODUCT_NAME' => $info_create_order->product,
                                        'PRODUCT_DESCRIPTION' => '',
                                        'PRODUCT_WEIGHT' => $mass_fake,
                                        'PRODUCT_QUANTITY' => 1,
                                        'PRODUCT_PRICE' => $value
                                    );

                                    return $this->_create_order_VTP($dataAPI, $token, $id_create_order, $id_orders_shop, $info_create_order, $mass, $dvvcSource, $dvvcFinsh, $codeNew);

                                    break;
                                case 'VNC':

                                    $data_default = $this->db->get('tbl_default_mass_volume_vnc')->row();
                                    $dataLogin = array(
                                        "USERNAME" => $data_default->username,
                                        "PASSWORD" => base64_decode($data_default->password)
                                    );

                                    $token = loginVNC($dataLogin, URL_VNC . 'User/Login');

                                    $codeNew = $data_default->code . randerCode(6);

                                    $warehouser = $this->db->get('tbl_warehouse_send')->row();

                                    $dataAPI = array(
                                        'Code' => $codeNew,
                                        'ProductName' => $info_create_order->product,
                                        'CollectAmount' => $info_create_order->amount,
                                        'JourneyType' => 1,
                                        'ServiceId' => $transport == 0 || $transport == 1 ? 12491 : 12490,
                                        'Weight' => $mass_fake,
                                        'Note' => $info_create_order->note,
                                        'NumberOfProducts' => 1,
                                        'SourceCity' => $warehouser->province,
                                        'SourceDistrict' => $warehouser->district,
                                        'SourceWard' => $warehouser->commune,
                                        'SourceAddress' => $warehouser->nameAddress,
                                        'SourceName' => $info_customers->customer_shop_code,
                                        'SourcePhoneNumber' => $warehouser->phone,
                                        'DestCity' => $info_create_order->province,
                                        'DestDistrict' => $info_create_order->district,
                                        'DestWard' => $info_create_order->commune,
                                        'DestAddress' => $info_create_order->commune . ', ' . $info_create_order->district . ', ' . $info_create_order->province,
                                        'DestName' => $info_create_order->name,
                                        'DestPhoneNumber' => $info_create_order->phone,
                                        'Width' => 0,
                                        'Height' => 0,
                                        'Length' => 0,
                                        'ProductPrice' => $value
                                    );

                                    return $this->_create_order_VNC($dataAPI, $token, $id_create_order, $id_orders_shop, $info_create_order, $mass, $dvvcSource, $dvvcFinsh, $codeNew);

                                    break;
                                case 'NB':

                                    $today = date('Y-m-d H:i:s');
                                    $code = CODE_NB.'.'.randerCode(2).code(6);

                                    // Tạo dữ liệu cập nhật bảng create_order
                                    $data_update_create_order = array(
                                        'created' => $today,
                                        'code' => $code
                                    );

                                    $this->db->where('id', $id_create_order);
                                    $update = $this->db->update('tbl_create_order', $data_update_create_order);


                                    // Khởi tạo biến dữ liệu cập nhật vào bảng tblorders_shop
                                    $data_update_orders_shop = array(
                                        'code_supership' => $code
                                    );
                                    $this->db->where('id', $id_orders_shop);
                                    $update_orders_shop = $this->db->update('tblorders_shop', $data_update_orders_shop);

                                    if ($update && $update_orders_shop) {
                                        // Lưu lịch sử chuyển đổi
                                        $data_history = array(
                                            'date_create' => date('Y-m-d H:i:s'),
                                            'code_old' => $info_create_order->code,
                                            'code_new' => $code,
                                            'mass' => $mass,
                                            'dvvc_source' => $dvvcSource,
                                            'dvvc_finsh' => $dvvcFinsh,
                                            'orders_id' => $id_create_order
                                        );

                                        $this->db->insert('tbl_history_change', $data_history);
                                        fnLog('Mã đơn hàng mới: ' . $code);
                                        return $code;
                                    }

                                    $message = '';
                                    if (!$update)
                                        $message = 'Cập nhật đơn hàng id ' . $id_create_order . ' trên bảng tbl_create_order thông qua chức năng chuyển đổi đơn hàng thất bại.';
                                    if (!$update_orders_shop)
                                        $message = 'Cập nhật đơn hàng id ' . $id_orders_shop . ' trên bảng tblorders_shop thông qua chức năng chuyển đổi đơn hàng thất bại.';
                                    fnLog($message);
                                    return 'Update_order_Failed';

                                    break;
                                default:
                                    return 'DVVCnoSupport';
                                    break;

                            }


                        }

                        $message = '';
                        if (!$cancel)
                            $message = 'Hủy đơn hàng id ' . $info_create_order->id . ' trên bảng tblcreate_order bằng chức năng chuyển đổi đơn vị vận chuyển thất bại';

                        if (!$cancel_orders_shop)
                            $message = 'Hủy đơn hàng id ' . $info_order->id . ' trên bảng tblcreate_order bằng chức năng chuyển đổi đơn vị vận chuyển thất bại';

                        fnLog($message);
                        return 'Cancel_Order_Systems_Failed';

                        break;
                    case 'GHTK':

                        // Hủy đơn trên GHTK
                        $dataAPI = array('code' => $info_order->code_ghtk);
                        $resultJSON = self::api_ghtk($dataAPI, $info_create_order->token_ghtk);

                        if (empty($resultJSON)) {
                            fnLog('Dữ liệu API Hủy của GHTK trả về rỗng khi sử dụng chức năng chuyển đổi đơn vị vận chuyển. Vui lòng kiểm tra lại');
                            return 'dataEmpty';
                        }

                        $resultArr = json_decode($resultJSON, true);

                        if ($resultArr['success'] != true) {
                            fnLog('Hủy đơn hàng trên GHTK bằng chức năng chuyển đổi đơn vị vận chuyển thất bại. Với nội dung trả về như sau: ' . $resultJSON);
                            return 'Cancel_Order_on_GHTK_Failed';
                        }


                        // Hủy đơn hàng trên hệ thống
                        $data_cancel_create_order = array('status_cancel' => 1);
                        $this->db->where('id', $info_create_order->id);
                        $cancel = $this->db->update('tbl_create_order', $data_cancel_create_order);

                        $data_cancel_orders_shop = array('status' => 'Hủy');
                        $this->db->where('id', $info_order->id);
                        $cancel_orders_shop = $this->db->update('tblorders_shop', $data_cancel_orders_shop);

                        if ($cancel && $cancel_orders_shop) {
                            $required_code = $info_create_order->required_code;

                            // Tạo đơn hàng mới trên bảng orders_shop
                            // Khởi tạo dữ liệu
                            $data_orders_shop = array(
                                'shop' => $info_order->shop,
                                'code_orders' => $info_create_order->soc,
                                'status' => 'Đã Nhập Kho',
                                'date_create' => date('Y-m-d H:i:s'),
                                'mass' => $mass,
                                'collect' => $info_create_order->amount,
                                'value' => $info_create_order->value,
                                'prepay' => 0,
                                'receiver' => $info_create_order->name,
                                'phone' => $info_create_order->phone,
                                'address' => $info_create_order->address,
                                'ward' => $info_create_order->commune,
                                'district' => $info_create_order->district,
                                'city' => $info_create_order->province,
                                'note' => $info_create_order->note,
                                'warehouses' => $info_create_order->pickup_address,
                                'product' => $info_create_order->product,
                                'city_send' => 'Tỉnh Hải Dương',
                                'hd_fee_stam' => $info_create_order->supership_value,
                                'last_time_updated' => date('Y-m-d H:i:s'),
                                'DVVC' => $dvvcFinsh,
                                'is_hd_branch' => 1,
                                'payer' => ($info_create_order->payer == 1) ? 'Người Gửi' : '',
                                'sale' => 0,
                                'pack_data' => ($info_create_order->service == 1) ? 'Tốc Hành' : 'Tiết Kiệm',
                                'pay_refund' => 0,
                                'status_over' => 0,
                                'status_delay' => 0,
                                'warehouse_send' => get_option('warehouse_send'),
                                'mass_fake' => $mass_fake,
                                'required_code' => $required_code,
                                'region_id' => $info_create_order->region_id
                            );

                            $this->db->insert('tblorders_shop', $data_orders_shop);
                            $id_orders_shop = $this->db->insert_id();

                            if (!$id_orders_shop) {
                                fnLog('Tạo đơn mới trên bảng tblorders_shop sử dụng chức năng chuyển đổi đơn vị vận chuyển thất bại.');
                                return 'Insert_in_tblorders_shop_Failed';
                            }

                            // Tạo đơn hàng mới trên bảng create_order
                            // Khởi tạo dữ liệu
                            $data_create_order = array(
                                'customer_id' => $info_create_order->customer_id,
                                'supership_value' => $info_create_order->supership_value,
                                'cod' => $info_create_order->cod,
                                'pickup_address' => $info_create_order->pickup_address,
                                'pickup_province' => $info_create_order->pickup_province,
                                'pickup_district' => $info_create_order->pickup_district,
                                'pickup_commune' => $info_create_order->pickup_commune,
                                'pickup_phone' => $info_create_order->pickup_phone,
                                'name' => $info_create_order->name,
                                'phone' => $info_create_order->phone,
                                'sphone' => $info_create_order->sphone,
                                'address' => $info_create_order->address,
                                'province' => $info_create_order->province,
                                'district' => $info_create_order->district,
                                'commune' => $info_create_order->commune,
                                'amount' => $info_create_order->amount,
                                'weight' => $mass,
                                'volume' => $info_create_order->volume,
                                'soc' => $info_create_order->soc,
                                'note' => $info_create_order->note,
                                'service' => $info_create_order->service,
                                'config' => $info_create_order->config,
                                'payer' => $info_create_order->payer,
                                'product_type' => $info_create_order->product_type,
                                'product' => $info_create_order->product,
                                'barter' => $info_create_order->barter,
                                'value' => $info_create_order->value,
                                'code' => '',
                                'created' => '',
                                'user_created' => get_staff_user_id(),
                                'status_cancel' => 0,
                                'the_fee_bearer' => $info_create_order->the_fee_bearer,
                                'transport' => $transport == 0 || $transport == 1 ? 'road' : 'fly',
                                'ghtk' => $info_create_order->ghtk,
                                'token_ghtk' => $info_create_order->token_ghtk,
                                'orders_shop_id' => $id_orders_shop,
                                'dvvc' => $dvvcFinsh,
                                'required_code' => $required_code,
                                'mass_fake' => $mass_fake
                            );

                            $this->db->insert('tbl_create_order', $data_create_order);
                            $id_create_order = $this->db->insert_id();

                            if (!$id_create_order) {
                                fnLog('Tạo đơn mới trên bảng tbl_create_order sử dụng chức năng chuyển đổi đơn vị vận chuyển thất bại.');
                                return 'Insert_in_tbl_create_order_Failed';
                            }

                            $value = $info_create_order->value;
                            if ($value < 3000000) {
                                $valueNew = rand(2500000, 3000000);
                                if ($valueNew > $value) {
                                    $number2 = substr($valueNew, 3, 4);
                                    $value = $valueNew - $number2;
                                }
                            }

                            switch ($dvvcFinsh) {
                                case 'SPS':

                                    // Khởi tạo dữ liệu gửi lên API SPS
                                    $data_sps = array(
                                        'pickup_phone' => $info_customers->customer_phone,
                                        'pickup_address' => $info_create_order->pickup_address,
                                        'pickup_province' => $info_create_order->pickup_province,
                                        'pickup_district' => $info_create_order->pickup_district,
                                        'name' => $info_create_order->name,
                                        'phone' => $info_create_order->phone,
                                        'address' => $info_create_order->address,
                                        'province' => $info_create_order->province,
                                        'district' => $info_create_order->district,
                                        'amount' => $info_create_order->amount,
                                        'weight' => $mass_fake,
                                        'service' => $info_create_order->service,
                                        'config' => $info_create_order->config,
                                        'payer' => $info_create_order->payer,
                                        'product_type' => $info_create_order->product_type,
                                        'product' => $info_create_order->product,
                                        'sphone' => $info_create_order->sphone,
                                        'commune' => $info_create_order->commune,
                                        'value' => $value,
                                        'note' => $info_create_order->note,
                                        'barter' => $info_create_order->barter,
                                        'soc' => randerCode(3) . '-' . $info_create_order->soc
                                    );

                                    return $this->_create_order_SPS($data_sps, $info_customers->token_customer, $id_create_order, $id_orders_shop, $info_create_order, $mass, $dvvcSource, $dvvcFinsh);
                                    break;
                                case 'GHTK':
                                    $default_data = $query = $this->db->get('tbl_default_mass_volume_ghtk')->row();
                                    $codeNew = $default_data->code . randerCode(6);


                                    // Khởi tạo dữ liệu gửi lên API GHTK
                                    $data_ghtk = new stdClass();
                                    $product = new stdClass();
                                    $data_ghtk->products = [];

                                    $product->name = $info_create_order->product;
                                    $product->weight = (float)$mass_fake / 1000;
                                    $product->quantity = 1;
                                    array_push($data_ghtk->products, $product);
                                    $data_ghtk->order = new stdClass();
                                    $data_ghtk->order->id = $codeNew;
//                                    $data_ghtk->order->pick_address_id = $info_customers->address_id;
                                    $data_ghtk->order->pick_name = $info_customers->customer_shop_code;
                                    // $data_ghtk->order->pick_address = $data_create_order['pickup_address'];
                                    // $data_ghtk->order->pick_province = $data_create_order['pickup_province'];
                                    // $data_ghtk->order->pick_district = $data_create_order['pickup_district'];
                                    $data_ghtk->order->pick_tel = $info_warehouse_send->phone;
                                    $data_ghtk->order->tel = $data_create_order['phone'];
                                    $data_ghtk->order->name = $data_create_order['name'];
                                    $data_ghtk->order->address = $data_create_order['address'];
                                    $data_ghtk->order->province = $data_create_order['province'];
                                    $data_ghtk->order->district = $data_create_order['district'];
                                    $data_ghtk->order->ward = $data_create_order['commune'];
                                    $data_ghtk->order->is_freeship = 1;
                                    $data_ghtk->order->pick_money = $data_create_order['amount'];
                                    $data_ghtk->order->note = $data_create_order['note'];
                                    $data_ghtk->order->transport = $data_create_order['transport'];
                                    $data_ghtk->order->use_return_address = 0;
                                    $data_ghtk->order->value = $value;

                                    $data_ghtk->order->hamlet = "Hải dương";
                                    // Warehouse
                                    $data_ghtk->order->pick_address = $info_warehouse_send->nameAddress;
                                    $data_ghtk->order->pick_province = $info_warehouse_send->province;
                                    $data_ghtk->order->pick_district = $info_warehouse_send->district;
									$data_ghtk->order->pick_ward = $info_warehouse_send->commune;

                                    return $this->_create_order_GHTK($data_ghtk, $default_data->token_ghtk, $id_create_order, $id_orders_shop, $info_create_order, $mass, $dvvcSource, $dvvcFinsh, $codeNew);
                                    break;
                                case 'VTP':

                                    $codeNew = CODE_VTP . randerCode(2) . code(6);

                                    $data_default = $this->db->get('tbl_default_mass_volume_vpost')->row();
                                    $dataLogin = array(
                                        "USERNAME" => $data_default->username,
                                        "PASSWORD" => base64_decode($data_default->password)
                                    );
                                    $token = loginVP($dataLogin);

                                    $commue = explode(',', $info_create_order->pickup_address);
                                    $convertData = convertData($info_create_order->pickup_province, $info_create_order->pickup_district, $commue[count($commue) - 1], $info_create_order->province, $info_create_order->district, $info_create_order->commune);

                                    if ($convertData['sender_province'] == $convertData['receiver_province']) {
                                        $ORDER_SERVICE = 'PHS';
                                    } else {
                                        $ORDER_SERVICE = ($info_create_order->service == 1) ? 'VTK' : 'SCOD';
                                    }

                                    // Tạo dữ liệu gửi lên API của VTP
                                    $dataAPI = array(
                                        'ORDER_NUMBER' => $id_create_order,
                                        'GROUPADDRESS_ID' => $info_customers->address_id_vpost,
                                        'CUS_ID' => '',
                                        'SENDER_FULLNAME' => $info_customers->customer_shop_code,
                                        'SENDER_ADDRESS' => $info_create_order->pickup_address,
                                        'SENDER_PHONE' => $info_create_order->pickup_phone,
                                        'SENDER_EMAIL' => '',
                                        'SENDER_WARD' => $convertData['sender_wards'],
                                        'SENDER_DISTRICT' => $convertData['sender_district'],
                                        'SENDER_PROVINCE' => $convertData['sender_province'],
                                        'RECEIVER_FULLNAME' => $info_create_order->name,
                                        'RECEIVER_ADDRESS' => $info_create_order->address,
                                        'RECEIVER_PHONE' => $info_create_order->phone,
                                        'RECEIVER_EMAIL' => '',
                                        'RECEIVER_WARD' => $convertData['receiver_ward'],
                                        'RECEIVER_DISTRICT' => $convertData['receiver_district'],
                                        'RECEIVER_PROVINCE' => $convertData['receiver_province'],
                                        'ORDER_PAYMENT' => 3,
                                        'ORDER_SERVICE' => $ORDER_SERVICE,
                                        'ORDER_SERVICE_ADD' => '',
                                        'ORDER_VOUCHER' => '',
                                        'ORDER_NOTE' => $info_create_order->note,
                                        'MONEY_COLLECTION' => $info_create_order->amount,
                                        'MONEY_TOTALFEE' => '',
                                        'MONEY_FEECOD' => 0,
                                        'MONEY_FEEVAS' => 0,
                                        'MONEY_FEEINSURRANCE' => 0,
                                        'MONEY_FEE' => 0,
                                        'MONEY_FEEOTHER' => 0,
                                        'MONEY_TOTALVAT' => 0,
                                        'MONEY_TOTAL' => 0,
                                        'PRODUCT_TYPE' => 'HH',
                                        'PRODUCT_NAME' => $info_create_order->product,
                                        'PRODUCT_DESCRIPTION' => '',
                                        'PRODUCT_WEIGHT' => $mass_fake,
                                        'PRODUCT_QUANTITY' => 1,
                                        'PRODUCT_PRICE' => $value
                                    );

                                    return $this->_create_order_VTP($dataAPI, $token, $id_create_order, $id_orders_shop, $info_create_order, $mass, $dvvcSource, $dvvcFinsh, $codeNew);

                                    break;

                                case 'VNC':

                                    $data_default = $this->db->get('tbl_default_mass_volume_vnc')->row();
                                    $dataLogin = array(
                                        "USERNAME" => $data_default->username,
                                        "PASSWORD" => base64_decode($data_default->password)
                                    );

                                    $token = loginVNC($dataLogin, URL_VNC . 'User/Login');

                                    $codeNew = $data_default->code . randerCode(6);

                                    $warehouser = $this->db->get('tbl_warehouse_send')->row();

                                    $dataAPI = array(
                                        'Code' => $codeNew,
                                        'ProductName' => $info_create_order->product,
                                        'CollectAmount' => $info_create_order->amount,
                                        'JourneyType' => 1,
                                        'ServiceId' => $transport == 0 || $transport == 1 ? 12491 : 12490,
                                        'Weight' => $mass_fake,
                                        'Note' => $info_create_order->note,
                                        'NumberOfProducts' => 1,
                                        'SourceCity' => $warehouser->province,
                                        'SourceDistrict' => $warehouser->district,
                                        'SourceWard' => $warehouser->commune,
                                        'SourceAddress' => $warehouser->nameAddress,
                                        'SourceName' => $info_customers->customer_shop_code,
                                        'SourcePhoneNumber' => $warehouser->phone,
                                        'DestCity' => $info_create_order->province,
                                        'DestDistrict' => $info_create_order->district,
                                        'DestWard' => $info_create_order->commune,
                                        'DestAddress' => $info_create_order->commune . ', ' . $info_create_order->district . ', ' . $info_create_order->province,
                                        'DestName' => $info_create_order->name,
                                        'DestPhoneNumber' => $info_create_order->phone,
                                        'Width' => 0,
                                        'Height' => 0,
                                        'Length' => 0,
                                        'ProductPrice' => $value
                                    );

                                    return $this->_create_order_VNC($dataAPI, $token, $id_create_order, $id_orders_shop, $info_create_order, $mass, $dvvcSource, $dvvcFinsh, $codeNew);

                                    break;
                                case 'NB':

                                    $today = date('Y-m-d H:i:s');
                                    $code = CODE_NB.'.'.randerCode(2).code(6);

                                    // Tạo dữ liệu cập nhật bảng create_order
                                    $data_update_create_order = array(
                                        'created' => $today,
                                        'code' => $code
                                    );

                                    $this->db->where('id', $id_create_order);
                                    $update = $this->db->update('tbl_create_order', $data_update_create_order);


                                    // Khởi tạo biến dữ liệu cập nhật vào bảng tblorders_shop
                                    $data_update_orders_shop = array(
                                        'code_supership' => $code
                                    );
                                    $this->db->where('id', $id_orders_shop);
                                    $update_orders_shop = $this->db->update('tblorders_shop', $data_update_orders_shop);

                                    if ($update && $update_orders_shop) {
                                        // Lưu lịch sử chuyển đổi
                                        $data_history = array(
                                            'date_create' => date('Y-m-d H:i:s'),
                                            'code_old' => $info_create_order->code,
                                            'code_new' => $code,
                                            'mass' => $mass,
                                            'dvvc_source' => $dvvcSource,
                                            'dvvc_finsh' => $dvvcFinsh,
                                            'orders_id' => $id_create_order
                                        );

                                        $this->db->insert('tbl_history_change', $data_history);
                                        fnLog('Mã đơn hàng mới: ' . $code);
                                        return $code;
                                    }

                                    $message = '';
                                    if (!$update)
                                        $message = 'Cập nhật đơn hàng id ' . $id_create_order . ' trên bảng tbl_create_order thông qua chức năng chuyển đổi đơn hàng thất bại.';
                                    if (!$update_orders_shop)
                                        $message = 'Cập nhật đơn hàng id ' . $id_orders_shop . ' trên bảng tblorders_shop thông qua chức năng chuyển đổi đơn hàng thất bại.';
                                    fnLog($message);
                                    return 'Update_order_Failed';

                                    break;
                                default:
                                    return 'DVVCnoSupport';
                                    break;

                            }


                        }

                        $message = '';
                        if (!$cancel)
                            $message = 'Hủy đơn hàng id ' . $info_create_order->id . ' trên bảng tblcreate_order bằng chức năng chuyển đổi đơn vị vận chuyển thất bại';

                        if (!$cancel_orders_shop)
                            $message = 'Hủy đơn hàng id ' . $info_order->id . ' trên bảng tblcreate_order bằng chức năng chuyển đổi đơn vị vận chuyển thất bại';

                        fnLog($message);
                        return 'Cancel_Order_Systems_Failed';


                        break;
                    case 'VTP':

                        $data_default = $this->db->get('tbl_default_mass_volume_vpost')->row();
                        $dataLogin = array(
                            "USERNAME" => $data_default->username,
                            "PASSWORD" => base64_decode($data_default->password)
                        );
                        $token = loginVP($dataLogin);

                        $data = array(
                            'TYPE' => 4,
                            'ORDER_NUMBER' => $info_order->code_ghtk,
                            'NOTE' => 'Thông tin đơn bị lỗi'
                        );

                        $resultArr = $this->_api_viettel($data, $token, 'https://partner.viettelpost.vn/v2/order/UpdateOrder');

                        if (empty($resultArr)) {
                            fnLog('Dữ liệu API Hủy của VTP trả về rỗng khi sử dụng chức năng chuyển đổi đơn vị vận chuyển. Vui lòng kiểm tra lại');
                            return 'dataEmpty';
                        }


                        if ($resultArr['status'] != 200) {
                            fnLog('Hủy đơn hàng trên VTP bằng chức năng chuyển đổi đơn vị vận chuyển thất bại. Với nội dung trả về như sau: ' . json_encode($resultArr));
                            return 'Cancel_Order_on_VTP_Failed';
                        }

                        // Hủy đơn hàng trên hệ thống
                        $data_cancel_create_order = array('status_cancel' => 1);
                        $this->db->where('id', $info_create_order->id);
                        $cancel = $this->db->update('tbl_create_order', $data_cancel_create_order);

                        $data_cancel_orders_shop = array('status' => 'Hủy');
                        $this->db->where('id', $info_order->id);
                        $cancel_orders_shop = $this->db->update('tblorders_shop', $data_cancel_orders_shop);

                        if ($cancel && $cancel_orders_shop) {
                            $required_code = $info_create_order->required_code;

                            // Tạo đơn hàng mới trên bảng orders_shop
                            // Khởi tạo dữ liệu
                            $data_orders_shop = array(
                                'shop' => $info_order->shop,
                                'code_orders' => $info_create_order->soc,
                                'status' => 'Đã Nhập Kho',
                                'date_create' => date('Y-m-d H:i:s'),
                                'mass' => $mass,
                                'collect' => $info_create_order->amount,
                                'value' => $info_create_order->value,
                                'prepay' => 0,
                                'receiver' => $info_create_order->name,
                                'phone' => $info_create_order->phone,
                                'address' => $info_create_order->address,
                                'ward' => $info_create_order->commune,
                                'district' => $info_create_order->district,
                                'city' => $info_create_order->province,
                                'note' => $info_create_order->note,
                                'warehouses' => $info_create_order->pickup_address,
                                'product' => $info_create_order->product,
                                'city_send' => 'Tỉnh Hải Dương',
                                'hd_fee_stam' => $info_create_order->supership_value,
                                'last_time_updated' => date('Y-m-d H:i:s'),
                                'DVVC' => $dvvcFinsh,
                                'is_hd_branch' => 1,
                                'payer' => ($info_create_order->payer == 1) ? 'Người Gửi' : '',
                                'sale' => 0,
                                'pack_data' => ($info_create_order->service == 1) ? 'Tốc Hành' : 'Tiết Kiệm',
                                'pay_refund' => 0,
                                'status_over' => 0,
                                'status_delay' => 0,
                                'warehouse_send' => get_option('warehouse_send'),
                                'mass_fake' => $mass_fake,
                                'required_code' => $required_code,
                                'region_id' => $info_create_order->region_id
                            );

                            $this->db->insert('tblorders_shop', $data_orders_shop);
                            $id_orders_shop = $this->db->insert_id();

                            if (!$id_orders_shop) {
                                fnLog('Tạo đơn mới trên bảng tblorders_shop sử dụng chức năng chuyển đổi đơn vị vận chuyển thất bại.');
                                return 'Insert_in_tblorders_shop_Failed';
                            }

                            // Tạo đơn hàng mới trên bảng create_order
                            // Khởi tạo dữ liệu
                            $data_create_order = array(
                                'customer_id' => $info_create_order->customer_id,
                                'supership_value' => $info_create_order->supership_value,
                                'cod' => $info_create_order->cod,
                                'pickup_address' => $info_create_order->pickup_address,
                                'pickup_province' => $info_create_order->pickup_province,
                                'pickup_district' => $info_create_order->pickup_district,
                                'pickup_commune' => $info_create_order->pickup_commune,
                                'pickup_phone' => $info_create_order->pickup_phone,
                                'name' => $info_create_order->name,
                                'phone' => $info_create_order->phone,
                                'sphone' => $info_create_order->sphone,
                                'address' => $info_create_order->address,
                                'province' => $info_create_order->province,
                                'district' => $info_create_order->district,
                                'commune' => $info_create_order->commune,
                                'amount' => $info_create_order->amount,
                                'weight' => $mass,
                                'volume' => $info_create_order->volume,
                                'soc' => $info_create_order->soc,
                                'note' => $info_create_order->note,
                                'service' => $info_create_order->service,
                                'config' => $info_create_order->config,
                                'payer' => $info_create_order->payer,
                                'product_type' => $info_create_order->product_type,
                                'product' => $info_create_order->product,
                                'barter' => $info_create_order->barter,
                                'value' => $info_create_order->value,
                                'code' => '',
                                'created' => '',
                                'user_created' => get_staff_user_id(),
                                'status_cancel' => 0,
                                'the_fee_bearer' => $info_create_order->the_fee_bearer,
                                'transport' => $transport == 0 || $transport == 1 ? 'road' : 'fly',
                                'ghtk' => $info_create_order->ghtk,
                                'token_ghtk' => $info_create_order->token_ghtk,
                                'orders_shop_id' => $id_orders_shop,
                                'dvvc' => $dvvcFinsh,
                                'required_code' => $required_code,
                                'mass_fake' => $mass_fake
                            );

                            $this->db->insert('tbl_create_order', $data_create_order);
                            $id_create_order = $this->db->insert_id();

                            if (!$id_create_order) {
                                fnLog('Tạo đơn mới trên bảng tbl_create_order sử dụng chức năng chuyển đổi đơn vị vận chuyển thất bại.');
                                return 'Insert_in_tbl_create_order_Failed';
                            }

                            $value = $info_create_order->value;
                            if ($value < 3000000) {
                                $valueNew = rand(2500000, 3000000);
                                if ($valueNew > $value) {
                                    $number2 = substr($valueNew, 3, 4);
                                    $value = $valueNew - $number2;
                                }
                            }

                            switch ($dvvcFinsh) {

                                case 'SPS':

                                    // Khởi tạo dữ liệu gửi lên API SPS
                                    $data_sps = array(
                                        'pickup_phone' => $info_customers->customer_phone,
                                        'pickup_address' => $info_create_order->pickup_address,
                                        'pickup_province' => $info_create_order->pickup_province,
                                        'pickup_district' => $info_create_order->pickup_district,
                                        'name' => $info_create_order->name,
                                        'phone' => $info_create_order->phone,
                                        'address' => $info_create_order->address,
                                        'province' => $info_create_order->province,
                                        'district' => $info_create_order->district,
                                        'amount' => $info_create_order->amount,
                                        'weight' => $mass_fake,
                                        'service' => $info_create_order->service,
                                        'config' => $info_create_order->config,
                                        'payer' => $info_create_order->payer,
                                        'product_type' => $info_create_order->product_type,
                                        'product' => $info_create_order->product,
                                        'sphone' => $info_create_order->sphone,
                                        'commune' => $info_create_order->commune,
                                        'value' => $value,
                                        'note' => $info_create_order->note,
                                        'barter' => $info_create_order->barter,
                                        'soc' => randerCode(3) . '-' . $info_create_order->soc
                                    );

                                    return $this->_create_order_SPS($data_sps, $info_customers->token_customer, $id_create_order, $id_orders_shop, $info_create_order, $mass, $dvvcSource, $dvvcFinsh);
                                    break;
                                case 'GHTK':

                                    $default_data = $query = $this->db->get('tbl_default_mass_volume_ghtk')->row();
                                    $codeNew = $default_data->code . randerCode(6);

                                    // Khởi tạo dữ liệu gửi lên API GHTK
                                    $data_ghtk = new stdClass();
                                    $product = new stdClass();
                                    $data_ghtk->products = [];

                                    $product->name = $info_create_order->product;
                                    $product->weight = (float)$mass_fake / 1000;
                                    $product->quantity = 1;
                                    array_push($data_ghtk->products, $product);
                                    $data_ghtk->order = new stdClass();
                                    $data_ghtk->order->id = $codeNew;
//                                    $data_ghtk->order->pick_address_id = $info_customers->address_id;
                                    $data_ghtk->order->pick_name = $info_customers->customer_shop_code;
                                    // $data_ghtk->order->pick_address = $data_create_order['pickup_address'];
                                    // $data_ghtk->order->pick_province = $data_create_order['pickup_province'];
                                    // $data_ghtk->order->pick_district = $data_create_order['pickup_district'];
                                    $data_ghtk->order->pick_tel = $info_warehouse_send->phone;
                                    $data_ghtk->order->tel = $data_create_order['phone'];
                                    $data_ghtk->order->name = $data_create_order['name'];
                                    $data_ghtk->order->address = $data_create_order['address'];
                                    $data_ghtk->order->province = $data_create_order['province'];
                                    $data_ghtk->order->district = $data_create_order['district'];
                                    $data_ghtk->order->ward = $data_create_order['commune'];
                                    $data_ghtk->order->is_freeship = 1;
                                    $data_ghtk->order->pick_money = $data_create_order['amount'];
                                    $data_ghtk->order->note = $data_create_order['note'];
                                    $data_ghtk->order->transport = $data_create_order['transport'];
                                    $data_ghtk->order->use_return_address = 0;
                                    $data_ghtk->order->value = $value;

                                    $data_ghtk->order->hamlet = "Hải dương";
                                    // Warehouse
                                    $data_ghtk->order->pick_address = $info_warehouse_send->nameAddress;
                                    $data_ghtk->order->pick_province = $info_warehouse_send->province;
                                    $data_ghtk->order->pick_district = $info_warehouse_send->district;
									$data_ghtk->order->pick_ward = $info_warehouse_send->commune;

                                    return $this->_create_order_GHTK($data_ghtk, $default_data->token_ghtk, $id_create_order, $id_orders_shop, $info_create_order, $mass, $dvvcSource, $dvvcFinsh, $codeNew);
                                    break;
                                case 'VTP':
                                    $codeNew = CODE_VTP . randerCode(2) . code(6);
                                    $data_default = $this->db->get('tbl_default_mass_volume_vpost')->row();
                                    $dataLogin = array(
                                        "USERNAME" => $data_default->username,
                                        "PASSWORD" => base64_decode($data_default->password)
                                    );
                                    $token = loginVP($dataLogin);

                                    $commue = explode(',', $info_create_order->pickup_address);
                                    $convertData = convertData($info_create_order->pickup_province, $info_create_order->pickup_district, $commue[count($commue) - 1], $info_create_order->province, $info_create_order->district, $info_create_order->commune);

                                    if ($convertData['sender_province'] == $convertData['receiver_province']) {
                                        $ORDER_SERVICE = 'PHS';
                                    } else {
                                        $ORDER_SERVICE = ($info_create_order->service == 1) ? 'VTK' : 'SCOD';
                                    }

                                    // Tạo dữ liệu gửi lên API của VTP
                                    $dataAPI = array(
                                        'ORDER_NUMBER' => $id_create_order,
                                        'GROUPADDRESS_ID' => $info_customers->address_id_vpost,
                                        'CUS_ID' => '',
                                        'SENDER_FULLNAME' => $info_customers->customer_shop_code,
                                        'SENDER_ADDRESS' => $info_create_order->pickup_address,
                                        'SENDER_PHONE' => $info_create_order->pickup_phone,
                                        'SENDER_EMAIL' => '',
                                        'SENDER_WARD' => $convertData['sender_wards'],
                                        'SENDER_DISTRICT' => $convertData['sender_district'],
                                        'SENDER_PROVINCE' => $convertData['sender_province'],
                                        'RECEIVER_FULLNAME' => $info_create_order->name,
                                        'RECEIVER_ADDRESS' => $info_create_order->address,
                                        'RECEIVER_PHONE' => $info_create_order->phone,
                                        'RECEIVER_EMAIL' => '',
                                        'RECEIVER_WARD' => $convertData['receiver_ward'],
                                        'RECEIVER_DISTRICT' => $convertData['receiver_district'],
                                        'RECEIVER_PROVINCE' => $convertData['receiver_province'],
                                        'ORDER_PAYMENT' => 3,
                                        'ORDER_SERVICE' => $ORDER_SERVICE,
                                        'ORDER_SERVICE_ADD' => '',
                                        'ORDER_VOUCHER' => '',
                                        'ORDER_NOTE' => $info_create_order->note,
                                        'MONEY_COLLECTION' => $info_create_order->amount,
                                        'MONEY_TOTALFEE' => '',
                                        'MONEY_FEECOD' => 0,
                                        'MONEY_FEEVAS' => 0,
                                        'MONEY_FEEINSURRANCE' => 0,
                                        'MONEY_FEE' => 0,
                                        'MONEY_FEEOTHER' => 0,
                                        'MONEY_TOTALVAT' => 0,
                                        'MONEY_TOTAL' => 0,
                                        'PRODUCT_TYPE' => 'HH',
                                        'PRODUCT_NAME' => $info_create_order->product,
                                        'PRODUCT_DESCRIPTION' => '',
                                        'PRODUCT_WEIGHT' => $mass_fake,
                                        'PRODUCT_QUANTITY' => 1,
                                        'PRODUCT_PRICE' => $value
                                    );

                                    return $this->_create_order_VTP($dataAPI, $token, $id_create_order, $id_orders_shop, $info_create_order, $mass, $dvvcSource, $dvvcFinsh, $codeNew);

                                    break;

                                case 'VNC':

                                    $data_default = $this->db->get('tbl_default_mass_volume_vnc')->row();
                                    $dataLogin = array(
                                        "USERNAME" => $data_default->username,
                                        "PASSWORD" => base64_decode($data_default->password)
                                    );

                                    $token = loginVNC($dataLogin, URL_VNC . 'User/Login');

                                    $codeNew = $data_default->code . randerCode(6);

                                    $warehouser = $this->db->get('tbl_warehouse_send')->row();

                                    $dataAPI = array(
                                        'Code' => $codeNew,
                                        'ProductName' => $info_create_order->product,
                                        'CollectAmount' => $info_create_order->amount,
                                        'JourneyType' => 1,
                                        'ServiceId' => $transport == 0 || $transport == 1 ? 12491 : 12490,
                                        'Weight' => $mass_fake,
                                        'Note' => $info_create_order->note,
                                        'NumberOfProducts' => 1,
                                        'SourceCity' => $warehouser->province,
                                        'SourceDistrict' => $warehouser->district,
                                        'SourceWard' => $warehouser->commune,
                                        'SourceAddress' => $warehouser->nameAddress,
                                        'SourceName' => $info_customers->customer_shop_code,
                                        'SourcePhoneNumber' => $warehouser->phone,
                                        'DestCity' => $info_create_order->province,
                                        'DestDistrict' => $info_create_order->district,
                                        'DestWard' => $info_create_order->commune,
                                        'DestAddress' => $info_create_order->commune . ', ' . $info_create_order->district . ', ' . $info_create_order->province,
                                        'DestName' => $info_create_order->name,
                                        'DestPhoneNumber' => $info_create_order->phone,
                                        'Width' => 0,
                                        'Height' => 0,
                                        'Length' => 0,
                                        'ProductPrice' => $value
                                    );

                                    return $this->_create_order_VNC($dataAPI, $token, $id_create_order, $id_orders_shop, $info_create_order, $mass, $dvvcSource, $dvvcFinsh, $codeNew);

                                    break;
                                case 'NB':

                                    $today = date('Y-m-d H:i:s');
                                    $code = CODE_NB.'.'.randerCode(2).code(6);

                                    // Tạo dữ liệu cập nhật bảng create_order
                                    $data_update_create_order = array(
                                        'created' => $today,
                                        'code' => $code
                                    );

                                    $this->db->where('id', $id_create_order);
                                    $update = $this->db->update('tbl_create_order', $data_update_create_order);


                                    // Khởi tạo biến dữ liệu cập nhật vào bảng tblorders_shop
                                    $data_update_orders_shop = array(
                                        'code_supership' => $code
                                    );
                                    $this->db->where('id', $id_orders_shop);
                                    $update_orders_shop = $this->db->update('tblorders_shop', $data_update_orders_shop);

                                    if ($update && $update_orders_shop) {
                                        // Lưu lịch sử chuyển đổi
                                        $data_history = array(
                                            'date_create' => date('Y-m-d H:i:s'),
                                            'code_old' => $info_create_order->code,
                                            'code_new' => $code,
                                            'mass' => $mass,
                                            'dvvc_source' => $dvvcSource,
                                            'dvvc_finsh' => $dvvcFinsh,
                                            'orders_id' => $id_create_order
                                        );

                                        $this->db->insert('tbl_history_change', $data_history);
                                        fnLog('Mã đơn hàng mới: ' . $code);
                                        return $code;
                                    }

                                    $message = '';
                                    if (!$update)
                                        $message = 'Cập nhật đơn hàng id ' . $id_create_order . ' trên bảng tbl_create_order thông qua chức năng chuyển đổi đơn hàng thất bại.';
                                    if (!$update_orders_shop)
                                        $message = 'Cập nhật đơn hàng id ' . $id_orders_shop . ' trên bảng tblorders_shop thông qua chức năng chuyển đổi đơn hàng thất bại.';
                                    fnLog($message);
                                    return 'Update_order_Failed';

                                    break;
                                default:
                                    return 'DVVCnoSupport';
                                    break;

                            }


                        }

                        $message = '';
                        if (!$cancel)
                            $message = 'Hủy đơn hàng id ' . $info_create_order->id . ' trên bảng tblcreate_order bằng chức năng chuyển đổi đơn vị vận chuyển thất bại';

                        if (!$cancel_orders_shop)
                            $message = 'Hủy đơn hàng id ' . $info_order->id . ' trên bảng tblcreate_order bằng chức năng chuyển đổi đơn vị vận chuyển thất bại';

                        fnLog($message);
                        return 'Cancel_Order_Systems_Failed';

                        break;

                    case 'VNC':

                        $data_default = $this->db->get('tbl_default_mass_volume_vnc')->row();
                        $dataLogin = array(
                            "USERNAME" => $data_default->username,
                            "PASSWORD" => base64_decode($data_default->password)
                        );
                        $token = loginVNC($dataLogin, URL_VNC . 'User/Login');

                        $data = array(
                            'Code' => explode('.', $info_create_order->code)[0]
                        );

                        $resultArr = $this->_api_vnc($data, $token, URL_VNC . 'Order/Cancel');

                        if (empty($resultArr)) {
                            fnLog('Dữ liệu API Hủy của VNC trả về rỗng khi sử dụng chức năng chuyển đổi đơn vị vận chuyển. Vui lòng kiểm tra lại');
                            return 'dataEmpty';
                        }


                        if ($resultArr['Result'] == 2) {
                            fnLog('Hủy đơn hàng trên VNC bằng chức năng chuyển đổi đơn vị vận chuyển thất bại. Với nội dung trả về như sau: ' . json_encode($resultArr));
                            return 'Cancel_Order_on_VNC_Failed';
                        }

                        // Hủy đơn hàng trên hệ thống
                        $data_cancel_create_order = array('status_cancel' => 1);
                        $this->db->where('id', $info_create_order->id);
                        $cancel = $this->db->update('tbl_create_order', $data_cancel_create_order);

                        $data_cancel_orders_shop = array('status' => 'Hủy');
                        $this->db->where('id', $info_order->id);
                        $cancel_orders_shop = $this->db->update('tblorders_shop', $data_cancel_orders_shop);

                        if ($cancel && $cancel_orders_shop) {
                            $required_code = $info_create_order->required_code;

                            // Tạo đơn hàng mới trên bảng orders_shop
                            // Khởi tạo dữ liệu
                            $data_orders_shop = array(
                                'shop' => $info_order->shop,
                                'code_orders' => $info_create_order->soc,
                                'status' => 'Đã Nhập Kho',
                                'date_create' => date('Y-m-d H:i:s'),
                                'mass' => $mass,
                                'collect' => $info_create_order->amount,
                                'value' => $info_create_order->value,
                                'prepay' => 0,
                                'receiver' => $info_create_order->name,
                                'phone' => $info_create_order->phone,
                                'address' => $info_create_order->address,
                                'ward' => $info_create_order->commune,
                                'district' => $info_create_order->district,
                                'city' => $info_create_order->province,
                                'note' => $info_create_order->note,
                                'warehouses' => $info_create_order->pickup_address,
                                'product' => $info_create_order->product,
                                'city_send' => 'Tỉnh Hải Dương',
                                'hd_fee_stam' => $info_create_order->supership_value,
                                'last_time_updated' => date('Y-m-d H:i:s'),
                                'DVVC' => $dvvcFinsh,
                                'is_hd_branch' => 1,
                                'payer' => ($info_create_order->payer == 1) ? 'Người Gửi' : '',
                                'sale' => 0,
                                'pack_data' => ($info_create_order->service == 1) ? 'Tốc Hành' : 'Tiết Kiệm',
                                'pay_refund' => 0,
                                'status_over' => 0,
                                'status_delay' => 0,
                                'warehouse_send' => get_option('warehouse_send'),
                                'mass_fake' => $mass_fake,
                                'required_code' => $required_code,
                                'region_id' => $info_create_order->region_id
                            );

                            $this->db->insert('tblorders_shop', $data_orders_shop);
                            $id_orders_shop = $this->db->insert_id();

                            if (!$id_orders_shop) {
                                fnLog('Tạo đơn mới trên bảng tblorders_shop sử dụng chức năng chuyển đổi đơn vị vận chuyển thất bại.');
                                return 'Insert_in_tblorders_shop_Failed';
                            }

                            // Tạo đơn hàng mới trên bảng create_order
                            // Khởi tạo dữ liệu
                            $data_create_order = array(
                                'customer_id' => $info_create_order->customer_id,
                                'supership_value' => $info_create_order->supership_value,
                                'cod' => $info_create_order->cod,
                                'pickup_address' => $info_create_order->pickup_address,
                                'pickup_province' => $info_create_order->pickup_province,
                                'pickup_district' => $info_create_order->pickup_district,
                                'pickup_commune' => $info_create_order->pickup_commune,
                                'pickup_phone' => $info_create_order->pickup_phone,
                                'name' => $info_create_order->name,
                                'phone' => $info_create_order->phone,
                                'sphone' => $info_create_order->sphone,
                                'address' => $info_create_order->address,
                                'province' => $info_create_order->province,
                                'district' => $info_create_order->district,
                                'commune' => $info_create_order->commune,
                                'amount' => $info_create_order->amount,
                                'weight' => $mass,
                                'volume' => $info_create_order->volume,
                                'soc' => $info_create_order->soc,
                                'note' => $info_create_order->note,
                                'service' => $info_create_order->service,
                                'config' => $info_create_order->config,
                                'payer' => $info_create_order->payer,
                                'product_type' => $info_create_order->product_type,
                                'product' => $info_create_order->product,
                                'barter' => $info_create_order->barter,
                                'value' => $info_create_order->value,
                                'code' => '',
                                'created' => '',
                                'user_created' => get_staff_user_id(),
                                'status_cancel' => 0,
                                'the_fee_bearer' => $info_create_order->the_fee_bearer,
                                'transport' => $transport == 0 || $transport == 1 ? 'road' : 'fly',
                                'ghtk' => $info_create_order->ghtk,
                                'token_ghtk' => $info_create_order->token_ghtk,
                                'orders_shop_id' => $id_orders_shop,
                                'dvvc' => $dvvcFinsh,
                                'required_code' => $required_code,
                                'mass_fake' => $mass_fake
                            );

                            $this->db->insert('tbl_create_order', $data_create_order);
                            $id_create_order = $this->db->insert_id();

                            if (!$id_create_order) {
                                fnLog('Tạo đơn mới trên bảng tbl_create_order sử dụng chức năng chuyển đổi đơn vị vận chuyển thất bại.');
                                return 'Insert_in_tbl_create_order_Failed';
                            }

                            $value = $info_create_order->value;
                            if ($value < 3000000) {
                                $valueNew = rand(2500000, 3000000);
                                if ($valueNew > $value) {
                                    $number2 = substr($valueNew, 3, 4);
                                    $value = $valueNew - $number2;
                                }
                            }

                            switch ($dvvcFinsh) {

                                case 'SPS':

                                    // Khởi tạo dữ liệu gửi lên API SPS
                                    $data_sps = array(
                                        'pickup_phone' => $info_customers->customer_phone,
                                        'pickup_address' => $info_create_order->pickup_address,
                                        'pickup_province' => $info_create_order->pickup_province,
                                        'pickup_district' => $info_create_order->pickup_district,
                                        'name' => $info_create_order->name,
                                        'phone' => $info_create_order->phone,
                                        'address' => $info_create_order->address,
                                        'province' => $info_create_order->province,
                                        'district' => $info_create_order->district,
                                        'amount' => $info_create_order->amount,
                                        'weight' => $mass_fake,
                                        'service' => $info_create_order->service,
                                        'config' => $info_create_order->config,
                                        'payer' => $info_create_order->payer,
                                        'product_type' => $info_create_order->product_type,
                                        'product' => $info_create_order->product,
                                        'sphone' => $info_create_order->sphone,
                                        'commune' => $info_create_order->commune,
                                        'value' => $value,
                                        'note' => $info_create_order->note,
                                        'barter' => $info_create_order->barter,
                                        'soc' => randerCode(3) . '-' . $info_create_order->soc
                                    );

                                    return $this->_create_order_SPS($data_sps, $info_customers->token_customer, $id_create_order, $id_orders_shop, $info_create_order, $mass, $dvvcSource, $dvvcFinsh);
                                    break;
                                case 'GHTK':

                                    $default_data = $query = $this->db->get('tbl_default_mass_volume_ghtk')->row();
                                    $codeNew = $default_data->code . randerCode(6);

                                    // Khởi tạo dữ liệu gửi lên API GHTK
                                    $data_ghtk = new stdClass();
                                    $product = new stdClass();
                                    $data_ghtk->products = [];

                                    $product->name = $info_create_order->product;
                                    $product->weight = (float)$mass_fake / 1000;
                                    $product->quantity = 1;
                                    array_push($data_ghtk->products, $product);
                                    $data_ghtk->order = new stdClass();
                                    $data_ghtk->order->id = $codeNew;
//                                    $data_ghtk->order->pick_address_id = $info_customers->address_id;
                                    $data_ghtk->order->pick_name = $info_customers->customer_shop_code;
                                    // $data_ghtk->order->pick_address = $data_create_order['pickup_address'];
                                    // $data_ghtk->order->pick_province = $data_create_order['pickup_province'];
                                    // $data_ghtk->order->pick_district = $data_create_order['pickup_district'];
                                    $data_ghtk->order->pick_tel = $info_warehouse_send->phone;
                                    $data_ghtk->order->tel = $data_create_order['phone'];
                                    $data_ghtk->order->name = $data_create_order['name'];
                                    $data_ghtk->order->address = $data_create_order['address'];
                                    $data_ghtk->order->province = $data_create_order['province'];
                                    $data_ghtk->order->district = $data_create_order['district'];
                                    $data_ghtk->order->ward = $data_create_order['commune'];
                                    $data_ghtk->order->is_freeship = 1;
                                    $data_ghtk->order->pick_money = $data_create_order['amount'];
                                    $data_ghtk->order->note = $data_create_order['note'];
                                    $data_ghtk->order->transport = $data_create_order['transport'];
                                    $data_ghtk->order->use_return_address = 0;
                                    $data_ghtk->order->value = $value;

                                    $data_ghtk->order->hamlet = "Hải dương";
                                    // Warehouse
                                    $data_ghtk->order->pick_address = $info_warehouse_send->nameAddress;
                                    $data_ghtk->order->pick_province = $info_warehouse_send->province;
                                    $data_ghtk->order->pick_district = $info_warehouse_send->district;
									$data_ghtk->order->pick_ward = $info_warehouse_send->commune;

                                    return $this->_create_order_GHTK($data_ghtk, $default_data->token_ghtk, $id_create_order, $id_orders_shop, $info_create_order, $mass, $dvvcSource, $dvvcFinsh, $codeNew);
                                    break;
                                case 'VTP':
                                    $codeNew = CODE_VTP . randerCode(2) . code(6);
                                    $data_default = $this->db->get('tbl_default_mass_volume_vpost')->row();
                                    $dataLogin = array(
                                        "USERNAME" => $data_default->username,
                                        "PASSWORD" => base64_decode($data_default->password)
                                    );
                                    $token = loginVP($dataLogin);

                                    $commue = explode(',', $info_create_order->pickup_address);
                                    $convertData = convertData($info_create_order->pickup_province, $info_create_order->pickup_district, $commue[count($commue) - 1], $info_create_order->province, $info_create_order->district, $info_create_order->commune);

                                    if ($convertData['sender_province'] == $convertData['receiver_province']) {
                                        $ORDER_SERVICE = 'PHS';
                                    } else {
                                        $ORDER_SERVICE = ($info_create_order->service == 1) ? 'VTK' : 'SCOD';
                                    }

                                    // Tạo dữ liệu gửi lên API của VTP
                                    $dataAPI = array(
                                        'ORDER_NUMBER' => $id_create_order,
                                        'GROUPADDRESS_ID' => $info_customers->address_id_vpost,
                                        'CUS_ID' => '',
                                        'SENDER_FULLNAME' => $info_customers->customer_shop_code,
                                        'SENDER_ADDRESS' => $info_create_order->pickup_address,
                                        'SENDER_PHONE' => $info_create_order->pickup_phone,
                                        'SENDER_EMAIL' => '',
                                        'SENDER_WARD' => $convertData['sender_wards'],
                                        'SENDER_DISTRICT' => $convertData['sender_district'],
                                        'SENDER_PROVINCE' => $convertData['sender_province'],
                                        'RECEIVER_FULLNAME' => $info_create_order->name,
                                        'RECEIVER_ADDRESS' => $info_create_order->address,
                                        'RECEIVER_PHONE' => $info_create_order->phone,
                                        'RECEIVER_EMAIL' => '',
                                        'RECEIVER_WARD' => $convertData['receiver_ward'],
                                        'RECEIVER_DISTRICT' => $convertData['receiver_district'],
                                        'RECEIVER_PROVINCE' => $convertData['receiver_province'],
                                        'ORDER_PAYMENT' => 3,
                                        'ORDER_SERVICE' => $ORDER_SERVICE,
                                        'ORDER_SERVICE_ADD' => '',
                                        'ORDER_VOUCHER' => '',
                                        'ORDER_NOTE' => $info_create_order->note,
                                        'MONEY_COLLECTION' => $info_create_order->amount,
                                        'MONEY_TOTALFEE' => '',
                                        'MONEY_FEECOD' => 0,
                                        'MONEY_FEEVAS' => 0,
                                        'MONEY_FEEINSURRANCE' => 0,
                                        'MONEY_FEE' => 0,
                                        'MONEY_FEEOTHER' => 0,
                                        'MONEY_TOTALVAT' => 0,
                                        'MONEY_TOTAL' => 0,
                                        'PRODUCT_TYPE' => 'HH',
                                        'PRODUCT_NAME' => $info_create_order->product,
                                        'PRODUCT_DESCRIPTION' => '',
                                        'PRODUCT_WEIGHT' => $mass_fake,
                                        'PRODUCT_QUANTITY' => 1,
                                        'PRODUCT_PRICE' => $value
                                    );

                                    return $this->_create_order_VTP($dataAPI, $token, $id_create_order, $id_orders_shop, $info_create_order, $mass, $dvvcSource, $dvvcFinsh, $codeNew);

                                    break;

                                case 'VNC':

                                    $data_default = $this->db->get('tbl_default_mass_volume_vnc')->row();
                                    $dataLogin = array(
                                        "USERNAME" => $data_default->username,
                                        "PASSWORD" => base64_decode($data_default->password)
                                    );

                                    $token = loginVNC($dataLogin, URL_VNC . 'User/Login');

                                    $codeNew = $data_default->code . randerCode(6);

                                    $warehouser = $this->db->get('tbl_warehouse_send')->row();

                                    $dataAPI = array(
                                        'Code' => $codeNew,
                                        'ProductName' => $info_create_order->product,
                                        'CollectAmount' => $info_create_order->amount,
                                        'JourneyType' => 1,
                                        'ServiceId' => $transport == 0 || $transport == 1 ? 12491 : 12490,
                                        'Weight' => $mass_fake,
                                        'Note' => $info_create_order->note,
                                        'NumberOfProducts' => 1,
                                        'SourceCity' => $warehouser->province,
                                        'SourceDistrict' => $warehouser->district,
                                        'SourceWard' => $warehouser->commune,
                                        'SourceAddress' => $warehouser->nameAddress,
                                        'SourceName' => $info_customers->customer_shop_code,
                                        'SourcePhoneNumber' => $warehouser->phone,
                                        'DestCity' => $info_create_order->province,
                                        'DestDistrict' => $info_create_order->district,
                                        'DestWard' => $info_create_order->commune,
                                        'DestAddress' => $info_create_order->commune . ', ' . $info_create_order->district . ', ' . $info_create_order->province,
                                        'DestName' => $info_create_order->name,
                                        'DestPhoneNumber' => $info_create_order->phone,
                                        'Width' => 0,
                                        'Height' => 0,
                                        'Length' => 0,
                                        'ProductPrice' => $value
                                    );

                                    return $this->_create_order_VNC($dataAPI, $token, $id_create_order, $id_orders_shop, $info_create_order, $mass, $dvvcSource, $dvvcFinsh, $codeNew);

                                    break;
                                case 'NB':

                                    $today = date('Y-m-d H:i:s');
                                    $code = CODE_NB.'.'.randerCode(2).code(6);

                                    // Tạo dữ liệu cập nhật bảng create_order
                                    $data_update_create_order = array(
                                        'created' => $today,
                                        'code' => $code
                                    );

                                    $this->db->where('id', $id_create_order);
                                    $update = $this->db->update('tbl_create_order', $data_update_create_order);


                                    // Khởi tạo biến dữ liệu cập nhật vào bảng tblorders_shop
                                    $data_update_orders_shop = array(
                                        'code_supership' => $code
                                    );
                                    $this->db->where('id', $id_orders_shop);
                                    $update_orders_shop = $this->db->update('tblorders_shop', $data_update_orders_shop);

                                    if ($update && $update_orders_shop) {
                                        // Lưu lịch sử chuyển đổi
                                        $data_history = array(
                                            'date_create' => date('Y-m-d H:i:s'),
                                            'code_old' => $info_create_order->code,
                                            'code_new' => $code,
                                            'mass' => $mass,
                                            'dvvc_source' => $dvvcSource,
                                            'dvvc_finsh' => $dvvcFinsh,
                                            'orders_id' => $id_create_order
                                        );

                                        $this->db->insert('tbl_history_change', $data_history);
                                        fnLog('Mã đơn hàng mới: ' . $code);
                                        return $code;
                                    }

                                    $message = '';
                                    if (!$update)
                                        $message = 'Cập nhật đơn hàng id ' . $id_create_order . ' trên bảng tbl_create_order thông qua chức năng chuyển đổi đơn hàng thất bại.';
                                    if (!$update_orders_shop)
                                        $message = 'Cập nhật đơn hàng id ' . $id_orders_shop . ' trên bảng tblorders_shop thông qua chức năng chuyển đổi đơn hàng thất bại.';
                                    fnLog($message);
                                    return 'Update_order_Failed';

                                    break;
                                default:
                                    return 'DVVCnoSupport';
                                    break;

                            }


                        }

                        $message = '';
                        if (!$cancel)
                            $message = 'Hủy đơn hàng id ' . $info_create_order->id . ' trên bảng tblcreate_order bằng chức năng chuyển đổi đơn vị vận chuyển thất bại';

                        if (!$cancel_orders_shop)
                            $message = 'Hủy đơn hàng id ' . $info_order->id . ' trên bảng tblcreate_order bằng chức năng chuyển đổi đơn vị vận chuyển thất bại';

                        fnLog($message);
                        return 'Cancel_Order_Systems_Failed';

                        break;

                        break;

                    default:
                        break;
                }

                break;

        }

    }

//    =================================================================================

    /**
     * Đây là hàm tạo đơn trên SPS
     * @param $data là dữ liệu gửi lên API
     * @param $token là mã token của thành viên
     * @param $id_create_order là id của đơn hàng mới trên bảng create_order
     * @param $id_orders_shop là id của đơn hàng mới trên bảng orders_shop
     * @param $info_create_order là thông tin đơn hàng của đơn hàng mới trên bảng create_order
     * @param $mass là khối lượng sản phẩm
     * @param $dvvcSource là đơn vị vận chuyển ban đầu
     * @param $dvvcFinsh là đơn vị vận chuyển chuyển đến
     */
    private function _create_order_SPS($data, $token, $id_create_order, $id_orders_shop, $info_create_order, $mass, $dvvcSource, $dvvcFinsh)
    {
        $resultAPI = self::api_sps($data, $token, true);

        if (empty($resultAPI)) {
            fnLog('Dữ liệu API của SPS trả về rỗng khi sử dụng chức năng chuyển đổi đơn vị vận chuyển. Vui lòng kiểm tra lại');
            return 'dataEmpty';
        }

        $resultArr = json_decode($resultAPI, true);

        if ($resultArr['status'] == 'Error') {

            $this->db->delete('tbl_create_order', ['id' => $id_create_order]);
            $this->db->delete('tblorders_shop', ['id' => $id_orders_shop]);

            fnLog('Tạo đơn trên hệ thống SPS thông qua chức năng chuyển đổi đơn vị với thông tin lỗi: ' . $resultAPI);
            return 'Create_order_on_SPS_Failed';
        }

        $today = date('Y-m-d H:i:s');
        $code = $resultArr['results']['code'];

        // Tạo dữ liệu cập nhật bảng create_order
        $data_update_create_order = array(
            'created' => $today,
            'code' => $code
        );

        $this->db->where('id', $id_create_order);
        $update = $this->db->update('tbl_create_order', $data_update_create_order);


        // Khởi tạo biến dữ liệu cập nhật vào bảng tblorders_shop
        $data_update_orders_shop = array(
            'code_supership' => $code
        );
        $this->db->where('id', $id_orders_shop);
        $update_orders_shop = $this->db->update('tblorders_shop', $data_update_orders_shop);

        if ($update && $update_orders_shop) {
            // Lưu lịch sử chuyển đổi
            $data_history = array(
                'date_create' => date('Y-m-d H:i:s'),
                'code_old' => $info_create_order->code,
                'code_new' => $code,
                'mass' => $mass,
                'dvvc_source' => $dvvcSource,
                'dvvc_finsh' => $dvvcFinsh,
                'orders_id' => $id_create_order
            );

            $this->db->insert('tbl_history_change', $data_history);
            fnLog('Mã đơn hàng mới: ' . $code);
            return $code;
        }

        $message = '';
        if (!$update)
            $message = 'Cập nhật đơn hàng id ' . $id_create_order . ' trên bảng tbl_create_order thông qua chức năng chuyển đổi đơn hàng thất bại.';
        if (!$update_orders_shop)
            $message = 'Cập nhật đơn hàng id ' . $id_orders_shop . ' trên bảng tblorders_shop thông qua chức năng chuyển đổi đơn hàng thất bại.';
        fnLog($message);
        return 'Update_order_Failed';
    }

    /**
     * Đây là hàm tạo đơn trên GHTK
     * @param $data là dữ liệu gửi lên API
     * @param $token là mã token của thành viên
     * @param $id_create_order là id của đơn hàng mới trên bảng create_order
     * @param $id_orders_shop là id của đơn hàng mới trên bảng orders_shop
     * @param $info_create_order là thông tin đơn hàng của đơn hàng mới trên bảng create_order
     * @param $mass là khối lượng sản phẩm
     * @param $dvvcSource là đơn vị vận chuyển ban đầu
     * @param $dvvcFinsh là đơn vị vận chuyển chuyển đến
     * @param $codeNew là mã ngắn của đơn hàng
     */
    private function _create_order_GHTK($data, $token, $id_create_order, $id_orders_shop, $info_create_order, $mass, $dvvcSource, $dvvcFinsh, $codeNew)
    {
        $resposenAPI = self::api_ghtk($data, $token, true);

        if (empty($resposenAPI)) {
            fnLog('Dữ liệu API của GHTK trả về rỗng khi sử dụng chức năng chuyển đổi đơn vị vận chuyển. Vui lòng kiểm tra lại');
            return 'dataEmpty';
        }

        $resultArr = json_decode($resposenAPI, true);

        if ($resultArr['success'] == false) {
            $this->db->delete('tbl_create_order', ['id' => $id_create_order]);
            $this->db->delete('tblorders_shop', ['id' => $id_orders_shop]);

            fnLog('Tạo đơn trên hệ thống GHTK thông qua chức năng chuyển đổi đơn vị với thông tin lỗi: ' . $resposenAPI);
            return 'Create_order_on_GHTK_Failed';
        }

        // Cập nhật đơn hàng
        $today = date('Y-m-d H:i:s');
        $codeArr = explode('.', $resultArr['order']['label']);
        $code = $codeNew . '.' . $codeArr[count($codeArr) - 1];

        $data_update_create_order = array(
            'created' => $today,
            'code' => $code,
            'token_ghtk' => $token
        );

        $this->db->where('id', $id_create_order);
        $update = $this->db->update('tbl_create_order', $data_update_create_order);


        $data_update_orders_shop = array(
            'code_supership' => $code,
            'pay_transport' => $resultArr['order']['fee'],
            'insurance' => $resultArr['order']['insurance_fee'],
            'code_ghtk' => $resultArr['order']['label']
        );

        $this->db->where('id', $id_orders_shop);
        $update_orders_shop = $this->db->update('tblorders_shop', $data_update_orders_shop);

        if ($update && $update_orders_shop) {
            // Lưu lịch sử chuyển đổi
            $data_history = array(
                'date_create' => date('Y-m-d H:i:s'),
                'code_old' => $info_create_order->code,
                'code_new' => $code,
                'mass' => $mass,
                'dvvc_source' => $dvvcSource,
                'dvvc_finsh' => $dvvcFinsh,
                'orders_id' => $id_create_order
            );

            $this->db->insert('tbl_history_change', $data_history);
            fnLog('Mã đơn hàng mới: ' . $code);
            return $code;
        }

        $message = '';
        if (!$update)
            $message = 'Cập nhật đơn hàng id ' . $id_create_order . ' trên bảng tbl_create_order thông qua chức năng chuyển đổi đơn hàng thất bại.';

        if (!$update_orders_shop)
            $message = 'Cập nhật đơn hàng id ' . $id_orders_shop . ' trên bảng tblorders_shop thông qua chức năng chuyển đổi đơn hàng thất bại.';

        fnLog($message);
        return 'Update_order_Failed';
    }

    /**
     * Đây là hàm tạo đơn trên VTP
     * @param $data là dữ liệu gửi lên API
     * @param $token là mã token của thành viên
     * @param $id_create_order là id của đơn hàng mới trên bảng create_order
     * @param $id_orders_shop là id của đơn hàng mới trên bảng orders_shop
     * @param $info_create_order là thông tin đơn hàng của đơn hàng mới trên bảng create_order
     * @param $mass là khối lượng sản phẩm
     * @param $dvvcSource là đơn vị vận chuyển ban đầu
     * @param $dvvcFinsh là đơn vị vận chuyển chuyển đến
     * @param $codeNew là mã ngắn của đơn hàng
     */
    private function _create_order_VTP($data, $token, $id_create_order, $id_orders_shop, $info_create_order, $mass, $dvvcSource, $dvvcFinsh, $codeNew)
    {
        $resultArr = $this->_api_viettel($data, $token, 'https://partner.viettelpost.vn/v2/order/createOrder');

        if (empty($resultArr)) {
            fnLog('Dữ liệu API của VTP trả về rỗng khi sử dụng chức năng chuyển đổi đơn vị vận chuyển. Vui lòng kiểm tra lại');
            return 'dataEmpty';
        }


        if ($resultArr['status'] != 200) {
            $this->db->delete('tbl_create_order', ['id' => $id_create_order]);
            $this->db->delete('tblorders_shop', ['id' => $id_orders_shop]);

            fnLog('Tạo đơn trên hệ thống VTP thông qua chức năng chuyển đổi đơn vị với thông tin lỗi: ' . json_encode($resultArr));
            return 'Create_order_on_VTP_Failed';
        }


        $today = date('Y-m-d H:i:s');
        $resultData = $resultArr['data'];
        $codeVTP = $codeNew . '.' . $resultData['ORDER_NUMBER'];

        $data_update_create_order = array(
            'code' => $codeVTP,
            'user_created' => get_staff_user_id(),
            'created' => $today,
            'supership_value' => $resultData['MONEY_TOTAL']
        );

        $this->db->where('id', $id_create_order);
        $update = $this->db->update('tbl_create_order', $data_update_create_order);


        $data_orders_shop = array(
            'code_supership' => $codeVTP,
            'pay_transport' => $resultData['MONEY_TOTAL'],
            'code_ghtk' => $resultData['ORDER_NUMBER']
        );

        $this->db->where('id', $id_orders_shop);
        $update_orders_shop = $this->db->update('tblorders_shop', $data_orders_shop);

        if ($update && $update_orders_shop) {
            // Lưu lịch sử chuyển đổi
            $data_history = array(
                'date_create' => date('Y-m-d H:i:s'),
                'code_old' => $info_create_order->code,
                'code_new' => $codeVTP,
                'mass' => $mass,
                'dvvc_source' => $dvvcSource,
                'dvvc_finsh' => $dvvcFinsh,
                'orders_id' => $id_create_order
            );

            $this->db->insert('tbl_history_change', $data_history);
            fnLog('Mã đơn hàng mới: ' . $codeVTP);
            return $codeVTP;
        }

        $message = '';
        if (!$update)
            $message = 'Cập nhật đơn hàng id ' . $id_create_order . ' trên bảng tbl_create_order thông qua chức năng chuyển đổi đơn hàng thất bại.';

        if (!$update_orders_shop)
            $message = 'Cập nhật đơn hàng id ' . $id_orders_shop . ' trên bảng tblorders_shop thông qua chức năng chuyển đổi đơn hàng thất bại.';

        fnLog($message);
        return 'Update_order_Failed';
    }

    /**
     * Đây là hàm tạo đơn trên VNC
     * @param $data là dữ liệu gửi lên API
     * @param $token là mã token của thành viên
     * @param $id_create_order là id của đơn hàng mới trên bảng create_order
     * @param $id_orders_shop là id của đơn hàng mới trên bảng orders_shop
     * @param $info_create_order là thông tin đơn hàng của đơn hàng mới trên bảng create_order
     * @param $mass là khối lượng sản phẩm
     * @param $dvvcSource là đơn vị vận chuyển ban đầu
     * @param $dvvcFinsh là đơn vị vận chuyển chuyển đến
     * @param $codeNew là mã ngắn của đơn hàng
     */
    private function _create_order_VNC($data, $token, $id_create_order, $id_orders_shop, $info_create_order, $mass, $dvvcSource, $dvvcFinsh, $codeNew)
    {
        $resultArr = $this->_api_vnc($data, $token, URL_VNC . 'Order/Add');

        if (empty($resultArr)) {
            fnLog('Dữ liệu API của VTP trả về rỗng khi sử dụng chức năng chuyển đổi đơn vị vận chuyển. Vui lòng kiểm tra lại');
            return 'dataEmpty';
        }


        if ($resultArr['Result'] == 2) {
            $this->db->delete('tbl_create_order', ['id' => $id_create_order]);
            $this->db->delete('tblorders_shop', ['id' => $id_orders_shop]);

            fnLog('Tạo đơn trên hệ thống VTP thông qua chức năng chuyển đổi đơn vị với thông tin lỗi: ' . json_encode($resultArr));
            return 'Create_order_on_VNC_Failed';
        }


        $today = date('Y-m-d H:i:s');
        $codeVTP = $codeNew . '.' . $resultArr['Code'];

        $data_update_create_order = array(
            'code' => $codeVTP,
            'user_created' => get_staff_user_id(),
            'created' => $today
        );

        $this->db->where('id', $id_create_order);
        $update = $this->db->update('tbl_create_order', $data_update_create_order);


        $data_orders_shop = array(
            'code_supership' => $codeVTP,
            'code_ghtk' => $resultArr['Code']
        );

        $this->db->where('id', $id_orders_shop);
        $update_orders_shop = $this->db->update('tblorders_shop', $data_orders_shop);

        if ($update && $update_orders_shop) {
            // Lưu lịch sử chuyển đổi
            $data_history = array(
                'date_create' => date('Y-m-d H:i:s'),
                'code_old' => $info_create_order->code,
                'code_new' => $codeVTP,
                'mass' => $mass,
                'dvvc_source' => $dvvcSource,
                'dvvc_finsh' => $dvvcFinsh,
                'orders_id' => $id_create_order
            );

            $this->db->insert('tbl_history_change', $data_history);
            fnLog('Mã đơn hàng mới: ' . $codeVTP);
            return $codeVTP;
        }

        $message = '';
        if (!$update)
            $message = 'Cập nhật đơn hàng id ' . $id_create_order . ' trên bảng tbl_create_order thông qua chức năng chuyển đổi đơn hàng thất bại.';

        if (!$update_orders_shop)
            $message = 'Cập nhật đơn hàng id ' . $id_orders_shop . ' trên bảng tblorders_shop thông qua chức năng chuyển đổi đơn hàng thất bại.';

        fnLog($message);
        return 'Update_order_Failed';
    }



//    =================================================================================

    /**
     * This function api_sps
     * @param $data is array
     * @param $token is token of customers
     * @param $active is active of function
     * @return json.
     */
    private static function api_sps($data, $token, $active = false)
    {
        if (empty($active)) {
            // Cancel
            $url = 'https://api.mysupership.vn/v1/partner/orders/cancel';
        } else {
            // Create
            $url = 'https://api.mysupership.vn/v1/partner/orders/add';
        }
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Authorization: Bearer ' . $token;
        $headers[] = 'Content-Type: application/json';
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    /**
     * This function api_ghtk
     * @param $data is array
     * @param $token is token of customers
     * @param $active is active of function
     * @return json.
     */
    private static function api_ghtk($data, $token, $active = false)
    {
        if (empty($active)) {
            $url = 'https://services.giaohangtietkiem.vn/services/shipment/cancel/' . $data['code'];

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
                CURLOPT_HTTPHEADER => array(
                    "Token: " . $token,
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
        } else {
            $url = 'https://services.giaohangtietkiem.vn/services/shipment/order';
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($curl, CURLOPT_POST, 1);

            $headers = [];
            $headers[] = 'Token:' . $token;
            $headers[] = 'Accept: application/json';
            $headers[] = 'Content-Type: application/json';
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($curl);
            curl_close($curl);
        }
        return $response;
    }

    /**
     * This function api Viettel Post
     * @param $data is array data
     * @param $token is token of account
     * @param $url is url call
     */
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


    /**
     * Đây là hàm api VNC
     * @param $data is array data
     * @param $token is token of account
     * @param $url is url call
     **/
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

    //==========================Tính phí===================================================================
    private function _checkAndReplaceStam($shop, $province, $district, $mass, $total, $value, $price = 0)
    {
        $id = null;

        if ($this->get_customer_id($shop) !== null) {
            $id = $this->get_customer_id($shop)->id;
        }

        if ($id !== null) {
            $policy_id = $this->get_policy_id($id);
        } else {
            $policy_id = false;
        }

        if ($policy_id === false) {
            $region = $this->search_region($province, $district, $policy_id);

            $data_for_calc = $region;

            $supership_cost = (float)$data_for_calc->price_region;

            $massInput = (int)$mass;

            $massFree = (int)($data_for_calc->mass_region);

            $masscalc = ($massInput - $massFree) / (int)$data_for_calc->mass_region_free;

            if ($masscalc <= 0) {
                $masscalc = 0;
            } else {
                $masscalc = ceil($masscalc) * (int)$data_for_calc->price_over_mass_region;
            }

            $volumecalc = 0;

            if ($masscalc > $volumecalc) {
                $supership_cost += $masscalc;
            } elseif ($masscalc < $volumecalc) {
                $supership_cost += $volumecalc;
            } else {
                $supership_cost += $masscalc;
            }
            //Tính bảo hiểm
            if ($total > $value) {
                if ($total > (int)$data_for_calc->amount_of_free_insurance) {
                    $insurance = ($total / 100) * (int)$data_for_calc->insurance_price;
                    $insurance = round($insurance / 1000) * 1000;
                } else {
                    $insurance = 0;
                }
            } else {
                if ($value > (int)$data_for_calc->amount_of_free_insurance) {
                    $insurance = ($value / 100) * (int)$data_for_calc->insurance_price;
                    $insurance = round($insurance / 1000) * 1000;
                } else {
                    $insurance = 0;
                }
            }

            $supership_cost = $supership_cost + $insurance + $price;
        } else {
            $region = $this->search_region($province, $district, $policy_id);

            $data_for_calc = null;

            if ($region !== null) {
                $data_for_calc = $region->data_region;
            }

            if ($data_for_calc !== null) {
                $supership_cost = (float)$data_for_calc->price_region;

                //Tính khối lượng
                $massInput = (int)$mass;

                $massFree = (int)($data_for_calc->mass_region);

                $masscalc = ($massInput - $massFree) / (int)$data_for_calc->mass_region_free;

                if ($masscalc <= 0) {
                    $masscalc = 0;
                } else {
                    $masscalc = ceil($masscalc) * (int)$data_for_calc->price_over_mass_region;
                }

                $volumecalc = 0;

                if ($masscalc > $volumecalc) {
                    $supership_cost += (float)$masscalc;
                } elseif ($masscalc < $volumecalc) {
                    $supership_cost += (float)$volumecalc;
                } else {
                    $supership_cost += (float)$masscalc;
                }

                //Tính bảo hiểm
                if ($total > $value) {
                    if ($total > (int)$data_for_calc->amount_of_free_insurance) {
                        $insurance = ($total / 100) * (int)$data_for_calc->insurance_price;
                        $insurance = round($insurance / 1000) * 1000;
                    } else {
                        $insurance = 0;
                    }
                } else {
                    if ($value > (int)$data_for_calc->amount_of_free_insurance) {
                        $insurance = ($value / 100) * (int)$data_for_calc->insurance_price;
                        $insurance = round($insurance / 1000) * 1000;
                    } else {
                        $insurance = 0;
                    }
                }

                $supership_cost = $supership_cost + $insurance + $price;

            } else {
                $supership_cost = 0;
            }
        }

        return $supership_cost;
    }

    private function get_customer_id($shop = '')
    {
        $this->db->select('id');
        $this->db->where('customer_shop_code', $shop);
        $this->db->from('tblcustomers');

        return $this->db->get()->row();
    }

    private function get_policy_id($id)
    {
        $this->db->select('*');
        $this->db->where('customer_id', $id);
        $search_result = $this->db->get('tblcustomer_policy')->row();

        if (empty($search_result)) {
            return false;
        }

        return $search_result->id;

        //
    }

    private function search_region($province, $district, $policy_id)
    {
        $region = null;
        $this->db->select('*');
        $this->db->where('city', trim($province));
        $this->db->where('district', trim($district));
        $search_result = $this->db->get('tblregion_excel')->row();

        if ($search_result !== null) {
            $this->db->select('*');
            $this->db->where('id', $search_result->region_id);
            $region = $this->db->get('tbldeclared_region')->result()[0];

            $this->db->select('*');
            $this->db->where('id_policy', $policy_id);
            $this->db->where('id_region', $region->id);
            $data_region = $this->db->get('tbldata_region')->row();
            $region->data_region = $data_region;
        }

        return $region;
    }

}
