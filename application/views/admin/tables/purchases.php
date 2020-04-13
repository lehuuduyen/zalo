    <?php

    defined('BASEPATH') or exit('No direct script access allowed');

    $hasPermissionDelete = has_permission('purchases', '', 'delete');

    $custom_fields = get_table_custom_fields('purchases');
    $this->ci->db->query("SET sql_mode = ''");

    $aColumns = [
        'tblpurchases.id',
        'tblpurchases.code',
        'tbl_productions_capacity.reference_no',
        'tblpurchases.name_purchase',
        'tblpurchases.staff_create',
        'tblpurchases.date',
        'tblpurchases.status',
        'tblpurchases.history_status',
        'tblpurchases.type',
    ];
    $sIndexColumn = 'id';
    $sTable       = 'tblpurchases';
    $where        = [];
    $filter = [];

    $join =  ['LEFT JOIN tbl_productions_capacity on tbl_productions_capacity.purchases_id = tblpurchases.id'];
    foreach ($custom_fields as $key => $field) {
        $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);
        array_push($customFieldsColumns, $selectAs);
        array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
        array_push($join, 'LEFT JOIN '.db_prefix().'customfieldsvalues as ctable_' . $key . ' ON tblpurchases.id = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $key . '.fieldid=' . $field['id']);
    }
    $join = hooks()->apply_filters('purchases_table_sql_join', $join);

    if ($this->ci->input->post('filterStatus')) {
        if(is_numeric($this->ci->input->post('filterStatus'))) {
            if($this->ci->input->post('filterStatus') == 1 || $this->ci->input->post('filterStatus') == 2 || $this->ci->input->post('filterStatus') == 3 || $this->ci->input->post('filterStatus') == 4) {
                array_push($where, 'AND tblpurchases.status = '.$this->ci->input->post('filterStatus'));
            } else if($this->ci->input->post('filterStatus') == 5) {
                array_push($where, 'AND tbl_productions_capacity.reference_no is not NULL');
            }
        }
    }
    if ($this->ci->input->post('search_code')) {
        if(is_numeric($this->ci->input->post('search_code'))) {
            array_push($where, 'AND tblpurchases.id = '.$this->ci->input->post('search_code'));
        }
    }
    if ($this->ci->input->post('search_staff')) {
        array_push($where, 'AND  tblpurchases.staff_create IN (' . implode(', ', $this->ci->input->post('search_staff')) . ')');
    }
    $search_date = $this->ci->input->post('search_date');
    if($search_date)
    {
        $data_start = explode(' - ', $search_date);
        array_push($where, 'AND tblpurchases.date_create BETWEEN "' . to_sql_date($data_start[0]) . '" and "' . to_sql_date($data_start[1]) . '"');
    }

    $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
     'tblpurchases.prefix',
     'tblpurchases.process',
     'tblpurchases.note_cancel',
     'tblpurchases.not_new_by_staff',
     'tblpurchases.id_order',
    ]);
    $output  = $result['output'];
    $rResult = $result['rResult'];
    $j=0;
    foreach ($rResult as $aRow) {
        $j++;
        $row = [];
        $not_new_by_staff = explode(',',$aRow['not_new_by_staff']);
        // if(empty($aRow['process'])&&($aRow['tblpurchases.status'] == 3)&&(empty($aRow['id_order'])))
        // {
        // $row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['tblpurchases.id'] . '"><label></label></div>';
        // }else
        // {
        // $processas = explode(',', $aRow['process']);
        //     if($processas[0] == 3&&($aRow['tblpurchases.status'] == 3&&(empty($aRow['id_order']))))
        //     {
        //     $purchase = get_items_purchase_new($aRow['tblpurchases.id']);
        //     if($purchase > 0){
        //     $row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['tblpurchases.id'] . '"><label></label></div>';
        //     }else
        //     {
        //     $row[] ='';    
        //     }
        //     }else
        //     {
        //     $row[] ='';
        //     }    
        // }
        if(!in_array(get_staff_user_id(), $not_new_by_staff) && $aRow['tblpurchases.status'] == 1) {
            $row[] = $j.' <span class="wap-new">new</span>';
            $row['DT_RowClass'] = 'alert-new';
        }
        else {
            $row[] = $j;
        }

        $purchases  = $aRow['prefix'].$aRow['tblpurchases.code'];
        $isPerson = false;
        $purchases = '<a href="#" onclick="view_purchases('.$aRow['tblpurchases.id'].'); return false;" >' . $purchases . '</a>';
        $purchases .= '<div class="row-options">';
        $purchases .= '<a href="#" onclick="view_purchases('.$aRow['tblpurchases.id'].'); return false;" >' . _l('view') . '</a>';

        if($aRow['tblpurchases.status'] < 3)
        {
            $purchases .= ' | <a href="' . admin_url('purchases/detail/' . $aRow['tblpurchases.id']) . '" >' . _l('edit') . '</a>';
        }
        $ktrrfq =  get_table_where("tblrfq_ask_price",array('id_purchases'=>$aRow['tblpurchases.id']),'','row');
        
        if ($hasPermissionDelete) {
            $purchases .= ' | <a href="' . admin_url('purchases/delete/' . $aRow['tblpurchases.id']) . '" class="text-danger delete-remind">' . _l('delete') . '</a>';
        }   
        $purchases .= '</div>';

        $row[] = $purchases;
        if(!empty($aRow['tbl_productions_capacity.reference_no'])){
        $productions_capacity = '<span class="inline-block label label-success">'._l('ch_productions_capacity').'</span><br>';
        }else
        {
        $productions_capacity ='';   
        }
        $row[] = $productions_capacity.$aRow['tbl_productions_capacity.reference_no'];
        
        $row[] = ($aRow['tblpurchases.name_purchase'] ? $aRow['tblpurchases.name_purchase'] : '');
        
        $staff = staff_profile_image($aRow['tblpurchases.staff_create'], array('staff-profile-image-small mright5'), 'small', array(
                            'data-toggle' => 'tooltip',
                            'data-title' => get_staff_full_name($aRow['tblpurchases.staff_create'])
                        )).get_staff_full_name($aRow['tblpurchases.staff_create']);
        $row[] = ($aRow['tblpurchases.staff_create'] ? $staff : '');


        $row[] = _dt($aRow['tblpurchases.date']);
        
            if($aRow['tblpurchases.status']==1)
                {
                    $type='warning';
                    $status=_l('dont_confirm');
                }
                elseif($aRow['tblpurchases.status']==2)
                {
                    $type='info';
                    $status=_l('dont_approve');
                }
                elseif($aRow['tblpurchases.status']==3)
                {
                    $type='success';
                    $status=_l('ch_confirm_22');
                }
                else
                {
                    $type='danger';
                    $status=_l('ch_cancel');
                }
            $status='<span class="inline-block label label-'.$type.'" task-status-table="'.$aRow['tblpurchases.status'].'">' . $status.'';
            if(has_permission('purchases', '', 'view') && has_permission('purchases', '', 'view_own'))
            {
                if($aRow['tblpurchases.status']!=3){
                    if($aRow['tblpurchases.status']==4){
                    $cancel = explode('|',$aRow['tblpurchases.history_status']);
                    if(explode(',', $cancel[3])[0] == '1foso')
                    {
                    $status .= '<a href="javacript:void(0)">
                    <i class="fa fa-check task-icon task-finished-icon" data-toggle="tooltip"></i>';    
                    }else
                    {
                    $status.='<a class="delete-remind" data-title="Bấm để bỏ hủy" href="'.admin_url('purchases/no_note_cancel/'.$aRow['tblpurchases.id']).'">
                    <i class="fa fa-check task-icon task-unfinished-icon" data-toggle="tooltip" ></i>';    
                    }
                    
                    
                    }else{
                    $status.='<a href="javacript:void(0)" data-loading-text="" onclick="var_status('.$aRow['tblpurchases.status'].','.$aRow['tblpurchases.id'].'); return false;">
                    <i class="fa fa-check task-icon task-unfinished-icon" data-toggle="tooltip" ></i>                    
                    ';
                    }
                }
                else
                {

                    
                     $status.='<a class="add_contact_person" data-toggle="tooltip" title="" data-type="service" data-placement="top"  data-id="'.$aRow['tblpurchases.id'].'">
                    <i class="fa fa-check task-icon task-finished-icon" data-title="Bấm để hủy" data-toggle="tooltip"></i>';   
                    
                }
            }
            else {
                if($aRow['tblpurchases.status']==1) {
                    $status .= '<a href="javacript:void(0)" data-loading-text="" onclick="var_status(' . $aRow['tblpurchases.status'] . ',' . $aRow['tblpurchases.id'] . '); return false">
                    <i class="fa fa-check task-icon task-unfinished-icon" data-toggle="tooltip" ></i>';
                }
                else
                {
                    $status .= '<a href="javacript:void(0)">
                    <i class="fa fa-check task-icon task-finished-icon" data-toggle="tooltip"></i>';
                }
            }
        $status .= '</a>
                </span><br>';
        $_data='';
        $history_status = explode('|',$aRow['tblpurchases.history_status']);

        foreach ($history_status as $key => $value) {
            $data=explode(',',$value);
            if(is_numeric($data[0]))
            {
            if($key == 1)
            {
                $name_status = _l('ch_confirm').': ';
            }elseif($key == 2)
            {
                $name_status = _l('ch_confirm_2').': ';
            }elseif($key == 3)
            {
                $name_status = _l('ch_cancel').': ';
            }
            $_data.=$name_status.staff_profile_image($data[0], array('staff-profile-image-small mright5'), 'small', array(
                            'data-toggle' => 'tooltip',
                            'data-title' => _l('ch_time').': '._dt($data[1])
                        )).get_staff_full_name($data[0]).'<br>';
            }
            $note_cancel= false;
            if($key == 3&&($aRow['note_cancel'] != ''))
            {
                $note_cancel = true;
                $_data.= '<b style="color:red">'._l('ch_note_cancel').': '.$aRow['note_cancel'].'</b><br>';
            }
        }

        $row[] = $status.$_data;

        $purchase_order = get_table_where('tblpurchase_order',array('id_purchases'=>$aRow['tblpurchases.id']));
        if(empty($ktrrfq))
        {
            $ktrrfq = get_table_where('tblsupplier_quotes',array('id_purchases'=>$aRow['tblpurchases.id']),'','row');
            $supplier_quote = $ktrrfq ;
        }
        if(empty($ktrrfq))
        {

            $ktrrfq = get_table_where('tblpurchase_order',array('id_purchases'=>$aRow['tblpurchases.id']),'','row');
        }
        $toggleActive = '<div class="onoffswitch" data-toggle="tooltip">
            <input type="checkbox"' . (!empty($ktrrfq) ? ' disabled' : '') . ' data-switch-url="' . admin_url() . 'purchases/change_purchases_type" name="onoffswitch"  class="onoffswitch-checkbox" id="' . $aRow['tblpurchases.id'] . '" data-id="' . $aRow['tblpurchases.id'] . '" ' . ($aRow['tblpurchases.type'] == 1 ? 'checked' : '') . '>
            <label class="onoffswitch-label change_type" for="' . $aRow['tblpurchases.id'] . '"></label>
            </div>';
        $toggleActive .= '<span class="hide">' . ($aRow['tblpurchases.type'] == 1 ? _l('is_active_export') : _l('is_not_active_export')) . '</span>';
        if(empty($purchase_order)) {
            $row[] = $toggleActive;
        } else {
            $row[] ='';    
        }

        $dataRow = '';
        $data ='<a href="'.admin_url('purchase_order/create_detail/'.$aRow['tblpurchases.id']).'" type="button"  class="btn btn-success">'._l('ch_add_purchase_order').'</a>';
        if(!empty($aRow['process'])) 
        {
            $process =  explode('|', $aRow['process']);
            if($process[0] == 1)
            {
                $ktr = get_table_where('tblrfq_ask_price',array('id_purchases'=>$aRow['tblpurchases.id']),'','row'); 
                $dataRow = '<span class="inline-block label label-warning">'.$ktr->prefix.'-'.$ktr->code.'</span>';
            } elseif($process[0] == 2) {
              $supplier_quotes  = $supplier_quote->prefix.'-'.$supplier_quote->code;   
              $dataRow = '<a href="#" onclick="view_supplier_quotes('.$supplier_quote->id.'); return false;" >' . purchase_quote($supplier_quotes) . '</a>';
            } elseif($process[0] == 3) {
                $purchase = get_items_purchase_new($aRow['tblpurchases.id']);
                $count = count($purchase_order);
                $_data = '';
                foreach ($purchase_order as $k => $v) {
                    $_data.= '<li><a onclick="view_purchase_order('.$v['id'].'); return false;" >' . $v['prefix'].'-'.$v['code'] . '</a></li>';
                }
                $_outputStatus = '<div class="dropdown" style="text-align: center;">
                            <button class="dropdown-toggle no_background color_warning" type="button" data-toggle="dropdown">'.count_number_PO_ch($count).'
                            </button>
                            <ul class="dropdown-menu right50">';
                $_outputStatus .= $_data;
                $_outputStatus .= '</ul></div>';
                if($purchase > 0) {
                    // $row[] = text_align(count_number_PO_ch($count).'<br>'.$data.'<br>'.$_data,'center');
                    $dataRow = $_outputStatus;
                } else {
                    $dataRow = $_outputStatus;
                }   
            }
        }
        else {
            $dataRow = '';
        }
        $row[] = text_align(process_purchases_img($aRow['tblpurchases.id']).process_purchases($aRow['tblpurchases.id']),'center');


        $_outputStatus = '<div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">'._l('action').'
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu h_right">';

        if(!empty($aRow['process']) || !empty($aRow['id_order'])) {
            $process =  explode('|', $aRow['process']);
            if($process[0] == 1) {
               
            } elseif($process[0] == 2) {
                $_outputStatus .= '';
            } else{
                $order = get_table_where('tblpurchase_order',array('id_purchases'=>$aRow['tblpurchases.id']),'','row');
                if(!empty($order))
                {
                    $purchase = get_items_purchase_new($aRow['tblpurchases.id']);
                    if(($purchase > 0)&&($aRow['tblpurchases.status']!=4)) {
                        $_outputStatus .= '<li><a href="'.admin_url('purchase_order/create_detail/'.$aRow['tblpurchases.id']).'"><i class="lnr lnr-cog"></i>  '._l('ch_add_purchase_order').'</a></li>';
                    }
                }else
                {
                    $purchase = get_items_purchase_new($aRow['tblpurchases.id']);
                    if(($purchase > 0)&&($aRow['tblpurchases.status']!=4)) {
                        $_outputStatus .= '<li><a href="'.admin_url('purchase_order/create_detail/'.$aRow['tblpurchases.id']).'"><i class="lnr lnr-cog"></i>  '._l('ch_add_purchase_order').'</a></li>';
                    }else{
                    $_outputStatus .= '';   
                    }
                }
            }
        }
        else {
            if($aRow['tblpurchases.status']==3) {
                if($aRow['tblpurchases.type'] == 1) {
                    $_outputStatus .= '<li><a onclick="rdq_modal('.$aRow['tblpurchases.id'].'); return false;"><i class="lnr lnr-cog"></i>  '._l('ch_add_rfq').'</a></li>';
                    $_outputStatus .= '<li><a href="'.admin_url('purchase_order/create_detail/'.$aRow['tblpurchases.id']).'"><i class="lnr lnr-cog"></i>  '._l('ch_add_purchase_order').'</a></li>';
                } else {
                    $_outputStatus .= '<li><a href="'.admin_url('supplier_quotes/detail_create/'.$aRow['tblpurchases.id'].'/0/1').'"><i class="lnr lnr-cog"></i>'._l('ch_add_quote').'</a></li>';
                    $_outputStatus .= '<li><a href="'.admin_url('purchase_order/create_detail/'.$aRow['tblpurchases.id']).'"><i class="lnr lnr-cog"></i>  '._l('ch_add_purchase_order').'</a></li>';
                }
            }
        }
        $content = str_replace('"', '\'', render_textarea('note_cancel', 'ch_note_finish')).'<div class=\'text-right\'><button onclick=\'save_contact_person('.$aRow['tblpurchases.id'].')\' class=\'btn btn-danger po-delete-json\'>'._l('submit').'</button><a class=\'btn btn-default po-close\'>'._l('close').'</a></div>';
        if($aRow['tblpurchases.status']!=4)
        {
        $_outputStatus .= '<li class="not-outside"><a type="button" data-container="body" data-html="true" data-toggle="popover" data-placement="left" data-content="'.$content.'"><i class="fa fa-remove"></i>  '._l('ch_finish').'</a></li>';
        }
        $_outputStatus .= '<li><a href="'.admin_url('purchases/print_pdf/'.$aRow['tblpurchases.id']).'" target="_blank"><i class="fa fa-print"></i>  '._l('print_vote').'</a></li>';
        $_outputStatus .= '</ul></div>';


        $row[] = $_outputStatus;
        
        // Custom fields add values
        foreach ($customFieldsColumns as $customFieldColumn) {
            $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
        }

        // $row['DT_RowClass'] = 'has-row-options';

        $row = hooks()->apply_filters('purchases_table_row_data', $row, $aRow);

        $output['aaData'][] = $row;
    }
