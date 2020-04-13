<?php

header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');

class Advisory_lead extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    /* List all leads */
    public function index()
    {
        $data['title'] = _l('cong_advisory_lead');
        $this->db->select(db_prefix().'procedure_client_detail.*');
        $this->db->join(db_prefix().'procedure_client', db_prefix().'procedure_client.id = '.db_prefix().'procedure_client_detail.id_detail');
        $this->db->where('type', 'lead');
        $data['client_detail'] = $this->db->get(db_prefix().'procedure_client_detail')->result_array();
        $this->load->view('admin/advisory_lead/manage', $data);
    }

    public function table()
    {
        $this->app->get_table_data('advisory_lead');
    }

    public function table_advisory_lead_tab($id_lead = "")
    {
        if (!is_staff_member()) {
            ajax_access_denied();
        }
        $this->app->get_table_data('advisory_lead_tab', ['id_lead' => $id_lead]);
    }

    public function getModal()
    {
		//lấy quy trình phiếu tư vấn
        $this->db->select('tblprocedure_client_detail.*');
        $this->db->where('type' ,'lead');
        $this->db->join('tblprocedure_client', 'tblprocedure_client.id = tblprocedure_client_detail.id_detail');
        $this->db->order_by('orders', 'asc');
        $data['procedure_detail'] = $this->db->get('tblprocedure_client_detail')->result_array();

	    //lấy quy trình phiếu tư vấn mặc định là bắt đầu
	    $this->db->select('tblprocedure_client_detail.*');
	    $this->db->where('type' ,'lead');
	    $this->db->where('active_start' ,'1');
	    $this->db->join('tblprocedure_client', 'tblprocedure_client.id = tblprocedure_client_detail.id_detail');
	    $this->db->order_by('orders', 'asc');
	    $procedure_detail_active = $this->db->get('tblprocedure_client_detail')->row();
	    if(!empty($procedure_detail_active))
	    {
		    $data['procedure_detail_active'] = $procedure_detail_active->id;
	    }

        if($this->input->post('id'))
        {
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            $data['advisory_lead'] = $this->db->get('tbladvisory_lead')->row();

            $this->db->where('id_advisory', $id);
            $this->db->where('active', 1);
            $advisory_lead_procedure = $this->db->get('tblprocedure_advisory_lead')->num_rows();
            if($advisory_lead_procedure > 1)
            {
                $data['advisory_lead']->active = 1;
            }
        }

        $this->load->view('admin/advisory_lead/modal', $data);
    }

    public function detail()
    {
        if($this->input->post())
        {
            $data = $this->input->post();
            if(!empty($data['id']))
            {
                $id = $data['id'];
                unset($data['id']);
                $this->db->where('id', $id);
                $advisory_lead = $this->db->get('tbladvisory_lead')->row();
                if(!empty($advisory_lead)) {
                    $array_update = [
                        'product_other_buy' => $data['product_other_buy'],
                        'address_other_buy' => $data['address_other_buy'],
                    ];
                    if (!empty($data['date'])) {
                        $array_update['date'] = to_sql_date($data['date']);
                    }

                    $object = explode('_', $data['object']);
                    $id_object = $object[1];
                    $type_object = $object[0];

	                $array_update['id_object_it'] = NULL;
	                $array_update['type_object_it'] = NULL;
	                if(!empty($data['object_it']))
	                {
		                $object_it = explode('_', $data['object_it']);
		                if(!empty($object_it))
		                {
			                $id_object_it = $object_it[1];
			                $type_object_it = $object_it[0];
			                $array_update['id_object_it'] = $id_object_it;
			                $array_update['type_object_it'] = $type_object_it;
		                }
	                }

                    $type_code = 'NC';

                    if($type_object == 'client')
                    {
                        $this->db->where('client', $object[1]);
                        $numrows = $this->db->get('tblorders')->num_rows();
                        if(!empty($numrows))
                        {
                            $type_code = 'OC';
                        }
                    }
                    $array_update['id_object'] = $id_object;
                    $array_update['type_code'] = $type_code;
                    $array_update['type_object'] = $type_object;


                    $this->db->select('count(id) as count_id');
                    $this->db->where('id_advisory', $id);
                    $this->db->where('active', 1);
                    $advisory_lead_procedure = $this->db->get('tblprocedure_advisory_lead')->row();
                    if ($advisory_lead_procedure->count_id == 1 && (!empty($data['status_first']) && ($advisory_lead->status_first != $data['status_first'] || $advisory_lead->date != $array_update['date']))) {

                            $array_update['status_first'] = $data['status_first'];
                    }
                    else if($advisory_lead_procedure->count_id > 1) {
                        echo json_encode([
                            'success' => false,
                            'alert_type' => 'danger',
                            'message' => _l('cong_data_to_change_not_update')
                        ]);
                        die();
                    }


                    $this->db->where('id', $id);
                    $success = $this->db->update('tbladvisory_lead', $array_update);
                    if (!empty($success)) {

                    	$arrayInfoNotDelete = [];
                    	if(!empty($data['info']))
	                    {
	                    	foreach($data['info'] as $keyInfo => $valInfo)
		                    {
		                    	if(!empty($valInfo))
			                    {
			                        $this->db->where('id_advisory', $id);
			                        $this->db->where('id_info', $keyInfo);
			                        $this->db->where('value_info', $valInfo);
			                        $ktVal_Info = $this->db->get('tbladvisory_info_value')->row();
			                        if(!empty($ktVal_Info))
				                    {
					                    $arrayInfoNotDelete[] = $ktVal_Info->id;
				                    }
			                        else
				                    {
										$this->db->insert('tbladvisory_info_value', ['id_advisory' => $id, 'id_info' => $keyInfo, 'value_info' => $valInfo]);
										if($this->db->insert_id())
										{
											$arrayInfoNotDelete[] = $this->db->insert_id();
										}
				                    }
			                    }
		                    }
	                    }

                    	$this->db->where('id_advisory', $id);
                    	if(!empty($arrayInfoNotDelete))
	                    {
	                    	$this->db->where_not_in('id', $arrayInfoNotDelete);
	                    }
                    	$this->db->delete('tbladvisory_info_value');

                        if((!empty($array_update['date']) && $array_update['date'] != $advisory_lead->date)||
                           (!empty($data['status_first']) && $data['status_first'] != $advisory_lead->status_first))
                        {

                            $this->db->where('id_advisory', $id);
                            $this->db->delete('tblprocedure_advisory_lead');

                            $this->db->where('id', $data['status_first']);
                            $procedure_detail = $this->db->get('tblprocedure_client_detail')->row();
                            if(!empty($procedure_detail))
                            {
                                $this->db->order_by('orders', 'ASC');
                                $this->db->where('id_detail', $procedure_detail->id_detail);
                                $this->db->where('orders >= ', $procedure_detail->orders);
                                $advisory_procedure = $this->db->get('tblprocedure_client_detail')->result_array();
                                $_date = $array_update['date'];
                                foreach($advisory_procedure as $key => $value)
                                {
                                    $leadtime = $value['leadtime'];
                                    $_date =  date("Y-m-d", strtotime("$_date +$leadtime day"));
                                    $this->db->insert('tblprocedure_advisory_lead', [
                                        'id_advisory' => $id,
                                        'name_status' => $value['name'],
                                        'orders_status' => $value['orders'],
                                        'leadtime' => $value['leadtime'],
                                        'status_procedure' => $value['id'],
                                        'date_expected ' => $_date,
                                        'not_procedure ' => !empty($advisory_procedure->not_procedure) ? $advisory_procedure->not_procedure : NULL,
                                    ]);
                                }
                            }
                        }

                        echo json_encode([
                            'success' => true,
                            'alert_type' => 'success',
                            'message' => _l('cong_update_true')
                        ]);
                        die();
                    }
                }
                echo json_encode([
                    'success' => false,
                    'alert_type' => 'danger',
                    'message' => _l('cong_update_false')
                ]);die();
            }
            else
            {
                $this->db->select('tblprocedure_client_detail.*');
                $this->db->order_by('orders', 'ASC');
                $this->db->join('tblprocedure_client', 'tblprocedure_client.id = tblprocedure_client_detail.id_detail');
                $this->db->where('tblprocedure_client.type', 'lead');
                $advisory_procedure = $this->db->get('tblprocedure_client_detail')->result_array();

                $object = explode('_', $data['object']);
                $id_object = $object[1];
                $type_object = $object[0];

                $type_code = 'NC';

                if($type_object == 'client')
                {
                    $this->db->where('client', $object[1]);
                    $numrows = $this->db->get('tblorders')->num_rows();
                    if(!empty($numrows))
                    {
                        $type_code = 'OC';
                    }
                }

                $array_add = [
                    'id_object' => $id_object,
                    'type_object' => $type_object,
                    'type_code' => $type_code,
                    'date' => to_sql_date($data['date']),
                    'status_first' => $data['status_first'],
                    'status_active' => 0,
                    'product_other_buy' => $data['product_other_buy'],
                    'address_other_buy' => $data['address_other_buy'],
                    'date_create' => date('Y-m-d H:i:s'),
                    'create_by' => get_staff_user_id()
                ];

	            $array_add['id_object_it'] = NULL;
	            $array_add['type_object_it'] = NULL;
	            if(!empty($data['object_it']))
	            {
		            $object_it = explode('_', $data['object_it']);
		            if(!empty($object_it))
		            {
			            $id_object_it = $object_it[1];
			            $type_object_it = $object_it[0];
			            $array_add['id_object_it'] = $id_object_it;
			            $array_add['type_object_it'] = $type_object_it;
		            }
	            }


                $this->db->insert('tbladvisory_lead', $array_add);
                if($this->db->insert_id())
                {
                    $id = $this->db->insert_id();
                    //Thêm Mã tư vấn cho Phiếu
                    CreateCode('advisory', $id);
                    //End thêm mã tư vấn cho phiếu

                    ChangeTag_manuals($type_object, $id_object, $id, ['advisory' => $id]);

	                if(!empty($data['info']))
	                {
		                foreach($data['info'] as $keyInfo => $valInfo)
		                {
			                $this->db->insert('tbladvisory_info_value', ['id_advisory' => $id, 'id_info' => $keyInfo, 'value_info' => $valInfo]);
		                }
	                }

                    $_date = to_sql_date($data['date']);
                    $orders_status = 1;
                    foreach($advisory_procedure as $key => $value)
                    {
                        $leadtime = $value['leadtime'];
                        $_date =  date("Y-m-d", strtotime("$_date +$leadtime day"));
                        $this->db->insert('tblprocedure_advisory_lead', [
                            'id_advisory' => $id,
                            'name_status' => $value['name'],
                            'orders_status' => $value['orders'],
                            'leadtime' => $value['leadtime'],
                            'status_procedure' => $value['id'],
                            'date_expected ' => $_date,
                            'not_procedure ' => !empty($value['not_procedure']) ? $value['not_procedure'] : NULL,
                        ]);
                        if($value['id'] == $data['status_first'])
                        {
                            $orders_status = $value['orders'];
                        }
                    }
                    $this->db->where("id_advisory", $id);
                    $this->db->where("orders_status <= ", $orders_status);
                    $procedure_advisory = $this->db->get('tblprocedure_advisory_lead')->result_array();
                    foreach($procedure_advisory as $key => $value)
                    {
                        $this->db->where('id', $value['id']);
                        $success_update = $this->db->update('tblprocedure_advisory_lead', [
                            'active' => 1,
                            'date_create' => date('Y-m-d H:i:s'),
                            'create_by' => get_staff_user_id()
                        ]);
                        if(!empty($success_update))
                        {
                            addLog_advisory_lead([
                                'type_object' => $type_object,
                                'id_object' => $id_object,
                                'name_status' => $value['name_status'],
                                'staff' => get_staff_user_id(),
                                'id_procedure' => $value['status_procedure']
                            ], 1);
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

    public function delete_advisory_lead()
    {
        if($this->input->post('id'))
        {
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            if($this->db->delete(db_prefix().'advisory_lead'))
            {
                $this->db->where('id_advisory', $id);
                $this->db->delete('procedure_advisory_lead');

                $this->db->where('id_advisory', $id);
                $this->db->delete('tbladvisory_detail_experience');
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
                $advisory_lead = $this->db->get('tbladvisory_lead')->row();
                if(!empty($advisory_lead))
                {
                    //Kiểm tra phiếu đã kế thúc chưa
                    if($advisory_lead->status_break == 1) {
                        echo json_encode([
                            'success' => true,
                            'alert_type' => 'danger',
                            'message' => _l('cong_advisory_status_break')
                        ]);die();
                    }

                    //kiểm tra phiếu đã được duyệt qua chưa
                    $this->db->where('id_advisory', $data['id']);
                    $this->db->where('status_procedure', $data['status_procedure']);
                    $action_advisory = $this->db->get('tblprocedure_advisory_lead')->row();
                    if(!empty($action_advisory))
                    {
                        $array_update = [
                            'active' => 1,
                            'create_by' => get_staff_user_id(),
                            'date_create' => date('Y-m-d H:i:s')
                        ];
                        $active =  true;
                        if(!empty($action_advisory->not_procedure) && $action_advisory->active == 1)
                        {
                            $array_update['active'] = 0;
                            $array_update['create_by'] = NULL;
                            $array_update['date_create'] = NULL;
	                        $active = false;
                        }
                        $this->db->where('id', $action_advisory->id);
                        $success_update = $this->db->update('tblprocedure_advisory_lead', $array_update);
                        if(!empty($success_update) && empty($action_advisory->not_procedure)) {

                            $status_active = StatusActiveAdvisory();
                            foreach($status_active as $key => $value)
                            {
                                if($value['orders_procedure'] == $action_advisory->orders_status)
                                {
                                    $this->db->where('id', $advisory_lead->id);
                                    $this->db->update('tbladvisory_lead', ['status_active' => $key]);
                                    break;
                                }
                            }


                            $this->db->where('orders_status <', $action_advisory->orders_status);
                            $this->db->where('active', 0);
                            $this->db->where('id_advisory', $data['id']);
                            $this->db->where('not_procedure is NULL');
                            $advisory_before = $this->db->get('tblprocedure_advisory_lead')->result_array();
                            if(!empty($advisory_before)) {
                                foreach ($advisory_before as $key => $value) {
                                    $this->db->where('id', $value['id']);
                                    $this->db->update('tblprocedure_advisory_lead', [
                                        'active' => 1,
                                        'date_create' => date('Y-m-d H:i:s'),
                                        'create_by' => get_staff_user_id()
                                    ]);
                                    addLog_advisory_lead([
                                        'type_object' => $advisory_lead->type_object,
                                        'id_object' => $advisory_lead->id_object,
                                        'name_status' => $value['name_status'],
                                        'staff' => get_staff_user_id(),
                                        'id_procedure' => $value['status_procedure']
                                    ], 1);
                                }
                            }

                            $this->db->where('orders_status >', $action_advisory->orders_status);
                            $this->db->where('active', 1);
	                        $this->db->where('id_advisory', $data['id']);
                            $this->db->where('not_procedure is NULL');
                            $advisory_last = $this->db->get('tblprocedure_advisory_lead')->result_array();
                            if(!empty($advisory_last))
                            {
                                foreach($advisory_last as $key => $value)
                                {
                                    $this->db->where('id', $value['id']);
                                    $this->db->update('tblprocedure_advisory_lead', [
                                        'active' => 0,
                                        'date_create' => NULL,
                                        'create_by' => NULL
                                    ]);
                                    addLog_advisory_lead([
                                        'type_object' => $advisory_lead->type_object,
                                        'id_object' => $advisory_lead->id_object,
                                        'name_status' => $value['name_status'],
                                        'staff' => get_staff_user_id(),
                                        'id_procedure' => $value['status_procedure']
                                    ], 2);
                                }
                            }
                        }


	                    $arrayUpdate = [
	                    	'status_advisory' => $action_advisory->status_procedure,
//	                    	'status_active' => $action_advisory->orders_status,
	                    ];
                        $orders_status = $action_advisory->orders_status;
                        if(empty($active))
                        {
		                    $this->db->where('id_advisory', $advisory_lead->id);
		                    $this->db->where('active', 1);
		                    $this->db->order_by('status_procedure', 'DESC');
		                    $action_advisory_before = $this->db->get('tblprocedure_advisory_lead')->row();
		                    if(!empty($action_advisory_before))
		                    {
			                    $arrayUpdate['status_advisory'] = $action_advisory_before->status_procedure;
			                    $orders_status = $action_advisory_before->orders_status;
		                    }
                        }

	                    $status_active = StatusActiveAdvisory();
	                    foreach($status_active as $key => $value)
	                    {
		                    if($value['orders_procedure'] == $orders_status)
		                    {
			                    $arrayUpdate['status_active'] = $key;
			                    break;
		                    }

	                    }

	                    $this->db->where('id', $advisory_lead->id);
	                    $this->db->update('tbladvisory_lead', $arrayUpdate);
	                    addLog_advisory_lead([
		                    'type_object' => $advisory_lead->type_object,
		                    'id_object' => $advisory_lead->id_object,
		                    'name_status' => $action_advisory->name_status,
		                    'staff' => get_staff_user_id(),
		                    'id_procedure' => $action_advisory->status_procedure
	                    ], 1);
                        echo json_encode([
                            'success' => true,
                            'alert_type' => 'success',
                            'message' => _l('cong_update_true')
                        ]);die();
                    }
                    else
                    {
                        echo json_encode([
                            'success' => false,
                            'alert_type' => 'danger',
                            'message' => _l('cong_dont_isset_status')
                        ]);die();
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

    //end Sửa
    public function restore_advisory_lead()
    {
        if($this->input->post())
        {
            $data = $this->input->post();
            if(!empty($data['id']))
            {
                $id = $data['id'];
                $this->db->where('id', $id);
                $advisory_lead = $this->db->get('tbladvisory_lead')->row();
                if(!empty($advisory_lead))
                {
                    $this->db->where('id_advisory', $advisory_lead->id);
                    $this->db->where('active', 1);
                    $this->db->order_by('orders_status', 'desc');
                    $action_advisory = $this->db->get('tblprocedure_advisory_lead')->row();
                    if(!empty($action_advisory))
                    {
                        $this->db->where('id', $action_advisory->id);
                        if($this->db->update('tblprocedure_advisory_lead', ['active' => 0, 'date_create' => NULL]))
                        {
                            $status_active = StatusActiveAdvisory();
                            foreach($status_active as $key => $value)
                            {
                                if($value['orders_procedure'] == $action_advisory->orders_status)
                                {
                                    $this->db->where('id', $advisory_lead->id);
                                    $this->db->update('tbladvisory_lead', ['status_active' => $key]);
                                    break;
                                }
                            }

                            //Thêm vào lịch sử chăn sóc khách hàng
                            addLog_advisory_lead([
                                'type_object' => $advisory_lead->type_object,
                                'id_object' => $advisory_lead->id_object,
                                'name_status' => $action_advisory->name_status,
                                'staff' => get_staff_user_id(),
                                'id_procedure' => $action_advisory->status_procedure
                            ], 0);


                            $this->db->where('id_advisory', $advisory_lead->id);
                            $this->db->where('active', 1);
                            $this->db->order_by('orders_status', 'desc');
                            $action_advisory_before = $this->db->get('tblprocedure_advisory_lead')->row();

                            $this->db->where('id', $advisory_lead->id);
                            $this->db->update('tbladvisory_lead', ['status_advisory' => (!empty($action_advisory_before->status_procedure) ? $action_advisory_before->status_procedure : 0)]);

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

    public function break_advisory()
    {
        $id = $this->input->post('id');
        if(!empty($id))
        {
            $status = $this->input->post('status');
            $this->db->where('id', $id);
            $advisory_lead = $this->db->get(db_prefix().'advisory_lead')->row();
            if(!empty($advisory_lead))
            {
                $this->db->where('id', $id);
                $success = $this->db->update(db_prefix().'advisory_lead' , [
                    'status_break' => $status
                ]);
                if($success)
                {
                    $this->db->where('id_advisory', $id);
                    $this->db->order_by('date_create', 'desc');
                    $action_advisory = $this->db->get(db_prefix().'procedure_advisory_lead')->row();

                    addLog_advisory_lead([
                        'type_object' => $advisory_lead->type_object,
                        'id_object' => $advisory_lead->id_object,
                        'name_status' => $action_advisory->name_status,
                        'staff' => get_staff_user_id(),
                        'id_procedure' => $action_advisory->status_procedure
                    ], 3);

                    echo json_encode([
                        'success' => true,
                        'alert_type' => 'success',
                        'message' => _l('cong_break_advisory_true')
                    ]);die();
                }
            }
        }
        echo json_encode([
            'success' => false,
            'alert_type' => 'false',
            'message' => _l('cong_break_advisory_false')
        ]);die();
    }
    //end Sủa

    public function SearchObject($id = "")
    {
        if(!empty($id))
        {
            $id = explode('_', $id);
        }
        $data = [];
        $search = $this->input->get('term');
        $limit_one = 100;
        $limit_all = 200;
        $this->db->select('
            concat("lead_", id) as id,
            concat(name_system) as text,
            CONCAT("download/preview_image?path=uploads/leads/", id,"/", "small_", lead_image) as img'
            , false);
        if(!empty($id) && $id[0] == 'lead')
        {
            $this->db->where('id', $id[1]);
            $leads = $this->db->get('tblleads')->row();
            if(!empty($leads)) {
                $data['results'] = $leads;
                echo json_encode($data);die();
            }
        }
        else
        {
            if (!empty($search))
            {
                $this->db->group_start();
                $this->db->like('name_system', $search);
                $this->db->or_like('CONCAT(prefix_lead,code_lead," - ", code_type)', $search);
                $this->db->group_end();
            }
            $this->db->order_by('dateadded', 'DESC');
            $this->db->limit($limit_one);
            $leads = $this->db->get('tblleads')->result_array();
            if(!empty($leads))
            {
                $data['results'][] =
                    [
                        'text' => _l('cong_lead'),
                        'children' => $leads
                    ];
            }
        }



        $count_leads = count($leads);
        $this->db->select('
                concat("client_", userid) as id,
                concat(name_system) as text,
                CONCAT("download/preview_image?path=uploads/clients/", userid,"/", "small_", client_image) as img'
            , false);
        if(!empty($id) && $id[0] == 'client')
        {
            $this->db->where('userid', $id[1]);
            $clients = $this->db->get('tblclients')->row();
            if(!empty($clients)) {
                $data['results'] = $clients;
                echo json_encode($data);die();
            }
        }
        else
        {
            if (!empty($search))
            {
                $this->db->group_start();
                $this->db->like('fullname', $search);
                $this->db->or_like('name_system', $search);
                $this->db->or_like('concat(prefix_client, code_client," - ", code_type)', $search);
                $this->db->group_end();
            }
            $this->db->limit(($limit_all - $count_leads));
            $this->db->order_by('datecreated', 'DESC');
            $clients = $this->db->get('tblclients')->result_array();
            if(!empty($clients)) {
                $data['results'][] =
                    [
                        'text' => _l('cong_client'),
                        'children' => $clients
                    ];
            }
        }
        echo json_encode($data);die();
    }

    // change trải nghiệm tư vấn
    public function ChangeErience()
    {
        $id = $this->input->post('id');
	    $id_detail = $this->input->post('id_detail');
        $data = $this->input->post('erience');
        if(!empty($id))
        {
        	if(!empty($data))
	        {
	            foreach($data as $key => $value)
	            {
	                $arrayNotDelete = [];
	                foreach($value as $kv => $vV)
	                {
	                    $exrience_detail = get_table_where('tblexperience_advisory_detail', ['id' => $vV], '', 'row');

	                    $this->db->where('id_advisory', $id);
	                    $this->db->where('id_experience', $key);
	                    $this->db->where('id_experience_detail', $exrience_detail->id);
	                    $ktDetail = $this->db->get('tbladvisory_detail_experience')->row();
	                    if(!empty($ktDetail))
	                    {
	                            $arrayNotDelete[] = $ktDetail->id;
	                    }
	                    else
	                    {

	                        $this->db->insert('tbladvisory_detail_experience', [
	                            'id_experience' => $key,
	                            'id_advisory' => $id,
	                            'date_create' => date('Y-m-d H:i:s'),
	                            'create_by' => get_staff_user_id(),
	                            'name' => $exrience_detail->name,
	                            'id_experience_detail' => $exrience_detail->id,
	                        ]);
	                        if($this->db->insert_id())
	                        {
	                            $arrayNotDelete[] = $this->db->insert_id();
	                        }
	                    }
	                }

	                $this->db->where('id_experience', $key);
	                $this->db->where('id_advisory', $id);
	                if(!empty($arrayNotDelete))
	                {
	                    $this->db->where_not_in('id', $arrayNotDelete);
	                }
	                $this->db->delete('tbladvisory_detail_experience');

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
			        $this->db->where('id_advisory', $id);
			        $this->db->delete('tbladvisory_detail_experience');
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

    //update status active
    public function updateStatus()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        if(!empty($id))
        {
            $this->db->where('id', $id);
            $advisory_lead = $this->db->get('tbladvisory_lead')->row();
            if(!empty($advisory_lead))
            {
                $this->db->where('id', $id);
                $success = $this->db->update('tbladvisory_lead', ['status_active' => $status]);
                if($success)
                {
                	$cancel_advisory = get_option('cancel_advisory');
                	$break_advisory = get_option('break_advisory');
                	if($status != $break_advisory && $status != $cancel_advisory)
	                {
	                    $activeAdvisory = StatusActiveAdvisory();
	                    foreach($activeAdvisory as $key => $value)
	                    {
	                        if(isset($value['orders_procedure']) && $key == $status)
	                        {
	                            $this->db->where('orders_status <= ',$value['orders_procedure']);
	                            $this->db->where('id_advisory', $id);
	                            $this->db->where('active', 0);
	                            $this->db->where('not_procedure is null');
	                            $procedure_advisory_before = $this->db->get('tblprocedure_advisory_lead')->result_array();
	                            if(!empty($procedure_advisory_before))
	                            {
	                                foreach($procedure_advisory_before as $kPro => $vPro)
	                                {
	                                    $this->db->where('id', $vPro['id']);
	                                    $this->db->update('tblprocedure_advisory_lead', [
	                                        'create_by' => get_staff_user_id(),
	                                        'active' => 1,
	                                        'date_create' => date('Y-m-d H:i:s')
	                                    ]);
	                                    addLog_advisory_lead([
	                                        'type_object' => $advisory_lead->type_object,
	                                        'id_object' => $advisory_lead->id_object,
	                                        'name_status' => $vPro['name_status'],
	                                        'staff' => get_staff_user_id(),
	                                        'id_procedure' => $vPro['status_procedure']
	                                    ], 1);
	                                }
	                            }

	                            $this->db->where('orders_status >',$value['orders_procedure']);
	                            $this->db->where('id_advisory', $id);
	                            $this->db->where('active', 1);
	                            $this->db->where('not_procedure is null');
	                            $procedure_advisory_last = $this->db->get('tblprocedure_advisory_lead')->result_array();
	                            if(!empty($procedure_advisory_last))
	                            {
	                                foreach($procedure_advisory_last as $kPro => $vPro)
	                                {
	                                    $this->db->where('id', $vPro['id']);
	                                    $this->db->update('tblprocedure_advisory_lead', [
	                                        'create_by' => NULL,
	                                        'active' => 0,
	                                        'date_create' => NULL
	                                    ]);
	                                    addLog_advisory_lead([
	                                        'type_object' => $advisory_lead->type_object,
	                                        'id_object' => $advisory_lead->id_object,
	                                        'name_status' => $vPro['name_status'],
	                                        'staff' => get_staff_user_id(),
	                                        'id_procedure' => $vPro['status_procedure']
	                                    ], 2);
	                                }
	                            }
	                            break;
	                        }
	                    }
	                }
                	else
	                {
		                $this->db->where('orders_status', $status);
		                $this->db->where('id_advisory', $id);
		                $procedure_advisory_cancel = $this->db->get('tblprocedure_advisory_lead')->row();
		                if(!empty($procedure_advisory_cancel))
		                {
		                	if(empty($procedure_advisory_cancel->active))
			                {
				                $this->db->where('id', $procedure_advisory_cancel->id);
				                $this->db->update('tblprocedure_advisory_lead', [
					                'create_by' => get_staff_user_id(),
					                'active' => 1,
					                'date_create' => date('Y-m-d H:i:s')
				                ]);
			                }
		                }
		                else
		                {
							$this->db->where('orders', $status);
							$this->db->where('id_detail', 1);
							$procedure = $this->db->get('tblprocedure_client_detail')->row();

							if(!empty($procedure))
							{
								$leadtime = $procedure->leadtime;

								$this->db->where('orders_status', 6);
								$this->db->where('id_advisory', $id);
								$procedure_beforer = $this->db->get('tblprocedure_advisory_lead')->row();
								if(!empty($procedure_beforer))
								{
									$_date = $procedure_beforer->date_expected;
									$_date =  date("Y-m-d", strtotime("$_date +$leadtime day"));
									$this->db->insert('tblprocedure_advisory_lead', [
										'create_by' => get_staff_user_id(),
										'active' => 1,
										'orders_status' => $status,
										'status_procedure' => $procedure->id,
										'id_advisory' => $id,
										'name_status' => $procedure->name,
										'leadtime' => $procedure->leadtime,
										'date_expected' => $leadtime,
										'not_procedure' => $procedure->not_procedure,
										'date_create' => date('Y-m-d H:i:s')
									]);
								}
							}
		                }
	                }
                    echo json_encode([
                        'alert_type' => 'success',
                        'message' => _l('cong_update_true'),
                        'success' => true
                    ]);die();
                }
                echo json_encode([
                    'alert_type' => 'danger',
                    'message' => _l('cong_update_false'),
                    'success' => false
                ]);die();
            }
            echo json_encode([
                'alert_type' => 'danger',
                'message' => _l('cong_data_isset_change'),
                'success' => false
            ]);die();
        }
        echo json_encode([
            'alert_type' => 'danger',
            'message' => _l('cong_update_false'),
            'success' => false
        ]);die();
    }

    public function updateCriteria()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $colums = $this->input->post('colums');
        if(!empty($id) && !empty($status) && !empty($colums))
        {
            $arrayUpdate = [];
            if($colums == 'criteria_one')
            {
                $arrayUpdate['criteria_one'] = $status;
                $arrayUpdate['date_criteria_one'] = date('Y-m-d H:i:s');
            }
            else if($colums == 'criteria_two')
            {
                $arrayUpdate['criteria_two'] = $status;
                $arrayUpdate['date_criteria_two'] = date('Y-m-d H:i:s');
            }
            $success = false;
            if(!empty($arrayUpdate))
            {
                $this->db->where('id', $id);
                $success = $this->db->update('tbladvisory_lead', $arrayUpdate);
            }
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

    public function editColum_advisory()
    {
        $id = $this->input->post('id');
        $product_other_buy = $this->input->post('product_other_buy');
        $address_other_buy = $this->input->post('address_other_buy');
        $note_reason_spam = $this->input->post('note_reason_spam');
        $note_reason_stop = $this->input->post('note_reason_stop');
        $staff_appointment = $this->input->post('staff_appointment');
        $note_appointment = $this->input->post('note_appointment');
        $note = $this->input->post('note');
        $info = $this->input->post('info');
        if(!empty($id))
        {
            $array_update = [];
            if(isset($product_other_buy))
            {
                $array_update['product_other_buy'] = $product_other_buy;
            }
            if(isset($address_other_buy))
            {
                $array_update['address_other_buy'] = $address_other_buy;
            }
            if(isset($note_reason_spam))
            {
                $array_update['note_reason_spam'] = $note_reason_spam;
            }
            if(isset($note_reason_stop))
            {
                $array_update['note_reason_stop'] = $note_reason_stop;
            }

            if(isset($staff_appointment))
            {
                $array_update['staff_appointment'] = $staff_appointment;
            }
            if(isset($note_appointment))
            {
                $array_update['note_appointment'] = $note_appointment;
            }
            if(isset($note))
            {
                $array_update['note'] = $note;
            }

            if(!empty($array_update))
            {
                $this->db->where('id', $id);
                $success = $this->db->update('tbladvisory_lead', $array_update);
            }

            if(!empty($info))
            {
            	foreach($info as $key => $value)
	            {
	            	$arrayNotDelete = [];
	            	$this->db->where('id_info', $key);
	            	$this->db->where('value_info', $value);
	            	$this->db->where('id_advisory', $id);
	            	$ktAdvisory_info = $this->db->get('tbladvisory_info_value')->row();
	            	if(!empty($ktAdvisory_info))
		            {
			            $arrayNotDelete[] = $ktAdvisory_info->id;
		            }
	            	else
		            {
			            $this->db->insert('tbladvisory_info_value', [
			            	'id_info' => $key,
			            	'value_info' => $value,
			            	'id_advisory' => $id,
			            ]);
			            if($this->db->insert_id())
			            {
				            $arrayNotDelete[] = $this->db->insert_id();
			            }
		            }
	            	$this->db->where('id_advisory', $id);
	            	$this->db->where('id_info', $key);
	            	if(!empty($arrayNotDelete))
		            {
		            	$this->db->where_not_in('id', $arrayNotDelete);
		            }
		            $success = $this->db->delete('tbladvisory_info_value');
	            }
            }
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

    public function getConcerns($id_obj = '')
    {
        $data = explode('_', $id_obj);
        $type = $data[0];
        $id = $data[1];
        $getData = [];
        if(!empty($id))
        {
            $this->db->where('view_modal', 1);
            $info_view_detail = $this->db->get('tblclient_info_detail')->result_array();
            if($type == 'client') {
                foreach($info_view_detail as $kInfo => $vInfo)
                {
                    if($vInfo['type_form'] == 'select' || $vInfo['type_form'] == 'select multiple' || $vInfo['type_form'] == 'checkbox' || $vInfo['type_form'] == 'radio')
                    {
                        $this->db->select('group_concat(tblclient_info_detail_value.name) as value_name');
                        $this->db->where('client', $id);
                        $this->db->where('id_detail', $vInfo['id']);
                        $this->db->join('tblclient_info_detail_value', 'tblclient_info_detail_value.id = tblclient_value.value');
                        $info_view_detail[$kInfo]['value'] = $this->db->get('tblclient_value')->row();
                    }
                    else
                    {
                        $this->db->select('tblclient_value.value as value_name');
                        $this->db->where('client', $id);
                        $this->db->where('id_detail', $vInfo['id']);
                        $info_view_detail[$kInfo]['value'] = $this->db->get('tblclient_value')->row();
                    }
                }
                $getData['view_detail'] = $info_view_detail;

                $client = get_table_where('tblclients', ['userid' => $id], '', 'row');
                $getData['date_contact'] = _dt($client->date_contact);
            }
            else if($type == 'lead'){
                foreach($info_view_detail as $kInfo => $vInfo)
                {
                    if($vInfo['type_form'] == 'select' || $vInfo['type_form'] == 'select multiple' || $vInfo['type_form'] == 'checkbox' || $vInfo['type_form'] == 'radio')
                    {
                        $this->db->select('group_concat(tblclient_info_detail_value.name) as value_name');
                        $this->db->where('lead', $id);
                        $this->db->where('id_detail', $vInfo['id']);
                        $this->db->join('tblclient_info_detail_value', 'tblclient_info_detail_value.id = tbllead_value.value');
                        $info_view_detail[$kInfo]['value'] = $this->db->get('tbllead_value')->row();
                    }
                    else
                    {
                        $this->db->select('tbllead_value.value as value_name');
                        $this->db->where('lead', $id);
                        $this->db->where('id_detail', $vInfo['id']);
                        $info_view_detail[$kInfo]['value'] = $this->db->get('tbllead_value')->row();
                    }
                }
                $getData['view_detail'] = $info_view_detail;

                $lead = get_table_where('tblleads', ['id' => $id], '', 'row');
                $getData['date_contact'] = _dt($lead->date_contact);
            }
        }
        echo json_encode($getData);die;
    }

    public function getProcedure_detail($id_obj = '')
    {
        $data = explode('_', $id_obj);
        $type = $data[0];
        $id = $data[1];
        if($type == 'client') {
            $this->db->select(db_prefix().'procedure_client_detail.*');
            $this->db->where('type' ,'lead');
            $this->db->where('tblprocedure_client_detail.check_type',NULL);
            $this->db->join(db_prefix().'procedure_client', db_prefix().'procedure_client.id = '.db_prefix().'procedure_client_detail.id_detail');
            $getData = $this->db->get(db_prefix().'procedure_client_detail')->result_array();
        }
        if($type == 'lead') {
            $this->db->select(db_prefix().'procedure_client_detail.*');
            $this->db->where('type' ,'lead');
            $this->db->join(db_prefix().'procedure_client', db_prefix().'procedure_client.id = '.db_prefix().'procedure_client_detail.id_detail');
            $getData = $this->db->get(db_prefix().'procedure_client_detail')->result_array();
        }
        echo json_encode($getData);die;
    }

	public function updateColums()
	{
		$data_input = $this->input->post('data_input');
		$name_input = $this->input->post('name_input');
		$id = $this->input->post('id');
		if(!empty($name_input) && !empty($id))
		{
			if(strpos($name_input, 'datetime_picker_') !== false)
			{
				$data_input = to_sql_date($data_input, true);
				$name_input = str_replace('datetime_picker_', '', $name_input ) ;
			}
			else if(strpos($name_input, 'date_picker_') !== false)
			{
				$data_input = to_sql_date($data_input);
				$name_input = str_replace('date_picker_', '', $name_input) ;
			}

			$this->db->where('id', $id);
			$advisory_lead = $this->db->get('tbladvisory_lead')->row();
			if(!empty($advisory_lead))
			{

				$this->db->where('id', $id);
				$success = $this->db->update('tbladvisory_lead', [$name_input => $data_input]);
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

	public function Search_Staff($id = "")
	{
		$data = [];
		$search = $this->input->get('term');
		$limit_all = 100;
		$this->db->select('staffid as id, concat(COALESCE(lastname)," ", COALESCE(firstname)) as text', false);
		if(!empty($id))
		{
			$this->db->where('staffid', $id);
			$staff = $this->db->get('tblstaff')->row();
			if(!empty($staff))
			{
				$data['results'] = $staff;
				echo json_encode($data);die();
			}
		}
		else
		{
			if (!empty($search))
			{
				$this->db->group_start();
				$this->db->like('concat(COALESCE(lastname)," ", COALESCE(firstname))', $search);
				$this->db->group_end();
			}
			$this->db->order_by('firstname', 'DESC');
			$this->db->limit($limit_all);
			$staff = $this->db->get('tblstaff')->result_array();
			if(!empty($staff))
			{
				$data['results'] = $staff;
			}
		}
		echo json_encode($data);die();
	}

	//Get modal chi tiết phiếu chăm sóc
	public function getModalDetail() {
		$id = $this->input->post('id');
		if(!empty($id))
		{
			$this->db->select('
				concat(COALESCE(tbladvisory_lead.prefix),COALESCE(tbladvisory_lead.code)) as fullcode,
				tbladvisory_lead.*
            ');
			$this->db->where('tbladvisory_lead.id', $id);
			$data['advisory'] = $this->db->get('tbladvisory_lead')->row();
			echo json_encode([
				'data' => $this->load->view('admin/advisory_lead/modal_view_detail', $data, true)
			]);die();
		}
	}

}
