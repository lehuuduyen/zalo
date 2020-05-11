<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Header_controller extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function edit_status()
    {
        if ($_POST['status_delay'] === 'true') {
            $_POST['status_delay'] = 1;
            $_POST['status_over'] = 1;
            $_POST['delay_time'] = date("Y-m-d H:i:s");
            $_POST['over_time'] = date("Y-m-d H:i:s");
        } else {
            $_POST['status_delay'] = 0;
            $_POST['status_over'] = 0;
            $_POST['delay_time'] = null;
            $_POST['over_time'] = null;
        }

        $this->db->where('id', $_POST['id']);
        $update = $this->db->update('tblorders_shop', $_POST);


        if ($update) {
            echo $_POST['status_delay'];
        }
        die();
    }

    public function edit_note()
    {
        $this->db->where('id', $_POST['id']);

        // $_POST['note_delay'] = $_POST['note_delay'];
        // $_POST['note_over'] = $_POST['note_delay'];
        $update = $this->db->update('tblorders_shop', $_POST);


        if ($update) {
            echo $_POST['status_delay'];
        }
        die();
    }

    public function getNumberList()
    {
        $this->edit_time_status();
        $data_delay =
            $this->db
                ->select('id,status_delay')
                ->order_by('status_delay', 'ASC')
                ->order_by("delivery_delay_time", "DESC")
                // ->not_like('city' , 'Tỉnh Hải Dương')
                ->where('status', 'Hoãn Giao Hàng')
                ->from('tblorders_shop')->get()->result();

        foreach ($data_delay as $key => $value) {
            if ($value->status_delay == '1' || $value->status_delay == 1) {
                unset($data_delay[$key]);
            }
        }
        echo sizeof($data_delay);
    }

    public function get_note()
    {
        $data_delay =
            $this->db
                ->select('id,note_delay')
                ->where('id', $_GET['id'])
                ->from('tblorders_shop')->get()->row();
        echo json_encode($data_delay);
    }


    public function add_to_array_5($data, $phone)
    {
        return array_slice($data, 0, 5, true) + array("phone" => $phone) + array_slice($data, 5, count($data) - 1, true);
    }

    public function object_to_array($data, $aColumns)
    {
        $j = 0;
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
                if ($row[9] == '1' || $row[9] == 1) {
                    $icon_get_data = '<label class="label-border" data-id="' . $aRow['id'] . '" style="color:green"> <span>Đã XL</span>  <input checked class="check-change-status" type="checkbox"></label>';
                } else {
                    $icon_get_data = '<label class="label-border" data-id="' . $aRow['id'] . '" style="color:red"> <span>Chưa XL</span>  <input class="check-change-status" type="checkbox"></label>';
                }

                $row[] = $icon_get_data . '</div>';
            } else {
                $row[] = '';
            }

            if ($row[10]) {
                $input_row = '<a href="javascript:;" class="popup-edit-note" data-id="' . $aRow['id'] . '" style="color:green;display: block;margin: 0 auto;width: 18px;"> <i class="fa fa-pencil"></i> </a>';
                $row[10] = '<pre class="will-show-hover">' . $row[10] . '</pre>' . $input_row;
            } else {
                $input_row = '<a href="javascript:;" class="popup-edit-note" data-id="' . $aRow['id'] . '" style="color:red;display: block;margin: 0 auto;width: 18px;"> <i class="fa fa-pencil"></i> </a>';
                $row[10] = $input_row;
            }


            if($row[11] == 'GHTK'){
                $row[4] = "<a target='_blank' href='https://khachhang.giaohangtietkiem.vn/khachhang?code=".$row[12]."'>" . $row[4] . "</a>";
            }else{
                $row[4] = "<a target='_blank' href='https://mysupership.com/search?category=orders&query=" . $row[4] . "'>" . $row[4] . "</a>";
            }
            $row[11] = $row[13];
            unset($row[13]);
            unset($row[12]);
            $data_aaDATA[] = $row;
        }


        return $data_aaDATA;
    }


    public function get_phone_customer($shop)
    {
        $this->db->select('customer_phone');
        $this->db->where(array('customer_shop_code' => $shop));
        return $this->db->get('tblcustomers')->row()->customer_phone;
    }


    public function differenceInHours($startdate, $enddate)
    {
        $starttimestamp = strtotime($startdate);
        $endtimestamp = strtotime($enddate);
        $difference = abs($endtimestamp - $starttimestamp) / 3600;
        return $difference;
    }

    public function edit_time_status()
    {
        $data_over = $this->db
            ->select(' id,  delay_time ')
            ->where('delay_time !=', null)
            ->from('tblorders_shop')->get()->result();
        $start_check = date("Y-m-d H:i:s");
        $data_status_null = [];


        if (sizeof($data_over) > 0) {
            foreach ($data_over as $key => $value) {
                if ($this->differenceInHours($start_check, $value->delay_time) >= 24) {
                    $id_status_null['id'] = $value->id;
                    $id_status_null['delay_time'] = null;
                    $id_status_null['over_time'] = null;
                    $id_status_null['status_delay'] = 0;
                    $id_status_null['status_over'] = 0;
                    $data_status_null[] = $id_status_null;
                }
            }
        }


        $status = $this->db->update_batch('tblorders_shop', $data_status_null, 'id');
        return $status;
    }

    public function get_delay()
    {
        $data_delay =
            $this->db
                ->select('id, delivery_delay_time, date_create , status, code_supership , shop , district , city , status_delay , note_delay, DVVC, code_ghtk')
                ->order_by('status_delay', 'ASC')
                ->order_by("delivery_delay_time", "DESC")
                // ->not_like('city' , 'Tỉnh Hải Dương')
                ->where('status', 'Hoãn Giao Hàng')
                ->from('tblorders_shop')->get()->result();

        foreach ($data_delay as $key => $value) {
            $phone = $this->get_phone_customer($value->shop);
            $data_delay[$key] = $this->add_to_array_5(json_decode(json_encode($value), true), $phone);
        }


        $aColumns = array(
            'id',
            'delivery_delay_time',
            'date_create',
            'status',
            'code_supership',
            'shop',
            'phone',
            'district',
            'city',
            'status_delay',
            'note_delay', 'DVVC', 'code_ghtk'
        );

        $dataTableInit = [
            "aaData" => $this->object_to_array($data_delay, $aColumns),
            "draw" => $_POST['draw'],
            "iTotalDisplayRecords" => sizeof($data_delay),
            "iTotalRecords" => sizeof($data_delay)
        ];
        echo json_encode($dataTableInit);
    }


    public function get_order_half()
    {
        $data_delay =
            $this->db
                ->select('tbl_orders_change_money.order_shop_id, tbl_orders_change_money.id, tbl_orders_change_money.shop_name , 
            tbl_orders_change_money.code, tbl_orders_change_money.old_money , 
            tbl_orders_change_money.new_money , tbl_orders_change_money.created_date , tbl_orders_change_money.status,
            tblorders_shop.status as status_order, date_create, tblorders_shop.DVVC, tblorders_shop.code_ghtk')
                ->order_by('created_date', 'DESC')
                ->join('tblorders_shop', 'tblorders_shop.code_supership = tbl_orders_change_money.code')
                ->where('DATE_FORMAT(tbl_orders_change_money.created_date, "%Y-%m-%d") >= "2019-03-10"')
                ->where('(new_money - old_money) < 0')
                ->where('is_hd_branch', 1)
                ->where('tbl_orders_change_money.status != 1')
                ->where('tblorders_shop.status != "Đã Trả Hàng Một Phần"')
                ->from('tbl_orders_change_money')->get()->result();

        $aColumns = array(
            'created_date',
            'date_create',
            'shop_name',
            'code',
            'status_order',
            '1',
            'old_money',
            'new_money',
            'DVVC',
            'code_ghtk'
        );
        $colum_not_view = ['status'];

        $dataTableInit = [
            "aaData" => $this->aaData_order_half($data_delay, $aColumns, $colum_not_view),
            "draw" => $_POST['draw'],
            "iTotalDisplayRecords" => sizeof($data_delay),
            "iTotalRecords" => sizeof($data_delay)
        ];
        echo json_encode($dataTableInit);
    }


    public function aaData_order_half($data, $aColumns, $colum_not_view)
    {
        $j = 0;
        $data_aaDATA = [];
        foreach ($data as $aRow) {
            $row = array();
            $j++;


            $aRow = json_decode(json_encode($aRow), true);

            for ($i = 0; $i < count($aColumns); $i++) {
                $_data = $aRow[$aColumns[$i]];
                if ($aColumns[$i] == 'created_date') {
                    if (!empty($aRow['created_date'])) {
                        $_data = _dt($aRow['created_date']);
                    }
                }
                if ($aColumns[$i] == 'date_create') {
                    if (!empty($aRow['date_create'])) {
                        $_data = _dt($aRow['date_create']);
                    }
                }
                if ($aColumns[$i] == 'old_money' || $aColumns[$i] == 'new_money') {
                    $_data = number_format_data($aRow[$aColumns[$i]]);
                }
                if ($aColumns[$i] == '1') {
                    $_data = '';
                    if ($aRow['status'] == 2) {
                        $_data = 'Đơn Hàng Giao Hàng Một Phần';
                    }
                }
                $row[] = $_data;
            }

            $input_check = '<div>';
            if ($aRow['status'] != 2) {
                $input_check .= '<a title="Đơn Hàng Giao Hàng Một Phần" data-id="' . $aRow['id'] . '" id-colum="status" value="2" style="font-size: 20px;margin-right: 20px;" class="check-change-status-status-half" href="javascript:;"><i class="fa fa-undo" aria-hidden="true"></i></a>';
                $input_check .= '<label title="Đơn Giao Hàng Toàn Bộ" class="label-border" data-id="' . $aRow['id'] . '" style="color:red;margin:0 0px;">
                                <input checked="" class="check-change-status-status-half" data-id="' . $aRow['id'] . '" id-colum="status" value="1" type="checkbox">
                            </label>';
            }
            $input_check .= '</div>';

            $row[] = $input_check;

            if($row[8] == 'GHTK'){
                $row[3] = '<a href="https://khachhang.giaohangtietkiem.vn/khachhang?code='.$row[9].'" target="_blank">'.$row[3].'</a>';
            }else
                $row[3]='<a href="https://mysupership.com/search?category=orders&query='.$row[3].'" target="_blank">'.$row[3].'</a>';
            $row[8] = $row[10];
            unset($row[9]);
            unset($row[10]);
            $data_aaDATA[] = $row;
        }


        return $data_aaDATA;
    }

    public function update_status_half()
    {
        $id = $this->input->get('id');
        $data = $this->input->get('data');
        $colum = $this->input->get('colums');

        if (!empty($id) && !empty($data) && !empty($colum)) {
            $this->db->where('id', $id);
            if ($this->db->update('tbl_orders_change_money', [
                $colum => $data
            ])) {
                echo json_encode(['success' => true, 'alert_type' => 'success', 'message' => 'Cập nhật thành công']);
                die();
            }
        }
        echo json_encode(['success' => false, 'alert_type' => 'danger', 'message' => 'Cập nhật không thành công']);
        die();
    }

    public function update_note_half()
    {
        $id = $this->input->post('id');
        $note = $this->input->post('note_half');
        $this->db->where('id', $id);
        $get_shop = $this->db->get('tblorders_shop')->row();
        if (!empty($get_shop)) {

            $this->db->where('code_supership', $get_shop->code_supership);
            $get_half = $this->db->get('tblorders_shop_half')->row();
            if (!empty($get_half)) {
                $this->db->where('id', $get_half->id);
                if ($this->db->update('tblorders_shop_half', [
                    'date_update' => date('Y-m-d H:i:s'),
                    'note_half' => $note
                ])) {
                    echo json_encode(['success' => true, 'alert_type' => 'success', 'message' => 'Thay Đổi Ghi Chú Thành Công']);
                    die();
                }
            } else {
                $this->db->insert('tblorders_shop_half', [
                    'date_update' => date('Y-m-d H:i:s'),
                    'code_supership' => $get_shop->code_supership,
                    'note_half' => $note,
                    'data_check' => 0,
                    'date_break' => 0
                ]);
                $id_half = $this->db->insert_id();
                if (!empty($id_half)) {
                    echo json_encode(['success' => true, 'alert_type' => 'success', 'message' => 'Thay Đổi Ghi Chú Thành Công']);
                    die();
                }
            }
        }
        echo json_encode(['success' => false, 'alert_type' => 'danger', 'message' => 'Thay Đổi Ghi Chú không Thành Công']);
        die();
    }


    public function getNumberListHalf()
    {
        $data_delay =
            $this->db
                ->select('tbl_orders_change_money.order_shop_id, tbl_orders_change_money.id, tbl_orders_change_money.shop_name , 
            tbl_orders_change_money.code, tbl_orders_change_money.old_money , 
            tbl_orders_change_money.new_money , tbl_orders_change_money.created_date , tbl_orders_change_money.status,
            tblorders_shop.status as status_order')
                ->order_by('created_date', 'DESC')
                ->join('tblorders_shop', 'tblorders_shop.code_supership = tbl_orders_change_money.code')
                ->where('DATE_FORMAT(tbl_orders_change_money.created_date, "%Y-%m-%d") >= "2019-03-10"')
                ->where('(new_money - old_money) < 0')
                ->where('is_hd_branch', 1)
                ->where('tbl_orders_change_money.status = 0')
                ->from('tbl_orders_change_money')->get()->result();
        echo sizeof($data_delay);
    }

}
