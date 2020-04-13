<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hasPermissionDelete = has_permission('pay_slip', '', 'delete');

$aColumns     = array(
    'tblpayment_order.id',
    '1',
    '2',
    'tblpayment_order.code',
    'tblclients.name_system',
    'tblpayment_order.total_voucher',
    'tblorders.code',
    'tblpayment_order.total_voucher_received',
    'tblcurrencies.name',
    'tblpayment_order.currency_vnd',
    'tblpayment_modes.name',
    'tblpayment_order.note',
    'tblpayment_order.account_information',
    'tblpayment_order.type_payment',
    'tblaccount_business.name',
    'tblpayment_order.date',
    'tblpayment_order.date_create',
    'tblpayment_order.staff_id',
    '12',
    'tblclients.code_system as code_system',
    '(SELECT concat(prefix_lead,code_lead,"-",code_type) FROM tblleads WHERE id = tblclients.leadid) as info_lead',
    'concat(prefix_client,code_client) as full_code_client1',
    'concat(prefix_client,code_client) as full_code_client',
);
$sIndexColumn = "id";
$sTable       = 'tblpayment_order';
$where        = array(
  
);
    if ($this->ci->input->post('filterStatus')) {
        if(is_numeric($this->ci->input->post('filterStatus'))) {
            if($this->ci->input->post('filterStatus') == 2) {
                array_push($where, 'AND tblpayment_order.id_order != 0');
                array_push($where, 'AND tblpayment_order.staff_cancel = 0');
            } else if($this->ci->input->post('filterStatus') == 1) {
                array_push($where, 'AND tblpayment_order.id_order = 0');
                array_push($where, 'AND tblpayment_order.staff_cancel = 0');
            }else if($this->ci->input->post('filterStatus') == 3) {
                array_push($where, 'AND tblpayment_order.staff_cancel = 1');
            }
        }
    }
$join         = array(
    'LEFT JOIN tblpayment_modes ON tblpayment_modes.id=tblpayment_order.payment_modes',
    'LEFT JOIN tblaccount_business ON tblaccount_business.id=tblpayment_order.account_business',
    'LEFT JOIN tblcurrencies ON tblcurrencies.id=tblpayment_order.currency',
    'LEFT JOIN tblorders ON tblorders.id=tblpayment_order.id_order',
    'LEFT JOIN tblclients ON tblclients.userid=tblpayment_order.client',

);
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array(
    'tblpayment_order.prefix','tblorders.prefix as prefix_order','tblpayment_order.id_order','tblpayment_order.client','tblpayment_order.currency','tblcurrencies.symbol','tblpayment_order.date_client','tblpayment_order.staff_client','tblpayment_order.date_order','tblpayment_order.staff_order','staff_cancel','date_cancel','status_cancel','note_cancel','zcode','tblclients.code_type as code_type_client','tblclients.datecreated'
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
        if ($aColumns[$i] == 'tblpayment_order.id') {
        $_data ='<div class="text-center">'.$j.'</div>';
        }
        if ($aColumns[$i] == '2') {
        $_data =text_align('Trong Thời Gian An Toàn','center');
        }
        if ($aColumns[$i] == '12') {
        $_data =process_payment_order_img($aRow['tblpayment_order.id']).process_payment_order($aRow['tblpayment_order.id'],$aRow['tblpayment_order.date'],$aRow['tblpayment_order.date_create']);
        }

    if(empty($aRow['zcode']))
    {

        $dateadded = $aRow['datecreated'];
        $dateadded = date("Y-m-d", strtotime("$dateadded +30 day"));
        if(strtotime($aRow['datecreated'])  >= strtotime($dateadded))
        {
            
            $full_code_client= '-NOT NEW';
        }
        else
        {
            
            $full_code_client= '-NEW';
        }
    }
    else
    {
        if($aRow['code_type_client'] == 'NEW' || $aRow['code_type_client'] == 'NEW') {
          
            $full_code_client= '-TN';
        }
        else if($aRow['code_type_client'] == 'TN')
        {
            $full_code_client= '-TN';
        }
        else
        {
            $full_code_client= '-'.$aRow['code_type_client'];
        }
    }
        if ($aColumns[$i] == 'concat(prefix_client,code_client) as full_code_client') {
            $_data='';
            if(!empty($aRow['full_code_client']))
            {
            $_data =$aRow['full_code_client'].$full_code_client;
            }
        }
        if ($aColumns[$i] == 'concat(prefix_client,code_client) as full_code_client1') {
            $_data='';
            if(!empty($aRow['full_code_client1']))
            {
            $_data =$aRow['full_code_client1'].$full_code_client;
            }
            
        }
        if ($aColumns[$i] == 'tblpayment_order.date_create') {
        $_data ='<div class="text-center">'._dt($aRow['tblpayment_order.date_create']).'</div>';
        }
        if ($aColumns[$i] == 'tblpayment_order.currency_vnd') {
            $_data ='';
            if($aRow['currency'] != 4)
            {
             $_data ='<div class="text-right">'.app_format_money($aRow['tblpayment_order.currency_vnd'],'VND').'</div>';
            }
        
        }
        if ($aColumns[$i] == 'tblorders.code') {
            if($aRow['status_cancel'] == 1)
            {
             $_data ='<a onclick="initOrders('.$aRow['id_order'].')">'.$aRow['prefix_order'].$aRow['tblorders.code'].'</a>';   
            }else
            {
        // $_data ='<a onclick="initOrders('.$aRow['id_order'].')">'.$aRow['prefix_order'].$aRow['tblorders.code'].'</a>';
            if(!empty($aRow['id_order']))
            {
                $_data ='<a onclick="initOrders('.$aRow['id_order'].')">'.$aRow['prefix_order'].$aRow['tblorders.code'].'</a>';
            }else{
            $_data =ch_EditColumSelectInput_1($aRow['id_order'], $aRow['tblpayment_order.id'], '', '<a onclick="initOrders('.$aRow['id_order'].')">'.$aRow['prefix_order'].$aRow['tblorders.code'].'</a>', admin_url('payment_order/SearchOrder'), admin_url('payment_order/updateOrder'),'class="formUpdateDataTable"','data_input',$aRow['client']);
            }
            }
        }
        if ($aColumns[$i] == 'tblclients.name_system') {
            if($aRow['status_cancel'] == 1)
            {
             $_data='<a class="pointer" href="'.admin_url('clients/client/'.$aRow['client']).'" target="_blank">'.$aRow['tblclients.name_system'].'</a>';   
            }else
            {
        if(!empty($aRow['id_order']))
        {    
        $_data='<a class="pointer" href="'.admin_url('clients/client/'.$aRow['client']).'" target="_blank">'.$aRow['tblclients.name_system'].'</a>';
        }else
        {
        $_data =ch_EditColumSelectInput($aRow['client'], $aRow['tblpayment_order.id'], 'tblclients.name_system', '<a class="pointer" href="'.admin_url('clients/client/'.$aRow['client']).'" target="_blank">'.$aRow['tblclients.name_system'].'</a>', admin_url('payment_order/SearchClient'), admin_url('payment_order/updateClient'),'class="formUpdateDataTable"');    
        }
        // 
        }
        }
        if ($aColumns[$i] == 'tblpayment_order.code') {
        $_data ='<div >'.$aRow['prefix'].$aRow['tblpayment_order.code'].'</div>';
            $pay_slip  = $aRow['prefix'].$aRow['tblpayment_order.code'];
            $pay_slip =  $pay_slip ;
            $pay_slip .= '<div class="row-options">';
            if($aRow['status_cancel'] != 1)
            {
            $pay_slip .= '<a onclick="payment_detail('.$aRow['tblpayment_order.id'].')" >' . _l('edit') . '</a>';
            }
            if ($hasPermissionDelete) {
                if(!empty($aRow['id_order']))
                {
                    $ktr_order = get_table_where('tblorders_step',array('id_orders'=>$aRow['id_order'],'order_by'=>3,'active'=>1),'','row');
                    if(empty($ktr_order)){
                    $pay_slip .= '| <a href="' . admin_url('payment_order/delete/' . $aRow['tblpayment_order.id']) . '" class="text-danger delete-remind">' . _l('delete') . '</a>';        
                    }
                }else
                {
                $pay_slip .= '| <a href="' . admin_url('payment_order/delete/' . $aRow['tblpayment_order.id']) . '" class="text-danger delete-remind">' . _l('delete') . '</a>';    
                }
            }   
            $pay_slip .= '</div>';
        $_data=$pay_slip;
        }
        if ($aColumns[$i] == 'tblpayment_order.type') {
            $_data = format_status_invoice($aRow['tblpayment_order.type']);
        }
        if ($aColumns[$i] == 'tblsuppliers.company') {
            $_data = '<a href="#" onclick="int_suppliers_view('.$aRow['id_supplierss'].'); return false;">' . $aRow['tblsuppliers.company'] . '</a>';
        }
        if ($aColumns[$i] == 'tblpayment_order.type_payment') {
        $_data='';
        if($aRow['tblpayment_order.type_payment'] == 0)
        {
           $_data='Nguồn doanh thu chính'; 
        }
        }
        if ($aColumns[$i] == 'tblpayment_order.total_voucher') {
        // $_data='<div class="text-right">'.number_format($aRow['tblpayment_order.total_voucher']).' VNĐ<div>';
        $_data='<div class="text-right">'.number_format($aRow['tblpayment_order.total_voucher']).'</div>'; 
        }
        if ($aColumns[$i] == 'tblpayment_order.total_voucher_received') {
            
            $_data='<div class="text-right">'.app_format_money($aRow['tblpayment_order.total_voucher_received'],$aRow['symbol']).'</div>';    
            
        }
        if ($aColumns[$i] == 'tblpayment_order.staff_id') {
        $_data=staff_profile_image($aRow['tblpayment_order.staff_id'], array('staff-profile-image-small mright5'), 'small', array(
                        )).get_staff_full_name($aRow['tblpayment_order.staff_id']).'<br>';;
        }
        if ($aColumns[$i] == '1') {
        if($aRow['status_cancel'] == 1)
        {

        $_data = '<span class=" menu-receipts inline-block label label-warning"><i class="fa fa-exclamation-circle" aria-hidden="true" data-toggle="tooltip" data-title="Lý do hủy" data-original-title="" title=""></i> Hủy';
         $_data.= '<div class="content-menu hide">';
                    $_data.= '
                        <div class="table_popover padding10">
                            <div class="table_popover_head text-center">
                                <div class="pull-left wap-head">
                                    <span class="text-center">'._l('Người hủy').'</span>
                                </div>
                                <div class="pull-left wap-head">
                                    <span class="text-center">'._l('Ngày hủy').'</span>
                                </div>
                                <div class="pull-left wap-head">
                                    <span class="text-center">'._l('Lý do hủy').'</span>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="table_popover_body text-center">';
                    $_data .='
                        <div class="wap-body">
                            <span class="text-center pull-left">'.get_staff_full_name($aRow['staff_cancel']).'</span>
                            <span class="text-center pull-left">'._dt($aRow['date_cancel']).'</span>
                            <span class="text-center pull-left">'.$aRow['note_cancel'].'</span>
                            <div class="clearfix"></div>
                        </div>';

                    $_data .= '</div><br>
                        </div></div></span>';
        }else
        {
        $_datass='Mới';
        $class='default';
        if(!empty($aRow['client']))
        {
        $_datass='Xác nhận khách hàng';
        $class='success';
        }
        if(!empty($aRow['id_order']))
        {
        $_datass='Xác nhận đơn hàng';
        $class='info';
        }
    $content = str_replace('"', '\'', render_textarea('note_cancel', 'ch_note_cancel_2')).'<div class=\'text-right\'><button onclick=\'save_contact_person('.$aRow['tblpayment_order.id'].')\' class=\'btn btn-danger po-delete-json\'>'._l('submit').'</button><a class=\'btn btn-default po-close\'>'._l('close').'</a></div>';
        $status_active ='<span class="inline-block label label-'.$class.'">
                    '.$_datass.'
                        <div class="dropdown inline-block mleft5 table-export-exclude">
                            <a href="#" style="font-size:14px;vertical-align:middle;" class="dropdown-toggle text-dark"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span data-toggle="tooltip" title="'.$_datass.'">
                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right"><li class="not-outside"><a data-container="body" data-html="true" data-toggle="popover" data-placement="left" data-content="'.$content.'" id-data="'.$aRow['tblpayment_order.id'].'">Hủy</a></li></ul>
                        </div>
                    </span>';
        $_data = $status_active;
        }

        }
        $row[] = $_data;
    }
    $_outputStatus = '<div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">'._l('action').'
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu right">';
    $_outputStatus .= '<li><a href="'.admin_url('pay_slip/print_pdf/'.$aRow['tblpayment_order.id']).'" target="_blank"><i class="fa fa-file-pdf-o width-icon-actions"></i>'._l('print_vote').'</a></li>';
    $_outputStatus .= '</ul></div>';
    $row[] = $_outputStatus;
    $output['aaData'][] = $row;
}
