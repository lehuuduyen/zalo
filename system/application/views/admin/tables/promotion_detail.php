<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'tblpromotion.id',
    'tblpromotion_list.name',
	'tblpromotion.name',
    'tblpromotion.type',
    'tblpromotion.method_of_application',
    'tblpromotion.area_of_application',
    'tblpromotion.customer_id',
    'tblpromotion.groups_in',
    '9',
    '10'
];

$sIndexColumn = 'id';
$sTable       = 'tblpromotion';

$join         = array(
    'LEFT JOIN tblpromotion_list ON tblpromotion_list.id = tblpromotion.promotion_list_id'
);

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, [], array(
    'tblpromotion.date_active_start as date_active_start',
    'tblpromotion.date_active_end as date_active_end'
));
$output  = $result['output'];
$rResult = $result['rResult'];
$currentPage=$this->_instance->input->post('start');
$currentall=$output['iTotalRecords'];
foreach ($rResult as $r => $aRow) {
    $row = [];
    for ($i = 0 ; $i < count($aColumns) ; $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'tblpromotion.id') {
            $_data = ($currentall+1)-($currentPage+$r+1);
        }
        else if ($aColumns[$i] == 'tblpromotion.type') {
            $_data = '';
            if($aRow['tblpromotion.type'] == 'discount') {
                $_data = '<span class="inline-block label label-warning">'._l('promotion_by_discount').'</span>';
            }
            else if($aRow['tblpromotion.type'] == 'item') {
                $_data = '<span class="inline-block label label-success">'._l('promotion_by_item').'</span>';
            }
            else if($aRow['tblpromotion.type'] == 'sales') {
                $_data = '<span class="inline-block label label-danger">'._l('promotion_by_sales').'</span>';
            }
        }
        else if ($aColumns[$i] == 'tblpromotion.method_of_application') {
            $_data = '';
            if($aRow['tblpromotion.method_of_application'] == 'one') {
                $_data = '<span class="inline-block label label-warning">'._l('promotion_application_one').'</span>';
            }
            else if($aRow['tblpromotion.method_of_application'] == 'all') {
                $_data = '<span class="inline-block label label-success">'._l('promotion_application_all').'</span>';
            }
            else if($aRow['tblpromotion.method_of_application'] == 'other') {
                $_data = '<span class="inline-block label label-danger">'._l('promotion_application_other').'</span>';
            }
        }
        else if ($aColumns[$i] == 'tblpromotion.area_of_application') {
            $_data = '';
            if($aRow['tblpromotion.area_of_application'] == 'all') {
                $_data = '<span class="inline-block label label-warning">'._l('cong_all').'</span>';
            }
            else if($aRow['tblpromotion.area_of_application'] == 'area') {
                $_data = '<span class="inline-block label label-success">'._l('promotion_area').'</span>';
            }
            else if($aRow['tblpromotion.area_of_application'] == 'other') {
                $_data = '<span class="inline-block label label-danger">'._l('promotion_area_other').'</span>';
            }
        }
        else if ($aColumns[$i] == 'tblpromotion.customer_id') {
            $_data = '';
            if($aRow['tblpromotion.customer_id'] > 0) {
                $_data = get_table_where('tblclients',array('userid'=>$aRow['tblpromotion.customer_id']),'','row')->company;
            }
            else {
                $_data = '';
            }
        }
        else if ($aColumns[$i] == 'tblpromotion.groups_in') {
            $_data = '';
            if($aRow['tblpromotion.groups_in']) {
                $val = '';
                $arr = explode(',', $aRow['tblpromotion.groups_in']);
                foreach ($arr as $key_arr => $value_arr) {
                    $get_name = get_table_where('tblcustomers_groups',array('id'=>$value_arr),'','row');
                    if($get_name) {
                        $val .= $get_name->name . ', ';
                    }
                }
                $_data = trim($val,', ');
            }
            else {
                $_data = '';
            }
        }
        else if ($aColumns[$i] == '9') {
            $_data = '';
            $_data .= _d($aRow['date_active_start']) .' - '. _d($aRow['date_active_end']);
        }
        else if ($aColumns[$i] == '10') {
            $_data = '';
            $_outputStatus = '<div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">'._l('action').'
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu h_right">
                                    <li><a href="'.admin_url('promotion/detail/'.$aRow['tblpromotion.id']).'"><i class="fa fa-edit"></i> '._l('edit').'</a></li>
                                    <li><a onclick="delete_promotion('.$aRow['tblpromotion.id'].');return false;" class="delete-remind"><i class="fa fa-remove"></i> '._l('delete').'</a></li>
                                </ul>
                            </div>';
            $_data = $_outputStatus;
        }
        $row[] = $_data;
    }

    $output['aaData'][] = $row;
}
