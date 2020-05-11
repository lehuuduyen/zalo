<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
    ul.dropdown-menu.inner {
        margin-bottom: 0px!important;
    }
    .pd20{
        padding:20px;
    }
    .border-ds{
        border:1px dashed #d0d0d0!important;
        opacity: .6!important;
        cursor: pointer;
    }
    .add-contacts:hover {
        opacity: 1!important;
    }
    .mborder{
        border-bottom:1px solid #d0d0d0!important;
    }
    .pborder{
        border:1px solid #d0d0d0!important;
    }
    .font-40{
        font-size: 40px;
    }
    a.removeImg{
        display: inherit;
        top: 0;
        float: left;
    }
    .mbot50{
        margin-bottom: 50px;
    }
</style>
<link rel="stylesheet" href="<?=base_url('assets/css/step_by_step.css')?>">
<div class="row mbot50">
   <?php echo form_open_multipart($this->uri->uri_string(),array('class'=>'client-form','autocomplete'=>'off')); ?>
   <div class="additional"></div>
   <div class="col-md-12">
      <div class="horizontal-scrollable-tabs">
         <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
         <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
         <div class="horizontal-tabs">
            <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal" role="tablist">
               <li role="presentation" class="<?php if(!$this->input->get('tab')){echo 'active';}; ?>">
                  <a href="#contact_info" aria-controls="contact_info" role="tab" data-toggle="tab">
                  <?php echo _l( 'customer_profile_details'); ?>
                  </a>
               </li>
                <?php if(isset($client)){ ?>
               <li role="presentation">
                  <a href="#billing_and_shipping" aria-controls="billing_and_shipping" role="tab" data-toggle="tab">
                  <?php echo _l( 'cong_billing_shipping'); ?>
                  </a>
               </li>
               <?php hooks()->do_action('after_customer_billing_and_shipping_tab', isset($client) ? $client : false); ?>

               <li role="presentation">
                  <a href="#customer_admins" aria-controls="customer_admins" role="tab" data-toggle="tab">
                  <?php echo _l( 'customer_admins' ); ?>
                  </a>
               </li>
               <?php hooks()->do_action('after_customer_admins_tab',$client); ?>
               <?php } ?>

               <?php if(isset($client)){ ?>
                <li role="presentation">
                  <a href="#activity_log" aria-controls="activity_log" role="tab" data-toggle="tab">
                  <?php echo _l( 'activity_log_puchases' ); ?>
                  </a>
                </li>
               <?php } ?>
            </ul>
         </div>
      </div>
      <div class="tab-content">
         <?php hooks()->do_action('after_custom_profile_tab_content',isset($client) ? $client : false); ?>
         <div role="tabpanel" class="tab-pane<?php if(!$this->input->get('tab')){echo ' active';}; ?>" id="contact_info">
            <div class="customer-view <?php if(!$view){echo 'hide';}; ?>">
              <?php if(isset($view)) { ?>
                <input type="hidden" name="VoE" value="1">
              <?php } ?>
              <div class="col-md-12">
                <div class="col-md-12 mbot10">
                  <a class="font-medium-xs" onclick="change_type(); return false;"><?=_l('edit')?>
                    <i class="fa fa-pencil-square-o"></i>
                  </a>
                </div>
                  <div class="col-md-6 col-xs-12 lead-information-col mbot10">
                      <div class="padding0">
                          <h4 class="no-margin font-medium-xs bold backgroundBlue padding10 colorFFF uppercase">
                              <?php echo _l('cong_profile_client'); ?>
                          </h4>
                      </div>
                      <div class="wap-content firt">
                          <span class="text-muted lead-field-heading"><?php echo _l('cong_customer_orders'); ?>: </span>
                          <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->company != '' ? $dataView->company : '-') ?></span>
                      </div>
                      <div class="wap-content second">
                          <span class="text-muted lead-field-heading"><?php echo _l('cong_date_create_company'); ?>: </span>
                          <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->date_create_company != '' ? _d($dataView->date_create_company) : '-') ?></span>
                      </div>
                      <div class="wap-content firt">
                          <span class="text-muted lead-field-heading"><?php echo _l('client_vat_number'); ?>: </span>
                          <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->vat != '' ? $dataView->vat : '-') ?></span>
                      </div>
                        <div class="wap-content second">
                            <span class="text-muted lead-field-heading"><?php echo _l('client_phonenumber'); ?>: </span>
                            <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->phonenumber != '' ? $dataView->phonenumber : '-') ?></span>
                        </div>
                        <div class="wap-content firt">
                            <span class="text-muted lead-field-heading"><?php echo _l('cong_email_client'); ?>: </span>
                            <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->email_client != '' ? $dataView->email_client : '-') ?></span>
                        </div>
                        <div class="wap-content second">
                              <span class="text-muted lead-field-heading"><?php echo _l('cong_debt_limit'); ?>: </span>
                              <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->debt_limit != '' ? number_format($dataView->debt_limit) : '-') ?></span>
                          </div>
                          <div class="wap-content firt">
                              <span class="text-muted lead-field-heading"><?php echo _l('cong_debt_limit_day'); ?>: </span>
                              <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->debt_limit != '' ? number_format($dataView->debt_limit) : '-') ?></span>
                          </div>
                          <div class="wap-content firt">
                              <span class="text-muted lead-field-heading"><?php echo _l('Công nợ đầu kỳ'); ?>: </span>
                              <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->debt_begin != '' ? number_format($dataView->debt_begin) : '-') ?></span>
                          </div>
                          <div class="wap-content second">
                              <span class="text-muted lead-field-heading"><?php echo _l('cong_discount'); ?>: </span>
                              <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->discount != '' ? number_format($dataView->discount) : '-') ?></span>
                          </div>
                        <div class="wap-content firt">
                            <span class="text-muted lead-field-heading"><?php echo _l('client_address'); ?>: </span>
                            <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->address != '' ? $dataView->address : '-') ?></span>
                        </div>
                        <div class="wap-content second">
                          <span class="text-muted lead-field-heading no-mtop bold"><?php echo _l('clients_country'); ?>: </span>
                          <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->short_name_countries != '' ? $dataView->short_name_countries : '-') ?></span>
                          </div>
                          <div class="wap-content firt">
                              <span class="text-muted lead-field-heading"><?php echo _l('cong_client_city'); ?>: </span>
                              <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->name_province != '' ? $dataView->name_province : '-') ?></span>
                          </div>
                          <div class="wap-content second">
                              <span class="text-muted lead-field-heading"><?php echo _l('cong_client_district'); ?>: </span>
                              <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->name_district != '' ? $dataView->name_district : '-') ?></span>
                          </div>
                          <div class="wap-content firt">
                              <span class="text-muted lead-field-heading"><?php echo _l('cong_client_ward'); ?>: </span>
                              <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->name_ward != '' ? $dataView->name_ward : '-') ?></span>
                          </div>

                          <div class="wap-content second">
                              <span class="text-muted lead-field-heading no-mtop bold"><?php echo _l('cong_client_sources'); ?>: </span>
                              <span class="bold font-medium-xs"><?php echo(!empty($dataView->name_sources) ? $dataView->name_sources : '-') ?></span>
                          </div>
                        <div class="wap-content firt">
                            <span class="text-muted lead-field-heading"><?php echo _l('client_website'); ?>: </span>
                            <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->website != '' ? $dataView->website : '-') ?></span>
                        </div>

                        <div class="wap-content second">
                            <span class="text-muted lead-field-heading no-mtop bold"><?php echo _l('facebook'); ?>: </span>
                            <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->facebook != '' ? $dataView->facebook : '-') ?></span>
                          </div>

                          <div class="wap-content firt">
                              <span class="text-muted lead-field-heading"><?php echo _l('customer_groups'); ?>: </span>
                              <?php foreach ($dataGroup as $key => $value) {?>
                                <span class="bold font-medium-xs"><?php echo(isset($dataGroup) && $value['name_groups'] != '' ? $value['name_groups'] : '-') ?></span>
                                <?php if(count($dataGroup) > $key+1) { ?>
                                  <span>, </span>
                                <?php } ?>
                              <?php } ?>
                          </div>
                          <div class="wap-content second">
                            <span class="text-muted lead-field-heading"><?php echo _l('tnh_allowed_vat'); ?>: </span>
                            <span class="bold font-medium-xs"><?php echo !empty($dataView->allowed_vat) ? lang('yes') : lang('no') ?></span>
                        </div>
                            <div class="wap-content second">
                           <span class="text-muted lead-field-heading no-mtop"><?php echo _l('sex'); ?>: </span>
                           <span class="bold font-medium-xs mbot15"><?php echo(isset($dataView) && $dataView->gender == 1 ? _l('cong_male') : _l('cong_female')) ?></span>
                            </div>
                            <div class="wap-content firt">
                                <span class="text-muted lead-field-heading"><?php echo _l('cong_note'); ?>: </span>
                                <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->note != '' ? $dataView->note : '-') ?></span>
                            </div>

                  </div>
              <?php  include_once(APPPATH . 'views/admin/clients/group_info_client/groups_info_client_view.php');?>
              </div>
            </div>
            <div class="customer-edit <?php if($view){echo 'hide';}; ?>">
              <div class="row">
                 <div class="col-md-12<?php if(isset($client) && (!is_empty_customer_company($client->userid) && total_rows(db_prefix().'contacts',array('userid'=>$client->userid,'is_primary'=>1)) > 0)) { echo ''; } else {echo ' hide';} ?>" id="client-show-primary-contact-wrapper">
                    <div class="checkbox checkbox-info mbot20 no-mtop">
                       <input type="checkbox" name="show_primary_contact"<?php if(isset($client) && $client->show_primary_contact == 1){echo ' checked';}?> value="1" id="show_primary_contact">
                       <label for="show_primary_contact"><?php echo _l('show_primary_contact',_l('invoices').', '._l('estimates').', '._l('payments').', '._l('credit_notes')); ?></label>
                    </div>
                 </div>
                  <div class="col-md-12">
                    <div class="wap-left">
                      <div class="wap-left-title bold uppercase event_tab title-main active" active-tab="1">
                        <?=_l('cong_infomation_client')?>
                      </div>
                      <div class="wap-left-title bold uppercase event_tab title-main" active-tab="2">
                        <?=_l('cong_infomation_client_contact')?>
                      </div>

                      <?php $check_custom_fields = false; ?>
                      <?php if(total_rows(db_prefix().'customfields',array('fieldto'=>'customers','active'=>1)) > 0 ) { $check_custom_fields=true; ?>
                          <div class="wap-left-title bold uppercase event_tab" active-tab="3">
                            <?=_l('custom_fields')?>
                          </div>
                      <?php } ?>
                      <?php if(!empty($info_group)) { ?>
                        <?php $dem_temp = 4; //4 là số trường cố định + 1 ?>
                        <?php foreach($info_group as $key => $value) { ?>
                          <div class="wap-left-title bold uppercase event_tab" active-tab="<?=$dem_temp?>">
                            <?=$value['name']?>
                          </div>
                        <?php $dem_temp++; ?>
                        <?php } ?>
                      <?php } ?>
                    </div>
                    <div class="wap-right">
                         <!-- Công bổ sung-->
                         <div class="fieldset active" role-fieldset="1">
                            <div class="col-md-12">
                              <div class="align_right">
                                <a type="button" name="next" class="next action-button">Next</a>
                              </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                             <div class="form-group input_upload <?= (!empty($client->client_image) ? 'hide' : '');?>">
                                 <label for="profile_image" class="profile-image"><?=_l('cong_img_client')?></label>
                                 <input type="file" name="client_image" class="form-control" id="client_image">
                             </div>
                             </div>
                             <div class="col-md-6 col-xs-12">
                                <input type="text" class="hide"  id="id_client_ch" value="<?=(isset($client) ? $client->userid : '')?>">
                             <?php
                             if(!empty($client->client_image)){
                                 if (!empty($client->client_image))
                                 {
                                     $profileImagePath = 'uploads/clients/'.$client->userid.'/thumb_'.$client->client_image;
                                     $url = base_url('download/preview_image?path='.$profileImagePath);
                                 }
                                 ?>
                                  <a class="removeImg pointer text-danger" name_img="<?=$client->client_image?>" title="<?=_l('remove_img')?>" title="<?=_l('remove_image')?>">X</a>
                                  <img src="<?=$url?>" class="staff-profile-image-thumb mbot20 imgClient" alt="<?=$client->company?>">
                             <?php }?>
                             </div>
                             <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <?php $value = (isset($client) ? $client->zcode : ''); ?>
                                    <label for="zcode"><?php echo _l('cong_zcode'); ?></label>
                                    <input type="text" name="zcode" id="zcode" class="form-control zcode" value="<?=$value?>" placeholder="<?=_l('system_default_string')?>" >
                                </div>
                            </div>
                             <div class="col-md-6 col-xs-12">
                             <!-- công bổ sung-->
                            <?php $value = ( isset($client) ? $client->company : '');?>
                            <?php $attrs = (isset($client) ? array() : array('autofocus'=>true)); ?>
                            <?php echo render_input( 'company', 'cong_company_system_lead',$value,'text',$attrs); ?>
                            </div>
                            <div class="col-md-6 col-xs-12">
                             <!-- công bổ sung-->
                            <?php $value = ( isset($client) ? $client->representative : '');?>
                            <?php echo render_input( 'representative', 'representative',$value,'text'); ?>
                            </div>
                             <div class="col-md-6 col-xs-12">
                             <?php $value=( isset($client) ? _d($client->date_create_company) : ''); ?>
                             <?php echo render_date_input( 'date_create_company', 'cong_date_create_company',$value); ?>
                             <div id="company_exists_info" class="hide"></div>
                             </div>
                             <div class="col-md-6 col-xs-12">
                             <?php if(get_option('company_requires_vat_number_field') == 1){
                                 $value=( isset($client) ? $client->vat : '');
                                 echo render_input( 'vat', 'client_vat_number',$value);
                             } ?>
                             </div>
                             <div class="col-md-6 col-xs-12">
                             <?php $value=( isset($client) ? $client->phonenumber : ''); ?>
                             <?php echo render_input( 'phonenumber', 'client_phonenumber',$value); ?>
                             </div>
                             <div class="col-md-6 col-xs-12">
                             <?php $value=( isset($client) ? $client->email_client : ''); ?>
                             <?php echo render_input( 'email_client', 'cong_email_client',$value); ?>
                             </div>
                             <div class="hide">
                              <?php $value=( isset($client) ? $client->fax : ''); ?>
                              <?php echo render_input( 'fax', 'cong_client_fax',$value); ?>
                             </div>
                             <div class="col-md-6 col-xs-12">
                             <?php $value=( isset($client) ? $client->address : ''); ?>
                             <?php echo render_textarea( 'address', 'client_address', $value, array('rows' => 3)); ?>
                             </div>
                             <div class="col-md-6 col-xs-12">
                             <?php if((isset($client) && empty($client->website)) || !isset($client)){
                                 $value=( isset($client) ? $client->website : '');
                                 echo render_input( 'website', 'client_website',$value);
                             }
                             else
                             { ?>

                                 <div class="form-group">
                                     <label for="website"><?php echo _l('client_website'); ?></label>
                                     <div class="input-group">
                                         <input type="text" name="website" id="website" value="<?php echo $client->website; ?>" class="form-control">
                                         <div class="input-group-addon">
                                             <span><a href="<?php echo maybe_add_http($client->website); ?>" target="_blank" tabindex="-1"><i class="fa fa-globe"></i></a></span>
                                         </div>
                                     </div>
                                 </div>
                             <?php }?>
                             </div>
                             <div class="hide">
                               <?php
                                $selected = (isset($client->religion) ? $client->religion : '');
                                echo render_select_with_input_group('religion', $religion, ['id', 'name'], 'cong_religion', $selected, '<a href="#" data-toggle="modal" data-type="religion" onclick="SelectType(\'religion\',\'religion\')" data-target="#combobox_client_modal"><i class="fa fa-plus"></i></a>', array('data-actions-box' => true), array(), '', '', false);

                               ?>
                               <?php

                                   $selected = (isset($client->marriage) ? $client->marriage : '');
                                   echo render_select_with_input_group('marriage', $marriage, ['id', 'name'], 'cong_status_marriage', $selected, '<a href="#" data-toggle="modal" data-type="marriage" onclick="SelectType(\'marriage\',\'marriage\')" data-target="#combobox_client_modal"><i class="fa fa-plus"></i></a>', array('data-actions-box' => true), array(), '', '', false);

                               ?>
                             </div>
                             <div class="col-md-6 col-xs-12">
                             <?php $value=( isset($client) ? $client->sources : ''); ?>
                             <?php echo render_select( 'sources', $sources, array('id', 'name'), 'cong_client_sources',$value); ?>
                             </div>
                             <div class="col-md-6 col-xs-12">
                             <?php $value=( isset($client) ? $client->introduction : ''); ?>
                             <?php echo render_select( 'introduction', $list_client, array('userid', 'company'),'cong_client_introduction',$value); ?>
                             </div>
                            <!--Công bổ sung -->
                            <div class="hide">
                              <?php $value=( isset($client) ? $client->zip : ''); ?>
                              <?php echo render_input( 'zip', 'client_postal_code',$value); ?>
                            </div>
                             <div class="col-md-6 col-xs-12">
                             <?php $value=( isset($client) ? number_format($client->debt_limit) : ''); ?>
                             <?php echo render_input( 'debt_limit', 'cong_debt_limit',$value,'text',array('onkeyup'=>'formatNumBerKeyUp(this)')); ?>
                             </div>
                             <div class="col-md-6 col-xs-12">
                             <?php $value=( isset($client) ? number_format($client->debt_limit) : ''); ?>
                             <?php echo render_input( 'debt_limit_day', 'cong_debt_limit_day',$value,'text',array('onkeyup'=>'formatNumBerKeyUp(this)')); ?>
                             </div>
                             <div class="col-md-6 col-xs-12">
                             <?php $value=( isset($client) ? number_format($client->debt_begin) : ''); ?>
                             <?php echo render_input( 'debt_begin', 'Công nợ đầu kỳ',$value,'text',array('onkeyup'=>'formatNumBerKeyUp(this)')); ?>
                             </div>
                             <div class="col-md-6 col-xs-12">
                             <?php $value=( isset($client) ? number_format($client->discount) : ''); ?>
                             <?php echo render_input( 'discount', 'cong_discount',$value,'text',array('onkeyup'=>'formatNumBerKeyUp(this)')); ?>
                             </div>
                             <div class="col-md-6 col-xs-12">
                             <!--end công bổ sung-->
                             <?php
                             $selected = array();
                             if(isset($customer_groups)){
                                 foreach($customer_groups as $group){
                                     array_push($selected,$group['groupid']);
                                 }
                             }
                             if(is_admin() || get_option('staff_members_create_inline_customer_groups') == '1'){
                                 echo render_select_with_input_group('groups_in[]',$groups,array('id','name'),'customer_groups',$selected,'<a href="#" data-toggle="modal" data-target="#customer_group_modal"><i class="fa fa-plus"></i></a>',array('multiple'=>true,'data-actions-box'=>true),array(),'','',false);
                             } else {
                                 echo render_select('groups_in[]',$groups,array('id','name'),'customer_groups',$selected,array('multiple'=>true, 'data-actions-box'=>true),array(),'','',false);
                             }
                             ?>
                             </div>
                             <div class="hide">
                               <?php if(get_option('disable_language') == 0){ ?>
                                   <div class="form-group select-placeholder">
                                       <label for="default_language" class="control-label"><?php echo _l('localization_default_language'); ?>
                                       </label>
                                       <select name="default_language" id="default_language" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                           <option value=""><?php echo _l('system_default_string'); ?></option>
                                           <?php foreach(list_folders(APPPATH .'language') as $language){
                                               $selected = '';
                                               if(isset($client)){
                                                   if($client->default_language == $language){
                                                       $selected = 'selected';
                                                   }
                                               }
                                               ?>
                                               <option value="<?php echo $language; ?>" <?php echo $selected; ?>><?php echo ucfirst($language); ?></option>
                                           <?php } ?>
                                       </select>
                                   </div>
                               <?php } ?>
                             </div>
                             <div class="col-md-6 col-xs-12">
                            <?php $countries= get_all_countries();
                             $customer_default_country = get_option('customer_default_country');
                             $selected =( isset($client) ? $client->country : $customer_default_country);
                             echo render_select( 'country',$countries,array( 'country_id',array( 'short_name')), 'clients_country',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
                            ?>
                            </div>
                             <div class="col-md-6 col-xs-12">

                            <?php $value=( isset($client) ? $client->city : ''); ?>
                            <?php echo render_select( 'city', $province, array('provinceid', 'name'), 'cong_client_city',$value); ?>
                            </div>
                             <div class="col-md-6 col-xs-12">

                            <?php $value=( isset($client) ? $client->district : ''); ?>
                            <?php echo render_select( 'district', $district, array('districtid', 'name'), 'cong_client_district',$value); ?>
                            </div>
                             <div class="col-md-6 col-xs-12">

                            <?php $value=( isset($client) ? $client->ward : ''); ?>
                            <?php echo render_select( 'ward', $ward, array('wardid', 'name'), 'cong_client_ward',$value); ?>
                            </div>
                             <div class="col-md-6 col-xs-12">
                            <?php $value=( isset($client) ? $client->facebook : ''); ?>
                             <?php echo render_input( 'facebook', 'facebook',$value); ?>
                             </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="checkbox">
                                    <input type="checkbox" id="allowed_vat" name="allowed_vat" <?= !empty($lead) ? 'checked' : '' ?> value="1">
                                    <label for="allowed_vat"><?=_l('tnh_allowed_vat')?></label>
                                </div>
                            </div>
                             <div class="col-md-6 col-xs-12">
                             <label class="control-label"><?=_l('sex')?></label>
                             <div class="clearfix"></div>
                             <div class="col-md-6">
                                 <div class="radio">
                                     <input type="radio" id="gender_male" name="gender" value="1" <?=( ((isset($client) && $client->gender == 1) || empty($client)) ? 'checked' : '')?>>
                                     <label for="gender_male"><?=_l('cong_male')?></label>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="radio">
                                     <input type="radio" id="gender_female" name="gender" value="2" <?=( (isset($client) && $client->gender == 2) ? 'checked' : '')?>>
                                     <label for="gender_female"><?=_l('cong_female')?></label>
                                 </div>
                             </div>
                             </div>
                             <div class="col-md-6 col-xs-12">
                            <?php $value=( isset($client) ? $client->note : ''); ?>
                             <?php echo render_textarea( 'note', 'cong_note',$value, array('rows' => 3)); ?>
                             </div>
                             <div class="col-md-12 center">
                                 <?php $vip_rating = (isset($client) ? $client->vip_rating : '0'); ?>
                                 <div class="text-center" id="div_rating_client">
                                     <h5><?=_l('cong_vip_rating')?></h5>
                                     <span class="pointer fa fa-star rating_client <?= (1 <= $vip_rating ? 'checked' : '')?>" id-star="1" title="<?=_l('cong_1_start')?>"></span>
                                     <span class="pointer fa fa-star rating_client <?= (2 <= $vip_rating ? 'checked' : '')?>" id-star="2" title="<?=_l('cong_2_start')?>"></span>
                                     <span class="pointer fa fa-star rating_client <?= (3 <= $vip_rating ? 'checked' : '')?>" id-star="3" title="<?=_l('cong_3_start')?>"></span>
                                     <span class="pointer fa fa-star rating_client <?= (4 <= $vip_rating ? 'checked' : '')?>" id-star="4" title="<?=_l('cong_4_start')?>"></span>
                                     <span class="pointer fa fa-star rating_client <?= (5 <= $vip_rating ? 'checked' : '')?>" id-star="5" title="<?=_l('cong_5_start')?>"></span>
                                     <input type="hidden" name="vip_rating" id="vip_rating" value="<?=$vip_rating?>"/>
                                 </div>
                             </div>
                             <div class="clearfix"></div>

                             <!--end công bổ sung-->
                         </div>

                         <div class="fieldset" role-fieldset="2">
                            <div class="col-md-12">
                              <div class="align_right">
                                <a type="button" name="previous" class="previous action-button">Previous</a>
                                <a type="button" name="next" class="next action-button" <?=($check_custom_fields == false) ? 'data-stt="2"' : ''?>>Next</a>
                              </div>
                            </div>
                            <div>
                                <div id="div_contacts">
                                    <?php if(isset($client->contacts)){ $i = 0;?>
                                        <?php foreach($client->contacts as $key => $value){?>
                                            <div class="col-md-6 items_contact">
                                                <h5 class="mtop20"><?=_l('cong_contacts')?></h5>
                                                <p class="mborder"></p>
                                                <div class="pborder">
                                                    <div class="text-right">
                                                        <a class="remove_contact_panel pointer text-right text-danger" title="Xóa">
                                                            <i class="fa fa-trash gf-icon-hover"></i>
                                                        </a>
                                                    </div>
                                                    <div class="col-md-6 mtop10">
                                                        <input type="hidden" id="contacts[<?=$i?>][id]" name="contacts[<?=$i?>][id]" value="<?=$value['id']?>"/>
                                                        <div class="form-group" app-field-wrapper="contacts[<?=$i?>][firstname]">
                                                            <label for="contacts[<?=$i?>][firstname]" class="control-label"> <?=_l('cong_last_firstname')?></label>
                                                            <input type="text" name="contacts[<?=$i?>][firstname]" id="contacts[<?=$i?>][firstname]"  tabindex=<?=(1*($i+1))?>  class="form-control" autofocus="1" value="<?=$value['firstname']?>">
                                                        </div>

                                                        <div class="form-group" app-field-wrapper="contacts[<?=$i?>][title]">
                                                            <label for="contacts[<?=$i?>][title]" class="control-label"> <?=_l('cong_title')?></label>
                                                            <input type="text" name="contacts[<?=$i?>][title]" id="contacts[<?=$i?>][title]" tabindex=<?=(3*($i+1))?> class="form-control" autofocus="1" value="<?=$value['title']?>">
                                                        </div>
                                                        <div class="form-group" app-field-wrapper="contacts[<?=$i?>][phonenumber]">
                                                            <label for="contacts[<?=$i?>][phonenumber]" class="control-label"> <?=_l('cong_phonenumber')?></label>
                                                            <input type="text" name="contacts[<?=$i?>][phonenumber]" id="contacts[<?=$i?>][phonenumber]" tabindex=<?=(5*($i+1))?> class="form-control" autofocus="1" value="<?=$value['phonenumber']?>">
                                                        </div>

                                                        <div class="client_password_set_wrapper">
                                                            <label for="password" class="control-label">
                                                                <?=_l('cong_password')?>
                                                            </label>
                                                            <div class="input-group">
                                                                <input type="password" class="form-control password" name="contacts[<?=$i?>][password]" autocomplete="false">
                                                                <span class="input-group-addon">
                                                                    <a href="#" class="show_password" onclick="showPassword('contacts[<?=$i?>][password]'); return false;"><i class="fa fa-eye"></i></a>
                                                                </span>
                                                                <span class="input-group-addon">
                                                                    <a href="#" class="generate_password" onclick="generatePasswordContact(this);return false;"><i class="fa fa-refresh"></i></a>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="checkbox checkbox-primary">
                                                            <input type="checkbox" id="contacts[<?=$i?>][is_primary]" class="is_primary" name="contacts[<?=$i?>][is_primary]" <?= !empty($value['is_primary']) ? 'checked' : '' ?> value="1">
                                                            <label for="contacts[<?=$i?>][is_primary]" data-toggle="tooltip"><?=_l('cong_contact_primary')?></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mtop10">
                                                        <div class="form-group" app-field-wrapper="contacts[<?=$i?>][email]">
                                                            <label for="contacts[<?=$i?>][email]" class="control-label"> <?=_l('cong_email')?></label>
                                                            <input type="text" id="contacts[<?=$i?>][email]" name="contacts[<?=$i?>][email]" tabindex=<?=(4*($i+1))?>  class="form-control" autofocus="1" value="<?=$value['email']?>">
                                                        </div>
                                                        <div class="form-group" app-field-wrapper="contacts[<?=$i?>][birtday]">
                                                            <label for="contacts[<?=$i?>][birtday]" class="control-label"> <?=_l('cong_birtday')?></label>
                                                            <div class="input-group date">
                                                                <input type="text" id="contacts[<?=$i?>][birtday]" name="contacts[<?=$i?>][birtday]"   class="datepicker form-control" tabindex="<?=(4*($i+1))?>"  autofocus="1" value="<?=!empty($value['birtday']) ? _d($value['birtday']) : ''?>">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-calendar calendar-icon"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group" app-field-wrapper="contacts[<?=$i?>][note]">
                                                            <label for="contacts[<?=$i?>][note]" class="control-label">Ghi chú</label>
                                                            <textarea id="contacts[<?=$i?>][note]" name="contacts[<?=$i?>][note]" tabindex=<?=(6*($i+1))?> class="form-control" rows="4"><?=$value['note']?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                            <?php
                                                $__is_required_client[$i] = true;
                                            ?>
                                        <?php ++$i; }?>
                                    <?php }?>
                                </div>
                                <div class="pd20 mtop45 border-ds col-md-6 offset-md-6 text-center add-contacts">
                                    <div class="col-md-12 no-padd">
                                        <i class="lnr lnr-users font-40"></i>
                                    </div>
                                    <a>
                                        <i class="gicon-plus mr5 mt3"></i><?=_l('cong_add_contacts')?>
                                    </a>
                                </div>
                            </div>
                         </div>

                         <!-- công bổ sung-->

                        <div class="fieldset" role-fieldset="3">
                            <div class="col-md-12">
                                <div class="align_right">
                                    <a type="button" name="previous" class="previous action-button">Previous</a>
                                    <a type="button" name="next" class="next action-button">Next</a>
                                </div>
                            </div>
                            <?php $rel_id = (isset($client) ? $client->userid : false); ?>
                            <?php echo render_custom_fields('customers', $rel_id); ?>
                            <div class="clearfix"></div>
                        </div>
                      <?php  include_once(APPPATH . 'views/admin/clients/group_info_client/groups_info_client.php');?>
                    </div>
                  </div>
              </div>
            </div>
         </div>
         <?php if(isset($client)){ ?>
         <div role="tabpanel" class="tab-pane" id="customer_admins">
            <?php if (has_permission('customers', '', 'create') || has_permission('customers', '', 'edit')) { ?>
            <a href="#" data-toggle="modal" data-target="#customer_admins_assign" class="btn btn-info mbot30"><?php echo _l('assign_admin'); ?></a>
            <?php } ?>
            <table class="table dt-table">
               <thead>
                  <tr>
                     <th><?php echo _l('staff_member'); ?></th>
                     <th><?php echo _l('customer_admin_date_assigned'); ?></th>
                     <?php if(has_permission('customers','','create') || has_permission('customers','','edit')){ ?>
                     <th><?php echo _l('options'); ?></th>
                     <?php } ?>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach($customer_admins as $c_admin){ ?>
                  <tr>
                     <td><a href="<?php echo admin_url('profile/'.$c_admin['staff_id']); ?>">
                        <?php echo staff_profile_image($c_admin['staff_id'], array(
                           'staff-profile-image-small',
                           'mright5'
                           ));
                           echo get_staff_full_name($c_admin['staff_id']); ?></a>
                     </td>
                     <td data-order="<?php echo $c_admin['date_assigned']; ?>"><?php echo _dt($c_admin['date_assigned']); ?></td>
                     <?php if(has_permission('customers','','create') || has_permission('customers','','edit')){ ?>
                     <td>
                        <a href="<?php echo admin_url('clients/delete_customer_admin/'.$client->userid.'/'.$c_admin['staff_id']); ?>" class="btn btn-danger _delete btn-icon"><i class="fa fa-remove"></i></a>
                     </td>
                     <?php } ?>
                  </tr>
                  <?php } ?>
               </tbody>
            </table>
         </div>
         <?php } ?>


          <!-- địa chỉ thanh toán nhận hàng backup-->
         <div role="tabpanel" class="tab-pane" id="billing_and_shipping">
            <div class="row">
               <button class="btn btn-info mbot20" onclick="ChangeShippingClient('')" type="button"><?=_l('add_sippling')?></button>
               <div class="clearfix"></div>
               <div class="col-md-12">
                  <div class="row">
                      <?php render_datatable(array(
                          _l('cong_stt'),
                          _l('cong_shipping'),
                          _l('ch_contact_shiping'),
                          _l('ch_elivery_area'),
                          _l('cong_name_shipping'),
                          _l('cong_phone'),
                          _l('shipping_address'),
                          _l('Quận huyện'),
                          _l('cong_address_primary'),
                      ),'shipping_client'); ?>
                  </div>
               </div>
            </div>
         </div>
          <!-- end địa chỉ thanh toán nhận hàng backup-->

          <!-- activity log-->
          <div role="tabpanel" class="tab-pane" id="activity_log">
            <div class="row">
              <div class="col-md-12">
                  <div class="activity-container">
                    <?php foreach ($dataLog as $key => $value) { ?>
                      <div class="feed-item">
                          <div class="activity-text">
                              <?= staff_profile_image($value['staff_id'], array('staff-profile-image-small'), 'small'); ?> <?= get_staff_full_name($value['staff_id']); ?>
                          </div>
                          <div class="activity-time">
                              <?= time_ago($value['date']) ?> <span class="activity-module"><?=_l($value['table_obj'])?></span>
                          </div>
                          <div>
                              <?=$value['content']?>
                          </div>
                      </div>
                    <?php } ?>
                  </div>
               </div>
            </div>
          </div>
          <!-- end -->

      </div>
   </div>
   <?php echo form_close(); ?>
</div>
<?php if(isset($client)){ ?>
<?php if (has_permission('customers', '', 'create') || has_permission('customers', '', 'edit')) { ?>
<div class="modal fade" id="customer_admins_assign" tabindex="-1" role="dialog">
   <div class="modal-dialog">
      <?php echo form_open(admin_url('clients/assign_admins/'.$client->userid)); ?>
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo _l('assign_admin'); ?></h4>
         </div>
         <div class="modal-body">
            <?php
               $selected = array();
               foreach($customer_admins as $c_admin){
                  array_push($selected,$c_admin['staff_id']);
               }
               echo render_select('customer_admins[]', $staff, array('staffid', array('firstname','lastname')), '', $selected, array('multiple' => true), array(), '', '', false); ?>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
         </div>
      </div>
      <!-- /.modal-content -->
      <?php echo form_close(); ?>
   </div>
   <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<?php } ?>
<?php } ?>
<?php $this->load->view('admin/clients/client_group'); ?>
<?php $this->load->view('admin/clients/modals/type_client'); ?>
<?php $this->load->view('admin/clients/modals/combobox_modal'); ?>
<?php $this->load->view('admin/clients/shipping_client'); ?>
<script src="<?=base_url('assets/js/step_by_step.js')?>"></script>
<script>

    var _is_required_client = <?= !empty($__is_required_client) ? json_encode($__is_required_client) : '[]'?>;
</script>