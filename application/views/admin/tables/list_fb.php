<?php

defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
	'tbllist_fb.avatar as avatar',
	'concat(COALESCE(tbllist_fb.prefix),COALESCE(tbllist_fb.code)) as code_list',
	'tbllist_fb.name_system as name_system',
	'tbllist_fb.name_facebook as name_facebook',
	'tbllist_fb.name as name',
	'tbllist_fb.gender as gender',
	'tbllist_fb.religion as religion',
	'tbllist_fb.birtday as birtday',
	'ROUND(DATEDIFF(CURDATE(), tbllist_fb.birtday) / 365, 0) AS years', // tuổi
	'tbllist_fb.note as note',
	'tbllist_fb.id_fanpage as id_fanpage',
	'tbllist_fb.id_facebook as id_facebook',
	'tbllist_fb.email_facebook as email_facebook'
];

$sIndexColumn = 'id';
$sTable       = 'tbllist_fb';
$join         = [
	'LEFT JOIN tblcombobox_client ON tblcombobox_client.id = tbllist_fb.religion',
	'LEFT JOIN tbltoken_fanpage ON tbltoken_fanpage.id_fanpage = tbllist_fb.id_fanpage'
];

$where = [];

if($this->ci->input->post()) {
	if($this->ci->input->post('code')) {
		$where[] = 'AND concat(COALESCE(tbllist_fb.prefix, ""), COALESCE(tbllist_fb.code, "")) LIKE "%'.$this->ci->input->post('code').'%"';
	}

	if($this->ci->input->post('name')) {
		$where[] = 'AND name_system LIKE "%'.$this->ci->input->post('name').'%"';
	}

	if($this->ci->input->post('object_type')) {
		$object_type = $this->ci->input->post('object_type');
		if($object_type == 'lead') {

			$where[] = 'AND (tbllist_fb.type_custom = "lead")';


//			$join[] = 'JOIN tblleads on tblleads.id_facebook = tbllist_fb.id_facebook and tblleads.id_facebook is not null';
//			$join[] = 'LEFT JOIN tblclients on tblclients.id_facebook = tbllist_fb.id_facebook';
//			$where[] = 'AND (tblleads.id_facebook is not null AND tblclients.id_facebook is null)';
		}
		else if($object_type == 'client') {
//			$join[] = 'JOIN tblclients on tblclients.id_facebook = tbllist_fb.id_facebook and tblclients.id_facebook is not null';
//			$where[] = 'AND (tblclients.id_facebook is not null)';
			$where[] = 'AND (tbllist_fb.type_custom = "client")';
		}
	}
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
	'tbllist_fb.id',
	'tblcombobox_client.name as name_religion',
	'tbltoken_fanpage.name_fanpage as name_fanpage'
]);


$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
	$row = [];
	$avatar = '<img src="'.base_url().'download/preview_image?path=uploads/avatarFB/'.$aRow['id'].'/thumb_'.$aRow['avatar'].'" class="img img-responsive staff-profile-image-small pull-left">';
	$row[] = $avatar;
	$row[] = $aRow['code_list'];

	$notConnect = false;
	$this->ci->db->where('id_facebook' , $aRow['id_facebook']);
	$row_clients = $this->ci->db->get('tblclients')->num_rows();
	if(empty($row_clients)) {
		$this->ci->db->where('id_facebook' , $aRow['id_facebook']);
		$row_leads = $this->ci->db->get('tblleads')->num_rows();
		if(!empty($row_leads)) {
			$notConnect = true;
		}
	}
	else {
		$notConnect = true;
	}
	if(!empty($notConnect)) {
		$statusMapFB = '<br/><span class="label label-default inline-block span-tag-object" data-title="'._l('cong_connect_client').'" data-color="#0fc31d" style="color:white;background-color:#0fc31d">'._l('cong_connect_client').'</span>';
	}
	else {
		$statusMapFB = '<br/><span class="label label-default inline-block span-tag-object" data-title="'._l('cong_not_connect_client').'" data-color="red" style="color:white;background-color:red">'._l('cong_not_connect_client').'</span>';
	}


	$row[] = $aRow['name_system'].$statusMapFB;
	$row[]  = EditColumInput($aRow['name_facebook'], $aRow['id'], 'name_facebook', admin_url('list_fb/updateColums'),'class="formUpdateDataTable"');
	$row[]  = EditColumInput($aRow['name'], $aRow['id'], 'name', admin_url('list_fb/updateColums'),'class="formUpdateDataTable"');

	$gender = [
		['id' => 1, 'name' => _l('cong_male')],
		['id' => 2, 'name' => _l('cong_female')],
	];
	$row[] = EditColumSelect($aRow['gender'], $gender, ['id', 'name'], $aRow['id'], 'gender', admin_url('list_fb/updateColums'),'class="formUpdateDataTable"');

	$row[] = EditColumSelectInput($aRow['religion'], $aRow['id'], 'religion', $aRow['name_religion'], admin_url('leads/Search_combobox_client/religion'), admin_url('list_fb/updateColums'),'class="formUpdateDataTable"');
	$row[] = EditColumInput(_dt($aRow['birtday']), $aRow['id'], 'birtday', admin_url('leads/updateColums'),'class="formUpdateDataTable"', 'datetimepicker');
	$row[] = $aRow['years'];
	$row[] = EditColumInput($aRow['note'], $aRow['id'], 'note', admin_url('list_fb/updateColums'),'class="formUpdateDataTable"');

	$row[] = !empty($aRow['name_fanpage']) ? $aRow['name_fanpage'] : ('<p style="opacity: 0.6;">'.(_l('cong_not_found_fanpage_to_data').' '. $aRow['id_fanpage']).' '._l('cong_in_system').'</p>');
	$row[] = $aRow['id_facebook'];
	$row[] = $aRow['email_facebook'];
	$output['aaData'][] = $row;
}

$this->ci->db->where('type_custom', 'lead');
$output['count']['count_lead'] = $this->ci->db->get('tbllist_fb')->num_rows();

$this->ci->db->where('type_custom', 'client');
$output['count']['count_client'] = $this->ci->db->get('tbllist_fb')->num_rows();

$output['count']['count_all'] = $this->ci->db->get('tbllist_fb')->num_rows();
