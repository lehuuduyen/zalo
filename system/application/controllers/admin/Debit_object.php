<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Debit_object extends AdminController
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('cash_book_model');

        $this->load->model('debit_object_model');
    }


    public function index()
    {
        $array=array();
        if($this->input->get('thang')==true)
        {
            $array['is_admin']=true;
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('debit_object', $array);
        }
        $data['group_cash_book']=get_table_where('tblgroup_cash_book');
        $data['payments_modes']=get_table_where('tblinvoicepaymentsmodes');
        $data['staff']=get_table_where('tblstaff');
        $data['date_end']=date('Y-m-d');
        $date = new DateTime($data['date_end']);;
        date_sub($date, date_interval_create_from_date_string('30 days'));
        $data['date_start'] = date_format($date, 'Y-m-d');
        $data['title'] = _l('Điều chỉnh công nợ');
        $this->load->view('admin/debit_object/manage', $data);
    }
    public function order_detail($id='')
    {

        if(!has_permission('debit_object','','view')&&!has_permission('debit_object','','view'))
        {
            access_denied('debit_object');
        }
        if ($this->input->post()) {
            $data=$this->input->post();
            if ($id == '') {
                if(!has_permission('debit_object','','create'))
                {
                    access_denied('debit_object');
                }
                $data['code'] = $this->get_cash_code('return');
                $id = $this->debit_object_model->add($data);

                if ($id) {
                   echo json_encode(array('alert_type'=>'success','add'=>$id,'message'=>'Thêm phiếu thành công'));die();
                }
                echo json_encode(array('alert_type'=>'danger','message'=>'Thêm phiếu không thành công'));die();
            }
            else
            {
                if(!has_permission('debit_object','','edit'))
                {
                    access_denied('debit_object');
                }
                $success = $this->debit_object_model->update($data, $id);


                if ($success == true) {
                    echo json_encode(array('alert_type'=>'success','message'=>'cập nhật phiếu thành công'));die();
                }
                else
                {
                    echo json_encode(array('alert_type'=>'danger','message'=>'cập nhật phiếu không thành công'));die();
                }
            }
        }
    }

    public function get_cash()
    {
        if($this->input->post('id'))
        {

            $id=$this->input->post('id');
            $this->db->where('id',$id);
            $result=$this->db->get('tbldebit_object')->row();
            $result->date=_d($result->date);
            echo json_encode($result);die();
        }
    }
//
    public function get_cash_code($type=0)
    {
        $number=getMaxIDCODE('id','tbldebit_object',array())+1;
        $code=str_pad($number, 6, '0', STR_PAD_LEFT);
        if($type='return')
        {
            return 'CN-'.$code;
        }
        echo json_encode(array('code'=>$code));
    }

//  public function pdf($id="")
//    {
//        $receipts = $this->receipts_model->get_data_pdf($id);
//        if ($this->input->get('combo')) {
//            $receipts->combo=$this->input->get('combo');
//        }
//        $pdf      = receipts_pdf($receipts);
//
//        $type     = 'D';
//        if ($this->input->get('print')) {
//            $type = 'I';
//        }
//
//        $pdf->Output(slug_it($receipts->code_vouchers) . '.pdf', $type);
//    }
//

    public function update_status()
    {
        $id=$this->input->post('id');
        $status=$this->input->post('status');
        $staff_id=get_staff_user_id();
        $date=date('Y-m-d H:i:s');
        $data=array('status'=>$status);

        $inv=get_table_where('tbldebit_object',array('id'=>$id),'','row');
        if(is_admin() && $inv->status<2)
        {
            $data['user_admin_id']=$staff_id;
            $data['user_admin_date']=$date;

            $data['status']=2;
        }

        $success=false;
        if(is_admin())
        {

            $success=$this->debit_object_model->update_status($id,$data);
        }
        if($success) {
            echo json_encode(array(
                'success' => $success,
                'message' => _l('Xác nhận phiếu thành công')
            ));
        }
        else
        {
            echo json_encode(array(
                'success' => $success,
                'message' => _l('Không thể cập nhật dữ liệu')
            ));
        }
        die;
    }
    public function delete($id)
    {
        if(is_numeric($id))
        {
            $is_admin=$this->input->get('is_admin');
            $object_debt=get_table_where('tbldebit_object',array('id'=>$id),'','row');
            if(!empty($object_debt)&&($is_admin||$object_debt->status!=2))
            {
                $result=$this->debit_object_model->delete($id);
                if($result)
                {
                    $message=_l('Xóa dữ liệu thành công');
                    set_alert('success', _l('Xóa dữ liệu thành công'));
                }
                else
                {
                    $message=_l('Xóa dữ liệu không thành công');
                    set_alert('danger', _l('Xóa dữ liệu không thành công'));

                }
                if($this->input->is_ajax_request())
                {
                    echo json_encode(array('success'=>$result,'message'=>$message));die;
                }
                redirect(admin_url('debit_object'));
            }
        }

    }
    public function get_object()
    {
        $table=$this->input->post('id');
        if($table)
        {
            if($table=='tblstaff')
            {
                $this->db->select('staffid as id,CONCAT(lastname, firstname) as name');
            }
            if($table=='tblsuppliers')
            {
                $this->db->select('userid as id,company as name');
            }
            if($table=='tblcustomers')
            {
                $this->db->select('id,customer_shop_code as name');
            }
            if($table=='tblracks')
            {
                $this->db->select('rackid as id,rack as name');
            }
            if($table=='tblracks')
            {
                $this->db->select('rackid as id,rack as name');
            }
            $result=$this->db->get($table)->result_array();
            echo json_encode($result);die();
        }
    }
//    public function get_information($id_contract=NULL)
//    {
//        $id=$this->input->post('id');
//        if(is_numeric($id))
//        {
//            $this->db->where('id_other_object',$id);
//            $this->db->where('status!=',1);
//            if($id_contract)
//            {
//                $this->db->or_where('id',$id_contract);
//            }
//            $result=$this->db->get('tblcontract_borrowing')->result_array();
//            echo json_encode($result);die();
//        }
//    }
}
