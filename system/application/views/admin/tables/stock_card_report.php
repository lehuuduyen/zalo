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
        if($type_items == 'product')
            {
               $type_itemss = 'products';
            }
            if($type_items == 'nvl')
            {
               $type_itemss = 'materials';
            }
            if($type_items == 'tools')
            {
               $type_itemss = 'tools_supplies'; 
            }else
            {
                $type_itemss =$type_items;
            }
        //Nhập kho
        $selectimport = array(
            'tblimport.warehouseman_date as warehouseman_date',
            'tblimport.date as date',
            'concat(tblimport.prefix,"-",tblimport.code) as code',
            'tblimport.note as reason',
            '1',
            'tblimport_items.quantity_net as import_quantity',
            '0 as export_quantity',
            '1 as exists_quantity'
        );
        $whereimport= array(
            'AND tblimport.warehouse_id ='.$warehouse_id,
            'AND tblimport.warehouseman_id != 0',
        );
        if(!empty($type_items))
        {
            array_push($whereimport, 'AND tblimport_items.product_id =',$custom_item_select);   
            array_push($whereimport, 'AND tblimport_items.type = "'.$type_itemss.'"');
        }else{
            array_push($whereimport, 'AND tblimport_items.product_id = -1');
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
        $resultimport  = data_tables_init_nolimt($aColumnsimport, $sIndexColumnimport, $sTableimport, $joinimport, $whereimport, array('tblimport.id as id_main,tblimport_items.product_id as product_id,tblimport_items.type as type,tblimport_items.localtion_warehouses_id as localtion_id'));

        $outputimport  = $resultimport['output'];
        $rResultimport = $resultimport['rResult'];
        //Xuất kho
        $selectexport = array(
            'tbl_export_warehouses.date_warehouseman as warehouseman_date',
            'tbl_export_warehouses.date as date',
            'tbl_export_warehouses.reference_no as code',
            'tbl_export_warehouses.note as reason',
            '1',
            '0 as import_quantity',
            'tbl_export_warehous_items.quantity as export_quantity',
            '2 as exists_quantity'
        );
        $whereexport= array(
            'AND tbl_export_warehous_items.warehouse_id ='.$warehouse_id,
            'AND tbl_export_warehouses.warehouseman_id != 0',
        );
        if(!empty($type_items))
        {
            array_push($whereexport, 'AND tbl_export_warehous_items.item_id =',$custom_item_select);   
            
            array_push($whereexport, 'AND tbl_export_warehous_items.type_item = "'.$type_itemss.'"');
        }else{
            array_push($whereexport, 'AND tbl_export_warehous_items.item_id = -1');
        } 
        if(!empty($beginMonth)&&!empty($endMonth))
        {
            array_push($whereexport, 'AND tbl_export_warehouses.date >='.'"'.$beginMonth.' 00:00:00"');  
            array_push($whereexport, 'AND tbl_export_warehouses.date <='.'"'.$endMonth.' 23:59:59"');
        }
        $aColumnsexport     = $selectexport;
        $sIndexColumnexport = "id";
        $sTableexport       = 'tbl_export_warehous_items';
        $joinexport         = array(
            'LEFT JOIN tbl_export_warehouses ON tbl_export_warehouses.id = tbl_export_warehous_items.export_warehouse_id',
             
        );

        $order_byexport ='order by item_id asc';
        $resultexport   = data_tables_init_nolimt($aColumnsexport , $sIndexColumnexport , $sTableexport , $joinexport , $whereexport , array('tbl_export_warehouses.id as id_main,tbl_export_warehous_items.item_id as product_id,tbl_export_warehous_items.type_item as type,tbl_export_warehous_items.location_id as localtion_id'));

        $outputexport  = $resultexport['output'];
        $rResultexport = $resultexport['rResult'];
        //trả hàng NCC
        $select_return = array(
            'tblreturn_suppliers.data_warehouseman as warehouseman_date',
            'tblreturn_suppliers.date as date',
            'concat(tblreturn_suppliers.prefix,"",tblreturn_suppliers.code) as code',
            'tblreturn_suppliers.note as reason',
            '1',
            '0 as import_quantity',
            'tblreturn_suppliers_items.quantity_net as export_quantity',
            '3 as exists_quantity'
        );
        $where_return= array(
            'AND tblreturn_suppliers.warehouse_id ='.$warehouse_id,
            'AND tblreturn_suppliers.warehouseman_id != 0',
        );
        if(!empty($type_items))
        {
            array_push($where_return, 'AND tblreturn_suppliers_items.product_id =',$custom_item_select);   
            array_push($where_return, 'AND tblreturn_suppliers_items.type = "'.$type_items.'"');
        }else{
            array_push($where_return, 'AND tblreturn_suppliers_items.product_id = -1');
        }   
        if(!empty($beginMonth)&&!empty($endMonth))
        {
            array_push($where_return, 'AND tblreturn_suppliers.date >='.'"'.$beginMonth.' 00:00:00"');  
            array_push($where_return, 'AND tblreturn_suppliers.date <='.'"'.$endMonth.' 23:59:59"');
        }
        $aColumns_return     = $select_return;
        $sIndexColumn_return = "id";
        $sTable_return       = 'tblreturn_suppliers_items';
        $join_return         = array(
            'LEFT JOIN tblreturn_suppliers ON tblreturn_suppliers.id = tblreturn_suppliers_items.id_return',
             
        );

        $order_by_return ='order by product_id asc';
        $result_return   = data_tables_init_nolimt($aColumns_return , $sIndexColumn_return , $sTable_return , $join_return , $where_return , array('tblreturn_suppliers.id as id_main,tblreturn_suppliers_items.product_id as product_id,tblreturn_suppliers_items.type as type,tblreturn_suppliers_items.localtion_warehouses_id as localtion_id'));

        $output_return  = $result_return['output'];
        $rResult_return = $result_return['rResult'];

        //Điều chỉnh kho giảm
        $select_adjustedG = array(
            'tbladjusted.date_create as warehouseman_date',
            'tbladjusted.date as date',
            'concat(tbladjusted.prefix,"",tbladjusted.code) as code',
            'tbladjusted.note as reason',
            '1',
            '0 as import_quantity',
            'tbladjusted_items.quantity_net as export_quantity',
            '4 as exists_quantity'
        );
        $where_adjustedG= array(
            'AND tbladjusted.warehouse_id ='.$warehouse_id,
            'AND tbladjusted.type = 2',
        );
        if(!empty($type_items))
        {
            array_push($where_adjustedG, 'AND tbladjusted_items.product_id =',$custom_item_select);   
            array_push($where_adjustedG, 'AND tbladjusted_items.type = "'.$type_items.'"');
        }else{
            array_push($where_adjustedG, 'AND tbladjusted_items.product_id = -1');
        }     
        if(!empty($beginMonth)&&!empty($endMonth))
        {
            array_push($where_adjustedG, 'AND tbladjusted.date >='.'"'.$beginMonth.' 00:00:00"');  
            array_push($where_adjustedG, 'AND tbladjusted.date <='.'"'.$endMonth.' 23:59:59"');
        }
        $aColumns_adjustedG     = $select_adjustedG;
        $sIndexColumn_adjustedG = "id";
        $sTable_adjustedG       = 'tbladjusted_items';
        $join_adjustedG         = array(
            'LEFT JOIN tbladjusted ON tbladjusted.id = tbladjusted_items.id_adjusted',
             
        );

        $order_by_adjustedG ='order by product_id asc';
        $result_adjustedG   = data_tables_init_nolimt($aColumns_adjustedG , $sIndexColumn_adjustedG , $sTable_adjustedG , $join_adjustedG , $where_adjustedG , array('tbladjusted.id as id_main,tbladjusted_items.product_id as product_id,tbladjusted_items.type as type,tbladjusted_items.localtion as localtion_id'));

        $output_adjustedG  = $result_adjustedG['output'];
        $rResult_adjustedG = $result_adjustedG['rResult'];

        //Điều chỉnh kho tăng
        $select_adjustedT = array(
            'tbladjusted.date_create as warehouseman_date',
            'tbladjusted.date as date',
            'concat(tbladjusted.prefix,"",tbladjusted.code) as code',
            'tbladjusted.note as reason',
            '1',
            'tbladjusted_items.quantity_net as import_quantity',
            '0 as export_quantity',
            '5 as exists_quantity'
        );
        $where_adjustedT= array(
            'AND tbladjusted.warehouse_id ='.$warehouse_id,
            'AND tbladjusted.type = 1',
        );
        if(!empty($type_items))
        {
            array_push($where_adjustedT, 'AND tbladjusted_items.product_id =',$custom_item_select);   
            array_push($where_adjustedT, 'AND tbladjusted_items.type = "'.$type_items.'"');
        }else{
            array_push($where_adjustedT, 'AND tbladjusted_items.product_id = -1');
        }   
        if(!empty($beginMonth)&&!empty($endMonth))
        {
            array_push($where_adjustedT, 'AND tbladjusted.date >='.'"'.$beginMonth.' 00:00:00"');  
            array_push($where_adjustedT, 'AND tbladjusted.date <='.'"'.$endMonth.' 23:59:59"');
        }
        $aColumns_adjustedT     = $select_adjustedT;
        $sIndexColumn_adjustedT = "id";
        $sTable_adjustedT       = 'tbladjusted_items';
        $join_adjustedT         = array(
            'LEFT JOIN tbladjusted ON tbladjusted.id = tbladjusted_items.id_adjusted',
             
        );

        $order_by_adjustedT ='order by product_id asc';
        $result_adjustedT   = data_tables_init_nolimt($aColumns_adjustedT , $sIndexColumn_adjustedT , $sTable_adjustedT , $join_adjustedT , $where_adjustedT , array('tbladjusted.id as id_main,tbladjusted_items.product_id as product_id,tbladjusted_items.type as type,tbladjusted_items.localtion as localtion_id'));

        $output_adjustedT  = $result_adjustedT['output'];
        $rResult_adjustedT = $result_adjustedT['rResult'];

        //Chuyển kho: nhận
        $select_TranfersN = array(
            'tbltransfer_warehouse.warehouseman_date as warehouseman_date',
            'tbltransfer_warehouse.date as date',
            'concat(tbltransfer_warehouse.prefix,"-",tbltransfer_warehouse.code) as code',
            'tbltransfer_warehouse.note as reason',
            '1',
            'tbltransfer_warehouse_detail.quantity_net as import_quantity',
            '0 as export_quantity',
            '6 as exists_quantity'
        );
        $where_TranfersN= array(
            'AND tbltransfer_warehouse.warehouse_to ='.$warehouse_id,
            'AND tbltransfer_warehouse.warehouseman_id != 0',
        );
        if(!empty($type_items))
        {
            array_push($where_TranfersN, 'AND tbltransfer_warehouse_detail.id_items =',$custom_item_select);   
            array_push($where_TranfersN, 'AND tbltransfer_warehouse_detail.type = "'.$type_items.'"');
        }else{
            array_push($where_TranfersN, 'AND tbltransfer_warehouse_detail.id_items = -1');
        }   
        if(!empty($beginMonth)&&!empty($endMonth))
        {
            array_push($where_TranfersN, 'AND tbltransfer_warehouse.date >='.'"'.$beginMonth.' 00:00:00"');  
            array_push($where_TranfersN, 'AND tbltransfer_warehouse.date <='.'"'.$endMonth.' 23:59:59"');
        }
        $aColumns_TranfersN     = $select_TranfersN;
        $sIndexColumn_TranfersN = "id";
        $sTable_TranfersN       = 'tbltransfer_warehouse_detail';
        $join_TranfersN         = array(
            'LEFT JOIN tbltransfer_warehouse ON tbltransfer_warehouse.id = tbltransfer_warehouse_detail.id_transfer',
             
        );

        $order_by_TranfersN ='order by id_items asc';
        $result_TranfersN   = data_tables_init_nolimt($aColumns_TranfersN , $sIndexColumn_TranfersN , $sTable_TranfersN , $join_TranfersN , $where_TranfersN , array('tbltransfer_warehouse.id as id_main,tbltransfer_warehouse_detail.id_items as product_id,tbltransfer_warehouse_detail.type as type,tbltransfer_warehouse_detail.localtion_id as localtion_id'));

        $output_TranfersN  = $result_TranfersN['output'];
        $rResult_TranfersN = $result_TranfersN['rResult'];

        //Chuyển kho: di
        $select_TranfersD = array(
            'tbltransfer_warehouse.warehouseman_date as warehouseman_date',
            'tbltransfer_warehouse.date as date',
            'concat(tbltransfer_warehouse.prefix,"-",tbltransfer_warehouse.code) as code',
            'tbltransfer_warehouse.note as reason',
            '1',
            '0 as import_quantity',
            'tbltransfer_warehouse_detail.quantity_net as export_quantity',
            '7 as exists_quantity'
        );
        $where_TranfersD= array(
            'AND tbltransfer_warehouse.warehouse_id ='.$warehouse_id,
            'AND tbltransfer_warehouse.warehouseman_id != 0',
        );
        if(!empty($type_items))
        {
            array_push($where_TranfersD, 'AND tbltransfer_warehouse_detail.id_items =',$custom_item_select);   
            array_push($where_TranfersD, 'AND tbltransfer_warehouse_detail.type = "'.$type_items.'"');
        }else{
            array_push($where_TranfersD, 'AND tbltransfer_warehouse_detail.id_items = -1');
        }   
        if(!empty($beginMonth)&&!empty($endMonth))
        {
            array_push($where_TranfersD, 'AND tbltransfer_warehouse.date >='.'"'.$beginMonth.' 00:00:00"');  
            array_push($where_TranfersD, 'AND tbltransfer_warehouse.date <='.'"'.$endMonth.' 23:59:59"');
        }
        $aColumns_TranfersD     = $select_TranfersD;
        $sIndexColumn_TranfersD = "id";
        $sTable_TranfersD       = 'tbltransfer_warehouse_detail';
        $join_TranfersD         = array(
            'LEFT JOIN tbltransfer_warehouse ON tbltransfer_warehouse.id = tbltransfer_warehouse_detail.id_transfer',
             
        );

        $order_by_TranfersD ='order by id_items asc';
        $result_TranfersD   = data_tables_init_nolimt($aColumns_TranfersD , $sIndexColumn_TranfersD , $sTable_TranfersD , $join_TranfersD , $where_TranfersD , array('tbltransfer_warehouse.id as id_main,tbltransfer_warehouse_detail.id_items as product_id,tbltransfer_warehouse_detail.type as type,tbltransfer_warehouse_detail.localtion_id as localtion_id'));

        $output_TranfersD  = $result_TranfersD['output'];
        $rResult_TranfersD = $result_TranfersD['rResult'];   

        //Xuất kho sản xuất
        $select_exportsx = array(
            'tbl_suggest_exporting.date_warehouseman as warehouseman_date',
            'tbl_suggest_exporting.date as date',
            'tbl_suggest_exporting.reference_stock as code',
            'tbl_suggest_exporting.note as reason',
            '1',
            '0 as import_quantity',
            'tbl_suggest_exporting_items.quantity_exchange as export_quantity',
            '8 as exists_quantity'
        );
        $where_exportsx= array(
            'AND tbl_suggest_exporting.warehouse_id ='.$warehouse_id,
            // 'AND tbl_suggest_exporting_items.type_item = "materials"',
            'AND tbl_suggest_exporting.status_stock is not NULL',
            'AND tbl_suggest_exporting.warehouseman_id != 0',
        );
        if(!empty($type_items))
        {
            array_push($where_exportsx, 'AND tbl_suggest_exporting_items.item_id = '.$custom_item_select);   
            array_push($where_exportsx, 'AND tbl_suggest_exporting_items.type_item = "materials"');
        }else{
            array_push($where_exportsx, 'AND tbl_suggest_exporting_items.item_id = -1');
        }  
        if(!empty($beginMonth)&&!empty($endMonth))
        {
            array_push($where_exportsx, 'AND tbl_suggest_exporting.date >='.'"'.$beginMonth.' 00:00:00"');  
            array_push($where_exportsx, 'AND tbl_suggest_exporting.date <='.'"'.$endMonth.' 23:59:59"');
        }
        $aColumns_exportsx     = $select_exportsx;
        $sIndexColumn_exportsx = "id";
        $sTable_exportsx       = 'tbl_suggest_exporting_items';
        $join_exportsx         = array(
            'LEFT JOIN tbl_suggest_exporting ON tbl_suggest_exporting.id = tbl_suggest_exporting_items.suggest_exporting_id',
        );

        $order_by_exportsx ='order by item_id asc';
        $result_exportsx   = data_tables_init_nolimt($aColumns_exportsx , $sIndexColumn_exportsx , $sTable_exportsx , $join_exportsx , $where_exportsx , array('tbl_suggest_exporting.id as id_main,tbl_suggest_exporting_items.item_id as product_id,concat("nvl") as type,tbl_suggest_exporting_items.location_id as localtion_id'));

        $output_exportsx  = $result_exportsx['output'];
        $rResult_exportsx = $result_exportsx['rResult'];
//nhapkho thành phẩm
        $select_import_tp = array(
            'tbl_purchase_products.date_warehouseman as warehouseman_date',
            'tbl_purchase_products.date as date',
            'tbl_purchase_products.reference_no as code',
            'tbl_purchase_products.note as reason',
            '1',
            'tbl_purchase_product_items.quantity as import_quantity',
            '0 as export_quantity',
            '9 as exists_quantity'
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
        }else{
            array_push($where_import_tp, 'AND tbl_purchase_product_items.item_id = -1');
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
        $result_import_tp   = data_tables_init_nolimt($aColumns_import_tp , $sIndexColumn_import_tp , $sTable_import_tp , $join_import_tp , $where_import_tp , array('tbl_purchase_products.id as id_main,tbl_purchase_product_items.item_id as product_id,concat("tools") as type,tbl_purchase_product_items.location_id as localtion_id'));

        $output_import_tp  = $result_import_tp['output'];
        $rResult_import_tp = $result_import_tp['rResult'];

        //Xuất kho khác
        $select_different = array(
            'tblexport_different.warehouseman_date as warehouseman_date',
            'tblexport_different.date as date',
            'concat(tblexport_different.prefix,"-",tblexport_different.code) as code',
            'tblexport_different.note as reason',
            '1',
            '0 as import_quantity',
            'tbltblexport_different_items.quantity_net as export_quantity',
            '15 as exists_quantity'
        );
        $where_different= array(
            'AND tbltblexport_different_items.warehouses_id ='.$warehouse_id,
            'AND tblexport_different.warehouseman_id != 0',
        );
        if(!empty($type_items))
        {
            array_push($where_different, 'AND tbltblexport_different_items.product_id =',$custom_item_select);   
            array_push($where_different, 'AND tbltblexport_different_items.type = "'.$type_items.'"');
        }else{
            array_push($where_different, 'AND tbltblexport_different_items.product_id = -1');
        } 
        if(!empty($beginMonth)&&!empty($endMonth))
        {
            array_push($where_different, 'AND tblexport_different.date >='.'"'.$beginMonth.' 00:00:00"');  
            array_push($where_different, 'AND tblexport_different.date <='.'"'.$endMonth.' 23:59:59"');
        }
        $aColumns_different     = $select_different;
        $sIndexColumn_different = "id";
        $sTable_different       = 'tbltblexport_different_items';
        $join_different         = array(
            'LEFT JOIN tblexport_different ON tblexport_different.id = tbltblexport_different_items.id_export_different',
             
        );

        $order_by_different ='order by product_id asc';
        $result_different   = data_tables_init_nolimt($aColumns_different , $sIndexColumn_different , $sTable_different , $join_different , $where_different , array('tblexport_different.id as id_main,tbltblexport_different_items.product_id as product_id,tbltblexport_different_items.type as type,tbltblexport_different_items.localtion_warehouses_id as localtion_id'));

        $output_different  = $result_different['output'];
        $rResult_different = $result_different['rResult'];   
                //Nhập kho phe lieu
        $selecti_internal = array(
            'tbl_purchase_internal.date_warehouseman as warehouseman_date',
            'tbl_purchase_internal.date as date',
            'reference_no as code',
            'tbl_purchase_internal.note as reason',
            '1',
            'tbl_purchase_internal_items.quantity as import_quantity',
            '0 as export_quantity',
            '16 as exists_quantity'
        );
        $where_internal= array(
            'AND tbl_purchase_internal.warehouse_id ='.$warehouse_id,
            'AND tbl_purchase_internal.warehouseman_id != 0',
        );
        if(!empty($custom_item_select))
        {
            array_push($where_internal, 'AND tbl_purchase_internal_items.item_id =',$custom_item_select);   
        }else{
            array_push($where_internal, 'AND tbl_purchase_internal_items.item_id = -1');
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
        $result_internal  = data_tables_init_nolimt($aColumns_internal, $sIndexColumn_internal, $sTable_internal, $join_internal, $where_internal, array('tbl_materials.name','tbl_materials.code  as code_items,tbl_purchase_internal.id as id_main,tbl_materials.id as product_id,tbl_purchase_internal_items.location_id as localtion_id,concat("nvl") as type'));

        $output_internal  = $result_internal['output'];
        $rResult_internal = $result_internal['rResult'];
        $aColumnsG=array(
            'warehouseman_date',
            'date',
            'code',
            'reason',
            '1',
            'import_quantity',
            'export_quantity',
            'exists_quantity'
        );
        $rResultG = array();
        if(!empty($rResultimport))
        {
        $rResultG=array_merge($rResultG,$rResultimport);   
        }
        if(!empty($rResultexport))
        {
        $rResultG=array_merge($rResultG,$rResultexport);   
        }
        if(!empty($rResult_return))
        {
        $rResultG=array_merge($rResultG,$rResult_return);   
        }
        if(!empty($rResult_adjustedG))
        {
        $rResultG=array_merge($rResultG,$rResult_adjustedG);   
        }
        if(!empty($rResult_adjustedT))
        {
        $rResultG=array_merge($rResultG,$rResult_adjustedT);   
        }
        if(!empty($rResult_TranfersN))
        {
        $rResultG=array_merge($rResultG,$rResult_TranfersN);   
        }
        if(!empty($rResult_TranfersD))
        {
        $rResultG=array_merge($rResultG,$rResult_TranfersD);   
        }
        if(!empty($rResult_exportsx))
        {
        $rResultG=array_merge($rResultG,$rResult_exportsx);   
        }
        if(!empty($rResult_import_tp))
        {
        $rResultG=array_merge($rResultG,$rResult_import_tp);   
        }     
        if(!empty($rResult_different))
        {
        $rResultG=array_merge($rResultG,$rResult_different);   
        }  
        if(!empty($rResult_internal))
        {
        $rResultG=array_merge($rResultG,$rResult_internal);   
        }    
        if(!empty($rResultG))
        {
        usort($rResultG, ch_make_cmp(['product_id' => "desc",'localtion_id' => "desc",'warehouseman_date' => "asc",'type' => "desc",'exists_quantity'=> "asc"]));
        }
        $output=$outputimport;
        $output['iTotalRecords']=$outputimport['iTotalRecords']+$outputexport['iTotalRecords'] +$output_return['iTotalRecords'] + $output_adjustedG['iTotalRecords']  + $output_TranfersN['iTotalRecords']  + $output_adjustedT['iTotalRecords']   + $output_exportsx['iTotalRecords'] + $output_TranfersD['iTotalRecords'] + $output_import_tp['iTotalRecords'] + $output_different['iTotalRecords']+ $output_internal['iTotalRecords'];


        $output['iTotalDisplayRecords']=$outputimport['iTotalDisplayRecords'] + $outputexport['iTotalDisplayRecords'] + $output_return['iTotalDisplayRecords'] + $output_adjustedG['iTotalDisplayRecords'] + $output_adjustedT['iTotalDisplayRecords'] + $output_TranfersN['iTotalDisplayRecords']    + $output_TranfersD['iTotalDisplayRecords'] + $output_exportsx['iTotalDisplayRecords']+ $output_import_tp['iTotalDisplayRecords']+ $output_different['iTotalDisplayRecords']+ $output_internal['iTotalDisplayRecords'];
        $currentPage=$this->ci->input->post('start');
        $sumFExistsQ = 0;
        // $row= array();
            foreach ($rResultG as $key => $aRow) {
            if($key==0)
            {   
                $date=$aRow['product_id'];
                $type=$aRow['type'];
                $sumFExistsQall=getStartInventory($aRow['product_id'],$aRow['type'],$warehouse_id,$beginMonth);
                $sumExistsQall=sumExistsQ_all_ch_v1($aRow['product_id'],$rResultG,$key,$aRow['type'])+$sumFExistsQall;
                $get_items = get_items($aRow['product_id'],$aRow['type']);
                    $row=array(
                        $get_items->name.' ('.$get_items->code.') '.format_item_purchases($aRow['type']),
                        '',
                        '',
                        '',
                        '<div class="text-center">'.$get_items->unit_name.'</div>',
                        '',
                        '',
                        _l('inventory_all').': '.formatNumber($sumExistsQall)
                    );
                $row['DT_RowClass'] = 'alert-header bold warning';

                for ($i=0 ; $i<count($aColumnsG) ; $i++ ){
                    $row[]="";
                    }
                $output['aaData'][] = $row;
                $localtion=$aRow['localtion_id'];

                    $name =get_listname_localtion_warehouse($aRow['localtion_id']);
                    $sumFExistsQ_local=getStartInventory($aRow['product_id'],$aRow['type'],$warehouse_id,$beginMonth,$aRow['localtion_id']);
                    $sumExistsQ_local=sumExistsQ_location($aRow['localtion_id'],$aRow['product_id'],$rResultG,$key,$aRow['type'])+$sumFExistsQ_local;
                    $row=array(
                        _l('tnh_categories_capacity').': '.$name,
                        '',
                        '',
                        '',
                        '',
                        '',
                        _l('inventory_begin').': '.formatNumber($sumFExistsQ_local),
                        _l('inventory_end').': '.formatNumber($sumExistsQ_local)
                    );
                    if($aRow['localtion_id'] != $localtion)
                    {
                    $name =get_listname_localtion_warehouse($aRow['localtion_id']);
                    $sumFExistsQ_local=getStartInventory($aRow['product_id'],$aRow['type'],$warehouse_id,$beginMonth,$aRow['localtion_id']);
                    $sumExistsQ_local=sumExistsQ_location($aRow['localtion_id'],$aRow['product_id'],$rResultG,$key,$aRow['type'])+$sumFExistsQ_local;
                    $row=array(
                        _l('tnh_categories_capacity').': '.$name,
                        '',
                        '',
                        '',
                        '',
                        '',
                        _l('inventory_begin').': '.formatNumber($sumFExistsQ_local),
                        _l('inventory_end').': '.formatNumber($sumExistsQ_local)
                    );    
                    $localtion=$aRow['localtion_id'];
                    }
                    $row['DT_RowClass'] = 'alert-header bold danger';

                    for ($i=0 ; $i<count($aColumnsG) ; $i++ ){
                        $row[]="";
                        }
                    $output['aaData'][] = $row;
            }else
            {

                if($aRow['product_id'] != $date&&$aRow['type'] ==$type)
                {
                    $date=$aRow['product_id'];
                    $type=$aRow['type'];
                    $sumFExistsQall=getStartInventory($aRow['product_id'],$aRow['type'],$warehouse_id,$beginMonth);
                    $sumExistsQall=sumExistsQ_all_ch_v2($aRow['product_id'],$rResultG,$key,$aRow['type'])+$sumFExistsQall;
                    $get_items = get_items($aRow['product_id'],$aRow['type']);
                        $row=array(
                            
                            $get_items->name.' ('.$get_items->code.') '.format_item_purchases($aRow['type']),
                            '',
                            '',
                            '',
                            '<div class="text-center">'.$get_items->unit_name.'</div>',
                            '',
                            '',
                            _l('inventory_all').': '.formatNumber($sumExistsQall)
                        );
                    $row['DT_RowClass'] = 'alert-header bold warning';

                    for ($i=0 ; $i<count($aColumnsG) ; $i++ ){
                        $row[]="";
                        }
                    $output['aaData'][] = $row;

                    $localtion=$aRow['localtion_id'];

                    $name =get_listname_localtion_warehouse($aRow['localtion_id']);
                    $sumFExistsQ_local=getStartInventory($aRow['product_id'],$aRow['type'],$warehouse_id,$beginMonth,$aRow['localtion_id']);
                    $sumExistsQ_local=sumExistsQ_location($aRow['localtion_id'],$aRow['product_id'],$rResultG,$key,$aRow['type'])+$sumFExistsQ_local;
                    $row=array(
                        _l('tnh_categories_capacity').': '.$name,
                        '',
                        '',
                        '',
                        '',
                        '',
                        _l('inventory_begin').': '.formatNumber($sumFExistsQ_local),
                        _l('inventory_end').': '.formatNumber($sumExistsQ_local)
                    );
                    if($aRow['localtion_id'] != $localtion)
                    {
                    $name =get_listname_localtion_warehouse($aRow['localtion_id']);
                    $sumFExistsQ_local=getStartInventory($aRow['product_id'],$aRow['type'],$warehouse_id,$beginMonth,$aRow['localtion_id']);
                    $sumExistsQ_local=sumExistsQ_location($aRow['localtion_id'],$aRow['product_id'],$rResultG,$key,$aRow['type'])+$sumFExistsQ_local;
                    $row=array(
                        _l('tnh_categories_capacity').': '.$name,
                        '',
                        '',
                        '',
                        '',
                        '',
                        _l('inventory_begin').': '.formatNumber($sumFExistsQ_local),
                        _l('inventory_end').': '.formatNumber($sumExistsQ_local)
                    );    
                    $localtion=$aRow['localtion_id'];
                    }
                    $row['DT_RowClass'] = 'alert-header bold danger';
                    for ($i=0 ; $i<count($aColumnsG) ; $i++ ){
                        $row[]="";
                    }
                    $output['aaData'][] = $row;
                }elseif($aRow['product_id'] != $date&&$aRow['type'] !=$type)
                {
                    $date=$aRow['product_id'];
                    $type=$aRow['type'];
                    $sumFExistsQall=getStartInventory($aRow['product_id'],$aRow['type'],$warehouse_id,$beginMonth);
                    $sumExistsQall=sumExistsQ_all_ch_v2($aRow['product_id'],$rResultG,$key,$aRow['type'])+$sumFExistsQall;
                    $get_items = get_items($aRow['product_id'],$aRow['type']);
                        $row=array(
                            
                            $get_items->name.' ('.$get_items->code.') '.format_item_purchases($aRow['type']),
                            '',
                            '',
                            '',
                            '<div class="text-center">'.$get_items->unit_name.'</div>',
                            '',
                            '',
                            _l('inventory_all').': '.formatNumber($sumExistsQall)
                        );
                    $row['DT_RowClass'] = 'alert-header bold warning';

                    for ($i=0 ; $i<count($aColumnsG) ; $i++ ){
                        $row[]="";
                        }
                    $output['aaData'][] = $row;

                    $localtion=$aRow['localtion_id'];

                    $name =get_listname_localtion_warehouse($aRow['localtion_id']);
                    $sumFExistsQ_local=getStartInventory($aRow['product_id'],$aRow['type'],$warehouse_id,$beginMonth,$aRow['localtion_id']);
                    $sumExistsQ_local=sumExistsQ_location($aRow['localtion_id'],$aRow['product_id'],$rResultG,$key,$aRow['type'])+$sumFExistsQ_local;
                    $row=array(
                        _l('tnh_categories_capacity').': '.$name,
                        '',
                        '',
                        '',
                        '',
                        '',
                        _l('inventory_begin').': '.formatNumber($sumFExistsQ_local),
                        _l('inventory_end').': '.formatNumber($sumExistsQ_local)
                    );
                    if($aRow['localtion_id'] != $localtion)
                    {
                    $name =get_listname_localtion_warehouse($aRow['localtion_id']);
                    $sumFExistsQ_local=getStartInventory($aRow['product_id'],$aRow['type'],$warehouse_id,$beginMonth,$aRow['localtion_id']);
                    $sumExistsQ_local=sumExistsQ_location($aRow['localtion_id'],$aRow['product_id'],$rResultG,$key,$aRow['type'])+$sumFExistsQ_local;
                    $row=array(
                        _l('tnh_categories_capacity').': '.$name,
                        '',
                        '',
                        '',
                        '',
                        '',
                        _l('inventory_begin').': '.formatNumber($sumFExistsQ_local),
                        _l('inventory_end').': '.formatNumber($sumExistsQ_local)
                    );    
                    $localtion=$aRow['localtion_id'];
                    }
                    $row['DT_RowClass'] = 'alert-header bold danger';
                    for ($i=0 ; $i<count($aColumnsG) ; $i++ ){
                        $row[]="";
                    }
                    $output['aaData'][] = $row;
                }else
                {
                    if($aRow['localtion_id'] != $localtion)
                    {
                    $name =get_listname_localtion_warehouse($aRow['localtion_id']);
                    $sumFExistsQ_local=getStartInventory($aRow['product_id'],$aRow['type'],$warehouse_id,$beginMonth,$aRow['localtion_id']);
                    $sumExistsQ_local=sumExistsQ_location($aRow['localtion_id'],$aRow['product_id'],$rResultG,$key,$aRow['type'])+$sumFExistsQ_local;
                    $row=array(
                        _l('tnh_categories_capacity').': '.$name,
                        '',
                        '',
                        '',
                        '',
                        '',
                        _l('inventory_begin').': '.formatNumber($sumFExistsQ_local),
                        _l('inventory_end').': '.formatNumber($sumExistsQ_local)
                    );    
                    $localtion=$aRow['localtion_id'];
                    $row['DT_RowClass'] = 'alert-header bold danger';

                    for ($i=0 ; $i<count($aColumnsG) ; $i++ ){
                        $row[]="";
                        }
                    $output['aaData'][] = $row;
                    }
                }
            }
            $row = [];
            for ($i = 0 ; $i < count($aColumnsG) ; $i++) {
                if(strpos($aColumnsG[$i],'as') !== false && !isset($aRow[ $aColumnsG[$i] ])){
                    $_data = $aRow[ strafter($aColumnsG[$i],'as ')];
                } else {
                    $_data = $aRow[ $aColumnsG[$i] ];
                }
                if($aColumnsG[$i]=='date')
                {
                    $_data = '<div class="text-center">'._dhau($aRow['date']).'<div>';
                }
                if($aColumnsG[$i]=='warehouseman_date')
                {
                    $_data = '<div class="text-center">'._d($aRow['warehouseman_date']).'<div>';
                }
                if($aColumnsG[$i]=='import_quantity')
                {
                    $_data = '<div class="text-center">'.formatNumber($aRow['import_quantity']).'<div>';
                }
                if($aColumnsG[$i]=='export_quantity')
                {
                    $_data = '<div class="text-center">'.formatNumber($aRow['export_quantity']).'<div>';
                }
                if($aColumnsG[$i]=='1')
                {
                    $_data = '';
                }
                if($aColumnsG[$i]=='exists_quantity')
                {
                    $sumFExistsQ_local+= $aRow['import_quantity'] - $aRow['export_quantity'];
                    $_data = '<div class="text-center">'.formatNumber($sumFExistsQ_local).'<div>';
                }
                if($aColumnsG[$i]=='code')
                {
                    if($aRow['exists_quantity'] == 1)
                    {
                     $_data = '<a href="#" onclick="view_import('.$aRow['id_main'].'); return false;" >' . $_data . '</a>  <span class="inline-block label label-warning">'._l('ch_importss').'</span>';   
                    }elseif($aRow['exists_quantity'] == 2)
                    {
                     $_data = '<a data-tnh="modal" class="tnh-modal" href="'.admin_url('releases/view_export_warehouse/'.$aRow['id_main']).'" data-toggle="modal" data-target="#myModal">' . $_data . '</a>  <span class="inline-block label label-info">'._l('tnh_export_warehouse_sales').'</span>';   
                    }elseif($aRow['exists_quantity'] == 3)
                    {
                     $_data = '<a href="#" onclick="view_return_suppliers('.$aRow['id_main'].'); return false;" >' . $_data . '</a>  <span class="inline-block label label-info">'._l('ch_return_ncc').'</span>';   
                    }elseif($aRow['exists_quantity'] == 4)
                    {
                     $_data = '<a href="#" onclick="view_adjusted('.$aRow['id_main'].'); return false;" >' . $_data . '</a>  <span class="inline-block label label-info">'._l('ch_adjustedG').'</span>';   
                    }elseif($aRow['exists_quantity'] == 5)
                    {
                     $_data = '<a href="#" onclick="view_adjusted('.$aRow['id_main'].'); return false;" >' . $_data . '</a>  <span class="inline-block label label-info">'._l('ch_adjustedT').'</span>';   
                    }elseif($aRow['exists_quantity'] == 6)
                    {
                     $_data = '<a href="#" onclick="view_transfer('.$aRow['id_main'].'); return false;" >' . $_data . '</a>  <span class="inline-block label label-info">'._l('ch_transfer_N').'</span>';   
                    }elseif($aRow['exists_quantity'] == 7)
                    {
                     $_data = '<a href="#" onclick="view_transfer('.$aRow['id_main'].'); return false;" >' . $_data . '</a>  <span class="inline-block label label-info">'._l('ch_transfer_D').'</span>';   
                    }elseif($aRow['exists_quantity'] == 8)
                    {
                     $_data = '<a class="tnh-modal" title="Xem" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="'.admin_url('stock/view_exporting_production/'.$aRow['id_main']).'">' . $_data . '</a>  <span class="inline-block label label-success">'._l('tnh_exporting_stock_producion').'</span>';    
                    }elseif($aRow['exists_quantity'] == 9)
                    {
                     $_data = '<a data-tnh="modal" class="tnh-modal" href="'.admin_url('stock/view_purchase_product/'.$aRow['id_main']).'" data-toggle="modal" data-target="#myModal">' . $_data . '</a>  <span class="inline-block label label-success">'._l('purchase_products').'</span>';    
                    }elseif($aRow['exists_quantity'] == 15)
                    {
                     $_data = '<a href="#" onclick="view_export_different('.$aRow['id_main'].'); return false;" >' . $_data . '</a>  <span class="inline-block label label-info">'._l('ch_export_different').'</span>';      
                    }elseif($aRow['exists_quantity'] == 16){
                     $_data = '<a class="tnh-modal" title="'._l('purchase_internal').'" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="'.admin_url('stock/view_purchase_internal/'.$aRow['id_main']).'">' . $_data . '</a>  <span class="inline-block label label-info">'._l('purchase_internal').'</span>';    
                    }
                    
                }
                $row[] = $_data;
            }
            $output['aaData'][] = $row;
        }