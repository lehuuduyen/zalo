    <?php
    defined('BASEPATH') or exit('No direct script access allowed');
    $hasPermissionDelete = has_permission('debt_suppliers', '', 'delete');

    $beginMonth =  '';
    $endMonth   =  '';
        $months_report = $this->ci->input->post('report_months');

        if ($months_report != '') {
            $custom_date_select = '';
            if (is_numeric($months_report)) {
                // Last month
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
$id_account = $this->ci->input->post('id_account_bank');
$aColumns = [
        'day_vouchers as date',
        'concat(tblstaff.firstname," ",tblstaff.lastname) as fullname',
        'concat(tblpay_slip.prefix,"-",tblpay_slip.code) as code',
        'tblpay_slip.note as note',
        '0 as thu',
        'tblpay_slip.payment as chi',
        '0 as sub_total'
    ];
    $sIndexColumn = 'id';
    $sTable       = 'tblpay_slip';
    $where        = [];
    if(!empty($beginMonth)&&!empty($endMonth))
    {
        array_push($where, 'AND day_vouchers >='.'"'.$beginMonth.' 00:00:00"');  
        array_push($where, 'AND day_vouchers <='.'"'.$endMonth.' 23:59:59"');
    }
    array_push($where, 'AND payment_mode ='.$id_account);
    $filter = [];
    $join = [
        'LEFT JOIN tblsuppliers  ON tblsuppliers.id=tblpay_slip.id_supplierss',
        'LEFT JOIN tblstaff  ON tblstaff.staffid=tblpay_slip.staff_id'
    ];
    $group_by = '';
    $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
        '0 as objects','tblpay_slip.type','id_old','3 as checks'
    ],$group_by);
    $output  = $result['output'];
    $rResult = $result['rResult'];    


    $aColumns_other = [
        'tblother_payslips.date as date',
        'concat(tblstaff.firstname," ",tblstaff.lastname) as fullname',
        'concat(tblother_payslips.prefix,"-",tblother_payslips.code) as code',
        'tblother_payslips.note as note',
        '0 as thu',
        'tblother_payslips.total as chi',
        '0 as sub_total'
    ];
    $sIndexColumn_other = 'id';
    $sTable_other       = 'tblother_payslips';
    $where_other        = [];
    if(!empty($beginMonth)&&!empty($endMonth))
    {
        array_push($where_other, 'AND tblother_payslips.date >='.'"'.$beginMonth.' 00:00:00"');  
        array_push($where_other, 'AND tblother_payslips.date <='.'"'.$endMonth.' 23:59:59"');
    }
    array_push($where_other, 'AND payment_modes ='.$id_account);
    $filter_other = [];
    $join_other = [
        'LEFT JOIN tblstaff  ON tblstaff.staffid=tblother_payslips.staff_id'
    ];
    $group_by_other = '';
    $result_other = data_tables_init($aColumns_other, $sIndexColumn_other, $sTable_other, $join_other, $where_other, [
        'objects as objects','3 as type','type_vouchers','vouchers_id','3 as checks'
    ],$group_by_other);
    $output_other  = $result_other['output'];
    $rResult_other = $result_other['rResult'];


    $aColumns_thu = [
        'date_vouchers as date',
        'concat(tblstaff.firstname," ",tblstaff.lastname) as fullname',
        'code_vouchers as code',
        'tblvouchers_coupon.note as note',
        'tblvouchers_coupon.payment as thu',
        '0 as chi',
        '0 as sub_total'
    ];
    $sIndexColumn_thu = 'id';
    $sTable_thu       = 'tblvouchers_coupon';
    $where_thu        = [];
    if(!empty($beginMonth)&&!empty($endMonth))
    {
        array_push($where_thu, 'AND date_vouchers >='.'"'.$beginMonth.' 00:00:00"');  
        array_push($where_thu, 'AND date_vouchers <='.'"'.$endMonth.' 23:59:59"');
    }
    array_push($where_thu, 'AND payment_mode ='.$id_account);
    $filter_thu = [];
    $join_thu = [
        'LEFT JOIN tblclients  ON tblclients.userid=tblvouchers_coupon.customer',
        'LEFT JOIN tblstaff  ON tblstaff.staffid=tblvouchers_coupon.staff'
    ];
    $group_by_thu = '';
    $result_thu = data_tables_init($aColumns_thu, $sIndexColumn_thu, $sTable_thu, $join_thu, $where_thu, [
        '0 as objects','2 as type','1 as checks'
    ],$group_by_thu);
    $output_thu  = $result_thu['output'];
    $rResult_thu = $result_thu['rResult'];


    $aColumns_other_thu = [
        'tblother_payslips_coupon.date as date',
        'concat(tblstaff.firstname," ",tblstaff.lastname) as fullname',
        'concat(tblother_payslips_coupon.prefix,"-",tblother_payslips_coupon.code) as code',
        'tblother_payslips_coupon.note as note',
        'tblother_payslips_coupon.total as thu',
        '0 as chi',
        '0 as sub_total'

    ];
    $sIndexColumn_other_thu = 'id';
    $sTable_other_thu       = 'tblother_payslips_coupon';
    $where_other_thu        = [];
    if(!empty($beginMonth)&&!empty($endMonth))
    {
        array_push($where_other_thu, 'AND tblother_payslips_coupon.date >='.'"'.$beginMonth.' 00:00:00"');  
        array_push($where_other_thu, 'AND tblother_payslips_coupon.date <='.'"'.$endMonth.' 23:59:59"');
    }
    array_push($where_other_thu, 'AND payment_modes ='.$id_account);
    $filter_other_thu = [];
    $join_other_thu = [
        'LEFT JOIN tblstaff  ON tblstaff.staffid=tblother_payslips_coupon.staff_id'
    ];
    $group_by_other_thu = '';
    $result_other_thu = data_tables_init($aColumns_other_thu, $sIndexColumn_other_thu, $sTable_other_thu, $join_other_thu, $where_other_thu, [
        'objects as objects','1 as type','type_vouchers','vouchers_id','1 as checks'
    ],$group_by_other_thu);
    $output_other_thu  = $result_other_thu['output'];
    $rResult_other_thu = $result_other_thu['rResult'];
        $aColumnsG=array(
            'date',
            'fullname',
            'code',
            'note',
            'thu',
            'chi',
            'sub_total'
        );
        if(!empty($rResult_other_thu))
        {
        $rResult_thu=array_merge($rResult_thu,$rResult_other_thu);   
        }
        if(!empty($rResult_thu))
        {
        $rResult=array_merge($rResult,$rResult_thu);   
        }
        if(!empty($rResult_other))
        {
        $rResult=array_merge($rResult,$rResult_other);   
        }
        usort($rResult, ch_make_cmp(['date' => "asc"]));
        $output['iTotalRecords']=$output['iTotalRecords']+$output_thu['iTotalRecords']+$output_other_thu['iTotalRecords']+$output_other['iTotalRecords'];
        $output['iTotalDisplayRecords']=$output['iTotalDisplayRecords']+$output_thu['iTotalDisplayRecords']+$output_other_thu['iTotalDisplayRecords']+$output_other['iTotalDisplayRecords'];
    $j=0;

$footer_data = array(
    'thu' => 0,
    'chi' => 0,
    'tong'=> 0,
);
        $opening_balance = get_table_where('tblpayment_modes',array('id'=>$id_account),'','row');
        $existing_period = getStart_coupons($id_account,$beginMonth)-getStart_payslips($id_account,$beginMonth)+$opening_balance->opening_balance;
        $sub_total = $existing_period;
        $footer_data['tong']=$sub_total;
        $row=array(
                'SỐ DƯ ĐẦU KỲ - '.$opening_balance->name,
                '',
                '',
                '',
                '',
                '',
                number_format($existing_period)
            );
        $row['DT_RowClass'] = 'alert-headertext bold warning';

        for ($i=0 ; $i<count($aColumns) ; $i++ ){
            $row[]="";
            }
        $output['aaData'][] = $row;
foreach ($rResult as $r => $aRow) {
    $row = array();
    $j++;

    for ($i = 0; $i < count($aColumnsG); $i++) {
        if (strpos($aColumnsG[$i], 'as') !== false && !isset($aRow[$aColumnsG[$i]])) {
            $_data = $aRow[strafter($aColumnsG[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumnsG[$i]];
        }
        if ($aColumnsG[$i] == 'date') {
            $_data=_d($_data);
        }
        if ($aColumnsG[$i] == 'thu') {
            $footer_data['thu']+=$aRow['thu']; 
            $sub_total+=$aRow['thu'];
            $_data=number_format($_data);
        }
        if ($aColumnsG[$i] == 'chi') {
            $sub_total-=$aRow['chi'];
            $_data=number_format($_data);
        }
        if ($aColumnsG[$i] == 'sub_total') {
            $footer_data['tong']=$sub_total; 
            $_data=number_format($sub_total);
        }

        $row[] = $_data;
    }
    $output['aaData'][] = $row;
}
foreach ($footer_data as $key => $total) {
    $footer_data[$key] = number_format($total);
}
$output['sums'] = $footer_data;