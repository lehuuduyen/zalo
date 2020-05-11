<?php

defined('BASEPATH') or exit('No direct script access allowed');

$procedure = get_table_where(db_prefix().'procedure_client', [
    'type' => 'orders'
] ,'', 'row');

if(!empty($procedure))
{
    $this->ci->db->select(db_prefix().'procedure_client_detail.*');
    $this->ci->db->where('id_detail', $procedure->id);
    $this->ci->db->order_by('orders', 'ASC');
    $list_procedure_detail = $this->ci->db->get(db_prefix().'procedure_client_detail')->result_array();
}

$aColumns = [
    '1',
    'tblorders.code',
    'tblorders.client',
    'tblorders.date',
    'assigned', // nhân viên phụ trách
    'tblorders.date_create', // Ngày tạo
    'tblorders.create_by', // nhân viên tạo
    'grand_total', //  tổng giá trị đơn hàng
    'total_item', // tổng số sản phẩm
    'total_cost_trans', // tổng chi phí vận chuyển
    'guest_giving', // khách hàng tặng thêm
    '12',
    '13',
    '14',
    '15',
    '16',
    '17',
    '18',
    '19',
    '20',
    '21',
    '22',
    '23',
    '24',
    '25',
    '26',
    '27',
    '28',
    '29',
    '30',
    '31',
    '32',
    '33',
    '34',

];
$sIndexColumn = 'id';
$sTable       = db_prefix().'orders';
$where        = [];

$filter = [];

if(is_numeric($this->ci->input->post('filterStatus')))
{
    $where[] = 'AND '.db_prefix().'orders.status = "'.$this->ci->input->post('filterStatus').'"';
}

if(!empty($draft))
{
	$where[] = 'AND tblorders.draft = "1"';
}
else
{
	$where[] = 'AND (tblorders.draft is null or tblorders.draft = 0)';
}




$join[] = 'LEFT JOIN tblstaff cby on cby.staffid = tblorders.create_by';
$join[] = 'LEFT JOIN tblstaff ss on ss.staffid = tblorders.assigned';
$join[] = 'LEFT JOIN tblclients c on c.userid = tblorders.client';
$join[] = 'LEFT JOIN tblshipping_client on tblshipping_client.id = tblorders.shipping';
$join[] = 'LEFT JOIN tbladvisory_lead on tbladvisory_lead.id = tblorders.advisory_lead_id';
$join[] = 'LEFT JOIN tblcurrencies on tblcurrencies.id = tblorders.currencies_id';
$join[] = 'LEFT JOIN tbladvisory_apply on tbladvisory_apply.id = tblorders.advisory_apply_id';
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where,[
    'tblorders.id as id',
    'cby.firstname as cbyfirstname',
    'cby.lastname as cbylastname',
    'ss.firstname as ssfirstname',
    'ss.lastname as sslastname',
    'tblorders.date',
    'c.code_system as code_system',
    'c.prefix_client as prefix_client',
    'c.code_client as code_client',
    'c.code_type as code_type',
    'c.leadid as leadid',
    'c.company as company',
    'c.name_system as name_system',
    'c.zcode as zcode',
    'tblorders.status',
    'tblshipping_client.name as name_shipping',
    'tblshipping_client.phone as phone_shipping',
    'tblshipping_client.address as address_ship',
    'tblshipping_client.code_zip as code_zip_ship',
    'tblshipping_client.address_primary as address_primary_ship',
    'CONCAT(COALESCE(tbladvisory_lead.prefix), COALESCE(tbladvisory_lead.code), "-", COALESCE(tbladvisory_lead.type_code)) as full_code_advisory',
    'tblcurrencies.name as name_currencies',
    'tblcurrencies.amount_to_vnd as amount_to_vnd',
    'tbladvisory_apply.name as name_advisory_apply',
    'concat(COALESCE(c.prefix_client), COALESCE(c.code_client)) as fullcode_client',
	'concat(COALESCE(tblorders.prefix), COALESCE(tblorders.code)) as fullcode',
	'(select name from tblpayment_modes where tblpayment_modes.id = tblorders.mode_payment) as name_pay',
	'draft',
	'type_object_draft',
	'id_object_draft',
	'tblorders.shipping'
]);
// var_dump($result);die;
$output  = $result['output'];
$rResult = $result['rResult'];
$currentPage=$this->_instance->input->post('start');
$currentall=$output['iTotalRecords'];
$count_success = 0;
$all_count = count($rResult);
foreach ($rResult as $r => $aRow) {
    $row = [];
	$options = '';
	if(!empty($aRow['draft']))
	{
		$options .= '<span class="wap-new">'._l('cong_draft').'</span>';
		if($aRow['type_object_draft'] == 'lead')
		{
			$lead = get_table_where('tblleads', ['id' => $aRow['id_object_draft']], '', 'row');
			if(!empty($lead))
			{
				$aRow['name_system'] = '<p>'.$lead->zcode.'</p></div><span class="wap-new">'._l('cong_lead').'</span>';
				$aRow['zcode'] = $lead->zcode;

				$shipping_lead = get_table_where('tblshipping_client', ['id' => $aRow['shipping']], '', 'row');
				if(!empty($shipping_lead))
				{
					$aRow['name_shipping'] = $shipping_lead->name;
					$aRow['phone_shipping'] = $shipping_lead->phone;
					$aRow['address_ship'] = $shipping_lead->address;
				}
			}
		}
	}
    $options .= '<div class="row-options">';
    $options .= '    <a onclick="initOrders('.$aRow['id'].')">'._l('view').'</a> |';
    $options .= '    <a href="'.admin_url('orders/detail/'.$aRow['id']).'" class="">'._l('edit').'</a> |';
    if($aRow['status'] > 0 || $aRow['status'] == -1)
    {
        $options .= '    <a class="text-warning pointer" onclick="restore_step('.$aRow['id'].')">'._l('cong_restore_step').'</a>|</br>';
    }
    if($aRow['status'] == -2 || $aRow['status'] == -3)
    {
        $options .= '    <a class="text-warning pointer"  onclick="restore_orders('.$aRow['id'].', '.$aRow['status'].')">'._l('cong_restore_orders').'</a>';
    }

    $options .= '    <a class="text-danger pointer" onclick="DeleteOrders('.$aRow['id'].')">'._l('delete').'</a>';
	if($aRow['draft'])
	{
		$options .= '<br/>|<a class="text-warning" onclick="moved_orders_primary('.$aRow['id'].')">
                        <b>'._l('cong_war_to_orders_primary').'</b>
                    </a>';
	}
    $options .= '</div>';



    $String_status = "";
    $Alert_status = "";
    if($aRow['status'] == '-3')
    {
        $String_status = '<b class="text-danger">'._l('cong_orders_cancel').'</b>';
        $Alert_status = 'bg-danger';
    }
    else if($aRow['status'] == '-2')
    {
        $String_status = '<b class="text-danger">'._l('cong_orders_delay').'</b>';
        $Alert_status = 'bg-warning';
    }
    else if($aRow['status'] == '-1')
    {
        $String_status = '<b class="text-danger">'._l('cong_orders_success').'</b>';
        $Alert_status = 'bg-dd';
    }

    $row['DT_RowClass'] = $Alert_status;

    $row[] = ($currentall+1) - ($currentPage + $r + 1);

    $row[] = '<p class="one-control pointer"><a href="'.admin_url('orders/detail/'.$aRow['id']).'">'.$aRow['fullcode'].(!empty($String_status) ? '<br/>('.$String_status.')' : '').'</a></p>'.$options;
    $row[] = '<a class="pointer" href="'.admin_url('clients/client/'.$aRow['tblorders.client']).'" target="_blank">'.$aRow['name_system'].(!empty($aRow['fullcode_client']) ? '<br/>('.$aRow['fullcode_client'].')' : '' ).'</a>';
    $row[] = $aRow['zcode'];
    $row[] = '<p class="text-center">'._d($aRow['tblorders.date']).'</p>';

    // Nhân viên hoàn thành chăm sóc
    $profile_assigned = "";
    if(!empty($aRow['assigned']))
    {
        $profile_assigned = $aRow['sslastname'] . ' ' . $aRow['ssfirstname'];
        $profile_assigned = '<p class="text-center"><a data-toggle="tooltip" data-title="' . $profile_assigned . '" href="' . admin_url('profile/' . $aRow['assigned']) . '">' . staff_profile_image($aRow['assigned'], [
                'staff-profile-image-small',
            ]) . '</a></p>';
        $profile_assigned .= '<p class="text-center"><a href="'.admin_url('staff/member/'.$aRow['assigned']).'" target="_blank">' . $aRow['sslastname'] . ' ' . $aRow['ssfirstname'] . '</a></p>';
    }
    $row[] = $profile_assigned;

    $this->ci->db->select('max(order_by) as max_order, id_orders_item, id_procedure');
    $this->ci->db->where('id_orders', $aRow['id']);
    $this->ci->db->where('active', 1);
    $this->ci->db->group_by('id_orders_item');
    $orders_step = $this->ci->db->get('tblorders_step')->result_array();
    $orders_min = 0;
    $orders_procedure = 0;
    if(!empty($orders_step))
    {
        $orders_min = $orders_step[0]['max_order'];
        foreach($orders_step as $Korder => $vOrder)
        {
            if($vOrder['max_order'] < $orders_min)
            {
                $orders_min = $vOrder['max_order'];
                $orders_procedure = $vOrder['id_procedure'];
            }
        }
    }

    if(!empty($procedure))
    {
        $this->ci->db->select('tblprocedure_client_detail.*');
        $this->ci->db->where('id_detail', $procedure->id);
        $this->ci->db->order_by('orders', 'ASC');
        $procedure_detail = $this->ci->db->get(db_prefix().'procedure_client_detail')->result_array();
    }

    $Row_procedure = '   <ul class="progressbar" style="display: flex;flex-direction: row;justify-content: center;">';
    foreach($procedure_detail as $kDetail => $vDetail)
    {
        //(tồn tại status trong bảng step hoặc (dữ liệu chưa có trạng thái) hoặc tồn tại trạng thái trước đó) và khác đơn hàng tạm dừng và đơn hàng bị hủy hoặc kết thúc
        $active = (!empty($orders_min) && $vDetail['orders'] <= $orders_min);
        $Row_procedure .= '<li '.($active ? 'class="active"' : '').'>';
        $Row_procedure .= '     <p class="pointer li_pad10"> '.$vDetail['name'].' </p>';
        $Row_procedure .= '</li>';

    }


    $Row_procedure .= '<li class="'.(!empty($aRow['status']) && $aRow['status'] == -2 ? 'active' : '').' initli">';
    $Row_procedure .= '     <p class="pointer li_pad10 '. ( ($aRow['status'] != 2 && $aRow['status'] != -3) ? 'status_orders' : ($aRow['status'] != -3 ? 'CRwa' : '') ) .'" '.($aRow['status'] != -2 ? ('status-procedure="-2" id-data = "'.$aRow['id'].'"') : '').'> '._l('cong_orders_delay').'</p>';
    $Row_procedure .= '</li>';



    $Row_procedure .= '<li class="'.(!empty($aRow['status']) && $aRow['status'] == -3 ? 'active' : '').' initli">';
    $Row_procedure .= '     <p class="pointer li_pad10 '. ($aRow['status'] != -3 ? 'status_orders' : 'CRwa') .'" '.($aRow['status'] != -3 ? ('status-procedure="-3" id-data = "'.$aRow['id'].'"') : '').'> '._l('cong_orders_cancel').' </p>';
    $Row_procedure .= '</li>';


    $Row_procedure .= '<div class="clearfix"></div>';
    $Row_procedure .= '</ul>';



    $row[] = $Row_procedure;

    $row[] = '<p class="text-center">'._dt($aRow['tblorders.date_create']).'</p>';
    $fullname_CREATE = $aRow['cbylastname'] . ' ' . $aRow['cbyfirstname'];
    $profile_CREATE = '<p class="text-center"><a data-toggle="tooltip" data-title="' . $fullname_CREATE . '" href="' . admin_url('profile/' . $aRow['tblorders.create_by']) . '">' . staff_profile_image($aRow['tblorders.create_by'], [
            'staff-profile-image-small',
        ]) . '</a></p>';
    $profile_CREATE .= '<span class="text-center"><a  href="'.admin_url('staff/member/'.$aRow['assigned']).'" target="_blank">' . $aRow['cbylastname'] . ' ' . $aRow['cbyfirstname'] . '</a></span>';
    $row[] = $profile_CREATE;
    $row[] = '<p class="text-right">'.number_format($aRow['grand_total']).'</p>';
    $row[] = '<p class="text-right">'.number_format(0).'</p>'; //tổng giá trị phiếu thu
	$sumPayMent = SumPaymentOrder($aRow['id']);
    $row[] = '<p class="text-right">'.number_format($aRow['grand_total'] - $sumPayMent).'</p>'; //khoảng còn lại phải thu của khách hàng
    $row[] = '<p class="text-right">'.number_format($aRow['total_item']).'</p>';
    $row[] = '<p class="text-right">'.number_format($aRow['total_cost_trans']).'</p>';
    $row[] = '<p class="text-right">'.number_format($aRow['guest_giving']).'</p>';

    $row[] = !empty($aRow['name_pay']) ? $aRow['name_pay'] : '-' ; //hình thức thanh toán
    $row[] = $aRow['name_shipping'];
    $row[] = $aRow['phone_shipping'];
    $row[] = $aRow['address_ship'];
    $payment = get_table_where('tblpayment_order', ['id_order' => $aRow['id']]);
    if(!empty($payment))
    {
    $table = '<span class="inline-block label label-warning pointer menu-receipts">'._l('ch_payment_order').': '.count($payment); //số lượng phiếu thu
    $table .= '<div class="content-menu hide">';
    $table .= '
        <div class="table_popover padding10">
            <div class="table_popover_head text-center">
                <div class="pull-left wap-head">
                    <span class="text-center">'._l('ch_code_number').'</span>
                </div>
                <div class="pull-left wap-head">
                    <span class="text-center">'._l('date').'</span>
                </div>
                <div class="pull-left wap-head">
                    <span class="text-center">'._l('als_staff').'</span>
                </div>
                <div class="pull-left wap-head">
                    <span class="text-center">'._l('amount').'</span>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="table_popover_body text-center">';
            $total = 0;
            foreach ($payment as $key => $value) {
            $table .='
                <div class="wap-body view_payment" data-id="'.$value['id'].'">
                    <span class="text-center pull-left">'.$value['prefix'].$value['code'].'</span>
                    <span class="text-center pull-left">'._dt($value['date']).'</span>
                    <span class="text-center pull-left">'.get_staff_full_name($value['staff_id']).'</span>
                    <span class="text-right pull-left">'.number_format($value['total_voucher']).'</span>
                    <div class="clearfix"></div>
                </div>';
            $total+=$value['total_voucher'];    
            }
            $table .='
                <div class="wap-body" style="font-weight:bold;color:red">
                    <span class="text-center pull-left">Tổng tiền</span>
                    <span class="text-center pull-left"></span>
                    <span class="text-center pull-left"></span>
                    <span class="text-right pull-right">'.number_format($total).'</span>
                    <div class="clearfix"></div>
                </div>';
    $table .= '</div><br>
        </div></div></span>';
    }
    else
    {
        $table = '<span class="inline-block label label-warning pointer">'._l('ch_not_payment_order').'</span>';
    }

    $row[] = $table;
    $row[] = $aRow['full_code_advisory']; //phiếu tư vấn khách hàng
    $row[] = $aRow['code_system'];
    $dataLead = get_table_where('tblleads',array('id'=>$aRow['leadid']),'','row');
    if(!empty($dataLead)) {
        $row[] = $dataLead->prefix_lead.$dataLead->code_lead.'-'.$dataLead->zcode.'-'.$dataLead->code_type;
    }
    else {
        $row[] = '';
    }
    $row[] = $aRow['prefix_client'].$aRow['code_client'].'-'.$aRow['zcode'].'-'.$aRow['code_type'];
    $row[] = $aRow['prefix_client'].$aRow['code_client'].'-'.$aRow['zcode'].'-'.$aRow['code_type'];
    $row[] = $aRow['name_currencies']; //đơn vị tiền
    $row[] = $aRow['name_advisory_apply']; //giá thỉnh áp dụng
    $row[] = number_format($aRow['amount_to_vnd']);
    $row[] = app_format_money($aRow['total_cost_trans'] / $aRow['amount_to_vnd'], $aRow['name_currencies']);
    $row[] = app_format_money($aRow['guest_giving'] / $aRow['amount_to_vnd'], $aRow['name_currencies']);
    $row[] = app_format_money($aRow['grand_total'] / $aRow['amount_to_vnd'], $aRow['name_currencies']);
    $row[] = app_format_money(0 / $aRow['amount_to_vnd'], $aRow['name_currencies']); //tổng giá trị phiếu thu
    $row[] = app_format_money(($aRow['grand_total'] - $sumPayMent) / $aRow['amount_to_vnd'], $aRow['name_currencies']); //khoảng còn lại phải thu của khách hàng

    //tác vụ
    $_outputStatus = '<div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">'._l('action').'
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu h_right">';
    $_outputStatus .= '<li><a onclick="payment('.$aRow['id'].')"><i class="fa fa-file width-icon-actions"></i>'._l('ch_chose_payment').'</a></li>';
    $_outputStatus .= '</ul></div>';
    $row[] = $_outputStatus;

    $output['aaData'][] = $row;
}
//Đếm số lượng theo trạng thái
$output['total'] = [];
foreach($list_procedure_detail as $kDetail => $vDetail){
    $this->ci->db->where('status', $vDetail['id']);
    $output['total'][$vDetail['id']] = $this->ci->db->get('tblorders')->num_rows();
}
$this->ci->db->where('status', '-1');
$output['total']['-1'] = $this->ci->db->get(db_prefix().'orders')->num_rows();

$this->ci->db->where('status', '-2');
$output['total']['-2'] = $this->ci->db->get(db_prefix().'orders')->num_rows();

$this->ci->db->where('status', '-3');
$output['total']['-3'] = $this->ci->db->get(db_prefix().'orders')->num_rows();

$this->ci->db->where('status', '0');
$output['total']['0'] = $this->ci->db->get(db_prefix().'orders')->num_rows();
