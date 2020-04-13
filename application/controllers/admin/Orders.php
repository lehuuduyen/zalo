<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Orders extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('orders_model');
        $this->load->model('quotes_orders_model');
        $this->load->model('products_model');
    }

    public function index()
    {
        if (!has_permission('orders', '', 'view')) {
            if (!has_permission('import', '', 'create')) {
                access_denied('import');
            }
        }
        $data['title']          = _l('cong_orders');
        $procedure = get_table_where(db_prefix().'procedure_client', [
            'type' => 'orders'
        ] ,'', 'row');
        $this->db->where('id_detail', $procedure->id);
        $this->db->order_by('orders', 'ASC');
        $data['procedure_detail'] = $this->db->get(db_prefix().'procedure_client_detail')->result_array();
        $this->load->view('admin/orders/manage', $data);
    }

    public function table()
    {
        if (!has_permission('orders', '', 'view')) {
                ajax_access_denied();
        }
        $draft = $this->input->get('draft');
        $this->app->get_table_data('orders', ['draft' => $draft]);
    }

    public function table_detail()
    {
        if (!has_permission('orders', '', 'view')) {
                ajax_access_denied();
        }
        $this->app->get_table_data('orders_detail');
    }

    public function detail($id = '')
    {
        if($this->input->post())
        {
            $data  = $this->input->post();
            $object = $data['object'];
            unset($data['object']);

            $object = explode('_', $object);
            $type_object = $object[0];
            $id_object = $object[1];
            //bổ xung chuyễn KHTN sang khách hàng chính thức
            if($type_object == 'lead') {
                $this->db->where('id', $id_object);
                $lead = $this->db->get('tblleads')->row();
                if(!empty($lead)) {
                    $this->db->where('leadid', $lead->id);
                    $ktClient = $this->db->get('tblclients')->row();
                    if(empty($ktClient)) {
                        $first_date = strtotime(date('Y-m-d'));
                        $second_date = strtotime($lead->date_contact);
                        $datediff = abs($first_date - $second_date);
                        $leadtime =  floor($datediff / (60*60*24));

                        $arrayAdd = [
                            'email_client' => $lead->email,
                            'birtday' => $lead->birtday,
                            'note' => $lead->description,
                            'code_system' => $lead->code_system,
                            'company' => $lead->company,
                            'fullname' => $lead->name,
                            'phonenumber' => $lead->phonenumber,
                            'id_facebook' => $lead->id_facebook,
                            'leadid' => $lead->id,
                            'zcode' => $lead->zcode,
                            'datecreated' => date('Y-m-d H:i:s'),
                            'addedfrom' => get_staff_user_id(),
                            'dt' => $lead->dt,
                            'kt' => $lead->kt,
                            'religion' => $lead->religion,
                            'marriage' => $lead->marriage,
                            'city' => $lead->city,
                            'district' => $lead->district,
                            'ward' => $lead->ward,
                            'date_contact' => $lead->date_contact,
                            'name_facebook' => $lead->name_facebook,
                            'link_facebook' => $lead->link_facebook,
                            'leadtime' => $leadtime
                        ];
                        $this->db->insert('tblclients', $arrayAdd);
                        if($this->db->insert_id())
                        {
                            $idClient = $this->db->insert_id();
                            CreateCode('client', $idClient);
                            $data['client'] = $idClient;
                        }
                        $this->db->where('lead', $lead->id);
                        $lead->info_group = $this->db->get('tbllead_value')->result_array();
                        if(!empty($lead->info_group))
                        {
                            foreach($lead->info_group as $kInfo => $vInfo)
                            {
                                $arrayInfo = [
                                    'id_detail' => $vInfo['id_detail'],
                                    'value' => $vInfo['value'],
                                    'client' => $idClient,
                                ];
                                $this->db->insert('tblclient_value', $arrayInfo);
                            }
                        }

                        $img_lead =  get_upload_path_by_type('lead') . $lead->id . '/';
                        $img_client = get_upload_path_by_type('customer') . $idClient . '/';
                        _maybe_create_upload_path($img_client);
                        @copy($img_lead.'small_'.$lead->lead_image, $img_client.'small_'.$lead->lead_image);
                        @copy($img_lead.'thumb_'.$lead->lead_image, $img_client.'thumb_'.$lead->lead_image);

                        $arrayUpdateClient = [
                            'client_image' => $lead->lead_image
                        ];

                        $this->db->where('userid', $idClient);
                        $this->db->update('tblclients', $arrayUpdateClient);

                        $this->db->where('lead_id' ,$lead->id);
                        $shipping = $this->db->get('tblshipping_lead')->result_array();
                        foreach($shipping as $key => $value)
                        {
                            $id_shipping = $value['id'];
                            unset($value['id']);
                            unset($value['lead_id']);
                            $value['client'] = $idClient;

                            $this->db->insert('tblshipping_client', $value);
                            $id_new_shipping = $this->db->insert_id();
                            if($data['shipping'] == $id_shipping)
                            {
                                $data['shipping'] = $id_new_shipping;
                            }
                        }
                    }
                }
            }
            else
            {
                $data['client'] = $id_object;
            }

            //end
            if(empty($id))
            {
                $data['note'] = $this->input->post('note', false);
                $success = $this->orders_model->add($data);
                if($success)
                {
                    if(!empty($idClient) && $type_object == 'lead')
                    {
                        ChangeObjectAssigned('lead', $lead->id, 'client', $idClient);
                        createCodeNameSystem('client', $idClient);
                    }

                    set_alert('success', _l('cong_add_true'));
                    redirect(admin_url('orders'));
                }
                else
                {
                    //Nếu thêm đơn hàng không thành công => xóa khách hàng
                    if(!empty($idClient)  && $type_object == 'lead') {
                        $this->db->where('userid', $idClient);
                        $this->db->delete('tblclients');
                    }
                    set_alert('danger', _l('cong_add_false'));
                    redirect(admin_url('orders/detail'));
                }
            }
            else
            {
                $data['note'] = $this->input->post('note', false);
                $success = $this->orders_model->update($id, $data);
                if($success)
                {
                    //Nếu thêm đơn hàng thành công => chuyễn địa chĩ giao hàng
                   if(!empty($idClient) && $type_object == 'lead')
                    {
                        ChangeObjectAssigned('lead', $lead->id, 'client', $idClient);
                        createCodeNameSystem('client', $idClient);
                    }

                    set_alert('success', _l('cong_update_true'));
                    redirect(admin_url('orders'));
                }
                else
                {
                    //Nếu thêm đơn hàng không thành công => xóa khách hàng
                    if(!empty($idClient)  && $type_object == 'lead') {
                        $this->db->where('userid', $idClient);
                        $this->db->delete('tblclients');
                    }
                    set_alert('danger', _l('cong_update_false'));
                    redirect(admin_url('orders/detail'));
                }

            }
        }
        else
        {
            if (!has_permission('orders', '', 'create')) {
                    ajax_access_denied();
            }
            $data['advisory_lead'] = array();
            if($id != '') {
                $data['title']          = _l('cong_update_orders');
                $data['orders'] = $this->orders_model->get($id);
                $data['orders']->name_status = $this->orders_model->get_status_orders($id, $data['orders']->status);
                $data['staff'] = get_table_where('tblstaff', '( active = 1 or staffid = '.$data['orders']->assigned.' )');

                $this->db->where('userid', $data['orders']->client);
                $data['clients'] = $this->db->get('tblclients')->result_array();

                $data['shipping'] = get_table_where('tblshipping_client', ['client' => $data['orders']->client]);

                $getDataMain = get_table_where('tblorders', ['id' => $id],'','row');

                if(!empty($getDataMain->advisory_lead_id)) {
                    $this->db->select('tbladvisory_lead.id, CONCAT(tbladvisory_lead.prefix, tbladvisory_lead.code, "-", tbladvisory_lead.type_code) as full_code');
                    $this->db->from('tbladvisory_lead');
                    $this->db->where('tbladvisory_lead.id', $getDataMain->advisory_lead_id);
                    $data['advisory_lead'] = $this->db->get()->result_array();
                }
            }
            else
            {
                if($this->input->get('convert_quotes')) // tại phiếu từ phiếu báo giá
                {
                    $id_quotes = $this->input->get('convert_quotes');
                    $this->db->where('id_quotes_orders', $id_quotes);
                    $orders = $this->db->get(db_prefix().'orders')->num_rows();
                    if($orders == 0)
                    {
                        $data['title']          = _l('cong_add_orders_to_quotes');
                        $data['orders'] = $this->quotes_orders_model->get($id_quotes);
                        if( $data['orders']->status != 2)
                        {
                            $data['orders']->name_status = _l('cong_add_orders_to_quotes');
                            $data['staff'] = get_table_where(db_prefix().'staff', '( active = 1 or staffid = '.$data['orders']->assigned.' )');
                            $this->db->where('userid', $data['orders']->client);
                            $data['clients'] = $this->db->get(db_prefix().'clients')->result_array();
                            $data['shipping'] = get_table_where('tblshipping_client', ['client' => $data['orders']->client]);
                            $data['convert_quotes'] = $id_quotes;
                        }
                        else
                        {
                            set_alert('danger', _l('cong_quotes_orders_cancel'));
                            redirect(admin_url('quotes_orders'));
                        }
                    }
                    else
                    {
                        set_alert('danger', _l('cong_quotes_orders_isset_orders'));
                        redirect(admin_url('quotes_orders'));
                    }
                }
                else
                {
                    //Lấy khách hàng
                    $this->db->limit(10);
                    $data['clients'] = $this->db->get(db_prefix().'clients')->result_array();
                    //End lấy khách hàng

                    $data['staff'] = get_table_where(db_prefix().'staff', ['active' => 1]);
                    $data['title']          = _l('cong_add_orders');
                    $data['shipping'] = [];
                }
            }
            //Lấy khách hàng TN
            $this->db->limit(20);
            $wap_lead = $this->db->get('tblleads')->result_array();
            foreach ($wap_lead as $key => $value) {
                $checkExists = get_table_where('tblclients',array('leadid'=>$value['id']),'','row');
                if(!$checkExists) {
                    $data['wap_lead'][$key]['id'] = $value['id'];
                    $data['wap_lead'][$key]['name_system'] = $value['name_system'];
                    $data['wap_lead'][$key]['company'] = $value['company'];
                }
            }
            $data['dataCurrency'] = get_table_where('tblcurrencies');
            $data['dataAdvisory'] = get_table_where('tbladvisory_apply');

	        $data['mode_payment'] = get_table_where('tblpayment_modes', ['active' => 1]);
            //End lấy khách hàng TN
            $this->load->view('admin/orders/detail', $data);
        }
    }

    public function getItemsAjax()
    {
        $search = $this->input->post('q');
        $limit = 100;
        if(!empty($search))
        {
            $this->db->select('id, name, code, price, avatar, "items" as type_items');
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('code', $search);
            $this->db->group_end();
            $this->db->limit($limit);
            $items = $this->db->get('tblitems')->result_array();

            $group_items = [['id' => 'group', 'name' => lang('ch_items'), 'code' => lang('ch_items'), 'price' => 0, 'avatar' => false, 'type_items' => "items"]];
            $list = array_merge($group_items, $items);

            $this->db->select('id, name, code, price_sell as price, CONCAT("uploads/products/", "", images, "") as avatar, "products" as type_items');
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('code', $search);
            $this->db->group_end();
            $this->db->limit($limit);
            $products = $this->db->get('tbl_products')->result_array();
            $group_products = [['id' => 'group', 'name' => lang('tnh_products'), 'code' => lang('tnh_products'), 'price' => 0, 'avatar' => false, 'type_items' => "items"]];
            $list = array_merge($list, $group_products);
            $list = array_merge($list, $products);

            echo json_encode($list);
            die();
        }
        echo json_encode([]);die();
    }

    public function getCustomerAjax()
    {
        $search = $this->input->post('q');
        if(!empty($search))
        {
            $this->db->select('userid, name_system, concat(prefix_client,code_client," - ",code_type) as full_code');
            $this->db->like('name_system', $search);
	        $this->db->order_by('datecreated', 'DESC');
            $customer = $this->db->get('tblclients')->result_array();
            echo json_encode($customer);die();
        }
        echo json_encode([]);die();
    }

    public function AStatusAdvisory()
    {
        if ($this->input->post()) {
            $id=$this->input->post('id');
            $idd=$this->input->post('idd');
            $data =array(
                'statusActive'=>$idd,
            );
            $this->db->where('id',$id);
            $success=$this->db->update('tblorders_items',$data);
        }
        if($success) {
            echo json_encode(array(
                'alert_type' => 'success',
                'message' => _l('Chuyển thành công')
            ));
        }
        else
        {
            echo json_encode(array(
                'alert_type' => 'warning',
                'message' => _l('Chuyển không thành công')
            ));
        }
        die;
    }
    //Cập nhật trạng thái
    public function update_status($id = '', $status = '', $id_detail = '')
    {
        if($id == '' && $status == '' && $id_detail == '') {
            $id = $this->input->post('id');
            $id_detail = $this->input->post('id_detail');
            $status = $this->input->post('status');
        }
        if(!empty($id))
        {
            $this->db->where('id', $id);
            $orders = $this->db->get('tblorders')->row();
            if(!empty($orders))
            {
                if($status != -1 && $status != -2 && $status != -3)
                {
                    $this->db->where('id', $id);
                    $success = $this->db->update('tblorders', ['status' => $status]);
                    if($success)
                    {
                        $this->db->where('id_orders', $id);
                        $this->db->where('id_procedure', $status);
                        $this->db->where('id_orders_item', $id_detail);
                        $orders_step = $this->db->get('tblorders_step')->row();

                        if(!empty($orders_step))
                        {
                            $this->db->where('id', $orders_step->id);
                            $success = $this->db->update('tblorders_step', [
                                'date_create' => date('Y-m-d H:i:s'),
                                'active' => 1,
                                'id_staff' => get_staff_user_id()
                            ]);

                            if(!empty($success))
                            {
                                $this->db->where('id_orders', $id);
                                $this->db->where('id_procedure > 0');
                                $this->db->where('id_orders_item', $id_detail);
                                $this->db->where('active', 0);
                                $this->db->where('order_by <', $orders_step->order_by);
                                $order_step_before = $this->db->update('tblorders_step',[
                                    'date_create' => date('Y-m-d H:i:s'),
                                    'active' => 1,
                                    'id_staff' => get_staff_user_id()
                                ]);

                                $this->db->where('id_orders', $id);
                                $this->db->where('id_procedure < 0');
                                $this->db->where('id_orders_item', $id_detail);
                                $this->db->where('active', 1);
                                $this->db->where('order_by <', $orders_step->order_by);
                                $this->db->update('tblorders_step',[
                                    'active' => 0
                                ]);

                                $this->db->where('id_orders', $id);
                                $this->db->where('id_procedure > 0');
                                $this->db->where('id_orders_item', $id_detail);
                                $this->db->where('active', 1);
                                $this->db->where('order_by >', $orders_step->order_by);
                                $this->db->update('tblorders_step',[
                                    'active' => 0,
                                    'date_create' => NULL,
                                    'id_staff' => NULL
                                ]);
                            }

                            echo json_encode([
                                'success' => true,
                                'alert_type' => 'success',
                                'message' => _l('cong_change_status_true')
                            ]);die();
                        }
                    }

                    echo json_encode([
                        'success' => false,
                        'alert_type' => 'danger',
                        'message' => _l('cong_change_status_false')
                    ]);die();
                }
                else
                {
                    $this->db->where('id', $id);
                    $success = $this->db->update('tblorders', ['status' => $status]);
                    if($success)
                    {
                        $this->db->select('max(order_by) as max_order');
                        $this->db->where('id_orders', $id);
                        $this->db->where('active', 1);
                        $max_step = $this->db->get('tblorders_step')->row();

                        $this->db->insert('tblorders_step', [
                            'id_orders' => $id,
                            'id_procedure' => $status,
                            'id_orders_item' => $id_detail,
                            'date_create' => date('Y-m-d H:i:s'),
                            'id_staff' => get_staff_user_id(),
                            'name_procedure' => $status == -2 ? _l('cong_orders_delay') : ($status == -3 ? _l('cong_orders_cancel') : _l('finished')),
                            'active' => 1,
                            'order_by' => ($max_step->max_order.'.'.time())
                        ]);

                        $id_step = $this->db->insert_id();

                        if(!empty($id_step))
                        {
                            echo json_encode([
                                'success' => true,
                                'alert_type' => 'success',
                                'message' => _l('cong_change_status_true')
                            ]);die();
                        }
                        
                    }
                }
            }
        }
        echo json_encode([
            'success' => false,
            'alert_type' => 'danger',
            'message' => _l('cong_data_change_pls')
        ]);die();
    }

    public function restore_orders()
    {
        if($this->input->post('id'))
        {
            $id = $this->input->post('id');
            $id_detail = $this->input->post('id_detail');
            $status = $this->input->post('status');
            if(!empty($id))
            {
                $this->db->where('id', $id);
                $orders = $this->db->get('tblorders')->row();
                if( ($orders->status == -2 || $orders->status == -3) && $orders->status == $status)
                {
                    $this->db->where('id_orders', $id);
                    if(!empty($id_detail))
                    {
                        $this->db->where('id_orders_item', $id_detail);
                    }
                    $this->db->where('id_procedure !='.$status);
                    $this->db->order_by('order_by', 'DESC');
                    $orders_step = $this->db->get('tblorders_step')->row();

                    $this->db->where('id', $id);
                    $success = $this->db->update('tblorders', [
                    	'status' => !empty($orders_step->id_procedure) ? $orders_step->id_procedure: '0'
                    ]);
                    if($success)
                    {
                        $this->db->where('id_orders', $id);
	                    if(!empty($id_detail))
	                    {
		                    $this->db->where('id_orders_item', $id_detail);
	                    }
                        $this->db->where('id_procedure', $status);
                        $this->db->delete('tblorders_step');
                        echo json_encode([
                            'success' => true,
                            'alert_type' => 'success',
                            'message' => _l('cong_change_status_true')
                        ]);die();
                    }
                }
                else
                {
                    echo json_encode([
                        'success' => false,
                        'alert_type' => 'danger',
                        'message' => _l('cong_data_change_pls')
                    ]);die();
                }
            }
            echo json_encode([
                'success' => false,
                'alert_type' => 'danger',
                'message' => _l('cong_change_status_false')
            ]);die();
        }
    }

    public function delete_orders()
    {
        $id = $this->input->post('id');
        if(!empty($id))
        {
            $this->db->where('id', $id);
            $orders = $this->db->get('tblorders')->row();
            $this->db->where('id', $id);
            $success = $this->db->delete('tblorders');
            if(!empty($success))
            {

                $this->db->where('id_orders', $id);
                $this->db->delete('tblorders_detail_shipping');

                $this->db->where('id_orders', $id);
                $this->db->delete('tblorders_items');

                $this->db->where('id_orders', $id);
                $this->db->delete('tblorders_step');
                if(!empty($orders->id_quotes_orders))
                {
                    $this->db->where('id', $orders->id_quotes_orders);
                    $this->db->update('tblquotes_orders', ['status' => 0]);
                }

                //tnh
                if (checkModule('quotes')) {
                    $this->db->where('order_id', $id);
                    $this->db->update('tbl_quotes', ['order_id' => 0]);
                }
                //
                echo json_encode([
                    'success' => true,
                    'alert_type' => 'success',
                    'message' => _l('cong_delete_true')
                ]);die();

            }
        }
        echo json_encode([
            'success' => false,
            'alert_type' => 'danger',
            'message' => _l('cong_delete_false')
        ]);die();
    }

    public function loadViewOrder($id = "")
    {
        if(!empty($id))
        {
            $data['title'] = _l('cong_detail_orders');
            $data['orders'] = $this->orders_model->get_view($id);

            //hoàng crm bổ xung
            $this->db->select('tblpayment_order. *, tblpayment_modes.name as name_pay_moders');
            $this->db->where('id_order', $id);
            $this->db->join('tblpayment_modes', 'tblpayment_modes.id = tblpayment_order.payment_modes', 'left');
            $data['payment'] = $this->db->get('tblpayment_order')->result_array();

            $get_currencies_id = get_table_where('tblorders',array('id'=>$id),'','row');
            $this->db->where('id', $get_currencies_id->currencies_id);
            $currencies = $this->db->get('tblcurrencies')->row();
            if(!empty($currencies))
            {
                $data['total_international'] = 0;
                $data['total_cost_trans_international'] = 0;
                $data['grand_total_international'] = 0;
                $data['money_paid_international'] = 0;
                if(!empty($get_currencies_id->total_international)) {
                    $data['total_international'] = app_format_money($get_currencies_id->total_international, $currencies->name);
                }
                if(!empty($get_currencies_id->total_cost_trans_international)) {
                    $data['total_cost_trans_international'] = app_format_money($get_currencies_id->total_cost_trans_international, $currencies->name);
                }
                if(!empty($get_currencies_id->grand_total_international)) {
                    $data['grand_total_international'] = app_format_money($get_currencies_id->grand_total_international, $currencies->name);
                }
                if(!empty($get_currencies_id->money_paid_international)) {
                    $data['money_paid_international'] = app_format_money($get_currencies_id->money_paid_international, $currencies->name);
                }
            }
            //end
            $data['orders']->name_status = $this->orders_model->get_status_orders($id, $data['orders']->status);
            echo json_encode(['success' => true, 'data' => $this->load->view('admin/orders/view_modal', $data , true)]);die();
        }
        echo json_encode(['success' => false, 'alert_type' => 'danger', 'message' => _l('cong_found_data')]);die();
    }

    public function getShipping()
    {
        $client = $this->input->post('client');
        if(!empty($client))
        {
            $this->db->where('client', $client);
            $shipping_client = $this->db->get('tblshipping_client')->result_array();
            if(!empty($shipping_client))
            {
                echo json_encode($shipping_client);die();
            }
        }
        echo json_encode([]);die();
    }

    public function ViewModalShipping()
    {
        $id = $this->input->post('id');
        $type = $this->input->post('type');
        $idSelect = $this->input->post('idSelect');
        if(!empty($id) && !empty($type)) {
            echo json_encode([
                'data' => $this->load->view('admin/orders/Addshipping', ['client' => $id, 'type' => $type, 'idSelect' => $idSelect], true),
                'success' => true
                ]);die();
        }
        echo json_encode([
            'alert_type' => 'warning',
            'success' => false,
            'message' => _l('cong_not_found_client_shipping')
        ]);die();
    }

    public function SearchObjectItems($code = "")
    {
        $data = [];
        $search = $this->input->get('term');
        $limit_one = 100;
        $limit_all = 200;
        if(!empty($code))
        {
            $code = explode('_', $code);
        }
        if((empty($code) || !empty($code) && $code[0] == 'client'))
        {
            $this->db->select('
                concat("client_", userid) as id,
                tblclients.name_system as text,
                CONCAT("download/preview_image?path=uploads/clients/",userid, "/small_", tblclients.client_image, "") as img'
            , false);
            
            if (!empty($search))
            {
                $this->db->group_start();
                $this->db->like('tblclients.name_system', $search);
                $this->db->group_end();
            }
            if(!empty($code) && $code[0] == 'client')
            {
                $this->db->where('userid' , $code[1]);
            }
            $this->db->order_by('datecreated', 'DESC');
            $this->db->limit($limit_one);
            $clients = $this->db->get('tblclients')->result_array();
            if(!empty($clients))
            {
                if(!empty($code))
                {
                    $data['results'] = $clients[0];
                    echo json_encode($data);die();
                    
                }
                else
                {
                    $data['results'][] =
                        [
                            'text' => _l('cong_client'),
                            'children' => $clients
                        ];
                }   
            }
            $count_client = count($clients);
        }
        if(empty($code) || (!empty($code) && $code[0] == 'lead'))
        {
            $where = '';
            if(!empty($search))
            {
                $where = 'AND name_system like "%'.$search.'%"';
            }
            if(!empty($code) && $code[0] == 'lead')
            {
                $where = 'AND id = '.$code[1];
            }
            $lead = $this->db->query('
                        SELECT 
                            concat("lead_", id) as id,
                            tblleads.name_system as text,
                            CONCAT("download/preview_image?path=uploads/leads/",id, "/small_", tblleads.lead_image, "") as img
                        FROM tblleads
                        WHERE ( (NOT EXISTS (SELECT 1 FROM tblclients WHERE tblclients.leadid = tblleads.id)) ) '.$where.' 
                        ORDER BY dateadded DESC
                       LIMIT '.($limit_all - $count_client).'
                ')->result_array();
            
            if(!empty($lead)) {
                if(!empty($code))
                {
                    $data['results'] = $lead[0];
                    echo json_encode($data);die();
                    
                }
                else
                {
                    $data['results'][] =
                        [
                            'text' => _l('cong_lead'),
                            'children' => $lead
                        ];
                }   
            }
        }
        echo json_encode($data);die();
    }

    public function SearchCustomerSelect2($code = "")
    {
        $data = [];
        $search = $this->input->get('term');
        $limit_all = 200;


        $this->db->select('
            userid as id,
            tblclients.name_system as text,
            CONCAT("download/preview_image?path=uploads/clients/",userid, "/small_", tblclients.client_image, "") as img'
        , false);

        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('tblclients.name_system', $search);
            $this->db->group_end();
        }
        if(!empty($code) )
        {
            $this->db->where('userid' , $code);
        }
        $this->db->order_by('datecreated', 'DESC');
        $this->db->limit($limit_all);
        $clients = $this->db->get('tblclients')->result_array();
        if(!empty($clients))
        {
            if(!empty($code))
            {
                $data['results'] = $clients[0];
                echo json_encode($data);die();

            }
            else
            {
                $data['results'] = $clients;

            }
        }
        echo json_encode($data);die();
    }

    public function SearchUnit_ship($code = "")
    {
        $data = [];
        $search = $this->input->get('term');
        $limit_all = 200;
        $this->db->select('
            id,
            name as text'
        , false);

        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->group_end();
        }
        if(!empty($code) )
        {
            $this->db->where('id' , $code);
        }
        $this->db->where('type', 'ship');
        $this->db->order_by('name', 'DESC');
        $this->db->limit($limit_all);
        $combobox = $this->db->get('tblcombobox_client')->result_array();
        if(!empty($combobox))
        {
            if(!empty($code))
            {
                $data['results'] = $combobox[0];
                echo json_encode($data);die();

            }
            else
            {
                $data['results'] = $combobox;

            }
        }
        echo json_encode($data);die();
    }

    public function getAdvisory_lead_and_shipping()
    {
        $object = $this->input->post('object');
        if(!empty($object))
        {
            $object = explode('_', $object);

            $type = $object[0];
            $id = $object[1];
            if($type == 'lead')
            {
                $this->db->select('tbladvisory_lead.id, concat(COALESCE(prefix), COALESCE(code)) as full_code');
                $this->db->where('type_object', 'lead');
                $this->db->where('id_object', $id);
                $this->db->join('tblprocedure_advisory_lead', 'tblprocedure_advisory_lead.id_advisory = tbladvisory_lead.id and status_procedure = 6 and active = 1');
	            $this->db->group_by('tbladvisory_lead.id');
                $advisory = $this->db->get('tbladvisory_lead')->result_array();

                $this->db->where('lead_id', $id);
                $shipping_client = $this->db->get('tblshipping_lead')->result_array();
            }
            else if($type == 'client')
            {
                $clients = get_table_where('tblclients', ['userid' => $id], '', 'row');
                $this->db->select('tbladvisory_lead.id, concat(COALESCE(prefix), COALESCE(code)) as full_code');

                $this->db->group_start();
	                $this->db->group_start();
	                $this->db->where('type_object', 'client');
	                $this->db->where('id_object', $id);
	                $this->db->group_end();

		            if(!empty($clients->leadid))
		            {
			            $this->db->or_group_start();
			            $this->db->or_where('id_object', $clients->leadid);
			            $this->db->or_where('type_object', 'lead');
			            $this->db->group_end();
		            }
                $this->db->group_end();

				$this->db->group_by('tbladvisory_lead.id');
                $this->db->join('tblprocedure_advisory_lead', 'tblprocedure_advisory_lead.id_advisory = tbladvisory_lead.id and status_procedure = 6 and active = 1');
                $advisory = $this->db->get('tbladvisory_lead')->result_array();

                $this->db->where('client', $id);
                $shipping_client = $this->db->get('tblshipping_client')->result_array();
            }
            echo json_encode([
                'advisory' => $advisory,
                'shipping' => $shipping_client
            ]);die();
        }
        echo json_encode([]);die();
    }

    public function SearchProductItems($code = "")
    {
        $data = [];
        $search = $this->input->get('term');
        $limit_one = 50;
        $limit_all = 100;
        $this->db->select('
            concat("products_", id) as id,
            tbl_products.code as text,
            tbl_products.name as name,
            tbl_products.price_sell as price,
            CONCAT("uploads/products/", "", tbl_products.images, "") as img,
            "products" as type_items
            '
            , false);
        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('tbl_products.name', $search);
            $this->db->or_like('tbl_products.code', $search);
            $this->db->group_end();
        }
        $this->db->order_by('tbl_products.name', 'DESC');
        $this->db->limit($limit_one);
        $product = $this->db->get('tbl_products')->result_array();
        if(!empty($product))
        {
            $data['results'][] =
                [
                    'text' => _l('cong_ts_product'),
                    'children' => $product
                ];
        }


        $count_product = count($product);
        $this->db->select('
                concat("items_", id) as id,
                tblitems.code as text,
                tblitems.name as name,
                tblitems.price,
                images_product as img,
                "items" as type_items
                '
            , false);
        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('tblitems.name', $search);
            $this->db->or_like('tblitems.code', $search);
            $this->db->group_end();
        }
        $this->db->order_by('name', 'DESC');
        $this->db->limit(($limit_all - $count_product));
        $items = $this->db->get('tblitems')->result_array();
        if(!empty($items)) {
            $data['results'][] =
                [
                    'text' => _l('cong_ts_items'),
                    'children' => $items
                ];
        }

        echo json_encode($data);die();

    }

    public function GetItemsProduct()
    {
        $id = $this->input->post('id');
        if(!empty($id))
        {
            $id = explode('_', $id);
            if($id[0] == 'items')
            {
                $this->db->select('id, name, price');
                $this->db->where('id', $id[1]);
                $items = $this->db->get('tblitems')->row();
                echo $items->name.' - '. number_format_data($items->price);die();
            }
            else if($id[0] == 'products')
            {
                $this->db->select('id, name, price_sell as price');
                $this->db->where('id', $id[1]);
                $products = $this->db->get('tbl_products')->row();
                if(!empty($products))
                {
                    echo $products->name.' - '. number_format_data($products->price);die();
                }

            }
        }
        echo '';die();
    }

    //Chuyển từ đơn hàng nháp sáng đơn hàng chính thức

	public function moved_orders_primary()
	{
		$id = $this->input->post('id');
		if(!empty($id)) {
			$this->db->where('id', $id);
			$orders = $this->db->get('tblorders')->row();
			if(!empty($orders))
			{
				if(!empty($orders->draft))
				{
					if($orders->type_object_draft == 'client')
					{
						$this->db->where('id', $orders->id);
						$success = $this->db->update('tblorders', [
							'draft' => 0,
							'type_object_draft' => NULL,
							'id_object_draft' => NULL]);
					}
					else if($orders->type_object_draft == 'lead')
					{
						$Kt_client = get_table_where('tblclients', ['leadid' => $orders->id_object_draft], '', 'row');
						if(!empty($Kt_client))
						{
							$array_update  = [
								'draft' => 0,
								'client' => $Kt_client->userid,
								'type_object_draft' => NULL,
								'id_object_draft' => NULL
							];
							$array_update['prefix'] = get_option('prefix_c_orders');
							$this->db->where('id', $orders->id);
							$success = $this->db->update('tblorders', $array_update);
							if(!empty($success))
							{
								CreateCode('orders', $orders->id);
							}
						}
						else
						{
							$this->db->where('id', $orders->id_object_draft);
							$lead = $this->db->get('tblleads')->row();
							if(!empty($lead)) {
								$first_date = strtotime(date('Y-m-d'));
								$second_date = strtotime($lead->date_contact);
								$datediff = abs($first_date - $second_date);
								$leadtime =  floor($datediff / (60*60*24));

								$arrayAdd = [
									'email_client' => $lead->email,
									'birtday' => $lead->birtday,
									'note' => $lead->description,
									'code_system' => $lead->code_system,
									'company' => $lead->company,
									'fullname' => $lead->name,
									'phonenumber' => $lead->phonenumber,
									'id_facebook' => $lead->id_facebook,
									'leadid' => $lead->id,
									'zcode' => $lead->zcode,
									'datecreated' => date('Y-m-d H:i:s'),
									'addedfrom' => get_staff_user_id(),
									'dt' => $lead->dt,
									'kt' => $lead->kt,
									'religion' => $lead->religion,
									'marriage' => $lead->marriage,
									'city' => $lead->city,
									'district' => $lead->district,
									'ward' => $lead->ward,
									'date_contact' => $lead->date_contact,
									'name_facebook' => $lead->name_facebook,
									'link_facebook' => $lead->link_facebook,
									'leadtime' => $leadtime
								];
								$array_update = [];
								$this->db->insert('tblclients', $arrayAdd);
								if($this->db->insert_id())
								{
									$idClient = $this->db->insert_id();
									CreateCode('client', $idClient);
								}
								$this->db->where('lead', $lead->id);
								$lead->info_group = $this->db->get('tbllead_value')->result_array();
								if(!empty($lead->info_group))
								{
									foreach($lead->info_group as $kInfo => $vInfo)
									{
										$arrayInfo = [
											'id_detail' => $vInfo['id_detail'],
											'value' => $vInfo['value'],
											'client' => $idClient,
										];
										$this->db->insert('tblclient_value', $arrayInfo);
									}
								}

								$img_lead =  get_upload_path_by_type('lead') . $lead->id . '/';
								$img_client = get_upload_path_by_type('customer') . $idClient . '/';
								_maybe_create_upload_path($img_client);
								@copy($img_lead.'small_'.$lead->lead_image, $img_client.'small_'.$lead->lead_image);
								@copy($img_lead.'thumb_'.$lead->lead_image, $img_client.'thumb_'.$lead->lead_image);

								$arrayUpdateClient = [
									'client_image' => $lead->lead_image
								];

								$this->db->where('userid', $idClient);
								$this->db->update('tblclients', $arrayUpdateClient);

								ChangeObjectAssigned('lead', $lead->id, 'client', $idClient);
								createCodeNameSystem('client', $idClient);

								$shipping_lead = get_table_where('tblshipping_lead', ['lead_id' => $lead->id]);
								if(!empty($shipping_lead))
								{
									foreach($shipping_lead as $kS => $vS)
									{
										$this->db->insert('tblshipping_client', [
											'client' => $idClient,
											'name' => $vS['name'],
											'phone' => $vS['phone'],
											'address' => $vS['address'],
											'code_zip' => $vS['code_zip'],
											'address_primary' => $vS['address_primary'],
											'date_create' => $vS['date_create'],
											'create_by' => $vS['create_by'],
										]);
										$id_shipping = $this->db->insert_id();
										if($orders->shipping == $vS['id'])
										{
											$array_update['shipping'] = $id_shipping;
										}
									}
								}

								if (!empty($idClient))
								{
									$array_update['client'] = $idClient;
									$array_update['draft'] = 0;
									$array_update['type_object_draft'] = NULL;
									$array_update['id_object_draft'] = NULL;
									$array_update['prefix'] = get_option('prefix_c_orders');
									$this->db->where('id', $id);
									$success = $this->db->update('tblorders', $array_update);
									if(!empty($success))
									{
										CreateCode('orders', $id);
										$this->db->where('id', $lead->id);
										$this->db->update('tblleads', ['status' => '1']);
										updateTypecodeClient($idClient, 'DM');
										echo json_encode([
											'success' => true,
											'alert_type' => 'success',
											'id_facebook' => $lead->id_facebook,
											'message' => _l('cong_moved_orders_true')
										]);die();
									}

								}
							}
						}
					}

					if(!empty($success))
					{
						echo json_encode([
							'success' => true,
							'alert_type' => 'success',
							'message' => _l('cong_moved_orders_true')
						]);die();
					}
				}
				echo json_encode([
					'success' => false,
					'alert_type' => 'danger',
					'message' => _l('cong_moved_orders_false')
				]);die();
			}
			else
			{
				echo json_encode([
					'success' => false,
					'alert_type' => 'danger',
					'message' => _l('cong_not_found_orders')
				]);die();
			}
		}
	}

    //hoàng crm bổ xung
    public function getAdvisory_lead($id_obj = '', $type = '', $id_orders = '')
    {
        $getDataMain = array();

        $this->db->select('tbladvisory_lead.id, CONCAT(tbladvisory_lead.prefix, tbladvisory_lead.code, "-", tbladvisory_lead.type_code) as full_code');
        $this->db->from('tbladvisory_lead');
        $this->db->where('tbladvisory_lead.id_object', $id_obj);
        $this->db->where('tbladvisory_lead.type_object', $type);
        $dataKey = $this->db->get()->result_array();
        foreach ($dataKey as $key => $value) {
            if($id_orders == '') {
                $id_orders = 0;
            }
            $checkExists = get_table_where('tblorders',array('advisory_lead_id'=>$value['id'], 'id <>'=>$id_orders),'','row');
            if(!$checkExists) {
                $getDataMain[$key]['id'] = $value['id'];
                $getDataMain[$key]['full_code'] = $value['full_code'];
            }
        }
        echo json_encode($getDataMain);
    }

    public function getLeadAjax()
    {
        $search = $this->input->post('q');
        if(!empty($search))
        {
            $this->db->like('name_system1', $search);
            $wap_lead = $this->db->get('tblleads')->result_array();
            foreach ($wap_lead as $key => $value) {
                $checkExists = get_table_where('tblclients', array('leadid' => $value['id']),'','row');
                if(empty($checkExists)) {
                    $lead[$key]['id'] = $value['id'];
                    $lead[$key]['name_system'] = $value['name_system'];
                    $lead[$key]['company'] = $value['company'];
                }
            }
            if(!empty($lead))
            {
                echo json_encode($lead);die();
            }
        }
        echo json_encode([]);die();
    }

    public function getShippingLead()
    {
        $lead_id = $this->input->post('lead_id');
        if(!empty($lead_id))
        {
            $this->db->where('lead_id', $lead_id);
            $shipping_client = $this->db->get('tblshipping_client')->result_array();
            if(!empty($shipping_client))
            {
                echo json_encode($shipping_client);die();
            }
        }
        echo json_encode([]);die();
    }

    public function getCurrency()
    {
        $data = $this->input->post();
        if(!empty($data['id_currency']))
        {
            $this->db->where('id', $data['id_currency']);
            $currencies = $this->db->get('tblcurrencies')->row();
            if(!empty($currencies))
            {
                $currencies->total_cost_trans_currency = 0;
                $currencies->total_orders_currency = 0;
                $currencies->total_receipts_currency = 0;
                $currencies->c_guest_giving_currency = 0;
                $currencies->rest_collect_currency = 0;
                if($currencies->amount_to_vnd > 0) {
                    $currencies->total_cost_trans_currency = app_format_money($data['total_cost_trans'] / $currencies->amount_to_vnd, $currencies->name);
                    $currencies->total_orders_currency = app_format_money($data['total_orders'] / $currencies->amount_to_vnd, $currencies->name);
                    $currencies->total_receipts_currency = app_format_money($data['total_receipts'] / $currencies->amount_to_vnd, $currencies->name);
                    $currencies->c_guest_giving_currency = app_format_money($data['c_guest_giving'] / $currencies->amount_to_vnd, $currencies->name);
                    $currencies->rest_collect_currency = app_format_money((!empty($data['rest_collect']) ? $data['rest_collect'] : 0) / $currencies->amount_to_vnd, $currencies->name);

                }

                echo json_encode($currencies);die();
            }
        }
        echo json_encode([]);die();
    }
    //end
    //hau
    public function payment_order($id='')
    {
        $data['order']=get_table_where('tblorders',array('id'=>$id),'','row');
        $data['code'] = get_option('prefix_payment_order').'-'.sprintf('%06d', ch_getMaxID('id', 'tblpayment_order') + 1);
        $data['clients'] = $this->db->get(db_prefix().'clients')->result_array();
        $data['payment_modes'] = get_table_where('tblpayment_modes',array('active'=>1));
        $data['currencies'] = get_table_where('tblcurrencies');
        $data['account_business'] = get_table_where('tblaccount_business');
        $this->db->where('client',0);
        $this->db->or_group_start();
        $this->db->where('client',$data['order']->client);
        $this->db->where('id_order',0);
        $this->db->where('status_cancel',0);
        $this->db->group_end();
        $data['payment_order'] = $this->db->get('tblpayment_order')->result_array();
        $this->load->view('admin/orders/payment_modal',$data);
    }

    public function payment_order_view($id='')
    {
        $payment = get_table_where('tblpayment_order',array('id'=>$id),'','row');
        $data['payment'] = $payment;
        $data['order']=get_table_where('tblorders',array('id'=>$payment->id_order),'','row');
        $data['code'] = $payment->prefix.$payment->code;
        $data['clients'] = $this->db->get(db_prefix().'clients')->result_array();
        $data['payment_modes'] = get_table_where('tblpayment_modes',array('active'=>1));
        $data['currencies'] = get_table_where('tblcurrencies');
        $data['account_business'] = get_table_where('tblaccount_business');

        $this->load->view('admin/orders/payment_order_view',$data);
    }



}