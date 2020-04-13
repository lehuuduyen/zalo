    <?php

    defined('BASEPATH') or exit('No direct script access allowed');

    $hasPermissionDelete = has_permission('return_suppliers', '', 'delete');
    $hasPermissionEdit = has_permission('return_suppliers', '', 'edit');

    $custom_fields = get_table_custom_fields('return_suppliers');
    $this->ci->db->query("SET sql_mode = ''");

    $aColumns = [
        'tblexport_different.id',
        'tblexport_different.code',
        'tblexport_different.date',
        'tblexport_different.object',
        'tblexport_different.id_object',
        'tblexport_different.status',
        'tblexport_different.subtotal',
        'tblexport_different.staff_id',
        'tblexport_different.warehouseman_id',
        'tblexport_different.note',
        '2',
    ];
    $sIndexColumn = 'id';
    $sTable       = 'tblexport_different';
    $where        = [];
    $filter = [];
    $join = [
    ];


    $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
        'tblexport_different.prefix','tblexport_different.history_status','object_text'
    ]);
    $output  = $result['output'];
    $rResult = $result['rResult'];
    $j=0;
    $currentPage=$this->_instance->input->post('start');
    $currentall=$output['iTotalRecords'];
    foreach ($rResult as $key => $aRow) {
    $row = array();
    $j++;
    for ($i = 0; $i < count($aColumns); $i++) {
        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }
        if ($aColumns[$i] == '2') {
        $_data ='';
        }
        if ($aColumns[$i] == 'tblexport_different.id') {
        $_data =$j;
        }
        if ($aColumns[$i] == 'tblexport_different.code') {
            $_data =$aRow['prefix'].'-'.$aRow['tblexport_different.code'];
            $_outputStatus = '<div class="row-options">';
            $_outputStatus .= '<a href="#" onclick="view_export_different('.$aRow['tblexport_different.id'].'); return false;" >' . _l('view') . '</a>';
            if($aRow['tblexport_different.status'] != 1)
            {
            $_outputStatus .= ' | <a href="' . admin_url('export_different/detail/' . $aRow['tblexport_different.id']) . '" >' . _l('edit') . '</a>';
            }
            if($hasPermissionDelete)
            {
            $_outputStatus .= ' | <a href="' . admin_url('export_different/delete/' . $aRow['tblexport_different.id']) . '" class="text-danger delete-reminds">' . _l('delete') . '</a>';
            }
            $_outputStatus .= '</div>';
            $_data=$_data.$_outputStatus;
        }
        if ($aColumns[$i] == 'tblexport_different.date') {
        $_data =_d($aRow['tblexport_different.date']);
        }
        if ($aColumns[$i] == 'tblexport_different.warehouseman_id') {
            if (has_permission('return_suppliers', '', 'approve_warehouse')) {
                if ($aRow['tblexport_different.status'] == 1) {
                        $button = _l('ch_warehouse_nd');
                        $title = _l('warehouseman_confirm');
                        $type = 'fa-square-o';
                        if($aRow['tblexport_different.warehouseman_id'])
                        {   
                            $button = _l('ch_warehouse_d');
                            $title=_l('warehouseman_confirm_cancel');
                            $type='fa-check-square-o';
                            $_data = '<a href="" onclick="confirm_warehous('.$aRow['tblexport_different.id'].','.$aRow['tblexport_different.warehouseman_id'].');return false;" class=" btn btn-info btn-icon "  data-toggle="tooltip" data-loading-text="'._l('wait_text').'" data-original-title="'.$title.'"><i class="fa  '.$type.'"></i> '.$button.'</a>'.($aRow['tblexport_different.warehouseman_id']?'<br>'._l('warehouseman').': <span style="color: red;">'.get_staff_full_name($aRow['tblexport_different.warehouseman_id']).'</span>':'');
                        }else
                        {
                        $_data = '<a href="" onclick="confirm_warehous('.$aRow['tblexport_different.id'].','.$aRow['tblexport_different.warehouseman_id'].');return false;" class=" btn btn-info btn-icon "  data-toggle="tooltip" data-loading-text="'._l('wait_text').'" data-original-title="'.$title.'"><i class="fa  '.$type.'"></i> '.$button.'</a>'.($aRow['tblexport_different.warehouseman_id']?'<br>'._l('warehouseman').': <span style="color: red;">'.get_staff_full_name($aRow['tblexport_different.warehouseman_id']).'</span>':'');
                        }
                }else
                {
                        $_data ='';
                }
            }
        }
        if ($aColumns[$i] == 'tblexport_different.staff_id') {
        $_data =staff_profile_image($aRow['tblexport_different.staff_id'], array('staff-profile-image-small mright5'), 'small', array(
                            'data-toggle' => 'tooltip',
                            'data-title' => get_staff_full_name($aRow['tblexport_different.staff_id'])
                        )).get_staff_full_name($aRow['tblexport_different.staff_id']);
        }
        if ($aColumns[$i] == 'tblexport_different.subtotal') {
        $_data =number_format($aRow['tblexport_different.subtotal']);
        }

        if ($aColumns[$i] == 'tblexport_different.object') {
            if($aRow['tblexport_different.object'] == 1)
            {
            $text = 'KHÁCH HÀNG';
            }
            if($aRow['tblexport_different.object'] == 2)
            {
            $text = 'NHÀ CUNG CẤP';
            }
            if($aRow['tblexport_different.object'] == 3)
            {
            $text = 'NHÂN VIÊN';
            }
            if($aRow['tblexport_different.object'] == 4)
            {
            $text = 'KHÁC';
            }
        $_data =$text;
        }
        if ($aColumns[$i] == 'tblexport_different.id_object') {
            $_data = '';
            if($aRow['tblexport_different.object'] == 2)
            {
                $supplier = get_table_where('tblsuppliers',array('id'=>$aRow['tblexport_different.id_object']),'','row');
                $_data = '<a href="#" onclick="int_suppliers_view('.$supplier->id.'); return false;" >'.$supplier->company.'</a>';
            }
            if($aRow['tblexport_different.object'] == 1)
            {
                $client = get_table_where('tblclients',array('userid'=>$aRow['tblexport_different.id_object']),'','row');
                $_data = $client->company;
            }
            if($aRow['tblexport_different.object'] == 3)
            {
                $_data = get_staff_full_name($aRow['tblexport_different.id_object']);
            }
            if($aRow['tblexport_different.object'] == 4)
            {
                $_data = $aRow['object_text'];
            }

        }
        if($aColumns[$i] == 'tblexport_different.status')
        {
                if($aRow['tblexport_different.status']==0)
                {
                    $type='warning';
                    $status=_l('dont_approve');
                }
                elseif($aRow['tblexport_different.status']==1)
                {
                    $type='info';
                    $status=_l('ch_confirm_22');
                }
                $status='<span class="inline-block label label-'.$type.'" task-status-table="'.$aRow['tblexport_different.status'].'">' . $status.'';
                if(has_permission('return_suppliers', '', 'approve'))
                {
                    if($aRow['tblexport_different.status']==0) {
                        $status .= '<a href="javacript:void(0)" data-loading-text=""  onclick="var_status(' . $aRow['tblexport_different.status'] . ',' . $aRow['tblexport_different.id'] . '); return false">
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
                $__data='';
                $history_status = explode('|',$aRow['history_status']);

                foreach ($history_status as $key => $value) {
                    $data=explode(',',$value);
                    if(is_numeric($data[0]))
                    {
                    $__data.=staff_profile_image($data[0], array('staff-profile-image-small mright5'), 'small', array(
                                    'data-toggle' => 'tooltip',
                                    'data-title' => ' Vào lúc: '._dt($data[1])
                                )).get_staff_full_name($data[0]).'<br>';
                    }
                }

                $_data = $status.$__data;
        }
        $row[] = $_data;
    }
        $output['aaData'][] = $row;
    }
