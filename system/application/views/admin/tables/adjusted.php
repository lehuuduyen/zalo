<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hasPermissionDelete = has_permission('pay_slip', '', 'delete');

$aColumns     = array(
    'tbladjusted.id',
    'tbladjusted.code',
    'tbladjusted.date',
    'tbladjusted.id_inventory',
    'tbladjusted.type',
    'tbladjusted.staff_id',
    '1',
    'waid.name as waidname',
    'tbladjusted.note',
);
$sIndexColumn = "id";
$sTable       = 'tbladjusted';
$where        = array(
  
);
    if ($this->ci->input->post('filterStatus')) {
        if(is_numeric($this->ci->input->post('filterStatus'))) {
            if($this->ci->input->post('filterStatus') == 1) {
                array_push($where, 'AND tbladjusted.type = 1');
            } else if($this->ci->input->post('filterStatus') == 2) {
                array_push($where, 'AND tbladjusted.type = 2');
            }
        }
    }
$join         = array(
    'LEFT JOIN tblwarehouse waid on waid.id = tbladjusted.warehouse_id',
);
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array('tbladjusted.prefix','date_create','history_status','tbladjusted.not_new_by_staff',
));
$output       = $result['output'];
$rResult      = $result['rResult'];

$j=0;
foreach ($rResult as $aRow) {
    $row = array();
    $j++;
    for ($i = 0; $i < count($aColumns); $i++) {
        $not_new_by_staff = explode(',',$aRow['not_new_by_staff']);
        $test_quantity = get_table_where('tblwarehouse_product',array('import_id'=>$aRow['tbladjusted.id'],'quantity_export >'=>0,'type_export'=>3),'','row');
        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }
        if ($aColumns[$i] == 'tbladjusted.id') {
        if(!in_array(get_staff_user_id(), $not_new_by_staff)) {
            $_data = '<div class="text-center">'.$j.'</div> <span class="wap-new">new</span>';
            $row['DT_RowClass'] = 'alert-new';
        }
        else {
            $_data ='<div class="text-center">'.$j.'</div>';
        }
        }
        if ($aColumns[$i] == 'tbladjusted.code') {
        $_data ='<div class="text-center">'.$aRow['prefix'].$aRow['tbladjusted.code'].'</div>';
            $transfer  = $aRow['prefix'].$aRow['tbladjusted.code'];
            $transfer = '<a  >' . $transfer . '</a>';
            $transfer .= '<div class="row-options">';

            $transfer .= '<a href="#" onclick="view_adjusted('.$aRow['tbladjusted.id'].'); return false;" >' . _l('view') . '</a>';
           
            if ($hasPermissionDelete  && empty($test_quantity)) {
                $transfer .= ' | <a href="' . admin_url('adjusted/delete/' . $aRow['tbladjusted.id']) . '" class="text-danger delete-remind">' . _l('delete') . '</a>';
            }   
            $transfer .= '</div>';
        $_data=$transfer;
        }
        if ($aColumns[$i] == 'tbladjusted.date') {
            $_data = _d($aRow['tbladjusted.date']);
        } 
        if ($aColumns[$i] == 'tbladjusted.id_inventory') {
            $_data = '';
        } 
        if ($aColumns[$i] == 'tbladjusted.type') {
            if($aRow['tbladjusted.type'] == 1)
            {
            $_data ='<span class="inline-block label label-success">'._l('Tăng').'</span><br>';
            }else
            {
            $_data ='<span class="inline-block label label-info">'._l('Giảm').'</span><br>';
            }
        } 
        if ($aColumns[$i] == 'tbladjusted.status') {
            if($aRow['tbladjusted.status']==0)
                {
                    $type='warning';
                    $status=_l('dont_approve');
                }
                elseif($aRow['tbladjusted.status']==1)
                {
                    $type='info';
                    $status=_l('ch_confirm_22');
                }
            $status='<span class="inline-block label label-'.$type.'" task-status-table="'.$aRow['tbladjusted.status'].'">' . $status.'';
            if(has_permission('pay_slip', '', 'view') && has_permission('pay_slip', '', 'view_own'))
            {
                if($aRow['tbladjusted.status']==0) {
                    $status .= '<a href="javacript:void(0)" data-loading-text=""  onclick="var_status(' . $aRow['tbladjusted.status'] . ',' . $aRow['tbladjusted.id'] . '); return false">
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
        if ($aColumns[$i] == 'tbladjusted.staff_id') {
        $_data=staff_profile_image($aRow['tbladjusted.staff_id'], array('staff-profile-image-small mright5'), 'small', array(
                            'data-toggle' => 'tooltip',
                            'data-title' => ' Vào lúc: '._dt($aRow['date_create'])
                        )).get_staff_full_name($aRow['tbladjusted.staff_id']).'<br>';
        }
        if ($aColumns[$i] == '1') {
                $_data = '';
                if(!empty($test_quantity))
                {
                $button = _l('ch_exsit_export');
                $title=_l('ch_exsit_export');
                $_data = '<span class="inline-block label label-warning" task-status-table="">'.$button.'</span>';
                }else
                {
                $_data='<span class="inline-block label label-success" task-status-table="">Đã duyệt kho</span>';
                }
        }

        $row[] = $_data;
    }
    $_outputStatus = '<div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">'._l('action').'
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu h_right">';
        // if($aRow['tbladjusted.status'] == 1)
        // {
        // $_outputStatus .= '<li><a href="'.admin_url('adjusted/create/'.$aRow['tbladjusted.id']).'" target="_blank"><i class="lnr lnr-sync width-icon-actions"></i>'._l('Tạo điều chỉnh kho').'</a></li>';    
        // }
    $_outputStatus .= '<li><a href="'.admin_url('adjusted/print_pdf/'.$aRow['tbladjusted.id']).'" target="_blank"><i class="fa fa-file-pdf-o width-icon-actions"></i>'._l('In phiếu chuyển kho').'</a></li>';
    $_outputStatus .= '</ul></div>';
    $row[] = $_outputStatus;
    $output['aaData'][] = $row;
}
