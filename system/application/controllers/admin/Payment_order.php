<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Payment_order extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        if (!is_admin()) {
                access_denied('Payment order');
        }
        $data['title']          = _l('ch_payment');
        $this->load->view('admin/payment_order/manage', $data);
    }
    public function table()
    {
        if (!is_admin()) {
                ajax_access_denied();
        }
        $this->app->get_table_data('payment_order');
    }
    public function count_all()
    {
        $count = get_table_where_select('count(*) as alls','tblpayment_order',array(),'','row');
        $no_pay = get_table_where_select('count(*) as no_determined','tblpayment_order',array('id_order'=>0,'status_cancel'=>0),'','row');
        $pay_client = get_table_where_select('count(*) as determined','tblpayment_order',array('id_order !='=>0,'status_cancel'=>0),'','row');
        $cancel = get_table_where_select('count(*) as cancel','tblpayment_order',array('status_cancel !='=>0),'','row');
        $data['all'] = $count->alls;
        $data['no_determined'] = $no_pay->no_determined;
        $data['determined'] = $pay_client->determined;
        $data['cancel'] = $cancel->cancel;

        echo json_encode($data);
    }
    public function payment_order()
    {
        $data['code'] = get_option('prefix_payment_order').'-'.sprintf('%06d', ch_getMaxID('id', 'tblpayment_order') + 1);
        $data['clients'] = $this->db->get(db_prefix().'clients')->result_array();
        $data['payment_modes'] = get_table_where('tblpayment_modes',array('active'=>1));
        $data['currencies'] = get_table_where('tblcurrencies');
        $data['account_business'] = get_table_where('tblaccount_business');

        $this->load->view('admin/payment_order/payment_order',$data);
    }
    public function note_cancel($id ='')
    {
        $data = $this->input->post();
        $in = array(
            'status_cancel'=>1,
            'date_cancel'=>date('Y-m-d H:i:s'),
            'staff_cancel'=>get_staff_user_id(),
            'note_cancel' => $data['note_cancel'],
        );
        $this->db->where('id', $id);
        $result = $this->db->update('tblpayment_order', $in);
        if ($result) {
            $ktr = get_table_where('tblpayment_order',array('id'=>$id),'','row');
            if(!empty($ktr->id_order)){
            $ktr_order = get_table_where('tblorders',array('id'=>$ktr->id_order),'','row');

            if($ktr->currency == 4)
            {
            $total = $ktr_order->money_paid - $ktr->total_voucher_received;
            $this->db->update('tblorders',array('money_paid'=>$total),array('id'=>$ktr->id_order));
            }else
            {  
            $money_paid_international = $ktr_order->money_paid_international - $ktr->total_voucher_received;
            $this->db->update('tblorders',array('money_paid_international'=>$money_paid_international),array('id'=>$ktr->id_order));
            }
            }

            $message = _l('updated_successfuly');
            $alert_type = 'success';
            log_activity('Payment order cancel [ID: ' . $id . ']');
        }
        echo json_encode(array(
            'success' => $result,
            'message' => $message,
            'alert_type' => $alert_type
        ));
        die;
    }
    public function detail($id)
    {
        $data['order'] = array();
        $payment = get_table_where('tblpayment_order',array('id'=>$id),'','row');
        $data['payment'] = $payment;
        if(!empty($payment->id_order))
        {
            $data['order']=get_table_where('tblorders',array('id'=>$payment->id_order),'','row');
            $data['payment']->left_total = $data['payment']->left_total + $data['order']->grand_total - $data['order']->money_paid;
            if($data['payment']->left_total > $data['order']->grand_total)
            {
                $data['payment']->left_total = $data['order']->grand_total;
            }
        }
        $data['code'] = $payment->prefix.$payment->code;
        $data['clients'] = $this->db->get(db_prefix().'clients')->result_array();
        $data['payment_modes'] = get_table_where('tblpayment_modes',array('active'=>1));
        $data['currencies'] = get_table_where('tblcurrencies');
        $data['account_business'] = get_table_where('tblaccount_business');

        $this->load->view('admin/payment_order/payment_order_detail',$data);
    }
    public function add_update_payment()
    {
        $success = false;
        $alert_type = 'warning';
        $message    = _l('Cập nhật không thành công');
        if ($this->input->post()) {
            $data = $this->input->post();
            $data['total'] = str_replace(',', '', $data['total']);
            $get_payment = get_table_where('tblpayment_order',array('id'=>$data['code']),'','row');
            $_data['client'] = $data['client'];
            $_data['left_total'] = str_replace(',', '', $data['left']);
            $_data['id_order'] = $data['id_order'];
            $this->db->where('id',$data['code']);
            if(!empty($data['client'])&&empty($get_payment->client))
            {
            $_data['date_client'] = date('Y-m-d H:i:s');
            $_data['staff_client'] = get_staff_user_id();
            }
            if(!empty($data['id_order'])&&empty($get_payment->id_order))
            {
            $_data['date_order'] = date('Y-m-d H:i:s');
            $_data['staff_order'] = get_staff_user_id();   
            }
            
            $id_pay = $this->db->update('tblpayment_order',$_data);
            
            if($id_pay)
            {

                if(!empty($_data['id_order'])){
                if(!empty($get_payment->id_order)){
                $get_payments = $get_payment->total_voucher - $data['total'];
                $order = get_table_where('tblorders',array('id'=>$_data['id_order']),'','row');
                
                $money_paid =  $order->money_paid -  $get_payments;
                }else
                {
                $order = get_table_where('tblorders',array('id'=>$_data['id_order']),'','row');
                $money_paid =  $order->money_paid + $data['total'];    
                }
                $this->db->update('tblorders',array('money_paid'=>$money_paid),array('id'=>$_data['id_order']));
                
                $count_order = get_table_where('tblpayment_order',array('id_order'=>$_data['id_order']));
                if(count($count_order) == 1)
                {
                    $this->db->update('tblorders_step',array('date_create'=>date('Y-m-d H:i:s'),'id_staff'=>get_staff_user_id(),'active'=>1),array('order_by'=>2,'id_orders'=>$_data['id_order']));
                }
                }
            $success = true;
            $alert_type = 'success';
            $message    = _l('Cập nhật thành công');
            }
        }
        echo json_encode(array(
            'success' => $success, 
            'alert_type' => $alert_type,
            'message' => $message
        ));die;
    }
    public function updateClient()
    {
        $data = $this->input->post();
        $_data['date_client'] = date('Y-m-d H:i:s');
        $_data['staff_client'] = get_staff_user_id();  
        $_data['client'] = $data['data_input'];
        $id_pay = $this->db->update('tblpayment_order',$_data,array('id'=>$data['id'])); 
        if($id_pay){
        echo json_encode([
                        'success' => true,
                        'alert_type' => 'success',
                        'message' => _l('cong_update_true')
                    ]);die();  
        }else{
        echo json_encode([
                        'success' => false,
                        'alert_type' => 'danger',
                        'message' => _l('cong_update_false')
                    ]);die();      
        }
    }
    public function updateOrder()
    {
        $data = $this->input->post();
        $_data['date_order'] = date('Y-m-d H:i:s');
        $_data['staff_order'] = get_staff_user_id();    
        $_data['id_order'] = $data['data_input'];
        $id_pay = $this->db->update('tblpayment_order',$_data,array('id'=>$data['id'])); 
        if($id_pay){
                $get_payment = get_table_where('tblpayment_order',array('id'=>$data['id']),'','row');
                if(!empty($_data['id_order'])){
             
                $order = get_table_where('tblorders',array('id'=>$_data['id_order']),'','row');
                
                $money_paid =  $order->money_paid + $get_payment->total_voucher; 

                $this->db->update('tblorders',array('money_paid'=>$money_paid),array('id'=>$_data['id_order']));
                
                $count_order = get_table_where('tblpayment_order',array('id_order'=>$_data['id_order']));
                if(count($count_order) == 1)
                {
                    $this->db->update('tblorders_step',array('date_create'=>date('Y-m-d H:i:s'),'id_staff'=>get_staff_user_id(),'active'=>1),array('order_by'=>2,'id_orders'=>$_data['id_order']));
                }
                }
        echo json_encode([
                        'success' => true,
                        'alert_type' => 'success',
                        'message' => _l('cong_update_true')
                    ]);die();  
        }else{
        echo json_encode([
                        'success' => false,
                        'alert_type' => 'danger',
                        'message' => _l('cong_update_false')
                    ]);die();      
        }
    }
    public function payment_update($id)
    {
        $get_payment = get_table_where('tblpayment_order',array('id'=>$id),'','row');
        $success = false;
        $alert_type = 'warning';
        $message    = _l('Cập nhật không thành công');
        if ($this->input->post()) {
            $data = $this->input->post();
            $_data['client'] = $data['client'];
            $_data['id_order'] = $data['id_order'];
            $_data['date'] = to_sql_date($data['date'],true);
            $_data['payment_modes'] = $data['payment_mode'];
            $_data['left_total'] = str_replace(',', '', $data['left']);
            $_data['total_voucher'] = str_replace(',', '', $data['total']);
            $_data['total_voucher_received'] = str_replace(',', '', $data['payment']);
            $_data['note'] = $data['note'];
            $_data['account_business'] = $data['account_business'];
            $_data['account_information'] = $data['account_information'];
            $_data['currency'] = $data['currency'];
            if($data['currency'] != $get_payment->currency){
            $currencies = get_table_where('tblcurrencies',array('id'=>$data['currency']),'','row');
            $_data['currency_vnd'] = $currencies->amount_to_vnd;
            }
            $this->db->where('id',$id);
            if(!empty($data['client'])&&($data['client'] != $get_payment->client))
            {
            $_data['date_client'] = date('Y-m-d H:i:s');
            $_data['staff_client'] = get_staff_user_id();
            }
            if(!empty($data['id_order'])&&empty($get_payment->id_order))
            {
            $_data['date_order'] = date('Y-m-d H:i:s');
            $_data['staff_order'] = get_staff_user_id();   
            }
            
            $id_pay = $this->db->update('tblpayment_order',$_data);
            
            if($id_pay)
            {

                if(!empty($_data['id_order'])){
                if(!empty($get_payment->id_order)){
                $get_payments = $get_payment->total_voucher - $_data['total_voucher'];
                $order = get_table_where('tblorders',array('id'=>$_data['id_order']),'','row');
                
                $money_paid =  $order->money_paid -  $get_payments;
                }else
                {
                $order = get_table_where('tblorders',array('id'=>$_data['id_order']),'','row');
                
                $money_paid =  $order->money_paid + $_data['total_voucher'];    
                }
                $this->db->update('tblorders',array('money_paid'=>$money_paid),array('id'=>$_data['id_order']));
                
                $count_order = get_table_where('tblpayment_order',array('id_order'=>$_data['id_order']));
                if(count($count_order) == 1)
                {
                    $this->db->update('tblorders_step',array('date_create'=>date('Y-m-d H:i:s'),'id_staff'=>get_staff_user_id(),'active'=>1),array('order_by'=>2,'id_orders'=>$_data['id_order']));
                }
                }
            $success = true;
            $alert_type = 'success';
            $message    = _l('Cập nhật thành công');
            }
        }
        echo json_encode(array(
            'success' => $success, 
            'alert_type' => $alert_type,
            'message' => $message
        ));die;
    }
    public function payment()
    {
        $success = false;
        $alert_type = 'warning';
        $message    = _l('ch_added_successfuly_not');
        if ($this->input->post()) {
            $data = $this->input->post();
            $_data['client'] = $data['client'];
            $_data['id_order'] = $data['id_order'];
            $_data['date'] = to_sql_date($data['date'],true);
            $_data['date_create'] = date('Y-m-d H:i:s');
            $_data['staff_id'] = get_staff_user_id();
            $_data['payment_modes'] = $data['payment_mode'];
            $_data['left_total'] = str_replace(',', '', $data['left']);
            $_data['total_voucher'] = str_replace(',', '', $data['total']);
            $_data['total_voucher_received'] = str_replace(',', '', $data['payment']);
            $_data['note'] = $data['note'];
            $order = get_table_where('tblorders',array('id'=>$_data['id_order']),'','row');
            $_data['account_business'] = $data['account_business'];
            $_data['account_information'] = $data['account_information'];
            $_data['currency'] = $data['currency'];
            $currencies = get_table_where('tblcurrencies',array('id'=>$data['currency']),'','row');
            $_data['currency_vnd'] = $currencies->amount_to_vnd;
            // currency_vnd
            $_data['prefix'] = get_option('prefix_payment_order');
            $_data['code'] = sprintf('%06d', ch_getMaxID('id', 'tblpayment_order') + 1);
            if(!empty($data['client']))
            {
            $_data['date_client'] = date('Y-m-d H:i:s');
            $_data['staff_client'] = get_staff_user_id();
            }
            if(!empty($data['id_order']))
            {
            $_data['date_order'] = date('Y-m-d H:i:s');
            $_data['staff_order'] = get_staff_user_id();   
            }
            $this->db->insert('tblpayment_order',$_data);

            $id_pay = $this->db->insert_id();
            if($id_pay)
            {
                if(!empty($_data['id_order'])){
                $order = get_table_where('tblorders',array('id'=>$_data['id_order']),'','row');
                
                $money_paid =  $order->money_paid + $_data['total_voucher'];
                $this->db->update('tblorders',array('money_paid'=>$money_paid),array('id'=>$_data['id_order']));
                
                $count_order = get_table_where('tblpayment_order',array('id_order'=>$_data['id_order']));
                if(count($count_order) == 1)
                {
                    $this->db->update('tblorders_step',array('date_create'=>date('Y-m-d H:i:s'),'id_staff'=>get_staff_user_id(),'active'=>1),array('order_by'=>2,'id_orders'=>$_data['id_order']));
                }
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
    public function update_status($id,$status)
    {
        if(!empty($id))
        {
            $this->db->where('id', $id);
            $orders = $this->db->get(db_prefix().'orders')->row();
            if(!empty($orders))
            {
                if($status != -1 && $status != -2 && $status != -3)
                {
                    $produce = GetProducedure($orders->status);
                    if($produce->id == $status)
                    {
                        $this->db->where('id', $id);
                        $success = $this->db->update('tblorders', ['status' => $status]);
                        if($success)
                        {
                            $this->db->insert('tblorders_step', [
                                'id_orders' => $id,
                                'id_procedure' => $status,
                                'date_create' => date('Y-m-d H:i:s'),
                                'id_staff' => get_staff_user_id(),
                                'name_procedure' => $produce->name,
                                'active' => 1,
                                'order_by' => $produce->orders
                            ]);
                            $id_step = $this->db->insert_id();
                            if(!empty($id_step))
                            {
                                $this->db->where('id_orders', $id);
                                $this->db->where('id !=', $id_step);
                                $this->db->where('id_procedure != ', $status);
                                $this->db->update('tblorders_step', ['active' => 0]);
                            }

                            $produce_next = GetProducedure($status);
                            if(empty($produce_next))
                            {
                                $this->db->where('id', $id);
                                $success = $this->db->update('tblorders', ['status' => '-1']);
                            }
                        }
                    }
                }
                else if($status != -1)
                {
                    $this->db->where('id', $id);
                    $success = $this->db->update('tblorders', ['status' => $status]);
                    if($success)
                    {
                        $this->db->select('max(order_by) as max_order');
                        $this->db->where('id_orders', $id);
                        $max_step = $this->db->get('tblorders_step')->row();
                        $this->db->insert('tblorders_step', [
                            'id_orders' => $id,
                            'id_procedure' => $status,
                            'date_create' => date('Y-m-d H:i:s'),
                            'id_staff' => get_staff_user_id(),
                            'name_procedure' => $status == -2 ? _l('cong_orders_delay') : _l('cong_orders_cancel'),
                            'active' => 1,
                            'order_by' => ($max_step->max_order + 1)
                        ]);
                        $id_step = $this->db->insert_id();
                        if(!empty($id_step))
                        {
                            $this->db->where('id_orders', $id);
                            $this->db->where('id != ', $id_step);
                            $this->db->where('id_procedure != ', $status);
                            $this->db->update('tblorders_step', ['active' => 0]);
                        }
                    }
                }
            }
        }
    }
    public function get_order_datail($id='')
    {
        echo json_encode(get_table_where('tblorders',array('id'=>$id),'','row'));
    }
    public function SearchOrder($id='')
    {
       $data = [];
        $search = $this->input->get('term');
        $client = $this->input->get('client');
            $this->db->select('
                    id as id,
                    CONCAT(prefix,code) as text'
            , false);

            if(!empty($search))
            {
                $this->db->group_start();
                $this->db->like('CONCAT(prefix,code)', $search);
                $this->db->group_end();
                $this->db->order_by('id', 'DESC');
                $this->db->limit(50);
                $this->db->where('client', $client);
                $this->db->where('money_paid < grand_total');
                $items = $this->db->get('tblorders')->result_array();
                if(!empty($items)) {
                    $data['results'] = $items;
                }
            }else{
            
                if($id > 0) {
                    $this->db->group_start();
                    $this->db->where('id', $id);
                    $this->db->group_end();
                    $this->db->order_by('id', 'DESC');
                    $this->db->limit(50);
                    $this->db->where('client', $client);
                    $this->db->where('money_paid < grand_total');
                    $items = $this->db->get('tblorders')->row();
                    if(!empty($items)) {
                        $data['results'] = $items;
                    }
                }else
                {
                    $this->db->order_by('id', 'DESC');
                    $this->db->limit(50);
                    $this->db->where('client', $client);
                    $this->db->where('money_paid < grand_total');
                    $items = $this->db->get('tblorders')->result_array();
                    if(!empty($items)) {
                        $data['results'] = $items;
                    }
                }
            }
            echo json_encode($data);die();
    }   
    public function SearchClient($id='')
    {
       $data = [];
        $search = $this->input->get('term');
            $this->db->select('
                    userid as id,
                    tblclients.name_system as text'
            , false);


            if(!empty($search))
            {
                $this->db->group_start();
                $this->db->like('CONCAT(prefix_client,code_client)', $search);
                $this->db->or_like('tblclients.name_system', $search);
                $this->db->group_end();
                $this->db->order_by('name_system', 'DESC');
                $this->db->limit(50);
                $items = $this->db->get('tblclients')->result_array();
                if(!empty($items)) {
                    $data['results'] = $items;
                }
            }else {
            if($id > 0) {
                $this->db->group_start();
                $this->db->where('userid', $id);
                $this->db->group_end();
                $this->db->order_by('name_system', 'DESC');
                $this->db->limit(50);
                $items = $this->db->get('tblclients')->row();
                if(!empty($items)) {
                    $data['results'] = $items;
                }
                }else
                {
                $this->db->order_by('name_system', 'DESC');
                $this->db->limit(50);
                $items = $this->db->get('tblclients')->result_array();
                if(!empty($items)) {
                    $data['results'] = $items;
                }    
                }
            }
            
            echo json_encode($data);die();
    }

    public function get_order($id='')
    {
       $string_option="<option></option>";
       $order = get_table_where('tblorders',array('client'=>$id,'money_paid < grand_total'));
       foreach ($order as $key => $value) {
           $left = $value['grand_total'] - $value['money_paid'];
           $left_international = $value['grand_total_international'] - $value['money_paid_international'];
           $string_option.='<option left-usd='.$left_international.' left-id='.$left.' value="'.$value['id'].'">'.$value['prefix'].$value['code'].'</option>';
       }
       echo ($string_option);die();
    }
    public function delete($id)
    {
        if (!is_admin()) {
            access_denied('Delete Import');
        }

        $ktr = get_table_where('tblpayment_order',array('id'=>$id),'','row');
        $response = $this->db->delete('tblpayment_order',array('id'=>$id));
        if(!empty($ktr->id_order)&&($ktr->staff_cancel != 1)){
        $ktr_order = get_table_where('tblorders',array('id'=>$ktr->id_order),'','row');

        if($ktr->currency == 4)
        {
        $total = $ktr_order->money_paid - $ktr->total_voucher_received;
        $this->db->update('tblorders',array('money_paid'=>$total),array('id'=>$ktr->id_order));
        }else
        {  
        $money_paid_international = $ktr_order->money_paid_international - $ktr->total_voucher_received;
        $this->db->update('tblorders',array('money_paid_international'=>$money_paid_international),array('id'=>$ktr->id_order));
        }
        }
        $ktr_payment = get_table_where('tblpayment_order',array('id_order'=>$ktr->id_order),'','row');
        if(empty($ktr_payment))
        {
        $this->db->update('tblorders_step',array('date_create'=>NULL,'id_staff'=>NULL,'active'=>0),array('order_by'=>2,'id_orders'=>$ktr->id_order));    
        }
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
    public function get_code($id='')
    {
        echo json_encode(get_table_where('tblpayment_order',array('id'=>$id),'','row'));die;
    }

}