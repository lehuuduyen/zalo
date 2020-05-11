    <?php
    defined('BASEPATH') or exit('No direct script access allowed');
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
            // $this->db->where($custom_date_select);
        }

    $aColumns = [
        'tblpurchase_order.date_create',
        'tblpurchase_order.date',
        'concat(tblpurchase_order.prefix,"",tblpurchase_order.code) as code',
        'tblpurchase_order.note',
        '3',
        'tblpurchase_order_items.quantity_suppliers',
        'tblpurchase_order_items.price_suppliers',
        '(tblpurchase_order_items.promotion_expected + tblpurchase_order_items.total_suppliers) as total_suppliers',
        'tblpurchase_order_items.promotion_expected',
        'tblpurchase_order.discount_percent_suppliers',
        '(tblpurchase_order.amount_paid + tblpurchase_order.price_other_expenses)',
        '10',
    ];
    $sIndexColumn = 'id';
    $sTable       = 'tblpurchase_order_items';
    $where        = [];

    $filter = [];
    $join = [
        'LEFT JOIN tblpurchase_order  ON tblpurchase_order.id=tblpurchase_order_items.id_purchase_order'
    ];
    array_push($where, 'AND tblpurchase_order.id IN(select id_order from tblimport)');
    $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['tblpurchase_order.id as id_orders,tblpurchase_order_items.product_id as product_id,tblpurchase_order_items.type as type',
    ]);
    $output  = $result['output'];
    $rResult = $result['rResult'];
    $j=0;
    usort($rResult, ch_make_cmp(['id_orders'=> "asc"]));
foreach ($rResult as $key => $aRow) {
    $j++;
    $row = array();
    if($key == 0)
    {
            $data =  $aRow['id_orders'];
            for ($i = 0; $i < count($aColumns); $i++) {
            $_data = '';
            if($aColumns[$i]=='tblpurchase_order.date_create')
            {
                $_data = $aRow['tblpurchase_order.date_create'];
            }
            if($aColumns[$i]=='tblpurchase_order.date')
            {
                $_data = $aRow['tblpurchase_order.date'];
            }
            if($aColumns[$i]=='concat(tblpurchase_order.prefix,"",tblpurchase_order.code) as code')
            {
                $_data = $aRow['code'];
            }
            if($aColumns[$i]=='tblpurchase_order.note')
            {
                $_data = $aRow['tblpurchase_order.note'];
            }
            $row[] = $_data;
            }
            $row['DT_RowClass'] = 'alert-header bold warning';
            $output['aaData'][] = $row;
    }else
    {
        if($aRow['id_orders'] != $data)
        {
            $data =  $aRow['id_orders'];
            for ($i = 0; $i < count($aColumns); $i++) {
            $_data = '';
            if($aColumns[$i]=='tblpurchase_order.date_create')
            {
                $_data = $aRow['tblpurchase_order.date_create'];
            }
            if($aColumns[$i]=='tblpurchase_order.date')
            {
                $_data = $aRow['tblpurchase_order.date'];
            }
            if($aColumns[$i]=='concat(tblpurchase_order.prefix,"",tblpurchase_order.code) as code')
            {
                $_data = $aRow['code'];
            }
            if($aColumns[$i]=='tblpurchase_order.note')
            {
                $_data = $aRow['tblpurchase_order.note'];
            }
            $row[] = $_data;
            }
            $row['DT_RowClass'] = 'alert-header bold warning';
            $output['aaData'][] = $row;
        }

    }
    $row = array();
    $get_items = get_items($aRow['product_id'],$aRow['type']);
    for ($i = 0; $i < count($aColumns); $i++) {
        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }
        $data =  $aRow['id_orders'];
        if($aColumns[$i]=='tblpurchase_order.date_create')
        {
            $_data = '';
        }
        if($aColumns[$i]=='tblpurchase_order.date')
        {
            $_data = '';
        }
        if($aColumns[$i]=='tblpurchase_order_items.quantity_suppliers')
        {
            $_data = formatNumber($aRow['tblpurchase_order_items.quantity_suppliers']);
        }
        if($aColumns[$i]=='tblpurchase_order_items.price_suppliers')
        {
            $_data = number_format($aRow['tblpurchase_order_items.price_suppliers']);
        }
        if($aColumns[$i]=='tblpurchase_order_items.promotion_expected')
        {
            $_data = number_format($aRow['tblpurchase_order_items.promotion_expected']);
        }
        if($aColumns[$i]=='(tblpurchase_order_items.promotion_expected + tblpurchase_order_items.total_suppliers) as total_suppliers')
        {
            $_data = number_format($aRow['total_suppliers']);
        }
        if($aColumns[$i]=='concat(tblpurchase_order.prefix,"",tblpurchase_order.code) as code')
        {
            $_data = '';
        }
        if($aColumns[$i]=='tblpurchase_order.note')
        {
            $_data = $get_items->name.' ('.$get_items->code.') <br>'.format_item_purchases($aRow['type']);
        }
        if($aColumns[$i]=='3')
        {
            $_data = $get_items->unit_name;
        }
        $row[] = $_data;
    }
    $output['aaData'][] = $row;
}
