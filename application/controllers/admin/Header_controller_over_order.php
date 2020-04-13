<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Header_controller_over_order extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }


    public function number_day_go($date = '')
    {
        $now = time(); // or your date as well
        $your_date = strtotime($date);
        $datediff = $now - $your_date;

        return round($datediff / (60 * 60 * 24));
    }

    public function search_region($city, $district)
    {
        $this->db->select('*');
        $this->db->where('city', trim($city));
        $this->db->where('district', trim($district));
        $search_result = $this->db->get('tblregion_excel')->row();


        if ($search_result) {
            $this->db->select('max_day');
            $this->db->where('id', $search_result->region_id);
            $region = $this->db->get('tbldeclared_region')->row();


            return $region;
        }
    }

    public function get_phone_customer($shop)
    {
        $this->db->select('customer_phone');
        $this->db->where(array('customer_shop_code' => $shop));
        return $this->db->get('tblcustomers')->row()->customer_phone;
    }
    public function add_to_array_5($data, $phone)
    {
        return array_slice($data, 0, 5, true) +
      array("phone" => $phone) +
      array_slice($data, 5, count($data) - 1, true) ;
    }

    public function getNumberList()
    {
        $data = $this->db->select('id, status, old_weight, new_weight')->from('tbl_orders_change_weight')->get()->result();
        $count = 0;
        foreach ($data as $key => $value) {
            if ($value->status == 0 && $value->old_weight < $value->new_weight) {
                $count++;
            }
        }
        echo $count;
    }

    public function differenceInHours($startdate, $enddate)
    {
        $starttimestamp = strtotime($startdate);
        $endtimestamp = strtotime($enddate);
        $difference =  abs($endtimestamp - $starttimestamp)/3600;
        return $difference;
    }
    public function edit_time_status()
    {
        $data_over = $this->db
        ->select(' id,  over_time ')
        ->where('over_time !=', null)
        ->from('tblorders_shop')->get()->result();
        $start_check = date("Y-m-d H:i:s");


        $data_status_null = [];


        if (sizeof($data_over) > 0) {
            foreach ($data_over as $key => $value) {
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
        $status  = $this->db->update_batch('tblorders_shop', $data_status_null, 'id');
        return $status;
    }

    public function index()
    {
        $data_over =
        $this->db->select('id,created_date,order_shop_id,shop_name,code,old_weight,new_weight,status')
        ->from('tbl_orders_change_weight')
        ->get()
        ->result();
        foreach ($data_over as $key => $value) {
            if ($value->status != 0) {
                unset($data_over[$key]);
            } elseif ($value->old_weight >= $value->new_weight) {
                unset($data_over[$key]);
            }
        }
        $aColumns     = array(
          'id',
          'created_date',
          'order_shop_id',
          'shop_name',
          'code',
          'old_weight',
          'new_weight',
          'status'
        );




        $dataTableInit = [
          "aaData" => $this->object_to_array($data_over, $aColumns),
          "draw" =>  $_POST['draw'],
          "iTotalDisplayRecords" => sizeof($data_over),
          "iTotalRecords"=> sizeof($data_over)
        ];
        echo json_encode($dataTableInit);
    }

    public function edit_status()
    {
        $this->db->where('id', $_POST['id']);
        if ($_POST['status']) {
            $_POST['status'] = 1;
        } else {
            $_POST['status'] = 0;
        }

        $update = $this->db->update('tbl_orders_change_weight', $_POST);

        if ($update) {
            echo $_POST['status'];
        }
        die();
    }

    public function edit_note()
    {
        $this->db->where('id', $_POST['id']);

        $_POST['note_delay'] = $_POST['note_over'];
        $_POST['note_over'] = $_POST['note_over'];
        $update = $this->db->update('tblorders_shop', $_POST);


        if ($update) {
            echo $_POST['status_over'];
        }
        die();
    }



    public function get_note()
    {
        $data_delay =
        $this->db
          ->select('id,note_over')
          ->where('id', $_GET['id'])
          ->from('tblorders_shop')->get()->row();
        echo json_encode($data_delay);
    }





    public function object_to_array($data, $aColumns)
    {
        $j=0;
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
                if ($row[7] == '0' || $row[7] == 0) {
                    $icon_get_data = '<label class="label-border" data-id="'. $aRow['id'] .'" style="color:red"> <span>Chưa XL</span>  <input class="check-change-status-over" type="checkbox"></label>';
                } else {
                    $icon_get_data = '<label class="label-border" data-id="'. $aRow['id'] .'" style="color:green"> <span>Đã XL</span>  <input checked class="check-change-status-over" type="checkbox"></label>';
                }
            }

            $row[7] = $icon_get_data;

            $row[4] = "<a target='_blank' href='https://mysupership.com/search?category=orders&query=".$row[4]."'>". $row[4] ."</a>";

            $data_aaDATA[] =  $row;
        }


        return $data_aaDATA;
    }
}
