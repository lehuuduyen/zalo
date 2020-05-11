<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Quick_reply extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data['title'] = _l('quick_reply');
        $this->load->view('admin/quick_reply/manage', $data);
    }

    public function table_quick_reply($value='')
    {
        if (!has_permission('quick_reply', '', 'view')) {
            ajax_access_denied();
        }
        $this->app->get_table_data('quick_reply');
    }

    public function detail($id = '')
    {
        $data = $this->input->post();
	    $id = $this->input->post('id');
        if($id == "") {
            $in = array(
                'name' => $data['name'],
                'id_parent' => !empty($data['id_parent']) ? $data['id_parent'] : NULL,
                'content' => $data['content']
            );
            if(!empty($data['id_parent']))
            {
                $this->db->where('id', $data['id_parent']);
                $parent_quick_reply = $this->db->get('tblquick_reply')->row();
                if(!empty($parent_quick_reply))
                {
	                $in['num_parent'] = $parent_quick_reply->num_parent + 1;
                }
            }

            $this->db->insert('tblquick_reply',$in);
            echo json_encode(array('success' => true, 'alert_type' => 'success', 'message' => _l('add_quick_reply_success')));
        }
        else {
            $in = array(
                'name' => $data['name'],
                'id_parent' => !empty($data['id_parent']) ? $data['id_parent'] : NULL,
                'content' => $data['content']
            );
	        if(!empty($data['id_parent']))
	        {
		        $this->db->where('id', $data['id_parent']);
		        $parent_quick_reply = $this->db->get('tblquick_reply')->row();
		        if(!empty($parent_quick_reply))
		        {
			        $in['num_parent'] = $parent_quick_reply->num_parent + 1;
		        }
	        }
            $this->db->where('id', $id);
            $success = $this->db->update('tblquick_reply', $in);
            if(!empty($success))
            {
                echo json_encode(array('success' => true, 'alert_type' => 'success', 'message' => _l('edit_quick_reply_success')));
            }
	        echo json_encode(array('success' => false, 'alert_type' => 'danger', 'message' => _l('cong_update_false')));
        }
    }

    public function delete_quick_reply($id='')
    {
        $this->db->where('id',$id);
        $this->db->delete('tblquick_reply');
        echo json_encode(array('success' => true, 'alert_type' => 'success', 'message' => _l('delete_quick_reply_success')));
    }

	public function SearchQuickreply($id = "")
	{
		$data = [];
		$search = $this->input->get('term');
		$limit_all = 200;
		$this->db->select('
            id,
            name as text,
        ', false);
		if(!empty($id))
		{
			$this->db->where('id', $id);
		}
		if(!empty($search))
		{
			$this->db->group_start();
			$this->db->like('name', $search);
			$this->db->group_end();
		}
		$this->db->order_by('tblquick_reply.name', 'DESC');
		$this->db->limit($limit_all);
		$product = $this->db->get('tblquick_reply')->result_array();
		if(!empty($product))
		{
			$data['results'] = $product;
		}
		echo json_encode($data);die();

	}

	public function getData($id = "")
	{
		$data = [];
		if(!empty($id))
		{
			$this->db->where('id', $id);
			$data['quick_reply'] = $this->db->get('tblquick_reply')->row();
		}
		$this->db->where('id_parent is null');
		$quick_reply = $this->db->get('tblquick_reply')->result_array();
		$arrayOption = [];
		foreach($quick_reply as $key => $value)
		{
			$arrayOption[] = $value;
			getChildQuick_reply($value['id'], $arrayOption, $icon = '➪');
		}
		$data['parent'] = $arrayOption;
		$this->load->view('admin/quick_reply/modal', $data);
	}

}