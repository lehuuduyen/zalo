<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$aColumns     = array(
    'id',
    'name',
    'time',
);
$sIndexColumn = "id";
$sTable       = 'tblpayment_time_level';
$where        = array(
//    'AND id_lead="' . $rel_id . '"'
);
$join         = array(
    // 'LEFT JOIN tblroles  ON tblroles.roleid=tbldepartment.id_role'
);
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array(
    // 'tblroles.name',
    // 'tblroles.roleid'
));
$output       = $result['output'];
$rResult      = $result['rResult'];
//var_dump($rResult);die();


$j=0;
foreach ($rResult as $aRow) {
    $row = array();
    $j++;
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        $row[] = $_data;
    }
    if (is_admin()) {
        $_data = '<a href="#" class="btn btn-default btn-icon" onclick="view_init_department(' . $aRow['id'] . '); return false;"><i class="fa fa-eye"></i></a>';
        $row[] =$_data.icon_btn('payment_time_level/delete_payment_time_level/'. $aRow['id'] , 'remove', 'btn-danger delete-remind');
    } else {
        $row[] = '';
    }
    $output['aaData'][] = $row;
}
