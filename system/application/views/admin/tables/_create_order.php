<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$aColumns = array(
    'tbl_create_order.id',
    'created',
    'product',
    'required_code',
    'code',
    'customer_id',
    db_prefix() . 'customers.customer_shop_code as customer_shop_code',
    'supership_value',
    'cod',
    'amount',
    'name',
    'phone',
    'sphone',
    'address',
    'commune',
    'district',
    'province',
    'weight',
    'volume',
    'soc',
    'note',
    'service',
    'config',
    'payer',
    'barter',
    'value',
    'user_created',
    db_prefix() . 'staff.firstname',
    'status_cancel',
    'the_fee_bearer',
);
$sIndexColumn = "id";
$sTable = 'tbl_create_order';
$_POST['date_end_customer'] = date('Y-m-d 23:59:59', strtotime(str_replace('/', '-', $_POST['date_end_customer'])));
$_POST['date_start_customer'] = date('Y-m-d 00:00:00', strtotime(str_replace('/', '-', $_POST['date_start_customer'])));

$string_filter = "AND dvvc = 'SPS' AND created <= '" . $_POST['date_end_customer'] . "'" . " AND created >= '" . $_POST['date_start_customer'] . "'" . " ";


$join = $join = [
    'LEFT JOIN ' . db_prefix() . 'customers ON ' . db_prefix() . 'customers.	id=' . db_prefix() . '_create_order.customer_id',
    'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.		staffid=' . db_prefix() . '_create_order.user_created',
];
$where = [$string_filter];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, array());
$output = $result['output'];

$rResult = $result['rResult'];

$j = 0;


foreach ($rResult as $aRow) {
    $row = array();
    $j++;
    for ($i = 0; $i < count($aColumns); $i++) {

        if ($aColumns[$i] === db_prefix() . 'customers.customer_shop_code as customer_shop_code') {
            $_data = $aRow['customer_shop_code'];

        } else if ($aColumns[$i] === 'tbl_create_order.id') {
            $aRow['id'] = $aRow['tbl_create_order.id'];
            $_data = $aRow['id'];


        } else if ($aColumns[$i] == 'supership_value') {
            $_data = number_format_data($aRow[$aColumns[$i]]);
        } else if ($aColumns[$i] == 'amount') {
            $_data = number_format_data($aRow[$aColumns[$i]]);
        } else if ($aColumns[$i] == 'weight') {
            $_data = number_format_data($aRow[$aColumns[$i]]);
        } else if ($aColumns[$i] == 'volume') {
            $_data = number_format_data($aRow[$aColumns[$i]]);
        } else if ($aColumns[$i] == 'cod') {
            $_data = number_format_data($aRow[$aColumns[$i]]);
        } else if ($aColumns[$i] == 'value') {
            $_data = number_format_data($aRow[$aColumns[$i]]);
        } else if ($aColumns[$i] == 'barter') {
            if ($aRow[$aColumns[$i]] === '1') {
                $_data = "có";
            } else {
                $_data = "không";
            }
        } else if ($aColumns[$i] == 'service') {
            if ($aRow[$aColumns[$i]] === '1') {
                $_data = "Tốc Hành";
            } else {
                $_data = "Tiết Kiệm";
            }
        } else if ($aColumns[$i] == 'config') {
            if ($aRow[$aColumns[$i]] === '1') {
                $_data = "Cho Xem Hàng Nhưng Không Cho Thử Hàng";
            } else if ($aRow[$aColumns[$i]] === '2') {
                $_data = "Cho Thử Hàng";
            } else {
                $_data = "Không Cho Xem Hàng";
            }
        } else if ($aColumns[$i] == 'payer') {
            if ($aRow[$aColumns[$i]] === '1') {
                $_data = "Người Gửi";
            } else {
                $_data = "Người Nhận";
            }
        }
        // else if($aColumns[$i] == 'tblstaff.firstname' ){
        //   var_dump($aRow[$aColumns[$i]]);
        //   die();
        // }
        else {
            $_data = $aRow[$aColumns[$i]];

        }


        $row[] = $_data;
    }

    if (is_admin()) {


        $icon_delete = '<a href="/system/admin/create_order/delete/' . $aRow['id'] . '" class="btn btn-danger delete-reminder-custom btn-icon">
        <i class="fa fa-remove"></i>
        </a>';

        $icon_print = '<a href="https://mysupership.com/orders/print?code=' . $aRow['code'] . '&size=S9" class="btn btn-primary btn-icon" target="_blank">
        <i class="fa fa-print"></i>
        </a>';


        $row[] = $icon_delete . $icon_print . '</div>';

    } else {
        $row[] = '';
    }

//    $row[9] = $row[9] . ', ' . $row[10] . ', ' . $row[11] . ', ' . $row[12];

//    if ($row[27] == '1') {
//        $row[26] = "<span style='color:red;font-weight:bold'> Đã Hủy </span>";
//    }


    if ($row[28] != NULL) {
        if ($row[28] == '1') {
            $row[27] = "<span style='color:red;font-weight:bold'> Đã Hủy </span>";
        }
    }


    $output['aaData'][] = $row;
}
