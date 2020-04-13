<?php

header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');

class Care_of_clients extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    /* List all leads */
    public function index()
    {
        $data['title'] = _l('cong_care_of_clients');
        $this->db->select('tblprocedure_client_detail.*');
        $this->db->join('tblprocedure_client', 'tblprocedure_client.id = tblprocedure_client_detail.id_detail');
        $this->db->where('type', 'client');
        $data['client_detail'] = $this->db->get('tblprocedure_client_detail')->result_array();
        $this->load->view('admin/care_of_clients/manage', $data);
    }

    public function table($userid = "")
    {
        $this->app->get_table_data('care_of_clients', ['id_client' => $userid]);
    }

    public function table_tab($userid = "")
    {
        $this->app->get_table_data('care_of_clients_tab', ['id_client' => $userid]);
    }

    public function getModal()
    {
        if($this->input->post('id'))
        {
            $id = $this->input->post('id');
            // $id=6;
            $this->db->where('id', $id);
            $data['care_of_clients'] = $this->db->get('tblcare_of_clients')->row();

            $this->db->select('tblclients.*');
            $this->db->where('userid', $data['care_of_clients']->client);
            $data['clients'] = $this->db->get('tblclients')->result_array();

            $this->db->select('tblorders.id, concat(COALESCE(tblorders.prefix),"-",COALESCE(tblorders.code)) as full_code');
            $this->db->where('client', $data['care_of_clients']->client);
            $data['orders'] = $this->db->get('tblorders')->result_array();

            $this->db->select('group_concat(concat(COALESCE(type_items),"_",COALESCE(id_product))) as group_product');
            $this->db->group_by('id_care_of');
            $data['care_of_clients']->id_product = $this->db->get('tblcare_of_client_items')->row();
        }
        else
        {
            $data['orders'] = [];
            $data['product'] = [];

            $this->db->select('tblclients.*');
            $this->db->limit(200);
            $data['clients'] = $this->db->get('tblclients')->result_array();
        }

        $this->load->view('admin/care_of_clients/modal', $data);
    }
    
    public function detail()
    {
        if($this->input->post())
        {
            $data = $this->input->post();
            if(!empty($data['id']))
            {
                if(!empty($data['id_product']))
                {
                    $id_product = explode(',', $data['id_product']);
                }
                unset($data['id_product']);

                $id = $data['id'];
                unset($data['id']);
                $array_update = [
                    'client' => $data['client'],
                    'note' => $data['note'],
                    'solution' => $data['solution'],
                    'theme_of' => $data['theme_of']
                ];
                if(!empty($data['date']))
                {
                    $array_update['date'] = to_sql_date($data['date'], true);
                }

                $this->db->where('id', $id);
                if($this->db->update('tblcare_of_clients', $array_update))
                {
                    $product_not_delete = [];
                    foreach($id_product as $key => $value)
                    {
                        $products = explode('_', $value);
                        $this->db->where('type_items', $products[0]);
                        $this->db->where('id_product', $products[1]);
                        $this->db->where('id_care_of', $id);
                        $kt_product = $this->db->get('tblcare_of_client_items')->row();
                        if(empty($kt_product))
                        {
                            $this->db->insert('tblcare_of_client_items', [
                                'type_items' => $products[0],
                                'id_product' => $products[1],
                                'id_care_of' => $id
                            ]);
                            if($this->db->insert_id())
                            {
                                $product_not_delete[] = $this->db->insert_id();
                            }
                        }
                        else
                        {
                            $product_not_delete[] = $kt_product->id;
                        }
                    }
                    $this->db->where('id_care_of', $id);
                    if(!empty($product_not_delete))
                    {
                        $this->db->where_not_in('id', $product_not_delete);
                    }
                    $this->db->delete('tblcare_of_client_items');

                    echo json_encode([
                        'success' => true,
                        'alert_type' => 'success',
                        'message' => _l('cong_update_true')
                    ]);die();
                }
                echo json_encode([
                    'success' => false,
                    'alert_type' => 'danger',
                    'message' => _l('cong_update_false')
                ]);die();
            }
            else
            {
				//Update không làm thay đổi quy trình chăm sóc
                $id_product = $data['id_product'];
                if(!empty($data['id_product']))
                {
                    $id_product = explode(',', $data['id_product']);
                }
                unset($data['id_product']);
                $array_add = [
                    'client' => $data['client'],
                    'date' => to_sql_date($data['date'], true),
                    'date_create' => date('Y-m-d H:i:s'),
                    'create_by' => get_staff_user_id(),
                    'id_orders' => !empty($data['id_orders']) ? $data['id_orders'] :'',
                    'theme_of' => !empty($data['theme_of']) ? $data['theme_of'] :'',
                    'solution' => !empty($data['solution']) ? $data['solution'] :'',
                    'note' => !empty($data['note']) ? $data['note'] :''
                ];

                $theme_of = StatusThemeCare_of();
                $array_add['short_theme'] = $theme_of[$data['theme_of']]['short'];

                $this->db->insert('tblcare_of_clients', $array_add);
                if($this->db->insert_id())
                {
                    $id = $this->db->insert_id();
                    //Thêm Mã tư vấn cho Phiếu
                    CreateCode('care_of_clients', $id , '');

                    if(!empty($data['id_orders']))
                    {
                        $this->db->where('id', $data['id_orders']);
                        $Orders = $this->db->get('tblorders')->row();
                        ChangeTag_manuals('client', $data['client'], $Orders->advisory_lead_id , ['status_care_of']);
                    }

                    $GetProcedure = GetProcedure('client', NULL, true, [], 'orders asc');
                    if(!empty($GetProcedure->detail))
                    {
	                    $date_expected = to_sql_date($data['date'], true);
                    	foreach($GetProcedure->detail as $kD => $vD)
	                    {
		                    $leadtime = $vD->leadtime;
		                    $date_expected =  date("Y-m-d", strtotime("$date_expected +$leadtime day"));
		                    $arrayInsert = [
		                    	'id_care_of' => $id,
		                    	'status_procedure' => $vD->id,
		                    	'orders' => $vD->orders,
		                    	'not_procedure' => !empty($vD->not_procedure) ? $vD->not_procedure : 0,
		                    	'date_expected' => $date_expected,
		                    	'name_status' => $vD->name
		                    ];
		                    if(!empty($vD->active_start))
		                    {
			                    $arrayInsert['active'] = 1;
			                    $arrayInsert['date_create'] = date('Y-m-d H:i:s');
			                    $arrayInsert['create_by'] = get_staff_user_id();

			                    $this->db->where('id_care_of', $id);
			                    $this->db->where('orders < ', $vD->orders);
			                    $this->db->where('not_procedure != 1');
			                    $this->db->update('tblprocedure_care_of', [
			                    	'active' => 1,
			                    	'date_create' => date('Y-m-d H:i:s'),
			                    	'create_by' => get_staff_user_id()
			                    ]);
		                    }
		                    $this->db->insert('tblprocedure_care_of', $arrayInsert);
	                    }
                    }

                    //End thêm mã tư vấn cho phiếu
                    if(!empty($id_product))
                    {
                        foreach($id_product as $key => $value)
                        {
                            $products = explode('_', $value);
                            $this->db->insert('tblcare_of_client_items', [
                                'type_items' => $products[0],
                                'id_product' => $products[1],
                                'id_care_of' => $id
                            ]);
                        }
                    }

                    echo json_encode([
                        'success' => true,
                        'alert_type' => 'success',
                        'message' => _l('cong_add_true')
                    ]);die();
                }
                echo json_encode([
                    'success' => false,
                    'alert_type' => 'danger',
                    'message' => _l('cong_add_false')
                ]);die();
            }
        }
    }

    public function delete_care_of_clients()
    {
        if($this->input->post('id'))
        {
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            if($this->db->delete('tblcare_of_clients'))
            {
                $this->db->where('id_care_of', $id);
                $this->db->delete('tblcare_of_client_items');

	            $this->db->where('id_care_of', $id);
                $this->db->delete('tblprocedure_care_of');

                $this->db->where('id_care_of', $id);
                $this->db->delete('tblcare_of_detail_experience');
                echo json_encode([
                    'success' => true,
                    'alert_type' => 'success',
                    'message' => _l('cong_delete_true')
                ]);die();
            }
            echo json_encode([
                'success' => false,
                'alert_type' => 'danger',
                'message' => _l('cong_delete_false')
            ]);die();

        }
    }

    public function update_status()
    {
        if($this->input->post())
        {
            $data = $this->input->post();
            if(!empty($data['id']) && !empty($data['status_procedure']))
            {
                $this->db->where('id', $data['id']);
                $care_of_clients = $this->db->get('tblcare_of_clients')->row();
                if(!empty($care_of_clients))
                {
                    //Kiểm tra phiếu đã kế thúc chưa
                    if($care_of_clients->status_break == 1)
                    {
                        echo json_encode([
                            'success' => true,
                            'alert_type' => 'danger',
                            'message' => _l('cong_advisory_status_break')
                        ]);die();
                    }

                    //kiểm tra phiếu đã được duyệt qua chưa
                    $this->db->where('id_care_of', $data['id']);
                    $this->db->where('status_procedure', $data['status_procedure']);
                    $action_care_of = $this->db->get('tblprocedure_care_of')->row();
                    if(empty($action_care_of) || ($action_care_of->active == 1 && empty($action_care_of->not_procedure)))
                    {
                        echo json_encode([
                            'success' => true,
                            'alert_type' => 'danger',
                            'message' => _l('cong_update_isset')
                        ]);die();
                    }
                    else
                    {
                    	if(empty($action_care_of->not_procedure))
	                    {
		                    $this->db->where('id_care_of', $data['id']);
		                    $this->db->where('active != 1');
		                    $this->db->where('not_procedure', 0);
		                    $this->db->where('orders <'.$action_care_of->orders);
		                    $this->db->where('status_procedure', $data['status_procedure']);
		                    $action_care_of_before = $this->db->get('tblprocedure_care_of')->row();
		                    if(!empty($action_care_of_before))
		                    {
			                    echo json_encode([
				                    'success' => true,
				                    'alert_type' => 'danger',
				                    'message' => _l('cong_step_fail')
			                    ]);die();
		                    }

		                    //Duyệt trạng thái
		                    $this->db->where('id', $action_care_of->id);
		                    $success = $this->db->update('tblprocedure_care_of', [
			                    'create_by' => get_staff_user_id(),
			                    'date_create' => date('Y-m-d H:i:s'),
			                    'active' => 1
		                    ]);
		                    if(!empty($success))
		                    {
			                    addLog_care_of([
				                    'id_client' => $care_of_clients->client,
				                    'staff' => get_staff_user_id(),
				                    'id_procedure' => $data['status_procedure']
			                    ], 1);
			                    echo json_encode([
				                    'success' => true,
				                    'alert_type' => 'success',
				                    'message' => _l('cong_update_true')
			                    ]);die();
		                    }
	                    }
                    	else
	                    {
		                    $arrayUpdate = ['active' => 0, 'date_create' => NULL, 'create_by' => NULL];
	                    	if(empty($action_care_of->active))
		                    {
			                    $arrayUpdate = ['active' => 1, 'date_create' => date('Y-m-d H:i:s'), 'create_by' => get_staff_user_id()];
		                    }
		                    //Duyệt trạng thái
		                    $this->db->where('id', $action_care_of->id);
		                    $success = $this->db->update('tblprocedure_care_of', $arrayUpdate);
		                    if(!empty($success))
		                    {
			                    addLog_care_of([
				                    'id_client' => $care_of_clients->client,
				                    'staff' => get_staff_user_id(),
				                    'id_procedure' => $data['status_procedure']
			                    ], 2);
			                    echo json_encode([
				                    'success' => true,
				                    'alert_type' => 'success',
				                    'message' => _l('cong_update_true')
			                    ]);die();
		                    }
	                    }
                    }
                }
            }
        }
        echo json_encode([
            'success' => false,
            'alert_type' => 'danger',
            'message' => _l('cong_update_false')
        ]);die();
    }

    public function restore_care_of_clients()
    {
        if($this->input->post())
        {
            $data = $this->input->post();
            if(!empty($data['id']))
            {
                $id = $data['id'];
                $this->db->where('id', $id);
                $care_of_clients = $this->db->get('tblcare_of_clients')->row();
                if(!empty($care_of_clients))
                {
                    $this->db->where('id_care_of', $id);
                    $this->db->order_by('date_create', 'desc');
                    $action_care_of = $this->db->get('tblprocedure_care_of')->row();
                    if(!empty($action_care_of))
                    {
                        $this->db->where('id', $action_care_of->id);
                        if($this->db->delete('tblprocedure_care_of'))
                        {
                            $this->db->where('id_care_of', $id);
                            $this->db->order_by('date_create', 'desc');
                            $action_advisory_old = $this->db->get('tblprocedure_care_of')->row(); // lui lại bước để làm active
                            if(!empty($action_advisory_old))
                            {
                                $this->db->where('id', $action_advisory_old->id);
                                $this->db->update('tblprocedure_care_of', ['active' => 1]);
                            }
                            //Thêm vào lịch sử chăn sóc khách hàng
                            addLog_care_of([
                                'id_client' => $care_of_clients->client,
                                'staff' => get_staff_user_id(),
                                'id_procedure' => $action_care_of->status_procedure
                            ], 0);

                            $this->db->where('id', $action_care_of->status_procedure);
                            $client_detail = $this->db->get('tblprocedure_client_detail')->row(); // lấy quy trình cập nhật
                            if(!empty($client_detail))
                            {
                                $this->db->where('orders > ', $client_detail->orders);
                                $this->db->order_by('orders', 'asc');
                                $this->db->where('id_detail', $client_detail->id_detail);
                                $client_detail_next = $this->db->get('tblprocedure_client_detail')->row();
                                if(!empty($client_detail_next)) {
                                    $_date = $care_of_clients->date_expected;
                                    $date = date("Y-m-d", strtotime("$_date -$client_detail_next->leadtime day"));
                                    $this->db->where('id', $id);
                                    $this->db->update(db_prefix() . 'care_of_clients', ['date_expected' => $date]);
                                }

                                echo json_encode([
                                    'success' => true,
                                    'alert_type' => 'success',
                                    'message' => _l('cong_restore_advisory_true')
                                ]);die();
                            }
                            echo json_encode([
                                'success' => true,
                                'alert_type' => 'success',
                                'message' => _l('cong_restore_advisory_true')
                            ]);die();
                        }
                    }
                    echo json_encode([
                        'success' => true,
                        'alert_type' => 'danger',
                        'message' => _l('cong_data_not_isset')
                    ]);die();
                }
            }
            echo json_encode([
                'success' => false,
                'alert_type' => 'danger',
                'message' => _l('cong_update_false')
            ]);die();
        }
    }

    public function break_care_of()
    {
        $id = $this->input->post('id');
        if(!empty($id))
        {
            $status = $this->input->post('status');
            $this->db->where('id', $id);
            $care_of_clients = $this->db->get('tblcare_of_clients')->row();
            if(!empty($care_of_clients))
            {
                $this->db->where('id', $id);
                if($this->db->update('tblcare_of_clients' , [
                    'status_break' => $status
                ]))
                {
                    $this->db->where('id_care_of', $id);
                    $this->db->order_by('date_create', 'desc');
                    $action_care_of = $this->db->get('tblprocedure_care_of')->row();

                    if(!empty($care_of_clients->id_orders))
                    {
                        $orders = get_table_where('tblorders', ['id' => $care_of_clients->id_orders], '', 'row');
                        if(!empty($orders))
                        {
                            $this->db->where('id_orders', $care_of_clients->id_orders );
                            $this->db->where('status_break', 0);
                            $Numcare_of_clients = $this->db->get('tblcare_of_clients')->num_rows();
                            if(empty($Numcare_of_clients))
                            {
                                $this->db->where('advisory', $orders->advisory_lead_id);
                                $this->db->where('orders', $orders->id);
                                $this->db->update('tbltag_manuals', ['break' => 1]);
                            }
                            else
                            {
                                $this->db->where('advisory', $orders->advisory_lead_id);
                                $this->db->where('orders', $orders->id);
                                $this->db->update('tbltag_manuals', ['break' => 0]);
                            }
                        }
                    }

                    addLog_care_of([
                        'id_client' => $care_of_clients->client,
                        'staff' => get_staff_user_id(),
                        'id_procedure' => !empty($action_care_of->status_procedure) ? $action_care_of->status_procedure : NULL
                    ], 3);
                    if($status == 1)
                    {
                        echo json_encode([
                            'success' => true,
                            'alert_type' => 'success',
                            'message' => _l('cong_break_advisory_true')
                        ]);die();
                    }
                    else
                    {
                        echo json_encode([
                            'success' => true,
                            'alert_type' => 'success',
                            'message' => _l('cong_restore_care_of_true')
                        ]);die();
                    }
                }
            }
        }
        echo json_encode([
            'success' => false,
            'alert_type' => 'false',
            'message' => _l('cong_break_advisory_false')
        ]);die();
    }

    public function change_priority()
    {
        $id = $this->input->post('id');
        $priority = $this->input->post('priority');
        if(!empty($id) && isset($priority))
        {
            $this->db->where('id', $id);
            $success = $this->db->update('tblcare_of_clients', ['priority' => $priority]);
            if(!empty($success))
            {
                echo json_encode(['success' => true, 'alert_type' => 'success', 'message' => _l('cong_change_status_true')]);die();
            }
        }
        echo json_encode(['success' => false, 'alert_type' => 'danger', 'message' => _l('cong_change_status_false')]);die();
    }

    public function getOrderAjax()
    {
        $search = $this->input->post('q');
        $client = $this->input->post('client');
        if(!empty($search))
        {
            $this->db->select('id,concat(COALESCE(prefix), COALESCE(code)) as full_code');
            $this->db->group_start();
            $this->db->or_like('concat(COALESCE(prefix), COALESCE(code))', $search);
            $this->db->or_like('prefix', $search);
            $this->db->or_like('code', $search);
            $this->db->group_end();
            if(!empty($client))
            {
                $this->db->where('client', $client);
            }
            $orders = $this->db->get('tblorders')->result_array();
            echo json_encode($orders);die();
        }
        echo json_encode([]);die();
    }

    public function getProductAjax($id = "")
    {
        $data = [];
        $search = $this->input->get('term');
        $id_orders = $this->input->get('orders');
        $limit_one = 200;
        $limit_all = 400;
        $this->db->select('
            concat("products_", tbl_products.id) as id,
            tbl_products.name as text,
            tbl_products.price_sell as price,
            CONCAT("uploads/products/", "", tbl_products.images, "") as img'
        , false);
        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('tbl_products.name', $search);
            $this->db->like('tbl_products.code', $search);
            $this->db->group_end();
        }
        if(!empty($id_orders))
        {
            $this->db->join('tblorders_items','tblorders_items.id_product = tbl_products.id and tblorders_items.type_items="products" and id_orders = '.$id_orders);
        }
        if(!empty($id))
        {
            $list_id = explode('-', $id);
            $list_where_id = [];
            foreach($list_id as $key => $vID)
            {
                $vID = explode('_', $vID);
                if($vID[0] == 'products')
                {
                    if(!empty($vID[1]))
                    {
                        $list_where_id[] = $vID[1];
                    }
                } 
            }
            if(!empty($list_where_id))
            {
                $this->db->where_in('tbl_products.id', $list_where_id);
                $this->db->order_by('tbl_products.name', 'DESC');
                $this->db->limit($limit_one);
                $product = $this->db->get('tbl_products')->result_array();
            }
        }
        else
        {

            $this->db->order_by('tbl_products.name', 'DESC');
            $this->db->limit($limit_one);
            $product = $this->db->get('tbl_products')->result_array();
        }

        $product = !empty($product) ? $product : [];



        if(!empty($product))
        {
            if(empty($id))
            {
                 $data['results'][] =
                [
                    'text' => _l('cong_ts_product'),
                    'children' => $product
                ];
            }
        }


        $count_product = count($product);
        $this->db->select('
                concat("items_", tblitems.id) as id,
                tblitems.name as text,
                tblitems.price,
                images_product as img'
        , false);
        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('tblitems.name', $search);
            $this->db->like('tblitems.code', $search);
            $this->db->group_end();
        }
        if(!empty($id_orders))
        {
            $this->db->join('tblorders_items','tblorders_items.id_product = tblitems.id and tblorders_items.type_items="items" and id_orders = '.$id_orders);
        }

        if(!empty($id))
        {
            $list_id = explode(',', $id);
            $list_where_id = [];
            foreach($list_id as $key => $vID)
            {
                $vID = explode('_', $vID);
                if($vID[0] == 'items')
                {
                    if(!empty($vID[1]))
                    {
                        $list_where_id[] = $vID[1];
                    }
                } 
            }
            if(!empty($list_where_id))
            {
                $this->db->where_in('tblitems.id', $list_where_id);
                $this->db->order_by('name', 'DESC');
                $this->db->limit(($limit_all - $count_product));
                $items = $this->db->get('tblitems')->result_array();
            }
        }
        else
        {
            $this->db->order_by('name', 'DESC');
            $this->db->limit(($limit_all - $count_product));
            $items = $this->db->get('tblitems')->result_array();
        }
        $items = !empty($items) ? $items : [];
        if(!empty($items)) {
            if(empty($id))
            {
                $data['results'][] =
                [
                    'text' => _l('cong_ts_items'),
                    'children' => $items
                ];
            }
        }

        if(!empty($id))
        {
            $data['results'] = array_merge($product, $items);
        }
        echo json_encode($data);die();
    }

     // change trải nghiệm
    public function ChangeErience()
    {
        $id = $this->input->post('id');
        $data = $this->input->post('erience');
        $id_detail = $this->input->post('id_detail');
        $id_care_items = $this->input->post('id_care_items');
        if(!empty($id))
        {
	        if(!empty($data))
	        {
	            foreach($data as $key => $value)
	            {
	                $arrayNotDelete = [];
	                if(is_array($value))
	                {
	                    foreach($value as $kv => $vV)
	                    {
	                        $exrience_detail = get_table_where('tblexperience_care_of_client_detail', ['id' => $vV], '', 'row');
	                        $this->db->where('id_care_of', $id);
	                        $this->db->where('id_experience', $key);
	                        if(!empty($id_care_items))
	                        {
	                            $this->db->where('id_care_items', $id_care_items);
	                        }
	                        $this->db->where('id_experience_detail', $exrience_detail->id);
	                        $ktDetail = $this->db->get('tblcare_of_detail_experience')->row();
	                        if(!empty($ktDetail))
	                        {
	                                $arrayNotDelete[] = $ktDetail->id;
	                        }
	                        else
	                        {
	                            $array_insert = [
	                                'id_experience' => $key,
	                                'id_care_of' => $id,
	                                'date_create' => date('Y-m-d H:i:s'),
	                                'create_by' => get_staff_user_id(),
	                                'name' => $exrience_detail->name,
	                                'id_experience_detail' => $exrience_detail->id,
	                            ];
	                            if(!empty($id_care_items))
	                            {
	                                $array_insert['id_care_items'] = $id_care_items;
	                            }

	                            $this->db->insert('tblcare_of_detail_experience', $array_insert);
	                            if($this->db->insert_id())
	                            {
	                                $arrayNotDelete[] = $this->db->insert_id();
	                            }
	                        }
	                    }
	                }
	                else
	                {
	                        $this->db->where('id_care_of', $id);
	                        $this->db->where('id_experience', $key);
	                        if(!empty($id_care_items))
	                        {
	                            $this->db->where('id_care_items', $id_care_items);
	                        }
	                        $ktDetail = $this->db->get('tblcare_of_detail_experience')->row();
	                        if(!empty($ktDetail))
	                        {
	                            $arrayNotDelete[] = $ktDetail->id;
	                            $experience_care_of = get_table_where('tblexperience_care_of_client', ['id' => $key], '', 'row');
	                            if(!empty($experience_care_of))
	                            {
	                                $this->db->where('id', $ktDetail->id);
	                                $this->db->update('tblcare_of_detail_experience', [
	                                    'name' => ($experience_care_of->type == 'date' ? to_sql_date($value) : ($experience_care_of->type == 'datetime' ? to_sql_date($value, true) : $value) ),
	                                ]);
	                            }
	                        }
	                        else
	                        {
	                            $experience_care_of = get_table_where('tblexperience_care_of_client', ['id' => $key], '', 'row');
	                            if(!empty($experience_care_of))
	                            {
	                                $array_insert = [
	                                    'id_experience' => $key,
	                                    'id_care_of' => $id,
	                                    'date_create' => date('Y-m-d H:i:s'),
	                                    'create_by' => get_staff_user_id(),
	                                    'name' => ($experience_care_of->type == 'date' ? to_sql_date($value) : ($experience_care_of->type == 'datetime' ? to_sql_date($value, true) : $value) ),
	                                    'id_experience_detail' => NULL,
	                                ];
	                                if(!empty($id_care_items))
	                                {
	                                    $array_insert['id_care_items'] = $id_care_items;
	                                }
	                                $this->db->insert('tblcare_of_detail_experience', $array_insert);
	                                if($this->db->insert_id())
	                                {
	                                    $arrayNotDelete[] = $this->db->insert_id();
	                                }
	                            }
	                        }
	                }

	                $this->db->where('id_experience', $key);
	                $this->db->where('id_care_of', $id);
	                if(!empty($id_care_items))
	                {
	                     $this->db->where('id_care_items', $id_care_items);
	                }

	                if(!empty($arrayNotDelete))
	                {
	                    $this->db->where_not_in('id', $arrayNotDelete);
	                }
	                $this->db->delete('tblcare_of_detail_experience');

	                echo json_encode([
	                    'success' => true,
	                    'alert_type' => 'success',
	                    'message' => _l('cong_change_true')
	                ]);die();
	            }
	        }
	        else
	        {
	        	if(!empty($id_detail))
		        {
			        $this->db->where('id_experience', $id_detail);
			        $this->db->where('id_care_of', $id);
			        if(!empty($id_care_items))
			        {
				        $this->db->where('id_care_items', $id_care_items);
			        }
			        else
			        {
				        $this->db->where('id_care_items is null');
			        }
			        $this->db->delete('tblcare_of_detail_experience');
			        echo json_encode([
				        'success' => true,
				        'alert_type' => 'success',
				        'message' => _l('cong_change_true')
			        ]);die();
		        }
	        }
        }

        echo json_encode([
            'success' => false,
            'alert_type' => 'danger',
            'message' => _l('cong_change_false')
        ]);die();
    }

    public function erience_img($id_care_of, $id_erience, $id_care_items = "")
    {
        if (!empty($_FILES['file']))
        {
            $path = get_upload_path_by_type('care_of_client') . $id_care_of . '/';
            $insertImg = 0;
            _maybe_create_upload_path($path);
            $path = $path.$id_erience.'/';
            _maybe_create_upload_path($path);
            foreach($_FILES['file']['name'] as $KF => $VF)
            {
                if (isset($_FILES['file']['name'][$KF])) {
                    $tmpFilePath = $_FILES['file']['tmp_name'][$KF];
                    if (!empty($tmpFilePath) && $tmpFilePath != '') 
                    {
                        $filename = unique_filename($path, time().$_FILES['file']['name'][$KF]);
                        if (_upload_extension_allowed($filename)) {
                            $newFilePath = $path .$filename;
                            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                                $file_uploaded = true;
                                $attachment    = [];
                                $attachment[]  = [
                                'file_name' => $filename,
                                'filetype'  => $_FILES['file']['type'],
                                ];


                                $array_add = [
                                    'id_experience' => $id_erience,
                                    'id_care_of' => $id_care_of,
                                    'date_create' => date('Y-m-d H:i:s'),
                                    'create_by' => get_staff_user_id(),
                                    'name' => $filename,
                                    'id_experience_detail' => NULL,
                                ];
                                if(!empty($id_care_items))
                                {
                                    $array_add['id_care_items'] = $id_care_items;
                                }
                                $this->db->insert('tblcare_of_detail_experience', $array_add);
                                if($this->db->insert_id())
                                {
                                    $insertImg++;
                                }
                            }
                        }
                    }
                }
            }
            if(!empty($insertImg))
            {
                echo json_encode([
                    'success' => true,
                    'alert_type' => 'success',
                    'message' => _l('cong_upload_true')
                ]);die();
            }
        }
        echo json_encode([
            'success' => false,
            'alert_type' => 'danger',
            'message' => _l('cong_upload_false')
        ]);die();
    }

    public function removeImg()
    {

        $url = $this->input->post('url');
        $id_detail = $this->input->post('id_img');
        if(!empty($id_detail) && !empty($url))
        {
            unlink(get_upload_path_by_type('care_of_client').$url);
            $this->db->where('id', $id_detail);
            $success = $this->db->delete('tblcare_of_detail_experience');
            if(!empty($success))
            {
                echo json_encode([
                    'success' => true,
                    'alert_type' => 'success',
                    'message' => _l('cong_remove_img_true')
                ]);die();
            }
        }
        echo json_encode([
            'success' => false,
            'alert_type' => 'danger',
            'message' => _l('cong_remove_img_false')
        ]);die();
    }

    public function UpdateSolution()
    {
        $solution = $this->input->post('solution');
        $id = $this->input->post('id');
        if(!empty($id))
        {
            $this->db->where('id', $id);
            $success = $this->db->update('tblcare_of_clients', ['solution' => $solution]);
            if(!empty($success))
            {
                echo json_encode([
                    'success' => true,
                    'alert_type' => 'success',
                    'message' => _l('cong_update_true')
                ]);die();
            }
        }
        echo json_encode([
            'success' => false,
            'alert_type' => 'danger',
            'message' => _l('cong_update_false')
        ]);die();
    }

    public function get_experience()
    {
        $id = $this->input->post('id');
        $theme_of = $this->input->post('theme_of');
        $id_detail = $this->input->post('id_detail');
        if(!empty($id))
        {
            $stringHtml = '<div class="content-menu-care_of">';
            if(!empty($id_detail))
            {
                $this->db->select('group_concat(tblcare_of_detail_experience.name,",") as fullname, tblexperience_care_of_client.*');
                $this->db->where('id_care_items', $id_detail);
                $this->db->where('id_care_of', $id);
                $this->db->where('(tblexperience_care_of_client.theme is null or tblexperience_care_of_client.theme = '.$theme_of.')');
                $this->db->group_by('tblexperience_care_of_client.id');
                $this->db->join('tblcare_of_detail_experience', 'tblcare_of_detail_experience.id_experience = tblexperience_care_of_client.id');
                $care_of_clients = $this->db->get('tblexperience_care_of_client')->result_array();
                if(!empty($care_of_clients))
                {
                    foreach($care_of_clients as $key => $value)
                    {
                        if($value['type'] == 'staff')
                        {
                            $value['fullname'] = str_replace(', ,',',', $value['fullname']);
                            $value['fullname'] = trim($value['fullname'], ', ');
                            $list_id_staff = explode(',', $value['fullname']);
                            $this->db->select('group_concat(concat(COALESCE(lastname), COALESCE(firstname))) as fullname');
                            $this->db->where_in('staffid', $list_id_staff);
                            $staff = $this->db->get('tblstaff')->row();
                            $stringHtml .='<div class="mbot5"><span><b class="bold600">'.$value['name'].':</b> </span><span>'.$staff->fullname.'</span></div>';
                        }
                        else if($value['type'] == 'img')
                        {
                            $value['fullname'] = str_replace(', ,',',', $value['fullname']);
                            $value['fullname'] = trim($value['fullname'], ', ');
                            $list_img = explode(',', $value['fullname']);
                            $img = '';
                            foreach($list_img as $kImg => $vImg)
                            {
                                $img.='<img src="'.base_url('download/preview_image?path=uploads/care_of_client/'.$id.'/'.$value['id'].'/'.$vImg).'" class="image-small"/>';
                            }
                            $stringHtml .='<div class="mbot5"><span><b class="bold600">'.$value['name'].':</b> </span><span>'.$img.'</span></div>';
                        }
                        else if($value['type'] == 'date' || $value['type'] == 'datetime')
                        {
                            $value['fullname'] = str_replace(', ,',',', $value['fullname']);
                            $value['fullname'] = trim($value['fullname'], ', ');
                            $stringHtml .='<div class="mbot5"><span><b class="bold600">'.$value['name'].':</b> </span><span>'._d($value['fullname']).'</span></div>';
                        }
                        else
                        {
                            $value['fullname'] = str_replace(', ,','</br>', $value['fullname']);
                            if(!empty($value['fullname']))
                            {
                                $value['fullname'] = '<br/>'.$value['fullname'];
                            }
                            $stringHtml .='<div class="mbot5"><span><b class="bold600">'.$value['name'].':</b> </span><span>'.trim($value['fullname'], ', ').'</span></div>';
                        }
                    }
                }
                else
                {
                    $stringHtml = _l('cong_not_found');
                }
            }
            else
            {
                $this->db->select('group_concat(tblcare_of_detail_experience.name,",") as fullname, tblexperience_care_of_client.*');
                $this->db->where('id_care_of', $id);
                $this->db->where('(tblexperience_care_of_client.theme is null or tblexperience_care_of_client.theme = '.$theme_of.')');
                $this->db->group_by('tblexperience_care_of_client.id');
                $this->db->join('tblcare_of_detail_experience', 'tblcare_of_detail_experience.id_experience = tblexperience_care_of_client.id');
                $care_of_clients = $this->db->get('tblexperience_care_of_client')->result_array();
                if(!empty($care_of_clients))
                {
                    foreach($care_of_clients as $key => $value)
                    {
                        if($value['type'] == 'staff')
                        {
                            $value['fullname'] = str_replace(', ,',',', $value['fullname']);
                            $value['fullname'] = trim($value['fullname'], ', ');
                            $list_id_staff = explode(',', $value['fullname']);
                            $this->db->select('group_concat(concat(COALESCE(lastname), COALESCE(firstname))) as fullname');
                            $this->db->where_in('staffid', $list_id_staff);
                            $staff = $this->db->get('tblstaff')->row();
                            $stringHtml .='<div class="mbot5"><span><b class="bold600">'.$value['name'].':</b> </span><span>'.$staff->fullname.'</span></div>';
                        }
                        else if($value['type'] == 'img')
                        {
                            $value['fullname'] = str_replace(', ,',',', $value['fullname']);
                            $value['fullname'] = trim($value['fullname'], ', ');
                            $list_img = explode(',', $value['fullname']);
                            $img = '';
                            foreach($list_img as $kImg => $vImg)
                            {
                                $img.='<img src="'.base_url('download/preview_image?path=uploads/care_of_client/'.$id.'/'.$value['id'].'/'.$vImg).'" class="image-small"/>';
                            }
                            $stringHtml .='<div class="mbot5"><span><b class="bold600">'.$value['name'].':</b> </span><span>'.$img.'</span></div>';
                        }
                        else if($value['type'] == 'date' || $value['type'] == 'datetime')
                        {
                            $value['fullname'] = str_replace(', ,',',', $value['fullname']);
                            $value['fullname'] = trim($value['fullname'], ', ');
                            $stringHtml .='<div class="mbot5"><span><b class="bold600">'.$value['name'].':</b> </span><span>'._d($value['fullname']).'</span></div>';
                        }
                        else
                        {
                            $value['fullname'] = str_replace(', ,','</br>', $value['fullname']);
                            if(!empty($value['fullname']))
                            {
                                $value['fullname'] = '<br/>'.$value['fullname'];
                            }
                            $stringHtml .='<div class="mbot5"><span><b class="bold600">'.$value['name'].':</b> </span><span>'.trim($value['fullname'], ', ').'</span></div>';
                        }
                    }
                }
                else
                {
                    $stringHtml = _l('cong_not_found');
                }
            }
            $stringHtml .= '</div>';
            echo $stringHtml;die();
        }
    }


    //Get modal chi tiết phiếu chăm sóc
    public function getModalDetail()
    {
        $id = $this->input->post('id');
        if(!empty($id))
        {
            $this->db->select('
                 tblclients.name_system,
                 tblclients.code_system, 
                 concat(COALESCE(prefix_client), COALESCE(code_client), "-", COALESCE(tblclients.zcode), "-", COALESCE(tblclients.code_type)) as fullcode_client, 
                 tblclients.zcode, 
                 concat(COALESCE(prefix_lead),COALESCE(code_lead),"-",COALESCE(tblleads.zcode),"-",COALESCE(tblleads.code_type)) as fullcode_lead,
                 tblcare_of_clients.*,
                 concat(COALESCE(tblorders.prefix), COALESCE(tblorders.code)) as code_orders
             ');
            $this->db->where('tblcare_of_clients.id', $id);
            $this->db->join('tblclients', 'tblclients.userid = tblcare_of_clients.client');
            $this->db->join('tblleads', 'tblleads.id = tblclients.leadid', 'left');
            $this->db->join('tblorders', 'tblorders.id = tblcare_of_clients.id_orders', 'left');
            $data['care_of_clients'] = $this->db->get('tblcare_of_clients')->row();
            echo json_encode([
                'data' => $this->load->view('admin/care_of_clients/modal_view_detail', $data, true)
            ]);die();
        }
    }

    public function updateColum()
    {
    	if($this->input->post());
	    {
	    	$note = $this->input->post('note', false);
	    	$id = $this->input->post('id');
	    	if(!empty($id))
		    {
		    	$this->db->where('id', $id);
		    	$success = $this->db->update('tblcare_of_clients', ['note' => $note]);
		    	if(!empty($success))
			    {
			    	echo json_encode([
			    		'success' => true,
					    'alert_type' => 'success',
					    'message' => _l('cong_update_true')
				    ]);die();
			    }
		    }
	    }
	    echo json_encode([
		    'success' => false,
		    'alert_type' => 'danger',
		    'message' => _l('cong_update_false')
	    ]);die();
    }


}
