<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Reports_revenue extends AdminController
{
    private $_instance;

    function __construct()
    {
        parent::__construct();
        if (!has_permission('reports', '', 'view')) {
            access_denied('reports');
        }
    }

    /* No access on this url */
    public function index()
    {
        if (!has_permission('reports', '', 'view')) {
            access_denied('reports');
        }
        redirect(site_url('admin'));
    }

    //báo cáo chính sách
    public function attrition()
    {
        $data['title']='Báo cáo Khấu hao';
        $this->load->view('admin/reports_revenue/attrition',$data);
    }
    public function get_table_attrition($status=0)
    {
        if ($this->input->is_ajax_request())
        {
            $this->app->get_table_data('reports_revenue_attrition',array('status'=>$status));
        }
    }
    public function add_attrition($id=NULL)
    {
        if($this->input->post())
        {
            $data=$this->input->post();
            $data['date']=to_sql_date($data['date']);
            $data['quantity']=str_replace(',','',$data['quantity']);
            $data['price']=str_replace(',','',$data['price']);

            $date=$data['date'];
            $month=$data['month'];

            $data['date_last']=date("Y-m-d", strtotime("$date +$month months"));
            if(!empty($data['id']))
            {
                $id=$data['id'];
                unset($data['id']);
                $this->db->where('id',$id);
                if($this->db->update('tblattrition',$data))
                {
                    echo json_encode(array('alert_type'=>'success','success'=>true,'message'=>'Cập nhật dữ liệu thành công'));die();
                }
                echo json_encode(array('alert_type'=>'danger','success'=>false,'message'=>'Cập nhật dữ liệu không thành công'));die();
            }
            else
            {
                $data['create_by']=get_staff_user_id();
                $data['date_create']=date('Y-m-d');
                $this->db->insert('tblattrition',$data);
                if(!empty($this->db->insert_id()))
                {
                    echo json_encode(array('alert_type'=>'success','success'=>true,'message'=>'Thêm dữ liệu thành công'));die();
                }
                echo json_encode(array('alert_type'=>'danger','success'=>false,'message'=>'Thêm dữ liệu không thành công'));die();
            }
        }
        else
        {
            if(!empty($id))
            {
                $attrition=get_table_where('tblattrition',array('id'=>$id),'','row');
                if(!empty($attrition))
                {
                    $attrition->date=_d($attrition->date);
                }
                echo json_encode($attrition);die();
            }
        }
    }

    public function delete_attrition($id=NULL)
    {
        if(!empty($id))
        {
            $this->db->where('id',$id);
            if($this->db->delete('tblattrition'))
            {
                echo json_encode(array('alert_type'=>'success','success'=>true,'message'=>'Xóa dữ liệu thành công'));die();
            }
            echo json_encode(array('alert_type'=>'danger','success'=>false,'message'=>'Xóa dữ liệu không thành công'));die();
        }
    }
    public function update_status()
    {
        $data=$this->input->post();
        unset($data['id_attrition']);
        $data['note_buy']=$this->input->post('note_buy',false);

        $id=$this->input->post('id_attrition');
        $staff_id=get_staff_user_id();
        $date=date('Y-m-d H:i:s');
        $data['status']=2;
        $get_attrition=get_table_where('tblattrition',array('id'=>$id),'','row');
        $success=false;
        if(!empty($get_attrition))
        {
            $data['price_buy']=str_replace(',','',$data['price_buy']);
            $data['user_admin_id']=$staff_id;
            $data['user_admin_date']=$date;
            $this->db->where('id',$id);
            $success=$this->db->update('tblattrition',$data);
        }
        if($success) {
            echo json_encode(array(
                'success' => $success,
                'alert_type' => 'success',
                'message' => _l('Chuyển tài sản qua trạng thái bán thành công')
            ));
        }
        else
        {
            echo json_encode(array(
                'success' => $success,
                'alert_type' => 'danger',
                'message' => _l('Chuyển trạng thái không thành công')
            ));
        }
        die;
    }
    public function detail_pdf($id)
    {
        if (!$id) {
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $invoice        = $this->db->get_where('tblattrition',array('id'=>$id))->row();
        $invoice_number = $invoice->name;

        if($this->input->get('combo'))
        {
            $invoice->combo=$this->input->get('combo');
        }
        $pdf            = import_attrition_detail_pdf($invoice);

        $type           = 'D';
        if ($this->input->get('pdf') || $this->input->get('print')) {
            $type = 'I';
        }
        $pdf->Output(mb_strtoupper(slug_it($invoice_number)) . '.pdf', $type);
    }






}
