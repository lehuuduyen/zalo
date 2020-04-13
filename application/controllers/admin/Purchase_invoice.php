<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Purchase_invoice extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('costs_model');
    }
    public function index()
    {
        if (!has_permission('purchase_invoice', '', 'view')) {
                access_denied('purchase_invoice');
        }
        // $data['suppliers'] = get_table_where('tblsuppliers');
        $this->db->select('tblsuppliers.*');
        $this->db->join('tblsuppliers','tblsuppliers.id = tblpurchase_invoice.id_supplier');
        $this->db->group_by('tblsuppliers.id');
        $data['suppliers'] = $this->db->get('tblpurchase_invoice')->result_array();

        $data['title']          = _l('ch_purchase_invoice');
        $this->load->view('admin/purchase_invoice/manage', $data);
    }
    public function table()
    {
        if (!has_permission('purchase_invoice', '', 'view')) {
                ajax_access_denied();
        }
        $this->app->get_table_data('purchase_invoice');
    }
    public function update_link($id='')
    {
        $alert_type = 'warning';
        $message    = _l('cong_update_false');
       if ($this->input->post()) {
        if($this->db->update('tblpurchase_invoice',array('link'=>$this->input->post('link')),array('id'=>$id)))
        {
            $alert_type = 'success';
            $message    = _l('cong_update_true');
        }
        }
        echo json_encode(array(
            'alert_type' => $alert_type,
            'message' => $message
        ));die;
    }
    public function electronic_bill($id='')
    {
        $data['id_invoice'] = $id;
        $data['invoice'] = get_table_where('tblpurchase_invoice',array('id'=>$id),'','row');
        $data['invoice']->attachments = $this->get_invoice_attachments('', $id);
        $this->load->view('admin/purchase_invoice/electronic_bill',$data);
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
           $invoice = get_table_where('tblpurchase_invoice',array('id'=>$value),'','row');
           $data['total']+=$invoice->total_price_befor_vat;
        }
        $data['payment_modes'] = get_table_where('tblpayment_modes',array('active'=>1));
        $this->load->view('admin/purchase_invoice/payment_all_modal',$data);
    }
    public function pay_slip_all()
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
            $_data['id_supplierss'] = $data['id_supplierss'];
            $_data['type'] = 1;
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
                    $__data['type'] = 1;
                    $invoice = get_table_where('tblpurchase_invoice',array('id'=>$value),'','row');
                    $__data['total'] = $invoice->total_price_befor_vat;
                    $__data['payment'] = $invoice->total_price_befor_vat;
                    $this->db->insert('tblpay_slip_detail',$__data);
                    $this->db->update('tblpurchase_invoice',array('amount_paid'=>$invoice->total_price_befor_vat,'status'=>2),array('id'=>$invoice->id));  
                }
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
    public function payment($id='')
    {
        $data['costs'] = array();
        $this->costs_model->get_by_id(0,$data['costs']);
        $data['id_invoice'] = $id;
        $data['code'] = get_option('prefix_pay_slip').'-'.sprintf('%06d', ch_getMaxID('id', 'tblpay_slip') + 1);
        $data['invoice'] = get_table_where('tblpurchase_invoice',array('id'=>$id),'','row');
        $data['payment_modes'] = get_table_where('tblpayment_modes',array('active'=>1));
        $this->load->view('admin/purchase_invoice/payment_modal',$data);
    }   
    public function pay_slip($id='')
    {
        $success = false;
        $alert_type = 'warning';
        $message    = _l('ch_added_successfuly_not');
        if ($this->input->post()) {
            $invoicess = get_table_where('tblpurchase_invoice',array('id'=>$id),'','row');
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
            $_data['id_supplierss'] = $invoicess->id_supplier;
            $_data['type'] = 1;
            $_data['id_old'] = $id;
            $_data['prefix'] = get_option('prefix_pay_slip');
            $_data['code'] = sprintf('%06d', ch_getMaxID('id', 'tblpay_slip') + 1);
            $this->db->insert('tblpay_slip',$_data);
            $id_pay = $this->db->insert_id();
            if($id_pay)
            {
                $__data['id_old'] = $id; 
                $__data['id_pay_slip'] = $id_pay;
                $__data['type'] = 1;
                $__data['total'] = str_replace(',', '', $data['total']);
                $__data['payment'] = str_replace(',', '', $data['payment']);
                $this->db->insert('tblpay_slip_detail',$__data);
                $invoice = get_table_where('tblpurchase_invoice',array('id'=>$id),'','row');
                $amount_paid =  $invoice->amount_paid + $__data['payment'];
                if(($amount_paid + $invoice->price_other_expenses) == $invoice->total_price_befor_vat)
                {
                    $status = 2;
                }else
                {
                    $status = 1;
                }
                $this->db->update('tblpurchase_invoice',array('amount_paid'=>$amount_paid,'status'=>$status),array('id'=>$invoice->id));
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
    public function delele_file($id="")
    {
        if(is_numeric($id))
        {
            $file = get_table_where('tblfiles',array('id'=>$id),'','row');
            $this->db->where('id',$id);
            if($this->db->delete('tblfiles')){
            if (file_exists('uploads/invoices/'.$file->rel_id.'/'.$file->file_name)) {
                unlink('uploads/invoices/'.$file->rel_id.'/'.$file->file_name);
            }
            }
        }
    }
    public function get_invoice_attachments($attachment_id = '', $id = '')
    {
        if (is_numeric($attachment_id)) {
            $this->db->where('id', $attachment_id);

            return $this->db->get(db_prefix() . 'files')->row();
        }
        $this->db->order_by('dateadded', 'desc');
        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'invoice');

        return $this->db->get(db_prefix() . 'files')->result_array();
    }
    public function add_attachment($id)
    {
        $return = handle_invoice_attachment($id);
        $returns = get_table_where('tblfiles',array('id'=>$return),'','row');
        if($return){
                if(substr($returns->filetype,0,5)=='image')
                {
                    $returns->image=true;
                }
                else
                {
                    $returns->image=false;
                }
                $success = true;
                $alert_type = 'success';
                $message    = _l('Tải lên thành công', _l('Hóa đơn thuế'));
        }
        else
        {
            $success = false;
            $alert_type = 'danger';
            $message    = _l('Tải lên thất bại', _l('Hóa đơn thuế'));
        }
    
        echo json_encode(array(
            'alert_type' => $alert_type,
            'success' => $success,
            'message' => $message,
            'result'  => $returns
        ));
    }
    public function add_all()
    {
        $alert_type = 'warning';
        $message    = _l('ch_added_successfuly_not');
        if ($this->input->post()) {
        $data = $this->input->post();
        $count = 0;
        $id_import_all = explode(',', trim($data['id_import_all'],','));
            $_data['id_supplier'] = $data['id_supplier'];
            $_data['date_invoice'] = to_sql_date($data['date_invoice_all']);
            $_data['date_create'] = date('Y-m-d H:i:s');
            $_data['code_invoice'] = $data['code_invoice_all'];
            $_data['note'] = $data['note_all'];
            $_data['staff_create'] = get_staff_user_id();
            //này sẽ đổi lại là id đơn hàng
            $_data['id_import'] = trim($data['id_import_all'],',');
            $_data['total_price_befor_vat'] = 0;
            $_data['total_price_vat'] = 0;
            $_data['total_price_affter_vat'] = 0;
            $_data['promotion_expected'] = 0;
            $_data['price_other_expenses'] = 0;
        foreach ($id_import_all as $key => $value) {
                $import = get_table_where('tblpurchase_order',array('id'=>$value),'','row');
                $_data['total_price_befor_vat'] += $import->totalAll_suppliers;
                $_data['total_price_vat'] += $import->totalAll_suppliers + $import->promotion_expected - $import->total_novat;
                $_data['total_price_affter_vat'] += $import->total_novat;
                $_data['promotion_expected'] += $import->promotion_expected;
                $_data['price_other_expenses'] += $import->price_other_expenses;
        }
        if($this->db->insert('tblpurchase_invoice',$_data))
        {
            $id_invoice = $this->db->insert_id();
            foreach ($id_import_all as $key => $value) {
                $this->db->update('tblpurchase_order',array('red_invoice'=>$id_invoice),array('id'=>$value));
            }
            $alert_type = 'success';
            $message    = _l('ch_added_successfuly');
        }
        }
        echo json_encode(array(
            'alert_type' => $alert_type,
            'message' => $message
        ));die;
    }
    public function add()
    {
        $alert_type = 'warning';
        $message    = _l('ch_added_successfuly_not');
        if ($this->input->post()) {
        $data = $this->input->post();
        $data['date_invoice'] = to_sql_date($data['date_invoice']);
        $data['date_create'] = date('Y-m-d H:i:s');
        $data['staff_create'] = get_staff_user_id();
        //id import sẽ là id đơn hàng
        $import = get_table_where('tblpurchase_order',array('id'=>$data['id_import']),'','row');
                $data['total_price_befor_vat'] = $import->totalAll_suppliers;
                $data['total_price_vat'] = $import->totalAll_suppliers + $import->promotion_expected - $import->total_novat;
                $data['total_price_affter_vat'] = $import->total_novat;
                $data['promotion_expected'] = $import->promotion_expected;
                $data['price_other_expenses'] = $import->price_other_expenses;
        if($this->db->insert('tblpurchase_invoice',$data))
        {   
            $id_invoice = $this->db->insert_id();
            $this->db->update('tblpurchase_order',array('red_invoice'=>$id_invoice),array('id'=>$data['id_import']));
            $alert_type = 'success';
            $message    = _l('ch_added_successfuly');
        }
        }
        echo json_encode(array(
            'alert_type' => $alert_type,
            'message' => $message
        ));die;
    }
    public function update($id)
    {
        $alert_type = 'warning';
        $message    = _l('cong_update_false');
        if ($this->input->post()) {
        $data = $this->input->post();
        $_data['code_invoice'] = $data['code_invoice'];
        $_data['date_invoice'] = to_sql_date($data['date_invoice']);
        $_data['note'] = $data['note'];
        if($this->db->update('tblpurchase_invoice',$_data,array('id_import'=>$id)))
        {
            $alert_type = 'success';
            $message    = _l('cong_update_true');
        }
        }
        echo json_encode(array(
            'alert_type' => $alert_type,
            'message' => $message
        ));die;
    }
    public function delete($id)
    {
        if (!is_admin()) {
            access_denied('Delete Invoice');
        }
        $alert_type = 'warning';
        $message    = _l('ch_no_delete');
        $ktr = get_table_where('tblpurchase_invoice',array('id'=>$id),'','row');
        $response = $this->db->delete('tblpurchase_invoice',array('id'=>$id));
        if ($response) {
            if (file_exists(get_upload_path_by_type('invoice') . $id)) {
                delete_dir_ch(get_upload_path_by_type('invoice') . $id);
            }
            $id_import = explode(',', $ktr->id_import);
            foreach ($id_import as $key => $value) {
                $this->db->update('tblpurchase_order',array('red_invoice'=>0),array('id'=>$value));
            }
            
            $alert_type = 'success';
            $message    = _l('ch_delete');
        }
        echo json_encode(array(
            'alert_type' => $alert_type,
            'message' => $message
            ));
    }

}