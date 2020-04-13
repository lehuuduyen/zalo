<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$aColumns     = array(
    'id',
    'name_region',
    'price_region',
    'mass_region',
    'mass_region_free',
    'price_over_mass_region',
    'volume_region',
    'volume_region_free',
    'price_over_volume_region',
    'amount_of_free_insurance',
    'insurance_price',
    'order_region',
    'max_day',
);
$sIndexColumn = "id";
$sTable       = 'tbldeclared_region';
$where        = array(
);
$join         = array(
);
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array(
));

$output       = $result['output'];
$rResult      = $result['rResult'];


usort($rResult, function ($item1, $item2) {
    if ($item1['order_region'] == $item2['order_region']) return 0;
    return $item1['order_region'] < $item2['order_region'] ? -1 : 1;
});




$j=0;
foreach ($rResult as $aRow) {
    $row = array();
    $j++;
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];

        if ($aColumns[$i]== 'name_region') {
          $_data = '<div style="width:250px;">'.$_data.'</div>';
        }
        if($aColumns[$i]=='price_region')
        {
            $_data=number_format_data($_data);
        }
        if($aColumns[$i]=='mass_region')
        {
            $_data=number_format_data($_data);
        }
        if($aColumns[$i]=='volume_region')
        {
            $_data=number_format_data($_data);
        }
        if($aColumns[$i]=='price_over_mass_region')
        {
            $_data=number_format_data($_data);
        }
        if($aColumns[$i]=='price_over_volume_region')
        {
            $_data=number_format_data($_data);
        }
        if($aColumns[$i]=='mass_region_free')
        {
            $_data=number_format_data($_data);
        }
        if($aColumns[$i]=='volume_region_free')
        {
            $_data=number_format_data($_data);
        }
        if($aColumns[$i]=='amount_of_free_insurance')
        {
            $_data=number_format_data($_data);
        }
        $row[] = $_data;
    }
    if (is_admin()) {
        $_data = '<div class="cover-btn-csv"><a href="javascript:;" class="btn btn-default btn-icon btn-csv" data-id="' . $aRow['id'] . '"><label for="for_check'. $aRow['id'] .'" ><i class="fa fa-cloud-upload" aria-hidden="true"></i></label><input type="file" class="file_region"  name="file_region[]" class="form-file" value="" id="for_check'. $aRow['id'] .'" /></a>';



        $icon_delete = '<a data-id="'.$aRow['id'].'" href="javascript:;" class="btn btn-danger delete-reminder-custom btn-icon">
        <i class="fa fa-remove"></i>
        </a>';
        $icon_Detail = "<a data-id='". $aRow['id'] ."'  style='padding: 3px;' class='btn btn-primary btn-detail-csv btn-icon' href='javascript:;'><i class='fa fa-eye' ></i></a>";

        $icon_edit = "<a data-id='". $aRow['id'] ."'  style='padding: 3px;' class='btn btn-primary edit-region' href='javascript:;'><i class='fa fa-pencil' ></i></a>";

        $row[] =$_data.$icon_delete.$icon_Detail.$icon_edit.'</div>';

    } else {
        $row[] = '';
    }
    $output['aaData'][] = $row;
}
