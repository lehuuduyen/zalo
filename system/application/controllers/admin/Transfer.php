<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Transfer extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('transfer_model');
    }
    public function index()
    {
        if (!has_permission('transfer', '', 'view')) {
                access_denied('Debt suppliers');
        }
        $data['title']          = _l('ch_transfer_warehouse');
        // $full_costs = $this->costs_model->get_full_costs();
        $this->load->view('admin/transfer/manage', $data);
    }
  	public function table()
    {
        if (!has_permission('transfer', '', 'view')) {
                ajax_access_denied();
        }
        $this->app->get_table_data('transfer');
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
    public function detail($id = '')
    {
        if (!has_permission('transfer', '', 'create')) {
                ajax_access_denied();
        }
        if ($this->input->post()) {
            if ($id == '') {

                if (!has_permission('transfer', '', 'create')) {
                    access_denied('transfer');
                }

                $data                 = $this->input->post();
                $data['note'] = $this->input->post('note',true);
                if(isset($data['items']) && count($data['items']) > 0)
                {
                    $id = $this->transfer_model->add($data);
                }
                
                if ($id) {
                    set_alert('success', _l('ch_added_successfuly'));
                    redirect(admin_url('transfer'));
                }
            } else {
                if (!has_permission('transfer', '', 'edit')) {
                        access_denied('transfer');
                }
                $data                 = $this->input->post();
                $data['note'] = $this->input->post('note',true);
                $success = $this->transfer_model->update($data, $id);
                if ($success == true) {
                    set_alert('success', _l('ch_updated_successfuly'));
                }
                redirect(admin_url('transfer/detail/' . $id));
            }
        }
        if($id != '')
        {
            $data['title']          = _l('ch_edit_transfers'); 
            $data['items'] = $this->transfer_model->get($id);
        }else
        {
            $data['title']          = _l('ch_add_transfers');  
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
        $data['warehouse'] = get_table_where('tblwarehouse');
        $data['localtion_warehouses'] = array();
        
        $this->load->view('admin/transfer/detail', $data);
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
            access_denied('Delete Transfer Warehouse');
        }
        $response = $this->transfer_model->delete($id);
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
                'message' => _l('Số lượng bên kho xuất không đủ để duyệt kho!'),
            ));die;
        }else{
            $data=array(
            'warehouseman_id'=>get_staff_user_id(),
            'warehouseman_date'=>date('Y-m-d H:i:s')
            );
            if(empty($warehouseman_id))
            {
                log_activity('Transfer Warehouse items approved [ID Import: ' . $id);
                $this->transfer_model->increaseTranfersWarehouse($id);
                $alert_type = 'success';
                $message    = _l('ch_successful_approval');
                $success    = $this->db->update('tbltransfer_warehouse',$data,array('id'=>$id));
            }
        }    

        echo json_encode(array(
            'alert_type' => $alert_type,
            'message' => $message
        ));
    }
    public function transfer_data($id = '')
    {
        $data['items'] = $this->transfer_model->get($id);
        $this->load->view('admin/transfer/view_modal',$data);
    }
    public function update_status($value='')
    {
        if ($this->input->post()) {
            $id=$this->input->post('id');
            $status=$this->input->post('status');
            $import = get_table_where('tbltransfer_warehouse',array('id'=>$id),'','row');
            if($import->status == 2)
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
            $success=$this->transfer_model->update_status($id,$data);
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
}