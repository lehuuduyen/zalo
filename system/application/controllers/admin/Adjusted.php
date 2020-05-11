<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Adjusted extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('adjusted_model');
        $this->load->model('invoice_items_model');
    }
    public function index()
    {
        if (!has_permission('adjusted', '', 'view')) {
                access_denied('adjusted');
        }
        $data['title']          = _l('ch_adjusted_warehouse');
        $this->load->view('admin/adjusted/manage', $data);
    }
  	public function table()
    {
        if (!has_permission('adjusted', '', 'view')) {
                ajax_access_denied();
        }
        $this->app->get_table_data('adjusted');
    }
    public function SearchItems()
    {
        $data = [];
        $search = $this->input->get('term');
        $type = $this->input->get('type');
        $limit_one = 15;
        $limit_two = 15;
        $limit_all = 50;

        if($type == -1)
        {
            $this->db->select('
                    id,
                    tblitems.name as text,
                    tblitems.price,
                    concat("items") as type,
                    tblitems.avatar as img'
            , false);
            if (!empty($search))
            {
                $this->db->group_start();
                $this->db->like('tblitems.name', $search);
                $this->db->or_like('tblitems.code', $search);
                $this->db->group_end();
            }
            $this->db->order_by('name', 'DESC');
            $this->db->limit($limit_one);
            $items = $this->db->get('tblitems')->result_array();
            if(!empty($items)) {
                $data['results'][] =
                    [
                        'text' => _l('Sản phẩm'),
                        'children' => $items
                    ];
            }
            $count_items = count($items);
            $this->db->select('
                id as id,
                tbl_products.name as text,
                tbl_products.price_sell as price,
                concat("product") as type,
                CONCAT("uploads/products/", "", tbl_products.images, "") as img'
            , false);
            if (!empty($search))
            {
                $this->db->group_start();
                $this->db->like('tbl_products.name', $search);
                $this->db->or_like('tbl_products.code', $search);
                $this->db->group_end();
            }
            $this->db->order_by('tbl_products.name', 'DESC');
            $this->db->limit($limit_two);
            // $this->db->limit(($limit_all - $count_product));
            $product = $this->db->get('tbl_products')->result_array();
            if(!empty($product))
            {
                $data['results'][] =
                    [
                        'text' => _l('Thành phẩm'),
                        'children' => $product
                    ];
            }

            $count_product = count($product);
            $this->db->select('
                id as id,
                tbl_materials.name as text,
                tbl_materials.price_sell as price,
                concat("nvl") as type,
                CONCAT("uploads/materials/", "", tbl_materials.images, "") as img'
            , false);
            if (!empty($search))
            {
                $this->db->group_start();
                $this->db->like('tbl_materials.name', $search);
                $this->db->or_like('tbl_materials.code', $search);
                $this->db->group_end();
            }
            $this->db->order_by('tbl_materials.name', 'DESC');
            $this->db->limit(($limit_all - $count_product - $count_items));
            $product = $this->db->get('tbl_materials')->result_array();
            if(!empty($product))
            {
                $data['results'][] =
                    [
                        'text' => _l('Nguyên vật liệu'),
                        'children' => $product
                    ];
            }
        }else
        if($type == 'items')
        {
            $this->db->select('
                    id as id,
                    tblitems.name as text,
                    tblitems.price,
                    items as type,
                    tblitems.avatar as img'
            , false);
            if (!empty($search))
            {
                $this->db->group_start();
                $this->db->like('tblitems.name', $search);
                $this->db->or_like('tblitems.code', $search);
                $this->db->group_end();
            }
            $this->db->order_by('name', 'DESC');
            $this->db->limit(50);
            $items = $this->db->get('tblitems')->result_array();
            if(!empty($items)) {
                $data['results'][] =
                    [
                        'text' => _l('Sản phẩm'),
                        'children' => $items
                    ];
            }
        }else
        if($type == 'product')
        {
            $this->db->select('
                id as id,
                tbl_products.name as text,
                tbl_products.price_sell as price,
                product as type,
                CONCAT("uploads/products/", "", tbl_products.images, "") as img'
            , false);
            if (!empty($search))
            {
                $this->db->group_start();
                $this->db->like('tbl_products.name', $search);
                $this->db->or_like('tbl_products.code', $search);
                $this->db->group_end();
            }
            $this->db->order_by('tbl_products.name', 'DESC');
            $this->db->limit(50);
            // $this->db->limit(($limit_all - $count_product));
            $product = $this->db->get('tbl_products')->result_array();
            if(!empty($product))
            {
                $data['results'][] =
                    [
                        'text' => _l('Thành phẩm'),
                        'children' => $product
                    ];
            }
        }elseif($type == 'nvl')
        {
            $this->db->select('
                id as id,
                tbl_materials.name as text,
                tbl_materials.price_sell as price,
                nvl as type,
                CONCAT("uploads/materials/", "", tbl_materials.images, "") as img'
            , false);
            if (!empty($search))
            {
                $this->db->group_start();
                $this->db->like('tbl_materials.name', $search);
                $this->db->or_like('tbl_materials.code', $search);
                $this->db->group_end();
            }
            $this->db->order_by('tbl_materials.name', 'DESC');
            $this->db->limit(50);
            $product = $this->db->get('tbl_materials')->result_array();
            if(!empty($product))
            {
                $data['results'][] =
                    [
                        'text' => _l('Nguyên vật liệu'),
                        'children' => $product
                    ];
            }
        }
        echo json_encode($data);die();
    }
    public function create($id = '')
    {
        $this->load->model('inventory_model');
        $items = $this->inventory_model->get($id);
        $duong = get_table_where('tblinventory_items',array('inventory_id'=>$id,'quantity_diff >'=>0));
        if(!empty($duong)){
        $data['id_inventory'] = $id;
        $data['prefix'] = get_option('prefix_detail_up');
        $data['code'] = sprintf('%06d', ch_getMaxID('id', 'tbladjusted') + 1);
        $data['date'] = date('Y-m-d');  
        $data['date_create'] = date('Y-m-d H:i:s');  
        $data['warehouse_id'] = $items->warehouse_id;
        $data['staff_id'] = get_staff_user_id();
        $data['type'] = 1;
        $this->db->insert('tbladjusted',$data);
        $id_duong = $this->db->insert_id();
        $warehouse_id = $items->warehouse_id;
        $date_import = date('Y-m-d');
        $_item=array();
            foreach ($duong as $key => $value) {
                    $price = $this->adjusted_model->get_full_item($value['product_id'],$value['type']);
                    $_item['id_adjusted']=$id_duong;
                    $_item['product_id']=$value['product_id'];
                    $_item['unit_cost']=$price->price;
                    $_item['warehouse_id']=$warehouse_id;
                    $_item['localtion']=$value['localtion'];
                    $_item['type']=$value['type'];
                    $_item['quantity']=$value['quantity'];
                    $_item['quantity_net']=$value['quantity_diff'];
                    $this->db->insert('tbladjusted_items',$_item);
                    $idd = $this->db->insert_id();
                        $date_warehouse = date('Y-m-d H:i:s');
                        $localtion =  $_item['localtion'];
                        $product_id = $_item['product_id'];
                        $type_items = $_item['type'];
                        $quantity = $_item['quantity_net'];
                        $count=increaseadjuProductQuantity($warehouse_id,$id_duong,$date_warehouse,$data['date'],$product_id,$quantity,$localtion,$type_items);
                        //tăng kho tổng
                        increaseWarehuseQuantity($warehouse_id,$localtion,$product_id,$quantity,$type_items);


                            
            }
        }
        $am = get_table_where('tblinventory_items',array('inventory_id'=>$id,'quantity_diff <='=>0));
        if(!empty($am)){
        $data['id_inventory'] = $id;
        $data['prefix'] = get_option('prefix_detail_up');
        $data['code'] = sprintf('%06d', ch_getMaxID('id', 'tbladjusted') + 1);
        $data['date'] = date('Y-m-d');  
        $data['date_create'] = date('Y-m-d H:i:s');  
        $data['warehouse_id'] = $items->warehouse_id;
        $data['staff_id'] = get_staff_user_id();
        $data['type'] = 2;
        $this->db->insert('tbladjusted',$data);
        $id_am = $this->db->insert_id();
        $_item=array();
         $warehouse_id = $items->warehouse_id;
            foreach ($am as $key => $value) {
                    $price = $this->adjusted_model->get_full_item($value['product_id'],$value['type']);
                    $_item['id_adjusted']=$id_am;
                    $_item['product_id']=$value['product_id'];
                    $_item['unit_cost']=$price->price;
                    $_item['warehouse_id']=$warehouse_id;
                    $_item['localtion']=$value['localtion'];
                    $_item['type']=$value['type'];
                    $_item['quantity']=$value['quantity'];
                    $_item['quantity_net']=abs($value['quantity_diff']);
                    $this->db->insert('tbladjusted_items',$_item);
                    $idd = $this->db->insert_id();


                        $date_warehouse = date('Y-m-d H:i:s');
                        $localtion  =  $_item['localtion'];
                        $product_id = $_item['product_id'];
                        $type_items = $_item['type'];
                        $quantity = abs($_item['quantity_net']);
                        export_AdjuWarehuseQuantity($warehouse_id,$id_am,$date_warehouse,$data['date'],$product_id,$quantity,$localtion,$type_items);
                        $count=decreaseAdjuWarehuseQuantity($warehouse_id,$idd,$product_id,$quantity,$localtion,$type_items);
                        //trừ kho tổng
                        decreaseWarehuseQuantity($warehouse_id,$localtion,$product_id,$quantity,$type_items); 
            }
        }
        set_alert('success', _l('ch_added_successfuly'));
        redirect(admin_url('adjusted'));
    }
    public function detail_down($id = '')
    {
        if (!has_permission('adjusted', '', 'create')) {
                ajax_access_denied();
        }
        if ($this->input->post()) {
            if ($id == '') {

                if (!has_permission('adjusted', '', 'create')) {
                    access_denied('adjusted');
                }

                $data                 = $this->input->post();
                $data['note'] = $this->input->post('note',true);
                if(isset($data['items']) && count($data['items']) > 0)
                {
                    $id = $this->adjusted_model->add($data);
                }
                
                if ($id) {
                    set_alert('success', _l('ch_added_successfuly'));
                    redirect(admin_url('adjusted'));
                }
            } else {
                if (!has_permission('adjusted', '', 'edit')) {
                        access_denied('adjusted');
                }
                $data                 = $this->input->post();
                $data['note'] = $this->input->post('note',true);
                $success = $this->adjusted_model->update($data, $id);
                if ($success == true) {
                    set_alert('success', _l('ch_updated_successfuly'));
                }
                redirect(admin_url('adjusted/detail_up/' . $id));
            }
        }
        if($id != '')
        {
            $data['title']          = _l('ch_edit_adjusteds'); 
            $data['items'] = $this->adjusted_model->get($id);
        }else
        {
            $data['title']          = _l('ch_add_adjusteds_down');  
        }
        $data['taxes'] = get_taxes_dropdown_template('',0);
        $data['tax'] = get_table_where('tbltaxes');
        $type_items = get_table_where('tbltype_items',array('active'=>1));
        $count = 0;
        $data['type_items'][0] = array('type'=>'-1','name'=>_l('task_list_all'));
        foreach ($type_items as $key => $value) {
            $count++;
            $data['type_items'][$count] = $value;
        }
        $data['suppliers'] = get_table_where('tblsuppliers');
        $data['warehouse'] = get_table_where('tblwarehouse',array('id !='=>8));
        $data['localtion_warehouses'] = array();
        
        $this->load->view('admin/adjusted/detail_down', $data);
    }
    public function detail_up($id = '')
    {
        if (!has_permission('adjusted', '', 'create')) {
                ajax_access_denied();
        }
        if ($this->input->post()) {
            if ($id == '') {

                if (!has_permission('adjusted', '', 'create')) {
                    access_denied('adjusted');
                }

                $data                 = $this->input->post();
                $data['note'] = $this->input->post('note',true);
                if(isset($data['items']) && count($data['items']) > 0)
                {
                    $id = $this->adjusted_model->add($data);
                }
                
                if ($id) {
                    set_alert('success', _l('ch_added_successfuly'));
                    redirect(admin_url('adjusted'));
                }
            } else {
                if (!has_permission('adjusted', '', 'edit')) {
                        access_denied('adjusted');
                }
                $data                 = $this->input->post();
                $data['note'] = $this->input->post('note',true);
                $success = $this->adjusted_model->update($data, $id);
                if ($success == true) {
                    set_alert('success', _l('ch_updated_successfuly'));
                }
                redirect(admin_url('adjusted/detail_up/' . $id));
            }
        }
        if($id != '')
        {
            $data['title']          = _l('ch_edit_adjusteds'); 
            $data['items'] = $this->adjusted_model->get($id);
        }else
        {
            $data['title']          = _l('ch_add_adjusteds_up');  
        }
        $data['taxes'] = get_taxes_dropdown_template('',0);
        $data['tax'] = get_table_where('tbltaxes');
        $type_items = get_table_where('tbltype_items',array('active'=>1));
        $count = 0;
        $data['type_items'][0] = array('type'=>'-1','name'=>_l('task_list_all'));
        foreach ($type_items as $key => $value) {
            $count++;
            $data['type_items'][$count] = $value;
        }
        $data['suppliers'] = get_table_where('tblsuppliers');
        $data['warehouse'] = get_table_where('tblwarehouse',array('id !='=>8));
        $data['localtion_warehouses'] = array();
        
        $this->load->view('admin/adjusted/detail_up', $data);
    }
    public function add()
    {
        if ($this->input->post()) {
                $message = '';
                $data = $this->input->post();
                unset($data['id']); 
                if($data['costs_parent']==NULL || $data['costs_parent']=='')
                {
                    $data['lever']=1;
                }else
                {
                    $lever = 1;
                    $parent = $data['costs_parent'];
                         

                    while ($parent > 0) {
                       $ktr = get_table_where('tblcosts',array('id'=>$parent),'','row');
                       $parent = $ktr->costs_parent;
                       $lever++; 

                    }
                    $data['lever'] = $lever;
                }
                $this->db->insert('tblcosts',$data);

                $id = $this->db->insert_id();
                if ($id) {
                    $success = true;
                    $message = _l('ch_added_successfuly');
                }
                echo json_encode(array(
                    'success' => $success,
                    'message' => $message
                ));
            die;
        }
    }
    public function update()
    {
        if ($this->input->post()) {
                $message = '';
                $data = $this->input->post();
                $id = $data['id'];
                unset($data['id']);
                $this->db->where('id',$id);
                $idd = $this->db->update('tblcosts',$data);

                if ($id) {
                    $success = true;
                    $message = _l('ch_updated_successfuly');
                }
                echo json_encode(array(
                    'success' => $success,
                    'message' => $message
                ));
            die;
        }
    }
    public function test_quantity()
    {
        $warehouse_id_main=$this->input->post('warehouse_id_main');
        $test_quantity = 0;
            $product=explode(',', trim($this->input->post('product_id'),','));
            $this->db->select('count(*) as count');
            foreach ($product as $key => $v) {
                $product_id=explode('|', $v);
                $this->db->or_group_start();
                $this->db->where('id_items',$product_id[1]);
                $this->db->where('type_items',$product_id[0]);
                $this->db->where('localtion',$product_id[2]);
                $this->db->where('product_quantity >=',$product_id[3]);
                $this->db->group_end();
            }
            $this->db->where('warehouse_id',$warehouse_id_main);
            $result = $this->db->get('tblwarehouse_items')->row();
                if($result->count == count($product))
            {
                $data['success'] = true;
            }else
            {
                $data['success'] = false;
                foreach ($product as $key => $v) {
                    $product_id=explode('|', $v);
                    $this->db->select('product_quantity');
                    $this->db->where('id_items',$product_id[1]);
                    $this->db->where('type_items',$product_id[0]);
                    $this->db->where('localtion',$product_id[2]);
                    $this->db->where('warehouse_id',$warehouse_id_main);
                    $data['items'][$product_id[4]] = $this->db->get('tblwarehouse_items')->row()->product_quantity;
                }
            }
        echo json_encode($data);die; 
    }
    public function delete($id)
    {
        if (!is_admin()) {
            access_denied('Delete adjusted Warehouse');
        }
        $response = $this->adjusted_model->delete($id);
        $alert_type = 'warning';
        $message    = _l('ch_no_delete');  
        if ($response) {
            $alert_type = 'success';
            $message    = _l('ch_delete');
        }
        echo json_encode(array(
            'alert_type' => $alert_type,
            'message' => $message
            ));
    }
    public function confirm_warehous()
    {
        $id=$this->input->post('id');
        $warehouseman_id=$this->input->post('warehouseman_id'); 
        if (!$id) {
            die('ch_no_items');
        }
        if(!test_quantity_tranfer($id))
        {
            echo json_encode(array(
                'alert_type' => 'warning',
                'message' => _l('Số lượng bên kho xuất kho đủ để duyệt kho!'),
            ));die;
        }else{
            $data=array(
            'warehouseman_id'=>get_staff_user_id(),
            'warehouseman_date'=>date('Y-m-d H:i:s')
            );
            if(empty($warehouseman_id))
            {
                log_activity('adjusted Warehouse items approved [ID Import: ' . $id);
                $this->adjusted_model->increaseTranfersWarehouse($id);
                $alert_type = 'success';
                $message    = _l('ch_successful_approval');
                $success    = $this->db->update('tbladjusted_warehouse',$data,array('id'=>$id));
            }
        }    

        echo json_encode(array(
            'alert_type' => $alert_type,
            'message' => $message
        ));
    }
    public function adjusted_data($id = '')
    {
        $data['items'] = $this->adjusted_model->get($id);
        $data['warehouse_name'] = get_table_where('tblwarehouse',array('id'=>$data['items']->warehouse_id),'','row');
        $this->load->view('admin/adjusted/view_modal',$data);
    }
    public function update_status($value='')
    {
        if ($this->input->post()) {
            $id=$this->input->post('id');
            $status=$this->input->post('status');
            $import = get_table_where('tbladjusted',array('id'=>$id),'','row');
            if($import->status == 1)
            {
                die;
            }
            $staff_id=get_staff_user_id();
            $date=date('Y-m-d H:i:s');
            $history_status = $import->history_status;
            $history_status.='|'.$staff_id.','.$date;
            $data =array(
                'history_status'=>$history_status,
                'status' => ($status+1),
            );
            $this->db->where('id',$id);
            $success=$this->db->update('tbladjusted',$data);
        }
        if($success) {
            echo json_encode(array(
                'success' => $success,
                'alert_type' => 'success',
                'message' => _l('ch_successful_approval')
            ));
        }
        else
        {
            echo json_encode(array(
                'success' => $success,
                'alert_type' => 'danger',
                'message' => _l('ch_no_successful_approval')
            ));
        }
        die;
    }
    public function count_all()
    {
        $count = get_table_where_select('count(*) as alls','tbladjusted',array(),'','row');
        $up = get_table_where_select('count(*) as up','tbladjusted',array('type'=>1),'','row');
        $down = get_table_where_select('count(*) as down','tbladjusted',array('type'=>2),'','row');

        $data['all'] = $count->alls;
        $data['up'] = $up->up;
        $data['down'] = $down->down;

        echo json_encode($data);
    }
    public function test_quantity_sumbit($warehouse_id='',$id_product='')
    {   
        $warehouse_id=$this->input->post('warehouse_id');
        $test_quantity = 0;
            $product=explode(',', trim($this->input->post('product_id'),','));
            foreach ($product as $key => $v) {
                $product_id=explode('|', $v);
                $data['items'][$key]['quantity'] = 0;
                $warehous = get_table_where('tblwarehouse_items',array('warehouse_id'=>$warehouse_id,'localtion'=>$product_id[1],'id_items'=>$product_id[0],'type_items'=>$product_id[2]),'','row');
                if(!empty($warehous))
                {
                    $data['items'][$key]['quantity'] = $warehous->product_quantity;
                }
                
                if($product_id[3] != ($data['items'][$key]['quantity']))
                {
                 $test_quantity++;   
                }
            }
        $data['test_quantity'] = $test_quantity;
        echo json_encode($data);die; 

    }
    public function print_pdf($id='')
    {
        ob_start();
        $data = new stdClass();
        // $dataField = get_table_where('tbl_field_pdf',array('parent_field'=>'import'),'','row');
        $dataMain = get_table_where('tbladjusted',array('id'=>$id),'','row');
        $dataSub = get_table_where('tbladjusted_items',array('id_adjusted'=>$id));
        $warehouse = get_table_where('tblwarehouse',array('id'=>$dataMain->warehouse_id),'','row');
        $table = '';
        $img = file_get_contents(base_url('uploads/company/').get_option('company_logo'));
        $data->img = '<img width="100" src="data:image/png;base64,'.base64_encode($img).'"/>';
        $data->content = '<table class="table table-bordered" width="100%">
                <thead>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 20%;">
                        </td>
                        <td style="width: 80%;">
                            <span style="font-weight: bold;font-size: 14px;">'.get_option('invoice_company_name').'</span><br>
                            <span>'.lang('tnh_address').': '.get_option('invoice_company_address').'</span><br>
                            <span>'.lang('tnh_phone').': '.get_option('invoice_company_phonenumber').'</span><br>
                            <span>'._l('Email').': </span><br>
                            <span>'.lang('tnh_website').': '.get_option('company_website').'</span><br>
                        </td>
                    </tr>
                </tbody>
            </table>';
        $data->content .= '<span style="text-align: center;">_____________________________________________________________________________________________</span><br><br>';
        $data->content .= '<span style="text-align: center;font-size: 20px;font-weight: bold;">PHIẾU ĐIỀU CHỈNH</span><br><br>';
        $data->content .= '<span style="text-align: right;font-style: italic;">'._l('ch_code_p').': '.$dataMain->prefix.$dataMain->code.'</span><br>';
        $data->content .= '<span style="text-align: right;font-style: italic;">'._l('ch_date_p').': '._d($dataMain->date).'</span><br><br>';
        $data->content .= '
            <span style="font-weight: bold;">'._l('ch_staff_p').': </span><span>'.get_staff_full_name($dataMain->staff_id).'</span><br>
            <span style="font-weight: bold;">'._l('Kho hàng').': </span><span>'.$warehouse->name.'</span><br>
            <span style="font-weight: bold;">'._l('ch_note_t').': </span><span>'.$dataMain->note.'</span><br><br>
        ';


        $table = '
            <table class="table table-bordered" border="1" width="100%">
                <thead>
                    <tr>
                        <td style="width: 5%;text-align: center;font-weight: bold;">'._l('STT').'</td>
        ';
        $table .= '<td style="width: 27%;text-align: center;font-weight: bold;">'._l('ch_items_name_t').'</td>';
        $table .= '<td style="width: 13%;text-align: center;font-weight: bold;">'._l('item_unit').'</td>';
        $table .= '<td style="width: 15%;text-align: center;font-weight: bold;">'._l('warehouse_localtion').'</td>';
        $table .= '<td style="width: 12%;text-align: center;font-weight: bold;">'._l('item_quantity').'</td>';
        if($dataMain->type == 1)
        {
        $table .= '<td style="width: 16%;text-align: center;font-weight: bold;">'._l('Số điều chỉnh tăng').'</td>';
        }else
        {
        $table .= '<td style="width: 16%;text-align: center;font-weight: bold;">'._l('Số điều chỉnh giảm').'</td>';    
        }
        $table .= '<td style="width: 12%;text-align: center;font-weight: bold;">'._l('Số lượng thực kho').'</td>';
        $table .= '</tr>
                </thead>
                <tbody>';
        $sum_quantity = 0;
        $quantity_net = 0;
        $quantity_diff = 0;
        foreach ($dataSub as $key => $value) {
            $table .= '<tr>';
            $dataItem = $this->invoice_items_model->get_full_item($value['product_id'],$value['type']);
            $dataLocaltion = get_table_where('tbllocaltion_warehouses',array('id'=>$value['localtion']),'','row');

            $table .= '<td style="width: 5%;text-align: center;">'.++$key.'</td>';
            $table .= '<td style="width: 27%;text-align: left;">'.$dataItem->name.'</td>';
            $table .= '<td style="width: 13%;text-align: left;">'.$dataItem->unit_name.'</td>';
            $table .= '<td style="width: 15%;text-align: left;">'.$dataLocaltion->name.'</td>';
            $table .= '<td style="width: 12%;text-align: center;">'.number_format($value['quantity']).'</td>';
            $table .= '<td style="width: 16%;text-align: center;">'.number_format($value['quantity_net']).'</td>';
            if($dataMain->type == 1)
            {
            $table .= '<td style="width: 12%;text-align: center;">'.number_format($value['quantity']+$value['quantity_net']).'</td>';
            }else
            {
            $table .= '<td style="width: 12%;text-align: center;">'.number_format($value['quantity']-$value['quantity_net']).'</td>';    
            }
            $table .= '</tr>';
            $sum_quantity+=$value['quantity'];
            $quantity_net+=$value['quantity_net'];
            if($dataMain->type == 1)
            {
            $quantity_diff+=$value['quantity']+$value['quantity_net'];
            }else
            {
            $quantity_diff+=$value['quantity']-$value['quantity_net'];    
            }
        }
        $table .= '<tr>
                <td colspan="4" style="text-align: center;font-weight: bold;">'._l('invoice_dt_table_heading_amount').'</td>';
        $table .= '<td style="text-align: center;">'.number_format($sum_quantity).'</td>';
        $table .= '<td style="text-align: center;">'.number_format($quantity_net).'</td>';
        $table .= '<td style="text-align: center;">'.number_format($quantity_diff).'</td>';
        $table .= '</tr>';
        $table .= '</tbody>
            </table>';
        $data->content .= $table;


        $table = '<table class="table table-bordered" width="100%">
                <thead>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;">
                            <span style="font-weight: bold;">Người Đề Nghị</span><br>
                            <span>(ký, ghi rõ họ tên)</span>
                        </td>
                        <td style="text-align: center;">
                            <span style="font-weight: bold;">Người Giao</span><br>
                            <span>(ký, ghi rõ họ tên)</span>
                        </td>
                        <td style="text-align: center;">
                            <span style="font-weight: bold;">Người Nhận</span><br>
                            <span>(ký, ghi rõ họ tên)</span>
                        </td>
                        <td style="text-align: center;">
                            <span style="font-weight: bold;">Thủ Kho</span><br>
                            <span>(ký, ghi rõ họ tên)</span>
                        </td>
                    </tr>
                </tbody>
            </table>';
        $data->content .= $table;
        $pdf      = print_pdf($data);
        $type     = 'I';
        $pdf->Output(slug_it('') . '.pdf', $type);
    }
}