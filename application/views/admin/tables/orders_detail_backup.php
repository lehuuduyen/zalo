<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'tblorders_items.id as id',
];
$sIndexColumn = 'id';
$sTable       = 'tblorders_items';
$where        = [];

$join[] = 'LEFT JOIN tblorders on tblorders.id = tblorders_items.id_orders';

$join[] = 'LEFT JOIN tblclients on tblclients.userid = tblorders.client';
$join[] = 'LEFT JOIN tblleads on tblleads.id = tblclients.leadid';

$join[] = 'LEFT JOIN tblshipping_client on tblshipping_client.id = tblorders.shipping';
$join[] = 'LEFT JOIN tbladvisory_lead on tbladvisory_lead.id = tblorders.advisory_lead_id';
$join[] = 'LEFT JOIN tblcurrencies on tblcurrencies.id = tblorders.currencies_id';
$join[] = 'LEFT JOIN tbladvisory_apply on tbladvisory_apply.id = tblorders.advisory_apply_id';
$join[] = 'LEFT JOIN tblstaff on tblstaff.staffid = tblorders.create_by';

$join[] = 'LEFT JOIN tbl_products on tbl_products.id = tblorders_items.id_product AND tblorders_items.type_items = "products"';
$join[] = 'LEFT JOIN tblitems on tblitems.id = tblorders_items.id_product AND tblorders_items.type_items = "items"';

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where,[
    'CONCAT(tblorders.prefix, tblorders.code) as full_code',
    'tblorders.id as id_orders',
    'tblorders.status as status_orders',
    'tblorders.client as client',
    'tblorders.date_create as date_create',
    'tblorders.create_by as create_by',
    'tblclients.name_system as name_system',
    'tblclients.zcode as zcode',
    'tblclients.code_system as code_system',
    'tblclients.leadid as leadid',
    'tblstaff.firstname as cbyfirstname',
    'tblstaff.lastname as cbylastname',
    'tblclients.prefix_client as prefix_client',
    'tblclients.code_client as code_client',
    'tblclients.code_type as code_type',
    'CONCAT(tblclients.prefix_client, tblclients.code_client) as fullcode_client',
    'tblorders_items.id_product as id_product',


    'code_product',
    'IF(tblorders_items.type_items = "items", tblitems.name, tbl_products.name) as name_product',
    'IF(tblorders_items.type_items = "items", tblitems.avatar, CONCAT("uploads/products/", "", tbl_products.images, "")) as avatar',
    'IF(tblorders_items.type_items = "items", tblitems.code, tbl_products.code) as code_items',


    'tblorders_items.cost_trans',
    'tblorders_items.quantity as quantity',
    'tblorders_items.price as price',
    'tblorders_items.type_discount as type_discount',
    'tblorders_items.discount as discount',
    'tblorders_items.total as total',
    'tblorders_items.grand_total as grand_total',
    'tblorders.grand_total as grand_total_order',
    'tblorders.total_item as total_item',
    'tblorders.total_cost_trans as total_cost_trans',
    'tblorders.guest_giving as guest_giving',
    'tblorders_items.id_customer as id_customer_item',
    'tblorders_items.size as size',
    'tblorders_items.statusActive as statusActive',
    'tblorders_items.type_items as type_items',
    'tblshipping_client.name as name_ship',
    'tblshipping_client.phone as phone_ship',
    'tblshipping_client.address as address_ship',
    'tblshipping_client.code_zip as code_zip_ship',
    'tblshipping_client.address_primary as address_primary_ship',
    'CONCAT(tbladvisory_lead.prefix,tbladvisory_lead.code,"-",tbladvisory_lead.type_code) as full_code_advisory',
    'tblcurrencies.name as name_currencies',
    'tblcurrencies.amount_to_vnd as amount_to_vnd',
    'tbladvisory_apply.name as name_advisory_apply',
    'concat(COALESCE(tblleads.prefix_lead), COALESCE(tblleads.code_lead), "-", COALESCE(tblleads.zcode), "-",COALESCE(tblleads.code_type)) as full_code_lead',
]);

// var_dump($result);die;
$output  = $result['output'];
$rResult = $result['rResult'];
$currentPage=$this->_instance->input->post('start');
$currentall=$output['iTotalRecords'];
$count_success = 0;
$all_count = count($rResult);
foreach ($rResult as $r => $aRow) {

    $payment = get_table_where('tblpayment_order',array('id_order'=>$aRow['id_orders']));
    $total_payment = 0;
    $countPayment = count($payment);

    $row = [];
    $row[] = ($currentall + 1) - ($currentPage + $r + 1);

    $htmlLi = '';
    $orders_step = get_table_where('tblorders_step', ['id_orders_item' => $aRow['id']], 'order_by ASC');

    $name_hau = _l('pick_status');

    $name_color = '#000';
    $StepActive = [];
    foreach($orders_step as $kStep => $vStep)
    {
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
                $string_active = 'status-table="'.$vStep['id_orders_item'] .'" data-id="'.$aRow['id_orders'].'" id-detail="'.$aRow['id'].'"';
            }
        }

        $htmlLi .= '<div class="padding10 '.(empty($string_active) ? $no_drop : '').' '.(!empty($vStep['active']) ? $no_background : '').'">
                        <a class="AStatusAdvisory '.(empty($string_active) ? ($no_event.' '.$no_drop ) : '').'" '.$string_active.'>
                            '.$vStep['name_procedure'].'
                        </a>
                    </div>';
    }

    $table =  '<span class="inline-block label pointer menu-receipts-status" style="border: 1px solid '.$StepActive['color'].'; color: '.$StepActive['color'].'">
                '.$StepActive['name_procedure'];
    $table .= '     <div class="content-menu-status hide">';
    $table .= '         <div>'.$htmlLi.'<div>';
    $table .= '     </div>';
    $table .= '</span>';
    $row[] = $table;
    //end

    $row[] = '<span class="inline-block label label-warning">Ưu Tiên Cấp Độ III</span>';

    $options  = '<div class="row-options">';
    $options .= '    <a onclick="initOrders('.$aRow['id_orders'].')">'._l('view').'</a> |';
    $options .= '    <a href="'.admin_url('orders/detail/'.$aRow['id_orders']).'" class="">'._l('edit').'</a> |';
    $options .= '    <a class="text-danger pointer" onclick="DeleteOrders('.$aRow['id_orders'].')">'._l('delete').'</a>';
    $options .= '</div>';
    $row[] = '<p class="one-control pointer"><a href="'.admin_url('orders/detail/'.$aRow['id_orders']).'">'.$aRow['full_code'].'</a></p>'.$options;

    $row[] = '<a class="pointer" href="'.admin_url('clients/client/'.$aRow['client']).'" target="_blank">'.$aRow['name_system'].(!empty($aRow['fullcode_client']) ? '<br/>('.$aRow['fullcode_client'].')' : '' ).'</a>';
    $row[] = $aRow['zcode'];

    $row[] = '<img width="50" src="'.base_url((!empty($aRow['avatar']) ? $aRow['avatar'] : 'uploads/no-img.jpg')).'">';
    $row[] = $aRow['code_product'];
    $row[] = $aRow['name_product'];



    $row[] = number_format($aRow['quantity']);
    $row[] = number_format($aRow['price']); //giá thỉnh trong nước
    if($aRow['discount'] > 0)
    {
        if($aRow['type_discount'] == 1)
        {
            $row[] = number_format(($aRow['discount']*$aRow['total']) / 100);
        }
        else
        {
            $row[] = number_format($aRow['total']-$aRow['discount']);
        }
    }
    else {
        $row[] = 0;
    }
    $row[] = number_format($aRow['grand_total']); // thành tiền

    if(!empty($payment))
    {
        $table = '<span class="inline-block label label-warning pointer menu-receipts">Thu lần: '.$countPayment; //số lượng phiếu thu
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
                    <div class="clearfix"></div>
                </div>
                <div class="table_popover_body text-center">';
                foreach ($payment as $key => $value) {
                $table .='
                    <div class="wap-body">
                        <span class="text-center pull-left">'.$value['prefix'].$value['code'].'</span>
                        <span class="text-center pull-left">'._dt($value['date']).'</span>
                        <span class="text-center pull-left">'.get_staff_full_name($value['staff_id']).'</span>
                        <div class="clearfix"></div>
                    </div>';
                    $total_payment += $value['total_voucher'];
                }

        $table .= '</div><br>
            </div></div></span>';
    }
    else
    {
        $table = '<span class="inline-block label label-warning pointer">Chưa thu</span>';
    }

    $row[] = number_format($aRow['cost_trans']); //chi phí vận chuyển

    $row[] = $table; // phiếu chi thu

    $dataCustomer = get_table_where('tblclients', ['userid' => $aRow['id_customer_item']], '' , 'row'); // mua cho
    if(!empty($dataCustomer->name_system))
    {
        $row[] = '<a class="pointer" href="'.admin_url('clients/client/'.$aRow['id_customer_item']).'" target="_blank">'.$dataCustomer->name_system.'</a>';
    }
    else
    {
        $row[] = '';
    }

    $row[] = $aRow['size'];
    $row[] = $aRow['name_ship'];
    $row[] = $aRow['phone_ship'];
    $row[] = $aRow['address_ship'];
    $row[] = $aRow['full_code_advisory']; //phiếu tư vấn khách hàng
    $row[] = $aRow['code_system'];




    $dataLead = get_table_where('tblleads', ['id'=>$aRow['leadid']], '', 'row');
    if(!empty($dataLead)) {
        // $row[] = $dataLead->prefix_lead.$dataLead->code_lead.'-'.$dataLead->zcode.'-'.$dataLead->code_type;
        $row[] = $aRow['full_code_lead'];
    }
    else {
        $row[] = '';
    }
    $row[] = $aRow['prefix_client'].$aRow['code_client'].'-'.$aRow['zcode'].'-'.$aRow['code_type'];
    $row[] = $aRow['prefix_client'].$aRow['code_client'].'-'.$aRow['zcode'].'-'.$aRow['code_type'];
    $row[] = $aRow['name_currencies']; //đơn vị tiền
    $row[] = $aRow['name_advisory_apply']; //giá thỉnh áp dụng
    $row[] = number_format($aRow['amount_to_vnd']);

    if($aRow['amount_to_vnd'] > 0 || !empty($aRow['amount_to_vnd'])) {
        $row[] = app_format_money($aRow['cost_trans'] / $aRow['amount_to_vnd'], $aRow['name_currencies']);
        // $row[] = app_format_money($aRow['guest_giving'] / $aRow['amount_to_vnd'], $aRow['name_currencies']);
        // $row[] = app_format_money($aRow['grand_total'] / $aRow['amount_to_vnd'], $aRow['name_currencies']);
        // $row[] = $total_voucher; //tổng giá trị phiếu thu
        // $row[] = $grand_total - $total_voucher; //khoảng còn lại phải thu của khách hàng
    }
    else
    {
        $row[] = '';
        // $row[] = '';
        // $row[] = '';
        // $row[] = $total_voucher; //tổng giá trị phiếu thu
        // $row[] = $grand_total; //khoảng còn lại phải thu của khách hàng
    }



    $html = '';
    $datashipping_date = get_table_where('tblorders_detail_shipping', ['id_detail' => $aRow['id']]);
    foreach ($datashipping_date as $key => $value) {
        $html .= _d($value['date_shipping']).'('.$value['quantity_shipping'].'),<br>';
    }
    $row[] = trim($html,',<br>');

    $row[] = '<p class="text-center">'._dt($aRow['date_create']).'</p>';
    $fullname_CREATE = $aRow['cbylastname'] . ' ' . $aRow['cbyfirstname'];
    $profile_CREATE = '<p class="text-center"><a data-toggle="tooltip" data-title="' . $fullname_CREATE . '" href="' . admin_url('profile/' . $aRow['create_by']) . '">' . staff_profile_image($aRow['create_by'], [
            'staff-profile-image-small',
        ]) . '</a></p>';
    $row[] = $profile_CREATE;
    //bổ xung trạng thái

    //end

    //chăm sóc khách hàng
    $html = '';
    $getDataItem = get_table_where('tblcare_of_client_items',array('id_product'=>$aRow['id_product'],'type_items'=>$aRow['type_items']));
    foreach ($getDataItem as $key => $value) {
        $getData = get_table_where('tblcare_of_clients',array('id'=>$value['id_care_of'],'id_orders'=>$aRow['id_orders']),'','row');
        if(!empty($getData)) {

            //-------------------------
            $html_img = '';
            $get_img = get_table_where('tblcare_of_detail_experience',array('id_experience'=>13,'id_care_of'=>$value['id_care_of'],'id_care_items'=>$value['id']));
            foreach ($get_img as $key_img => $value_img) {
                if(!empty($value_img['name'])) {
                    $html_img .= '<img width="30" style="border-radius: 50%;" src="'.base_url('download/preview_image?path=uploads/care_of_client/'.$value['id_care_of'].'/13/'.$value_img['name']).'">';
                }
            }
            if($html_img == '') {
                $html_img = '-';
            }
            //-------------------------
            $html_type_report_order = '';
            $get_type_report_order = get_table_where('tblcare_of_detail_experience',array('id_experience'=>12,'id_care_of'=>$value['id_care_of'],'id_care_items'=>$value['id']));
            foreach ($get_type_report_order as $key_type_report_order => $value_type_report_order) {
                $html_type_report_order .= $value_type_report_order['name'].', ';
            }
            $html_type_report_order = trim($html_type_report_order, ', ');
            if($html_type_report_order == '') {
                $html_type_report_order = '-';
            }
            //-------------------------
            $html_advisory = '';
            $get_advisory = get_table_where('tblcare_of_detail_experience',array('id_experience'=>14,'id_care_of'=>$value['id_care_of'],'id_care_items'=>$value['id']));
            foreach ($get_advisory as $key_advisory => $value_advisory) {
                $html_advisory .= $value_advisory['name'].', ';
            }
            $html_advisory = trim($html_advisory, ', ');
            if($html_advisory == '') {
                $html_advisory = '-';
            }
            //-------------------------
            $html_product = '';
            $get_product = get_table_where('tblcare_of_detail_experience',array('id_experience'=>15,'id_care_of'=>$value['id_care_of'],'id_care_items'=>$value['id']));
            foreach ($get_product as $key_product => $value_product) {
                $html_product .= $value_product['name'].', ';
            }
            $html_product = trim($html_product, ', ');
            if($html_product == '') {
                $html_product = '-';
            }
            //-------------------------
            $html_pack = '';
            $get_pack = get_table_where('tblcare_of_detail_experience',array('id_experience'=>16,'id_care_of'=>$value['id_care_of'],'id_care_items'=>$value['id']));
            foreach ($get_pack as $key_pack => $value_pack) {
                $html_pack .= $value_pack['name'].', ';
            }
            $html_pack = trim($html_pack, ', ');
            if($html_pack == '') {
                $html_pack = '-';
            }
            //-------------------------
            $html_efficiency_product = '';
            $get_efficiency_product = get_table_where('tblcare_of_detail_experience',array('id_experience'=>17,'id_care_of'=>$value['id_care_of'],'id_care_items'=>$value['id']));
            foreach ($get_efficiency_product as $key_efficiency_product => $value_efficiency_product) {
                $html_efficiency_product .= $value_efficiency_product['name'].', ';
            }
            $html_efficiency_product = trim($html_efficiency_product, ', ');
            if($html_efficiency_product == '') {
                $html_efficiency_product = '-';
            }
            //-------------------------
            $html_life_after_use_product = '';
            $get_life_after_use_product = get_table_where('tblcare_of_detail_experience',array('id_experience'=>18,'id_care_of'=>$value['id_care_of'],'id_care_items'=>$value['id']));
            foreach ($get_life_after_use_product as $key_life_after_use_product => $value_life_after_use_product) {
                $html_life_after_use_product .= $value_life_after_use_product['name'].', ';
            }
            $html_life_after_use_product = trim($html_life_after_use_product, ', ');
            if($html_life_after_use_product == '') {
                $html_life_after_use_product = '-';
            }
            //-------------------------
            $html_payment = '';
            $get_payment = get_table_where('tblcare_of_detail_experience',array('id_experience'=>19,'id_care_of'=>$value['id_care_of'],'id_care_items'=>$value['id']));
            foreach ($get_payment as $key_payment => $value_payment) {
                $html_payment .= $value_payment['name'].', ';
            }
            $html_payment = trim($html_payment, ', ');
            if($html_payment == '') {
                $html_payment = '-';
            }
            //-------------------------
            $html_support = '';
            $get_support = get_table_where('tblcare_of_detail_experience',array('id_experience'=>20,'id_care_of'=>$value['id_care_of'],'id_care_items'=>$value['id']));
            foreach ($get_support as $key_support => $value_support) {
                $html_support .= $value_support['name'].', ';
            }
            $html_support = trim($html_support, ', ');
            if($html_support == '') {
                $html_support = '-';
            }
            //-------------------------

            $html .= '<span class="inline-block label label-warning pointer js-care_of_client">'.(!empty($getData->prefix) ? $getData->prefix : '').(!empty($getData->code) ? $getData->code : '').'-'.(!empty($getData->short_theme) ? $getData->short_theme : '');
            $html .= '<div class="title-menu-care_of hide">'.(!empty($getData->prefix) ? $getData->prefix : '').(!empty($getData->code) ? $getData->code : '').'-'.(!empty($getData->short_theme) ? $getData->short_theme : '').'</div>';
            $html .= '<div class="content-menu-care_of hide">';
            $html .= '
                <div><span>'._l('h_date_success_care_of_client').': </span><span>'.(!empty($getData->date_success) ? _dt($getData->date_success) : '').'</span></div>
                <div><span>'._l('img_report_customer').': </span>'.$html_img.'</div>
                <div><span>'._l('type_report_order').': </span><span class="bold600">'.$html_type_report_order.'</span></div>
                <div><span>'._l('this_advisory').': </span><span class="bold600">'.$html_advisory.'</span></div>
                <div><span>'._l('this_product').': </span><span class="bold600">'.$html_product.'</span></div>
                <div><span>'._l('this_pack').': </span><span class="bold600">'.$html_pack.'</span></div>
                <div><span>'._l('this_efficiency_product').': </span><span class="bold600">'.$html_efficiency_product.'</span></div>
                <div><span>'._l('this_life_after_use_product').': </span><span class="bold600">'.$html_life_after_use_product.'</span></div>
                <div><span>'._l('this_payment').': </span><span class="bold600">'.$html_payment.'</span></div>
                <div><span>'._l('this_support').': </span><span class="bold600">'.$html_support.'</span></div>
            ';
            $html .= '</div></span><div class="mtop5"></div>';
        }
    }
    $row[] = $html;
    //end

    $output['aaData'][] = $row;
}