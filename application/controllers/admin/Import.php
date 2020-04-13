<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Import extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('invoice_items_model');
        $this->load->model('purchase_order_model');
        $this->load->model('import_model');
        $this->load->model('costs_model');
    }

    public function index()
    {
        if (!has_permission('import', '', 'view')) {
                access_denied('import');
        }
        // $data['suppliers'] = get_table_where('tblsuppliers');
        $this->db->select('tblsuppliers.*');
        $this->db->join('tblsuppliers','tblsuppliers.id = tblimport.suppliers_id');
        $this->db->group_by('tblsuppliers.id');
        $data['suppliers'] = $this->db->get('tblimport')->result_array();

        $this->db->select('tblstaff.staffid, CONCAT(firstname," ",lastname) as name');
        $this->db->from('tblstaff');
        $data['dataStaff'] = $this->db->get()->result_array();

        $this->db->select('tblsuppliers.id, tblsuppliers.company, CONCAT(prefix,"-",code) as code');
        $this->db->from('tblsuppliers');
        $data['dataSupplier'] = $this->db->get()->result_array();
        
        $data['title']          = _l('ch_imports');
        $this->load->view('admin/import/manage', $data);
    }
    public function table()
    {
        if (!has_permission('import', '', 'view')) {
                ajax_access_denied();
        }
        $this->app->get_table_data('table_import');
    }
    public function get_invoice($id='')
    {
       $purchase_invoice = get_table_where('tblpurchase_invoice',array('id_import'=>$id),'','row');
       $purchase_invoice->date_invoice = _d($purchase_invoice->date_invoice);

       echo  json_encode($purchase_invoice);die;
    }
    public function detail($id = '')
    {
        if (!has_permission('import', '', 'create')) {
                ajax_access_denied();
        }
        if ($this->input->post()) {
            if ($id == '') {

                if (!has_permission('import', '', 'create')) {
                    access_denied('import');
                }

                $data                 = $this->input->post();
                
                if(isset($data['items']) && count($data['items']) > 0)
                {
                    $id = $this->import_model->add($data);
                }
                
                if ($id) {
                    set_alert('success', _l('ch_added_successfuly'));
                    redirect(admin_url('import'));
                }
            } else {
                if (!has_permission('import', '', 'edit')) {
                        access_denied('import');
                }
                $success = $this->import_model->update($this->input->post(), $id);
                if ($success == true) {
                    set_alert('success', _l('ch_updated_successfuly'));
                }
                redirect(admin_url('import/detail/' . $id));
            }
        }
        if($id != '')
        {
            $data['title']          = _l('ch_edit_imports'); 
            $data['items'] = $this->import_model->get($id);
        }else
        {
            $data['title']          = _l('ch_add_imports');  
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
        
        $this->load->view('admin/import/detail', $data);
    }
    public function test_quantity()
    {   
        $type_of_document=$this->input->post('type_of_document');
        $id=$this->input->post('id');
        $id_import=$this->input->post('id_import');
        $test_quantity = 0;
        if($type_of_document == 1)
        {
            
            $product=explode(',', trim($this->input->post('product_id'),','));
            foreach ($product as $key => $v) {
                $product_id=explode('|', $v);
                $data['items'][$key]['quantity']=$this->purchase_order_model->sum_quantity_import($product_id[0],$id,$product_id[1]);
                if($data['items'][$key]['quantity'] != NULL)
                {
                    $data['items'][$key]['type'] = $product_id[0];
                    $data['items'][$key]['id_product'] = $product_id[1];
                    $quantity = get_table_where('tblpurchase_order_items',array('product_id'=>$product_id[1],'type'=>$product_id[0],'id_purchase_order'=>$id),'','row')->quantity_suppliers;
                    $quantityss = ($quantity - $data['items'][$key]['quantity']);
                    $quantity_old = 0;
                    if(!empty($id_import))
                    {
                    $quantity_old = get_table_where('tblimport_items',array('id_import'=>$id_import,'product_id'=>$product_id[1],'type'=>$product_id[0]),'','row')->quantity_net;
                    }
                    if($product_id[2] > ($quantity + $quantity_old - $data['items'][$key]['quantity']))
                    {
                     $test_quantity++;   
                    }
                    $data['items'][$key]['quantity'] = $quantityss;
                }
            }
        }
        $data['test_quantity'] = $test_quantity;
        echo json_encode($data);die; 

    }
    public function create_detail($id='')
    {
        if (!has_permission('import', '', 'create')) {
                ajax_access_denied();
        }
        if ($this->input->post()) {
                if (!has_permission('import', '', 'create')) {
                    access_denied('import');
                }

                $data                 = $this->input->post();
                
                if(isset($data['items']) && count($data['items']) > 0)
                {
                    $idd = $this->import_model->add($data);
                }
                
                if ($idd) {
                    $count_items_import = get_items_import($id);
                    if($count_items_import == 0)
                    {
                        $cancels = '1foso,'.date('Y-m-d H:i:s');
                        $cancel =array(
                            'cancel'=>$cancels
                        );
                        $this->db->where('id',$id);
                        $this->db->update('tblpurchase_order',$cancel);    
                    }
                    set_alert('success', _l('ch_added_successfuly'));
                    redirect(admin_url('import'));
                }
        }
        $data['id_import']          = 0;
        $data['title']          = _l('ch_add_imports');  
        $data['purchase_order'] = $this->purchase_order_model->get_purchase_order($id);
        $purchase_order = $this->purchase_order_model->get_create_purchase_order_import($id);
        $data['items_purchase_order'] = $this->purchase_order_model->get_items_purchase_order_import_v2($id);
        $html='<option></option>';
        foreach ($purchase_order->items as $key => $value) {
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
        $data['html']          = $html;
        $data['id']          = $id;
        $data['type_of_document']          = 1;
        $data['taxes'] = get_taxes_dropdown_template('',0);
        $data['tax'] = get_table_where('tbltaxes');
        $type_items = get_table_where('tbltype_items',array('active'=>1));
        $count = 0;
        $data['type_items'][0] = array('type'=>'-1','name'=>_l('task_list_all'));
        foreach ($type_items as $key => $value) {
            $count++;
            $data['type_items'][$count] = $value;
        }
        $order = get_table_where('tblpurchase_order',array('id'=>$id),'','row');
        $data['type_plan'] = false;
        if($order->type_plan)
        {
        $data['type_plan'] = true;    
        }
        $data['suppliers'] = get_table_where('tblsuppliers');
        $data['warehouse'] = get_table_where('tblwarehouse');
        $data['localtion_warehouses'] = array();
        
        $this->load->view('admin/import/create_detail', $data);
    }
    public function detail_order($id='')
    {
        if (!has_permission('import', '', 'create')) {
                ajax_access_denied();
        }
        if ($this->input->post()) {
                if (!has_permission('import', '', 'create')) {
                    access_denied('import');
                }

                $data                 = $this->input->post();
                
               
                $success = $this->import_model->update($this->input->post(), $id);
                if ($success == true) {
                    set_alert('success', _l('ch_updated_successfuly'));
                }
                redirect(admin_url('import/detail_order/' . $id));
                
                
        }
        if($id != '')
        {
            $data['title']          = _l('ch_edit_imports'); 
            $data['items'] = $this->import_model->get($id);
            $data['purchase_order'] = $this->purchase_order_model->get($data['items']->id_order);
        } 
        $data['id']          = $data['items']->id_order;
        $data['id_import']          = $id;
        $data['type_of_document']          = 1;
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
        $data['type_plan'] = false;
        if($data['items']->type_plan == 1)
        {
            $data['type_plan'] = true;
        }
        $purchase_order = $this->purchase_order_model->get_create_purchase_order_import($data['items']->id_order,$id);
        $html='<option></option>';
        foreach ($purchase_order->items as $key => $value) {
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
        $data['html']          = $html;
        $this->load->view('admin/import/create_detail', $data);
    }
    public function get_items($id='',$type='')
    {
        $data = $this->invoice_items_model->get_full_item($id,$type);
        $data->html = format_item_color($id,$type);
            $data->avatar = (!empty($data->avatar)?(file_exists($data->avatar) ? base_url($data->avatar) : (file_exists('uploads/materials/'.$data->avatar) ? base_url('uploads/materials/'.$data->avatar) : (file_exists('uploads/products/'.$data->avatar) ? base_url('uploads/products/'.$data->avatar) : base_url('assets/images/preview-not-available.jpg')))):base_url('assets/images/preview-not-available.jpg'));
        
        echo json_encode($data);
    }
    public function get_items_order($id='',$type='',$id_order='')
    {
        $data = $this->purchase_order_model->get_items_import_order($id,$type,$id_order);
        if($data->avatar)
        {
            $data->avatar = (file_exists($data->avatar) ? base_url($data->avatar) : (file_exists('uploads/materials/'.$data->avatar) ? base_url('uploads/materials/'.$data->avatar) : (file_exists('uploads/products/'.$data->avatar) ? base_url('uploads/products/'.$data->avatar) : base_url('assets/images/preview-not-available.jpg'))));
        }else
        {
            $data->avatar = base_url('assets/images/preview-not-available.jpg');
        }
        
        echo json_encode($data);
    }    
    public function getLocaltion_warehouses($warehouse_id = '')
    {
        echo json_encode(get_table_where('tbllocaltion_warehouses',array('warehouse'=>$warehouse_id)));
    }
    public function views_import($id = '')
    {
        $data['items'] = $this->import_model->get($id);
        $data['warehouse_name'] = get_table_where('tblwarehouse',array('id'=>$data['items']->warehouse_id),'','row');
        $this->load->view('admin/import/view_modal',$data);
    }
    public function delete($id)
    {
        if (!is_admin()) {
            access_denied('Delete Import');
        }
        $response = $this->import_model->delete($id);
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
    public function update_status($value='')
    {
        if ($this->input->post()) {
            $id=$this->input->post('id');
            $status=$this->input->post('status');
            $import = get_table_where('tblimport',array('id'=>$id),'','row');
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
            $success=$this->import_model->update_status($id,$data);
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
    public function confirm_warehous()
    {
        $id=$this->input->post('id');
        $test_quantity = get_table_where('tblwarehouse_product',array('import_id'=>$id,'quantity_export >'=>0,'type_export'=>1),'','row');
        $import = get_table_where('tblimport',array('id'=>$id),'','row');               
        $warehouseman_id=$this->input->post('warehouseman_id'); 
        if (!$id) {
            die('ch_no_items');
        }

        $data=array(
            'warehouseman_id'=>get_staff_user_id(),
            'warehouseman_date'=>date('Y-m-d H:i:s')
        );
        if($warehouseman_id)
        {   
            if(!empty($test_quantity))
            {
                echo json_encode(array(
                'alert_type' => 'warning',
                'message' => _l('ch_cance_confirm_export_warehouse')
                ));die;
            }
            if(empty($import->warehouseman_id))
            {
                echo json_encode(array(
                'alert_type' => 'warning',
                'message' => _l('ch_exsit_cancel_confirm_warehouse')
                ));die;
            }
            $data=array(
                'warehouseman_id'=>NULL,
                'warehouseman_date'=>NULL
            );
        }else
        {
            if(!empty($import->warehouseman_id))
            {
                echo json_encode(array(
                'alert_type' => 'warning',
                'message' => _l('ch_exsit_confirm_warehouse')
                ));die;
            }
        }
        $success    = $this->db->update('tblimport',$data,array('id'=>$id));
        $alert_type = 'warning';
        $message    = _l('ch_no_successful_approval');
        if($warehouseman_id)
        {
            $message    = _l('ch_no_successful_approval_cance');
        }
        if ($success) {
            $alert_type = 'success';
            $message    = _l('ch_successful_approval');
            if($warehouseman_id)
            {
                $message    = _l('ch_successful_approval_cance');
            }
            if(empty($warehouseman_id))
            {
                log_activity('Warehouse items approved [ID Import: ' . $id);
                $this->import_model->increaseWarehouse($id);
            }
            else
            {   
                log_activity('Warehouse items cancel approved [ID Import: ' . $id);
                $this->import_model->decreaseWarehouse($id);
            }
        }
        echo json_encode(array(
            'alert_type' => $alert_type,
            'message' => $message
        ));
    }

    public function print_pdf($id='')
    {
        ob_start();
        $data = new stdClass();
        $dataField = get_table_where('tbl_field_pdf',array('parent_field'=>'import'),'','row');
        $dataMain = get_table_where('tblimport',array('id'=>$id),'','row');
        $dataSub = get_table_where('tblimport_items',array('id_import'=>$id));
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
        $data->content .= '<span style="text-align: center;font-size: 20px;font-weight: bold;">NHẬP HÀNG</span><br><br>';
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
        $dem_temp = 2;
        if(isset($dataField->arr_field)) {
            $arr = explode(',', $dataField->arr_field);
            foreach ($arr as $key => $value) {
                if($value == 'item_warehouse_localtion_import') {
                    $item_warehouse_localtion_import = true;
                    $dem_temp++;
                }
                if($value == 'item_unit_import') {
                    $item_unit_import = true;
                    $dem_temp++;
                }
                if($value == 'item_quantity_import') {
                    $item_quantity_import = true;
                }
                if($value == 'item_quantity_confirm_import') {
                    $item_quantity_confirm_import = true;
                }
                if($value == 'item_price_import') {
                    $item_price_import = true;
                }
                if($value == 'item_promotion_suppliers_import') {
                    $item_promotion_suppliers_import = true;
                }
                if($value == 'item_tax_import') {
                    $item_tax_import = true;
                }
                if($value == 'item_invoice_total_import') {
                    $item_invoice_total_import = true;
                }
                if($value == 'item_note_import') {
                    $item_note_import = true;
                }
            }
            if(isset($item_warehouse_localtion_import) && isset($item_unit_import) && isset($item_quantity_import) && isset($item_quantity_confirm_import) && isset($item_price_import) && isset($item_promotion_suppliers_import) && isset($item_tax_import) && isset($item_invoice_total_import) && isset($item_note_import)) {
                $width1 = 'width: 5%;';
                $width2 = 'width: 16%;';
                $width3 = 'width: 13%;';
                $width4 = 'width: 7%;';
                $width5 = 'width: 7%;';
                $width6 = 'width: 7%;';
                $width7 = 'width: 9%;';
                $width8 = 'width: 9%;';
                $width9 = 'width: 5%;';
                $width10 = 'width: 13%;';
                $width11 = 'width: 9%;';
            }
        }
        $table = '
            <table class="table table-bordered" border="1" width="100%">
                <thead>
                    <tr>
                        <td style="'.$width1.'text-align: center;font-weight: bold;">'._l('STT').'</td>
        ';
        $table .= '<td style="'.$width2.'text-align: center;font-weight: bold;">'._l('ch_items_name_t').'</td>';
        
        if(isset($item_warehouse_localtion_import)) {
            $table .= '<td style="'.$width3.'text-align: center;font-weight: bold;">'._l('warehouse_localtion').'</td>';
        }
        if(isset($item_unit_import)) {
            $table .= '<td style="'.$width4.'text-align: center;font-weight: bold;">'._l('item_unit').'</td>';
        }
        if(isset($item_quantity_import)) {
            $table .= '<td style="'.$width5.'text-align: center;font-weight: bold;">'._l('item_quantity').'</td>';
        }
        if(isset($item_quantity_confirm_import)) {
            $table .= '<td style="'.$width6.'text-align: center;font-weight: bold;">'._l('item_quantity_confirm').'</td>';
        }
        if(isset($item_price_import)) {
            $table .= '<td style="'.$width7.'text-align: center;font-weight: bold;">'._l('tnh_price_import').'</td>';
        }
        if(isset($item_promotion_suppliers_import)) {
            $table .= '<td style="'.$width8.'text-align: center;font-weight: bold;">'._l('promotion_suppliers').'</td>';
        }
        if(isset($item_tax_import)) {
            $table .= '<td style="'.$width9.'text-align: center;font-weight: bold;">'._l('tax').'</td>';
        }
        if(isset($item_invoice_total_import)) {
            $table .= '<td style="'.$width10.'text-align: center;font-weight: bold;">'._l('invoice_total').'</td>';
        }
        if(isset($item_note_import)) {
            $table .= '<td style="'.$width11.'text-align: center;font-weight: bold;">'._l('note').'</td>';
        }
        $table .= '</tr>
                </thead>
                <tbody>';
        $sum_quantity = 0;
        $sum_quantity_net = 0;
        $sum_price = 0;
        $sum_promotion_suppliers = 0;
        $sum_amount = 0;
        foreach ($dataSub as $key => $value) {
            $table .= '<tr>';
            $dataItem = $this->invoice_items_model->get_full_item($value['product_id'],$value['type']);
            $dataLocaltion = get_table_where('tbllocaltion_warehouses',array('id'=>$value['localtion_warehouses_id']),'','row');

            $table .= '<td style="'.$width1.'text-align: center;">'.++$key.'</td>';
            $table .= '<td style="'.$width2.'text-align: left;">'.$dataItem->name.'</td>';
            
            if(isset($item_warehouse_localtion_import)) {
                if(!empty($dataLocaltion)) {
                    // $name_parent = str_replace("<i class='fa fa-caret-right text-danger' aria-hidden='true'>","a",$dataLocaltion->name_parent);
                    $table .= '<td style="'.$width3.'text-align: center;">'.$dataLocaltion->name_parent.'</td>';
                }
                else {
                    $table .= '<td></td>';
                }
            }
            if(isset($item_unit_import)) {
                $table .= '<td style="'.$width4.'text-align: center;">'.$dataItem->unit_name.'</td>';
            }
            if(isset($item_quantity_import)) {
                $table .= '<td style="'.$width5.'text-align: center;">'.number_format($value['quantity']).'</td>';
                $sum_quantity += $value['quantity'];
            }
            if(isset($item_quantity_confirm_import)) {
                $table .= '<td style="'.$width6.'text-align: center;">'.number_format($value['quantity_net']).'</td>';
                $sum_quantity_net += $value['quantity_net'];
            }
            if(isset($item_price_import)) {
                $table .= '<td style="'.$width7.'text-align: right;">'.number_format($value['price']).'</td>';
                $sum_price += $value['price'];
            }
            if(isset($item_promotion_suppliers_import)) {
                $table .= '<td style="'.$width8.'text-align: right;">'.number_format($value['promotion_suppliers']).'</td>';
                $sum_promotion_suppliers += $value['promotion_suppliers'];
            }
            if(isset($item_tax_import)) {
                $table .= '<td style="'.$width9.'text-align: center;">'.number_format($value['tax_rate']).' %</td>';
            }
            if(isset($item_invoice_total_import)) {
                $table .= '<td style="'.$width10.'text-align: right;">'.number_format($value['amount']).'</td>';
                $sum_amount += $value['amount'];
            }
            if(isset($item_note_import)) {
                $table .= '<td style="'.$width11.'text-align: center;">'.$value['note'].'</td>';
            }
            $table .= '</tr>';
        }
        $table .= '<tr>
                <td colspan="'.$dem_temp.'" style="text-align: center;font-weight: bold;">'._l('invoice_dt_table_heading_amount').'</td>';
        if(isset($item_quantity_import)) {
            $table .= '<td style="text-align: center;">'.number_format($sum_quantity).'</td>';
        }
        if(isset($item_quantity_confirm_import)) {
            $table .= '<td style="text-align: center;">'.number_format($sum_quantity_net).'</td>';
        }
        if(isset($item_price_import)) {
            $table .= '<td style="text-align: right;">'.number_format($sum_price).'</td>';
        }
        if(isset($item_promotion_suppliers_import)) {
            $table .= '<td style="text-align: right;">'.number_format($sum_promotion_suppliers).'</td>';
        }
        if(isset($item_tax_import)) {
            $table .= '<td></td>';
        }
        if(isset($item_invoice_total_import)) {
            $table .= '<td style="text-align: right;">'.number_format($sum_amount).'</td>';
        }
        if(isset($item_note_import)) {
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
    public function payment_all()
    {
        $data['costs'] = array();
        $this->costs_model->get_by_id(0,$data['costs']);
        $data['code'] = get_option('prefix_pay_slip').'-'.sprintf('%06d', ch_getMaxID('id', 'tblpay_slip') + 1);
        $datas = $this->input->post();
        $data['total'] = 0;
        $data['id_old'] = trim($datas['ids'],',');
        foreach (explode(',', trim($datas['ids'],',')) as $key => $value) {
           $import = get_table_where('tblimport',array('id'=>$value),'','row');
           $data['total']+=$import->total - $import->price_other_expenses - $import->amount_paid;
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
        $data['import'] = get_table_where('tblimport',array('id'=>$id),'','row');
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
                    $import = get_table_where('tblimport',array('id'=>$value),'','row');
                    $__data['total'] = $import->total - $import->price_other_expenses;
                    $__data['payment'] = $import->total  - $import->price_other_expenses;
                    $this->db->insert('tblpay_slip_detail',$__data);
                    
                    $this->db->update('tblimport',array('amount_paid'=>($import->total-$import->price_other_expenses),'money_arises'=>($import->total-$import->price_other_expenses-$import->amount_paid),'status_pay'=>2),array('id'=>$import->id));    
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
            $imports = get_table_where('tblimport',array('id'=>$id),'','row');
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
                $import = get_table_where('tblimport',array('id'=>$id),'','row');
                $amount_paid =  $import->amount_paid + $__data['payment'];
                if(($amount_paid + $import->price_other_expenses) == $import->total)
                {
                    $status = 2;
                }else
                {
                    $status = 1;
                }
                $this->db->update('tblimport',array('amount_paid'=>$amount_paid,'status_pay'=>$status),array('id'=>$import->id));
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
}