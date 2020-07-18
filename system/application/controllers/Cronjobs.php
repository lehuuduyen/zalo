<?php



class Cronjobs extends CI_Controller

{

    public function __construct()

    {

        parent::__construct();

    }



    public function updateSPS()

    {

        ini_set('max_execution_time', 99999999);

        error_reporting(E_ALL);

        ini_set('display_errors', 'On');

        ini_set('memory_limit', '-1');



        /**

         * 1. hd_fee_stam = null

         */

        $this->db->where('hd_fee_stam', null);

        if (in_array($_SERVER['HTTP_HOST'], array('spshd.test.lediun.com')))

            $this->db->limit(10, 0);

        $this->db->from('tblorders_shop');

        $list_order_hd_fee_stam = $this->db->get()->result();



        foreach ($list_order_hd_fee_stam as $order_hd_fee) {



            $this->db->where('orders_shop_id', $order_hd_fee->id);

            $info_order = $this->db->get('tbl_create_order')->result();

            $price = 0;

            if ($info_order) {

                $price = $info_order[0]->price;

            }



            $this->_checkAndReplaceStam($order_hd_fee->code_supership, $order_hd_fee->id, $order_hd_fee, $price);

        }



        /**

         * 2. hd_fee is NULL

         */

        // TH1

        $nameStatus = ['Đã Đối Soát Giao Hàng', 'Đã Giao Hàng Toàn Bộ', 'Đã Giao Hàng Một Phần'];

        $this->db->where_in('status', $nameStatus);

        $this->db->where('hd_fee', null);



        if (in_array($_SERVER['HTTP_HOST'], array('spshd.test.lediun.com')))

            $this->db->limit(10, 0);



        $this->db->from('tblorders_shop');

        $list_order_hd_fee1 = $this->db->get()->result();

        foreach ($list_order_hd_fee1 as $order_hd_fee) {



            $this->db->where('orders_shop_id', $order_hd_fee->id);

            $info_order = $this->db->get('tbl_create_order')->result();

            $price = 0;

            if ($info_order) {

                $price = $info_order[0]->price;

            }



            $this->_checkAndReplace($order_hd_fee->code_supership, $order_hd_fee->id, $order_hd_fee, $price);

        }



        // TH2

        unset($nameStatus);

        $nameStatus = ['Không Giao Được',

            'Xác Nhận Hoàn',

            'Đang Trả Hàng',

            'Đang Chuyển Kho Trả',

            'Đã Đối Soát Trả Hàng',

            'Đã Chuyển Kho Trả',

            'Đã Trả Hàng','Đã Trả Hàng Toàn Bộ',

            'Hoãn Trả Hàng', 'Đã Trả Hàng Một Phần','Đã Chuyển Kho Trả Toàn Bộ','Đang Trả Hàng Toàn Bộ'];

        $this->db->where_in('status', $nameStatus);

        $this->db->where('hd_fee', null);



        if (in_array($_SERVER['HTTP_HOST'], array('spshd.test.lediun.com')))

            $this->db->limit(10, 0);



        $this->db->from('tblorders_shop');

        $list_order_hd_fee2 = $this->db->get()->result();

        foreach ($list_order_hd_fee2 as $order_hd_fee) {

            $this->db->where('orders_shop_id', $order_hd_fee->id);

            $info_order = $this->db->get('tbl_create_order')->result();

            $price = 0;

            if ($info_order) {

                $price = $info_order[0]->price;

            }

            $this->checkAndReplace_fee($order_hd_fee->code_supership, $order_hd_fee->id, $order_hd_fee, $price);

        }



    }





	/**

     * This function update region_id in tbl_create_order

     */

    public function updateRegion_id()

    {

        $this->db->where('region_id', 0);

        $this->db->limit(100, 0);

        $list_order = $this->db->get('tbl_create_order')->result();

		//        pre($this->db->last_query());

        $success = $errors = array();

        foreach ($list_order as $order){

            $this->db->select('*');

            $this->db->where('city', $order->province);

            $this->db->where('district', $order->district);

            $search_result = $this->db->get('tblregion_excel')->row();

            if ($search_result) {

                $this->db->select('*');

                $this->db->where('id', $search_result->region_id);

                $region = $this->db->get('tbldeclared_region')->row();



                $data = array('region_id' => $region->id);

                $this->db->where('id', $order->id);

                if(!$this->db->update('tbl_create_order', $data)){

                    array_push($errors, 'Cập nhật thất bại đơn id '.$order->id);

                }



                array_push($success, 'Cập nhật thành công đơn id '.$order->id);

            }

        }



        if(!empty($errors)){

            echo 'Số đơn cập nhật thất bại:' .count($errors);

        }



        if(!empty($success)){

            echo 'Số đơn cập nhật thành công:' .count($success);

        }

    }







/**

     * Đây là hàm xử lý thông tin đơn hàng VNC

     */

    public function updateVNC()

    {

        $now = date('Y-m-d H:i:s');



        // array Status in table tbldeclare

        $arrStatusDB = getArrDataInTbldeclare();

        $dataArrStatus = array();



        foreach ($arrStatusDB as $status) {

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

                AND `DVVC` = "VNC"

                AND `date_create` >= "' . date("Y-m-d H:i:s", strtotime("$now - 90 day")) . '"

				AND `date_create` < "' . date("Y-m-d H:i:s", mktime(date('H'), date('i') - 15, date('s'), date('m'), date('d'), date('Y'))) . '" ORDER BY id DESC';



        $isTest = $this->input->get('Test');

        $acTest = false;

        if ($isTest === 'true') {

            $sql .= ' LIMIT 10';

            $acTest = true;

        }

        // pre($sql);



        $list_order = $this->db->query($sql)->result();

        $errors = $success = $deadlineFail = array();



        if (!empty($list_order)) {



            $this->load->model('orders_change_weight_model');

            $data_default = $this->db->get('tbl_default_mass_volume_vnc')->row();

            $dataLogin = array(

                "USERNAME" => $data_default->username,

                "PASSWORD" => base64_decode($data_default->password)

            );



            $token = loginVNC($dataLogin, URL_VNC . 'User/Login');





            foreach ($list_order as $order) {

//                if($order->code_ghtk!="84855073903526")

//                {

//                    continue;

//                }

                // Select info new of order

                $this->db->where('id', $order->id);

                $info_order = $this->db->get('tblorders_shop')->row();

                if (!in_array($info_order->status, $arrStatusDB)) {

                    $data = array('Code' => $order->code_ghtk);

                    $curl = curl_init();

                    curl_setopt_array($curl, array(

                        CURLOPT_URL => URL_VNC_TRACKING . 'Track/Order',

                        CURLOPT_RETURNTRANSFER => true,

                        CURLOPT_ENCODING => "",

                        CURLOPT_MAXREDIRS => 10,

                        CURLOPT_TIMEOUT => 0,

                        CURLOPT_FOLLOWLOCATION => true,

                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

                        CURLOPT_CUSTOMREQUEST => "POST",

                        CURLOPT_POSTFIELDS => json_encode($data),

                        CURLOPT_HTTPHEADER => array(

                            "Authorization: Bearer " . $token,

                            "Content-Type: application/json"

                        ),

                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);

                    $arr = json_decode($response, true);



                    if ($arr['Result'] == 1) {

                        $dataUpdate = array(

                            'mass_fake' => $arr['Weight'],

                            'collect' => explode('.', $arr['CollectAmount'])[0],

                            'value' => explode('.', $arr['ProductPrice'])[0],

                            'pay_transport' => explode('.', $arr['FreightFeeVAT'])[0]

                        );

                        // Status Order

                        $TrackAndTrace = $arr['TrackAndTrace'];

                        $arrStatus = array();

                        $dataTrack = $TrackAndTrace[count($TrackAndTrace) - 1];

                        $StatusOrder = $dataTrack['Status'];



                        foreach ($TrackAndTrace as $track) {

                            if (!is_null($track['Status'])) {

                                array_push($arrStatus, $track['Status']);

                            }

                        }



                        if (!is_null($StatusOrder)) {

                            $this->db->where('status_ghtk', $StatusOrder);

                            $result_Status = $this->db->get('tblstatus_order')->row();

                        } else {

                            $this->db->where('status_ghtk', $arrStatus[count($arrStatus) - 1]);

                            $result_Status = $this->db->get('tblstatus_order')->row();

                        }



                        $datePre = preg_replace("/[^a-zA-Z0-9_.-\s]/", ' ', html_entity_decode($dataTrack['CreatedTimeDisplay2']));

                        $arrDate = explode(' ', $datePre);



                        $date = $arrDate[0] . '-' . $arrDate[1] . '-' . $arrDate[2] . ' ' . $arrDate[4] . ':' . $arrDate[5] . ':' . $arrDate[6];



                        $dataUpdate['delivery_delay_time'] = $date;

                        $dataUpdate['last_time_updated'] = $date;

                        $dataUpdate['status'] = (!empty($result_Status)) ? $result_Status->status_change : $order->status;





                        // Date_debit

                        if (empty($order->group_debits)) {

                            if (!empty($result_Status->status_debit) && is_null($order->date_debits)) {

                                $dataUpdate['date_debits'] = date('Y-m-d H:m:s');

                            }

                        } elseif ($order->group_debits == 1) {

                            if (!empty($result_Status->group_debits) && is_null($order->date_debits)) {

                                $dataUpdate['date_debits'] = date('Y-m-d H:m:s');

                            }

                        }



                        // Weight

                        if ($arr['Weight'] > $order->mass) {

                            $dataUpdate['mass'] = $arr['Weight'];



                            $data_insert = [

                                'order_shop_id' => $order->id,

                                'shop_name' => $order->shop,

                                'code' => $order->code_supership,

                                'old_weight' => $order->mass,

                                'new_weight' => $arr['Weight'],

                                'created_date' => date('Y-m-d H:i:s'),

                            ];

                            $this->orders_change_weight_model->insert($data_insert);

                        }



                        // Collect Amount

                        if ((float)$order->collect != (float)explode('.', $arr['CollectAmount'])[0]) {

                            $data_insert_money = [

                                'order_shop_id' => $order->id,

                                'shop_name' => $order->shop,

                                'code' => $order->code_supership,

                                'old_money' => $order->collect,

                                'new_money' => explode('.', $arr['CollectAmount'])[0],

                                'created_date' => date('Y-m-d H:i:s'),

                            ];

                            $this->orders_change_weight_model->insert_money($data_insert_money);

                        }





                        //

                        // if(stripos($dataTrack['Name'], 'Đã thanh toán đối soát') !== false && is_null($order->control_date)){

                        // $dataUpdate['control_date'] = $date;

                        // }



                        //cap nhat deadline

                        $getDbDeadline = $this->getDeadlineByDVVC('VNC');

                        $checkDbInsideObj = $this->checkDbInsideObjVNC($getDbDeadline,$dataTrack);

                        if($checkDbInsideObj &&$checkDbInsideObj>0 || $checkDbInsideObj==="0"){

                            $dataUpdate['deadline_order']=date('Y-m-d H:i:s',strtotime("+$checkDbInsideObj hour",strtotime($dataUpdate['last_time_updated'])));



                        }else{

                            array_push($deadlineFail, array('code' => $order->code_supership));



                            $dataUpdate['deadline_order']=null;

                        }





                        $this->db->where('id', $order->id);

                        $update = $this->db->update('tblorders_shop', $dataUpdate);



                        if (!$update) {

                            array_push($errors, 'Cập nhật thất bại đơn có mã VNC: ' . $order->code_ghtk);

                        }

                        array_push($success, array('code' => $order->code_supership));



                    } else {

                        array_push($errors, 'Không có giá trị đơn có mã VNC: ' . $order->code_supership . ' trên VNC');

                    }



                }



            }



            $this->_update_control_date($token, $acTest);



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





    /**

     * Đây là hàm xử lý thông tin đơn hàng trên SPS

     */

    public function updateNewSPS()

    {

        $now = date('Y-m-d H:i:s');



        $code = htmlentities($this->input->get('code'));



        // array Status in table tbldeclare

        $arrStatus = getArrDataInTbldeclare();

        $dataArrStatus = array();



        foreach ($arrStatus as $status) {

            if (!in_array($status, $dataArrStatus)) {

                array_push($dataArrStatus, "\"" . $status . "\"");

            }

        }



        if (empty($code)) {

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

					WHERE `code_supership` != "" AND ((`status` NOT IN (' . implode(',', $dataArrStatus) . '))

					OR `status` IS NULL )

					AND `is_hd_branch` = 1

					AND `DVVC` = "SPS" 

					AND `date_create` >= "' . date("Y-m-d H:i:s", strtotime("$now - 90 day")) . '"

				AND `date_create` < "' . date("Y-m-d H:i:s", mktime(date('H'), date('i') - 15, date('s'), date('m'), date('d'), date('Y'))) . '" ORDER BY id DESC';

        } else {

            $sql = 'SELECT * FROM 

						`tblorders_shop` 

					WHERE `code_supership` = "' . $code . '" ORDER BY id DESC';

        }



        $isTest = $this->input->get('Test');

        if ($isTest === 'true')

            $sql .= ' LIMIT 10';



        $list_order = $this->db->query($sql)->result();



        $errors = $success =$deadlineFail= array();



        if (!empty($list_order)) {



            $this->load->model('orders_change_weight_model');



            foreach ($list_order as $order) {



                // Select info new of order

                $this->db->where('id', $order->id);

                $info_order = $this->db->get('tblorders_shop')->row();



                if (!in_array($info_order->status, $arrStatus)) {

                    $curl = curl_init();



                    curl_setopt_array($curl, array(

                        CURLOPT_URL => "https://api.mysupership.vn/v1/partner/orders/info?code=" . $order->code_supership,

                        CURLOPT_RETURNTRANSFER => true,

                        CURLOPT_ENCODING => "",

                        CURLOPT_MAXREDIRS => 10,

                        CURLOPT_TIMEOUT => 0,

                        CURLOPT_FOLLOWLOCATION => true,

                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

                        CURLOPT_CUSTOMREQUEST => "GET",

                    ));



                    $response = curl_exec($curl);



                    curl_close($curl);

                    $arrResult = json_decode($response, true);



                    if ($arrResult['status'] === 'Success') {

                        $result = $arrResult['results'];

                        $dataUpdate = array(

                            'mass_fake' => $result['weight'],

                            'collect' => $result['amount'],

                            'value' => $result['value'],

                            'pay_transport' => $result['fee']['shipment'],

                            'insurance' => $result['fee']['insurance'],

                            'pay_refund' => $result['fee']['return']

                        );



                        $this->db->where('status_ghtk', $result['status']);

                        $this->db->where('dvvc', 'SPS');

                        $result_Status = $this->db->get('tblstatus_order')->row();



                        // journeys

                        $journeys = $result['journeys'];

                        $dataJourneys = $journeys[count($journeys) - 1];



                        // Date_debit

                        if (empty($order->group_debits)) {

                            if (!empty($result_Status->status_debit) && is_null($order->date_debits)) {

                                $dataUpdate['date_debits'] = date('Y-m-d H:m:s');

                            }

                        } elseif ($order->group_debits == 1) {

                            if (!empty($result_Status->group_debits) && is_null($order->date_debits)) {

                                $dataUpdate['date_debits'] = date('Y-m-d H:m:s');

                            }

                        }



                        $dataUpdate['delivery_delay_time'] = $dataJourneys['time'];

                        $dataUpdate['last_time_updated'] = $dataJourneys['time'];

                        $dataUpdate['status'] = (!empty($result_Status)) ? $result_Status->status_change : $order->status;





                        // Weight

                        if ($result['weight'] > $order->mass) {

                            $dataUpdate['mass'] = $result['weight'];



                            $data_insert = [

                                'order_shop_id' => $order->id,

                                'shop_name' => $order->shop,

                                'code' => $order->code_supership,

                                'old_weight' => $order->mass,

                                'new_weight' => $result['weight'],

                                'created_date' => date('Y-m-d H:i:s'),

                            ];

                            $this->orders_change_weight_model->insert($data_insert);

                        }



                        // Collect Amount

                        if ((float)$order->collect != (float)$result['amount']) {



                            $data_insert_money = [

                                'order_shop_id' => $order->id,

                                'shop_name' => $order->shop,

                                'code' => $order->code_supership,

                                'old_money' => $order->collect,

                                'new_money' => $result['amount'],

                                'created_date' => date('Y-m-d H:i:s'),

                            ];

                            $this->orders_change_weight_model->insert_money($data_insert_money);

                        }





                        //control_date

                        if (is_null($order->control_date) && stripos($dataJourneys['status'], 'Đã Đối Soát') !== false) {

                            $dataUpdate['control_date'] = $dataJourneys['time'];

                        }



                        $this->db->where('id', $order->id);

                        $getDbDeadline = $this->getDeadlineByDVVC('SPS');

                        $checkDbInsideObj = $this->checkDbInsideObj($getDbDeadline,$dataJourneys,$order->code_supership);

                        if($checkDbInsideObj &&$checkDbInsideObj>0|| $checkDbInsideObj==="0"){

                            $dataUpdate['deadline_order']=date('Y-m-d H:i:s',strtotime("+$checkDbInsideObj hour",strtotime($dataUpdate['last_time_updated'])));

                        }else{

                            $dataUpdate['deadline_order']=null;

                            array_push($deadlineFail, array('code' => $order->code_supership));



                        }

                        $update = $this->db->update('tblorders_shop', $dataUpdate);

                        if (!$update) {

                            array_push($errors, 'Cập nhật đơn hàng có mã ' . $order->code_supership . ' thất bại');

                        }



                        if ($isTest === 'true') {

                            array_push($success, 'Mã đơn: ' . $order->code_supership);

                        } else {

                            array_push($success, 'Cập nhật đơn hàng có mã ' . $order->code_supership . ' thành công');

                        }



                    } else {

                        array_push($errors, 'Không có giá trị hoặc sai thông tin đơn có mã SPS: ' . $order->code_supership . ' trên SPS');

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

//    kiem tra sps

    private function checkDbInsideObj($data,$obj,$code_supership){

        $check =false;

        $time ='';

        $maVung =$this->covertSuperShip($code_supership);

        foreach($data as $value){

            $name = $value->name;

            $arrName = explode(',',$name);



            foreach ($arrName as $key=> $nameChild){



                $nameChild=ltrim($nameChild);

                $check=$this->checkTextInsideObjSPS($obj,$nameChild);

                if($check){



                    if($maVung == "NT"){

                        $time=$value->time_nt;



                    }elseif ($maVung == "NM"){

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

    private function checkDbInsideObjVNC($data,$obj){

        $check =false;

        $time ='';

        foreach($data as $value){

            $name = $value->name;

            $arrName = explode(',',$name);



            foreach ($arrName as $key=> $nameChild){



                $nameChild=ltrim($nameChild);

                    $check=$this->checkTextInsideObjVNC($obj,$nameChild);

                if($check){

                    $time=$value->time_lm;

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

    private function checkTextInsideObjSPS($array,$string){

        $check = false;

        $key = true;





        if (stripos($string,"<>")!== false ){

            $key =false;

            $string = str_replace("<>","",$string);

        }

        if(stripos($array['status'],$string)!== false){

            $check =$key;



        }elseif (stripos($array['province'],$string)!== false){

            $check =$key;



        }elseif (stripos($array['district'],$string)!== false){

            $check =$key;



        }elseif (stripos($array['note'],$string)!== false){

            $check = $key;

        }else{

            if($key ==false){

                $check = true;

            }

        }





        return $check;

    }



    private function checkTextInsideObjVNC($array,$string){

        $check = false;

        $key = true;





        if (stripos($string,"<>")!== false ){

            $key =false;

            $string = str_replace("<>","",$string);

        }

        if(stripos($array['Status'],$string)!== false){

            $check =$key;



        }elseif (stripos($array['Name'],$string)!== false){

            $check =$key;



        }else{

            if($key ==false){

                $check = true;

            }

        }





        return $check;

    }



    private function covertSuperShip($code_supership){

        $arr =explode('.',$code_supership);

        return substr($arr[0],-2);

    }



	/**

     * ==================================================================================

     */









    /**

     * Private function

     */

    /**

     * Function _checkAndReplaceStam là hàm tính phí có cộng thêm phụ phí thực hiện trên bảng tblorders_shop.

     * @param $code_supership là mã đơn hàng trên bảng tblorders_shop

     * @param $id_update là id của đơn hàng trên bảng tblorders_shop

     * @param $full_data_true là thông tin chi tiết đơn hàng trên bảng tblorders_shop

     * @param $price là phụ phí mặc định là 0.

     * @return bool true|false

     */

    private function _checkAndReplaceStam($code_supership = '', $id_update, $full_data_true, $price = 0)

    {

        $province = $full_data_true->city;

        $district = $full_data_true->district;

        $mass = $full_data_true->mass;

        $total = (int)$full_data_true->collect;

        $value = (int)$full_data_true->value;



        $id = null;



        if ($this->get_customer_id($full_data_true->shop) !== null) {

            $id = $this->get_customer_id($full_data_true->shop)->id;

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

                    $insurance = ($total / 100) * $data_for_calc->insurance_price;

                    $insurance = round(($insurance / 1000) * 1000);

                } else {

                    $insurance = 0;

                }

            } else {

                if ($value > (int)$data_for_calc->amount_of_free_insurance) {

                    $insurance = ($value / 100) * $data_for_calc->insurance_price;

                    $insurance = round(($insurance / 1000) * 1000);

                } else {

                    $insurance = 0;

                }

            }



            $supership_cost = $supership_cost + $insurance + $price;



            $replaceCalc = $this->replaceDataCode_Super_ship_stam($id_update, $supership_cost);

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

                        $insurance = ($total / 100) * $data_for_calc->insurance_price;

                        $insurance = round(($insurance / 1000) * 1000);

                    } else {

                        $insurance = 0;

                    }

                } else {

                    if ($value > (int)$data_for_calc->amount_of_free_insurance) {

                        $insurance = ($value / 100) * $data_for_calc->insurance_price;

                        $insurance = round(($insurance / 1000) * 1000);

                    } else {

                        $insurance = 0;

                    }

                }



                $supership_cost = $supership_cost + $insurance + $price;



                $replaceCalc = $this->replaceDataCode_Super_ship_stam($id_update, $supership_cost);

            } else {

                $replaceCalc = $this->replaceDataCode_Super_ship_stam($id_update, 0);

            }

        }

    }



    private function _checkAndReplace($code_supership = '', $id_update, $full_data_true, $price = 0)

    {

        $province = $full_data_true->city;

        $district = $full_data_true->district;

        $mass = $full_data_true->mass;

        $total = (int)$full_data_true->collect;

        $value = (int)$full_data_true->value;



        $id = null;



        if ($this->get_customer_id($full_data_true->shop) !== null) {

            $id = $this->get_customer_id($full_data_true->shop)->id;

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

                    $insurance = ($total / 100) * $data_for_calc->insurance_price;

                    $insurance = round(($insurance / 1000) * 1000);

                } else {

                    $insurance = 0;

                }

            } else {

                if ($value > (int)$data_for_calc->amount_of_free_insurance) {

                    $insurance = ($value / 100) * $data_for_calc->insurance_price;

                    $insurance = round(($insurance / 1000) * 1000);

                } else {

                    $insurance = 0;

                }

            }



            $supership_cost = $supership_cost + $insurance + $price;



            $replaceCalc = $this->replaceDataCode_Super_ship($id_update, $supership_cost);

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

                        $insurance = ($total / 100) * $data_for_calc->insurance_price;

                        $insurance = round(($insurance / 1000) * 1000);

                    } else {

                        $insurance = 0;

                    }

                } else {

                    if ($value > (int)$data_for_calc->amount_of_free_insurance) {

                        $insurance = ($value / 100) * $data_for_calc->insurance_price;

                        $insurance = round(($insurance / 1000) * 1000);

                    } else {

                        $insurance = 0;

                    }

                }



                $supership_cost = $supership_cost + $insurance + $price;



                $replaceCalc = $this->replaceDataCode_Super_ship($id_update, $supership_cost);

            }

        }

    }



    private function checkAndReplace_fee($code_supership = '', $id_update, $full_data_true, $price = 0)

    {

        $province = $full_data_true->city;

        $district = $full_data_true->district;

        $mass = $full_data_true->mass;

        $total = (int)$full_data_true->collect;

        $value = (int)$full_data_true->value;



        $id = null;



        if ($this->get_customer_id($full_data_true->shop) !== null) {

            $id = $this->get_customer_id($full_data_true->shop)->id;

        }



        if ($id !== null) {

            $policy_id = $this->get_policy_id($id);

        } else {

            $policy_id = false;

        }



        if ($policy_id === false) {

            $region = $this->search_region($province, $district, $policy_id);



            $data_for_calc = $region;

            $fee_back_new = (float)$data_for_calc->fee_back_new;

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

                    $insurance = ($total / 100) * $data_for_calc->insurance_price;

                    $insurance = round(($insurance / 1000) * 1000);

                } else {

                    $insurance = 0;

                }

            } else {

                if ($value > (int)$data_for_calc->amount_of_free_insurance) {

                    $insurance = ($value / 100) * $data_for_calc->insurance_price;

                    $insurance = round(($insurance / 1000) * 1000);

                } else {

                    $insurance = 0;

                }

            }



            $supership_cost = (($supership_cost + $insurance) * $fee_back_new) / 100 + $price;



            $replaceCalc = $this->replaceDataCode_Super_ship($id_update, $supership_cost);

        } else {

            $region = $this->search_region($province, $district, $policy_id);



            $data_for_calc = null;



            if ($region !== null) {

                $data_for_calc = $region->data_region;

            }



            if ($data_for_calc !== null) {

                $fee_back_new = (float)$data_for_calc->fee_back_new;

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

                        $insurance = ($total / 100) * $data_for_calc->insurance_price;

                        $insurance = round(($insurance / 1000) * 1000);

                    } else {

                        $insurance = 0;

                    }

                } else {

                    if ($value > (int)$data_for_calc->amount_of_free_insurance) {

                        $insurance = ($value / 100) * $data_for_calc->insurance_price;

                        $insurance = round(($insurance / 1000) * 1000);

                    } else {

                        $insurance = 0;

                    }

                }



                $supership_cost = (($supership_cost + $insurance) * $fee_back_new) / 100 + $price;



                $replaceCalc = $this->replaceDataCode_Super_ship($id_update, $supership_cost);

            }

        }

    }



    private function get_customer_id($shop = '')

    {

        $this->db->select('id');

        $this->db->where('customer_shop_code', $shop);

        $this->db->from('tblcustomers');



        return $this->db->get()->row();

    }



    private function replaceDataCode_Super_ship($id, $value)

    {

        $this->db->set('hd_fee', $value);

        $this->db->where('id', $id);

        $update = $this->db->update('tblorders_shop');



        return $update;

    }



    private function replaceDataCode_Super_ship_stam($id, $value)

    {

        $this->db->set('hd_fee_stam', $value);

        $this->db->where('id', $id);

        $update = $this->db->update('tblorders_shop');



        return $update;

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

	

	private function clean($str)

	{

		$str = utf8_encode($str);

	   $str = utf8_decode($str);

	   $str = str_replace("&nbsp;", " ", $str);

	   $str = preg_replace("/\s+/", " ", $str);

	   $str = trim($str);

	   return $str;

	}

	

	private function _update_control_date($token, $isTest = false){

		$sql = 'SELECT * FROM 

                    `tblorders_shop` 

                WHERE `date_debits` IS NOT NULL AND `control_date` IS NULL

                AND `DVVC` = "VNC"

                AND `date_create` >= "' . date("Y-m-d H:i:s", strtotime("$now - 90 day")) . '"

				AND `date_create` < "' . date("Y-m-d H:i:s", mktime(date('H'), date('i') - 15, date('s'), date('m'), date('d'), date('Y'))) . '" ORDER BY id DESC';

				

		if(!empty($isTest))

			$sql .= ' LIMIT 10';

		

		$list_order = $this->db->query($sql)->result();

		

		if(!empty($list_order)){

            

			foreach ($list_order as $order) {

                // Select info new of order

                $this->db->where('id', $order->id);

                $info_order = $this->db->get('tblorders_shop')->row();

				if(!in_array($info_order->status, $arrStatusDB)){

                    $data = array('Code' => $order->code_ghtk);

                    $curl = curl_init();

                    curl_setopt_array($curl, array(

                        CURLOPT_URL => URL_VNC_TRACKING . 'Track/Order',

                        CURLOPT_RETURNTRANSFER => true,

                        CURLOPT_ENCODING => "",

                        CURLOPT_MAXREDIRS => 10,

                        CURLOPT_TIMEOUT => 0,

                        CURLOPT_FOLLOWLOCATION => true,

                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

                        CURLOPT_CUSTOMREQUEST => "POST",

                        CURLOPT_POSTFIELDS => json_encode($data),

                        CURLOPT_HTTPHEADER => array(

                            "Authorization: Bearer " . $token,

                            "Content-Type: application/json"

                        ),

                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);

                    $arr = json_decode($response, true);



                    if ($arr['Result'] == 1) {

						// Status Order

                        $TrackAndTrace = $arr['TrackAndTrace'];

                        $arrStatus = array();

                        $dataTrack = $TrackAndTrace[count($TrackAndTrace) - 1];

                        $Name = $dataTrack['Name'];

						

						if(stripos($Name, 'Đã thanh toán đối soát') !== false){

							

							$datePre = preg_replace("/[^a-zA-Z0-9_.-\s]/", ' ', html_entity_decode($dataTrack['CreatedTimeDisplay2']));

							$arrDate = explode(' ', $datePre);

							$date = $arrDate[0] . '-' . $arrDate[1] . '-' . $arrDate[2] . ' ' . $arrDate[4] . ':' . $arrDate[5] . ':' . $arrDate[6];

							

							$dataUpdate = array('control_date' => $date);

							$this->db->where('id', $order->id);

							$update = $this->db->update('tblorders_shop', $dataUpdate);

						}

                    }

					

				}

                

            }

			

		}

	}

}

