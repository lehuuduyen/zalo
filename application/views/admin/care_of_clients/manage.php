<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php $this->load->view('admin/care_of_clients/style_css')?>
<div id="wrapper">
    <div class="panel_s mbot10 H_scroll" id="H_scroll">
        <div class="panel-body _buttons">
            <span class="bold uppercase fsize18 H_title"><?=$title?></span>
            <a class="btn btn-info mright5 test pull-right H_action_button">
				<?php echo _l('Export excel'); ?>
            </a>
            <a class="btn btn-info mright5 test pull-right H_action_button" data-toggle="collapse" data-target="#search">
		        <?php echo _l('search'); ?>
            </a>
            <div class="line-sp"></div>
            <a href="#" class="btn btn-info pull-right H_action_button" onclick="editCare_of_clients()">
                <i class="lnr lnr-plus-circle" aria-hidden="true"></i>
				<?php echo _l('cong_button_add'); ?>
            </a>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div  class="btn-group mbot15">
                            <button type="button" data-toggle="tab" class="btn font11 btn-filter filter_all btn-icon btn-info active"><?=_l('cong_all')?></button>
							<?php if(!empty($client_detail)){?>
								<?php foreach($client_detail as $key => $value) { ?>
                                    <button type="button"  id_data="<?=$value['id']?>" class="btn-filter btn font11 btn-icon btn-info">
										<?=$value['name']?>
                                    </button>
								<?php } ?>
							<?php } ?>
							<?php echo form_hidden('procedure'); ?>
                        </div>
                        <div class="clearfix"></div>
                        <div id="search" class="collapse">
                            <div class="col-md-3">
		                        <?php echo render_date_input('date_start', 'cong_date_start_expected'); ?>
                            </div>
                            <div class="col-md-3">
		                        <?php echo render_date_input('date_end', 'cong_date_end_expected'); ?>
                            </div>
                            <div class="col-md-3">
			                    <?php echo render_input('name_client', 'cong_name_client');?>
                            </div>

                            <div class="col-md-3">
		                        <?php echo render_input('code_client', 'cong_code_client');?>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-3">
		                        <?php echo render_input('code_care_of', 'cong_code_care_of');?>
                            </div>

                            <div class="clearfix"></div>

                            <hr class="hr-panel-heading" />
                        </div>

                        <div class="clearfix"></div>
						<?php
						$experience = get_table_where('tblexperience_care_of_client',[], 'theme asc, id asc');
						$tableArray = [
							_l('cong_fullcode_care_of_short'),
							_l('cong_theme_care_of_client_short'),
							_l('clients_list_company'),
							_l('cong_solution_care_of_client'),
							_l('cong_date_feedback'),
							_l('priority_level'),
							_l('cong_status_care_of'),
							_l('cong_zcode'),
							_l('cong_code_orders'),
							_l('cong_code_items_to_orders'),
							_l('cong_name_items_to_orders'),
							'<p class="mw600">'._l('cong_step_care_of').'</p>',
						];
						foreach($experience as $key => $value)
						{
							$tableArray[] = $value['name'];
						}
						$tableArray[] = _l('cong_code_system');
						$tableArray[] = _l('cong_code_lead');
						$tableArray[] = _l('cong__code_client');
						$tableArray[] = _l('cong__code_client_now');
						$tableArray[] =  _l('cong_date_create_care_of_client');
						$tableArray[] =  _l('cong_staff_create_care_of_client');
						$tableArray[] =  _l('cong_date_success_care_of_client');
						$tableArray[] =  _l('cong_staff_success_care_of_client');

						render_datatable($tableArray, 'care_of_clients dont-responsive-table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_care_of_clients" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"></div>

<?php init_tail(); ?>
<!-- hoang -->
<link rel="stylesheet" type="text/css" href="<?= css('fixdatatable.css') ?>">
<script type="text/javascript" src="<?= js('datatables/jquery.dataTables.min.js') ?>"></script>
<script type="text/javascript" src="<?= js('datatables/dataTables.fixedColumns.min.js') ?>"></script>
<!-- //end -->
<?php $this->load->view('admin/care_of_clients/script_js')?>
</body>
</html>
