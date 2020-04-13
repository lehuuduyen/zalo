<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Costs extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('costs_model');
    }
    public function index()
    {
        if (!has_permission('costs', '', 'view')) {
                access_denied('Debt suppliers');
        }
        $data['title']          = _l('ch_costs');
        $data['costs'] = [];
        $this->costs_model->get_by_id(0,$data['costs']);
        $full_costs = $this->costs_model->get_full_costs();
        $data['full_costs'] = $full_costs;
        $this->load->view('admin/costs/manage', $data);
    }
  	public function table()
    {
        if (!has_permission('costs', '', 'view')) {
                ajax_access_denied();
        }
        $this->app->get_table_data('costs');
    }
    public function get_parent($id='')
    {
       $lever =  get_table_where('tblcosts',array('id'=>$id),'','row');
       $data['data'] = get_table_where('tblcosts',array('lever'=>($lever->lever - 1)));
       $data['costs_parent'] = $lever->costs_parent;
       echo json_encode($data);
    }  
    public function get_costs_parents()
    {
        $data['costs'] = [];
        $this->costs_model->get_costs_parent(0,$data['costs']);
        echo json_encode($data['costs']);
    }
    public function add()
    {
        if ($this->input->post()) {
                $message = '';
                $data = $this->input->post();
                unset($data['id']); 
                if($data['costs_parent']==NULL || $data['costs_parent']=='')
                {
                    $data['lever']=1;
                }else
                {
                    $lever = 1;
                    $parent = $data['costs_parent'];
                         

                    while ($parent > 0) {
                       $ktr = get_table_where('tblcosts',array('id'=>$parent),'','row');
                       $parent = $ktr->costs_parent;
                       $lever++; 

                    }
                    $data['lever'] = $lever;
                }
                $this->db->insert('tblcosts',$data);

                $id = $this->db->insert_id();
                if ($id) {
                    $success = true;
                    $message = _l('ch_added_successfuly');
                }
                echo json_encode(array(
                    'success' => $success,
                    'message' => $message
                ));
            die;
        }
    }
    public function update()
    {
        if ($this->input->post()) {
                $message = '';
                $data = $this->input->post();
                $id = $data['id'];
                unset($data['id']);
                $this->db->where('id',$id);
                $idd = $this->db->update('tblcosts',$data);

                if ($id) {
                    $success = true;
                    $message = _l('ch_updated_successfuly');
                }
                echo json_encode(array(
                    'success' => $success,
                    'message' => $message
                ));
            die;
        }
    }
    public function get_exsit($id='')
    {
        // $items = get_table_where('tblitems',array('category_id'=>$id),'','row');
        // if(!empty($items))
        // {
        //     echo json_encode(true);die;
        // }else
        // {
        //     $parent = get_table_where('tblcategories',array('category_parent'=>$id),'','row');
        //     if(!empty($parent))
        //     {
        //     echo json_encode(true);die;    
        //     }
        //     $success = $this->db->delete('tblcategories',array('id'=>$id));

        //         if ($success) {
        //             $success = true;
        //             $message = _l('ch_delete_successfuly', _l('ch_categories'));
        //         }
        //         echo json_encode(array(
        //             'success' => $success,
        //             'message' => $message
        //         ));die;
        // }
    }
}