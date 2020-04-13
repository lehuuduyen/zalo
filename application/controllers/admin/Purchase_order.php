<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Purchase_order extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('purchase_order_model');
        $this->load->model('purchases_model');
        $this->load->model('invoice_items_model');
        $this->load->model('supplier_quotes_model');
        $this->load->model('misc_model');
        $this->load->model('costs_model');
    }
    public function index()
    {
        if (!has_permission('purchase_order', '', 'view')) {
            if (!has_permission('purchase_order', '', 'create')) {
                access_denied('purchase_order');
            }
        }
        $this->db->select('tblstaff.staffid, CONCAT(firstname," ",lastname) as name');
        $this->db->from('tblstaff');
        $data['dataStaff'] = $this->db->get()->result_array();

        $this->db->select('tblsuppliers.id, tblsuppliers.company, CONCAT(prefix,"-",code) as code');
        $this->db->from('tblsuppliers');
        $data['dataSupplier'] = $this->db->get()->result_array();
        $data['dataPriorities'] = get_table_where('tbltickets_priorities');

        $data['title']          = _l('ch_order');
        //cập nhật dữ liệu mức độ ưu tiên cho người đăng nhập đầu tiên trong ngày
        $checkDone = get_table_where('tbl_update_on_day',array(),'','row');
        if($checkDone->date != date('Y-m-d')) {
            $this->misc_model->update_priority();

            $this->db->set('date',date('Y-m-d'));
            $this->db->update('tbl_update_on_day');
        }
        //end
        $this->db->select('tblsuppliers.*');
        $this->db->join('tblsuppliers','tblsuppliers.id = tblpurchase_order.suppliers_id');
        $this->db->group_by('tblsuppliers.id');
        $data['suppliers'] = $this->db->get('tblpurchase_order')->result_array();
        $this->load->view('admin/purchase_order/manage', $data);
    }
    public function table()
    {
        if (!has_permission('purchase_order', '', 'view')) {
                ajax_access_denied();
        }
        $this->app->get_table_data('purchase_order');
    }
    public function detail($id='')
    {
        if ($this->input->post()) {
            if ($id == '') {

                if (!has_permission('purchase_order', '', 'create')) {
                    access_denied('purchase_order');
                }

                $data = $this->input->post();
                
                if(isset($data['items']) && count($data['items']) > 0)
                {
                    $id = $this->purchase_order_model->add($data);
                }
                
                if ($id) {
                    set_alert('success', _l('ch_added_successfuly'));
                    redirect(admin_url('purchase_order'));
                }
            } else {
                if (!has_permission('purchase_order', '', 'edit')) {
                        access_denied('purchase_order');
                }
                $success = $this->purchase_order_model->update($this->input->post(), $id);
                if ($success == true) {
                    $this->load->model('misc_model');
                    $this->misc_model->changeRowNew_model('tblpurchase_order',$id);
                    
                    set_alert('success', _l('ch_updated_successfuly'));
                }
                redirect(admin_url('purchase_order/detail/' . $id));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('ch_purchase_order'));

        } else {
            $title = _l('edit', _l('ch_purchase_order'));
            $data['items'] = $this->purchase_order_model->get($id);
        }
        $data['staff'] = get_table_where('tblstaff');
        $data['suppliers'] = get_table_where('tblsuppliers');
        $data['tax'] = get_table_where('tbltaxes');
        $data['taxes'] = get_taxes_dropdown_template('',0);
        $type_items = get_table_where('tbltype_items',array('active'=>1));
        $count = 0;
        $data['type_items'][0] = array('type'=>'-1','name'=>_l('task_list_all'));
        foreach ($type_items as $key => $value) {
            $count++;
            $data['type_items'][$count] = $value;
        }
        $data['title'] = $title;
        $this->load->view('admin/purchase_order/detail', $data);
    } 
    public function purchases_detail($id='')
    {
        if ($this->input->post()) {
            if (!has_permission('purchase_order', '', 'create')) {
                access_denied('purchase_order');
            }

            $data = $this->input->post();
            
            $success = $this->purchase_order_model->update($this->input->post(), $id);
                if ($success == true) {
                    $purchase_order = get_table_where('tblpurchase_order',array('id'=>$id),'','row');
                        $purchase = get_items_purchase_new($purchase_order->id_purchases);
                        if($purchase <= 0)
                        {
                            $purchases = get_table_where('tblpurchases',array('id'=>$purchase_order->id_purchases),'','row');
                                $staff_id='1foso';
                                $date=date('Y-m-d H:i:s');
                                $history_status = $purchases->history_status;
                                $history_status.='|'.$staff_id.','.$date;
                            $in = array(
                                'history_status'=>$history_status,
                                'note_cancel' => '',
                                'status' => 4,
                            );
                            $this->db->where('id', $purchase_order->id_purchases);
                            $result = $this->db->update('tblpurchases', $in);
                        }else
                        {
                                $ktr_purchases = get_table_where('tblpurchases',array('id'=>$purchase_order->id_purchases),'','row');
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
                                        $this->db->where('id', $purchase_order->id_purchases);
                                        $this->db->update('tblpurchases', $in);
                                    }
                                } 
                        }
                    $this->load->model('misc_model');
                    $this->misc_model->changeRowNew_model('tblpurchase_order',$id);
                    
                    set_alert('success', _l('ch_updated_successfuly'));
                }
                redirect(admin_url('purchase_order/purchases_detail/' . $id));
        }

        $data['type_of_document'] = 1;

        $title = _l('edit', _l('ch_purchase_order'));
        $data['items'] = $this->purchase_order_model->get($id);
        $data['purchase'] = $this->purchases_model->get_create_purchase_order($data['items']->id_purchases,$id);
        $html='<option></option>';
        foreach ($data['purchase']->items as $key => $value) {
            if($key == 0)
                {
                $html.='<optgroup label="'.$value['name'].'">';
                }else if($value['id'] == 'h')
                {
                    $html.='</optgroup>';
                    $html.='<optgroup label="'.$value['name'].'">'; 
                }else{
                $html.='<option quantity_warehoue="'.$value['quantity_warehoue'].'" data-id='.$value['type_items'].' value="' .$value['id']. '">['.$value['code_item'].'] '.$value['name']. '</option>';
                }
        }
        $html.='</optgroup>';
        $data['html'] = $html;
        $data['idd'] = $id;
        $data['id'] = $data['items']->id_purchases;
        $data['staff'] = get_table_where('tblstaff');
        $data['suppliers'] = get_table_where('tblsuppliers');
        $data['taxes'] = get_taxes_dropdown_template('',0);
        $data['tax'] = get_table_where('tbltaxes');
        $type_items = get_table_where('tbltype_items',array('active'=>1));
        $count = 0;
        $data['type_items'][0] = array('type'=>'-1','name'=>_l('task_list_all'));
        foreach ($type_items as $key => $value) {
            $count++;
            $data['type_items'][$count] = $value;
        }
        $data['title'] = $title;
        $this->load->view('admin/purchase_order/create_detail', $data);
    }

    public function create_detail($id='')
    {
        if ($this->input->post()) {
            if (!has_permission('purchase_order', '', 'create')) {
                access_denied('purchase_order');
            }

            $data = $this->input->post();
            
            if(isset($data['items']) && count($data['items']) > 0)
            {
                $idd = $this->purchase_order_model->add($data);
            }
            if ($idd) {
                $purchase = get_items_purchase_new($id);
                if($purchase <= 0)
                {
                    $purchases = get_table_where('tblpurchases',array('id'=>$id),'','row');
                        $staff_id='1foso';
                        $date=date('Y-m-d H:i:s');
                        $history_status = $purchases->history_status;
                        $history_status.='|'.$staff_id.','.$date;
                    $in = array(
                        'history_status'=>$history_status,
                        'note_cancel' => '',
                        'status' => 4,
                    );
                    $this->db->where('id', $id);
                    $result = $this->db->update('tblpurchases', $in);
                }
                set_alert('success', _l('ch_added_successfuly'));
                redirect(admin_url('purchase_order'));
            }
        }
        $data['type_of_document'] = 1;
        $title = _l('add_new', _l('ch_purchase_order'));
        $data['purchase'] = $this->purchases_model->get_create_purchase_order($id);
        $html='<option></option>';
        foreach ($data['purchase']->items as $key => $value) {
            if($key == 0)
                {
                $html.='<optgroup label="'.$value['name'].'">';
                }else if($value['id'] == 'h')
                {
                    $html.='</optgroup>';
                    $html.='<optgroup label="'.$value['name'].'">'; 
                }else{
                $html.='<option quantity_warehoue="'.$value['quantity_warehoue'].'" data-id='.$value['type_items'].' value="' .$value['id']. '">['.$value['code_item'].'] '.$value['name']. '</option>';
                }
        }
        $html.='</optgroup>';
        $data['html'] = $html;
        $data['id'] = $id;
        $data['idd'] = 0;
        $data['staff'] = get_table_where('tblstaff');
        $data['suppliers'] = get_table_where('tblsuppliers');
        $data['taxes'] = get_taxes_dropdown_template('',0);
        $type_items = get_table_where('tbltype_items',array('active'=>1));
        $count = 0;
        $data['type_items'][0] = array('type'=>'-1','name'=>_l('task_list_all'));
        foreach ($type_items as $key => $value) {
            $count++;
            $data['type_items'][$count] = $value;
        }
        $data['title'] = $title;
        $this->load->view('admin/purchase_order/create_detail', $data);
    }
    public function create_detailquotes($id='')
    {
        if ($this->input->post()) {

            if (!has_permission('purchase_order', '', 'create')) {
                access_denied('purchase_order');
            }

            $data = $this->input->post();
            
            if(isset($data['items']) && count($data['items']) > 0)
            {
                $idd = $this->purchase_order_model->add($data);
            }
            
            if ($idd) {
                // $quotes = get_table_where('tblsupplier_quotes',array('id'=>$id),'','row');
                // $this->db->update('tblpurchase_order',array('id_purchase_proce'=>$quotes->id_purchases),array('id'=>$idd));
                set_status_purchse_order($idd);
                // $quotes = get_table_where('tblsupplier_quotes',array('id'=>$id),'','row');
                // if(!empty($quotes->id_purchases))
                // {
                //     $purchase = get_items_purchase_quotes($quotes->id_purchases);
                //     if($purchase <= 0)
                //     {
                //         $purchases = get_table_where('tblpurchases',array('id'=>$quotes->id_purchases),'','row');
                //             $staff_id='1foso';
                //             $date=date('Y-m-d H:i:s');
                //             $history_status = $purchases->history_status;
                //             $history_status.='|'.$staff_id.','.$date;
                //         $in = array(
                //             'history_status'=>$history_status,
                //             'note_cancel' => '',
                //             'status' => 4,
                //         );
                //         $this->db->where('id', $id);
                //         $result = $this->db->update('tblpurchases', $quotes->id_purchases);
                //     }
                // }
                set_alert('success', _l('ch_added_successfuly'));
                redirect(admin_url('purchase_order'));
            }
        }
        $title = _l('add_new', _l('ch_purchase_order'));
            $data['quotes'] = $this->supplier_quotes_model->get_full_edit($id);
            $data['load_html'] = $this->supplier_quotes_model->get_items_quotes_combobox($id);
            $html='<option></option>';
            foreach ($data['load_html'] as $key => $value) {
                if($key == 0)
                    {
                    $html.='<optgroup label="'.$value['name'].'">';
                    }else if($value['id'] == 'h')
                    {
                        $html.='</optgroup>';
                        $html.='<optgroup label="'.$value['name'].'">'; 
                    }else{
                    $html.='<option quantity_warehoue="'.$value['quantity_warehoue'].'" data-id='.$value['type_items'].' value="' .$value['id']. '">('.$value['code_name'].') '  .$value['name']. '</option>';
                    }
            }
            $html.='</optgroup>';
        $data['html'] = $html; 
        $data['id'] = $id;
        $data['staff'] = get_table_where('tblstaff');
        $data['suppliers'] = get_table_where('tblsuppliers');
        $data['taxes'] = get_taxes_dropdown_template('',0);
        $type_items = get_table_where('tbltype_items',array('active'=>1));
        $count = 0;
        $data['type_items'][0] = array('type'=>'-1','name'=>_l('task_list_all'));
        foreach ($type_items as $key => $value) {
            $count++;
            $data['type_items'][$count] = $value;
        }
        $data['title'] = $title;
        $this->load->view('admin/purchase_order/create_detailquotes', $data);
    }
    public function view_purchase_order($id = '')
    {
        $data['items'] = $this->purchase_order_model->get($id);
        $this->load->view('admin/purchase_order/view_modal',$data);
    }
    public function update_status($value='')
    {
        if ($this->input->post()) {
            $id=$this->input->post('id');
            $checkCancel = get_table_where('tblpurchase_order',array('id'=>$id,'cancel <>'=>0),'','row');
            if($checkCancel) {
                $success = false;
            }
            else {
                $status=$this->input->post('status');
                $purchases = get_table_where('tblpurchase_order',array('id'=>$id),'','row');
                $staff_id=get_staff_user_id();
                $date=date('Y-m-d H:i:s');
                $history_status = $purchases->history_status;
                $history_status.='|'.$staff_id.','.$date;
                $data =array(
                    'history_status'=>$history_status,
                    'status' => ($status+1),
                );
                $success=$this->purchase_order_model->update_status($id,$data);
            }
        }
        if($success) {
            echo json_encode(array(
                'success' => $success,
                'alert_type' => 'success',
                'message' => _l('Xác nhận đề xuất thành công')
            ));
        }
        else
        {
            echo json_encode(array(
                'success' => $success,
                'alert_type' => 'danger',
                'message' => _l('Không thể cập nhật dữ liệu')
            ));
        }
        die;
    }
    public function cancel_status($value='')
    {
        if ($this->input->post()) {
            $id=$this->input->post('id');
            $cancel = get_staff_user_id().','.date('Y-m-d H:i:s');
            $data =array(
                'cancel'=>$cancel
            );
            $this->db->where('id',$id);
            $success = $this->db->update('tblpurchase_order',$data);
        }
        if($success) {
            $this->db->set('id_tickets_priorities',NULL);
            $this->db->where('id',$id);
            $this->db->update('tblpurchase_order');
            echo json_encode(array(
                'success' => $success,
                'message' => _l('Xác nhận hủy đơn hàng')
            ));
        }
        else
        {
            echo json_encode(array(
                'success' => $success,
                'message' => _l('Không thể cập nhật dữ liệu')
            ));
        }
        die;
    }
    public function delete($id)
    {
        if (!is_admin()) {
            access_denied('Delete Purchases order');
        }
        $order = get_table_where('tblimport',array('id_order'=>$id),'','row');
        if(!empty($order))
        {
        echo json_encode(array(
            'alert_type' => 'warning',
            'message' => 'Đã tồn tại phiếu nhập hàng! Không thể xóa!'
            ));die;    
        } 
        $response = $this->purchase_order_model->delete_purchase_order($id);
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
    public function items($type_of_document='',$id='',$type='')
    {
        if($type_of_document == 1)
        {
            $items = $this->purchase_order_model->get_items_purchase($type,$id);
            echo json_encode($items);die; 
        }
    }
    public function get_items($id='',$type='',$type_of_document='',$id_product)
    {
        if($type_of_document == 1)
        {
            $items = $this->purchase_order_model->get_items_purchases_item($type,$id,$id_product);
            if($items->avatar == '') {
                $items->avatar = 'uploads/no-img.jpg';
            }
            echo json_encode($items);die; 
        }
    }
    public function get_items_order($id='',$type='',$id_purchases='')
    {
        $items = $this->purchases_model->get_items_order($id,$type,$id_purchases);
        
        $items->avatar = (!empty($items->avatar)?(file_exists($items->avatar) ? base_url($items->avatar) : (file_exists('uploads/materials/'.$items->avatar) ? base_url('uploads/materials/'.$items->avatar) : (file_exists('uploads/products/'.$items->avatar) ? base_url('uploads/products/'.$items->avatar) : base_url('assets/images/preview-not-available.jpg')))):base_url('assets/images/preview-not-available.jpg'));
        echo json_encode($items);
    }    
    public function items_quote($id='',$type='')
    {
        echo json_encode($this->purchase_order_model->get_items_quote($type,$id));die; 
    }    
    public function test_quantity_all($type='',$type_of_document='',$id='',$id_product='')
    {   
        $type_of_document=$this->input->post('type_of_document');
        $id=$this->input->post('id');
        $id_order=$this->input->post('id_order');
        $test_quantity = 0;
        if($type_of_document == 1)
        {
            
            $product=explode(',', trim($this->input->post('product_id'),','));

            foreach ($product as $key => $v) {
                $product_id=explode('|', $v);
                $data['items'][$key]['quantity']=$this->purchase_order_model->test_quantity_all($product_id[0],$id,$product_id[1]);
                $data['items'][$key]['type'] = $product_id[0];
                $data['items'][$key]['id_product'] = $product_id[1];
                $quantity_old = 0;
                if(!empty($id_order))
                    {
                    $quantity_old = get_table_where('tblpurchase_order_items',array('id_purchase_order'=>$id_order,'product_id'=>$product_id[1],'type'=>$product_id[0]),'','row')->quantity;
                    }
                if($product_id[2] > ($data['items'][$key]['quantity'] + $quantity_old))
                {
                 $test_quantity++;   
                }
            }
        }
  
        $data['test_quantity'] = $test_quantity;
        echo json_encode($data);die; 

    }
    public function test_quantity($type='',$type_of_document='',$id='',$id_product='')
    {   
        $type_of_document=$this->input->post('type_of_document');
        $id=$this->input->post('id');
        $id_order=$this->input->post('id_order');
        $test_quantity = 0;
        if($type_of_document == 1)
        {
            
            $product=explode(',', trim($this->input->post('product_id'),','));

            foreach ($product as $key => $v) {
                $product_id=explode('|', $v);
                $data['items'][$key]['quantity']=$this->purchase_order_model->test_quantity($product_id[0],$id,$product_id[1]);
                $data['items'][$key]['type'] = $product_id[0];
                $data['items'][$key]['id_product'] = $product_id[1];
                $quantity_old = 0;
                if(!empty($id_order))
                    {
                    $quantity_old = get_table_where('tblpurchase_order_items',array('id_purchase_order'=>$id_order,'product_id'=>$product_id[1],'type'=>$product_id[0]),'','row')->quantity_suppliers;
                    $data['items'][$key]['quantity'] = $data['items'][$key]['quantity']  + $quantity_old;
                    }
                if($product_id[2] > ($data['items'][$key]['quantity']))
                {
                 $test_quantity++;   
                }
            }
        }
        $data['test_quantity'] = $test_quantity;
        echo json_encode($data);die; 

    }

    public function print_pdf($id='')
    {
        ob_start();
        $data = new stdClass();
        $dataField = get_table_where('tbl_field_pdf',array('parent_field'=>'purchase_order'),'','row');
        $dataMain = get_table_where('tblpurchase_order',array('id'=>$id),'','row');
        $dataSub = get_table_where('tblpurchase_order_items',array('id_purchase_order'=>$id));
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
        $data->content .= '<span style="text-align: center;">____________________________________________________________________________________________________________________________________________</span><br><br>';
        $data->content .= '<span style="text-align: center;font-size: 20px;font-weight: bold;">ĐƠN ĐẶT HÀNG</span><br><br>';
        $data->content .= '<span style="text-align: right;font-style: italic;">'._l('ch_code_p').': '.$dataMain->prefix.'-'.$dataMain->code.'</span><br>';
        $data->content .= '<span style="text-align: right;font-style: italic;">'._l('ch_date_p').': '._d($dataMain->date).'</span><br><br>';
        $data->content .= '
            <span style="font-weight: bold;">'._l('ch_staff_p').': </span><span>'.get_staff_full_name($dataMain->staff_create).'</span><br>
            <span style="font-weight: bold;">'._l('ch_note_t').': </span><span>'.$dataMain->note.'</span><br><br>
        ';

        $width1 = '';
        $width2 = '';
        $width3 = '';
        $width4 = '';
        $width5 = '';
        $width6 = '';
        $width7 = '';
        $width8 = '';
        $width9 = '';
        $width10 = '';
        $width11 = '';
        $width12 = '';
        $dem_temp = 2;
        if(isset($dataField->arr_field)) {
            $arr = explode(',', $dataField->arr_field);
            foreach ($arr as $key => $value) {
                if($value == 'item_quantity_purchase_order') {
                    $item_quantity_purchase_order = true;
                }
                if($value == 'item_quantity_suppliers_purchase_order') {
                    $item_quantity_suppliers_purchase_order = true;
                }
                if($value == 'item_unit_purchase_order') {
                    $item_unit_purchase_order = true;
                }
                if($value == 'item_price_expected_purchase_order') {
                    $item_price_expected_purchase_order = true;
                }
                if($value == 'item_price_suppliers_purchase_order') {
                    $item_price_suppliers_purchase_order = true;
                }
                if($value == 'item_amount_expected_purchase_order') {
                    $item_amount_expected_purchase_order = true;
                }
                if($value == 'item_promotion_suppliers_purchase_order') {
                    $item_promotion_suppliers_purchase_order = true;
                }
                if($value == 'item_tax_purchase_order') {
                    $item_tax_purchase_order = true;
                }
                if($value == 'item_amount_suppliers_purchase_order') {
                    $item_amount_suppliers_purchase_order = true;
                }
                if($value == 'item_note_purchase_order') {
                    $item_note_purchase_order = true;
                }
            }
            if(isset($item_quantity_purchase_order) && isset($item_quantity_suppliers_purchase_order) && isset($item_unit_purchase_order) && isset($item_price_expected_purchase_order) && isset($item_price_suppliers_purchase_order) && isset($item_amount_expected_purchase_order) && isset($item_promotion_suppliers_purchase_order) && isset($item_tax_purchase_order) && isset($item_amount_suppliers_purchase_order) && isset($item_note_purchase_order)) {
                $width1 = 'width: 5%;';
                $width2 = 'width: 15%;';
                $width3 = 'width: 8%;';
                $width4 = 'width: 8%;';
                $width5 = 'width: 8%;';
                $width6 = 'width: 8%;';
                $width7 = 'width: 8%;';
                $width8 = 'width: 8%;';
                $width9 = 'width: 8%;';
                $width10 = 'width: 8%;';
                $width11 = 'width: 8%;';
                $width12 = 'width: 8%;';
            }
        }
        $table = '
            <table class="table table-bordered" border="1" width="100%">
                <thead>
                    <tr>
                        <td style="'.$width1.'text-align: center;font-weight: bold;">'._l('STT').'</td>
        ';
        $table .= '<td style="'.$width2.'text-align: center;font-weight: bold;">'._l('ch_items_name_t').'</td>';
        
        if(isset($item_quantity_purchase_order)) {
            $table .= '<td style="'.$width3.'text-align: center;font-weight: bold;">'._l('item_quantity').'</td>';
        }
        if(isset($item_quantity_suppliers_purchase_order)) {
            $table .= '<td style="'.$width4.'text-align: center;font-weight: bold;">'._l('quantity_suppliers').'</td>';
        }
        if(isset($item_unit_purchase_order)) {
            $table .= '<td style="'.$width5.'text-align: center;font-weight: bold;">'._l('item_unit').'</td>';
        }
        if(isset($item_price_expected_purchase_order)) {
            $table .= '<td style="'.$width6.'text-align: center;font-weight: bold;">'._l('price_expected').'</td>';
        }
        if(isset($item_price_suppliers_purchase_order)) {
            $table .= '<td style="'.$width7.'text-align: center;font-weight: bold;">'._l('price_suppliers').'</td>';
        }
        if(isset($item_amount_expected_purchase_order)) {
            $table .= '<td style="'.$width8.'text-align: center;font-weight: bold;">'._l('amount_expected_vnd').'</td>';
        }
        if(isset($item_promotion_suppliers_purchase_order)) {
            $table .= '<td style="'.$width9.'text-align: center;font-weight: bold;">'._l('promotion_suppliers').'</td>';
        }
        if(isset($item_tax_purchase_order)) {
            $table .= '<td style="'.$width10.'text-align: center;font-weight: bold;">'._l('tax').'</td>';
        }
        if(isset($item_amount_suppliers_purchase_order)) {
            $table .= '<td style="'.$width11.'text-align: center;font-weight: bold;">'._l('amount_suppliers_vnd').'</td>';
        }
        if(isset($item_note_purchase_order)) {
            $table .= '<td style="'.$width12.'text-align: center;font-weight: bold;">'._l('note').'</td>';
        }
        $table .= '</tr>
                </thead>
                <tbody>';
        $sum_quantity = 0;
        $sum_quantity_suppliers = 0;
        $sum_price_expected = 0;
        $sum_price_suppliers = 0;
        $sum_total_expected = 0;
        $sum_promotion_expected = 0;
        $sum_total_suppliers = 0;
        foreach ($dataSub as $key => $value) {
            $table .= '<tr>';
            $dataItem = $this->invoice_items_model->get_full_item($value['product_id'],$value['type']);
            $table .= '<td style="'.$width1.'text-align: center;">'.++$key.'</td>';
            $table .= '<td style="'.$width2.'text-align: left;">'.$dataItem->name.'</td>';
            
            if(isset($item_quantity_purchase_order)) {
                $table .= '<td style="'.$width3.'text-align: center;">'.number_format($value['quantity']).'</td>';
                $sum_quantity += $value['quantity'];
            }
            if(isset($item_quantity_suppliers_purchase_order)) {
                $table .= '<td style="'.$width4.'text-align: center;">'.number_format($value['quantity_suppliers']).'</td>';
                $sum_quantity_suppliers += $value['quantity_suppliers'];
            }
            if(isset($item_unit_purchase_order)) {
                $table .= '<td style="'.$width5.'text-align: center;">'.$dataItem->unit_name.'</td>';
            }
            if(isset($item_price_expected_purchase_order)) {
                $table .= '<td style="'.$width6.'text-align: center;">'.number_format($value['price_expected']).'</td>';
                $sum_price_expected += $value['price_expected'];
            }
            if(isset($item_price_suppliers_purchase_order)) {
                $table .= '<td style="'.$width7.'text-align: center;">'.number_format($value['price_suppliers']).'</td>';
                $sum_price_suppliers += $value['price_suppliers'];
            }
            if(isset($item_amount_expected_purchase_order)) {
                $table .= '<td style="'.$width8.'text-align: right;">'.number_format($value['total_expected']).'</td>';
                $sum_total_expected += $value['total_expected'];
            }
            if(isset($item_promotion_suppliers_purchase_order)) {
                $table .= '<td style="'.$width9.'text-align: right;">'.number_format($value['promotion_expected']).'</td>';
                $sum_promotion_expected += $value['promotion_expected'];
            }
            if(isset($item_tax_purchase_order)) {
                $table .= '<td style="'.$width10.'text-align: center;">'.number_format($value['tax_rate']).' %</td>';
            }
            if(isset($item_amount_suppliers_purchase_order)) {
                $table .= '<td style="'.$width11.'text-align: right;">'.number_format($value['total_suppliers']).'</td>';
                $sum_total_suppliers += $value['total_suppliers'];
            }
            if(isset($item_note_purchase_order)) {
                $table .= '<td style="'.$width12.'text-align: center;">'.$value['note'].'</td>';
            }
            $table .= '</tr>';
        }
        $table .= '<tr>
                <td colspan="'.$dem_temp.'" style="text-align: center;font-weight: bold;">'._l('invoice_dt_table_heading_amount').'</td>';
        if(isset($item_quantity_purchase_order)) {
            $table .= '<td style="text-align: center;">'.number_format($sum_quantity).'</td>';
        }
        if(isset($item_quantity_suppliers_purchase_order)) {
            $table .= '<td style="text-align: center;">'.number_format($sum_quantity_suppliers).'</td>';
        }
        if(isset($item_unit_purchase_order)) {
            $table .= '<td></td>';
        }
        if(isset($item_price_expected_purchase_order)) {
            $table .= '<td style="text-align: center;">'.number_format($sum_price_expected).'</td>';
        }
        if(isset($item_price_suppliers_purchase_order)) {
            $table .= '<td style="text-align: center;">'.number_format($sum_price_suppliers).'</td>';
        }
        if(isset($item_amount_expected_purchase_order)) {
            $table .= '<td style="text-align: right;">'.number_format($sum_total_expected).'</td>';
        }
        if(isset($item_promotion_suppliers_purchase_order)) {
            $table .= '<td style="text-align: right;">'.number_format($sum_promotion_expected).'</td>';
        }
        if(isset($item_tax_purchase_order)) {
            $table .= '<td></td>';
        }
        if(isset($item_amount_suppliers_purchase_order)) {
            $table .= '<td style="text-align: right;">'.number_format($sum_total_suppliers).'</td>';
        }
        if(isset($item_note_purchase_order)) {
            $table .= '<td></td>';
        }
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
        $pdf      = print_pdf_L($data);
        $type     = 'I';
        $pdf->Output(slug_it('') . '.pdf', $type);
    }
    public function get_items_all($product_id='',$type='')
    {
      echo json_encode($this->invoice_items_model->get_full_item($product_id,$type));die;
    }
    //tạo đơn hàng tổng từ ycmh
    public function create_detail_all()
    {
        $id = $this->input->get('id');
        if ($this->input->post()) {
            if (!has_permission('purchase_order', '', 'create')) {
                access_denied('purchase_order');
            }

            $data = $this->input->post();
            
            if(isset($data['items']) && count($data['items']) > 0)
            {
                $id = $this->purchase_order_model->add_all($data);
            }
            if ($id) {
                set_alert('success', _l('ch_added_successfuly'));
                redirect(admin_url('purchase_order'));
            }
        }
        $data['type_of_document'] = 1;
        $title = _l('add_new', _l('ch_purchase_order'));
        $data['purchase'] = $this->purchases_model->get_items_purchase_order_all($id);

        $html='<option></option>';
        foreach ($data['purchase'] as $key => $value) {
            if($key == 0)
                {
                $html.='<optgroup label="'.$value['name'].'">';
                }else if($value['id'] == 'h')
                {
                    $html.='</optgroup>';
                    $html.='<optgroup label="'.$value['name'].'">'; 
                }else{
                $html.='<option quantity_warehoue="'.$value['quantity_warehoue'].'" data-id='.$value['type_items'].' value="' .$value['id']. '">['.$value['code_item'].'] '.$value['name']. '</option>';
                }
        }
        $html.='</optgroup>';
        $data['html'] = $html;
        $data['id'] = $id;
        $data['idd'] = 0;
        $data['staff'] = get_table_where('tblstaff');
        $data['suppliers'] = get_table_where('tblsuppliers');
        $data['taxes'] = get_taxes_dropdown_template('',0);
        $type_items = get_table_where('tbltype_items',array('active'=>1));
        $count = 0;
        $data['type_items'][0] = array('type'=>'-1','name'=>_l('task_list_all'));
        foreach ($type_items as $key => $value) {
            $count++;
            $data['type_items'][$count] = $value;
        }
        $data['title'] = $title;
        $this->load->view('admin/purchase_order/create_detail_all', $data);
    }
        public function purchases_detail_all($id='')
    {
        if ($this->input->post()) {
            if ($id == '') {

                if (!has_permission('purchase_order', '', 'create')) {
                    access_denied('purchase_order');
                }

                $data = $this->input->post();
                
                if(isset($data['items']) && count($data['items']) > 0)
                {
                    $id = $this->purchase_order_model->add($data);
                }
                
                if ($id) {
                    set_alert('success', _l('ch_added_successfuly'));
                    redirect(admin_url('purchase_order'));
                }
            } else {
                if (!has_permission('purchase_order', '', 'edit')) {
                        access_denied('purchase_order');
                }
                $success = $this->purchase_order_model->update_all($this->input->post(), $id);
                if ($success == true) {
                    $this->load->model('misc_model');
                    $this->misc_model->changeRowNew_model('tblpurchase_order',$id);
                    
                    set_alert('success', _l('ch_updated_successfuly'));
                }
                redirect(admin_url('purchase_order/purchases_detail_all/' . $id));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('ch_purchase_order'));

        } else {
            $title = _l('edit', _l('ch_purchase_order'));
            $data['items'] = $this->purchase_order_model->get($id);
        }
        $data['staff'] = get_table_where('tblstaff');
        $data['suppliers'] = get_table_where('tblsuppliers');
        $data['tax'] = get_table_where('tbltaxes');
        $data['taxes'] = get_taxes_dropdown_template('',0);
        $type_items = get_table_where('tbltype_items',array('active'=>1));
        $count = 0;
        $data['type_items'][0] = array('type'=>'-1','name'=>_l('task_list_all'));
        foreach ($type_items as $key => $value) {
            $count++;
            $data['type_items'][$count] = $value;
        }
        $data['title'] = $title;
        $this->load->view('admin/purchase_order/detail_all', $data);
    } 
    public function payment_all()
    {
        $data['costs'] = array();
        $this->costs_model->get_by_id(0,$data['costs']);
        $data['code'] = get_option('prefix_pay_slip').'-'.sprintf('%06d', ch_getMaxID('id', 'tblpay_slip') + 1);
        $datas = $this->input->post();
        $data['total'] = 0;
        $data['id_old'] = trim($datas['ids'],',');
        foreach (explode(',', trim($datas['ids'],',')) as $key => $value) {
           $import = get_table_where('tblpurchase_order',array('id'=>$value),'','row');
           $data['total']+=$import->totalAll_suppliers - $import->price_other_expenses - $import->amount_paid;
        }
        $data['payment_modes'] = get_table_where('tblpayment_modes',array('active'=>1));
        $this->load->view('admin/import/payment_all_modal',$data);
    }    
    public function payment($id='')
    {
        $data['costs'] = array();
        $this->costs_model->get_by_id(0,$data['costs']);
        $data['id_import'] = $id;
        $data['code'] = get_option('prefix_pay_slip').'-'.sprintf('%06d', ch_getMaxID('id', 'tblpay_slip') + 1);
        $data['import'] = get_table_where('tblpurchase_order',array('id'=>$id),'','row');
        $data['payment_modes'] = get_table_where('tblpayment_modes',array('active'=>1));
        $this->load->view('admin/import/payment_modal',$data);
    }
    public function pay_slip_all()
    {
        $success = false;
        $alert_type = 'warning';
        $message    = _l('ch_pay_false');
        if ($this->input->post()) {
            $data = $this->input->post();
            $_data['day_vouchers'] = to_sql_date($data['day_vouchers']);
            $_data['date'] = date('Y-m-d H:i:s');
            $_data['id_costs'] = $data['id_costs'];
            $_data['staff_id'] = get_staff_user_id();
            $_data['receiver'] = $data['receiver'];
            $_data['payment_mode'] = $data['payment_mode'];
            $_data['payment'] = str_replace(',', '', $data['payment']);
            $_data['total'] = str_replace(',', '', $data['total']);
            $_data['note'] = $data['note'];
            $_data['id_supplierss']= $data['id_supplierss'];
            $_data['type'] = 2;
            $_data['id_old'] =$data['id_old'];
            $_data['prefix'] = get_option('prefix_pay_slip');
            $_data['code'] = sprintf('%06d', ch_getMaxID('id', 'tblpay_slip') + 1);
            $this->db->insert('tblpay_slip',$_data);
            $id_pay = $this->db->insert_id();
            if($id_pay)
            {   
                $id_old = explode(',', $data['id_old']);
                foreach ($id_old as $key => $value) {
                    $__data['id_old'] = $value; 
                    $__data['id_pay_slip'] = $id_pay;
                    $__data['type'] = 2;
                    $import = get_table_where('tblpurchase_order',array('id'=>$value),'','row');
                    $__data['total'] = $import->totalAll_suppliers - $import->price_other_expenses;
                    $__data['payment'] = $import->totalAll_suppliers  - $import->price_other_expenses;
                    $this->db->insert('tblpay_slip_detail',$__data);
                    
                    $this->db->update('tblpurchase_order',array('amount_paid'=>($import->totalAll_suppliers-$import->price_other_expenses),'money_arises'=>($import->totalAll_suppliers-$import->price_other_expenses-$import->amount_paid),'status_pay'=>2),array('id'=>$import->id));    
                }
            $success = true;
            $alert_type = 'success';
            $message    = _l('ch_pay_succes');
            }
        }
        echo json_encode(array(
            'success' => $success, 
            'alert_type' => $alert_type,
            'message' => $message
        ));die;
    } 
    public function pay_slip($id='')
    {
        $success = false;
        $alert_type = 'warning';
        $message    = _l('ch_added_successfuly_not');
        if ($this->input->post()) {
            $data = $this->input->post();
            $_data['day_vouchers'] = to_sql_date($data['day_vouchers']);
            $_data['date'] = date('Y-m-d H:i:s');
            $_data['staff_id'] = get_staff_user_id();
            $_data['receiver'] = $data['receiver'];
            $_data['id_costs'] = $data['id_costs'];
            $_data['payment_mode'] = $data['payment_mode'];
            $_data['payment'] = str_replace(',', '', $data['payment']);
            $_data['total'] = str_replace(',', '', $data['total']);
            $_data['note'] = $data['note'];
            $imports = get_table_where('tblpurchase_order',array('id'=>$id),'','row');
            $_data['id_supplierss'] = $imports->suppliers_id;
            $_data['type'] = 2;
            $_data['id_old'] = $id;
            $_data['prefix'] = get_option('prefix_pay_slip');
            $_data['code'] = sprintf('%06d', ch_getMaxID('id', 'tblpay_slip') + 1);
            $this->db->insert('tblpay_slip',$_data);
            $id_pay = $this->db->insert_id();
            if($id_pay)
            {
                $__data['id_old'] = $id; 
                $__data['id_pay_slip'] = $id_pay;
                $__data['type'] = 2;
                $__data['total'] = str_replace(',', '', $data['total']);
                $__data['payment'] = str_replace(',', '', $data['payment']);
                $this->db->insert('tblpay_slip_detail',$__data);
                $import = get_table_where('tblpurchase_order',array('id'=>$id),'','row');
                $amount_paid =  $import->amount_paid + $__data['payment'];
                if(($amount_paid + $import->price_other_expenses) == $import->totalAll_suppliers)
                {
                    $status = 2;
                }else
                {
                    $status = 1;
                }
                $this->db->update('tblpurchase_order',array('amount_paid'=>$amount_paid,'status_pay'=>$status),array('id'=>$import->id));
            $success = true;
            $alert_type = 'success';
            $message    = _l('ch_added_successfuly');
            }
        }
        echo json_encode(array(
            'success' => $success, 
            'alert_type' => $alert_type,
            'message' => $message
        ));die;
    }
    public function count_all()
    {
        $count = get_table_where_select('count(*) as alls','tblpurchase_order',array(),'','row');
        $status0 = get_table_where_select('count(*) as status0','tblpurchase_order',array('status'=>1),'','row');
        $status1 = get_table_where_select('count(*) as status1','tblpurchase_order',array('status'=>2),'','row');
        $status2 = get_table_where_select('count(*) as status2','tblpurchase_order',array('status'=>3),'','row');
        $red_invoice = get_table_where_select('count(*) as red_invoice','tblpurchase_order',array('red_invoice !='=>0),'','row');
        $red_invoice_no = get_table_where_select('count(*) as red_invoice_no','tblpurchase_order',array('red_invoice'=>0),'','row');
        $this->db->select('count(*) as status_pay');
        $this->db->where('((tblpurchase_order.status_pay = 2 AND tblpurchase_order.red_invoice = 0 ) or (tblpurchase_order.red_invoice != 0 AND tblpurchase_invoice.status = 2))');
        $this->db->join('tblpurchase_invoice','tblpurchase_invoice.id = tblpurchase_order.red_invoice','LEFT');
        $status_pay = $this->db->get('tblpurchase_order')->row();

        $this->db->select('count(*) as status_pay1');
        $this->db->where('((tblpurchase_order.status_pay = 1 AND tblpurchase_order.red_invoice = 0 ) or (tblpurchase_order.red_invoice != 0 AND tblpurchase_invoice.status = 1))');
        $this->db->join('tblpurchase_invoice','tblpurchase_invoice.id = tblpurchase_order.red_invoice','LEFT');
        $status_pay1 = $this->db->get('tblpurchase_order')->row();

        $this->db->select('count(*) as status_pay0');
        $this->db->where('((tblpurchase_order.status_pay = 0 AND tblpurchase_order.red_invoice = 0 ) or (tblpurchase_order.red_invoice != 0 AND tblpurchase_invoice.status = 0))');
        $this->db->join('tblpurchase_invoice','tblpurchase_invoice.id = tblpurchase_order.red_invoice','LEFT');
        $status_pay0 = $this->db->get('tblpurchase_order')->row();

        $data['all'] = $count->alls;
        $data['status0'] = $status0->status0;
        $data['status1'] = $status1->status1;
        $data['status2'] = $status2->status2;
        $data['red_invoice'] = $red_invoice->red_invoice;
        $data['red_invoice_no'] = $red_invoice_no->red_invoice_no;
        $data['status_pay'] = $status_pay->status_pay;
        $data['status_pay0'] = $status_pay0->status_pay0;
        $data['status_pay1'] = $status_pay1->status_pay1;

        echo json_encode($data);
    }
    
}