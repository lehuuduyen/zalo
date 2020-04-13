<?php

defined('BASEPATH') or exit('No direct script access allowed');

$permissions_manage_orders = has_permission('orders', '', 'permissions_manage_orders'); // quản lý đơn hàng
$permissions_view = has_permission('orders', '', 'view'); // quản lý đơn hàng
$permissions_view_own = has_permission('orders', '', 'view_own'); // quản lý đơn hàng

$aColumns = [
    'tblorders.code',
];
$sIndexColumn = 'id';
$sTable       = 'tblorders';
$where        = [];


$join[] = 'LEFT JOIN tblorders_items on tblorders_items.id_orders = tblorders.id';

$join[] = 'LEFT JOIN tbladvisory_lead on tbladvisory_lead.id = tblorders.advisory_lead_id';
$join[] = 'LEFT JOIN tblcurrencies on tblcurrencies.id = tblorders.currencies_id';
$join[] = 'LEFT JOIN tbladvisory_apply on tbladvisory_apply.id = tblorders.advisory_apply_id';

$where[] = 'AND (tblorders.client = '.$id_client. ')';


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
	'tblorders.id as id',
    'concat(COALESCE(tblorders.prefix), COALESCE(tblorders.code)) as full_code',
    'CONCAT(tbladvisory_lead.prefix,tbladvisory_lead.code,"-",tbladvisory_lead.type_code) as full_code_advisory',
    'tblorders.date',
    'tblorders.date_create',
    'tblorders.create_by',
    'tblorders.shipping',
    'tblorders.status as status_order',
    '(tblorders.grand_total - tblorders.total_cancel) as grand_total',
    'tblorders.total_discount_percent',
    'tblorders.total_discount_money',
    'tblorders.total_quantity',
    'tblorders.guest_giving',
    'tblorders.total_item',
    'tblorders.total',
    'tblorders.address_shipping as address_shipping_orders',
    'tblcurrencies.name as name_currencies',
    'tblorders.exchange_rate as amount_to_vnd',
    'tbladvisory_apply.name as name_advisory_apply',
    '(tblorders.grand_total_international - (tblorders.total_cancel / tblorders.exchange_rate)) as grand_total_international',
    'tblorders.total_cost_trans_international',
    'tblorders.total_cost_trans',
    'tblorders.date_want_to_receive',
	'draft',
	'type_object_draft',
	'id_object_draft',
	'orders_break',
	'code_items',
	'tblorders.status_pending'
],'group by tblorders.id');



$output  = $result['output'];
$rResult = $result['rResult'];
$currentPage = $this->_instance->input->post('start');
$currentall = $output['iTotalRecords'];
$count_success = 0;
$all_count = count($rResult);



$RowArray = [

	'ma_don_hang' => 0,
	'tinh_trang_don_hang' => 1,
	'muc_do_uu_tien' => 2,
	'hinh_anh' => 3,
	'ma_san_pham_trong_don_hang' => 4,
	'ten_san_pham' => 5,
	'so_luong' => 6,
	'thanh_tien' => 7,
	'tong_gia_tri_phieu_thu' => 8,
	'khoan_con_lai_phai_thu_cua_khach_hang' => 9,
	'khoan_con_lai_phai_thu_cua_khach_hang_ngoai_te' => 10,
	'mua_cho' => 11,
	'ngay_tao_don_hang' => 12,
	'quy_trinh' => 13,
];



foreach ($rResult as $r => $aRow) {
    if($aRow['status_order'] == '51') {
        $aRow['grand_total'] = 0;
        $aRow['grand_total_international'] = 0;
        $aRow['total_cost_trans'] = 0;
        $aRow['total_cost_trans_international'] = 0;
        $aRow['guest_giving'] = 0;
    }

	$allRow = [];
	for($i = 0 ; $i < 14 ; $i++) {
		$allRow[] = '';
	}
	$allRow[0] = '<b class="text-danger">Tổng:</b>';
    $rowNum = ($currentall) - ($currentPage + $r + 1);

    $this->ci->db->select('tblpayment_order. *, tblpayment_modes.name as name_pay_moders')->where('id_order', $aRow['id'])->where('status_cancel', 0)->join('tblpayment_modes', 'tblpayment_modes.id = tblpayment_order.payment_modes', 'left');
    $payment = $this->ci->db->get('tblpayment_order')->result_array();
    $total_payment = 0;
    $countPayment = count($payment);

    $this->ci->db->select('
        IF(tblorders_items.type_items = "items", tblitems.avatar, CONCAT("uploads/products/", "", tbl_products.images, "")) as avatar,
        tblorders_items.*
    ');
    $this->ci->db->where('id_orders', $aRow['id'])->join('tbl_products', 'tbl_products.id = tblorders_items.id_product and tblorders_items.type_items = "products"', 'left')->join('tblitems', 'tblitems.id = tblorders_items.id_product and tblorders_items.type_items = "items"', 'left');
    $orders_items = $this->ci->db->get('tblorders_items')->result_array();

    if(empty($orders_items)) {
    	continue;
    }
	$total_discount_percent = 0;
	$total = 0;
    foreach($orders_items as $key => $vItems) {
        $row = [];
        $htmlLi = '';
        $orders_step = get_table_where('tblorders_step', ['id_orders_item' => $vItems['id']], 'order_by ASC');
        $name_hau = _l('pick_status');
        $name_color = '#000';
        $StepActive = [];
        $Row_procedure_item = '';
        $Row_procedure_item_img = '';
	    $table = '';

	    $date_create = to_sql_date(_dC($aRow['date']));
        if(empty($aRow['draft'])) {
	        if(!empty($orders_step)) {
	        	$maxOrdersStep = get_table_query_cong('select max(order_by) as max_order_by from tblorders_step where active = 1 and id_orders_item = '.$vItems['id'], 'row');
		        $pending = false;
	        	foreach($orders_step as $kStep => $vStep) {
			        $day = NULL;
			        if(!empty($vStep['date_create'])) {
				        $date_createActive = to_sql_date(_dC($vStep['date_create']));
				        $dateAdvisory = strtotime($date_create);
				        $dateCreate = strtotime($date_createActive);
				        $datediff = abs($dateAdvisory - $dateCreate);
				        $day =  floor($datediff / (60 * 60 * 24));
				        $date_create = $date_createActive;
			        }

			        $step_dateCreate = '<br/>';
			        if(!empty($vStep['active'])) {
			        	if($vStep['order_by'] >= 3 && has_permission('orders', '', 'orders_'.$vStep['order_by'])) {
					        $dateStep = (!empty($vStep['date_create']) ? ('('._l('finished_short').': '._dC($vStep['date_create']).')') : '');
					        $step_dateCreate = EditColumInputDivEdit(_dC($vStep['date_create']), $vStep['id'], 'datetime_picker_date_create', admin_url('orders/updateColumsStep'),'class="formUpdateDataTable"','datetimepicker width100','data_input', $dateStep); // ngày khách nhân được hàng
				        }
			        	else {
				            $step_dateCreate = (!empty($vStep['date_create']) ? ('<br/>'.'('._l('finished_short').': '._dC($vStep['date_create']).')') : '');
				        }
			        }
			        $cancel = "";
			        $htmlCancel = "";
			        if(empty($vStep['not_procedure'])) {
			        	if(!empty($aRow['status_pending']) && $vStep['order_by'] == 2) {
					        $vStep['name_procedure'] = _l('cong_peding_active_payment');
					        $pending = true;
				        }

				        $Row_procedure_item .= '<li '.(!empty($vStep['active']) ? 'class="active"' : '').'>';
			            $Row_procedure_item .= '     <p class="pointer li_pad0"> '.
			                                                $vStep['name_procedure'].
				                                            $step_dateCreate.
			                                                (!empty($vStep['date_expected']) ? ('('._l('cong_date_expected_short').': '._dC($vStep['date_expected']).')') : '').
				                                            (isset($day) ? '<br/><i class="text-warning">'.$day.' '._l('cong_day').'</i>' : '').
				                                    '</p>';
			            $Row_procedure_item .= '</li>';
			            $Row_procedure_item_img .='<li>'.staff_profile_image($vStep['id_staff'], ['staff-profile-image-smalls'],'small', [
			                                        'data-toggle' => 'tooltip',
			                                        'data-title' => !empty($vStep['id_staff']) ? get_staff_full_name($vStep['id_staff']) : ''
			                                    ]).'</li>';
			        }
			        else {
				        if($vStep['order_by'] == '11') {
					        $cancel = (!empty($vStep['active']) ? "" : "li_cancel");

					        if(!empty($vStep['active'])) {
						        if (has_permission('orders', '', 'orders_' . $vStep['order_by'])) {
							        $dateStep = (!empty($vStep['date_create']) ? ('(' . _l('finished_short') . ': ' . _dC($vStep['date_create']) . ')') : '');
							        $htmlCancel = "<br/>" . EditColumInputDivEdit($vStep['note_cancel'], $vStep['id'], 'note_cancel', admin_url('orders/updateColumsStep'), 'class="formUpdateDataTable"', 'width100', 'data_input', ("<i class='text-danger'>" . _l('cong_note_cancel_short') . ': ' . $vStep['note_cancel'] . "</i>")); // ngày khách nhân được hàng
						        }
						        else {
							        $htmlCancel = "<br/><i class='text-danger'>" . (!empty($vStep['note_cancel']) ? (_l('cong_note_cancel_short') . ': ' . $vStep['note_cancel']) : '') . "</i>";
						        }
					        }
					        $day = NULL;
				        }

				        $Row_procedure_item .= '<li class="initli '.(!empty($vStep['active']) ? 'active' : '').'">';
				        $Row_procedure_item .= '     <p class="pointer li_pad0"> '.
					                                    $vStep['name_procedure'].
					                                    $step_dateCreate.
												        (!empty($vStep['date_expected']) ? ('('._l('cong_date_expected_short').': '._dC($vStep['date_expected']).')') : '').
												        (isset($day) ? '<br/><i class="text-warning">'.$day.' '._l('cong_day').'</i>' : '').
						                                $htmlCancel.
											        '</p>';
				        $Row_procedure_item .= '</li>';
				        $Row_procedure_item_img .='<li>'.staff_profile_image($vStep['id_staff'], ['staff-profile-image-smalls'],'small', [
						        'data-toggle' => 'tooltip',
						        'data-title' => !empty($vStep['id_staff']) ? get_staff_full_name($vStep['id_staff']) : ''
					        ]).'</li>';
			        }

		            $no_drop = 'css-no-drop';
		            $no_background = 'css-no-background';
		            $no_event = 'css-no-event';

		            if(!empty($vStep['active'])) {
		                $name_color = $vStep['color'];
		                $StepActive = $vStep;
		            }

		            $string_active = "";
		            if(!is_admin()) {

			            if(empty($pending) || !empty($vStep['not_procedure'])) {
				            if ($maxOrdersStep->max_order_by == 2 && $kStep > 2 && $kStep < 4) {
					            $string_active = 'status-table="' . $vStep['id_procedure'] . '" data-id="' . $aRow['id'] . '" id-detail="' . $vStep['id_orders_item'] . '"';
				            } else
					            if ((($kStep > 0 && !empty($orders_step[$kStep - 1]['active'])) && $vStep['order_by'] >= $maxOrdersStep->max_order_by) || !empty($vStep['not_procedure'])) {
						            if (($kStep == 1 && $countPayment > 0 && $kStep >= $maxOrdersStep->max_order_by) || ($kStep != 1 && $vStep['order_by'] >= $maxOrdersStep->max_order_by) || !empty($vStep['not_procedure'])) {
							            $string_active = 'status-table="' . $vStep['id_procedure'] . '" data-id="' . $aRow['id'] . '" id-detail="' . $vStep['id_orders_item'] . '"';
						            }
					            }
			            }
		            }
		            else {
			                $string_active = 'status-table="'.$vStep['id_procedure'] .'" data-id="'.$aRow['id'].'" id-detail="'.$vStep['id_orders_item'].'"';
		            }

		            $htmlLi .= '<li class="padding10 items-status '.(empty($string_active) ? $no_drop : 'bg-next-active').' '.(!empty($vStep['active']) ? ($no_background.' current') : '').'">
			                        <a class="'.(!empty($cancel) ? $cancel :' AStatusAdvisory ').' '.(empty($string_active) ? ($no_event.' '.$no_drop ) : '').'" '.$string_active.'>
			                            '.$vStep['name_procedure'].'
			                        </a>
		                    	</li>';
		        }
		        $table =  '<span class="inline-block label pointer menu-receipts-status" style="border: 1px solid '.(!empty($StepActive['color']) ? $StepActive['color'] : 'black').'; color: '.(!empty($StepActive['color']) ? $StepActive['color'] : 'black').'">
		                '.(!empty($StepActive['name_procedure']) ? $StepActive['name_procedure'] : _l('cong_not_found_step'));
		        $table .= '     <div class="content-menu-status hide">';
		        $table .= '         <ol class="cd-breadcrumb triangle">'.$htmlLi.'<div>';
		        $table .= '     </div>';
		        $table .= '</span>';
	        }
	        $row[$RowArray['tinh_trang_don_hang']] = $table; //tình trạng đơn hàng
        }
        else {
        	if(!empty($aRow['orders_break'])) {
		        $row['DT_RowClass'] = 'bg-danger';
		        $allRow['DT_RowClass'] = 'bg-danger';
	        }
	        $row[$RowArray['tinh_trang_don_hang']] = '<span class="wap-new">'._l('cong_draft').'</span>';
        }




        $step_order_by = (!empty($StepActive['order_by']) ? $StepActive['order_by'] : '');
        $id_orders_item = (!empty($StepActive['id_orders_item']) ? $StepActive['id_orders_item'] : '');
        $row[$RowArray['muc_do_uu_tien']] = !empty($id_orders_item) ? GetPriority_order($step_order_by , $aRow['id'], $StepActive['id_orders_item'], true) : ''; //mức độ ưu tiên
        $row[$RowArray['ma_san_pham_trong_don_hang']] = '<p class="text-danger">'.$aRow['full_code'].'-'.$vItems['code_items'].'</p>'; //Mã SP

	    $aRow['name_shipping'] = ''; $aRow['phone_shipping'] = ''; $aRow['name_country'] = ''; $aRow['name_city'] = ''; $aRow['name_district'] = ''; $aRow['name_ward'] = '';



	    $row[$RowArray['ten_san_pham']] = '<p style="width: 200px">'.$vItems['name_product'].'</p>'; //Tên SP 5
	    $row[$RowArray['so_luong']] = number_format_data($vItems['quantity']); //Số lượng 6
	    //Quy trình
	    $Row_procedure_img = '<ul class="progressbar_img" style="display: flex;flex-direction: row;justify-content: left;">'.$Row_procedure_item_img.'</ul>';
	    $Row_procedure = '    <ul class="progressbar" style="display: flex;flex-direction: row;justify-content: center;">'.
		                            $Row_procedure_item.'
                                    <div class="clearfix"></div>
                               </ul>';
	    $row[$RowArray['quy_trinh']] = $Row_procedure_img.$Row_procedure; //Quy trình

        $row[$RowArray['hinh_anh']] = '<img width="50" src="'.base_url((!empty($vItems['avatar']) ? $vItems['avatar'] : 'uploads/no-img.jpg')).'">'; //Hình ảnh SP
        $row[$RowArray['ma_don_hang']] = '<a target="_blank" href="'.admin_url('messager/view_detail_orders/'.$vItems['id_orders']).'">'.$aRow['full_code'].'</a>'; //Mã đơn hàng

	    if(!empty($maxOrdersStep->max_order_by) && $maxOrdersStep->max_order_by != 11) {
		    $total += $vItems['total'];
	    	if($vItems['type_discount'] == 1) {
			    $total_discount_percent += ($vItems['total'] * $vItems['discount']) / 100;
		    }
	    	else {
			    $total_discount_percent += $vItems['discount'];
		    }
	    }
	    else
	    {
		    $aRow['total_item']-= $vItems['quantity'];
	    }

	    $row[$RowArray['thanh_tien']] = number_format_data($vItems['grand_total']); //Tổng giá trị thanh toán
        $row[$RowArray['tong_gia_tri_phieu_thu']] = '-'; //Tổng giá trị phiếu thu
        $row[$RowArray['khoan_con_lai_phai_thu_cua_khach_hang']] = ''; //Khoản Còn Lại Phải Thu Của KH (VND)

        //Mua cho
	    $customer_orders = [];
        if(!empty($vItems['id_customer'])) {
        	if($vItems['type_customer'] == 'client') {
	            $customer_orders = get_table_where('tblclients', ['userid' => $vItems['id_customer']], '', 'row');
		        if(!empty($customer_orders)) {
		            $href = admin_url('clients/client/'.$vItems['id_customer'].'?view');
		        }
		        else {
			        $href = '';
		        }

	        }
        	else if($vItems['type_customer'] == 'lead') {
		        $customer_orders = get_table_where('tblleads', ['id' => $vItems['id_customer']], '', 'row');
		        if(!empty($customer_orders)) {
		            $href = admin_url('leads/index/'.$customer_orders->id);
		        }
		        else {
			        $href = '';
		        }
			}

        }
        else {
            $row[$RowArray['mua_cho']] = ''; //Mua cho
        }
        $valNameSystem = !empty($customer_orders->name_system) ? '<a target="_blank" href="'.(!empty($href) ? $href : '').'">'.$customer_orders->name_system.'</a>' : '';
	    $row[$RowArray['mua_cho']] = $valNameSystem;
        //END Mua cho
        $row[$RowArray['khoan_con_lai_phai_thu_cua_khach_hang_ngoai_te']] = '-'; //Khoản Còn Lại Phải Thu Của KH (Ngoại Tệ)
       

        $this->ci->db->where('id_detail', $vItems['id']);
        $orders_shipping = $this->ci->db->get('tblorders_detail_shipping')->result_array();
        $stringShipping = "";
        foreach($orders_shipping as $keySh => $valueSh) {
            $stringShipping.= _d($valueSh['date_shipping']) .' ('._l('cong_quantity_short') .' :'.$valueSh['quantity_shipping'].')'.'<br/>';
        }

        $row[$RowArray['ngay_tao_don_hang']] = _dt($aRow['date_create']); //Ngày Tạo Đơn Hàng


  

        $output['aaData'][] = $row;
    }



    $allRow[$RowArray['so_luong']] = '<b class="text-danger">'.number_format_data($aRow['total_item']).'</b>';


    $allRow[$RowArray['thanh_tien']] = '<b class="text-danger">'.number_format_data($aRow['grand_total']).'</b>';



    $total_payment = 0;
    if(!empty($payment)) {
        $table_item = '';
        foreach ($payment as $kPay => $vPay) {
            $table_item .='<div class="wap-body view_payment" data-id="'.$vPay['id'].'">
                            <span class="text-center pull-left">'.$vPay['prefix'].$vPay['code'].'</span>
                            <span class="text-center pull-left">'._dt($vPay['date']).'</span>
                            <span class="text-center pull-left">'.get_staff_full_name($vPay['staff_id']).'</span>
                            <span class="text-right pull-left">'.number_format($vPay['total_voucher']).'</span>
                            <div class="clearfix"></div>
                       </div>';
            $total_payment += $vPay['total_voucher'];
        }

        $total = SumPaymentOrder($aRow['id']);
    }

    $allRow[$RowArray['tong_gia_tri_phieu_thu']] = '<b class="text-danger">'.number_format_data($total_payment).'</b>';

    $allRow[$RowArray['khoan_con_lai_phai_thu_cua_khach_hang']] = '<b class="text-danger">'.number_format_data($aRow['grand_total'] - $total_payment).'</b>';





    $allRow[$RowArray['khoan_con_lai_phai_thu_cua_khach_hang_ngoai_te']] = '<b class="text-danger">'.app_format_money((!empty($aRow['amount_to_vnd']) ? (($aRow['grand_total'] - $total_payment) / $aRow['amount_to_vnd']) : 0), $aRow['name_currencies']).'</b>'; // Khoản còn lại phải thu của KH ngoại tệ

    $lable_care_of_not_product = '';
    $care_of_client_not_product = get_table_query_cong('SELECT tblcare_of_clients.*
                                    FROM tblcare_of_clients
                                    WHERE NOT EXISTS (
                                        SELECT 1 
                                        FROM tblcare_of_client_items 
                                        WHERE 
                                            tblcare_of_client_items.id_care_of = tblcare_of_clients.id 
                                            AND id_orders = '.$aRow['id'].'
                                    );
                                ');
	$lable_care_of = "";
    if(!empty($care_of_client_not_product)) {
        foreach($care_of_client_not_product as $kCare => $vCare) {
            $title_care_of = $vCare['prefix'].$vCare['code'].'-'.$vCare['short_theme'];
            $lable_care_of .= '<span class="inline-block label label-warning pointer js-care_of_client" title_html="'.$title_care_of.'"  id-data="'.$vCare['id'].'" data-original-title="" title="">'.$title_care_of.'</span>';
            $lable_care_of .= '<div class="mtop5"></div>';
        }
    }


	if($permissions_view || $permissions_view_own)
	{
        $output['aaData'][] = $allRow;
	}
}


//Đếm số lượng theo trạng thái
$output['total'] = [];
if(empty($draft)) {

	$procedure = get_table_where(db_prefix().'procedure_client', [
		'type' => 'orders'
	] ,'', 'row');

	if(!empty($procedure)) {
		$this->ci->db->select('tblprocedure_client_detail.*');
		$this->ci->db->where('id_detail', $procedure->id);
		$this->ci->db->order_by('orders', 'ASC');
		$list_procedure_detail = $this->ci->db->get('tblprocedure_client_detail')->result_array();

		$where_add = '';
		if(!has_permission('orders', '', 'view') && has_permission('orders', '', 'view_own')) {
			$where_add = ' AND (tblorders.create_by = '.get_staff_user_id(). ' or tblorders.assigned = '.get_staff_user_id().')';
		}

		foreach($list_procedure_detail as $kDetail => $vDetail) {
			$where_action = $where_add;
			if($vDetail['orders'] == 2){
				$id_orders_payment = $vDetail['id'];
				$where_action .= ' AND status_pending = 0 ';
			}

			$query = 'SELECT tblorders_items.* 
						FROM tblorders_items 
						JOIN tblorders ON tblorders.id = tblorders_items.id_orders 
						WHERE (draft is null or draft = 0) '.$where_action.' 
						AND tblorders_items.id = (
							select ot.id_orders_item 
							FROM tblorders_step ot
							WHERE ot.id_orders_item = tblorders_items.id
							AND active = 1 
							AND id_procedure = '.$vDetail['id'].'
							AND ot.order_by = (
									SELECT max(order_by) 
									FROM tblorders_step ot2 
									WHERE ot2.id_orders_item = tblorders_items.id and active = 1 
								)
						)
						
					';
			$output['total'][$vDetail['id']] = $this->ci->db->query($query)->num_rows();
		}
	}

	$query = 'SELECT tblorders_items.* 
						FROM tblorders_items 
						JOIN tblorders ON tblorders.id = tblorders_items.id_orders 
						WHERE (draft is null or draft = 0) '.$where_add.' and status_pending = 1 
						AND tblorders_items.id = (
							select ot.id_orders_item 
							FROM tblorders_step ot
							WHERE ot.id_orders_item = tblorders_items.id
							AND active = 1 
							AND id_procedure = '.$id_orders_payment.'
							
						)
			';
	$output['total'][-2] = $this->ci->db->query($query)->num_rows();

	$this->ci->db->where('(tblorders.grand_total - (SELECT SUM(total_voucher) FROM tblpayment_order where id_order = tblorders.id and status_cancel = 0))  > 0 ');
	$output['total'][-5] = $this->ci->db->get('tblorders')->num_rows();
}
