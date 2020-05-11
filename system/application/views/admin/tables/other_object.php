<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$aColumns     = array(
    'id',
    'name',
    'cmnd',
    'phone',
    'address',
    'create_by',
    'opening_balance',

);
$sIndexColumn = "id";
$sTable       = 'tblother_object';
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
        if($aColumns[$i]=='create_by')
        {
            $_data = '<a href="' . admin_url('staff/member/' . $aRow['create_by']) . '">' . staff_profile_image($aRow['create_by'], array(
                    'staff-profile-image-small'
                )) . '</a>';
            $_data .= ' <a href="' . admin_url('staff/member/' . $aRow['create_by']) . '">' . get_staff_full_name($aRow['create_by']). '</a>';
        }
        $row[] = $_data;
    }
    if (is_admin()) {
        $_data = '<a href="#" class="btn btn-default btn-icon" onclick="view_init_department(' . $aRow['id'] . '); return false;"><i class="fa fa-eye"></i></a>';
        $row[] =$_data.icon_btn('other_object/delete_other_object/'. $aRow['id'] , 'remove', 'btn-danger delete-reminder');
    } else {
        $row[] = '';
    }
    $output['aaData'][] = $row;
}
