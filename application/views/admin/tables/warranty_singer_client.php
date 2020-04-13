<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
	'tblseries.id',
    'tbl_export_warehouses.date',
    'tbl_export_warehouses.reference_no',
    'tblseries.series',
    'tblseries.type_item',
    '6',
    '7'
];

$sIndexColumn = 'id';
$sTable       = 'tblseries';

$join         = array(
    'LEFT JOIN tbl_export_warehouses ON tbl_export_warehouses.id = tblseries.id_export_warehouses'
);

$where        = array(
);

array_push($where, 'AND tblseries.id_customer = '.$clientid);

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, array(
    'tblseries.id_item'
));
$output  = $result['output'];
$rResult = $result['rResult'];
$currentPage=$this->_instance->input->post('start');
$currentall=$output['iTotalRecords'];
foreach ($rResult as $r => $aRow) {
    $row = [];
    for ($i = 0 ; $i < count($aColumns) ; $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'tbl_export_warehouses.date') {
            $_data = _dt($aRow['tbl_export_warehouses.date']);
        }
        else if ($aColumns[$i] == 'tblseries.type_item') {
            if ($aRow['tblseries.type_item'] == "products") {
                $_data = '<span class="label label-success">'._l('products').'</span>';
            } else if ($aRow['tblseries.type_item'] == "items") {
                $_data = '<span class="label label-warning">'._l('ch_items').'</span>';
            }
        }
        else if ($aColumns[$i] == '6') {
            if ($aRow['tblseries.type_item'] == "products") {
                $_data = get_table_where('tbl_products',array('id'=>$aRow['id_item']),'','row')->code;
            } else if ($aRow['tblseries.type_item'] == "items") {
                $_data = get_table_where('tblitems',array('id'=>$aRow['id_item']),'','row')->code;
            }
        }
        else if ($aColumns[$i] == '7') {
            if ($aRow['tblseries.type_item'] == "products") {
                $_data = get_table_where('tbl_products',array('id'=>$aRow['id_item']),'','row')->name;
            } else if ($aRow['tblseries.type_item'] == "items") {
                $_data = get_table_where('tblitems',array('id'=>$aRow['id_item']),'','row')->name;
            }
        }
        $row[] = $_data;
    }
    $output['aaData'][] = $row;
}
