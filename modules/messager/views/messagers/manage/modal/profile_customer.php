<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button group="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">
                <span>
                    <?=_l('info_client_menu')?>
                </span>
            </h4>
        </div>
        <div class="modal-body">
            <div class="col-md-3 col-xs-12 lead-information-col mbot10">
              <div class="padding0">
                  <h4 class="no-margin font-medium-xs bold backgroundBlue padding10 colorFFF uppercase">
                      <?php echo _l('cong_infomation_client'); ?>
                  </h4>
              </div>
              <div class="wap-content firt">
                  <span class="text-muted lead-field-heading no-mtop bold"><?php echo _l('cong_type_client'); ?>: </span>
                  <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->nameType_client != '' ? $dataView->nameType_client : '-') ?></span>
              </div>
              <div class="wap-content second">
                  <span class="text-muted lead-field-heading"><?php echo _l('cong_client_company_pm'); ?>: </span>
                  <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->company != '' ? $dataView->company : '-') ?></span>
              </div>
              <div class="wap-content firt">
                  <span class="text-muted lead-field-heading"><?php echo _l('cong_client_fullname'); ?>: </span>
                  <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->fullname != '' ? $dataView->fullname : '-') ?></span>
              </div>
              <div class="wap-content second">
                  <span class="text-muted lead-field-heading"><?php echo _l('cong_note'); ?>: </span>
                  <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->note != '' ? $dataView->note : '-') ?></span>
              </div>
              <div class="wap-content firt">
                  <span class="text-muted lead-field-heading"><?php echo _l('cong_date_create_company'); ?>: </span>
                  <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->date_create_company != '' ? _d($dataView->date_create_company) : '-') ?></span>
              </div>
              <div class="wap-content second">
                  <span class="text-muted lead-field-heading"><?php echo _l('client_vat_number'); ?>: </span>
                  <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->vat != '' ? $dataView->vat : '-') ?></span>
              </div>
                <div class="wap-content firt">
                    <span class="text-muted lead-field-heading no-mtop bold"><?php echo _l('facebook'); ?>: </span>
                    <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->facebook != '' ? $dataView->facebook : '-') ?></span>
                </div>
                <div class="wap-content second">
                    <span class="text-muted lead-field-heading"><?php echo _l('client_phonenumber'); ?>: </span>
                    <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->phonenumber != '' ? $dataView->phonenumber : '-') ?></span>
                </div>
                <div class="wap-content firt">
                    <span class="text-muted lead-field-heading"><?php echo _l('cong_client_fax'); ?>: </span>
                    <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->fax != '' ? $dataView->fax : '-') ?></span>
                </div>
                <div class="wap-content second">
                    <span class="text-muted lead-field-heading"><?php echo _l('client_address'); ?>: </span>
                    <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->address != '' ? $dataView->address : '-') ?></span>
                </div>
                <div class="wap-content firt">
                    <span class="text-muted lead-field-heading"><?php echo _l('cong_email_client'); ?>: </span>
                    <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->email_client != '' ? $dataView->email_client : '-') ?></span>
                </div>
                <div class="wap-content second">
                    <span class="text-muted lead-field-heading"><?php echo _l('client_website'); ?>: </span>
                    <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->website != '' ? $dataView->website : '-') ?></span>
                </div>

              <div class="padding0">
                  <h4 class="no-margin font-medium-xs bold backgroundBlue padding10 colorFFF uppercase">
                      <?php echo _l('cong_profile_client'); ?>
                  </h4>
              </div>
              <div class="wap-content firt">
                  <span class="text-muted lead-field-heading no-mtop"><?php echo _l('sex'); ?>: </span>
                  <span class="bold font-medium-xs mbot15"><?php echo(isset($dataView) && $dataView->gender == 1 ? _l('cong_male') : _l('cong_female')) ?></span>
              </div>
              <div class="wap-content second">
                  <span class="text-muted lead-field-heading"><?php echo _l('cong_status_marriage'); ?>: </span>
                  <span class="bold font-medium-xs mbot15"><?php echo(isset($dataView) && $dataView->nameMarriage != '' ? $dataView->nameMarriage : '-') ?></span>
              </div>
              <div class="wap-content firt">
                  <?php $v = get_table_where('tblcombobox_client',array('id'=>$dataView->religion),'','row'); ?>
                  <span class="text-muted lead-field-heading"><?php echo _l('cong_religion'); ?>: </span>
                  <span class="bold font-medium-xs"><?php echo(isset($dataView) && !empty($v) ? $v->name : '-') ?></span>
              </div>
          </div>
          <div class="col-md-3 col-xs-12 lead-information-col mbot10">
              <div class="padding0">
                  <h4 class="no-margin font-medium-xs bold backgroundBlue padding10 colorFFF uppercase">
                      <?php echo _l('cong_infomation_profile'); ?>
                  </h4>
              </div>
              <div class="wap-content firt">
                  <span class="text-muted lead-field-heading no-mtop bold"><?php echo _l('cong_client_birtday'); ?>: </span>
                  <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->birtday != '' ? _d($dataView->birtday) : '-') ?></span>
              </div>
              <div class="wap-content second">
                  <?php $v = get_table_where('tblcombobox_client',array('id'=>$dataView->kt),'','row'); ?>
                  <span class="text-muted lead-field-heading"><?php echo _l('KT'); ?>: </span>
                  <span class="bold font-medium-xs"><?php echo(isset($dataView) && !empty($v) ? $v->name : '-') ?></span>
              </div>
              <div class="wap-content firt">
                  <?php $v = get_table_where('tblcombobox_client',array('id'=>$dataView->dt),'','row'); ?>
                  <span class="text-muted lead-field-heading"><?php echo _l('DT'); ?>: </span>
                  <span class="bold font-medium-xs"><?php echo(isset($dataView) && !empty($v) ? $v->name : '-') ?></span>
              </div>

              <div class="padding0">
                  <h4 class="no-margin font-medium-xs bold backgroundBlue padding10 colorFFF uppercase">
                      <?php echo _l('cong_infomation_address'); ?>
                  </h4>
              </div>
              <div class="wap-content firt">
                  <span class="text-muted lead-field-heading no-mtop bold"><?php echo _l('clients_country'); ?>: </span>
                  <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->short_name_countries != '' ? $dataView->short_name_countries : '-') ?></span>
              </div>
              <div class="wap-content second">
                  <span class="text-muted lead-field-heading"><?php echo _l('cong_client_city'); ?>: </span>
                  <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->name_province != '' ? $dataView->name_province : '-') ?></span>
              </div>
              <div class="wap-content firt">
                  <span class="text-muted lead-field-heading"><?php echo _l('cong_client_district'); ?>: </span>
                  <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->name_district != '' ? $dataView->name_district : '-') ?></span>
              </div>
              <div class="wap-content second">
                  <span class="text-muted lead-field-heading"><?php echo _l('cong_client_ward'); ?>: </span>
                  <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->name_ward != '' ? $dataView->name_ward : '-') ?></span>
              </div>
              <div class="wap-content firt">
                  <span class="text-muted lead-field-heading"><?php echo _l('client_state'); ?>: </span>
                  <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->state != '' ? $dataView->state : '-') ?></span>
              </div>
              <div class="wap-content second">
                  <span class="text-muted lead-field-heading"><?php echo _l('cong_client_area'); ?>: </span>
                  <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->area != '' ? $dataView->area : '-') ?></span>
              </div>
          </div>
          <div class="col-md-3 col-xs-12 lead-information-col mbot10">
              <div class="padding0">
                  <h4 class="no-margin font-medium-xs bold backgroundBlue padding10 colorFFF uppercase">
                      <?php echo _l('cong_infomation_other'); ?>
                  </h4>
              </div>
              <div class="wap-content firt">
                  <span class="text-muted lead-field-heading no-mtop bold"><?php echo _l('cong_client_sources'); ?>: </span>
                  <span class="bold font-medium-xs"><?php echo(!empty($dataView->name_sources) ? $dataView->name_sources : '-') ?></span>
              </div>
              <div class="wap-content second">
                  <?php $v = get_table_where('tblclients',array('userid' => $dataView->introduction),'','row'); ?>
                  <span class="text-muted lead-field-heading"><?php echo _l('cong_client_introduction'); ?>: </span>
                  <span class="bold font-medium-xs"><?php echo(isset($dataView) && !empty($v) ? $v->company : '-') ?></span>
              </div>
              <div class="wap-content firt">
                  <span class="text-muted lead-field-heading"><?php echo _l('client_postal_code'); ?>: </span>
                  <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->zip != '' ? $dataView->zip : '-') ?></span>
              </div>
              <div class="wap-content second">
                  <span class="text-muted lead-field-heading"><?php echo _l('cong_debt_limit'); ?>: </span>
                  <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->debt_limit != '' ? $dataView->debt_limit : '-') ?></span>
              </div>
              <div class="wap-content firt">
                  <span class="text-muted lead-field-heading"><?php echo _l('cong_debt_limit_day'); ?>: </span>
                  <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->debt_limit != '' ? $dataView->debt_limit : '-') ?></span>
              </div>
              <div class="wap-content second">
                  <span class="text-muted lead-field-heading"><?php echo _l('cong_discount'); ?>: </span>
                  <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->discount != '' ? $dataView->discount : '-') ?></span>
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
                  <span class="text-muted lead-field-heading"><?php echo _l('invoice_add_edit_currency'); ?>: </span>
                  <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->name_currencies != '' ? $dataView->name_currencies : '-') ?></span>
              </div>
              <div class="wap-content firt">
                  <span class="text-muted lead-field-heading"><?php echo _l('localization_default_language'); ?>: </span>
                  <span class="bold font-medium-xs"><?php echo(isset($dataView) && $dataView->default_language != '' ? $dataView->default_language : '-') ?></span>
              </div>
          </div>
          <?php  include_once(APPPATH . 'views/admin/clients/group_info_client/groups_info_client_view.php');?>
          <div style="clear: both;"></div>
        </div>
        <div class="modal-footer">
            <button group="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        </div>
    </div>
</div>