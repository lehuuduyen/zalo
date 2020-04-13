    <?php

    defined('BASEPATH') or exit('No direct script access allowed');

    $hasPermissionDelete = has_permission('debt_suppliers', '', 'delete');

    $this->ci->db->query("SET sql_mode = ''");

    $aColumns = [
        'tblpurchase_order.id',
        'tblpurchase_order.date',
        'CONCAT(tblpurchase_order.prefix,"-",tblpurchase_order.code)',
        'tblpurchase_order.totalAll_suppliers as total_import',
        '(COALESCE(tblpurchase_order.amount_paid,0)+ COALESCE(tblpurchase_order.price_other_expenses,0) + COALESCE(tblpurchase_invoice.amount_paid,0))  as amount_paid_import',
        'tblpurchase_order.price_other_expenses as price_other_expenses_import',
        '7',
    ];
    $sIndexColumn = 'id';
    $sTable       = 'tblsuppliers';
    $where        = [];
    array_push($where, 'AND ((tblpurchase_order.status_pay != 2 AND tblpurchase_order.red_invoice = 0) or (tblpurchase_invoice.status != 2 AND tblpurchase_order.status_pay = 0))');
    $filter = [];
    $join = [
        'LEFT JOIN tblpurchase_order ON tblpurchase_order.suppliers_id=tblsuppliers.id',
        'LEFT JOIN tblpurchase_invoice ON tblpurchase_invoice.id=tblpurchase_order.red_invoice',
    ];
    array_push($where, 'AND tblpurchase_order.id IN(select id_order from tblimport)');
    array_push($where, 'AND tblsuppliers.id = ',$id);
    array_push($where, 'AND tblpurchase_order.totalAll_suppliers > 0 ');
    $date_start = to_sql_date($this->ci->input->post('date_start'));

    if(!empty($date_start))
    {
        array_push($where, 'AND tblpurchase_order.date >=','"'.$date_start.'"');
    }
    $date_end = to_sql_date($this->ci->input->post('date_end'));
    if(!empty($date_end))
    {
       array_push($where, 'AND tblpurchase_order.date <=', '"'.$date_end.'"');
    }
    $group_by = '';
    $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
        'red_invoice','tblpurchase_order.status',
    ],'',$group_by);
    $output  = $result['output'];
    $rResult = $result['rResult'];
    $j=0;
    foreach ($rResult as $aRow) {
    $row = array();
    $j++;
    for ($i = 0; $i < count($aColumns); $i++) {
        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }
        if ($aColumns[$i] == 'tblpurchase_order.date') {
        $_data =_dhau($aRow['tblpurchase_order.date']);
        }
        if ($aColumns[$i] == 'tblpurchase_order.id') {
          
                $importss = get_table_where('tblimport',array('id_order'=>$aRow['tblpurchase_order.id'],'warehouseman_id !='=>0),'','row');
                if(!empty($importss))
                {
                    if(($importss->status_pay != 2)&&($aRow['red_invoice'] == 0)&&($aRow['status'] > 2))
                    {    
                    $_data= '<div class="checkbox text-center"><input class="checkbox_ch" data-id="'.($aRow['total_import']-$aRow['amount_paid_import']).'" type="checkbox" value="' . $aRow['tblpurchase_order.id'] . '"><label></label></div>';
                    }else
                    {
                    $_data= '';
                    }
                }else
                {
                    $_data= '';
                }
        }
        if ($aColumns[$i] == '(COALESCE(tblpurchase_order.amount_paid,0)+ COALESCE(tblpurchase_order.price_other_expenses,0) + COALESCE(tblpurchase_invoice.amount_paid,0))  as amount_paid_import') {
            $_data = number_format($aRow['amount_paid_import'] - $aRow['price_other_expenses_import']);      
        }
        if ($aColumns[$i] == '7') {
        $_data =number_format($aRow['total_import']-$aRow['amount_paid_import']);
        }
        if ($aColumns[$i] == 'tblpurchase_order.price_other_expenses as price_other_expenses_import') {
        $_data =number_format($aRow['price_other_expenses_import']); 
        }
        if ($aColumns[$i] == 'tblpurchase_order.totalAll_suppliers as total_import') {
        $_data =number_format($aRow['total_import']);
        }
        $row[] = $_data;
    }
    $output['aaData'][] = $row;
}
 
