function typeDesignBom(type_design_bom, select = '')
{
    options = '<option value=""></option>';
    $.each(type_design_bom, function(index, el) {
        selected = select == index ? 'selected' : '';
        options += '<option '+ selected +' value="'+ index +'">'+ el +'</option>';
    });
    return options;
}

function getUnits(units, select = '')
{
    options = '<option value=""></option>';
    $.each(units, function(index, el) {
        selected = select == el.unitid ? 'selected' : '';
        options += '<option '+ selected +' value="'+ el.unitid +'">'+ el.unit +'</option>';
    });
    return options;
}

$(function(){
	$(document).on('click', '.btn-add-element', function(event) {
		event.preventDefault();
		tr_html = '';
		tr_html += '<tr>';
        tr_html += '<input type="hidden" name="i[]" id="i" class="form-control i" value="'+i+'">'
		tr_html += '<td>\
						<div class="text-center">\
							<button type="button" class="btn btn-primary btn-icon btn-add-items">\
								<i class="fa fa-plus"></i>\
							</button>\
						</div>\
					</td>';

		tr_html += '<td colspan="2">\
						<input type="text" name="element_name_'+i+'" id="element_name_'+i+'" class="form-control" value="" placeholder="'+lang_bom['tnh_element_name']+'" required="required">\
					</td>';
        tr_html += '<td>\
                    </td>';
		tr_html += '<td>\
						<input type="number" name="element_number_'+i+'" class="form-control" value="1">\
					</td>';
		tr_html += '<td>\
						<div class="text-center"><i class="btn btn-danger fa fa-remove remove-element"></i></div>\
					</td>';
		tr_html += '</tr>';

		$('.table-bom tbody').append(tr_html);
        json['element_name_'+i+''] = 'required';
        // appValidateForm($('#add-category'), json, addBOM);
        i++;
	});

	$(document).on('click', '.btn-add-items', function(event) {
		event.preventDefault();
        row_element = $(this).closest('tr');
        i_current = row_element.find('.i').val();

        tr_html = '';
        tr_html += '<tr class="tnh-item-'+ i_current +'">';
        tr_html += '<td></td>';
        tr_html += '<input type="hidden" name="iii" id="iii" class="form-control iii" value="'+ i_current +'">';
        tr_html += '<input type="hidden" name="k[]" id="k" class="form-control k" value="'+ k +'">';
        tr_html += '<td colspan="1" style="width: 200px;">\
                        <select name="type_design_bom_'+ i_current +'[]" data-none-selected-text="'+ lang_bom['type'] +'" id="type_design_bom_'+ k +'" class="form-control type_design_bom" required="required">\
                            '+typeDesignBom(type_design_bom, 0)+'\
                        </select>\
                    </td>';
        // <select name="items_'+ i_current +'[]" placeholder="'+ lang_bom['choose'] +'" data-live-search="true" data-none-selected-text="'+ lang_bom['choose'] +'" id="items_'+ k +'" class="form-control" required="required">\
        //                     <option value=""></option>\
        //                 </select>\
        tr_html += '<td colspan="1">\
                        <input type="text" name="items_'+ i_current +'[]" id="items_'+ k +'" data-placeholder="'+ lang_bom['choose'] +'" class="modal-select2 it" style="width: 100%;" value="" required="required">\
                    </td>';
        tr_html += '<td class="td-unit" colspan="">\
                        <select data-placeholder="'+ lang_bom['choose'] +'" id="units_'+ k +'" name="units_'+ i_current +'[]" class="modal-select2 units" style="width: 100%;" required></select>\
                    </td>';
        tr_html += '<td colspan="">\
                        <input type="number" name="element_item_number_'+i_current+'[]" class="form-control" value="0" min="0">\
                    </td>';
        tr_html += '<td colspan="">\
                        <div class="text-center"><i class="btn btn-danger fa fa-remove remove-element-item"></i></div>\
                    </td>';
        tr_html += '</tr>';
        row_element.after(tr_html);
        json['type_design_bom_'+ k +''] = 'required';
        json['items_'+ k +''] = 'required';
        json['units_'+ k +''] = 'required';
        $('#type_design_bom_'+ k +'').selectpicker();
        $('select[name="units_'+ i_current +'[]"]').select2();
        // $('#items_'+ k +'').selectpicker();
        k++;
	});

    $(document).on('change', 'select.type_design_bom', function(event) {
        event.preventDefault();
        row_item = $(this).closest('tr');
        k_current = row_item.find('.k').val();
        type_current = $(this).val();
        if (type_current == "semi_products") {
            ajaxSelectParamsCallback('#items_'+ k_current +'', 'admin/products/searchSelect2SemiProducts', 0);
        } else if (type_current == "semi_products_outside") {
            ajaxSelectParamsCallback('#items_'+ k_current +'', 'admin/products/searchSelect2SemiProductsOutside', 0);
        } else {
            ajaxSelectParamsCallback('#items_'+ k_current +'', 'admin/items/searchSelect2Materials', 0);

            // $('select#items_'+ k_current +'').val('default').trigger('change');
            // $('select#items_'+ k_current +' option').remove();
            // $('select#items_'+ k_current +'').selectpicker("refresh");
            // $('select#items_'+ k_current +'').trigger('change');
            // selectAjax($('select#items_'+ k_current +''), false, 'admin/items/searchMaterials', 'items/searchMaterials');
            // $('select#items_'+ k_current +'').data('AjaxBootstrapSelect').options.ajax.url = 'items/searchMaterials';
        }
    });

    $(document).on('change', '.it', function(event) {
        tr = $(this).closest('tr');
        type = tr.find('select.type_design_bom').val();
        iii = tr.find('.iii').val();
        kk = tr.find('.k').val();
        item_id = $(this).val();
        $.ajax({
            url: site.base_url+'admin/products/rowItem',
            type: 'GET',
            dataType: 'json',
            data: {
                token: hash,
                type: type,
                item_id: item_id,
            },
        })
        .done(function(data) {
            if (data) {
                tr.find('select.units').html(getUnits(data.units, data.selected));
                tr.find('select.units').val(data.selected).trigger('change');
            }
        })
        .fail(function() {
            console.log("error");
        });
    });

    $(document).on('click', '.remove-element-item', function(event) {
        event.preventDefault();
        $(this).closest('tr').remove();
    });

    $(document).on('click', '.remove-element', function(event) {
        event.preventDefault();
        row_current = $(this).closest('tr');
        i_current = row_current.find('.i').val();
        row_current.remove();
        $('.tnh-item-'+i_current).remove();
    });

    // $(document).on('click', '.copy-bom', function(event) {
    //     event.preventDefault();
    // });
})