$(function(e){
    //Tagmual đang có cho search
    AddSearchTagMual();
})

$('#time_chat').daterangepicker({
    autoUpdateInput: true,
    locale: {
        cancelLabel: 'Clear',
        "format": "DD/MM/YYYY",
        "separator": " - ",
        "applyLabel": lang_daterangepicker.applyLabel,
        "cancelLabel": lang_daterangepicker.clearLabel,
        "fromLabel": lang_daterangepicker.fromLabel,
        "toLabel": lang_daterangepicker.toLabel,
        "customRangeLabel": lang_daterangepicker.customRangeLabel,
        "daysOfWeek": lang_daterangepicker.daysOfWeek,
        "monthNames": lang_daterangepicker.monthNames
    }
});


$('body').on('change', '#tag', function(e){
    var form = $(this).parents('form');
    var dataTag = $(this).val();
    if(form.length > 0)
    {
        ActionChangeTag(dataTag, form);
    }
})

//Chang thẻ tag
function ActionChangeTag(InVal, form ,type =0)
{
    var id_facebook = $('#id_facebook').val();
    addTagProfile(InVal, id_facebook);
    if(form.hasClass('form_customer'))
    {
        if($('input[name="userid"]').val() != "")
        {
            var id = $('input[name="userid"]').val();
            var data = {};
            if (typeof (csrfData) !== 'undefined') {
                data[csrfData['token_name']] = csrfData['hash'];
            }
            data['tag'] = InVal;
            data['rel_type'] = 'client';
            $.post(admin_url+'messager/updateDataTag/'+id, data, function(res){
                console.log(res);
            })
        }
    }
    else if(form.hasClass('form_lead'))
    {
        if($('input[name="id"]').val() != "")
        {
            var id = $('input[name="id"]').val();
            var data = {};
            if (typeof (csrfData) !== 'undefined') {
                data[csrfData['token_name']] = csrfData['hash'];
            }
            data['tag'] = InVal;
            data['rel_type'] = 'lead';
            $.post(admin_url+'messager/updateDataTag/'+id, data, function(res){
                console.log(res);
            })
        }
    }
    else if(form.hasClass('form_listfb'))
    {
        var id = $('input[name="id"]').val();
        var data = {};
        if (typeof (csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        data['tag'] = InVal;
        data['rel_type'] = 'listfb';
        $.post(admin_url+'messager/updateDataTag/'+id, data, function(res){
            console.log(res);
        })
    }
}

//Add giao diện cho profile
function addTagProfile(InVal, id_facebook)
{
    $('.content-profile[id_user="'+id_facebook+'"]').find('.tag_left').find('span').remove();
    $.each(InVal, function(iV, vV){
        var color = C_available_tags_color[vV];
        var background_color = C_available_tags_background_color[vV];
        var span_profile = $('<span class="label label-default inline-block pointer mtop5 tag-lef-'+vV+'" id="tag-lef-'+id_facebook+'-'+vV+'"></span>');
        if(background_color != "")
        {
            span_profile.append('<i class="fa fa-circle" style="color:'+background_color+'" aria-hidden="true"></i> ');
        }
        else
        {
            span_profile.append('<i class="fa fa-circle" aria-hidden="true"></i> ');
        }

        if($('.content-profile[id_user="'+id_facebook+'"]').find('.tag_left').find('#tag-lef-'+id_facebook+'-'+vV).length == 0)
        {
            var name = C_available_tags[vV];
            span_profile.append(name);
            $('.content-profile[id_user="'+id_facebook+'"]').find('.tag_left').prepend(span_profile);
        }
    })

}

//Chang phân quyền
$('body').on('change', '#browsers_staff_assigned', function(e){
    var id_staff = $(this).val();
    var id_facebook = $('#id_facebook').val();
    var form = $('#customer-info');
    if(form.attr('type') == 'client')
    {
        if(form.find('input[name="userid"]').val())
        {
            var userid = $('input.id_object').val();
            var data = {};
            data['userid'] = userid;
            data['id_staff'] = id_staff;
            if (typeof(csrfData) !== 'undefined') {
                data[csrfData['token_name']] = csrfData['hash'];
            }
            $.post(admin_url+'messager/staff_assigned_client', data, function(data){
                data = JSON.parse(data);
                alert_float(data.alert_type, data.message);
                varInfoUser(id_facebook)
            })
        }
    }
    else if(form.attr('type') == 'lead')
    {
        if(form.find('input[name="id"]').val())
        {
            var id = $('input.id_object').val();
            var data = {};
            data['id'] = id;
            data['id_staff'] = id_staff;
            if (typeof(csrfData) !== 'undefined') {
                data[csrfData['token_name']] = csrfData['hash'];
            }
            $.post(admin_url+'messager/staff_assigned_lead', data, function(data){
                data = JSON.parse(data);
                alert_float(data.alert_type, data.message);
                varInfoUser(id_facebook)
            })
        }
    }
    else if(form.attr('type') == 'listfb')
    {
        if(form.find('input[name="id"]').val())
        {
            var id = $('input.id_object').val();
            var data = {};
            data['id'] = id;
            data['id_staff'] = id_staff;
            if (typeof(csrfData) !== 'undefined') {
                data[csrfData['token_name']] = csrfData['hash'];
            }
            $.post(admin_url+'messager/staff_assigned_listfb', data, function(data){
                data = JSON.parse(data);
                alert_float(data.alert_type, data.message);
                varInfoUser(id_facebook)
            })
        }
    }
})

//Button thêm khách hàng hoặc khách hàng tìm năng
$(document).on('click', '.btn_add_data', function(e){

    var button = $(this);
    button.button({loadingText: "<i class='fa fa-spinner fa-spin'></i>"});
    button.button('loading');
    var id_facebook = $('.content-profile.active').attr('id_user');
    var type = $(this).attr('id-data');
    var data = {};
    data['id_facebook'] = id_facebook;
    data['type'] = type;
    if (typeof(csrfData) !== 'undefined') {
        data[csrfData['token_name']] = csrfData['hash'];
    }
    $.post(admin_url+'messager/load_new_client', data, function(data){
        data = JSON.parse(data);
        if(data.data)
        {
            $('#content_customer').html(data.data);
            var content_profile = $('.content-profile.active');
            var src_img = content_profile.find('.img-info').find('img').attr('src');
            $('#img-customer').find('img').attr('src', src_img);

            var name = content_profile.find('.name-profile').text();
            $('#name-customer-right').text(name);
            $('#name-customer-right').parent().find('input[name="company"]').val(name);
            button.button('reset');
        }
    }).always(function() {
        button.button('reset')
    });
})

//lấy thông tin khách hoặc khách hàng tiềm năng có facebook
function varInfoUser(id_facebook) {
    //hoàng crm bổ xung
    if($('.chat-area-content').find('.js-check-empty').length > 0) {
        $('.chat-area-content').html('');
    }
    //end
    var data = {id_facebook : id_facebook};
    var profile = $('.content-profile[id_user="'+id_facebook+'"]');
    var name = profile.find('.name-profile').html();
    if(name.length > 0)
    {
        data['name'] = name;
    }

    var from = $('#form_action_client');


    var arrayCareOfShow = [];
    var Care_of_Detail = $('.jsCare_of.collapse.in');
    $.each(Care_of_Detail, function(idetail, vdetail){
        arrayCareOfShow.push($(vdetail).attr('id'));
    })

    var arrayOrderShow = [];
    var collapseOrder = $('.collapseOrder.collapse.in');
    $.each(collapseOrder, function(idetail, vdetail){
        arrayOrderShow.push($(vdetail).attr('id-data'));
    })

    var arrayAdvisoryShow = [];
    var collapseAdvisory = $('.js-more.collapse.in');
    $.each(collapseAdvisory, function(idetail, vdetail){
        arrayAdvisoryShow.push($(vdetail).attr('id_data'));
    })

    if (typeof(csrfData) !== 'undefined') {
        data[csrfData['token_name']] = csrfData['hash'];
    }

    var scrollOrder = $('.order').scrollTop();

    $.post(admin_url+'messager/get_lead_to_facebook', data, function(data){
        data = JSON.parse(data);
        if(data.data)
        {
            if(data.type_data)
            {
                if(data.data)
                {
                    $('#content_customer').html(data.data);
                    $('.countOrder').html(intVal(data.countOrder));
                    $('.countAdvisory').html(intVal(data.countAdvisory));
                    $('.countCareof').html(intVal(data.countCareof));
                    if(data.one_type && data.two_type)
                    {
                        $('.event_create_advisory').attr("onclick", "CreateAdvisory("+data.one_type+",'"+data.two_type+"')");
                        $('.event_create_orders').attr("onclick", "CreateOrders("+data.one_type+",'"+data.two_type+"')");
                        $('.event_create_orders_draft').attr("onclick", "CreateOrdersDraft("+data.one_type+",'"+data.two_type+"')");
                        if(data.two_type == 'client')
                        {
                            $('.event_create_advisory_client').attr("onclick", "CreateCareOfClient("+data.one_type+",'"+data.two_type+"')");
                            $('.event_create_advisory_client').parents('.wap-action').removeClass('hide');
                        }
                        else
                        {
                            $('.event_create_advisory_client').removeAttr('onclick');
                            $('.event_create_advisory_client').parents('.wap-action').addClass('hide');
                        }
                    }
                    else
                    {
                        $('.event_create_advisory').removeAttr('onclick');
                        $('.event_create_orders').removeAttr('onclick');

                        $('.event_create_advisory_client').removeAttr('onclick');
                        $('.event_create_advisory_client').parents('.wap-action').addClass('hide');

                    }
                    if(data.advisory) {
                        $('.emtry-advisory').html(data.advisory);
                    }
                    else
                    {
                        $('.emtry-advisory').html("");
                    }

                    if(data.care_of) {
                        $('.emtry-careof').html(data.care_of);
                    }
                    else
                    {
                        $('.emtry-careof').html("");
                    }

                    if(data.orders) {
                        $('#list_order').html(data.orders);
                    }
                    else
                    {
                        $('#list_order').html("");
                    }
                    AddTagMuals(id_facebook, data.tag_manuals);
                }
            }
            else
            {
                if(from.find('input[name="userid"]').val() || from.find('input[name="id"]').val() || $('#form_action_client').length == 0)
                {
                    $('.countOrder').html(intVal(data.countOrder));
                    $('.countCareof').html(intVal(data.countCareof));
                    $('.countAdvisory').html(intVal(data.countAdvisory));

                    if(data.one_type && data.two_type)
                    {
                        $('.event_create_advisory').attr("onclick", "CreateAdvisory("+data.one_type+",'"+data.two_type+"')");
                        $('.event_create_orders').attr("onclick", "CreateOrders("+data.one_type+",'"+data.two_type+"')");
                        $('.event_create_orders_draft').attr("onclick", "CreateOrdersDraft("+data.one_type+",'"+data.two_type+"')");
                        if(data.two_type == 'client')
                        {
                            $('.event_create_advisory_client').attr("onclick", "CreateCareOfClient("+data.one_type+",'"+data.two_type+"')");
                            $('.event_create_advisory_client').parents('.wap-action').removeClass('hide');
                        }
                        else
                        {
                            $('.event_create_advisory_client').removeAttr('onclick');
                            $('.event_create_advisory_client').parents('.wap-action').addClass('hide');
                        }
                    }
                    else
                    {
                        $('.event_create_advisory').removeAttr('onclick');
                        $('.event_create_orders').removeAttr('onclick');

                        $('.event_create_advisory_client').removeAttr('onclick');
                        $('.event_create_advisory_client').parents('.wap-action').addClass('hide');
                    }

                    $('.emtry-advisory').html("");
                    $('#content_customer').html(data.data);
                    if(data.advisory) {
                        $('.emtry-advisory').html(data.advisory);
                    }

                    $('.emtry-careof').html("");
                    if(data.care_of) {
                        $('.emtry-careof').html(data.care_of);
                    }
                    $('#list_order').html("");
                    if(data.orders) {
                        $('#list_order').html(data.orders);
                    }


                    AddTagMuals(id_facebook, data.tag_manuals);
                }
                else
                {
                    $('#id_facebook').val(id_facebook);
                }
            }

        }
        else
        {
            if(data.type_data) {
                $('.countCareof').html(intVal(data.countCareof));
                $('.countOrder').html(intVal(data.countOrder));
                $('.countAdvisory').html(intVal(data.countAdvisory));
                $('#content_customer').html(data.data);

                if(data.one_type && data.two_type)
                {
                    $('.event_create_advisory').attr("onclick", "CreateAdvisory("+data.one_type+",'"+data.two_type+"')");
                    $('.event_create_orders').attr("onclick", "CreateOrders("+data.one_type+",'"+data.two_type+"')");
                    $('.event_create_orders_draft').attr("onclick", "CreateOrdersDraft("+data.one_type+",'"+data.two_type+"')");
                    if(data.two_type == 'client')
                    {
                        $('.event_create_advisory_client').attr("onclick", "CreateCareOfClient("+data.one_type+",'"+data.two_type+"')");
                        $('.event_create_advisory_client').parents('.wap-action').removeClass('hide');
                    }
                    else
                    {
                        $('.event_create_advisory_client').removeAttr('onclick');
                        $('.event_create_advisory_client').parents('.wap-action').addClass('hide');
                    }
                }
                else
                {
                    $('.event_create_advisory').removeAttr('onclick');
                    $('.event_create_orders').removeAttr('onclick');

                    $('.event_create_advisory_client').removeAttr('onclick');
                    $('.event_create_advisory_client').parents('.wap-action').addClass('hide');
                }

                if(data.advisory) {
                    $('.emtry-advisory').html(data.advisory);
                }
                else
                {
                    $('.emtry-advisory').html("");
                }

                $('.emtry-careof').html("");
                if(data.care_of) {
                    $('.emtry-careof').html(data.care_of);
                }
                $('#list_order').html("");
                if(data.orders) {
                    $('#list_order').html(data.orders);
                }

                AddTagMuals(id_facebook, data.tag_manuals);
            }
            else
            {
                if(from.find('input[name="userid"]').val() || from.find('input[name="id"]').val() || $('#form_action_client').length == 0)
                {
                    $('#content_customer').html(data.data);

                    $('.countCareof').html(intVal(data.countCareof));
                    $('.countOrder').html(intVal(data.countOrder));
                    $('.countAdvisory').html(intVal(data.countAdvisory));

                    if(data.one_type && data.two_type)
                    {
                        $('.event_create_advisory').attr("onclick", "CreateAdvisory("+data.one_type+",'"+data.two_type+"')");
                        $('.event_create_orders').attr("onclick", "CreateOrders("+data.one_type+",'"+data.two_type+"')");
                        $('.event_create_orders_draft').attr("onclick", "CreateOrdersDraft("+data.one_type+",'"+data.two_type+"')");

                        if(data.two_type == 'client')
                        {
                            $('.event_create_advisory_client').attr("onclick", "CreateCareOfClient("+data.one_type+",'"+data.two_type+"')");
                            $('.event_create_advisory_client').parents('.wap-action').removeClass('hide');
                        }
                        else
                        {
                            $('.event_create_advisory_client').removeAttr('onclick');
                            $('.event_create_advisory_client').parents('.wap-action').addClass('hide');
                        }
                    }
                    else
                    {
                        $('.event_create_advisory').removeAttr('onclick');
                        $('.event_create_orders').removeAttr('onclick');

                        $('.event_create_advisory_client').removeAttr('onclick');
                        $('.event_create_advisory_client').parents('.wap-action').addClass('hide');
                    }

                    $('.emtry-advisory').html("");
                    if(data.advisory) {
                        $('.emtry-advisory').html(data.advisory);
                    }

                    $('.emtry-careof').html("");
                    if(data.care_of) {
                        $('.emtry-careof').html(data.care_of);
                    }
                    $('#list_order').html("");
                    if(data.orders) {
                        $('#list_order').html(data.orders);
                    }

                    AddTagMuals(id_facebook, data.tag_manuals);
                }
                else
                {
                    $('#id_facebook').val(id_facebook);
                }
            }
        }

        $('.content-profile').removeClass('active');
        if(!$('#id_facebook').val())
        {
            $('.content-profile[id_user="'+id_facebook+'"]').addClass('active');
        }
        else
        {
            $('.content-profile[id_user="'+$('#id_facebook').val()+'"]').addClass('active');
            id_facebook = $('#id_facebook').val();
        }

        if($('.span_name_system').length)
        {
            $('.id_name_profile_chat').text($.trim($('.span_name_system').text()));
            $('.content-profile[id_user="'+$('#id_facebook').val()+'"]').find('.name-profile').text($.trim($('.span_name_system').text()));
        }


        //Show  collaps
        $.each(arrayCareOfShow, function(Ishow, vShow){
            $('#'+vShow).collapse("show");
        })
        $.each(arrayOrderShow, function(Ishow, vShow){
            $('.'+vShow).collapse("show");
        })

        $.each(arrayAdvisoryShow, function(Ishow, vShow){
            $('#js-more_'+vShow).collapse("show");
        })
        $('.content-profile[id_user="'+$('#id_facebook').val()+'"]').find('.sone-unread').find('i').attr('class', 'fa fa-circle-o').attr('data-original-title', lang_unread);
        $('.content-profile[id_user="'+$('#id_facebook').val()+'"]').removeClass('profile_unread');
        $('.order').scrollTop(scrollOrder);
        var RObject = $('#id_facebook');
        var type_object = RObject.attr('id-type');
        var id_object = RObject.attr('id-data');
        ajaxSelectCallBack('#product_suggest', admin_url+"messager/SearchProductSuggest/"+type_object+'/'+id_object, '', '');
    })
}

//Search cột khách hàng hoặc khtn
$(document).on('keyup','#search_customer',function(e){
    var ValueInput = $(this).val().toLowerCase();
    var table_Title = $('#content_customer').find('.span-title');
    if(ValueInput != "")
    {
        $.each(table_Title, function(i, v){
            var content = $(v).text().toLowerCase();
            if(content.search(ValueInput) >= 0)
            {
                $(v).parents('.wap-content').removeClass('hide');
            }
            else
            {
                $(v).parents('.wap-content').addClass('hide');
            }
        })
    }
})

//Lấy thông tin tin nhắn message
function GetMessager()
{
    var Client_chat = $('#replyMessager').attr('id_user');
    if(typeof(Client_chat) == 'undefined') {
        Client_chat = "";
    }
    FB.api(
        "/"+$.cookie('page_active')+"/conversations?access_token="+$.cookie('access_token_page_active')+'&fields=updated_time,senders',
        function (response) {
            if (response && !response.error) {
                $.each(response.data, function(i, v){
                    if(Client_chat.length > 0 && Client_chat == v.senders.data[0].id)
                    {
                        var senderData = v.senders.data[0];
                        FB.api(
                            "/"+v.id+"/messages?access_token="+$.cookie('access_token_page_active')+'&fields=message,from,to,created_time,tags,attachments&limit=16',
                            function (response_messager) {
                                if (response_messager && !response_messager.error) {
                                    var CountWarting = 0;
                                    for(var i = (response_messager.data.length - 1); i >= 0; i--) {
                                        var messageData = response_messager.data[i];
                                        if($('#'+messageData.id).length == 0) {
                                            var date = new Date(messageData.created_time);
                                            if(messageData.attachments)
                                            {
                                                if(!messageData.message)
                                                {
                                                    messageData.message = "";
                                                }
                                                $.each(messageData.attachments.data, function(ii,vv){
                                                    if(vv.mime_type == 'image/jpeg')
                                                    {
                                                        response_messager.data[i].message += '<img class="mtop10" src="'+vv.image_data.url+'">';
                                                    }
                                                })
                                            }

                                            if(messageData.from['id'] == $.cookie('page_active')) {
                                                addMy_Send(messageData.message, date, messageData.id, true, 'last', v.id);
                                            }
                                            else
                                            {

                                                addClient_Send(messageData.message, senderData.id, date, messageData.id, true, 'last', v.id);
                                            }
                                        }

                                        if(senderData.id == messageData.from['id']) {
                                            CountWarting++;
                                            $.each(messageData.tags.data, function(ii,vv){
                                                if(vv.name == 'read')
                                                {
                                                    CountWarting--;
                                                    return false;
                                                }
                                            })
                                        }
                                        if(i == 0) {
                                            if(!messageData.attachments) {
                                                var string_data_messager = messageData.message;
                                                if (messageData.message.length > 25) {
                                                    string_data_messager = messageData.message.substr(0, 25) + '...';
                                                }
                                                $('#chat_' + senderData.id).html(string_data_messager);
                                            }
                                            else
                                            {
                                                $('#chat_' + senderData.id).html('<i class="fa fa-picture-o"></i>');
                                            }
                                        }

                                    }
                                    if(CountWarting > 0) {
                                        $('#'+senderData.id).html( (CountWarting > 5 ? '5+' : CountWarting) );
                                        $('#'+senderData.id).removeClass('hide');
                                    }
                                    else
                                    {
                                        $('#'+senderData.id).html("");
                                        $('#'+senderData.id).addClass('hide');
                                    }
                                }
                            }
                        )
                    }
                    else
                    {
                        FB.api(
                            "/"+v.id+"/messages?access_token="+$.cookie('access_token_page_active')+'&fields=message,from,to,created_time,tags,attachments&limit=16',
                            function (response_messager) {
                                if (response_messager && !response_messager.error)
                                {
                                    var CountWarting = 0;
                                    for(var i = (response_messager.data.length - 1); i >= 0; i--) {
                                        var messageData = response_messager.data[i];
                                        if (v.senders.data[0].id == messageData.from['id']) {
                                            CountWarting++;
                                            $.each(messageData.tags.data, function (ii, vv) {
                                                if (vv.name == 'read') {
                                                    CountWarting--;
                                                    return false;
                                                }
                                            })
                                        }
                                        if(i == 0) {
                                            if(!messageData.attachments) {
                                                var string_data_messager = messageData.message;
                                                if (messageData.message.length > 25) {
                                                    string_data_messager = messageData.message.substr(0, 25) + '...';
                                                }
                                                $('#chat_' + v.senders.data[0].id).html(string_data_messager);
                                            }
                                            else
                                            {
                                                $('#chat_' + v.senders.data[0].id).html('<i class="fa fa-picture-o"></i>');
                                            }
                                        }
                                    }
                                    if(CountWarting > 0) {
                                        $('#'+v.senders.data[0].id).html( (CountWarting > 5 ? '5+' : CountWarting) );
                                        $('#'+v.senders.data[0].id).removeClass('hide');
                                    }
                                    else {
                                        $('#'+v.senders.data[0].id).html("");
                                        $('#'+v.senders.data[0].id).addClass('hide');
                                    }

                                }
                            }
                        )
                    }
                })
            }
        }
    )

}

// click profile facebook
$(document).on('click', '.content-profile .profile_active', function(e){

    var profile = $(this).parents('.content-profile');
    if(!profile.hasClass('active'))
    {
        $('#replyMessager').attr('id_user',"");
        var id_user = profile.attr('id_user');
        var name = profile.find('.name-profile').html();
        $('.chat-area-reply').removeClass('hide');
        $('.close_file').trigger('click');
        var id_message = profile.attr('id_senders');
        var src = profile.find('img').prop('src');
        $('#replyMessager').attr('id_user', id_user);
        if($('#tab_'+id_message).length > 0)
        {
            $('div[href="#tab_'+id_message+'"]').tab('show');
            $('.id_profile_chat').prop('src', src);
            $('.id_name_profile_chat').html(name);

            setTimeout(function(){
                var div_content = $('#tab_'+id_message);
                $('#chat_content_body').scrollTop(div_content.innerHeight());
            }, 500);
        }
        else
        {
            $.get(admin_url+'messager/getJson_message', {id: id_message}, function (data) {
                $('#chat_content_body').append(data);
                $('div[href="#tab_' + id_message + '"]').tab('show');
                $('.id_profile_chat').prop('src', src);
                $('.id_name_profile_chat').html(name);
                var div_content = $('#tab_'+id_message);
                $('#chat_content_body').scrollTop(div_content.innerHeight());
            })
        }

        // $('#name-customer-right').html(name);
        $('#form_action_client').find('input[name="company"]').val(name);
        var img = '<img src="https://graph.facebook.com/'+id_user+'/picture?height=100&width=100&access_token='+$.cookie('access_token_page_active')+'">';
        $('#img-customer').html(img);
        varInfoUser(id_user); // lấy thông tin khashc hàng hàng tiềm năng
        $('#search_customer').val('');
        CloseSearchContentFacebook();
    }
})

//scroll nội dung chat
$( "#chat_content_body" ).scroll(function() {
    if($(this).scrollTop() == 0)
    {
        var id_message = $('.content-profile.active').attr('id_senders');
        var id_client = $('#replyMessager').attr('id_user');

        var limit = $('#chat_content_body').find('.messages-container').length;
        var new_limit = limit+10;
        $('#chat_content_body').find('.tab-pane.fade.active').attr('limit', new_limit);
        FB.api(
            "/"+id_message+"/messages?access_token="+$.cookie('access_token_page_active')+'&fields=message,from,to,created_time,tags,attachments&limit='+new_limit,
            function (response_messager) {
                if (response_messager && !response_messager.error)
                {
                    for(var i = 0; i < (response_messager.data.length - 1); i++)
                    {
                        var messageData = response_messager.data[i];
                        if($('#'+messageData.id).length == 0)
                        {
                            var date = new Date(messageData.created_time);

                            if(messageData.attachments)
                            {
                                if(!messageData.message)
                                {
                                    messageData.message = "";
                                }
                                $.each(messageData.attachments.data, function(ii,vv){
                                    if(vv.mime_type == 'image/jpeg')
                                    {
                                        messageData.message += '<img class="mtop10" src="'+vv.image_data.url+'">';
                                    }
                                })
                            }
                            if(messageData.from['id'] == $.cookie('page_active'))
                            {
                                addMy_Send(messageData.message, date, messageData.id, true, 'first', id_message);

                            }
                            else
                            {
                                addClient_Send(messageData.message,id_client, date, messageData.id, true, 'first', id_message);
                            }
                        }

                    }
                }
            }
        )
    }
})

//thêm tin nhắn vào chính mình khi gửi
function addMy_Send(replyMessager, date, message_id = "", err = true, type = 'last', id_message)
{
    var div_messager = $('#tab_'+id_message).find('.chat-area-content-profile').find('.content-message:'+type);
    var time_now = Math.floor(date.getTime() / 1000);
    if(div_messager.hasClass('my-message'))
    {
        if(type == 'last')
        {
            time_messager = div_messager.attr('time');
            if((time_now - time_messager) > 3600)
            {
                $('.chat-area-content-profile').append('<div class="time-chat"><span>'+date.getHours()+':'+date.getMinutes()+':'+date.getSeconds()+'</span></div>');
                $('.chat-area-content-profile').append('<div class="my-batch-content-container"><div class="my-messages"><div class="my-messages-container content-message my-message" time="'+time_now+'" id="'+message_id+'"><span>'+replyMessager+'</span></div></div></div>');
            }
            else
            {
                $('.chat-area-content-profile').find('.my-messages:'+type).append('<div class="my-messages-container content-message my-message" time="'+time_now+'" id="'+message_id+'"><span>'+replyMessager+'</span></div>');
            }
        }
        else
        {
            time_messager = div_messager.attr('time');
            if((time_now - time_messager) < 3600)
            {
                $('#tab_'+id_message).find('.chat-area-content-profile').prepend('<div class="my-batch-content-container"><div class="my-messages"><div class="my-messages-container content-message my-message" time="'+time_now+'" id="'+message_id+'"><span>'+replyMessager+'</span></div></div></div>');
                $('#tab_'+id_message).find('.chat-area-content-profile').prepend('<div class="time-chat"><span>'+date.getHours()+':'+date.getMinutes()+':'+date.getSeconds()+'</span></div>');
            }
            else
            {
                $('#tab_'+id_message).find('.chat-area-content-profile').find('.my-messages:'+type).prepend('<div class="my-messages-container content-message my-message" time="'+time_now+'" id="'+message_id+'"><span>'+replyMessager+'</span></div>');

            }
        }

    }
    else
    {
        if(type == 'last')
        {
            var date = new Date();
            time_messager = div_messager.attr('time');
            if((time_now - time_messager) > 3600)
            {
                $('.chat-area-content-profile').append('<div class="time-chat"><span>'+date.getHours()+':'+date.getMinutes()+':'+date.getSeconds()+'</span></div>');
            }
            $('.chat-area-content-profile').append('<div class="my-batch-content-container"><div class="my-messages"><div class="my-messages-container content-message my-message" time="'+time_now+'" id="'+message_id+'"><span>'+replyMessager+'</span></div></div></div>');
        }
        else
        {
            var date = new Date();
            time_messager = div_messager.attr('time');
            if((time_now - time_messager) < 3600)
            {
                $('.chat-area-content-profile').prepend('<div class="time-chat"><span>'+date.getHours()+':'+date.getMinutes()+':'+date.getSeconds()+'</span></div>');
            }
            $('.chat-area-content-profile').prepend('<div class="my-batch-content-container"><div class="my-messages"><div class="my-messages-container content-message my-message" time="'+time_now+'" id="'+message_id+'"><span>'+replyMessager+'</span></div></div></div>');

        }
    }

    if(type == "last")
    {
        var div_content = $('#tab_'+id_message);
        $('#chat_content_body').scrollTop(div_content.innerHeight());
    }
}

//thêm tin nhắn vào khi gửi của khách hàng
function addClient_Send(replyMessager, id_client = "", date, message_id = "", err = false, type = 'last', id_message)
{
    if($('#tab_'+id_message).find('.chat-area-content-profile').find('.client-message[id="'+message_id+'"]').length > 0)
    {
        return;
    }
    var div_messager = $('#tab_'+id_message).find('.chat-area-content-profile').find('.content-message:'+type);
    var time_now = Math.floor(date.getTime()/1000);
    if(div_messager.hasClass('client-message'))
    {
        time_messager = div_messager.attr('time');
        if(type == 'last')
        {
            if((time_now - time_messager) > 3600)
            {
                $('#tab_'+id_message).find('.chat-area-content-profile').append('<div class="time-chat"><span>'+date.getHours()+':'+date.getMinutes()+':'+date.getSeconds()+'</span></div>');
                $('#tab_'+id_message).find('.chat-area-content-profile').append('<div class="batch-content-container">' +
                    '    <div class="avatar">'+
                    '        <img src="https://graph.facebook.com/'+id_client+'/picture?height=100&width=100&access_token='+$.cookie('access_token_page_active')+'">'+
                    '    </div>'+
                    '    <div class="messages">' +
                    '        <div class="messages-container content-message client-message" time="'+time_now+'" id="'+message_id+'">' +
                    '            <span>'+replyMessager+'</span>' +
                    '        </div>' +
                    '    </div>' +
                    '</div>');
            }
            else
            {
                $('#tab_'+id_message).find('.chat-area-content-profile').find('.messages:'+type).append('<div class="messages-container content-message client-message" time="'+Math.floor(date.getTime()/1000)+'" id="'+message_id+'"><span>'+replyMessager+'</span></div>');

            }
        }
        else
        {

            if((time_messager - time_now) > 3600)
            {
                $('#tab_'+id_message).find('.chat-area-content-profile').prepend('<div class="batch-content-container">' +
                    '    <div class="avatar">'+
                    '        <img src="https://graph.facebook.com/'+id_client+'/picture?height=100&width=100&access_token='+$.cookie('access_token_page_active')+'">'+
                    '    </div>'+
                    '    <div class="messages">' +
                    '        <div class="messages-container content-message client-message" time="'+time_now+'" id="'+message_id+'">' +
                    '            <span>'+replyMessager+'</span>' +
                    '        </div>' +
                    '    </div>' +
                    '</div>');
                $('#tab_'+id_message).find('.chat-area-content-profile').prepend('<div class="time-chat"><span>'+date.getHours()+':'+date.getMinutes()+':'+date.getSeconds()+'</span></div>');

            }
            else
            {
                $('#tab_'+id_message).find('.chat-area-content-profile').find('.messages:'+type).prepend('<div class="messages-container content-message client-message" time="'+time_messager+'" id="'+message_id+'"><span>'+replyMessager+'</span></div>');

            }
        }
    }
    else
    {
        var date = new Date();
        time_messager = div_messager.attr('time');
        if(type == 'last') {
            if ((time_now - time_messager) > 3600) {
                $('.chat-area-content-profile').append('<div class="time-chat"><span>' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + '</span></div>');
            }
            $('#tab_'+id_message).find('.chat-area-content-profile').append('<div class="batch-content-container">' +
                '    <div class="avatar">' +
                '        <img src="https://graph.facebook.com/' + id_client + '/picture?height=100&width=100&access_token=' + $.cookie('access_token_page_active') + '">' +
                '    </div>' +
                '    <div class="messages">' +
                '        <div class="messages-container content-message client-message" time="' + time_now + '" id="' + message_id + '">' +
                '            <span>' + replyMessager + '</span>' +
                '        </div>' +
                '    </div>' +
                '</div>');
        }
        else
        {

            if ((time_messager - time_now) > 3600) {
                $('.chat-area-content-profile').prepend('<div class="time-chat"><span>' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + '</span></div>');
            }
            $('#tab_'+id_message).find('.chat-area-content-profile').prepend('<div class="batch-content-container">' +
                '    <div class="avatar">' +
                '        <img src="https://graph.facebook.com/' + id_client + '/picture?height=100&width=100&access_token=' + $.cookie('access_token_page_active') + '">' +
                '    </div>' +
                '    <div class="messages">' +
                '        <div class="messages-container content-message client-message" time="' + time_messager + '" id="' + message_id + '">' +
                '            <span>' + replyMessager + '</span>' +
                '        </div>' +
                '    </div>' +
                '</div>');
        }
    }

    if(type == "last")
    {
        var div_content = $('#tab_'+id_message);
        $('#chat_content_body').scrollTop(div_content.innerHeight());
    }
    OrderByDiv(id_client);

    var KTPhone = checkPhone(replyMessager.toString());
    if(KTPhone == true)
    {
        AddphoneSearch(id_client, replyMessager.toString());
    }

}

//Các function update file

function GetFilePC()
{
    $('#form_uploadfile')[0].reset();
    $('input#file').click();
}


$('body').on('change','input#file',function(e){
    if($('#replyMessager').attr('id_user'))
    {
        var form = $('#form_uploadfile');
        var file_data = $('input[type="file"]').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('userid', $('#replyMessager').attr('id_user'));
        if (typeof(csrfData) !== 'undefined') {
            form_data.append(csrfData['token_name'], csrfData['hash']);
        }
        $.ajax({
            url: form.attr('action'),
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (data) {
                if(data.success)
                {
                    var divFileSend = $('<div class="file_send" url="'+data.url+'"></div>');
                    var divNameFile = $('   <div class="name_file"></div>');
                    divNameFile.append('        <i class="fa fa-picture-o" aria-hidden="true">'+data.name+'</i>');
                    divNameFile.append('        <div class="close_file pull-right" newfile="'+data.newfile+'">X</div>');
                    divFileSend.append(divNameFile);
                    $('#all_file_send').append(divFileSend);
                    reloadHeightChat();
                }
            }
        });
    }
})


$('body').on('click', '.close_file', function(e){
    var data = {};
    var url = $(this).attr('newfile');
    data['url'] = url;
    if (typeof(csrfData) !== 'undefined') {
        data[csrfData['token_name']] = csrfData['hash'];
    }

    $.post(admin_url+"messager/deleteFile", data, function(data){})
    $(this).parents('.file_send').remove();
    reloadHeightChat();
})


function GetFileLink()
{
    $('#file_link').modal('show');
}


$('#file_link').on('hidden.bs.modal', function (e){
    ('#file_link').val('');
})

function SendFileLink()
{
    var id_user = $('#replyMessager').attr('id_user');
    var url = $('#input_file_link').val();
    if(url != "")
    {
        $('#input_file_link').val('');
        $.post('https://graph.facebook.com/'+VersionAppFB+'/me/messages',
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
                $('#file_link').modal('hide');
            })
    }
}

function AddPhotoModal()
{
    $('#fanpage_photo_modal').find('.body_modal').html('');
    var access_token = $.cookie('access_token_page_active');
    FB.api(
        "/"+$.cookie('page_active')+"/photos?access_token="+access_token,
        function (response) {
            if (response && !response.error) {
                $.each(response.data, function(i, v){
                    $('#fanpage_photo_modal').find('.body_modal').append('<div class="col-md-4"><a onclick="AddImgSend('+v.id+')"><img style="width:100px;height:100px;" src="https://graph.facebook.com/'+v.id+'/picture?height=100&width=100&access_token='+$.cookie('access_token_page_active')+'"></a></div>');
                })
            }
        }
    );
    $('#fanpage_photo_modal').modal('show');
}

function AddImgSend(id)
{
    var url = 'https://graph.facebook.com/'+id+'/picture?height=100&width=100&access_token='+$.cookie('access_token_page_active');
    var time = new Date();
    $('#all_file_send').append('<div class="file_send"  url="'+url+'">\
                                                        <div class="name_file">\
                                                            <i class="fa fa-picture-o" aria-hidden="true"></i>\
                                                            '+time.getTime()+'\
                                                        </div>\
                                                        <div class="close_file text-danger pointer" newfile="">X</div>\
                                                    </div>');
    reloadHeightChat();
    $('#fanpage_photo_modal').modal('hide');
}

function AddImgSendProduct(url)
{
    var time = new Date();
    $('#all_file_send').append('<div class="file_send"  url="'+url+'">\
                                                        <div class="name_file">\
                                                            <i class="fa fa-picture-o" aria-hidden="true"></i>\
                                                            '+time.getTime()+'\
                                                        </div>\
                                                        <div class="close_file text-danger pointer" newfile="">X</div>\
                                                    </div>');
    reloadHeightChat();
    $('#fanpage_photo_modal').modal('hide');
}
//End function update file

//Sắp xếp và tạo list khách hàng mới
function OrderByDiv(id_user)
{
    if(id_user != "")
    {
        var div_profile_first = $('.list-profile').find('.content-profile[id_user="'+id_user+'"]');
        var string_html = div_profile_first;
        $('.list-profile').prepend(string_html);
    }
}

function CreateProfileMessager(idClient, Messager)
{
    if(idClient != "")
    {
        FB.api(
            "/"+$.cookie('page_active')+"/conversations?access_token="+$.cookie('access_token_page_active')+'&fields=updated_time,senders',
            function (response) {
                if (response && !response.error) {
                    $.each(response.data, function(i,v) {
                        if (idClient == v.senders.data[0].id) {
                            $('.list-profile').append('<div class="content-profile profile_unread" id_senders="'+v.id+'" id_user="' + idClient + '" data-toggle="tab" href="#tab_'+v.id+'">\
                                                                <div class="img-info">\
                                                                    <img src="https://graph.facebook.com/' + idClient + '/picture?height=100&width=100&access_token=' + $.cookie('access_token_page_active') + '">\
                                                                </div>\
                                                                <div class="some-info">\
                                                                    <div class="name-profile">\
                                                                       '+v.senders.data[0].name+'\
                                                                    </div>\
                                                                    <div class="chat-profile" id="chat_'+idClient+'">'+Messager+'</div>\
                                                                </div>\
                                                                <div class="time-info" time="'+getDay+'">\
                                                                    Just now\
                                                                </div>\
                                                                <div class="pull-left">\
                                                                    <span class="sone-unread"><i class="fa fa-circle" aria-hidden="true" data-toggle="tooltip" data-original-title="'+lang_read+'"></i></span>\
                                                                    <img style="width: 15px; height: 15px;" src="'+site_url+'modules/messager/uploads/messager-fb.png">\
                                                                </div>\
                                                                <div class="clearfix"></div>\
                                                            </div>');
                            OrderByDiv(idClient);
                            var dataProfile = {id_facebook : idClient, name : v.senders.data[0].name};
                            if (typeof(csrfData) !== 'undefined') {
                                dataProfile[csrfData['token_name']] = csrfData['hash'];
                            }
                            $.post(admin_url+'messager/addProfileListFB', dataProfile, function(e){})
                        }
                    })
                }
            })
    }
}
//END Sắp xếp và tạo list khách hàng mới

//Thay đổi fanpage facebook

function change_fanpage(_this)
{
    var name_fanpage = $(_this).attr('name_fanpage');
    var access_token = $(_this).attr('access_token');
    var id_fanpage   = $(_this).attr('id_fanpage');
    $.cookie("page_active", id_fanpage, { path: '/' });
    $.cookie("access_token_page_active", access_token, { path: '/' });
    $.cookie("name_page_active", name_fanpage, { path: '/' });
    location.reload();
}

//log out facebook
function LogoutFB(){
    $.cookie("page_active", '', { expires: -1, path: '/' });
    $.cookie("access_token_page_active", '', { expires: -1, path: '/' });
    $.cookie("name_page_active", '', { expires: -1, path: '/' });
    $.cookie("access_token_page", '', { expires: -1, path: '/' });
    $.cookie("user_token", '', { expires: -1, path: '/' });
    FB.getLoginStatus(function(response) {
        FB.logout(function (res) {
            location.reload();
        });
    })
}

//nhận và xữ lý Pusher
channel.bind('GetNewMessager', function(data)
{
    var id_senders = $('.content-profile[id_user="'+data.message.sender.id+'"]').attr('id_senders');
    if(data.message.sender.id == $.cookie('page_active'))
    {
        var date = new Date();
        addMy_Send(data.message.message.text, data.message.timestamp, data.message.message.mid, true, 'last', id_senders);

        UpdateLast_messager(data.message.sender.id, 1);
        if(data.message.message.text.length > 25)
        {
            var stringText = data.message.message.text.substr(0, 25)+'...';
        }
        else
        {
            stringText = data.message.message.text;
        }
        $('#chat_'+data.message.sender.id).html($.trim(data.message.message.text));
        $('#time_' + data.message.sender.id).html(timeConverter(data.message.sender.timestamp));
    }
    else
    {

        if(data.message.message)
        {
            if($('.content-profile[id_user="'+data.message.sender.id+'"]').length > 0) {
                if (data.message.message.attachments)
                {
                    data.message.message.text = "";
                    $.each(data.message.message.attachments, function (i, v) {
                        if (v.type == 'image') {
                            data.message.message.text += '<img class="mtop10" src="' + v.payload.url + '"/>';
                        }
                    })
                }

                var date = new Date();
                addClient_Send(data.message.message.text, data.message.sender.id, date, data.message.message.mid, true, 'last', id_senders);

                if (!data.message.message.attachments) {
                    var string_data_messager = data.message.message.text;
                    if (data.message.message.text.length > 25) {
                        string_data_messager = data.message.message.text.substr(0, 25) + '...';
                    }
                    $('#chat_' + data.message.sender.id).html(string_data_messager);
                } else {
                    $('#chat_' + data.message.sender.id).html('<i class="fa fa-picture-o"></i>');
                }
                $('#time_' + data.message.sender.id).html(timeConverter(data.message.timestamp));
                UpdateLast_messager(data.message.sender.id, 0);
            }
            else
            {

                if (data.message.message.attachments) {
                    data.message.message.text = "";
                    $.each(data.message.message.attachments, function (i, v) {
                        if (v.type == 'image') {
                            data.message.message.text += '<img class="mtop10" src="' + v.payload.url + '"/>';
                        }
                    })
                }
                CreateProfileMessager(data.message.sender.id,data.message.message.text);
            }

        }
        else
        {
            FB.api(
                "/"+id_senders+"/messages?access_token="+$.cookie('access_token_page_active')+'&fields=message,from,to,created_time,tags,attachments&limit=16',
                function (response_messager) {
                    if (response_messager && !response_messager.error)
                    {
                        var CountWarting = 0;
                        for(var i = (response_messager.data.length - 1); i >= 0; i--)
                        {
                            if($('#'+response_messager.data[i].id).length == 0)
                            {
                                var date = new Date(response_messager.data[i].created_time)

                                if(response_messager.data[i].attachments)
                                {
                                    if(!response_messager.data[i].message)
                                    {
                                        response_messager.data[i].message = "";
                                    }
                                    $.each(response_messager.data[i].attachments.data, function(ii,vv){
                                        if(vv.mime_type == 'image/jpeg')
                                        {
                                            response_messager.data[i].message += '<img class="mtop10" src="'+vv.image_data.url+'">';
                                        }
                                        if(vv.mime_type == 'file')
                                        {
                                            response_messager.data[i].message += '<a target="_blank"  href="'+vv.image_data.url+'">'+File+'</a>';
                                        }
                                    })
                                }
                                if(response_messager.data[i].from['id'] == $.cookie('page_active'))
                                {
                                    addMy_Send(response_messager.data[i].message, date, response_messager.data[i].id, true, 'last', id_senders);
                                    OrderByDiv(response_messager.data[i].to['id']);

                                }
                                else
                                {
                                    var id_data_response = response_messager.data[i].id.split('m_');
                                    if(id_data_response.length > 1)
                                    {
                                        id_data_response = id_data_response[1];
                                    }
                                    else {
                                        id_data_response = response_messager.data[i].id;
                                    }
                                    addClient_Send(response_messager.data[i].message, data.message.recipient.id, date, id_data_response, true, 'last', id_senders);
                                    if(!response_messager.data[i].attachments) {
                                        var string_data_messager = response_messager.data[i].message;
                                        if (response_messager.data[i].message.length > 25) {
                                            string_data_messager = response_messager.data[i].message.substr(0, 25) + '...';
                                        }
                                        $('#chat_' + data.message.recipient.id).html(string_data_messager);

                                    }
                                    else
                                    {
                                        $('#chat_' + data.message.recipient.id).html('<i class="fa fa-picture-o"></i>');
                                    }
                                    $('#time_' + data.message.recipient.id).html(timeConverter(data.message.timestamp));
                                }
                            }
                        }
                    }
                }
            )
        }
    }
});

$('body').on('click', '.createTag', function(e){
    var tname = $(this).attr('title');
    $('.tagstype').tagit('createTag', $.trim(tname));
    $('.tagstype').trigger('change');
})

function copy_content(string) {
    var textArea = document.createElement("textarea");
    textArea.value = string;
    document.body.appendChild(textArea);
    // textArea.focus();
    textArea.select();
    document.execCommand("copy");
    document.body.removeChild(textArea);
    alert_float('info',alert_success_copy);
}

function convert_lead_to_customerFB(e) {
    var t = $("#lead-modal");
        t.find(".data").html(""), requestGet("messager/get_convert_dataFB/" + e).done(function(e) {
            $("#lead_convert_to_customer").html(e), $("#convert_lead_to_client_modal").modal({
                show: !0,
                backdrop: "static",
                keyboard: !1
            })
        }).fail(function(e) {
            alert_float("danger", e.responseText)
        }).always(function() {
            t.off("hidden.bs.modal.convert")
        })
}

$('body').on('click', '.war_client', function(e){
    var form = $(this).parents('form');
    var id = form.find('#id').val();
    if($.isNumeric(id))
    {
        var data = {id:id};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        $.post(admin_url+'messager/WarClient', data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                varInfoUser(data.id_facebook);
            }
            alert_float(data.alert_type, data.message);
        })
    }
})

$('body').on('click', '.war_lead', function(e){
    var form = $(this).parents('form');
    var id = form.find('#id').val();
    if($.isNumeric(id))
    {
        var data = {id:id};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        $.post(admin_url+'messager/WarLead', data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                varInfoUser(data.id_facebook);
            }
            alert_float(data.alert_type, data.message);
        })
    }
})

//search data client
$('body').on('click', '.TSearch', function(e){
    var LiActive = $(this);
    var id_data = LiActive.attr('id-data');
    if(id_data != 'tag')
    {
        $('.DSearch').removeClass('active');
        $('.TSearch').parent('li').removeClass('active');
        LiActive.parent('li').addClass('active');
        SearchLeft();
    }
})

$('body').on('click', '.DSearchTag', function(e){
    var TagActive = $(this);
    var id = TagActive.attr('id-data');
    $('.navSearch').find('li:not(.DSearch)').removeClass('active');
    if(TagActive.parent('li').hasClass('active'))
    {
        TagActive.parent('li').removeClass('active');
    }
    else
    {
        TagActive.parent('li').addClass('active');

        TagActive.parents('li').parents('li').addClass('active');

    }
    // $('.DSearchTag').parent('li').removeClass('active');
    SearchLeft();
    return;
})

//search profile chat
$('body').on('keyup', '#search_Chat', function(e){
    SearchLeft();
    return;
})

$('body').on('click', '.TLeftSearch', function(e){

    if(!$(this).hasClass('TTimeChat'))
    {
        if($(this).hasClass('active'))
        {
            $(this).removeClass('active');
        }
        else
        {
            $(this).addClass('active');
        }

        if($(this).hasClass('TAssigned'))
        {
            if($('#assignedSearch').val() != "")
            {
                $(this).addClass('active');
                $(this).find('.DeleteSreach').removeClass('hide');
            }
            else
            {
                $(this).removeClass('active');
                $(this).find('.DeleteSreach').addClass('hide');
            }
        }
    }
    else
    {
        $('#time_chat').click();
    }
    SearchLeft();
})


$('body').on('click', '.ItemSearchAssigned', function(e){
    var assignedSearch = $('#assignedSearch').val();
    if($(this).hasClass('active'))
    {
        var id = $(this).attr('id-data');
        $(this).removeClass('active');
        if($.isNumeric(id))
        {
            var assignedArray = assignedSearch.split(',');

            var assignedArrayNew = [];
            $.each(assignedArray, function(i, v){
                if(id != v)
                {
                    assignedArrayNew.push(v);
                }
            })
            assignedSearch = assignedArrayNew.join(',');
            $('#assignedSearch').val(assignedSearch);
        }
    }
    else
    {
        $(this).addClass('active');
        var id = $(this).attr('id-data');
        if(assignedSearch == "")
        {
           var assignedArray = [];
        }
        else
        {
            var assignedArray = assignedSearch.split(',');
        }

        assignedArray.push(id);
        assignedSearch = assignedArray.join(',');
        $('#assignedSearch').val(assignedSearch);

    }
    var html_popver = $(this).parents('.popover-content').html();
    $('.TLeftSearch.TAssigned').find('a[data-toggle="popover"]').attr('data-content', html_popver);
    SearchLeft();
    return;
})

var ObjectHtmlAssignedDefaule = $('.TLeftSearch.TAssigned').find('a[data-toggle="popover"]').attr('data-content');

$('body').on('click', '.DeleteSreach', function(e){
    var LeftSearch = $(this).parents('.TLeftSearch');
    if(LeftSearch.hasClass('TAssigned'))
    {
        LeftSearch.find('a[data-toggle="popover"]').attr('data-content', ObjectHtmlAssignedDefaule);
        $('#assignedSearch').val('');
        $('.TLeftSearch.TAssigned').trigger('click');
    }
    else if(LeftSearch.hasClass('TTimeChat'))
    {
        $(this).addClass('hide');
        $('#time_chat').val('');
        $('.TLeftSearch.TTimeChat').removeClass('active');
    }
})

$('#time_chat').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
    $('.TTimeChat').removeClass('active');
    $('.TTimeChat').find('.DeleteSreach').addClass('hide');
    SearchLeft();
});

$('#time_chat').on('apply.daterangepicker', function(ev, picker) {;
    $('.TTimeChat').addClass('active');
    $('.TTimeChat').find('.DeleteSreach').removeClass('hide');
    SearchLeft();
});

$('#time_chat').on('showCalendar.daterangepicker', function(ev, picker) {;
    if(!$('.TTimeChat').hasClass('active'))
    {
        $(this).val('');
        SearchLeft();
    }
});

function CreateOrders(id = "", type = "")
{
    if(id != "" && type != "")
    {
        var data = {id : id ,type : type};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        $.post(admin_url + 'messager/ViewCreateOrder', data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                $('#modalOne').html(data.data);
            }
            else
            {
                alert_float(data.alert_type, data.message);
            }
        })
    }

}

function CreateOrdersDraft(id = "", type = "")
{
    if(id != "" && type != "")
    {
        var data = {id : id ,type : type};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        var id_facebook = $('#id_facebook').val();
        data['id_facebook'] = id_facebook;
        $.post(admin_url + 'messager/ViewCreateOrderDraft', data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                $('#modalOne').html(data.data);
            }
            else
            {
                alert_float(data.alert_type, data.message);
            }
        })
    }

}

function parseDate(str) {
    var mdy = str.split('/');
    return new Date(mdy[2], mdy[1], mdy[0]);
}

function SearchLeft()
{
    var content_profile = $('.content-profile');
    $('.content-profile').removeClass('hide');
    var ValChat = $('#search_Chat').val().toLowerCase();
    if(ValChat != "")
    {
        $.each(content_profile, function(i, v){
            var content = $(v).find('.name-profile').text().toLowerCase();
            if(content.search(ValChat) >= 0)
            {
                $(v).removeClass('hide');
            }
            else
            {
                $(v).addClass('hide');
            }
        })
    }

    var TagActive = $('li.DSearch.active .DSearchTag');
    if(TagActive.length > 0)
    {
        $.each(content_profile, function(i, v) {
            var ProfileShow = false;
            $.each(TagActive, function(kTagActive, vTagActive){
                if(!$(vTagActive).hasClass('Muals'))
                {
                    var id = $(vTagActive).attr('id-data');
                    console.log(id)
                    if($.isNumeric(id))
                    {
                        if($(v).find('.tag-lef-'+id).length == 0){
                            ProfileShow = false;
                        }
                        else
                        {
                            ProfileShow = true;
                            return false;
                        }
                    }
                    else
                    {
                        if($(v).find('.tag_left').find('span').length == 0 && $(v).find('.tag-muals').find('.span-tag-muals').length == 0){
                            ProfileShow = true;
                            return false;
                        }
                    }
                }
                else
                {
                    var DataSearch = $.trim($(vTagActive).text());
                    $('.span-tag-muals[data-title="'+DataSearch+'"]');
                    if($(v).find('.span-tag-muals[data-title="'+DataSearch+'"]').length == 0)
                    {
                        ProfileShow = false;
                    }
                    else
                    {
                        ProfileShow = true;
                        return false;
                    }
                }

            })

            if(ProfileShow == false)
            {
                $(v).addClass('hide');
            }
        })

    }

    var LiActive = $('.active a.TSearch');
    if(LiActive.length > 0)
    {
        var id_data = LiActive.attr('id-data');
        if(id_data == 'comment')
        {
            $('.content-profile').addClass('hide');
        }
        else if(id_data == 'not_see')
        {
            var content_profile = $('.content-profile');
            $.each(content_profile, function(i, v){
                if($(v).find('.sone-unread').find('i.fa-circle-o').length > 0)
                {
                    $(v).addClass('hide');
                }
            })
        }
    }

    var LeftSearch = $('.TLeftSearch.active a');
    $.each(LeftSearch, function(i, v){
        var id_data = $(v).attr('id-data');
        if(id_data == 'phone')
        {
            $('.content-profile[phone=""]').addClass('hide');
        }
        else if(id_data == 'not_phone')
        {
            $('.content-profile[phone!=""]').addClass('hide');
        }
        else if(id_data == 'orders')
        {
            $('.content-profile[orders=""]').addClass('hide');
        }
    })


    var assignedSearch = $('#assignedSearch').val();
    if(assignedSearch != "")
    {
        assignedSearch = assignedSearch.split(',');
        var content_profile = $('.content-profile[assigned != ""]');
        $.each(content_profile, function(i, v) {
            var assigned = $(v).attr('assigned').split(',');
            var success = false;
            $.each(assignedSearch, function(ia,va){
                $.each(assigned, function(ii, vv){
                    if(va == vv)
                    {
                        success = true;
                        console.log(va+'-'+vv)
                        return false;
                    }
                })
                if(success == true)
                {
                    return false;
                }
            })
            if(success == false)
            {
                $(v).addClass('hide');
            }

        })
        if(assignedSearch.length > 0)
        {
            $('.content-profile[assigned=""]').addClass('hide');
        }

    }


    if($('.TTimeChat').hasClass('active') && $('#time_chat').val() != "")
    {
        var dateTime = $('#time_chat').val();
        var arrayTime = dateTime.split(" - ");
        var startDate = parseDate(arrayTime[0]).getTime();
        var endDate = parseDate(arrayTime[1]).getTime();
        $.each(content_profile, function(key, value){
            var time = $(value).find('.time-info').attr('time');
            time = parseDate(time).getTime();
            if(time < startDate || time > endDate)
            {
                $(value).addClass('hide');
            }
        })

        $('.TTimeChat').find('a[id-data="time"]').attr('title', cong_t_search_time+ ' '+dateTime);
        $('.TTimeChat').find('a[id-data="time"]').attr('data-original-title', cong_t_search_time+ ' '+dateTime);
    }
    else
    {
        $('.TTimeChat').find('a[id-data="time"]').attr('title', cong_t_search_time);
        $('.TTimeChat').find('a[id-data="time"]').attr('data-original-title', cong_t_search_time);
    }
}

$('body').on('click', '.TLeftSearchALL', function(e){
    $('.TLeftSearch').removeClass('active');
    $('#assignedSearch').val('');
    $('.DeleteSreach').addClass('hide');
    $('#time_chat').val('');
    $('.DSearch').removeClass('active');
    SearchLeft();

})

function manageSubmitFB(form) {
    var button = $(form).find('button[type="submit"]');
    button.button({loadingText: please_wait});
    button.button('loading');
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function(response) {
        console.log(response);
        response = JSON.parse(response);
        if (response.success == true) {
            alert_float('success', response.message);
            varInfoUser(response.id_facebook);
            $(form).parents('.modal').modal('hide');
        }
    }).always(function() {
        button.button('reset')
    });
    return false;
}

function CreateAdvisory(id, type)
{
    if(id != "" && type != "")
    {
        var data = {id : id ,type : type};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        $.post(admin_url + 'messager/ViewAdvisory', data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                $('#modal_advisory_lead').html(data.data);
                $('#modal_advisory_lead').modal('show');
            }
            else
            {
                alert_float(data.alert_type, data.message);
            }
        })
    }
}

function CreateCareOfClient(id, type)
{
    if(id != "" && type != "")
    {
        var data = {id : id ,type : type};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        $.post(admin_url + 'messager/ViewCareOf', data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                $('#modal_care_of_clients').html(data.data);
                $('#modal_care_of_clients').modal('show');
            }
            else
            {
                alert_float(data.alert_type, data.message);
            }
        })
    }
}

/*
    Các function đơn hàng
 */
//Cập nhật trạng thái đơn hàng
$('body').on('click', '.status_orders',function(e){
    var PStatus = $(this);
    var id = $(this).attr('id-data');
    var status = $(this).attr('status-procedure');
    var data = {};
    if (typeof(csrfData) !== 'undefined') {
        data[csrfData['token_name']] = csrfData['hash'];
    }
    data['id'] = id;
    data['status'] = status;
    $.post(admin_url+'orders/update_status', data, function(data){
        data = JSON.parse(data);
        if(data.success)
        {
            var id_facebook = $('#id_facebook').val();
            varInfoUser(id_facebook);
        }
        alert_float(data.alert_type, data.message);
    })
})

function restore_step(id = "")
{
    if($.isNumeric(id))
    {
        var data = {};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        data['id'] = id;
        $.post(admin_url+'orders/restore_step', data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                var id_facebook = $('#id_facebook').val();
                varInfoUser(id_facebook);
            }
            alert_float(data.alert_type, data.message);
        })
    }
}

function restore_orders(id = "", status = "")
{
    if($.isNumeric(id) && $.isNumeric(status))
    {
        var data = {};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        data['id'] = id;
        data['status'] = status;
        $.post(admin_url+'orders/restore_orders', data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                var id_facebook = $('#id_facebook').val();
                varInfoUser(id_facebook);
            }
            alert_float(data.alert_type, data.message);
        })
    }
}

function DeleteOrders(id = "")
{
    if(confirm(cong_you_must_delete))
    {
        if($.isNumeric(id))
        {
            var data = {};
            if (typeof(csrfData) !== 'undefined') {
                data[csrfData['token_name']] = csrfData['hash'];
            }
            data['id'] = id;
            $.post(admin_url+'orders/delete_orders', data, function(data){
                data = JSON.parse(data);
                if(data.success)
                {
                    var id_facebook = $('#id_facebook').val();
                    varInfoUser(id_facebook);
                }
                alert_float(data.alert_type, data.message);
            })
        }
    }
}

/*
    end Các function đơn hàng
 */

//hoàng crm bổ xung view xem thông tin khách hàng
function ViewProfile(id, type)
{
    if(id != "" && type != "")
    {
        var data = {id : id ,type : type};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        $.post(admin_url + 'messager/ViewAdvisory', data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                $('#modal_profile_customer').html(data.data);
                $('#modal_profile_customer').modal('show');
            }
            else
            {
                alert_float(data.alert_type, data.message);
            }
        })
    }
}

function ViewProfileLead(id, type)
{
    if(id != "" && type != "")
    {
        var data = {id : id ,type : type};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        $.post(admin_url + 'messager/ViewAdvisory', data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                $('#modal_profile_lead').html(data.data);
                $('#modal_profile_lead').modal('show');
            }
            else
            {
                alert_float(data.alert_type, data.message);
            }
        })
    }
}

function reloadHeightChat()
{
   var heightChatConTent = $('#chat_content_body').height();
   var lengthItems = $('#all_file_send').find('.file_send').length;
   if(lengthItems > 0)
   {
       var HeightNew = 220 + lengthItems * 33;
       $('#chat_content_body').css('height','calc(100% - ('+(HeightNew)+'px)');
   }
   else
   {
       var HeightNew = 220;
       $('#chat_content_body').css('height','calc(100% - ('+(HeightNew)+'px)');
   }
}

//fotmat select2
function formatTag(state) {
    if (!state.id) return state.text;
    var color = C_available_tags_color[state.id];
    var background_color = C_available_tags_background_color[state.id];
    return "<i class='fa fa-circle' style='color:"+background_color+"' aria-hidden='true'></i> "+ state.text;
}

//Edit input thông tin

$('body').on('dblclick', '.viewInput', function(e){
    var divContent = $(this).parents('.wap-content');
    $('.viewInput').removeClass('hide');
    divContent.find('.viewInput:not(.span-title)').addClass('hide');

    $('.editInput').addClass('hide');
    divContent.find('.editInput').removeClass('hide');
    // divContent.find('.editInput').find('input').focus();
    divContent.find('.editInput').find('input').select();
})

$('body').on('click', '.not-edit-input', function(e){
    var divContent = $(this).parents('.div_input_content');
    divContent.find('.viewInput').removeClass('hide');
    var textBefore = divContent.find('.viewInput').text();
    divContent.find('.editInput').addClass('hide');
    if($.trim(textBefore) == '-')
    {
        textBefore = "";
    }
    divContent.find('.editInput').find('input.C_text_input').val($.trim(textBefore));
    divContent.find('.editInput').find('select').val($.trim(textBefore));
})

//end
//hoàng crm bổ xung view empty
$( document ).ready(function() {
    if($('.list-profile').find('.active').length == 0)
    {
        $('.chat-area-content').append('\
            <div class="center js-check-empty">\
                <img style="width: 380px; height: 370px;" src="'+basePath+'empty.png">\
                <p style="font-weight: 500; font-size: 20px; color: #b3b3b3;">Vui lòng chọn hội thoại để bắt đầu</p>\
            </div>\
        ');
    }
});
//end

//pos.js
$(document).on('click', '.customer-tab', (e) => {
  $('.customer-tab').addClass('active');
  $('.order-tab').removeClass('active');
  $('.tab-content-customer').removeClass('hide');
  $('.order').addClass('hide');
  document.getElementById('customer-tab').style.zIndex = '2';
  document.getElementById('order-tab').style.zIndex = '1';
});

$(document).on('click', '.order-tab', (e) => {
  $('.customer-tab').removeClass('active');
  $('.order-tab').addClass('active');
  $('.tab-content-customer').addClass('hide');
  $('.order').removeClass('hide');
  document.getElementById('customer-tab').style.zIndex = '1';
  document.getElementById('order-tab').style.zIndex = '2';
  resizeHeight();
});

    //láy chiều cao động
$(document).ready(function(){
    var offsetHeightCustomer = 0;
    var offsetHeightSummary = 0;
    if($('#customer-info').length)
    {
        offsetHeightCustomer = document.getElementById('customer-info').offsetHeight;
    }
    if($('#summary-inserted').length)
    {
        offsetHeightSummary = document.getElementById('summary-inserted').offsetHeight;
    }
    var totalHeight = offsetHeightCustomer + offsetHeightSummary;
    if($('#each-list-container').length) {
        document.getElementById('each-list-container').style.height = 'calc(100% - '+totalHeight+'px)';
    }
});

function resizeHeight() {
    if($('#delivery-info').length) {
        var offsetHeightDelivery = document.getElementById('delivery-info').offsetHeight;
    }
    if($('#order-search').length) {
        var offsetHeightOrder = document.getElementById('order-search').offsetHeight;
    }
    if($('#item-list-container').length) {
        var offsetHeightItem = document.getElementById('item-list-container').offsetHeight;
    }
    if($('#checkout-details').length) {
        var offsetHeightCheckout = document.getElementById('checkout-details').offsetHeight;
    }
    if($('#pos-actions').length) {
        var offsetHeightPos = document.getElementById('pos-actions').offsetHeight;
    }
    if($('#body-item-list-container').length && offsetHeightDelivery && offsetHeightOrder && offsetHeightItem && offsetHeightCheckout && offsetHeightPos) {
        var totalHeight = offsetHeightDelivery + offsetHeightOrder + offsetHeightItem + offsetHeightCheckout + offsetHeightPos;
        document.getElementById('body-item-list-container').style.height = 'calc(100% - '+totalHeight+'px)';
    }
}
    //end

$('body').on('change', 'select[name="city"]', function(e){
    var id_city = $(this).val();
    $('#district').html("<option></option>").selectpicker('refresh');
    var data = {id_province:id_city};
    if (typeof(csrfData) !== 'undefined') {
        data[csrfData['token_name']] = csrfData['hash'];
    }
    $.post(admin_url+'clients/get_district', data, function(data){
        data = JSON.parse(data);
        var option = "<option></option>";
        $.each(data, function(i,v){
            option += '<option value="'+v.districtid+'">'+v.name+'</option>';
        })
        $('select[name="district"]').html(option).selectpicker('refresh');
    })
})

$('body').on('change', 'select[name="district"]', function(e){
    var id_district = $(this).val();
    var data = {id_district:id_district};
    if (typeof(csrfData) !== 'undefined') {
        data[csrfData['token_name']] = csrfData['hash'];
    }
    $('#ward').html("<option></option>").selectpicker('refresh');
    $.post(admin_url+'clients/get_ward',data, function(data){
        data = JSON.parse(data);
        var option = "<option></option>";
        $.each(data, function(i,v){
            option += '<option value="'+v.wardid+'">'+v.name+'</option>';
        })
        $('select[name="ward"]').html(option).selectpicker('refresh');
    })
})

$('body').on('change', 'select[name="country"]', function(e){
    var id_country = $(this).val();
    $('#city').html("<option></option>").selectpicker('refresh');
    var data = {id_country:id_country};
    if (typeof(csrfData) !== 'undefined') {
        data[csrfData['token_name']] = csrfData['hash'];
    }
    $.post(admin_url+'clients/get_province', data, function(data){
        data = JSON.parse(data);
        var option = "<option></option>";
        $.each(data, function(i,v){
            option += '<option value="'+v.provinceid+'">'+v.name+'</option>';
        })
        $('select[name="city"]').html(option).selectpicker('refresh');
    })
})

function initOrders(id = "")
{
    if($.isNumeric(id))
    {
        var data = {};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        $.post(admin_url+'orders/loadViewOrder/'+id, data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                $('.div_modal_orders').html(data.data)
            }
            else
            {
                alert_float(data.alert_type, data.message);
            }
        })
    }
}

function UpdateLast_messager(id_facebook = "", see = 0, active = true)
{
    if(id_facebook != "")
    {
        var data = {id_facebook : id_facebook, see: see, postData: true};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        $.post(admin_url+'messager/addlast_facebook', data, function(result){
            result = JSON.parse(result);
            if(result.success)
            {
                if(see == 0)
                {
                    $('.content-profile[id_user="'+id_facebook+'"]').find('.sone-unread').find('i').attr('class', 'fa fa-circle').attr('data-original-title', lang_read);
                    var id_senders = $('.content-profile[id_user="'+id_facebook+'"]').attr('id_senders');
                    $('.content-profile[id_user="'+id_facebook+'"]').addClass('profile_unread');
                    if(active == true)
                    {
                        if($('#tab_'+id_senders).hasClass('active'))
                        {
                            UpdateLast_messager(id_facebook, 1)
                        }
                    }
                }
                else
                {
                    $('.content-profile[id_user="'+id_facebook+'"]').find('.sone-unread').find('i').attr('class', 'fa fa-circle-o').attr('data-original-title', lang_unread);
                    $('.content-profile[id_user="'+id_facebook+'"]').removeClass('profile_unread');
                }
            }
        })
    }
}

//load ẩn hiện collapse các phiếu chăm sóc và đơn hàng
$(document).ready(function(){
    $("body").on("hide.bs.collapse",'.jsCare_of.collapse', function(){
        var id = $(this).attr('id');
        $('#list_care_of').find('a[data-target="#'+id+'"]').html('<b>'+lang_more+'</b>');
    });
    $("body").on("show.bs.collapse",'.jsCare_of.collapse', function(){
        var id = $(this).attr('id');
        $('#list_care_of').find('a[data-target="#'+id+'"]').html('<b>'+lang_hide_detail+'</b>');
    });

    $("body").on("hide.bs.collapse",'.collapseOrder.collapse', function(){
        var id = $(this).attr('id-data');
        $('#list_order').find('a[data-target=".'+id+'"]').html('<b>'+lang_more+'</b>');
    });
    $("body").on("show.bs.collapse",'.collapseOrder.collapse', function(){
        var id = $(this).attr('id-data');
        console.log(1);
        $('#list_order').find('a[data-target=".'+id+'"]').html('<b>'+lang_hide_detail+'</b>');
    });

    $("body").on("hide.bs.collapse",'.js-more.collapse', function(){
        var id = $(this).attr('id-data');
        $('#list_advisory').find('a[data-target=".'+id+'"]').html('<b>'+lang_more+'</b>');
    });
    $("body").on("show.bs.collapse",'.js-more.collapse', function(){
        var id = $(this).attr('id-data');
        $('#list_advisory').find('a[data-target=".'+id+'"]').html('<b>'+lang_hide_detail+'</b>');
        console.log($('#list_advisory').find('a[data-target=".'+id+'"]'))
    });
});

// đổi từ time sang ngày tháng năm dd/mm/yyyy
function timeConverter(UNIX_timestamp){
    if(UNIX_timestamp)
    {
        var a = new Date(parseFloat(UNIX_timestamp));
        var months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
        var year = a.getFullYear();
        var month = months[a.getMonth()];
        var date = a.getDate();
        if(date < 10)
        {
            date = '0'+date;
        }
        var hour = a.getHours();
        var min = a.getMinutes();
        var sec = a.getSeconds();
        var time = date + '/' + month + '/' + year + ' ' + hour + ':' + min + ':' + sec ;
        return time;
    }
}

//Kiểm tra xem có phải là số điện thoại hay không
function checkPhone(phone = "") {
    var phone = String(phone);
    var phoneNum = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
    if (phone.match(phoneNum)) {
        return true;
    } else {
        return false;
    }
}

var ListSearchContent = [];

var KeySearch = 0;

function searchContentMessage()
{
    ListSearchContent = [];
    var Valsearch = $('#search_content_message').val();
    var area_active = $('.chat-area-container.active');
    if(area_active.length)
    {
        var content_message = area_active.find('.content-message');
        $.each(content_message, function(i, v){
            var spanContent = $(v).find('span');
            var content = spanContent.text().toLowerCase();
            if(content.search(Valsearch) >= 0)
            {
                spanContent.addClass('bg-yellow');
                ListSearchContent.push($(spanContent));
            }
            else
            {
                spanContent.removeClass('bg-yellow');
            }
        })

        $('.result_search_content').removeClass('hide');
        $('.search-profile').addClass('hide');
        if(ListSearchContent.length > 0)
        {
            $(ListSearchContent[0]).focus();
            KeySearch = 0;
            $('.result_search_content').html('1/' + ListSearchContent.length + ' ' + result_find );
            $('.SearchContentUp').addClass('opacity-05');
            $('.SearchContentDown').removeClass('opacity-05');
            if(ListSearchContent.length == 1)
            {
                $('.SearchContentUp').addClass('opacity-05');
                $('.SearchContentDown').addClass('opacity-05');
            }
        }
        else
        {
            $('.result_search_content').html('0/' + ListSearchContent.length + ' ' + result_find);
            $('.SearchContentUp').addClass('opacity-05');
            $('.SearchContentDown').addClass('opacity-05');
        }

    }
    $('.btn-active-search').addClass('hide');

    $('.btn-end-search').removeClass('hide');
}

$('body').on('click', '.SearchContentDown', function(e)
{
    if($(ListSearchContent[KeySearch+1]).length)
    {
        KeySearch++;
        $('.result_search_content').html( (KeySearch+1)+'/'+ListSearchContent.length + ' ' + result_find);
        var offsetSearch =  $(ListSearchContent[KeySearch]).offset();
        var area_active = $('.chat-area-container.active');
        var HeightConaiterActive = area_active.height();
        var searchTop = offsetSearch.top;
        if(searchTop < 0)
        {
            searchTop = searchTop * (-1);
        }
        $('#chat_content_body').scrollTop(HeightConaiterActive - searchTop - 100);
        $('.SearchContentUp').removeClass('opacity-05');
        if($(ListSearchContent[KeySearch+1]).length)
        {
            $('.SearchContentDown').removeClass('opacity-05');
        }
        else
        {
            $('.SearchContentDown').addClass('opacity-05');

        }
    }
    else
    {
        if($(ListSearchContent[KeySearch+1]).length)
        {
            $('.SearchContentDown').addClass('opacity-05');
        }
    }



})

$('body').on('click', '.SearchContentUp', function(e)
{
    if($(ListSearchContent[KeySearch-1]).length)
    {
        $('.result_search_content').html((KeySearch) + '/' + ListSearchContent.length + ' ' + result_find);
        KeySearch--;
        var offsetSearch = $(ListSearchContent[KeySearch]).offset();
        var area_active = $('.chat-area-container.active');
        var HeightConaiterActive = area_active.height();
        var searchTop = offsetSearch.top;
        if(searchTop < 0)
        {
            searchTop = searchTop * (-1);
        }
        $('#chat_content_body').scrollTop(HeightConaiterActive - searchTop - 100);
        $('.SearchContentDown').removeClass('opacity-05');
        if($(ListSearchContent[KeySearch - 1]).length)
        {
            $('.SearchContentUp').removeClass('opacity-05');
        }
        else
        {
            $('.SearchContentUp').addClass('opacity-05');
        }
    }
    else
    {
        $('.SearchContentUp').addClass('opacity-05');
    }

})

//Hủy search
function RightSearchContent()
{
    $('.result_search_content').text('').addClass('hide');
    $('.search-profile').removeClass('hide');
    $('#search_content_message').val('');
    $('.SearchContentDown').addClass('opacity-05');
    $('.SearchContentUp').addClass('opacity-05');
    $('.content-message').find('span').removeClass('bg-yellow');

    $('.btn-end-search').addClass('hide');
    $('.btn-active-search').addClass('hide');
    $('.close_search').removeClass('hide');
    ListSearchContent = [];
    KeySearch = 0;
}

$('body').on('keyup', '#search_content_message', function(e)
{
    var valThis = $(this).val();
    if(valThis == '')
    {
        $('.btn-active-search').addClass('hide');
        $('.close_search').removeClass('hide');
    }
    else
    {
        $('.btn-active-search').removeClass('hide');
        $('.close_search').addClass('hide');
    }

})

function CloseSearchContentFacebook()
{
    RightSearchContent();
    $('.action-search').addClass('hide');
}

function searchContentFacebook()
{
    $('.action-search').removeClass('hide');
}

$('body').on('click', '.sone-unread', function(e){
    var content_profile = $(this).parents('.content-profile');
    if($(this).find('i').hasClass('fa-circle-o'))
    {
        UpdateLast_messager(content_profile.attr('id_user'), 0, false);
    }
    else
    {
        UpdateLast_messager(content_profile.attr('id_user'), 1, false);
    }
})


function moved_concat_to_lead(type, id_contact)
{
    var data = {type:type, id_contact : id_contact};
    if (typeof(csrfData) !== 'undefined') {
        data[csrfData['token_name']] = csrfData['hash'];
    }
    $.post(admin_url+'messager/create_lead_from_contact', data, function(result){
        result = JSON.parse(result);
        if(result.success)
        {
            var id_facebook = $('#id_facebook').val();
            if(id_facebook)
            {
                varInfoUser(id_facebook);
            }
        }
        alert_float(result.alert_type, result.message);
    })
}

//AddTag cho profine
function AddTagMuals(id_facebook = "", tag_manuals = [])
{
    if(id_facebook && tag_manuals)
    {
        var content_profile_active = $('.content-profile[id_user="'+id_facebook+'"]');
        content_profile_active.find('.tag-muals').html('');
        $.each(tag_manuals, function(iTag, vTag){
            var StrTag = '<span id-href="'+ (base_url + vTag.link) + '" data-toggle="tooltip" data-original-title="'+vTag.fullcode+'" class="class_href label label-default inline-block pointer mtop5 span-tag-muals" data-title="'+(vTag.name)+'" data-color="'+vTag.color+'">' +
                '           <i class="fa fa-circle bg-'+vTag.color+' text-'+vTag.color+'" aria-hidden="true"></i>'+(vTag.name)+
                '       </span>';
            content_profile_active.find('.tag-muals').append(StrTag);
        })
    }
    AddSearchTagMual();
}

//Tổng hợp và add lại tagmual cho search
function AddSearchTagMual()
{
    var DataActive = $.trim($('.LiMuals.active').text());
    TagSearchMuals = {};
    $('.DSearch.LiMuals').remove();
    var span_tag_muals = $('.span-tag-muals');
    $.each(span_tag_muals, function(i, v){
        TagSearchMuals[$(v).attr('data-title')] = {'name' : $(v).attr('data-title'), 'color' : $(v).attr('data-color')};
    })
    if(TagSearchMuals)
    {
        var ulTagMuals = $('.TSearch[id-data="tag"]').parent('li').find('ul.dropdown-menu');
        $.each(TagSearchMuals, function(i, v){
            var active = '';
            if(DataActive)
            {
                active = 'active';
            }
            ulTagMuals.append('<li class="DSearch LiMuals '+active+'">' +
                '                  <a class="DSearchTag Muals">' +
                '                         <i class="fa fa-circle text-'+v.color+'" aria-hidden="true"></i> ' +v.name+
                '                  </a>' +
                '              </li>');
        })

    }
}

function AddphoneSearch(id_facebook, phone)
{
    var data = {id_facebook : id_facebook, phone : phone.toString()};
    if (typeof (csrfData) !== 'undefined') {
        data[csrfData['token_name']] = csrfData['hash'];
    }
    $.post(admin_url+'messager/insertPhoneFacebook', data, function(data){
        $('.content-profile[id_user="'+id_facebook+'"]').attr('phone', data);
    })
}

$('body').on('click', 'span.class_href', function(e){
    var href = $(this).attr('id-href');
    window.open(href, '_blank');
})



//hoàng crm
var inner_popover_template = '<div class="popover" style="width:600px;max-width: 600px;"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"></div></div></div>'; 

$(document).on('click','.menu-receipts',function(e){
    $(this).popover({
        html: true,
        placement: "left",
        trigger: 'click',
        title:'Chi tiết phiếu thu <button class="close"><span aria-hidden="true">&times;</span></button>',
        content: function() {
            return $(this).find('.content-menu').html();
        },
        template: inner_popover_template
    });
});

$(document).on('click','.close',function(e){
    $('.menu-receipts').popover('hide');
});
//end

//hau
$('body').on('click', '.view_payment',function(e){
    var id = $(this).attr('data-id');
    $('#payment_order_data').html('');
            $.get(admin_url + 'orders/payment_order_view/'+id).done(function(response) {
            $('#payment_order_data_view').html(response);
            $('#payment_order_view').modal({show:true,backdrop:'static'});
            init_selectpicker();
            init_datepicker();
            }).fail(function(error) {
            var response = JSON.parse(error.responseText);
            alert_float('danger', response.message);
            }); 
})

$('body').on('hidden.bs.modal', '#payment_order_view', function() {
    $('#payment_order_data_view').html('');
});
//end
//hoàng crm
$('body').on('click', '.more', function(e) {
    $(this).parents('.div_advisory').find('.more').addClass('hide');
    var data_more = $(this).attr('data-more');
    if(data_more == 'more') {
        $(this).parents('.div_advisory').find('.js-more').removeClass('hide');
        $(this).parent().find('b.more[data-more=unmore]').removeClass('hide');
    }
    else {
        $(this).parents('.div_advisory').find('.js-more').addClass('hide');
        $(this).parent().find('b.more[data-more=more]').removeClass('hide');
    }
});
//end
