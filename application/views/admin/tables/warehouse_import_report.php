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
        //Nhập kho
        $selectimport = array(
            'tblimport.date as date',
            'concat(tblimport.prefix,"-",tblimport.code) as code',
            '2',
            'tblimport_items.localtion_warehouses_id as localtion_id',
            'tblimport_items.quantity as quantity',
            'tblimport_items.quantity_net as quantity_net'
        );
        $whereimport= array(
            'AND tblimport.warehouse_id ='.$warehouse_id,
            'AND tblimport.warehouseman_id != 0',
        );
        if(!empty($type_items))
        {
            array_push($whereimport, 'AND tblimport_items.product_id =',$custom_item_select);   
            array_push($whereimport, 'AND tblimport_items.type = "'.$type_items.'"');
        }    
        if(!empty($beginMonth)&&!empty($endMonth))
        {
            array_push($whereimport, 'AND tblimport.date >='.'"'.$beginMonth.' 00:00:00"');  
            array_push($whereimport, 'AND tblimport.date <='.'"'.$endMonth.' 23:59:59"');
        }
        $aColumnsimport     = $selectimport;
        $sIndexColumnimport = "id";
        $sTableimport       = 'tblimport_items';
        $joinimport         = array(
            'LEFT JOIN tblimport ON tblimport.id = tblimport_items.id_import',
             
        );

        $order_byimport='order by product_id asc';
        $resultimport  = data_tables_init($aColumnsimport, $sIndexColumnimport, $sTableimport, $joinimport, $whereimport, array('tblimport.id as id_main,tblimport_items.product_id as product_id,tblimport_items.type as type,1 as exists_quantity'));

        $outputimport  = $resultimport['output'];
        $rResultimport = $resultimport['rResult'];

        //Điều chỉnh kho tăng
        // $select_adjustedT = array(
        //     'tbladjusted.date as date',
        //     'concat(tbladjusted.prefix,"",tbladjusted.code) as code',
        //     '2',
        //     'tbladjusted_items.localtion as localtion_id',
        //     'tbladjusted_items.quantity as quantity',
        //     'tbladjusted_items.quantity_net as quantity_net'
        // );
        // $where_adjustedT= array(
        //     'AND tbladjusted.warehouse_id ='.$warehouse_id,
        //     'AND tbladjusted.type = 1',
        // );
        // if(!empty($type_items))
        // {
        //     array_push($where_adjustedT, 'AND tbladjusted_items.product_id =',$custom_item_select);   
        //     array_push($where_adjustedT, 'AND tbladjusted_items.type = "'.$type_items.'"');
        // }  
        // if(!empty($beginMonth)&&!empty($endMonth))
        // {
        //     array_push($where_adjustedT, 'AND tbladjusted.date >='.'"'.$beginMonth.' 00:00:00"');  
        //     array_push($where_adjustedT, 'AND tbladjusted.date <='.'"'.$endMonth.' 23:59:59"');
        // }
        // $aColumns_adjustedT     = $select_adjustedT;
        // $sIndexColumn_adjustedT = "id";
        // $sTable_adjustedT       = 'tbladjusted_items';
        // $join_adjustedT         = array(
        //     'LEFT JOIN tbladjusted ON tbladjusted.id = tbladjusted_items.id_adjusted',
             
        // );

        // $order_by_adjustedT ='order by product_id asc';
        // $result_adjustedT   = data_tables_init($aColumns_adjustedT , $sIndexColumn_adjustedT , $sTable_adjustedT , $join_adjustedT , $where_adjustedT , array('tbladjusted.id as id_main,tbladjusted_items.product_id as product_id,tbladjusted_items.type as type,5 as exists_quantity'));

        // $output_adjustedT  = $result_adjustedT['output'];
        // $rResult_adjustedT = $result_adjustedT['rResult'];

        // //Chuyển kho: nhận
        // $select_TranfersN = array(
        //     'tbltransfer_warehouse.date as date',
        //     'concat(tbltransfer_warehouse.prefix,"",tbltransfer_warehouse.code) as code',
        //     '2',
        //     'tbltransfer_warehouse_detail.localtion_id as localtion_id',
        //     'tbltransfer_warehouse_detail.quantity as quantity',
        //     'tbltransfer_warehouse_detail.quantity_net as quantity_net'
        // );
        // $where_TranfersN= array(
        //     'AND tbltransfer_warehouse.warehouse_to ='.$warehouse_id,
        //     'AND tbltransfer_warehouse.warehouseman_id != 0',
        // );
        // if(!empty($type_items))
        // {
        //     array_push($where_TranfersN, 'AND tbltransfer_warehouse_detail.id_items =',$custom_item_select);   
        //     array_push($where_TranfersN, 'AND tbltransfer_warehouse_detail.type = "'.$type_items.'"');
        // } 
        // if(!empty($beginMonth)&&!empty($endMonth))
        // {
        //     array_push($where_TranfersN, 'AND tbltransfer_warehouse.date >='.'"'.$beginMonth.' 00:00:00"');  
        //     array_push($where_TranfersN, 'AND tbltransfer_warehouse.date <='.'"'.$endMonth.' 23:59:59"');
        // }
        // $aColumns_TranfersN     = $select_TranfersN;
        // $sIndexColumn_TranfersN = "id";
        // $sTable_TranfersN       = 'tbltransfer_warehouse_detail';
        // $join_TranfersN         = array(
        //     'LEFT JOIN tbltransfer_warehouse ON tbltransfer_warehouse.id = tbltransfer_warehouse_detail.id_transfer',
             
        // );

        // $order_by_TranfersN ='order by id_items asc';
        // $result_TranfersN   = data_tables_init($aColumns_TranfersN , $sIndexColumn_TranfersN , $sTable_TranfersN , $join_TranfersN , $where_TranfersN , array('tbltransfer_warehouse.id as id_main,tbltransfer_warehouse_detail.id_items as product_id,tbltransfer_warehouse_detail.type as type,6 as exists_quantity'));

        // $output_TranfersN  = $result_TranfersN['output'];
        // $rResult_TranfersN = $result_TranfersN['rResult'];
//nhapkho thành phẩm
        $select_import_tp = array(
            'tbl_purchase_products.date as date',
            'tbl_purchase_products.reference_no as code',
            '2',
            'tbl_purchase_product_items.location_id as localtion_id',
            'tbl_purchase_product_items.quantity as quantity',
            'tbl_purchase_product_items.quantity as quantity_net'
        );
        $where_import_tp= array(
            'AND tbl_purchase_products.warehouse_id ='.$warehouse_id,
            // 'AND tbl_purchase_product_items.type_item = "products"',
            'AND tbl_purchase_products.warehouseman_id != 0',
        );
        if(!empty($type_items)&&$type_items=='tools')
        {
            array_push($where_import_tp, 'AND tbl_purchase_product_items.item_id =',$custom_item_select);   
        }elseif(!empty($type_items)&&($type_items!='tools'))
        {
            array_push($where_import_tp, 'AND tbl_purchase_product_items.item_id =',0);
        }
        if(!empty($beginMonth)&&!empty($endMonth))
        {
            array_push($where_import_tp, 'AND tbl_purchase_products.date >='.'"'.$beginMonth.' 00:00:00"');  
            array_push($where_import_tp, 'AND tbl_purchase_products.date <='.'"'.$endMonth.' 23:59:59"');
        }
        $aColumns_import_tp     = $select_import_tp;
        $sIndexColumn_import_tp = "id";
        $sTable_import_tp       = 'tbl_purchase_product_items';
        $join_import_tp         = array(
            'LEFT JOIN tbl_purchase_products ON tbl_purchase_products.id = tbl_purchase_product_items.purchase_product_id',
        );

        $order_by_import_tp ='order by item_id asc';
        $result_import_tp   = data_tables_init($aColumns_import_tp , $sIndexColumn_import_tp , $sTable_import_tp , $join_import_tp , $where_import_tp , array('tbl_purchase_products.id as id_main,tbl_purchase_product_items.item_id as product_id,concat("tools") as type,9 as exists_quantity'));

        $output_import_tp  = $result_import_tp['output'];
        $rResult_import_tp = $result_import_tp['rResult'];
                 //Nhập kho phe lieu
        $selecti_internal = array(
            'tbl_purchase_internal.date as date',
            'reference_no as code',
            '2',
            'tbl_purchase_internal_items.location_id as localtion_id',
            'tbl_purchase_internal_items.quantity as quantity',
            'tbl_purchase_internal_items.quantity as quantity_net',
        );
        $where_internal= array(
            'AND tbl_purchase_internal.warehouse_id ='.$warehouse_id,
            'AND tbl_purchase_internal.warehouseman_id != 0',
        );
        if(!empty($custom_item_select))
        {
            array_push($where_internal, 'AND tbl_purchase_internal_items.item_id =',$custom_item_select);   
        } 
        if(!empty($beginMonth)&&!empty($endMonth))
        {
            array_push($where_internal, 'AND tbl_purchase_internal.date >='.'"'.$beginMonth.' 00:00:00"');  
            array_push($where_internal, 'AND tbl_purchase_internal.date <='.'"'.$endMonth.' 23:59:59"');
        }
        $aColumns_internal     = $selecti_internal;
        $sIndexColumn_internal = "id";
        $sTable_internal       = 'tbl_purchase_internal_items';
        $join_internal         = array(
            'LEFT JOIN tbl_materials ON tbl_materials.id = tbl_purchase_internal_items.item_id',
            'LEFT JOIN tbl_purchase_internal ON tbl_purchase_internal.id = tbl_purchase_internal_items.purchase_internal_id',
        );

        $order_by_internal='order by item_id asc';
        $result_internal  = data_tables_init_nolimt($aColumns_internal, $sIndexColumn_internal, $sTable_internal, $join_internal, $where_internal, array('tbl_materials.name','tbl_materials.code  as code_items,tbl_purchase_internal.id as id_main,tbl_materials.id as product_id,tbl_purchase_internal_items.location_id as localtion_id,concat("nvl") as type,16 as exists_quantity'));

        $output_internal  = $result_internal['output'];
        $rResult_internal = $result_internal['rResult'];
        $aColumnsG=array(
            'date',
            'code',
            '2',
            'localtion_id',
            'quantity',
            'quantity_net'
        );
        $rResultG = array();
        if(!empty($rResultimport))
        {
        $rResultG=array_merge($rResultG,$rResultimport);   
        }
        if(!empty($rResult_internal))
        {
        $rResultG=array_merge($rResultG,$rResult_internal);   
        }
        if(!empty($rResult_adjustedT))
        {
        $rResultG=array_merge($rResultG,$rResult_adjustedT);   
        }
        if(!empty($rResult_TranfersN))
        {
        $rResultG=array_merge($rResultG,$rResult_TranfersN);   
        }
        if(!empty($rResult_import_tp))
        {
        $rResultG=array_merge($rResultG,$rResult_import_tp);   
        }        
        if(!empty($rResultG))
        {
        usort($rResultG, ch_make_cmp(['date' => "asc"]));
        }
        $output=$outputimport;
        // $output['iTotalRecords']=$outputimport['iTotalRecords']+ $output_TranfersN['iTotalRecords']  + $output_adjustedT['iTotalRecords'] + $output_import_tp['iTotalRecords'];
        // $output['iTotalDisplayRecords']=$outputimport['iTotalDisplayRecords'] + $output_adjustedT['iTotalDisplayRecords'] + $output_TranfersN['iTotalDisplayRecords'] + $output_import_tp['iTotalDisplayRecords'];
        $output['iTotalRecords']=$outputimport['iTotalRecords']+ $output_import_tp['iTotalRecords'];
        $output['iTotalDisplayRecords']=$outputimport['iTotalDisplayRecords']+ $output_import_tp['iTotalDisplayRecords'];
        $currentPage=$this->ci->input->post('start');
        $sumFExistsQ = 0;
            foreach ($rResultG as $key => $aRow) {
            $row = [];
            $get_items = get_items($aRow['product_id'],$aRow['type']);
            for ($i = 0 ; $i < count($aColumnsG) ; $i++) {
                if(strpos($aColumnsG[$i],'as') !== false && !isset($aRow[ $aColumnsG[$i] ])){
                    $_data = $aRow[ strafter($aColumnsG[$i],'as ')];
                } else {
                    $_data = $aRow[ $aColumnsG[$i] ];
                }
                if($aColumnsG[$i]=='2')
                {
                    $_data = $get_items->name.' ('.$get_items->code.') '.format_item_purchases($aRow['type']);
                }
                if($aColumnsG[$i]=='date')
                {
                    $_data = '<div class="text-center">'._dhau($aRow['date']).'<div>';
                }
                if($aColumnsG[$i]=='warehouseman_date')
                {
                    $_data = '<div class="text-center">'._d($aRow['warehouseman_date']).'<div>';
                }
                if($aColumnsG[$i]=='localtion_id')
                {
                    $_data = '<div class="text-center">'.get_listname_localtion_warehouse($aRow['localtion_id']).'<div>';
                }
                if($aColumnsG[$i]=='quantity')
                {
                    $_data = '<div class="text-center">'.formatNumber($aRow['quantity']).'<div>';
                }
                if($aColumnsG[$i]=='quantity_net')
                {
                    $_data = '<div class="text-center">'.formatNumber($aRow['quantity_net']).'<div>';
                }
                if($aColumnsG[$i]=='code')
                {
                    if($aRow['exists_quantity'] == 1)
                    {
                     $_data = '<a href="#" onclick="view_import('.$aRow['id_main'].'); return false;" >' . $_data . '</a>  <span class="inline-block label label-warning">'._l('ch_importss').'</span>';   
                    }elseif($aRow['exists_quantity'] == 5)
                    {
                     $_data = '<a href="#" onclick="view_adjusted('.$aRow['id_main'].'); return false;" >' . $_data . '</a>  <span class="inline-block label label-info">'._l('ch_adjustedT').'</span>';   
                    }elseif($aRow['exists_quantity'] == 6)
                    {
                     $_data = '<a href="#" onclick="view_transfer('.$aRow['id_main'].'); return false;" >' . $_data . '</a>  <span class="inline-block label label-info">'._l('ch_transfer_N').'</span>';   
                    }elseif($aRow['exists_quantity'] == 9)
                    {
                     $_data = '<a data-tnh="modal" class="tnh-modal" href="'.admin_url('stock/view_purchase_product/'.$aRow['id_main']).'" data-toggle="modal" data-target="#myModal">' . $_data . '</a>  <span class="inline-block label label-success">'._l('purchase_products').'</span>';    
                    }elseif($aRow['exists_quantity'] == 16){
                     $_data = '<a class="tnh-modal" title="'._l('purchase_internal').'" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="'.admin_url('stock/view_purchase_internal/'.$aRow['id_main']).'">' . $_data . '</a>  <span class="inline-block label label-info">'._l('purchase_internal').'</span>';    
                    }
                    
                }
                $row[] = $_data;
            }
            $output['aaData'][] = $row;
        }