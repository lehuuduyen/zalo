<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="customer_profile">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if (isset($client) && $client->registration_confirmed == 0 && is_admin()) { ?>
                    <div class="alert alert-warning">
                        <?php echo _l('customer_requires_registration_confirmation'); ?>
                        <br/>
                        <a href="<?php echo admin_url('clients/confirm_registration/' . $client->userid); ?>"><?php echo _l('confirm_registration'); ?></a>
                    </div>
                <?php } else if (isset($client) && $client->active == 0 && $client->registration_confirmed == 1) { ?>
                    <div class="alert alert-warning">
                        <?php echo _l('customer_inactive_message'); ?>
                        <br/>
                        <a href="<?php echo admin_url('clients/mark_as_active/' . $client->userid); ?>"><?php echo _l('mark_as_active'); ?></a>
                    </div>
                <?php } ?>
                <?php if (isset($client) && (!has_permission('customers', '', 'view') && is_customer_admin($client->userid))) { ?>
                    <div class="alert alert-info">
                        <?php echo _l('customer_admin_login_as_client_message', get_staff_full_name(get_staff_user_id())); ?>
                    </div>
                <?php } ?>
            </div>
            <?php if ($group == 'profile') { ?>
                <div class="btn-bottom-toolbar btn-toolbar-container-out text-right">
                    <button class="btn btn-info only-save customer-form-submiter">
                        <?php echo _l('submit'); ?>
                    </button>
                    <button class="btn btn-default mleft10">
                        <a href="<?=admin_url('clients')?>"><?php echo _l('cong_previous'); ?></a>
                    </button>
                </div>
            <?php } ?>
            <?php if (isset($client)) { ?>
                <div class="col-md-12">
                    <div class="panel_s mbot5">
                        <div class="col-md-4 panel-body padding-10">
                            <h4 class="bold">
                                #<?php echo $client->userid . ' ' . $title; ?>
                                <?php if (has_permission('customers', '', 'delete') || is_admin()) { ?>
                                    <div class="btn-group">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <?php if (is_admin()) { ?>
                                                <li>
                                                    <a href="<?php echo admin_url('clients/login_as_client/' . $client->userid); ?>"
                                                       target="_blank">
                                                        <i class="fa fa-share-square-o"></i> <?php echo _l('login_as_client'); ?>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if (has_permission('customers', '', 'delete')) { ?>
                                                <li>
                                                    <a href="<?php echo admin_url('clients/delete/' . $client->userid); ?>"
                                                       class="text-danger delete-text _delete"><i
                                                                class="fa fa-remove"></i> <?php echo _l('delete'); ?>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                <?php } ?>
                                <?php if (isset($client) && $client->leadid != NULL) { ?>
                                    <br/>
                                    <small>
                                        <b><?php echo _l('customer_from_lead', _l('lead')); ?></b>
                                        <a href="<?php echo admin_url('leads/index/' . $client->leadid); ?>"
                                           onclick="init_lead(<?php echo $client->leadid; ?>); return false;">
                                            - <?php echo _l('view'); ?>
                                        </a>
                                    </small>
                                <?php } ?>
                            </h4>
                        </div>
                        <div class="col-md-8">    
                            <div class="report_debt ">
                                <div class="text-center col-md-3 col-xs-3 border-right border-left">
                                    <h4 class="bold text-muted count_all">
                                    0
                                    </h4>
                                    <span style="color:red" class="text-danger">
                                        <?=_l('ch_all_order')?>
                                    </span>
                                </div>
                                <div class="text-center col-md-3 col-xs-3 border-right">
                                    <h4 class="bold text-muted total">
                                    0
                                    </h4>
                                    <span style="color:red" class="text-danger">
                                       <?=_l('ch_total_arises')?>
                                    </span>
                                </div>
                                <div class="text-center col-md-3 col-xs-3 border-right">
                                    <h4 class="bold text-muted pay">
                                    0
                                    </h4>
                                    <span style="color:red" class="text-danger">
                                        <?=_l('invoice_status_paid')?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="search_date" class="control-label"><?=_l('ch_date_start_end')?></label>
                                        <div class="input-group">
                                            <input type="text" autocomplete="off" id="search_date" name="search_date" class="form-control search_date" aria-invalid="false">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar calendar-icon"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <div class="clearfix"></div>
                    <?php $this->load->view('admin/clients/tabs'); ?>
                </div>
            <?php } ?>
            <div class="col-md-<?php if (isset($client)) {
                echo 12;
            } else {
                echo 12;
            } ?>">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php if (isset($client)) { ?>
                            <?php echo form_hidden('isedit'); ?>
                            <?php echo form_hidden('userid', $client->userid); ?>
                            <div class="clearfix"></div>
                        <?php } ?>
                        <div>
                            <div class="tab-content">
                                <?php $this->load->view((isset($tab) ? $tab['view'] : 'admin/clients/groups/profile')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($group == 'profile') { ?>
            <div class="btn-bottom-pusher"></div>
        <?php } ?>
    </div>
</div>
<?php init_tail(); ?>
<?php if (isset($client)) { ?>
    <script>
        $(function () {
            init_rel_tasks_table(<?php echo $client->userid; ?>, 'customer');
        });
    </script>
<?php } ?>
<?php $this->load->view('admin/clients/client_js'); ?>


<?php if(!empty($group) && $group == 'care_of_clients'){?>
    <!--Lịch sử chăm sóc khách hàng tiềm năng -->
    <?php $this->load->view('admin/care_of_clients/style_css')?>
    <?php $this->load->view('admin/care_of_clients/script_tab_js', ['userid' => $client->userid])?>
<?php } ?>

<?php if(!empty($group) && $group == 'advisory_lead' && !empty($client->leadid)){?>
    <!--Lịch sử chăm sóc khách hàng tiềm năng -->
    <?php $this->load->view('admin/advisory_lead/style_tab_css')?>
    <?php $this->load->view('admin/advisory_lead/script_tab_js', ['leadid' => $client->leadid])?>
<?php } ?>
</body>
</html>
