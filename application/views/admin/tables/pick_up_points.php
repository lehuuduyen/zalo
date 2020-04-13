<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$aColumns     = array(
  'created',
  db_prefix().'pickuppoint.id',
  'customer_id',
  db_prefix().'customers.customer_shop_code as customer_shop_code',
  'phone_customer',
  'repo_customer',
  'note',
  'status',
  'name_customer_new',
  "modified",
  "district_filter",
  "commune_filter",
  "address_filter",
  'user_reg',
  db_prefix().'staff.firstname as firstname',
  db_prefix().'staff.lastname as lastname'
);
$sIndexColumn = "id";
$sTable       = 'tblpickuppoint';
$where        = ['AND status= 0 OR status= 2'];
$join = [
  'LEFT JOIN '.db_prefix().'customers ON '.db_prefix().'customers.	id='.db_prefix().'pickuppoint.customer_id',
  'LEFT JOIN '.db_prefix().'staff ON '.db_prefix().'staff.	staffid='.db_prefix().'pickuppoint.user_reg',
];
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, $join , $where, array(
));
$output       = $result['output'];

$rResult      = $result['rResult'];



usort($rResult, function ($a, $b) {
  return strcmp(trim($a['commune_filter']), trim($b['commune_filter']));
});

usort($rResult, function ($a, $b) {
  return strcmp(trim($a['district_filter']), trim($b['district_filter']));
});


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

        if ($aRow['status'] === '0' || $aRow['status'] === '2') {
          $_data = '<label class="label-border" data-id="'. $aRow['tblpickuppoint.id'] .'" style="color:red"> <span>Chưa Lấy</span>  <input class="check-change-status-number" type="checkbox"></label> ';
        }else {
          $_data = '<label class="label-border" data-id="'. $aRow['tblpickuppoint.id'] .'" style="color:green"> <span>Đã lấy</span>  <input class="check-change-status-number" type="checkbox" checked></label> ';
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

    $row[13] = $row[15].' '.$row[14];




    $output['aaData'][] = $row;

}
