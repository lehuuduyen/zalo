<?php

defined('BASEPATH') or exit('No direct script access allowed');


$aColumns = [
    'name',
	'color'
];

$sIndexColumn = 'id';
$sTable       = 'tblsolutions';
$where        = [];
$join         = array();


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
	'id'
]);
$output  = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $key => $aRow) {
    $row = [];
    $row[] = $aRow['name'];
	$row[] ='<span style="background: '.$aRow['color'].';">'.$aRow['color'].'</span>';
    $options = icon_btn('#', 'edit', 'btn-info', ['onclick' => "editSolution('".$aRow['id']."'); return false;"]);
    $options .= icon_btn('#', 'remove', 'btn-danger delete-remind', ['onclick' => "deleteData(".$aRow['id'].", '".('fields_data_solutions/delete')."'); return false;"]);
    $row[] = $options;
    $output['aaData'][] = $row;
}
