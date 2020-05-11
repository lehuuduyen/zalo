<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$months_report = $this->ci->input->post('report_months');
$CI =& get_instance();
if ($months_report != '') {
    $custom_date_select = '';
    if (is_numeric($months_report)) {
        if ($months_report == '1') {
            $beginMonth = date('Y-m-01', strtotime('first day of last month'));
            $endMonth   = date('Y-m-t', strtotime('last day of last month'));
        } else {
            $months_report = (int) $months_report;
            $months_report--;
            $beginMonth = date('Y-m-01', strtotime("-$months_report MONTH"));
            $endMonth   = date('Y-m-t');
        }
    } elseif ($months_report == 'this_month') {
        $beginMonth = date('Y-m-01');
        $endMonth   = date('Y-m-t');
    } elseif ($months_report == 'this_year') {
        $beginMonth = date('Y-m-d', strtotime(date('Y-01-01')));
        $endMonth   = date('Y-m-d', strtotime(date('Y-12-31')));
    } elseif ($months_report == 'last_year') {
        $beginMonth = date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01')));
        $endMonth   = date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31')));
    } elseif ($months_report == 'custom') {
        $from_date = to_sql_date($this->ci->input->post('report_from'));
        $to_date   = to_sql_date($this->ci->input->post('report_to'));
        if ($from_date == $to_date) {
            $beginMonth =  $to_date;
            $endMonth   =  $to_date;
        } else {
            $beginMonth =  $from_date;
            $endMonth   =  $to_date;
        }
    }
}

$aColumns     = array(
    'tblclients.zcode',
    'tblclients.company',
    '3',
    '4',
    '5',
    '6',
    '7',
    '8'
);
$sIndexColumn = "userid";
$sTable       = 'tblclients';
$where        = array();

$customer_select = $this->ci->input->post('customer_select');
if($customer_select)
{
    $customer_id = explode('__', $customer_select);
    if(is_numeric($customer_id[1])) {
        array_push($where, 'AND tblclients.userid = '.$customer_id[1]);
    }
}

$join         = array(
    // 'LEFT JOIN tbl_orders ON tbl_orders.customer_id = tblitems.id ',
);
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array(
    'tblclients.userid',
    'tblclients.debt_begin',
));
$output       = $result['output'];
$rResult      = $result['rResult'];

$output = $output;
$output['iTotalRecords'] = $output['iTotalRecords'];
$output['iTotalDisplayRecords'] = $output['iTotalDisplayRecords'];
$footer_data['total1'] = 0; //nợ đầu kỳ
$footer_data['total2'] = 0; //có đầu kỳ
$footer_data['total3'] = 0; //nợ phát sinh
$footer_data['total4'] = 0; //có phát sinh
$footer_data['total5'] = 0; //nợ cuối kỳ
$footer_data['total6'] = 0; //có cuối kỳ
foreach ($rResult as $aRow) {
    $row = array();
    $col1 = 0;
    $col2 = 0;
    $col3 = 0;
    $col4 = 0;
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if($aColumns[$i] == '3')
        {
            $debt_begin = 0;
            if(!empty($beginMonth)&&!empty($endMonth)) {
                //tổng nợ
                $CI->db->select('tbl_orders.*');
                $CI->db->where('tbl_orders.customer_id', $aRow['userid']);
                $CI->db->where('tbl_orders.status', 'approved');
                $CI->db->where('tbl_orders.date < ', $beginMonth);
                $get_all_order = $CI->db->get('tbl_orders')->result_array();
                foreach ($get_all_order as $key => $value) {
                    $debt_begin += $value['grand_total'] - $value['total_payment'];
                }
                $debt_begin = $aRow['debt_begin'] + $debt_begin;

                //tổng thu khác
                $total_payslips = 0;
                $CI->db->select('tblother_payslips_coupon.*');
                $CI->db->where('tblother_payslips_coupon.objects', 1);
                $CI->db->where('tblother_payslips_coupon.objects_id', $aRow['userid']);
                $CI->db->where('tblother_payslips_coupon.status', 1);
                $CI->db->where('tblother_payslips_coupon.date < ', $beginMonth);
                $get_payslips = $CI->db->get('tblother_payslips_coupon')->result_array();
                foreach ($get_payslips as $key => $value) {
                    $total_payslips += $value['total'];
                }
                $debt_begin = $debt_begin - $total_payslips;
            }
            else {
                $debt_begin = $aRow['debt_begin'];
            }

            if($debt_begin > 0) {
                $_data = number_format($debt_begin);
                $col1 = $debt_begin;
            }
            else {
                $_data = 0;
                $col1 = 0;
            }
            $footer_data['total1'] += $col1;
        }
        else if($aColumns[$i] == '4')
        {
            $debt_begin = 0;
            if(!empty($beginMonth)&&!empty($endMonth)) {
                //tổng nợ
                $CI->db->select('tbl_orders.*');
                $CI->db->where('tbl_orders.customer_id', $aRow['userid']);
                $CI->db->where('tbl_orders.status', 'approved');
                $CI->db->where('tbl_orders.date < ', $beginMonth);
                $get_all_order = $CI->db->get('tbl_orders')->result_array();
                foreach ($get_all_order as $key => $value) {
                    $debt_begin += $value['grand_total'] - $value['total_payment'];
                }
                $debt_begin = $aRow['debt_begin'] + $debt_begin;

                //tổng thu khác
                $total_payslips = 0;
                $CI->db->select('tblother_payslips_coupon.*');
                $CI->db->where('tblother_payslips_coupon.objects', 1);
                $CI->db->where('tblother_payslips_coupon.objects_id', $aRow['userid']);
                $CI->db->where('tblother_payslips_coupon.status', 1);
                $CI->db->where('tblother_payslips_coupon.date < ', $beginMonth);
                $get_payslips = $CI->db->get('tblother_payslips_coupon')->result_array();
                foreach ($get_payslips as $key => $value) {
                    $total_payslips += $value['total'];
                }
                $debt_begin = $debt_begin - $total_payslips;
            }
            else {
                $debt_begin = $aRow['debt_begin'];
            }

            if($debt_begin < 0) {
                $_data = number_format(abs($debt_begin));
                $col2 = abs($debt_begin);
            }
            else {
                $_data = 0;
                $col2 = 0;
            }
            $footer_data['total2'] += abs($col2);
        }
        else if($aColumns[$i] == '5')
        {
            $debt_begin = 0;
            $CI->db->select('tbl_orders.*');
            $CI->db->where('tbl_orders.customer_id', $aRow['userid']);
            $CI->db->where('tbl_orders.status', 'approved');
            if(!empty($beginMonth)&&!empty($endMonth)) {
                $CI->db->where('tbl_orders.date >= ', $beginMonth);
                $CI->db->where('tbl_orders.date <= ', $endMonth);
            }
            $get_all_order = $CI->db->get('tbl_orders')->result_array();

            foreach ($get_all_order as $key => $value) {
                $debt_begin += $value['grand_total'];
            }
            $_data = number_format($debt_begin);
            $col3 = $debt_begin;
            $footer_data['total3'] += $col3;
        }
        else if($aColumns[$i] == '6')
        {
            $debt_begin = 0;
            $CI->db->select('tbl_orders.*');
            $CI->db->where('tbl_orders.customer_id', $aRow['userid']);
            $CI->db->where('tbl_orders.status', 'approved');
            if(!empty($beginMonth)&&!empty($endMonth)) {
                $CI->db->where('tbl_orders.date >= ', $beginMonth);
                $CI->db->where('tbl_orders.date <= ', $endMonth);
            }
            $get_all_order = $CI->db->get('tbl_orders')->result_array();
            foreach ($get_all_order as $key => $value) {
                $debt_begin += $value['total_payment'];
            }

            //tổng thu khác
            $total_payslips = 0;
            $CI->db->select('tblother_payslips_coupon.*');
            $CI->db->where('tblother_payslips_coupon.objects_id', $aRow['userid']);
            $CI->db->where('tblother_payslips_coupon.status', 1);
            $CI->db->where('tblother_payslips_coupon.objects', 1);
            if(!empty($beginMonth)&&!empty($endMonth)) {
                $CI->db->where('tblother_payslips_coupon.date >= ', $beginMonth);
                $CI->db->where('tblother_payslips_coupon.date <= ', $endMonth);
            }
            $get_payslips = $CI->db->get('tblother_payslips_coupon')->result_array();
            foreach ($get_payslips as $key => $value) {
                $total_payslips += $value['total'];
            }
            $debt_begin = $debt_begin + $total_payslips;

            $_data = number_format($debt_begin);
            $col4 = $debt_begin;
            $footer_data['total4'] += $col4;
        }
        else if($aColumns[$i] == '7')
        {
            $total_col = $col1 + $col3 - $col2 - $col4;
            if($total_col > 0) {
                $_data = number_format($total_col);
                $footer_data['total5'] += $total_col;
            }
            else {
                $_data = 0;
                $footer_data['total5'] += 0;
            }
        }
        else if($aColumns[$i] == '8')
        {
            $total_col = $col1 + $col3 - $col2 - $col4;
            if($total_col < 0) {
                $_data = number_format(abs($total_col));
                $footer_data['total6'] += abs($total_col);
            }
            else {
                $_data = 0;
                $footer_data['total6'] += 0;
            }
        }

        $row[] = $_data;
    }
    $output['aaData'][] = $row;
}
$output['sums'] = $footer_data;
foreach ($footer_data as $key => $total) {
    $footer_data[$key] = number_format($total);
}
$output['sums'] = $footer_data;