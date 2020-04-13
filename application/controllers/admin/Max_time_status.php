<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Max_time_status extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_admin()) {
            redirect(admin_url());
        }
        $this->load->model('max_time_status_model');
    }

    public function index()
    {
        $data = array();
        $data['items'] = $this->max_time_status_model->get_all();
        $data['update_success'] = $this->session->flashdata('update_success');
        $this->load->view('admin/max_time_status', $data);
    }

    public function update_max_time_status()
    {
        $data = $this->input->post();
        if(!empty($data)){
            $data_update = array();
            foreach ($data as $k => $v){
                array_push($data_update, array(
                   'id' => $k,
                    'duration' => $v,
                ));
            }
            $x = $this->max_time_status_model->update_batch($data_update);
            $this->session->set_flashdata('update_success', 1);
            redirect(base_url('admin/max_time_status'));
        }
    }
}
