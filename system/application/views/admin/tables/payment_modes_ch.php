<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'name',
    'note',
    ];
$sIndexColumn = 'id';
$sTable       = db_prefix().'payment_modes_ch';

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], [
    'id',
    ]);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];

        if ($aColumns[$i] == 'name') {
            $_data = '<a href="#" data-toggle="modal"  data-id="' . $aRow['id'] . '">' . $_data . '</a>';
        }

        $row[] = $_data;
    }

    $options = icon_btn('#' . $aRow['id'], 'pencil-square-o', 'btn-default', [
        'data-toggle'           => 'modal',
        'data-target'           => '#payment_mode_modal',
        'data-id'               => $aRow['id'],
        ]);
    $row[] = $options .= icon_btn('paymentmodes_receipts/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');

    $output['aaData'][] = $row;
}
