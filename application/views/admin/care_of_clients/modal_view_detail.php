<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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
                        <?=_l('cong_detail_care_of_client')?> <?= !empty($view_not_modal) ? ' : '.$care_of_clients->name_system : ' - ' ?>
                    </span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php
                        $experience = get_table_where('tblexperience_care_of_client', [], 'id ASC');
                        $solution = care_solutions();
                        $theme_of = StatusThemeCare_of($care_of_clients->theme_of);
                    ?>
                    <div class="col-md-6">
                        <div class="panel panel-primary mtop5 mbot10">
                            <div class="panel-heading">
                                <b><?=_l('cong_info_care_of_client')?></b>
                            </div>
                            <div class="padding-5 padding-top-0">
                                <div class="">
                                    <span class="col-md-5 mtop5 padding-left-0 lbltitle"><?= _l('cong_code_care_of_client')?> :</span>
                                    <span class="col-md-7 mtop5 text-left padding-right-0 text-danger"><?= $care_of_clients->prefix.$care_of_clients->code.'-'.$care_of_clients->short_theme?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>
                                <div class="mbot5">
                                    <span class="col-md-5 mtop5 padding-left-0 lbltitle"><?=_l('cong_name_system')?> :</span>
                                    <span class="col-md-7 mtop5 text-left padding-right-0 text-danger"><?= $care_of_clients->name_system?></span>
                                </div>
                                <div class="mbot5">
                                    <span class="col-md-5 mtop5 padding-left-0 lbltitle"><?=_l('cong_theme_care_of')?> :</span>
                                    <span class="col-md-7 mtop5 text-left padding-right-0 text-danger">
<!--                                        --><?php
//                                            echo EditColumSelect($care_of_clients->theme_of, $theme_of, ['id', 'name'], $care_of_clients->id, '', 'care_of_clients/updateColums', ['id' => 'form_care_of'], 'theme_of');
//                                        ?>
                                        <?= $theme_of['name']?>
                                    </span>
                                </div>
                                <div class="clearfix clearfix_C"></div>

                                <div class="mbot5">
                                    <span class="col-md-5 mtop5  padding-left-0 lbltitle"><?=_l('cong_solution_care_of_client')?> :</span>
                                    <span class="col-md-7 mtop5  text-left padding-right-0 text-danger"><?= $solution[$care_of_clients->solution]['name']?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>
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
                                    <span class="col-md-7 mtop5 text-left padding-right-0 text-danger"><?= $care_of_clients->code_system?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>
                                <div class="mbot5">
                                    <span class="col-md-5 mtop5 padding-left-0 lbltitle"><?=_l('cong_code_lead')?> :</span>
                                    <span class="col-md-7 mtop5 text-left padding-right-0 text-danger"><?= $care_of_clients->fullcode_lead?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>
                                <div class="mbot5">
                                    <span class="col-md-5 mtop5 padding-left-0 lbltitle"><?=_l('cong_code_client')?> :</span>
                                    <span class="col-md-7 mtop5 text-left padding-right-0 text-danger"><?= $care_of_clients->fullcode_client?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>
                                <div class="mbot5">
                                    <span class="col-md-5 mtop5 padding-left-0 lbltitle"><?=_l('code_client_now')?> :</span>
                                    <span class="col-md-7 mtop5 text-left padding-right-0 text-danger"><?= $care_of_clients->fullcode_client?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>
                                <div class="mbot5">
                                    <span class="col-md-5 mtop5  padding-left-0 lbltitle"><?=_l('cong_zcode')?> :</span>
                                    <span class="col-md-7 mtop5  text-left padding-right-0 text-danger"><?= $care_of_clients->zcode?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-md-6">
                        <div class="panel panel-primary mtop5 mbot10">
                            <div class="panel-heading">
                                <b><?=_l('cong_time_manage_care_of_client')?></b>
                            </div>
                            <div class="padding-5 padding-top-0">
                                <div class="">
                                    <span class="col-md-5 mtop5 padding-left-0 lbltitle"><?= _l('cong_date_feedback')?> :</span>
                                    <span class="col-md-7 mtop5 text-left padding-right-0 text-danger"><?= !empty($care_of_clients->date) ? _dt($care_of_clients->date) : ' - ' ?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>
                                <div class="mbot5">
                                    <span class="col-md-5 mtop5 padding-left-0 lbltitle"><?=_l('cong_date_create_care_of_client')?> :</span>
                                    <span class="col-md-7 mtop5 text-left padding-right-0 text-danger"><?= !empty($care_of_clients->date_create) ? _dt($care_of_clients->date_create) : '-' ?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>
                                <div class="mbot5">
                                    <span class="col-md-5 mtop5 padding-left-0 lbltitle"><?=_l('cong_date_success_care_of_client')?> :</span>
                                    <span class="col-md-7 mtop5 text-left padding-right-0 text-danger"><?= !empty($care_of_clients->date_success) ? _dt($care_of_clients->date_success) : '-' ?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="panel panel-primary mtop5 mbot10">
                            <div class="panel-heading">
                                <b><?=_l('cong_staff_manage_create_of_client')?></b>
                            </div>
                            <div class="padding-5 padding-top-0">
                                <div class="">
                                    <span class="col-md-5 mtop5 padding-left-0 lbltitle"><?= _l('cong_staff_manage_create_of_client')?> :</span>
                                    <span class="col-md-7 mtop5 text-left padding-right-0 text-danger"><?= !empty($care_of_clients->create_by) ? get_staff_full_name($care_of_clients->create_by) : '-'?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>
                                <div class="mbot5">
                                    <span class="col-md-5 mtop5 padding-left-0 lbltitle"><?= _l('cong_staff_success_care_of_client')?> :</span>
                                    <span class="col-md-7 mtop5 text-left padding-right-0 text-danger"><?= !empty($care_of_clients->staff_success) ? get_staff_full_name($care_of_clients->staff_success) : '-'?></span>
                                </div>
                                <div class="clearfix clearfix_C"></div>
                            </div>
                        </div>
                    </div>

                    <?php $product_care_of = getItemsCare_of_Orders($care_of_clients->id); ?>
                    <?php if(!empty($product_care_of)){ ?>
                        <div class="col-md-12">
                            <div class="panel panel-primary mtop5 mbot10">
                                <div class="panel-heading">
                                    <b><?=_l('cong_theme_care_of_and_feedback_order')?></b>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered mtop0 table_colorheader">
                                        <thead>
                                            <tr>
                                                <th rowspan="2"><?=_l('cong_code_orders')?></th>
                                                <th rowspan="2"><?=_l('cong_code_items_to_orders')?></th> <!--Mã sản phẩm-->
                                                <th rowspan="2"><?=_l('cong_name_items_to_orders')?></th> <!--tên sản phẩm-->
                                                <?php $experience_Count = get_table_query_cong('select count(id) as count_id, theme from tblexperience_care_of_client where type_detail = 2'); ?>
                                                <?php
                                                    foreach($experience_Count as $key => $value)
                                                    {
                                                        if(!empty($value['theme'])) {
                                                            $getThem = StatusThemeCare_of($value['theme']);
                                                            echo '<th colspan="'.$value['count_id'].'" class="text-center">'.(!empty($getThem['name']) ?  $getThem['name'] : '-' ).'</th>';
                                                        }
                                                        else
                                                        {
                                                            if (!empty($product_care_of)) {
                                                                echo '<th colspan="'.$value['count_id'].'" class="text-center">'._l('cong_client_feedback_to_order').'</th>';
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </tr>
                                            <?php
                                                $stringTh = '';
                                                $experience_type_detail_product = get_table_where('tblexperience_care_of_client', ['type_detail' => 2], 'theme asc');
                                                foreach ($experience_type_detail_product as $kExperience => $vExperience) {
                                                        $stringTh .= '<th>' . $vExperience['name'] . '</th>';
                                                }

                                                if(!empty($stringTh))
                                                {
                                                    echo '<tr>';
                                                    echo $stringTh;
                                                    echo '</tr>';
                                                }
                                            ?>
                                        </thead>
                                        <tbody>
                                            <?php if(!empty($product_care_of)) {?>
                                                <?php foreach($product_care_of as $kProCare => $vProCare) { ?>
                                                    <tr>
                                                        <?php if($kProCare == 0){?>
                                                            <td class="vertical_middle" rowspan="<?=count($product_care_of)?>"><?=(!empty($care_of_clients->code_orders) ? $care_of_clients->code_orders : ' - ')?></td>
                                                        <?php }?>
                                                        <td><?= $vProCare['code'] ?></td>
                                                        <td><?= $vProCare['name'] ?></td>
                                                        <?php foreach($experience_type_detail_product as $kExperience => $vExperience) {?>
                                                            <?php
                                                                if ($vExperience['type'] == 'select') {
                                                                    $detail_experience = get_table_where_select_cong('tblcare_of_detail_experience', [
                                                                        'id_care_of' => $care_of_clients->id,
                                                                        'id_experience' => $vExperience['id'],
                                                                        'id_care_items' => $vProCare['id']
                                                                    ], '', 'group_concat(name separator "</br>") as listname, group_concat(id_experience_detail) as listid', 'row');
                                                                    echo "<td>" . (!empty($detail_experience->listname) ? $detail_experience->listname : '') . "</td>";

                                                                }
                                                                else if ($vExperience['type'] == 'staff') {

                                                                    $detail_experience = get_table_where_select_cong('tblcare_of_detail_experience', [
                                                                        'id_care_of' => $care_of_clients->id,
                                                                        'id_experience' => $vExperience['id'],
                                                                        'id_care_items' => $vProCare['id']
                                                                    ], '', 'name', 'row');
                                                                    echo "<td>" . (!empty($detail_experience->name) ? get_staff_full_name($detail_experience->name) : '') . "</td>";

                                                                }
                                                                else if ($vExperience['type'] == 'img') {

                                                                    $detail_experience = get_table_where_select_cong('tblcare_of_detail_experience', [
                                                                        'id_care_of' => $care_of_clients->id,
                                                                        'id_experience' => $vExperience['id'],
                                                                        'id_care_items' => $vProCare['id']
                                                                    ], '', 'id, name', 'result');

                                                                    $img = '';
                                                                    if (!empty($detail_experience)) {
                                                                        $img = '<div class="preview_image" style="width: auto;">';
                                                                        $img .= '   <div class="display-block contract-attachment-wrapper img">';
                                                                        $img .= '       <div style="width:45px; margin: auto;display: flex;">';
                                                                        foreach ($detail_experience as $kImg => $vImg) {
                                                                            $img .= '       <a href="' . base_url('download/preview_image?path=uploads/care_of_client/' . $care_of_clients->id . '/' . $vExperience['id'] . '/' . $vImg->name) . '" data-lightbox="customer-profile" class="display-block mbot5 mleft5">';
                                                                            $img .= '           <img src="' . base_url('download/preview_image?path=uploads/care_of_client/' . $care_of_clients->id . '/' . $vExperience['id'] . '/' . $vImg->name) . '" class="image-small"/>';
                                                                            $img .= '       </a>';
                                                                            $img .= '       <a class="text-danger removeImg"  id_img="' . $vImg->id . '"  url="' . $care_of_clients->id . '/' . $vExperience['id'] . '/' . $vImg->name . '">X</a>';
                                                                        }
                                                                        $img .= '        </div>';
                                                                        $img .= '    </div>';
                                                                        $img .= '</div>';
                                                                    }
                                                                    echo "<td>" . $img . "</td>";

                                                                }
                                                                else {
                                                                    $detail_experience = get_table_where_select_cong('tblcare_of_detail_experience', [
                                                                        'id_care_of' => $care_of_clients->id,
                                                                        'id_experience' => $vExperience['id'],
                                                                        'id_care_items' => $vProCare['id']
                                                                    ], '', 'name', 'row');

                                                                    if (!empty($detail_experience->name)) {
                                                                        $value_name = $detail_experience->name;
                                                                    }
                                                                    if ($vExperience['type'] == 'date') {
                                                                        $class = "datepicker";
                                                                        $value_name = !empty($detail_experience->name) ? _dC($detail_experience->name) : '';
                                                                    } else if ($vExperience['type'] == 'datetime') {
                                                                        $class = "datetimepicker";
                                                                        $value_name = !empty($detail_experience->name) ? _dt($detail_experience->name) : '';
                                                                    }
                                                                    echo "<td>" . (!empty($value_name) ? $value_name : '') . "</td>";
                                                                }
                                                            ?>
                                                        <?php } ?>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <!--Các giá trị tư vấn theo phiếu chăm sóc-->


                    <?php $experience_group_not_product = get_table_query_cong('select theme from tblexperience_care_of_client where type_detail = 1 group by theme');?>

                    <?php
                    $countThem = [];
                    foreach($experience_group_not_product as $keyGroup => $valGroup){ ?>
                        <div class="col-md-6">
                            <div class="panel panel-primary mtop5 mbot10">
                                <div class="panel-heading">
                                    <?php $getThem = StatusThemeCare_of($valGroup['theme']); ?>
                                    <b>
                                        <?=!empty($getThem['name']) ? $getThem['name'] : _l('cong_review_care_of_client') ?>
                                    </b>
                                </div>
                                <div class="padding-5 padding-top-0">
                                    <?php
                                        $experience_type_detail_not_product = get_table_where('tblexperience_care_of_client', ['type_detail' => 1, 'theme' => $valGroup['theme']], 'theme asc');
                                        foreach($experience_type_detail_not_product as $kExperience => $vExperience) { ?>
                                            <?php
                                                if ($vExperience['type'] == 'select') {
                                                    $detail_experience = get_table_where_select_cong('tblcare_of_detail_experience', [
                                                        'id_care_of' => $care_of_clients->id,
                                                        'id_experience' => $vExperience['id']
                                                    ], '', 'group_concat(name separator "</br>") as listname, group_concat(id_experience_detail) as listid', 'row');
                                                    echo '<div class="lbltitle">';
                                                    echo '<span class="col-md-5 mtop5 padding-left-0 lbltitle">'.$vExperience['name'].' :</span>';
                                                    echo '<span class="col-md-7 mtop5 text-left padding-right-0 text-danger">'.(!empty($detail_experience->listname) ? $detail_experience->listname : ' - ').'</span>';
                                                    echo '</div>';
                                                    echo '<div class="clearfix clearfix_C"></div>';

                                                }
                                                else if ($vExperience['type'] == 'staff') {
                                                    $detail_experience = get_table_where_select_cong('tblcare_of_detail_experience', [
                                                        'id_care_of' => $care_of_clients->id,
                                                        'id_experience' => $vExperience['id']
                                                    ], '', 'name', 'row');
                                                    echo '<span class="col-md-5 mtop5 padding-left-0 lbltitle">'.$vExperience['name'].' :</span>';
                                                    echo '<span class="col-md-7 mtop5 text-left padding-right-0 text-danger">'.(!empty($detail_experience->name) ? get_staff_full_name($detail_experience->name) : ' - ').'</span>';
                                                    echo '<div class="clearfix clearfix_C"></div>';

                                                }
                                                else if ($vExperience['type'] == 'img') {
                                                    $detail_experience = get_table_where_select_cong('tblcare_of_detail_experience', [
                                                        'id_care_of' => $care_of_clients->id,
                                                        'id_experience' => $vExperience['id']
                                                    ], '', 'id, name', 'result');
                                                    $img = '';
                                                    if (!empty($detail_experience)) {
                                                        $img = '<div class="preview_image" style="width: auto;">';
                                                        $img .= '   <div class="display-block contract-attachment-wrapper img">';
                                                        $img .= '       <div style="width:45px; margin: auto;display: flex;">';
                                                        foreach ($detail_experience as $kImg => $vImg) {
                                                            $img .= '       <a href="' . base_url('download/preview_image?path=uploads/care_of_client/' . $care_of_clients->id . '/' . $vExperience['id'] . '/' . $vImg->name) . '" data-lightbox="customer-profile" class="display-block mbot5 mleft5">';
                                                            $img .= '           <img src="' . base_url('download/preview_image?path=uploads/care_of_client/' . $care_of_clients->id . '/' . $vExperience['id'] . '/' . $vImg->name) . '" class="image-small"/>';
                                                            $img .= '       </a>';
                                                            $img .= '       <a class="text-danger removeImg"  id_img="' . $vImg->id . '"  url="' . $care_of_clients->id . '/' . $vExperience['id'] . '/' . $vImg->name . '">X</a>';
                                                        }
                                                        $img .= '        </div>';
                                                        $img .= '    </div>';
                                                        $img .= '</div>';
                                                    }
                                                    echo '<span class="col-md-5 mtop5 padding-left-0 lbltitle">'.$vExperience['name'].' :</span>';
                                                    echo '<span class="col-md-7 mtop5 text-left padding-right-0 text-danger">'.($img).'</span>';
                                                    echo '<div class="clearfix clearfix_C"></div>';

                                                }
                                                else {
                                                    $detail_experience = get_table_where_select_cong('tblcare_of_detail_experience', [
                                                        'id_care_of' => $care_of_clients->id,
                                                        'id_experience' => $vExperience['id']
                                                    ], '', 'name', 'row');

                                                    if (!empty($detail_experience->name)) {
                                                        $value_name = $detail_experience->name;
                                                    }
                                                    if ($vExperience['type'] == 'date') {
                                                        $class = "datepicker";
                                                        $value_name = !empty($detail_experience->name) ? _dC($detail_experience->name) : '';
                                                    } else if ($vExperience['type'] == 'datetime') {
                                                        $class = "datetimepicker";
                                                        $value_name = !empty($detail_experience->name) ? _dt($detail_experience->name) : '';
                                                    }

                                                    echo '<span class="col-md-5 mtop5 padding-left-0 lbltitle">'.$vExperience['name'].' :</span>';
                                                    echo '<span class="col-md-7 mtop5 text-left padding-right-0 text-danger">'.(!empty($value_name) ? $value_name : ' - ').'</span>';
                                                    echo '<div class="clearfix clearfix_C"></div>';

                                                }
                                            ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <!--END Các giá trị tư vấn theo phiếu chăm sóc-->

                </div>
            </div>

            <div class="modal-footer <?= !empty($view_not_modal) ? 'hide' : '' ?>">
                <button class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            </div>
        </div>



<?php if(empty($view_not_modal)){?>
    </div>
<?php } ?>
