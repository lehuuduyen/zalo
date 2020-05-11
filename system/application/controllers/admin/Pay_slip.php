<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pay_slip extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        if (!has_permission('pay_slip', '', 'view')) {
                access_denied('pay_slip');
        }
        $data['title']          = _l('ch_pay_slip');
        $this->load->view('admin/pay_slip/manage', $data);
    }
    public function table()
    {
        if (!has_permission('pay_slip', '', 'view')) {
                ajax_access_denied();
        }
        $this->app->get_table_data('pay_slip');
    }
    public function view_pay_slip($id,$type)
    {
        if (!has_permission('pay_slip', '', 'view')) {
                ajax_access_denied();
        }
        if($type == 1)
        {
        $this->app->get_table_data('view_pay_slip_invoice',array('id'=>$id));
        }
        else
        {
        $this->app->get_table_data('view_pay_slip',array('id'=>$id));    
        }
    }    
    public function update_status()
    {
        if ($this->input->post()) {
            $id=$this->input->post('id');
            $status=$this->input->post('status');
            $pay_slip = get_table_where('tblpay_slip',array('id'=>$id),'','row');
            if($pay_slip->status == 1)
            {
                die;
            }
            $staff_id=get_staff_user_id();
            $date=date('Y-m-d H:i:s');
            $history_status=$staff_id.','.$date;
            $data =array(
                'history_status'=>$history_status,
                'status' => ($status+1),
            );
            $success=$this->db->update('tblpay_slip',$data,array('id'=>$id));
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
    public function electronic_bill($id='')
    {
        $data['items'] = get_table_where('tblpay_slip',array('id'=>$id),'','row');
        $invoice = explode(',', $data['items']->id_old);
        if($data['items']->type == 1){
        $this->db->where_in('id',$invoice);
        $data['items']->item = $this->db->get('tblpurchase_invoice')->result_array();
        foreach ($data['items']->item as $key => $value) {
            $data['items']->item[$key]['total'] = $value['total_price_befor_vat'];
            $data['items']->item[$key]['code'] = $value['code_invoice'];
        }
        }else
        {
        $this->db->where_in('id',$invoice);
        $data['items']->item = $this->db->get('tblimport')->result_array();   
        foreach ($data['items']->item as $key => $value) {
            $data['items']->item[$key]['code'] = $value['prefix'].'-'.$value['code'];
        } 
        }
        $this->load->view('admin/pay_slip/view_modal',$data);
    }
    public function delete($id)
    {
        if (!is_admin()) {
            access_denied('Delete Pay Slip');
        }
        $alert_type = 'warning';
        $message    = _l('ch_no_delete');
        $pay_slip = get_table_where('tblpay_slip',array('id'=>$id),'','row');
        $response = $this->db->delete('tblpay_slip',array('id'=>$id));
        if ($response) {
            $this->db->delete('tblpay_slip_detail',array('id_pay_slip'=>$id));
            if($pay_slip->type == 1)
            {
                $id_old = explode(',', $pay_slip->id_old);
                if(count($id_old) == 1)
                {
                    foreach ($id_old as $key => $value) {
                        $invoice  = get_table_where('tblpurchase_invoice',array('id'=>$value),'','row');
                        $amount_paid = $invoice->amount_paid - $pay_slip->payment - $invoice->price_other_expenses;
                        if($amount_paid == 0)
                        {
                            $status = 0;
                        }else
                        {
                            $status = 1;
                        }
                        $this->db->update('tblpurchase_invoice',array('amount_paid'=>$amount_paid,'status'=>$status),array('id'=>$invoice->id));
                    }
                }else
                {
                    foreach ($id_old as $key => $value) {
                        $invoice  = get_table_where('tblpurchase_invoice',array('id'=>$value),'','row');
                        $amount_paid = $invoice->price_other_expenses;
                        if($amount_paid == 0)
                        {
                            $status = 0;
                        }else
                        {
                            $status = 1;
                        }
                        $this->db->update('tblpurchase_invoice',array('amount_paid'=>0,'status'=>0),array('id'=>$value));
                    }   
                }
            }else
            {
                $id_old = explode(',', $pay_slip->id_old);
                if(count($id_old) == 1)
                {
                foreach ($id_old as $key => $value) {
                    $import  = get_table_where('tblpurchase_order',array('id'=>$value),'','row');

                    $amount_paid = $import->amount_paid - $pay_slip->payment;
                    if(($amount_paid+$import->price_other_expenses) <= 0)
                    {
                        $status = 0;
                    }else
                    {
                        $status = 1;
                    }
                    $this->db->update('tblpurchase_order',array('amount_paid'=>$amount_paid,'status_pay'=>$status),array('id'=>$import->id));
                }
                }else
                {
                 foreach ($id_old as $key => $value) {
                    $import  = get_table_where('tblpurchase_order',array('id'=>$value),'','row');
                    $amount_paid = $import->amount_paid - $pay_slip->money_arises;
                    if(($amount_paid+$import->price_other_expenses) <= 0)
                    {
                        $status = 0;
                    }else
                    {
                        $status = 1;
                    }
                    $this->db->update('tblpurchase_order',array('amount_paid'=>($import->amount_paid - $import->money_arises),'status_pay'=>$status,'money_arises'=>0),array('id'=>$value));
                }   
                }
            }
            $alert_type = 'success';
            $message    = _l('ch_delete');
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
        $dataMain = get_table_where('tblpay_slip',array('id'=>$id),'','row');
        $table = '';
        $img = file_get_contents(base_url('uploads/company/').get_option('company_logo'));
        $data->img = '<img width="100" src="data:image/png;base64,'.base64_encode($img).'"/>';
        $data->content = '<table style="border-bottom:1pt " class="table table-bordered" width="100%">
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
            </table><br><br>';
        $data->content .= '<span style="text-align: center;font-size: 20px;font-weight: bold;">'._l('ch_pay_slip_IN').'</span><br>';
        
        $data->content .= '<span style="text-align: center;font-style: italic;">'._l('ch_number').': '.$dataMain->prefix.'-'.$dataMain->code.'</span><br>';

        $day = date('d', strtotime($dataMain->day_vouchers));
        $month = date('m', strtotime($dataMain->day_vouchers));
        $year = date('Y', strtotime($dataMain->day_vouchers));
        $date = _l('ch_day') . ' ' . $day . ' ' . _l('ch_month') . ' ' . $month . ' ' . _l('ch_year') . ' ' . $year;
        $data->content .= '<span style="text-align: center;font-style: italic;">'.$date.'</span><br><br>';
        $suppliers = get_table_where('tblsuppliers',array('id'=>$dataMain->id_supplierss),'','row');
        $pay_modes = get_table_where('tblpayment_modes',array('id'=>$dataMain->payment_mode),'','row');
        $data->content .= '
            <span style="font-weight: bold;">'._l('ch_units_in').': </span><span style="font-weight: bold;">'.$suppliers->company.'</span><br/><br>
            <span style="font-weight: bold;">'._l('ch_note_pay_slips').': </span><span>'.$dataMain->note.'</span><br><br>
            <span style="font-weight: bold;">'._l('acs_sales_payment_modes_submenu').': </span><span>'.$pay_modes->name.'</span><br><br>
            <span style="font-weight: bold;">'._l('expense_add_edit_amount').': </span><span>'.number_format($dataMain->payment).'</span><br><br>
            <span style="font-weight: bold;">'._l('ch_write_in_words').': </span><span>'.ucfirst(convert_number_to_words($dataMain->payment)).' đồng</span><br>';
        $date_2 = _l('ch_day') . ' ........ ' . _l('ch_month') . ' ........ ' . _l('ch_year') . ' ........';
        $data->content .= '<span style="text-align: right;font-style: italic;">'.$date_2.'</span><br>';
        $table = '<table class="table table-bordered" width="100%">
                <thead>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;">
                            <span style="font-weight: bold;">'._l('ch_ceo').'</span><br>
                            <span>'._l('ch_signature').'</span>
                        </td>
                        <td style="text-align: center;">
                            <span style="font-weight: bold;">'._l('ch_chief_accountant').'</span><br>
                            <span>'._l('ch_signature').'</span>
                        </td>
                        <td style="text-align: center;">
                            <span style="font-weight: bold;">'._l('ch_cashier').'</span><br>
                            <span>'._l('ch_signature').'</span>
                        </td>
                        <td style="text-align: center;">
                            <span style="font-weight: bold;">'._l('ch_vote_maker').'</span><br>
                            <span>'._l('ch_signature').'</span>
                        </td>
                        <td style="text-align: center;">
                            <span style="font-weight: bold;">'._l('ch_recipient_pirce').'</span><br>
                            <span>'._l('ch_signature').'</span>
                        </td>
                    </tr>
                </tbody>
            </table>';
        $data->content .= $table;
        $datas = '<br><br><br><br><br><span style="text-align: center;">__________________________________________________________________</span><br>';
        $data->content .=$datas.$data->content;
        $pdf      = print_pdf_ch($data);
        $type     = 'I';
        $pdf->Output(slug_it('') . '.pdf', $type);
    }    
}