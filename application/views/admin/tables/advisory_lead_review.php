<?php

defined('BASEPATH') or exit('No direct script access allowed');
$statusActive = StatusActiveAdvisory();
if(!empty($id_client)) {
	$where_advisory =  ' (id_object = '.$id_client.' AND type_object = "client") ' ;
	if(!empty($leadid)) {
		$where_advisory .=  ' OR (id_object = '.$leadid.' AND type_object = "lead")' ;
	}
}




$start = $this->ci->input->post('start');
$length = $this->ci->input->post('length');




// lấy tất cả khách hàng và liên hệ thỏa điều kiện
$All_data = $this->ci->db->query('
    SELECT
        id,
        concat(COALESCE(prefix), COALESCE(code), "-", COALESCE(type_code)) as full_code_advisory,
        date_create,
        status_active,
        type_object_it,
        id_object_it
    FROM tbladvisory_lead  '.(!empty($where_advisory) ? (' where '.$where_advisory ) : '' )

)->num_rows();

// lấy giới hạn khách hàng và liên hệ
$limit_data = $this->ci->db->query('
    SELECT 
        id,
        concat(COALESCE(prefix), COALESCE(code), "-", COALESCE(type_code)) as full_code_advisory,
        date_create,
        status_active,
        type_object_it,
        id_object_it
    FROM tbladvisory_lead '.(!empty($where_advisory) ? (' where '.$where_advisory ) : '' ) .' limit '.$start.','.$length
)->result_array();


$draw = $this->ci->input->post('draw');
$result = array(
	'rResult' => $limit_data,
	'output' => array(
		"draw" => $draw,
		"iTotalRecords" => $All_data,
		"iTotalDisplayRecords" => $All_data,
		"aaData" => array()
	)

);
$output  = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {
    $row = [];
    $row[] = '<p class="one-control pointer">'.$aRow['full_code_advisory'].'</p>';

    $row[] = priority_level_advisory($aRow['status_active'], $aRow['id'], true);

    $activeStatusRow = $statusActive[$aRow['status_active']];

    $htmlLi = '';
	$OneChangeUpdate = 1;
    $status_active ='<span class="inline-block label '.$activeStatusRow['class'].'">
                    '.$activeStatusRow['name'].'
                    </span>';
    $row[] = $status_active;


	if(empty($info_view_detail)) {
		$info_view_detail = get_table_where('tblclient_info_detail', ['view_modal' => 1]);
	}

	foreach($info_view_detail as $key => $value) {
		$this->ci->db->select('name, value_info');
		$this->ci->db->join('tblclient_info_detail_value','tblclient_info_detail_value.id = tbladvisory_info_value.value_info')
		->where('id_advisory', $aRow['id'])
		->where('id_info', $value['id']);
		$advisory_info = $this->ci->db->get('tbladvisory_info_value')->row();

		$ValShow = !empty($advisory_info->name) ? $advisory_info->name : '';
		$KeyShow = !empty($advisory_info->value_info) ? $advisory_info->value_info : '';
		$row[] = EditColumSelectInput($KeyShow, $aRow['id'], $value['id'], $ValShow, admin_url('leads/Search_infoDetail/'.$value['id']), admin_url('advisory_lead/updateColumsInfo'),'class="formUpdateDataTable"');
	}

	if(!empty($aRow['type_object_it'])) {
		if($aRow['type_object_it'] == 'lead') {
			$lead = get_table_where('tblleads', ['id' => $aRow['id_object_it']], '', 'row');
		}
		else if($aRow['type_object_it'] == 'client') {

		}
	}
	else {

	}

    $row[] = '';
    $row[] = _dt($aRow['date_create']);
    $output['aaData'][] = $row;
}
