<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hasPermissionDelete = has_permission('pay_slip', '', 'delete');

$aColumns     = array(
    'tblpay_slip_detail.id',
    'tblpurchase_order.code',
    'tblpay_slip_detail.type',
    '1',
    '2',
    '3',
    '4',
    'tblpurchase_order.totalAll_suppliers'
);
$sIndexColumn = "id";
$sTable       = 'tblpay_slip_detail';
$where        = array(
  
);
array_push($where, 'AND tblpay_slip_detail.id_pay_slip ='.$id);
$join         = array(
    'LEFT JOIN tblpurchase_order ON tblpurchase_order.id=tblpay_slip_detail.id_old',
);
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array(
    'tblpurchase_order.prefix','tblpay_slip_detail.id_old',
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
        if(in_array($aRow['tblpay_slip_detail.id'], $views))
        {
        $style = '<span onclick="no_view('.$aRow['tblpay_slip_detail.id'].'); return false;" style="padding: 1px 5px 0px 7px;cursor:pointer;" ><img src="'.base_url('assets/images/details_close.png').'"></i></span>';    
        }else
        {
        $style = '<span onclick="view('.$aRow['tblpay_slip_detail.id'].'); return false;" style="padding: 1px 5px 0px 7px;cursor:pointer;" ><img src="'.base_url('assets/images/details_open.png').'"></span>';
        }
        if ($aColumns[$i] == 'tblpay_slip_detail.id') {
        $_data ='<div class="text-center">'.$j.$style.'</div>';
        }
        if ($aColumns[$i] == 'tblpurchase_order.totalAll_suppliers') {
        $_data ='<div class="text-right">'.number_format($aRow['tblpurchase_order.totalAll_suppliers']).'</div>';
        }
        if ($aColumns[$i] == 'tblpurchase_order.code') {
        $imports  = $aRow['prefix'].'-'.$aRow['tblpurchase_order.code'];
        $_data=$imports;
        }

        $row[] = $_data;
    }
    $_data='';
    $output['aaData'][] = $row;
    if(in_array($aRow['tblpay_slip_detail.id'], $views))
        {
            $this->ci->load->model('purchase_order_model');
            $import = $this->ci->purchase_order_model->get_items_purchase_order($aRow['id_old']);
            foreach ($import as $key => $value) {
                $row=[];
                $row[]='<div class="text-center">'.($key+1).'</div>';
                $row[]=format_item_purchases($value['type']);
                $row[]=$value['name_item'];
                $row[]='<div class="text-center">'.number_format($value['quantity_suppliers']).'</div>';
                $row[]='<div class="text-right">'.number_format($value['price_suppliers']).'</div>';
                $row[]='<div class="text-right">'.number_format(($value['tax_rate']/100)*$value['quantity_suppliers']*$value['price_suppliers']).'</div>';
                $row[]='<div class="text-right">'.number_format($value['promotion_expected']).'</div>';
                $row[]='<div class="text-right">'.number_format($value['total_suppliers']).'</div>';
                $output['aaData'][] = $row;
            }
        }
}
