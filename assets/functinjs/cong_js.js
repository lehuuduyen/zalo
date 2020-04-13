//Delete data
$('table').on('click', '._deleteRow', function(e){
    if(confirm(app.lang.confirm_action_prompt))
    {
        if(confirm(app.lang.comfim_delete_all_list))
        {
            $('.alert_typeTbable').html('');
            var button = $(this);
            button.button({loadingText: 'please wait...'});
            button.button('loading');
            var table = $(this).parents('table.dataTable');
            var data = {};
            if (typeof(csrfData) !== 'undefined') {
                data[csrfData['token_name']] = csrfData['hash'];
            }
            $.post($(this).attr('href'), data, function(result){
                result = JSON.parse(result);
                if(result.success)
                {
                    table.DataTable().ajax.reload();
                }
                else
                {
                    if(result.ktConnect)
                    {
                        $.each(result.ktConnect, function(i, v){
                            $('.alert_typeTbable').append('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+v.message+' : '+v.data+'</div>');
                        })
                    }
                }
                alert_float(result.alert_type, result.message);
                return false;
            }).always(function() {
                button.button('reset')
            });
        }
    }
    return false;
})


function DeleteList(ThisTable, href)
{
    if(confirm(app.lang.confirm_action_prompt)) {
        if(confirm(app.lang.comfim_delete_all_list)) {
            $('.alert_typeTbable').html('');
            var Table = $(ThisTable);
            var MassSelect = Table.find('tbody').find('td:nth-child(1)').find('input[type="checkbox"]:checked');
            var ListID = [];
            $.each(MassSelect, function (i, v) {
                ListID.push($(v).val());
            })
            var data = {};
            if (typeof (csrfData) !== 'undefined') {
                data[csrfData['token_name']] = csrfData['hash'];
            }
            data['listData'] = ListID;
            $.post(admin_url + href, data, function (data) {
                data = JSON.parse(data);
                if (data.success) {
                    $(ThisTable).DataTable().ajax.reload();
                }
                if (data.ktConnect) {
                    $.each(data.ktConnect, function (i, v) {
                        var StringTab = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + v.code;
                        $.each(v.data, function (ii, vv) {
                            StringTab += '<p>' + vv.message + ' : ' + vv.data + '</p>';

                        })
                        StringTab += '</div>';
                        $('.alert_typeTbable').append(StringTab);

                    })
                }
                alert_float(data.alert_type, data.message);
            })
        }
    }
}

function deleteData(id, href)
{
    if($.isNumeric(id))
    {
        if(confirm(app.lang.confirm_action_prompt))
        {
            var data = {id : id};
            if (typeof(csrfData) !== 'undefined') {
                data[csrfData['token_name']] = csrfData['hash'];
            }
            $.post(admin_url+href, data, function (result) {
                result = JSON.parse(result);
                if(result.success)
                {
                    alert_float(result.alert_type,  result.message);
                }
                $('.dataTable').DataTable().ajax.reload();
            })
        }
    }
}

function Backloader()
{
    $(document).on({
        ajaxStart: function() {
            $('.dataTables_processing').remove(),$("#loader").removeClass('hide');
        },
        ajaxStop: function() { $("#loader").addClass('hide'); }
    });
}



function ajaxSelectGroupOption_C(element, url, id, types = '')
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
                    url: url + '/' + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                        callback(data.results);
                    }
                });
            },
            ajax: {
                url: url,
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
            },
            formatResult: repoFormatSelection,
            formatSelection: repoFormatSelection,
            dropdownCssClass: "bigdrop",
            escapeMarkup: function (m) { return m; }
        });
    }
    else
    {
        $(element).select2({
            // minimumInputLength: 1,
            width: 'resolve',
            allowClear: true,
            ajax: {
                url: url,
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
            },
            formatResult: repoFormatSelection,
            formatSelection: repoFormatSelection,
            dropdownCssClass: "bigdrop",
            escapeMarkup: function (m) { return m; }
        });
    }
}

//Select Ajax khách hàng
function selectpicker_jax_customer(_class = "", _url = "")
{
    var _data = {};
    if (typeof(csrfData) !== 'undefined') {
        _data[csrfData['token_name']] = csrfData['hash'];
    }
    _data['q'] = "{{{q}}}";
    var options = {
        ajax: {
            url: _url,
            type: "POST",
            dataType: "json",
            data: _data
        },
        locale: {
            emptyTitle: pls_option_select,
            statusInitialized: pls_option_select,
        },
        log: 3,
        preserveSelected: false,
        preprocessData: function(data) {
            var i,
                l = data.length,
                array = [];
            if (l) {
                for (i = 0; i < l; i++) {
                    array.push(
                        $.extend(true, data[i], {
                            text: data[i].name_system,
                            value: data[i].userid
                        })
                    );
                }
            }
            return array;
        }
    };
    if($(_class).length)
    {
        $(_class).selectpicker().filter(".with-ajax").ajaxSelectPicker(options);
    }
    else
    {
        $(".selectpicker.c_customer.with-ajax").selectpicker().filter(".with-ajax").ajaxSelectPicker(options);
    }
}

//Select Ajax đơn hàng
function selectpicker_jax_orders(_class = "", _url = "", datanew = {})
{
    ajaxSelector = $('body').find(_class);
    var options = {
        ajax: {
            url: _url,
            type: "POST",
            dataType: "json",
            data: function(){
                var _data = {};
                if (typeof(csrfData) !== 'undefined') {
                    _data[csrfData['token_name']] = csrfData['hash'];
                }
                _data['q'] = "{{{q}}}";
                $.each(datanew, function(i, v){
                    _data[i] = $(v).val();
                })
                return _data;

                console.log(_data);
            }
        },
        locale: {
            emptyTitle: pls_option_select,
            statusInitialized: pls_option_select,
        },
        log: 3,
        cache: false,
        preserveSelected: false,
        preprocessData: function(data) {
            var i,
                l = data.length,
                array = [];
            if (l) {
                for (i = 0; i < l; i++) {
                    array.push(
                        $.extend(true, data[i], {
                            text: data[i].full_code,
                            value: data[i].id
                        })
                    );
                }
            }
            return array;
        }
    };
    if($(_class).length)
    {
        $(_class).selectpicker().filter(".with-ajax").ajaxSelectPicker(options);
    }
    else
    {
        $(".selectpicker.orders.with-ajax").selectpicker().filter(".with-ajax").ajaxSelectPicker(options);
    }
}

//Select Ajax khách hàng
function selectpicker_jax_products(_class = "", _url = "", datanew = {})
{
    ajaxSelector = $('body').find(_class);
    var options = {
        ajax: {
            url: _url,
            type: "POST",
            dataType: "json",
            data: function(){
                var _data = {};
                if (typeof(csrfData) !== 'undefined') {
                    _data[csrfData['token_name']] = csrfData['hash'];
                }
                _data['q'] = "{{{q}}}";
                $.each(datanew, function(i, v){
                    _data[i] = $(v).val();
                })
                return _data;
            }
        },
        locale: {
            emptyTitle: pls_option_select,
            statusInitialized: pls_option_select,
        },
        log: 3,
        cache: false,
        preserveSelected: false,
        preprocessData: function(data) {
            var i,
                l = data.length,
                array = [];
            if (l) {
                for (i = 0; i < l; i++) {
                    array.push(
                        $.extend(true, data[i], {
                            text: data[i].full_code,
                            value: data[i].id
                        })
                    );
                }
            }
            return array;
        }
    };
    if($(_class).length)
    {
        $(_class).selectpicker().filter(".with-ajax").ajaxSelectPicker(options);
    }
    else
    {
        $(".selectpicker.orders.with-ajax").selectpicker().filter(".with-ajax").ajaxSelectPicker(options);
    }
    // ajaxSelector.data('AjaxBootstrapSelect').options.ajax.url = _url;
}


function ajaxSelect2ImgCallBack(element, url, id, types = '', __data = {})
{
    
    if (id)
    {
        var str = $(element).val();
        str = str.replace(",", "-");
        $(element).val(id).select2({
            // minimumInputLength: 1,
            multiple: true,
            width: 'resolve',
            allowClear: true,
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: url + '/' + str,
                    dataType: "json",
                    success: function (data) {
                        callback(data.results);
                    }
                });
            },
            ajax: {
                url: url,
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    var _data =  {
                        types: types,
                        term: term,
                        limit: 50
                    };
                    $.each(__data, function(i, v){
                        _data[i]  = $(v).val();
                    })
                    return _data;

                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            },
            formatResult: repoFormatSelection,
            formatSelection: repoFormatSelection,
            dropdownCssClass: "bigdrop",
            escapeMarkup: function (m) { return m; }
        });
    }
    else
    {
        $(element).select2({
            multiple: true,
            width: 'resolve',
            allowClear: true,
            ajax: {
                url: url,
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    var _data =  {
                        types: types,
                        term: term,
                        limit: 50
                    };
                    $.each(__data, function(i, v){
                        _data[i]  = $(v).val();
                    })
                    console.log(_data)
                    return _data;
                },
                results: function (data, page) {
                    if(data.results != null) {
                        return { results: data.results };
                    } else {
                        return { results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            },
            formatResult: repoFormatSelection,
            formatSelection: repoFormatSelection,
            dropdownCssClass: "bigdrop",
            escapeMarkup: function (m) { return m; }
        });
    }
}

function repoFormatSelection(state) {
    if (!state.id) return state.text;
    if(state.img)
    {
        var img = '<img class="img_option" src="'+site_url +state.img+'"/> ';
    }
    else
    {
        var img = '<img class="img_option" src="'+site_url +'download/preview_image"/> ';
    }
    var Stringreturn = img + state.text;
    if(state.price)
    {
        Stringreturn += ' - '+ C_formatNumber(state.price)
    }

    return  Stringreturn ;
}

function repoFormatIcon(state) {
    if (!state.id) return state.text;
    var Stringreturn = '➪';
    for(var i = 0; i <= state.num_parent; i++)
    {
        Stringreturn += '➪';
    }
    Stringreturn += state.text;
    if(state.price)
    {
        Stringreturn += ' - '+ C_formatNumber(state.price)
    }

    return  Stringreturn ;
}


function ViewDetailCare_of(id = "", _this)
{
    var button = $(_this);
    button.button({loadingText: 'Please_wait...'});
    button.button('loading');
    var data = {};
    if (typeof (csrfData) !== 'undefined') {
        data[csrfData['token_name']] = csrfData['hash'];
    }
    if($.isNumeric(id)) {
        data['id'] = id;
    }
    $.post(admin_url+'care_of_clients/getModalDetail', data, function(data){
        data = JSON.parse(data);
        $('#modal_detail_care_of_clients').html(data.data);
        $('#modal_detail_care_of_clients').modal('show');
    }).always(function() {
        button.button('reset');
    });
}

function ajaxSelectCallBack(element, url, id, types = '', selection = true)
{
    if (id > 0)
    {
        var ActionOptionSelect = {
            // minimumInputLength: 1,
            width: 'resolve',
                allowClear: true,
            initSelection: function (element, callback) {
            $.ajax({
                type: "get", async: false,
                url: url + '/' + $(element).val(),
                dataType: "json",
                success: function (data) {
                    callback(data.results);
                }
            });
        },
            ajax: {
                url: url,
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
            },
            formatResult: repoFormatSelection,
            formatSelection: repoFormatSelection,
            dropdownCssClass: "bigdrop",
            escapeMarkup: function (m) { return m; }
        };
        if(selection == true)
        {
            ActionOptionSelect['formatSelection'] =  repoFormatSelection;
        }
        $(element).val(id).select2(ActionOptionSelect);
    }
    else
    {
        var ActionOptionSelect = {
            // minimumInputLength: 1,
            width: 'resolve',
            allowClear: true,
            ajax: {
                url: url,
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
            },
            formatResult: repoFormatSelection,
            // formatSelection: repoFormatSelection,
            dropdownCssClass: "bigdrop",
            escapeMarkup: function (m) { return m; }
        };
        if(selection == true)
        {
            ActionOptionSelect['formatSelection'] =  repoFormatSelection;
        }
        $(element).select2(ActionOptionSelect);
    }
}


function ajaxSelectParent(element, url, id, types = '') {
    if (id != "")
    {
        var DataSelect = {
            width: 'resolve',
            allowClear: true,
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: url + '/' + $(element).val(),
                    dataType: "json",
                    success: function (data)
                    {
                        console.log(data.results)
                        callback(data.results);
                    }
                });
            },
            ajax: {
                url: url,
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
            },
            formatResult: repoFormatIcon,
            formatSelection: repoFormatIcon,
            dropdownCssClass: "bigdrop",
            escapeMarkup: function (m) { return m; }
        };



        $(element).val(id).select2(DataSelect);
    }
    else
    {
        var DataSelect = {
            width: 'resolve',
            allowClear: true,
            ajax: {
                url: url,
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
            },
            formatResult: repoFormatIcon,
            formatSelection: repoFormatIcon,
            dropdownCssClass: "bigdrop",
            escapeMarkup: function (m) { return m; }
        };


        $(element).select2(DataSelect);
    }
}


function ajaxSelectNotImg(element, url, id, types = '') {
    if (id != "")
    {
        var DataSelect = {
            width: 'resolve',
            allowClear: true,
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: url + '/' + $(element).val(),
                    dataType: "json",
                    success: function (data)
                    {
                        console.log(data.results)
                        callback(data.results);
                    }
                });
            },
            ajax: {
                url: url,
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
            },
            escapeMarkup: function (m) { return m; }
        };



        $(element).val(id).select2(DataSelect);
    }
    else
    {
        var DataSelect = {
            width: 'resolve',
            allowClear: true,
            ajax: {
                url: url,
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
            },
            escapeMarkup: function (m) { return m; }
        };


        $(element).select2(DataSelect);
    }
}

function ajaxSelectNotImgSelect2(element, url, id, types = '', DataAdd = {}) {
    if (id != "")
    {
        var dataPost = id;
        var DataSelect = {
            width: 'resolve',
            allowClear: true,
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: url,
                    dataType: "json",
                    data : {id: id},
                    success: function (data)
                    {
                        callback(data.results);
                    }
                });
            },
            ajax: {
                url: url,
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
            },
            escapeMarkup: function (m) { return m; }
        };

        $.each(DataAdd, function(i, v){
            DataSelect[i] = v;
        })

        $(element).val(id).select2(DataSelect);
    }
    else
    {
        var DataSelect = {
            width: 'resolve',
            allowClear: true,
            ajax: {
                url: url,
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
            },
            escapeMarkup: function (m) { return m; }
        };

        $.each(DataAdd, function(i, v){
            DataSelect[i] = v;
        })

        $(element).select2(DataSelect);
    }
}




//Trong giai đoạn demo
$('body').on('click', '.editDataTable', function(e){
    var type = $(this).attr('data-type');
    var _td = $(this).parents('td');
    _td.find('.lableScript').addClass('hide');
    _td.find('.inputScript').removeClass('hide');
    if(type == 'select' || type == 'select_search')
    {
        var url = $(this).attr('data-href');
        var inputSelect =  _td.find('.inputScript').find('.ChangeDataTable');
        if(url)
        {
            if(inputSelect.hasClass('multiple'))
            {
                ajaxSelectNotImgSelect2(inputSelect, url, inputSelect.attr('data-hidden'),'', {'multiple': true});
            }
            else
            {
                ajaxSelectNotImg(inputSelect, url, inputSelect.attr('data-hidden'));
            }
        }
        else
        {
            $(inputSelect).select2();
        }
    }
    init_datepicker();
    appValidateForm(_td.find('.formUpdateDataTable'), {}, manage_Udpdatecolum);
})

$('body').on('click', '.closeEditData', function(e){
    var _td = $(this).parents('td');
    _td.find('.lableScript').removeClass('hide');
    _td.find('.inputScript').addClass('hide');
    var inputDataChange = _td.find('input.ChangeDataTable');
    var valueBefore = inputDataChange.attr('data-hidden');
    inputDataChange.val(valueBefore);
})

function manage_Udpdatecolum(form)
{
    var TableData = $(form).parents('.dataTables_wrapper');
    var button = $(form).find('button[type="submit"]');
    button.button({loadingText: 'Please wait'});
    button.button('loading');
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function (response) {
        response = JSON.parse(response);
        if (response.success == true) {
            TableData.find('.btn-dt-reload').click();
        }
        alert_float(response.alert_type, response.message);
    }).always(function () {
        button.button('reset')
    });
    return false;
}


function moved_orders_primary(id = '')
{
    if(confirm(app.lang.confirm_action_prompt))
    {
        var data = {id : id};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        $.post(admin_url+'orders/moved_orders_primary', data, function (result) {
            result = JSON.parse(result);
            if(result.success)
            {
                if($('#id_facebook').val())
                {
                    varInfoUser($('#id_facebook').val())
                }
                if($('.btn-dt-reload'))
                {
                    $('.btn-dt-reload').click();
                }
            }

            alert_float(result.alert_type,  result.message);
            // $('.dataTable').DataTable().ajax.reload();
        })
    }
}

