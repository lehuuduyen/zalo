<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$aColumns     = array(
    'rackid',
    'rack',
    'note',
    'gross_ton',
    'route',
    'opening_balance',
    'not_freight',

);
$sIndexColumn = "rackid";
$sTable       = 'tblracks';
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
        if($aColumns[$i]=='opening_balance')
        {
            $_data=number_format_data($aRow['opening_balance']);
        }
        if($aColumns[$i]=='gross_ton')
        {
            $_data='<p class="">'.number_format_data($aRow['gross_ton']).'</p>';
        }
        if($aColumns[$i]=='not_freight'){
            if($aRow[$aColumns[$i]]==0)
            {
                $_data='Tính cước xe';
            }
            else
            {
                $_data='Không tính cước xe';
            }
        }
        $row[] = $_data;
    }
    $_data = '<a href="#" class="btn btn-default btn-icon" onclick="view_init_department(' . $aRow['rackid'] . '); return false;"><i class="fa fa-eye"></i></a>';
    $row[] =$_data.icon_btn('racks/delete_rack/'. $aRow['rackid'] , 'remove', 'btn-danger delete-reminder');

    $output['aaData'][] = $row;
}
