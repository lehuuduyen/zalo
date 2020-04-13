<?php $CI = &get_instance(); ?>
<?php //echo form_open('messager/detail_customer', array('id' => 'form_action_client', 'class' => 'form_customer')); ?>
    <div class="customer-info col-md-12" id="customer-info" type="client">
        <div class="col-md-12">
            <div class="css-right">
                <div class="img-customer">
                    <div id="img-customer">
                        <?php
                            if(!empty($data->img)){
                                echo '<img src="'.$data->img.'">';
                            }
                            else{
                                echo '<img src="'.base_url('assets/images/user-placeholder.jpg').'">';
                            }
                        ?>
                    </div>
                    <?php $value = !empty($data->id_facebook) ? $data->id_facebook : (!empty($id_facebook) ? $id_facebook : '');?>
                    <?php if(!empty($value)){
                        $alert_type ='success';
                        $type_messs = _l('cong_client');
                    }
                    else
                    {
                        $alert_type ='danger';
                        $type_messs = _l('cong_data_client_new');
                    }?>
                    <div class="ribbon <?=$alert_type?>"> <span><?=$type_messs?></span> </div>
                    <?php $value = !empty($data->id_facebook) ? $data->id_facebook : '';?>
                    <input type="hidden" id="id_facebook" name="id_facebook" id-type="client" id-data="<?=(!empty($data->userid) ? $data->userid : '')?>" value="<?=$value?>">

                    <?php $value = !empty($data->userid) ? $data->userid : '';?>
                    <input type="hidden" id="userid" class="id_object" name="userid" value="<?=$value?>">
                </div>
                <div class="profile-customer text-center">
                    <span id="name-customer-right">
                        <?= !empty($data->name_facebook) ? $data->name_facebook : ''?>
                    </span>
                </div>
            </div>
            <div class="css-left">
                <div>
                    <button class="btn btn-info font10" onclick="ViewProfile(<?=$data->userid?>, 'profile')" type="button">
                        <?=_l('info_client_menu')?>
                    </button>
                </div>

                <?php if(empty($data)){ ?>
                    <div class="action_profile mtop5">
                        <div class="col-md-12 text-center">
                            <button type="reset" class="btn btn-icon btn-danger"><?=_l('cong_reset')?></button>
                            <button type="submit" class="btn btn-icon btn-info"><?=_l('cong_add')?></button>
                        </div>
                        <div class="col-md-12 mtop5 text-center">
                            <button class="btn btn-info mright10 btn_add_data btn-icon" id-data="lead" type="button"><i class="fa fa-arrow-left" aria-hidden="true"></i> <?=_l('cong_add_lead_short')?></button>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="right-info mleft5 mbot5">
        <div class="view_customer">
            <div class="wap-content lbl-wap-content">
                <h5 class="lbl-title-silver"><?=_l('info_client_menu')?></h5>
            </div>
            <div class="wap-content">
                <span class="text-muted lead-field-heading no-mtop col-md-7 row"><?=_l('cong_name_facebook')?>: </span>
                <span class="bold font-medium-xs mbot15">
                    <?=!empty($data->name_facebook) ? $data->name_facebook : ''?>
                </span>
            </div>

            <?php
                $value = !empty($data->fullname) ? $data->fullname : '';
                echo Create_wap_content_input(_l('cong_full_name_client'), 'fullname', $value, $data->userid, 'messager/detail_customer', array('class' => 'form_customer'));
            ?>

            <div class="wap-content">
                <span class="text-muted lead-field-heading no-mtop"><?=_l('cong_name_system')?>: </span>
                <span class="bold font-medium-xs mbot15 span_name_system">
                    <?=!empty($data->name_system) ? $data->name_system : '-'?>
                </span>
            </div>

            <?php
                $value = !empty($data->zcode) ? $data->zcode : '';
                echo Create_wap_content_input(_l('cong_zcode'), 'zcode', $value, $data->userid, 'messager/detail_customer', array('class' => 'form_customer'));
            ?>

            <?php
                $value = !empty($data->link_facebook) ? $data->link_facebook : '';
                echo Create_wap_content_input(_l('cong_link_facebook'), 'link_facebook', $value, $data->userid, 'messager/detail_customer', array('class' => 'form_customer'));
            ?>

            <?php
                $value = !empty($data->gender) ? $data->gender : '';
                $option = [
                    ['id' => 1, 'name' => _l('cong_male')],
                    ['id' => 2, 'name' => _l('cong_female')],
                ];
                echo Create_wap_content_radio(_l('cong__gender'), 'gender', $value, $data->userid, 'messager/detail_customer', array('class' => 'form_customer'), $option);
            ?>

            <?php
                $value = !empty($data->birtday) ? _dt($data->birtday) : '';
                echo Create_wap_content_input(_l('cong_birtday'), 'birtday', $value, $data->userid, 'messager/detail_customer', array('class' => 'form_customer'), 'datetime');
            ?>

            <div class="wap-content">
                <span class="text-muted lead-field-heading no-mtop col-md-7 row"><?=_l('cong_old')?>: </span>
                <span class="bold font-medium-xs mbot15">
                    <?php
                        if(!empty($data->birtday))
                        {
                            $datenow = new DateTime(date("Y-m-d"));
                            $birthday = new DateTime($data->birtday);
                            $year = $datenow->diff($birthday);
                        }
                    ?>
                    <?=!empty($year) ? $year->y : '-'?>
                </span>
            </div>

            <?php
                $religion = get_table_where('tblcombobox_client', ['type' => 'religion']);
                $value = !empty($data->religion) ? $data->religion : ''; // tôn giáo
                echo Create_wap_content_select(_l('cong_religion'), 'religion', $value, $data->userid, 'messager/detail_customer', array('class' => 'form_customer'), $religion);
            ?>


            <div class="wap-content lbl-wap-content">
                <h5 class="lbl-title-silver"><?=_l('info_BT')?></h5>
            </div>

            <?php
                $dt = get_table_where('tblcombobox_client', ['type' => 'dt']);
                $value = !empty($data->dt) ? $data->dt : ''; // DT
                echo Create_wap_content_select(_l('cong_client_dt'), 'dt', $value, $data->userid, 'messager/detail_customer', array('class' => 'form_customer'), $dt);
            ?>
            <?php
                $kt = get_table_where('tblcombobox_client', ['type' => 'kt']);
                $value = !empty($data->kt) ? $data->kt : ''; // kt
                echo Create_wap_content_select(_l('cong_client_kt'), 'kt', $value, $data->userid, 'messager/detail_customer', array('class' => 'form_customer'), $kt);
            ?>



            <div class="wap-content lbl-wap-content">
                <h5 class="lbl-title-silver"><?=_l('cong_info_client_to_system')?></h5>
            </div>

            <div class="wap-content">
                <span class="text-muted lead-field-heading no-mtop col-md-7 row"><?=_l('cong_code_system')?>: </span>
                <span class="bold font-medium-xs mbot15">
                    <?=!empty($data->code_system) ? $data->code_system : ''?>
                </span>
            </div>

            <div class="wap-content">
                <span class="text-muted lead-field-heading no-mtop col-md-7 row"><?=_l('cong__code_lead')?>: </span>
                <span class="bold font-medium-xs mbot15">
                    <?php $get_code_lead = get_table_where('tblleads',array('id'=>$data->leadid),'','row'); ?>
                    <?=!empty($get_code_lead) ? $get_code_lead->prefix_lead.$get_code_lead->code_lead.'-'.$get_code_lead->zcode.'-'.$get_code_lead->code_type : ''?>
                </span>
            </div>

            <div class="wap-content">
                <span class="text-muted lead-field-heading no-mtop col-md-7 row"><?=_l('cong_code_client')?>: </span>
                <span class="bold font-medium-xs mbot15">
                    <?=!empty($data->prefix_client) ? $data->prefix_client : ''?><?=!empty($data->code_client) ? $data->code_client : ''?>-<?=!empty($data->zcode) ? $data->zcode : ''?>-<?=!empty($data->code_type) ? $data->code_type : ''?>
                </span>
            </div>

            <div class="wap-content">
                <span class="text-muted lead-field-heading no-mtop col-md-7 row"><?=_l('cong_code_client_now')?>: </span>
                <span class="bold font-medium-xs mbot15">
                    <?=!empty($data->prefix_client) ? $data->prefix_client : ''?><?=!empty($data->code_client) ? $data->code_client : ''?>-<?=!empty($data->zcode) ? $data->zcode : ''?>-<?=!empty($data->code_type) ? $data->code_type : ''?>
                </span>
            </div>
            
            <div class="wap-content">
                <span class="text-muted lead-field-heading no-mtop col-md-7 row"><?=_l('cong_date_contact')?>: </span>
                <span class="bold font-medium-xs mbot15">
                    <?=!empty($data->date_contact) ? _dt($data->date_contact) : '-'?>
                </span>
            </div>

            <div class="wap-content">
                <span class="text-muted lead-field-heading no-mtop col-md-7 row"><?=_l('cong_staff_create_client')?>: </span>
                <span class="bold font-medium-xs mbot15">
                    <?=!empty($data->addedfrom) ? get_staff_full_name($data->addedfrom) : '-'?>
                </span>
            </div>

            <div class="wap-content">
                <span class="text-muted lead-field-heading no-mtop col-md-7 row"><?=_l('cong_date_create_client')?>: </span>
                <span class="bold font-medium-xs mbot15">
                    <?=!empty($data->datecreated) ? _dt($data->datecreated) : '-'?>
                </span>
            </div>

            <div class="wap-content lbl-wap-content">
                <h5 class="lbl-title-silver"><?=_l('cong_info_contact')?></h5>
            </div>

        
            <?php
                $value = !empty($data->phonenumber) ? $data->phonenumber : '';
                echo Create_wap_content_input(_l('cong_phonenumber'), 'phonenumber', $value, $data->userid, 'messager/detail_customer', array('class' => 'form_customer'));
            ?>

            <?php
                $value = !empty($data->email_client) ? $data->email_client : '';
                echo Create_wap_content_input(_l('cong_email'), 'email_client', $value, $data->userid, 'messager/detail_customer', array('class' => 'form_customer'));
            ?>

            <?php
                $value = !empty($data->address) ? $data->address : '';
                echo Create_wap_content_input(_l('cong_address'), 'address', $value, $data->userid, 'messager/detail_customer', array('class' => 'form_customer'));
            ?>

            <?php
                $CI->db->select('country_id as id, short_name as name');
                $countries = $CI->db->get('tblcountries')->result_array();
                $value = !empty($data->country) ? $data->country : ''; // Quốc gia
                echo Create_wap_content_select(_l('cong_country'), 'country', $value, $data->userid, 'messager/detail_customer', array('class' => 'form_customer'), $countries);
            ?>

            <?php
                $province = [];
                if(!empty($data->country))
                {
                    $CI->db->select('provinceid as id, name');
                    $CI->db->where('countries', $data->country);
                    $province = $CI->db->get('tblprovince')->result_array();
                }
                $value = !empty($data->city) ? $data->city : ''; // Tỉnh thành
                echo Create_wap_content_select(_l('cong_city'), 'city', $value, $data->userid, 'messager/detail_customer', array('class' => 'form_customer'), $province);
            ?>

            <?php
                $district = [];
                if(!empty($data->city))
                {
                    $CI->db->select('districtid as id, name');
                    $CI->db->where('provinceid', $data->city);
                    $district = $CI->db->get('tbldistrict')->result_array();
                }
                $value = !empty($data->district) ? $data->district : ''; // Quận huyệnh
                echo Create_wap_content_select(_l('cong_district'), 'district', $value, $data->userid, 'messager/detail_customer', array('class' => 'form_customer'), $district);
            ?>
            <?php
                $ward = [];
                if(!empty($data->district)) {
                    $CI->db->select('wardid as id, name');
                    $CI->db->where('districtid', $data->district);
                    $ward = $CI->db->get('tblward')->result_array();
                }
                $value = !empty($data->ward) ? $data->ward : ''; // Quận huyệnh
                echo Create_wap_content_select(_l('cong__ward'), 'ward', $value, $data->userid, 'messager/detail_customer', array('class' => 'form_customer'), $ward);
            ?>

            <?php
                $value = !empty($data->company) ? $data->company : '';
                echo Create_wap_content_input(_l('cong_company'), 'company', $value, $data->userid, 'messager/detail_customer', array('class' => 'form_customer'));
            ?>



            <div class="wap-content lbl-wap-content">
                <h5 class="lbl-title-silver"><?=_l('cong_info_marriage')?></h5>
            </div>

            <?php
                $marriage = get_table_where('tblcombobox_client', ['type' => 'marriage']);
                $value = !empty($data->marriage) ? $data->marriage : ''; // Tình trạng hôn nhân
                echo Create_wap_content_select(_l('cong_marriage'), 'marriage', $value, $data->userid, 'messager/detail_customer', array('class' => 'form_customer'), $marriage);
            ?>


            <div class="wap-content lbl-wap-content">
                <h5 class="lbl-title-silver"><?=_l('cong_note_client')?></h5>
            </div>
            <?php
                $value = !empty($data->note) ? $data->note : '';
                echo Create_wap_content_input(_l('cong_note_client'), 'note', $value, $data->userid, 'messager/detail_customer', array('class' => 'form_customer'));
            ?>

        </div>
        <div class="col-md-12 row">
            <?php echo form_open('messager/detail_customer', array('class' => 'form_customer')); ?>
                <div class="spanTag">
                    <?php $tagsCheck = !empty($data->userid) ? GetDataIDTag($data->userid, 'client') : ''?>
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
            <?php echo form_close()?>
        </div>

        <div>

            <?php
            foreach($data->info_group as $kInfo => $vInfo)
            {
                echo '<div class="wap-content lbl-wap-content">
                        <h5 class="lbl-title-silver">'.$vInfo['name'].'</h5>
                    </div>';
                if(!empty($vInfo['detail'])) {
                    foreach ($vInfo['detail'] as $KeyDetail => $ValueDetail) {
                        if($ValueDetail['type_form'] == 'input' || $ValueDetail['type_form'] == 'password' || $ValueDetail['type_form'] == 'date' || $ValueDetail['type_form'] == 'datetime')
                        {
                            $value = !empty($ValueDetail['value']) ? $ValueDetail['value'] : '';
                            echo Create_wap_content_input(
                                $ValueDetail['name'],
                                'info_detail['.$ValueDetail['id'].']',
                                $value,
                                $data->userid,
                                'messager/detail_customer',
                                array('class' => 'form_customer'),
                                $ValueDetail['type_form']
                            );
                        }
                        else if($ValueDetail['type_form'] == 'radio')
                        {
                            $value = !empty($ValueDetail['value']) ? $ValueDetail['value'] : '';
                            echo Create_wap_content_radio(
                                $ValueDetail['name'],
                                'info_detail['.$ValueDetail['id'].']',
                                $value,
                                $data->userid,
                                'messager/detail_customer',
                                array('class' => 'form_customer'),
                                $ValueDetail['detail']
                            );
                        }
                        else if($ValueDetail['type_form'] == 'checkbox')
                        {
                            $value = !empty($ValueDetail['value']) ? explode(',', $ValueDetail['value']) : [];
                            echo Create_wap_content_checkbox(
                                $ValueDetail['name'],
                                'info_detail['.$ValueDetail['id'].']',
                                $value,
                                $data->userid,
                                'messager/detail_customer',
                                array('class' => 'form_customer'),
                                $ValueDetail['detail']
                            );
                        }
                        else if($ValueDetail['type_form'] == 'select multiple')
                        {
                            $value = !empty($ValueDetail['value']) ? explode(',', $ValueDetail['value']) : [];
                            echo Create_wap_content_select_multiple(
                                $ValueDetail['name'],
                                'info_detail['.$ValueDetail['id'].']',
                                $value,
                                $data->userid,
                                'messager/detail_customer',
                                array('class' => 'form_customer'),
                                $ValueDetail['detail']
                            );
                        }
                        else if($ValueDetail['type_form'] == 'select')
                        {
                            $value = !empty($ValueDetail['value']) ? $ValueDetail['value'] : '';
                            echo Create_wap_content_select(
                                $ValueDetail['name'],
                                'info_detail['.$ValueDetail['id'].']',
                                $value,
                                $data->userid,
                                'messager/detail_customer',
                                array('class' => 'form_customer'),
                                $ValueDetail['detail']
                            );
                        }
                    }
                }
            }
            ?>
        </div>

        <div>
            <div class="wap-content lbl-wap-content">
                <h5 class="lbl-title-silver"><?=_l('cong_info_family')?></h5>
            </div>
            <?php
                $contacts = get_table_where('tblcontacts', ['userid' => $data->userid], 'is_primary desc');
                $html_contact = '';
                foreach($contacts as $key => $value)
                {
                    if(!empty($value['id_lead_create']))
                    {
                        $concat_lead = get_table_where('tblleads', ['id' => $value['id_lead_create']], '', 'row');
                        if(!empty($concat_lead)) {?>
                            <div class="wap-content">
                                <span class="text-muted lead-field-heading no-mtop col-md-7 row"><?=_l('cong_code_system')?>: </span>
                                <span class="bold font-medium-xs mbot15">
                                    <?=!empty($concat_lead->code_system) ? $concat_lead->code_system : ''?>
                                </span>
                            </div>
                            <div class="clearfix_C"></div>
                        <?php }
                    }
                    echo Create_wap_content_input(_l('cong_location_family'), '_contact[title]', $value['title'], $value['id'], 'messager/updateContactClient', array('class' => 'form_customer'), 'input');
                    echo Create_wap_content_input(_l('cong_full_name'), '_contact[firstname]', $value['firstname'], $value['id'], 'messager/updateContactClient', array('class' => 'form_customer'), 'input');
                    echo Create_wap_content_input(_l('cong_email'), '_contact[email]', $value['email'], $value['id'], 'messager/updateContactClient', array('class' => 'form_customer'), 'input');
                    echo Create_wap_content_input(_l('cong_birtday'), '_contact[birtday]', _dt($value['birtday']), $value['id'], 'messager/updateContactClient', array('class' => 'form_customer'), 'datetime');
                    echo Create_wap_content_input(_l('cong_phonenumber'), '_contact[phonenumber]', $value['phonenumber'], $value['id'], 'messager/updateContactClient', array('class' => 'form_customer'), 'input');
                    echo Create_wap_content_input(_l('cong_note'), '_contact[note]', $value['note'], $value['id'], 'messager/updateContactClient', array('class' => 'form_customer'), 'input');
	                if(!empty($value['id_lead_create']))
                    {
                        if(!empty($concat_lead)) {
	                        echo Create_wap_content_input(_l('cong_zcode'), '_contact[_zcode]', $value['_zcode'], $value['id'], 'messager/updateContactClient/'.$value['id_lead_create'], ['class' => 'form_customer'], 'input');

	                        $value_dt = !empty($concat_lead->dt) ? $concat_lead->dt : ''; // DT
	                        echo Create_wap_content_select(_l('cong_client_dt'), '_contact[_dt]', $value_dt, $value['id'], 'messager/updateContactClient/'.$value['id_lead_create'], ['class' => 'form_customer'], $dt);

	                        $value_kt = !empty($concat_lead->kt) ? $concat_lead->kt : ''; // kt
	                        echo Create_wap_content_select(_l('cong_client_kt'), '_contact[_kt]', $value_kt, $value['id'], 'messager/updateContactClient/'.$value['id_lead_create'], array('class' => 'form_customer'), $kt);
	                        echo '<div class="wap-content text-center">
                                        <span class="label label-default">
                                            <a onclick="init_lead('.$concat_lead->id.')"> '.$concat_lead->name_system.'</a>
                                        </span>
                                  </div>';
                        }
                        else {
	                        echo '<div class="wap-content text-center">
                                <a class="btn btn-icon btn-default" onclick="moved_concat_to_lead(\'client\', '.$value['id'].')">'._l('create_leads').'</a>
                          </div>';
                        }
                    }
	                else {
		                echo '<div class="wap-content text-center">
                                <a class="btn btn-icon btn-default" onclick="moved_concat_to_lead(\'client\', ' . $value['id'] . ')">' . _l('create_leads') . '</a>
                          </div>';
	                }
	                echo '<hr/>';
                    echo '<p class="mbot30"></p>';
                }
            ?>
            
            
            <div class="view_contact">
               <?php echo form_open(admin_url('messager/addContactClient'), ['id' => 'form_contact_client', 'class' => 'form_lead']) ?>
               <?php $value = (!empty($data->userid) ? $data->userid : ''); ?>
                    <input type="hidden"  name="userid" value="<?=$value?>">
               <?php echo form_close();?>
            </div>
            <div class="text-center add_contact add_contact_message mtop20">
                <a>+ <?=_l('cong_add_contact')?></a>
            </div>
        </div>

        <div>
            <div class="div_advisory panel panel-info mtop15">
                <div class="panel-heading">
                    <?=_l('cong_history_buy_items')?>
                </div>
                <?php
                $userid = !empty($data->userid) ? $data->userid : '';
                $orders = get_product_orders_items_client($userid);
                ?>
                <div class="panel-body">
                    <?php if(!empty($orders)){?>
                        <?php foreach($orders as $key => $value){?>
                            <div>
                                <img class="orders-img pull-left" src="<?=base_url($value->avatar);?>">
                                <div class="pull-left">
                                    <a class="orders-code mleft10" onclick="initOrders(<?=$value->id?>)"><?= $value->full_code ?></a>
                                    <p class="orders-name mleft10 mtop10"><?=$value->name?> (<?=!empty($value->sum_quantity) ? $value->sum_quantity : '0' ?>)</p>
                                </div>
                            </div>
                            <div class="clearfix_C"></div>
                        <?php }?>
                    <?php }?>
                </div>
            </div>
        </div>

    </div>

<!--</form>-->
<?php
if(!empty($data->userid))
{
    $staff_assigned = getAssignedClient($data->userid);
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
        appValidateForm($('.form_customer'), {
            phonenumber: 'required',
            address: 'required',
            email: 'required',
            id_facebook: 'required'
        }, manageAction_client);
        function manageAction_client(form) {
            var button = $('.form_customer').find('button[type="submit"]');
            button.button({loadingText: "<i class='fa fa-spinner fa-spin'></i>"});
            button.button('loading');
            var data = $(form).serialize();
            var url = form.action;
            $.post(url, data).done(function(response) {
                response = JSON.parse(response);
                alert_float(response.alert_type, response.message);
                if (response.success == true) {
                    $('.form_customer').find('.action_profile').addClass('hide');
                    $('.form_customer').find('#update_profile').removeClass('hide');
                    var id_facebook = $('#id_facebook').val();
                    varInfoUser(id_facebook);

                }
            }).always(function() {
                button.button('reset')
            });
            return false;
        }
    })
</script>
<script>
    $(function() {
        $('.profile_staff_assigned').addClass('hide');
        $('#browsers_staff_assigned').selectpicker('val','');
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
        // $('body').find('a[aria-describedby="'+id+'"]').popover('hide');
        $('body').find('a[aria-describedby="'+id+'"]').click();
    })


    var objectInput = {};
        appValidateForm($('#form_contact_client'), objectInput, manageAction_concat);
        function manageAction_concat(form) {
            var button = $('.form_lead').find('button[type="submit"]');
            button.button({loadingText: "<i class='fa fa-spinner fa-spin'></i>"});
            button.button('loading');
            var data = $(form).serialize();
            var url = form.action;
            $.post(url, data).done(function(response) {
                response = JSON.parse(response);
                alert_float(response.alert_type, response.message);
                if (response.success == true) {
                    $('.form_lead').find('.action_profile').addClass('hide');
                    $('.form_lead').find('#update_profile').removeClass('hide');
                    var id_facebook = $('#id_facebook').val();
                    varInfoUser(id_facebook);
                }
            }).always(function() {
                button.button('reset');
            });
            return false;
        }



    var iContact = 0;
    $('.add_contact_message').click(function(e){
        var content = $('<div class="wap-content"></div>');

        var div_input_content = $('<div class="div_input_content col-md-5 row"></div>');

        div_input_content.append('<span class="text-muted lead-field-heading no-mtop viewInput pointer span-title  col-md-7 row"><?=_l('cong_location_family')?></span>');
        div_input_content.append('<input type="input" class="C_text_input " name="contact['+iContact+'][title]" value="">');

        div_input_content.append('<span class="text-muted lead-field-heading no-mtop viewInput pointer span-title  col-md-7 row"><?=_l('cong_full_name')?></span>');
        div_input_content.append('<input type="input" class="C_text_input " name="contact['+iContact+'][firstname]" value="">');

        div_input_content.append('<span class="text-muted lead-field-heading no-mtop viewInput pointer span-title  col-md-7 row"><?=_l('cong_email')?></span>');
        div_input_content.append('<input type="input" class="C_text_input " name="contact['+iContact+'][email]" value="">');

        div_input_content.append('<span class="text-muted lead-field-heading no-mtop viewInput pointer span-title  col-md-7 row"><?=_l('cong_birtday')?></span>');
        div_input_content.append('<input type="input" class="C_text_input datetimepicker" name="contact['+iContact+'][birtday]" value="">');

        div_input_content.append('<span class="text-muted lead-field-heading no-mtop viewInput pointer span-title  col-md-7 row"><?=_l('cong_phonenumber')?></span>');
        div_input_content.append('<input type="input" class="C_text_input " name="contact['+iContact+'][phonenumber]" value="">');

        div_input_content.append('<span class="text-muted lead-field-heading no-mtop viewInput pointer span-title  col-md-7 row"><?=_l('cong_note')?></span>');
        div_input_content.append('<textarea type="input" class="C_text_input " name="contact['+iContact+'][note]" value=""></textarea>');

        div_input_content.append('<p class="mbot10"></p>');

        div_input_content.append('<button type="submit" class="btn btn-info btn-icon mtop5 mbot20 classSubmit"><?=_l('submit')?></button>');
        div_input_content.append('<button type="button" class="btn btn-icon btn-danger mtop5 mbot20 removeDivContact"><?=_l('close')?></button>');
        div_input_content.append('<p class="mbot20"></p>');

        $('.classSubmit').remove();
        content.append(div_input_content);

        $('#form_contact_client').append(content);

        objectInput['contact['+iContact+'][title]'] = 'required';
        objectInput['contact['+iContact+'][firstname]'] = 'required';
        appValidateForm($('#form_contact_client'), objectInput, manageAction_concat);
        init_datepicker();
        iContact++;
    })


    $('body').on('click', '.removeDivContact', function(e){
        var divConcat =  $(this).parents('.wap-content').remove();
    })
</script>