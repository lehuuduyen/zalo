<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hasPermissionDelete = has_permission('pay_slip', '', 'delete');

$aColumns     = array(
    'tblpay_slip.id',
    'tblpay_slip.code',
    'tblpay_slip.type',
    'tblpay_slip.id_old',
    'tblpay_slip.day_vouchers',
    'tblsuppliers.company',
    'tblpay_slip.total',
    'tblpay_slip.payment',
    'tblpay_slip.status',
    'tblpay_slip.staff_id',
    'tblpayment_modes.name',
    'tblpay_slip.note',
);
$sIndexColumn = "id";
$sTable       = 'tblpay_slip';
$where        = array(
  
);
    if ($this->ci->input->post('filterStatus')) {
        if(is_numeric($this->ci->input->post('filterStatus'))) {
            if($this->ci->input->post('filterStatus') == 1) {
                array_push($where, 'AND tblpay_slip.type = 1');
            } else if($this->ci->input->post('filterStatus') == 2) {
                array_push($where, 'AND tblpay_slip.type = 2');
            } else if($this->ci->input->post('filterStatus') == 3) {
                array_push($where, 'AND tblpay_slip.status = 1');
            } else if($this->ci->input->post('filterStatus') == 4) {
                array_push($where, 'AND tblpay_slip.status = 0');
            }
        }
    }
$join         = array(
    'LEFT JOIN tblsuppliers ON tblsuppliers.id=tblpay_slip.id_supplierss',
    'LEFT JOIN tblpayment_modes ON tblpayment_modes.id=tblpay_slip.payment_mode',
);
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array(
    'tblpay_slip.prefix','date','history_status','tblpay_slip.id_supplierss'
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
        if ($aColumns[$i] == 'tblpay_slip.id') {
        $_data ='<div class="text-center">'.$j.'</div>';
        }
        if ($aColumns[$i] == 'tblpay_slip.code') {
        $_data ='<div class="text-center">'.$aRow['prefix'].'-'.$aRow['tblpay_slip.code'].'</div>';
            $pay_slip  = $aRow['prefix'].'-'.$aRow['tblpay_slip.code'];
            $pay_slip = '<a href="#" onclick="view_pay_slip('.$aRow['tblpay_slip.id'].'); return false;" >' . $pay_slip . '</a>';
            $pay_slip .= '<div class="row-options">';

            $pay_slip .= '<a href="#" onclick="view_pay_slip('.$aRow['tblpay_slip.id'].'); return false;" >' . _l('view') . '</a>';

            if ($hasPermissionDelete) {
                $pay_slip .= ' | <a href="' . admin_url('pay_slip/delete/' . $aRow['tblpay_slip.id']) . '" class="text-danger delete-remind">' . _l('delete') . '</a>';
            }   
            $pay_slip .= '</div>';
        $_data=$pay_slip;
        }
        if ($aColumns[$i] == 'tblpay_slip.type') {
            $_data = format_status_invoice($aRow['tblpay_slip.type']);
        }
        if ($aColumns[$i] == 'tblsuppliers.company') {
            $_data = '<a href="#" onclick="int_suppliers_view('.$aRow['id_supplierss'].'); return false;">' . $aRow['tblsuppliers.company'] . '</a>';
        }
        if ($aColumns[$i] == 'tblpay_slip.id_old') {
        $_data='';
        if($aRow['tblpay_slip.type'] == 1)
        {
        $id_invoice = explode(',', $aRow['tblpay_slip.id_old']);
        $count = count($id_invoice);
        if($count == 1)
        {
        $invoice = get_table_where('tblpurchase_invoice',array('id'=>$id_invoice[0]),'','row');
        $_data='<div class="text-center">'.$invoice->code_invoice.'</div>';
        }else
        {
            $_data = '<div class="dropdown" style="text-align: center;">
                        <button class="dropdown-toggle no_background color_warning" type="button" data-toggle="dropdown">'.format_status_number_invoices_ch($count).'
                        </button>';
            $__data='';
            foreach ($id_invoice as $key => $value) {
                $invoice = get_table_where('tblpurchase_invoice',array('id'=>$value),'','row');
                $__data.='<li><a>'.$invoice->code_invoice.'</a></li>';   
            }
                $_data .= '<ul style="top:100%;bottom:unset;left:unset;right: 12%" class="dropdown-menu ch_foso">'.$__data;
                $_data .= '</ul>';
                $_data .= '</div>';
        }
        }else
        {
            $id_import = explode(',', $aRow['tblpay_slip.id_old']);
            $count = count($id_import);
            if($count == 1)
            {
            $import = get_table_where('tblpurchase_order',array('id'=>$id_import[0]),'','row');
            $imports  = $import->prefix.'-'.$import->code;
            $_data='<a href="#" onclick="view_purchase_order('.$id_import[0].'); return false;" >' . $imports . '</a>';
            }else
            {
                $_data = '<div class="dropdown" style="text-align: center;">
                            <button class="dropdown-toggle no_background color_warning" type="button" data-toggle="dropdown">'.format_status_number_order_ch($count).'
                            </button>';
                $__data='';
                foreach ($id_import as $key => $value) {
                    $import = get_table_where('tblpurchase_order',array('id'=>$value),'','row');
                    $__data.='<li><a href="#" onclick="view_purchase_order('.$value.'); return false;" >' . $import->prefix.'-'.$import->code . '</a></li>';   
                }
                    $_data .= '<ul style="top:100%;bottom:unset;left:unset;right: 12%" class="dropdown-menu ch_foso">'.$__data;
                    $_data .= '</ul>';
                    $_data .= '</div>';
            }  
        }
        }
        if ($aColumns[$i] == 'tblpay_slip.day_vouchers') {
        $_data='<div class="text-center">'._d($aRow['tblpay_slip.day_vouchers']).'<div>';
        }
        if ($aColumns[$i] == 'tblpay_slip.total') {
        $_data='<div class="text-right">'.number_format($aRow['tblpay_slip.total']).'<div>';
        }
        if ($aColumns[$i] == 'tblpay_slip.payment') {
        $_data='<div class="text-right">'.number_format($aRow['tblpay_slip.payment']).'<div>';
        }
        if ($aColumns[$i] == 'tblpay_slip.status') {
            if($aRow['tblpay_slip.status']==0)
                {
                    $type='warning';
                    $status=_l('ch_status_pays_slip_no');
                }
                elseif($aRow['tblpay_slip.status']==1)
                {
                    $type='info';
                    $status=_l('ch_status_pays_slip');
                }
            $status='<span class="inline-block label label-'.$type.'" task-status-table="'.$aRow['tblpay_slip.status'].'">' . $status.'';
            if(has_permission('pay_slip', '', 'view') && has_permission('pay_slip', '', 'view_own'))
            {
                if($aRow['tblpay_slip.status']==0) {
                    $status .= '<a href="javacript:void(0)" data-loading-text=""  onclick="var_status(' . $aRow['tblpay_slip.status'] . ',' . $aRow['tblpay_slip.id'] . '); return false">
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
        if ($aColumns[$i] == 'tblpay_slip.staff_id') {
        $_data=staff_profile_image($aRow['tblpay_slip.staff_id'], array('staff-profile-image-small mright5'), 'small', array(
                            'data-toggle' => 'tooltip',
                            'data-title' => ' Vào lúc: '._dt($aRow['date'])
                        )).get_staff_full_name($aRow['tblpay_slip.staff_id']).'<br>';;
        }

        $row[] = $_data;
    }
    $_outputStatus = '<div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">'._l('action').'
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu right">';
    $_outputStatus .= '<li><a href="'.admin_url('pay_slip/print_pdf/'.$aRow['tblpay_slip.id']).'" target="_blank"><i class="fa fa-file-pdf-o width-icon-actions"></i>'._l('print_vote').'</a></li>';
    $_outputStatus .= '</ul></div>';
    $row[] = $_outputStatus;
    $output['aaData'][] = $row;
}
