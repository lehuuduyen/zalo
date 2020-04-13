    <?php

    defined('BASEPATH') or exit('No direct script access allowed');

    $hasPermissionDelete = has_permission('debt_suppliers', '', 'delete');

    $this->ci->db->query("SET sql_mode = ''");

    $aColumns = [
        'tblsuppliers.id',
        'tblsuppliers.company',
        'SUM(tblpurchase_order.totalAll_suppliers) as total_import',
        '(COALESCE(SUM(tblpurchase_order.amount_paid),0)+ COALESCE(SUM(tblpurchase_order.price_other_expenses),0) + COALESCE(SUM(tblpurchase_invoice.amount_paid),0))  as amount_paid_import',
        'SUM(tblpurchase_order.price_other_expenses) as price_other_expenses_import',
        '4',
        '5',
        '6',
        '7',
    ];
    $sIndexColumn = 'id';
    $sTable       = 'tblsuppliers';
    $where        = [];
    $having = 'HAVING (total_import - amount_paid_import) > 0';
    array_push($where, 'AND ((tblpurchase_order.status_pay != 2 AND tblpurchase_order.red_invoice = 0) or (tblpurchase_invoice.status != 2 AND tblpurchase_order.status_pay = 0))');
    $filter = [];
    $join = [
        'LEFT JOIN tblpurchase_order ON tblpurchase_order.suppliers_id=tblsuppliers.id',
        'LEFT JOIN tblpurchase_invoice ON tblpurchase_invoice.id=tblpurchase_order.red_invoice',
    ];
    $suppliers_id = $this->ci->input->post('suppliers_id');
    if(!empty($suppliers_id))
    {
        array_push($where, 'AND tblsuppliers.id IN('.trim($suppliers_id,',').')');
    }
    $filterStatus = $this->ci->input->post('filterStatus');
    if($filterStatus == 1)
    {
        array_push($where, 'AND tblsuppliers.debt_limit > 0 AND tblsuppliers.debt_limit < ((select(SUM(tblpurchase_order.totalAll_suppliers)) from tblpurchase_order where tblpurchase_order.suppliers_id=tblsuppliers.id ) -(select((COALESCE(SUM(tblpurchase_order.amount_paid),0)+ COALESCE(SUM(tblpurchase_order.price_other_expenses),0) + COALESCE(SUM(tblpurchase_invoice.amount_paid),0))) from tblpurchase_order left JOIN tblpurchase_invoice ON tblpurchase_invoice.id=tblpurchase_order.red_invoice where ((tblpurchase_order.status_pay != 2 AND tblpurchase_order.red_invoice = 0) or (tblpurchase_invoice.status != 2 AND tblpurchase_order.status_pay = 0)) AND tblpurchase_order.suppliers_id=tblsuppliers.id))');
    }
    $group_by = 'GROUP BY tblsuppliers.id';
    $result = data_tables_init_having($aColumns, $sIndexColumn, $sTable, $join, $where, [

    ],'',$group_by,$having);
    $output  = $result['output'];
    $rResult = $result['rResult'];
    $j=0;
        $today = date('Y-m-d');
        $week30s = strtotime(date("Y-m-d", strtotime($today)) . " -30 day");
        $week30 = strftime("%Y-%m-%d", $week30s);
        $week60s = strtotime(date("Y-m-d", strtotime($today)) . " -60 day");
        $week60 = strftime("%Y-%m-%d", $week60s);
        $week90s = strtotime(date("Y-m-d", strtotime($today)) . " -90 day");
        $week90 = strftime("%Y-%m-%d", $week90s);

        $footer_data = array(
            'debt' => 0,
            'payment' => 0,
            'left' => 0,
        );

foreach ($rResult as $aRow) {
    $row = array();
    $j++;
    for ($i = 0; $i < count($aColumns); $i++) {
        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }
        if ($aColumns[$i] == 'tblsuppliers.id') {
        $_data ='<div class="text-center">'.$j.'</div>';
        }
        if ($aColumns[$i] == '(COALESCE(SUM(tblpurchase_order.amount_paid),0)+ COALESCE(SUM(tblpurchase_order.price_other_expenses),0) + COALESCE(SUM(tblpurchase_invoice.amount_paid),0))  as amount_paid_import') {
            $_data = number_format($aRow['amount_paid_import'] - $aRow['price_other_expenses_import']);      
        }
        if ($aColumns[$i] == '7') {
        $_data =number_format($aRow['total_import']-$aRow['amount_paid_import']);
        $footer_data['left']+=$aRow['total_import']-$aRow['amount_paid_import'];
        }
        if ($aColumns[$i] == 'SUM(tblpurchase_order.price_other_expenses) as price_other_expenses_import') {
        $_data =number_format($aRow['price_other_expenses_import']); 
        $footer_data['payment']+=$aRow['amount_paid_import'];  
        }
        if ($aColumns[$i] == 'SUM(tblpurchase_order.totalAll_suppliers) as total_import') {
        $_data =number_format($aRow['total_import']);
        $footer_data['debt']+=$aRow['total_import'];     
        }
        if ($aColumns[$i] == '4') {
            $whereJoin=array();
            $whereJoin['where']=array(
              'tblpurchase_order.suppliers_id' =>$aRow['tblsuppliers.id'],
              'tblpurchase_order.date >=' =>$week30,
              'tblpurchase_order.date <=' =>$today,
            );
            $whereJoin['where_or']="((tblpurchase_order.status_pay != 2 AND tblpurchase_order.red_invoice = 0) or (tblpurchase_invoice.status != 2 AND tblpurchase_order.status_pay = 0))";
            $whereJoin['join']=array('tblpurchase_invoice,tblpurchase_invoice.id=tblpurchase_order.red_invoice,left');
            $whereJoin['field']='totalAll_suppliers';
            $subtotal=sum_from_table_join('tblpurchase_order',$whereJoin);

            $whereJoin1=array();
            $whereJoin1['where']=array(
              'tblpurchase_order.suppliers_id' =>$aRow['tblsuppliers.id'],
              'tblpurchase_order.date >=' =>$week30,
              'tblpurchase_order.date <=' =>$today,
            );
            $whereJoin1['where_or']="((tblpurchase_order.status_pay != 2 AND tblpurchase_order.red_invoice = 0) or (tblpurchase_invoice.status != 2 AND tblpurchase_order.status_pay = 0))";
            $whereJoin1['join']=array('tblpurchase_invoice,tblpurchase_invoice.id=tblpurchase_order.red_invoice,left');
            $whereJoin1['field']='tblpurchase_order.amount_paid';
            $amount_paid=sum_from_table_join('tblpurchase_order',$whereJoin1);

            $whereJoin2=array();
            $whereJoin2['where']=array(
              'tblpurchase_order.suppliers_id' =>$aRow['tblsuppliers.id'],
              'tblpurchase_order.date >=' =>$week30,
              'tblpurchase_order.date <=' =>$today,
            );
            $whereJoin2['where_or']="((tblpurchase_order.status_pay != 2 AND tblpurchase_order.red_invoice = 0) or (tblpurchase_invoice.status != 2 AND tblpurchase_order.status_pay = 0))";
            $whereJoin2['join']=array('tblpurchase_invoice,tblpurchase_invoice.id=tblpurchase_order.red_invoice,left');
            $whereJoin2['field']='tblpurchase_order.price_other_expenses';
            $amount_paid_invoice=sum_from_table_join('tblpurchase_order',$whereJoin2);

            $_data =number_format($subtotal - $amount_paid -$amount_paid_invoice);
        }
        if ($aColumns[$i] == '5') {
            $whereJoin=array();
            $whereJoin['where']=array(
              'tblpurchase_order.suppliers_id' =>$aRow['tblsuppliers.id'],
              'tblpurchase_order.date >=' =>$week60,
              'tblpurchase_order.date <' =>$week30,
            );
            $whereJoin['join']=array();
            $whereJoin['where_or']="((tblpurchase_order.status_pay != 2 AND tblpurchase_order.red_invoice = 0) or (tblpurchase_invoice.status != 2 AND tblpurchase_order.status_pay = 0))";
            $whereJoin['join']=array('tblpurchase_invoice,tblpurchase_invoice.id=tblpurchase_order.red_invoice,left');
            $whereJoin['field']='totalAll_suppliers';
            $subtotal=sum_from_table_join('tblpurchase_order',$whereJoin);
                        $whereJoin1=array();
            $whereJoin1['where']=array(
              'tblpurchase_order.suppliers_id' =>$aRow['tblsuppliers.id'],
              'tblpurchase_order.date >=' =>$week60,
              'tblpurchase_order.date <' =>$week30,
            );
            $whereJoin1['where_or']="((tblpurchase_order.status_pay != 2 AND tblpurchase_order.red_invoice = 0) or (tblpurchase_invoice.status != 2 AND tblpurchase_order.status_pay = 0))";
            $whereJoin1['join']=array('tblpurchase_invoice,tblpurchase_invoice.id=tblpurchase_order.red_invoice,left');
            $whereJoin1['field']='tblpurchase_order.amount_paid';
            $amount_paid=sum_from_table_join('tblpurchase_order',$whereJoin1);

            $whereJoin2=array();
            $whereJoin2['where']=array(
              'tblpurchase_order.suppliers_id' =>$aRow['tblsuppliers.id'],
              'tblpurchase_order.date >=' =>$week60,
              'tblpurchase_order.date <' =>$week30,
            );
            $whereJoin2['where_or']="((tblpurchase_order.status_pay != 2 AND tblpurchase_order.red_invoice = 0) or (tblpurchase_invoice.status != 2 AND tblpurchase_order.status_pay = 0))";
            $whereJoin2['join']=array('tblpurchase_invoice,tblpurchase_invoice.id=tblpurchase_order.red_invoice,left');
            $whereJoin2['field']='tblpurchase_order.price_other_expenses';
            $amount_paid_invoice=sum_from_table_join('tblpurchase_order',$whereJoin2);

            $_data =number_format($subtotal - $amount_paid -$amount_paid_invoice);
        }
        if ($aColumns[$i] == '6') {
            $whereJoin=array();
            $whereJoin['where']=array(
              'tblpurchase_order.suppliers_id' =>$aRow['tblsuppliers.id'],
              'tblpurchase_order.date <' =>$week60,
            );
            $whereJoin['join']=array();
            $whereJoin['where_or']="((tblpurchase_order.status_pay != 2 AND tblpurchase_order.red_invoice = 0) or (tblpurchase_invoice.status != 2 AND tblpurchase_order.status_pay = 0))";
            $whereJoin['join']=array('tblpurchase_invoice,tblpurchase_invoice.id=tblpurchase_order.red_invoice,left');
            $whereJoin['field']='totalAll_suppliers';
            $subtotal=sum_from_table_join('tblpurchase_order',$whereJoin);
                        $whereJoin1=array();
            $whereJoin1['where']=array(
              'tblpurchase_order.suppliers_id' =>$aRow['tblsuppliers.id'],
              'tblpurchase_order.date <' =>$week60,
            );
            $whereJoin1['where_or']="((tblpurchase_order.status_pay != 2 AND tblpurchase_order.red_invoice = 0) or (tblpurchase_invoice.status != 2 AND tblpurchase_order.status_pay = 0))";
            $whereJoin1['join']=array('tblpurchase_invoice,tblpurchase_invoice.id=tblpurchase_order.red_invoice,left');
            $whereJoin1['field']='tblpurchase_order.amount_paid';
            $amount_paid=sum_from_table_join('tblpurchase_order',$whereJoin1);

            $whereJoin2=array();
            $whereJoin2['where']=array(
              'tblpurchase_order.suppliers_id' =>$aRow['tblsuppliers.id'],
              'tblpurchase_order.date <' =>$week60,
            );
            $whereJoin2['where_or']="((tblpurchase_order.status_pay != 2 AND tblpurchase_order.red_invoice = 0) or (tblpurchase_invoice.status != 2 AND tblpurchase_order.status_pay = 0))";
            $whereJoin2['join']=array('tblpurchase_invoice,tblpurchase_invoice.id=tblpurchase_order.red_invoice,left');
            $whereJoin2['field']='tblpurchase_order.price_other_expenses';
            $amount_paid_invoice=sum_from_table_join('tblpurchase_order',$whereJoin2);

            $_data =number_format($subtotal - $amount_paid -$amount_paid_invoice);
        }
        $row[] = $_data;
    }
    $output['aaData'][] = $row;
}
    foreach ($footer_data as $key => $total) {
        $footer_data[$key] = number_format($total);
    }
    $output['sums'] = $footer_data;