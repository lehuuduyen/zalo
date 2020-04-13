<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns        = [
    'tblorders_shop.id',
    'tblorders_shop.shop', //của hàng
    'control_code', //mã đối soát
    'tblorders_shop.code_orders', //mã đơn khách hàng
    'tblorders_shop.code_supership', //mã đơn supership
    'tblorders_shop.status',  // trạng thái
    'tblorders_shop.date_create', // ngày tạo
    'mass', //khối lượng
    'tblorders_shop.collect', //thu hộ
    'value', //	trị giá
    'prepay', //trả trước
    'pay_transport', //phí vận chuyển
    'tblorders_shop.insurance', //phí bảo hiểm
    'pay_refund', //phí chuyển hoàn
    'sale', //khuyến mãi
    'pack_data', //gói cước
    'payer', //	người trả phí
    'receiver', //	người nhận
    'phone', //	số điện thoại
    'address', //	địa chỉ
    'ward', //phường xã
    'district', //quận huyện
    'tblorders_shop.city', //	thành phố
    'note', //ghi chú
    'warehouses', //	kho hàng
    'product', //sản phẩm
    'control_date', //	ngày đối soát
    'type', //loại
    'city_send', //tình thành gửi,
    'tbldelivery_orders.code_delivery', // mã giao hàng
    'tbldelivery_orders.deliver', // người giao
    'tbldelivery_orders.collect_report', // thu hộ báo cáo
    'tbldelivery_list.collection_by', // người giao
    'tbldelivery_list.date_collection', // người giao
    'hd_fee', //Phí hải dương
    'revenue_calculated',//Doanh Thu Tổng Tính
    'real_revenue', //Doanh Thu Thực
    'date_debits', //ngày tính công nợ
    'is_hd_branch' //Chi Nhánh Lấy
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'orders_shop';
$join         = ['LEFT JOIN tbldelivery_orders ON tbldelivery_orders.code_supership = tblorders_shop.code_supership'];
$join[]       = 'LEFT JOIN tbldelivery_list ON tbldelivery_list.code_delivery = tbldelivery_orders.code_delivery';

$where = [];

if($this->ci->input->post())
{
    $_dataSearch = $this->ci->input->post();
    if(!empty($_dataSearch['create_start']))
    {
        $where[] = 'AND DATE_FORMAT(tblorders_shop.date_create, "%Y-%m-%d")>="'.to_sql_date($_dataSearch['create_start']).'"';
    }
    if(!empty($_dataSearch['create_end']))
    {
        $where[] = 'AND DATE_FORMAT(tblorders_shop.date_create, "%Y-%m-%d")<="'.to_sql_date($_dataSearch['create_end']).'"';
    }
    if(!empty($_dataSearch['collection_start']))
    {
        $where[] = 'AND DATE_FORMAT(date_collection, "%Y-%m-%d")>="'.to_sql_date($_dataSearch['collection_start']).'"';
    }
    if(!empty($_dataSearch['collection_end']))
    {
        $where[] = 'AND DATE_FORMAT(date_collection, "%Y-%m-%d")<="'.to_sql_date($_dataSearch['collection_end']).'"';
    }


    if(!empty($_dataSearch['date_debits_start']))
    {
        $where[] = 'AND DATE_FORMAT(date_debits, "%Y-%m-%d")>="'.to_sql_date($_dataSearch['date_debits_start']).'"';
    }
    if(!empty($_dataSearch['date_debits_end']))
    {
        $where[] = 'AND DATE_FORMAT(date_debits, "%Y-%m-%d")<="'.to_sql_date($_dataSearch['date_debits_end']).'"';
    }

    if(!empty($_dataSearch['control_start']))
    {
        $where[] = 'AND DATE_FORMAT(control_date, "%Y-%m-%d")>="'.to_sql_date($_dataSearch['control_start']).'"';
    }
    if(!empty($_dataSearch['control_end']))
    {
        $where[] = 'AND DATE_FORMAT(control_date, "%Y-%m-%d")<="'.to_sql_date($_dataSearch['control_end']).'"';
    }

    if(!empty($_dataSearch['shop']))
    {
        $where[] = 'AND tblorders_shop.shop ="'.$_dataSearch['shop'].'"';
    }
    if(!empty($_dataSearch['status']))
    {
        $where[] = 'AND tblorders_shop.status ="'.$_dataSearch['status'].'"';
    }
    if(!empty($_dataSearch['city_send']))
    {
        $where[] = 'AND city_send ="'.$_dataSearch['city_send'].'"';
    }
    if(!empty($_dataSearch['city']))
    {
        $where[] = 'AND tblorders_shop.city ="'.$_dataSearch['city'].'"';
    }
    if(!empty($_dataSearch['deliver']))
    {
        $where[] = 'AND tbldelivery_orders.deliver ="'.$_dataSearch['deliver'].'"';
    }

}


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    'tbldelivery_orders.status', // trạng thái giao hàng'
], 'group by tblorders_shop.id');

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    foreach($aColumns as $key => $value)
    {
        $CRow_data = $aRow[$value];
        if($value == 'tblorders_shop.status')
        {
            if(!empty($aRow['tblorders_shop.status']))
            {
                $CRow_data =  $aRow['tblorders_shop.status'];
            }

        }
        if($value == 'tblorders_shop.date_create' || $value == 'control_date' || $value == 'tbldelivery_list.date_collection' || $value == 'date_debits')
        {
            $CRow_data = _dt($aRow[$value]);
        }

        if($value == 'is_hd_branch')
        {
            if($aRow[$value] == NULL)
            {
                $CRow_data = 'Chưa Xác Định';
            }
            else if(!empty($aRow[$value]))
            {
                $CRow_data = 'Chi Nhánh Mình';
            }
            else
            {
                $CRow_data = 'Chi Nhánh Khác';
            }
        }
        $row[] =  $CRow_data;
    }
    $output['aaData'][] = $row;
}
