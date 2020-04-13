<?php
defined('BASEPATH') OR exit('No direct script access allowed');





$debt = 0;
$debt = get_option('debt_control_start');
if(empty($debt))
{
    $debt = 0;
}
if($this->ci->input->post('date_start_control'))
{

    $dateStart = $this->ci->input->post('date_start_control');
    $where_debt = [];

    $where_debt[] =  'AND DATE_FORMAT(date_debits,"%Y-%m-%d") < "'.to_sql_date($dateStart).'"';

    $where_debt[] =  'AND control_date is null';
    $where_debt[] =  'AND is_hd_branch = 1';
    $where_debt[] =  'AND (status = "Đã Giao Hàng Một Phần" or status = "Đã Giao Hàng Toàn Bộ")';
//    $where_debt[] =  'AND tblorders_shop.shop = (select tblcustomers.customer_shop_code from tblcustomers where tblcustomers.customer_shop_code = tblorders_shop.shop)';

    if(!empty($where_debt))
    {
        $where_debt = trim(implode(' ',$where_debt), 'AND');
        $where_debt = 'where '.$where_debt;
    }


    $DataDebt = $this->ci->db->query('
        SELECT
            date_debits,
            code_supership,
            status as note,
            IFNULL(collect, 0) as pstang,
            IFNULL(pay_transport, 0) + IFNULL(insurance, 0) + IFNULL(pay_refund, 0) - IFNULL(sale, 0) as psgiam,
            id,
            0 as debt
        FROM tblorders_shop '.$where_debt.' order by date_debits asc
    ')->result_array();
    foreach($DataDebt as $key => $value)
    {
        $debt += $value['pstang'];
        $debt -= $value['psgiam'];
    }
}










$start = $this->ci->input->post('start');
$length = $this->ci->input->post('length');
$where_order = [];
//$where_order = ['AND tblorders_shop.shop = (select tblcustomers.customer_shop_code from tblcustomers where tblcustomers.customer_shop_code = tblorders_shop.shop)'];
//$where_order[] =  'AND control_date is not null';

$where_order[] =  'AND control_date is null';
$where_order[] =  'AND is_hd_branch = 1';
$where_order[] =  'AND (status = "Đã Giao Hàng Một Phần" or status = "Đã Giao Hàng Toàn Bộ")';
if($this->ci->input->post('date_start_control'))
{
    $dateStart = $this->ci->input->post('date_start_control');
    $where_order[] =  'AND DATE_FORMAT(date_debits,"%Y-%m-%d") >= "'.to_sql_date($dateStart).'"';
}

if($this->ci->input->post('date_end_control'))
{
    $dateEnd = $this->ci->input->post('date_end_control');
    $where_order[] =  'AND DATE_FORMAT(date_debits,"%Y-%m-%d") <= "'.to_sql_date($dateEnd).'"';
}


$new_where_order = trim(implode(' ',$where_order), 'AND');

if(!empty($new_where_order))
{
    $new_where_order = 'where '.$new_where_order;
}

$All_data = $this->ci->db->query('
    SELECT
        date_debits,
        code_supership,
        status as note,
        IFNULL(collect, 0) as pstang,
        IFNULL(pay_transport, 0) + IFNULL(insurance, 0) + IFNULL(pay_refund, 0) - IFNULL(sale, 0) as psgiam,
        id,
        0 as debt
    FROM tblorders_shop '.$new_where_order.' order by date_debits desc
')->result_array();

// lấy giới hạn khách hàng và liên hệ
$limit_data = $this->ci->db->query('
    SELECT
        date_debits,
        code_supership,
        status as note,
        IFNULL(collect, 0) as pstang,
        IFNULL(pay_transport, 0) + IFNULL(insurance, 0) + IFNULL(pay_refund, 0) - IFNULL(sale, 0) as psgiam,
        id,
        0 as debt
    FROM tblorders_shop '.$new_where_order.' order by date_debits desc '.(!empty($length) && $length >=0 ? ('limit '.($start).','.($length) ) : '').'
')->result_array();



$countAll = count($All_data);

if($length >= 0 && (($start + $length) <  $countAll))
{
    $limit_data_last = $this->ci->db->query('
        SELECT
            date_debits,
            code_supership,
            status as note,
            IFNULL(collect, 0) as pstang,
            IFNULL(pay_transport, 0) + IFNULL(insurance, 0) + IFNULL(pay_refund, 0) - IFNULL(sale, 0) as psgiam,
            id,
            0 as debt
        FROM tblorders_shop '.$new_where_order.' order by date_debits desc limit '.($start + $length).','.$countAll.'
    ')->result_array();
}



$draw = $this->ci->input->post('draw');

$result = array(
    'rResult' => $limit_data,
    'output' => array(
        "draw" => $draw,
        "iTotalRecords" => count($All_data),
        "iTotalDisplayRecords" => count($All_data),
        "aaData" => array()
    )

);
$output  = $result['output'];
$rResult = $result['rResult'];


if(!empty($limit_data_last))
{
    $count_DataLast = count($limit_data_last);
    $debt_array = [];
    for ( $i = ($count_DataLast - 1); $i >= 0 ; $i--) {

        $debt += !empty($limit_data_last[$i]['pstang']) ? $limit_data_last[$i]['pstang'] : 0;
        $debt -= !empty($limit_data_last[$i]['psgiam']) ? $limit_data_last[$i]['psgiam'] : 0;
    }
}


$count_rResult = count($rResult);
$debt_array = [];
for ( $i = ($count_rResult - 1); $i >= 0 ; $i--) {

    $debt += $rResult[$i]['pstang'];
    $debt -= $rResult[$i]['psgiam'];
    $debt_array[$i] = $debt;
}



foreach ($rResult as $key => $aRow) {
    $row = [];
    $row[] = _dt($aRow['date_debits']);
    $row[] = $aRow['code_supership'];
    $row[] = $aRow['note'];
    $row[] = number_format_data($aRow['pstang']);
    $row[] = number_format_data($aRow['psgiam']);
    $row[] = number_format_data($debt_array[$key]);

    $output['aaData'][] = $row;
}
