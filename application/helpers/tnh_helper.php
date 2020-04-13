<?php

defined('BASEPATH') or exit('No direct script access allowed');

use app\services\messages\Message;
use app\services\messages\PopupMessage;

if(! function_exists('js')) {
	function js($link = '')
	{
		return base_url('assets/js/tnh/').$link;
	}
}

if(! function_exists('css')) {
	function css($link = '')
	{
		return base_url('assets/css/').$link;
	}
}

if(! function_exists('pathMaterial')) {
	function pathMaterial($file = '')
	{
		return base_url('uploads/materials/').$file;
	}
}

if(! function_exists('pathProduct')) {
	function pathProduct($file = '')
	{
		return base_url('uploads/products/').$file;
	}
}

if ( ! function_exists('lang')) {
	/**
	 * Lang
	 *
	 * Fetches a language variable and optionally outputs a form label
	 *
	 * @param	string	$line		The language line
	 * @param	string	$for		The "for" value (id of the form element)
	 * @param	array	$attributes	Any additional HTML attributes
	 * @return	string
	 */
	function lang($line, $for = '', $attributes = array())
	{
		$temp = $line;
		$line = get_instance()->lang->line($line);
		if (empty($line)) $line = $temp;
		if ($for !== '')
		{
			$line = '<label for="'.$for.'"'._stringify_attributes($attributes).'>'.$line.'</label>';
		}
		return $line;
	}
}

if (!function_exists('print_arrays')) {
	function print_arrays()
    {
        $args = func_get_args();
        echo "<pre>";
        foreach ($args as $arg) {
            print_r($arg);
        }
        echo "</pre>";
        die();
    }
}

if (!function_exists('type_products')) {
	function type_products()
    {
        $option['products'] = lang('products');
        $option['semi_products'] = lang('semi_products');
        $option['semi_products_outside'] = lang('semi_products_outside');
        return $option;
    }
}

if (!function_exists('type_design_bom')) {
	function type_design_bom($type = 'all')
    {
        $option['materials'] = lang('materials');
        if ($type != 'not_all')
        {
	        $option['semi_products'] = lang('semi_products');
	        $option['semi_products_outside'] = lang('semi_products_outside');
        }
        return $option;
    }
}

if (!function_exists('status_machine')) {
	function status_machine()
    {
        $option['not_produced'] = lang('tnh_not_produced');
        $option['producing'] = lang('tnh_producing');
        $option['maintenance'] = lang('tnh_maintenance');
        $option['damaged'] = lang('tnh_damaged');
        return $option;
    }
}

if (!function_exists('type_tools_supplies')) {
	function type_tools_supplies()
    {
        $option['tools'] = lang('tools');
        $option['supplies'] = lang('supplies');
        $option['packaging'] = lang('packaging');
        return $option;
    }
}

if (!function_exists('statusBom')) {
	function statusBom()
    {
        $option['active'] = lang('tnh_active');
        $option['off'] = lang('tnh_off');
        $option['end'] = lang('tnh_end');
        return $option;
    }
}

if (!function_exists('normalize')) {
	function normalize ($string) {
	    $table = array(
	        'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
	        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
	        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
	        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
	        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
	        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
	        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
	        'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
	    );
	    return strtr($string, $table);
	}
}
if (!function_exists('vn_to_str')) {
	function vn_to_str ($str){
		$unicode = array(
			'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
			'd'=>'đ',
			'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
			'i'=>'í|ì|ỉ|ĩ|ị',
			'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
			'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
			'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
			'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
			'D'=>'Đ',
			'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
			'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
			'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
			'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
			'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
		);
		foreach($unicode as $nonUnicode=>$uni){
			$str = preg_replace("/($uni)/i", $nonUnicode, $str);
		}
		$str = str_replace(' ','_',$str);
		return $str;
	}
}

if (!function_exists('get_fields_export')) {
	function get_fields_export($table, $arr_diff = false, $arr_more = false)
	{
		$CI = & get_instance();
		$fields = $CI->db->list_fields($table);
		if (!empty($arr_diff)) {
			$fields = array_diff($fields, $arr_diff);
		}
		if (!empty($arr_more)) {
			$fields = array_merge($fields, $arr_more);
		}
		return $fields;
	}
}

if (!function_exists('style_excel')) {
	function style_excel()
	{
		$data = [];
		$data['BStyle_center'] = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'font'  => array(
				'bold'  => false,
				'color' => array('rgb' => '111112'),
				'size'  => 12,
				'name'  => 'Times New Roman'
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			)
		);
		$data['BStyle'] = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'font'  => array(
				'bold'  => false,
				'color' => array('rgb' => '111112'),
				'size'  => 12,
				'name'  => 'Times New Roman'
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_JUSTIFY
			)
		);

		$data['title'] = [
			'font'  => array(
				'bold'  => true,
				'color' => array('rgb' => '111112'),
				'size'  => 16,
				'name'  => 'Times New Roman'
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			)
		];

		$data['bold'] = [
			'font'  => array(
				'bold'  => true,
			)
		];

		$data['center'] = [
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			)
		];

		$data['border'] = [
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			)
		];

		$data['font'] = [
			'font'  => array(
				'bold'  => false,
				'color' => array('rgb' => '111112'),
				'size'  => 12,
				'name'  => 'Times New Roman'
			)
		];

		$data['Background_header'] = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '14b8e9'),
				'size'  => 14,
				'bold'  => true
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			)

		);

		return $data;
	}
}

if (!function_exists('cloumns_excel')) {
	function cloumns_excel()
	{
		return ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
			'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ',
			'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ',
			'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ',
			'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ'
		];
	}
}

if (!function_exists('getReference')) {
	function getReference($field) {
		$CI = & get_instance();
	    $q = $CI->db->get_where('tbl_order_ref', array('ref_id' => '1'), 1);
	    if ($q->num_rows() > 0) {
	        $ref = $q->row();
	        switch ($field) {
	            case 'productions_plan':
	                $prefix = get_option('prefix_productions_plan');
	                break;
	            case 'business_plan':
	                $prefix = get_option('prefix_business_plan');
	                break;
	            case 'productions_capacity':
	                $prefix = get_option('prefix_productions_capacity');
	                break;
	            case 'productions_orders':
	                $prefix = get_option('prefix_productions_orders');
	                break;
	            case 'productions_orders_details':
	                $prefix = get_option('prefix_productions_orders_details');
	                break;
	            case 'quotes':
	                $prefix = get_option('prefix_quotes');
	                break;
	            case 'suggest_exporting':
	                $prefix = get_option('prefix_suggest_exporting');
	                break;
	            case 'stock':
	                $prefix = get_option('prefix_stock');
	                break;
	            case 'material_system':
	                $prefix = get_option('prefix_material_system');
	                break;
	            case 'product_system':
	                $prefix = get_option('prefix_product_system');
	                break;
	            default:
	                $prefix = '';
	        }

	        $separator = get_option('separator');
	        $format_date_prefix = get_option('format_date_prefix');
	        $ref_no = (!empty($prefix)) ? $prefix."$separator" : '';
	        if ($field != "material_system" || $field != "product_system") {
	        	$ref_no .= date("$format_date_prefix").sprintf("%04s", $ref->{$field});
	        } else {
	        	$ref_no .= sprintf("%04s", $ref->{$field});
	        }

	        // if ($this->Settings->reference_format == 1) {
	        //     $ref_no .= date('Y') . "/" . sprintf("%04s", $ref->{$field});
	        // } elseif ($this->Settings->reference_format == 2) {
	        //     $ref_no .= date('Y') . "/" . date('m') . "/" . sprintf("%04s", $ref->{$field});
	        // } elseif ($this->Settings->reference_format == 3) {
	        //     $ref_no .= sprintf("%04s", $ref->{$field});
	        // } else {
	        //     $ref_no .= $this->getRandomReference();
	        // }

	        return $ref_no;
	    }
	    return FALSE;
	}
}

if (!function_exists('countReferenceMinus')) {
	function countReferenceMinus($field) {
		$ct = 0;
		switch ($field) {
            case 'productions_plan':
                $ct+= strlen(get_option('prefix_productions_plan'));
                break;
            case 'business_plan':
                $ct+= strlen(get_option('prefix_business_plan'));
                break;
            case 'productions_capacity':
                $ct+= strlen(get_option('prefix_productions_capacity'));
                break;
            case 'productions_orders':
                $ct+= strlen(get_option('prefix_productions_orders'));
                break;
            case 'productions_orders_details':
                $ct+= strlen(get_option('prefix_productions_orders_details'));
                break;
            case 'quotes':
                $ct+= strlen(get_option('prefix_quotes'));
                break;
            case 'suggest_exporting':
	            $ct+= strlen(get_option('prefix_suggest_exporting'));
	            break;
            case 'stock':
                $ct+= strlen(get_option('prefix_stock'));
                break;
            case 'material_system':
                $ct+= strlen(get_option('prefix_material_system'));
                break;
            default:
                $ct+= 0;
        }
		// if ($field == "productions_orders") {
		// 	$ct+= strlen(get_option('prefix_productions_orders'));
		// }
		$format_date_prefix = get_option('format_date_prefix');
		if ($format_date_prefix == "dmY") {
			$ct = $ct + 8;
		}
		$separator = get_option('separator');
		$ct+= strlen($separator);
		return $ct;
	}
}

if (!function_exists('subReference')) {
	function subReference($str) {
		$max = preg_replace('/[^0-9]/', '', $str);
        if (get_option('format_date_prefix') == "dmY") {
            $max = substr($max, 8);
        }
        $max = ceil($max) + 1;
        return $max;
    }
}

if (!function_exists('updateReferenceNormal')) {
	function updateReferenceNormal($field, $number) {
		$CI = & get_instance();
        return $CI->db->update('tbl_order_ref', [$field => $number], array('ref_id' => '1'));
    }
}

if (!function_exists('updateReference')) {
	function updateReference($field) {
		$CI = & get_instance();
        $q = $CI->db->get_where('tbl_order_ref', array('ref_id' => '1'), 1);
        if ($q->num_rows() > 0) {
            $ref = $q->row();
            $CI->db->update('tbl_order_ref', array($field => $ref->{$field} + 1), array('ref_id' => '1'));
            return TRUE;
        }
        return FALSE;
    }
}

if (!function_exists('formatSAC')) {
	function formatSAC($num) {
	    $pos = strpos((string)$num, ".");
	    if ($pos === false) { $decimalpart="00";}
	    else { $decimalpart= substr($num, $pos+1, 2); $num = substr($num,0,$pos); }

	    if(strlen($num)>3 & strlen($num) <= 12){
	        $last3digits = substr($num, -3 );
	        $numexceptlastdigits = substr($num, 0, -3 );
	        $formatted = $this->makecomma($numexceptlastdigits);
	        $stringtoreturn = $formatted.",".$last3digits.".".$decimalpart ;
	    } elseif(strlen($num)<=3) {
	        $stringtoreturn = $num.".".$decimalpart ;
	    } elseif(strlen($num)>12) {
	        $stringtoreturn = number_format($num, 2);
	    }

	    if(substr($stringtoreturn,0,2)=="-,"){$stringtoreturn = "-".substr($stringtoreturn,2 );}

	    return $stringtoreturn;
	}
}

if (!function_exists('formatDecimalMoney')) {
	function formatDecimalMoney($number, $decimals = NULL)
	{
	    if ( ! is_numeric($number)) {
	        return NULL;
	    }
	    if (!$decimals) {
	        $decimals = get_option('decimals_money');
	    }
	    return number_format($number, $decimals, '.', '');
	}
}

if (!function_exists('formatNumber')) {
	function formatNumber($number, $decimals = NULL)
	{
	    if (!$decimals) {
	        $decimals = get_option('decimals_number');
	    }
	    $ts = get_option('thousands_sep') == '0' ? ' ' : get_option('thousands_sep');
	    $ds = get_option('decimals_sep');
	    return number_format($number, $decimals, $ds, $ts);
	}
}

if (!function_exists('formatMoney')) {
	function formatMoney($number, $decimals = NULL)
	{
	    if(get_option('sac')) {
	        return formatSAC(formatDecimalMoney($number));
	    }
	    if (!$decimals) {
	        $decimals = get_option('decimals_money');
	    }
	    $ts = get_option('thousands_sep') == '0' ? ' ' : get_option('thousands_sep');
	    $ds = get_option('decimals_sep');
	    return number_format($number, $decimals, $ds, $ts);
	}
}

if (!function_exists('status_productions_plan')) {
	function status_productions_plan()
    {
        $option['un_approved'] = lang('un_approved');
        $option['approved'] = lang('approved');
        $option['capacity'] = lang('capacity');
        return $option;
    }
}

if (!function_exists('recursive_stages')) {
	function recursive_stages(&$output = null, $parent_id = 0, $indent = null) {
		$CI = & get_instance();

		$CI->db->select('*');
		$CI->db->from('tbl_stages');
		$CI->db->where('tbl_stages.parent_id', $parent_id);
		$CI->db->order_by('tbl_stages.parent_id');
		$query = $CI->db->get()->result_array();

		foreach ($query as $key => $item) {
			if ($item['parent_id'] == $parent_id) {
				$disabled = '';
				if ($parent_id == 0) {
					$disabled = 'disabled';
				}
				// data-icon="fa fa-ellipsis-h"
				$output .= '<option '. $disabled .'  value="' . $item['id'] . '">'. $indent . $item['name'] . "</option>";
				recursive_stages($output, $item['id'], $indent . "&nbsp;&nbsp;&nbsp;&nbsp;");
			}
		}

	    return $output;

	}
}

if (!function_exists('recursiveCategoryItems')) {
	function recursiveCategoryItems($id = 0, &$output = null, $parent_id = 0, $indent = null) {
		$CI = & get_instance();

		$CI->db->select('*');
		$CI->db->from('tbl_category_items');
		$CI->db->where('tbl_category_items.parent_id', $parent_id);
		$CI->db->order_by('tbl_category_items.parent_id');
		$query = $CI->db->get()->result_array();

		foreach ($query as $key => $item) {
			if ($item['parent_id'] == $parent_id) {
				$disabled = '';
				if ($item['id'] == $id && $id != 0) {
					continue;
				}
				// if ($parent_id == 0) {
				// 	$disabled = 'disabled';
				// }
				// data-icon="fa fa-ellipsis-h"
				$output .= '<option '. $disabled .'  value="' . $item['id'] . '">'. $indent .'➪ '. $item['name'] .'('.$item['code'].')'.  "</option>";
				recursiveCategoryItems($id, $output, $item['id'], $indent . "&nbsp;&nbsp;&nbsp;&nbsp;");
			}
		}

	    return $output;

	}
}

if (!function_exists('recursiveCategoryProducts')) {
	function recursiveCategoryProducts($id = 0, &$output = null, $parent_id = 0, $indent = null) {
		$CI = & get_instance();

		$CI->db->select('*');
		$CI->db->from('tbl_category_products');
		$CI->db->where('tbl_category_products.parent_id', $parent_id);
		$CI->db->order_by('tbl_category_products.parent_id');
		$query = $CI->db->get()->result_array();

		foreach ($query as $key => $item) {
			if ($item['parent_id'] == $parent_id) {
				$disabled = '';
				if ($item['id'] == $id && $id != 0) {
					continue;
				}
				$output .= '<option '. $disabled .'  value="' . $item['id'] . '">'. $indent .'➪ '. $item['name'] .'('.$item['code'].')'. "</option>";
				recursiveCategoryProducts($id, $output, $item['id'], $indent . "&nbsp;&nbsp;&nbsp;&nbsp;");
			}
		}

	    return $output;

	}
}

if (!function_exists('status_productions_capacity')) {
	function status_productions_capacity()
    {
        $option['un_approved'] = lang('un_approved');
        $option['approved'] = lang('approved');
        $option['purchases'] = lang('tnh_st_purchases');
        $option['un_purchases'] = lang('tnh_st_un_purchases');
        return $option;
    }
}

if (!function_exists('tnh_html_entity_decode')) {
	function tnh_html_entity_decode($string)
	{
		if (empty($string)) $string = '';
		return html_entity_decode($string);
	}
}

if (!function_exists('tnh_htmlentities')) {
	function tnh_htmlentities($string)
	{
		return htmlentities($string, ENT_QUOTES);
	}
}

if (!function_exists('checkModule')) {
	function checkModule($module)
	{
		$CI = & get_instance();
		$CI->db->from('tblmodules');
		$CI->db->where('module_name', $module);
		$CI->db->where('active', 1);
		return $CI->db->get()->num_rows();
	}
}

if (!function_exists('refererModel')) {
	function refererModel($message)
	{
		set_alert('danger', $message);
		die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('admin')) . "'; }, 10);</script>");
	}
}

if (!function_exists('recursiveLocations')) {
	function recursiveLocations($id_parent, &$output = null, $indent = null) {
		$CI = & get_instance();

		$CI->db->select('*');
		$CI->db->from('tbllocaltion_warehouses');
		$CI->db->where('tbllocaltion_warehouses.id', $id_parent);
		$query = $CI->db->get()->row_array();
		$name = $query['name'];
		$id_parent = $query['id_parent'];
		if ($id_parent != 0) {
			$output = $name.'->'.$output;
			recursiveLocations($id_parent, $output);
		}
		return substr($output, 0, -2);
	}
}

if (!function_exists('recursiveLocationWarehouses')) {
	function recursiveLocationWarehouses($id, &$output = null, $parent_id = 0, $indent = null) {
		$CI = & get_instance();

		$CI->db->select('*');
		$CI->db->from('tbllocaltion_warehouses');
		$CI->db->where('tbllocaltion_warehouses.id_parent', $parent_id);
		$CI->db->where('tbllocaltion_warehouses.warehouse', $id);
		$CI->db->order_by('tbllocaltion_warehouses.id_parent');
		$query = $CI->db->get()->result_array();

		foreach ($query as $key => $item) {
			if ($item['id_parent'] == $parent_id) {
				$disabled = '';
				$CI->db->from('tbllocaltion_warehouses');
				$CI->db->where('tbllocaltion_warehouses.id_parent', $item['id']);
				$CI->db->limit(1);
				$q = $CI->db->get()->num_rows();
				if ($q) {
					$disabled = 'disabled';
				}
				$output .= '<option '. $disabled .'  value="' . $item['id'] . '">'. $indent .'➪ '. $item['name'] ."</option>";
				recursiveLocationWarehouses($id, $output, $item['id'], $indent . "&nbsp;&nbsp;&nbsp;&nbsp;");
			}
		}
	    return $output;
	}
}
?>