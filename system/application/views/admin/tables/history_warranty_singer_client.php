<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI =& get_instance();

$aColumns     = array(
    '1',
    '2',
    '3'
);
$sIndexColumn = "id";
$sTable       = 'tblwarranty';
$where        = array();

$join         = array(
);
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array(
    'tblwarranty.code',
    'tblwarranty.date_create',
    'tblwarranty.id_warranty_receive'
));
$output       = $result['output'];
$rResult      = $result['rResult'];

$output = $output;
$output['iTotalRecords'] = $output['iTotalRecords'];
$output['iTotalDisplayRecords'] = $output['iTotalDisplayRecords'];
$currentPage=$this->_instance->input->post('start');
$currentall=$output['iTotalRecords'];
foreach ($rResult as $r => $aRow) {
    $row = array();
    $row[] = '<span class="bold">'._l('warranty_code').': '.$aRow['code'].'</span>';
    $row[] = '<span class="bold">'._l('date').': '._d($aRow['date_create']).'</span>';
    $row[] = '';
    $row['DT_RowClass'] = 'row-header';
    $output['aaData'][] = $row;

    $CI->db->select('tblwarranty_receive.*');
    $CI->db->where('tblwarranty_receive.id', $aRow['id_warranty_receive']);
    $get_warranty_receive = $CI->db->get('tblwarranty_receive')->row();
    $get_warranty_item = get_table_where('tblwarranty_items',array('id_warranty_receive'=>$get_warranty_receive->id));
    foreach ($get_warranty_item as $key => $value) {
        $getSeries = get_table_where('tblseries',array('id'=>$value['id_series']),'','row');
        $row = array();
        for ($i = 0; $i < count($aColumns); $i++) {
            if($aColumns[$i] == '1') {
                $_data = '';
            }
            else if($aColumns[$i] == '2') {
                $_data = '';
            }
            $row[] = $_data;
        }
        $output['aaData'][] = $row;
    }
}