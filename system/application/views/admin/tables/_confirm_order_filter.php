<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$aColumns = array(
    'tbl_create_order.id',
    'created',
    'product',
    'required_code',
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
	'transport'
);
$sIndexColumn = "id";
$sTable = 'tbl_create_order';
$_POST['date_end_customer'] = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['date_end_customer']))) ." 23:59:00";
$_POST['date_start_customer'] = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['date_start_customer']))) ." 00:00:00";

$string_filter = "AND dvvc IS NULL AND status_cancel != '1' AND created <= '" . $_POST['date_end_customer'] . "'" . " AND created >= '" . $_POST['date_start_customer'] . "'" . " ";
if ($_POST['id_customer'] != '') {
    $string_filter = $string_filter . "AND customer_id = '" . $_POST['id_customer'] . "'" . " ";
} else if ($_POST['province_filter'] != 'null') {
    $string_filter = $string_filter . "AND province = '" . json_decode($_POST['province_filter'])->name . "'" . " ";
    if ($_POST['district_filter'] != 'null') {
        $string_filter = $string_filter . "AND district = '" . $_POST['district_filter'] . "'" . " ";
    }
} else if ($_POST['staff_filter'] != 'null') {
    $string_filter = $string_filter . "AND user_created = '" . $_POST['staff_filter'] . "'" . " ";
}

$where = [$string_filter];


$join = $join = [
    'LEFT JOIN ' . db_prefix() . 'customers ON ' . db_prefix() . 'customers.	id=' . db_prefix() . '_create_order.customer_id',
    'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.		staffid=' . db_prefix() . '_create_order.user_created',
];

$result = data_tables_init_cod($aColumns, $sIndexColumn, $sTable, $join, $where, array());
$output = $result['output'];

$rResult = $result['rResult'];

$j = 0;


foreach ($rResult as $aRow) {
	$transport = $aRow['transport'];
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
        } else {
            $_data = $aRow[$aColumns[$i]];
        }
        $row[] = $_data;
    }

    if (is_admin()) {


        $icon_delete = '<a href="/system/admin/confirm_order/delete/' . $aRow['id'] . '" class="btn btn-danger delete-reminder-custom btn-icon">
        <i class="fa fa-remove"></i>
        </a>';

        $icon_print = '<a href="javascript:;" class="btn btn-primary btn-icon" onclick="fnConfirm_Order(' . $aRow['id'] . ',\'' . $aRow['required_code'] . '\','.$aRow['weight'].',\''.$transport.'\','.$aRow['customer_id'].'"><i class="fa fa-check"></i></a>';


        $row[] = $icon_delete . $icon_print . '</div>';

    } else {
        $row[] = '';
    }
    $row[0] = '<input type="checkbox" name="cbOrder[]" value="'.$row[0].'" class="form-control"/>';
    $row[9] = $row[9] . ', ' . $row[10] . ', ' . $row[11] . ', ' . $row[12];

    if ($row[27] == '1') {
        $row[26] = "<span style='color:red;font-weight:bold'> Đã Hủy </span>";
    }
    if ($row[28] != NULL) {
        if ($row[28] == '0') {
            $row[28] = 'Người Nhận';
        } else {
            $row[28] = 'Người Gửi';

        }
    }
	$row[29] = $row[30];
    $output['aaData'][] = $row;
}
