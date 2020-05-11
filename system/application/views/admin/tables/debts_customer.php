<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$aColumns     = array(
  'id',
  'customer_shop_code',
  'customer_phone',
);
$sIndexColumn = "id";
$sTable       = 'tblcustomers';

if ($_POST['id_customer']) {
  $where        = ["AND tblcustomers.id = ".$_POST['id_customer']];
}else {
  $where        = array(

  );
}


$join         = array(
);
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array(
));

$output       = $result['output'];
$rResult      = $result['rResult'];
$j=0;

foreach ($rResult as $aRow) {
    $row = array();
    $j++;
    for ($i = 0; $i < count($aColumns); $i++) {
      $_data = $aRow[$aColumns[$i]];
      $row[] = $_data;

    }


    if (is_admin()) {
        $icon_get_data = "<a on data-debits='". $aRow['customer_shop_code'] ."'  style='padding: 3px;' class='btn btn-default btn-icon get_data_debits' href='javascript:;'><i class='fa fa-eye' ></i></a>";

        $row[] =$icon_get_data.'</div>';

    } else {
        $row[] = '';
    }



    $output['aaData'][] = $row;
}
