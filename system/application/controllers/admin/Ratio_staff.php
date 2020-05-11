<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ratio_staff extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data['title']          = _l('cong_title_ratio_staff_assigned');
        $this->load->view('admin/ratio_staff/manage', $data);
    }
  	public function table()
    {
        $this->app->get_table_data('ratio_staff');
    }

    public function detail($id = '')
    {
		if($this->input->post())
		{
			$data = $this->input->post();
			if(!empty($id))
			{
				$staff = $data['staff'];
				unset($data['staff']);
				$arrayUpdate = [
					'name' => $data['name'],
					'month' => $data['month'],
					'year' => $data['year'],
					'create_by' => get_staff_user_id(),
					'date_create' => date('Y-m-d H:i:s'),
				];

				$this->db->where('id', $id);
				$success = $this->db->update('tblratio_staff', $arrayUpdate);
				if(!empty($success))
				{
					foreach($staff as $key => $value)
					{
						$ktDetail = get_table_where('tblratio_staff_detail', [
							'id_ratio' => $id,
							'id_staff' => $key
						], '', 'row');
						if(empty($ktDetail))
						{
							$this->db->insert('tblratio_staff_detail', [
								'id_ratio' => $id,
								'id_staff' => $key,
								'ratio' => !empty($value) ? $value : 0
							]);
						}
						else
						{
							$this->db->where('id', $ktDetail->id);
							$this->db->update('tblratio_staff_detail', ['ratio' => !empty($value) ? $value : 0]);
						}
					}
					set_alert('success', _l('cong_update_true'));
				}
				else
				{
					set_alert('danger', _l('cong_update_false'));
				}
				redirect('admin/ratio_staff');
			}
			else
			{
				$staff = $data['staff'];
				unset($data['staff']);
				$arrayAdd = [
					'name' => $data['name'],
					'month' => $data['month'],
					'year' => $data['year'],
					'create_by' => get_staff_user_id(),
					'date_create' => date('Y-m-d H:i:s'),
				];
				$this->db->insert('tblratio_staff', $arrayAdd);
				$id_insert = $this->db->insert_id();
				if(!empty($id_insert))
				{
					foreach($staff as $key => $value)
					{
						$this->db->insert('tblratio_staff_detail', [
							'id_ratio' => $id_insert,
							'id_staff' => $key,
							'ratio' => !empty($value) ? $value : 0
						]);
					}
					set_alert('success', _l('cong_add_true'));
				}
				else
				{
					set_alert('danger', _l('cong_add_false'));
				}
				redirect('admin/ratio_staff');
			}
		}
		else
		{
			if(!empty($id))
			{
				$data['title'] = _l('cong_edit_ratio_staff');
				$this->db->where('id', $id);
				$data['ratio_staff'] = $this->db->get('tblratio_staff')->row();
				if(!empty($data['ratio_staff']))
				{
					$this->db->select('tblstaff.*, tblratio_staff_detail.ratio');
					$this->db->join('tblratio_staff_detail', 'tblratio_staff_detail.id_staff = tblstaff.staffid and id_ratio = '.$id, 'left');
					$data['staff']  = $this->db->get('tblstaff')->result_array();
				}

				$this->db->select('group_concat(month) as listMonth');
				$this->db->where('year', $data['ratio_staff']->year);
				$this->db->where('id != '.$id);
				$ratio = $this->db->get('tblratio_staff')->row();
				if(!empty($ratio))
				{
					$listMonth = explode(',', $ratio->listMonth);
				}
				$arrayMonth = [];
				for($i = 1; $i <= 12 ; $i++)
				{
					$arrayMonth[$i] = $i;
				}

				foreach($listMonth as $key => $value)
				{
					unset($arrayMonth[$value]);
				}
				$data['month'] = [];
				foreach($arrayMonth as $key => $value)
				{
					$data['month'][] = [
						'id' => $value,
						'name' => _l('cong_month').' '.$value
					];
				}
			}
			else
			{
				$data['title'] = _l('cong_add_ratio_staff');
				$data['staff'] = get_table_where('tblstaff', ['active' => 1]);
				$data['month'] = [];
			}

			$yearNow = date('Y');
			$data['year'] = [];
			for($i = $yearNow - 1; $i < $yearNow+3; $i++)
			{
				$data['year'][] = ['id' => $i, 'name' => _l('cong_year').' '.$i];
			}
			$this->load->view('admin/ratio_staff/detail', $data);
		}
    }

    public function getMonth()
    {
    	if($this->input->post())
	    {
	    	$data = $this->input->post();
	    	$this->db->select('group_concat(month) as listMonth');
	    	$this->db->where('year', $data['year']);
	    	if(!empty($data['id']))
		    {
		    	$this->db->where('id != '.$data['id']);
		    }
	    	$ratio = $this->db->get('tblratio_staff')->row();
	    	if(!empty($ratio))
		    {
		    	$listMonth = explode(',', $ratio->listMonth);
		    }

		    $month = [
			    1 => _l('cong_month').' 1',
			    2 => _l('cong_month').' 2',
			    3 => _l('cong_month').' 3',
			    4 => _l('cong_month').' 4',
			    5 => _l('cong_month').' 5',
			    6 => _l('cong_month').' 6',
			    7 => _l('cong_month').' 7',
			    8 => _l('cong_month').' 8',
			    9 => _l('cong_month').' 9',
			    10 => _l('cong_month').' 10',
			    11 => _l('cong_month').' 11',
			    12 => _l('cong_month').' 12',
		    ];

		    foreach($listMonth as $key => $value)
		    {
		    	unset($month[$value]);
		    }
		    echo json_encode($month);die();
	    }
    }

    public function Delete()
    {
	    $id = $this->input->post('id');
    	if(!empty($id))
	    {
	    	$this->db->where('id', $id);
	    	$success = $this->db->delete('tblratio_staff');
	    	if(!empty($success))
		    {
		    	$this->db->where('id_ratio', $id);
		    	$this->db->delete('tblratio_staff_detail');
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

}