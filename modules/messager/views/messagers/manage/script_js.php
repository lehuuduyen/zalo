<?php
    init_tail();
    $VersionAppFB   =   get_option('VersionAppFB');
    $IdAppFB        =   get_option('IdAppFB');
    $basePath       =   module_dir_url('messager', 'uploads/');
    $baseAssets     =   module_dir_url('messager', 'assets/');
    $C_TagFB = getFullDataTag();
?>




<script src="https://js.pusher.com/4.4/pusher.min.js"></script>
<script src="<?= $baseAssets.'messager_fb/js/jquery.cookie.js';?>"></script>

<script src="<?= $baseAssets.'messager_fb/js/daterangepicker/daterangepicker.min.js';?>"></script>


<script>
    var text_lableTag = "<?=!empty(_l('cong_infomation_tag')) ? _l('cong_infomation_tag') : ''?>";
    var admin_url = "<?=admin_url()?>";
    var VersionAppFB = "<?=$VersionAppFB?>";
    var basePath = "<?=$basePath?>";
    var C_available_tags = <?= json_encode($C_TagFB['name']) ?>;
    var C_available_tags_color = <?= json_encode($C_TagFB['color']) ?>;
    var C_available_tags_background_color = <?= json_encode($C_TagFB['background_color']) ?>;
    var C_available_tags_ids = <?= json_encode($C_TagFB['id']) ?>;
    var please_wait = '<?=_l('cong_please_wait')?>';
    var cong_you_must_delete = '<?=_l('cong_you_must_delete')?>';
    var getDay = '<?=_d(date('Y-m-d'))?>';
    var cong_t_search_time = '<?=_l('cong_t_search_time')?>';
    var base_url = '<?=base_url()?>';
    var lang_more = '<?=_l('more')?>';
    var lang_hide_detail = '<?=_l('cong_hide_detail')?>';
    var lang_price = '<?=_l('cong_price_buy')?>';
    var result_find = '<?=_l('cong_result_find')?>';
    var lang_search = '<?=_l('search')?>';
    var lang_close = '<?=_l('close')?>';
    var lang_cong_end_right = '<?=_l('cong_end_right')?>';
    var lang_unread = '<?=_l('cong_check_unread')?>';
    var lang_read = '<?=_l('cong_check_read')?>';


    //Pusher
    Pusher.logToConsole = true;
    var pusher = new Pusher('3ffdad22ae304306f311', {
        cluster: 'eu',
        forceTLS: true
    });

    var channel = pusher.subscribe($.cookie('page_active'));
</script>

<script src="<?= $baseAssets.'messager_fb/js/main.js';?>"></script>

<script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=<?=$VersionAppFB?>&appId=<?=$IdAppFB?>&autoLogAppEvents=1"></script>
<script>
    var leadUniqueValidationFields = ["email"];
    
    var csrfData = <?php echo json_encode(get_csrf_for_ajax()); ?>;

    $(document).ready(function() {
        $.ajaxSetup({ cache: true });
    });

    $(function(e){
        $("#taghidden").select2({
            formatResult: formatTag,
            formatSelection: formatTag,
            escapeMarkup: function(m) { return m; }
        });


        ajaxSelectCallBack('#product_hidden', "<?=admin_url('messager/SearchProductItems')?>", '', '');

        //ajaxSelectCallBack('#product_suggest', "<?//=admin_url('messager/SearchProductSuggest')?>///"+$('.id_object').val(), '', '');
        ajaxSelectCallBack('.product_suggest', "<?=admin_url('messager/SearchProductSuggest')?>/"+$('.id_object').val(), '', '');

        //ajaxSelectParent('#reply_hidden', "<?//=admin_url('messager/SearchReply')?>//", '', '');
        $('#reply_hidden').select2();

    })

    $('body').on('keypress','#replyMessager',function(event){
        if(event.keyCode == 13)
        {
            if(!event.shiftKey){
                var replyMessager = $(this).val();
                $('#replyMessager').val("");
                var id_user = $(this).attr('id_user');
                if($.trim(replyMessager) != "" && id_user != "")
                {
                    replyMessager =$.trim(replyMessager);

                    var date = new Date();
                    var id_message = $('.content-profile.active').attr('id_senders');
                    $.post('https://graph.facebook.com/<?=$VersionAppFB?>/me/messages',{access_token:$.cookie('access_token_page_active'), recipient:{"id": id_user}, message:{"text": replyMessager}},function(response){
                        if(response.error)
                        {
                            $('#replyMessager').val("");
                            addMy_Send(replyMessager, date, "", false, id_message, true, 'last', id_message);
                        }
                        else
                        {
                            $('#replyMessager').val("");
                            addMy_Send(replyMessager, date, response.message_id, true, 'last', id_message);
                        }
                    })
                }

                if(id_user != "" && $('#all_file_send').find('.file_send').length > 0)
                {
                    var FileSend =  $('#all_file_send').find('.file_send');
                    $.each(FileSend, function(i, v){
                        var url = $(v).attr('url');
                        $.post('https://graph.facebook.com/<?=$VersionAppFB?>/me/messages',
                            {
                                access_token:$.cookie('access_token_page_active'),
                                recipient:{"id": id_user},
                                message:{
                                    "attachment": {
                                        'type' : 'image',
                                        'payload' : {'url' : url, 'is_reusable':true},
                                    }
                                }
                            },function(response){
                                $(v).find('.close_file').click();
                            }).fail(function() {
                                alert_float('danger', '<?=_l('cong_err_send_img')?>');
                        });
                    })
                }
            }
        }
    });

    $(window).bind("load", function() {
        $(document).ready(function() {
            GetMessager();
        })
    })

    $('body').on('select2-close', '#taghidden', function(e){
        $('.div_tag_hidden').find('.selectHidden').addClass('hide');
    })


    $('body').on('click', '.EventTagHidden', function(e){
        $('.div_tag_hidden').find('.selectHidden').removeClass('hide');
        $('#taghidden').select2('open');
    })


    $('body').on('change','#taghidden', function(e){
        var id = $(this).val();
        var tag = $('#tag').val();
        tag.push(id);
        $('#tag').val(tag).select2({
            formatResult: formatTag,
            formatSelection: formatTag,
            escapeMarkup: function(m) { return m; }
        }).trigger('change');
    })

    $('body').on('select2-close', '#product_hidden', function(e){
        $('.selectProduct').addClass('hide');
    })

    $('body').on('select2-close', '.product_suggest', function(e){
        $('.selectSuggest').addClass('hide');
    })


    $('body').on('select2-close', '#reply_hidden', function(e){
        $('.selectReply').addClass('hide');
    })


    $('body').on('click', '.actionSelectProductHidden', function(e){
        $('.selectProduct').removeClass('hide');
        $('#product_hidden').select2('open');
    })

    $('body').on('click', '.actionSelectReply', function(e){
        $('.selectReply').removeClass('hide');
        $('#reply_hidden').select2('open');
    })

    $('body').on('click', 'label[for="tag"]', function(e){
        $('#tag').select2('open');
    })

    $('body').on('change', '#product_hidden', function(e){
        var product_selected = e.added;
        console.log(product_selected.text)
        var id = $(this).val();
        var name = $(this).find('option:selected').text();

        var data = {};
        if (typeof (csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        data['id'] = id;

        var stringProduct = product_selected.name_customer + ' - ' + lang_price + ' ' + C_formatNumber(product_selected.price);
        AddImgSendProduct(site_url + product_selected.img);
        var reply = $('#replyMessager').val();
        $('#replyMessager').val(reply + stringProduct)
    })

    $('body').on('change', '.product_suggest', function(e){
        var product_selected = e.added;
        var id = $(this).val();
        var name = $(this).find('option:selected').text();

        var data = {};
        if (typeof (csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        data['id'] = id;

        var stringProduct = product_selected.name_customer + ' - ' + lang_price + ' ' + C_formatNumber(product_selected.price);
        AddImgSendProduct(site_url + product_selected.img);
        var reply = $('#replyMessager').val();
        $('#replyMessager').val(reply + stringProduct)
    })

    $('body').on('change', '#reply_hidden', function(e){
        var id = $(this).val();
        var name = $(this).find('option:selected').text();

        var data = {};
        if (typeof (csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        data['id'] = id;

        $.post(admin_url+'messager/GetReply', data, function(data){
            var reply = $('#replyMessager').val();
            $('#replyMessager').val(reply + data)
        })
    })

    $('body').on('click', '.createTag', function(e){
        var id = $(this).attr('id-tag');
        var tag = $('#tag').val();
        tag.push(id);
        $('#tag').val(tag).select2({
            formatResult: formatTag,
            formatSelection: formatTag,
            escapeMarkup: function(m) { return m; }
        }).trigger('change');
    })

    $('body').on('click', '.SaveErience', function(e){
        var button = $(this);
        button.button({loadingText: 'please wait...'});
        button.button('loading');
        var select = $(this).parents('.popover-content').find('select.SelectErience');
        var id = $(select).attr('id-data');
        var id_detail = $(select).attr('id-detail');
        var name = $(select).attr('name');
        var value = $(select).val();
        var data = {
            id : id,
            [name] : value,
            id_detail : id_detail
        };
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        $.post(admin_url+'advisory_lead/ChangeErience', data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                var id_facebook = $('#id_facebook').val();
                varInfoUser(id_facebook);
            }
            alert_float(data.alert_type, data.message);
        }).always(function() {
            button.button('reset')
        });
    })

    $('body').on('click', '.close_popover', function(e){
        var btn =  $(this);
        var popover = btn.parents('.popover').attr('id');
        console.log(popover);
        $('body').find('.PopverSelect2[aria-describedby="'+popover+'"]').click();
    })

    function BreakAdvisory(id = "", status, _this)
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
        $.post(admin_url+'advisory_lead/break_advisory', data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                var id_facebook = $('#id_facebook').val();
                varInfoUser(id_facebook);
            }
            alert_float(data.alert_type, data.message);
        }).always(function() {
            button.button('reset')
        });
    }

    $('body').on('click', '.SaveErienceCare', function(e){
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
                var id_facebook = $('#id_facebook').val();
                varInfoUser(id_facebook);
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
                    var id_facebook = $('#id_facebook').val();
                    varInfoUser(id_facebook);
                }
                alert_float(data.alert_type, data.message);
            }
        }).always(function() {
            button.button('reset')
        });
    })

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
                var id_facebook = $('#id_facebook').val();
                varInfoUser(id_facebook);
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
                var id_facebook = $('#id_facebook').val();
                varInfoUser(id_facebook);
            }
            alert_float(data.alert_type, data.message);
        })
    })

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
                var id_facebook = $('#id_facebook').val();
                varInfoUser(id_facebook);
            }
            alert_float(data.alert_type, data.message);
        }).always(function() {
            button.button('reset');
        });
    }
</script>
