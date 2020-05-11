<?php
defined('BASEPATH') OR exit('No direct script access allowed');





$debt = 0;
$debt = get_option('debt_start');
if($this->ci->input->post('date_start_code_sum')) {

    $dateStart = $this->ci->input->post('date_start_code_sum');

    $where_cash_debt = ['AND groups = 14'];

    $where_object_debt = [];

    $where_debt = [];



    $where_cash_debt[] =  'AND DATE_FORMAT(date,"%Y-%m-%d") < "'.to_sql_date($dateStart).'"';

    $where_object_debt[] =  'AND DATE_FORMAT(date,"%Y-%m-%d") < "'.to_sql_date($dateStart).'"';

    $where_debt[] =  'AND DATE_FORMAT(control_date,"%Y-%m-%d") < "'.to_sql_date($dateStart).'"';

    $where_debt[] =  'AND control_date is not null';
//    $where_debt[] =  'AND tblorders_shop.shop = (select tblcustomers.customer_shop_code from tblcustomers where tblcustomers.customer_shop_code = tblorders_shop.shop)';
    $where_debt[] =  'AND tblorders_shop.is_hd_branch = 1';
    $where_debt[] =  'AND tblorders_shop.status != "Hủy"';

    if(!empty($where_cash_debt))
    {
        $where_cash_debt = trim(implode(' ',$where_cash_debt), 'AND');
        $where_cash_debt = 'where '.$where_cash_debt;
    }


    if(!empty($where_object_debt))
    {
        $where_object_debt = trim(implode(' ',$where_object_debt), 'AND');
        $where_object_debt = 'where '.$where_object_debt;
    }

    if(!empty($where_debt))
    {
        $where_debt = trim(implode(' ',$where_debt), 'AND');
        $where_debt = 'where '.$where_debt;
    }


    $DataDebt = $this->ci->db->query('
        SELECT
            date as date,
            code as code,
            note as note,
            if (type = 1, price, 0) as pstang,
            if (type = 0, price, 0) as psgiam,
            id as id,
            0 as debt
        FROM tblcash_book '.$where_cash_debt.' 
            UNION
        SELECT
            control_date as date,
            code_supership as code,
            status as note,
            (if((
                control_date != null ||
                status = "Không Giao Được" ||
                status = "Xác Nhận Hoàn" ||
                status = "Đang Trả Hàng" ||
                status = "Đang Chuyển Kho Trả" ||
                status = "Đã Đối Soát Trả Hàng" ||
                status = "Đã Chuyển Kho Trả" ||
                status = "Đã Trả Hàng" ||
                status = "Hoãn Trả Hàng"
               ), 0, collect)) as pstang,
            (if( (type != "Nội Tỉnh" && (control_date != null ||  status = "Không Giao Được" || status = "Xác Nhận Hoàn" ||status = "Đang Trả Hàng" ||status = "Đang Chuyển Kho Trả" ||status = "Đã Đối Soát Trả Hàng" ||status = "Đã Chuyển Kho Trả") ), ((IFNULL(pay_transport, 0) + IFNULL(insurance, 0) + IFNULL(pay_refund, 0) - IFNULL(sale, 0)) * 1), (IFNULL(pay_transport, 0) + IFNULL(insurance, 0) + IFNULL(pay_refund, 0) - IFNULL(sale, 0)))) as psgiam,
            id,
            0 as debt
        FROM tblorders_shop '.$where_debt.' order by date asc
    ')->result_array();
    foreach($DataDebt as $key => $value) {
        $debt += $value['pstang'];
        $debt -= $value['psgiam'];
    }
}

//(if( (type != "Nội Tỉnh" && (control_date != null ||  status = "Không Giao Được" || status = "Xác Nhận Hoàn" ||status = "Đang Trả Hàng" ||status = "Đang Chuyển Kho Trả" ||status = "Đã Đối Soát Trả Hàng" ||status = "Đã Chuyển Kho Trả") ), ((IFNULL(pay_transport, 0) + IFNULL(insurance, 0) + IFNULL(pay_refund, 0) - IFNULL(sale, 0)) * 1), (IFNULL(pay_transport, 0) + IFNULL(insurance, 0) + IFNULL(pay_refund, 0) - IFNULL(sale, 0)))) as psgiam,











$start = $this->ci->input->post('start');
$length = $this->ci->input->post('length');

$where = [];
$where_cash = ['AND groups = 14'];
//$where_order = ['AND tblorders_shop.shop = (select tblcustomers.customer_shop_code from tblcustomers where tblcustomers.customer_shop_code = tblorders_shop.shop)'];
$where_order = ['AND tblorders_shop.is_hd_branch = 1'];
$where_order[] =  'AND control_date is not null';
$where_order[] =  'AND tblorders_shop.status != "Hủy"';
if($this->ci->input->post('date_start_code_sum'))
{
    $dateStart = $this->ci->input->post('date_start_code_sum');
    $where[] =  'AND DATE_FORMAT(date,"%Y-%m-%d") >= "'.to_sql_date($dateStart).'"';
    $where_cash[] =  'AND DATE_FORMAT(date,"%Y-%m-%d") >= "'.to_sql_date($dateStart).'"';
    $where_order[] =  'AND DATE_FORMAT(control_date,"%Y-%m-%d") >= "'.to_sql_date($dateStart).'"';
}
if($this->ci->input->post('date_end_code_sum'))
{
    $dateEnd = $this->ci->input->post('date_end_code_sum');
    $where[] =  'AND DATE_FORMAT(date,"%Y-%m-%d") <= "'.to_sql_date($dateEnd).'"';
    $where_cash[] =  'AND DATE_FORMAT(date,"%Y-%m-%d") <= "'.to_sql_date($dateEnd).'"';
    $where_order[] =  'AND DATE_FORMAT(control_date,"%Y-%m-%d") <= "'.to_sql_date($dateEnd).'"';
}


$new_where = trim(implode(' ',$where), 'AND');


$new_where_cash = trim(implode(' ',$where_cash), 'AND');


$new_where_order = trim(implode(' ',$where_order), 'AND');

if(!empty($new_where))
{
    $new_where = 'where '.$new_where;
}
if(!empty($new_where_cash))
{
    $new_where_cash = 'where '.$new_where_cash;
}

if(!empty($new_where_order))
{
    $new_where_order = 'where '.$new_where_order;
}

$All_data = $this->ci->db->query('
    SELECT
        date as date,
        code as code,
        note as note,
        if (type = 1, price, 0) as pstang,
        if (type = 0, price, 0) as psgiam,
        id as id,
        0 as debt
    FROM tblcash_book '.$new_where_cash.' 
        UNION
    SELECT
        control_date as date,
        code_supership as code,
        status as note,
        (if((
                control_date != null ||
                status = "Không Giao Được" ||
                status = "Xác Nhận Hoàn" ||
                status = "Đang Trả Hàng" ||
                status = "Đang Chuyển Kho Trả" ||
                status = "Đã Đối Soát Trả Hàng" ||
                status = "Đã Chuyển Kho Trả" ||
                status = "Đã Trả Hàng" ||
                status = "Hoãn Trả Hàng"
           ),0,collect)) as pstang,
        (if( (type != "Nội Tỉnh" && (control_date != null ||  status = "Không Giao Được" || status = "Xác Nhận Hoàn" ||status = "Đang Trả Hàng" ||status = "Đang Chuyển Kho Trả" ||status = "Đã Đối Soát Trả Hàng" ||status = "Đã Chuyển Kho Trả") ), ((IFNULL(pay_transport, 0) + IFNULL(insurance, 0) + IFNULL(pay_refund, 0) - IFNULL(sale, 0)) * 1), (IFNULL(pay_transport, 0) + IFNULL(insurance, 0) + IFNULL(pay_refund, 0) - IFNULL(sale, 0)))) as psgiam,
        id,
        0 as debt
    FROM tblorders_shop '.$new_where_order.' order by date desc
')->result_array();

// lấy giới hạn khách hàng và liên hệ
$limit_data = $this->ci->db->query('
    SELECT
        date as date,
        code as code,
        note as note,
        if (type = 1, price, 0) as pstang,
        if (type = 0, price, 0) as psgiam,
        id as id,
        0 as debt
    FROM tblcash_book '.$new_where_cash.'
        UNION
    SELECT
        control_date as date,
        code_supership as code,
        status as note,
        (if((
                control_date != null ||
                status = "Không Giao Được" ||
                status = "Xác Nhận Hoàn" ||
                status = "Đang Trả Hàng" ||
                status = "Đang Chuyển Kho Trả" ||
                status = "Đã Đối Soát Trả Hàng" ||
                status = "Đã Chuyển Kho Trả" ||
                status = "Đã Trả Hàng" ||
                status = "Hoãn Trả Hàng"
           ),0,collect)) as pstang,
        (if( (type != "Nội Tỉnh" && (control_date != null ||  status = "Không Giao Được" || status = "Xác Nhận Hoàn" ||status = "Đang Trả Hàng" ||status = "Đang Chuyển Kho Trả" ||status = "Đã Đối Soát Trả Hàng" ||status = "Đã Chuyển Kho Trả") ), ((IFNULL(pay_transport, 0) + IFNULL(insurance, 0) + IFNULL(pay_refund, 0) - IFNULL(sale, 0)) * 1), (IFNULL(pay_transport, 0) + IFNULL(insurance, 0) + IFNULL(pay_refund, 0) - IFNULL(sale, 0)))) as psgiam,
        id,
        0 as debt
    FROM tblorders_shop '.$new_where_order.' order by date desc '.(!empty($length) && $length >=0 ? ('limit '.($start).','.($length) ) : '').'
')->result_array();



$countAll = count($All_data);

if($length >= 0 && (($start + $length) <  $countAll))
{
    $limit_data_last = $this->ci->db->query('
        SELECT
            date as date,
            code as code,
            note as note,
            if (type = 1, price, 0) as pstang,
            if (type = 0, price, 0) as psgiam,
            id as id,
            0 as debt
        FROM tblcash_book '.$new_where_cash.'
            UNION
        SELECT
            control_date as date,
            code_supership as code,
            status as note,
            (if((
                control_date != null ||
                status = "Không Giao Được" ||
                status = "Xác Nhận Hoàn" ||
                status = "Đang Trả Hàng" ||
                status = "Đang Chuyển Kho Trả" ||
                status = "Đã Đối Soát Trả Hàng" ||
                status = "Đã Chuyển Kho Trả" ||
                status = "Đã Trả Hàng" ||
                status = "Hoãn Trả Hàng"
            ),0,collect)) as pstang,
            (if( (type != "Nội Tỉnh" && (control_date != null ||  status = "Không Giao Được" || status = "Xác Nhận Hoàn" ||status = "Đang Trả Hàng" ||status = "Đang Chuyển Kho Trả" ||status = "Đã Đối Soát Trả Hàng" ||status = "Đã Chuyển Kho Trả") ), ((IFNULL(pay_transport, 0) + IFNULL(insurance, 0) + IFNULL(pay_refund, 0) - IFNULL(sale, 0)) * 1), (IFNULL(pay_transport, 0) + IFNULL(insurance, 0) + IFNULL(pay_refund, 0) - IFNULL(sale, 0)))) as psgiam,
            id,
            0 as debt
        FROM tblorders_shop '.$new_where_order.' order by date desc limit '.($start + $length).','.$countAll.'
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

        $debt += $limit_data_last[$i]['pstang'];
        $debt -= $limit_data_last[$i]['psgiam'];
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
    $row[] = _dt($aRow['date']);
    $row[] = $aRow['code'];
    $row[] = $aRow['note'];
    $row[] = number_format_data($aRow['pstang']);
    $row[] = number_format_data($aRow['psgiam']);
    $row[] = number_format_data($debt_array[$key]);

    $output['aaData'][] = $row;
}
