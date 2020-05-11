<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$aColumns     = array(
  'id',
  'customer_shop_code',
  'customer_phone',
  'customer_email',
  'customer_password',
  'customer_phone_zalo',
  'customer_monitoring',
  'customer_number_bank',
  'customer_id_bank',
  'customer_name_bank',
  'customer_note',
  db_prefix().'staff.firstname as firstname',
  db_prefix().'staff.lastname as lastname',
  'policy'
);
$sIndexColumn = "id";
$sTable       = 'tblcustomers';

if ($_POST['customer']) {
  $where        = ["AND tblcustomers.active = 0 AND tblcustomers.id =".$_POST['customer']];
}else {
  $where        = ["AND tblcustomers.active = 0"];
}
$join = [
  'LEFT JOIN '.db_prefix().'staff ON '.db_prefix().'staff.	staffid='.db_prefix().'customers.customer_monitoring',
];
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, $join , $where, array(
));
$output       = $result['output'];

$rResult      = $result['rResult'];



$j=0;
foreach ($rResult as $aRow) {
    $row = array();
    $j++;
    for ($i = 0; $i < count($aColumns); $i++) {
      if ($aColumns[$i] === 'tblstaff.firstname as firstname') {
          $_data = $aRow['firstname'];
      }else if($aColumns[$i] === 'tblstaff.lastname as lastname'){
        $_data = $aRow['lastname'];

      }else {
        $_data = $aRow[$aColumns[$i]];

      }
      $row[] = $_data;

    }


    if (is_admin()) {



      if ($_POST['thang'] === 'true') {

        $icon_delete = '<a  href="/system/admin/customer/delete/'.$aRow['id'] .'" class="btn btn-danger delete-reminder-custom btn-icon">
        <i class="fa fa-remove"></i>
        </a>';
      }else {
        $icon_delete = "";
      }




        $icon_edit = "<a data-id='". $aRow['id'] ."'  style='padding: 3px;' class='btn btn-primary edit-customer' href='javascript:;'><i class='fa fa-pencil' ></i></a>";

        $row[] =$icon_delete.$icon_edit.'</div>';

    } else {
        $row[] = '';
    }

    $row[6] = $row[12]. ' ' .$row[11];
    if ($row[13] === "1") {
      $row[13] = 'Có';
    }else {
      $row[13] = 'Không';
    }
    $output['aaData'][] = $row;
}
