<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hasPermissionDelete = has_permission('pay_slip', '', 'delete');

$aColumns     = array(
    'tbltransfer_warehouse.id',
    'tbltransfer_warehouse.code',
    'tbltransfer_warehouse.date',
    'tbltransfer_warehouse.staff_id',
    'tbltransfer_warehouse.status',
    'waid.name as waidname',
    'wato.name as watoname',
    'tbltransfer_warehouse.warehouseman_id',
    'tbltransfer_warehouse.note',
);
$sIndexColumn = "id";
$sTable       = 'tbltransfer_warehouse';
$where        = array(
  
);
    if ($this->ci->input->post('filterStatus')) {
        if(is_numeric($this->ci->input->post('filterStatus'))) {
            if($this->ci->input->post('filterStatus') == 1) {
                array_push($where, 'AND tbltransfer_warehouse.type = 1');
            } else if($this->ci->input->post('filterStatus') == 2) {
                array_push($where, 'AND tbltransfer_warehouse.type = 2');
            } else if($this->ci->input->post('filterStatus') == 3) {
                array_push($where, 'AND tbltransfer_warehouse.status = 1');
            } else if($this->ci->input->post('filterStatus') == 4) {
                array_push($where, 'AND tbltransfer_warehouse.status = 0');
            }
        }
    }
$join         = array(
    'LEFT JOIN tblwarehouse waid on waid.id = tbltransfer_warehouse.warehouse_id',
    'LEFT JOIN tblwarehouse wato on wato.id = tbltransfer_warehouse.warehouse_to'
);
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array('tbltransfer_warehouse.prefix','date_create','history_status'
));
$output       = $result['output'];
$rResult      = $result['rResult'];

$j=0;
foreach ($rResult as $aRow) {
    $row = array();
    $j++;
    for ($i = 0; $i < count($aColumns); $i++) {
        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }
        if ($aColumns[$i] == 'tbltransfer_warehouse.id') {
        $_data ='<div class="text-center">'.$j.'</div>';
        }
        if ($aColumns[$i] == 'tbltransfer_warehouse.code') {
        $_data ='<div class="text-center">'.$aRow['prefix'].'-'.$aRow['tbltransfer_warehouse.code'].'</div>';
            $transfer  = $aRow['prefix'].'-'.$aRow['tbltransfer_warehouse.code'];
            $transfer = '<a  >' . $transfer . '</a>';
            $transfer .= '<div class="row-options">';

            $transfer .= '<a href="#" onclick="view_transfer('.$aRow['tbltransfer_warehouse.id'].'); return false;" >' . _l('view') . '</a>';
            if(empty($aRow['tbltransfer_warehouse.warehouseman_id']))
            {
                $transfer .= ' | <a href="' . admin_url('transfer/detail/' . $aRow['tbltransfer_warehouse.id']) . '" >' . _l('edit') . '</a>';   
            }
            if ($hasPermissionDelete) {
                $transfer .= ' | <a href="' . admin_url('transfer/delete/' . $aRow['tbltransfer_warehouse.id']) . '" class="text-danger delete-remind">' . _l('delete') . '</a>';
            }   
            $transfer .= '</div>';
        $_data=$transfer;
        }
        if ($aColumns[$i] == 'tbltransfer_warehouse.date') {
            $_data = _d($aRow['tbltransfer_warehouse.date']);
        } 
        if ($aColumns[$i] == 'tbltransfer_warehouse.status') {
            if($aRow['tbltransfer_warehouse.status']==1)
                {
                    $type='warning';
                    $status=_l('dont_approve');
                }
                elseif($aRow['tbltransfer_warehouse.status']==2)
                {
                    $type='info';
                    $status=_l('ch_confirm_22');
                }
            $status='<span class="inline-block label label-'.$type.'" task-status-table="'.$aRow['tbltransfer_warehouse.status'].'">' . $status.'';
            if(has_permission('pay_slip', '', 'view') && has_permission('pay_slip', '', 'view_own'))
            {
                if($aRow['tbltransfer_warehouse.status']==1) {
                    $status .= '<a href="javacript:void(0)" data-loading-text=""  onclick="var_status(' . $aRow['tbltransfer_warehouse.status'] . ',' . $aRow['tbltransfer_warehouse.id'] . '); return false">
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
        if ($aColumns[$i] == 'tbltransfer_warehouse.staff_id') {
        $_data=staff_profile_image($aRow['tbltransfer_warehouse.staff_id'], array('staff-profile-image-small mright5'), 'small', array(
                            'data-toggle' => 'tooltip',
                            'data-title' => ' Vào lúc: '._dt($aRow['date_create'])
                        )).get_staff_full_name($aRow['tbltransfer_warehouse.staff_id']).'<br>';
        }
        if ($aColumns[$i] == 'tbltransfer_warehouse.warehouseman_id') {
                $_data = '';
            if (has_permission('import', '', 'view') || has_permission('import', '', 'view_own')) {
                if ($aRow['tbltransfer_warehouse.status'] == 2 && !empty($aRow['tbltransfer_warehouse.staff_id'])) {
                
                        $button = _l('ch_warehouse_nd');
                        $title = _l('warehouseman_confirm');
                        $type = 'fa-square-o';
                        if($aRow['tbltransfer_warehouse.warehouseman_id'])
                        {   
                            $button = _l('ch_warehouse_d');
                            $title=_l('warehouseman_confirm_cancel');
                            $type='fa-check-square-o';
                        }
                        if(empty($aRow['tbltransfer_warehouse.warehouseman_id'])){
                            $_data='<span class="inline-block label label-warning" task-status-table="">Số lượng không đủ</span>';
                        if(test_quantity_tranfer($aRow['tbltransfer_warehouse.id']))
                        {
                        $_data = '<a href="" onclick="confirm_warehous('.$aRow['tbltransfer_warehouse.id'].','.$aRow['tbltransfer_warehouse.warehouseman_id'].');return false;" class=" btn btn-info btn-icon "  data-toggle="tooltip" data-loading-text="'._l('wait_text').'" data-original-title="'.$title.'"><i class="fa  '.$type.'"></i> '.$button.'</a>'.($aRow['tbltransfer_warehouse.warehouseman_id']?'<br>'._l('warehouseman').': <span style="color: red;">'.get_staff_full_name($aRow['tbltransfer_warehouse.warehouseman_id']).'</span>':'');
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
    $_outputStatus .= '<li><a href="'.admin_url('pay_slip/print_pdf/'.$aRow['tbltransfer_warehouse.id']).'" target="_blank"><i class="fa fa-file-pdf-o width-icon-actions"></i>'._l('print_vote').'</a></li>';
    $_outputStatus .= '</ul></div>';
    $row[] = $_outputStatus;
    $output['aaData'][] = $row;
}
