<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Notification extends AdminController
{
    function __construct()
    {
        parent::__construct();
    }


    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $array=array();
            if($this->input->get('thang'))
            {
                $array=array('is_admin'=>$this->input->get('thang'));
            }
            $this->app->get_table_data('notification_company',$array);
        }
        $data['title']='Danh sách thông báo';
        $this->load->view('admin/notification/manage', $data);
    }
    public function order_detail($id=NULL)
    {
        if($this->input->post()) {
            $data=$this->input->post();
            $data['note']=$this->input->post('note',false);
            $data['date_start']=to_sql_date($data['date_start']);
            $date =$data['date_start'];
            $number=(int)$data['number']-1;
            $type_notification=' '.$data['type_notification'];
            if($number==0)
            {
                $data['date_next'] = date("Y-m-d", strtotime("$date +$number $type_notification"));
            }
            else
            {
                $number=(int)$data['number'];
                $number_date=NULL;
                if(!empty($data['number_date']))
                {
                    $number_date=$data['number_date']-1;
                }
                $data['date_next'] = $this->get_date_next($date,$type_notification,$number,$number_date);
            }
            $date_last = $data['date_start'];
            if(!empty($data['number_date']))
            {
                $number=(int)$data['number'];
                for($i=0;$i<($data['number_date']-1);$i++)
                {
                    $date_last = date("Y-m-d", strtotime("$date_last +$number $type_notification"));
                }
                $data['date_last']=$date_last;

            }
            else
            {
                unset($data['number_date']);
                $data['date_last']=NULL;
                $data['number_date']=NULL;
            }
            if(is_numeric($id))
            {
                $this->db->where('id',$id);
                if($this->db->update('tblnotification_company',$data))
                {

                    echo json_encode(array('alert_type'=>'success','message'=>'Cập nhật thành công'));die();
                }
                echo json_encode(array('alert_type'=>'danger','message'=>'Cập nhật không thành công'));die();
            }
            else
            {
                $data['date_create']=date('Y-m-d H:i:s');
                $data['create_by']=get_staff_user_id();
                $this->db->insert('tblnotification_company',$data);
                if($this->db->insert_id())
                {
                    echo json_encode(array('alert_type'=>'success','message'=>'Thêm thành công'));die();
                }
                echo json_encode(array('alert_type'=>'danger','message'=>'Thêm không thành công'));die();
            }
        }
        echo json_encode(array('alert_type'=>'danger','message'=>'Lổi tác vụ'));die();
    }


    public function get_date_next($date=NULL,$type=NULL,$number=0,$number_date=NULL)
    {
        if(empty($number_date))
        {
            $date = date("Y-m-d", strtotime("$date +$number $type"));
            if(strtotime($date) < strtotime(date('Y-m-d')))
            {
                $date= $this->get_date_next($date,$type,$number,NULL);
            }
        }
        else
        {
            for($i=0;$i<$number_date;$i++)
            {
                $date = date("Y-m-d", strtotime("$date +$number $type"));
            }
        }
        return $date;
    }

    public function get_notification()
    {
        $id=$this->input->post('id');
        if(is_numeric($id))
        {
            $this->db->where('id',$id);
            $notification=$this->db->get('tblnotification_company')->row();
            $notification->date_start=_d($notification->date_start);
            echo json_encode($notification);die();
        }
        echo json_encode(array());die();
    }
    public function delete($id=NULL)
    {
        if(is_numeric($id))
        {
            $this->db->where('id',$id);
            if($this->db->delete('tblnotification_company'))
            {
                echo json_encode(array('alert_type'=>'success','message'=>'Xóa phiếu thành công'));die();
            }
        }
        echo json_encode(array('alert_type'=>'success','message'=>'Xóa phiếu không thành công'));die();
    }
    public function update_status()
    {
        $id=$this->input->post('id');
        $status=2;
        if(is_numeric($id))
        {
            $notification=get_table_where('tblnotification_company',array('id'=>$id),'','row');
            if(!empty($notification))
            {
                if($notification->status==2)
                {
                    $status=0;
                }
            }
            $this->db->where('id',$id);
            if($this->db->update('tblnotification_company',array('status'=>$status)))
            {
                echo json_encode(array('success'=>true,'alert_type'=>'success','message'=>'Chuyển trạng thái thành công'));die();
            }
        }
        echo json_encode(array('success'=>false,'alert_type'=>'danger','message'=>'Chuyển trạng thái thành công'));die();
    }
}


