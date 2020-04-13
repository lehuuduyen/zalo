<?php

defined('BASEPATH') or exit('No direct script access allowed');


$aColumns = [
    'name'
];

$sIndexColumn = 'id';
$sTable       = 'tblexperience_care_of_client_detail';
$where        = ['AND id_detail = '.$id_detail];
$join         = array();



$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
	'id'
]);
$output  = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $key => $aRow) {
    $row = [];
	$row[] = EditColumTextarea($aRow['name'], $aRow['id'], 'name', admin_url('fields_data_experience/updateName_experience_care_of'),'class="formUpdateDataTable"');
    $options = icon_btn('#', 'remove', 'btn-danger delete-remind', [
    	'onclick' => "deleteData(".$aRow['id'].", '".('fields_data_experience/delete_experience_care_of_detail')."'); return false;",
	    'data-toggle' => 'tooltip',
	    'title' => _l('cong_delete_data_detail')
    ]);
    $row[] = $options;
    $output['aaData'][] = $row;
}
