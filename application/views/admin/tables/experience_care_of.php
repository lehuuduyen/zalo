<?php

defined('BASEPATH') or exit('No direct script access allowed');

$theme_of = StatusThemeCare_of();
$aColumns = [
	'name',
	'type',
	'theme'
];

$sIndexColumn = 'id';
$sTable       = 'tblexperience_care_of_client';
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
	$row[] = !empty($aRow['theme']) ? $theme_of[$aRow['theme']]['name'] : '';
	$row[] = $aRow['type'];
	$options = '';
	if($aRow['type'] == 'select')
	{
		$options = icon_btn('#', 'eye', 'btn-info', [
			'onclick' => "ViewExperience_care_of_detail(".$aRow['id']."); return false;",
			'data-toggle' => 'tooltip',
			'title' => _l('cong_view_detail_and_add_data_detail')
		]);
		$options .= icon_btn('#', 'edit', 'btn-default', [
			'onclick' => "editExperience_care_of(".$aRow['id']."); return false;",
			'data-toggle' => 'tooltip',
			'title' => _l('cong_edit_data')
		]);
	}
	else
	{
		$options .= icon_btn('#', 'edit', 'btn-default mleft25', [
			'onclick' => "editExperience_care_of(".$aRow['id']."); return false;",
			'data-toggle' => 'tooltip',
			'title' => _l('cong_edit_data')
		]);
	}

	$options .= icon_btn('#', 'remove', 'btn-danger delete-remind', [
		'onclick' => "deleteData(".$aRow['id'].", '".('fields_data_experience/delete_experience_care_of')."'); return false;",
		'data-toggle' => 'tooltip',
		'title' => _l('cong_delete_data')
	]);
	$row[] = $options;
	$output['aaData'][] = $row;
}
