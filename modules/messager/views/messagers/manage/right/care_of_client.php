<?php if (!empty($data)) { ?>
    <?php 
        $experience_care = get_table_where('tblexperience_care_of_client', ['type_detail' => 1], 'id ASC');
        $experience_product = get_table_where('tblexperience_care_of_client', ['type_detail' => 2], 'id ASC');
        $solution = care_solutions();
    ?>
    <?php foreach ($data as $kData => $vData) { ?>
        <div class="div_careof panel panel-info">
            <div class="panel-heading padding_bottom25">
                <?php if (!empty($vData)) { ?>
                    <?=_l('cong_fullcode_care_of')?> :
                    <a target="_blank" href="<?=admin_url('messager/view_detail_care_of/'.$vData->id)?>">
                        <?= $vData->prefix . $vData->code. '-'.$vData->short_theme?>
                    </a>
                <?php } ?>
                <a class="text-danger pull-right" onclick="BreakCare_of(<?=$vData->id?>, 1, this); return false;">
                    <b><?=_l('cong_break_advisory')?></b>
                </a>
                <div class="clearfix_C"></div>
                <a class="text-danger pull-left mtop5"  data-toggle="collapse" data-target="#jsCare_of_Detail_<?=$vData->id?>"><b><?=_l('more')?></b></a>
            </div>
            <div class="panel-body padding-5">
                <?php 
                    // Chủ đề chăm sóc ban đầu
                    $theme_of = StatusThemeCare_of($vData->theme_of);
                     echo '<div class="mbot5">'._l('cong_theme_care_of').': '.(!empty($theme_of['name']) ? $theme_of['name'] : '').'</div>';
                     //End chủ đề chăm sóc ban đầu

                    //Giải pháp chăm sóc
                    $htmlSolution = '';
                    foreach($solution as $kSolution => $vSolution)
                    {
                        if(!empty($kSolution))
                        {
                            $htmlSolution .= '<li><a class="solution" status-table="'.$kSolution.'" id-data="'.$vData->id.'">'.$vSolution['name'].'</a></li>';
                        }
                    }
                    $Solution_active = !empty($solution[$vData->solution]) ? $solution[$vData->solution] : [];

                    $DropdowSolution ='<span class="inline-block label '.(!empty($Solution_active['class']) ? $Solution_active['class'] : '').'">
                                    '.(!empty($Solution_active['name']) ? $Solution_active['name'] : '').'
                                        <div class="dropdown inline-block mleft5 table-export-exclude">
                                            <a href="#" style="font-size:14px;vertical-align:middle;" class="dropdown-toggle text-dark"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span data-toggle="tooltip" title="'.(!empty($Solution_active['name']) ? $Solution_active['name'] : '').'">
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                </span>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-right"  colums="criteria_one">'.$htmlSolution.'</ul>
                                        </div>
                                    </span>';
                    echo '<div class="mbot5">'._l('cong_solution_care_of').': '.$DropdowSolution.'</div>';
                    //End giải pháp chăm sóc
                ?>
                <!--mức độ ưu tiên-->
                <div class="mbot5">
                    <?=_l('priority_level')?>: <?= priority_level_care_of_client($vData->id, true); ?>
                </div>
                <!-- mức độ ưu tiên-->

                <div class="mbot5">
                    <?php $statusCare = get_table_where('tblprocedure_care_of', ['id_care_of' => $vData->id, 'active' => 1], 'orders desc', 'row');?>
		            <?=_l('cong_status_care_of')?>: <?= !empty($statusCare) ? $statusCare->name_status : ' - '; ?>
                </div>



                <!--Đơn hàng -->
                <div class="mbot5">
		            <?=_l('cong_orders')?>:
                    <?php
                        if(!empty($vData->id_orders)) {
	                        echo '<span class="col-order" id-orders="'.$vData->id_orders.'">'.(!empty($vData->code_order) ? $vData->code_order : '-').'</span>';
                        }
                        else
                        {
                            echo '<span class="col-order"> - </span>';
                        }
                    ?>
                </div>
                <!--End đơn hàng-->
                <!--Sản phẩm bổ sung thêm-->
                <?php if(!empty($vData->order_items)){
                        echo Create_wap_content_select(_l('cong_product_not_check_orders'), 'items_product', '', $vData->id, 'messager/add_care_of_client_items', array('class' => 'form_care_of'), $vData->order_items);
                 } ?>
                <!--Sản phẩm bổ sung thêm-->

                <div class="mbot5">
		            <?=_l('cong_date_create_care_of_client')?>: <?=!empty($vData->date_create) ? _dt($vData->date_create) : ' - '?>
                </div>
	            <?php
                    $value = $vData->note ? $vData->note : '';
                    echo Create_wap_content_input(_l('cong_note_care_of_client'), 'note', $value, $vData->id, 'admin/care_of_clients/updateColum', array('class' => 'form_care_of'), '');
	            ?>

                <div id="jsCare_of_Detail_<?=$vData->id?>" class="jsCare_of collapse" id_data="<?=$vData->id?>">

                    <!--Ngày khách phản hồi-->
                    <div class="mbot5">
		                <?=_l('cong_date_feedback')?>: <?=!empty($vData->date) ? _dt($vData->date) : ' - '?>
                    </div>
                    <!-- Ngày khách phản hồi-->
                    <div class="mbot5">
		                <?=_l('cong_date_success_care_of_client')?>: <?=!empty($vData->date_success) ? _dt($vData->date_success) : ' - '?>
                    </div>

                    <div class="mbot5">
		                <?=_l('cong_staff_manage_create_of_client')?>: <?=!empty($vData->create_by) ? get_staff_full_name($vData->create_by) : ' - '?>
                    </div>
                    <div class="mbot5">
		                <?=_l('cong_staff_success_care_of_client')?>: <?=!empty($vData->staff_success) ? get_staff_full_name($vData->staff_success) : ' - '?>
                    </div>

                    <?php
                        $KeySTT = 0;
                        //Các phản hồi chăm sóc theo phiếu
                        $kCount = [];
                        $kCount_null = 0;
                        foreach($experience_care as $kExperience => $vExperience)
                        {
                            if(!empty($vExperience['theme']) && empty($kCount[$vExperience['theme']]))
                            {
	                            $theme_of = StatusThemeCare_of($vExperience['theme']);
                                echo '<div class="wap-content lbl-wap-content">';
                                echo '    <h5 class="lbl-title-silver">'.(!empty($theme_of['name']) ? $theme_of['name'] : ' - ').'</h5>';
                                echo '</div>';
	                            $kCount[$vExperience['theme']] = 1;
                            }
                            else if( (empty($vExperience['theme']) && $kCount_null == 0) )
                            {
                                if(empty($vExperience['theme']) && $kCount_null == 0)
                                {
                                    echo '<div class="wap-content lbl-wap-content">';
                                    echo '    <h5 class="lbl-title-silver">'._l('cong_review_care_of_client').'</h5>';
                                    echo '</div>';
	                                $kCount_null = 1;
                                }
                            }

                            $htmlDrop = "";
                            if($vExperience['type'] == 'select')
                            {
                                $detail_experience = get_table_where_select_cong('tblcare_of_detail_experience', [
                                        'id_care_of' => $vData->id,
                                        'id_experience' => $vExperience['id']
                                ], '', 'group_concat(name separator "</br>") as listname, group_concat(id_experience_detail) as listid', 'row');

                                $DropdownList = DropdownListexpErienceCare_of($vExperience['id'], !empty($detail_experience->listid) ? explode(',', $detail_experience->listid) : '', $vData->id);
                                $DropdownList.="<button type='button' class='SaveErienceCare btn btn-info btn-icon mtop10'>"._l('cong_save')."</button>
                                                <button type='button' class='btn btn-danger btn-icon mtop10 close_popover'>"._l('cong_close')."</button>";
                                $htmlDrop = '<a class="PopverSelect2 pointer" data-toggle="popover" data-placement="left" title="'.$vExperience['name'].'" data-html="true" data-content="'.$DropdownList.'">
                                                '.(!empty($detail_experience->listname) ? trim($detail_experience->listname) : "...").'
                                            </a>';
                            }
                            else if($vExperience['type'] == 'staff')
                            {
                                $detail_experience = get_table_where_select_cong('tblcare_of_detail_experience', [
                                        'id_care_of' => $vData->id,
                                        'id_experience' => $vExperience['id']
                                ], '', 'name', 'row');

                                $value_name = !empty($detail_experience->name) ? $detail_experience->name : "";

                                $DropdownList = DropdownListexpErienceStaff($vExperience['id'], $value_name, $vData->id);
                                $DropdownList.="<button type='button' class='SaveErienceCare btn btn-info btn-icon mtop10'>"._l('cong_save')."</button>
                                                <button type='button' class='btn btn-danger btn-icon mtop10 close_popover'>"._l('cong_close')."</button>";
                                $htmlDrop = '<a class="PopverSelect2 pointer" data-toggle="popover" data-placement="left" title="'.$vExperience['name'].'" data-html="true" data-content="'.$DropdownList.'">
                                                '.(!empty($value_name) ? trim($value_name) : "...").'
                                            </a>';
                            }
                            else if($vExperience['type'] == 'img')
                            {
                                $detail_experience = get_table_where_select_cong('tblcare_of_detail_experience', [
                                        'id_care_of' => $vData->id,
                                        'id_experience' => $vExperience['id']
                                ], '', 'id, name', 'result');

                                $DropdownList = DropdownListexpErienceFile($vExperience['id'], $vData->id, '', 'file');

                                $DropdownList.="<button type='button' class='SaveFileErience btn btn-info btn-icon mtop10'>"._l('cong_save')."</button>
                                                <button type='button' class='btn btn-danger btn-icon mtop10 close_popover'>"._l('cong_close')."</button>";
                                $img = "";
                                if(!empty($detail_experience))
                                {
                                    $img = '<div class="preview_image" style="width: auto;">';
                                    $img .= '   <div class="display-block contract-attachment-wrapper img">';
                                    $img .= '       <div style="width:45px; margin: auto;display: flex;">';
                                    foreach($detail_experience as $kImg => $vImg)
                                    {
                                        $img .= '       <a href="'.base_url('download/preview_image?path=uploads/care_of_client/'.$vData->id.'/'.$vExperience['id'].'/'.$vImg->name).'" data-lightbox="customer-profile" class="display-block mbot5 mleft5">';
                                        $img .= '           <img src="'.base_url('download/preview_image?path=uploads/care_of_client/'.$vData->id.'/'.$vExperience['id'].'/'.$vImg->name).'" class="image-small"/>';
                                        $img .= '       </a>';
                                        $img .= '       <a class="text-danger removeImg"  id_img="'.$vImg->id.'"  url="'.$vData->id.'/'.$vExperience['id'].'/'.$vImg->name.'">X</a>';
                                    }
                                    $img .='        </div>';
                                    $img .='    </div>';
                                    $img .='</div>';
                                }
                                $img .='<p class="clearfix_C"></p>';
                                $htmlDrop = $img.'<a class="PopverSelect2 pointer" data-toggle="popover" data-placement="left" title="'.$vExperience['name'].'" data-html="true" data-content="'.$DropdownList.'">...</a>';
                            }
                            else {
                                $detail_experience = get_table_where_select_cong('tblcare_of_detail_experience', [
                                        'id_care_of' => $vData->id,
                                        'id_experience' => $vExperience['id']
                                ], '', 'name', 'row');

                                $class = '';
                                $value_name = '';
                                if(!empty($detail_experience->name))
                                {
                                    $value_name = $detail_experience->name;
                                }
                                if($vExperience['type'] == 'date'){
                                    $class = "datepicker";
                                    $value_name = !empty($detail_experience->name) ? _dC($detail_experience->name) : '';
                                }
                                else if($vExperience['type'] == 'datetime'){
                                    $class = "datetimepicker";
                                    $value_name = !empty($detail_experience->name) ? _dt($detail_experience->name) : '';
                                }
                                $DropdownList = DropdownListexpErienceType($vExperience['id'], $value_name, $vData->id, $class);
                                $DropdownList.="<button type='button' class='SaveErienceCare btn btn-info btn-icon mtop10'>"._l('cong_save')."</button>
                                                <button type='button' class='btn btn-danger btn-icon mtop10 close_popover'>"._l('cong_close')."</button>";
                                $htmlDrop = '<a class="PopverSelect2 pointer" data-toggle="popover" data-placement="left" title="'.$vExperience['name'].'" data-html="true" data-content="'.$DropdownList.'">
                                                '.(!empty($value_name) ? trim($value_name) : "...").'
                                            </a>';

                            }
                            echo '<div class="mbot5 viewchange">'.($kExperience + 1).'. '.$vExperience['name'].': '.$htmlDrop.'</div>';
	                        $KeySTT = ($kExperience + 1);
                        }
                        //End các phản hồi chăm sóc theo phiếu

                        //Các phản hồi chăm sóc của sản phẩm
                        $product_care_of = getItemsCare_of_Orders($vData->id);
                        if(!empty($product_care_of)) {
                            foreach($product_care_of as $kProCare => $vProCare)
                            {

	                            $kCount = [];
                                echo '<hr class="mbot5"/>';
                                echo '<div class="mbot5 mtop10"><span class="inline-block label label-success">'.($kProCare+1).'. '.$vProCare['code'].'('.$vProCare['name'].')</span></div>';
                                foreach($experience_product as $kExperience => $vExperience){
                                    if( empty($kCount[$vExperience['theme']]) )
                                    {
	                                    $theme_of = StatusThemeCare_of($vExperience['theme']);
                                        echo '<div class="wap-content lbl-wap-content">';
                                        echo '    <h5 class="lbl-title-silver">'.(!empty($theme_of['name']) ? $theme_of['name'] : ' - ').'</h5>';
                                        echo '</div>';
	                                    $kCount[$vExperience['theme']] = 1;
                                    }

                                    $htmlDrop = "";
                                    if($vExperience['type'] == 'select')
                                    {
                                        $detail_experience = get_table_where_select_cong('tblcare_of_detail_experience', [
                                                'id_care_of' => $vData->id,
                                                'id_experience' => $vExperience['id'],
                                                'id_care_items' => $vProCare['id']
                                        ], '', 'group_concat(name separator "</br>") as listname, group_concat(id_experience_detail) as listid', 'row');

                                        $DropdownList = DropdownListexpErienceCare_of($vExperience['id'], (!empty($detail_experience->listid) ? explode(',', $detail_experience->listid) : ''), $vData->id, $vProCare['id']);
                                        $DropdownList.="<button type='button' class='SaveErienceCare btn btn-info btn-icon mtop10'>"._l('cong_save')."</button>
                                                        <button type='button' class='btn btn-danger btn-icon mtop10 close_popover'>"._l('cong_close')."</button>";
                                        $htmlDrop = '<a class="PopverSelect2 pointer" data-toggle="popover" data-placement="left" title="'.$vExperience['name'].'" data-html="true" data-content="'.$DropdownList.'">
                                                        '.(!empty($detail_experience->listname) ? trim($detail_experience->listname) : "...").'
                                                    </a>';
                                    }
                                    else if($vExperience['type'] == 'staff')
                                    {
                                        $detail_experience = get_table_where_select_cong('tblcare_of_detail_experience', [
                                                'id_care_of' => $vData->id,
                                                'id_experience' => $vExperience['id'],
                                                'id_care_items' => $vProCare['id']
                                        ], '', 'name', 'row');

                                        $value_name = !empty($detail_experience->name) ? $detail_experience->name : "";

                                        $DropdownList = DropdownListexpErienceStaff($vExperience['id'], $value_name, $vData->id, $vProCare['id']);
                                        $DropdownList.="<button type='button' class='SaveErienceCare btn btn-info btn-icon mtop10'>"._l('cong_save')."</button>
                                                        <button type='button' class='btn btn-danger btn-icon mtop10 close_popover'>"._l('cong_close')."</button>";
                                        $htmlDrop = '<a class="PopverSelect2 pointer" data-toggle="popover" data-placement="left" title="'.$vExperience['name'].'" data-html="true" data-content="'.$DropdownList.'">
                                                        '.(!empty($value_name) ? get_stafF_full_name($value_name) : "...").'
                                                    </a>';
                                    }
                                    else if($vExperience['type'] == 'img')
                                    {
                                        $detail_experience = get_table_where_select_cong('tblcare_of_detail_experience', [
                                                'id_care_of' => $vData->id,
                                                'id_experience' => $vExperience['id'],
                                                'id_care_items' => $vProCare['id']
                                        ], '', 'id, name', 'result');

                                        $DropdownList = DropdownListexpErienceFile($vExperience['id'], $vData->id, '', 'file', $vProCare['id']);

                                        $DropdownList.="<button type='button' class='SaveFileErience btn btn-info btn-icon mtop10'>"._l('cong_save')."</button>
                                                        <button type='button' class='btn btn-danger btn-icon mtop10 close_popover'>"._l('cong_close')."</button>";

                                        $img = '';
                                        if(!empty($detail_experience))
                                        {
                                            $img = '<div class="preview_image" style="width: auto;">';
                                            $img .= '   <div class="display-block contract-attachment-wrapper img">';
                                            $img .= '       <div style="margin: auto;display: flex;">';
                                            foreach($detail_experience as $kImg => $vImg)
                                            {
                                                $img .= '       <a href="'.base_url('download/preview_image?path=uploads/care_of_client/'.$vData->id.'/'.$vExperience['id'].'/'.$vImg->name).'" data-lightbox="customer-profile" class="display-block mbot5 mleft5">';
                                                $img .= '           <img src="'.base_url('download/preview_image?path=uploads/care_of_client/'.$vData->id.'/'.$vExperience['id'].'/'.$vImg->name).'" class="image-small"/>';
                                                $img .= '       </a>';
                                                $img .= '       <a class="text-danger removeImg"  id_img="'.$vImg->id.'"  url="'.$vData->id.'/'.$vExperience['id'].'/'.$vImg->name.'">X</a>';
                                            }
                                            $img .='        </div>';
                                            $img .='    </div>';
                                            $img .='</div>';
                                        }
                                        $htmlDrop = $img.'<a class="PopverSelect2 pointer" data-toggle="popover" data-placement="left" title="'.$vExperience['name'].'" data-html="true" data-content="'.$DropdownList.'">
                                                        ...
                                                    </a>';
                                    }
                                    else
                                    {
                                        $detail_experience = get_table_where_select_cong('tblcare_of_detail_experience', [
                                                'id_care_of' => $vData->id,
                                                'id_experience' => $vExperience['id'],
                                                'id_care_items' => $vProCare['id']
                                        ], '', 'name', 'row');

                                        $class = '';
                                        $value_name = '';
                                        if(!empty($detail_experience->name))
                                        {
                                            $value_name = $detail_experience->name;
                                        }
                                        if($vExperience['type'] == 'date'){
                                            $class = "datepicker";
                                            $value_name = !empty($detail_experience->name) ? _dC($detail_experience->name) : '';
                                        }
                                        else if($vExperience['type'] == 'datetime'){
                                            $class = "datetimepicker";
                                            $value_name = !empty($detail_experience->name) ? _dt($detail_experience->name) : '';
                                        }
                                        $DropdownList = DropdownListexpErienceType($vExperience['id'], $value_name, $vData->id, $class, $vProCare['id']);
                                        $DropdownList.="<button type='button' class='SaveErienceCare btn btn-info btn-icon mtop10'>"._l('cong_save')."</button>
                                                        <button type='button' class='btn btn-danger btn-icon mtop10 close_popover'>"._l('cong_close')."</button>";
                                        $htmlDrop = '<a class="PopverSelect2 pointer" data-toggle="popover" data-placement="left" title="'.$vExperience['name'].'" data-html="true" data-content="'.$DropdownList.'">
                                                    '.(!empty($value_name) ? trim($value_name) : "...").'
                                                </a>';

                                    }
                                    echo '<div class="mbot5 viewchange">'.($KeySTT+1).'.'.($kProCare+1).'. '.$vExperience['name'].': '.$htmlDrop.'</div>';
	                                $KeySTT = ($KeySTT + 1);
                                }

                            }
                        }
                        //End các phản hồi chăm sóc của sản phẩm
                     ?>

                    <div>
                        <h5 class="lbl-title-silver">3. <?=_l('cong_consulting_process')?></h5>
                    </div>
	                <?php if(!empty($vData)){?>
		                <?php
                            $html_progressbar = '';
                            $html_progressbar_img = '';
		                ?>
		                <?php $html_progressbar.='<ul class="progressbar">';?>
		                <?php $html_progressbar_img.='<ul  class="progressbar_img" style="display: flex;flex-direction: row;justify-content: left;">';?>
		                <?php $active = 1;?>
		                <?php foreach($vData->detail as $key => $value){?>

			                <?php
                                $active = false;
                                if(( ($key == 0 || !empty($vData->detail[$key - 1]->active) ) && empty($value->active)) || !empty($value->not_procedure))
                                {
                                    $active = true;
                                }

                                $next_step = false;
                                if(empty($value->active))
                                {
                                    if(strtotime($value->date_expected) <= strtotime(date('Y-m-d')))
                                    {
                                        $next_step = true;
                                    }
                                }
                                $next_step = true;
                                if(!empty($value->date_create))
                                {
                                    $first_date = strtotime($vData->date);
                                    $second_date = strtotime($value->date_create);
                                    $datediff = abs($first_date - $second_date);
                                    $day =  floor($datediff / (60*60*24));
                                }
			                ?>
			                <?php $html_progressbar.='<li class="'.(!empty($value->active) ? "active" : "").(!empty($value->not_procedure) ? ' initli' : '').'">' ?>
			                <?php $html_progressbar.='<a class="'.(!empty($value->active) ? 'text-success' : (!empty($next_step) ? ('text-danger') : '')).' '.(!empty($active) ? 'update_status_care_of' : '').'" id-data="'.$vData->id.'" status-procedure="'.$value->status_procedure.'">';?>
			                <?php $html_progressbar.='<p>';?>
			                <?php  $html_progressbar.= $value->name_status.'<br/>'; ?>
			                <?php
                                if(!empty($value->active)) {
                                    $html_progressbar .= '<i class="text-success">'._l('finished').'<br/>(' . (_dt($value->date_create, false)) . ')'.'</i>';
                                    $html_progressbar .= '<br/><i class="text-danger">'._l('cong_date_expected').'<br/>('._dC($value->date_expected).')'.'</i>';
                                    $html_progressbar .= '<br/><i class="text-warning">'.$day." "._l('cong_day').'</i>';
                                }
                                else
                                {
                                    $html_progressbar .= ' <i>(' . (_dC($value->date_expected, false)) . ')</i>';
                                }
			                ?>
			                <?php $html_progressbar .= '</p>';?>
			                <?php $html_progressbar .= '</a>';?>
			                <?php $html_progressbar .= '</li>';?>
			                <?php $html_progressbar_img .= '<li>'.staff_profile_image($value->create_by, ['staff-profile-image-small'],'small',[
					                'data-toggle' => 'tooltip',
					                'data-title' => !empty($value->create_by) ? get_staff_full_name($value->create_by) : ''
				                ]).'</li>'?>

		                <?php } ?>
		                <?php
                            $html_progressbar .= '</ul>';
                            $html_progressbar_img .= '</ul>';
		                ?>
	                <?php } else {
		                $html_progressbar .= '<p class="text-danger">'.mb_strtoupper(_l('cong_not_advisory_panel'), 'UTF-8').'</p>';
	                } ?>

	                <?php
	                    echo $html_progressbar_img.$html_progressbar;
	                ?>
                </div>
                <div class="mbot5"></div>
            </div>
        </div>
    <?php } ?>
<?php } ?>


<script type="text/javascript">

    $('body').on('shown.bs.popover', '#list_care_of .PopverSelect2', function(e) {
        var id = $(this).attr('aria-describedby');
        if($('#'+id).find("select.SelectErience").length)
        {
            $('#'+id).find("select.SelectErience").select2({
                escapeMarkup: function(m) { return m; }
            });
        }
        init_datepicker();
    })

    $('body').on('keyup', 'input.SelectErience',function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            $(this).parents('.popover-content').find('.SaveErience').trigger('click');
        }
    });

    appValidateForm($('.form_care_of'), {}, manageCare_of_client);

    function manageCare_of_client(form) {
        var button = $('.form_care_of').find('button[type="submit"]');
        button.button({loadingText: "<i class='fa fa-spinner fa-spin'></i>"});
        button.button('loading');
        var data = $(form).serialize();
        var url = form.action;
        $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            alert_float(response.alert_type, response.message);
            if (response.success == true) {
                $('.form_care_of').find('.action_profile').addClass('hide');
                $('.form_care_of').find('#update_profile').removeClass('hide');
                var id_facebook = $('#id_facebook').val();
                varInfoUser(id_facebook);
            }
        }).always(function() {
            button.button('reset');
        });
        return false;
    }

    $('.update_status_care_of').click(function(e){
        var id_assigned  = $(this).attr('id-data');
        var status_procedure  = $(this).attr('status-procedure');
        if($.isNumeric(id_assigned)) {
            var data = {};
            var button = $(this);
            button.button({loadingText: '<?=_l('cong_please_wait')?>'});
            button.button('loading');
            if (typeof (csrfData) !== 'undefined') {
                data[csrfData['token_name']] = csrfData['hash'];
            }
            data['id'] = id_assigned;
            data['status_procedure'] = status_procedure;
            $.post(admin_url + 'care_of_clients/update_status/', data, function (data) {
                data = JSON.parse(data);
                if (data.success) {
                    var id_facebook = $('#id_facebook').val();
                    varInfoUser(id_facebook);
                }
                alert_float(data.alert_type, data.message);
            }).always(function () {
                button.button('reset')
            });
        }
    })
</script>
    
</script>
