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


        $warehouse_id_export = $this->ci->input->post('warehouse_id_export');
        $warehouse_id_import = $this->ci->input->post('warehouse_id_import');
        $custom_item_select = $this->ci->input->post('custom_item_select');
        $type_items = $this->ci->input->post('type_items');

        // //Chuyển kho: nhận
        $select = array(
            'tbltransfer_warehouse.date as date',
            'concat(tbltransfer_warehouse.prefix,"",tbltransfer_warehouse.code) as code',
            '2',
            'tbltransfer_warehouse_detail.localtion_id as localtion_id',
            'tbltransfer_warehouse_detail.quantity_net as quantity_net',
            'tbltransfer_warehouse_detail.localtion_to as localtion_to',
            'tbltransfer_warehouse_detail.quantity_net as quantity_net',
        );
        $where= array(
            'AND tbltransfer_warehouse.warehouseman_id != 0',
        );
        if(!empty($warehouse_id_export))
        {
            array_push($where, 'AND tbltransfer_warehouse.warehouse_id ='.$warehouse_id_export);  
        } 
        if(!empty($warehouse_id_import))
        {
            array_push($where, 'AND tbltransfer_warehouse.warehouse_to ='.$warehouse_id_import);  
        } 
        if(!empty($type_items))
        {
            array_push($where, 'AND tbltransfer_warehouse_detail.id_items =',$custom_item_select);   
            array_push($where, 'AND tbltransfer_warehouse_detail.type = "'.$type_items.'"');
        } 
        if(!empty($beginMonth)&&!empty($endMonth))
        {
            array_push($where, 'AND tbltransfer_warehouse.date >='.'"'.$beginMonth.' 00:00:00"');  
            array_push($where, 'AND tbltransfer_warehouse.date <='.'"'.$endMonth.' 23:59:59"');
        }
        $aColumns     = $select;
        $sIndexColumn = "id";
        $sTable       = 'tbltransfer_warehouse_detail';
        $join         = array(
            'LEFT JOIN tbltransfer_warehouse ON tbltransfer_warehouse.id = tbltransfer_warehouse_detail.id_transfer',
             
        );

        $order_by ='order by id_items asc';
        $result   = data_tables_init($aColumns , $sIndexColumn , $sTable , $join , $where , array('tbltransfer_warehouse.id as id_main,tbltransfer_warehouse_detail.id_items as product_id,tbltransfer_warehouse_detail.type as type,6 as exists_quantity'));

        $output  = $result['output'];
        $rResult = $result['rResult'];

        $currentPage=$this->ci->input->post('start');
            foreach ($rResult as $key => $aRow) {
            $row = [];
            $get_items = get_items($aRow['product_id'],$aRow['type']);
            for ($i = 0 ; $i < count($aColumns) ; $i++) {
                if(strpos($aColumns[$i],'as') !== false && !isset($aRow[ $aColumns[$i] ])){
                    $_data = $aRow[ strafter($aColumns[$i],'as ')];
                } else {
                    $_data = $aRow[ $aColumns[$i] ];
                }
                if($aColumns[$i]=='2')
                {
                    $_data = $get_items->name.' ('.$get_items->code.') '.format_item_purchases($aRow['type']);
                }
                if($aColumns[$i]=='tbltransfer_warehouse.date as date')
                {
                    $_data = '<div class="text-center">'._dhau($aRow['date']).'<div>';
                }
                if($aColumns[$i]=='tbltransfer_warehouse_detail.localtion_id as localtion_id')
                {
                    $_data = '<div class="text-center">'.get_listname_localtion_warehouse($aRow['localtion_id']).'<div>';
                }
                if($aColumns[$i]=='tbltransfer_warehouse_detail.localtion_to as localtion_to')
                {
                    $_data = '<div class="text-center">'.get_listname_localtion_warehouse($aRow['localtion_to']).'<div>';
                }
                if($aColumns[$i]=='tbltransfer_warehouse_detail.quantity_net as quantity_net')
                {
                    $_data = '<div class="text-center">'.formatNumber($aRow['quantity_net']).'<div>';
                }
                if($aColumns[$i]=='concat(tbltransfer_warehouse.prefix,"",tbltransfer_warehouse.code) as code')
                {
                  
                $_data =  '<div class="text-center"><a href="#" onclick="view_transfer('.$aRow['id_main'].'); return false;" >' . $_data . '</a><div>';   
                   
                }
                $row[] = $_data;
            }
            $output['aaData'][] = $row;
        }