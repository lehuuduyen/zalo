<script>

    var filterList = {
        'datestart' : '[name="date_start"]',
        'dateend' : '[name="date_end"]',
        'procedure' : '[name="procedure"]',
        'name_client' : '[name="name_client"]',
        'code_care_of' : '[name="code_care_of"]',
        'code_client' : '[name="code_client"]',
        'vip_code' : '[name="vip_code"]',
        'vip_rating_lead' : '[name="vip_rating_lead"]'
    };

    var TableCare_of = initDataTableCustom('.table-care_of_clients', admin_url+'care_of_clients/table', [0], [0], filterList, [0, 'desc'], fixedColumns = {leftColumns: 3, rightColumns: 0});


    $('.table-care_of_clients').on('draw.dt', function() {
        var invoiceReportsTable = $(this).DataTable();
        var data = invoiceReportsTable.ajax.json().count_detail;
        $('td').find('.progressbar').find('li').css('width',100/data+'%');
        $('.progressbar').parent('td').css('white-space', 'inherit');
    })
    $.each(filterList, function(i, filter){
        $(filter).on('change', function(e){
            if($('.table-care_of_clients').hasClass('dataTable')) {
                TableCare_of.DataTable().ajax.reload();
            }
        })
    })

    function editCare_of_clients(id = "", _this)
    {
        var button = $(_this);
        button.button({loadingText: '<?=_l('cong_please_wait')?>'});
        button.button('loading');
        var data = {};
        if (typeof (csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        if($.isNumeric(id)) {
            data['id'] = id;
        }
        $.post(admin_url+'care_of_clients/getModal', data, function(data){
            $('#modal_care_of_clients').html(data);
            $('#modal_care_of_clients').modal('show');
        }).always(function() {
            button.button('reset')
        });
    }

    function deleteCare_of(id = "", _this) {
        if($.isNumeric(id))
        {
            if(confirm("<?=_l('cong_you_must_delete')?>"))
            {
                var button = $(_this);
                button.button({loadingText: '<?=_l('cong_please_wait')?>'});
                button.button('loading');
                var data = {};
                if (typeof (csrfData) !== 'undefined') {
                    data[csrfData['token_name']] = csrfData['hash'];
                }
                data['id'] = id;
                $.post(admin_url+'care_of_clients/delete_care_of_clients', data, function(data){
                    data = JSON.parse(data);
                    alert_float(data.alert_type, data.message);
                    if(data.success) {
                        TableCare_of.ajax.reload();
                    }
                }).always(function() {
                    button.button('reset')
                });
            }
        }
    }

    $('body').on('click', '.update_status_care_of', function(e){
        var id_assigned  = $(this).attr('id-data');
        var status_procedure  = $(this).attr('status-procedure');
        var button = $(this);
        button.button({loadingText: '<?=_l('cong_please_wait')?>'});
        button.button('loading');
        var data = {};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        data['id'] = id_assigned;
        data['status_procedure'] = status_procedure;
        $.post(admin_url+'care_of_clients/update_status/', data, function(data){
            data = JSON.parse(data);
            if(data.success) {
                TableCare_of.ajax.reload();
            }
            alert_float(data.alert_type, data.message);
        }).always(function() {
            button.button('reset')
        });
    })


    function restore_Care_of_clients(id = "", _this)
    {
        var button = $(_this);
        button.button({loadingText: '<?=_l('cong_please_wait')?>'});
        button.button('loading');
        var data = {};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        data['id'] = id;
        $.post(admin_url+'care_of_clients/restore_care_of_clients', data, function(data){
            data = JSON.parse(data);
            if(data.success) {
                TableCare_of.ajax.reload();
            }
            alert_float(data.alert_type, data.message);
        }).always(function() {
            button.button('reset')
        });
    }
    function BreakCare_of(id = "", status, _this)
    {
        var button = $(_this);
        button.button({loadingText: '<?=_l('cong_please_wait')?>'});
        button.button('loading');
        var data = {};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }

        data['id'] = id;
        data['status'] = status;
        $.post(admin_url+'care_of_clients/break_care_of', data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                TableCare_of.ajax.reload();
            }
            alert_float(data.alert_type, data.message);
        }).always(function() {
            button.button('reset');
        });
    }

</script>
<script>
    $(document).on('click', '.btn-filter',function(e){
        if(!$(this).hasClass('filter_all')) {
            var id_procedure = $(this).attr('id_data');
            $('input[name="procedure"]').val(id_procedure);
        }
        else {
            $('input[name="procedure"]').val('');
        }
        TableCare_of.ajax.reload();
        $('.btn-filter').removeClass('active');
        $(this).addClass('active');
    })
    function care_of_change_priority(priority, id)
    {
        var data = {};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        data['priority'] = priority;
        data['id'] = id;
        $.post(admin_url+'care_of_clients/change_priority', data).done(function (data) {
            data = JSON.parse(data);
            if(data.success)
            {
                TableCare_of.ajax.reload();
            }
            alert_float(data.alert_type, data.message);
        }).fail(function () {
            alert_float('danger', 'err');
        }).always(function () {

        })
    }
</script>


<script type="text/javascript">
    $('body').on('click', '.SaveErience', function(e){
        var button = $(this);
        button.button({loadingText: '<?=_l('cong_please_wait')?>'});
        button.button('loading');
        var SelectErience = $(this).parents('.popover-content').find('select.SelectErience');
        if(SelectErience.length == 0)
        {
            var SelectErience = $(this).parents('.popover-content').find('input.SelectErience');
        }
        var id = $(SelectErience).attr('id-data');
        var id_detail = $(SelectErience).attr('id-detail');
        var name = $(SelectErience).attr('name');
        var value = $(SelectErience).val();
        var id_care_items = $(SelectErience).attr('id_care_items');
        var data = {
            id : id,
            [name] : value,
            id_care_items : id_care_items,
            id_detail : id_detail
        };


        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        $.post(admin_url+'care_of_clients/ChangeErience', data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                TableCare_of.ajax.reload();
            }
            alert_float(data.alert_type, data.message);
        }).always(function() {
            button.button('reset')
        });
    })

    $('body').on('click', '.SaveFileErience', function(e){
        var button = $(this);
        button.button({loadingText: '<?=_l('cong_please_wait')?>'});
        button.button('loading');
        var SelectErience = $(this).parents('.popover-content').find('.FileErience');
        var id = $(SelectErience).attr('id-data');
        var name = $(SelectErience).attr('name');
        var id_care_items = $(SelectErience).attr('id_care_items');
        var value = $(SelectErience).val();
        var data = {id : id, [name] : value, id_care_items : id_care_items};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }

        var form = $(this).parents('.popover-content').find('#form_img_care_of');
        var file_data = $('input#file_erience').prop('files');
        var form_data = new FormData();
        $.each(file_data, function(i, v){
            form_data.append('file[]', v);
        })
        form_data.append('csrf_token_name', csrfData.hash);
        $.ajax({
            url: form.attr('action'),
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (data) {
                alert_float(data.alert_type, data.message);
                if(data.success)
                {
                    TableCare_of.ajax.reload();
                }
                alert_float(data.alert_type, data.message);
            }
        }).always(function() {
            button.button('reset')
        });
    })

    $('body').on('click', '.close_popover', function(e){
       var btn =  $(this);
       var popover = btn.parents('.popover').attr('id');
       btn.parents('.popover').parents('td').find('.PopverSelect2[aria-describedby="'+popover+'"]').click();
    })

    $('body').on('shown.bs.popover', '.PopverSelect2', function(e){
        var id = $(this).attr('aria-describedby');
        if($('#'+id).find("select.SelectErience").length)
        {
            $('#'+id).find("select.SelectErience").select2({
                escapeMarkup: function(m) { return m; }
            });
        }
        init_datepicker();
    })

    $('body').on('keyup', 'input.SelectErience',function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            $(this).parents('.popover-content').find('.SaveErience').trigger('click');
        }
    });

    $('body').on('click', '.removeImg', function(e){
         var data = {};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        var url = $(this).attr('url');
        var id_img = $(this).attr('id_img');
        data["url"] = url;
        data["id_img"] = id_img;
        $.post(admin_url+'care_of_clients/removeImg', data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                TableCare_of.ajax.reload();
            }
            alert_float(data.alert_type, data.message);
        })

    })
    
    $('body').on('click', '.solution', function(e){
         var data = {};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        var solution = $(this).attr('status-table');
        var id_data = $(this).attr('id-data');
        data["solution"] = solution;
        data["id"] = id_data;
        $.post(admin_url+'care_of_clients/UpdateSolution', data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                TableCare_of.ajax.reload();
            }
            alert_float(data.alert_type, data.message);
        })

    })
</script>