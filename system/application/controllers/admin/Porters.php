<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Porters extends AdminController
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('porters_model');
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
            $this->app->get_table_data('porters');
        }
        $data['title'] = _l('Bốc vác');
        $this->load->view('admin/porters/manage', $data);
    }
    /* Get task data in a right pane */
    public function delete($id)
    {
        if (!$id) {
            die('Không tìm thấy mục nào');
        }
        $success    = $this->porters_model->delete_porters($id);
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
     public function add_porters()
    {
        if ($this->input->post()) {
            $message = '';
                $id = $this->porters_model->add_porters($this->input->post(NULL, FALSE));
                if ($id) {
                    $success = true;
                    $message = _l('added_successfuly', _l('als_porters'));
                }
                echo json_encode(array(
                    'success' => $success,
                    'message' => $message
                ));
            die;
        }
    }
    public function update_porters($id="")
    {
        if($id!=""){
            $message    = '';
            $alert_type = 'warning';
            if ($this->input->post()) {
                $success = $this->porters_model->update_porters($this->input->post(), $id);
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
                $success = $this->porters_model->add_porters($this->input->post());
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



    public function get_row_porters($id)
    {
        echo json_encode($this->porters_model->get_row_porters($id));
    }

    public function name_porters()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                // First we need to check if the email is the same
                $userid = $this->input->post('id');
                if ($userid != '') {
                    $this->db->where('id', $userid);
                    $this->db->where('name', mb_convert_case($this->input->post('name'), MB_CASE_TITLE, "UTF-8"));
                    $porter = $this->db->get('tblporters')->row();
                    if ($porter) {
                        echo json_encode(true);
                        die();
                    }
                }
                $this->db->where('name', mb_convert_case($this->input->post('name'), MB_CASE_TITLE, "UTF-8"));
                $total_rows = $this->db->count_all_results('tblporters');
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
