function initTnhDataTable(selector, url, notsearchable, notsortable, fnserverparams, defaultorder) {
    var table = typeof(selector) == 'string' ? $("body").find('table' + selector) : selector;

    if (table.length === 0) {
        return false;
    }

    fnserverparams = (fnserverparams == 'undefined' || typeof(fnserverparams) == 'undefined') ? [] : fnserverparams;

    // If not order is passed order by the first column
    if (typeof(defaultorder) == 'undefined') {
        defaultorder = [
            [0, 'asc']
        ];
    } else {
        if (defaultorder.length === 1) {
            defaultorder = [defaultorder];
        }
    }

    var user_table_default_order = table.attr('data-default-order');

    if (!empty(user_table_default_order)) {
        var tmp_new_default_order = JSON.parse(user_table_default_order);
        var new_defaultorder = [];
        for (var i in tmp_new_default_order) {
            // If the order index do not exists will throw errors
            if (table.find('thead th:eq(' + tmp_new_default_order[i][0] + ')').length > 0) {
                new_defaultorder.push(tmp_new_default_order[i]);
            }
        }
        if (new_defaultorder.length > 0) {
            defaultorder = new_defaultorder;
        }
    }

    var length_options = [10, 25, 50, 100];
    var length_options_names = [10, 25, 50, 100];

    app.options.tables_pagination_limit = parseFloat(app.options.tables_pagination_limit);

    if ($.inArray(app.options.tables_pagination_limit, length_options) == -1) {
        length_options.push(app.options.tables_pagination_limit);
        length_options_names.push(app.options.tables_pagination_limit);
    }

    length_options.sort(function(a, b) {
        return a - b;
    });
    length_options_names.sort(function(a, b) {
        return a - b;
    });

    length_options.push(-1);
    length_options_names.push(app.lang.dt_length_menu_all);

    var dtSettings = {
        "language": app.lang.datatables,
        "processing": true,
        "retrieve": true,
        "serverSide": true,
        'paginate': true,
        'searchDelay': 750,
        "bDeferRender": true,
        // "responsive": true,
        "autoWidth": false,
        dom: "<'row'><'row'<'col-md-7'lB><'col-md-5'f>>rt<'row'<'col-md-4'i>><'row'<'#colvis'><'.dt-page-jump'>p>",
        "pageLength": app.options.tables_pagination_limit,
        "lengthMenu": [length_options, length_options_names],
        "columnDefs": [{
            "searchable": false,
            "targets": notsearchable,
        }, {
            "sortable": false,
            "targets": notsortable
        }],
        "fnDrawCallback": function(oSettings) {
            _table_jump_to_page(this, oSettings);
            if (oSettings.aoData.length === 0) {
                $(oSettings.nTableWrapper).addClass('app_dt_empty');
            } else {
                $(oSettings.nTableWrapper).removeClass('app_dt_empty');
            }
        },
        "fnCreatedRow": function(nRow, aData, iDataIndex) {
            // If tooltips found
            $(nRow).attr('data-title', aData.Data_Title);
            $(nRow).attr('data-toggle', aData.Data_Toggle);
        },
        "initComplete": function(settings, json) {
            var t = this;
            var $btnReload = $('.btn-dt-reload');
            $btnReload.attr('data-toggle', 'tooltip');
            $btnReload.attr('title', app.lang.dt_button_reload);

            var $btnColVis = $('.dt-column-visibility');
            $btnColVis.attr('data-toggle', 'tooltip');
            $btnColVis.attr('title', app.lang.dt_button_column_visibility);

            if (t.hasClass('scroll-responsive') || app.options.scroll_responsive_tables == 1) {
                t.wrap('<div class="table-responsive"></div>');
            }

            var dtEmpty = t.find('.dataTables_empty');
            if (dtEmpty.length) {
                dtEmpty.attr('colspan', t.find('thead th').length);
            }

            // Hide mass selection because causing issue on small devices
            if (is_mobile() && $(window).width() < 400 && t.find('tbody td:first-child input[type="checkbox"]').length > 0) {
                t.DataTable().column(0).visible(false, false).columns.adjust();
                $("a[data-target*='bulk_actions']").addClass('hide');
            }

            t.parents('.table-loading').removeClass('table-loading');
            t.removeClass('dt-table-loading');
            var th_last_child = t.find('thead th:last-child');
            var th_first_child = t.find('thead th:first-child');
            if (th_last_child.text().trim() == app.lang.options) {
                th_last_child.addClass('not-export');
            }
            if (th_first_child.find('input[type="checkbox"]').length > 0) {
                th_first_child.addClass('not-export');
            }
        },
        "order": defaultorder,
        "ajax": {
            "url": url,
            "type": "POST",
            "data": function(d) {
                if (typeof(csrfData) !== 'undefined') {
                    d[csrfData['token_name']] = csrfData['hash'];
                }
                for (var key in fnserverparams) {
                    d[key] = $(fnserverparams[key]).val();
                }
                if (table.attr('data-last-order-identifier')) {
                    d['last_order_identifier'] = table.attr('data-last-order-identifier');
                }
            }
        },
        buttons: get_datatable_buttons(table),
    };

    if (table.hasClass('scroll-responsive') || app.options.scroll_responsive_tables == 1) {
        dtSettings.responsive = false;
    }

    table = table.dataTable(dtSettings);
    var tableApi = table.DataTable();

    var hiddenHeadings = table.find('th.not_visible');
    var hiddenIndexes = [];

    $.each(hiddenHeadings, function() {
        hiddenIndexes.push(this.cellIndex);
    });

    setTimeout(function() {
        for (var i in hiddenIndexes) {
            tableApi.columns(hiddenIndexes[i]).visible(false, false).columns.adjust();
        }
    }, 10);

    if (table.hasClass('customizable-table')) {

        var tableToggleAbleHeadings = table.find('th.toggleable');
        var invisible = $('#hidden-columns-' + table.attr('id'));
        try {
            invisible = JSON.parse(invisible.text());
        } catch (err) {
            invisible = [];
        }

        $.each(tableToggleAbleHeadings, function() {
            var cID = $(this).attr('id');
            if ($.inArray(cID, invisible) > -1) {
                tableApi.column('#' + cID).visible(false);
            }
        });
    }

    // Fix for hidden tables colspan not correct if the table is empty
    if (table.is(':hidden')) {
        table.find('.dataTables_empty').attr('colspan', table.find('thead th').length);
    }

    table.on('preXhr.dt', function(e, settings, data) {
        if (settings.jqXHR) settings.jqXHR.abort();
    });

    return tableApi;
}

function setResize()
{
    height_page = $('body').height();
    height_header = $('#header').height();
    height_title = $('#H_scroll').height();
    if ($('.status-table').height() > 0) {
        height_status_table = $('.status-table').height() + 25 + 25;
    } else {
        height_status_table = 0;
    }
    if ($('.minus-height-more')) {
        height_minus_more = 50;
    }
    height_body = (height_page - height_header - height_title - height_status_table - height_minus_more - 150) +'px';
    console.log(height_body);
    $('.dataTables_scrollBody').css('height', height_body);
}

$(window).resize(function(){
    setResize();
});
setResize();

$(document).on('click', '.tnh-modal', function(event) {
    event.preventDefault();
    this.blur();
    link = this.href;
    $.ajax({
    	url: link,
    	type: 'GET',
    	dataType: 'html',
    	data: {
    		token: hash
    	},
    })
    .done(function(data) {
    	$('#tnhModal').html(data);
    })
    .fail(function() {
    	console.log("error");
    });
    // $('#tnhModal').modal('show');
    $('#tnhModal').modal({backdrop: 'static', keyboard: true});
});

$(document).on('click', '.tnh-modal2', function(event) {
    event.preventDefault();
    this.blur();
    link = this.href;
    $.ajax({
        url: link,
        type: 'GET',
        dataType: 'html',
        data: {
            token: hash
        },
    })
    .done(function(data) {
        $('.modal-select2').select2('close');
        $('#tnhModal2').html(data);
    })
    .fail(function() {
        console.log("error");
    });
    // $('#tnhModal').modal('show');
    $('#tnhModal2').modal({backdrop: 'static', keyboard: true});
});

// $(document).on('hide.bs.modal', '.modal', function () {
// 	// tinyMCE.remove();
//     console.log(123);
//     $('.modal-select2').select2('close');
// });

// $(document).on('hidden.bs.modal', '.modal', function () {
//     console.log(456);
// });

$(document).on('click', '.close', function(event) {
    event.preventDefault();
    $('.modal-select2').select2('close');
});

function loadAjax()
{
    $(document).on({
        ajaxStart: function() { $("#loader").removeClass('hide'); },
        ajaxStop: function() { $("#loader").addClass('hide'); }
    });
}

function filterCustom(element_search, table, filters)
{
    $.each(filters, function(index, el) {
        title = el.label;
        element = el.element;
        if (typeof el.label == 'undefined')
        {
            title = $(element).text();
        }
        type = el.type;
        if (type == 'text')
        {
            $(element).html( '<input type="text"  placeholder="'+title+'" class="column_search form-control" style="width: 100%;" />' );
        }
    });
    $(element_search).on( 'keyup', ".column_search",function () {
        table.column( $(this).parent().index() ).search( this.value ).draw();
    });
}

// function reload

function tnhDatatable(selector, initParams)
{
    initParams.cache = false;
    initParams.pageLength = intVal(app.options.tables_pagination_limit);
    oTableCustom = $(selector).DataTable(initParams);
    reLoadDatatable();

    // setTimeout(function(){ oTableCustom.draw(); }, 1000);
    return oTableCustom;
}

function reLoadDatatable()
{
    $('div.reload').remove();
    $("div.dataTables_length").after('<div class="dt-buttons btn-group reload"><button class="btn btn-default btn-default-dt-options btn-dt-reload" tabindex="0" aria-controls="table-leads" type="button" data-toggle="tooltip" title="" data-original-title="Tải lại"><span><i class="fa fa-refresh"></i></span></button></div>');

    setTimeout(function(){
        width_table = $('.dataTables_scrollBody tbody').width();
        console.log(width_table);
        if (width_table > 100)
        {
            $('.dataTables_scrollHeadInner table').css('width', (width_table + 1)+'px');
            $('.dataTables_scrollFootInner table').css('width', (width_table + 1)+'px');
        }
        // oTableCustom.draw();
    }, 1000);
}

function reloadDataTableFullScreen() {
    $("div.dataTables_length").after('<div class="btn-group fullscreen"><button class="btn btn-default btn-default-dt-options" tabindex="0" aria-controls="table-leads" type="button" data-toggle="tooltip" title="" data-original-title=""><span><i class="fa fa-expand text-primary"></i></span></button></div>');
}

$(document).on('click', '.fullscreen', function(event) {
    event.preventDefault();
});

//popover
row_popover = '';
$(document).on('click', '.po', function() {
    row_popover = $(this).closest('div');
    // $(this).popover('show');
});

$(document).on('click', '.po-close', function() {
    // $('.po').popover('hide');
    row_popover.find('.po').trigger('click');
    return false;
});

$(document).on('click', '.po-delete', function() {
    row_popover = $(this).closest('div');
});

$(document).on('click', '.po-close-new', function() {
    // $(this).closest('.popover').popover('hide');
    row_popover.find('.po-delete').trigger('click');
    return false;
});

$(document).on('click', '.po-custom', function(e) {
    e.preventDefault();
    // $('.po-custom').popover({html: true, placement: 'auto', trigger: 'manual'}).popover('show').not(this).popover('hide');
    $('.po-custom').popover({html: true, placement: 'left', trigger: 'manual'}).popover('show').not(this).popover('hide');;
    return false;
});
$(document).on('click', '.po-close-custom', function() {
    $('.po-custom').popover('hide');
    return false;
});

$(document).on('click', '.po-delete-json', function(e) {
    var row = $(this).closest('tr');
    e.preventDefault();
    $('.po').popover('hide');
    $('.po-delete').popover('hide');
    var link = $(this).attr('href');
    $.ajax({
        url: link,
        type: 'GET',
        dataType: 'JSON',
        data: {
            param1: 'value1'
        },
    })
    .done(function(data) {
        if (data)
        {
            if (data.result == 1) {
                if (typeof data.table != 'undefined')
                {
                    if (data.type == "BOM") {
                        $('table[data-bom="'+data.table+'"]').remove();
                    } else if (data.type == 'stages') {
                        $('table[data-stages="'+data.table+'"]').remove();
                    } else if (data.type = 'type') {
                        if (typeof dtSuggest != 'undefined') {
                            dtSuggest.draw('page');
                        }
                    }
                } else {
                    if (typeof oTable != 'undefined') {
                        oTable.draw('page');
                    }
                }
                alert_float('success', data.message);
            } else {
                alert_float('danger', data.message);
            }
        }
    })
    .fail(function() {
        alert_float('danger', 'fail');
    })
    return false;
});

$(document).on('click', '.delete-confirm-json', function(event) {
    event.preventDefault();
    var row = $(this).closest('tr');
    var link = $(this).attr('href');
    bootbox.confirm({
        message: lang_core['you_want_remove'],
        buttons: {
            confirm: {
                label: lang_core['yes'],
                className: 'btn-success'
            },
            cancel: {
                label: lang_core['no'],
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result) {
                $.ajax({
                    url: link,
                    type: 'GET',
                    dataType: 'JSON',
                    data: {
                        delete: true
                    },
                })
                .done(function(data) {
                    if (data)
                    {
                        if (data.result == 1) {
                            if (typeof oTable != 'undefined') {
                                oTable.draw('page');
                            }
                            alert_float('success', data.message);
                        } else {
                            alert_float('danger', data.message);
                        }
                        if (typeof data.errors != "undefined" && data.errors) {
                            $('.show-alert').show();
                            $('.show-errors').html(data.errors);
                        }
                    }
                })
                .fail(function() {
                    alert_float('danger', 'fail');
                })
            }
        }
    });
    return false;
});

$(document).on('click', '.po-delete-multiple-json', function(e) {
    var row = $(this).closest('tr');
    e.preventDefault();
    $('.po').popover('hide');
    var data = $('form').serialize();
    var link = $(this).attr('href');
    $.ajax({
        url: link,
        type: 'POST',
        dataType: 'JSON',
        data: data
    })
    .done(function(data) {
        if (data)
        {
            if (data.result == 1) {
                if (typeof oTable != 'undefined') {
                    oTable.draw();
                }
                alert_float('success', data.message);
            } else {
                alert_float('danger', data.message);
            }
            if (typeof data.errors != "undefined" && data.errors) {
                $('.show-alert').show();
                $('.show-errors').html(data.errors);
            }
        }
    })
    .fail(function() {
        alert_float('danger', 'fail');
    })
    return false;
});


function selectAjax(selector, server_data, link, change_link = false, attrs = false)
{
    var ajaxSelector = $('body').find(selector);
    if (ajaxSelector.length) {
        var options = {
            ajax: {
                url: site.base_url+link,
                type: "GET",
                data: function() {
                    var data = {};
                    data.rel_id = '';
                    data.q = '{{{q}}}';
                    data.token = hash;
                    if (typeof(server_data) != 'undefined') {
                        jQuery.extend(data, server_data);
                    }
                    return data;
                }
            },
            locale: {
                emptyTitle: app.lang.search_ajax_empty,
                statusInitialized: app.lang.search_ajax_initialized,
                statusSearching: app.lang.search_ajax_searching,
                statusNoResults: app.lang.not_results_found,
                searchPlaceholder: app.lang.search_ajax_placeholder,
                currentlySelected: app.lang.currently_selected
            },
            requestDelay: 500,
            cache: false,
            preprocessData: function(processData) {
                var bs_data = [];
                var len = processData.length;
                for (var i = 0; i < len; i++) {
                    var tmp_data = {
                        'value': processData[i].id,
                        'text': processData[i].name,
                    };
                    if (attrs == 'products') {
                        tmp_data.data = {
                            subtext: processData[i].product_name,
                            image: processData[i].images,
                        };
                    } else if (attrs == true) {
                        tmp_data.data = {
                            subtext: processData[i].subtext,
                        };
                    }
                    // if (processData[i].subtext) {
                    //     tmp_data.data = { subtext: processData[i].subtext };
                    // }
                    bs_data.push(tmp_data);
                }
                return bs_data;
            },
            preserveSelectedPosition: 'before',
            preserveSelected: true
        };
        if (ajaxSelector.data('empty-title')) {
            options.locale.emptyTitle = ajaxSelector.data('empty-title');
        }
        ajaxSelector.selectpicker().ajaxSelectPicker(options);
        if (change_link) {
            ajaxSelector.data('AjaxBootstrapSelect').options.ajax.url = change_link;
        }
    }
}



function formatNumberCus(nStr, decSeperate, groupSeperate) {
    //decSeperate= ki tu cach,groupSeperate= ki tu noi
    nStr += '';
    x = nStr.split(decSeperate);
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
    }
    // console.log(x1);
    return x1 + x2;
}

function formatNumBerKeyUpCus(id_input)
{
    // key = "";
    // money = $(id_input).val().replace(/[^\-\d\.]/g, '');
    // a=money.split(".");
    // $.each(a , function (index, value){
    //     key=key+value;
    // });
    // $(id_input).val(formatNumberCus(money, '.', ','));

    vl = $(id_input).val().replace(/[^\-\d\.]/g, '');
    vl = vl.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    $(id_input).val(vl)
}

function formatNumberTnh(nStr, decSeperate=".", groupSeperate=",") {
    nStr += '';
    x = nStr.split(decSeperate);
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    x2=x2.substr(0,2);
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
    }
    return x1 + x2;
};

$(document).ready(function() {
    $(document).ready(function() {
        $(document).on('click', '.dropdown-menu .not-outside', function(event) {
            event.stopPropagation();
        });
    });
});

$(document).ready(function() {
    $(document).on('changed.bs.select', 'select.unit_exchange', function (e, clickedIndex, isSelected, previousValue) {
        lastrow = $('.table-exchange tbody tr')[$('.table-exchange tbody tr').length - 1];
        if ($(lastrow).find('select.unit_exchange').val() > 0) {
            $('.table-exchange thead tr th .btn-add-items').trigger('click');
        }
    });
});


function download(filename, text) {
    var element = document.createElement('a');
    element.setAttribute('href', text);
    element.setAttribute('download', filename);
    element.style.display = 'none';
    document.body.appendChild(element);
    element.click();
    document.body.removeChild(element);
}

function optionCloumnExcel(cloumn_excel, selected_cloumn = 0)
{
    option = '<option></option>';
    $.each(cloumn_excel, function(index, el) {
        selected = selected_cloumn == el ? 'selected' : '';
        option+= '<option '+selected+' value="'+el+'">'+el+'</option>';
    });
    return option;
}

function optionFields(fields, selected_field = 0)
{
    option = '<option></option>';
    $.each(fields, function(index, el) {
        selected = selected_field == index ? 'selected' : '';
        option+= '<option '+selected+' value="'+index+'">'+el+'</option>';
    });
    return option;
}

function textErrors(text)
{
    return '<div class="text-danger">'+text+'</div>';
}

function bs_input_file() {
    $(".input-file").before(
        function() {
            if ( ! $(this).prev().hasClass('input-ghost') ) {
                var element = $("<input type='file' class='input-ghost' style='visibility:hidden; height:0'>");
                element.attr("name",$(this).attr("name"));
                element.change(function(){
                    element.next(element).find('input').val((element.val()).split('\\').pop());
                });
                $(this).find("button.btn-choose").click(function(){
                    element.click();
                });
                $(this).find("button.btn-reset").click(function(){
                    element.val(null);
                    $(this).parents(".input-file").find('input').val('');
                });
                $(this).find('input').css("cursor","pointer");
                $(this).find('input').mousedown(function() {
                    $(this).parents('.input-file').prev().click();
                    return false;
                });
                return element;
            }
        }
    );
}

function fld(oObj) {
    if (typeof oObj != 'undefined' && oObj != null) {
        var aDate = oObj.split('-');
        var bDate = aDate[2].split(' ');
        year = aDate[0], month = aDate[1], day = bDate[0], time = bDate[1];
        return day + "/" + month + "/" + year + " " + time;
        // if (site.dateFormats.js_sdate == 'dd-mm-yyyy')
        //     return day + "-" + month + "-" + year + " " + time;
        // else if (site.dateFormats.js_sdate === 'dd/mm/yyyy')
        //     return day + "/" + month + "/" + year + " " + time;
        // else if (site.dateFormats.js_sdate == 'dd.mm.yyyy')
        //     return day + "." + month + "." + year + " " + time;
        // else if (site.dateFormats.js_sdate == 'mm/dd/yyyy')
        //     return month + "/" + day + "/" + year + " " + time;
        // else if (site.dateFormats.js_sdate == 'mm-dd-yyyy')
        //     return month + "-" + day + "-" + year + " " + time;
        // else if (site.dateFormats.js_sdate == 'mm.dd.yyyy')
        //     return month + "." + day + "." + year + " " + time;
        // else
        //     return oObj;
    } else {
        return '';
    }
}


function fsd(oObj) {
    if (typeof oObj != 'undefined' && oObj != null) {
        var aDate = oObj.split('-');
        return aDate[2] + "/" + aDate[1] + "/" + aDate[0];
        // if (site.dateFormats.js_sdate == 'dd-mm-yyyy')
        //     return aDate[2] + "-" + aDate[1] + "-" + aDate[0];
        // else if (site.dateFormats.js_sdate === 'dd/mm/yyyy')
        //     return aDate[2] + "/" + aDate[1] + "/" + aDate[0];
        // else if (site.dateFormats.js_sdate == 'dd.mm.yyyy')
        //     return aDate[2] + "." + aDate[1] + "." + aDate[0];
        // else if (site.dateFormats.js_sdate == 'mm/dd/yyyy')
        //     return aDate[1] + "/" + aDate[2] + "/" + aDate[0];
        // else if (site.dateFormats.js_sdate == 'mm-dd-yyyy')
        //     return aDate[1] + "-" + aDate[2] + "-" + aDate[0];
        // else if (site.dateFormats.js_sdate == 'mm.dd.yyyy')
        //     return aDate[1] + "." + aDate[2] + "." + aDate[0];
        // else
        //     return oObj;
    } else {
        return '';
    }
}

$(document).ready(function() {
    $('.dateranger').daterangepicker({
        // "locale": {
        //     lang_daterangepicker
        // }
        "locale": {
            "format": "DD/MM/YYYY",
            "separator": " - ",
            "applyLabel": lang_daterangepicker.applyLabel,
            "cancelLabel": lang_daterangepicker.cancelLabel,
            "fromLabel": lang_daterangepicker.fromLabel,
            "toLabel": lang_daterangepicker.toLabel,
            "customRangeLabel": lang_daterangepicker.customRangeLabel,
            "daysOfWeek": lang_daterangepicker.daysOfWeek,
            "monthNames": lang_daterangepicker.monthNames
            // "daysOfWeek": [
            //     "Su",
            //     "Mo",
            //     "Tu",
            //     "We",
            //     "Th",
            //     "Fr",
            //     "Sa"
            // ],
            // "monthNames": [
            //     "January",
            //     "February",
            //     "March",
            //     "April",
            //     "May",
            //     "June",
            //     "July",
            //     "August",
            //     "September",
            //     "October",
            //     "November",
            //     "December"
            // ],
        }
    });
});

$(document).ready(function() {
    $('table').addClass('dont-responsive-table');
});

function formatSA (x) {
    x=x.toString();
    var afterPoint = '';
    if(x.indexOf('.') > 0)
       afterPoint = x.substring(x.indexOf('.'),x.length);
    x = Math.floor(x);
    x=x.toString();
    var lastThree = x.substring(x.length-3);
    var otherNumbers = x.substring(0,x.length-3);
    if(otherNumbers != '')
        lastThree = ',' + lastThree;
    var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree + afterPoint;

    return res;
}

function tnhFormatNumber(x, d = 0) {
    if(!d) { d = site.decimals_number; }
    return accounting.formatNumber(x, d, site.thousands_sep == 0 ? ' ' : site.thousands_sep, site.decimals_sep);
}

function tnhFormatMoney(x, d = 0) {
    if(!d) { d = site.decimals_money; }
    return accounting.formatNumber(x, d, site.thousands_sep == 0 ? ' ' : site.thousands_sep, site.decimals_sep);
}

function tnhFormatMoneySymbol(x, symbol) {
    if(!symbol) { symbol = ""; }
    if(site.sac == 1) {
        return symbol+''+formatSA(parseFloat(x).toFixed(site.decimals_money));
    }
    return accounting.formatMoney(x, symbol, site.decimals, site.thousands_sep == 0 ? ' ' : site.thousands_sep, site.decimals_sep, "%s%v");
}

function arraysEqual(arr1, arr2) {
    if(arr1.length !== arr2.length)
        return false;
    for(var i = arr1.length; i--;) {
        if(arr1[i] !== arr2[i])
            return false;
    }

    return true;
}

function removeArray(data, removeItem) {
    data = jQuery.grep(data, function(value) {
        return value != removeItem;
    });
    return data;
}

function openFullscreen(elem) {
    if (elem.requestFullscreen) {
        elem.requestFullscreen();
    } else if (elem.mozRequestFullScreen) { /* Firefox */
        elem.mozRequestFullScreen();
    } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
        elem.webkitRequestFullscreen();
    } else if (elem.msRequestFullscreen) { /* IE/Edge */
        elem.msRequestFullscreen();
    }
}

/* Close fullscreen */
function closeFullscreen() {
    if (document.exitFullscreen) {
        document.exitFullscreen();
    } else if (document.mozCancelFullScreen) { /* Firefox */
        document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) { /* Chrome, Safari and Opera */
        document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) { /* IE/Edge */
        document.msExitFullscreen();
    }
}

function ajaxSelectCallBack(element, url, id, types = '')
{
    if (id != 0)
    {
        $(element).val(id).select2({
            // minimumInputLength: 1,
            width: 'resolve',
            allowClear: true,
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url + url + '/' + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        callback(data.row);
                    }
                });
            },
            ajax: {
                url: site.base_url + url,
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        types: types,
                        term: term,
                        limit: 50
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });
    } else {
        $(element).select2({
            // minimumInputLength: 1,
            width: 'resolve',
            allowClear: true,
            ajax: {
                url: site.base_url + url,
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        types: types,
                        term: term,
                        limit: 50
                    };
                },
                results: function (data, page) {
                    if(data.results != null) {
                        return { results: data.results };
                    } else {
                        return { results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });
    }
}

function ajaxSelectParams(element, url, id, params = false)
{
    if (id)
    {
        $(element).val(id).select2({
            // minimumInputLength: 1,
            width: 'resolve',
            allowClear: true,
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url + url + '/' + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        callback(data.row);
                    }
                });
            },
            ajax: {
                url: site.base_url + url,
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        params: params,
                        term: term,
                        limit: 50
                    };
                },
                results: function (data, page) {
                    if(data.results != null) {
                        return { results: data.results };
                    } else {
                        return { results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });
    } else {
        $(element).select2({
            // minimumInputLength: 1,
            width: 'resolve',
            allowClear: true,
            ajax: {
                url: site.base_url + url,
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        params: params,
                        term: term,
                        limit: 50
                    };
                },
                results: function (data, page) {
                    if(data.results != null) {
                        return { results: data.results };
                    } else {
                        return { results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });
    }
}

function ajaxSelectParamsCallback(element, url, id, params = false)
{
    if (id != 0)
    {
        $(element).val(id).select2({
            // minimumInputLength: 1,
            width: 'resolve',
            allowClear: true,
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url + url + '/' + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        callback(data.row);
                    }
                });
            },
            ajax: {
                url: site.base_url + url,
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        params: params,
                        term: term,
                        limit: 50
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });
    } else {
        $(element).select2({
            // minimumInputLength: 1,
            width: 'resolve',
            allowClear: true,
            ajax: {
                url: site.base_url + url,
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        params: params,
                        term: term,
                        limit: 50
                    };
                },
                results: function (data, page) {
                    if(data.results != null) {
                        return { results: data.results };
                    } else {
                        return { results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });
    }
}

function formatCustomer(result)
{
    if (!result.id) return result.text; // optgroup
    tr = '';
    if (result) {
        tr+= '<td style="width: 33%;">'+result.text+'</td>';
        tr+= '<td style="width: 33%;">'+result.fullname+'</td>';
        tr+= '<td style="width: 33%;">'+result.phonenumber+'</td>';
    }
    tableSelect = '<table class="tnh-table table-bordered dont-responsive-table">'+
                        '<tbody>'+
                            tr
                        '</tbody>'+
                    '</table>';
    return tableSelect;
}

function ajaxSelectCustomerFormatTableCallBack(element, url, id)
{
    if (id)
    {
        $(element).val(id).select2({
            // minimumInputLength: 1,
            width: 'resolve',
            allowClear: true,
            formatResult: formatCustomer,
            escapeMarkup: function(m) {
                return m;
            },
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url + url + '/' + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        callback(data.row);
                    }
                });
            },
            ajax: {
                url: site.base_url + url,
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 50
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });
    } else {
        $(element).select2({
            // minimumInputLength: 1,
            width: 'resolve',
            allowClear: true,
            formatResult: formatCustomer,
            // formatSelection: formatTable,
            escapeMarkup: function(m) {
                return m;
            },
            ajax: {
                url: site.base_url + url,
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 50
                    };
                },
                results: function (data, page) {
                    if(data.results != null) {
                        return { results: data.results };
                    } else {
                        return { results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });
    }
}

function ajaxSelectFormatTableCallBack(element, url, id)
{
    if (id > 0)
    {
        $(element).val(id).select2({
            // minimumInputLength: 1,
            width: 'resolve',
            allowClear: true,
            formatResult: formatTable,
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url + url + '/' + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        callback(data.results);
                    }
                });
            },
            ajax: {
                url: site.base_url + url,
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 50
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });
    } else {
        $(element).select2({
            // minimumInputLength: 1,
            width: 'resolve',
            allowClear: true,
            formatResult: formatTable,
            // formatSelection: formatTable,
            escapeMarkup: function(m) {
                return m;
            },
            ajax: {
                url: site.base_url + url,
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 50
                    };
                },
                results: function (data, page) {
                    if(data.results != null) {
                        return { results: data.results };
                    } else {
                        return { results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });
    }
}

function ajaxSelectParentCallBack(element, url, id)
{
    if (id > 0)
    {
        $(element).val(id).select2({
            // minimumInputLength: 1,
            width: 'resolve',
            allowClear: true,
            formatResult: formatParent,
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url + url + '/' + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        callback(data.results);
                    }
                });
            },
            ajax: {
                url: site.base_url + url,
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 50
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });
    } else {
        $(element).select2({
            // minimumInputLength: 1,
            width: 'resolve',
            allowClear: true,
            formatResult: formatParent,
            // formatSelection: formatTable,
            escapeMarkup: function(m) {
                return m;
            },
            ajax: {
                url: site.base_url + url,
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 50
                    };
                },
                results: function (data, page) {
                    if(data.results != null) {
                        return { results: data.results };
                    } else {
                        return { results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });
    }
}

function ajaxSelectMultipleCallBack(element, url, id, types = '')
{
    if (id > 0)
    {
        $(element).val(id).select2({
            // minimumInputLength: 1,
            width: 'resolve',
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url + url + '/' + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        callback(data.results);
                    }
                });
            },
            ajax: {
                url: site.base_url + url,
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        types: types,
                        term: term,
                        limit: 50
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });
    } else {
        $(element).select2({
            // minimumInputLength: 1,
            multiple: true,
            allowClear: true,
            width: 'resolve',
            ajax: {
                url: site.base_url + url,
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        types: types,
                        term: term,
                        limit: 50
                    };
                },
                results: function (data, page) {
                    if(data.results != null) {
                        return { results: data.results };
                    } else {
                        return { results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });
    }
}

$(document).ready(function() {
    $('.tnh-select').select2();

    $(document).on('change', '.tnh-select', function(event) {
        event.preventDefault();
        if ($(this).val()) {
            $('#departments-error').html('');
        }
    });
    $(document).on('change', '#customers', function(event) {
        event.preventDefault();
        if ($(this).val()) {
            $('#customers-error').html('');
        }
    });

    $(document).on('change', '#address_delivery', function(event) {
        event.preventDefault();
        if ($(this).val()) {
            $('#address_delivery-error').html('');
        }
    });
    $(document).on('click', '.number-format', function(event) {
        event.preventDefault();
        // $(this).select();
        // formatNumBerKeyUpCus(this);
    });
    $(document).on('click', '.money-format', function(event) {
        event.preventDefault();
        // $(this).select();
    });

    $(document).on('change', '.number-format', function(event) {
        event.preventDefault();
        // $(this).select();
        formatNumBerKeyUpCus(this);
    });
    $(document).on('change', '.money-format', function(event) {
        event.preventDefault();
        // $(this).select();
        formatNumBerKeyUpCus(this);
    });

    formatNumberPlugin();
    formatMoneyPlugin();
});

function formatNumberPlugin() {
    // $('.number-format').number(true, site.decimals_number, site.decimals_sep, site.thousands_sep);
}

function formatMoneyPlugin() {
    // $('.money-format').number(true, site.decimals_money, site.decimals_sep, site.thousands_sep);
}

$("body").on("change", "#mass_select_all", function() {
    var e, t, a;
    e = $(this).data("to-table"), t = $(".table-" + e).find("tbody tr"), a = $(this).prop("checked"), $.each(t, function() {
        $($(this).find("td").eq(1)).find("input").prop("checked", a)
    })
});

