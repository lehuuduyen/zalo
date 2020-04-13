<?php

defined('BASEPATH') or exit('No direct script access allowed');
$beginMonth =  '';
$endMonth   =  '';
        $months_report = $this->ci->input->post('report_months');

        if ($months_report != '') {
            $custom_date_select = '';
            if (is_numeric($months_report)) {
                // Last month
                if ($months_report == '1') {
                    $beginMonth = date('Y-m-01', strtotime('first day of last month'));
                    $endMonth   = date('Y-m-t', strtotime('last day of last month'));
                } else {
                    $months_report = (int) $months_report;
                    $months_report--;
                    $beginMonth = date('Y-m-01', strtotime("-$months_report MONTH"));
                    $endMonth   = date('Y-m-t');
                }
            } elseif ($months_report == 'this_month') {
                $beginMonth = date('Y-m-01');
                $endMonth   = date('Y-m-t');
            } elseif ($months_report == 'this_year') {
                $beginMonth = date('Y-m-d', strtotime(date('Y-01-01')));
                $endMonth   = date('Y-m-d', strtotime(date('Y-12-31')));
            } elseif ($months_report == 'last_year') {
                $beginMonth = date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01')));
                $endMonth   = date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31')));
            } elseif ($months_report == 'custom') {
                $from_date = to_sql_date($this->ci->input->post('report_from'));
                $to_date   = to_sql_date($this->ci->input->post('report_to'));
                if ($from_date == $to_date) {
                    $beginMonth =  $to_date;
                    $endMonth   =  $to_date;
                } else {
                    $beginMonth =  $from_date;
                    $endMonth   =  $to_date;
                }
            }
            // $this->db->where($custom_date_select);
        }


        $warehouse_id = $this->ci->input->post('warehouse_id');
        $custom_item_select = $this->ci->input->post('custom_item_select');
        $type_items = $this->ci->input->post('type_items');
$aColumns = [
    'tblwarehouse_product.id',
    'tblwarehouse_product.type_items',
    '1',
    '2',
    '4',
    '5',
    '6',
    '7',
    '8',
    '9',
    '10',
];
$sIndexColumn = 'id';
$sTable       = db_prefix().'warehouse_product';

$join         = array(
    'LEFT JOIN tbllocaltion_warehouses ON tbllocaltion_warehouses.id = tblwarehouse_product.localtion',
    'LEFT JOIN tbltype_items ON tbltype_items.type = tblwarehouse_product.type_items',
);
        $where= array(
            'AND tblwarehouse_product.warehouse_id ='.$warehouse_id,
        );
        if(!empty($type_items))
        {
            array_push($where, 'AND tblwarehouse_product.product_id =',$custom_item_select);   
            array_push($where, 'AND tblwarehouse_product.type_items = "'.$type_items.'"');
        }
$group_by ="GROUP BY tblwarehouse_product.product_id,tblwarehouse_product.type_items";
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['tblwarehouse_product.product_id','tbltype_items.name as name_type'],$group_by);
$output  = $result['output'];
$rResult = $result['rResult'];

usort($rResult, ch_make_cmp(['tblwarehouse_product.type_items' => "desc", 'product_id' => "desc"]));
$currentPage=$this->_instance->input->post('start');
$currentall=$output['iTotalRecords'];
foreach ($rResult as $r => $aRow) {
    $row = [];
    $get_items = get_items($aRow['product_id'],$aRow['tblwarehouse_product.type_items']);
    $sumFExistsQall=getStartInventory($aRow['product_id'],$aRow['tblwarehouse_product.type_items'],$warehouse_id,$beginMonth);
    $sumFExistsQall_import=getStartInventory_import($aRow['product_id'],$aRow['tblwarehouse_product.type_items'],$warehouse_id,$beginMonth,$endMonth);
    $sumFExistsQall_export=getStartInventory_export($aRow['product_id'],$aRow['tblwarehouse_product.type_items'],$warehouse_id,$beginMonth,$endMonth);
    for ($i = 0 ; $i < count($aColumns) ; $i++) {
        if(strpos($aColumns[$i],'as') !== false && !isset($aRow[ $aColumns[$i] ])){
            $_data = $aRow[ strafter($aColumns[$i],'as ')];
        } else {
            $_data = $aRow[ $aColumns[$i] ];
        }
        if($aColumns[$i]=='tblwarehouse_product.id')
        {
            $_data = $get_items->code.'<br>'.format_item_purchases($aRow['tblwarehouse_product.type_items']);
        }
        if($aColumns[$i]=='1')
        {
            $_data = '<div class="text-center">'.$get_items->unit_name.'<div>';
        }
        if($aColumns[$i]=='2')
        {
            $_data = '<div class="text-center">'.formatNumber($sumFExistsQall).'<div>';
        }
        if($aColumns[$i]=='4')
        {
            $_data = '<div class="text-right">'.number_format($sumFExistsQall*$get_items->price).'<div>';
        }
        if($aColumns[$i]=='5')
        {
            $_data = '<div class="text-center">'.formatNumber($sumFExistsQall_import).'<div>';
        }
        if($aColumns[$i]=='6')
        {
            $_data = '<div class="text-right">'.number_format($sumFExistsQall_import*$get_items->price).'<div>';
        }
        if($aColumns[$i]=='7')
        {
            $_data = '<div class="text-center">'.formatNumber($sumFExistsQall_export).'<div>';
        }
        if($aColumns[$i]=='8')
        {
            $_data = '<div class="text-right">'.number_format($sumFExistsQall_export*$get_items->price).'<div>';
        }
        if($aColumns[$i]=='9')
        {
            // $sumFExistsQall-=$sumFExistsQall_export;
            $_data = '<div class="text-center">'.formatNumber($sumFExistsQall+$sumFExistsQall_import-$sumFExistsQall_export).'<div>';
        }
        if($aColumns[$i]=='10')
        {
            $_data = '<div class="text-right">'.number_format(($sumFExistsQall+$sumFExistsQall_import-$sumFExistsQall_export)*$get_items->price).'<div>';
        }
        if($aColumns[$i]=='tblwarehouse_product.type_items')
        {
            $_data = $get_items->name;
        }
        $row[] = $_data;
    }

    $output['aaData'][] = $row;
}
