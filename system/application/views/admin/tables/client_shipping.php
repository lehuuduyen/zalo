<?php

defined('BASEPATH') or exit('No direct script access allowed');


$aColumns = [
    'id',
    'name',
    'phone',
    'address',
    'address_primary',
];

$sIndexColumn = 'id';
$sTable       = db_prefix().'shipping_client';
$where        = ['AND client = '.$userid];
$join         = array();


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);
$output  = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $key => $aRow) {
    $row = [];
    $row[]    = ($key+1);
    $company = "";
    $company .= '<div class="row-options">';
    if (is_admin()) {

        $company .= '<a onclick="ChangeShippingClient(' . $aRow['id'] . ')">' . _l('view') . '</a>';
        $company .= ' | <a href="' . admin_url('clients/delete_shipping/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }

    $company .= '</div>';
    $row[]    = '<a onclick="ChangeShippingClient(' . $aRow['id'] . ')">'.$aRow['name'].'</a>'.$company;
    $row[]    = $aRow['phone'];
    $row[]    = $aRow['address'];
    $row[]    = '<div class="checkbox"><input class="check_address_primary" type="checkbox" value="' . $aRow['id'] . '" '.($aRow['address_primary'] == 1 ? 'checked' : '').'><label></label></div>';
    $output['aaData'][] = $row;
}
