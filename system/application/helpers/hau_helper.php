<?php

defined('BASEPATH') or exit('No direct script access allowed');
    function ch_EditColumSelectInput($value = "", $id = '', $name = "", $ValShow = "", $urlGetData = '', $urlFrom = '', $indexAddfrom = '', $name_data_input = 'data_input')
    {
        $html ='<div class="lableScript">'.$ValShow.' 
                    <a class="editDataTable" data-type="select" data-href="'.$urlGetData.'" data-id="'.$id.'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                </div>
                <div style="width:250px" class="inputScript hide">
                    '.form_open($urlFrom, $indexAddfrom).'
                        <input style="width:250px" name="'.$name_data_input.'" data-hidden="'.$value.'" class="ChangeDataTable" value="'.$value.'"/>
                        <input style="width:250px" name="name_input" type="hidden" value="'.$name.'"/>
                        <input style="width:250px" name="id" type="hidden" value="'.$id.'"/>
                        <div style="width:250px" class="clearfix mtop10"></div>
                        <button type="submit" class="btn btn-icon"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                        <button type="button" class="btn btn-icon text-danger closeEditData"><i class="fa fa-times" aria-hidden="true"></i></i></button>
                    '.form_close().'
                </div>';
        return $html;
    }
    function ch_EditColumSelectInput_1($value = "", $id = '', $name = "", $ValShow = "", $urlGetData = '', $urlFrom = '', $indexAddfrom = '', $name_data_input = 'data_input',$client='')
    {
        $html ='<div class="lableScript">'.$ValShow.' 
                    <a class="editDataTable_ch" data-client="'.$client.'" data-type="select" data-href="'.$urlGetData.'" data-id="'.$id.'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                </div>
                <div style="width:130px" class="inputScript hide">
                    '.form_open($urlFrom, $indexAddfrom).'
                        <input style="width:130px" name="'.$name_data_input.'" data-hidden="'.$value.'" class="ChangeDataTable" value="'.$value.'"/>
                        <input style="width:130px" name="name_input" type="hidden" value="'.$name.'"/>
                        <input style="width:130px" name="id" type="hidden" value="'.$id.'"/>
                        <div style="width:130px" class="clearfix mtop10"></div>
                        <button type="submit" class="btn btn-icon"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                        <button type="button" class="btn btn-icon text-danger closeEditData"><i class="fa fa-times" aria-hidden="true"></i></i></button>
                    '.form_close().'
                </div>';
        return $html;
    }
function convert_number_to_words( $number )
{
    $hyphen = ' ';
    $conjunction = '  ';
    $separator = ' ';
    $negative = 'âm ';
    $decimal = ' phẩy ';
    $dictionary = array(
        0 => 'không',
        1 => 'một',
        2 => 'hai',
        3 => 'ba',
        4 => 'bốn',
        5 => 'năm',
        6 => 'sáu',
        7 => 'bảy',
        8 => 'tám',
        9 => 'chín',
        10 => 'mười',
        11 => 'mười một',
        12 => 'mười hai',
        13 => 'mười ba',
        14 => 'mười bốn',
        15 => 'mười năm',
        16 => 'mười sáu',
        17 => 'mười bảy',
        18 => 'mười tám',
        19 => 'mười chín',
        20 => 'hai mươi',
        30 => 'ba mươi',
        40 => 'bốn mươi',
        50 => 'năm mươi',
        60 => 'sáu mươi',
        70 => 'bảy mươi',
        80 => 'tám mươi',
        90 => 'chín mươi',
        100 => 'trăm',
        1000 => 'nghìn',
        1000000 => 'triệu',
        1000000000 => 'tỷ',
        1000000000000 => 'nghìn tỷ',
        1000000000000000 => 'ngàn triệu triệu',
        1000000000000000000 => 'tỷ tỷ'
    );

    if( !is_numeric( $number ) )
    {
        return false;
    }

    if( ($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX )
    {
        // overflow
        trigger_error( 'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING );
        return false;
    }

    if( $number < 0 )
    {
        return $negative . convert_number_to_words( abs( $number ) );
    }

    $string = $fraction = null;

    if( strpos( $number, '.' ) !== false )
    {
        list( $number, $fraction ) = explode( '.', $number );
    }

    switch (true)
    {
        case $number < 21:
        $string = $dictionary[$number];
        break;
        case $number < 100:
        $tens = ((int)($number / 10)) * 10;
        $units = $number % 10;
        $string = $dictionary[$tens];
        if( $units )
        {
            $string .= $hyphen . $dictionary[$units];
        }
        break;
        case $number < 1000:
        $hundreds = $number / 100;
        $remainder = $number % 100;
        $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
        if( $remainder )
        {
            $string .= $conjunction . convert_number_to_words( $remainder );
        }
        break;
        default:
        $baseUnit = pow( 1000, floor( log( $number, 1000 ) ) );
        $numBaseUnits = (int)($number / $baseUnit);
        $remainder = $number % $baseUnit;
        $string = convert_number_to_words( $numBaseUnits ) . ' ' . $dictionary[$baseUnit];
        if( $remainder )
        {
            $string .= $remainder < 100 ? $conjunction : $separator;
            $string .= convert_number_to_words( $remainder );
        }
        break;
    }

    if( null !== $fraction && is_numeric( $fraction ) )
    {
        $string .= $decimal;
        $words = array( );
        foreach( str_split((string) $fraction) as $number )
        {
            $words[] = $dictionary[$number];
        }
        $string .= implode( ' ', $words );
    }

    return $string;
}
function get_table_where_select($select,$table, $where = array(),$order_by="",$result='result_array',$colum_where="",$where_in=array())
{
    $CI =& get_instance();
    if($select!="")
    {
        $CI->db->select($select);
    }
    if (isset($where) && is_array($where)) {
        $i = 0;
        foreach ($where as $key => $val) {
            if (is_numeric($key)) {
                $CI->db->where($val);
                unset($where[$key]);
            }
            $i++;
        }
        $CI->db->where($where);
    }
    elseif(strlen($where)>0)
    {
        $CI->db->where($where);
    }
    if($where_in!=array()&&$colum_where!="")
    {
        $CI->db->where_in($colum_where,$where_in);
    }
    if($order_by!="")
    {
        $CI->db->order_by($order_by);
    }
    $result=$CI->db->get($table)->$result();
    

    if ($result) {
        return $result;
    } else {
        return array();
    }
}
function sum_from_table_join($table, $attr = array())
{
    // var_dump($attr['where']);die;
    if (!isset($attr['field'])) {
        show_error('sum_from_table(); function expect field to be passed.');
    }
    $CI =& get_instance();
    if (isset($attr['where']) && is_array($attr['where'])) {
        $i = 0;
        foreach ($attr['where'] as $key => $val) {
            if (is_numeric($key)) {
                $CI->db->where($val);
                unset($attr['where'][$key]);
            }
            $i++;
        }
        $CI->db->where($attr['where']);
    }
    if (isset($attr['where_or']) && !empty($attr['where_or'])) {
        $CI->db->where($attr['where_or']);
    }
    if (isset($attr['join']) && is_array($attr['join'])) {
        foreach ($attr['join'] as $key => $val) {
            $val=explode(',', $val);
            if(count($val)==3)
            {
                $CI->db->join($val[0],$val[1],$val[2]);
            }
            else
            {
                $CI->db->join($val[0],$val[1]);
            }
        }
    }
    elseif(strlen($attr['join'])>0)
    {
        $attr['join']=explode(',', $attr['join']);
        if(count($attr['join'])==3)
        {
            $CI->db->join($attr['join'][0],$attr['join'][1],$attr['join'][1]);
        }
        else
        {
            $CI->db->join($attr['join'][0],$attr['join'][1]);
        }
    }
    // $CI->db->select('product_id,product_quantity,warehouse,warehouseid');
    $CI->db->select_sum($attr['field']);
    $CI->db->from($table);
    $result = $CI->db->get()->row();
    // var_dump($CI->db->last_query());die;
    // echo "<pre>";
    // var_dump($result);die;
    $field=$attr['field'];
    if(strpos($attr['field'], '.')!== false)
    {
        $field=strafter($attr['field'],'.');
    }
    return $result->{$field};
}

/**
 * General function for all datatables, performs search,additional select,join,where,orders
 * @param  array $aColumns           table columns
 * @param  mixed $sIndexColumn       main column in table for bettter performing
 * @param  string $sTable            table name
 * @param  array  $join              join other tables
 * @param  array  $where             perform where in query
 * @param  array  $additionalSelect  select additional fields
 * @param  string $orderby
 * @param  string $groupBy - note yet tested
 * @return array
 */
function data_tables_init_having($aColumns, $sIndexColumn, $sTable, $join = array(), $where = array(), $additionalSelect = array(), $orderby = '', $groupBy = '',$having='')
{

    $CI =& get_instance();
    $__post = $CI->input->post();

    /*
     * Paging
     */
    $sLimit = "";
    if ((is_numeric($CI->input->post('start'))) && $CI->input->post('length') != '-1') {
        $sLimit = "LIMIT " . intval($CI->input->post('start')) . ", " . intval($CI->input->post('length'));
    }
    $_aColumns = array();
    foreach ($aColumns as $column) {
        // if found only one dot
        if (substr_count($column, '.') == 1 && strpos($column, ' as ') === false) {
            $_column = explode('.', $column);
            if (isset($_column[1])) {
                if (_startsWith($_column[0], 'tbl')) {
                    $_prefix = prefixed_table_fields_wildcard($_column[0], $_column[0], $_column[1]);
                    array_push($_aColumns, $_prefix);
                } else {
                    array_push($_aColumns, $column);
                }
            } else {
                array_push($_aColumns, $_column[0]);
            }
        } else {
            array_push($_aColumns, $column);
        }
    }
    /*
     * Ordering
     */
    $sOrder = "";
    if ($CI->input->post('order')) {
        $sOrder = "ORDER BY  ";
        foreach ($CI->input->post('order') as $key => $val) {

            $sOrder .= $aColumns[intval($__post['order'][$key]['column'])];

            $__order_column = $sOrder;
            if (strpos($__order_column, ' as ') !== false) {
                $sOrder = strbefore($__order_column, ' as');
            }
            $_order = strtoupper($__post['order'][$key]['dir']);
            if ($_order == 'ASC') {
                $sOrder .= ' ASC';
            } else {
                $sOrder .= ' DESC';
            }
            $sOrder .= ', ';
        }
        if (trim($sOrder) == "ORDER BY") {
            $sOrder = "";
        }
        if ($sOrder == '' && $orderby != '') {
            $sOrder = $orderby;
        } else {
            $sOrder = substr($sOrder, 0, -2);
        }

    } else {
        $sOrder = $orderby;
    }
    /*
     * Filtering
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here, but concerned about efficiency
     * on very large tables, and MySQL's regex functionality is very limited
     */
    $sWhere = "";
    if ((isset($__post['search'])) && $__post['search']['value'] != "") {
        $search_value = $__post['search']['value'];

        $sWhere = "WHERE (";
        for ($i = 0; $i < count($aColumns); $i++) {
            $__search_column = $aColumns[$i];
            if (strpos($__search_column, ' as ') !== false) {
                $__search_column = strbefore($__search_column, ' as');
            }
            if (($__post['columns'][$i]) && $__post['columns'][$i]['searchable'] == "true") {
                $sWhere .= $__search_column . " LIKE '%" . $search_value . "%' OR ";
            }
        }
        if (count($additionalSelect) > 0) {
            foreach ($additionalSelect as $searchAdditionalField) {
                if (strpos($searchAdditionalField, ' as ') !== false) {
                    $searchAdditionalField = strbefore($searchAdditionalField, ' as');
                }

                $sWhere .= $searchAdditionalField . " LIKE '%" . $search_value . "%' OR ";
            }
        }
        $sWhere = substr_replace($sWhere, "", -3);
        $sWhere .= ')';
    } else {
        // Check for custom filtering
        $searchFound = 0;
        $sWhere      = "WHERE (";
        for ($i = 0; $i < count($aColumns); $i++) {
            if (($__post['columns'][$i]) && $__post['columns'][$i]['searchable'] == "true") {
                $search_value    = $__post['columns'][$i]['search']['value'];
                $__search_column = $aColumns[$i];
                if (strpos($__search_column, ' as ') !== false) {
                    $__search_column = strbefore($__search_column, ' as');
                }
                if ($search_value != '') {
                    $sWhere .= $__search_column . " LIKE '%" . $search_value . "%' OR ";
                    if (count($additionalSelect) > 0) {
                        foreach ($additionalSelect as $searchAdditionalField) {
                            $sWhere .= $searchAdditionalField . " LIKE '%" . $search_value . "%' OR ";
                        }
                    }
                    $searchFound++;
                }
            }
        }
        if ($searchFound > 0) {
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        } else {
            $sWhere = '';
        }
    }

    /*
     * SQL queries
     * Get data to display
     */
    $_additionalSelect = '';
    if (count($additionalSelect) > 0) {
        $_additionalSelect = ',' . implode(',', $additionalSelect);
    }
    $where = implode(' ', $where);
    if ($sWhere == '') {
        $where = trim($where);
        if (_startsWith($where, 'AND') || _startsWith($where, 'OR')) {
            if (_startsWith($where, 'OR')) {
                $where = substr($where, 2);
            } else {
                $where = substr($where, 3);
            }
            $where = 'WHERE ' . $where;
        }
    }
    $sQuery  = "
    SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $_aColumns)) . " " . $_additionalSelect . "
    FROM   $sTable
    " . implode(' ', $join) . "
    $sWhere
    " . $where . "
    $groupBy
    $having
    $sOrder
    $sLimit
    ";
             // return $sQuery;
    // exit($sQuery);
    $rResult = $CI->db->query($sQuery)->result_array();

    /* Data set length after filtering */
    $sQuery         = "
    SELECT FOUND_ROWS()
    ";
    $_query         = $CI->db->query($sQuery)->result_array();
    $iFilteredTotal = $_query[0]['FOUND_ROWS()'];
    if (_startsWith($where, 'AND')) {
        $where = 'WHERE ' . substr($where, 3);
    }
    /* Total data set length */
    $sQuery = "
    SELECT COUNT(" . $sTable . '.' . $sIndexColumn . ")
    FROM $sTable " . implode(' ', $join) . ' ' . $where;
    $_query = $CI->db->query($sQuery)->result_array();
    $iTotal = $_query[0]['COUNT(' . $sTable . '.' . $sIndexColumn . ')'];
    /*
     * Output
     */
    $output = array(
        "draw" => $__post['draw'] ? intval($__post['draw']) : 0,
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iFilteredTotal,
        "aaData" => array()
    );

    return array(
        'rResult' => $rResult,
        'output' => $output
    );
}
/**
 * Prefix field name with table ex. table.column
 * @param  string $table
 * @param  string $alias
 * @param  string $field field to check
 * @return string
 */
function get_table_where_sum($table, $where = array(),$field='total')
{
    $CI =& get_instance();
    $CI->db->select_sum($field);
    if (isset($where) && is_array($where)) {
        $i = 0;
        foreach ($where as $key => $val) {
            if (is_numeric($key)) {
                $CI->db->where($val);
                unset($where[$key]);
            }
            $i++;
        }
        $CI->db->where($where);
    }
    elseif(strlen($where)>0)
    {
        $CI->db->where($where);
    }
    $result=$CI->db->get($table)->row();
    if ($result) {
        return $result->$field;
    } else {
        return 0;
    }
}
function render_input_ch($name, $label = '', $value = '', $type = 'text', $input_attrs = [], $form_group_attr = [], $form_group_class = '', $input_class = '')
{
    $input            = '';
    $_form_group_attr = '';
    $_input_attrs     = '';
    foreach ($input_attrs as $key => $val) {
        // tooltips
        if ($key == 'title') {
            $val = _l($val);
        }
        $_input_attrs .= $key . '=' . '"' . $val . '" ';
    }

    $_input_attrs = rtrim($_input_attrs);

    $form_group_attr['app-field-wrapper'] = $name;

    foreach ($form_group_attr as $key => $val) {
        // tooltips
        if ($key == 'title') {
            $val = _l($val);
        }
        $_form_group_attr .= $key . '=' . '"' . $val . '" ';
    }

    $_form_group_attr = rtrim($_form_group_attr);

    if (!empty($form_group_class)) {
        $form_group_class = ' ' . $form_group_class;
    }
    if (!empty($input_class)) {
        $input_class = ' ' . $input_class;
    }
    $input .= '<div class="form-group' . $form_group_class . '" ' . $_form_group_attr . '>';
    if ($label != '') {
        $input .= '<label for="' . $name . '" class="control-label">' . _l($label, '', false) . '</label>';
    }
    $input .= '<div class="input-group">';
    $input .= '<input data-href="'.$value.'" id="run_href" type="submit" id="' . $name . '" name="' . $name . '" class="form-control' . $input_class . '" ' . $_input_attrs . ' value="'.$value.'">';
        $input .= '<div class="input-group-addon">
    <a href="#" onclick="new_link(this);return false;" class="suppliers-field-new"><i id="icon_hau" class="fa fa-plus"></i></a>
    </div>';
    $input .= '</div>';
    $input .= '</div>';
    return $input;
}
function vn_to_str($str){
 
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

function recursive_Category_Items($id = 0, &$output = null, $parent_id = 0, $indent = null) {
    $CI = & get_instance();
    $CI->db->select('*');
    $CI->db->from('tblcategories');
    $CI->db->where('tblcategories.category_parent', $parent_id);
    $CI->db->order_by('tblcategories.category_parent');
    $query = $CI->db->get()->result_array();
    foreach ($query as $key => $item) {
        if ($item['category_parent'] == $parent_id) {
            $disabled = '';
            if ($item['id'] == $id && $id != 0) {
                continue;
            }
            $output .= '<option '. $disabled .'  value="' . $item['id'] . '">'. $indent .'➪ '. $item['category'] . "</option>";
            recursive_Category_Items($id, $output, $item['id'], $indent . "&nbsp;&nbsp;&nbsp;&nbsp;");
        }
    }
    return $output;
}
function Check_Exists_Items($id='')
{
    $purchases = get_table_where('tblpurchases_items',array('type'=>'items','product_id'=>$id),'','row');
    $RFQ = get_table_where('tblrfq_ask_price_items',array('type'=>'items','product_id'=>$id),'','row');
    $supplier_quotes = get_table_where('tblsupplier_quote_items',array('type'=>'items','product_id'=>$id),'','row');
    $import = get_table_where('tblimport_items',array('type'=>'items','product_id'=>$id),'','row');
    $purchase_order = get_table_where('tblpurchase_order_items',array('type'=>'items','product_id'=>$id),'','row');
    if(!empty($purchases) || !empty($RFQ) || !empty($supplier_quotes) || !empty($purchase_order) || !empty($import))
    {
        return  true;
    }else
    {
        return  false;
    }
}
function Check_combo_Items($id='')
{
    $combo = get_table_where('tblcombo_items',array('product_id'=>$id));
    if(!empty($combo))
    {      
            foreach ($combo as $key => $value) {
                if(Check_Exists_Items($value['rel_id']))
                {
                    return  1;
                }
            }
            return  2;
    }else
    {
        return  false;
    }
}
function get_supplier_full_name($id='')
{
   if(is_numeric($id))
   {
        $CI =& get_instance();
        $CI->db->where('id', $id);
        $suppliers = $CI->db->get('tblsuppliers')->row();
        return $suppliers->company;
   }else
   {
    return '';
   }
}
function get_full_childs_id($parent_id='', &$result=array()) {
        $CI =& get_instance();
        array_push($result, $parent_id);
        $CI->db->where('id_parent', $parent_id);
        $items = $CI->db->get('tbllocaltion_warehouses')->result();  
        foreach($items as $value) {
            get_full_childs_id($value->id, $result);
        }
}
function sumExistsQ_all($type='',$product_id='',$items=array(),$index=0)
{
    $total=0;
    if(is_numeric($product_id) && isset($items))
    {
        for ($i=$index; $i < count($items) ; $i++) { 
            if(($items[$i]['product_id']==$product_id)&&($items[$i]['tblwarehouse_product.type_items']==$type))
            {
                $total+=$items[$i]['quantity'];
            }
            if($items[$i]['product_id']!=$product_id) break;
        }
    }
    return $total;
}
function set_status_purchse_order($id='')
    {
       $CI =& get_instance();
       $order = get_table_where('tblpurchase_order',array('id'=>$id),'','row');

       $get_purchases_items = get_table_where('tblpurchases_items',array('purchases_id'=>$order->id_purchase_proce));
       $count = 0;
       foreach ($get_purchases_items as $key => $value) {
           $quantity_net = sum_quantity_order_purchases_all($value['type'],$order->id_purchase_proce,$value['product_id']);
           $quantity =  $value['quantity_net'] - $quantity_net;
           // echo '<pre>';
           // var_dump($quantity,$value['product_id']);
           if($quantity > 0)
           {
            $count++;
           }
       }
        // die;
       if($count == 0)
       {
            $purchases = get_table_where('tblpurchases',array('id'=>$order->id_purchase_proce),'','row');
                        $staff_id='1foso';
                        $date=date('Y-m-d H:i:s');
                        $history_status = $purchases->history_status;
                        $history_status.='|'.$staff_id.','.$date;
                    $in = array(
                        'history_status'=>$history_status,
                        'note_cancel' => '',
                        'status' => 4,
                    );
                    $CI->db->where('id', $order->id_purchase_proce);
                    $result = $CI->db->update('tblpurchases', $in);
       }else
       {
            $ktr_purchases = get_table_where('tblpurchases',array('id'=>$order->id_purchase_proce),'','row');
                if($ktr_purchases->status = 4)
                {
                    $cance = explode('|', $ktr_purchases->history_status);
                    $cances = explode(',',$cance[3]);
                    if($cances[0] == '1foso')
                    {
                            $history_statuss = '';
                            $history_status = $ktr_purchases->history_status;
                            $history = explode('|', $history_status);
                            foreach ($history as $key => $value) {
                                if($key > 0)
                                {
                                if($key < 3)
                                {
                               $history_statuss.='|'.$value; 
                                }
                                }
                            }
                        $in = array(
                            'history_status'=>$history_statuss,
                            'note_cancel' => '',
                            'status' => 3,
                        );
                        $CI->db->where('id', $order->id_purchase_proce);
                        $CI->db->update('tblpurchases', $in);
                    }
                }
       }
    }
function sum_quantity_import($type='',$id='',$id_product='')
    {
        $CI =& get_instance();
        $CI->db->select('SUM(tblimport_items.quantity_net) as quantity_net');
        $CI->db->from('tblimport_items');
        $CI->db->join('tblimport','tblimport.id=tblimport_items.id_import','left');
        $CI->db->where('tblimport.id_order',$id);
        $CI->db->where('product_id',$id_product);
        $CI->db->where('type',$type);
        return $CI->db->get()->row()->quantity_net;
    }
function sum_quantity_order($type='',$id='',$id_product='')
    {
        $CI =& get_instance();
        $CI->db->select('SUM(tblpurchase_order_items.quantity_suppliers) as quantity_suppliers');
        $CI->db->from('tblpurchase_order_items');
        $CI->db->join('tblpurchase_order','tblpurchase_order.id=tblpurchase_order_items.id_purchase_order','left');
        $CI->db->where('tblpurchase_order.id_quotes',$id);
        $CI->db->where('product_id',$id_product);
        $CI->db->where('type',$type);
        return $CI->db->get()->row()->quantity_suppliers;
    }
function sum_quantity_order_purchases_all($type='',$id='',$id_product='')
    {
        $CI =& get_instance();
        $CI->db->select('SUM(tblpurchase_order_items.quantity_suppliers) as quantity_suppliers');
        $CI->db->from('tblpurchase_order_items');
        $CI->db->join('tblpurchase_order','tblpurchase_order.id=tblpurchase_order_items.id_purchase_order','left');
        $CI->db->where('tblpurchase_order.id_purchase_proce',$id);
        $CI->db->where('product_id',$id_product);
        $CI->db->where('type',$type);
        return $CI->db->get()->row()->quantity_suppliers;
    } 
function sum_quantity_order_purchases($type='',$id='',$id_product='')
    {
        $CI =& get_instance();
        $CI->db->select('SUM(tblpurchase_order_items.quantity_suppliers) as quantity_suppliers');
        $CI->db->from('tblpurchase_order_items');
        $CI->db->join('tblpurchase_order','tblpurchase_order.id=tblpurchase_order_items.id_purchase_order','left');
        $CI->db->where('tblpurchase_order.id_purchases',$id);
        $CI->db->where('product_id',$id_product);
        $CI->db->where('type',$type);
        return $CI->db->get()->row()->quantity_suppliers;
    }    
function get_localtion_warehouses_import_excel($where=array(),$lever = NULL)
{
    $CI =& get_instance();
    $CI->db->where('(id_parent is null or id_parent = 0)');
    $CI->db->where('child',0);
    $CI->db->where('status',0);
    if(!empty($lever))
    {
        $lever = (int)$lever - 1;
        $CI->db->where('lever',$lever);
    }
    if(!empty($where))
    {
        $CI->db->where($where);
    }
    $localtion=$CI->db->get('tbllocaltion_warehouses')->result_array();
    $string_option="<option></option>";
    foreach($localtion as $key=>$value)
    {
        if(!empty($value['id']))
        {   
            $string_option.='<option value="'.$value['id'].'" '.($value['child']?'child="'.$value['child'].'"':'').' data-content="'.$value['name_parent'].'" content="'.$value['name_parent'].'">'.$value['name'].'</option>';
            if(empty($lever))
            {
            $string_option.=option_child_localtion_warehouses($value['id']);
            }
        }
    }
    return $string_option;
}
function option_child_localtion_warehouses_import_excel($id_parent="",$list_array="<i class='fa fa-caret-right' aria-hidden='true'></i>",&$string_option="")
{
    $CI =& get_instance();
    if(!empty($id_parent))
    {
        $CI->db->where('id_parent',$id_parent);
        $CI->db->where('status',0);
        $localtion=$CI->db->get('tbllocaltion_warehouses')->result_array();
        foreach($localtion as $key=>$value)
        {
            if(!empty($value['id']))
            {
                $string_option.='<option value="'.$value['id'].'" '.($value['child']?('child="'.$value['child'].'" content="'.(get_listname_localtion_warehouse($value['id']).'"')):'').' data-content="'.$list_array.' '.$value['name'].'">'.$value['name'].'</option>';
                option_child_localtion_warehouses_import_excel($value['id'],("<i class='fa fa-caret-right' aria-hidden='true'></i>".$list_array),$string_option);
            }
        }
        return $string_option;
    }
}
function get_localtion_warehouses($where=array(),$lever = NULL,$checked='')
{
    $CI =& get_instance();
    $CI->db->where('status',0);
    if(!empty($lever))
    {
        $lever = (int)$lever - 1;
        $CI->db->where('lever',$lever);
    }else
    {
        $CI->db->where('(( child = 1 and id_parent = 0) or ((id_parent is null or id_parent = 0) and child = 0))');
    }
    if(!empty($where))
    {
        $CI->db->where($where);
    }
    $localtion=$CI->db->get('tbllocaltion_warehouses')->result_array();
    $string_option="<option></option>";
    foreach($localtion as $key=>$value)
    {
        if(!empty($value['id']))
        {   
            $checkeds='';
            if($checked == $value['id'])
            {
                    $checkeds = 'selected';
                    $value['child'] = 1;
            }
            $string_option.='<option '.$checkeds.' value="'.$value['id'].'" '.($value['child']?'child="'.$value['child'].'"':'').' data-content="'.$value['name_parent'].'" content="'.$value['name_parent'].'">'.$value['name'].'</option>';
            if(empty($lever))
            {
            $string_option.=option_child_localtion_warehouses($value['id'],'',$checked);
            }
        }
    }
    return $string_option;
}
function option_child_localtion_warehouses($id_parent="",$list_array="<i class='fa fa-caret-right' aria-hidden='true'></i>",$checked='',&$string_option="")
{
    $CI =& get_instance();
    if(!empty($id_parent))
    {
        $CI->db->where('id_parent',$id_parent);
        $CI->db->where('status',0);
        $localtion=$CI->db->get('tbllocaltion_warehouses')->result_array();
        foreach($localtion as $key=>$value)
        {
            if(!empty($value['id']))
            {
                $checkeds='';

                if($checked == $value['id'])
                {
                    $checkeds = 'selected';
                }
                $string_option.='<option '.$checkeds.' value="'.$value['id'].'" '.($value['child']?('child="'.$value['child'].'" content="'.(get_listname_localtion_warehouse($value['id']).'"')):'').' data-content="'.$list_array.' '.$value['name'].'">'.$value['name'].'</option>';
                option_child_localtion_warehouses($value['id'],("<i class='fa fa-caret-right' aria-hidden='true'></i>".$list_array),$checked,$string_option);
            }
        }
        return $string_option;
    }
}
function get_options_search_cbo($rel_type='',$rel_id='',$type_items='')
{   

    $rel_data = get_relation_data($rel_type,$rel_id,$type_items);
    $rel_val = get_relation_values($rel_data,$rel_type);
    if(!empty($rel_val))
        return array($rel_val);
    return array();
}
function get_listname_localtion_warehouse($id="")
{
    if(!empty($id))
    {
        $CI =& get_instance();
        $CI->db->where('id',$id);
        $localtion=$CI->db->get('tbllocaltion_warehouses')->row();
        if(!empty($localtion))
        {
            $string=$localtion->name;
            if(!empty($localtion->id_parent))
            {
                return get_parent_localtion_warehouses($localtion->id_parent,$string);
            }
            else
            {
                return $string;
            }
        }
    }
    return false;
}
function get_parent_localtion_warehouses($id="",&$string="")
{
    $CI =& get_instance();
    if(!empty($id))
    {
        $CI->db->where('id',$id);
        $localtion=$CI->db->get('tbllocaltion_warehouses')->row();
        if(!empty($localtion))
        {
            $string=$localtion->name." <i class='fa fa-caret-right text-danger' aria-hidden='true'></i> ".$string;
            if(!empty($localtion->id_parent))
            {
                get_parent_localtion_warehouses($localtion->id_parent,$string);
            }
        }
        return $string;
    }
}
function button_child_localtion_warehouses($id_parent="",$list_array='<i class="fa fa-caret-right" aria-hidden="true"></i><i class="fa fa-caret-right" aria-hidden="true"></i>',&$string=array('name'=>'','date_create'=>'','status'=>'','delete'=>''))
{
    $CI =& get_instance();
    if(!empty($id_parent))
    {
        $CI->db->where('id_parent',$id_parent);
        $localtion=$CI->db->get('tbllocaltion_warehouses')->result_array();
        foreach($localtion as $key=>$value)
        {
            if(!empty($value['id']))
            {
                $string['name'].='<p '.(!empty($value['child'])?'class="text-danger"':'').'>'.$list_array.' '.$value['name'].'
                             
                         </p>';
                $string['date_create'].='<p>'._dt($value['date_create']).'</p>';

                $string['status'].='<p class="onoffswitch '.($value['status'] == 0 ? 'onoffswitch_ch' : 'onoffswitch_chc' ).'" data-switch-url="' . admin_url() . 'warehouse/change_warehouse_localtion_status/'.$value['id'].'/'.$value['status'].'" data-toggle="tooltip" data-title="' . _l('') . '">
                <input type="checkbox"' . (!    is_admin() ? ' disabled' : '') . ' data-switch-url="' . admin_url() . 'warehouse/change_warehouse_localtion_status" name="onoffswitch" class="onoffswitch-checkbox" id="' . $value['id'] . '" data-id="' . $value['id'] . '" ' . ($value['status'] == 0 ? 'checked' : '') . '>
                <label style="height: 23px;" class="onoffswitch-label" for="' . $value['id'] . '"></label>
                </p>';
                $string['delete'].='<p>'.(($value['child'] == 1&&!exsit_localtion($value['warehouse'],$value['id'])) ? '<a onclick="delete_localtion_warehouses('.$value['id'].')" class="btn btn-danger  btn-icon pull-right" data-toggle="tooltip" data-placement="left"  title='._l('ch_delete_localtion').'>
                            <i class="fa fa-remove"></i>
                        </a>' : '' ).'
                             <a class="btn btn-default btn-icon pull-right" data-loading-text="<i class=\'fa fa-circle-o-notch fa-spin\'></i> '._l('ch_loading').'" onclick="new_localtion_warehouse('.$value['id'].',this)"><i class="fa fa-pencil-square-o"></i>
                             </a></p>';
                button_child_localtion_warehouses($value['id'],('<i class="fa fa-caret-right" aria-hidden="true"></i><i class="fa fa-caret-right" aria-hidden="true"></i>'.$list_array.$value['name'].'<i class="fa fa-caret-right" aria-hidden="true"></i>'),$string);
            }
        }
        return $string;
    }
}
function ch_getProvince($id)
{
    $CI = & get_instance();
    if (isset($id)) {
        $CI->db->where('provinceid',$id);
        return $CI->db->get('province')->row();
    }
    return false;
}
function ch_getDistrict($id)
{        
    $CI = & get_instance();
    if (isset($id)) {
        $CI->db->where('districtid',$id);
        return $CI->db->get('district')->row();
    }
    return false;
}
function ch_getWard($id)
{        
    $CI = & get_instance();
    if (isset($id)) {
        $CI->db->where('wardid',$id);
        return $CI->db->get('ward')->row();
    }
    return false;
}    
function get_fields_export_excel_hau()
{
    $CI = & get_instance();
    $colum_client = $CI->db->list_fields(db_prefix().'suppliers');
            $colum_client = array_diff($colum_client, [
            'default_language' ,
            'default_currency',
        ]);
    $colum_info_client = $CI->db->get(db_prefix().'suppliers_info_detail')->result_array();
    return ['colum_client' => $colum_client, 'colum_info_client' => $colum_info_client];
}
function get_fields_export_excel_categories_hau()
{
    $CI = & get_instance();
    $colum_categories = $CI->db->list_fields(db_prefix().'categories');
        $colum_categories = array_diff($colum_categories, [
            'code' ,
        ]);
    return ['colum_categories' => $colum_categories];
}
function get_fields_import_excel_hau()
{
    $CI = & get_instance();
    $colum_client = $CI->db->list_fields(db_prefix().'suppliers');
            $colum_client = array_diff($colum_client, [
            'default_language',
            'id',
            'prefix',
            'default_currency',
            'addedfrom',
            'datecreated',
        ]);
    $colum_info_client = $CI->db->get(db_prefix().'suppliers_info_detail')->result_array();
    return ['colum_client' => $colum_client, 'colum_info_client' => $colum_info_client];
}
function get_fields_import_items_excel_hau()
{
    $CI = & get_instance();
    $colum_client = $CI->db->list_fields(db_prefix().'items');
            $colum_client = array_diff($colum_client, [
            'id',
            'prefix',
            'rate',
            'tax',
            'avatar',
            'images_product',
            'active',
            'is_tax',
            'info',
            'country_id',
            'type_items',
            'specification',
            'staff_id',
            'date_create',
            'description',
        ]);
    return ['colum_items' => $colum_client];
}
function get_status_label_hau($id)
{
    $label = 'default';

    if ($id == 2) {
        $label = 'light-green';
    } else if ($id == 3) {
        $label = 'default';
    } else if ($id == 4) {
        $label = 'info';
    } else if ($id == 5) {
        $label = 'success';
    } else if ($id == 6) {
        $label = 'warning';
    }

    return $label;
}
function format_status_number_ch($id)
{
    $label = get_status_label_hau(6);
    $status_name=$id.' '._l('ch_quote_count');
    $class = 'label label-' . $label;
    return '<span class="inline-block ' . $class . '">' . $status_name . '</span>';
}
function format_status_number_rfq_ch($id)
{
    $label = get_status_label_hau(6);
    $status_name=$id.' '._l('ch_quote_count');
    $class = 'label label-' . $label;
    return  $status_name;
}
function format_status_number_import_ch($id)
{
    $label = get_status_label_hau(6);
    $status_name=$id.' '._l('ch_importss');
    $class = 'label label-' . $label;
    return  $status_name;
}
function format_status_number_order_ch($id)
{
    $label = get_status_label_hau(6);
    $status_name=$id.' '._l('ch_data_order_t');
    $class = 'label label-' . $label;
    return  $status_name;
}

function format_status_number_invoices_ch($id)
{
    $label = get_status_label_hau(6);
    $status_name=$id.' '._l('client_invoices_tab');
    $class = 'label label-' . $label;
    return  $status_name;
}
function format_status_invoice($id)
{

        $label = get_status_label_purchases($id);
        if ($id == 1) {
            $label = 'light-green';
            $item_name='Hóa đơn thuế';
        }
        else if ($id == 2) {
                $label = 'info';
                $item_name='Hóa đơn lẻ';
            }
            $class = 'label label-' . $label;
            return '<span class="inline-block ' . $class . '">' . $item_name . '</span>';
}
function format_status_pay_slip($id)
{

        $label = get_status_label_hau($id);
        if ($id == 2) {
            $label = 'light-green';
            $status_name=_l('ch_status_pays_slip');
        }
        else if ($id == 1) {
                $label = 'info';
                $status_name=_l('ch_status_pays_slip_part');
            } else if ($id == 0) {
                $label = 'warning';
                $status_name=_l('ch_status_pays_slip_no');
            }
            $class = 'label label-' . $label;
            return '<span class="inline-block ' . $class . '">' . $status_name . '</span>';
}
function format_status_suppler_quote($id)
{

        $label = get_status_label_hau($id);
        if ($id == 2) {
            $label = 'light-green';
            $status_name=_l('ch__quote_ncc');
        }
        else if ($id == 1) {
                $label = 'info';
                $status_name=_l('ch__YCMH');
            } else if ($id == 0) {
                $label = 'warning';
                $status_name=_l('dont_approve');
            }
            $class = 'label label-' . $label;
            return '<span class="inline-block ' . $class . '">' . $status_name . '</span>';
}
function get_table_where_sum_ch($table, $where = array(),$field='total')
{
    $CI =& get_instance();
    $CI->db->select_sum($field);
    if (isset($where) && is_array($where)) {
        $i = 0;
        foreach ($where as $key => $val) {
            if (is_numeric($key)) {
                $CI->db->where($val);
                unset($where[$key]);
            }
            $i++;
        }
        $CI->db->where($where);
    }
    elseif(strlen($where)>0)
    {
        $CI->db->where($where);
    }
    $result=$CI->db->get($table)->row();
    if ($result) {
        return $result->$field;
    } else {
        return 0;
    }
}
function format_purchase_order_father($id='', $classes = '', $label = true ,$size = '14px')
{   
    $status_name='';
    $purchase_order = get_table_where('tblpurchase_order',array('id'=>$id),'','row');    
    if(!empty($purchase_order->id_purchases))
    {
        $purchase = get_table_where('tblpurchases',array('id'=>$purchase_order->id_purchases),'','row');
        $label = 'info';
        $status_name=_l('ch__tuYCMH').' :'.$purchase->prefix.'-'.$purchase->code;
        $class = 'label label-' . $label;
        return '<span style="font-size:'.$size.'" class="inline-block ' . $class . '">' . $status_name . '</span>';
    }else
    {
        return;
    }
}
function format_purchase_order_father_all($id='', $classes = '', $label = true ,$size = '14px')
{   
    $status_name='';
    $purchase_order = get_table_where('tblpurchase_order',array('id'=>$id),'','row');    
    if(!empty($purchase_order->id_purchases))
    {
        $id_purchases = explode(',', trim($purchase_order->id_purchases,','));
        $data='';
        foreach ($id_purchases as $key => $value) {
        $purchase = get_table_where('tblpurchases',array('id'=>$purchase_order->id_purchases),'','row');
        $data.= $purchase->prefix.$purchase->code.',';   
        }
        $label = 'info';
        $status_name=count($id_purchases).' '._l('YCMH').' :'.trim($data,',');
        $class = 'label label-' . $label;
        return '<span style="font-size:'.$size.'" class="inline-block ' . $class . '">' . $status_name . '</span>';
    }else
    {
        return;
    }
}
function format_quotes_father($id='', $classes = '', $label = true ,$size = '14px')
{   
    $status_name='';
    $purchase_order = get_table_where('tblsupplier_quotes',array('id'=>$id),'','row');    
    if(!empty($purchase_order->id_purchases))
    {
        $purchase = get_table_where('tblpurchases',array('id'=>$purchase_order->id_purchases),'','row');
        $label = 'info';
        $status_name=_l('ch__tuYCMH').' :'.$purchase->prefix.'-'.$purchase->code;
        $class = 'label label-' . $label;
        return '<span style="font-size:'.$size.'" class="inline-block ' . $class . '">' . $status_name . '</span>';
    }if(!empty($purchase_order->id_ask_price))
    {
        $ask_price = get_table_where('tblrfq_ask_price',array('id'=>$purchase_order->id_ask_price),'','row');
        $label = 'info';
        $status_name=_l('ch__turfq').' :'.$ask_price->prefix.'-'.$ask_price->code;
        $class = 'label label-' . $label;
        return '<span style="font-size:'.$size.'" class="inline-block ' . $class . '">' . $status_name . '</span>';
    }
}
function format_purchase_order($id)
{
    $purchase_order = get_table_where('tblpurchase_order',array('id'=>$id),'','row');    
    if(!empty($purchase_order->id_purchases))
    {
        if($purchase_order->check_purchase_all == 1)
        {
        $id_purchases = explode(',', trim($purchase_order->id_purchases,','));
        $_data='';
        foreach ($id_purchases as $k => $v) {
            $purchase = get_table_where('tblpurchases',array('id'=>$v),'','row');
                    $_data.= '<li><a onclick="view_purchases('.$v.'); return false;" >' . $purchase->prefix.$purchase->code. '</a></li>';
                }
                $_outputStatus = '<div class="dropdown" style="text-align: center;">
                            <button class="dropdown-toggle no_background" style="border: 1px solid #03a9f4;color: #03a9f4;" type="button" data-toggle="dropdown">'.count($id_purchases).' YCMH
                            </button>
                            <ul class="dropdown-menu right50">';
                $_outputStatus .= $_data;
                $_outputStatus .= '</ul></div>';
        return $_outputStatus;
        // '<a onclick="view_purchases('.$purchase->id.'); return false;" >' . $purchase->prefix.'-'.$purchase->code.'</a>'.'<br><span class="inline-block ' . $class . '">' . $status_name . '</span>';    
        }else
        {
        $purchase = get_table_where('tblpurchases',array('id'=>$purchase_order->id_purchases),'','row');
        $label = 'info';
        $status_name="YCMH";
        $class = 'label label-' . $label;
        return '<a  onclick="view_purchases('.$purchase->id.'); return false;" >' . $purchase->prefix.$purchase->code.'</a>'.'<br><span class="inline-block ' . $class . '">' . $status_name . '</span>';
        }
    }elseif(!empty($purchase_order->id_quotes))
    {
        $quotes = get_table_where('tblsupplier_quotes',array('id'=>$purchase_order->id_quotes),'','row');
        $label = 'warning';
        $status_name=_l('estimates');
        $class = 'label label-' . $label;
        return '<a  onclick="view_supplier_quotes('.$quotes->id.'); return false;" >' . $quotes->prefix.'-'.$quotes->code.'</a>'.'<br><span class="inline-block ' . $class . '">' . $status_name . '</span>'; 
    }else
    {
       return; 
    }
}
function get_items_import($id='')
{
        $count = 0;
        $CI =& get_instance();
        $CI->db->select('tblpurchase_order_items.*')->distinct();
        $CI->db->from('tblpurchase_order_items');
        $CI->db->where('id_purchase_order',$id);  
        $item = $CI->db->get()->result_array();
        foreach ($item as $key => $value) {
            $quantity = sum_quantity_import($value['type'],$id,$value['product_id']);
            if(empty($quantity))
            {
                $quantity = 0;
            }
            $quantity_net = $value['quantity_suppliers'] - $quantity;
            if($quantity_net > 0)
            {
                $count++;
            }
        }
        return $count;
}
function get_items_purchase_new($id='')
{
        $count = 0;
        $CI =& get_instance();
        $CI->db->select('tblpurchases_items.*')->distinct();
        $CI->db->from('tblpurchases_items');
        $CI->db->where('purchases_id',$id);  
        $item = $CI->db->get()->result_array();
        foreach ($item as $key => $value) {
            $quantity_net = $value['quantity_net'] - $value['quantity_create'] - $value['quantity_create_all'];
            if($quantity_net > 0)
            {
                $count++;
            }
        }
        return $count;
}
function sum_quantity_quotes($type='',$id='',$id_product)
{
        $CI =& get_instance();
        $CI->db->select('SUM(tblsupplier_quote_items.quantity) as quantity');
        $CI->db->from('tblsupplier_quote_items');
        $CI->db->join('tblsupplier_quotes','tblsupplier_quotes.id=tblsupplier_quote_items.id_purchase_order','left');
        $CI->db->where('tblsupplier_quotes.id_purchases',$id);
        $CI->db->where('product_id',$id_product);
        $CI->db->where('type',$type);
        return $CI->db->get()->row()->quantity;
}
function get_items_purchase_quotes($id='',$id_order)
{
        $count = 0;
        $CI =& get_instance();
        $CI->db->select('tblpurchases_items.*')->distinct();
        $CI->db->from('tblpurchases_items');
        $CI->db->where('purchases_id',$id);  
        $item = $CI->db->get()->result_array();
        foreach ($item as $key => $value) {
            $quantity = sum_quantity_quotes($value['type'],$id_order,$value['product_id']);
            if(empty($quantity))
            {
                $quantity = 0;
            }
            $quantity_net = $value['quantity_net'] - $quantity;
            if($quantity_net > 0)
            {
                $count++;
            }
        }
        return $count;
}
function get_items_purchase($id='')
{
        $count = 0;
        $CI =& get_instance();
        $CI->db->select('tblpurchases_items.*,tblitems.name as name_item,tblitems.code as code_item,tblunits.unit as unit_name')->distinct();
        $CI->db->from('tblpurchases_items');
        $CI->db->join('tblitems','tblitems.id=tblpurchases_items.product_id AND tblitems.type_items= tblpurchases_items.type','left');
        $CI->db->join('tblunits','tblunits.unitid=tblitems.unit','left');
        $CI->db->where('purchases_id',$id);  
        $item = $CI->db->get()->result_array();
        foreach ($item as $key => $value) {
            $quantity = sum_quantity($value['type'],$id,$value['product_id']);
            if(empty($quantity))
            {
                $quantity = 0;
            }
            $quantity_net = $value['quantity_net'] - $quantity;
            if($quantity_net > 0)
            {
                $count++;
            }
        }
        return $count;
}
function sum_quantity($type='',$id='',$id_product)
{
        $CI =& get_instance();
        $CI->db->select('SUM(tblpurchase_order_items.quantity) as quantity');
        $CI->db->from('tblpurchase_order_items');
        $CI->db->join('tblpurchase_order','tblpurchase_order.id=tblpurchase_order_items.id_purchase_order','left');
        $CI->db->where('tblpurchase_order.id_purchases',$id);
        $CI->db->where('product_id',$id_product);
        $CI->db->where('type',$type);
        return $CI->db->get()->row()->quantity;
}
function count_number_PO_ch($id)
{
    // $label = get_status_label_hau(6);
    $status_name = $id.' '._l('ch_po');
    // $class = 'label label-' . $label;
    if($id > 0)
    {
        // return '<span class="inline-block ' . $class . '">' . $status_name . '</span>';
        return $status_name;
    }
    else
    {
        return;
    }
}
function purchase_order_quote($code='')
{
    $label = get_status_label_hau(6);
    $status_name='Đơn hàng: '.$code;
    $class = 'label label-' . $label;
    if(!empty($code))
    {
    return '<span class="inline-block ' . $class . '">' . $status_name . '</span>';
    }
    else
    {
        return;
    }
}
function purchase_quote($code='')
{
    $label = get_status_label_hau(6);
    $status_name=$code;
    $class = 'label label-' . $label;
    if(!empty($code))
    {
    return '<span class="inline-block ' . $class . '">' . $status_name . '</span>';
    }
    else
    {
        return;
    }
}
function process_purchases_down($id='',$type = 0,$string_Row='')
{
    $data[0] = _l('ch_data_pr');
    $data[1] = _l('ch_data_rfq');
    $data[2] = _l('ch_data_quotes');
    $data[3] = _l('ch_data_order');
    $idd='';
    // if($type == 0)
    // {
    //  $process = get_table_where('tblpurchases',array('id'=>$id),'','row');  
    // }
    if($type == 1)
    {
     $process = get_table_where('tblrfq_ask_price',array('id'=>$id),'','row');
     $idd = $process->id_purchases;
    }
    if($type == 2)
    {
     $process = get_table_where('tblsupplier_quotes',array('id'=>$id),'','row');
     if(!empty($process->id_purchases))
     {
        $idd = $process->id_purchases;
     } 
     if(!empty($process->id_ask_price))
     {
        $idd = $process->id_ask_price;
     }
    }
    if($type == 3)
    {
     $process = get_table_where('tblpurchase_order',array('id'=>$id),'','row');  
    if(!empty($process->id_purchases))
     {
        $idd = $process->id_purchases;
     } 
     if(!empty($process->id_quotes))
     {
        $idd = $process->id_quotes;
     }
    }
        // var_dump($idd);die;  
    
   if(!empty($idd))
   {

    $string_Row .= '<li class="active">';
    $string_Row .= '    <a class="pointer #ff6f00"    status-procedure="1" >';
    $string_Row .=          mb_convert_case($data[$type], MB_CASE_TITLE, "UTF-8");
    $string_Row .=      '</a>';
    $string_Row .='</li>';

    $type = $type - 1;
    if($string_Row != '')
    {
        var_dump($string_Row);
     return  $string_Row;   
    }
    $string_Row = process_purchases_down($idd,$type,$string_Row);

   }else
   {

    return  $string_Row;
   }
}
function process_purchases_img($id='')
{
    $data[0] = _l('ch_data_pr');
    $data[1] = _l('ch_data_rfq');
    $data[2] = _l('ch_data_quotes');
    $data[3] = _l('ch_data_order');
    $data[4] = _l('ch_data_cancel');
        $purchases = get_table_where('tblpurchases',array('id'=>$id),'','row');
        $cance = explode('|', $purchases->history_status);
        $string_Row = '<ul class="progressbar_img" style="display: flex;flex-direction: row;justify-content: center;">';
        $string_Row .= '<li class="active_img">';
        $string_Row .= staff_profile_image($purchases->staff_create, array('staff-profile-image-small'), 'small');
        $string_Row .='</li>';  
        if(!empty($purchases->process))
        {
        $process = explode('|', $purchases->process);
        if($process[0] == 1)
        {
            $ask_price = get_table_where('tblrfq_ask_price',array('id'=>$process[1]),'','row');
            $dataRow = '<span class="inline-block label label-warning">'.$ask_price->prefix.'-'.$ask_price->code.'</span>';
            $string_Row .= '<li class="active_img">';
            $string_Row .= staff_profile_image($ask_price->staff_create, array('staff-profile-image-small'), 'small');
            $string_Row .='</li>';     
            $idquotes = get_table_where('tblsupplier_quotes',array('id_ask_price'=>$ask_price->id),'','row');
            if(!empty($idquotes))
            {
              $process = explode('|', $ask_price->process);
              if(!empty($idquotes))
                  {
                      $id_quotes= array();
                      $quotes = get_table_where('tblsupplier_quotes',array('id_ask_price'=>$ask_price->id));
                            $_data = '';
                            foreach ($quotes as $k => $v) {
                                $order = get_table_where('tblpurchase_order',array('id_quotes'=>$v['id']),'','row');
                                if(!empty($order)){
                                $id_quotes[] = $v['id'];
                                }
                            }
                      $supplier_quotes = get_table_where('tblsupplier_quotes',array('id'=>$idquotes->id),'','row');

                      $string_Row .= '<li class="active_img">';
                      $string_Row .= staff_profile_image($supplier_quotes->staff_create, array('staff-profile-image-small'), 'small');
                      $string_Row .='</li>';
                      if(!empty($id_quotes))
                      { 
                        if(!empty($id_quotes))
                        {
                            $order = get_table_where('tblpurchase_order',array('id_quotes'=>$id_quotes[0]),'','row');

                            if(!empty($order))
                            {
                            $string_Row .= '<li class="active_img">';
                            $string_Row .= staff_profile_image($order->staff_create, array('staff-profile-image-small'), 'small');
                            $string_Row .='</li>'; 
                            }else
                            {
                            $string_Row .= '<li class="active_img">';
                            $string_Row .= staff_profile_image(0, array('staff-profile-image-small'), 'small');
                            $string_Row .='</li>';     
                            }
                            
                            if($purchases->status == 4)
                            {
                            $cances=explode(',', $cance[3]);
                            if($cances[0] == '1foso')
                            {
                                $string_Row .= '<li class="cancel">';
                                $string_Row .= '<img src="'.base_url('uploads/company/'.get_option('company_logo')).'" class="staff-profile-image-small">';
                                $string_Row .='</li>';    
                            }else
                            {
                                $string_Row .= '<li class="cancel">';
                                $string_Row .= staff_profile_image($cances[0], array('staff-profile-image-small'), 'small');
                                $string_Row .='</li>';
                            }
                            }
                        }
                      }else
                      {
                        if($purchases->status == 4)
                            {
                            $string_Row .= '<li class="cancel">';
                            $string_Row .= '    <a class="pointer red"    status-procedure="1" >';
                            $string_Row .=      mb_convert_case($data[4], MB_CASE_TITLE, "UTF-8");
                            $string_Row .=      '</a>';
                            $string_Row .='</li>';
                            }else
                            {
                        $string_Row .= '<li>';
                        $string_Row .= staff_profile_image(0, array('staff-profile-image-small'), 'small');
                        $string_Row .='</li>'; 
                        }
                      }

                  }else
                  if($process[0] == 3)
                  {
                      $order = get_table_where('tblpurchase_order',array('id'=>$process[1]),'','row');
                      $string_Row .= '<li>';
                      $string_Row .= staff_profile_image($order->staff_create, array('staff-profile-image-small'), 'small');
                      $string_Row .='</li>';
                    if($purchases->status == 4)
                    {
                        if($cances[0] == '1foso')
                            {
                                $string_Row .= '<li class="cancel">';
                                $string_Row .= '<img src="'.base_url('uploads/company/'.get_option('company_logo')).'" class="staff-profile-image-small">';
                                $string_Row .='</li>';    
                            }else
                            {
                                $string_Row .= '<li class="cancel">';
                                $string_Row .= staff_profile_image($cances[0], array('staff-profile-image-small'), 'small');
                                $string_Row .='</li>';
                            }
                    } 

                  }
            }else
            {
                if($purchases->status == 4)
                {
                $cances=explode(',', $cance[3]);
                    if($cances[0] == '1foso')
                            {
                                $string_Row .= '<li class="cancel">';
                                $string_Row .= '<img src="'.base_url('uploads/company/'.get_option('company_logo')).'" class="staff-profile-image-small">';
                                $string_Row .='</li>';    
                            }else
                            {
                                $string_Row .= '<li class="cancel">';
                                $string_Row .= staff_profile_image($cances[0], array('staff-profile-image-small'), 'small');
                                $string_Row .='</li>';
                            }
                }else
                {
            $string_Row .= '<li>';
            $string_Row .= staff_profile_image(0, array('staff-profile-image-small'), 'small');
            $string_Row .='</li>';
            $string_Row .= '<li>';
            $string_Row .= staff_profile_image(0, array('staff-profile-image-small'), 'small');
            $string_Row .='</li>';
                }
            }

        }else
        if($process[0] == 2)
        {
            $supplier_quotes = get_table_where('tblsupplier_quotes',array('id'=>$process[1]),'','row');
            $quotes  = $supplier_quotes->prefix.'-'.$supplier_quotes->code;   
            $dataRow = '<a href="#" onclick="view_supplier_quotes('.$supplier_quotes->id.'); return false;" >' . purchase_quote($quotes) . '</a>';
            $string_Row .= '<li class="active_img">';
            $string_Row .= staff_profile_image($supplier_quotes->staff_create, array('staff-profile-image-small'), 'small');
            $string_Row .='</li>';
            if(!empty($supplier_quotes->process))
            { 
              $process = explode('|', $supplier_quotes->process);
              if($process[0] == 3)
              {
                                    $order = get_table_where('tblpurchase_order',array('id'=>$process[1]),'','row');
                  $string_Row .= '<li class="active_img">';
                  $string_Row .= staff_profile_image($order->staff_create, array('staff-profile-image-small'), 'small');
                  $string_Row .='</li>';
                if($purchases->status == 4)
                {
                $cances=explode(',', $cance[3]);
                    if($cances[0] == '1foso')
                            {
                                $string_Row .= '<li class="cancel">';
                                $string_Row .= '<img src="'.base_url('uploads/company/'.get_option('company_logo')).'" class="staff-profile-image-small">';
                                $string_Row .='</li>';    
                            }else
                            {
                                $string_Row .= '<li class="cancel">';
                                $string_Row .= staff_profile_image($cances[0], array('staff-profile-image-small'), 'small');
                                $string_Row .='</li>';
                            }
                }
              }
            }else
            {
                if($purchases->status == 4)
                {
                $cances=explode(',', $cance[3]);
                    if($cances[0] == '1foso')
                            {
                                $string_Row .= '<li class="cancel">';
                                $string_Row .= '<img src="'.base_url('uploads/company/'.get_option('company_logo')).'" class="staff-profile-image-small">';
                                $string_Row .='</li>';    
                            }else
                            {
                                $string_Row .= '<li class="cancel">';
                                $string_Row .= staff_profile_image($cances[0], array('staff-profile-image-small'), 'small');
                                $string_Row .='</li>';
                            }
                }else
                {
                    $string_Row .= '<li>';
                    $string_Row .= staff_profile_image(0, array('staff-profile-image-small'), 'small');
                    $string_Row .='</li>';
                }
            }

        }else 
        {

            $order = get_table_where('tblpurchase_order',array('id_purchases'=>$id),'','row');
            if(!empty($order))
            {
            $string_Row .= '<li class="active_img">';
            $string_Row .= staff_profile_image($order->staff_create, array('staff-profile-image-small'), 'small');
            $string_Row .='</li>';   
            if($purchases->status == 4)
            {
            $cances=explode(',', $cance[3]);
            if($cances[0] == '1foso')
                            {
                                $string_Row .= '<li class="cancel">';
                                $string_Row .= '<img src="'.base_url('uploads/company/'.get_option('company_logo')).'" class="staff-profile-image-small">';
                                $string_Row .='</li>';    
                            }else
                            {
                                $string_Row .= '<li class="cancel">';
                                $string_Row .= staff_profile_image($cances[0], array('staff-profile-image-small'), 'small');
                                $string_Row .='</li>';
                            }
            }    
            }else
            {
            $string_Row .= '<li>';
            $string_Row .= staff_profile_image(0, array('staff-profile-image-small'), 'small');
            $string_Row .='</li>';
            $string_Row .= '<li>';
            $string_Row .= staff_profile_image(0, array('staff-profile-image-small'), 'small');
            $string_Row .='</li>'; 
            $string_Row .= '<li>';
            $string_Row .= staff_profile_image(0, array('staff-profile-image-small'), 'small');
            $string_Row .='</li>';    
            }
        }
        }
        else
        {
            
            $order_all = get_table_where('tblpurchases',array('id'=>$id),'','row');
            $order = get_table_where('tblpurchase_order',array('id_purchases'=>$id),'','row');
                    if(!empty($order))
                    {
                    $string_Row .= '<li class="active_img">';
                    $string_Row .= staff_profile_image($order->staff_create, array('staff-profile-image-small'), 'small');
                    $string_Row .='</li>';   
                    if($purchases->status == 4)
                    {
                    $cances=explode(',', $cance[3]);
                    if($cances[0] == '1foso')
                    {
                        $string_Row .= '<li class="cancel">';
                        $string_Row .= '<img src="'.base_url('uploads/company/'.get_option('company_logo')).'" class="staff-profile-image-small">';
                        $string_Row .='</li>';    
                    }else
                    {
                        $string_Row .= '<li class="cancel">';
                        $string_Row .= staff_profile_image($cances[0], array('staff-profile-image-small'), 'small');
                        $string_Row .='</li>';
                    }
                    }    
                    }else
            if(!empty($order_all->id_order))
            {
                $string_Row .= '<li class="cancel_all">';
                $string_Row .= '<img src="'.base_url('uploads/company/'.get_option('company_logo')).'" class="staff-profile-image-small">';
                $string_Row .='</li>';
                if($purchases->status == 4)
                    {
                    $cances=explode(',', $cance[3]);
                    if($cances[0] == '1foso')
                    {
                        $string_Row .= '<li class="cancel">';
                        $string_Row .= '<img src="'.base_url('uploads/company/'.get_option('company_logo')).'" class="staff-profile-image-small">';
                        $string_Row .='</li>';    
                    }else
                    {
                        $string_Row .= '<li class="cancel">';
                        $string_Row .= staff_profile_image($cances[0], array('staff-profile-image-small'), 'small');
                        $string_Row .='</li>';
                    }
                    } 
            }else
                {
                    $string_Row .= '<li>';
                    $string_Row .= staff_profile_image(0, array('staff-profile-image-small'), 'small');
                    $string_Row .='</li>';
                    $string_Row .= '<li>';
                    $string_Row .= staff_profile_image(0, array('staff-profile-image-small'), 'small');
                    $string_Row .='</li>'; 
                    $string_Row .= '<li>';
                    $string_Row .= staff_profile_image(0, array('staff-profile-image-small'), 'small');
                    $string_Row .='</li>';    
                }
            

        }
    $string_Row.='<div class="clearfix"></div></ul>';
    return $string_Row;
}
function process_purchases($id='')
{

    $data[0] = _l('ch_data_pr');
    $data[1] = _l('ch_data_rfq');
    $data[2] = _l('ch_data_quotes');
    $data[3] = _l('ch_data_order');
    $data[4] = _l('ch_data_cancel');
        $string_Row = '<ul class="progressbar" style="display: flex;flex-direction: row;justify-content: center;">';
        $string_Row .= '<li class="active">';
        $string_Row .= '    <a class="pointer #ff6f00"    status-procedure="1" >';
        $string_Row .=          mb_convert_case($data[0], MB_CASE_TITLE, "UTF-8");
        $string_Row .=      '</a>';
        $string_Row .='</li>';  
        $purchases = get_table_where('tblpurchases',array('id'=>$id),'','row');
        if(!empty($purchases->process))
        {
        $process = explode('|', $purchases->process);
        if($process[0] == 1)
        {
            $ask_price = get_table_where('tblrfq_ask_price',array('id'=>$process[1]),'','row');
            $dataRow = '<a onclick="rdq_modal('.$ask_price->id_purchases.')"><span class="inline-block label label-warning">'.$ask_price->prefix.'-'.$ask_price->code.'</span></a>';
            $string_Row .= '<li class="active">';
            $string_Row .= '    <a class="pointer #ff6f00"    status-procedure="1" >';
            $string_Row .=      mb_convert_case($data[$process[0]], MB_CASE_TITLE, "UTF-8");
            $string_Row .=      '</a><br><br>'.$dataRow;
            $string_Row .= '</li>';   
            $idquotes = get_table_where('tblsupplier_quotes',array('id_ask_price'=>$ask_price->id),'','row');
            if(!empty($idquotes))
            {
              $process = explode('|', $ask_price->process);
              if(!empty($idquotes))
                  {
                      $id_quotes= array();
                      $supplier_quotes = get_table_where('tblsupplier_quotes',array('id'=>$idquotes->id),'','row');
                      $quotes = get_table_where('tblsupplier_quotes',array('id_ask_price'=>$ask_price->id));
                                    $count = count($quotes);
                                    $_data = '';
                                    foreach ($quotes as $k => $v) {
                                        $order = get_table_where('tblpurchase_order',array('id_quotes'=>$v['id']),'','row');
                                            if(!empty($order)){
                                            $id_quotes[] = $v['id'];
                                            }
                                        $_data.= '<li class="hoang"><a onclick="view_supplier_quotes('.$v['id'].'); return false;" >' . $v['prefix'].'-'.$v['code'] . '</a></li>';
                                    }
                                    $_outputStatus = '<div class="dropdown" style="text-align: center;">
                                                <button class="dropdown-toggle no_background color_warning" type="button" data-toggle="dropdown">'.$count.' '._l('ch_quote_count').'
                                                </button>
                                                <ul style="top:unset;bottom:100%;left:unset;right: 12%" class="dropdown-menu ch_foso">';
                                    $_outputStatus .= $_data;
                                    $_outputStatus .= '</ul></div>';



                      $string_Row .= '<li class="active">';
                      $string_Row .= '    <a class="pointer #ff6f00"    status-procedure="2" >';
                      $string_Row .=      mb_convert_case($data[$process[0]], MB_CASE_TITLE, "UTF-8");
                      $string_Row .=      '</a><br><br>'.$_outputStatus;
                      $string_Row .='</li>';
                      if(!empty($id_quotes))
                      { 
                        if(!empty($id_quotes))
                        {
                            // hauhauhau
                            $count=0;
                            $_data = '';
                            foreach ($id_quotes as $keyorder => $valueorder) {
                                    $purchase_order = get_table_where('tblpurchase_order',array('id_quotes'=>$valueorder));
                                    $count+= count($purchase_order);
                                    foreach ($purchase_order as $k => $v) {
                                        $_data.= '<li class="hoang"><a onclick="view_purchase_order('.$v['id'].'); return false;" >' . $v['prefix'].'-'.$v['code'] . '</a></li>';
                                    }
                            }
                                    $_outputStatus = '<div class="dropdown" style="text-align: center;">
                                                <button class="dropdown-toggle no_background color_warning" type="button" data-toggle="dropdown">'.count_number_PO_ch($count).'
                                                </button>
                                                <ul style="top:unset;bottom:100%;left:unset;right: 12%" class="dropdown-menu ch_foso">';
                                    $_outputStatus .= $_data;
                                    $_outputStatus .= '</ul></div>';
                            $string_Row .= '<li class="active">';
                            $string_Row .= '    <a class="pointer #ff6f00"    status-procedure="3" >';
                            $string_Row .=      mb_convert_case($data[3], MB_CASE_TITLE, "UTF-8");
                            $string_Row .=      '</a><br><br>'.$_outputStatus;
                            $string_Row .='</li>'; 
                            if($purchases->status == 4)
                            {
                            $string_Row .= '<li class="cancel">';
                            $string_Row .= '    <a class="pointer red"    status-procedure="1" >';
                            $string_Row .=      mb_convert_case($data[4], MB_CASE_TITLE, "UTF-8");
                            $string_Row .=      '</a>';
                            $string_Row .='</li>';
                            }
                        }
                      }else
                      {
                        if($purchases->status == 4)
                            {
                            $string_Row .= '<li class="cancel">';
                            $string_Row .= '    <a class="pointer red"    status-procedure="1" >';
                            $string_Row .=      mb_convert_case($data[4], MB_CASE_TITLE, "UTF-8");
                            $string_Row .=      '</a>';
                            $string_Row .='</li>';
                            }else
                            {
                       $string_Row .= '<li class="">';
                        $string_Row .= '    <a class="pointer #ff6f00"    status-procedure="3" >';
                        $string_Row .=      mb_convert_case($data[3], MB_CASE_TITLE, "UTF-8");
                        $string_Row .=      '</a>';
                        $string_Row .='</li>'; 
                        }
                      }

                  }else
                  if($process[0] == 3)
                  {
                      $string_Row .= '<li class="active">';
                      $string_Row .= '    <a class="pointer #ff6f00"    status-procedure="3" >';
                      $string_Row .=      mb_convert_case($data[$process[0]], MB_CASE_TITLE, "UTF-8");
                      $string_Row .=      '</a>';
                      $string_Row .='</li>';  
                if($purchases->status == 4)
                {
                $string_Row .= '<li class="cancel">';
                $string_Row .= '    <a class="pointer red"    status-procedure="1" >';
                $string_Row .=      mb_convert_case($data[4], MB_CASE_TITLE, "UTF-8");
                $string_Row .=      '</a>';
                $string_Row .='</li>';
                } 

                  }
            }else
            {
                if($purchases->status == 4)
                {
                $string_Row .= '<li class="cancel">';
                $string_Row .= '    <a class="pointer red"    status-procedure="1" >';
                $string_Row .=      mb_convert_case($data[4], MB_CASE_TITLE, "UTF-8");
                $string_Row .=      '</a>';
                $string_Row .='</li>';
                }else
                {
            $string_Row .= '<li class="">';
            $string_Row .= '    <a class="pointer #ff6f00"    status-procedure="2" >';
            $string_Row .=      mb_convert_case($data[2], MB_CASE_TITLE, "UTF-8");
            $string_Row .=      '</a>';
            $string_Row .='</li>'; 
            $string_Row .= '<li class="">';
            $string_Row .= '    <a class="pointer #ff6f00"    status-procedure="3" >';
            $string_Row .=      mb_convert_case($data[3], MB_CASE_TITLE, "UTF-8");
            $string_Row .=      '</a>';
            $string_Row .='</li>';
                }
            }

        }else
        if($process[0] == 2)
        {
            $supplier_quotes = get_table_where('tblsupplier_quotes',array('id'=>$process[1]),'','row');
            $quotes  = $supplier_quotes->prefix.'-'.$supplier_quotes->code;   
            $dataRow = '<a href="#" onclick="view_supplier_quotes('.$supplier_quotes->id.'); return false;" >' . purchase_quote($quotes) . '</a>';
            $string_Row .= '<li class="active">';
            $string_Row .= '    <a class="pointer #ff6f00"    status-procedure="2" >';
            $string_Row .=      mb_convert_case($data[$process[0]], MB_CASE_TITLE, "UTF-8");
            $string_Row .=      '</a><br><br>'.$dataRow;
            $string_Row .='</li>';
            if(!empty($supplier_quotes->process))
            { 
              $process = explode('|', $supplier_quotes->process);
              if($process[0] == 3)
              {
                                    $order = get_table_where('tblpurchase_order',array('id'=>$process[1]),'','row');
                                    $purchase_order = get_table_where('tblpurchase_order',array('id_quotes'=>$order->id_quotes));
                                    $count = count($purchase_order);
                                    $_data = '';
                                    foreach ($purchase_order as $k => $v) {
                                        $_data.= '<li class="hoang"><a onclick="view_purchase_order('.$v['id'].'); return false;" >' . $v['prefix'].'-'.$v['code'] . '</a></li>';
                                    }
                                    $_outputStatus = '<div class="dropdown" style="text-align: center;">
                                                <button class="dropdown-toggle no_background color_warning" type="button" data-toggle="dropdown">'.count_number_PO_ch($count).'
                                                </button>
                                                <ul style="top:unset;bottom:100%;left:unset;right: 12%" class="dropdown-menu ch_foso">';
                                    $_outputStatus .= $_data;
                                    $_outputStatus .= '</ul></div>';
                  $string_Row .= '<li class="active">';
                  $string_Row .= '    <a class="pointer #ff6f00"    status-procedure="3" >';
                  $string_Row .=      mb_convert_case($data[$process[0]], MB_CASE_TITLE, "UTF-8");
                  $string_Row .=      '</a><br><br>'.$_outputStatus;
                  $string_Row .='</li>'; 
                if($purchases->status == 4)
                {
                $string_Row .= '<li class="cancel">';
                $string_Row .= '    <a class="pointer red"    status-procedure="1" >';
                $string_Row .=      mb_convert_case($data[4], MB_CASE_TITLE, "UTF-8");
                $string_Row .=      '</a>';
                $string_Row .='</li>';
                }
              }
            }else
            {
                if($purchases->status == 4)
                {
                $string_Row .= '<li class="cancel">';
                $string_Row .= '    <a class="pointer red"    status-procedure="1" >';
                $string_Row .=      mb_convert_case($data[4], MB_CASE_TITLE, "UTF-8");
                $string_Row .=      '</a>';
                $string_Row .='</li>';
                }else
                {
                    $string_Row .= '<li class="">';
                    $string_Row .= '    <a class="pointer #ff6f00"    status-procedure="3" >';
                    $string_Row .=      mb_convert_case($data[3], MB_CASE_TITLE, "UTF-8");
                    $string_Row .=      '</a>';
                    $string_Row .='</li>';
                }
            }

        }else
        {
            $order = get_table_where('tblpurchase_order',array('id_purchases'=>$id),'','row');
            if(!empty($order)){
            $purchase_order = get_table_where('tblpurchase_order',array('id_purchases'=>$order->id_purchases));
            
            $count = count($purchase_order);
            $_data = '';
            foreach ($purchase_order as $k => $v) {
                $_data.= '<li class="hoang"><a onclick="view_purchase_order('.$v['id'].'); return false;" >' . $v['prefix'].'-'.$v['code'] . '</a></li>';
            }

            $order_all = get_table_where('tblpurchases',array('id'=>$id),'','row');
            
            if(!empty($order_all->id_order))
            {
            $orders = get_table_where('tblpurchase_order',array('id'=>$order_all->id_order),'','row');   
            $_data.= '<li class="hoang"><a onclick="view_purchase_order('.$orders->id.'); return false;" >' . $orders->prefix.'-'.$orders->code . '</a></li>'; 
            $count=$count+1;
            }
            $_outputStatus = '<div class="dropdown" style="text-align: center;">
                        <button class="dropdown-toggle no_background color_warning" type="button" data-toggle="dropdown">'.count_number_PO_ch($count).'
                        </button>
                        <ul style="top:unset;bottom:100%;left:unset;right: 12%" class="dropdown-menu ch_foso" >';
            $_outputStatus .= $_data;
            $_outputStatus .= '</ul></div>';
                                    
            $string_Row .= '<li class="active">';
            $string_Row .= '    <a class="pointer #ff6f00"    status-procedure="3" >';
            $string_Row .=      mb_convert_case($data[3], MB_CASE_TITLE, "UTF-8");
            $string_Row .=      '</a><br><br>'.$_outputStatus;
            $string_Row .='</li>';   
            if($purchases->status == 4)
            {
            $string_Row .= '<li class="cancel">';
            $string_Row .= '    <a class="pointer red"    status-procedure="1" >';
            $string_Row .=      mb_convert_case($data[4], MB_CASE_TITLE, "UTF-8");
            $string_Row .=      '</a>';
            $string_Row .='</li>';
            }
            }else
            {   
                $string_Row .= '<li class="">';
                $string_Row .= '    <a class="pointer #ff6f00"    status-procedure="1" >';
                $string_Row .=      mb_convert_case($data[1], MB_CASE_TITLE, "UTF-8");
                $string_Row .=      '</a>';
                $string_Row .='</li>';
                $string_Row .= '<li class="">';
                $string_Row .= '    <a class="pointer #ff6f00"    status-procedure="2" >';
                $string_Row .=      mb_convert_case($data[2], MB_CASE_TITLE, "UTF-8");
                $string_Row .=      '</a>';
                $string_Row .='</li>'; 
                $string_Row .= '<li class="">';
                $string_Row .= '    <a class="pointer #ff6f00"    status-procedure="3" >';
                $string_Row .=      mb_convert_case($data[3], MB_CASE_TITLE, "UTF-8");
                $string_Row .=      '</a>';
                $string_Row .='</li>';   
            }
        }
        }
        else
        {
            // hau
            
            $order = get_table_where('tblpurchase_order',array('id_purchases'=>$id),'','row');
            if(!empty($order)){
            $purchase_order = get_table_where('tblpurchase_order',array('id_purchases'=>$order->id_purchases));
            
            $count = count($purchase_order);
            $_data = '';
            foreach ($purchase_order as $k => $v) {
                $_data.= '<li class="hoang"><a onclick="view_purchase_order('.$v['id'].'); return false;" >' . $v['prefix'].'-'.$v['code'] . '</a></li>';
            }

            $order_all = get_table_where('tblpurchases',array('id'=>$id),'','row');
            
            if(!empty($order_all->id_order))
            {
            $orders = get_table_where('tblpurchase_order',array('id'=>$order_all->id_order),'','row');   
            $_data.= '<li class="hoang"><a onclick="view_purchase_order('.$orders->id.'); return false;" >' . $orders->prefix.'-'.$orders->code . '</a></li>'; 
            $count=$count+1;
            }
            $_outputStatus = '<div class="dropdown" style="text-align: center;">
                        <button class="dropdown-toggle no_background color_warning" type="button" data-toggle="dropdown">'.count_number_PO_ch($count).'
                        </button>
                        <ul style="top:unset;bottom:100%;left:unset;right: 12%" class="dropdown-menu ch_foso" >';
            $_outputStatus .= $_data;
            $_outputStatus .= '</ul></div>';
                                    
            $string_Row .= '<li class="active">';
            $string_Row .= '    <a class="pointer #ff6f00"    status-procedure="3" >';
            $string_Row .=      mb_convert_case($data[3], MB_CASE_TITLE, "UTF-8");
            $string_Row .=      '</a><br><br>'.$_outputStatus;
            $string_Row .='</li>';   
            if($purchases->status == 4)
            {
            $string_Row .= '<li class="cancel">';
            $string_Row .= '    <a class="pointer red"    status-procedure="1" >';
            $string_Row .=      mb_convert_case($data[4], MB_CASE_TITLE, "UTF-8");
            $string_Row .=      '</a>';
            $string_Row .='</li>';
            }
            }elseif(!empty($order_all->id_order))
            {
                $_data='';
                $count1=0;
                $orders = get_table_where('tblpurchase_order',array('id'=>$order_all->id_order),'','row');   
                $_data.= '<li class="hoang"><a onclick="view_purchase_order('.$orders->id.'); return false;" >' . $orders->prefix.'-'.$orders->code . '</a></li>'; 
                $order = get_table_where('tblpurchase_order',array('id_purchases'=>$id),'','row');
                if(!empty($order)){
                $purchase_order = get_table_where('tblpurchase_order',array('id_purchases'=>$order->id_purchases));
                $count1 = count($purchase_order);
                $_data = '';
                foreach ($purchase_order as $k => $v) {
                    $_data.= '<li class="hoang"><a onclick="view_purchase_order('.$v['id'].'); return false;" >' . $v['prefix'].'-'.$v['code'] . '</a></li>';
                }
                }
                $count=1+$count1;
                $_outputStatus = '<div class="dropdown" style="text-align: center;">
                            <button class="dropdown-toggle no_background color_warning" type="button" data-toggle="dropdown">'.count_number_PO_ch($count).'
                            </button>
                            <ul style="top:unset;bottom:100%;left:unset;right: 12%" class="dropdown-menu ch_foso" >';
                $_outputStatus .= $_data;
                $_outputStatus .= '</ul></div>';
                                        
                $string_Row .= '<li class="active">';
                $string_Row .= '    <a class="pointer #ff6f00"    status-procedure="3" >';
                $string_Row .=      mb_convert_case($data[3], MB_CASE_TITLE, "UTF-8");
                $string_Row .=      '</a><br><br>'.$_outputStatus;
                $string_Row .='</li>';   
                if($purchases->status == 4)
                {
                $string_Row .= '<li class="cancel">';
                $string_Row .= '    <a class="pointer red"    status-procedure="1" >';
                $string_Row .=      mb_convert_case($data[4], MB_CASE_TITLE, "UTF-8");
                $string_Row .=      '</a>';
                $string_Row .='</li>';
                }  
            } 
            else
            {   
                $string_Row .= '<li class="">';
                $string_Row .= '    <a class="pointer #ff6f00"    status-procedure="1" >';
                $string_Row .=      mb_convert_case($data[1], MB_CASE_TITLE, "UTF-8");
                $string_Row .=      '</a>';
                $string_Row .='</li>';
                $string_Row .= '<li class="">';
                $string_Row .= '    <a class="pointer #ff6f00"    status-procedure="2" >';
                $string_Row .=      mb_convert_case($data[2], MB_CASE_TITLE, "UTF-8");
                $string_Row .=      '</a>';
                $string_Row .='</li>'; 
                $string_Row .= '<li class="">';
                $string_Row .= '    <a class="pointer #ff6f00"    status-procedure="3" >';
                $string_Row .=      mb_convert_case($data[3], MB_CASE_TITLE, "UTF-8");
                $string_Row .=      '</a>';
                $string_Row .='</li>';   
            }

        }
    $string_Row.='<div class="clearfix"></div></ul>';
    return $string_Row;
}
function render_hau_suppliert($id='',$id_suppliert='')
{
    $info_detail = get_table_where('tblsuppliers_info_detail',array('id_suppliers_info'=>$id));
    foreach ($info_detail as $key => $value) {
                    if(!empty($value['is_required']))
                            {
                                $_input_attrs['data-custom-field-required'] = true;
                                $required = 'data-custom-field-required = "1"';
                            }else
                            {
                                $required ='';
                                $_input_attrs = array();
                            }
                            if($value['type_form'] == 'input' || $value['type_form'] == 'password')
                            {
                                $valueData = get_table_where('tblsuppliers_value',array('id_suppliert'=>$id_suppliert,'id_detail'=>$value['id']),'','row');

                                $_valueData = !empty($valueData->value) ? $valueData->value : '';
                                echo render_input('info_detail['.$value['id'].']', $value['name'], $_valueData, $value['type_form'],$_input_attrs);
                            }
                            else if($value['type_form'] == 'radio')
                            {
                                $detail = get_table_where('tblsuppliers_info_detail_value',array('id_info_detail'=>$value['id']));
                                $valueData = get_table_where('tblsuppliers_value',array('id_suppliert'=>$id_suppliert,'id_detail'=>$value['id']),'','row');
                                $_valueData = !empty($valueData->value) ? $valueData->value : '';
                                echo '<label class="control-label">'.$value['name'].'</label>';
                                echo '<div class="clearfix"></div>';
                                foreach($detail as $kVal => $vVal)
                                {
                                    echo "<div class='col-md-6'>";
                                    echo '    <div class="radio">';
                                    echo '        <input '.$required.' type="radio" id="info_detail['.$value['id'].']['.$kVal.']" name="info_detail['.$value['id'].']" value="'.$vVal['id'].'" '.(($_valueData == $vVal['id']) ? "checked" : "").'>';
                                    echo '        <label for="info_detail['.$value['id'].']['.$kVal.']">'.$vVal['name'].'</label>';
                                    echo '    </div>';
                                    echo "</div>";
                                }
                            }
                            else if($value['type_form'] == 'checkbox')
                            {
                                $_valueData=array();
                                $detail = get_table_where('tblsuppliers_info_detail_value',array('id_info_detail'=>$value['id']));
                                $valueData = get_table_where('tblsuppliers_value',array('id_suppliert'=>$id_suppliert,'id_detail'=>$value['id']));
                                foreach ($valueData as $k => $v) {
                                   $_valueData[] = $v['value'];
                                }
                                echo '<label class="control-label">'.$value['name'].'</label>';
                                echo '<div class="clearfix"></div>';
                                foreach($detail as $kVal => $vVal)
                                {
                                    $checked = "";
                                    if(!empty($_valueData))
                                    {
                                        foreach($_valueData as $Kv => $Vv)
                                        {
                                            if($vVal['id'] == $Vv){
                                                $checked = "checked";
                                            }
                                        }
                                    }
                                    echo "<div class='col-md-6'>";
                                    echo '    <div class="checkbox">';
                                    echo '        <input '.$required.' type="checkbox" id="info_detail['.$value['id'].']['.$kVal.']" name="info_detail['.$value['id'].'][]" value="'.$vVal['id'].'" '.$checked.'>';
                                    echo '        <label for="info_detail['.$value['id'].']['.$kVal.']">'.$vVal['name'].'</label>';
                                    echo '    </div>';
                                    echo "</div>";
                                }
                            }
                            else if($value['type_form'] == 'select')
                            {
                                $detail = get_table_where('tblsuppliers_info_detail_value',array('id_info_detail'=>$value['id']));
                                $valueData = get_table_where('tblsuppliers_value',array('id_suppliert'=>$id_suppliert,'id_detail'=>$value['id']),'','row');

                                $_valueData = !empty($valueData->value) ? $valueData->value : '';
                                echo render_select('info_detail['.$value['id'].']', $detail, array('id', 'name'), $value['name'], $_valueData,$_input_attrs);
                            }
                            else if($value['type_form'] == 'select multiple')
                            {
                                $detail = get_table_where('tblsuppliers_info_detail_value',array('id_info_detail'=>$value['id']));
                                $_valueData=array();
                                $valueData = get_table_where('tblsuppliers_value',array('id_suppliert'=>$id_suppliert,'id_detail'=>$value['id']));
                                foreach ($valueData as $k => $v) {
                                   $_valueData[] = $v['value'];
                                }
                                echo render_select('info_detail['.$value['id'].'][]', $detail, array('id', 'name'), $value['name'], $_valueData, array('multiple' => true),$_input_attrs);
                            }
                            else if($value['type_form'] == 'date')
                            {
                                $valueData = get_table_where('tblsuppliers_value',array('id_suppliert'=>$id_suppliert,'id_detail'=>$value['id']),'','row');

                                $_valueData = !empty($valueData->value) ? _d($valueData->value) : '';
                                echo render_date_input('info_detail['.$value['id'].'][date]', $value['name'], $_valueData ,$_input_attrs);
                            }
                            else if($value['type_form'] == 'datetime')
                            {
                                $valueData = get_table_where('tblsuppliers_value',array('id_suppliert'=>$id_suppliert,'id_detail'=>$value['id']),'','row');
                                $_valueData = !empty($valueData->value) ? _dt($valueData->value) : '';
                                echo render_datetime_input('info_detail['.$value['id'].'][datetime]', $value['name'], $_valueData,$_input_attrs);
                            }
    }

    // echo '<script>

    //     is_required_lead = is_required_lead.concat('.(!empty($is_required_lead) ? json_encode($is_required_lead) : "{}").');</script>';
}
function get_value_info_suppliers($id_suppliert='',$id='',$type='')
{
    $text = '';
    $detail=array();
    if(($type == 'select multiple') || ($type == 'select'))
    {
        
    $detail = get_table_where('tblsuppliers_value',array('id_detail'=>$id,'id_suppliert'=>$id_suppliert)); 
    foreach ($detail as $k => $v) {
            $value = get_table_where('tblsuppliers_info_detail_value',array('id_info_detail'=>$id,'id'=>$v['value']),'','row');
            if(!empty($value))
            {
               $text.=$value->name.','; 
            }
       }   
    $text = trim($text,',');   
    }
    if(($type == 'checkbox') || ($type == 'radio'))
    {
        
    $detail = get_table_where('tblsuppliers_value',array('id_detail'=>$id,'id_suppliert'=>$id_suppliert)); 
    foreach ($detail as $k => $v) {
            $value = get_table_where('tblsuppliers_info_detail_value',array('id_info_detail'=>$id,'id'=>$v['value']),'','row');
            if(!empty($value))
            {
               $text.=','.$value->name; 
            }
       }   
    $text = trim($text,',');   
    }  
    if(($type == 'input'))
    {
        
    $detail = get_table_where('tblsuppliers_value',array('id_detail'=>$id,'id_suppliert'=>$id_suppliert),'','row'); 
        if(!empty($detail))
        {
            $text = $detail->value;   
        } 
    } 
    if(($type == 'date'))
    {
        
    $detail = get_table_where('tblsuppliers_value',array('id_detail'=>$id,'id_suppliert'=>$id_suppliert),'','row'); 
        if(!empty($detail))
        {
            $text = _d($detail->value);   
        } 
    }
    if(($type == 'datetime'))
    {
        
    $detail = get_table_where('tblsuppliers_value',array('id_detail'=>$id,'id_suppliert'=>$id_suppliert),'','row'); 
        if(!empty($detail))
        {
            $text = _dt($detail->value);   
        } 
    }
    if(($type == 'password'))
    {
        
    $detail = get_table_where('tblsuppliers_value',array('id_detail'=>$id,'id_suppliert'=>$id_suppliert),'','row'); 
        if(!empty($detail))
        {
            $text = 'Ẩn';   
        } 
    }
    return $text;
}
function ch_make_cmp(array $sortValues)
{
    return function ($a, $b) use (&$sortValues) {
        foreach ($sortValues as $column => $sortDir) {
            $diff = strcmp($a[$column], $b[$column]);
            if ($diff !== 0) {
                if ('asc' === $sortDir) {
                    return $diff;
                }
                return $diff * -1;
            }
        }
        return 0;
    };
}
    function exsit_costs($id='')
    {
        $CI =& get_instance();
        $CI->db->where('id_costs',$id);    
        $other_payslips = $CI->db->get('tblother_payslips')->row();

        $CI->db->where('id_costs',$id);
        $pay_slip = $CI->db->get('tblpay_slip')->row();
        if(!empty($other_payslips) || !empty($pay_slip))
        {
            return true;
        }else
        {
            return false;
        }
    }
function get_costs($data='')
{
    $html='';
    foreach ($data as $key => $value) {

        $html.='<tr class="treegrid-'.$value['id'].'">
                <td><h5 style="display: inline-block;">'.($key + 1).'</h5></td>
                <td><h5 style="display: inline-block;">'.$value['code'].'</h5></td>
                <td><h5 style="display: inline-block;">'.$value['name'].'</h5></td>
                <td>Cấp 1</td>';
                if($value['id'] > 0)
            {

            $html.='<td>'.icon_btn('#' , 'pencil', 'btn-default',array('onclick'=>"edit_costs(".$value['id'].",'".$value['code']."','".$value['name']."'); return false;"));
            $ktr = get_table_where('tblcosts',array('costs_parent'=>$value['id']),'','row');
                if(empty($ktr)&&!exsit_costs($value['id']))
                {
                    $html.='<a onclick="delete_category('.$value['id'].')" class="btn btn-danger  btn-icon " data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-remove"></i>
                                </a>
                        </td>';
                }
            }else{
            $html.='<td></td>';   
            }   
            $html.='</tr>';
        $_data =get_table_where('tblcosts',array('costs_parent'=>$value['id']));
        if($_data)
        {
        $html=costs($_data,$html,$value['id'],2);
        }else
        {
            continue;
        }
    }
    echo $html;
}
function costs($data='',$html,$parent,$level)
{
    foreach ($data as $key => $value) {
        $html.='<tr class="treegrid-'.$value['id'].' treegrid-parent-'.$parent.'">
                <td><h5 style="display: inline-block;">'.($key + 1).'</h5></td>
                <td><h5 style="display: inline-block;">'.$value['code'].'</h5></td>
                <td><h5 style="display: inline-block;">'.$value['name'].'</h5></td>
                <td>Cấp '.$level.'</td>';
            if($value['id'] > 0)
            {
            $html.='<td>'.icon_btn('#' , 'pencil', 'btn-default',array('onclick'=>"edit_costs(".$value['id'].",'".$value['code']."','".$value['name']."',".$parent."); return false;"));
                $ktr = get_table_where('tblcosts',array('costs_parent'=>$value['id']),'','row');
                if(empty($ktr)&&!exsit_costs($value['id'])  )
                {
                $html.='<a onclick="delete_category('.$value['id'].')" class="btn btn-danger  btn-icon " data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-remove"></i>
                            </a>
                    </td>';
                }
            }else{
            $html.='<td></td>';   
            }   
            $html.='</tr>';
        $_data =get_table_where('tblcosts',array('costs_parent'=>$value['id']));
        if($_data)
        {
        $html=costs($_data,$html,$value['id'],($level + 1));
        }else
        {
           continue;
        }
    }
     return $html;
}
function test_quantity_tranfer($id)
{
    $CI =& get_instance();
        $tranfer = get_table_where('tbltransfer_warehouse',array('id'=>$id),'','row');
        $items = get_table_where('tbltransfer_warehouse_detail',array('id_transfer'=>$id));
        $CI->db->select('count(*) as count');
        foreach ($items as $key => $v) {
            $CI->db->or_group_start();
            $CI->db->where('id_items',$v['id_items']);
            $CI->db->where('type_items',$v['type']);
            $CI->db->where('localtion',$v['localtion_id']);
            $CI->db->where('product_quantity >=',$v['quantity_net']);
            $CI->db->group_end();
        }
        $CI->db->where('warehouse_id',$tranfer->warehouse_id);
        $result = $CI->db->get('tblwarehouse_items')->row();
        if($result->count == count($items))
        {
            $data = true;
        }else
        {
            $data = false;
        }
        return $data;
   
}
function process_payment_order_img($id='')
{
    $string_Row = '<ul class="progressbar_img" style="display: flex;flex-direction: row;justify-content: center;">';
    $ktr = get_table_where('tblpayment_order',array('id'=>$id),'','row');
    $procedure = get_table_where('tblprocedure_client_detail',array('id_detail'=>6));
    foreach ($procedure as $key => $value) {
    
        if($key == 0)
        {
                $string_Row .= '<li class="active_img">';
                $string_Row .= staff_profile_image($ktr->staff_id, array('staff-profile-image-small'), 'small');
                $string_Row .='</li>';  
        }elseif($key == 1)
        {
            if(!empty($ktr->client))
            {
                    $string_Row .= '<li class="active_img">';
                    $string_Row .= staff_profile_image($ktr->staff_client, array('staff-profile-image-small'), 'small');
                    $string_Row .='</li>'; 
            }else
            {       
                    $string_Row .= '<li class="">';
                    $string_Row .= staff_profile_image(0, array('staff-profile-image-small'), 'small');
                    $string_Row .='</li>'; 
            }
        }elseif($key == 2)
        {
            if(!empty($ktr->id_order))
            {   
                    $order = get_table_where('tblorders',array('id'=>$ktr->id_order),'','row');
                    $string_Row .= '<li class="active_img">';
                    $string_Row .= staff_profile_image($order->create_by, array('staff-profile-image-small'), 'small');
                    $string_Row .='</li>';
            }else
            {
                    $string_Row .= '<li class="">';
                    $string_Row .= staff_profile_image(0, array('staff-profile-image-small'), 'small');
                    $string_Row .='</li>';    
            }
        }elseif($key == 3)
        {
            if(!empty($ktr->id_order))
            {   
                    $order = get_table_where('tblorders',array('id'=>$ktr->id_order),'','row');
                    $string_Row .= '<li class="active_img">';
                    $string_Row .= staff_profile_image($order->create_by, array('staff-profile-image-small'), 'small');
                    $string_Row .='</li>';
            }else
            {
                    $string_Row .= '<li class="">';
                    $string_Row .= staff_profile_image(0, array('staff-profile-image-small'), 'small');
                    $string_Row .='</li>';    
            }
        }elseif($key == 4)
        {
            if(!empty($ktr->status_cancel))
            {   
                    $string_Row .= '<li class="active_img">';
                    $string_Row .= staff_profile_image($ktr->staff_cancel, array('staff-profile-image-small'), 'small');
                    $string_Row .='</li>';
            }else
            {
                    $string_Row .= '<li class="">';
                    $string_Row .= staff_profile_image(0, array('staff-profile-image-small'), 'small');
                    $string_Row .='</li>';    
            }
        }
    }
    $string_Row.='<div class="clearfix"></div></ul>';
    return $string_Row;
}
function process_payment_order($id='',$date='',$date_create='')
{
    $string_Row = '<ul class="progressbar" style="display: flex;flex-direction: row;justify-content: center;">';
    $ktr = get_table_where('tblpayment_order',array('id'=>$id),'','row');
    $procedure = get_table_where('tblprocedure_client_detail',array('id_detail'=>6));
    foreach ($procedure as $key => $value) {
    
        if($key == 0)
        {
    $days = strtotime(date("Y-m-d H:i:s", strtotime($date)) . " +".$value['leadtime']." day");
    $days = date('Y-m-d', $days);
    $dayss = (strtotime($date_create) - strtotime($date)) / (60 * 60 * 24);
    $string_Row .= '<li class="active">';
    $string_Row .= '    <p class="pointer #ff6f00"  style="font-size:10px"  status-procedure="1" >';
    $string_Row .=          mb_convert_case($value['name'], MB_CASE_TITLE, "UTF-8").'<br>NTH('._dhau($date_create).')<br>NDK('._d($days).')<br>'.abs(round($dayss)).' '._l('estimate_dt_table_heading_date');
    $string_Row .=      '</p>';
    $string_Row .='</li>';    
        }elseif($key == 1)
        {
    $days = strtotime(date("Y-m-d H:i:s", strtotime($date)) . " +".$value['leadtime']." day");
    $days = date('Y-m-d', $days);
    $dates ='';
    $active1='';
    $Leadtime = '';
    if(!empty($ktr->client))
    {
    $dates = '<br>NTH('._dhau($ktr->date_client).')';
    $dayss = (strtotime($ktr->date_client) - strtotime($date_create)) / (60 * 60 * 24);
    $active1='active';
    $Leadtime = '<br>'.abs(round($dayss)).' '._l('estimate_dt_table_heading_date');
    }
    $string_Row .= '<li class="'.$active1.'">';
    $string_Row .= '    <p class="pointer #ff6f00" style="font-size:10px"   status-procedure="1" >';
    $string_Row .=          mb_convert_case($value['name'], MB_CASE_TITLE, "UTF-8").$dates.'<br>NDK('._d($days).')'.$Leadtime;
    $string_Row .=      '</p>';
    $string_Row .='</li>'; 
        }elseif($key == 2)
        {
    $days = strtotime(date("Y-m-d H:i:s", strtotime($date)) . " +".$value['leadtime']." day");
    $days = date('Y-m-d', $days);
    $dates ='';
    $active1='';
    $Leadtime = '';
    if(!empty($ktr->id_order))
    {
    $order = get_table_where('tblorders',array('id'=>$ktr->id_order),'','row');
    $dates = '<br>NTH('._d($order->date).')';
    $dayss = (strtotime($order->date) - strtotime($ktr->date_client)) / (60 * 60 * 24);
    $active1='active';
    $Leadtime = '<br>'.abs(round($dayss)).' '._l('estimate_dt_table_heading_date');
    }
    $string_Row .= '<li class="'.$active1.'">';
    $string_Row .= '    <p class="pointer #ff6f00"  style="font-size:10px"  status-procedure="1" >';
    $string_Row .=          mb_convert_case($value['name'], MB_CASE_TITLE, "UTF-8").$dates.'<br>NDK('._d($days).')'.$Leadtime;
    $string_Row .=      '</p>';
    $string_Row .='</li>'; 
        }elseif($key == 3)
        {
    $days = strtotime(date("Y-m-d H:i:s", strtotime($date)) . " +".$value['leadtime']." day");
    $days = date('Y-m-d', $days);
    $dates ='';
    $active1='';
    $Leadtime = '';
    if(!empty($ktr->id_order))
    {
    $dates = '<br>NTH('._dhau($ktr->date_order).')';
    $dayss = (strtotime($ktr->date_order) - strtotime($order->date)) / (60 * 60 * 24);
    $active1='active';
    $Leadtime = '<br>'.abs(round($dayss)).' '._l('estimate_dt_table_heading_date');
    }
    $string_Row .= '<li class="'.$active1.'">';
    $string_Row .= '    <p class="pointer #ff6f00"  style="font-size:10px"  status-procedure="1" >';
    $string_Row .=          mb_convert_case($value['name'], MB_CASE_TITLE, "UTF-8").$dates.'<br>NDK('._d($days).')'.$Leadtime;
    $string_Row .=      '</p>';
    $string_Row .='</li>'; 
        }elseif($key == 4)
        {
    $days = strtotime(date("Y-m-d H:i:s", strtotime($date)) . " +".$value['leadtime']." day");
    $days = date('Y-m-d', $days);
    $dates ='';
    $active1='';
    $Leadtime = '';
    if(!empty($ktr->status_cancel))
    {
    $dates = '<br>NTH('._dhau($ktr->date_cancel).')';
    $active1='active';
    }
    $string_Row .= '<li class="'.$active1.'">';
    $string_Row .= '    <p class="pointer #ff6f00"  style="font-size:10px"  status-procedure="1" >';
    $string_Row .=          mb_convert_case($value['name'], MB_CASE_TITLE, "UTF-8").$dates;
    $string_Row .=      '</p>';
    $string_Row .='</li>'; 
        }
    }
    $string_Row.='<div class="clearfix"></div></ul>';
    return $string_Row;
}