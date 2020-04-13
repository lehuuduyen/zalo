<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Account_bus extends AdminController
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('account_bus_model');
    }
    /* Open also all taks if user access this /tasks url */
    public function index()
    {

        $this->list_account_bus();
    }

    /* List all tasks */
    public function list_account_bus()
    {

       
        $data['title'] = _l('ch_account_bus');
        $this->load->view('admin/account_bus/manage', $data);
    }
    public function table()
    {
        $this->app->get_table_data('account_bus');
    }
    /* Get task data in a right pane */
    public function delete_account_bus($id)
    {
        if (!$id) {
            die('ch_no_items');
        }
        $success    = $this->account_bus_model->delete_account_bus($id);
        $alert_type = 'warning';
        $message    = _l('ch_no_delete');
        if ($success) {
            $alert_type = 'success';
            $message    = _l('ch_delete');
        }
        echo json_encode(array(
            'alert_type' => $alert_type,
            'message' => $message
        ));

    }
     public function add_account_bus()
    {
        if ($this->input->post()) {
            $message = '';
                $id = $this->account_bus_model->add_account_bus($this->input->post(NULL, FALSE));
                if ($id) {
                    $success = true;
                    $message = _l('cong_add_true', _l('als_account_bus'));
                }
                echo json_encode(array(
                    'success' => $success,
                    'message' => $message
                ));
            die;
        }
    }
    public function update_account_bus($id="")
    {
        if($id!=""){
            $message    = '';
            $alert_type = 'warning';
            if ($this->input->post()) {
                $success = $this->account_bus_model->update_account_bus($this->input->post(), $id);
                if ($success) {
                    $message    = _l('updated_successfuly');
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
                $success = $this->account_bus_model->add_account_bus($this->input->post());
                if ($success) {
                    $alert_type = 'success';
                    $message    = _l('cong_add_true');
                }
            }
            echo json_encode(array(
                'alert_type' => $alert_type,
                'message' => $message
            ));
        }
        die;
    }



    public function get_row_account_bus($id)
    {
        echo json_encode($this->account_bus_model->get_row_account_bus($id));
    }


}
