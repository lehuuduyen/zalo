<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$aColumns     = array(
    '1',
    'tblpurchase_invoice.id',
    'tblpurchase_invoice.code_invoice',
    'tblpurchase_invoice.date_invoice',
    'tblpurchase_invoice.id_import',
    'tblsuppliers.company',
    'total_price_affter_vat',
    'total_price_vat',
    'total_price_befor_vat',
    'price_other_expenses',
    'tblpurchase_invoice.status',
    'tblpurchase_invoice.staff_create',
    'tblpurchase_invoice.link',
);
$sIndexColumn = "id";
$sTable       = 'tblpurchase_invoice';
$where        = array(
  
);
$suppliers_id = $this->ci->input->post('suppliers_id');
if(is_numeric($suppliers_id))
{
    array_push($where, 'AND tblpurchase_invoice.id_supplier = '.$this->ci->input->post('suppliers_id'));
}
$join         = array(
    'LEFT JOIN tblsuppliers ON tblsuppliers.id=tblpurchase_invoice.id_supplier',
);
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array(
    'tblpurchase_invoice.id_supplier','tblpurchase_invoice.id_import','tblpurchase_invoice.date_create',
));
$output       = $result['output'];
$rResult      = $result['rResult'];
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
        if ($aColumns[$i] == '1') {
            if((is_numeric($suppliers_id)&&($aRow['tblpurchase_invoice.status'] == 0)))
            {
            $_data = '<div class="checkbox"><input type="checkbox" value="' . $aRow['tblpurchase_invoice.id'] . '"><label></label></div>';
            }else
            {
            $_data = '';    
            }
        }
        if ($aColumns[$i] == 'tblpurchase_invoice.id') {
        $_data =$j;
        }

        if ($aColumns[$i] == 'tblpurchase_invoice.date_invoice') {
        $_data =_d($aRow['tblpurchase_invoice.date_invoice']);
        }
        if ($aColumns[$i] == 'tblsuppliers.company') {
        $_data ='<a href="#" onclick="int_suppliers_view('.$aRow['id_supplier'].',false); return false;" >' . $aRow['tblsuppliers.company'] . '</a>';
        }
        if ($aColumns[$i] == 'tblpurchase_invoice.link') {
            $_data ='';
            if(!empty($aRow['tblpurchase_invoice.link']))
            {
            $_data ='<div class="text-center"><a href="'.$aRow['tblpurchase_invoice.link'].'" class="btn btn-primary dropdown-toggle" target="_blank" type="button" >'._l('Link').'
                    </a></div>';
            // $_data ='<a href="'.$aRow['tblpurchase_invoice.link'].'">' . $aRow['tblpurchase_invoice.link'] . '</a>';
            }
        }
        if ($aColumns[$i] == 'tblpurchase_invoice.code_invoice') {
        $_data =$aRow['tblpurchase_invoice.code_invoice'];
        $_outputStatus = '<div class="row-options">';
        if($aRow['tblpurchase_invoice.status'] != 2)
        {
        $_outputStatus .= '<a href="' . admin_url('purchase_invoice/delete/' . $aRow['tblpurchase_invoice.id']) . '" class="text-danger delete-remind">' . _l('delete') . '</a>';
        }
        $_outputStatus .= '</div>';
        $_data=$_data.$_outputStatus;
        }
        if ($aColumns[$i] == 'tblpurchase_invoice.id_import') {
        $_data='';
        $id_import = explode(',', $aRow['tblpurchase_invoice.id_import']);
        $count = count($id_import);
        if($count == 1)
        {
        $import = get_table_where('tblpurchase_order',array('id'=>$id_import[0]),'','row');
        $_data='<a href="#" onclick="view_purchase_order('.$id_import[0].'); return false;" >' . $import->prefix.'-'.$import->code . '</a>';    
        }else
        {
            $_data = '<div class="dropdown" style="text-align: center;">
                        <button class="dropdown-toggle no_background color_warning" type="button" data-toggle="dropdown">'.format_status_number_order_ch($count).'
                        </button>';
            $__data='';
            foreach ($id_import as $key => $value) {
                $import = get_table_where('tblpurchase_order',array('id'=>$value),'','row');
                $__data.='<li><a href="#" onclick="view_purchase_order('.$value.'); return false;" >' . $import->prefix.'-'.$import->code . '</a></li>';   
            }
                $_data .= '<ul style="top:100%;bottom:unset;left:unset;right: 12%" class="dropdown-menu ch_foso">'.$__data;
                $_data .= '</ul>';
                $_data .= '</div>';
        }
        
        
        }
        if ($aColumns[$i] == 'total_price_vat') {
        $_data='<div class="text-right">'.number_format($aRow['total_price_vat']).'<div>';
        }
        if ($aColumns[$i] == 'total_price_befor_vat - price_other_expenses as total_payment') {
        $_data='<div class="text-right">'.number_format($aRow['total_payment']).'<div>';
        }
        if ($aColumns[$i] == 'total_price_befor_vat') {
        $_data='<div class="text-right">'.number_format($aRow['total_price_befor_vat']).'<div>';
        }
        if ($aColumns[$i] == 'price_other_expenses') {
        $_data='<div class="text-right">'.number_format($aRow['price_other_expenses']).'<div>';
        }
        if ($aColumns[$i] == 'total_price_affter_vat') {
        $_data='<div class="text-right">'.number_format($aRow['total_price_affter_vat']).'<div>';
        }
        if ($aColumns[$i] == 'tblpurchase_invoice.status') {
        $_data='<div class="text-center">'.format_status_pay_slip($aRow['tblpurchase_invoice.status']).'<div>';
        }
        if ($aColumns[$i] == 'tblpurchase_invoice.staff_create') {
        $_data=staff_profile_image($aRow['tblpurchase_invoice.staff_create'], array('staff-profile-image-small mright5'), 'small', array(
                            'data-toggle' => 'tooltip',
                            'data-title' => ' Vào lúc: '._dt($aRow['date_create'])
                        )).get_staff_full_name($aRow['tblpurchase_invoice.staff_create']).'<br>';;
        }

        $row[] = $_data;
    }
    $_data='';
    if (is_admin()) {

    $_outputStatus = '<div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">'._l('action').'
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu h_right">';
        if($aRow['tblpurchase_invoice.status'] != 2)
        {
        $_outputStatus .= '<li><a onclick="payment('.$aRow['tblpurchase_invoice.id'].')"><i class="fa fa-file width-icon-actions"></i>'._l('ch_pay_slip').'</a></li>';
        }
        $_outputStatus .= '<li><a onclick="electronic_bill('.$aRow['tblpurchase_invoice.id'].')"><i class="fa fa-file-image-o width-icon-actions"></i>'._l('ch_electronic_bill').'</a></li>';
        
        


    $_outputStatus .= '</ul></div>';
    $row[] = $_outputStatus;
    } 
    else {
        $row[] = '';
    }
    $output['aaData'][] = $row;
}
