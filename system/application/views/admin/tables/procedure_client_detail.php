<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'tblprocedure_client_detail.name as name',
    'leadtime',
    'orders',
    'color'
];

$sIndexColumn = 'id';
$sTable       = 'tblprocedure_client_detail';
$where        = [];
if(!empty($id_detail))
{
    $where[] = 'AND id_detail = '.$id_detail;
}
$filter = [];

$join = [
	'JOIN tblprocedure_client on tblprocedure_client.id = tblprocedure_client_detail.id_detail'
];


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where,[
	'tblprocedure_client_detail.id as id',
	'type_object'
]);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    if($aRow['type_object'] == 1 && !empty($admin_change))
    {
        $row['DT_RowClass'] = 'dragger';
    }
    $row[] = $aRow['name'];
    $row[] = $aRow['leadtime'];
    $row[] = ($aRow['type_object'] == 1 ? $aRow['orders'] : '');
    
    $row[] ='<span style="background: '.$aRow['color'].';">'.$aRow['color'].'</span>';
    $options = "<input type='hidden' class='hidden_id' value='".$aRow['id']."'/>";
    $options .= icon_btn('#', 'pencil-square-o', 'btn-default', [
        'onclick' => 'editProcedure_client('.$aRow['id'].', this); return false;',
        'data-toggle' => 'tooltip',
        'title' => _l('cong_edit_title_from')
    ]);

    if(!empty($admin_change))
    {
	    $options .= icon_btn('#', 'remove', 'btn-danger delete-remind', ['onclick' => 'deleteProcedure_client('.$aRow['id'].', \'table-procadure_detail_'.$aRow['id'].'\', this); return false;']);
    }
    $row[]   = $options;
    $output['aaData'][] = $row;
}
