<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Other_payslips extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('costs_model');
    }

    public function index()
    {
        if (!has_permission('other_payslips', '', 'view')) {
                access_denied('other_payslips');
        }
        $data['title']          = _l('ch_other_payslips');
        $this->load->view('admin/other_payslips/manage', $data);
    }
    public function table()
    {
        if (!has_permission('other_payslips', '', 'view')) {
                ajax_access_denied();
        }
        $this->app->get_table_data('other_payslips');
    }
    public function count_all()
    {
        $count = get_table_where_select('count(*) as alls','tblother_payslips',array(),'','row');
        $no_pay = get_table_where_select('count(*) as no_pay','tblother_payslips',array('status'=>0),'','row');
        $pay_client = get_table_where_select('count(*) as pay_client','tblother_payslips',array('objects'=>1),'','row');
        $pay_suppliers = get_table_where_select('count(*) as pay_suppliers','tblother_payslips',array('objects'=>2),'','row');
        $pay_staff = get_table_where_select('count(*) as pay_staff','tblother_payslips',array('objects'=>3),'','row');
        $pay_other = get_table_where_select('count(*) as pay_other','tblother_payslips',array('objects'=>4),'','row');
        $data['all'] = $count->alls;
        $data['no_pay'] = $no_pay->no_pay;
        $data['pay_client'] = $pay_client->pay_client;
        $data['pay_suppliers'] = $pay_suppliers->pay_suppliers;
        $data['pay_staff'] = $pay_staff->pay_staff;
        $data['pay_other'] = $pay_other->pay_other;
        $data['pay'] = $data['all']-$data['no_pay'];
        echo json_encode($data);
    }
    public function pay_slip()
    {
        $success = false;
        $alert_type = 'warning';
        $message    = _l('ch_added_successfuly_not');
        if ($this->input->post()) {
            $data = $this->input->post();
            $id = '';
            if(!empty($data['id_orther']))
            {
            $id = $data['id_orther'];
            unset($data['id_orther']);
            }
            if(!empty($id))
            {
                $alert_type = 'warning';
                $message    = _l('ch_no_updated_successfuly');
                $orther = get_table_where('tblother_payslips',array('id'=>$id),'','row');
                $data['note'] = $this->input->post('note',true);
                $data['date'] = to_sql_date($data['date']);
                $data['total'] = str_replace(',', '', $data['total']);
                $id_pay = $this->db->update('tblother_payslips',$data,array('id'=>$id));
                if($id_pay)
                {   
                    if($orther->objects == 2)
                    {
                        if(!empty($orther->type_vouchers))
                        {
                            if($orther->type_vouchers == 1)
                            {    
                                if(!empty($orther->vouchers_id))
                                {
                                $import = get_table_where('tblpurchase_order',array('id'=>$orther->vouchers_id),'','row');

                                $total = $import->price_other_expenses-$orther->total+$data['total'];

                                if(($total+$import->amount_paid) == $import->total)
                                {
                                    $status = 2;
                                }elseif(($total+$import->amount_paid) == 0)
                                {
                                    $status = 0;
                                }else
                                {
                                    $status = 1;
                                }
                                $this->db->update('tblpurchase_order',array('price_other_expenses'=>$total,'status_pay'=>$status),array('id'=>$orther->vouchers_id));       
                                }
                            }
                        }
                    }
                    
                $success = true;
                $alert_type = 'success';
                $message    = _l('ch_updated_successfuly');
                }
            }
            else
            {
                $data['note'] = $this->input->post('note',true);
                $data['code'] = sprintf('%06d', ch_getMaxID('id', 'tblother_payslips') + 1);
                $data['date'] = to_sql_date($data['date']);
                $data['staff_id'] = get_staff_user_id();
                $data['total'] = str_replace(',', '', $data['total']);
                $data['date_create'] = date('Y-m-d H:i:s');
                $data['prefix'] = get_option('prefix_other_payslips');
                $this->db->insert('tblother_payslips',$data);
                $id_pay = $this->db->insert_id();
                if($id_pay)
                {   
                    if($data['objects'] == 2)
                    {
                        if(!empty($data['type_vouchers']))
                        {
                            if($data['type_vouchers'] == 1)
                            {
                                if(!empty($data['vouchers_id']))
                                {
                                $import = get_table_where('tblpurchase_order',array('id'=>$data['vouchers_id']),'','row');
                                $total = $import->price_other_expenses+$data['total'];
                                if(($total+$import->amount_paid) == $import->totalAll_suppliers)
                                {
                                    $status = 2;
                                }else
                                {
                                    $status = 1;
                                }
                                $this->db->update('tblpurchase_order',array('price_other_expenses'=>$total,'status_pay'=>$status),array('id'=>$data['vouchers_id']));       
                                }
                            }
                        }
                    }
                    
                $success = true;
                $alert_type = 'success';
                $message    = _l('ch_added_successfuly');
                }
            }
        }
        echo json_encode(array(
            'success' => $success, 
            'alert_type' => $alert_type,
            'message' => $message
        ));die;
    } 
    public function SearchClient($id='',$type='')
    {   
        $data = [];
        $search = $this->input->get('term');
        if (empty($type))
        {
        $type = $this->input->get('type');
        }
        $limit_one = 20;
        if($type == 1)
        {
        $this->db->select('
            tblclients.userid as id,
            tblclients.company as text,
            CONCAT(tblclients.prefix_client,tblclients.code_client) as code_client'
        , false);
        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('tblclients.company', $search);
            $this->db->or_like('CONCAT(tblclients.prefix_client, tblclients.code_client)', $search);
            $this->db->group_end();
        }
        if(!empty($id))
        {
        $this->db->where('tblclients.userid',$id);    
        }
        $this->db->order_by('tblclients.company', 'DESC');
        $this->db->limit($limit_one);
        $client = $this->db->get('tblclients')->result_array();
        $data['results'] = $client;
        }elseif($type == 2)
        {
        $this->db->select('
            tblsuppliers.id as id,
            tblsuppliers.company as text,
            CONCAT(tblsuppliers.prefix,tblsuppliers.code) as code_client'
        , false);
        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('tblsuppliers.company', $search);
            $this->db->or_like('CONCAT(tblsuppliers.prefix, tblsuppliers.code)', $search);
            $this->db->group_end();
        }
        if(!empty($id))
        {
        $this->db->where('tblsuppliers.id',$id);    
        }
        $this->db->order_by('tblsuppliers.company', 'DESC');
        $this->db->limit($limit_one);
        $suppliers = $this->db->get('tblsuppliers')->result_array();
        $data['results'] = $suppliers;    
        }elseif($type == 3)
        {
        $this->db->select('
            tblstaff.staffid as id,
            CONCAT(tblstaff.lastname,tblstaff.firstname) as text'
        , false);
        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('CONCAT(tblstaff.lastname, tblstaff.firstname)', $search);
            $this->db->group_end();
        }
        if(!empty($id))
        {
        $this->db->where('tblstaff.staffid',$id);    
        }
        $this->db->limit($limit_one);
        $suppliers = $this->db->get('tblstaff')->result_array();
        $data['results'] = $suppliers;    
        }
        echo json_encode($data);die();

    }
    public function vouchers_id()
    {
        $data = $this->input->post();
        $_data = array();
        if(!empty($data))
        {
            if($data['objects'] == 2)
            {
                if($data['type_vouchers'] == 1)
                {
                 $_data = get_table_where_select('tblpurchase_order.*,tblpurchase_order.totalAll_suppliers as total','tblpurchase_order',array('suppliers_id'=>$data['objects_id'],'red_invoice'=>0,'status >'=>2,'status_pay !='=>2));   
                }
            }
        }
        echo json_encode($_data);die();
    }
    public function other_payslips($id='')
    {
       $data['vouchers_id'] = array();
       if(!empty($id))
       {
       $data['items'] = get_table_where('tblother_payslips',array('id'=>$id),'','row');
       if($data['items']->objects == 2)
            {
                $vouchers_id = get_table_where('tblimport',array('id'=>$data['items']->vouchers_id));
                foreach ($vouchers_id as $key => $value) {
                    $data['vouchers_id'][$key]['id'] = $value['id'];   
                    $data['vouchers_id'][$key]['name'] = $value['prefix'].'-'.$value['code']; 
                    $data['vouchers_id'][$key]['total_import'] = $value['total'] - $value['amount_paid'] - $value['price_other_expenses'] + $data['items']->total;           
                }
            }
       }
       $data['id'] = 0;
       $data['payment_modes'] = get_table_where('tblpayment_modes',array('active'=>1));
       $data['costs'] = array();
       $this->costs_model->get_by_id(0,$data['costs']);
       $data['code'] = get_option('prefix_other_payslips').'-'.sprintf('%06d', ch_getMaxID('id', 'tblother_payslips') + 1);
       $this->load->view('admin/other_payslips/other_payslips',$data);
    }
    public function LoadListObjectByID($id){
        $list='';
        if($id==1){
            $data=$this->clients_model->get();
            foreach ($data as $key => $value) {
                $list.='<option value="'.$value['userid'].'">'.$value['company'].'</option>';
            } 
        }
        else if($id==2){
            $data=$this->staff_model->get_all_staff();
            foreach ($data as $key => $value) {
                $list.='<option value="'.$value['staffid'].'">'.$value['fullname'].'</option>';
            } 
        }
        else if($id==3){
            $data=$this->suppliers_model->get();
            foreach ($data as $key => $value) {
                $list.='<option value="'.$value['userid'].'">'.$value['company'].'</option>';
            } 
        }
        else
        {
            $list='';
        }
        print_r($list);
    }
    public function delete($id='')
    {
        $payslips = get_table_where('tblother_payslips',array('id'=>$id),'','row');
        $response = $this->db->delete('tblother_payslips',array('id'=>$id));
        $alert_type = 'warning';
        $message    = _l('ch_no_delete');  
        if ($response) {
            $alert_type = 'success';
            $message    = _l('ch_delete');

       if(!empty($payslips))
       {
           if($payslips->objects == 2)
           {
                if($payslips->type_vouchers == 1)
                {
                    if(!empty($payslips->vouchers_id))
                    {
                        $import = get_table_where('tblpurchase_order',array('id'=>$payslips->vouchers_id),'','row');
                        $total = $import->price_other_expenses - $payslips->total;
                        if(($total+$import->amount_paid) == 0)
                        {
                            $status = 0;
                        }else
                        {
                            $status = 1;
                        }
                        $this->db->update('tblpurchase_order',array('price_other_expenses'=>$total,'status_pay'=>$status),array('id'=>$payslips->vouchers_id)); 
                    }
                }
           }
       }
        }

        echo json_encode(array(
            'alert_type' => $alert_type,
            'message' => $message
            ));
    }
    public function update_status()
    {
        if ($this->input->post()) {
            $id=$this->input->post('id');
            $status=$this->input->post('status');
            $other_payslips = get_table_where('tblother_payslips',array('id'=>$id),'','row');
            if($other_payslips->status == 1)
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
            $success=$this->db->update('tblother_payslips',$data,array('id'=>$id));
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
    public function print_pdf($id='')
    {
        ob_start();
        $data = new stdClass();
        $dataMain = get_table_where('tblother_payslips',array('id'=>$id),'','row');
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
        $data->content .= '<span style="text-align: center;font-size: 20px;font-weight: bold;">'._l('ch_other_slip_IN').'</span><br>';
        
        $data->content .= '<span style="text-align: center;font-style: italic;">'._l('ch_number').': '.$dataMain->prefix.'-'.$dataMain->code.'</span><br>';

        $day = date('d', strtotime($dataMain->date));
        $month = date('m', strtotime($dataMain->date));
        $year = date('Y', strtotime($dataMain->date));
        $date = _l('ch_day') . ' ' . $day . ' ' . _l('ch_month') . ' ' . $month . ' ' . _l('ch_year') . ' ' . $year;
        $data->content .= '<span style="text-align: center;font-style: italic;">'.$date.'</span><br><br>';
        $pay_modes = get_table_where('tblpayment_modes',array('id'=>$dataMain->payment_modes),'','row');
        if($dataMain->objects== 2)
            {
                $supplier = get_table_where('tblsuppliers',array('id'=>$dataMain->objects_id),'','row');
                $data->content .= '
                <span style="font-weight: bold;">'._l('ch_units_in').': </span><span style="font-weight: bold;">'.$supplier->company.'</span><br/><br>';
            }
            if($dataMain->objects == 1)
            {
                $client = get_table_where('tblclients',array('userid'=>$dataMain->objects_id),'','row');
                $data->content .= '
                <span style="font-weight: bold;">'._l('clients').': </span><span style="font-weight: bold;">'.$client->company.'</span><br/><br>';
            }
            if($dataMain->objects == 3)
            {
                $_data = get_staff_full_name($dataMain->objects_id);
                $data->content .= '
                <span style="font-weight: bold;">'._l('ch_units_in').': </span><span style="font-weight: bold;">'.$_data.'</span><br/><br>';
            }
            if($dataMain->objects == 4)
            {
                $data->content .= '
                <span style="font-weight: bold;">'._l('ch_units_in').': </span><span style="font-weight: bold;">'.$dataMain->objects_text.'</span><br/><br>';
            }
        

        $data->content .='
            <span style="font-weight: bold;">'._l('ch_note_pay_slips').': </span><span>'.$dataMain->note.'</span><br><br>
            <span style="font-weight: bold;">'._l('acs_sales_payment_modes_submenu').': </span><span>'.$pay_modes->name.'</span><br><br>
            <span style="font-weight: bold;">'._l('expense_add_edit_amount').': </span><span>'.number_format($dataMain->total).'</span><br><br>
            <span style="font-weight: bold;">'._l('ch_write_in_words').': </span><span>'.ucfirst(convert_number_to_words($dataMain->total)).' đồng</span><br>';
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