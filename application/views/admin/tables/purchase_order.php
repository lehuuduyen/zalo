    <?php

    defined('BASEPATH') or exit('No direct script access allowed');

    $hasPermissionDelete = has_permission('purchases', '', 'delete');

    $custom_fields = get_table_custom_fields('purchases');
    $this->ci->db->query("SET sql_mode = ''");

    $aColumns = [
        '4',
        'tblpurchase_order.id',
        'tblpurchase_order.code',
        '1',
        'tblsuppliers.company',
        'tblpurchase_order.totalAll_suppliers',
        '(tblpurchase_order.price_other_expenses + tblpurchase_order.amount_paid) as total_expenses',
        'tblpurchase_order.date',
        'tblpurchase_order.red_invoice',
        'tblpurchase_order.status',
        'tblpurchase_order.status_pay',
        '2',
    ];
    $sIndexColumn = 'id';
    $sTable       = 'tblpurchase_order';
    $where        = [];
    $filter = [];
    $join         = array(
        'LEFT JOIN tblsuppliers ON tblsuppliers.id=tblpurchase_order.suppliers_id',
        'LEFT JOIN tbltickets_priorities ON tbltickets_priorities.priorityid = tblpurchase_order.id_tickets_priorities'
    );
    foreach ($custom_fields as $key => $field) {
        $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);
        array_push($customFieldsColumns, $selectAs);
        array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
        array_push($join, 'LEFT JOIN '.db_prefix().'customfieldsvalues as ctable_' . $key . ' ON tblpurchase_order.id = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $key . '.fieldid=' . $field['id']);
    }
    $join = hooks()->apply_filters('purchases_table_sql_join', $join);

    if ($this->ci->input->post('filterStatus')) {
        if(is_numeric($this->ci->input->post('filterStatus'))) {
            if($this->ci->input->post('filterStatus') == 1) {
                array_push($where, 'AND tblpurchase_order.status = 1');
            } else if($this->ci->input->post('filterStatus') == 2) {
                array_push($where, 'AND tblpurchase_order.status = 2');
            } else if($this->ci->input->post('filterStatus') == 3) {
                array_push($where, 'AND tblpurchase_order.status = 3');
            } else if($this->ci->input->post('filterStatus') == 4) {
                array_push($where, 'AND tblpurchase_order.red_invoice <> 0');
            } else if($this->ci->input->post('filterStatus') == 5) {
                array_push($where, 'AND tblpurchase_order.red_invoice = 0');
            } else if($this->ci->input->post('filterStatus') == 6) {
                array_push($where, 'AND ((tblpurchase_order.status_pay = 2 AND tblpurchase_order.red_invoice = 0 ) or (tblpurchase_order.red_invoice != 0 AND tblpurchase_invoice.status = 2))');
                array_push($join,'LEFT JOIN tblpurchase_invoice ON tblpurchase_invoice.id=tblpurchase_order.red_invoice');
            } else if($this->ci->input->post('filterStatus') == 7) {
                array_push($where, 'AND ((tblpurchase_order.status_pay = 1 AND tblpurchase_order.red_invoice = 0 ) or (tblpurchase_order.red_invoice != 0 AND tblpurchase_invoice.status = 1))');
                array_push($join,'LEFT JOIN tblpurchase_invoice ON tblpurchase_invoice.id=tblpurchase_order.red_invoice');
            } else if($this->ci->input->post('filterStatus') == 8) {
                array_push($where, 'AND ((tblpurchase_order.status_pay = 0 AND tblpurchase_order.red_invoice = 0 ) or (tblpurchase_order.red_invoice != 0 AND tblpurchase_invoice.status = 0))');
                array_push($join,'LEFT JOIN tblpurchase_invoice ON tblpurchase_invoice.id=tblpurchase_order.red_invoice');
            } 
        }
    }
    $suppliers_id = $this->ci->input->post('suppliers_id');
    if(is_numeric($suppliers_id))
    {
        array_push($where, 'AND tblpurchase_order.suppliers_id = '.$this->ci->input->post('suppliers_id'));
    }
    if ($this->ci->input->post('search_code')) {
        if(is_numeric($this->ci->input->post('search_code'))) {
            array_push($where, 'AND tblpurchase_order.id = '.$this->ci->input->post('search_code'));
        }
    }
    if ($this->ci->input->post('search_staff')) {
        array_push($where, 'AND  tblpurchase_order.staff_create IN (' . implode(', ', $this->ci->input->post('search_staff')) . ')');
    }
    if ($this->ci->input->post('search_id_suppliers')) {
        array_push($where, 'AND tblpurchase_order.suppliers_id IN (' . implode(', ', $this->ci->input->post('search_id_suppliers')) . ')');
    }
    if ($this->ci->input->post('search_priorities')) {
        array_push($where, 'AND tblpurchase_order.id_tickets_priorities IN (' . implode(', ', $this->ci->input->post('search_priorities')) . ')');
    }
    $search_date = $this->ci->input->post('search_date');
    if($search_date)
    {
        $data_start = explode(' - ', $search_date);
        array_push($where, 'AND tblpurchase_order.date_create BETWEEN "' . to_sql_date($data_start[0]) . '" and "' . to_sql_date($data_start[1]) . '"');
    }
    
    $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['
        tbltickets_priorities.name,
        tbltickets_priorities.color,
        tblpurchase_order.id_tickets_priorities,
        tblpurchase_order.prefix,
        tblpurchase_order.status_pay,
        tblpurchase_order.amount_paid,
        tblpurchase_order.price_other_expenses,
        tblpurchase_order.history_status,
        tblpurchase_order.suppliers_id,
        tblpurchase_order.cancel,
        tblpurchase_order.id_purchases,
        tblpurchase_order.check_purchase_all,
        tblpurchase_order.not_new_by_staff,
        tblpurchase_order.type_plan as type_plan_purchase_order
    ']);
    $output  = $result['output'];
    $rResult = $result['rResult'];

    $j=0;
    foreach ($rResult as $aRow) {
        // kiểm tra đã tạo phiếu nhập hết hay chưa
        $count_items_import = get_items_import($aRow['tblpurchase_order.id']);
        $import = get_table_where('tblimport',array('id_order'=>$aRow['tblpurchase_order.id']),'','row');
        $j++;
        $row = [];

        $not_new_by_staff = explode(',',$aRow['not_new_by_staff']);
    
        if(($aRow['tblpurchase_order.red_invoice'] == 0)&&(is_numeric($suppliers_id)&&($aRow['tblpurchase_order.status'] > 2)&&($aRow['status_pay'] != 2)))
        {
        $row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['tblpurchase_order.id'] . '"><label></label></div>';
        }else
        {
        $row[] = '';    
        }    
        $checkDateWarning = true;
        $row[] = $j;
        
        if($aRow['type_plan_purchase_order'] == 1){
        $productions_capacity = '<span class="inline-block label label-success">'._l('ch_productions_capacity').'</span><br>';
        }else
        {
        $productions_capacity ='';   
        }
        $purchases  = $aRow['prefix'].'-'.$aRow['tblpurchase_order.code'].'<br>'.$productions_capacity;
        $isPerson = false;
        $purchases = '<a onclick="view_purchase_order('.$aRow['tblpurchase_order.id'].'); return false;" >' . $purchases . '</a>';
        if(!in_array(get_staff_user_id(), $not_new_by_staff) && $aRow['tblpurchase_order.status'] == 1 && $aRow['cancel'] == 0) {
            $purchases.= '<br><p class="wap-new">new</p>';
            $row['DT_RowClass'] = 'alert-new';
            $checkDateWarning = false;
        }
        else if($aRow['id_tickets_priorities'] && $checkDateWarning) {
            $purchases.='<br><p style="background: '.$aRow['color'].';color: #fff;font-weight: 300;border-radius: 10px;padding: 0px 10px;">'.$aRow['name'].'</p>';
        }
        $purchases .= '<div class="row-options">';
        $purchases .= '<a href="#" onclick="view_purchase_order('.$aRow['tblpurchase_order.id'].'); return false;" >' . _l('view') . '</a>';

        if($aRow['tblpurchase_order.status'] < 3)
        {
            if(!empty($aRow['id_purchases']))
             {
                if($aRow['check_purchase_all'] == 1)
                {
                 $purchases .= ' | <a href="' . admin_url('purchase_order/purchases_detail_all/' . $aRow['tblpurchase_order.id']) . '" >' . _l('edit') . '</a>';   
                }
                else
                {
                 $purchases .= ' | <a href="' . admin_url('purchase_order/purchases_detail/' . $aRow['tblpurchase_order.id']) . '" >' . _l('edit') . '</a>';   
                }
             }else
             {
                $purchases .= ' | <a href="' . admin_url('purchase_order/detail/' . $aRow['tblpurchase_order.id']) . '" >' . _l('edit') . '</a>';
             }
        
        }
        if ($hasPermissionDelete&&($aRow['tblpurchase_order.red_invoice'] == 0)&&($aRow['tblpurchase_order.status_pay'] == 0)) {
            $purchases .= ' | <a href="' . admin_url('purchase_order/delete/' . $aRow['tblpurchase_order.id']) . '" class="text-danger delete-remind">' . _l('delete') . '</a>';
        }  
        $purchases .= '</div>';

        $row[] = $purchases;

        $row[] = format_purchase_order($aRow['tblpurchase_order.id']);
        $row[] ='<a onclick="int_suppliers_view('.$aRow['suppliers_id'].'); return false;">' . $aRow['tblsuppliers.company'] . '</a>';
        $row[] = number_format($aRow['tblpurchase_order.totalAll_suppliers']);
        // $row[] = number_format($aRow['total_expenses']);
        if($aRow['total_expenses'] > 0)
            {
            $title ='';
            if($aRow['price_other_expenses'] > 0)
            {
            $title .= _l('ch_other_expenses').': '.number_format($aRow['price_other_expenses']).'<br>';    
            }
            if($aRow['amount_paid'] > 0)
            {
            $title .= _l('ch_status_pays_slip_import').': '.number_format($aRow['amount_paid']);    
            }
            if(!empty($aRow['tblpurchase_order.red_invoice']))
            {
            $ktr = get_table_where('tblpurchase_invoice',array('id'=>$aRow['tblpurchase_order.red_invoice']),'','row');  
            if(!empty($ktr)&&$ktr->status == 2) 
            {
            $amount_paid = $aRow['tblpurchase_order.totalAll_suppliers'] - $aRow['price_other_expenses'];
            $title .= _l('ch_status_pays_slip_invoice').': '.number_format($amount_paid);
            $aRow['total_expenses'] = $aRow['tblpurchase_order.totalAll_suppliers'];
            } 
            }
            $row[] = '<span data-html="true" data-toggle="tooltip" data-title="' .$title. '" class="text-has-action">'.number_format($aRow['total_expenses']).'</span>';
            }else
            {
            if(!empty($aRow['tblpurchase_order.red_invoice']))
            {
            $ktr = get_table_where('tblpurchase_invoice',array('id'=>$aRow['tblpurchase_order.red_invoice']),'','row');  
            if(!empty($ktr)&&$ktr->status == 2) 
            {
            $aRow['total_expenses'] = $aRow['tblpurchase_order.totalAll_suppliers'];
            } 
            }
            $row[] = '<span >'.number_format($aRow['total_expenses']).'</span>';    
        }

        $row[] = _d($aRow['tblpurchase_order.date']);
        $content = str_replace('"', '\'', form_open(admin_url('purchase_invoice/add'), array('id' => 'purchase_invoice-form'))).'<input name=\'id_import\' class=\'hide\' value=\''.$aRow['tblpurchase_order.id'].'\'><input name=\'id_supplier\'class=\'hide\' value=\''.$aRow['suppliers_id'].'\'>'.str_replace('"', '\'', render_input('code_invoice', 'ch_code_invoice','','number')).str_replace('"', '\'', render_date_input('date_invoice', 'ch_date_invoice',_d(date('Y-m-d')))).str_replace('"', '\'', render_textarea('note', 'ch_note')).'<div class=\'text-right\'><button type=\'submit\' class=\'btn btn-danger po-delete-json\'>'._l('submit').'</button><a class=\'btn btn-default po-close\'>'._l('close').'</a></div>'.str_replace('"', '\'',form_close()).'<script>
                        var opt = {
                            format: \'d/m/Y\',
                            timepicker: false,
                            scrollInput: false,
                            lazyInit: true,
                            dayOfWeekStart: \'hau\',
                        };
                        $(\'#date_invoice\').datetimepicker(opt);
                           _validate_form($(\'#purchase_invoice-form\'),{code_invoice:\'required\',date_invoice:\'required\'},purchase_invoice);

           function purchase_invoice(form) {
               var data = $(form).serialize(),
                   action = form.action;
               return $.post(action, data).done(function(form) {
                   form = JSON.parse(form),
                   alert_float(form.alert_type, form.message);
                                                                                                    
                    $(\'.popover\').popover(\'hide\');
                    $(\'.table-import\').DataTable().ajax.reload();
                    window.open(\''.admin_url('purchase_invoice').'\', \'_blank\');   

               }), !1
           }</script>';
            if($aRow['tblpurchase_order.red_invoice'] != 0)
            {
                $color = 1;
                $class ='class="invoice_button_red"';
                $content ='';
            }else
            {
                $color = 0;
                $class ='class="invoice_button"';
                if(($aRow['status_pay'] != 0)&&$aRow['amount_paid'] > 0){
                $content ='';   
                }
            }
            $row[] = '<div class="text-center">
                    <a '.$class.' data-container="body" data-id="'.$aRow['tblpurchase_order.id'].'" data-html="true" data-toggle="popover" data-placement="left" data-content="'.$content.'">'.format_type_invoice($color).'</a>
                    </div>';        
        if(($aRow['tblpurchase_order.red_invoice'] == 0))
        {
            $row[]='<div class="text-center">'.format_status_pay_slip($aRow['tblpurchase_order.status_pay']).'<div>';
        }else
        {
            $invoice = get_table_where('tblpurchase_invoice',array('id'=>$aRow['tblpurchase_order.red_invoice']),'','row');
            $row[] = '<div class="text-center">'.format_status_pay_slip($invoice->status).'<div>';
        }
        if($aRow['tblpurchase_order.status']==1)
        {
            $type='warning';
            $status='Chưa xác nhận';
        }
        elseif($aRow['tblpurchase_order.status']==2)
        {
            $type='info';
            $status='Đã xác nhận';
        }
        else
        {
            $type='success';
            $status='Đã duyệt';
        }


        $none_img = staff_profile_image(0, array('staff-profile-image-small'), 'small');
        $history_status = explode('|',$aRow['history_status']);

        $users = '<div style="min-width: 700px;position: relative;">';
        $dem_temp = 0; // đếm xem có bao nhiêu đc active
        foreach ($history_status as $key => $value) {
            $data = explode(',',$value);
            if($key == 0) {
                $users .= '<div class="step-status">
                            <div class="active">'.staff_profile_image($data[0], array('staff-profile-image-small'), 'small').'</div>
                            <div class="success">'._l('create').'</div>
                            <div class="bold">'.get_staff_full_name($data[0]).'</div>
                            <div class="bold">'._dt($data[1]).'</div>
                        </div>';
                $dem_temp++;
            } else if($key == 1) {
                $users .= '<div class="step-status">
                            <div class="active">'.staff_profile_image($data[0], array('staff-profile-image-small'), 'small').'</div>
                            <div class="success">'._l('proceed').'</div>
                            <div class="bold">'.get_staff_full_name($data[0]).'</div>
                            <div class="bold">'._dt($data[1]).'</div>
                        </div>';
                $dem_temp++;
            } else if($key == 2) {
                $users .= '<div class="step-status">
                            <div class="active">'.staff_profile_image($data[0], array('staff-profile-image-small'), 'small').'</div>
                            <div class="success">'._l('accept').'</div>
                            <div class="bold">'.get_staff_full_name($data[0]).'</div>
                            <div class="bold">'._dt($data[1]).'</div>
                        </div>';
                $dem_temp++;
            } else if($key == 3) {
                $users .= '<div class="step-status">
                            <div class="active">'.staff_profile_image($data[0], array('staff-profile-image-small'), 'small').'</div>
                            <div class="success">'._l('add_items').'</div>
                            <div class="bold">'.get_staff_full_name($data[0]).'</div>
                            <div class="bold">'._dt($data[1]).'</div>
                        </div>';
                $dem_temp++;
            }
        }

        $no_event = '';
        $no_click = '';
        if($aRow['cancel'] != 0) {
            $no_event = 'no-drop';
            $no_click = 'none-event';
        }
        if($aRow['tblpurchase_order.status'] == 1) {
            $users .= '<div class="step-status '.$no_event.'">
                            <div class="'.$no_click.'" data-loading-text="" onclick="var_status('.$aRow['tblpurchase_order.status'].','.$aRow['tblpurchase_order.id'].'); return false;">'.$none_img.'</div>
                            <div>'._l('proceed').'</div>
                        </div>
                        <div class="step-status">
                            <div class="no-drop">'.$none_img.'</div>
                            <div>'._l('accept').'</div>
                        </div>
                        <div class="step-status">
                            <div class="no-drop">'.$none_img.'</div>
                            <div>'._l('add_items').'</div>
                        </div>';
        } else if($aRow['tblpurchase_order.status'] == 2) {
            $users .= '<div class="step-status '.$no_event.'">
                            <div class="'.$no_click.'"  data-loading-text="" onclick="var_status('.$aRow['tblpurchase_order.status'].','.$aRow['tblpurchase_order.id'].'); return false;">'.$none_img.'</div>
                            <div>'._l('accept').'</div>
                        </div>
                        <div class="step-status">
                            <div class="no-drop">'.$none_img.'</div>
                            <div>'._l('add_items').'</div>
                        </div>';
        } else if($aRow['tblpurchase_order.status'] == 3) {
            if(!empty($import))
            {
            $dem_temp++;
            if(file_exists('uploads/company/'.get_option('company_logo')))
            {
            $none_img_ch ='<img src="'.base_url('uploads/company/'.get_option('company_logo')).'" class="staff-profile-image-small">';
            if($count_items_import == 0)
            {
            $status_import ='<span class="inline-block label label-warning">' . _l('ch_imports_full') . '</span>'; 
            }else
            {
            $status_import ='<span class="inline-block label label-warning">' . _l('ch_imports_part') . '</span>';     
            }
            $users .= '<div class="step-status no-drop">
                            <div class="none-event no-drop active">'.$none_img_ch.'</div>
                            <div class="success">'._l('add_items').'</div><br>'.$status_import.'
                        </div>';   
            }else
            {
            $none_img_ch = $none_img;
            $users .= '<div class="step-status no-drop">
                            <div class="none-event no-drop active">'.$none_img_ch.'</div>
                            <div class="success">'._l('add_items').'</div><br>'.$status_import.'
                        </div>';   
            }  
            }else
            {
            $none_img_ch = $none_img;
            $users .= '<div class="step-status no-drop">
                            <div class="none-event no-drop" >'.$none_img_ch.'</div>
                            <div>'._l('add_items').'</div>
                        </div>';
            }
            
        }
        if($aRow['cancel'] == 0) {
            if($count_items_import == 0)
            {
                $no_event_ch = 'class="no-drop none-event"';
            }else
            {
                $no_event_ch = 'class="'.$no_event.'"';
            }
            $users .= '<div class="step-status">
                        <div '.$no_event_ch.' onclick="cancel_status('.$aRow['tblpurchase_order.id'].'); return false;">'.$none_img.'</div>
                        <div class="red">'._l('ch_cancel').'</div>
                    </div>';
        } else {
            $data = explode(',',$aRow['cancel']);
            if($data[0] == '1foso')
            {  
            $users .= '<div class="step-status">
                        <div class="cancel"><img src="'.base_url('uploads/company/'.get_option('company_logo')).'" class="staff-profile-image-small"></div>
                        <div class="red">'._l('ch_cancel').'</div>
                        <div class="bold">Hệ thống</div>
                        <div class="bold">'._dt($data[1]).'</div>
                    </div>';  
            }else
            {
            $users .= '<div class="step-status">
                        <div class="cancel">'.staff_profile_image($data[0], array('staff-profile-image-small'), 'small').'</div>
                        <div class="red">'._l('ch_cancel').'</div>
                        <div class="bold">'.get_staff_full_name($data[0]).'</div>
                        <div class="bold">'._dt($data[1]).'</div>
                    </div>';    
            }
            
        }
        if($dem_temp == 1) {
            $dem_temp = '10';
        } else if($dem_temp == 2) {
            $dem_temp = '30';
        } else if($dem_temp == 3) {
            $dem_temp = '50';
        } else if($dem_temp == 4) {
            $dem_temp = '70';
        }

        $users .= '<div class="line line'.$dem_temp.'"></div>';
        $users .= '<div class="clearfix"></div>';
        $users .= '</div>';
        $row[] = $users;
        

        $checkStatus = true;
        $_outputStatus = '<div class="dropdown H_drop ">
            <button class="btn btn-primary dropdown-toggle " type="button" data-toggle="dropdown">'._l('action').'
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu h_right">';
        if(($aRow['status_pay'] != 2)&&($aRow['tblpurchase_order.red_invoice'] == 0)&&($aRow['tblpurchase_order.status'] > 2))
        {
        $_outputStatus .= '<li><a onclick="payment('.$aRow['tblpurchase_order.id'].')"><i class="fa fa-file width-icon-actions"></i> '._l('ch_pay_slip').'</a></li>';
        }   
        if($aRow['cancel'] != 0 || $aRow['tblpurchase_order.status'] < 3 || $count_items_import <= 0)
        {
            $checkStatus = false;
        }
        if(isset($checkStatus) && $checkStatus === true) {
            $_outputStatus .='<li><a href="'.admin_url('import/create_detail/'.$aRow['tblpurchase_order.id']).'"><i class="fa fa-inbox" aria-hidden="true"></i> '._l('ch_importsadd').'</a></li>';
        }
        $_outputStatus .= '<li><a href="'.admin_url('purchase_order/print_pdf/'.$aRow['tblpurchase_order.id']).'" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> '._l('print_vote').'</a></li>';
        $_outputStatus .='</ul></div>';
        $row[] =$_outputStatus;    
        // Custom fields add values
        foreach ($customFieldsColumns as $customFieldColumn) {
            $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
        }

        // $row['DT_RowClass'] = 'has-row-options';
        
        $row = hooks()->apply_filters('purchases_table_row_data', $row, $aRow);

        $output['aaData'][] = $row;
    }
