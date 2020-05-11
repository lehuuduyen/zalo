<?php
defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
	'name',
	'concat( COALESCE(month), "/", COALESCE(year) ) as month_year',
	'create_by',
	'date_create'
];

$sIndexColumn = 'id';
$sTable       = 'tblratio_staff';
$where        = [];

$filter = [];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where,[
	'id'
]);

$output  = $result['output'];
$rResult = $result['rResult'];

$count_success = 0;
$all_count = count($rResult);
foreach ($rResult as $aRow) {
    $row = [];
    $row[] = '<p>'.$aRow['name'].'</p>';
    $row[] = '<p>'.sprintf("%07s",$aRow['month_year']).'</p>';
    $fullnameStaffCreate = get_staff_full_name($aRow['create_by']);
    $profileStaffCreate = '<a data-toggle="tooltip" data-title="' . $fullnameStaffCreate . '" href="' . admin_url('profile/' . $aRow['create_by']) . '">' . staff_profile_image($aRow['create_by'], [
            'staff-profile-image-small',
        ]) . '</a>';
    $profileStaffCreate .= '<span class="hide">' . $fullnameStaffCreate . '</span>';
    $row[] = $profileStaffCreate;

    $row[] = _dt($aRow['date_create']);

    $option = '<a href="'.admin_url('ratio_staff/detail/'.$aRow['id']).'" class="btn btn-icon btn-default" data-toggle="tooltip" data-original-title="'._l('cong_edit_ratio').'"><i class="fa fa-pencil-square-o"></i></a>';
    $option .= '<a class="btn btn-icon btn-danger" onclick="deleteData('.$aRow['id'].', \'ratio_staff/Delete\')" data-toggle="tooltip" data-original-title="'._l('cong_delete_ratio').'"><i class="fa fa-remove"></i></a>';
	$row[] = $option;

    $output['aaData'][] = $row;
}
