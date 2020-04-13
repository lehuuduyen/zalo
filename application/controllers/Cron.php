<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cron extends App_Controller
{
    public function index($key = '')
    {
        update_option('cron_has_run_from_cli', 1);

        if (defined('APP_CRON_KEY') && (APP_CRON_KEY != $key)) {
            header('HTTP/1.0 401 Unauthorized');
            die('Passed cron job key is not correct. The cron job key should be the same like the one defined in APP_CRON_KEY constant.');
        }

        $last_cron_run                  = get_option('last_cron_run');
        $seconds = hooks()->apply_filters('cron_functions_execute_seconds', 300);

        if ($last_cron_run == '' || (time() > ($last_cron_run + $seconds))) {
            $this->load->model('cron_model');
            $this->cron_model->run();
        }
    }

    public function kt_active_tool()
    {
        $time_tool = get_option('time_turn_zalo');
        if(isset($time_tool))
        {
            if($time_tool < time())
            {
                $this->db->where('name','time_turn_zalo');
                $this->db->update('tbloptions', array('value' => 0));

                $data_string = "<soapenv:Envelope xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' xmlns:tem='http://tempuri.org/' xmlns:SendSMS='http://183.91.2.4:6543/mp/brandname' xmlns:web='http://183.91.2.4:6543/mp/brandname?wsdl'>
                                        <soapenv:Header></soapenv:Header>
                                        <soapenv:Body>
                                            <tem:SendSMS>
                                                <brandname>CTY VIETPRO</brandname>
                                                <listuser>0904262456</listuser>
                                                <type>2</type>
                                                <templateid>M0904262456</templateid>
                                                <msgcontent>Lỗi tool update Supership</msgcontent>
                                                <username>ctyvietpro</username>
                                                <password>vietpro20%18</password>
                                            </tem:SendSMS>
                                        </soapenv:Body>
                                    </soapenv:Envelope>";

                $service_url = 'http://183.91.2.4:6543/mp/brandname?wsdl';
                $curl = curl_init($service_url);
                curl_setopt($curl, CURLOPT_URL,$service_url);
                curl_setopt($curl, CURLOPT_VERBOSE, 1);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                curl_setopt($curl, CURLOPT_POSTFIELDS,$data_string);
                $curl_response = curl_exec($curl);
                curl_close($curl);
                var_dump($curl_response);
                return true;
            }
        }
    }

    public function addtime_tool()
    {
        $this->db->where('name', 'time_turn_zalo');
        $this->db->update('tbloptions', ['value' => strtotime("+40 minutes")]);
    }



    public function GetNotification_zalo()
    {
        $this->db->where('status', 2);
        $this->db->where('(date_last >= "'. date('Y-m-d') .'" or date_last is null)');
        $this->db->where('date_next <= ', date('Y-m-d'));
        $this->db->where('date_start <= ', date('Y-m-d'));
        $notification_zalo = $this->db->get('tblnotification_zalo')->result_array();

        $arrayReturn = [];



        foreach($notification_zalo as $key => $value)
        {
            if(empty($value['staff_all']))
            {
                $this->db->select('tblstaff.zalo');
                $this->db->where('id_notification', $value['id']);
                $this->db->where('tblstaff.active', 1);
                $this->db->join('tblstaff', 'tblstaff.staffid = tblnotification_zalo_staff.id_staff');
                $id_staff = $this->db->get('tblnotification_zalo_staff')->result_array();
            }
            else
            {
                $id_staff = get_table_where('tblstaff', ['active' => 1]);
            }

            foreach($id_staff as $k => $v)
            {
                $staff_zalo = explode(',', $v['zalo']);
                foreach($staff_zalo as $kkk => $vvv)
                {
                    if(!empty($vvv))
                    {
                        $arrayReturn[] =  array(
                            'phone'=>trim($vvv),
                            'content'=>$value['note']." \n"."Tin nhắn được gửi tự động từ hệ thống của SUPERSHIP HẢI DƯƠNG"." \n"."Nếu có sai sót vui lòng liên hệ 0854.854999 / 0355.025.465"." \n"."Trân Trọng",
                            'status'=>0,
                            'date'=>date('Y-m-d'),
                            'type'=>'notification_zalo_staff',
                        );
                    }
                }
            }

            if(!empty($value['phone_add']))
            {
                $phone_add = explode(',',$value['phone_add']);
                foreach($phone_add as $k => $v)
                {
                    $arrayReturn[] =  array(
                        'phone' => trim($v),
                        'content' => $value['note']." \n"."Tin nhắn được gửi tự động từ hệ thống của SUPERSHIP HẢI DƯƠNG"." \n"."Nếu có sai sót vui lòng liên hệ 0854.854999 / 0355.025.465"." \n"."Trân Trọng",
                        'status' => 0,
                        'date' => date('Y-m-d'),
                        'type' => 'notification_zalo_staff',
                    );
                }
            }


            $date_next = $value['date_next'];
            $number = (int)$value['number'];
            $type_notification = ' '.$value['type_notification'];
            $date_next = date("Y-m-d", strtotime("$date_next +$number $type_notification"));

            $array = array('date_next' => $date_next);
            if(!empty($value['date_last']) && strtotime($date_next) >= strtotime($value['date_last'])) {

                $array['status'] = 2;
            }
            else
            {
                $array['status'] = 0;
            }
            $this->db->where('id', $value['id']);
            $this->db->update('tblnotification_zalo', $array);
        }
        echo json_encode($arrayReturn);die();
    }


    function gen_barcode($product_code = NULL, $bcs = 'code128', $height = 30, $text = 1)
    {
        ob_end_clean();
        $drawText = ($text != 1) ? FALSE : TRUE;
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $barcodeOptions = array('text' => $product_code, 'barHeight' => $height, 'drawText' => $drawText);
        $rendererOptions = array('horizontalPosition' => 'center', 'verticalPosition' => 'middle');
        Zend_Barcode::render('code128', 'image', $barcodeOptions, $rendererOptions);
    }
}
