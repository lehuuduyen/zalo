<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Other_object extends AdminController
{
    function __construct()
    {
        parent::__construct();
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
            access_denied('Other_object');
        }

        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('other_object');
        }
        $data['title'] = _l('Đối tượng vay mượn');
        $this->load->view('admin/other_object/manage', $data);
    }
    /* Get task data in a right pane */
    public function delete_other_object($id)
    {
        if (!$id) {
            die('Không tìm thấy mục nào');
        }
        $this->db->where('id',$id);
        $success=$this->db->delete('tblother_object');
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
     public function add_other_object()
    {
        if ($this->input->post()) {
            $message = '';
            $data=$this->input->post(NULL, FALSE);
            $data['create_by']=get_staff_user_id();
            $data['date_create']=date('Y-m-d');
            $data['name']=mb_convert_case($data['name'], MB_CASE_TITLE, "UTF-8");
            $this->db->insert('tblother_object',$data);
            $id=$this->db->insert_id();
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
    public function update_other_object($id="")
    {
        if($id!=""){
            $message    = '';
            $alert_type = 'warning';
            if ($this->input->post()) {
                $data=$this->input->post();
                $data['name']=mb_convert_case($data['name'], MB_CASE_TITLE, "UTF-8");
                $this->db->where('id',$id);
                $success=$this->db->update('tblother_object',$data);
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
                $data=$this->input->post(NULL, FALSE);
                $data['create_by']=get_staff_user_id();
                $data['date_create']=date('Y-m-d');
                $data['name']=mb_convert_case($data['name'], MB_CASE_TITLE, "UTF-8");
                $this->db->insert('tblother_object',$data);
                $success=$this->db->insert_id();
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



    public function get_row_rack($id=NULL)
    {
        if(is_numeric($id))
        {
            $this->db->where('id',$id);
            $result=$this->db->get('tblother_object')->row();
        }
        echo json_encode($result);die();
    }


    public function name_other_object()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                // First we need to check if the email is the same
                $userid = $this->input->post('id');
                if ($userid != '') {
                    $this->db->where('id', $userid);
                    $this->db->where('name', mb_convert_case($this->input->post('name'), MB_CASE_TITLE, "UTF-8"));
                    $rack = $this->db->get('tblother_object')->row();
                    if ($rack) {
                        echo json_encode(true);
                        die();
                    }
                }
                $this->db->where('name', mb_convert_case($this->input->post('name'), MB_CASE_TITLE, "UTF-8"));
                $total_rows = $this->db->count_all_results('tblother_object');
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
