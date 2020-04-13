<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Payment_time_level extends AdminController
{
    function __construct()
    {
        parent::__construct();
    }
    public function index()
    {

        $data['title'] = _l('Mức thời gian thanh toán');
        $this->load->view('admin/payment_time_level/manage', $data);
    }
    public function table()
    {
        $this->app->get_table_data('payment_time_level');
    }
    /* Get task data in a right pane */
    public function delete_payment_time_level($id)
    {
        if (!$id) {
            die('ch_no_items');
        }
        $success    = $this->db->delete('tblpayment_time_level',array('id'=>$id));
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
     public function add_payment_time_level()
    {
        if ($this->input->post()) {
            $message = '';
                
                $data = $this->input->post();
                $data['staff_id'] = get_staff_user_id();
                $data['data_create'] = date('Y-m-d H:i:s');
                $this->db->insert('tblpayment_time_level',$data);
                $id = $this->db->insert_id();
                if ($id) {
                    $success = true;
                    $message = _l('added_successfuly', _l('als_units'));
                }
                echo json_encode(array(
                    'success' => $success,
                    'message' => $message
                ));
            die;
        }
    }
    public function update_payment_time_level($id="")
    {
        if($id!=""){
            $message    = '';
            $alert_type = 'warning';
            if ($this->input->post()) {
                $this->db->where('id',$id);
                $success = $this->db->update('tblpayment_time_level',$this->input->post());
                if ($success) {
                    $message    = 'ch_updatee_items';
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
                $data = $this->input->post();
                $data['staff_id'] = get_staff_user_id();
                $data['data_create'] = date('Y-m-d H:i:s');
                $this->db->insert('tblpayment_time_level',$data);
                $success = $this->db->insert_id();
                if ($success) {
                    $alert_type = 'success';
                    $message    = 'ch_adde_items';
                }
            }
            echo json_encode(array(
                'alert_type' => $alert_type,
                'message' => $message
            ));
        }
        die;
    }



    public function get_row_unit($id)
    {
        echo json_encode(get_table_where('tblpayment_time_level',array('id'=>$id),'','row'));
    }


}
