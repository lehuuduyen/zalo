<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if(isset($client)){ ?>
      <?php render_datatable(array(
            _l('tnh_numbers'),
            _l('report_invoice_date'),
            _l('tnh_ex_warehouses'),
            _l('series'),
            _l('tnh_type'),
            _l('tnh_product_code'),
            _l('tnh_product_name')
      ),'warranty_singer_client'); ?>
<?php } ?>