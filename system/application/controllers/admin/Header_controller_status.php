<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Header_controller_status extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }


    public function edit_status()
    {
        $this->db->where('id', $_POST['id']);
        $_POST['status'] = 'Đã Trả Hàng';
        $update = $this->db->update('tblorders_shop', $_POST);

        if ($update) {
            echo $_POST['status_over'];
        }
        die();
    }


    public function checkTimeCurentAndMaxTime($orderLastUpdate, $statusTime)
    {
        $currentTime = date("Y-m-d H:i:s");

        $hourCompareStatus = $this->differenceInHours($orderLastUpdate, $currentTime);
        if ($hourCompareStatus < $statusTime) {
            return false;
        }
        return true;
    }

    public function getDurationStatus($status, $compare)
    {
        foreach ($status as $key => $value) {
            if ($value->status == $compare) {
                return $value->duration;
            }
        }
    }

    public function edit_time_status()
    {
        $dataStatus =
            $this->db
                ->select('*')->from('tbl_max_time_status')->get()->result();

        $where_in = array();
        foreach ($dataStatus as $key => $value) {
            $where_in[$key] = $value->status;
        }

        $data_over =
            $this->db
                ->select('id , last_time_updated , status , status_over , over_time')
                ->where('is_hd_branch', 1)
                // ->where('control_date', null)
                ->where_in('status', $where_in)
                ->from('tblorders_shop')
                ->get()
                ->result();


        $start_check = date("Y-m-d H:i:s");


        $data_status_null = [];


        if (sizeof($data_over) > 0) {
            foreach ($data_over as $key => $value) {
                // if ($value->id == '22911') {
                //     var_dump($value->id);
                //     var_dump($value->over_time);
                //
                //     var_dump($this->differenceInHours($start_check, $value->over_time));
                //     die();
                // }


                if ($this->differenceInHours($start_check, $value->over_time) >= 24) {
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

    public function getNumberList()
    {
        $this->edit_time_status();
        $dataStatus =
            $this->db
                ->select('*')->from('tbl_max_time_status')->get()->result();

        $where_in = array();
        foreach ($dataStatus as $key => $value) {
            $where_in[$key] = $value->status;
        }

        $data_delay =
            $this->db
                ->select('id , last_time_updated , status , status_over')
                ->where('is_hd_branch', 1)
                // ->where('control_date', null)
                ->where_in('status', $where_in)
                ->from('tblorders_shop')
                ->get()
                ->result();

        foreach ($data_delay as $key => $value) {
            $statusTime = $this->getDurationStatus($dataStatus, $value->status);

            if ($this->checkTimeCurentAndMaxTime($value->last_time_updated, $statusTime) == false || $value->status_over == '1') {
                unset($data_delay[$key]);
            }
        }

        echo sizeof($data_delay);
    }


    public function differenceInHours($startdate, $enddate)
    {
        $starttimestamp = strtotime($startdate);
        $endtimestamp = strtotime($enddate);
        $difference = abs($endtimestamp - $starttimestamp) / 3600;
        return $difference;
    }

    public function index()
    {
        $dataStatus =
            $this->db
                ->select('*')->from('tbl_max_time_status')->get()->result();

        $where_in = array();
        foreach ($dataStatus as $key => $value) {
            $where_in[$key] = $value->status;
        }


        $data_delay =
            $this->db
                ->select('id , date_create , status ,  code_supership , shop , phone ,  district , city ,last_time_updated , status_over , note_delay, DVVC, code_ghtk')
                ->where('is_hd_branch', 1)
                // ->where('control_date', null)
                ->where_in('status', $where_in)
                ->from('tblorders_shop')
                ->get()
                ->result();


        $array_active_status = array();
        $array_un_active_status = array();

        foreach ($data_delay as $key => $value) {
            if ($value->status_over == '1' || $value->status_over == 1) {
                $array_active_status[] = $value;
            } else {
                $array_un_active_status[] = $value;
            }
        }


        if ($array_active_status) {
            usort($array_active_status, function ($item1, $item2) {
                if ($item1->last_time_updated == $item2->last_time_updated) {
                    return 0;
                }
                return $item1->last_time_updated > $item2->last_time_updated ? -1 : 1;
            });
        }

        if ($array_un_active_status) {
            usort($array_un_active_status, function ($item1, $item2) {
                if ($item1->last_time_updated == $item2->last_time_updated) {
                    return 0;
                }
                return $item1->last_time_updated > $item2->last_time_updated ? -1 : 1;
            });
        }


        $data_delay = array_merge($array_un_active_status, $array_active_status);

        foreach ($data_delay as $key => $value) {
            $statusTime = $this->getDurationStatus($dataStatus, $value->status);

            if ($this->checkTimeCurentAndMaxTime($value->last_time_updated, $statusTime) == false) {
                unset($data_delay[$key]);
            }
        }
        $aColumns = array(
            'id',
            'date_create',
            'status',
            'code_supership',
            'shop',
            'phone',
            'district',
            'city',
            'last_time_updated',
            'status_over',
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
            $check_status = array('Đã Đối Soát Trả Hàng', 'Đang Chuyển Kho Trả', 'Đang Trả Hàng', 'Đã Chuyển Kho Trả', 'Đã Trả Hàng', 'Hoãn Trả Hàng');
            $check_display = array_search($row[2], $check_status);


            $change_status_to_back = '<a title="Xác Nhận Đã Trả Shop" data-id="' . $aRow['id'] . '" class="change_status_to_back" href="javascript:;"><i class="fa fa-undo" aria-hidden="true"></i></a>';
            if (is_admin()) {
                if ($row[9] == 1) {
                    $icon_get_data = '<label title="Đã Xử Lý" class="label-border active" data-id="' . $aRow['id'] . '" style="border-color:green;margin:0 10px;">   <input checked class="check-change-status-status" type="checkbox"></label>';
                } else {
                    $icon_get_data = '<label title="Chưa Xử Lý" class="label-border" data-id="' . $aRow['id'] . '" style="color:red;margin:0 10px;">   <input class="check-change-status-status" type="checkbox"></label>';
                }
            }


            if ($row[10]) {
                $input_row = '<a title="' . $row[10] . '"href="javascript:;" class="popup-edit-note" data-id="' . $aRow['id'] . '" style="color:green;width: 25px;display: flex;justify-content: center;font-size: 25px;"> <i class="fa fa-pencil"></i> </a>';
            } else {
                $input_row = '<a title="Ghi Chú" href="javascript:;" class="popup-edit-note" data-id="' . $aRow['id'] . '" style="color: red;width: 25px;display: flex;justify-content: center;font-size: 25px;"> <i class="fa fa-pencil"></i> </a>';
            }


            if ($check_display !== false) {
                $row[10] = '<div>' . $change_status_to_back . $icon_get_data . $input_row . '</div>';
            } else {
                $row[10] = '<div>' . $icon_get_data . $input_row . '</div>';
            }

            if ($row[11] == 'GHTK') {
                $row[3] = "<a target='_blank' href='https://khachhang.giaohangtietkiem.vn/khachhang?code=" . $row[12] . "'>" . $row[3] . "</a>";
            } else {
                $row[3] = "<a target='_blank' href='https://mysupership.com/search?category=orders&query=" . $row[3] . "'>" . $row[3] . "</a>";
            }

            $data_aaDATA[] = $row;
        }


        return $data_aaDATA;
    }
}
