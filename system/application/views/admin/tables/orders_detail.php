<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'tblorders.code',
];
$sIndexColumn = 'id';
$sTable       = 'tblorders';
$where        = [];

$join[] = 'LEFT JOIN tblorders_items on tblorders_items.id_orders = tblorders.id';

$join[] = 'LEFT JOIN tblclients on tblclients.userid = tblorders.client';
$join[] = 'LEFT JOIN tblcountries on tblcountries.country_id = tblclients.country';
$join[] = 'LEFT JOIN tbldistrict on tbldistrict.districtid = tblclients.district';
$join[] = 'LEFT JOIN tblprovince on tblprovince.provinceid = tblclients.city';
$join[] = 'LEFT JOIN tblward on tblward.wardid = tblclients.ward';

$join[] = 'LEFT JOIN tblleads on tblleads.id = tblclients.leadid';

$join[] = 'LEFT JOIN tblshipping_client on tblshipping_client.id = tblorders.shipping';
$join[] = 'LEFT JOIN tbladvisory_lead on tbladvisory_lead.id = tblorders.advisory_lead_id';
$join[] = 'LEFT JOIN tblcurrencies on tblcurrencies.id = tblorders.currencies_id';
$join[] = 'LEFT JOIN tbladvisory_apply on tbladvisory_apply.id = tblorders.advisory_apply_id';
$join[] = 'LEFT JOIN tblstaff on tblstaff.staffid = tblorders.create_by';


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
	'tblorders.id as id',
    'concat(COALESCE(tblorders.prefix), COALESCE(tblorders.code)) as full_code',
    'CONCAT(tbladvisory_lead.prefix,tbladvisory_lead.code,"-",tbladvisory_lead.type_code) as full_code_advisory',
    'tblorders.date',
    'tblorders.date_create',
    'tblorders.create_by',
    'tblclients.zcode',
    'tblclients.name_system',
    'tblclients.code_system',
    'tblstaff.firstname as cbyfirstname',
    'tblstaff.lastname as cbylastname',
    'tblclients.address',
    'tblclients.country',
    'tblclients.city',
    'tblclients.district',
    'tblclients.ward',
    'tblclients.phonenumber',
    'concat(COALESCE(tblleads.prefix_lead), COALESCE(tblleads.code_lead), "-", COALESCE(tblleads.zcode), "-",COALESCE(tblleads.code_type)) as full_code_lead',
    'CONCAT(tblclients.prefix_client, tblclients.code_client) as fullcode_client',
    'tblorders.grand_total',
    'tblorders.total_discount_percent',
    'tblorders.total_discount_money',
    'tblorders.total_quantity',
    'tblorders.guest_giving',
    'tblorders.total_item',
    'tblorders.total',
    'tblcountries.short_name as name_country',
    'tblprovince.name as name_city',
    'tbldistrict.name as name_district',
    'tblward.name as name_ward',
    'tblshipping_client.name as name_shipping',
    'tblshipping_client.phone as phone_shipping',
    'tblshipping_client.address as address_shipping',
    'tblcurrencies.name as name_currencies',
    'tblcurrencies.amount_to_vnd as amount_to_vnd',
    'tbladvisory_apply.name as name_advisory_apply',
    'tblorders.total_international',
    'tblorders.total_cost_trans_international',
    'tblorders.total_cost_trans',
    'tblorders.date_want_to_receive',
	'draft',
	'type_object_draft',
	'id_object_draft',
],'group by tblorders.id');

$allRow = [];
for($i = 0 ; $i < 51 ; $i++)
{
    $allRow[] = '';
}

$output  = $result['output'];
$rResult = $result['rResult'];
$currentPage = $this->_instance->input->post('start');
$currentall = $output['iTotalRecords'];
$count_success = 0;
$all_count = count($rResult);
foreach ($rResult as $r => $aRow) {

    $rowNum = ($currentall) - ($currentPage + $r + 1);

    $this->ci->db->select('tblpayment_order. *, tblpayment_modes.name as name_pay_moders');
    $this->ci->db->where('id_order', $aRow['id']);
    $this->ci->db->join('tblpayment_modes', 'tblpayment_modes.id = tblpayment_order.payment_modes', 'left');
    $payment = $this->ci->db->get('tblpayment_order')->result_array();
    $total_payment = 0;
    $countPayment = count($payment);

    $this->ci->db->select('
        IF(tblorders_items.type_items = "items", tblitems.avatar, CONCAT("uploads/products/", "", tbl_products.images, "")) as avatar,
        tblorders_items.*
    ');
    $this->ci->db->where('id_orders', $aRow['id']);
    $this->ci->db->join('tbl_products', 'tbl_products.id = tblorders_items.id_product and tblorders_items.type_items = "products"', 'left');
    $this->ci->db->join('tblitems', 'tblitems.id = tblorders_items.id_product and tblorders_items.type_items = "items"', 'left');
    $orders_items = $this->ci->db->get('tblorders_items')->result_array();
    foreach($orders_items as $key => $vItems)
    {
        $row = [];
        $row[0] = $rowNum.'.'.($key+1); //stt

        $htmlLi = '';
        $orders_step = get_table_where('tblorders_step', ['id_orders_item' => $vItems['id']], 'order_by ASC');

        $name_hau = _l('pick_status');
        $name_color = '#000';
        $StepActive = [];

        $Row_procedure_item = '';
        $Row_procedure_item_img = '';

	    $table = '';
        if(empty($aRow['draft']))
        {
	        if(!empty($orders_step))
	        {
		        foreach($orders_step as $kStep => $vStep)
		        {
			        $day = NULL;
			        if(!empty($vStep['date_create']))
			        {
				        $dateAdvisory = strtotime($aRow['date']);
				        $dateCreate = strtotime($vStep['date_create']);
				        $datediff = abs($dateAdvisory - $dateCreate);
				        $day =  floor($datediff / (60*60*24));
			        }
		            $Row_procedure_item .= '<li '.(!empty($vStep['active']) ? 'class="active"' : '').'>';
		            $Row_procedure_item .= '     <p class="pointer li_pad0"> '.
		                                                $vStep['name_procedure'].
		                                                (!empty($vStep['date_create']) ? ('<br/>'.'('._l('finished_short').': '._dC($vStep['date_create']).')') : '').
		                                                (!empty($vStep['date_expected']) ? ('<br/>'.'('._l('cong_date_expected_short').': '._dC($vStep['date_expected']).')') : '').
			                                            (isset($day) ? '<br/><i class="text-warning">'.$day.' '._l('cong_day').'</i>' : '').
			                                    '</p>';
		            $Row_procedure_item .= '</li>';
		            $Row_procedure_item_img .='<li>'.staff_profile_image($vStep['id_staff'], ['staff-profile-image-smalls'],'small', [
		                                        'data-toggle' => 'tooltip',
		                                        'data-title' => !empty($vStep['id_staff']) ? get_staff_full_name($vStep['id_staff']) : ''
		                                    ]).'</li>';

		            $no_drop = 'css-no-drop';
		            $no_background = 'css-no-background';
		            $no_event = 'css-no-event';

		            if(!empty($vStep['active']))
		            {
		                $name_color = $vStep['color'];
		                $StepActive = $vStep;
		            }

		            $string_active = "";
		            if($kStep > 0 && empty($vStep['active']) && !empty($orders_step[$kStep - 1]['active']))
		            {
		                if(($kStep == 1 && $countPayment > 0) || $kStep != 1)
		                {
		                    $string_active = 'status-table="'.$vStep['id_procedure'] .'" data-id="'.$aRow['id'].'" id-detail="'.$vStep['id_orders_item'].'"';
		                }
		            }

		            $htmlLi .= '<div class="padding10 '.(empty($string_active) ? $no_drop : '').' '.(!empty($vStep['active']) ? $no_background : '').'">
		                        <a class="AStatusAdvisory '.(empty($string_active) ? ($no_event.' '.$no_drop ) : '').'" '.$string_active.'>
		                            '.$vStep['name_procedure'].'
		                        </a>
		                    </div>';
		        }
		        $table =  '<span class="inline-block label pointer menu-receipts-status" style="border: 1px solid '.(!empty($StepActive['color']) ? $StepActive['color'] : '').'; color: '.(!empty($StepActive['color']) ? $StepActive['color'] : '').'">
		                '.(!empty($StepActive['name_procedure']) ? $StepActive['name_procedure'] : '');
		        $table .= '     <div class="content-menu-status hide">';
		        $table .= '         <div>'.$htmlLi.'<div>';
		        $table .= '     </div>';
		        $table .= '</span>';
	        }
	        $row[1] = $table; //tình trạng đơn hàng
        }
        else
        {
	        $row[1] = '<span class="wap-new">'._l('cong_draft').'</span>';
        }




        $step_order_by = (!empty($StepActive['order_by']) ? $StepActive['order_by'] : '');
        $id_orders_item = (!empty($StepActive['id_orders_item']) ? $StepActive['id_orders_item'] : '');
        $row[2] = !empty($id_orders_item) ? GetPriority_order($step_order_by , $aRow['id'], $StepActive['id_orders_item'], true) : ''; //mức độ ưu tiên
        $row[3] = $vItems['code_product']; //Mã SP
	    $row[4] = $aRow['name_system']; //Tên khách hàng trong hệ thống 4
	    $row[5] = $vItems['name_product']; //Tên SP 5
	    $row[6] = number_format_data($vItems['quantity']); //Số lượng 6

        $row[7] = '<p style="width: 100px">'.$aRow['name_shipping'].'</p>'; //Tên người nhận hàng 4
        $row[8] = $aRow['zcode']; //Tên khách hàng trong hệ thống
        $row[9] = '<img width="50" src="'.base_url((!empty($vItems['avatar']) ? $vItems['avatar'] : 'uploads/no-img.jpg')).'">'; //Hình ảnh SP
        $row[10] = '<a target="_blank" href="'.admin_url('messager/view_detail_orders/'.$vItems['id_orders']).'">'.$aRow['full_code'].'</a>'; //Mã đơn hàng
        $row[11] = number_format_data($vItems['price']); //Đơn giá
        $row[12] = $vItems['discount'].($vItems['type_discount'] == 1 ? ' %': ' VND'); //Chiết khấu(khuyến mãi)
        $row[13] = number_format_data($vItems['total']); //Thành tiền
        $row[14] = number_format_data($vItems['grand_total']); //Tổng giá trị thanh toán
        $row[15] = '-'; //Tổng giá trị phiếu thu
        $row[16] = ''; //Khoản Còn Lại Phải Thu Của KH (VND)
        $row[17] = '-'; //Số Mặt Hàng Trong Giỏ Hàng
        $row[18] = ''; //Chi phí vận chuyển
        $row[19] = '-'; //Khách hàng tặng thêm
//        $row[19] = ''; //Hình thức thanh toán
        $row[20] = ''; //Chi tiết phiếu thu

        //Mua cho
        if(!empty($vItems['id_customer'])) {
        	if($vItems['type_customer'] == 'client')
	        {
	            $customer_orders = get_table_where('tblclients', ['userid' => $vItems['id_customer']], '', 'row');
		        $row[21] = !empty($customer_orders) ? ('<a target="_blank" href="'.admin_url('clients/client/'.$customer_orders->userid.'?view').'">'.$customer_orders->name_system.'</a>') : '-';
	        }
        	else if($vItems['type_customer'] == 'lead')
	        {
		        $customer_orders = get_table_where('tblleads', ['id' => $vItems['id_customer']], '', 'row');
		        $row[21] = !empty($customer_orders) ? ('<a target="_blank" onclick="init_lead('.$customer_orders->id.');return false;" href="'.admin_url('leads/index/'.$customer_orders->id).'">'.$customer_orders->name_system.'</a>') : '-';

	        }
        }
        else {
            $row[21] = ''; //Mua cho
        }
        //END Mua cho

        $row[22] = $vItems['size']; //kích thước
        $row[23] = ''; //Ghi chú đơn hàng
        $row[24] = $aRow['phone_shipping']; //Số điện thoại nhận hàng
        $row[25] = $aRow['address_shipping']; //Địa chỉ nhận hàng
        $row[26] = $aRow['name_country']; //quốc gia
        $row[27] = $aRow['name_city']; //Tỉnh/Thành phố
        $row[28] = $aRow['name_district']; //Quận huyện
        $row[29] = $aRow['name_ward']; //Phường xã
//        $row[] = ''; //Số lượng phiếu thu
//        $row[] = ''; //Phiếu thu
        $row[30] = $aRow['full_code_advisory']; //Mã phiếu tư vấn
        $row[31] = $aRow['code_system']; //Mã khách hàng hệ thống
        $row[32] = $aRow['full_code_lead']; //Mã khách hàng tiềm năng
        $row[33] = $aRow['fullcode_client']; //Mã khách hàng
        $row[34] = $aRow['fullcode_client']; //Mã khách hàng hiện tại
        $row[35] = $aRow['name_currencies']; //Đơn vị tiền tệ
        $row[36] = $aRow['name_advisory_apply']; //Giá Thỉnh Áp Dụng
        $row[37] = number_format_data($aRow['amount_to_vnd']).' VNĐ'; //Tỉ Giá Ngoại Tệ/VND

        $row[38] = ''; //Phí Vận Chuyển (Ngoại Tệ)
        $row[39] = '-'; //Khách Tặng Thêm (Ngoại Tệ)


        $row[40] = '-'; //Tổng Giá Trị Đơn Hàng (Ngoại Tệ)
        $row[41] = '-'; //Tổng Giá Trị Phiếu Thu (Ngoại Tệ)
        $row[42] = '-'; //Khoản Còn Lại Phải Thu Của KH (Ngoại Tệ)
        $row[43] = _dt($aRow['date_want_to_receive']); //Ngày Khách Mong Muốn Được Nhận Được Đơn Hàng


        $this->ci->db->where('id_detail', $vItems['id']);
        $orders_shipping = $this->ci->db->get('tblorders_detail_shipping')->result_array();
        $stringShipping = "";
        foreach($orders_shipping as $keySh => $valueSh) {
            $stringShipping.= $valueSh['date_shipping'] .' ('.$valueSh['quantity_shipping'].')'.'<br/>';
        }
        $row[44] = $stringShipping; //Ngày giao hàng dự kiến

        $row[45] = _dt($aRow['date_create']); //Ngày Tạo Đơn Hàng

        $fullname_CREATE = $aRow['cbylastname'] . ' ' . $aRow['cbyfirstname'];
        $profile_CREATE = '<p class="text-center"><a data-toggle="tooltip" data-title="' . $fullname_CREATE . '" href="' . admin_url('profile/' . $aRow['create_by']) . '">' . staff_profile_image($aRow['create_by'], [
                'staff-profile-image-small',
            ]) . '</a></p>';
        $row[46] = $profile_CREATE;//Nhân viên Tạo Đơn Hàng
		//Quy trình
        $Row_procedure_img = '<ul class="progressbar_img" style="display: flex;flex-direction: row;justify-content: left;">'.$Row_procedure_item_img.'</ul>';
        $Row_procedure = '    <ul class="progressbar" style="display: flex;flex-direction: row;justify-content: center;">'.
                                    $Row_procedure_item.'
                                    <div class="clearfix"></div>
                               </ul>';
        $row[47] = $Row_procedure_img.$Row_procedure; //Quy trình



        $lable_care_of = '';
        $this->ci->db->select('tblcare_of_client_items.id as id_detail, tblcare_of_clients.*');
        $this->ci->db->where('id_orders', $aRow['id']);
        $this->ci->db->where('type_items', $vItems['type_items']);
        $this->ci->db->where('id_product', $vItems['id_product']);
        $this->ci->db->join('tblcare_of_clients', 'tblcare_of_clients.id = tblcare_of_client_items.id_care_of');
        $care_of_client = $this->ci->db->get('tblcare_of_client_items')->result_array();
        if(!empty($care_of_client))
        {
            foreach($care_of_client as $kCare => $vCare)
            {
                $title_care_of = $vCare['prefix'].$vCare['code'].'-'.$vCare['short_theme'];
                $lable_care_of .= '<span class="inline-block label label-warning pointer js-care_of_client" title_html="'.$title_care_of.'" theme-of="'.$vCare['theme_of'].'"  id-data="'.$vCare['id'].'" id-data-detail="'.$vCare['id_detail'].'" data-original-title="" title="">'.$title_care_of.'</span>';
                $lable_care_of .= '<div class="mtop5"></div>';
            }
        }
	    $unit_ship = get_table_where('tblcombobox_client', ['id' => $vItems['unit_ship']], '', 'row');
	    $row[48] = !empty($unit_ship->name) ? $unit_ship->name : '-';
	    $row[49] = !empty($vItems['code_ship']) ? $vItems['code_ship'] : '-';
	    $row[50] = $lable_care_of; //mã phiếu chăm sóc khách hàng

        $output['aaData'][] = $row;
    }



    $allRow[0] = '<b class="text-danger">'._l('cong_sum').'</b>';
    $allRow[6] = '<b class="text-danger">'.number_format_data($aRow['total_quantity']).'</b>';
    $allRow[12] = '<b class="text-danger">'.number_format_data($aRow['total_discount_percent'] + $aRow['total_discount_money']).' VND</b>';
    $allRow[13] = '<b class="text-danger">'.number_format_data($aRow['total']).'</b>'; // thành tiền
    $allRow[14] = '<b class="text-danger">'.number_format_data($aRow['grand_total']).'</b>';
    $allRow[17] = '<b class="text-danger">'.number_format_data($aRow['total_item']).'</b>'; // tổng số mặt hàng
    $allRow[19] = '<b class="text-danger">'.number_format_data($aRow['guest_giving']).'</b>'; // Khách hàng tawjgn thêm

    $total_payment = 0;
    if(!empty($payment))
    {
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

        $table = '<span class="inline-block label label-warning pointer menu-receipts">'._l('cong_collect_number').': '.($kPay+1); //số lượng phiếu thu
        $table .= '    <div class="content-menu hide">
                            <div class="table_popover padding10">
                                <div class="table_popover_head text-center">
                                    <div class="pull-left wap-head">
                                        <span class="text-center">' . _l('ch_code_number') . '</span>
                                    </div>
                                    <div class="pull-left wap-head">
                                        <span class="text-center">' . _l('date') . '</span>
                                    </div>
                                    <div class="pull-left wap-head">
                                        <span class="text-center">' . _l('als_staff') . '</span>
                                    </div>
                                    <div class="pull-left wap-head">
                                        <span class="text-center">' . _l('ch_value') . '</span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="table_popover_body text-center">' . $table_item . '</div>
                                <div class="wap-body" style="font-weight:bold;color:red">
                                    <span class="text-center pull-left">'._l('cong_total').'</span>
                                    <span class="text-center pull-left"></span>
                                    <span class="text-center pull-left"></span>
                                    <span class="text-right pull-right">'.number_format($total_payment).'</span>
                                    <div class="clearfix"></div>
                                </div><br>
                            </div>
                        </div>';
        $table .= '</span>';
    }
    else
    {
        $table = '<span class="inline-block label label-warning pointer">'._l('cong_not_collect').'</span>';
    }
    $allRow[20] = $table;
    $allRow[15] = '<b class="text-danger">'.number_format_data($total_payment).'</b>';

    $allRow[16] = '<b class="text-danger">'.number_format_data($aRow['grand_total'] - $total_payment).'</b>';

    $allRow[18] = '<b class="text-danger">'.number_format_data($aRow['total_cost_trans']).'</b>';


    $allRow[38] = '<b class="text-danger">'.app_format_money($aRow['total_cost_trans_international'], $aRow['name_currencies']).'</b>'; // Phí Vận Chuyển (Ngoại Tệ)
    $allRow[39] = '<b class="text-danger">'.app_format_money((!empty($aRow['amount_to_vnd']) ? ($aRow['guest_giving'] / $aRow['amount_to_vnd']) : ''), $aRow['name_currencies']).'</b>'; // Khách hàng tặng thêm ngoại tệ
    $allRow[40] = '<b class="text-danger">'.app_format_money($aRow['total_international'], $aRow['name_currencies']).'</b>'; // Tổng giá trị đơn hàng ngoại tệ


    $allRow[41] = '<b class="text-danger">'.app_format_money((!empty($aRow['amount_to_vnd']) ? $total_payment / $aRow['amount_to_vnd'] : ''), $aRow['name_currencies']).'</b>'; // Tổng giá trị phiếu thu ngoại tệ
    $allRow[42] = '<b class="text-danger">'.app_format_money((!empty($aRow['grand_total']) ? $aRow['grand_total'] / $aRow['amount_to_vnd'] : 0), $aRow['name_currencies']).'</b>'; // Khoản còn lại phải thu của KH ngoại tệ

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
    if(!empty($care_of_client_not_product))
    {
        foreach($care_of_client_not_product as $kCare => $vCare)
        {
            $title_care_of = $vCare['prefix'].$vCare['code'].'-'.$vCare['short_theme'];
            $lable_care_of .= '<span class="inline-block label label-warning pointer js-care_of_client" title_html="'.$title_care_of.'" theme-of="'.$vCare['theme_of'].'" id-data="'.$vCare['id'].'" data-original-title="" title="">'.$title_care_of.'</span>';
            $lable_care_of .= '<div class="mtop5"></div>';
        }
    }


	$allRow[48] = '';
	$allRow[49] = '';


	$row[50] = $lable_care_of; //mã phiếu chăm sóc khách hàng

    $output['aaData'][] = $allRow;
}