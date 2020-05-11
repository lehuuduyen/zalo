<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'tblvouchers_coupon.id',
    'tblvouchers_coupon.code_vouchers',
    'tblvouchers_coupon.date_vouchers',
    'tblclients.company',
    'tblvouchers_coupon.arr_code_orders',
    'tblvouchers_coupon.staff',
    'tblpayment_modes.name',
    'tblvouchers_coupon.total',
    'tblvouchers_coupon.payment',
    'tblvouchers_coupon.status',
    'tblvouchers_coupon.note',
    '11'
];

$sIndexColumn = 'id';
$sTable       = 'tblvouchers_coupon';

$join         = array(
    'LEFT JOIN tblclients on tblclients.userid = tblvouchers_coupon.customer',
    'LEFT JOIN tblpayment_modes on tblpayment_modes.id = tblvouchers_coupon.payment_mode',
);
$where = array();
if($this->ci->input->post('filterStatus')) {
    if(is_numeric($this->ci->input->post('filterStatus'))) {
        if($this->ci->input->post('filterStatus') == 666) {
            array_push($where, 'AND tblvouchers_coupon.status = 0');
        }
        else {
            array_push($where, 'AND tblvouchers_coupon.status = '.$this->ci->input->post('filterStatus'));
        }
    }
}
    if(has_permission('vouchers_coupon','','view_own')&&!is_admin())
    {
         array_push($where, 'AND  tblvouchers_coupon.staff = '.get_staff_user_id());
    }
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, array(
    'tblvouchers_coupon.history_status'
));
$output  = $result['output'];
$rResult = $result['rResult'];
$currentPage=$this->_instance->input->post('start');
$currentall=$output['iTotalRecords'];
$footer_data = array(
    'total' => 0,
    'pay' => 0,
);
foreach ($rResult as $r => $aRow) {
    $row = [];
    for ($i = 0 ; $i < count($aColumns) ; $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'tblvouchers_coupon.id') {
            $_data = ($currentall+1)-($currentPage+$r+1);
        }
        else if ($aColumns[$i] == 'tblvouchers_coupon.code_vouchers') {
            $_data = '<a onclick="view_vouchers_coupon('.$aRow['tblvouchers_coupon.id'].'); return false;">'.$aRow['tblvouchers_coupon.code_vouchers'].'</a>';
            $_data .= '<div class="row-options">';
            $_data .= '<a onclick="view_vouchers_coupon('.$aRow['tblvouchers_coupon.id'].'); return false;">'._l('view').'</a>';
            if(has_permission('vouchers_coupon', '', 'delete')) {
                $_data .= ' | <a class="text-danger" onclick="delete_vouchers_coupon('.$aRow['tblvouchers_coupon.id'].'); return false;">'._l('delete').'</a>';
            }
            $_data .= '</div>';
        }
        else if ($aColumns[$i] == 'tblvouchers_coupon.date_vouchers') {
            $_data = _d($aRow['tblvouchers_coupon.date_vouchers']);
        }
        else if ($aColumns[$i] == 'tblvouchers_coupon.arr_code_orders') {
            $_data = '<span class="inline-block label label-warning">0 '._l('vote').'</span>';
            if($aRow['tblvouchers_coupon.arr_code_orders'] != '') {
                $_data = '<span class="inline-block label label-warning popover-menu" style="cursor: pointer;">'.count(explode(',', $aRow['tblvouchers_coupon.arr_code_orders'])).' '._l('vote');
                $_data .= '<div class="content_code_orders hide">';
                foreach (explode(',', $aRow['tblvouchers_coupon.arr_code_orders']) as $key => $value) {
                    $id_order = explode('|',$value);
                    $get_detail = get_table_where('tbl_orders',array('id'=>$id_order[0]),'','row');
                    $_data .= '<div>'.$get_detail->reference_no.' <span style="font-size: 10px;">('.number_format($id_order[1]).')</span></div>';
                }
                $_data .= '</div>';
                $_data .= '</span>';
            }
        }
        else if ($aColumns[$i] == 'tblvouchers_coupon.staff') {
            $_data = staff_profile_image($aRow['tblvouchers_coupon.staff'], array('staff-profile-image-small mright5'), 'small').get_staff_full_name($aRow['tblvouchers_coupon.staff']).'<br>';
        }
        else if ($aColumns[$i] == 'tblvouchers_coupon.total') {
            $footer_data['total']+=$aRow['tblvouchers_coupon.total'];
            $_data = number_format($aRow['tblvouchers_coupon.total']);
        }
        else if ($aColumns[$i] == 'tblvouchers_coupon.payment') {
            $footer_data['pay']+=$aRow['tblvouchers_coupon.payment'];
            $_data = number_format($aRow['tblvouchers_coupon.payment']);
        }
        else if ($aColumns[$i] == 'tblvouchers_coupon.status') {
            if($aRow['tblvouchers_coupon.status'] == 0) {
                $type = 'warning';
                $status = _l('coupon_status_dont');
            }
            else if ($aRow['tblvouchers_coupon.status'] == 1) {
                $type = 'info';
                $status = _l('coupon_status_do');
            }
            $status = '<span class="inline-block label label-'.$type.'" task-status-table="'.$aRow['tblvouchers_coupon.status'].'">' . $status.'';
            if(has_permission('vouchers_coupon', '', 'approve'))
            {
                if($aRow['tblvouchers_coupon.status'] == 0) {
                    $status .= '<a onclick="var_status(' . $aRow['tblvouchers_coupon.status'] . ',' . $aRow['tblvouchers_coupon.id'] . '); return false;">
                    <i class="fa fa-check task-icon task-unfinished-icon" data-toggle="tooltip"></i>';
                }
                else {
                    $status .= '<a><i class="fa fa-check task-icon task-finished-icon" data-toggle="tooltip"></i>';
                }
            }
            $status .= '</a></span><br>';
            $__data='';
            $data=explode('|',$aRow['history_status']);
            if(is_numeric($data[0])) {
                $__data.=staff_profile_image($data[0], array('staff-profile-image-small mright5'), 'small', array(
                        'data-toggle' => 'tooltip',
                        'data-title' => ' Vào lúc: '._dt($data[1])
                    )).get_staff_full_name($data[0]).'<br>';
            }
            $_data = $status.$__data;
        }
        else if ($aColumns[$i] == '11') {
            $_outputStatus = '<div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">'._l('action').'
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu h_right">';
            $_outputStatus .= '<li><a onclick="view_vouchers_coupon('.$aRow['tblvouchers_coupon.id'].'); return false;"><i class="fa fa-eye"></i> '._l('view').'</a></li>';
            $_outputStatus .= '<li><a href="'.admin_url('vouchers_coupon/print_pdf_2/'.$aRow['tblvouchers_coupon.id']).'" target="_blank"><i class="fa fa-file-pdf-o"></i> '._l('print_vote').'</a></li>';
            if(has_permission('vouchers_coupon', '', 'delete')) {
                $_outputStatus .= '<li><a onclick="delete_vouchers_coupon('.$aRow['tblvouchers_coupon.id'].'); return false;"><i class="fa fa-trash"></i> '._l('delete').'</a></li>';
            }
            $_outputStatus .= '</ul></div>';
            $_data = $_outputStatus;
        }
        $row[] = $_data;
    }
    $output['aaData'][] = $row;
}
foreach ($footer_data as $key => $total) {
    $footer_data[$key] = number_format($total);
}
$output['sums'] = $footer_data;