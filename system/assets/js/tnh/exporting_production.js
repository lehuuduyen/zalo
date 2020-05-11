function totalExportingProductions()
{
    tb = '#tb-items tbody tr:not("[class^=not-tr]")';
    var table = $(tb).length;
    var stt = 0;
    var total_quantity = 0;
    var total_quantity_exchange = 0;
    count_errors = 0;
    var flag = false;
    for (ii = 0; ii < table; ii++)
    {
        stt++;
        element = $(tb)[ii];
        $(element).find('.stt').html(stt);
        item_id_current = $(element).find('input.items_id').val();
        suggest_exporting_items_id = $(element).find('.suggest_exporting_items_id').val();
        if (item_id_current || suggest_exporting_items_id) {
            quantity = intVal($(element).find('.quantity').val());
            number_exchange = intVal($(element).find('.number_exchange').val());
            quantity_exchange = quantity/number_exchange;
            $(element).find('.td-quantity-exchange').html(tnhFormatNumber(quantity_exchange));
            total_quantity+= quantity;
            total_quantity_exchange+= quantity_exchange;
            flag = true;
        }
    }
    $('.th-total-quantity').html(tnhFormatNumber(total_quantity));
    $('.th-total-quantity-exchange').html(tnhFormatNumber(total_quantity_exchange));

    if (flag) {
        $('#warehouses').select2('readonly', true);
    } else {
        $('#warehouses').select2('readonly', false);
    }
}

function getLocations(locations) {
    var option = '<option value=""></option>';
    $.each(locations, function(index, el) {
        option+= '<option value="'+el.localtion+'">'+el.location_name+'</option>';
    });
    return option;
}

$(document).ready(function() {
	init_editor('textarea[name="note"]');
    $('#warehouses').select2();
    if (edit == 0) {
        ajaxSelectParams('#productions_orders_detail_id', 'admin/stock/searchProductionsOrdersDetail', 0);
    } else if (edit == 1) {
        $('#warehouses').select2('readonly', true);
        $('select.locations').select2();
    }
	var dt = $('#tb-items').DataTable({
		"language": lang_datatables,
		'searching': false,
		'ordering': false,
		'paging': false,
        "info": false,
        'fixedHeader': true,
        // scrollY: true,
		// scrollY: '150px',
		// scrollX: true,
        'fnRowCallback': function (nRow, aData, iDisplayIndex) {
        },
        "initComplete": function(settings, json) {
            var t = this;
            t.parents('.table-loading').removeClass('table-loading');
            t.removeClass('dt-table-loading');
            mainWrapperHeightFix();
        },
	});

	$('.add-row').on('click', function(event) {
		event.preventDefault();
        productions_orders_detail_id = $('#productions_orders_detail_id').val();
        warehouses = $('#warehouses').val();
        if (!productions_orders_detail_id || !warehouses) {
            bootbox.alert(lang_ex['tnh_please_chosen_pod']);
            return;
        }

		tdRef = '<div class="stt text-center"></div>';
		tdItem = '<input type="hidden" name="counter[]" id="input" class="form-control" value="'+counter+'">\
            <input type="hidden" name="unit_id[]" id="unit_id" class="form-control unit_id" value="">\
            <input type="hidden" name="unit_parent_id[]" id="unit_parent_id" class="form-control unit_parent_id" value="">\
            <input type="hidden" name="number_exchange[]" id="number_exchange" class="form-control number_exchange" value="1">\
            <input type="text" name="items_id[]" id="items_'+counter+'" class="items_id" style="width: 100%;" data-placeholder="'+ lang_core['choose'] +'" value="">';
        tdImage = '<div class="td-image">'+
                    '<div class="preview_image" style="width: auto;">'+
                        '<div class="display-block contract-attachment-wrapper img">'+
                            '<div style="width:45px;">'+
                                '<a href="'+site.base_url+'assets/images/tnh/no_image.png" data-lightbox="customer-profile" class="display-block mbot5">'+
                                    '<div class="">'+
                                        '<img src="'+site.base_url+'assets/images/tnh/no_image.png" style="border-radius: 50%">'+
                                    '</div>'+
                                '</a>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
            '</div>';
        tdItemName = '<div class="td-item-name"></div>';
        tdUnit = '<div class="td-unit"></div>';
        tdLocation = '<div class="td-location"><select name="locations[]" data-placeholder="'+ lang_core['choose'] +'" id="locations" class="locations" style="width: 180px;"></select></div>';
        tdQuantity = '<div class="td-quantity"><input type="text" onkeyup="formatNumBerKeyUpCus(this)" name="quantity[]" id="quantity[]" class="form-control quantity" value="1"></div>';
        tdValueExchange = '<div class="text-center td-value-exchange"></div>';
        tdQuantityExchange = '<div class="text-center td-quantity-exchange"></div>';
		tdActions = '<div class="text-center"><i class="fa fa-remove btn btn-danger remove-row"></i></div>';

		rowNode = dt.row.add( [
            tdRef,
            tdItem,
            tdItemName,
            tdUnit,
            tdLocation,
            tdQuantity,
            tdValueExchange,
            tdQuantityExchange,
            tdActions
        ] ).draw( false ).node();
        ajaxSelectParams($('#items_'+ counter +''), 'admin/stock/searchItemsByProductionDetail', 0, {productions_orders_detail_id: productions_orders_detail_id});
        $('select.locations').select2();
        counter++;
        totalExportingProductions();
	});

    $(document).on('change', '.items_id', function(event) {
        event.preventDefault();
        row = $(this).closest('tr');
        data = event.added;
        sl = this;
        item_id = $(this).val();
        paramsData['warehouse_id'] = $('#warehouses').val();
        paramsData['item_id'] = item_id;
        row.find('select.locations').val(null).trigger('change');
        row.find('select.locations').html('');
        if (item_id) {
            tr = $(sl).closest('tr');
            subtext = data.item_name;
            unit_name = data.unit_name;
            unit_id = data.unit_id;
            unit_parent_id = data.unit_parent_id;
            number_exchange = data.number_exchange;
            // images = data.images;
            // if (images) {
            //     tr.find('.td-image a').attr('href', site.base_url+images);
            //     tr.find('.td-image img').attr('src', site.base_url+images);
            // } else {
            //     tr.find('.td-image a').attr('href', site.base_url+'assets/images/tnh/no_image.png');
            //     tr.find('.td-image img').attr('src', site.base_url+'assets/images/tnh/no_image.png');
            // }
            tr.find('.unit_id').val(unit_id);
            tr.find('.unit_parent_id').val(unit_parent_id);
            tr.find('.number_exchange').val(number_exchange);

            tr.find('.td-item-name').html(subtext);
            tr.find('.td-unit').html(unit_name);
            tr.find('.td-value-exchange').html(number_exchange);

            lastrow = $('#tb-items tbody tr')[$('#tb-items tbody tr').length - 1];
            if ($(lastrow).find('input.items_id').select2('val')) {
                $('.add-row').click();
            }

            //ajax
            $.ajax({
                url: site.base_url+'admin/stock/rowItem',
                type: 'POST',
                dataType: 'json',
                data: paramsData
            })
            .done(function(data) {
                if (data) {
                    row.find('select.locations').html(getLocations(data.warehouses));
                }
            })
            .fail(function() {
                console.log("error");
            })
        } else {
            tr.find('.td-item-name').html('');
            tr.find('.td-image a').attr('href', site.base_url+'assets/images/tnh/no_image.png');
            tr.find('.td-image img').attr('src', site.base_url+'assets/images/tnh/no_image.png');
        }
        totalExportingProductions();
    });

    $('#productions_orders_detail_id').on('change', function(event) {
        var productions_orders_detail_id = $(this).val();
        dt.rows().remove().draw();
        warehouses = $('#warehouses').val();
        if (warehouses) {
            $('.add-row').click();
            totalExportingProductions();
        }
    });

    $(document).on('change', '.quantity, .quantity_sub', function(event) {
        totalExportingProductions();
    });


	$(document).on('click', '.remove-row', function(event) {
		event.preventDefault();
		dt.row( $(this).parents('tr') ).remove().draw();
		totalExportingProductions();
	});

    $(document).on('click', '.remove-sub', function(event) {
        event.preventDefault();
        $(this).closest('.row').remove();
        totalExportingProductions();
    });


    $(document).on('click', '.add-row-foot', function(event) {
        event.preventDefault();
        $('.add-row').click();
    });

    if (edit == 0) {
        // $('.add-row').click();
    	$(document).on('click', '.referesh-reference', function(event) {
    		event.preventDefault();
    		$.ajax({
    			url: site.base_url+'admin/stock/refereshReferenceProductionsOrders',
    			type: 'GET',
    			dataType: 'JSON',
    			data: {
    				token: hash,
    				'referesh': 1
    			},
    		})
    		.done(function(data) {
    			if (data) {
    				$('#reference_no').val(data.reference_no);
    				alert_float('success', data.message);
    			} else {
    				alert_float('danger', 'fail');
    			}
    		})
    		.fail(function() {
    			console.log("error");
    		});
    	});
    }

	appValidateForm($('#add-exporting'), {
		reference_no: 'required',
        date: 'required',
        export_name: 'required',
       	warehouses: 'required',
        productions_orders_detail_id: 'required'
    }, db);

    function db(form) {
        // if (count_errors > 0) {
        //     alert_float('danger', lang_core['check_date_enter']);
        //     return;
        // }
    	$('.add').attr('disabled', 'disabled');
        tinymce.get('note').save();
        // var data = $(form).serialize();
        var url = form.action;
        var form = $(form),
            formData = new FormData(),
            formParams = form.serializeArray();

        $.each(form.find('input[type="file"]'), function(i, tag) {
            $.each($(tag)[0].files, function(i, file) {
                formData.append(tag.name, file);
            });
        });
        $.each(formParams, function(i, val) {
            formData.append(val.name, val.value);
        });
        //
        $.ajax({
            // url : site.base_url+'admin/business_plan/add',
        	url : url,
        	type : 'POST',
        	dataType: 'JSON',
            cache : false,
            contentType : false,
            processData : false,
        	data: formData,
        })
        .done(function(data) {
        	if (data.result) {
        		alert_float('success', data.message);
        		window.location.href = site.base_url+'admin/stock/exporting_producion';
        	} else {
        		alert_float('danger', data.message);
        		$('.add').removeAttr('disabled', 'disabled');
        	}
        })
        .fail(function() {
            alert_float('danger', 'error');
        	$('.add').removeAttr('disabled', 'disabled');
        });
        return false;
    }
});

