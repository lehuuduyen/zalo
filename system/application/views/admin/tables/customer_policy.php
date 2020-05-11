<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$aColumns     = array(
  'tblcustomer_policy.id',
  'day_policy',
  'customer_id',
  db_prefix().'customers.customer_shop_code as customer_shop_code',
  'special_policy',
  'fee_back',
  db_prefix().'staff.firstname as firstname',
  db_prefix().'staff.lastname as lastname',
  'staff_id',
  'note_default',
  'config'

);
$sIndexColumn = "id";
$sTable       = 'tblcustomer_policy';
if ($_POST['customer']) {
  $where        = ["AND tblcustomer_policy.customer_id =".$_POST['customer']];
}else {
  $where        = array(
  );
}

$join         = $join = [
  'LEFT JOIN '.db_prefix().'customers ON '.db_prefix().'customers.	id='.db_prefix().'customer_policy.customer_id','LEFT JOIN '.db_prefix().'staff ON '.db_prefix().'staff.	staffid='.db_prefix().'customer_policy.staff_id',
];
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array(
));
$output       = $result['output'];

$rResult      = $result['rResult'];


$j=0;
foreach ($rResult as $aRow) {
    $row = array();
    $j++;
    for ($i = 0; $i < count($aColumns); $i++) {

        if ($aColumns[$i] === "tblcustomers.customer_shop_code as customer_shop_code") {


          $_data = $aRow['customer_shop_code'];

        }else if($aColumns[$i] === 'tblcustomer_policy.id'){
          $aRow['id'] = $aRow['tblcustomer_policy.id'];
          $_data = $aRow['id'];
        }
        else if($aColumns[$i] === 'fee_back'){

          if ($aRow['fee_back'] == '0') {

            $_data = 'Không';
          }else {
            $_data = 'Có';
          }
        }
        if ($aColumns[$i] === 'tblstaff.firstname as firstname') {
            $_data = $aRow['firstname'];
        }else if($aColumns[$i] === 'tblstaff.lastname as lastname'){
          $_data = $aRow['lastname'];
        }
        else {
          if ($aColumns[$i] !== "tblcustomers.customer_shop_code as customer_shop_code" && $aColumns[$i] !== "fee_back") {
            $_data = $aRow[$aColumns[$i]];
          }

        }

        if($aColumns[$i] === 'config'){
          if ($aRow['config'] == '1') {

            $_data = 'Cho Xem Hàng Nhưng Không Cho Thử Hàng';
          }else if($aRow['config'] == '2'){
            $_data = 'Cho Thử Hàng';
          }else {
            $_data = 'Không Cho Xem Hàng';
          }
        }



        $row[] = $_data;
    }

    if (is_admin()) {


        $icon_delete = '<a href="/system/admin/customer_policy/delete/'.$aRow['id'] .'" class="btn btn-danger delete-reminder-custom btn-icon">
        <i class="fa fa-remove"></i>
        </a>';


        $icon_edit = "<a data-id='". $aRow['id'] ."'  style='padding: 3px;' class='btn btn-primary edit-policy' href='javascript:;'><i class='fa fa-pencil' ></i></a>";

        $copy_button = "<a data-id='". $aRow['id'] ."'  style='padding: 3px;' class='btn btn-warning copy-policy btn-icon' href='javascript:;'><i class='fa fa-clone'></i></a>";

        $row[] =$icon_delete.$copy_button.$icon_edit.'</div>';

    } else {
        $row[] = '';
    }

    $row[6] = $row[7].' '.$row[6];
    $output['aaData'][] = $row;
}
