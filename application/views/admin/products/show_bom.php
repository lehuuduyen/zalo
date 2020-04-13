<div style="padding-left: 120px;">
	<table id="sub-products-<?= $product_id ?>" class="tnh-tb table-bordered table-hover">
		<thead>
			<tr class="">
				<th class="primary-table" style="width: 5%;"><?= lang('tnh_numbers') ?></th>
				<th class="primary-table" style="width: 20%;"><?= lang('code') ?></th>
				<th class="primary-table" style="width: 20%;"><?= lang('name') ?></th>
				<th class="primary-table" style="width: 20%;"><?= lang('unit') ?></th>
				<th class="primary-table" style="width: 20%;"><?= lang('type') ?></th>
				<th class="primary-table" style="width: 15%;"><?= lang('tnh_quantity_use') ?></th>
			</tr>
		</thead>
		<tbody>
			<?= $html_bom ?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#sub-products-<?= $product_id ?>').DataTable({
			"language": app.lang.datatables,
			"pageLength": app.options.tables_pagination_limit,
			"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
			"initComplete": function(settings, json) {
                var t = this;
                t.parents('.table-loading').removeClass('table-loading');
                t.removeClass('dt-table-loading');
                // mainWrapperHeightFix();
            },
		});
		/*tnhDatatable(
			'#sub-products-<?= $product_id ?>',
			{
                "pageLength": app.options.tables_pagination_limit,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                "processing": true,
				"initComplete": function(settings, json) {
	                var t = this;
	                t.parents('.table-loading').removeClass('table-loading');
	                t.removeClass('dt-table-loading');
	                // mainWrapperHeightFix();
	            },
			}
		);*/
	});
</script>