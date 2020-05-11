<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hasPermissionDelete = has_permission('pay_slip', '', 'delete');

$aColumns     = array(
    'tblimport.id',
    'tblimport.code',
    'tblimport.type',
    '1',
    '2',
    '3',
    'tblimport.total'
);
$sIndexColumn = "id";
$sTable       = 'tblimport';
$where        = array(
  
);
array_push($where, 'AND tblpay_slip_detail.id_pay_slip ='.$id);
$join         = array(
    'LEFT JOIN tblpurchase_invoice ON tblpurchase_invoice.id=tblimport.red_invoice',
    'LEFT JOIN tblpay_slip_detail ON tblpay_slip_detail.id_old=tblpurchase_invoice.id',
);
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array(
    'tblimport.prefix',
));
$views =array();
$view = $this->ci->input->post('view');
if(!empty($view)) {
    $views = explode(',',$view);
}
$output       = $result['output'];
$rResult      = $result['rResult'];
$j=0;
foreach ($rResult as $aRow) {
    $row = array();
    $j++;
    for ($i = 0; $i < count($aColumns); $i++) {
        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = '';
        }
        if(in_array($aRow['tblimport.id'], $views))
        {
        $style = '<span onclick="no_view('.$aRow['tblimport.id'].'); return false;" style="padding: 1px 5px 0px 7px;cursor:pointer;" ><img src="'.base_url('assets/images/details_close.png').'"></i></span>';    
        }else
        {
        $style = '<span onclick="view('.$aRow['tblimport.id'].'); return false;" style="padding: 1px 5px 0px 7px;cursor:pointer;" ><img src="'.base_url('assets/images/details_open.png').'"></span>';
        }
        if ($aColumns[$i] == 'tblimport.id') {
        $_data ='<div class="text-center">'.$j.$style.'</div>';
        }
        if ($aColumns[$i] == 'tblimport.total') {
        $_data ='<div class="text-right">'.number_format($aRow['tblimport.total']).'</div>';
        }
        if ($aColumns[$i] == 'tblimport.code') {
        $imports  = $aRow['prefix'].'-'.$aRow['tblimport.code'];
        $_data=$imports;
        }

        $row[] = $_data;
    }
    $_data='';
    $output['aaData'][] = $row;
    if(in_array($aRow['tblimport.id'], $views))
        {
            $this->ci->load->model('import_model');
            $import = $this->ci->import_model->get_items_import($aRow['tblimport.id']);
            foreach ($import as $key => $value) {
                $row=[];
                $row[]='<div class="text-center">'.($key+1).'</div>';
                $row[]=format_item_purchases($value['type']);
                $row[]=$value['name_item'];
                $row[]='<div class="text-center">'.number_format($value['quantity_net']).'</div>';
                $row[]='<div class="text-right">'.number_format($value['price']).'</div>';
                $row[]='<div class="text-right">'.number_format(($value['tax_rate']/100)*$value['quantity_net']*$value['price']).'</div>';
                $row[]='<div class="text-right">'.number_format($value['amount']).'</div>';
                $output['aaData'][] = $row;
            }
        }
}
    