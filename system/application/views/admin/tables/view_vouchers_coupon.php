<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hasPermissionDelete = has_permission('pay_slip', '', 'delete');

$aColumns     = array(
    'tbl_orders.id',
    'tbl_orders.reference_no',
    'tbl_orders.grand_total',
    '(COALESCE(tbl_orders.total_payment,0)+ COALESCE(tbl_orders.price_other_expenses,0))  as total_payment_order',
    '1'
);
$sIndexColumn = "id";
$sTable       = 'tbl_orders';
$where        = array(
  
);
$vouchers_coupon = get_table_where('tblvouchers_coupon',array('id'=>$id),'','row');
$id_order = explode(',', $vouchers_coupon->arr_code_orders);
$order = array();
$total_order = array();
foreach ($id_order as $key => $value) {
    $v = explode('|', $value);
    $order[] = $v[0];
    $total_order[$v[0]]=$v[1];
}
array_push($where, 'AND tbl_orders.id IN('.implode(',', $order).')');

$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,array(), $where, array(
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
        // if(in_array($aRow['tbl_orders.id'], $views))
        // {
        // $style = '<span onclick="no_view('.$aRow['tbl_orders.id'].'); return false;" style="padding: 1px 5px 0px 7px;cursor:pointer;" ><img src="'.base_url('assets/images/details_close.png').'"></i></span>';    
        // }else
        // {
        // $style = '<span onclick="view('.$aRow['tbl_orders.id'].'); return false;" style="padding: 1px 5px 0px 7px;cursor:pointer;" ><img src="'.base_url('assets/images/details_open.png').'"></span>';
        // }
        if ($aColumns[$i] == '1') {
        $_data ='<div class="text-right">'.number_format($total_order[$aRow['tbl_orders.id']]).'</div>';
        }
        if ($aColumns[$i] == 'tbl_orders.id') {
        $_data ='<div class="text-center">'.$j.'</div>';
        }
        if ($aColumns[$i] == '(COALESCE(tbl_orders.total_payment,0)+ COALESCE(tbl_orders.price_other_expenses,0))  as total_payment_order') {
        $_data ='<div class="text-right">'.number_format($aRow['total_payment_order'] - $total_order[$aRow['tbl_orders.id']]).'</div>';
        }
        if ($aColumns[$i] == 'tbl_orders.grand_total') {
        $_data ='<div class="text-right">'.number_format($aRow['tbl_orders.grand_total']).'</div>';
        }
        if ($aColumns[$i] == 'tbl_orders.reference_no') {
        $imports  = $aRow['tbl_orders.reference_no'];
        $_data=$imports;
        }

        $row[] = $_data;
    }
    $_data='';
    $output['aaData'][] = $row;
    // if(in_array($aRow['tbl_orders.id'], $views))
    //     {
    //         $this->ci->load->model('orders_model');
    //         $import = $this->ci->orders_model->getOrderItemsByOrderId($aRow['tbl_orders.id']);
    //         foreach ($import as $key => $value) {
    //             $row=[];
    //             $row[]='<div class="text-center">'.($key+1).'</div>';
    //             $row[]='<div class="type-item">'.(($value['type_item'] == "products") ? '<span class="label label-success">'.lang($value['type_item']).'</span>' : '<span class="label label-primary">'.lang('ch_items').'</span>').'</div>';
    //             $row[]=$value['item_name'].'('.$value['item_code'].')';
    //             $row[]='<div class="text-center">'.number_format($value['quantity']).'</div>';
    //             $row[]='<div class="text-right">'.number_format($value['price']).'</div>';
    //             $row[]='<div class="text-right">'.number_format(($value['tax_rate_item']/100)*$value['quantity']*$value['price']).'</div>';
    //             $row[]='<div class="text-right">'.number_format($value['discount_percent_amount_item']).'</div>';
    //             $row[]='<div class="text-right">'.number_format($value['total_amount']).'</div>';
    //             $output['aaData'][] = $row;
    //         }
    //     }
}
