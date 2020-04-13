    <?php

    defined('BASEPATH') or exit('No direct script access allowed');
    $custom_fields = get_table_custom_fields('supplier_quotes');
    $hasPermissionDelete = has_permission('RFQ', '', 'delete');

    $aColumns = [
        'tblsupplier_quotes.id',
        'tblsupplier_quotes.code',
        '1',
        'tblsuppliers.company',
        'tblsupplier_quotes.staff_create',
        'tblsupplier_quotes.date',
        'tblsupplier_quotes.status',
        '2',
        '3',
    ];
    $sIndexColumn = 'id';
    $sTable       = 'tblsupplier_quotes';
    $where        = [];
    $filter = [];
    $join             = [
        'LEFT JOIN tblsuppliers ON tblsuppliers.id=tblsupplier_quotes.suppliers_id',
    ];
    foreach ($custom_fields as $key => $field) {
    $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);
    array_push($customFieldsColumns, $selectAs);
    array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
    array_push($join, 'LEFT JOIN '.db_prefix().'customfieldsvalues as ctable_' . $key . ' ON tblsupplier_quotes.id = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $key . '.fieldid=' . $field['id']);
    }

    if ($this->ci->input->post('filterStatus')) {
        if(is_numeric($this->ci->input->post('filterStatus'))) {
            if($this->ci->input->post('filterStatus') == 1 || $this->ci->input->post('filterStatus') == 2) {
                array_push($where, 'AND tblsupplier_quotes.status = '.$this->ci->input->post('filterStatus'));
            } else {

            }
        }
    }
    if ($this->ci->input->post('search_code')) {
        if(is_numeric($this->ci->input->post('search_code'))) {
            array_push($where, 'AND tblsupplier_quotes.id = '.$this->ci->input->post('search_code'));
        }
    }
    if ($this->ci->input->post('search_staff')) {
        array_push($where, 'AND  tblsupplier_quotes.staff_create IN (' . implode(', ', $this->ci->input->post('search_staff')) . ')');
    }
    if ($this->ci->input->post('search_id_suppliers')) {
        array_push($where, 'AND tblsupplier_quotes.suppliers_id IN (' . implode(', ', $this->ci->input->post('search_id_suppliers')) . ')');
    }
    $search_date = $this->ci->input->post('search_date');
    if($search_date)
    {
        $data_start = explode(' - ', $search_date);
        array_push($where, 'AND tblsupplier_quotes.date_create BETWEEN "' . to_sql_date($data_start[0]) . '" and "' . to_sql_date($data_start[1]) . '"');
    }

    $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['
        tblsupplier_quotes.prefix,
        tblsupplier_quotes.id_purchases,
        tblsupplier_quotes.id_ask_price,
        tblsupplier_quotes.suppliers_id,
        tblsupplier_quotes.history_status,
        tblsupplier_quotes.not_new_by_staff,
        tblsupplier_quotes.type_plan as type_plan_supplier_quotes
    ']);
    $output  = $result['output'];
    $rResult = $result['rResult'];
    $j=0;
    foreach ($rResult as $aRow) {
        $j++;
        $row = [];
        $not_new_by_staff = explode(',',$aRow['not_new_by_staff']);
        if(!in_array(get_staff_user_id(), $not_new_by_staff) && $aRow['tblsupplier_quotes.status'] == 1) {
            $row[] = $j.' <span class="wap-new">new</span>';
            $row['DT_RowClass'] = 'alert-new';
        }
        else {
            $row[] = $j;
        }
        $purchase_order = get_table_where('tblpurchase_order',array('id_quotes'=>$aRow['tblsupplier_quotes.id']),'','row');
        if($aRow['type_plan_supplier_quotes'] == 1){
        $productions_capacity = '<span class="inline-block label label-success">'._l('ch_productions_capacity').'</span><br>';
        }else
        {
        $productions_capacity ='';   
        }
        $supplier_quotes  = $aRow['prefix'].'-'.$aRow['tblsupplier_quotes.code'].'<br>'.$productions_capacity;
        $supplier_quotes = '<a href="#" onclick="view_supplier_quotes('.$aRow['tblsupplier_quotes.id'].'); return false;" >' . $supplier_quotes . '</a>';
        $supplier_quotes .= '<div class="row-options">';
        $supplier_quotes .= '<a href="#" onclick="view_supplier_quotes('.$aRow['tblsupplier_quotes.id'].'); return false;" >' . _l('view') . '</a>';

        if(($aRow['tblsupplier_quotes.status'] == 1)&&($aRow['id_purchases'] != 0 || $aRow['id_ask_price'] != 0))
        {
        $supplier_quotes .= ' | <a href="'.admin_url('supplier_quotes/detail_v2/'.$aRow['tblsupplier_quotes.id']).'" >' . _l('edit') . '</a>';
        }elseif($aRow['tblsupplier_quotes.status'] == 1)
        {
        $supplier_quotes .= ' | <a href="'.admin_url('supplier_quotes/detail/'.$aRow['tblsupplier_quotes.id']).'" >' . _l('edit') . '</a>';    
        }
        if ($hasPermissionDelete) {
            $supplier_quotes .= ' | <a href="' . admin_url('supplier_quotes/delete/' . $aRow['tblsupplier_quotes.id']) . '" class="text-danger delete-remind">' . _l('delete') . '</a></div>';
        }    

        $row[] = $supplier_quotes;
        if($aRow['id_purchases'] != 0)
        {
             // $row[] = '';
            $purchases = get_table_where('tblpurchases',array('id'=>$aRow['id_purchases']),'','row');
            $row[] = '<a href="#" onclick="view_purchases('.$purchases->id.'); return false;" >'.$purchases->prefix.'-'.$purchases->code.'</a><br>'.format_status_suppler_quote(1);
        }elseif($aRow['id_ask_price'] != 0)
        {
            $ask_price = get_table_where('tblrfq_ask_price',array('id'=>$aRow['id_ask_price']),'','row');
            $row[] = $ask_price->prefix.'-'.$ask_price->code.'<br>'.format_status_suppler_quote(2);
        }else
        {
            $row[] = '';
        }
        $row[] = '<a href="#" onclick="int_suppliers_view('.$aRow['suppliers_id'].'); return false;">' . $aRow['tblsuppliers.company'] . '</a>';
        $staff = staff_profile_image($aRow['tblsupplier_quotes.staff_create'], array('staff-profile-image-small mright5'), 'small', array(
                            'data-toggle' => 'tooltip',
                            'data-title' => get_staff_full_name($aRow['tblsupplier_quotes.staff_create'])
                        )).get_staff_full_name($aRow['tblsupplier_quotes.staff_create']);
        $row[] = ($aRow['tblsupplier_quotes.staff_create'] ? $staff : '');


        $row[] = _d($aRow['tblsupplier_quotes.date']);
        
        if($aRow['tblsupplier_quotes.status']==1)
                {
                    $type='warning';
                    $status='Chưa duyệt';
                }
                else if($aRow['tblsupplier_quotes.status']==2)
                {
                    $type='success';
                    $status='Đã duyệt';
                }
            $status='<span class="inline-block label label-'.$type.'" task-status-table="'.$aRow['tblsupplier_quotes.status'].'">' . $status.'';
            if(has_permission('purchases', '', 'view') && has_permission('purchases', '', 'view_own'))
            {
                if($aRow['tblsupplier_quotes.status']!=2){
                    $status.='<a href="javacript:void(0)" data-loading-text="" onclick="var_status('.$aRow['tblsupplier_quotes.status'].','.$aRow['tblsupplier_quotes.id'].'); return false;">
                    <i class="fa fa-check task-icon task-unfinished-icon" data-toggle="tooltip" ></i>                    
                    ';
                }
                else
                {
                    $status.='<a href="javacript:void(0)">
                    <i class="fa fa-check task-icon task-finished-icon" data-toggle="tooltip"></i>';
                }
            }
            else {
                if($aRow['tblsupplier_quotes.status']==1) {
                    $status .= '<a href="javacript:void(0)" data-loading-text="" onclick="var_status(' . $aRow['tblsupplier_quotes.status'] . ',' . $aRow['tblsupplier_quotes.id'] . '); return false">
                    <i class="fa fa-check task-icon task-unfinished-icon" data-toggle="tooltip" ></i>';
                }
                else
                {
                    $status .= '<a href="javacript:void(0)">
                    <i class="fa fa-check task-icon task-finished-icon" data-toggle="tooltip"></i>';
                }
            }
                $status.='
                    </a>
                </span>';
        $_data='';
        $history_status = explode('|',$aRow['history_status']);
        foreach ($history_status as $key => $value) {
            $data = explode(',',$value);
            if(is_numeric($data[0])) {
                $_data.=staff_profile_image($data[0], array('staff-profile-image-small mright5'), 'small', array(
                            'data-toggle' => 'tooltip',
                            'data-title' => ' Vào lúc: '._dt($data[1])
                        )).get_staff_full_name($data[0]).'<br>';
            }
        }
        $row[] = $status.'<br>'.$_data;


        $noEvent = '';
        if(empty($purchase_order)) {
            // $data ='<a href="'.admin_url('purchase_order/create_detailquotes/'.$aRow['tblsupplier_quotes.id']).'" type="button"  class="btn btn-success">'._l('ch_add_purchase_order').'</a>';
            $data ='';
        } else {
            $data=purchase_order_quote($purchase_order->prefix.'-'.$purchase_order->code);    
        }
        // if($aRow['id_ask_price'] != 0) {
        //     $row[] = text_align('<a onclick="evaluate_modal('.$aRow['id_ask_price'].','.$aRow['suppliers_id'].'); return false;" type="button" class="btn btn-success">'._l('evaluate').'</a><br>'.$data,'center');
        // } else {
        //     $row[] = text_align($data,'center');
        // }
        $row[] = $data;


        $data_btn = '';
        $data_text = '';
        $_outputStatus = '<div class="dropdown H_drop">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">'._l('action').'
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu h_right">';
        if(empty($aRow['id_ask_price'])&&empty($aRow['id_purchases']))
        {
            if($aRow['tblsupplier_quotes.status'] == 2)
            {
            $_outputStatus .= '<li><a href="'.admin_url('purchase_order/create_detailquotes/'.$aRow['tblsupplier_quotes.id']).'">'._l('ch_add_purchase_order').'</a></li>';
            }  
        }
        if(empty($purchase_order)) {
            if($aRow['tblsupplier_quotes.status'] == 2)
            {
            $data_btn ='<a href="'.admin_url('purchase_order/create_detailquotes/'.$aRow['tblsupplier_quotes.id']).'" type="button"  class="btn btn-success">'._l('ch_add_purchase_order').'</a>';
            }
        } else {
            $data_text = purchase_order_quote($purchase_order->prefix.'-'.$purchase_order->code);    
        }

        if($aRow['id_ask_price'] != 0) {
            $id_ask_price = get_table_where('tblrfq_ask_price',array('id'=>$aRow['id_ask_price']),'','row');
            $id_purchase = get_table_where('tblpurchases',array('id'=>$id_ask_price->id_purchases),'','row');
                
            $_outputStatus .= '<li><a onclick="evaluate_modal('.$aRow['id_ask_price'].','.$aRow['suppliers_id'].'); return false;">'._l('evaluate').'</a></li>';
            if($data_btn != '') {
            if($id_purchase->status!=4&&$aRow['tblsupplier_quotes.status'] == 2)
                {
                $_outputStatus .= '<li><a href="'.admin_url('purchase_order/create_detailquotes/'.$aRow['tblsupplier_quotes.id']).'">'._l('ch_add_purchase_order').'</a></li>';
            }
            }
        } else {
            if($data_btn != '') {
                $id_purchase = get_table_where('tblpurchases',array('id'=>$aRow['id_purchases']),'','row');
                if(!empty($id_purchase)&&$id_purchase->status!=4&&$aRow['tblsupplier_quotes.status'] == 2)
                {
                $_outputStatus .= '<li><a href="'.admin_url('purchase_order/create_detailquotes/'.$aRow['tblsupplier_quotes.id']).'">'._l('ch_add_purchase_order').'</a></li>';
                }
            }
        }
        $_outputStatus .= '<li><a href="'.admin_url('supplier_quotes/print_pdf/'.$aRow['tblsupplier_quotes.id']).'" target="_blank">'._l('print_vote').'</a></li>';
        $_outputStatus .= '</ul></div>';
        $row[] = $_outputStatus;

        foreach ($customFieldsColumns as $customFieldColumn) {
            $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
        }

        // $row['DT_RowClass'] = 'has-row-options';
        
        $output['aaData'][] = $row;
    }
