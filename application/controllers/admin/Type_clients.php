<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Type_clients extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('clients_model');
    }

    public function index()
    {
        $this->db->where('category_parent', '0');
        $categories = $this->db->get('tbltype_client')->result_array();
        $data['full_categories'] = $categories;

        $data['categories'] = [];
        $this->clients_model->get_by_id(0,$data['categories']);

        $data['title'] = _l('cong_type_client');
        $this->load->view('admin/type_clients/manage', $data);
    }

    public function add_category()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $data['create_by'] = get_staff_user_id();
            $data['date_create'] = date('Y-m-d H:i:s');
            $id = $this->db->insert('tbltype_client',$data);
            $message = '';
            if ($id) {
                $success = true;
                $message = _l('ch_added_successfuly', _l('cong_type_client_add_heading'));
            }
            echo json_encode(array(
                'success' => $success,
                'message' => $message
            ));die;
        }
    }

    public function update_category($id="")
    {
        if($id!=""){
            $message    = '';
            $alert_type = 'warning';
            if ($this->input->post()) {
                $data = $this->input->post();
                $this->db->where('id',$id);
                $success = $this->db->update('tbltype_client',$data);

                if ($success) {
                    $message = _l('ch_updated_successfuly', _l('cong_type_client_edit_heading'));
                };
            }
            echo json_encode(array(
                'success' => $success,
                'message' => $message
            ));die;
        }
        else
        {
            if ($this->input->post()) {
                $data = $this->input->post();
                $data['create_by'] = get_staff_user_id();
                $data['date_create'] = date('Y-m-d H:i:s');
                $success = $this->db->insert('tbltype_client',$data);
                if ($success) {
                    $alert_type = 'success';
                    $message = _l('ch_added_successfuly', _l('cong_type_client_add_heading'));
                }
            }
            echo json_encode(array(
                'alert_type' => $alert_type,
                'message' => $message
            ));die;
        }
    }
    public function delete_type_client($id = '')
    {
        $checkExists = get_table_where('tblclients',array('type_client'=>$id),'','row');
        $checkExists_lead = get_table_where('tblleads',array('type_lead'=>$id),'','row');
        if($checkExists || $checkExists_lead) {
            $success = false;
            $message = _l('dont_delete_content');
        }
        else {
            $this->db->where('id',$id);
            $result = $this->db->delete('tbltype_client');
            $success = false;
            $message = _l('delete_dont');
            if($result) {
                $success = true;
                $message = _l('cong_update_true');
            }
        }
        echo json_encode(array(
            'success' => $success,
            'message' => $message
        ));die;
    }
}
