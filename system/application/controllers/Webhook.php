<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Webhook extends CI_Controller
{
	public function __construct() {
		parent::__construct();
		load_admin_language();
	}

	public function index() {
 		$entityBody = file_get_contents('php://input');
        $data = json_decode($entityBody);
        if(!empty($data)) {
            $this->db->insert('tblwebhook_gh', [
                'partner_id' => $data->partner_id,
                'label_id' => $data->label_id,
                'status_id' => $data->status_id,
                'action_time' => $data->action_time,
                'reason_code' => $data->reason_code,
                'reason' => $data->reason,
                'weight' => $data->weight,
                'fee' => $data->fee,
                'pick_money' => $data->pick_money,
                'return_part_package' => $data->return_part_package,
                'date_create' => date('Y-m-d H:i:s'),
            ]);
            if($this->db->insert_id()) {
                header('HTTP/1.0 200 OK');
                echo _l('Thêm dữ liệu thành công');
                die;
            }
            else {
             	header('HTTP/1.0 500 No Record Found');
        		echo "Lỗi 500 không thêm được data";
            }
        }

        header('HTTP/1.0 404 Not Found');
        echo "Lỗi 404 không tìm thấy data";
        die;

	}

}
