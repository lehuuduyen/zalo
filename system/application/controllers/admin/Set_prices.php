<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Set_prices extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        if (!has_permission('set_prices', '', 'view') && !has_permission('set_prices', '', 'view_own')) {
            access_denied('set_prices');
        }
        $data['groups'] = get_table_where('tblcustomers_groups');
        $data['title'] = _l('set_prices');
        $this->load->view('admin/set_prices/manage', $data);
    }

    public function table_set_prices($value='')
    {
        if (!has_permission('set_prices', '', 'view')) {
            ajax_access_denied();
        }
        $this->app->get_table_data('set_prices');
    }

    public function group_detail($id = '')
    {
        $data = $this->input->post();
        $group = '';
        $date_start = '';
        $date_end = '';
        if($data['type_customer'] == 2) {
            if(!empty($data['groups_in'])) {
                $group = implode(',', $data['groups_in']);
            }
        }
        if(isset($data['checkbox_date'])) {
            $data['checkbox_date'] = 1;
        }
        else {
            $data['checkbox_date'] = 0;
            if(!empty($data['date_active'])) {
                $date = explode(' - ', $data['date_active']);
                $date_start = to_sql_date($date[0]);
                $date_end = to_sql_date($date[1]);
            }
        }
        if($id == "") {
            $in = array(
                'name'=>$data['name'],
                'checkbox_date'=>$data['checkbox_date'],
                'date_start'=>$date_start,
                'date_end'=>$date_end,
                'status '=>$data['status'],
                'type_customer'=>$data['type_customer'],
                'id_groups'=>$group,
                'type_item'=>$data['type_item'],
                'type_price_setting'=>$data['type_price_setting'],
                'sum_OR_sub'=>$data['sum_OR_sub'],
                'vnd_OR_percent'=>$data['vnd_OR_percent'],
                'value_price_setting '=>str_replace(',','',$data['value_price_setting'])
            );
            if(is_numeric($data['type_price_setting'])) {
                $this->db->insert('tbl_set_prices',$in);
                $insert_id = $this->db->insert_id();

                $get_data_item = get_table_where('tbl_set_prices_items',array('id_set_prices'=>$data['type_price_setting']));
                foreach ($get_data_item as $key => $value) {
                    $in_item = array(
                        'id_set_prices'=>$insert_id,
                        'type'=>$value['type'],
                        'id_product'=>$value['id_product']
                    );

                    if($data['sum_OR_sub'] == 'sum') {
                        if($data['vnd_OR_percent'] == 'vnd') {
                            $in_item['prices_new'] = $value['prices_new'] + str_replace(',','',$data['value_price_setting']);
                        }
                        else {
                            $in_item['prices_new'] = $value['prices_new'] + ($value['prices_new']*$data['value_price_setting']/100);
                        }
                    }
                    else {
                        if($data['vnd_OR_percent'] == 'vnd') {
                            $in_item['prices_new'] = $value['prices_new'] - str_replace(',','',$data['value_price_setting']);
                        }
                        else {
                            $in_item['prices_new'] = $value['prices_new'] - ($value['prices_new']*$data['value_price_setting']/100);
                        }
                    }
                    $this->db->insert('tbl_set_prices_items',$in_item);
                }
                echo json_encode(array('success' => true, 'alert_type' => 'success', 'message' => _l('add_set_prices_success')));
            }
            else {
                $this->db->insert('tbl_set_prices',$in);
                echo json_encode(array('success' => true, 'alert_type' => 'success', 'message' => _l('add_set_prices_success')));
            }
        }
        else {
            $in = array(
                'name'=>$data['name'],
                'checkbox_date'=>$data['checkbox_date'],
                'date_start'=>$date_start,
                'date_end'=>$date_end,
                'status '=>$data['status'],
                'type_customer'=>$data['type_customer'],
                'id_groups'=>$group,
                'type_item'=>$data['type_item'],
                'type_price_setting'=>$data['type_price_setting'],
                'sum_OR_sub'=>$data['sum_OR_sub'],
                'vnd_OR_percent'=>$data['vnd_OR_percent'],
                'value_price_setting '=>str_replace(',','',$data['value_price_setting'])
            );
            if(is_numeric($data['type_price_setting'])) {
                $this->db->where('id',$id);
                $this->db->update('tbl_set_prices',$in);

                $get_data_item = get_table_where('tbl_set_prices_items',array('id_set_prices'=>$data['type_price_setting']));
                foreach ($get_data_item as $key => $value) {
                    $in_item = array(
                        'id_set_prices'=>$id,
                        'type'=>$value['type'],
                        'id_product'=>$value['id_product']
                    );

                    if($data['sum_OR_sub'] == 'sum') {
                        if($data['vnd_OR_percent'] == 'vnd') {
                            $in_item['prices_new'] = $value['prices_new'] + str_replace(',','',$data['value_price_setting']);
                        }
                        else {
                            $in_item['prices_new'] = $value['prices_new'] + ($value['prices_new']*$data['value_price_setting']/100);
                        }
                    }
                    else {
                        if($data['vnd_OR_percent'] == 'vnd') {
                            $in_item['prices_new'] = $value['prices_new'] - str_replace(',','',$data['value_price_setting']);
                        }
                        else {
                            $in_item['prices_new'] = $value['prices_new'] - ($value['prices_new']*$data['value_price_setting']/100);
                        }
                    }
                    $get_item_old = get_table_where('tbl_set_prices_items',array('id_set_prices'=>$id));
                    foreach ($get_item_old as $key_item_old => $value_item_old) {
                        if($value_item_old['id_product'] == $value['id_product']) {
                            $this->db->where('id',$value_item_old['id']);
                            $this->db->delete('tbl_set_prices_items');
                        }
                    }
                    $this->db->insert('tbl_set_prices_items',$in_item);
                }
                echo json_encode(array('success' => true, 'alert_type' => 'success', 'message' => _l('edit_set_prices_success')));
            }
            else {
                $this->db->where('id',$id);
                $this->db->update('tbl_set_prices',$in);
                echo json_encode(array('success' => true, 'alert_type' => 'success', 'message' => _l('edit_set_prices_success')));
            }
        }
    }

    public function detail($type_item = '', $id = '')
    {
        //type_item: 1 - hàng hóa || 2 - thành phẩm
        if($type_item == 2) {
            if ($this->input->post()) {
                $data = $this->input->post();
                $arrID = array();
                if(isset($data['items'])) {
                    $items = $data['items'];
                    foreach ($items as $key => $item) {
                        $item_data = array(
                            'id_set_prices' => $id,
                            'type' => 'product',
                            'id_product' => $item['id_items'],
                            'prices_new' => str_replace(',','',$item['prices_new'])
                        );
                        $checkExists = get_table_where('tbl_set_prices_items',array('id_set_prices'=>$id,'id_product'=>$item['id_items'],'type'=>'product'),'','row');
                        if(!$checkExists) {
                            $this->db->insert('tbl_set_prices_items', $item_data);
                            $arrID[] = $this->db->insert_id();
                        }
                        else {
                            $this->db->where('id', $checkExists->id);
                            $this->db->update('tbl_set_prices_items', $item_data);
                            $arrID[] = $checkExists->id;
                        }
                    }
                }
                if($arrID!=array())
                {
                    $this->db->where('id_set_prices',$id);
                    $this->db->where_not_in('id',$arrID);
                }
                else {
                    $this->db->where('id_set_prices',$id);
                }
                $this->db->delete('tbl_set_prices_items');
                set_alert('success', _l('ch_updatee_items'));
                redirect(admin_url('set_prices/detail/2/' . $id));
            }
            $data['dataMain'] = get_table_where('tbl_set_prices',array('id'=>$id),'','row');

            $this->db->select('tbl_set_prices_items.*, tbl_products.id as id_item, tbl_products.name as name_item, tbl_products.code as code_item, tbl_products.price_import as price_import, tbl_products.images as images');
            $this->db->from('tbl_set_prices_items');
            $this->db->join('tbl_products', 'tbl_products.id = tbl_set_prices_items.id_product', 'left');
            $this->db->where('tbl_set_prices_items.type', 'product');
            $this->db->where('tbl_set_prices_items.id_set_prices', $id);
            $dataSub = $this->db->get()->result_array();
            $data['dataSub'] = array();
            foreach ($dataSub as $key => $value) {
                $data['dataSub'][$key]['type_item'] = 'product';
                $data['dataSub'][$key]['prices_new'] = $value['prices_new'];
                $data['dataSub'][$key]['id_item'] = $value['id_item'];
                $data['dataSub'][$key]['name_item'] = $value['name_item'];
                $data['dataSub'][$key]['code_item'] = $value['code_item'];
                $data['dataSub'][$key]['price_import'] = $value['price_import'];
                $data['dataSub'][$key]['images'] = $value['images'];
                $get_last_price = get_table_where('tblimport_items',array('product_id'=>$value['id_item'],'type'=>'product'),'id DESC','row');
                if($get_last_price) {
                    $data['dataSub'][$key]['last_price'] = $get_last_price->price;
                }
                else {
                    $data['dataSub'][$key]['last_price'] = $value['price_import'];
                }
            }
        }
        else if($type_item == 1) {
            if ($this->input->post()) {
                $data = $this->input->post();
                $arrID = array();
                if(isset($data['items'])) {
                    $items = $data['items'];
                    foreach ($items as $key => $item) {
                        $item_data = array(
                            'id_set_prices' => $id,
                            'type' => 'items',
                            'id_product' => $item['id_items'],
                            'prices_new' => str_replace(',','',$item['prices_new'])
                        );
                        $checkExists = get_table_where('tbl_set_prices_items',array('id_set_prices'=>$id,'id_product'=>$item['id_items'],'type'=>'items'),'','row');
                        if(!$checkExists) {
                            $this->db->insert('tbl_set_prices_items', $item_data);
                            $arrID[] = $this->db->insert_id();
                        }
                        else {
                            $this->db->where('id', $checkExists->id);
                            $this->db->update('tbl_set_prices_items', $item_data);
                            $arrID[] = $checkExists->id;
                        }
                    }
                }
                if($arrID!=array())
                {
                    $this->db->where('id_set_prices',$id);
                    $this->db->where_not_in('id',$arrID);
                }
                else {
                    $this->db->where('id_set_prices',$id);
                }
                $this->db->delete('tbl_set_prices_items');
                set_alert('success', _l('ch_updatee_items'));
                redirect(admin_url('set_prices/detail/1/' . $id));
            }
            $data['dataMain'] = get_table_where('tbl_set_prices',array('id'=>$id),'','row');

            $this->db->select('tbl_set_prices_items.*, tblitems.id as id_item, tblitems.name as name_item, tblitems.code as code_item, tblitems.price as price_import, tblitems.avatar as images');
            $this->db->from('tbl_set_prices_items');
            $this->db->join('tblitems', 'tblitems.id = tbl_set_prices_items.id_product', 'left');
            $this->db->where('tbl_set_prices_items.type', 'items');
            $this->db->where('tbl_set_prices_items.id_set_prices', $id);
            $dataSub = $this->db->get()->result_array();
            $data['dataSub'] = array();
            foreach ($dataSub as $key => $value) {
                $data['dataSub'][$key]['type_item'] = 'items';
                $data['dataSub'][$key]['prices_new'] = $value['prices_new'];
                $data['dataSub'][$key]['id_item'] = $value['id_item'];
                $data['dataSub'][$key]['name_item'] = $value['name_item'];
                $data['dataSub'][$key]['code_item'] = $value['code_item'];
                $data['dataSub'][$key]['price_import'] = $value['price_import'];
                $data['dataSub'][$key]['images'] = $value['images'];
                $get_last_price = get_table_where('tblimport_items',array('product_id'=>$value['id_item'],'type'=>'items'),'id DESC','row');
                if($get_last_price) {
                    $data['dataSub'][$key]['last_price'] = $get_last_price->price;
                }
                else {
                    $data['dataSub'][$key]['last_price'] = $value['price_import'];
                }
            }
        }

        $data['title'] = _l('detail_set_prices');
        $this->load->view('admin/set_prices/detail', $data);
    }
    public function change_status($id = '', $status = '')
    {
        if($id != '') {
            //2: k áp dụng, 1: áp dụng
            if($status == 0) {
                $this->db->set('status',2);
                $this->db->where('id',$id);
                $this->db->update('tbl_set_prices');
            }
            else if($status == 1) {
                $this->db->set('status',1);
                $this->db->where('id',$id);
                $this->db->update('tbl_set_prices');
            }
        }
    }
    public function getData($id='')
    {
        $this->db->select('tbl_set_prices.*');
        $this->db->where('id',$id);
        $data = $this->db->get('tbl_set_prices')->row();
        $data->date_start = _d($data->date_start);
        $data->date_end = _d($data->date_end);
        $data->date_active = '';
        if($data->checkbox_date == 0 || is_null($data->checkbox_date)) {
            $data->date_active = $data->date_start.' - '.$data->date_end;
        }
        $data->id_groups = explode(',', $data->id_groups);
        echo json_encode($data);
    }
    public function getData_items_by_category($id_category = '', $type_item = '')
    {
        if($type_item == 'product') {
            $arrID_child = array();
            $this->get_childs_id_product($id_category, $arrID_child);
            $arrData = array();
            if($arrID_child != array()) {
                $this->db->select('tbl_products.*');
                $this->db->where_in('category_id',$arrID_child);
                $this->db->where('type_products','products');
                $Data = $this->db->get('tbl_products')->result_array();

                foreach ($Data as $key => $value) {
                    $arrData[$key]['type_item'] = 'product';
                    $arrData[$key]['id'] = $value['id'];
                    $arrData[$key]['name'] = $value['name'];
                    $arrData[$key]['code'] = $value['code'];
                    $arrData[$key]['price_import'] = $value['price_import'];
                    $arrData[$key]['images'] = $value['images'];
                    $get_last_price = get_table_where('tblimport_items',array('product_id'=>$value['id'],'type'=>'product'),'id DESC','row');
                    if($get_last_price) {
                        $arrData[$key]['last_price'] = $get_last_price->price;
                    }
                    else {
                        $arrData[$key]['last_price'] = $value['price_import'];
                    }
                }
            }
            echo json_encode($arrData);die;
        }
        else if($type_item == 'items') {
            $arrID_child = array();
            $this->get_childs_id_items($id_category, $arrID_child);
            $arrData = array();
            if($arrID_child != array()) {
                $this->db->select('tblitems.*');
                $this->db->where_in('category_id',$arrID_child);
                $Data = $this->db->get('tblitems')->result_array();

                foreach ($Data as $key => $value) {
                    $arrData[$key]['type_item'] = 'items';
                    $arrData[$key]['id'] = $value['id'];
                    $arrData[$key]['name'] = $value['name'];
                    $arrData[$key]['code'] = $value['code'];
                    $arrData[$key]['price_import'] = $value['price'];
                    $arrData[$key]['images'] = $value['avatar'];
                    $get_last_price = get_table_where('tblimport_items',array('product_id'=>$value['id'],'type'=>'items'),'id DESC','row');
                    if($get_last_price) {
                        $arrData[$key]['last_price'] = $get_last_price->price;
                    }
                    else {
                        $arrData[$key]['last_price'] = $value['price'];
                    }
                }
            }
            echo json_encode($arrData);die;
        }
    }
    public function getData_items_by_item($id_item = '', $type_item = '')
    {
        if($type_item == 'product') {
            $this->db->select('tbl_products.*');
            $this->db->where('type_products','products');
            $this->db->where('id',$id_item);
            $Data = $this->db->get('tbl_products')->row();
            if($Data) {
                $Data->type_item = 'product';
                $get_last_price = get_table_where('tblimport_items',array('product_id'=>$Data->id,'type'=>'product'),'id DESC','row');
                if($get_last_price) {
                    $Data->last_price = $get_last_price->price;
                }
                else {
                    $Data->last_price = $Data->price_import;
                }
            }
            echo json_encode($Data);die;
        }
        else if($type_item == 'items') {
            $this->db->select('tblitems.*');
            $this->db->where('id',$id_item);
            $Data = $this->db->get('tblitems')->row();
            if($Data) {
                foreach ($Data as $key => $value) {
                    $Data->type_item = 'items';
                    $Data->price_import = $Data->price;
                    $Data->images = $Data->avatar;
                    $get_last_price = get_table_where('tblimport_items',array('product_id'=>$Data->id,'type'=>'items'),'id DESC','row');
                    if($get_last_price) {
                        $Data->last_price = $get_last_price->price;
                    }
                    else {
                        $Data->last_price = $Data->price;
                    }
                }
            }
            echo json_encode($Data);die;
        }
    }
    function get_childs_id_product($parent_id='', &$result=array()) {
        array_push($result, $parent_id);
        $this->db->where('parent_id', $parent_id);
        $items = $this->db->get('tbl_category_products')->result();  
        foreach($items as $value) {
            $this->get_childs_id_product($value->id, $result);
        }
    }
    function get_childs_id_items($parent_id='', &$result=array()) {
        array_push($result, $parent_id);
        $this->db->where('category_parent', $parent_id);
        $items = $this->db->get('tblcategories')->result();  
        foreach($items as $value) {
            $this->get_childs_id_items($value->id, $result);
        }
    }

    public function SearchItems()
    {
        $data = [];
        $search = $this->input->get('term');
        $type = $this->input->get('types');
        $limit_one = 15;
        $limit_two = 15;
        $limit_all = 50;
        if($type == 'product')
        {
            $this->db->select('
                tbl_products.id as id,
                tbl_products.name as text,
                tbl_products.code as code,
                CONCAT("uploads/products/", "", tbl_products.images, "") as img'
            , false);
            if (!empty($search))
            {
                $this->db->group_start();
                $this->db->like('tbl_products.name', $search);
                $this->db->or_like('tbl_products.code', $search);
                $this->db->group_end();
            }
            $this->db->where('tbl_products.type_products', 'products');
            $this->db->order_by('tbl_products.name', 'DESC');
            $this->db->limit(50);
            $product = $this->db->get('tbl_products')->result_array();
            if(!empty($product))
            {
                $data['results'][] =
                [
                    'children' => $product
                ];
            }
        }
        else if($type == 'items')
        {
            $this->db->select('
                tblitems.id as id,
                tblitems.name as text,
                tblitems.code as code,
                tblitems.avatar as img'
            , false);
            if (!empty($search))
            {
                $this->db->group_start();
                $this->db->like('tblitems.name', $search);
                $this->db->or_like('tblitems.code', $search);
                $this->db->group_end();
            }
            $this->db->order_by('tblitems.name', 'DESC');
            $this->db->limit(50);
            $product = $this->db->get('tblitems')->result_array();
            if(!empty($product))
            {
                $data['results'][] =
                [
                    'children' => $product
                ];
            }
        }
        echo json_encode($data);die();
    }
    public function getData_price($type = '')
    {
        $arr = array();
        $arr[] = array(
            'id'=>'giá vốn',
            'name'=>_l('cost_price'),
            'sub_name'=>_l('system')
        );
        $arr[] = array(
            'id'=>'giá nhập cuối',
            'name'=>_l('item_price_last'),
            'sub_name'=>_l('system')
        );
        $get_price = get_table_where('tbl_set_prices',array('type_item'=>$type));
        foreach ($get_price as $key => $value) {
            $value['sub_name'] = _l('table_set_prices');
            $arr[] = $value;
        }
        echo json_encode($arr);die();
    }
    public function delete_set_prices($id='')
    {
        $checkExists = get_table_where('tbl_set_prices',array('type_price_setting'=>$id),'','row');
        if($checkExists) {
            echo json_encode(array('success' => false, 'alert_type' => 'danger', 'message' => _l('dont_delete_set_prices')));
        }
        else {
            $this->db->where('id',$id);
            $this->db->delete('tbl_set_prices');

            $this->db->where('id_set_prices',$id);
            $this->db->delete('tbl_set_prices_items');

            echo json_encode(array('success' => true, 'alert_type' => 'success', 'message' => _l('ch_delete_successfuly')));
        }
    }
}