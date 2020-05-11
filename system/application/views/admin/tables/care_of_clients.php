<?php

defined('BASEPATH') or exit('No direct script access allowed');

$this->ci->db->order_by('theme', 'ASC');
$this->ci->db->order_by('id', 'ASC');
$experience = $this->ci->db->get('tblexperience_care_of_client')->result_array();

$solution = care_solutions();

$aColumns = [
    'concat('.db_prefix().'care_of_clients.prefix,tblcare_of_clients.code,"-",short_theme) as fullcode',
    'theme_of', //chủ đề chăm sóc
	'c.name_system as fullname_client',
    'solution', // giải pháp chăm sóc
    'tblcare_of_clients.date', // Ngày khách phản hồi
    'c.zcode as zcode',
    'concat(COALESCE(orders.prefix), COALESCE(orders.code)) as code_orders', // Mã đơn hàng
    '2', // Sản phẩm trong đơn hàng
    '3', //Tên sản phẩm
];

foreach($experience as $key => $value)
{
    $aColumns[] = ''.$value['id'];
}

$aColumns[] = 'c.code_system'; //Mã Khách Hệ Thống
$aColumns[] = 'concat(COALESCE(lead.prefix_lead),COALESCE(lead.code_lead),"-",COALESCE(lead.zcode),"-",COALESCE(lead.code_type)) as lead_code'; //mã khách hàng tiêm năng
$aColumns[] = 'concat(COALESCE(c.prefix_client),COALESCE(c.code_client),"-",COALESCE(c.zcode),"-",COALESCE(c.code_type)) as client_code'; //Mã khách hàng
$aColumns[] = 'concat(COALESCE(c.prefix_client),COALESCE(c.code_client),"-",COALESCE(c.zcode),"-",COALESCE(c.code_type)) as client_code'; //Mã khách hàng hiện tại
$aColumns[] = 'tblcare_of_clients.date_create as date_create'; // Ngày tạo phiếu chăm sóc
$aColumns[] = 'tblcare_of_clients.create_by as create_by'; //Nhân viên tạo phiếu chăm sóc
$aColumns[] = 'date_success'; //Ngày hoàn thành phiếu chăm sóc
$aColumns[] = 'staff_success'; //Nhân viên hoàn thành chăm sóc


$sIndexColumn = 'id';
$sTable       = 'tblcare_of_clients';
$where        = [];
if(!empty($id_client))
{
    $where[] = 'AND client = '.$id_client;
}
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
	$_whereCI = 'where tblcare_of_clients.id = tblprocedure_care_of.id_care_of and active = 1 '.($_whereCI);
	$where[] = 'AND tblcare_of_clients.id IN (select id_care_of from tblprocedure_care_of '.$_whereCI.')';
}

if($this->ci->input->post())
{
    if(!empty($this->ci->input->post('name_client')))
    {
        $where[] = 'AND c.name_system like "%'.$this->ci->input->post('name_client').'%"';
    }

    if(!empty($this->ci->input->post('code_care_of')))
    {
        $where[] = 'AND concat(tblcare_of_clients.prefix, tblcare_of_clients.code) like "%'.$this->ci->input->post('code_care_of').'%"';
    }
    if(!empty($this->ci->input->post('code_client')))
    {
        $where[] = 'AND concat(prefix_client, prefix_client, "-", zcode, "-", code_type) like "%'.$this->ci->input->post('code_client').'%"';
    }
    if(!empty($this->ci->input->post('vip_rating_client')))
    {
        $where[] = 'AND c.vip_rating ='.$this->ci->input->post('vip_rating_client');
    }
    if(!empty($this->ci->input->post('vip_code')))
    {
    //    $where[] = 'AND '.db_prefix().'advisory_lead.concat(prefix,code) like%'.$this->ci->input->post('code_advisory').'%';
    }
}



$join[] = 'LEFT JOIN tblstaff cby on cby.staffid = tblcare_of_clients.create_by';
$join[] = 'LEFT JOIN tblstaff ss on ss.staffid = tblcare_of_clients.staff_success';
$join[] = 'LEFT JOIN tblorders orders on orders.id = tblcare_of_clients.id_orders';
$join[] = 'JOIN tblclients c on c.userid = tblcare_of_clients.client';
$join[] = 'LEFT JOIN tblleads lead on lead.id = c.leadid';

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where,[
    'tblcare_of_clients.id',
    'tblcare_of_clients.client',
    'cby.firstname as cbyfirstname',
    'cby.lastname as cbylastname',
    'id_orders',
    'ss.firstname as ssfirstname',
    'ss.lastname as sslastname',
    'tblcare_of_clients.date',
    'c.company as company',
    'status_break',
]);
$output  = $result['output'];
$rResult = $result['rResult'];

$count_success = 0;
$all_count = count($rResult);
foreach ($rResult as $aRow) {
    $row = [];

    $options = '<div class="row-options">';
    $options .= '<a href="#" class="" onclick="editCare_of_clients('.$aRow['id'].', this); return false;">'._l('edit').'</a> | ';
    $options .= '<a href="#" class="text-warning" onclick="BreakCare_of('.$aRow['id'].', '.($aRow['status_break'] == 1 ? 0 : 1).', this); return false;">'.($aRow['status_break'] == 1 ? _l('cong_restore_break_advisory') : _l('cong_break_advisory')).'</a> | ';
    $options .= '<a href="#" class="text-danger" onclick="deleteCare_of('.$aRow['id'].', this); return false;">'._l('delete').'</a>';
    $options .= '</div>';

    $row[] = '<p class="one-control pointer"><a onclick="ViewDetailCare_of('.$aRow['id'].', this)">'.$aRow['fullcode'].'</a></p>'.$options;
    $theme_of = StatusThemeCare_of($aRow['theme_of']);

    $row[] = !empty($theme_of['name']) ? $theme_of['name'] : '';

	$row[] = '<p>'.$aRow['fullname_client'].'</p>';


    $htmlSolution = '';
    foreach($solution as $kSolution => $vSolution)
    {
        if(!empty($kSolution))
        {
            $htmlSolution .= '<li><a class="solution" status-table="'.$kSolution.'" id-data="'.$aRow['id'].'">'.$vSolution['name'].'</a></li>';
        }
    }
    $Solution_active = !empty($solution[$aRow['solution']]) ? $solution[$aRow['solution']] : [];

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
    $row[] = $DropdowSolution;
    $row[] = _dt($aRow['date']);
    $row[] = priority_level_care_of_client($aRow['id'], true);

    $statusCare = get_table_where('tblprocedure_care_of', ['id_care_of' => $aRow['id'], 'active' => 1], 'orders desc', 'row');

    $row[] = !empty($statusCare) ? $statusCare->name_status : ' - ';

    $row[] = $aRow['zcode'];
    $row[] = '<a target="_blank" href="'.admin_url('orders/detail/'.$aRow['id_orders']).'">'.$aRow['code_orders'].'</a>';

	$row[] = '';
	$row[] = '';

	//Quy trình chăm sóc khách hàng
	$Row_procedure_img = '<div class="text-center mw800 mbot0">';
	$Row_procedure_img .= '   <ul class="progressbar_img" style="display: flex;flex-direction: row;justify-content: left;">';

	$string_Row = '			  <ul class="progressbar">';
	$status_first = 0;
	$content_dk = ""; //Điều kiện để chạy tiếp theo

	$this->ci->db->where('id_care_of', $aRow['id']);
	$this->ci->db->order_by('orders  asc');
	$procedure_status = $this->ci->db->get('tblprocedure_care_of')->result_array();
	foreach($procedure_status as $key => $value)
	{
		$active = false;
		if(( ($key == 0 || !empty($procedure_status[$key - 1]['active']) ) && empty($value['active'])) || !empty($value['not_procedure']))
		{
			$active = true;
		}

		$color_text = "";
		if($value['active'] == 1)
		{
			$title_data_finish = _l('finished_short').' ('._dC($value['date_create']).')';
			$color_text = 'text-success';
			if(!empty($value['date_create']))
			{
				$dateAdvisory = strtotime($aRow['date']);
				$dateCreate = strtotime($value['date_create']);
				$datediff = abs($dateAdvisory - $dateCreate);
				$day =  floor($datediff / (60*60*24));
			}

			if(empty($value['not_procedure'])) {
				$title_data_finish .= "<br/><i class='text-danger'>" . _l('cong_date_expected_short') . "(" . _dC($value['date_expected']) . ")</i>";
			}
			$title_data_finish .= "<br/><i class='text-warning'>".$day.' '._l('cong_day')."</i>";
		}
		else
		{
			$color_text = 'text-danger';
			if(empty($value['not_procedure']))
			{
				$title_data_finish = _l('cong_date_expected').' ('._dC($value['date_expected']).')';
			}
			else
			{
				$title_data_finish = '';
			}
		}
		$string_Row .= '<li class="'.($value['active'] == 1 ? 'active' : '').(!empty($value['not_procedure']) ? ' initli' : '').'">';
		$string_Row .= '    <a class="pointer '.(!empty($active) ? 'update_status_care_of ' : '').$color_text.'"  title="'.$title_data_finish.'"  status-procedure="'.$value['status_procedure'].'" id-data="'.$aRow['id'].'">';
		$string_Row .=          mb_convert_case($value['name_status'], MB_CASE_TITLE, "UTF-8").'</br>'.'<p class="'.$color_text.'">'.$title_data_finish.'</p>';
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
	//End quy trình chăm sóc khashc hàng

	foreach($experience as $key => $value)
	{
		$DropdownList = "";
		if(!empty($value['type_detail']) && $value['type_detail'] == 1)
		{
			if($value['type'] == 'select')
			{
				$this->ci->db->select('group_concat(name separator "</br>") as listname, group_concat(id_experience_detail) as listid');
				$this->ci->db->where('id_care_of', $aRow['id']);
				$this->ci->db->where('id_experience', $value['id']);
				$detail_experience = $this->ci->db->get('tblcare_of_detail_experience')->row();
				$DropdownList = DropdownListexpErienceCare_of($value['id'], explode(',', $detail_experience->listid), $aRow['id']);
				$DropdownList.="<button type='button' class='SaveErience btn btn-info btn-icon mtop10'>"._l('cong_save')."</button>
                                <button type='button' class='btn btn-danger btn-icon mtop10 close_popover'>"._l('cong_close')."</button>";
				$row[] = '<a class="PopverSelect2 pointer" data-toggle="popover" data-placement="left" title="'.$value['name'].'" data-html="true" data-content="'.$DropdownList.'">
                                '.(!empty($detail_experience->listname) ? trim($detail_experience->listname) : "<i class='fa fa-pencil-square-o' aria-hidden='true'></i>").'
                         </a>';
			}
			else if($value['type'] == 'staff')
			{

				$this->ci->db->select('name');
				$this->ci->db->where('id_care_of', $aRow['id']);
				$this->ci->db->where('id_experience', $value['id']);
				$detail_experience = $this->ci->db->get('tblcare_of_detail_experience')->row();
				$value_name = !empty($detail_experience->name) ? $detail_experience->name : "";
				$DropdownList = DropdownListexpErienceStaff($value['id'], $value_name, $aRow['id']);
				$DropdownList.="<button type='button' class='SaveErience btn btn-info btn-icon mtop10'>"._l('cong_save')."</button>
                                <button type='button' class='btn btn-danger btn-icon mtop10 close_popover'>"._l('cong_close')."</button>";
				$row[] = '<a class="PopverSelect2 pointer" data-toggle="popover" data-placement="left" title="'.$value['name'].'" data-html="true" data-content="'.$DropdownList.'">
                                '.(!empty($detail_experience->name) ? trim(get_staff_full_name($value_name)) : "<i class='fa fa-pencil-square-o' aria-hidden='true'></i>").'
                         </a>';
			}
			else if($value['type'] == 'img')
			{

				$this->ci->db->select('id, name');
				$this->ci->db->where('id_care_of', $aRow['id']);
				$this->ci->db->where('id_experience', $value['id']);
				$detail_experience = $this->ci->db->get('tblcare_of_detail_experience')->result();

				$DropdownList = DropdownListexpErienceFile($value['id'], $aRow['id'], '', 'file');

				$DropdownList.="<button type='button' class='SaveFileErience btn btn-info btn-icon mtop10'>"._l('cong_save')."</button>
                                    <button type='button' class='btn btn-danger btn-icon mtop10 close_popover'>"._l('cong_close')."</button>";

				$img ='<p class="clearfix"></div>';
				$img .= '<div class="preview_image" style="width: auto;">';
				$img .= '   <div class="display-block contract-attachment-wrapper img">';
				$img .= '       <div style="width:45px; margin: auto;display: flex;">';
				if(!empty($detail_experience))
				{
					foreach($detail_experience as $kImg => $vImg)
					{
						$img .= '       <a href="'.base_url('download/preview_image?path=uploads/care_of_client/'.$aRow['id'].'/'.$value['id'].'/'.$vImg->name).'" data-lightbox="customer-profile" class="display-block mbot5 mleft5">';
						$img .= '           <img src="'.base_url('download/preview_image?path=uploads/care_of_client/'.$aRow['id'].'/'.$value['id'].'/'.$vImg->name).'" class="image-small"/>';
						$img .= '       </a>';
						$img .= '<a class="text-danger removeImg"  id_img="'.$vImg->id.'"  url="'.$aRow['id'].'/'.$value['id'].'/'.$vImg->name.'">X</a>';
					}
				}
				$img .='        <div>';
				$img .='    <div>';
				$img .='<div>';
				// var_dump($img);
				$row[] = '<a class="PopverSelect2 pointer" data-toggle="popover" data-placement="left" title="'.$value['name'].'" data-html="true" data-content="'.$DropdownList.'">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </a>'.$img;

			}
			else
			{
				$this->ci->db->select('name');
				$this->ci->db->where('id_care_of', $aRow['id']);
				$this->ci->db->where('id_experience', $value['id']);
				$detail_experience = $this->ci->db->get('tblcare_of_detail_experience')->row();

				$class = '';
				$value_name = '';
				if(!empty($detail_experience->name))
				{
					$value_name = $detail_experience->name;
				}
				if($value['type'] == 'date'){
					$class = "datepicker";
					$value_name = !empty($detail_experience->name) ? _dC($detail_experience->name) : '';
				}
				else if($value['type'] == 'datetime'){
					$class = "datetimepicker";
					$value_name = !empty($detail_experience->name) ? _dt($detail_experience->name) : '';
				}
				$DropdownList = DropdownListexpErienceType($value['id'], $value_name, $aRow['id'], $class);
				$DropdownList.="<button type='button' class='SaveErience btn btn-info btn-icon mtop10'>"._l('cong_save')."</button>
                                    <button type='button' class='btn btn-danger btn-icon mtop10 close_popover'>"._l('cong_close')."</button>";
				$row[] = '<a class="PopverSelect2 pointer" data-toggle="popover" data-placement="left" title="'.$value['name'].'" data-html="true" data-content="'.$DropdownList.'">
                                '.(!empty($value_name) ? trim($value_name) : "<i class='fa fa-pencil-square-o' aria-hidden='true'></i>").'
                            </a>';
			}

		}
		else
		{
			$row[] = "";
		}
	}

	$row[] = $aRow['code_system'];
	$row[] = $aRow['lead_code'];
	$row[] = $aRow['client_code'];
	$row[] = $aRow['client_code'];
	$row[] = _dt($aRow['date_create']);


	$fullname_CREATE = $aRow['cbylastname'] . ' ' . $aRow['cbyfirstname'];
	$profile_CREATE = '<a data-toggle="tooltip" data-title="' . $fullname_CREATE . '" href="' . admin_url('profile/' . $aRow['create_by']) . '">' . staff_profile_image($aRow['create_by'], [
			'staff-profile-image-small',
		]) . '</a>';
	$profile_CREATE .= '<span class="hide">' . $fullname_CREATE . '</span>';
	$row[] = $profile_CREATE;

	$row[] = _dt($aRow['date_success']);

	$profile_Scussess = "";
	if(!empty($aRow['staff_success']))
	{
		$fullname_Scussess = $aRow['sslastname'] . ' ' . $aRow['ssfirstname'];
		$profile_Scussess = '<a data-toggle="tooltip" data-title="' . $fullname_Scussess . '" href="' . admin_url('profile/' . $aRow['staff_success']) . '">' . staff_profile_image($aRow['staff_success'], [
				'staff-profile-image-small',
			]) . '</a>';
		$profile_Scussess .= '<span class="hide">' . $fullname_Scussess . '</span>';
	}
	$row[] = $profile_Scussess;

	if($aRow['status_break'] == 1)
	{
		$_row['DT_RowClass'] = 'bg-danger';
	}
	$output['aaData'][] = $row;

    $product_care_of = getItemsCare_of_Orders($aRow['id']);
    if(!empty($product_care_of))
    {
	    $count_row = 9;
	    foreach($product_care_of as $kProCare => $vProCare)
	    {
		    $krow = [];
		    for($j = 0; $j < $count_row; $j ++)
		    {
			    $krow[] = '';
		    }
		    $krow[] = $vProCare['code'];
		    $krow[] = $vProCare['name'];
		    $krow[] = '';
		    foreach($experience as $key => $value)
		    {
			    $DropdownList = "";
			    if(!empty($value['type_detail']) && $value['type_detail'] == 2)
			    {
				    if($value['type'] == 'select')
				    {
					    $this->ci->db->select('group_concat(name separator "</br>") as listname, group_concat(id_experience_detail) as listid');
					    $this->ci->db->where('id_care_of', $aRow['id']);
					    $this->ci->db->where('id_experience', $value['id']);
					    $this->ci->db->where('id_care_items', $vProCare['id']);
					    $detail_experience = $this->ci->db->get('tblcare_of_detail_experience')->row();
					    $DropdownList = DropdownListexpErienceCare_of($value['id'], explode(',', $detail_experience->listid), $aRow['id'], $vProCare['id']);
					    $DropdownList.="<button type='button' class='SaveErience btn btn-info btn-icon mtop10'>"._l('cong_save')."</button>
                                        <button type='button' class='btn btn-danger btn-icon mtop10 close_popover'>"._l('cong_close')."</button>";
					    $Srow = '<a class="PopverSelect2 pointer" data-toggle="popover" data-placement="left" title="'.$value['name'].'" data-html="true" data-content="'.$DropdownList.'">
                                    '.(!empty($detail_experience->listname) ? trim($detail_experience->listname) : "<i class='fa fa-pencil-square-o' aria-hidden='true'></i>").'
                                </a>';
					    $krow[] = $Srow;


				    }
				    else if($value['type'] == 'staff')
				    {

					    $this->ci->db->select('name');
					    $this->ci->db->where('id_care_of', $aRow['id']);
					    $this->ci->db->where('id_experience', $value['id']);
					    $this->ci->db->where('id_care_items', $vProCare['id']);
					    $detail_experience = $this->ci->db->get('tblcare_of_detail_experience')->row();
					    $value_name = !empty($detail_experience->name) ? $detail_experience->name : "";
					    $DropdownList = DropdownListexpErienceStaff($value['id'], $value_name, $aRow['id'], $vProCare['id']);
					    $DropdownList.="<button type='button' class='SaveErience btn btn-info btn-icon mtop10'>"._l('cong_save')."</button>
                                    <button type='button' class='btn btn-danger btn-icon mtop10 close_popover'>"._l('cong_close')."</button>";
					    $Srow = '<a class="PopverSelect2 pointer" data-toggle="popover" data-placement="left" title="'.$value['name'].'" data-html="true" data-content="'.$DropdownList.'">
                                    '.(!empty($detail_experience->name) ? trim(get_staff_full_name($value_name)) : "<i class='fa fa-pencil-square-o' aria-hidden='true'></i>").'
                             </a>';
					    $krow[] = $Srow;

				    }
				    else if($value['type'] == 'img')
				    {

					    $this->ci->db->select('id, name');
					    $this->ci->db->where('id_care_of', $aRow['id']);
					    $this->ci->db->where('id_experience', $value['id']);
					    $this->ci->db->where('id_care_items', $vProCare['id']);
					    $detail_experience = $this->ci->db->get('tblcare_of_detail_experience')->result();

					    $DropdownList = DropdownListexpErienceFile($value['id'], $aRow['id'], '', 'file', $vProCare['id']);

					    $DropdownList.="<button type='button' class='SaveFileErience btn btn-info btn-icon mtop10'>"._l('cong_save')."</button>
                                        <button type='button' class='btn btn-danger btn-icon mtop10 close_popover'>"._l('cong_close')."</button>";

					    $img ='<p class="clearfix"></div>';
					    $img .= '<div class="preview_image" style="width: auto;">';
					    $img .= '   <div class="display-block contract-attachment-wrapper img">';
					    $img .= '       <div style="width:45px; margin: auto;display: flex;">';
					    if(!empty($detail_experience))
					    {
						    foreach($detail_experience as $kImg => $vImg)
						    {
							    $img .= '       <a href="'.base_url('download/preview_image?path=uploads/care_of_client/'.$aRow['id'].'/'.$value['id'].'/'.$vImg->name).'" data-lightbox="customer-profile" class="display-block mbot5 mleft5">';
							    $img .= '           <img src="'.base_url('download/preview_image?path=uploads/care_of_client/'.$aRow['id'].'/'.$value['id'].'/'.$vImg->name).'" class="image-small"/>';
							    $img .= '       </a>';
							    $img .= '<a class="text-danger removeImg"  id_img="'.$vImg->id.'"  url="'.$aRow['id'].'/'.$value['id'].'/'.$vImg->name.'">X</a>';
						    }
					    }
					    $img .='        </div>';
					    $img .='    </div>';
					    $img .='</div>';
					    $Srow = $img.'<a class="PopverSelect2 pointer" data-toggle="popover" data-placement="left" title="'.$value['name'].'" data-html="true" data-content="'.$DropdownList.'">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a>';
					    $krow[] = $Srow;


				    }
				    else
				    {
					    $this->ci->db->select('name');
					    $this->ci->db->where('id_care_of', $aRow['id']);
					    $this->ci->db->where('id_experience', $value['id']);
					    $this->ci->db->where('id_care_items', $vProCare['id']);
					    $detail_experience = $this->ci->db->get('tblcare_of_detail_experience')->row();

					    $class = '';
					    $value_name = '';
					    if(!empty($detail_experience->name))
					    {
						    $value_name = $detail_experience->name;
					    }
					    if($value['type'] == 'date'){
						    $class = "datepicker";
						    $value_name = !empty($detail_experience->name) ? _dC($detail_experience->name) : '';
					    }
					    else if($value['type'] == 'datetime'){
						    $class = "datetimepicker";
						    $value_name = !empty($detail_experience->name) ? _dt($detail_experience->name) : '';
					    }
					    $DropdownList = DropdownListexpErienceType($value['id'], $value_name, $aRow['id'], $class, $vProCare['id']);
					    $DropdownList.="<button type='button' class='SaveErience btn btn-info btn-icon mtop10'>"._l('cong_save')."</button>
                                        <button type='button' class='btn btn-danger btn-icon mtop10 close_popover'>"._l('cong_close')."</button>";
					    $Srow = '<a class="PopverSelect2 pointer" data-toggle="popover" data-placement="left" title="'.$value['name'].'" data-html="true" data-content="'.$DropdownList.'">
                                    '.(!empty($value_name) ? trim($value_name) : "<i class='fa fa-pencil-square-o' aria-hidden='true'></i>").'
                                </a>';
					    $krow[] = $Srow;
				    }

			    }
			    else
			    {
				    $krow[] = '';

			    }

		    }
		    $krow[] = '';
		    $krow[] = '';
		    $krow[] = '';
		    $krow[] = '';
		    $krow[] = '';
		    $krow[] = '';
		    $krow[] = '';
		    $krow[] = '';
		    $output['aaData'][] = $krow;
	    }
    }



//
//    if(!empty($krow))
//    {
//        $countAllRow = count($row);
//        foreach($krow as $kRowHight => $vRowHight)
//        {
//            for($i = 0; $i < ($countAllRow - count($vRowHight)); $i++)
//            {
//                $krow[$kRowHight][] = '';
//            }
//            $output['aaData'][] = $krow[$kRowHight];
//        }
//    }
}
