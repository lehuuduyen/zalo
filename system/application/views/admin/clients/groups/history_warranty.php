<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if(isset($client)){ ?>
	<style>
		.row-header {
		    background: #fff7c0;
		}
	</style>
    <table class="table table-history_warranty_singer_client scroll-responsive">
        <thead>
            <tr>
               	<th class="text-center"><?php echo _l('tnh_product_code'); ?></th>
               	<th class="text-center"><?php echo _l('tnh_product_name'); ?></th>
               	<th class="text-center"><?php echo _l('series'); ?></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
<?php } ?>