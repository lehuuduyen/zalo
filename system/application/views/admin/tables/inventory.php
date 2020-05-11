<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hasPermissionDelete = has_permission('pay_slip', '', 'delete');

$aColumns     = array(
    'tblinventory.id',
    'tblinventory.code',
    'tblinventory.date',
    'tblinventory.staff_id',
    'tblinventory.status',
    'waid.name as waidname',
    'tblinventory.note',
);
$sIndexColumn = "id";
$sTable       = 'tblinventory';
$where        = array(
  
);
    if ($this->ci->input->post('filterStatus')) {
        if(is_numeric($this->ci->input->post('filterStatus'))) {
            if($this->ci->input->post('filterStatus') == 1) {
                array_push($where, 'AND tblinventory.status = 1');
            } else if($this->ci->input->post('filterStatus') == 2) {
                array_push($where, 'AND tblinventory.status = 2');
            }
        }
    }
$join         = array(
    'LEFT JOIN tblwarehouse waid on waid.id = tblinventory.warehouse_id',
);
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array('tblinventory.prefix','date_create','history_status','tblinventory.not_new_by_staff',
));
$output       = $result['output'];
$rResult      = $result['rResult'];

$j=0;
foreach ($rResult as $aRow) {
    $row = array();
    $j++;
    for ($i = 0; $i < count($aColumns); $i++) {
        $ktr_exit = get_table_where('tbladjusted',array('id_inventory'=>$aRow['tblinventory.id']),'','row');
        $not_new_by_staff = explode(',',$aRow['not_new_by_staff']);
        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }
        if ($aColumns[$i] == 'tblinventory.id') {
        if(!in_array(get_staff_user_id(), $not_new_by_staff)&& $aRow['tblinventory.status'] == 0) {
            $_data = '<div class="text-center">'.$j.'</div> <span class="wap-new">new</span>';
            $row['DT_RowClass'] = 'alert-new';
        }
        else {
            $_data ='<div class="text-center">'.$j.'</div>';
        }
        }
        if ($aColumns[$i] == 'tblinventory.code') {
        $_data ='<div class="text-center">'.$aRow['prefix'].'-'.$aRow['tblinventory.code'].'</div>';
            $transfer  = $aRow['prefix'].'-'.$aRow['tblinventory.code'];
            $transfer = '<a  >' . $transfer . '</a>';
            $transfer .= '<div class="row-options">';

            $transfer .= '<a href="#" onclick="view_inventory('.$aRow['tblinventory.id'].'); return false;" >' . _l('view') . '</a>';
            if(empty($ktr_exit))
            {
                $transfer .= ' | <a href="' . admin_url('inventory/detail/' . $aRow['tblinventory.id']) . '" >' . _l('edit') . '</a>';   
            }
            if ($hasPermissionDelete&&empty($ktr_exit)) {
                $transfer .= ' | <a href="' . admin_url('inventory/delete/' . $aRow['tblinventory.id']) . '" class="text-danger delete-remind">' . _l('delete') . '</a>';
            }   
            $transfer .= '</div>';
        $_data=$transfer;
        }
        if ($aColumns[$i] == 'tblinventory.date') {
            $_data = _d($aRow['tblinventory.date']);
        } 
        if ($aColumns[$i] == 'tblinventory.status') {
            if($aRow['tblinventory.status']==0)
                {
                    $type='warning';
                    $status=_l('dont_approve');
                }
                elseif($aRow['tblinventory.status']==1)
                {
                    $type='info';
                    $status=_l('ch_confirm_22');
                }
            $status='<span class="inline-block label label-'.$type.'" task-status-table="'.$aRow['tblinventory.status'].'">' . $status.'';
            if(has_permission('pay_slip', '', 'view') && has_permission('pay_slip', '', 'view_own'))
            {
                if($aRow['tblinventory.status']==0) {
                    $status .= '<a href="javacript:void(0)" data-loading-text=""  onclick="var_status(' . $aRow['tblinventory.status'] . ',' . $aRow['tblinventory.id'] . '); return false">
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
        if ($aColumns[$i] == 'tblinventory.staff_id') {
        $_data=staff_profile_image($aRow['tblinventory.staff_id'], array('staff-profile-image-small mright5'), 'small', array(
                            'data-toggle' => 'tooltip',
                            'data-title' => ' Vào lúc: '._dt($aRow['date_create'])
                        )).get_staff_full_name($aRow['tblinventory.staff_id']).'<br>';
        }
        if ($aColumns[$i] == 'tblinventory.warehouseman_id') {
                $_data = '';
            if (has_permission('import', '', 'view') || has_permission('import', '', 'view_own')) {
                if ($aRow['tblinventory.status'] == 2 && !empty($aRow['tblinventory.staff_id'])) {
                
                        $button = _l('ch_warehouse_nd');
                        $title = _l('warehouseman_confirm');
                        $type = 'fa-square-o';
                        if($aRow['tblinventory.warehouseman_id'])
                        {   
                            $button = _l('ch_warehouse_d');
                            $title=_l('warehouseman_confirm_cancel');
                            $type='fa-check-square-o';
                        }
                        if(empty($aRow['tblinventory.warehouseman_id'])){
                            $_data='<span class="inline-block label label-warning" task-status-table="">Số lượng không đủ</span>';
                        if(test_quantity_tranfer($aRow['tblinventory.id']))
                        {
                        $_data = '<a href="" onclick="confirm_warehous('.$aRow['tblinventory.id'].','.$aRow['tblinventory.warehouseman_id'].');return false;" class=" btn btn-info btn-icon "  data-toggle="tooltip" data-loading-text="'._l('wait_text').'" data-original-title="'.$title.'"><i class="fa  '.$type.'"></i> '.$button.'</a>'.($aRow['tblinventory.warehouseman_id']?'<br>'._l('warehouseman').': <span style="color: red;">'.get_staff_full_name($aRow['tblinventory.warehouseman_id']).'</span>':'');
                        }
                        }else
                        {
                         $_data='<span class="inline-block label label-success" task-status-table="">Đã duyệt kho</span>';   
                        }
                }
            }
        }

        $row[] = $_data;
    }
    
    $_outputStatus = '<div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">'._l('action').'
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu right">';
        if($aRow['tblinventory.status'] == 1&&empty($ktr_exit))
        {
        $_outputStatus .= '<li><a href="'.admin_url('adjusted/create/'.$aRow['tblinventory.id']).'" target="_blank"><i class="lnr lnr-sync width-icon-actions"></i>'._l('Tạo điều chỉnh kho').'</a></li>';    
        }
        $_outputStatus .= '<li><a href="'.admin_url('inventory/print_pdf/'.$aRow['tblinventory.id']).'" target="_blank"><i class="fa fa-file-pdf-o width-icon-actions"></i>'._l('In phiếu kiểm kê').'</a></li>';
    $_outputStatus .= '</ul></div>';
    $row[] = $_outputStatus;
    $output['aaData'][] = $row;
}
