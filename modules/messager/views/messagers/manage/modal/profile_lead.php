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
                <div class="lead-info-heading padding0">
                    <h4 class="no-margin font-medium-xs bold backgroundBlue padding10 colorFFF uppercase">
                        <?php echo _l('lead_info'); ?>
                    </h4>
                </div>

                <div class="wap-content second">
                    <span class="text-muted lead-field-heading no-mtop"><?php echo _l('cong_code_lead'); ?>: </span>
                    <span class="bold font-medium-xs mbot15"><?php echo((isset($lead) && !empty($lead->perfix_lead) && !empty($lead->code_lead)) ? $lead->perfix_lead.$lead->code_lead : '-') ?></span>
                </div>
                <div class="wap-content firt">
                    <span class="text-muted lead-field-heading no-mtop bold"><?php echo _l('cong_fullname'); ?>: </span>
                    <span class="bold font-medium-xs lead-name"><?php echo(isset($lead) && $lead->name != '' ? $lead->name : '-') ?></span>
                </div>
                <div class="wap-content second">
                    <span class="text-muted lead-field-heading"><?php echo _l('cong_company_lead'); ?>: </span>
                    <span class="bold font-medium-xs"><?php echo(isset($lead) && $lead->company != '' ? $lead->company : '-') ?></span>
                </div>
                <div class="wap-content firt">
                    <span class="text-muted lead-field-heading"><?php echo _l('cong_email'); ?>: </span>
                    <span class="bold font-medium-xs"><?php echo(isset($lead) && $lead->email != '' ? '<a href="mailto:' . $lead->email . '">' . $lead->email . '</a>' : '-') ?></span>
                </div>
                <div class="wap-content second">
                    <span class="text-muted lead-field-heading"><?php echo _l('lead_add_edit_phonenumber'); ?>: </span>
                    <span class="bold font-medium-xs"><?php echo(isset($lead) && $lead->phonenumber != '' ? '<a href="tel:' . $lead->phonenumber . '">' . $lead->phonenumber . '</a>' : '-') ?></span>
                </div>
                <div class="wap-content firt">
                    <span class="text-muted lead-field-heading"><?php echo _l('cong_date_create_company'); ?>: </span>
                    <span class="bold font-medium-xs"><?php echo(isset($lead) && $lead->date_create_company != '' ? _d($lead->date_create_company) : '-') ?></span>
                </div>
                <div class="wap-content second">
                    <span class="text-muted lead-field-heading"><?php echo _l('cong_religion'); ?>: </span>
                    <span class="bold font-medium-xs">
                        <?php echo(isset($lead) && $lead->religion != 0 ? get_DataCombobox($lead->religion)->name : '-') ?>
                    </span>
                </div>
                <div class="wap-content firt">
                    <span class="text-muted lead-field-heading"><?php echo _l('cong_marriage'); ?>: </span>
                    <span class="bold font-medium-xs">
                        <?php echo(isset($lead) && $lead->marriage != 0 ? get_DataCombobox($lead->marriage)->name : '-') ?>
                    </span>
                </div>
                <div class="wap-content second">
                    <span class="text-muted lead-field-heading">
                        <?php echo _l('cong_client_area'); ?>:
                    </span>
                    <span class="bold font-medium-xs">
                        <?php echo(!empty($lead->area) ? $lead->area : '-') ?>
                    </span>
                </div>
                <div class="wap-content firt">
                    <span class="text-muted lead-field-heading">
                        <?php echo _l('cong_client_dt'); ?>:
                    </span>
                    <span class="bold font-medium-xs">
                        <?php echo(isset($lead) && $lead->dt != 0 ? get_DataCombobox($lead->dt)->name : '-') ?>
                    </span>
                </div>
                <div class="wap-content second">
                    <span class="text-muted lead-field-heading">
                        <?php echo _l('cong_client_kt'); ?>:
                    </span>
                    <span class="bold font-medium-xs">
                        <?php echo(isset($lead) && $lead->kt != 0 ? get_DataCombobox($lead->kt)->name : '-') ?>
                    </span>
                </div>
                <div class="wap-content firt">
                    <span class="text-muted lead-field-heading">
                        <?php echo _l('sex'); ?>:
                    </span>
                    <span class="bold font-medium-xs">
                        <?php echo(isset($lead) ? ($lead->gender == 1 ? _l('cong_male') : ($lead->gender == 2 ? _l('cong_female') : '-')) : '-') ?>
                    </span>
                </div>
                <div class="wap-content second">
                    <span class="text-muted lead-field-heading">
                        <?php echo _l('cong_client_birtday'); ?>:
                    </span>
                    <span class="bold font-medium-xs">
                        <?php echo(isset($lead) ? _d($lead->birtday) : '-') ?>
                    </span>
                </div>
                <div class="wap-content firt">
                    <span class="text-muted lead-field-heading">
                        <?php echo _l('cong_client_facebook'); ?>:
                    </span>
                    <span class="bold font-medium-xs">
                        <?php echo(isset($lead) ? $lead->facebook : '-') ?>
                    </span>
                </div>
                <div class="wap-content second">
                    <span class="text-muted lead-field-heading">
                        <?php echo _l('cong_client_fax'); ?>:
                    </span>
                    <span class="bold font-medium-xs">
                        <?php echo(isset($lead) ? $lead->fax : '-') ?>
                    </span>
                </div>
                <div class="wap-content firt">
                    <span class="text-muted lead-field-heading">
                        <?php echo _l('cong_vat'); ?>:
                    </span>
                    <span class="bold font-medium-xs">
                        <?php echo(isset($lead) ? $lead->vat : '-') ?>
                    </span>
                </div>

                <!--End-->
                <div class="wap-content second">
                    <span class="text-muted lead-field-heading"><?php echo _l('lead_zip'); ?></span>
                    <span class="bold font-medium-xs"><?php echo(isset($lead) && $lead->zip != '' ? $lead->zip : '-') ?></span>
                </div>
            </div>

            <div class="col-md-3 col-xs-12 lead-information-col mbot10">
                <div class="lead-info-heading padding0">
                    <h4 class="no-margin font-medium-xs bold backgroundBlue padding10 colorFFF uppercase">
                        <?php echo _l('lead_general_info'); ?>
                    </h4>
                </div>
                <div class="wap-content second">
                    <span class="text-muted lead-field-heading no-mtop"><?php echo _l('cong_zcode'); ?>: </span>
                    <span class="bold font-medium-xs mbot15"><?php echo(isset($lead) && !empty($lead->zcode) ? $lead->zcode : '-') ?></span>
                </div>

                <div class="wap-content firt">
                    <span class="text-muted lead-field-heading no-mtop"><?php echo _l('cong_type_lead'); ?>: </span>
                    <?php $get_type_client = !empty($lead->type_lead) ? get_type_client($lead->type_lead)->name : '';?>
                    <span class="bold font-medium-xs mbot15"><?php echo(isset($lead) && !empty($get_type_client) ? $get_type_client : '-') ?></span>
                </div>

                <div class="wap-content second">
                    <span class="text-muted lead-field-heading no-mtop"><?php echo _l('lead_add_edit_status'); ?>: </span>
                    <span class="bold font-medium-xs mbot15"><?php echo(isset($lead) && $lead->status_name != '' ? $lead->status_name : '-') ?></span>
                </div>
                <div class="wap-content firt">
                    <span class="text-muted lead-field-heading"><?php echo _l('lead_add_edit_source'); ?>: </span>
                    <span class="bold font-medium-xs mbot15"><?php echo(isset($lead) && $lead->source_name != '' ? $lead->source_name : '-') ?></span>
                </div>
                <div class="wap-content second">
                    <span class="text-muted lead-field-heading"><?php echo _l('lead_website'); ?>: </span>
                    <span class="bold font-medium-xs"><?php echo(isset($lead) && $lead->website != '' ? '<a href="' . maybe_add_http($lead->website) . '" target="_blank">' . $lead->website . '</a>' : '-') ?></span>
                </div>
                <div class="wap-content firt">
                    <span class="text-muted lead-field-heading"><?php echo _l('lead_address'); ?>: </span>
                    <span class="bold font-medium-xs"><?php echo(isset($lead) && $lead->address != '' ? $lead->address : '-') ?></span>
                </div>
                <div class="wap-content second">
                    <span class="text-muted lead-field-heading"><?php echo _l('lead_city'); ?>: </span>
                    <span class="bold font-medium-xs"><?php echo(isset($lead) && $lead->city != '' ? $lead->name_city : '-') ?></span>
                </div>

                <!--Công bổ sung-->
                <div class="wap-content firt">
                    <span class="text-muted lead-field-heading">
                        <?php echo _l('cong_client_district'); ?>:
                    </span>
                    <span class="bold font-medium-xs">
                        <?php echo(isset($lead) && $lead->district != 0 ? get_district($lead->district)->name : '-') ?>
                    </span>
                </div>
                <div class="wap-content second">
                    <span class="text-muted lead-field-heading">
                        <?php echo _l('cong_client_ward'); ?>:
                    </span>
                    <span class="bold font-medium-xs">
                        <?php echo(isset($lead) && $lead->ward != 0 ? get_ward($lead->ward)->name : '-') ?>
                    </span>
                </div>
                <div class="wap-content firt">
                    <span class="text-muted lead-field-heading"><?php echo _l('lead_state'); ?>: </span>
                    <span class="bold font-medium-xs"><?php echo(isset($lead) && $lead->state != '' ? $lead->state : '-') ?></span>
                </div>
                <div class="wap-content second">
                    <span class="text-muted lead-field-heading"><?php echo _l('lead_country'); ?>: </span>
                    <span class="bold font-medium-xs"><?php echo(isset($lead) && $lead->country != 0 ? get_country($lead->country)->short_name : '-') ?></span>
                </div>


                <?php if (get_option('disable_language') == 0) { ?>
                    <div class="wap-content firt">
                        <span class="text-muted lead-field-heading"><?php echo _l('localization_default_language'); ?>: </span>
                        <span class="bold font-medium-xs mbot15"><?php echo(isset($lead) && $lead->default_language != '' ? ucfirst($lead->default_language) : _l('system_default_string')) ?></span>
                    </div>
                <?php } ?>
                <div class="wap-content second">
                    <span class="text-muted lead-field-heading"><?php echo _l('lead_add_edit_assigned'); ?>: </span>
                    <span class="bold font-medium-xs mbot15"><?php echo(isset($lead) && $lead->assigned != 0 ? get_staff_full_name($lead->assigned) : '-') ?></span>
                </div>
                <div class="wap-content firt">
                    <span class="text-muted lead-field-heading"><?php echo _l('tags'); ?>: </span>
                    <span class="bold font-medium-xs mbot10">
                        <?php
                        if (isset($lead)) {
                            $tags = get_tags_in($lead->id, 'lead');
                            if (count($tags) > 0) {
                                echo render_tags($tags);
                                echo '<div class="clearfix"></div>';
                            }
                            else
                            {
                                echo '-';
                            }
                        }
                        ?>
                    </span>
                </div>
                <div class="wap-content second">
                    <span class="text-muted lead-field-heading">
                        <?php echo _l('leads_dt_datecreated'); ?>:
                    </span>
                    <span class="bold font-medium-xs">
                        <?php echo(isset($lead) && $lead->dateadded != '' ? '<span class="text-has-action" data-toggle="tooltip" data-title="' . _dt($lead->dateadded) . '">' . time_ago($lead->dateadded) . '</span>' : '-') ?>
                    </span>
                </div>
                <div class="wap-content firt">
                    <span class="text-muted lead-field-heading"><?php echo _l('leads_dt_last_contact'); ?>: </span>
                    <span class="bold font-medium-xs"><?php echo(isset($lead) && $lead->lastcontact != '' ? '<span class="text-has-action" data-toggle="tooltip" data-title="' . _dt($lead->lastcontact) . '">' . time_ago($lead->lastcontact) . '</span>' : '-') ?></span>
                </div>
                <div class="wap-content second">
                    <span class="text-muted lead-field-heading"><?php echo _l('lead_public'); ?>: </span>
                    <span class="bold font-medium-xs mbot15">
                        <?php
                        if (isset($lead)) {
                            if ($lead->is_public == 1) {
                                echo _l('lead_is_public_yes');
                            } else {
                                echo _l('lead_is_public_no');
                            }
                        }
                        else
                        {
                            echo '-';
                        }
                        ?>
                    </span>
                </div>
                <?php if (isset($lead) && $lead->from_form_id != 0) { ?>
                    <div class="wap-content firt">
                        <span class="text-muted lead-field-heading"><?php echo _l('web_to_lead_form'); ?>: </span>
                        <span class="bold font-medium-xs mbot15"><?php echo $lead->form_data->name; ?></span>
                    </div>
                <?php } ?>
            </div>
            <div class="col-md-3 col-xs-12 lead-information-col mbot10">
                <?php
                    include_once(APPPATH . 'views/admin/leads/group_info/groups_info_lable.php');
                ?>
            </div>
            <div class="col-md-3 col-xs-12 lead-information-col mbot10">
                <?php if (total_rows(db_prefix() . 'customfields', array('fieldto' => 'leads', 'active' => 1)) > 0 && isset($lead)) { ?>
                    <div class="lead-info-heading padding0">
                        <h4 class="no-margin font-medium-xs bold backgroundBlue padding10 colorFFF uppercase">
                            <?php echo _l('custom_fields'); ?>
                        </h4>
                    </div>
                    <?php
                    $custom_fields = get_custom_fields('leads');
                    foreach ($custom_fields as $field) {
                        $value = get_custom_field_value($lead->id, $field['id'], 'leads'); ?>
                        <div class="wap-content firt">
                            <span class="text-muted lead-field-heading no-mtop"><?php echo $field['name']; ?>: </span>
                            <span class="bold font-medium-xs"><?php echo($value != '' ? $value : '-') ?></span>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12">
                <div class="wap-content firt">
                    <span class="text-muted lead-field-heading"><?php echo _l('cong_note'); ?>: </span>
                    <span class="bold font-medium-xs"><?php echo(isset($lead) && $lead->description != '' ? $lead->description : '-') ?></span>
                </div>
            </div>
          <div style="clear: both;"></div>
        </div>
        <div class="modal-footer">
            <button group="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        </div>
    </div>
</div>