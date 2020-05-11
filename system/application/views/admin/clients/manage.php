<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>
<style type="text/css">
    .checkbox-templates {
        float: left;
        width: 18%;
        margin-top: 0 !important;
        margin-right: 10px;
        margin-left: 10px;
    }
    #wrapper {
        min-height: unset !important;
    }
    .tags-labels{
        white-space: initial!important;
    }
    .table-clients tbody tr td{
        white-space: inherit;
        min-width: 120px;
    }
    .table-clients tbody tr td:nth-child(1){
        white-space: inherit;
        min-width: 50px;
    }
    .table-clients tbody tr td:nth-child(2){
        white-space: inherit;
        min-width: 50px;
        text-align: center;
    }
    .table-clients tbody tr td:nth-child(3){
        white-space: inherit;
        min-width: 50px;
        text-align: center;
    }
    .table-clients tbody tr td:nth-child(5){
        white-space: inherit;
        min-width: 200px;
    }
    .table-clients tbody tr td:nth-child(4){
        white-space: inherit;
        min-width: 110px;
        text-align: center;
    }
    .table-clients tbody tr td:nth-child(6){
        white-space: inherit;
        min-width: 110px;
    }
</style>
<?php
    $colum_view = [0,1];
    $table_data = array();
    $_table_data = array(
        '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="clients"><label for="mass_select_all"></label></div>',
        array(
            'name' => _l('the_number_sign'),
            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-number')
        ),
        array(
            'name' => _l('cong_image_lead_profile'),
            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-images')
        ),
        array(
            'name' => _l('cong_zcode'),
            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-zcode')
        ),
        array(
            'name' => _l('cong_company_system_lead'),
            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-company')
        ),
        array(
            'name' => _l('representative'),
            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-representative')
        ),
        array(
            'name' => _l('contact_primary'),
            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-primary-contact')
        ),
        array(
            'name' => _l('company_primary_email'),
            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-primary-contact-email')
        ),
        array(
            'name' => _l('clients_list_phone'),
            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-phone')
        ),
        array(
            'name' => _l('customer_active'),
            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-active')
        ),
        array(
            'name' => _l('customer_groups'),
            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-groups')
        ),
        array(
            'name' => _l('date_created'),
            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-date-created')
        ),
        array(
            'name' => _l('client_vat_number'),
            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-vat')
        ),
        array(
            'name' => _l('client_address'),
            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-address')
        ),
        array(
            'name' => _l('cong_note'),
            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-note')
        ),
        array(
            'name' => _l('client_website'),
            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-website')
        ),
        array(
            'name' => _l('cong_date_create_company'),
            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-date_create_company')
        ),
        array(
            'name' => _l('clients_country'),
            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-country')
        ),
        array(
            'name' => _l('cong_client_city'),
            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-city')
        ),
        array(
            'name' => _l('cong_client_district'),
            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-district')
        ),
        array(
            'name' => _l('cong_client_sources'),
            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-sources')
        ),
        array(
            'name' => _l('cong_client_introduction'),
            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-introduction')
        ),
        array(
            'name' => _l('cong_debt_limit'),
            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-debt_limit')
        ),
        array(
            'name' => _l('cong_debt_limit_day'),
            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-debt_limit_day')
        ),
        array(
            'name' => _l('cong_discount'),
            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-discount')
        )
    );
    $custom_fields = get_custom_fields('customers', array('show_on_table' => 1));
    $countTable = count($_table_data);
?>

<div id="wrapper">
    <div class="panel_s mbot10 H_scroll" id="H_scroll">
        <div class="panel-body _buttons">
            <div class="_buttons">
                <span class="bold uppercase fsize18 H_title"><?=$title?></span>
                <div class="btn-group pull-right btn-with-tooltip-group _filter_data" data-toggle="tooltip" data-title="<?php echo _l('filter_by'); ?>">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-filter" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-left" style="width:300px;">
                        <li class="active">
                            <a href="#" data-cview="all" onclick="dt_custom_view('','.table-clients',''); return false;">
                                <?php echo _l('customers_sort_all'); ?>
                            </a>
                        </li>
                        <?php if (get_option('customer_requires_registration_confirmation') == '1' || total_rows(db_prefix() . 'clients', 'registration_confirmed=0') > 0) { ?>
                            <li class="divider"></li>
                            <li>
                                <a href="#" data-cview="requires_registration_confirmation" onclick="dt_custom_view('requires_registration_confirmation','.table-clients','requires_registration_confirmation'); return false;">
                                    <?php echo _l('customer_requires_registration_confirmation'); ?>
                                </a>
                            </li>
                        <?php } ?>
                        <li class="divider"></li>
                        <li>
                            <a href="#" data-cview="my_customers" onclick="dt_custom_view('my_customers','.table-clients','my_customers'); return false;">
                                <?php echo _l('customers_assigned_to_me'); ?>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <?php if (count($groups) > 0) { ?>
                            <li class="dropdown-submenu pull-left groups">
                                <a href="#" tabindex="-1">
                                    <?php echo _l('customer_groups'); ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-left">
                                    <?php foreach ($groups as $group) { ?>
                                        <li>
                                            <a href="#" data-cview="customer_group_<?php echo $group['id']; ?>" onclick="dt_custom_view('customer_group_<?php echo $group['id']; ?>','.table-clients','customer_group_<?php echo $group['id']; ?>'); return false;">
                                                <?php echo $group['name']; ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <div class="clearfix"></div>
                            <li class="divider"></li>
                        <?php } ?>
                        <?php if (count($countries) > 1) { ?>
                            <li class="dropdown-submenu pull-left countries">
                                <a href="#" tabindex="-1"><?php echo _l('clients_country'); ?></a>
                                <ul class="dropdown-menu dropdown-menu-left">
                                    <?php foreach ($countries as $country) { ?>
                                        <li>
                                            <a href="#" data-cview="country_<?php echo $country['country_id']; ?>" onclick="dt_custom_view('country_<?php echo $country['country_id']; ?>','.table-clients','country_<?php echo $country['country_id']; ?>'); return false;">
                                                <?php echo $country['short_name']; ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <div class="clearfix"></div>
                            <li class="divider"></li>
                        <?php } ?>

                        <li class="dropdown-submenu pull-left invoice">
                            <a href="#" tabindex="-1"><?php echo _l('invoices'); ?></a>
                            <ul class="dropdown-menu dropdown-menu-left">
                                <?php foreach ($invoice_statuses as $status) { ?>
                                    <li>
                                        <a href="#" data-cview="invoices_<?php echo $status; ?>" onclick="dt_custom_view('invoices_<?php echo $status; ?>','.table-clients','invoices_<?php echo $status; ?>'); return false;">
                                            <?php echo _l('customer_have_invoices_by', format_invoice_status($status, '', false)); ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <div class="clearfix"></div>
                        <li class="divider"></li>
                        <li class="dropdown-submenu pull-left estimate">
                            <a href="#" tabindex="-1">
                                <?php echo _l('estimates'); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-left">
                                <?php foreach ($estimate_statuses as $status) { ?>
                                    <li>
                                        <a href="#" data-cview="estimates_<?php echo $status; ?>" onclick="dt_custom_view('estimates_<?php echo $status; ?>','.table-clients','estimates_<?php echo $status; ?>'); return false;">
                                            <?php echo _l('customer_have_estimates_by', format_estimate_status($status, '', false)); ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <div class="clearfix"></div>
                        <li class="divider"></li>
                        <li class="dropdown-submenu pull-left project">
                            <a href="#" tabindex="-1">
                                <?php echo _l('projects'); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-left">
                                <?php foreach ($project_statuses as $status) { ?>
                                    <li>
                                        <a href="#" data-cview="projects_<?php echo $status['id']; ?>"
                                           onclick="dt_custom_view('projects_<?php echo $status['id']; ?>','.table-clients','projects_<?php echo $status['id']; ?>'); return false;">
                                            <?php echo _l('customer_have_projects_by', $status['name']); ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <div class="clearfix"></div>
                        <li class="divider"></li>
                        <li class="dropdown-submenu pull-left proposal">
                            <a href="#" tabindex="-1"><?php echo _l('proposals'); ?></a>
                            <ul class="dropdown-menu dropdown-menu-left">
                                <?php foreach ($proposal_statuses as $status) { ?>
                                    <li>
                                        <a href="#" data-cview="proposals_<?php echo $status; ?>"  onclick="dt_custom_view('proposals_<?php echo $status; ?>','.table-clients','proposals_<?php echo $status; ?>'); return false;">
                                            <?php echo _l('customer_have_proposals_by', format_proposal_status($status, '', false)); ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <div class="clearfix"></div>
                        <?php if (count($contract_types) > 0) { ?>
                            <li class="divider"></li>
                            <li class="dropdown-submenu pull-left contract_types">
                                <a href="#" tabindex="-1"><?php echo _l('contract_types'); ?></a>
                                <ul class="dropdown-menu dropdown-menu-left">
                                    <?php foreach ($contract_types as $type) { ?>
                                        <li>
                                            <a href="#" data-cview="contract_type_<?php echo $type['id']; ?>" onclick="dt_custom_view('contract_type_<?php echo $type['id']; ?>','.table-clients','contract_type_<?php echo $type['id']; ?>'); return false;">
                                                <?php echo _l('customer_have_contracts_by_type', $type['name']); ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if (count($customer_admins) > 0 && (has_permission('customers', '', 'create') || has_permission('customers', '', 'edit'))) { ?>
                            <div class="clearfix"></div>
                            <li class="divider"></li>
                            <li class="dropdown-submenu pull-left responsible_admin">
                                <a href="#" tabindex="-1"><?php echo _l('responsible_admin'); ?></a>
                                <ul class="dropdown-menu dropdown-menu-left">
                                    <?php foreach ($customer_admins as $cadmin) { ?>
                                        <li>
                                            <a href="#" data-cview="responsible_admin_<?php echo $cadmin['staff_id']; ?>" onclick="dt_custom_view('responsible_admin_<?php echo $cadmin['staff_id']; ?>','.table-clients','responsible_admin_<?php echo $cadmin['staff_id']; ?>'); return false;">
                                                <?php echo get_staff_full_name($cadmin['staff_id']); ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="btn-group pull-right btn-with-tooltip-group mright5" data-toggle="tooltip" data-title="<?php echo _l('setting_colum'); ?>">
                    <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#infomation_client">
                        <i class="fa fa-area-chart" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="btn-group pull-right btn-with-tooltip-group mright5" data-toggle="tooltip" data-title="<?php echo _l('setting_colum'); ?>">
                    <button class="btn btn-info" data-toggle="collapse" data-target="#templates">
                        <i class="fa fa-cog" aria-hidden="true"></i>
                    </button>
                </div>
                <a href="#" class="btn btn-danger pull-right hidden-xs mright5" data-toggle="tooltip" data-title="<?php echo _l('delete'); ?>" onclick="DeleteList_ch('.table-clients', 'clients/deleteList')" >
                    <?php echo _l('delete'); ?>
                </a>
                <div class="pull-right mright5 H_border">
                    <a class="btn btn-info test H_action_button" data-toggle="modal" data-target="#export_excel_clent">
                        <?php echo _l('Export excel'); ?>
                    </a>
                </div>
                <?php if (has_permission('customers', '', 'create')) { ?>
                    <div class="pull-right mright5 H_border">
                        <a href="<?=admin_url('clients/import_client')?>" class="btn btn-info H_action_button hidden-xs">
                            <?php echo _l('Import excel'); ?>
                        </a>
                    </div>
                <?php } ?>
                <div class="pull-right mright5 H_border">
                    <a class="btn btn-info test H_action_button" data-toggle="collapse" data-target="#search">
                        <?php echo _l('search'); ?>
                    </a>
                </div>
                <?php if (has_permission('customers', '', 'create')) { ?>
                    <div class="pull-right mright5 H_border">
                        <a href="<?php echo admin_url('clients/client'); ?>" class="btn btn-info test H_action_button">
                            <?php echo _l('create_add_new'); ?>
                        </a>
                    </div>
                <?php } ?>
                <div class="clearfix"></div>
            </div>
        </div>
        <div id="templates" class="collapse templates" style="background: #fff;">
            <?php if(!empty($hidden_colum->field))
            {
                $hidden_colum->field = json_decode($hidden_colum->field);
            }?>
            <?php foreach($_table_data as $key => $value){
                if($key > 1){?>
                    <?php
                        $checked = '';
                        if(!empty($hidden_colum->field->{$value['th_attrs']['id']}) || ( empty($hidden_colum->field) && $key <= 8 ))
                        {
                            $colum_view[]= $key;
                            $checked = 'checked';
                        }
                    ?>
                    <div class="checkbox checkbox-primary checkbox-templates">
                        <input type="checkbox" class="field_client" id="field['<?=$value['th_attrs']['id']?>']" name="field['<?=$value['th_attrs']['id']?>']" <?=$checked?>  value="<?=$value['th_attrs']['id']?>">
                        <label for="clients_list_company_show">
                            <?=$value['name']?>
                        </label>
                    </div>
                <?php } ?>
            <?php } ?>
            <?php
                if(!empty($info_group))
                {
                    if(!empty($hidden_colum))
                    {
                        $hidden_colum->group_detail = json_decode($hidden_colum->group_detail);
                    }
                    foreach($info_group as $key => $value)
                    {
                        $countTable++;
                        $info_group[$key]['count'] = ($countTable - 1);
                        $checked = '';
                        if( !empty($hidden_colum->group_detail->{$value['id']}) )
                        {
                            $colum_view[]= ($countTable - 1);
                            $checked = 'checked';
                        }
                        ?>
                        <div class="checkbox checkbox-primary checkbox-templates">
                            <input type="checkbox" class="group_detail" id="<?='group_detail['.$value['id'].']'?>" name="<?='group_detail['.$value['id'].']'?>"  value="<?=$value['id']?>" <?= $checked?>>
                            <label for="<?='group_detail['.$value['id'].']'?>">
                                <?= $value['name'] ?>
                            </label>
                        </div>
                <?php }
                }
            ?>

            <?php
                if(!empty($custom_fields))
                {
                    if(!empty($hidden_colum->field_customer))
                    {
                        $hidden_colum->field_customer = json_decode($hidden_colum->field_customer);
                    }
                    foreach($custom_fields as $key => $value)
                    {
                        $countTable++;
                        $checked = '';
                        $custom_fields[$key]['count'] = ($countTable-1);
                        if( !empty($hidden_colum->field_customer->{$value['id']}) )
                        {
                            $colum_view[]= ($countTable - 1);
                            $checked = 'checked';
                        }
                        ?>
                        <div class="checkbox checkbox-primary checkbox-templates">
                            <input type="checkbox" class="field_customer" id="<?='field_customer['.$value['id'].']'?>" name="<?='field_customer['.$value['id'].']'?>"  value="<?=$value['id']?>" <?= $checked ?>>
                            <label for="<?='group_detail['.$value['id'].']'?>">
                                <?= $value['name'] ?>
                            </label>
                        </div>
                <?php }
                }
            ?>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="_filters _hidden_inputs hidden">
                    <?php
                    echo form_hidden('my_customers');
                    echo form_hidden('requires_registration_confirmation');
                    foreach ($groups as $group) {
                        echo form_hidden('customer_group_' . $group['id']);
                    }
                    foreach ($contract_types as $type) {
                        echo form_hidden('contract_type_' . $type['id']);
                    }
                    foreach ($invoice_statuses as $status) {
                        echo form_hidden('invoices_' . $status);
                    }
                    foreach ($estimate_statuses as $status) {
                        echo form_hidden('estimates_' . $status);
                    }
                    foreach ($project_statuses as $status) {
                        echo form_hidden('projects_' . $status['id']);
                    }
                    foreach ($proposal_statuses as $status) {
                        echo form_hidden('proposals_' . $status);
                    }
                    foreach ($customer_admins as $cadmin) {
                        echo form_hidden('responsible_admin_' . $cadmin['staff_id']);
                    }
                    foreach ($countries as $country) {
                        echo form_hidden('country_' . $country['country_id']);
                    }
                    ?>
                </div>
                <div class="panel_s">
                    <div class="">
                        <?php if (has_permission('customers', '', 'view') || have_assigned_customers()) {
                        $where_summary = '';
                        if (!has_permission('customers', '', 'view')) {
                            $where_summary = ' AND userid IN (SELECT customer_id FROM ' . db_prefix() . 'customer_admins WHERE staff_id=' . get_staff_user_id() . ')';
                        }
                        ?>
                        <div id="height_top">
                            <div id="infomation_client" class="collapse" aria-expanded="false">
                                <div class="row mbot15">
                                    <div class="col-md-12">
                                        <h4 class="no-margin"><?php echo _l('customers_summary'); ?></h4>
                                    </div>
                                    <div class="col-md-2 col-xs-6 border-right">
                                        <h3 class="bold"><?php echo total_rows(db_prefix() . 'clients', ($where_summary != '' ? substr($where_summary, 5) : '')); ?></h3>
                                        <span class="text-dark"><?php echo _l('customers_summary_total'); ?></span>
                                    </div>
                                    <div class="col-md-2 col-xs-6 border-right">
                                        <h3 class="bold">
                                            <?php echo total_rows(db_prefix() . 'clients', 'active=1' . $where_summary); ?>
                                        </h3>
                                        <span class="text-success">
                                            <?php echo _l('active_customers'); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-2 col-xs-6 border-right">
                                        <h3 class="bold">
                                            <?php echo total_rows(db_prefix() . 'clients', 'active=0' . $where_summary); ?>
                                        </h3>
                                        <span class="text-danger">
                                            <?php echo _l('inactive_active_customers'); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-2 col-xs-6 border-right">
                                        <h3 class="bold">
                                            <?php echo total_rows(db_prefix() . 'contacts', 'active=1' . $where_summary); ?>
                                        </h3>
                                        <span class="text-info">
                                            <?php echo _l('customers_summary_active'); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-2  col-xs-6 border-right">
                                        <h3 class="bold">
                                            <?php echo total_rows(db_prefix() . 'contacts', 'active=0' . $where_summary); ?>
                                        </h3>
                                        <span class="text-danger">
                                            <?php echo _l('customers_summary_inactive'); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-2 col-xs-6">
                                        <h3 class="bold">
                                            <?php echo total_rows(db_prefix() . 'contacts', 'last_login LIKE "' . date('Y-m-d') . '%"' . $where_summary); ?>
                                        </h3>
                                        <span class="text-muted">
                                       <?php
                                       $contactsTemplate = '';
                                       if (count($contacts_logged_in_today) > 0) {
                                           foreach ($contacts_logged_in_today as $contact) {
                                               $url = admin_url('clients/client/' . $contact['userid'] . '?contactid=' . $contact['id']);
                                               $fullName = $contact['firstname'] . ' ' . $contact['lastname'];
                                               $dateLoggedIn = _dt($contact['last_login']);
                                               $html = "<a href='$url' target='_blank'>$fullName</a><br /><small>$dateLoggedIn</small><br />";
                                               $contactsTemplate .= htmlspecialchars('<p class="mbot5">' . $html . '</p>');
                                           }
                                           ?>
                                       <?php } ?>
                                       <span <?php if ($contactsTemplate != '') { ?> class="pointer text-has-action" data-toggle="popover" data-title="<?php echo _l('customers_summary_logged_in_today'); ?>" data-html="true" data-content="<?php echo $contactsTemplate; ?>" data-placement="bottom" <?php } ?>>
                                           <?php echo _l('customers_summary_logged_in_today'); ?></span>
                                       </span>
                                    </div>
                                </div>

                                <hr class="hr-panel-heading"/>
                            </div>
                            <?php } ?>
                            <a href="#" data-toggle="modal" data-target="#customers_bulk_action" class="bulk-actions-btn table-btn hide" data-table=".table-clients">
                                <?php echo _l('bulk_actions'); ?>
                            </a>
                            <div class="modal fade bulk_actions" id="customers_bulk_action" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">
                                                <?php echo _l('bulk_actions'); ?>
                                            </h4>
                                        </div>
                                        <div class="modal-body">
                                            <?php if (has_permission('customers', '', 'delete')) { ?>
                                                <div class="checkbox checkbox-danger">
                                                    <input type="checkbox" name="mass_delete" id="mass_delete">
                                                    <label for="mass_delete"><?php echo _l('mass_delete'); ?></label>
                                                </div>
                                                <hr class="mass_delete_separator"/>
                                            <?php } ?>
                                            <div id="bulk_change">
                                                <?php echo render_select('move_to_groups_customers_bulk[]', $groups, array('id', 'name'), 'customer_groups', '', array('multiple' => true), array(), '', '', false); ?>
                                                <p class="text-danger">
                                                    <?php echo _l('bulk_action_customers_groups_warning'); ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                                            <a href="#" class="btn btn-info" onclick="customers_bulk_action(this); return false;">
                                                <?php echo _l('confirm'); ?>
                                            </a>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->
                            <div id="search" class="collapse">
                                <div class="col-md-3">
                                    <?php echo render_select('groups_search', $groups, array('id', 'name'), 'customer_groups', '', array('data-width' => '100%', 'data-none-selected-text' => _l('customer_groups')), array(), 'no-mbot'); ?>
                                </div>
                                <div class="col-md-3">
                                    <?php echo render_select('customer_admins_search', $staff, array('staffid', array('firstname', 'lastname')), 'staff_admin', '', array('data-width' => '100%', 'data-none-selected-text' => _l('staff_admin')), array(), 'no-mbot'); ?>
                                </div>

                                <div class="clearfix"></div>
                                <div class="checkbox">
                                    <input type="checkbox" checked id="exclude_inactive" name="exclude_inactive">
                                    <label for="exclude_inactive"><?php echo _l('exclude_inactive'); ?></label>
                                </div>
                                <div class="clearfix mtop20"></div>
                            </div>
                        </div>

                        <div class="alert_typeTbable"></div>
                        <div class="col-md-12" style="margin-left: -15px">
                            <input type="hidden" id="filterStatus" value=""/>
                            <div data-toggle="btn" class="btn-group mbot15">
                                <button style=" font-size: 11px;" type="button" id="btndata_all" data-toggle="tab" class="btn btn-info btn-search" data-value="all">
                                    <?= _l('leads_all') ?>
                                </button>
                                <?php foreach ($groups as $key => $value) { ?>
                                    <button style="font-size: 11px; color: #fff; background: <?= $value['color'] ?>" type="button" data-toggle="tab" class="btn btn-info btn-search" data-value="<?= $value['id'] ?>">
                                        <?= $value['name'] ?>
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                        <input type="hidden" name="filterStatus">
                        <div id="H_height">
                            <?php
                            foreach ($_table_data as $_t) {
                                array_push($table_data, $_t);
                            }
                            if(!empty($info_group))
                            {
                                foreach($info_group as $key => $value)
                                {
                                    array_push($table_data, [
                                            'name' => $value['name'],
                                            'th_attrs' => ['class' => 'toggleable', 'id' => 'th-'.$value['id']]
                                        ]);
                                }
                            }

                            foreach ($custom_fields as $field) {
                                array_push($table_data, $field['name']);
                            }

                            //bổ xung cột cập nhập bảng giá, chiết khấu
                            array_push($table_data, [
                                'name' => _l('table_set_prices'),
                                'th_attrs' => ['class' => 'toggleable', 'id' => 'th-set_prices']
                            ]);
                            array_push($table_data, [
                                'name' => _l('cong_discount'),
                                'th_attrs' => ['class' => 'toggleable', 'id' => 'th-set_discount']
                            ]);
                            //end
                            $table_data = hooks()->apply_filters('customers_table_columns', $table_data);

                            render_datatable($table_data, 'clients table-bordered', [], [
                                'data-last-order-identifier' => 'customers',
                                'data-default-order' => get_table_last_order('customers'),
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="init-modal-customer">
    
</div>
<?php init_tail(); ?>
<?php
    include_once(APPPATH . 'views/admin/export_excel/export_client.php');
?>
<style type="text/css">

    .table-clients th, .table-clients td { white-space: nowrap; }
</style>
<link rel="stylesheet" type="text/css" href="<?= css('fixdatatable.css') ?>">
<!-- <script type="text/javascript" src="<?= js('datatables/jquery.dataTables.min.js') ?>"></script> -->
<script type="text/javascript" src="<?= js('datatables/dataTables.fixedColumns.min.js') ?>"></script>
<script>
   $('.btn-search').click(function(e) {
      var target = $(e.currentTarget);
      var value = target.attr('data-value');
      $('input[name="filterStatus"]').val(value);
      $('input[name="filterStatus"]').change();
   });
var tAPI;
   var CustomersServerParams = {
      'filterStatus' : '[name="filterStatus"]',
      'groups_search' : '[name="groups_search"]',
      'customer_admins_search' : '[name="customer_admins_search"]',
   };
   $.each($('._hidden_inputs._filters input'),function(){
       CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
   });
   CustomersServerParams['exclude_inactive'] = '[name="exclude_inactive"]:checked';
   tAPI = initDataTableCustom('.table-clients', admin_url+'clients/table', [0], [0], CustomersServerParams,<?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(0,'desc'))); ?>, fixedColumns = {leftColumns: 5, rightColumns: 0});


   var arrayColum = <?=json_encode($colum_view)?>;
   var colum = <?=$countTable - 1 ?>;
   $(function(){

       var FullTh = $('.table-clients thead tr th').length;
       console.log(FullTh);
       for(var i = 2; i < FullTh; i++ )
       {
           tAPI.columns(i).visible(false, false);
       }
       tAPI.columns(arrayColum).visible(true, true);
       //end
       $('input[name="exclude_inactive"]').on('change',function(){
           tAPI.draw('page');
       });
       
      $.each(CustomersServerParams, function(filterIndex, filterItem){
         $('' + filterItem).on('change', function(){
             tAPI.draw('page');
         });
      });
   });
   function DeleteList_ch(ThisTable, href)
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
                    tAPI.draw('page');
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
   function customers_bulk_action(event) {
       var r = confirm(app.lang.confirm_action_prompt);
       if (r == false) {
           return false;
       } else {
           var mass_delete = $('#mass_delete').prop('checked');
           var ids = [];
           var data = {};
           if(mass_delete == false || typeof(mass_delete) == 'undefined'){
               data.groups = $('select[name="move_to_groups_customers_bulk[]"]').selectpicker('val');
               if (data.groups.length == 0) {
                   data.groups = 'remove_all';
               }
           } else {
               data.mass_delete = true;
           }
           var rows = $('.table-clients').find('tbody tr');
           $.each(rows, function() {
               var checkbox = $($(this).find('td').eq(0)).find('input');
               if (checkbox.prop('checked') == true) {
                   ids.push(checkbox.val());
               }
           });
           data.ids = ids;
           $(event).addClass('disabled');
           setTimeout(function(){
             $.post(admin_url + 'clients/bulk_action', data).done(function() {
              window.location.reload();
          });
         },50);
       }
   }
   // Hoàng CRM
   $(document).ready(function() {
       <?php
       if(!empty($_table_data))
       {
           foreach($_table_data as $key => $value)
           {
               if($key > 1)
               {
                   ?>
                       $('input[name="field[\'<?=$value['th_attrs']['id']?>\']"]').change(function() {
                           var data = {};
                           if (typeof(csrfData) !== 'undefined') {
                               data[csrfData['token_name']] = csrfData['hash'];
                           }
                           var input_field = $('.field_client:checked');
                           var field = [];
                           $.each(input_field, function(i, v){
                               var idField = $(v).val();
                               field.push(idField);
                           })
                           data['field'] = field;
                           $.post(admin_url+'clients/hidden_colum', data).done(function(response){
                               response = JSON.parse(response);
                               if(response.success == true) {
                                   if($('input[name="field[\'<?=$value['th_attrs']['id']?>\']"]').prop('checked'))
                                   {
                                        tAPI.columns(<?=$key?>).visible(true, true);
                                   }
                                   else
                                   {
                                       tAPI.columns(<?=$key?>).visible(false, false);
                                   }
                               }
                           });
                       });
                   <?php
               }
           }
       }?>



      //Công làm (Đổ dữ liệu từ bảng groups info ra)
       <?php
       if(!empty($info_group))
       {
               foreach($info_group as $key => $value)
               {
                   ?>
                   $('input[name="group_detail[<?=$value['id']?>]"]').change(function() {
                       var data = {};
                       if (typeof(csrfData) !== 'undefined') {
                           data[csrfData['token_name']] = csrfData['hash'];
                       }
                       var input_group_detail = $('.group_detail:checked');
                       var group_detail = [];
                       $.each(input_group_detail, function(i, v){
                           var idGroup = $(v).val();
                           group_detail.push(idGroup);
                       })
                       data['group_detail'] = group_detail;
                       $.post(admin_url+'clients/hidde_colum_info_client', data).done(function(response){
                           response = JSON.parse(response);
                           if(response.success == true) {
                               if($('input[name="group_detail[<?=$value['id']?>]"]').prop('checked'))
                               {
                                    tAPI.columns(<?=$value['count']?>).visible(true, true);
                               }
                               else
                               {
                                   tAPI.columns(<?=$value['count']?>).visible(false, false);
                               }
                           }
                       });
                   });
               <?php
               }
       }?>

       <?php
       if(!empty($custom_fields))
       {
               foreach($custom_fields as $key => $value)
               {
                   ?>
                   $('input[name="field_customer[<?=$value['id']?>]"]').change(function() {
                       var data = {};
                       if (typeof(csrfData) !== 'undefined') {
                           data[csrfData['token_name']] = csrfData['hash'];
                       }
                       var input_customer_field = $('.field_customer:checked');
                       var field_customer = [];
                       $.each(input_customer_field, function(i, v){
                           var idGroup = $(v).val();
                           field_customer.push(idGroup);
                       })
                       data['field_customer'] = field_customer;
                       $.post(admin_url+'clients/hidde_colum_field_customer', data).done(function(response){
                           response = JSON.parse(response);
                           if(response.success == true) {
                               if($('input[name="field_customer[<?=$value['id']?>]"]').prop('checked'))
                               {
                                    tAPI.columns(<?=$value['count']?>).visible(true, true);
                               }
                               else
                               {
                                   tAPI.columns(<?=$value['count']?>).visible(false, false);
                               }
                           }
                       });
                   });
               <?php
               }
       }?>
       //End
        // hoàng crm bổ xung cột cuối cùng set bảng giá luôn hiển thị
        var number_set_price = <?=$countTable - 1?> + 1;
        tAPI.columns(number_set_price).visible(true, true);

        var number_discount = <?=$countTable - 1?> + 2;
        tAPI.columns(number_discount).visible(true, true);
        // end
    });

   $("body").on("click", "._deleteRow", function (e) {
       if(confirm( app.lang.confirm_action_prompt))
       {
           var table = $(this).parents('table.dataTable');
           var data = {};
           if (typeof(csrfData) !== 'undefined') {
               data[csrfData['token_name']] = csrfData['hash'];
           }
            $.post($(this).attr('href'), data, function(result){
                result = JSON.parse(result);
                if(result.success)
                {
                    tAPI.draw('page');
                }
                alert_float(result.alert_type, result.message);
            })
       }
       return false;
   })

    $('.table-clients').on('draw.dt', function() {
        var total_tr = $('.table-clients').find('tbody').find('tr');
        $.each(total_tr,function(i,v){
            var id_set_price = $('#set_price_'+i).attr('data-idprice');
            $('#set_price_'+i).select2({'allowClear': true});
            $('#set_price_'+i).select2('val',id_set_price);

            var id_discount = $('#set_discount_'+i).attr('data-iddiscount');
            $('#set_discount_'+i).select2({'allowClear': true});
            $('#set_discount_'+i).select2('val',id_discount);
        });
    });

    $(document).on('change','.set_price', function (e) {
        var id_customer = $(this).attr('data-customer');
        var id_set_price = $(this).val();

        var data = {};
        if(!id_set_price) {
            id_set_price = 0;
        }
        data['id_customer'] = id_customer;
        data['id_set_price'] = id_set_price;
        if (typeof(csrfData) !== 'undefined') {
          data[csrfData['token_name']] = csrfData['hash'];
        }
        $.post(admin_url+'set_prices/set_price_to_customer', data).done(function(response){
        });
    });

    $(document).on('change','.set_discount', function (e) {
        var id_customer = $(this).attr('data-customer');
        var id_discount = $(this).val();

        var data = {};
        if(!id_discount) {
            id_discount = 0;
        }
        data['id_customer'] = id_customer;
        data['id_discount'] = id_discount;
        if (typeof(csrfData) !== 'undefined') {
          data[csrfData['token_name']] = csrfData['hash'];
        }
        $.post(admin_url+'discount/set_discount_to_customer', data).done(function(response){
        });
    });
</script>
</body>
</html>
