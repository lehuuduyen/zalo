<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Notification_zalo extends AdminController
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
            $this->app->get_table_data('notification_zalo',$array);
        }
        $data['staff']=get_table_where('tblstaff',array('active'=>1));
        $data['title']='Danh sách thông báo zalo nhân viên';
        $this->load->view('admin/notification_zalo/manage', $data);
    }
    public function order_detail($id=NULL)
    {
        if($this->input->post())
        {
            $data=$this->input->post();
            $data['note']=$this->input->post('note',false);
            $_staff=$this->input->post('staff');
            $data['status']=2;
            unset($data['staff']);

            $data['date_start']=to_sql_date($data['date_start']);
            $date =$data['date_start'];
            $number=(int)$data['number']-1;
            $type_notification=' '.$data['type_notification'];

            // tính thời gian ngày bắt đầu ngày kết thúc
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
            //end ngày bắt đầu ngày kết thúc

            if(is_numeric($id))
            {
                $this->db->where('id_notification',$id);
                $this->db->delete('tblnotification_zalo_staff');
                if(empty($data['staff_all']))
                {
                    $data['staff_all']=0;
                }
                $this->db->where('id',$id);
                if($this->db->update('tblnotification_zalo',$data))
                {
                    if(empty($data['staff_all']))
                    {
                        foreach($_staff as $key=>$value)
                        {
                            if(!empty($value))
                            {
                                $this->db->insert('tblnotification_zalo_staff',array('id_notification'=>$id,'id_staff'=>$value));
                            }
                        }
                    }
                    echo json_encode(array('alert_type'=>'success','message'=>'Cập nhật thành công'));die();
                }
                echo json_encode(array('alert_type'=>'danger','message'=>'Cập nhật không thành công'));die();
            }
            else
            {
                $data['date_create']=date('Y-m-d H:i:s');
                $data['create_by']=get_staff_user_id();
                if(empty($data['staff_all'])) {
                    $data['staff_all']=0;
                }
                $this->db->insert('tblnotification_zalo',$data);
                if($this->db->insert_id())
                {
                    $id=$this->db->insert_id();
                    if(empty($data['staff_all'])) {
                        foreach ($_staff as $key => $value) {
                            if (!empty($value)) {
                                $this->db->insert('tblnotification_zalo_staff', array('id_notification' => $id, 'id_staff' => $value));
                            }
                        }
                    }
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
            $notification=$this->db->get('tblnotification_zalo')->row();
            $notification->date_start=_d($notification->date_start);
            $notification->date_create=_d($notification->date_create);
            $notification->staff=[];
            $item_staff=get_table_where('tblnotification_zalo_staff',array('id_notification'=>$id));
            foreach($item_staff as $key=>$value)
            {
                $notification->staff[]=$value['id_staff'];
            }
            echo json_encode($notification);die();
        }
        echo json_encode(array());die();
    }
    public function delete($id=NULL)
    {
        if(is_numeric($id))
        {
            $this->db->where('id',$id);
            if($this->db->delete('tblnotification_zalo'))
            {
                $this->db->where('id_notification',$id);
                $this->db->delete('tblnotification_zalo_staff');
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
            $notification=get_table_where('tblnotification_zalo',array('id'=>$id),'','row');
            if(!empty($notification))
            {
                if($notification->status==2)
                {
                    $status=0;
                }
            }
            $this->db->where('id',$id);
            if($this->db->update('tblnotification_zalo',array('status'=>$status)))
            {
                echo json_encode(array('success'=>true,'alert_type'=>'success','message'=>'Chuyển trạng thái thành công'));die();
            }
        }
        echo json_encode(array('success'=>false,'alert_type'=>'danger','message'=>'Chuyển trạng thái thành công'));die();
    }


    public function read_excel()
    {

        require_once(APPPATH . 'third_party/Excel_reader/php-excel-reader/excel_reader2.php');
        require_once(APPPATH . 'third_party/Excel_reader/SpreadsheetReader.php');

        $create_by=get_staff_user_id();
        if (isset($_FILES["file_csv"])) {
            $filename = uniqid() . '_' . $_FILES["file_csv"]["name"];
            $temp_url = TEMP_FOLDER . $filename;
            if (move_uploaded_file($_FILES["file_csv"]["tmp_name"], $temp_url)) {
                try {
                    $xls_emails = new SpreadsheetReader($temp_url);
                } catch (Exception $e) {
                    die('Error loading file "' . pathinfo($temp_url, PATHINFO_BASENAME) . '": ' . $e->getMessage());
                }
                $array_colum = array();
                foreach ($xls_emails as $colum => $value) {
                    foreach ($value as $num => $rom) {
                        $array_colum[$colum][] = $rom;
                    }
                }
                $i=0;
                $string_not_add="";
                foreach ($array_colum as $key=>$row)
                {
                    $this->db->insert('tblsend_zalo',array(
                        'phone'=>trim($row[0]),
                        'content'=>$row[1]." \n".get_option('signature_zalo'),
                        'status'=>0,
                        'date'=>date('Y-m-d'),
                        'type'=>'notification_zalo_staff',
                    ));
                    if($this->db->insert_id())
                    {
                        ++$i;
                    }
                }
                echo json_encode(array('type_alert'=>'success','message'=> 'Thêm thành công '.$i.'/'.count($array_colum)));die();
            }
        }
    }

}


