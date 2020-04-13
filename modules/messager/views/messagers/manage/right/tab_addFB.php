<?php //echo form_open('messager/detail_Listfb', array('id' => 'form_action_client', 'class' => 'form_listfb')); ?>
    <div class="customer-info col-md-12" id="customer-info"  type="listfb">
        <div class="col-md-12">
            <div class="css-right">
                <div class="img-customer">
                    <div id="img-customer">
                        <?php
                        if(!empty($data->img))
                        {
                            echo '<img src="'.$data->img.'">';
                        }
                        else
                        {
                            echo '<img class="noneImg" src="'.base_url('assets/images/user-placeholder.jpg').'">';
                        }
                        ?>
                    </div>
                    <?php if(!empty($data)){
                        $alert_type ='info';
                        $type_messs = _l('cong_data_listid_new');
                    }
                    ?>
                    <div class="ribbon <?=$alert_type?>">
                        <span><?=$type_messs?></span>
                    </div>
                    <?php
                        $value = !empty($data->id_facebook) ? $data->id_facebook : (!empty($id_facebook) ? $id_facebook : '');
                    ?>
                    <input type="hidden" id="id_facebook" name="id_facebook" id-type="listfb" id-data="<?=(!empty($data->id) ? $data->id : '')?>" value="<?=$value?>">

                    <?php
                        $value = (!empty($data->id) ? $data->id : '');
                    ?>
                    <input type="hidden" id="id" class="id_object" name="id" value="<?=$value?>">
                </div>
                <div class="profile-customer text-center">
                    <span id="name-customer-right">
                        <?=!empty($data->name_facebook) ? $data->name_facebook : ''?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="right-info mleft5 mbot5">
        <div class="view_customer">
            <div class="wap-content">
                <span class="text-muted lead-field-heading no-mtop"><?=_l('cong_code_inbox')?>: </span>
                <span class="bold font-medium-xs mbot15">
                    <?=!empty($data->prefix) ? $data->prefix : ''?><?=!empty($data->code) ? $data->code : ''?>
                </span>
            </div>
            <?php
                $value = !empty($data->name_facebook) ? $data->name_facebook : '';
                echo Create_wap_content_input(_l('cong_name_facebook'), 'name_facebook', $value, $data->id, 'messager/detail_Listfb', array('class' => 'form_listfb'));
            ?>
            <?php
                $value = !empty($data->name) ? $data->name : '';
                echo Create_wap_content_input(_l('cong_client_fullname'), 'name', $value, $data->id, 'messager/detail_Listfb', array('class' => 'form_listfb'));
            ?>

            <div class="wap-content">
                <span class="text-muted lead-field-heading no-mtop"><?=_l('cong_name_system')?>: </span>
                <span class="bold font-medium-xs mbot15 span_name_system">
                    <?=!empty($data->name_system) ? $data->name_system : '-'?>
                </span>
            </div>


            <?php
                echo Create_wap_content_input(_l('cong_link_facebook'), 'name', '', $data->id, 'messager/detail_Listfb', array('class' => 'form_listfb'));
            ?>

            <?php
                $value = !empty($data->gender) ? $data->gender : '';
                $option = [
                    ['id' => 1, 'name' => _l('cong_male')],
                    ['id' => 2, 'name' => _l('cong_female')],
                ];
                echo Create_wap_content_radio(_l('cong__gender'), 'gender', $value, $data->id, 'messager/detail_Listfb', array('class' => 'form_listfb'), $option);
            ?>

            <?php
            $value = !empty($data->birtday) ? _dt($data->birtday) : '';
            echo Create_wap_content_input(_l('cong_birtday'), 'birtday', $value, $data->id, 'messager/detail_Listfb', array('class' => 'form_listfb'), 'datetime');
            ?>

	        <?php
                $religion = get_table_where('tblcombobox_client', ['type' => 'religion']);
                $value = !empty($data->religion) ? $data->religion : ''; // tôn giáo
                echo Create_wap_content_select(_l('cong_religion'), 'religion', $value, $data->id, 'messager/detail_Listfb', array('class' => 'form_listfb'), $religion);
	        ?>




            <?php
                $value = !empty($data->note) ? _dt($data->note) : '';
                echo Create_wap_content_input(_l('cong_note'), 'note', $value, $data->id, 'messager/detail_Listfb', array('class' => 'form_listfb'));
            ?>
        </div>
    </div>
    <div class="col-md-12">
        <?php echo form_open('messager/detail_Listfb', array('class' => 'form_listfb')); ?>
            <div class="spanTag">
                <?php $tagsCheck = !empty($data->id) ? GetDataIDTag($data->id, 'listfb') : ''?>
                <?php $tagsCheck = explode(',', $tagsCheck)?>
                <label for="tag" class="control-label">
                    <i class="fa fa-tag" aria-hidden="true"></i>
                    <?php echo _l('tags'); ?>
                </label>
                <?php $fullTagFB = get_tagsFB_table(); ?>
                <select id="tag" name="tag[]" multiple style="width: 100%">
                    <?php foreach($fullTagFB as $kTag => $vTag){
                        $selected = "";
                        foreach($tagsCheck as $kC => $vC)
                        {
                            if($vTag['id'] == $vC)
                            {
                                $selected = 'selected';
                                break;
                            }
                        }
                        ?>
                        <option value="<?=$vTag['id']?>" color="<?=$vTag['color']?>" background_color="<?=$vTag['background_color']?>" <?=$selected?>><?=$vTag['name']?></option>
                    <?php } ?>
                </select>
            </div>
        <?php echo form_close();?>
    </div>
<!--</form>-->
<?php
if(!empty($data->id))
{
    $staff_assigned = getAssignedListFb($data->id);
}
?>
<script>
    $(function() {
        $("#tag").select2({
            formatResult: formatTag,
            formatSelection: formatTag,
            escapeMarkup: function(m) { return m; }
        });
        init_datepicker();
        init_selectpicker();
        appValidateForm($('.form_listfb'), {
            phonenumber: 'required',
            address: 'required',
            email: 'required',
            id_facebook: 'required'
        }, manageAction_client);
        function manageAction_client(form) {
            var button = $('.form_listfb').find('button[type="submit"]');
            button.button({loadingText: "<i class='fa fa-spinner fa-spin'></i>"});
            button.button('loading');
            var data = $(form).serialize();
            var url = form.action;
            $.post(url, data).done(function(response) {
                response = JSON.parse(response);
                alert_float(response.alert_type, response.message);
                if (response.success == true) {
                    $('.form_listfb').find('.action_profile').addClass('hide');
                    $('.form_listfb').find('#update_profile').removeClass('hide');
                    var id_facebook = $('#id_facebook').val();
                    varInfoUser(id_facebook);
                }
            }).always(function() {
                button.button('reset');
            });
            return false;
        }
    })


</script>

<script>
    $(function() {
        $('.profile_staff_assigned').addClass('hide');
        $('#browsers_staff_assigned').selectpicker('val',[]);
        <?php
        if(!empty($data)){
            if(!empty($staff_assigned->list_staff)){?>
                $('#browsers_staff_assigned').selectpicker('val', [<?=$staff_assigned->list_staff?>]);
            <?php } ?>
            $('.profile_staff_assigned').removeClass('hide');
        <?php } ?>
    })
    $('body').on('click','.ClosePopover', function(e){
        var id = $(this).parents('.popover').attr('id');
        $('body').find('a[aria-describedby="'+id+'"]').click();
    })
</script>