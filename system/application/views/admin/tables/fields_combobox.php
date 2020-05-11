<?php

defined('BASEPATH') or exit('No direct script access allowed');


$aColumns = [
    'name'
];

$sIndexColumn = 'id';
$sTable       = 'tblcombobox_client';
$where        = [];

if($this->ci->input->post('filler_combobox')) {
	$type = $this->ci->input->post('filler_combobox');
	$where        = ['AND type = "'.$type.'"'];
}
$join         = array();


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
	'type',
	'id'
]);
$output  = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $key => $aRow) {
    $row = [];
    $row[] = $aRow['name'];
    $options = icon_btn('#', 'edit', 'btn-info', ['onclick' => "editCombobox(".$aRow['id'].", '".$aRow['type']."'); return false;"]);
    $options .= icon_btn('#', 'remove', 'btn-danger delete-remind', ['onclick' => "deleteData(".$aRow['id'].", '".('fields_data/delete')."'); return false;"]);
    $row[] = $options;
    $output['aaData'][] = $row;
}
