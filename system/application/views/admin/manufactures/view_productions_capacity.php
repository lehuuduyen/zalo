<div class="modal-dialog modal-lg" style="width: 70%;">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?= lang('view') ?></h4>
		</div>
		<div class="modal-body">
            <div role="tabpanel">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#info" aria-controls="info" role="tab" data-toggle="tab"><?= lang('info') ?></a>
                    </li>
                    <li role="presentation">
                        <a href="#statistical" aria-controls="tab" role="tab" data-toggle="tab"><?= lang('tnh_statistical_purchases') ?></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="info">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="lead-view" id="leadViewWrapper">
                                    <div class="wap-content firt">
                                        <span class="text-muted lead-field-heading no-mtop bold"><?= lang('date') ?>: </span>
                                        <span class="bold font-medium-xs lead-name"><?= _dt($productions_capacity['date']) ?></span>
                                    </div>
                                    <div class="wap-content second">
                                        <span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_reference_productions_capacity') ?>: </span>
                                        <span class="bold font-medium-xs lead-name"><?= ($productions_capacity['reference_no']) ?></span>
                                    </div>
                                    <div class="wap-content firt">
                                        <span class="text-muted lead-field-heading no-mtop bold"><?= lang('productions_capacity') ?>: </span>
                                        <span class="bold font-medium-xs lead-name" style="word-break: break-word;"><?= $productions_capacity['productions_plan_reference_no'] ?></span>
                                    </div>
                                    <div class="wap-content second">
                                        <span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_status') ?>: </span>
                                        <span class="bold font-medium-xs lead-name"><?= lang($productions_capacity['status']) ?></span>
                                    </div>
                                    <div class="wap-content firt">
                                        <span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_user_agree') ?>: </span>
                                        <span class="bold font-medium-xs lead-name"><?= $user_status ?></span>
                                    </div>
                                    <div class="wap-content second">
                                        <span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_date_agree') ?>: </span>
                                        <span class="bold font-medium-xs lead-name"><?= _dt($productions_capacity['date_status']) ?></span>
                                    </div>
                                    <div class="wap-content firt">
                                        <span class="text-muted lead-field-heading no-mtop bold"><?= lang('note') ?>: </span>
                                        <span class="bold font-medium-xs lead-name"><?= $productions_capacity['note'] ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mtop10">
                                <div class="table-responsive">
                                    <table id="table-view-capacity" class="dt-tnh table table-bordered table-hover dont-responsive-table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th><?= lang('tnh_numbers') ?></th>
                                                <th><?= lang('tnh_product_code') ?></th>
                                                <th><?= lang('tnh_product_name') ?></th>
                                                <th><?= lang('unit') ?></th>
                                                <th><?= lang('tnh_safe_inventory') ?></th>
                                                <th><?= lang('tnh_quantity_warehouses') ?></th>
                                                <th><?= lang('tnh_quantity_plan') ?></th>
                                                <th><?= lang('tnh_quantity_productions') ?></th>
                                                <th><?= lang('tnh_number_labor_minimum') ?></th>
                                                <th><?= lang('sub') ?></th>
                                                <th><?= lang('st') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6 pull-right">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><i class="fa fa-user"></i> <?= lang('tnh_user_created') ?></h3>
                                    </div>
                                    <div class="panel-body">
                                        <div><?= lang('tnh_created_by') ?>: <?= $created_by ?></div>
                                        <div><?= lang('tnh_date_creted') ?>: <?= _dt($productions_capacity['date_created']) ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="statistical">
                        <div class="row">
                            <div class="col-md-12">
                                <table id="view-statistical" class="dt-tnh table table-bordered table-hover dont-responsive-table" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th><?= lang('tnh_numbers') ?></th>
                                            <th><?= lang('code') ?></th>
                                            <th><?= lang('name') ?></th>
                                            <th><?= lang('type') ?></th>
                                            <th><?= lang('tnh_unit') ?></th>
                                            <th><?= lang('tnh_quantity_minimum') ?></th>
                                            <th><?= lang('tnh_quantity_warehouses') ?>(<?= lang('tnh_expected') ?>)</th>
                                            <th><?= lang('tnh_quantity_use') ?></th>
                                            <th><?= lang('tnh_quantity_purchase') ?>(<?= lang('tnh_expected') ?>)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</div>
		<input type="hidden" name="view_productions_capacity_id" id="view_productions_capacity_id" class="form-control" value="<?= $id ?>">
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></button>
		</div>
	</div>
</div>
<script type="text/javascript">
	var oTableProductionsCapacity = '';
	var paramsProductionsCapacity = {view_productions_capacity_id: '#view_productions_capacity_id'};
	var ii = 9;
    var ij = 10;
    var arr = [];
    function formatCapacity(d) {
    	versions = '';
        if (typeof d == 'undefined') return '';
        if (typeof d[ii][0] != 'undefined' && d[ii][0]['versions_bom'] != null) {
            versions = '('+d[ii][0]['versions_bom']+')';
        }
	    tr1 = '<tr><td colspan="9" class="text-center bold"><?= lang('BOM') ?> '+versions+'</td></tr>'+
                '<tr class="bold color-dark-green">'+
    	            '<td><?= lang('tnh_numbers') ?></td>'+
    	            '<td><?= lang('code') ?></td>'+
    	            '<td><?= lang('name') ?></td>'+
                    '<td><?= lang('unit') ?></td>'+
                    '<td><?= lang('type') ?></td>'+
                    // '<td><?= lang('tnh_quantity_minimum') ?></td>'+
                    // '<td><?= lang('tnh_quantity_warehouses') ?></td>'+
                    '<td><?= lang('tnh_quantity_use') ?></td>'+
    	            // '<td><?= lang('tnh_quantity_purchase') ?></td>'+
    	        '</tr>';

	    if (d[ii]) {
	    	element = d[ii];
	    	$.each(element, function(i, e) {
	    		if (typeof e.code_sub != 'undefined') {
	        		tr1+= '<tr>'+
			            '<td>'+(++i)+'</td>'+
			            '<td>'+e.code_sub+'</td>'+
                        '<td>'+e.name_sub+'</td>'+
			            '<td>'+e.unit+'</td>'+
	                    '<td>'+lang_core[e.type_sub]+'</td>'+
			            // '<td class="text-center">'+tnhFormatNumber(e.quantity_minimum_sub)+'</td>'+
	                    // '<td class="text-center">'+tnhFormatNumber(e.quantity_warehouse_sub)+'</td>'+
			            '<td class="text-center">'+tnhFormatNumber(e.quantity_plan_sub)+'</td>'+
	                    // '<td class="text-center">'+tnhFormatNumber(e.quantity_purchase_sub)+'</td>'+
			        '</tr>';
	    		}
        	});
        }

        tableBOM = '<table class="dt-table tnh-table table-bordered" style="width: 92% !important; float: right;">'+
			        tr1+
			    '</table>';

        //stages
        versions = '';
        if (typeof d[ij][0] != 'undefined' && d[ij][0]['versions_stages'] != null) {
            versions = '('+d[ij][0]['versions_stages']+')';
        }
        tr_stage = '<tr><td colspan="9" class="text-center bold"><?= lang('stages') ?> '+versions+'</td></tr>'+
                '<tr class="bold color-dark-green">'+
                    '<td style="width: 80px;"><?= lang('tnh_numbers') ?></td>'+
                    '<td><?= lang('tnh_stage_name') ?></td>'+
                    '<td><?= lang('tnh_machines') ?></td>'+
                    '<td><?= lang('tnh_number_hours') ?></td>'+
                '</tr>';

        if (d[ij]) {
            element = d[ij];
            $.each(element, function(index, el) {
            	if (typeof el.stage_name != 'undefined') {
	                tr_stage+= '<tr>'+
	                    '<td>'+(++index)+'</td>'+
	                    '<td>'+el.stage_name+'</td>'+
	                    '<td>'+el.machine_name+'</td>'+
	                    '<td class="text-center">'+tnhFormatNumber(el.number_hours)+'</td>'+
	                '</tr>';
	            }
            });
        }

        tableStages = '<table class="dt-table tnh-table table-bordered" style="width: 92% !important; float: right;     margin-top: 10px !important;">'+
                    tr_stage+
                '</table>';

        table = tableBOM+''+tableStages;
	    return table;
    }
	$(document).ready(function() {
		oTableProductionsCapacity = tnhDatatable(
            '#table-view-capacity',
            {
                'order': [[1, 'asc']],
                'orderCellsTop': true,
                "language": app.lang.datatables,
                "pageLength": app.options.tables_pagination_limit,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                // scrollY: "300px",
                // scrollX: true,
                // fixedColumns:   {
                //     leftColumns: 3,
                // },
                "serverSide": true,
                'sAjaxSource': '<?= site_url('admin/manufactures/getViewProductionsCapacity') ?>',
                'fnServerData': function (sSource, aoData, fnCallback) {
                    aoData.push({
                        "name": "<?= $this->security->get_csrf_token_name() ?>",
                        "value": "<?= $this->security->get_csrf_hash() ?>"
                    });
                    for (var key in paramsProductionsCapacity) {
                        aoData.push({
                            "name": key,
                            "value": $(paramsProductionsCapacity[key]).val()
                        });
                    }
                    $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
                },
                "initComplete": function(settings, json) {
                    var t = this;
                    t.parents('.table-loading').removeClass('table-loading');
                    t.removeClass('dt-table-loading');
                },
                "columnDefs": [
                    {
                    	"render": function(data) {
                    		return '<input type="hidden" name="records" id="records" class="form-control records" value="'+data+'"><div style="padding-left: 30px;">'+data+'</div>';
                    	},
                    	"targets": 0, "name": 'number_records', 'width': '50px', 'className': 'text-center details-control'
                    },
                    {"targets": 1, "name": 'code', 'width': '150px'},
                    {"targets": 2, "name": 'name', 'width': '150px'},
                    {"targets": 3, "name": 'unit', 'width': '80px', 'className': 'text-center'},
                    {
                    	"render": function(data) {
                    		return '<div class="text-center">'+tnhFormatNumber(data)+'</div>'
                    	},
                    	"targets": 4, "name": 'quantity_minimum', 'width': '80px'
                    },
                    {
                    	"render": function(data) {
                    		return '<div class="text-center">'+tnhFormatNumber(data)+'</div>'
                    	},
                    	"targets": 5, "name": 'quantity_warehouses', 'width': '80px'
                    },
                    {
                    	"render": function(data) {
                    		return '<div class="text-center">'+tnhFormatNumber(data)+'</div>'
                    	},
                    	"targets": 6, "name": 'quantity_use', 'width': '80px'
                    },
                    {
                        "render": function(data) {
                            return '<div class="text-center">'+tnhFormatNumber(data)+'</div>'
                        },
                        "targets": 7, "name": 'quantity_productions', 'width': '80px'
                    },
                    {
                        "render": function(data) {
                            return '<div class="text-center">'+data+'</div>'
                        },
                        "targets": 8, "name": 'number_labor', 'width': '80px'
                    },
                    {"targets": ii, "name": 'sub', 'width': '150px', 'visible': false, 'searchable': false},
                    {"targets": ij, "name": 'st', 'width': '150px', 'visible': false, 'searchable': false},
                ],
                "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                }
            }
        );

		$('#table-view-capacity tbody').on('click', 'td.details-control', function () {
	        var tr = $(this).closest('tr');
            var records = tr.find('#records').val();
	        var row = oTableProductionsCapacity.row( tr );

	        if ( row.child.isShown() ) {
	            arr = removeArray(arr, records);
	            row.child.hide();
	            tr.removeClass('shown');
	        }
	        else {
	            if (!arr.includes(records)) {
                    arr.push(records);
                }
	            row.child( formatCapacity(row.data()) ).show();
	            tr.addClass('shown');
	        }
	    } );

	    $('#table-view-capacity').on('draw.dt', function(e, settings) {
            if (arr.length > 0) {
                $.each(arr, function(index, el) {
                    $('input[name="records"][value="'+ el +'"]').closest('td').trigger('click');
                });
            }
        })
	});

    //tab2
    var oTableProductionsCapacityStatistical = '';
    var paramsProductionsCapacityStatistical = {view_productions_capacity_id: '#view_productions_capacity_id'};
    $(document).ready(function() {
        oTableProductionsCapacityStatistical = tnhDatatable(
            '#view-statistical',
            {
                'order': [[1, 'asc']],
                'orderCellsTop': true,
                "language": app.lang.datatables,
                "pageLength": app.options.tables_pagination_limit,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                // scrollY: "300px",
                // scrollX: true,
                // fixedColumns:   {
                //     leftColumns: 3,
                // },
                "serverSide": true,
                'sAjaxSource': '<?= site_url('admin/manufactures/getViewProductionsCapacityStatistical') ?>',
                'fnServerData': function (sSource, aoData, fnCallback) {
                    aoData.push({
                        "name": "<?= $this->security->get_csrf_token_name() ?>",
                        "value": "<?= $this->security->get_csrf_hash() ?>"
                    });
                    for (var key in paramsProductionsCapacityStatistical) {
                        aoData.push({
                            "name": key,
                            "value": $(paramsProductionsCapacityStatistical[key]).val()
                        });
                    }
                    $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
                },
                "initComplete": function(settings, json) {
                    var t = this;
                    t.parents('.table-loading').removeClass('table-loading');
                    t.removeClass('dt-table-loading');
                },
                "columnDefs": [
                    {
                        "targets": 0, "name": 'number_records', 'width': '50px', 'className': 'text-center'
                    },
                    {"targets": 1, "name": 'code', 'width': '150px'},
                    {"targets": 2, "name": 'name', 'width': '150px'},
                    {
                        "render": function(data) {
                            return lang_core[data];
                        },
                        "targets": 3, "name": 'type', 'width': '150px'
                    },
                    {"targets": 4, "name": 'unit_name', 'width': '100px'},
                    {
                        "render": function(data) {
                            return '<div class="text-center">'+tnhFormatNumber(data)+'</div>'
                        },
                        "targets": 5, "name": 'quantity_minimum', 'width': '80px', 'className': 'text-center'
                    },
                    {
                        "render": function(data) {
                            return '<div class="text-center">'+tnhFormatNumber(data)+'</div>'
                        },
                        "targets": 6, "name": 'quantity_warehouse', 'width': '80px', 'className': 'text-center'
                    },
                    {
                        "render": function(data) {
                            return '<div class="text-center">'+tnhFormatNumber(data)+'</div>'
                        },
                        "targets": 7, "name": 'quantity_plan', 'width': '80px', 'className': 'text-center'
                    },
                    {
                        "render": function(data) {
                            return '<div class="text-center">'+tnhFormatNumber(data)+'</div>'
                        },
                        "targets": 8, "name": 'quantity_purchase', 'width': '80px', 'className': 'text-center'
                    },
                ],
                "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                }
            }
        );
    });
</script>