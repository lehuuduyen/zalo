<?php

defined('BASEPATH') or exit('No direct script access allowed');

$this->ci->db->order_by('id', 'desc');
$experience = $this->ci->db->get('tblexperience_advisory')->result_array();

$statusActive = StatusActiveAdvisory();

$Criteria = StatusCriteria();

$aColumns = [
	'tbladvisory_lead.id',
    'id_object',
	'if(tbladvisory_lead.type_object = "lead", concat("uploads/leads/", clead.id, "/small_", clead.lead_image), concat("uploads/clients/", cClient.userid, "/small_", cClient.client_image))  as img',
	'if(tbladvisory_lead.type_object = "lead", clead.code_system, cClient.code_system)  as code_system',
    'type_object_it',
    '1',
    'status_active',
    'if (tbladvisory_lead.type_object = "lead", clead.zcode, cClient.zcode)  as zcode',
    'criteria_one',
    'criteria_two',
    'product_other_buy',
    'address_other_buy',
    'date',
    'if (tbladvisory_lead.type_object = "lead", clead.dateadded, cClient.datecreated)  as dateCreate', // ngày tạo
    'if (tbladvisory_lead.type_object = "lead", clead.addedfrom, cClient.addedfrom)  as staffCreate', // nhân viên tạo khtn
    'date_create',
    'create_by'
];

foreach($experience as $key => $value)
{
    $aColumns[] = $value['id'];
}
$aColumns[] = 'note_appointment';
$aColumns[] = 'note_reason_spam';
$aColumns[] = 'note_reason_stop';
$aColumns[] = 'staff_appointment';
$sIndexColumn = 'id';
$sTable       = 'tbladvisory_lead';
$where        = [];

$filter = [];

if(!empty($this->ci->input->post('procedure'))) {

	$procedure = $this->ci->input->post('procedure');
}
if(!empty($this->ci->input->post('datestart'))) {

	$datestart = to_sql_date($this->ci->input->post('datestart'));
}
if(!empty($this->ci->input->post('dateend'))) {

	$dateend = to_sql_date($this->ci->input->post('dateend'));
}

$_whereCI = '';
if(!empty($datestart))
{
    $_whereCI = 'AND DATE_FORMAT(date_expected, "%Y-%m-%d") >="'.$datestart.'"';
}
if(!empty($dateend)) {
	$_whereCI .= 'AND DATE_FORMAT(date_expected, "%Y-%m-%d") <="' . $dateend . '"';
}
if(!empty($procedure))
{
    $_whereCI .= 'AND status_procedure ="'.$procedure.'"';
}
if(!empty($_whereCI))
{
	$_whereCI = 'where tbladvisory_lead.id = tblprocedure_advisory_lead.id_advisory and active = 1 '.($_whereCI);
	$where[] = 'AND tbladvisory_lead.id IN (select id_advisory from tblprocedure_advisory_lead '.$_whereCI.')';
}
if($this->ci->input->post())
{
    if(!empty($this->ci->input->post('name_lead')))
    {
        $where[] = 'AND clead.name like "%'.$this->ci->input->post('name_lead').'%"';
    }

    if(!empty($this->ci->input->post('code_advisory')))
    {
        $where[] = 'AND concat('.db_prefix().'advisory_lead.prefix,'.db_prefix().'advisory_lead.code) like "%'.$this->ci->input->post('code_advisory').'%"';
    }
    if(!empty($this->ci->input->post('code_lead')))
    {
        $where[] = 'AND concat(prefix_lead,code_lead) like "%'.$this->ci->input->post('code_lead').'%"';
    }
    if(!empty($this->ci->input->post('vip_rating_lead')))
    {
        $where[] = 'AND clead.vip_rating ='.$this->ci->input->post('vip_rating_lead');
    }

}

$join[] = 'LEFT JOIN tblstaff cby on cby.staffid = tbladvisory_lead.create_by';
$join[] = 'LEFT JOIN tblleads clead on clead.id = tbladvisory_lead.id_object  and type_object = "lead"';
$join[] = 'LEFT JOIN tblclients cClient on cClient.userid = tbladvisory_lead.id_object  and type_object = "client"';

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where,[
    'tbladvisory_lead.id',
    'concat(tbladvisory_lead.prefix,tbladvisory_lead.code,"-", type_code) as fullcode',
    'cby.firstname as cbyfirstname',
    'cby.lastname as cbylastname',
    'date',
    'type_object',
    'status_break',
    'concat(clead.prefix_lead,clead.code_lead) as fullcode_lead',
    'tbladvisory_lead.status_first as status_first',
    'cClient.name_system as name_system_client',
    'clead.name_system as name_system_lead',
    'cClient.code_system as code_system_client',
    'clead.code_system as code_system_lead',
	'type_object_it',
	'id_object_it',
]);

$output  = $result['output'];
$rResult = $result['rResult'];

$count_success = 0;
$all_count = count($rResult);
foreach ($rResult as $aRow) {
    $row = [];
    $options = '<div class="row-options">';
    $options .= '<a href="#" class="" onclick="editAdvisory_lead('.$aRow['id'].', this); return false;">'._l('edit').'</a> |<br/> ';
//    if($aRow['status_break'] != 1)
//    {
//        $options .= '<a href="#" class="text-success" onclick="restore_advisory_lead('.$aRow['id'].', this); return false;">'._l('cong_restore').'</a> | ';
//    }
    $options .= '<a href="#" class="text-warning" onclick="BreakAdvisory('.$aRow['id'].', '.($aRow['status_break'] == 1 ? 0 : 1).', this); return false;">'.($aRow['status_break'] == 1 ? _l('cong_restore_break_advisory') : _l('cong_break_advisory')).'</a> | ';
    $options .= '<a href="#" class="text-danger" onclick="deleteAdvisory_lead('.$aRow['id'].', this); return false;">'._l('delete').'</a>';
    $options .= '</div>';


    $row[] = '<p class="one-control pointer">'.$aRow['fullcode'].'</p>'.$options;

    if($aRow['type_object'] == 'lead')
    {
        $row[] = '<a class="pointer" onclick="init_lead('.$aRow['id_object'].');return false;">'.$aRow['name_system_lead'].'</a>';
    }
    else
    {
        $row[] = '<a class="pointer" onclick="init_lead('.$aRow['id_object'].');return false;">'.$aRow['name_system_client'].'</a>';

    }
	$row[] = '<img src="'.base_url('download/preview_image?path='.$aRow['img']).'" class="staff-profile-image-small">';
    $row[] = $aRow['code_system'];
	if(!empty($aRow['type_object_it']))
	{
		if($aRow['type_object_it'] == 'lead')
		{
			$lead = get_table_where('tblleads', ['id' => $aRow['id_object_it']], '', 'row');
			if(!empty($lead))
			{
				$row[] = '<a class="pointer" onclick="init_lead('.$aRow['id_object_it'].');return false;">'._l('cong_buy_it_object').' '.$lead->name_system.'</a>';
			}
			else
			{
				$row[] = '';
			}
		}
		else
		{
			$clients = get_table_where('tblclients', ['userid' => $aRow['id_object_it']], '', 'row');
			if(!empty($clients))
			{
				$row[] = '<a class="pointer" onclick="init_lead('.$aRow['id_object_it'].');return false;">'._l('cong_buy_it_object').' '.$clients->name_system.'</a>';
			}
			else
			{
				$row[] = '';
			}
		}
	}
	else
	{
		$row[] = _l('cong_buy_this');
	}

    $row[] = priority_level_advisory($aRow['status_active'], $aRow['id'], true);

    $activeStatusRow = $statusActive[$aRow['status_active']];

    $htmlLi = '';
    foreach($statusActive as $kActive => $vActive)
    {
        $htmlLi .= '<li><a class="AStatusAdvisory" status-table="'.$kActive.'" id-data="'.$aRow['id'].'">'.$vActive['name'].'</a></li>';
    }

    $status_active ='<span class="inline-block label '.$activeStatusRow['class'].'">
                    '.$activeStatusRow['name'].'
                        <div class="dropdown inline-block mleft5 table-export-exclude">
                            <a href="#" style="font-size:14px;vertical-align:middle;" class="dropdown-toggle text-dark"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span data-toggle="tooltip" title="'.$activeStatusRow['name'].'">
                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">'.$htmlLi.'</ul>
                        </div>
                    </span>';
    $row[] = $status_active;

    $row[] = $aRow['zcode'];



    $htmlLicriteria = '';
    foreach($Criteria as $kCriteria => $vCriteria)
    {
        if(!empty($kCriteria))
        {
            $htmlLicriteria .= '<li><a class="criteria" status-table="'.$kCriteria.'" id-data="'.$aRow['id'].'">'.$vCriteria['name'].'</a></li>';
        }
    }
    $Criteria_one_active = $Criteria[$aRow['criteria_one']];

    $DropdowLicriteria_one ='<span class="inline-block label '.$Criteria_one_active['class'].'">
                    '.$Criteria_one_active['name'].'
                        <div class="dropdown inline-block mleft5 table-export-exclude">
                            <a href="#" style="font-size:14px;vertical-align:middle;" class="dropdown-toggle text-dark"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span data-toggle="tooltip" title="'.$Criteria_one_active['name'].'">
                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right"  colums="criteria_one">'.$htmlLicriteria.'</ul>
                        </div>
                    </span>';
    $row[] = $DropdowLicriteria_one;

    $Criteria_two_active = $Criteria[$aRow['criteria_two']];
    $DropdowLicriteria_two ='<span class="inline-block label '.$Criteria_two_active['class'].'">
                    '.$Criteria_two_active['name'].'
                        <div class="dropdown inline-block mleft5 table-export-exclude">
                            <a href="#" style="font-size:14px;vertical-align:middle;" class="dropdown-toggle text-dark"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span data-toggle="tooltip" title="'.$Criteria_two_active['name'].'">
                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right"  colums="criteria_two">'.$htmlLicriteria.'</ul>
                        </div>
                    </span>';
    $row[] = $DropdowLicriteria_two;




//    $row[] = $aRow['criteria_two'];

	$row[] = EditColumInput($aRow['product_other_buy'], $aRow['id'], 'product_other_buy', admin_url('advisory_lead/updateColums'),'class="formUpdateDataTable"');
	$row[] = EditColumInput($aRow['address_other_buy'], $aRow['id'], 'address_other_buy', admin_url('advisory_lead/updateColums'),'class="formUpdateDataTable"');




	if(empty($info_view_detail)) {
		$info_view_detail = get_table_where('tblclient_info_detail', ['view_modal' => 1]);
	}
	foreach($info_view_detail as $key => $value)
	{
		$this->ci->db->select('group_concat(name) as fullname');
		$this->ci->db->join('tblclient_info_detail_value','tblclient_info_detail_value.id = tbladvisory_info_value.value_info')
		->where('id_advisory', $aRow['id'])
		->where('id_info', $value['id']);
		$advisory_info = $this->ci->db->get('tbladvisory_info_value')->row();
		$row[] = $advisory_info->fullname;
	}



    $row[] = _dC($aRow['date']);
    $row[] = _dt($aRow['dateCreate']);

    $fullnameStaffCreate = get_staff_full_name($aRow['staffCreate']);
    $profileStaffCreate = '<a data-toggle="tooltip" data-title="' . $fullnameStaffCreate . '" href="' . admin_url('profile/' . $aRow['staffCreate']) . '">' . staff_profile_image($aRow['staffCreate'], [
            'staff-profile-image-small',
        ]) . '</a>';
    $profileStaffCreate .= '<span class="hide">' . $fullnameStaffCreate . '</span>';
    $row[] = $profileStaffCreate;

    $row[] = _dt($aRow['date_create']);

    $fullname_CREATE = $aRow['cbylastname'] . ' ' . $aRow['cbyfirstname'];
    $profile_CREATE = '<a data-toggle="tooltip" data-title="' . $fullname_CREATE . '" href="' . admin_url('profile/' . $aRow['create_by']) . '">' . staff_profile_image($aRow['create_by'], [
            'staff-profile-image-small',
        ]) . '</a>';
    $profile_CREATE .= '<span class="hide">' . $fullname_CREATE . '</span>';
    $row[] = $profile_CREATE;

    $dateContact = new DateTime($aRow['date']);
    $dateCreate = new DateTime($aRow['dateCreate']);
    $interval = $dateContact->diff($dateCreate);
    $day = $interval->d;
    $row[] = $day;


    $dateCreate = new DateTime($aRow['dateCreate']);
    $dateCreate_advisory = new DateTime($aRow['date_create']);
    $interval = $dateCreate->diff($dateCreate_advisory);
    $day = $interval->d;
    $row[] = $day;

    if($aRow['status_break'] == 1)
    {
        $row['DT_RowClass'] = 'bg-danger';
    }

    $Row_procedure_img = '<div class="text-center mw800">';
    $Row_procedure_img .= '   <ul class="progressbar_img" style="display: flex;flex-direction: row;justify-content: left;">';

    $string_Row = '<ul class="progressbar">';
    $status_first = 0;
    $content_dk = ""; //Điều kiện để chạy tiếp theo

    $this->ci->db->where('id_advisory', $aRow['id']);
    $this->ci->db->order_by('orders_status  asc');
    $procedure_status = $this->ci->db->get('tblprocedure_advisory_lead')->result_array();
    foreach($procedure_status as $key => $value)
    {
		$active = false;
		if(( ($key == 0 || !empty($procedure_status[$key - 1]['active']) ) && empty($value['active'])) || !empty($value['not_procedure']))
		{
			$active = true;
		}

        $color_text = "";
        $color_date_more = "";
        if($value['active'] == 1)
        {
            $title_data_finish = _l('finished_short').' ('._dC($value['date_create']).')';
            $color_text = 'text-success';
            if(!empty($value['date_create']))
            {
                $dateAdvisory = strtotime($aRow['date']);
                $dateCreate = strtotime($value['date_create']);
                $datediff = abs($dateAdvisory - $dateCreate);

	            $date_expected = strtotime($value['date_expected']);
	            $date_create = strtotime($value['date_create']);
	            $datediff = abs($date_expected - $date_create);

	            $DayMore =  floor($datediff / (60*60*24));
	            if($date_expected < $date_create && $DayMore != 0)
	            {
		            $color_date_more = ' color-more-time';
	            }

            }

	        if(empty($value['not_procedure'])) {
		        $title_data_finish .= "<br/><i class='text-danger'>" . _l('cong_date_expected_short') . "(" . _dC($value['date_expected']) . ")</i>";
	        }
            $title_data_finish .= "<br/><i class='text-warning ".$color_date_more."'>".$day.' '._l('cong_day')."</i>";
        }
        else
        {
            $color_text = 'text-danger';
            if(empty($value['not_procedure']))
            {
                $title_data_finish = _l('cong_date_expected_short').' ('._dC($value['date_expected']).')';
            }
            else
            {
	            $title_data_finish = '';
            }
        }
        $string_Row .= '<li class="'.($value['active'] == 1 ? 'active' : '').(!empty($value['not_procedure']) ? ' initli' : '').'">';
        $string_Row .= '    <a  class="pointer '.(!empty($active) ? 'update_status_lead ' : '').$color_text.'"   status-procedure="'.$value['status_procedure'].'" id-data="'.$aRow['id'].'">';
        $string_Row .=          mb_convert_case($value['name_status'], MB_CASE_TITLE, "UTF-8").'</br>'.'<i class="'.$color_text.'">'.$title_data_finish.'</p>';
        $string_Row .=      '</a>';
        $string_Row .='</li>';

        $Row_procedure_img .='<li>'.staff_profile_image($value['create_by'], ['staff-profile-image-smalls'],'small',[
                'data-toggle' => 'tooltip',
                'data-title' => !empty($value['create_by']) ? get_staff_full_name($value['create_by']) : ''
            ]).'</li>';

    }
    $string_Row.='</ul>';

    $Row_procedure_img .= '</ul>';
    $Row_procedure_img .= '</p>';
    $row[] = $Row_procedure_img.$string_Row;

    $row[] = EditColumInput($aRow['note_appointment'], $aRow['id'], 'note_appointment', admin_url('advisory_lead/updateColums'),'class="formUpdateDataTable"');
    $row[] = EditColumInput($aRow['note_reason_spam'], $aRow['id'], 'note_reason_spam', admin_url('advisory_lead/updateColums'),'class="formUpdateDataTable"');
    $row[] = EditColumInput($aRow['note_reason_stop'], $aRow['id'], 'note_reason_stop', admin_url('advisory_lead/updateColums'),'class="formUpdateDataTable"');
	$ValShow = get_staff_full_name($aRow['staff_appointment']);
	$row[] = EditColumSelectInput($aRow['staff_appointment'], $aRow['id'], 'staff_appointment', $ValShow, admin_url('advisory_lead/Search_Staff'), admin_url('advisory_lead/updateColums'),'class="formUpdateDataTable"');

	foreach($experience as $key => $value)
    {
        $this->ci->db->select('group_concat(name separator "</br>") as listname, group_concat(id_experience_detail) as listid');
        $this->ci->db->where('id_advisory', $aRow['id']);
        $this->ci->db->where('id_experience', $value['id']);
        $detail_experience = $this->ci->db->get('tbladvisory_detail_experience')->row();
        $DropdownList = DropdownListexpErience($value['id'], explode(',', $detail_experience->listid), $aRow['id']);
        $DropdownList.="<button type='button' class='SaveErience btn btn-info btn-icon mtop10'>"._l('cong_save')."</button><button type='button' class='btn btn-danger btn-icon mtop10 close_popover'>"._l('cong_close')."</button>";
        $row[] = '<a href="#" class="PopverSelect2" data-toggle="popover" data-placement="left" title="'.$value['name'].'" data-html="true" data-content="'.$DropdownList.'">
                        '.(!empty($detail_experience->listname) ? trim($detail_experience->listname) : "<i class='fa fa-pencil-square-o' aria-hidden='true'></i>").'
                 </a>';
    }
    $output['aaData'][] = $row;
}
