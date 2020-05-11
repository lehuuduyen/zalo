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
        }


        $warehouse_id = $this->ci->input->post('warehouse_id');
        $custom_item_select = $this->ci->input->post('custom_item_select');
        $type_items = $this->ci->input->post('type_items');
        $select = array(
            'tblimport.date_create',
            'tblimport.date',
            'concat(tblimport.prefix,"-",tblimport.code) as code',
            'tblpurchase_invoice.date_invoice',
            'tblpurchase_invoice.code_invoice',
            '1',
            '2',
            '3',
            'tblimport_items.quantity',
            'tblimport_items.price',
            'tblimport_items.tax_rate',
            'tblimport_items.promotion_suppliers',
            'tblimport_items.amount',
        );
        $where= array(
            'AND tblimport.warehouseman_id != 0',
        );
        if(!empty($type_items))
        {
            array_push($where, 'AND tblimport_items.product_id =',$custom_item_select);   
            array_push($where, 'AND tblimport_items.type = "'.$type_items.'"');
        }    
        if(!empty($beginMonth)&&!empty($endMonth))
        {
            array_push($where, 'AND tblimport.date >='.'"'.$beginMonth.' 00:00:00"');  
            array_push($where, 'AND tblimport.date <='.'"'.$endMonth.' 23:59:59"');
        }
        $aColumns     = $select;
        $sIndexColumn = "id";
        $sTable       = 'tblimport_items';
        $join         = array(
            'LEFT JOIN tblimport ON tblimport.id = tblimport_items.id_import',
            'LEFT JOIN tblpurchase_order ON tblpurchase_order.id = tblimport.id_order',
            'LEFT JOIN tblpurchase_invoice ON tblpurchase_invoice.id = tblpurchase_order.red_invoice',
        );

        $order_byimport='order by product_id asc';
        $result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, array('tblimport.id as id_main,tblimport_items.product_id as product_id,tblimport_items.type as type,1 as exists_quantity'));

        $output  = $result['output'];
        $rResult = $result['rResult'];
        $footer_data['total_quantity'] = 0; //so luong
        $footer_data['subtotals'] = 0; // tong tien
        $currentPage=$this->ci->input->post('start');
        $sumFExistsQ = 0;
            foreach ($rResult as $key => $aRow) {
            $row = [];
            $get_items = get_items($aRow['product_id'],$aRow['type']);
            $footer_data['total_quantity']+=$aRow['tblimport_items.quantity'];
            $footer_data['subtotals']+=$aRow['tblimport_items.amount'];
            for ($i = 0 ; $i < count($aColumns) ; $i++) {
                if(strpos($aColumns[$i],'as') !== false && !isset($aRow[ $aColumns[$i] ])){
                    $_data = $aRow[ strafter($aColumns[$i],'as ')];
                } else {
                    $_data = $aRow[ $aColumns[$i] ];
                }
                if($aColumns[$i]=='tblimport.date_create')
                {
                    $_data = _dhau($aRow['tblimport.date_create']);
                }
                if($aColumns[$i]=='tblimport.date')
                {
                    $_data = _d($aRow['tblimport.date']);
                }
                if($aColumns[$i]=='tblpurchase_invoice.date_invoice')
                {
                    $_data = _d($aRow['tblpurchase_invoice.date_invoice']);
                }
                if($aColumns[$i]=='concat(tblimport.prefix,"-",tblimport.code) as code')
                {
                    $_data = '<a href="#" onclick="view_import('.$aRow['id_main'].'); return false;" >' . $_data . '</a>';
                }
                if($aColumns[$i]=='1')
                {
                    $_data = $get_items->code.'<br>'.format_item_purchases($aRow['type']);
                }
                if($aColumns[$i]=='2')
                {
                    $_data = $get_items->name;
                }
                if($aColumns[$i]=='3')
                {
                    $_data = $get_items->unit_name;
                }                
                if($aColumns[$i]=='tblimport_items.price')
                {
                    $_data = number_format($aRow['tblimport_items.price']);
                }
                if($aColumns[$i]=='tblimport_items.quantity')
                {
                    $_data = formatNumber($aRow['tblimport_items.quantity']);
                }
                if($aColumns[$i]=='tblimport_items.tax_rate')
                {
                    $_data = number_format(($aRow['tblimport_items.tax_rate']/100)*$aRow['tblimport_items.price']*$aRow['tblimport_items.quantity']);
                }
                if($aColumns[$i]=='tblimport_items.promotion_suppliers')
                {
                    $_data = number_format($aRow['tblimport_items.promotion_suppliers']);
                }
                if($aColumns[$i]=='tblimport_items.amount')
                {
                    $_data = number_format($aRow['tblimport_items.amount']);
                }
                $row[] = $_data;
            }
            $output['aaData'][] = $row;
        }
        foreach ($footer_data as $key => $total) {
            $footer_data[$key]=number_format($total);
        }
        $output['sums']              = $footer_data;