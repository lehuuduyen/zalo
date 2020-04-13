<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$aColumns     = array(
    'id',
    'name',
    'phone',
    'email',
    'address',
    'opening_balance',

);
$sIndexColumn = "id";
$sTable       = 'tblporters';
$where        = array(
);
$join         = array(
);
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array(
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
        if($aColumns[$i]=='opening_balance')
        {
            $_data=number_format_data($_data);
        }
        $row[] = $_data;
    }
    if (is_admin()) {
        $_data = '<a href="#" class="btn btn-default btn-icon" onclick="view_init_department(' . $aRow['id'] . '); return false;"><i class="fa fa-eye"></i></a>';
        $row[] =$_data.icon_btn('porters/delete/'. $aRow['id'] , 'remove', 'btn-danger delete-reminder');
    } else {
        $row[] = '';
    }
    $output['aaData'][] = $row;
}
