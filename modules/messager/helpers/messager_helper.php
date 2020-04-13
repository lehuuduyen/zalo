<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Defined styling areas for the theme style feature
 * Those string are not translated to keep the language file neat
 * @param  string $type
 * @return array
 */

function getFullDataTag()
{
    $CI = &get_instance();
    $taggables = $CI->db->get('tbltagsfb')->result_array();
    $arrayData = [
        'id' => [],
        'name' => [],
        'color' => [],
        'background_color' => []
    ];
    if(!empty($taggables))
    {
        foreach($taggables as $key => $value)
        {
            $arrayData['id'][$value['id']] = $value['id'];
            $arrayData['name'][$value['id']] = $value['name'];
            $arrayData['color'][$value['id']] = $value['color'];
            $arrayData['background_color'][$value['id']] = $value['background_color'];
        }
    }
    return $arrayData;
}

function get_tagsFB_table()
{
    $CI = &get_instance();
    $CI->db->order_by('id', 'asc');
    $tags = $CI->db->get('tbltagsfb')->result_array();

    return $tags;
}

function GetDataTag($rel_id = "", $rel_type = "")
{
    $CI = &get_instance();
    $CI->db->select('group_concat(name) as rowName');
    $CI->db->where('rel_id', $rel_id)->where('rel_type', $rel_type);
    $CI->db->join(db_prefix().'tagsfb t', 't.id = tbltaggablesfb.tag_id');
    $CI->db->group_by('rel_id');
    $taggables = $CI->db->get('tbltaggablesfb')->row();
    if(!empty($taggables))
    {
        return  $taggables->rowName;
    }
    return  '';
}

function GetDataIDTag($rel_id = "", $rel_type = "")
{
    $CI = &get_instance();
    $CI->db->select('group_concat(id) as rowID');
    $CI->db->where('rel_id', $rel_id)->where('rel_type', $rel_type);
    $CI->db->join(db_prefix().'tagsfb t', 't.id = tbltaggablesfb.tag_id');
    $CI->db->group_by('rel_id');
    $taggables = $CI->db->get('tbltaggablesfb')->row();
    if(!empty($taggables))
    {
        return  $taggables->rowID;
    }
    return  '';
}

function getAssignedLead($lead = "")
{
    if(!empty($lead))
    {
        $CI = &get_instance();
        $CI->db->select('group_concat(staff) as list_staff');
        $CI->db->where('id_lead', $lead);
        $CI->db->group_by('id_lead');
        $lead_assigned = $CI->db->get(db_prefix().'lead_assigned')->row();
        if(!empty($lead_assigned))
        {
            return $lead_assigned;
        }
    }
    return false;
}

function getAssignedClient($client = "")
{
    if(!empty($client))
    {
        $CI = &get_instance();
        $CI->db->select('group_concat(staff_id) as list_staff');
        $CI->db->where('customer_id', $client);
        $client_assigned = $CI->db->get(db_prefix().'customer_admins')->row();
        if(!empty($client_assigned))
        {
            return $client_assigned;
        }
    }
    return false;
}

function getAssignedListFb($listid = "")
{
    if(!empty($listid))
    {
        $CI = &get_instance();
        $CI->db->select('group_concat(staff) as list_staff');
        $CI->db->where('id_listfb', $listid);
        $CI->db->group_by('id_listfb');
        $list_assigned = $CI->db->get(db_prefix().'listfb_assigned')->row();
        if(!empty($list_assigned))
        {
            return $list_assigned;
        }
    }
    return false;
}

function getInfoTagFacebook($id_facebook = "")
{
    $CI = &get_instance();
    if(!empty($id_facebook))
    {
        $CI->db->where('id_facebook', $id_facebook);
        $client = $CI->db->get(db_prefix().'clients')->row();
        if(!empty($client))
        {
            $CI->db->where('rel_id', $client->userid);
            $CI->db->where('rel_type', 'client');
            $CI->db->join(db_prefix().'tagsfb', db_prefix().'tagsfb.id = '.db_prefix().'taggablesfb.tag_id');
            $tag = $CI->db->get(db_prefix().'taggablesfb')->result_array();
            return $tag;
        }
        else
        {
            $CI->db->where('id_facebook', $id_facebook);
            $lead = $CI->db->get(db_prefix().'leads')->row();
            if(!empty($lead))
            {
                $CI->db->where('rel_id', $lead->id);
                $CI->db->where('rel_type', 'lead');
                $CI->db->join(db_prefix().'tagsfb', db_prefix().'tagsfb.id = '.db_prefix().'taggablesfb.tag_id');
                $tag = $CI->db->get(db_prefix().'taggablesfb')->result_array();
                return $tag;
            }
            else
            {
                $CI->db->where('id_facebook', $id_facebook);
                $listfb = $CI->db->get(db_prefix().'list_fb')->row();
                if(!empty($listfb))
                {
                    $CI->db->where('rel_id', $listfb->id);
                    $CI->db->where('rel_type', 'listfb');
                    $CI->db->join(db_prefix().'tagsfb', db_prefix().'tagsfb.id = '.db_prefix().'taggablesfb.tag_id');
                    $tag = $CI->db->get(db_prefix().'taggablesfb')->result_array();
                    return $tag;
                }
            }
        }
    }
    return false;
}

function getInfoIdFacebook($id_facebook = "")
{
	$CI = &get_instance();
	$RTArray = [
		'phone' => '',
		'orders' => '',
		'assigned' => '',
		'name_system' => '',
	];
	$CI->db->where('id_facebook', $id_facebook);
	$client = $CI->db->get('tblclients')->row();
	if (!empty($client)) {
		$RTArray['phone'] = $client->phonenumber;
		$CI->db->where('client', $client->userid);
		$orders = $CI->db->get('tblorders')->row();
		if (!empty($orders)) {
			$RTArray['orders'] = 1;
		}
		$CI->db->select('group_concat(staff_id) as assigned');
		$CI->db->where('customer_id', $client->userid);
		$CI->db->group_by('customer_id');
		$assigned = $CI->db->get('tblcustomer_admins')->row();
		if (!empty($assigned->assigned)) {
			$RTArray['assigned'] = $assigned->assigned;
		}
		$RTArray['type'] = '<span class="label label-default inline-block mtop5" style="color:white;background-color:red">PLKH</span>';
		$RTArray['name_system'] = $client->name_system;
	}
	else {
		$CI->db->where('id_facebook', $id_facebook);
		$Lead = $CI->db->get('tblleads')->row();
		if (!empty($Lead)) {
			$RTArray['phone'] = $Lead->phonenumber;
			$CI->db->select('group_concat(staff) assigned');
			$CI->db->where('id_lead', $Lead->id);
			$CI->db->group_by('id_lead');
			$assigned = $CI->db->get('tbllead_assigned')->row();
			if (!empty($assigned->assigned)) {
				$RTArray['assigned'] = $assigned->assigned;
			}
			$RTArray['name_system'] = $Lead->name_system;
			$RTArray['type'] = '<span class="label label-default inline-block mtop5" style="color:white;background-color:#0fc31d">KHTN</span>';
		} else {
			$CI->db->where('id_facebook', $id_facebook);
			$ListFB = $CI->db->get('tbllist_fb')->row();
			if (!empty($ListFB)) {
				$RTArray['phone'] = $ListFB->phonenumber;
				$CI->db->select('group_concat(staff) assigned');
				$CI->db->where('id_listfb', $ListFB->id);
				$CI->db->group_by('id_listfb');
				$assigned = $CI->db->get('tbllistfb_assigned')->row();
				if (!empty($assigned->assigned)) {
					$RTArray['assigned'] = $assigned->assigned;
				}
				$RTArray['name_system'] = !empty($ListFB->name_system) ? $ListFB->name_system : $ListFB->name_facebook;
				$RTArray['type'] = '<span class="label label-default inline-block mtop5" style="color:white;background-color:#3799ff;">NEW</span>';
			}
		}
	}

	return $RTArray;
}

//get step orders
function GetOrderStep($procedure = "", $order_id = "")
{
    $CI = &get_instance();
    if(!empty($procedure) && !empty($order_id))
    {
        $CI->db->select(db_prefix().'procedure_client_detail.*, 
        tblorders_step.id_procedure,
        tblorders_step.date_create,
        tblorders_step.id_staff,
        tblorders_step.active');
        $CI->db->where('id_detail', $procedure);
        $CI->db->order_by('orders', 'ASC');
        $CI->db->join('tblorders_step', 'tblorders_step.id_procedure = tblprocedure_client_detail.id and id_orders = '.$order_id, 'left');
        $procedure_detail =$CI->db->get(db_prefix().'procedure_client_detail')->result_array();
        if(!empty($procedure_detail))
        {
            return $procedure_detail;
        }
    }
    return false;
}

function get_product_orders_client($client = "")
{
    $CI = &get_instance();
    if(!empty($client))
    {
        $CI->db->select(' 
                        id_product,
                        IF(tblorders_items.type_items = "items", '.db_prefix().'items.name, tbl_products.name) as name,
                        IF(tblorders_items.type_items = "items", tblitems.avatar, CONCAT("uploads/products/", "", tbl_products.images, "")) as avatar,
                        IF(tblorders_items.type_items = "items", '.db_prefix().'items.code, tbl_products.code) as code_items,
                        group_concat(tblorders.prefix, tblorders.code) as full_code,
                        sum(quantity)  as sum_quantity
                    ', false);
        $CI->db->group_by('id_product');
        $CI->db->where('client', $client);

        $CI->db->join(db_prefix().'items', db_prefix().'items.id = '.db_prefix().'orders_items.id_product AND tblorders_items.type_items = "items"', 'left');
        $CI->db->join('tbl_products', 'tbl_products.id = '.db_prefix().'orders_items.id_product AND tblorders_items.type_items = "products"', 'left');

        $CI->db->join('tblorders', 'tblorders.id = tblorders_items.id_orders');
        $orders = $CI->db->get('tblorders_items')->result();
        if(!empty($orders))
        {
            return $orders;
        }
    }
    return false;
}

function get_product_orders_items_client($client = "")
{
    $CI = &get_instance();
    if(!empty($client))
    {
        $CI->db->select('
                        tblorders.id,
                        id_product,
                        IF(tblorders_items.type_items = "items", tblitems.name, tbl_products.name) as name,
                        IF(tblorders_items.type_items = "items", tblitems.avatar, CONCAT("uploads/products/", "", tbl_products.images, "")) as avatar,
                        IF(tblorders_items.type_items = "items", tblitems.code, tbl_products.code) as code_items,
                        concat(COALESCE(tblorders.prefix), COALESCE(tblorders.code)) as full_code,
                        sum(quantity)  as sum_quantity
                    ', false);
        $CI->db->group_by('tblorders.id');
        $CI->db->where('client', $client);

        $CI->db->join('tblitems', 'tblitems.id = tblorders_items.id_product AND tblorders_items.type_items = "items"', 'left');
        $CI->db->join('tbl_products', 'tbl_products.id = tblorders_items.id_product AND tblorders_items.type_items = "products"', 'left');

        $CI->db->join('tblorders', 'tblorders.id = tblorders_items.id_orders');
        $CI->db->limit(2);
        $orders = $CI->db->get('tblorders_items')->result();
        if(!empty($orders))
        {
            return $orders;
        }
    }
    return false;
}

function Create_wap_content_input($lang, $name, $value, $id, $url, $class = array(), $type  = 'input', $type_class = "text-muted")
{
    $classInput = '';
    $typeInpt = '';
    if($type == 'date')
    {
        $classInput = 'datepicker';
    }
    else if($type == 'datetime')
    {
        $classInput = 'datetimepicker';
    }
    else if($type == 'password')
    {
        $typeInpt = 'password';
    }
    else
    {
        $typeInpt = 'text';
    }
    $varDiv ='<div class="wap-content">
                <span class="'.$type_class.' lead-field-heading no-mtop viewInput pointer span-title  col-md-7 row">
                    '.$lang.':
                </span>
                <div class="div_input_content  col-md-5 row">
                    '.form_open($url, $class).'
                    <input type="hidden" name="id" value="'.$id.'" />
                    <p class="bold font-medium-xs mbot15 viewInput pointer">
                            '.(!empty($value) ? $value : '-').'
                    </p>
                    <span class="bold font-medium-xs mbot15 editInput hide">
                            <input type="'.$typeInpt.'" class="C_text_input '.$classInput.'" name="'.$name.'" value="'.(!empty($value) ? $value : '').'">
                            <button type="submit" class="mleft5 saveValue">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i>
                            </button>
                            <a class="text-danger mleft5 not-edit-input">
                                <i class="fa fa-remove"></i>
                            </a>
                        </span>
                    '.form_close().'
                </div>
            </div>
            <div class="clearfix_C"></div>';
    return $varDiv;
}

function Create_wap_content_textarea($lang, $name, $value, $id, $url, $class = array(), $type_class = "text-muted")
{
    $varDiv ='<div class="wap-content">
                <span class="'.$type_class.' lead-field-heading no-mtop viewInput pointer span-title  col-md-7 row">
                    '.$lang.':
                </span>
                <div class="div_input_content  col-md-5 row">
                    '.form_open($url, $class).'
                    <input type="hidden" name="id" value="'.$id.'" />
                    <p class="bold font-medium-xs mbot15 viewInput pointer">
                            '.(!empty($value) ? $value : '-').'
                    </p>
                    <span class="bold font-medium-xs mbot15 editInput hide">
                            <textarea class="C_text_textarea" name="'.$name.'">'.(!empty($value) ? trim($value) : '').'</textarea>
                            <button type="submit" class="mleft5 saveValue">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i>
                            </button>
                            <a class="text-danger mleft5 not-edit-input">
                                <i class="fa fa-remove"></i>
                            </a>
                        </span>
                    '.form_close().'
                </div>
            </div>
            <div class="clearfix_C"></div>';
    return $varDiv;
}

function Create_wap_content_radio($lang, $name, $value, $id, $url, $class = [], $option = [])
{

    $varRadio = '';
    foreach($option as $kVal => $vVal)
    {
        if($value == $vVal['id'])
        {
            $_value = $vVal['name'];
        }
        $varRadio.= '<input type="radio" id="'.$name.'_'.$vVal['id'].'" class="mleft5" name="'.$name.'" value="'.$vVal['id'].'">';
        $varRadio.= '<label for="'.$name.'_'.$vVal['id'].'">'.$vVal['name'].'</label>';
    }

    $varDiv ='<div class="wap-content">
                    <span class="text-muted lead-field-heading no-mtop viewInput pointer span-title  col-md-7 row">
                        '.$lang.':
                    </span>
                    <div class="div_input_content  col-md-5 row">
                        '.form_open($url, $class).'
                        <input type="hidden" name="id" value="'.$id.'" />
                        <p class="bold font-medium-xs mbot15 viewInput pointer">
                                '.(!empty($_value) ? $_value : '-').'
                        </p>
                        <span class="bold font-medium-xs mbot15 editInput mleft20 hide">
                                '.$varRadio.'
                                <button type="submit" class="mleft5 saveValue">
                                    <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                </button>
                                <a class="text-danger mleft5 not-edit-input">
                                    <i class="fa fa-remove"></i>
                                </a>
                            </span>
                        '.form_close().'
                    </div>
                </div>
            <div class="clearfix_C"></div>';
    return $varDiv;
}

function Create_wap_content_checkbox($lang, $name, $value, $id, $url, $class = array(), $option = array())
{

    $varCheckbox = '';
    $_value = [];
    foreach($option as $kVal => $vVal)
    {
        $checkbox = '';
        foreach($value as $k => $v)
        {
            if($vVal['id'] == $v)
            {
                $checkbox = 'checked';
                $_value[] = $vVal['name'];
                unset($value[$k]);
                break;
            }
        }
        $varCheckbox.= '<input type="checkbox" id="'.$name.'_'.$vVal['id'].'" class="C_text_input" name="'.$name.'[]" value="'.$vVal['id'].'" '.$checkbox.'>';
        $varCheckbox.= '<label for="'.$name.'_'.$vVal['id'].'">'.$vVal['name'].'</label>';
    }

    $varDiv ='<div class="wap-content">
                    <span class="text-muted lead-field-heading no-mtop viewInput pointer span-title  col-md-7 row">
                        '.$lang.':
                    </span>
                    <div class="div_input_content  col-md-5 row">
                        '.form_open($url, $class).'
                        <input type="hidden" name="id" value="'.$id.'" />
                        <p class="bold font-medium-xs mbot15 viewInput pointer">
                                '.(!empty($_value) ? implode(',', $value) : '-').'
                        </p>
                        <span class="bold font-medium-xs mbot15 mleft20 editInput hide">
                                '.$varCheckbox.'
                                <button type="submit" class="mleft5 saveValue">
                                    <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                </button>
                                <a class="text-danger mleft5 not-edit-input">
                                    <i class="fa fa-remove"></i>
                                </a>
                            </span>
                        '.form_close().'
                    </div>
                </div>
            <div class="clearfix_C"></div>';
    return $varDiv;
}

function Create_wap_content_select($lang, $name, $value, $id, $url, $class = array(), $option = array(), $collable = 'col-md-7')
{

    $varSelect = '<select class="selectpicker" name="'.$name.'" data-live-search="true">';
    $varSelect .= '<option></option>';
    $_value = '';
    foreach($option as $kVal => $vVal)
    {
        $selected =  ($vVal['id'] == $value) ? 'selected' : '';
        if(!empty($selected))
        {
            $_value = $vVal['name'];
        }
        $varSelect.= '<option value="'.$vVal['id'].'" '.$selected.'>'.$vVal['name'].'</option>';
    }
    $varSelect.='</select>';

    $varDiv ='<div class="wap-content">
                    <span class="text-muted lead-field-heading no-mtop viewInput pointer span-title  '.$collable.' row">
                        '.$lang.':
                    </span>
                    <div class="div_input_content  col-md-5 row">
                        '.form_open($url, $class).'
                        <input type="hidden" name="id" value="'.$id.'" />
                        <p class="bold font-medium-xs mbot15 viewInput pointer">
                                '.(!empty($_value) ? $_value : '-').'
                        </p>
                        <span class="bold font-medium-xs mbot15 editInput hide">
                                '.$varSelect.'
                                <button type="submit" class="mleft5 saveValue">
                                    <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                </button>
                                <a class="text-danger mleft5 not-edit-input">
                                    <i class="fa fa-remove"></i>
                                </a>
                            </span>
                        '.form_close().'
                    </div>
                </div>
            <div class="clearfix_C"></div>';
    return $varDiv;
}

function Create_wap_content_select_multiple($lang, $name, $value, $id, $url, $class = array(), $option = array())
{

    $_value = [];
    $varSelect = '<select class="selectpicker" multiple name="'.$name.'[]" data-live-search="true">';
    foreach($option as $kVal => $vVal)
    {
        $selected = '';
        foreach($value as $k => $v)
        {
            if($vVal['id'] == $v)
            {
                $selected = 'selected';
                $_value[] = $vVal['name'];
                unset($value[$k]);
                break;
            }
        }
        $varSelect.= '<option value="'.$vVal['id'].'" '.$selected.'>'.$vVal['name'].'</option>';
    }
    $varSelect.='</select>';

    $varDiv ='<div class="wap-content">
                    <span class="text-muted lead-field-heading no-mtop viewInput pointer span-title  col-md-7 row">
                        '.$lang.':
                    </span>
                    <div class="div_input_content  col-md-5 row">
                        '.form_open($url, $class).'
                        <input type="hidden" name="id" value="'.$id.'" />
                        <p class="bold font-medium-xs mbot15 viewInput pointer">
                                '.(!empty($_value) ? implode(',', $_value) : '-').'
                        </p>
                        <span class="bold font-medium-xs mbot15 editInput hide">
                                '.$varSelect.'
                                <button type="submit" class="mleft5 saveValue">
                                    <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                </button>
                                <a class="text-danger mleft5 not-edit-input">
                                    <i class="fa fa-remove"></i>
                                </a>
                            </span>
                        '.form_close().'
                    </div>
                </div>
            <div class="clearfix_C"></div>';
    return $varDiv;
}

function updateTypecodeClient($userid = '', $type = 'TN')
{
    $CI = &get_instance();
    if(!empty($userid))
    {
        $CI->db->where('userid', $userid);
        if($CI->db->update('tblclients', ['code_type' => $type]))
        {
            return true;
        }
    }
    return false;
}

function radomStaffAssigned($id_listfb = "")
{
    $CI = &get_instance();
    if(!empty($id_listfb))
    {
        $Year_month = date('Y-m');
        $CI->db->select('count(tbllistfb_assigned.id) as count_id, staffid');
        $CI->db->join('tbllistfb_assigned', 'tbllistfb_assigned.staff = tblstaff.staffid and (DATE_FORMAT(date_create, "%Y-%m-%d") >="'.$Year_month.'-1'.'" and DATE_FORMAT(date_create, "%Y-%m-%d") >="'.date('Y-m-d').'")', 'left');
        $CI->db->group_by('tblstaff.staffid');
        $CI->db->where('active', 1);
	    $staff_listfb = $CI->db->get('tblstaff')->result_array();
	    $listArray = [];
        if(!empty($staff_listfb))
        {
            $arrayActive = $staff_listfb[0];
            foreach($staff_listfb as $key => $value)
            {
            	if(!isset($listArray[$value['staffid']]))
	            {
	                $CI->db->where('id_staff', $value['staffid']);
	                $CI->db->where('year', date('Y'));
	                $CI->db->where('month', (int)date('m'));
	                $CI->db->join('tblratio_staff', 'tblratio_staff.id = tblratio_staff_detail.id_ratio');
	                $List_ratio = $CI->db->get('tblratio_staff_detail')->row();
		            $listArray[$value['staffid']] = 1;
	                if(!empty($List_ratio))
		            {
			            $listArray[$value['staffid']] = (100 - $List_ratio->ratio) / 100;
		            }
	            }
                if($key > 0)
                {
                    if(($arrayActive['count_id'] * $listArray[$arrayActive['staffid']]) > ($value['count_id'] * $listArray[$value['staffid']]))
                    {
                        $arrayActive = $value;
                    }
                }
            }
            $CI->db->insert('tbllistfb_assigned', [
                'staff' => $arrayActive['staffid'],
                'id_listfb' => $id_listfb,
                'created_by' => get_staff_user_id(),
                'date_create' => date('Y-m-d H:i:s')
            ]);
        }
    }
    return true;
}

function SeeMessage($id_facebook = "")
{
	$CI = &get_instance();
	if(!empty($id_facebook))
	{
		$CI->db->where('id_facebook', $id_facebook);
		$CI->db->where('see', 1);
		$numRow = $CI->db->get('tbllast_messager')->num_rows();
		if(!empty($numRow))
		{
			return true;
		}
	}
	return false;
}

function AddphoneFacebook($id_facebook = '', $phone = '')
{
	$CI = &get_instance();
	if(!empty($id_facebook) && !empty($phone))
	{
		$CI->db->where('id_facebook', $id_facebook);
		$CI->db->where('phone', $phone);
		$kt = $CI->db->get('tblphone_facebook')->row();
		if(empty($kt))
		{
			$success = $CI->db->insert('tblphone_facebook', [
				'id_facebook' => $id_facebook,
				'phone' => $phone
			]);
			return $success;
		}
	}
	return false;
}

function getPhoneFacebook($id_facebook = '')
{
	$CI = &get_instance();
	if(!empty($id_facebook))
	{
		$CI->db->select('group_concat(phone) as list_phone');
		$CI->db->where('id_facebook', $id_facebook);
		$phoneFacebook = $CI->db->get('tblphone_facebook')->row();
		if(!empty($phoneFacebook))
		{
			return $phoneFacebook->list_phone;
		}
	}
	return false;
}