<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
    $CI = & get_instance();
?>
<?php if(empty($view_not_modal)){?>
    <div class="modal-dialog modal-xl" role="document">
<?php } else {
	init_not_head();
} ?>
        <div class="modal-content">
            <div class="modal-header">
                <button group="button" class="close <?= !empty($view_not_modal) ? 'hide' : '' ?>" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="title">
                        <?php
                            $select_lead = 'id, 
                                            concat(COALESCE(prefix_lead), COALESCE(code_lead), "-", COALESCE(zcode), "-", COALESCE(code_type)) as fullcode, 
                                            name_system,
                                            zcode,
                                            code_system';
                            $select_client = 'userid, 
                                            concat(COALESCE(prefix_client), COALESCE(code_client), "-", COALESCE(zcode), "-", COALESCE(code_type)) as fullcode,
                                            name_system, 
                                            zcode,
                                            code_system';
                            if ($advisory->type_object == 'lead') {
                                $lead = get_table_where_select_cong('tblleads', ['id' => $advisory->id_object], '', $select_lead, 'row');
                                if (!empty($lead)) {
                                    $client = get_table_where_select_cong('tblclients', ['leadid' => $lead->id], '', $select_client, 'row');
                                    if (!empty($client)) {
                                        $object = $client;
                                    } else {
                                        $object = $lead;
                                    }
                                }
                            } else {
                                $client = get_table_where_select_cong('tblclients', ['userid' => $advisory->id_object], '', $select_client, 'row');
                                if (!empty($client)) {
                                    $object = $client;
                                    $lead = get_table_where_select_cong('tblleads', ['id' => $advisory->id_object], '', $select_lead, 'row');
                                }
                            }
                        ?>
                        <?=_l('cong_detail_care_of_client')?> <?= !empty($view_not_modal) ? ' : '.$object->name_system : ' - ' ?>
                    </span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php
                        $experience = get_table_where('tblexperience_advisory', [], 'id ASC');
                    ?>
                    <div class="col-md-6">
                        <div class="panel panel-primary mtop5 mbot10">
                            <div class="panel-heading">
                                <b><?=_l('cong_info_advisory')?></b>
                            </div>
                            <div class="padding-5 padding-top-0">
                                <div class="">
                                    <span class="col-md-5 mtop5 padding-left-0 lbltitle"><?= _l('cong_code_advisory_client')?> :</span>
                                    <span class="col-md-7 mtop5 text-left padding-right-0 text-danger"><?= $advisory->fullcode ?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>
                                <div class="mbot5">
                                    <span class="col-md-5 mtop5 padding-left-0 lbltitle"><?=_l('cong_name_system')?> :</span>
                                    <span class="col-md-7 mtop5 text-left padding-right-0 text-danger"><?= $object->name_system?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>

                                <div class="mbot5">
                                    <span class="col-md-5 mtop5 padding-left-0 lbltitle"><?=_l('ticket_settings_priority')?> :</span>
                                    <span class="col-md-7 mtop5 text-left padding-right-0 text-danger"><?= priority_level_advisory($advisory->status_active, $advisory->id, true);?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>
                                <div class="mbot5">
                                    <span class="col-md-5 mtop5 padding-left-0 lbltitle"><?=_l('cong_status_procedure')?> :</span>
                                    <?php $statusActive = StatusActiveAdvisory(); ?>
                                    <span class="col-md-7 mtop5 text-left padding-right-0 text-danger"><?= !empty($statusActive[$advisory->status_active]) ? $statusActive[$advisory->status_active]['name'] : $statusActive[0]['name'];?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>
                                <div class="mbot5">
                                    <span class="col-md-5 mtop5 padding-left-0 lbltitle"><?=_l('cong_product_other_buy')?> :</span>
                                    <span class="col-md-7 mtop5 text-left padding-right-0 text-danger"><?= $advisory->product_other_buy ?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>
                                <div class="mbot5">
                                    <span class="col-md-5 mtop5 padding-left-0 lbltitle"><?=_l('cong_address_other_buy')?> :</span>
                                    <span class="col-md-7 mtop5 text-left padding-right-0 text-danger"><?= $advisory->address_other_buy  ?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>
                                <?php
                                if(empty($info_view_detail)) {
	                                $info_view_detail = get_table_where('tblclient_info_detail', ['view_modal' => 1]);
                                }
                                foreach($info_view_detail as $key => $value)
                                {
	                                $CI->db->select('group_concat(name) as fullname');
	                                $CI->db->join('tblclient_info_detail_value','tblclient_info_detail_value.id = tbladvisory_info_value.value_info');
	                                $CI->db->where('id_advisory', $advisory->id);
	                                $CI->db->where('id_info', $value['id']);
	                                $advisory_info = $CI->db->get('tbladvisory_info_value')->row();
	                                ?>
                                    <div class="mbot5">
                                        <span class="col-md-5 mtop5 padding-left-0 lbltitle"><?= $value['name'] ?> :</span>
                                        <span class="col-md-7 mtop5 text-left padding-right-0 text-danger"><?= $advisory_info->fullname;  ?></span>
                                    </div>
                                    <div class="clearfix clearfix_C"></div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="panel panel-primary mtop5 mbot10">
                            <div class="panel-heading">
                                <b><?=_l('cong_profile_client')?></b>
                            </div>
                            <div class="padding-5 padding-top-0">
                                <div class="">
                                    <span class="col-md-5 mtop5 padding-left-0 lbltitle"><?= _l('cong_code_system')?> :</span>
                                    <span class="col-md-7 mtop5 text-left padding-right-0 text-danger"><?= $object->code_system?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>
                                <div class="mbot5">
                                    <span class="col-md-5 mtop5 padding-left-0 lbltitle"><?=_l('cong_code_lead')?> :</span>
                                    <span class="col-md-7 mtop5 text-left padding-right-0 text-danger"><?= !empty($lead) ? $lead->fullcode : '-'?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>
                                <div class="mbot5">
                                    <span class="col-md-5 mtop5 padding-left-0 lbltitle"><?=_l('cong_code_client')?> :</span>
                                    <span class="col-md-7 mtop5 text-left padding-right-0 text-danger"><?= !empty($client->fullcode) ? $client->fullcode : '-'?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>
                                <div class="mbot5">
                                    <span class="col-md-5 mtop5 padding-left-0 lbltitle"><?=_l('code_client_now')?> :</span>
                                    <span class="col-md-7 mtop5 text-left padding-right-0 text-danger"><?= $object->fullcode?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>
                                <div class="mbot5">
                                    <span class="col-md-5 mtop5  padding-left-0 lbltitle"><?=_l('cong_zcode')?> :</span>
                                    <span class="col-md-7 mtop5  text-left padding-right-0 text-danger"><?= $object->zcode?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-md-6">
                        <div class="panel panel-primary mtop5 mbot10">
                            <div class="panel-heading">
                                <b><?=_l('cong_time_manage_advisory')?></b>
                            </div>
                            <div class="padding-5 padding-top-0">
                                <div class="">
                                    <span class="col-md-5 mtop5 padding-left-0 lbltitle"><?= _l('cong_date_contact')?> :</span>
                                    <span class="col-md-7 mtop5 text-left padding-right-0 text-danger"><?= !empty($advisory->date) ? _dt($advisory->date) : ' - ' ?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>
                                <div class="mbot5">
                                    <span class="col-md-5 mtop5 padding-left-0 lbltitle"><?=_l('cong_date_create_advisory')?> :</span>
                                    <span class="col-md-7 mtop5 text-left padding-right-0 text-danger"><?= !empty($advisory->date_create) ? _dt($advisory->date_create) : '-' ?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="panel panel-primary mtop5 mbot10">
                            <div class="panel-heading">
                                <b><?=_l('cong_staff_manage_advisory')?></b>
                            </div>
                            <div class="padding-5 padding-top-0">
                                <div class="">
                                    <span class="col-md-5 mtop5 padding-left-0 lbltitle"><?= _l('cong_staff_manage_advisory')?> :</span>
                                    <span class="col-md-7 mtop5 text-left padding-right-0 text-danger"><?= !empty($advisory->create_by) ? get_staff_full_name($advisory->create_by) : '-'?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>
                            </div>
                        </div>
                    </div>

                    <!--Các giá trị tư vấn theo phiếu tư vấn-->
                        <div class="col-md-12">
                            <div class="panel panel-primary mtop5 mbot10">
                                <div class="panel-heading">
                                    <b>
                                        <?=_l('cong_info_when_advisory')?>
                                    </b>
                                </div>
                                <div class="padding-5 padding-top-0">
                                    <?php
                                        $experience_type = get_table_where('tblexperience_advisory');
                                        foreach($experience_type as $kExperience => $vExperience) { ?>
                                            <?php
                                                $detail_experience = get_table_where_select_cong('tbladvisory_detail_experience', [
                                                    'id_advisory' => $advisory->id,
                                                    'id_experience' => $vExperience['id']
                                                ], '', 'group_concat(name separator "</br>") as listname, group_concat(id_experience_detail) as listid', 'row');
                                                echo '<div class="lbltitle">';
                                                echo '<span class="col-md-5 mtop5 padding-left-0 lbltitle">'.$vExperience['name'].' :</span>';
                                                echo '<span class="col-md-7 mtop5 text-left padding-right-0 text-danger">'.(!empty($detail_experience->listname) ? $detail_experience->listname : ' - ').'</span>';
                                                echo '</div>';
                                                echo '<div class="clearfix clearfix_C"></div>';
                                            ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <!--END Các giá trị tư vấn theo phiếu tư vấn-->

                </div>
            </div>

            <div class="modal-footer <?= !empty($view_not_modal) ? 'hide' : '' ?>">
                <button class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            </div>
        </div>



<?php if(empty($view_not_modal)){?>
    </div>
<?php } ?>
