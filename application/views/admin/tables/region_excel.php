<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$aColumns     = array(
    'id',
    'region_id',
    'city',
    'district',
);

$sIndexColumn = "id";
$sTable       = 'tblregion_excel';
if( !empty($this->ci->input->post('filterStatus') ) ){
  $where[] = 'AND region_id = '.$this->ci->input->post('filterStatus');
}


$join         = array();

$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array());


$output       = $result['output'];
$rResult      = $result['rResult'];


$j=0;
foreach ($rResult as $aRow) {
    $row = array();
    $j++;
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if($aColumns[$i]=='opening_balance')
        {
            $_data=number_format_data($_data);
        }
        $row[] = $_data;
    }
    // if (is_admin()) {
    //     ;
    //
    // } else {
    //     $row[] = '';
    // }
    $output['aaData'][] = $row;
}
// <i class="fa fa-file-excel-o"></i>
