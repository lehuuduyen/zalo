<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Racks extends AdminController
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('rack_model');
    }
    /* Open also all taks if user access this /tasks url */
    public function index()
    {

        $this->list_racks();
    }
    /* List all tasks */
    public function list_racks()
    {
        if (!is_admin()) {
            access_denied('contracts');
        }

        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('racks');
        }
        $data['roles']=$this->rack_model->get_roles();
        $data['title'] = _l('Lái xe');
        $this->load->view('admin/racks/manage', $data);
    }
    /* Get task data in a right pane */
    public function delete_rack($id)
    {
        if (!$id) {
            die('Không tìm thấy mục nào');
        }
        $success    = $this->rack_model->delete_rack($id);
        $alert_type = 'warning';
        $message    = _l('Không thể xóa dữ liệu');
        if ($success) {
            $success=true;
            $alert_type = 'success';
            $message    = _l('Xóa dữ liệu thành công');
        }
        echo json_encode(array(
            'success'=>$success,
            'alert_type' => $alert_type,
            'message' => $message
        ));

    }
     public function add_rack()
    {
        if ($this->input->post()) {
            $message = '';
                $id = $this->rack_model->add_rack($this->input->post(NULL, FALSE));
                if ($id) {
                    $success = true;
                    $message = _l('added_successfuly', _l('als_racks'));
                }
                echo json_encode(array(
                    'success' => $success,
                    'message' => $message
                ));
            die;
        }
    }
    public function update_rack($id="")
    {
        if($id!=""){
            $message    = '';
            $alert_type = 'warning';
            if ($this->input->post()) {
                $success = $this->rack_model->update_rack($this->input->post(), $id);
                if ($success) {
                    $message    = 'Cập nhật dữ liệu thành công';
                };
            }
            echo json_encode(array(
                'success' => $success,
                'message' => $message
            ));
        }
        else
        {
            if ($this->input->post()) {
                $success = $this->rack_model->add_rack($this->input->post());
                if ($success) {
                    $alert_type = 'success';
                    $message    = 'Thêm dữ liệu thành công';
                }
            }
            echo json_encode(array(
                'alert_type' => $alert_type,
                'message' => $message
            ));
        }
        die;
    }



    public function get_row_rack($id)
    {
        echo json_encode($this->rack_model->get_row_rack($id));
    }


    public function name_racks()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                // First we need to check if the email is the same
                $userid = $this->input->post('id');
                if ($userid != '') {
                    $this->db->where('rackid', $userid);
                    $this->db->where('rack', mb_convert_case($this->input->post('rack'), MB_CASE_TITLE, "UTF-8"));
                    $rack = $this->db->get('tblracks')->row();
                    if ($rack) {
                        echo json_encode(true);
                        die();
                    }
                }
                $this->db->where('rack', mb_convert_case($this->input->post('rack'), MB_CASE_TITLE, "UTF-8"));
                $total_rows = $this->db->count_all_results('tblracks');
                if ($total_rows > 0) {
                    echo json_encode(false);
                } else {
                    echo json_encode(true);
                }
                die();
            }
        }
    }

}
