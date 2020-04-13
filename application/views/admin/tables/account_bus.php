<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$aColumns     = array(
    'id',
    'name',
    'note',

);
$sIndexColumn = "id";
$sTable       = 'tblaccount_business';
$where        = array(
);
$join         = array(
);
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array(

));
$output       = $result['output'];
$rResult      = $result['rResult'];

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

        $ktr = get_table_where('tblpayment_order',array('account_business'=>$aRow['id']),'','row');
        if(empty($ktr))
        {
        $_data.=icon_btn('account_bus/delete_account_bus/'. $aRow['id'] , 'remove', 'btn-danger delete-remind');
        }

        $row[] =$_data;
        
    } else {
        $row[] = '';
    }
    $output['aaData'][] = $row;
}
