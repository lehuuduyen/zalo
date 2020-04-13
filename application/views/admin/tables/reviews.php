<?php

defined('BASEPATH') or exit('No direct script access allowed');


if(!empty($id_client)) {
	$where_advisory =  ' (id_object = '.$id_client.' AND type_object = "client") ' ;
	if(!empty($leadid)) {
		$where_advisory .=  ' OR (id_object = '.$leadid.' AND type_object = "lead")' ;
	}

	$where_care_of = ' (client = '.$id_client.')';
}




$start = $this->ci->input->post('start');
$length = $this->ci->input->post('length');




// lấy tất cả khách hàng và liên hệ thỏa điều kiện
$All_data = $this->ci->db->query('
    SELECT
        id,
        concat(COALESCE(prefix), COALESCE(code), "-", COALESCE(type_code)) as full_code_advisory,
        0 as full_code_care_of,
        date,
        "advisory_lead" as type
    FROM tbladvisory_lead  '.(!empty($where_advisory) ? (' where '.$where_advisory ) : '' ).'
     UNION
    SELECT
        id,
        0 as full_code_care_of,
        concat(COALESCE(prefix), COALESCE(code), "-", short_theme) as full_code_care_of,
        date,
        "care_of_client" as type
    FROM tblcare_of_clients '.(!empty($where_care_of) ? (' where '.$where_care_of ) : '' )
)->num_rows();

// lấy giới hạn khách hàng và liên hệ
$limit_data = $this->ci->db->query('
    SELECT 
        id,
        concat(COALESCE(prefix), COALESCE(code), "-", COALESCE(type_code)) as full_code_advisory,
        0 as full_code_care_of,
        date,
        "advisory_lead" as type
    FROM tbladvisory_lead  '.(!empty($where_advisory) ? (' where '.$where_advisory ) : '' ).'
    UNION
    SELECT 
        id,
        0 as full_code_advisory,
        concat(COALESCE(prefix), COALESCE(code), "-", short_theme) as full_code_care_of,
        date,
        "care_of_client" as type
    FROM tblcare_of_clients '.(!empty($where_care_of) ? (' where '.$where_care_of ) : '' ) .' limit '.$start.','.$length
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
if(!empty($rResult)) {
	$query = '
        SELECT DISTINCT name, GROUP_CONCAT(id) as id from (
            SELECT name, concat(id, "_advisory") as id FROM tblexperience_advisory
            UNION 
            SELECT name, concat(id, "_care_of") as id FROM tblexperience_care_of_client WHERE theme is null order by name asc
        ) as experience 
        group by name
    ';
	$titleExprtience = get_table_query($query);


	$arrayAdvisory = [];
	$arrayCare_of = [];
	foreach($titleExprtience as $k => $v) {
		$exprtience = [];
		if(!empty($v['id'])) {
			$exprtience = explode(',', $v['id']);
			foreach($exprtience as $kk => $vv) {
				$exprtience_value = explode('_', $vv);
				if(!empty($exprtience_value)) {
					if($exprtience_value[1] == 'advisory') {
						$arrayAdvisory[$v['id']] = $exprtience_value[0];
					}
					else {
						$arrayCare_of[$v['id']] = $exprtience_value[0];
					}
				}
			}
		}
	}
}

foreach ($rResult as $key => $aRow) {
    $row = [];

    $row[] = !empty($aRow['full_code_advisory']) ? $aRow['full_code_advisory'] : '';
    $row[] = !empty($aRow['full_code_care_of']) ? $aRow['full_code_care_of'] : '';
	foreach($titleExprtience as $k => $v) {
		if($aRow['type'] == 'advisory_lead') {
			if(!empty($arrayAdvisory[$v['id']])) {
				$this->ci->db->select('group_concat(name separator "</br>") as asName');
				$this->ci->db->where('id_advisory', $aRow['id']);
				$this->ci->db->where('id_experience', $arrayAdvisory[$v['id']]);
				$experience = $this->ci->db->get('tbladvisory_detail_experience')->row();
				$row[] = $experience->asName;
			}
			else
			{
				$row[] = '';
			}
		}
		elseif($aRow['type'] == 'care_of_client') {
			if(!empty($arrayCare_of[$v['id']])) {
				$this->ci->db->select('group_concat(name separator "</br>") as asName');
				$this->ci->db->where('id_care_of', $aRow['id']);
				$this->ci->db->where('id_experience', $arrayCare_of[$v['id']]);
				$experience = $this->ci->db->get('tblcare_of_detail_experience')->row();
				$row[] = $experience->asName;
			}
			else
			{
				$row[] = '';
			}
		}

	}


    $output['aaData'][] = $row;
}
