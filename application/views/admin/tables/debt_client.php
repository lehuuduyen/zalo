<?php

defined('BASEPATH') or exit('No direct script access allowed');

$hasPermissionDelete = has_permission('debt_suppliers', '', 'delete');

$this->ci->db->query("SET sql_mode = ''");

$aColumns = [
    'tblclients.userid',
    'tblclients.company',
    'SUM(tbl_orders.grand_total) as total_import',
    '(COALESCE(SUM(tbl_orders.total_payment),0)+ COALESCE(SUM(tbl_orders.price_other_expenses),0))  as total_payment_import',
    'SUM(tbl_orders.price_other_expenses) as price_other_expenses_import',
    '4',
    '5',
    '6',
    '7',
];
$sIndexColumn = 'userid';
$sTable       = 'tblclients';
$where        = [];
$having = 'HAVING (total_import - total_payment_import) > 0';
$filter = [];
$join = [
    'LEFT JOIN tbl_orders ON tbl_orders.customer_id=tblclients.userid',
];
$customer_id = $this->ci->input->post('customer_id');
if(!empty($customer_id))
{
    array_push($where, 'AND tblclients.userid IN('.trim($customer_id,',').')');
}
$filterStatus = $this->ci->input->post('filterStatus');
if($filterStatus == 1)
{
    // array_push($where, 'AND tblclients.debt_limit > 0 AND tblclients.debt_limit < ((select(SUM(tbl_orders.grand_total)) from tbl_orders where tbl_orders.customer_id=tblclients.userid ) -(select((COALESCE(SUM(tbl_orders.total_payment),0)+ COALESCE(SUM(tbl_orders.price_other_expenses),0) + COALESCE(SUM(tblpurchase_invoice.total_payment),0))) from tbl_orders left JOIN tblpurchase_invoice ON tblpurchase_invoice.id=tbl_orders.red_invoice where ((tbl_orders.status_pay != 2 AND tbl_orders.red_invoice = 0) or (tblpurchase_invoice.status != 2 AND tbl_orders.status_pay = 0)) AND tbl_orders.customer_id=tblclients.userid))');
}
$group_by = 'GROUP BY tblclients.userid';
$result = data_tables_init_having($aColumns, $sIndexColumn, $sTable, $join, $where, [

],'',$group_by,$having);
$output  = $result['output'];
$rResult = $result['rResult'];
$j=0;
$today = date('Y-m-d');
$week30s = strtotime(date("Y-m-d", strtotime($today)) . " -30 day");
$week30 = strftime("%Y-%m-%d", $week30s);
$week60s = strtotime(date("Y-m-d", strtotime($today)) . " -60 day");
$week60 = strftime("%Y-%m-%d", $week60s);
$week90s = strtotime(date("Y-m-d", strtotime($today)) . " -90 day");
$week90 = strftime("%Y-%m-%d", $week90s);

$footer_data = array(
    'debt_total' => 0,
    'debt_30N' => 0,
    'debt_30N60N' => 0,
    'debt_60N' => 0,
);

foreach ($rResult as $aRow) {
    $row = array();
    $j++;
    for ($i = 0; $i < count($aColumns); $i++) {
        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }
        if ($aColumns[$i] == 'tblclients.userid') {
        $_data ='<div class="text-center">'.$j.'</div>';
        }
        if ($aColumns[$i] == 'tblclients.company') {
        $_data ='<a onclick="client_detail('.$aRow['tblclients.userid'].')">'.$aRow['tblclients.company'].'</a>';
        }
        if ($aColumns[$i] == '(COALESCE(SUM(tbl_orders.total_payment),0)+ COALESCE(SUM(tbl_orders.price_other_expenses),0))  as total_payment_import') {
            $_data = number_format($aRow['total_payment_import'] - $aRow['price_other_expenses_import']);      
        }
        if ($aColumns[$i] == '7') {
        $_data =number_format($aRow['total_import']-$aRow['total_payment_import']);
        $footer_data['debt_total']+=$aRow['total_import']-$aRow['total_payment_import'];
        }
        if ($aColumns[$i] == 'SUM(tbl_orders.price_other_expenses) as price_other_expenses_import') {
        $_data =number_format($aRow['price_other_expenses_import']); 
        }
        if ($aColumns[$i] == 'SUM(tbl_orders.grand_total) as total_import') {
        $_data =number_format($aRow['total_import']);
          
        }
        if ($aColumns[$i] == '4') {
            $whereJoin=array();
            $whereJoin['where']=array(
              'tbl_orders.customer_id' =>$aRow['tblclients.userid'],
              'tbl_orders.date >=' =>$week30,
              'tbl_orders.date <=' =>$today,
            );
            $whereJoin['join'] =array();
            $whereJoin['field']='grand_total';
            $subtotal=sum_from_table_join('tbl_orders',$whereJoin);

            $whereJoin1=array();
            $whereJoin1['where']=array(
              'tbl_orders.customer_id' =>$aRow['tblclients.userid'],
              'tbl_orders.date >=' =>$week30,
              'tbl_orders.date <=' =>$today,
            );
            $whereJoin1['join'] =array();
            $whereJoin1['field']='tbl_orders.total_payment';
            $total_payment=sum_from_table_join('tbl_orders',$whereJoin1);

            $whereJoin2=array();
            $whereJoin2['where']=array(
              'tbl_orders.customer_id' =>$aRow['tblclients.userid'],
              'tbl_orders.date >=' =>$week30,
              'tbl_orders.date <=' =>$today,
            );
            $whereJoin2['join'] =array();
            $whereJoin2['field']='tbl_orders.price_other_expenses';
            $total_payment_invoice=sum_from_table_join('tbl_orders',$whereJoin2);
            $footer_data['debt_30N']+=($subtotal - $total_payment -$total_payment_invoice);   
            $_data =number_format($subtotal - $total_payment -$total_payment_invoice);
        }
        if ($aColumns[$i] == '5') {
            $whereJoin=array();
            $whereJoin['where']=array(
              'tbl_orders.customer_id' =>$aRow['tblclients.userid'],
              'tbl_orders.date >=' =>$week60,
              'tbl_orders.date <' =>$week30,
            );
            $whereJoin['join']=array();
            $whereJoin['field']='grand_total';
            $subtotal=sum_from_table_join('tbl_orders',$whereJoin);
                        $whereJoin1=array();
            $whereJoin1['where']=array(
              'tbl_orders.customer_id' =>$aRow['tblclients.userid'],
              'tbl_orders.date >=' =>$week60,
              'tbl_orders.date <' =>$week30,
            );
            $whereJoin1['join'] =array();
            $whereJoin1['field']='tbl_orders.total_payment';
            $total_payment=sum_from_table_join('tbl_orders',$whereJoin1);

            $whereJoin2=array();
            $whereJoin2['where']=array(
              'tbl_orders.customer_id' =>$aRow['tblclients.userid'],
              'tbl_orders.date >=' =>$week60,
              'tbl_orders.date <' =>$week30,
            );
            $whereJoin2['join'] =array();
            $whereJoin2['field']='tbl_orders.price_other_expenses';
            $total_payment_invoice=sum_from_table_join('tbl_orders',$whereJoin2);
            $footer_data['debt_30N60N']+=($subtotal - $total_payment -$total_payment_invoice); 
            $_data =number_format($subtotal - $total_payment -$total_payment_invoice);
        }
        if ($aColumns[$i] == '6') {
            $whereJoin=array();
            $whereJoin['where']=array(
              'tbl_orders.customer_id' =>$aRow['tblclients.userid'],
              'tbl_orders.date <' =>$week60,
            );
            $whereJoin['join']=array();
            $whereJoin['field']='grand_total';
            $subtotal=sum_from_table_join('tbl_orders',$whereJoin);
                        $whereJoin1=array();
            $whereJoin1['where']=array(
              'tbl_orders.customer_id' =>$aRow['tblclients.userid'],
              'tbl_orders.date <' =>$week60,
            );
            $whereJoin1['join'] =array();
            $whereJoin1['field']='tbl_orders.total_payment';
            $total_payment=sum_from_table_join('tbl_orders',$whereJoin1);

            $whereJoin2=array();
            $whereJoin2['where']=array(
              'tbl_orders.customer_id' =>$aRow['tblclients.userid'],
              'tbl_orders.date <' =>$week60,
            );
            $whereJoin2['join'] =array();
            $whereJoin2['field']='tbl_orders.price_other_expenses';
            $total_payment_invoice=sum_from_table_join('tbl_orders',$whereJoin2);
            $footer_data['debt_60N']+=($subtotal - $total_payment -$total_payment_invoice); 
            $_data =number_format($subtotal - $total_payment -$total_payment_invoice);
        }
        $row[] = $_data;
    }
    $output['aaData'][] = $row;
}
foreach ($footer_data as $key => $total) {
    $footer_data[$key] = number_format($total);
}
$output['sums'] = $footer_data;