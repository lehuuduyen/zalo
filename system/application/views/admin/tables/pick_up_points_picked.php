<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$aColumns     = array(
  "modified",
  db_prefix().'pickuppoint.id',
  'customer_id',
  db_prefix().'customers.customer_shop_code as customer_shop_code',
  'phone_customer',
  'repo_customer',
  'note',
  'status',
  'name_customer_new',
  'created',
  "district_filter",
  "commune_filter",
  "address_filter",
  'user_geted',
  db_prefix().'staff.firstname as firstname',
  db_prefix().'staff.lastname as lastname',
  'number_order_get'
);
$sIndexColumn = "id";
$sTable       = 'tblpickuppoint';
$where        = ['AND status= 1' ];
$join = [
  'LEFT JOIN '.db_prefix().'customers ON '.db_prefix().'customers.	id='.db_prefix().'pickuppoint.customer_id',
  'LEFT JOIN '.db_prefix().'staff ON '.db_prefix().'staff.	staffid='.db_prefix().'pickuppoint.user_geted',
];
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, $join , $where, array(
));
$output       = $result['output'];

$rResult      = $result['rResult'];


usort($rResult, function ($item1, $item2) {
    if ($item1['modified'] == $item2['modified']) return 0;
    return $item1['modified'] > $item2['modified'] ? -1 : 1;
});

// usort($rResult, function($a, $b) {
//   $ad = new DateTime($a['modified']);
//   $bd = new DateTime($b['modified']);
//
//   if ($ad == $bd) {
//     return 0;
//   }
//
//   return $ad > $bd ? -1 : 1;
// });

$j=0;
foreach ($rResult as $aRow) {
    $row = array();
    $j++;
    for ($i = 0; $i < count($aColumns); $i++) {
      if ($aColumns[$i] === 'tblcustomers.customer_shop_code as customer_shop_code') {
        $_data = $aRow['customer_shop_code'];

      }else if($aColumns[$i] === 'tblpickuppoint.id'){

        $aRow['id'] = $aRow['tblpickuppoint.id'];
        $_data = $aRow['id'];
      }
      else if ($aColumns[$i] === 'status') {

        if ($aRow['status'] === '0') {
          $_data = '<label class="label-border" data-id="'. $aRow['tblpickuppoint.id'] .'" style="color:red"> <span>Chưa Lấy</span>  <input class="check-change-status" type="checkbox"></label> ';
        }else {
          $_data = '<label class="label-border" data-id="'. $aRow['tblpickuppoint.id'] .'" style="color:green"> <span>Đã lấy</span>  <input class="check-change-status" type="checkbox" checked></label> ';
        }

      }

      else {
        if ($aColumns[$i] !== 'tblstaff.firstname as firstname' && $aColumns[$i] !== 'tblstaff.lastname as lastname') {
            $_data = $aRow[$aColumns[$i]];
        }

      }

      if ($aColumns[$i] === 'tblstaff.firstname as firstname') {
          $_data = $aRow['firstname'];
      }else if($aColumns[$i] === 'tblstaff.lastname as lastname'){
        $_data = $aRow['lastname'];
      }




      $row[] = $_data;


    }


    if (is_admin()) {



      if (strpos($row[7] , 'Chưa') !== false) {
        $icon_delete = '<a  href="/system/admin/pick_up_points/delete/'.$aRow['id'] .'" class="btn btn-danger delete-reminder-custom btn-icon">
        <i class="fa fa-remove"></i>
        </a>';


        $icon_edit = "<a data-id='". $aRow['id'] ."'  style='padding: 3px;' class='btn btn-primary edit-customer' href='javascript:;'><i class='fa fa-pencil' ></i></a>";

        $row[] = $icon_delete.$icon_edit.'</div>';
      }else {
        $row[] = '</div>';
      }




    } else {
        $row[] = '';
    }



    if ($row[3] === null) {
      $row[3] = $row[8];
    }

    $row[14] = $row[15].' '.$row[14];
    $icon_edit_number = "<a data-user_geted='". $row[13] ."'  data-id='". $aRow['id'] ."'  style='margin-left: 5px;padding: 3px;' class='btn btn-primary edit-number-order' href='javascript:;'><i class='fa fa-pencil' ></i></a><input type='hidden' value='".$row[16]."'/>";
    $row[16] = $row[16].$icon_edit_number;
    $output['aaData'][] = $row;

}
