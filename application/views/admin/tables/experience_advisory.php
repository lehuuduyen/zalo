<?php

defined('BASEPATH') or exit('No direct script access allowed');


$aColumns = [
    'name'
];

$sIndexColumn = 'id';
$sTable       = 'tblexperience_advisory';
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
    $options = icon_btn('#', 'eye', 'btn-info', [
    	'onclick' => "ViewExperience_advisory_detail(".$aRow['id']."); return false;",
	    'data-toggle' => 'tooltip',
	    'title' => _l('cong_view_detail_and_add_data_detail')
    ]);
    $options .= icon_btn('#', 'edit', 'btn-default', [
    	'onclick' => "editExperience_advisory(".$aRow['id']."); return false;",
	    'data-toggle' => 'tooltip',
	    'title' => _l('cong_edit_data')
    ]);
    $options .= icon_btn('#', 'remove', 'btn-danger delete-remind', [
    	'onclick' => "deleteData(".$aRow['id'].", '".('fields_data_experience/delete_experience_advisory')."'); return false;",
	    'data-toggle' => 'tooltip',
	    'title' => _l('cong_delete_data')
    ]);
    $row[] = $options;
    $output['aaData'][] = $row;
}
