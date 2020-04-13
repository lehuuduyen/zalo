function totalProductionsOrders()
{
    tb = '#tb-productions-orders tbody tr:not("[class^=not-tr]")';
    var table = $(tb).length;
    var stt = 0;
    var total_quantity = 0;
    count_errors = 0;
    for (ii = 0; ii < table; ii++)
    {
        stt++;
        element = $(tb)[ii];
        $(element).find('.stt').html(stt);
        quantity = intVal($(element).find('.quantity').val());
        quantity_sub = 0;
        $.each($(element).find('.quantity_sub'), function(index, el) {
            quantity_sub+= intVal($(el).val());
        });

        total_quantity+= quantity;
    }
    $('.th-total-quantity').html(tnhFormatNumber(total_quantity));
}

function addRowShipping(counter, _this)
{
    var div = $(_this).closest('.td-date');

    html = '<div class="row">'+
                '<div class="col-md-7" style="padding: 0px;"><input type="text" name="date_sub['+counter+'][]" id="input" class="form-control datepicker date_sub" placeholder="'+lang_core['date']+'" value="" style="width: 100%;" title=""></div>'+
                '<div class="col-md-4" style="padding: 0px;"><input type="text" onkeyup="formatNumBerKeyUpCus(this)" style="width: 100%;" name="quantity_sub['+counter+'][]" id="input" class="form-control quantity_sub" value="0" title=""></div>'+
                '<div class="col-md-1" style="padding: 0px;"><div style="margin: 50%;"><i class="fa fa-remove remove-sub pointer text-danger"></i></div></div>'+
            '</div>';
    div.find('.sub').append(html);
    totalProductionsOrders();
    init_datepicker();
}

$(document).ready(function() {
	init_editor('textarea[name="note"]');
    // selectAjax('#productions_plan', false, 'admin/manufactures/searchProductionsPlanForOrders', false, true);
    ajaxSelectMultipleCallBack('#productions_plan', 'admin/manufactures/searchProductionsPlanForOrders', 0);
	var dt = $('#tb-productions-orders').DataTable({
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
		td1 = '<div class="stt text-center"></div>';
		td2 = '<input type="hidden" name="counter[]" id="input" class="form-control" value="'+counter+'">\
            <input type="text" name="items_id[]" id="items_'+counter+'" class="items_id" style="width: 100%;" data-placeholder="'+ lang_core['choose'] +'" value="">';
        td3 = '<div class="td-image">'+
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
        td4 = '<div class="td-item-name">'+lang_core['product_name']+'</div>';
        td5 = '<div class="td-quantity"><input type="text" onkeyup="formatNumBerKeyUpCus(this)" name="quantity[]" id="quantity[]" class="form-control quantity" value="0"></div>';
        td6 = '<div class="td-date">'+
                '<div class="sub"></div>'+
                '<a class="pointer" onclick="addRowShipping('+counter+', this)"><i class="fa fa-plus"></i> '+lang_core['expected_date']+'</a>'+
                '<div class="text-danger show-errors"></div>'+
            '</div>';
        td7 = '<div class="td-note"><textarea name="note_items[]" id="note_items[]" class="form-control" rows="3"></textarea></div>';
		td8 = '<div class="text-center"><i class="fa fa-remove btn btn-danger remove-row"></i></div>';

		rowNode = dt.row.add( [
            td1,
            td2,
            td3,
            td4,
            td5,
            td7,
            td8
        ] ).draw( false ).node();
        // selectAjax($('select#items_'+ counter +''), false, 'admin/products/searchProducts', false, attrs = 'products');
        ajaxSelectCallBack($('#items_'+ counter +''), 'admin/products/searchProductsSelect2', 0);
        counter++;
        totalProductionsOrders();
	});

    $(document).on('change', '.items_id', function(event) {
        event.preventDefault();
        data = event.added;
        sl = this;
        item_id = $(sl).val();
        if (item_id) {
            tr = $(sl).closest('tr');
            subtext = data.item_name;
            images = data.images;
            if (images) {
                tr.find('.td-image a').attr('href', site.base_url+images);
                tr.find('.td-image img').attr('src', site.base_url+images);
            } else {
                tr.find('.td-image a').attr('href', site.base_url+'assets/images/tnh/no_image.png');
                tr.find('.td-image img').attr('src', site.base_url+'assets/images/tnh/no_image.png');
            }
            tr.find('.td-item-name').html(subtext);

            lastrow = $('#tb-productions-orders tbody tr')[$('#tb-productions-orders tbody tr').length - 1];
            if ($(lastrow).find('input.items_id').select2('val')) {
                $('.add-row').click();
            }
        } else {
            tr.find('.td-item-name').html(lang_core['product_name']);
            tr.find('.td-image a').attr('href', site.base_url+'assets/images/tnh/no_image.png');
            tr.find('.td-image img').attr('src', site.base_url+'assets/images/tnh/no_image.png');
        }
    });
    // select2-selecting
    $('#productions_plan').on('change', function(event) {
        // var productions_plan_id = event.object.id;
        var productions_plan_id = $(this).val();
        // if (productions_plan_id) {
            $.ajax({
                url: site.base_url+'admin/manufactures/getItemsProductionsPlan',
                type: 'GET',
                dataType: 'JSON',
                data: {
                    token: hash,
                    productions_plan_id: productions_plan_id,
                },
            })
            .done(function(data) {
                $('.tr-production-plan').find('.remove-row').trigger('click');
                if (data) {
                    $.each(data.result, function(index, el) {
                        items_id = el.product_id;
                        code = el.code;
                        name = el.name;
                        images = el.images;
                        quantity = el.total_quantity;

                        if (images) {
                            images = site.base_url+images;
                        } else {
                            images = site.base_url+'assets/images/tnh/no_image.png';
                        }
                        td1 = '<div class="stt text-center"></div>';
                        td2 = '<input type="hidden" name="counter[]" id="input" class="form-control" value="'+counter+'">\
                            <input type="hidden" name="items_id[]" id="items_'+counter+'" class="items_id" style="width: 100%;" data-placeholder="'+ lang_core['choose'] +'" value="'+items_id+'">'+code;
                        td3 = '<div class="td-image">'+
                                    '<div class="preview_image" style="width: auto;">'+
                                        '<div class="display-block contract-attachment-wrapper img">'+
                                            '<div style="width:45px;">'+
                                                '<a href="'+images+'" data-lightbox="customer-profile" class="display-block mbot5">'+
                                                    '<div class="">'+
                                                        '<img src="'+images+'" style="border-radius: 50%">'+
                                                    '</div>'+
                                                '</a>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                            '</div>';
                        td4 = '<div class="td-item-name">'+name+'</div>';
                        td5 = '<div class="td-quantity"><input type="text" onkeyup="formatNumBerKeyUpCus(this)" name="quantity[]" id="quantity[]" class="form-control quantity" value="'+tnhFormatNumber(quantity)+'"></div>';
                        td6 = '<div class="td-note"><textarea name="note_items[]" id="note_items[]" class="form-control" rows="3"></textarea></div>';
                        td7 = '<div class="text-center"><i class="fa fa-remove btn btn-danger remove-row"></i></div>';

                        rowNode = dt.row.add( [
                            td1,
                            td2,
                            td3,
                            td4,
                            td5,
                            td6,
                            td7
                        ] ).draw( false ).node();
                        $(rowNode).addClass('tr-production-plan');
                        counter++;
                        // $('.tr-production-plan').remove();
                    // dt.draw(false);
                    });
                    totalProductionsOrders();

                }
            })
            .fail(function() {
                console.log("error");
            });
        // }
    });

    $('#enquiery').on('select2-removed', function (e) {
        // enquiery = e.val;
    });

    $(document).on('change', '.quantity, .quantity_sub', function(event) {
        totalProductionsOrders();
    });


	$(document).on('click', '.remove-row', function(event) {
		event.preventDefault();
		dt.row( $(this).parents('tr') ).remove().draw();
		totalProductionsOrders();
	});

    $(document).on('click', '.remove-sub', function(event) {
        event.preventDefault();
        $(this).closest('.row').remove();
        totalProductionsOrders();
    });


    $(document).on('click', '.add-row-foot', function(event) {
        event.preventDefault();
        $('.add-row').click();
    });

    if (edit == 0) {
        $('.add-row').click();
    	$(document).on('click', '.referesh-reference', function(event) {
    		event.preventDefault();
    		$.ajax({
    			url: site.base_url+'admin/manufactures/refereshReferenceProductionsOrders',
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

	appValidateForm($('#add-productions-orders'), {
		reference_no: 'required',
        date: 'required',
       	location: 'required',
    }, db);

    function db(form) {
        if (count_errors > 0) {
            alert_float('danger', lang_core['check_date_enter']);
            return;
        }
    	$('.add').attr('disabled', 'disabled');
        tinymce.get('note').save();
        // var data = $(form).serialize();
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
        var url = form.action;
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
        		window.location.href = site.base_url+'admin/manufactures/productions_orders';
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

