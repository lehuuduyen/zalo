<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messager extends AdminController
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('messager_model');
        $this->load->model('products_model');
        $this->load->model('leads_model');
	    $this->load->model('orders_model');

    }

    public function index()
    {
        if(!empty($_COOKIE['access_token_page_active']))
        {
            $data['title']  =   'Quản lý tin nhắn Fanpage Facebook';

            $VersionAppFB = get_option('VersionAppFB');
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://graph.facebook.com/".$VersionAppFB."/".$_COOKIE['page_active']."/conversations?access_token=".$_COOKIE['access_token_page_active'].'&fields=updated_time,senders&limit=500&suppress_http_code=1',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Accept: */*",
                    "Cache-Control: no-cache",
                    "Connection: keep-alive",
                    "Content-Type: application/json"
                ),
            ));

            $response = curl_exec($curl);
            $data_messager = curl_error($curl);

            curl_close($curl);
            $kt_data = json_decode($response);
            if(empty($kt_data->error->code))
            {
                $this->db->where('active', 1);
                $data['staff'] = $this->db->get('tblstaff')->result_array();


                $data['list_data'] = $response;
	            $this->db->where('id_parent is null');
	            $quick_reply = $this->db->get('tblquick_reply')->result_array();
	            $arrayOption = [];
	            foreach($quick_reply as $key => $value)
	            {
		            $arrayOption[] = $value;
		            getChildQuick_reply($value['id'], $arrayOption, $icon = '➪');
	            }
	            $data['quick_reply'] = $arrayOption;

                $this->load->view('messagers/manage/manage', $data);
            }
            else
            {
                redirect('admin/messager/login');
            }
        }
        else
        {
            redirect('admin/messager/login');
        }
    }

    public function login()
    {
        $data['title']  =   'Đăng nhập FB';
        $this->load->view('messagers/login', $data);
    }
    /*
     * LẤY NỘI DUNG TIN NHẮN CUỘC TRÒ CHUYỆN
     */
    public function getJson_message()
    {
        $id = $this->input->get('id');
        if(!empty($id))
        {
            if(!empty($_COOKIE['access_token_page_active'])) {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://graph.facebook.com/v3.1/" . $id . "/messages?access_token=" . $_COOKIE['access_token_page_active'] . "&fields=message,from,to,created_time,tags,attachments&limit=16&pretty=0&suppress_http_code=1",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "Accept: */*",
                        "Cache-Control: no-cache",
                        "Connection: keep-alive",
                        "Content-Type: application/json"
                    ),
                ));

                $response = curl_exec($curl);
                $data_messager = curl_error($curl);
                curl_close($curl);
                $data['message'] = $response;
                $data['chat_area'] = true;
                $data['id_chat'] = $id;
                $this->load->view('messagers/manage/content_mid', $data);
            }
            else
            {
                redirect('messager/login');
            }
        }
    }
    /*
    *
    * Upload để gửi file từ PC
    */
    public function uploadfilepc()
    {
        if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '' && !empty($this->input->post('userid')))
        {
            $userid = $this->input->post('userid');
            if (!file_exists(FCPATH . 'uploads/messager')) {
                @mkdir(FCPATH . 'uploads/messager');
                fopen(FCPATH . 'uploads/messager' . '/index.html', 'w');
            }
            $path        = FCPATH . 'uploads/messager' . '/'.$userid;
            $tmpFilePath = $_FILES['file']['tmp_name'];
            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                $path_parts         = pathinfo($_FILES["file"]["name"]);
                $extension          = $path_parts['extension'];
                $extension = strtolower($extension);
                $allowed_extensions = array(
                    'jpg',
                    'jpeg',
                    'png'
                );
                if (!in_array($extension, $allowed_extensions)) {
                    echo json_encode(array('success' => false));die();
                }
                if (!file_exists($path)) {
                    mkdir($path);
                    fopen($path . '/index.html', 'w');
                }
                $_FILES["file"]["name"] = time().'_'.$_FILES["file"]["name"];
                $filename    = unique_filename($path, $_FILES["file"]["name"]);
                $newFilePath = $path . '/' . $filename;
                if(move_uploaded_file($tmpFilePath, $newFilePath))
                {
                    echo json_encode([
                        'success' => true,
                        'name' => $filename,
                        'url' => base_url('uploads/messager/'.$userid.'/'.$filename),
                        'newfile' => $newFilePath
                        ]);die();
                }
            }
        }
        echo json_encode(array('success' => false));die();
    }
    /*
    *
    *   Xóa file khi gửi xong tin nhắn
    *
    */
    public function deleteFile()
    {
        if(!empty($this->input->post('url')))
        {
            unlink($this->input->post('url'));
        }
    }

    public function get_lead_to_facebook()
    {
        $id_facebook = $this->input->post('id_facebook');
        if(!empty($id_facebook))
        {
            $this->addlast_facebook($id_facebook, 1);

            $this->db->where('id_facebook', $id_facebook);
            $client = $this->db->get('tblclients')->row();
            if(!empty($client))
            {
                $tag_manuals = GetTagMunualsFacebook($id_facebook);
                if(!empty($client->client_image))
                {
                    $client->img = base_url('download/preview_image?path=uploads/clients/'.$client->userid.'/thumb_'.$client->client_image);
                }

                $Advisory = $this->GetAdvisoryLead($client->userid, 'client', ['id_object' => $client->leadid, 'type_object' => 'lead']);

                $client->info_group = $this->clients_model->getInfoGroup($client->userid);
                $care_of = $this->GetAdvisoryClient($client->userid);

                //Lấy dơn hàng
                $orders = $this->orders_model->getClientOrderOr($client->userid, ['id_object_draft' => $client->userid, 'type_object_draft' => 'client']);
                echo json_encode([
                    'type_data' => 'KH',
                    'two_type' => 'client',
                    'one_type' => $client->userid,

                    'data' => $this->load->view('messagers/manage/right/tab_content_customer', ['data' => $client], true),

                    'care_of' => $this->load->view('messagers/manage/right/care_of_client', ['data' => (!empty($care_of) ? $care_of : [])], true),

                    'advisory' => $this->load->view('messagers/manage/right/advisory', ['data' => (!empty($Advisory) ? $Advisory : [])], true),

                    'orders' => $this->load->view('messagers/manage/right/tab_orders_client', ['data' => (!empty($orders) ? $orders : [])], true),

                    'countOrder' => (!empty($orders) ? count($orders) : 0),

                    'countAdvisory' => (!empty($Advisory) ? count($Advisory) : 0),

                    'countCareof' => (!empty($care_of) ? count($care_of) : 0),

                    'tag_manuals' => (!empty($tag_manuals) ? ($tag_manuals) : [])

                ]);die();
            }
            else if(empty($client))
            {
                $this->db->where('id_facebook', $id_facebook);
                $lead = $this->db->get('tblleads')->row();
                if(!empty($lead->lead_image))
                {
                    $lead->img = base_url('download/preview_image?path=uploads/leads/'.$lead->id.'/thumb_'.$lead->lead_image);
                }

                if(!empty($lead))
                {
                    $tag_manuals = GetTagMunualsFacebook($id_facebook);
                    $lead->info_group = $this->leads_model->getInfoGroupLead($lead->id);
                    $Advisory = $this->GetAdvisoryLead($lead->id, 'lead');
	                $orders = $this->orders_model->getClientOrderOr('', ['id_object_draft' => $lead->id, 'type_object_draft' => 'lead']);
                    echo json_encode([
                        'type_data' => 'KHTN',

                        'two_type' => 'lead',

                        'one_type' => $lead->id,

                        'data' => $this->load->view('messagers/manage/right/tab_content_lead', ['data' => $lead], true),

                        'care_of' => $this->load->view('messagers/manage/right/care_of_client', ['data' =>  []], true),

                        'advisory' => $this->load->view('messagers/manage/right/advisory', ['data' => (!empty($Advisory) ? $Advisory : [])], true),

                        'orders' => $this->load->view('messagers/manage/right/tab_orders_client', ['data' => (!empty($orders) ? $orders : [])], true),

                        'countOrder' => 0,

                        'countCareof' => 0,

                        'countAdvisory' => (!empty($Advisory) ? count($Advisory) : 0),

                        'tag_manuals' => (!empty($tag_manuals) ? ($tag_manuals) : [])

                    ]);die();
                }
                else
                {
                    $this->db->where('id_facebook', $id_facebook);
                    $data = $this->db->get('tbllist_fb')->row();

                    if(empty($data))
                    {
                        $arrayAdd = ['id_facebook' => $id_facebook];
                        $name = $this->input->post('name');
                        if(!empty($name))
                        {
	                        $arrayAdd['name_facebook'] = trim($name);
                        }
                        $arrayAdd['code_type'] = 'NEW';


                        $arrayAdd['create_by'] = get_staff_user_id();
                        $arrayAdd['date_create'] = date('Y-m-d H:i:s');

                        $this->db->insert('tbllist_fb', $arrayAdd);
                        if($this->db->insert_id())
                        {
                            $id = $this->db->insert_id();
                            CreateCode('listfb', $id);
                            radomStaffAssigned($id);
	                        createCodeNameSystem('listfb', $id);
                            $paste_img = FCPATH . 'uploads/avatarFB' . '/' . $id . '/';
                            _maybe_create_upload_path($paste_img);

                            $image_small = 'https://graph.facebook.com/' . $id_facebook . '/picture?height=24&width=32&access_token=' . $_COOKIE['access_token_page_active'];
                            $image_thumb = 'https://graph.facebook.com/' . $id_facebook . '/picture?height=240&width=320&access_token=' . $_COOKIE['access_token_page_active'];
                            $time = time();
                            @copy($image_small, $paste_img . 'small_' . $time . '.jpg');
                            @copy($image_thumb, $paste_img . 'thumb_' . $time . '.jpg');
                            $avatar = $time . '.jpg';

                            $arrayUpdate = [
                                'avatar' => $avatar,
                            ];
                            $this->db->where('id', $id);
                            $this->db->update('tbllist_fb', $arrayUpdate);

                            $this->db->where('id', $id);
                            $data = $this->db->get('tbllist_fb')->row();
                            if(!empty($data->avatar))
                            {
                                $paste_img = base_url('download/preview_image?path=uploads/avatarFB/'.$data->id.'/thumb_'.$data->avatar);
                                $data->img = $paste_img;
                            }
                            echo json_encode([
                                'two_type' => 'listfb',
                                'one_type' => $id,
                                'data' => $this->load->view('messagers/manage/right/tab_addFB', [
                                'data' => $data
                            ], true)]);die();
                        }
                    }
                    else
                    {
                        if(!empty($data->avatar))
                        {
                            $paste_img = base_url('download/preview_image?path=uploads/avatarFB/'.$data->id.'/thumb_'.$data->avatar);
                            $data->img = $paste_img;
                        }
                        echo json_encode([
                            'two_type' => 'listfb',
                            'one_type' => $data->id,
                            'data' => $this->load->view('messagers/manage/right/tab_addFB', [
                            'data' => $data
                        ], true)]);die();
                    }
                }
            }

        }
        echo json_encode(['data' => '']);die();
    }

    public function detail_Listfb()
    {
        if($this->input->post()) {

            $data = $this->input->post();
            $id = $data['id'];
            unset($data['id']);
            $this->db->where('id', $id);
            $listfb = $this->db->get('tbllist_fb')->row();
            if(!empty($listfb))
            {
                $this->db->where('id_facebook', $listfb->id_facebook);
                $client  = $this->db->get('tblclients')->row();
                if(!empty($client))
                {
                    echo json_encode([
                        'message' => _l('cong_list_fb_hasing_client'),
                        'alert_type' => 'success',
                        'success' => true
                    ]);die();
                }
                else
                {
                    $this->db->where('id_facebook', $listfb->id_facebook);
                    $lead  = $this->db->get('tblleads')->row();
                    if(!empty($lead))
                    {
                        echo json_encode([
                            'message' => _l('cong_list_fb_hasing_lead'),
                            'alert_type' => 'success',
                            'success' => true
                        ]);die();
                    }
                }
            }

			if(!empty($data['birtday']))
			{
				$data['birtday'] = to_sql_date($data['birtday'], true);
			}
            $this->db->where('id', $id);
           if( $this->db->update('tbllist_fb', $data))
           {
	           createCodeNameSystem('listfb', $id);
                if(!$this->WarLead($id))
                {
                   echo json_encode([
                       'message' => _l('cong_war_lead_true'),
                       'alert_type' => 'success',
                       'success' => true
                   ]);die();
                }
                echo json_encode([
                    'message' => _l('cong_update_true'),
                    'alert_type' => 'success',
                    'success' => true
                ]);
                die();
           }
        }
        echo json_encode([
            'message' => _l('cong_add_false'),
            'alert_type' => 'danger',
            'success' => false
        ]);die();
    }

    public function detail_lead()
    {
        if($this->input->post()) {

            $data = $this->input->post();
            if(!empty($data['id']))
            {
                if (isset($data['zcode'])) {
                    $data['code_type'] = 'TN';
                    $data['zcode'] = $data['zcode'];
                }
                if(isset($data['birtday']))
                {
                    $data['birtday'] = to_sql_date($data['birtday'], true);
                }
                $id = $data['id'];
                unset($data['id']);

                if(!empty($data['info_detail']))
                {
                    $info_detail = $data['info_detail'];
                }
                unset($data['info_detail']);

                if(!empty($data))
                {
                    foreach($data as $key => $value)
                    {
                        if(!is_array($value))
                        {
                            $data[$key] = trim($value);
                        }
                    }

                    $this->db->where('id', $id);
                    $success = $this->db->update('tblleads', $data);
                    if($success)
                    {
                        createCodeNameSystem('lead', $id);
                    }
                }

                //$thêm các trường động từ bảng tblclient_info_group

                if(!empty($info_detail))
                {
                    $list_ValueNotDelete = [];
                    foreach($info_detail as $key => $value)
                    {
                        if(is_array($value))
                        {
                            foreach($value as $k => $v)
                            {
                                if(!empty($v))
                                {

                                    $array_group = [
                                        'lead' => $id,
                                        'id_detail' => $key,
                                        'value' => $v
                                    ];
                                    $this->db->where($array_group);
                                    $GetValue = $this->db->get('tbllead_value')->row();
                                    if(!empty($GetValue))
                                    {
                                        $list_ValueNotDelete[] = $GetValue->id;
                                    }
                                    else
                                    {
                                        $this->db->insert('tbllead_value', $array_group);
                                        $id_value = $this->db->insert_id();
                                        if(!empty($id_value))
                                        {
                                            $list_ValueNotDelete[] = $id_value;
                                        }
                                    }
                                }
                            }
                        }
                        else
                        {
                            if(!empty($value))
                            {
                                if(empty($value['date']) || empty($value['datetime']))
                                {
                                    $array_group = [
                                        'lead' => $id,
                                        'id_detail' => $key,
                                        'value' => $value
                                    ];
                                }
                                else if(!empty($value['date']))
                                {
                                    $array_group = [
                                        'lead' => $id,
                                        'id_detail' => $key,
                                        'value' => to_sql_date($value['date'])
                                    ];
                                }
                                else if(!empty($value['datetime']))
                                {
                                    $array_group = [
                                        'lead' => $id,
                                        'id_detail' => $key,
                                        'value' => to_sql_date($value['date'], true)
                                    ];
                                }
                                $this->db->where($array_group);
                                $GetValue = $this->db->get('tbllead_value')->row();
                                if(!empty($GetValue))
                                {
                                    $list_ValueNotDelete[] = $GetValue->id;
                                }
                                else
                                {
                                    $this->db->insert(db_prefix().'lead_value', $array_group);
                                    $id_value = $this->db->insert_id();
                                    if(!empty($id_value))
                                    {
                                        $list_ValueNotDelete[] = $id_value;
                                    }
                                }

                            }
                        }
                        $this->db->where('lead', $id);
                        $this->db->where('id_detail', $key);
                        $this->db->where_not_in('id', $list_ValueNotDelete);
                        $this->db->delete(db_prefix().'lead_value');
                    }
                }
                //end

                echo json_encode([
                    'message' => _l('cong_update_true'),
                    'alert_type' => 'success',
                    'success' => true
                ]);
                die();
            }
        }
        echo json_encode([
            'message' => _l('cong_add_false'),
            'alert_type' => 'danger',
            'success' => false
        ]);die();
    }

    public function detail_customer()
    {
        if($this->input->post())
        {

            $data = $this->input->post();
            if(!empty($data['id']))
            {
                if (isset($data['zcode'])) {
                    $data['code_type'] = 'TN';
                    $data['zcode'] = $data['zcode'];
                }
                if(isset($data['birtday']))
                {
                    $data['birtday'] = to_sql_date($data['birtday'], true);
                }
                $userid = $data['id'];
                unset($data['id']);

                if(!empty($data['info_detail']))
                {
                    $info_detail = $data['info_detail'];
                }

                unset($data['info_detail']);

                if(!empty($data))
                {
                    foreach($data as $key => $value)
                    {
                        if(!is_array($value))
                        {
                            $data[$key] = trim($value);
                        }
                    }
                    $this->db->where('userid', $userid);
                    $success = $this->db->update('tblclients', $data);
                    if($success)
                    {
                        createCodeNameSystem('client', $userid);
                    }
                }

                //$thêm các trường động từ bảng tblclient_info_group

                if(!empty($info_detail))
                {


                    $list_ValueNotDelete = [];
                    foreach($info_detail as $key => $value)
                    {
                        if(is_array($value))
                        {
                            foreach($value as $k => $v)
                            {
                                if(!empty($v))
                                {

                                    $array_group = [
                                        'client' => $userid,
                                        'id_detail' => $key,
                                        'value' => $v
                                    ];
                                    $this->db->where($array_group);
                                    $GetValue = $this->db->get('tblclient_value')->row();
                                    if(!empty($GetValue))
                                    {
                                        $list_ValueNotDelete[] = $GetValue->id;
                                    }
                                    else
                                    {
                                        $this->db->insert('tblclient_value', $array_group);
                                        $id_value = $this->db->insert_id();
                                        if(!empty($id_value))
                                        {
                                            $list_ValueNotDelete[] = $id_value;
                                        }
                                    }
                                }
                            }
                        }
                        else
                        {
                            if(!empty($value))
                            {
                                if(empty($value['date']) || empty($value['datetime']))
                                {
                                    $array_group = [
                                        'client' => $userid,
                                        'id_detail' => $key,
                                        'value' => $value
                                    ];
                                }
                                else if(!empty($value['date']))
                                {
                                    $array_group = [
                                        'client' => $userid,
                                        'id_detail' => $key,
                                        'value' => to_sql_date($value['date'])
                                    ];
                                }
                                else if(!empty($value['datetime']))
                                {
                                    $array_group = [
                                        'client' => $userid,
                                        'id_detail' => $key,
                                        'value' => to_sql_date($value['date'], true)
                                    ];
                                }
                                $this->db->where($array_group);
                                $GetValue = $this->db->get('tblclient_value')->row();
                                if(!empty($GetValue))
                                {
                                    $list_ValueNotDelete[] = $GetValue->id;
                                }
                                else
                                {
                                    $this->db->insert('tblclient_value', $array_group);
                                    $id_value = $this->db->insert_id();
                                    if(!empty($id_value))
                                    {
                                        $list_ValueNotDelete[] = $id_value;
                                    }
                                }

                            }
                        }
                        $this->db->where('client', $userid);
                        $this->db->where('id_detail', $key);
                        if(!empty($list_ValueNotDelete))
                        {
                            $this->db->where_not_in('id', $list_ValueNotDelete);
                        }
                        $this->db->delete('tblclient_value');
                    }
                }
                //end

                echo json_encode([
                    'message' => _l('cong_update_true'),
                    'alert_type' => 'success',
                    'success' => true
                ]);
                die();
            }
        }
        echo json_encode([
            'message' => _l('cong_update_false'),
            'alert_type' => 'danger',
            'success' => false
        ]);die();
    }

    public function load_new_client()
    {
        if($this->input->post())
        {
            $data = $this->input->post();
            if(!empty($data['id_facebook']))
            {
                if($data['type'] == 'lead')
                {
                    echo json_encode([
                        'type_data' => 'KHTN',
                        'data' => $this->load->view('messagers/manage/right/tab_content_lead', ['data' => [], 'id_facebook' => $data['id_facebook']], true),
                        'advisory' => ""
                    ]);die();
                }
                else if($data['type'] == 'client')
                {
                    echo json_encode([
                        'type_data' => 'KH',
                        'data' => $this->load->view('messagers/manage/right/tab_content_customer', ['data' => [], 'id_facebook' => $data['id_facebook']], true),
                        'advisory' => ""
                    ]);die();
                }
            }
        }
    }

    // cập nhật tag cho khách hàng tiềm năng - khách hàng - listfb
    public function updateDataTag($id = "", $tag = "" , $rel_type = 'lead')
    {
        if(!empty($id))
        {
            //Xóa tag
            if($this->input->post('rel_type'))
            {
                $rel_type = $this->input->post('rel_type');
            }
            else
            {
                return false;
            }

            $this->db->where('rel_id', $id)->where('rel_type', $rel_type)->delete('tbltaggablesfb');

            if($this->input->post('tag'))
            {
                $tag = $this->input->post('tag');
            }
            foreach($tag as $key => $value)
            {
                if(!empty(trim($value)))
                {
                    $this->db->where('id', trim($value));
                    $Gettag = $this->db->get('tbltagsfb')->row();
                    if(!empty($Gettag))
                    {
                        $this->db->insert('tbltaggablesfb', [
                            'rel_type' => $rel_type,
                            'rel_id' => $id,
                            'tag_id' => $Gettag->id,
                            'tag_order' => ($key+1)
                        ]);
                    }
                }
            }
        }
    }

    public function staff_assigned_client()
    {
        if($this->input->post())
        {
            $data = $this->input->post();
            if(!empty($data['userid']))
            {
                $_data = [];
                $_data['customer_admins'] = $data['id_staff'];
                if($this->clients_model->assign_admins($_data, $data['userid']))
                {
                    createCodeNameSystem('client', $data['userid']);
                    echo json_encode(['success' => true, 'alert_type' => 'success', 'message' => _l('cong_event_assigned_true') ]);die();
                }
            }
        }
        echo json_encode(['success' => false, 'alert_type' => 'danger', 'message' => _l('cong_event_assigned_false') ]);die();
    }

    public function staff_assigned_lead()
    {
        if($this->input->post())
        {
            $data = $this->input->post();
            $lead = $data['id'];
            if(!empty($lead)) {
                $assInsert = 0;
                $array_id = [];
                if(!empty($data['id_staff'])) {
                    foreach ($data['id_staff'] as $key => $value) {
                        $this->db->where('id_lead', $lead);
                        $this->db->where('staff', $value);
                        $kt_assigned = $this->db->get('lead_assigned')->row();
                        if (empty($kt_assigned)) {
                            $data_insert = [
                                'id_lead' => $lead,
                                'staff' => $value,
                                'date_create' => !empty($create_date[$value]) ? $create_date[$value] : date('Y-m-d H:i:s'),
                                'created_by' => !empty($create_by[$value]) ? $create_by[$value] : get_staff_user_id(),
                            ];
                        } else {
                            $array_id[] = $kt_assigned->id;
                            ++$assInsert;
                            continue;
                        }
                        $this->db->insert(db_prefix() . 'lead_assigned', $data_insert);
                        if ($this->db->insert_id()) {
                            $array_id[] = $this->db->insert_id();
                            add_notification([
                                'description' => 'cong_assigned_lead',
                                'touserid' => $value,
                                'link' => 'leads/index=' . $lead
                            ]);
                            ++$assInsert;
                        }
                    }
                }

                if (!empty($array_id)) {
                    $this->db->where_not_in('id', $array_id);
                }
                $this->db->where('id_lead', $lead);
                $assigned_delete = $this->db->get(db_prefix() . 'lead_assigned')->result_array();
                foreach ($assigned_delete as $key => $value) {
                    add_notification([
                        'description' => 'cong_none_assigned_lead',
                        'touserid' => $value['staff'],
                        'link' => 'leads/index=' . $value['id_lead']
                    ]);
                }

                if (!empty($array_id)) {
                    $this->db->where_not_in('id', $array_id);
                }
                $this->db->where('id_lead', $lead);
                $this->db->delete(db_prefix() . 'lead_assigned');

                createCodeNameSystem('lead', $lead);
                if ($assInsert > 0) {
                    echo json_encode([
                        'success' => true,
                        'alert_type' => 'success',
                        'message' => _l('cong_assigned_true')
                    ]);
                    die();
                }
            }
        }
        echo json_encode([
            'success' => false,
            'alert_type' => 'danger',
            'message' => _l('cong_add_false')
        ]);die();
    }

    public function staff_assigned_listfb()
    {
        if($this->input->post())
        {
            $data = $this->input->post();
            $idList = $data['id'];
            if(!empty($idList)) {
                $assInsert = 0;
                $array_id = [];
                foreach ($data['id_staff'] as $key => $value) {
                    $this->db->where('id_listfb', $idList);
                    $this->db->where('staff', $value);
                    $kt_assigned = $this->db->get('tbllistfb_assigned')->row();
                    if (empty($kt_assigned)) {
                        $data_insert = [
                            'id_listfb' => $idList,
                            'staff' => $value,
                            'date_create' => !empty($create_date[$value]) ? $create_date[$value] : date('Y-m-d H:i:s'),
                            'created_by' => !empty($create_by[$value]) ? $create_by[$value] : get_staff_user_id(),
                        ];
                    } else {
                        $array_id[] = $kt_assigned->id;
                        ++$assInsert;
                        continue;
                    }
                    $this->db->insert('tbllistfb_assigned', $data_insert);
                    if ($this->db->insert_id()) {
                        $array_id[] = $this->db->insert_id();
                        ++$assInsert;
                    }
                }

                if (!empty($array_id)) {
                    $this->db->where_not_in('id', $array_id);
                }
                $this->db->where('id_listfb', $idList);
                $assigned_delete = $this->db->get('tbllistfb_assigned')->result_array();
                foreach ($assigned_delete as $key => $value) {
                    add_notification([
                        'description' => 'cong_none_assigned_lead',
                        'touserid' => $value['staff'],
                        'link' => 'leads/index=' . $value['id_lead']
                    ]);
                }

                if (!empty($array_id)) {
                    $this->db->where_not_in('id', $array_id);
                }
                $this->db->where('id_listfb', $idList);
                $this->db->delete('tbllistfb_assigned');
                if ($assInsert > 0) {
                    echo json_encode([
                        'success' => true,
                        'alert_type' => 'success',
                        'message' => _l('cong_add_true')
                    ]);
                    die();
                }
            }
        }
        echo json_encode([
            'success' => false,
            'alert_type' => 'danger',
            'message' => _l('cong_add_false')
        ]);die();
    }

    //Hàm chuyển đổi khách hàng tiềm năng của facebook
    public function get_convert_dataFB($id)
    {
        $this->load->model('leads_model');
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($id)) {
            ajax_access_denied();
        }
        if (is_gdpr() && get_option('gdpr_enable_consent_for_contacts') == '1') {
            $this->load->model('gdpr_model');
            $data['purposes'] = $this->gdpr_model->get_consent_purposes($id, 'lead');
        }
        $data['lead'] = $this->leads_model->get($id);

        //Công bổ sung
        $customer_default_country = get_option('customer_default_country');  // quốc gia mặc định
        $data['city'] = get_table_where(db_prefix().'province', [
            'countries' => (!empty($data['lead']->country) ? $data['lead']->country : $customer_default_country)
        ]);

        $data['district'] = get_table_where(db_prefix().'district', [
            'provinceid' => $data['lead']->city
        ]);

        $data['ward'] = get_table_where(db_prefix().'ward', ['districtid' => $data['lead']->district]);
        $data['dt'] = get_table_where(db_prefix().'combobox_client', ['type' => 'dt']);
        $data['kt'] = get_table_where(db_prefix().'combobox_client', ['type' => 'kt']);
        $data['marriage'] = get_table_where(db_prefix().'combobox_client', ['type' => 'marriage']);
        $data['religion'] = get_table_where(db_prefix().'combobox_client', ['type' => 'religion']);
        $data['info_group'] = $this->leads_model->getInfoGroupLead($id);

        $data['type_client'] = get_table_where(db_prefix().'type_client');
        $data['sources']  = $this->leads_model->get_source();

        //end
        $this->load->view('messagers/manage/modal/convert_to_customerFB', $data);
    }

    //Chuyển thành khách hàng từ listFB
    public function WarClient()
    {
        $id = $this->input->post('id');
        if(!empty($id))
        {
            $this->db->where('id', $id);
            $listFB = $this->db->get('tbllist_fb')->row();
            if(!empty($listFB))
            {
                $this->db->where('id_facebook', $listFB->id_facebook);
                $Numclients = $this->db->get('tblclients')->num_rows();
                if($Numclients == 0)
                {
                    $this->db->insert('tblclients', [
                        'id_facebook' => $listFB->id_facebook,
                        'code_type' => 'NEW',
                        'fullname' => $listFB->name,
                        'company' => $listFB->company,
                        'email_client' => $listFB->email,
                        'address' => $listFB->address,
                        'birtday' => $listFB->birtday,
                        'gender' => $listFB->gender,
                        'addedfrom' => get_staff_user_id(),
                        'datecreated' => date('Y-m-d H:i:s')
                    ]);
                    $idClient = $this->db->insert_id();
                    if(!empty($idClient))
                    {
                        CreateCode('client', $idClient);
                        $paste_img = FCPATH . 'uploads/avatarFB' . '/' . $listFB->id . '/';
                        $paste_imgClient = get_upload_path_by_type('customer') . $idClient . '/';
                        _maybe_create_upload_path($paste_imgClient);

                        $image_small = $paste_img.'small_'.$listFB->avatar;
                        $image_thumb = $paste_img.'thumb_'.$listFB->avatar;
                        $time = time();
                        @copy($image_small, $paste_imgClient . 'small_' . $time . '.jpg');
                        @copy($image_thumb, $paste_imgClient . 'thumb_' . $time . '.jpg');
                        $avatar = $time . '.jpg';


                        $this->db->where('userid', $idClient);
                        $this->db->update('tblclients', [
                            'client_image' => $avatar
                        ]);
                        ChangeObjectAssigned('listfb', $listFB->id, 'client', $idClient);
                        createCodeNameSystem('client', $idClient);
                        echo json_encode([
                            'alert_type' => 'success',
                            'success' => true,
                            'id_facebook' => $listFB->id_facebook,
                            'message' => _l('cong_war_client_true')
                        ]);die();
                    }
                }
                echo json_encode([
                    'alert_type' => 'danger',
                    'success' => true,
                    'id_facebook' => $listFB->id_facebook,
                    'message' => _l('cong_war_isset_client')
                ]);die();
            }
        }
        echo json_encode([
            'alert_type' => 'danger',
            'success' => false,
            'message' => _l('cong_war_client_false')
        ]);die();
    }

    //Chuyển thành khách hàng tiềm năng từ listFB
    public function WarLead($id = '')
    {
        if(!empty($id))
        {
            $success = $this->messager_model->WarLead($id);
            return $success;
        }
        return false;
    }

    public function GetAdvisoryClient($client = "")
    {
        if(!empty($client))
        {
        	$this->db->select('tblcare_of_clients.*, concat(COALESCE(tblorders.prefix),COALESCE(tblorders.code)) as code_order');
            $this->db->where('tblcare_of_clients.client', $client);
            $this->db->where('tblcare_of_clients.status_break != 1');
            $this->db->join('tblorders', 'tblorders.id = tblcare_of_clients.id_orders', 'left');
            $care_of_clients = $this->db->get('tblcare_of_clients')->result();
			if(!empty($care_of_clients))
			{
				foreach($care_of_clients as $kCare => $vCare)
				{
					$this->db->select('group_concat(concat(COALESCE(tblcare_of_client_items.type_items), "_", COALESCE(tblcare_of_client_items.id_product))) as list_product');
					$this->db->where('id_care_of', $vCare->id);
					$list_care_of_client = $this->db->get('tblcare_of_client_items')->row();

					$this->db->select('concat(COALESCE(tblorders_items.type_items), "_", COALESCE(tblorders_items.id_product)) as id, tblorders_items.name_product as name');
					if(!empty($list_care_of_client->list_product))
					{
						$this->db->where_not_in('concat(COALESCE(tblorders_items.type_items), "_", COALESCE(tblorders_items.id_product))', explode(',', $list_care_of_client->list_product));
					}
					$this->db->where('id_orders', $vCare->id_orders);
					$care_of_clients[$kCare]->order_items = $this->db->get('tblorders_items')->result_array();

					$this->db->where('id_care_of', $vCare->id);
					$this->db->order_by('orders', 'asc');
					$care_of_clients[$kCare]->detail  = $this->db->get('tblprocedure_care_of')->result();
				}
			}
            return $care_of_clients;
        }
        return false;
    }

    public function GetAdvisoryLead($id_object = "", $type_object ='', $where_or = [])
    {
            $this->db->group_start();
            $this->db->or_group_start();
            $this->db->where('id_object', $id_object);
            $this->db->where('type_object', $type_object);
            $this->db->group_end();
            if(!empty($where_or))
            {
                $this->db->or_group_start();
                foreach($where_or as $key => $value)
                {
                    $this->db->where($key, $value);
                }
                $this->db->group_end();
            }
            $this->db->group_end();
            $this->db->where('status_break', 0);
            $this->db->order_by('date', 'desc');
            $advisory_lead = $this->db->get('tbladvisory_lead')->result();
            if(!empty($advisory_lead))
            {
                foreach($advisory_lead as $kAdvisory => $vAdvisoty)
                {
                    $this->db->where('id_advisory', $vAdvisoty->id);
                    $this->db->order_by('orders_status', 'asc');
                    $advisory_lead[$kAdvisory]->detail  = $this->db->get('tblprocedure_advisory_lead')->result();

                    $advisory_lead[$kAdvisory]->experience = get_table_where('tblexperience_advisory', [], 'id desc', 'result');
                    foreach($advisory_lead[$kAdvisory]->experience as $key => $value)
                    {
                        $this->db->select('group_concat(name separator "</br>") as listname, group_concat(id_experience_detail) as listid');
                        $this->db->where('id_advisory', $vAdvisoty->id);
                        $this->db->where('id_experience', $value->id);
                        $advisory_lead[$kAdvisory]->experience[$key]->detail = $this->db->get('tbladvisory_detail_experience')->row();
                    }

                }
                return $advisory_lead;
            }

        return false;
    }

    //modal tạo đơn hàng
    public function ViewCreateOrder(){
        $id = $this->input->post('id');
        $type = $this->input->post('type');
        if($type == 'lead')
        {
            $data['type'] = $type;
            $data['id'] = $id;
            $lead = get_table_where('tblleads', ['id' => $id], '', 'row');
            if(!empty($lead))
            {
                $data['object'] = $lead;
                $message_err = "";
                if(empty($lead->zcode)) {
                    $message_err .= ', '._l('cong_t_zcode');
                }
                if(!empty($message_err))
                {
                    echo json_encode([
                            'success' => false,
                            'alert_type' => 'danger',
                            'message' => (_l('cong_pls_input').' '.trim($message_err, ', '))
                        ]);die();
                }
                else
                {
                    echo json_encode([
                        'data' => $this->load->view('messagers/manage/modal/orders', $data, true),
                        'success' => true
                    ]);die();
                }

                $this->db->select('tbladvisory_lead.id, concat(COALESCE(prefix), COALESCE(code)) as full_code, tbladvisory_lead.type_code');
                $this->db->where('type_object', $type);
                $this->db->where('id_object', $id);
                $this->db->join('tblprocedure_advisory_lead', 'tblprocedure_advisory_lead.id_advisory = tbladvisory_lead.id and status_procedure = 6 and active = 1');
                $this->db->where('status_break != ' , 1);
                $data['advisory'] = $this->db->get('tbladvisory_lead')->result_array();



            }
        }
        else if($type == 'listfb')
        {
            $data['type'] = $type;
            $data['id'] = $id;
            $list_fb = get_table_where('tbllist_fb', ['id' => $id], '', 'row');
            if(!empty($list_fb))
            {
                $data['object'] = $list_fb;
                $message_err = "";
                if(empty($list_fb->zcode)) {
                    $message_err .= ', '._l('cong_t_zcode');
                }
                if(!empty($message_err))
                {
                    echo json_encode([
                            'success' => false,
                            'alert_type' => 'danger',
                            'message' => (_l('cong_pls_input').' '.trim($message_err, ', '))
                        ]);die();
                }
                else
                {
                    echo json_encode([
                        'data' => $this->load->view('messagers/manage/modal/orders', $data, true),
                        'success' => true
                    ]);die();
                }
            }
        }
        else if($type == 'client')
        {
            $data['type'] = $type;
            $data['id'] = $id;
            $client = get_table_where('tblclients', ['userid' => $id], '', 'row');
            if(!empty($client))
            {
                $data['object'] = $client;
                $message_err = "";
                if(empty($client->zcode)) {
                    $message_err .= ', '._l('cong_t_zcode');
                }
                if(!empty($message_err))
                {
                    echo json_encode([
                            'success' => false,
                            'alert_type' => 'danger',
                            'message' => (_l('cong_pls_input').' '.trim($message_err, ', '))
                        ]);die();
                }
                else
                {
                    $data['shipping'] = get_table_where('tblshipping_client', ['client' => $data['id']]);

                    $this->db->select('tbladvisory_lead.id, concat(COALESCE(prefix), COALESCE(code)) as full_code, tbladvisory_lead.type_code');
                    $this->db->group_start();
                        $this->db->group_start();
	                    $this->db->where('type_object', 'client');
	                    $this->db->where('id_object', $id);
	                    $this->db->group_end();
		                if(!empty($client->leadid))
		                {
			                $this->db->or_group_start();
			                $this->db->or_where('id_object', $client->leadid);
			                $this->db->or_where('type_object', 'lead');
			                $this->db->group_end();
		                }
                    $this->db->group_end();

                    $this->db->join('tblprocedure_advisory_lead', 'tblprocedure_advisory_lead.id_advisory = tbladvisory_lead.id and status_procedure = 6 and active = 1');
                    $this->db->where('status_break !=' , 1);
                    $data['advisory'] = $this->db->get('tbladvisory_lead')->result_array();

                    echo json_encode([
                        'data' => $this->load->view('messagers/manage/modal/orders', $data, true),
                        'success' => true
                    ]);die();
                }
            }
        }
    }

    //Tạo đơn hàng
    public function create_orders()
    {
        if($this->input->post()) {
            $data = $this->input->post();
            if(empty($data['assigned']))
            {
	            $data['assigned'] = get_staff_user_id();
            }
            $id = $data['id'];
            unset($data['id']);
            $type = $data['type'];
            unset($data['type']);
            if($type == 'lead')
            {
                $shipping = $data['shipping'];
                unset($data['shipping']);
                $this->db->where('id', $id);
                $lead = $this->db->get('tblleads')->row();
                if(!empty($lead)) {
                    $this->db->where('leadid', $lead->id);
                    $ktClient = $this->db->get('tblclients')->row();
                    if(empty($ktClient))
                    {
                        $first_date = strtotime(date('Y-m-d'));
                        $second_date = strtotime($lead->date_contact);
                        $datediff = abs($first_date - $second_date);
                        $leadtime =  floor($datediff / (60*60*24));

                        $arrayAdd = [
                            'email_client' => $lead->email,
                            'birtday' => $lead->birtday,
                            'note' => $lead->description,
                            'code_system' => $lead->code_system,
                            'company' => $lead->company,
                            'fullname' => $lead->name,
                            'phonenumber' => $lead->phonenumber,
                            'id_facebook' => $lead->id_facebook,
                            'leadid' => $lead->id,
                            'zcode' => $lead->zcode,
                            'datecreated' => date('Y-m-d H:i:s'),
                            'addedfrom' => get_staff_user_id(),
                            'dt' => $lead->dt,
                            'kt' => $lead->kt,
                            'religion' => $lead->religion,
                            'marriage' => $lead->marriage,
                            'city' => $lead->city,
                            'district' => $lead->district,
                            'ward' => $lead->ward,
                            'date_contact' => $lead->date_contact,
                            'name_facebook' => $lead->name_facebook,
                            'link_facebook' => $lead->link_facebook,
                            'leadtime' => $leadtime
                        ];
                        $this->db->insert('tblclients', $arrayAdd);
                        if($this->db->insert_id())
                        {
                            $idClient = $this->db->insert_id();
                            CreateCode('client', $idClient);
                        }
                        $this->db->where('lead', $lead->id);
                        $lead->info_group = $this->db->get('tbllead_value')->result_array();
                        if(!empty($lead->info_group))
                        {
                            foreach($lead->info_group as $kInfo => $vInfo)
                            {
                                $arrayInfo = [
                                    'id_detail' => $vInfo['id_detail'],
                                    'value' => $vInfo['value'],
                                    'client' => $idClient,
                                ];
                                $this->db->insert('tblclient_value', $arrayInfo);
                            }
                        }

                        $img_lead =  get_upload_path_by_type('lead') . $lead->id . '/';
                        $img_client = get_upload_path_by_type('customer') . $idClient . '/';
                        _maybe_create_upload_path($img_client);
                        @copy($img_lead.'small_'.$lead->lead_image, $img_client.'small_'.$lead->lead_image);
                        @copy($img_lead.'thumb_'.$lead->lead_image, $img_client.'thumb_'.$lead->lead_image);

                        $arrayUpdateClient = [
                            'client_image' => $lead->lead_image
                        ];

                        $this->db->where('userid', $idClient);
                        $this->db->update('tblclients', $arrayUpdateClient);

                        ChangeObjectAssigned('lead', $lead->id, 'client', $idClient);
                        createCodeNameSystem('client', $idClient);
                    }
                    else
                    {
                        $idClient = $ktClient->userid;
                    }

                    if (!empty($idClient))
                    {
                        $this->db->insert('tblshipping_client', [
                            'client' => $idClient,
                            'name' => $shipping['name'],
                            'phone' => $shipping['phone'],
                            'address' => $shipping['address'],
                            'date_create' => date('Y-m-d H:i:s'),
                            'create_by' => get_staff_user_id(),
                            'address_primary' => 1
                        ]);
                        $idShipping = $this->db->insert_id();
                        $data['client'] = $idClient;
                        $data['shipping'] = $idShipping;
                        $data['address_shipping'] = $shipping['address'];
                        $this->load->model('orders_model');
                        $success = $this->orders_model->add($data);
                        if(!empty($success))
                        {
                            $this->db->where('id', $lead->id);
                            $this->db->update('tblleads', ['status' => '1']);
                            updateTypecodeClient($idClient, 'DM');
                            echo json_encode([
                                'success' => true,
                                'alert_type' => 'success',
                                'id_facebook' => $lead->id_facebook,
                                'message' => _l('cong_add_true')
                            ]);die();
                        }
                        else
                        {
                            //Nếu thêm đơn hàng không thành công => xóa khách hàng
                            if(empty($ktClient))
                            {
                                $this->db->where('userid', $idClient);
                                $this->db->delete('tblclients');
                                $this->db->where('client', $idClient);
                                $this->db->delete('tblshipping_client');
                            }
                            echo json_encode([
                                'success' => false,
                                'alert_type' => 'danger',
                                'message' => _l('cong_add_false')
                            ]);
                        }
                    }
                }
            }
            if($type == 'listfb')
            {
                $shipping = $data['shipping'];
                unset($data['shipping']);
                $this->db->where('id', $id);
                $list_fb = $this->db->get('tbllist_fb')->row();
                if(!empty($list_fb)) {
                    $this->db->where('id_facebook', $list_fb->id_facebook);
                    $ktClient = $this->db->get('tblclients')->row();
                    if(empty($ktClient))
                    {
                        $name_staff_create = get_staff_full_name();
                        $arrayAdd = [
                            'email_client' => $list_fb->email,
                            'birtday' => $list_fb->birtday,
                            'note' => $list_fb->note,
                            'company' => $list_fb->company,
                            'fullname' => $list_fb->name,
                            'phonenumber' => $list_fb->phonenumber,
                            'id_facebook' => $list_fb->id_facebook,
                            'zcode' => $list_fb->zcode,
                            'datecreated' => date('Y-m-d H:i:s'),
                            'addedfrom' => get_staff_user_id(),
                        ];
                        //Mã loại hệ thống
                        if (!empty($arrayAdd['zcode'])) {
                            $arrayAdd['code_type'] = 'TN';
                        } else {
                            $arrayAdd['code_type'] = "NEW";
                        }
                        $this->db->insert('tblclients', $arrayAdd);
                        if($this->db->insert_id())
                        {
                            $idClient = $this->db->insert_id();
                            CreateCode('client', $idClient);

                            $paste_img = FCPATH . 'uploads/avatarFB' . '/' . $list_fb->id . '/';
                            $img_client = get_upload_path_by_type('customer') . $idClient . '/';
                            @copy($paste_img.'small_'.$list_fb->avatar, $img_client.'small_'.$list_fb->avatar);
                            @copy($paste_img.'thumb_'.$list_fb->avatar, $img_client.'thumb_'.$list_fb->avatar);
                            _maybe_create_upload_path($img_client);

                            $arrayUpdateClient = [
                                'client_image' => $list_fb->avatar
                            ];

                            $this->db->where('userid', $idClient);
                            $this->db->update('tblclients', $arrayUpdateClient);

                            ChangeObjectAssigned('listfb', $list_fb->id, 'client', $idClient);
                            createCodeNameSystem('client', $idClient);
                        }
                    }
                    else
                    {
                        $idClient = $ktClient->userid;
                    }

                    if (!empty($idClient)) {
                        $this->db->insert('tblshipping_client', [
                            'client' => $idClient,
                            'name' => $shipping['name'],
                            'phone' => $shipping['phone'],
                            'address' => $shipping['address'],
                            'date_create' => date('Y-m-d H:i:s'),
                            'create_by' => get_staff_user_id(),
                            'address_primary' => 1
                        ]);
                        $idShipping = $this->db->insert_id();
                        $data['client'] = $idClient;
                        $data['shipping'] = $idShipping;
                        $data['address_shipping'] = $shipping['address'];
                        $this->load->model('orders_model');
                        $success = $this->orders_model->add($data);
                        if(!empty($success))
                        {
                            updateTypecodeClient($idClient, 'DM');
                            echo json_encode([
                                'success' => true,
                                'alert_type' => 'success',
                                'id_facebook' => $list_fb->id_facebook,
                                'message' => _l('cong_add_true')
                            ]);die();
                        }
                        else
                        {
                            //Nếu thêm đơn hàng không thành công => xóa khách hàng
                            if(empty($ktClient))
                            {
                                $this->db->where('userid', $idClient);
                                $this->db->delete('tblclients');
                                $this->db->where('client', $idClient);
                                $this->db->delete('tblshipping_client');
                            }
                            echo json_encode([
                                'success' => false,
                                'alert_type' => 'danger',
                                'message' => _l('cong_add_false')
                            ]);
                        }
                    }
                }
            }
            if($type == 'client')
            {
                $this->db->where('userid', $id);
                $ktClient = $this->db->get('tblclients')->row();
                if(!empty($ktClient))
                {
                    $idClient = $ktClient->userid;
                }
                if (!empty($idClient)) {
                    $data['client'] = $idClient;
                    $this->load->model('orders_model');
                    $success = $this->orders_model->add($data);
                    if(!empty($success))
                    {
                        updateTypecodeClient($idClient, 'DM');
                        echo json_encode([
                            'success' => true,
                            'alert_type' => 'success',
                            'id_facebook' => $ktClient->id_facebook,
                            'message' => _l('cong_add_true')
                        ]);die();
                    }
                    else
                    {
                        //Nếu thêm đơn hàng không thành công => xóa khách hàng
                        echo json_encode([
                            'success' => false,
                            'alert_type' => 'danger',
                            'message' => _l('cong_add_false')
                        ]);
                    }
                }
            }
        }
    }

    //view modal chăm sóc khách hàng
    public function ViewAdvisory()
    {
        $id = $this->input->post('id');
        $type = $this->input->post('type');

        $this->db->where('view_modal', 1);
        $info_view_detail = $this->db->get('tblclient_info_detail')->result_array();
        if($type == 'lead')
        {
            $data['type_object'] = $type;
            $data['id_object'] = $id;
            $lead = get_table_where('tblleads', ['id' => $id], '', 'row');
            if(!empty($lead))
            {
	            $data['date_contact'] = $lead->date_contact;
                $this->db->select(db_prefix().'procedure_client_detail.*');
                $this->db->where('type' ,'lead');
                $this->db->join(db_prefix().'procedure_client', 'tblprocedure_client.id = '.db_prefix().'procedure_client_detail.id_detail');
                $this->db->order_by('orders', 'asc');
                $data['procedure_detail'] = $this->db->get('tblprocedure_client_detail')->result_array();


                foreach($info_view_detail as $kInfo => $vInfo)
                {
                    if($vInfo['type_form'] == 'select' || $vInfo['type_form'] == 'select multiple' || $vInfo['type_form'] == 'checkbox' || $vInfo['type_form'] == 'radio')
                    {
                        $this->db->select('group_concat(tblclient_info_detail_value.name) as value_name');
                        $this->db->where('lead', $lead->id);
                        $this->db->where('id_detail', $vInfo['id']);
                        $this->db->join('tblclient_info_detail_value', 'tblclient_info_detail_value.id = tbllead_value.value');
                        $info_view_detail[$kInfo]['value'] = $this->db->get('tbllead_value')->row();
                    }
                    else
                    {
                        $this->db->select('tbllead_value.value as value_name');
                        $this->db->where('lead', $lead->id);
                        $this->db->where('id_detail', $vInfo['id']);
                        $info_view_detail[$kInfo]['value'] = $this->db->get('tbllead_value')->row();
                    }
                }
                $data['info_view_detail'] = $info_view_detail;

                echo json_encode([
                        'data' => $this->load->view('admin/advisory_lead/modal', $data, true),
                        'success' => true
                ]);die();
            }
        }
        if($type == 'listfb')
        {
            $data['type'] = $type;
            $data['id'] = $id;
            $listFB = get_table_where('tbllist_fb', ['id' => $id], '', 'row');
            if(!empty($listFB))
            {
	            $data['date_contact'] = $listFB->date_create;
                $this->db->where('id_facebook', $listFB->id_facebook);
                $Numclients = $this->db->get('tblclients')->num_rows();
                if($Numclients == 0)
                {
                    $this->db->where('id_facebook', $listFB->id_facebook);
                    $NumLead = $this->db->get('tblleads')->num_rows();
                    if($NumLead == 0)
                    {
                        $this->db->select(db_prefix().'procedure_client_detail.*');
                        $this->db->where('type' ,'lead');
                        $this->db->join(db_prefix().'procedure_client', db_prefix().'procedure_client.id = '.db_prefix().'procedure_client_detail.id_detail');
                        $this->db->order_by('orders', 'asc');
                        $data['procedure_detail'] = $this->db->get(db_prefix().'procedure_client_detail')->result_array();
                        $data['date_contact'] = $listFB->date_create;
                        //Chuyển từ khách hàng inbox sang khách hàng tiềm năng bằng cách tạo phiếu chăm sóc
                        echo json_encode([
                                'data' => $this->load->view('messagers/manage/modal/advisory_listfb_convert_lead', $data, true),
                                'success' => true
                        ]);die();
                    }
                }
            }
        }
        else if($type == 'client')
        {
            $data['type_object'] = $type;
            $data['id_object'] = $id;
            $client = get_table_where('tblclients', ['userid' => $id], '', 'row');
            if(!empty($client))
            {
            	$data['date_contact'] = $client->date_contact;
                $this->db->select('tblprocedure_client_detail.*');
                $this->db->where('type' ,'lead');
                $this->db->where('tblprocedure_client_detail.check_type', NULL);
                $this->db->join('tblprocedure_client', 'tblprocedure_client.id = tblprocedure_client_detail.id_detail');
                $data['procedure_detail'] = $this->db->get('tblprocedure_client_detail')->result_array();

                foreach($info_view_detail as $kInfo => $vInfo)
                {
                    if($vInfo['type_form'] == 'select' || $vInfo['type_form'] == 'select multiple' || $vInfo['type_form'] == 'checkbox' || $vInfo['type_form'] == 'radio')
                    {
                        $this->db->select('group_concat(tblclient_info_detail_value.name) as value_name');
                        $this->db->where('client', $client->userid);
                        $this->db->where('id_detail', $vInfo['id']);
                        $this->db->join('tblclient_info_detail_value', 'tblclient_info_detail_value.id = tblclient_value.value');
                        $info_view_detail[$kInfo]['value'] = $this->db->get('tblclient_value')->row();
                    }
                    else
                    {
                        $this->db->select('tblclient_value.value as value_name');
                        $this->db->where('client', $client->userid);
                        $this->db->where('id_detail', $vInfo['id']);
                        $info_view_detail[$kInfo]['value'] = $this->db->get('tblclient_value')->row();
                    }
                }
                $data['info_view_detail'] = $info_view_detail;

                echo json_encode([
                    'data' => $this->load->view('admin/advisory_lead/modal', $data, true),
                    'success' => true
                ]);die();
            }
        }

        //hoàng crm bổ xung modal xem chi tiết khách hàng
        else if($type == 'profile')
        {
            if(!empty($id))
            {
                $this->db->select('tblclients.*, tbltype_client.name as nameType_client, tblcombobox_client.name as nameMarriage, tblcountries.short_name as short_name_countries, tblprovince.name as name_province, tbldistrict.name as name_district, tblward.name as name_ward, tblleads_sources.name as name_sources, tblcurrencies.name as name_currencies');
                $this->db->from('tblclients');
                $this->db->join('tbltype_client','tbltype_client.id = tblclients.type_client','left');
                $this->db->join('tblcombobox_client','tblcombobox_client.id = tblclients.marriage','left');
                $this->db->join('tblcountries','tblcountries.country_id = tblclients.country','left');
                $this->db->join('tblprovince','tblprovince.provinceid = tblclients.city','left');
                $this->db->join('tbldistrict','tbldistrict.districtid = tblclients.district','left');
                $this->db->join('tblward','tblward.wardid = tblclients.ward','left');
                $this->db->join('tblleads_sources','tblleads_sources.id = tblclients.sources','left');
                $this->db->join('tblcurrencies','tblcurrencies.id = tblclients.default_currency','left');
                $this->db->where('userid',$id);
                $data['dataView'] = $this->db->get()->row();

                $this->db->select('tblcustomer_groups.*, tblcustomers_groups.name as name_groups');
                $this->db->from('tblcustomer_groups');
                $this->db->join('tblcustomers_groups','tblcustomers_groups.id = tblcustomer_groups.groupid','left');
                $this->db->where('tblcustomer_groups.customer_id',$id);
                $data['dataGroup'] = $this->db->get()->result_array();

                $this->db->select(db_prefix().'client_info_group.*');
                $info_group = $this->db->get(db_prefix().'client_info_group')->result_array();
                foreach($info_group as $key => $value)
                {
                    if(!empty($id))
                    {
                        $this->db->select(
                            db_prefix().'client_info_detail.*,
                            (SELECT group_concat(value) FROM '.db_prefix().'client_value where id_detail = '.db_prefix().'client_info_detail.id AND client = '.$id.') as value
                            '
                        );
                        $this->db->join(db_prefix().'client_value', db_prefix().'client_value.id_detail = '.db_prefix().'client_info_detail.id and client = '.$id, 'left');
                        $this->db->group_by(db_prefix().'client_info_detail.id');
                        $this->db->order_by(db_prefix().'client_info_detail.id', 'desc');
                        $this->db->where('id_info_group', $value['id']);
                        $detail_group = $this->db->get(db_prefix().'client_info_detail')->result_array();
                    }
                    else
                    {
                        $this->db->order_by(db_prefix().'client_info_detail.id', 'desc');
                        $this->db->where('id_info_group', $value['id']);
                        $detail_group = $this->db->get(db_prefix().'client_info_detail')->result_array();
                    }

                    if(!empty($detail_group))
                    {
                        foreach($detail_group as $k => $v)
                        {
                            $this->db->where('id_info_detail', $v['id']);
                            $detail_group[$k]['detail'] = $this->db->get(db_prefix().'client_info_detail_value')->result_array();
                        }
                        $info_group[$key]['detail'] = $detail_group;
                    }
                }
                $data['info_group'] = $info_group;

                echo json_encode([
                    'data' => $this->load->view('messagers/manage/modal/profile_customer', $data, true),
                    'success' => true
                ]);die();
            }
        }
        else if($type == 'profile-lead')
        {
            if(!empty($id))
            {
                $this->db->select('*,' . db_prefix() . 'leads.name, ' . db_prefix() . 'leads.id,' . db_prefix() . 'leads_status.name as status_name,' . db_prefix() . 'leads_sources.name as source_name,'.db_prefix() . 'province.name as name_city');
                $this->db->join(db_prefix() . 'leads_status', db_prefix() . 'leads_status.id=' . db_prefix() . 'leads.status', 'left');
                $this->db->join(db_prefix() . 'leads_sources', db_prefix() . 'leads_sources.id=' . db_prefix() . 'leads.source', 'left');
                $this->db->join(db_prefix() . 'province', db_prefix() . 'province.provinceid=' . db_prefix() . 'leads.city', 'left');
                if (is_numeric($id)) {
                    $this->db->where(db_prefix() . 'leads.id', $id);
                    $lead = $this->db->get(db_prefix() . 'leads')->row();

                    if ($lead) {
                        if ($lead->from_form_id != 0) {
                            $lead->form_data = $this->get_form([
                                'id' => $lead->from_form_id,
                            ]);
                        }
                        $lead->attachments = $this->get_lead_attachments($id);
                        $lead->public_url  = leads_public_url($id);
                        $lead->contacts = get_table_where(db_prefix().'contacts_lead', ['id_lead' => $id]);
                    }

                    $data['lead'] = $lead;
                }

                $this->db->select(db_prefix().'client_info_group.*');
                $info_group = $this->db->get(db_prefix().'client_info_group')->result_array();
                foreach($info_group as $key => $value)
                {
                    if(!empty($id))
                    {
                        $this->db->select(
                            db_prefix().'client_info_detail.*,
                            (SELECT group_concat(value) FROM '.db_prefix().'lead_value where id_detail = '.db_prefix().'client_info_detail.id AND lead = '.$id.') as value
                            '
                        );
                        $this->db->join(db_prefix().'lead_value', db_prefix().'lead_value.id_detail = '.db_prefix().'client_info_detail.id and lead = '.$id, 'left');
                        $this->db->group_by(db_prefix().'client_info_detail.id');
                        $this->db->order_by(db_prefix().'client_info_detail.id', 'desc');
                        $this->db->where('id_info_group', $value['id']);
                        $detail_group = $this->db->get(db_prefix().'client_info_detail')->result_array();
                    }
                    else
                    {
                        $this->db->order_by(db_prefix().'client_info_detail.id', 'desc');
                        $this->db->where('id_info_group', $value['id']);
                        $detail_group = $this->db->get(db_prefix().'client_info_detail')->result_array();
                    }

                    if(!empty($detail_group))
                    {
                        foreach($detail_group as $k => $v)
                        {
                            $this->db->where('id_info_detail', $v['id']);
                            $detail_group[$k]['detail'] = $this->db->get(db_prefix().'client_info_detail_value')->result_array();
                        }
                        $info_group[$key]['detail'] = $detail_group;
                    }
                }
                $data['info_group'] = $info_group;

                echo json_encode([
                    'data' => $this->load->view('messagers/manage/modal/profile_lead', $data, true),
                    'success' => true
                ]);die();
            }
        }
        //end
        echo json_encode([
            'success' => false,
            'alert_type' => 'danger',
            'message' => _l('cong_err')
        ]);die();
    }

    //view modal tư vấn khách hàng
    public function ViewCareOf()
    {
        $id = $this->input->post('id');
        $type = $this->input->post('type');
        if($type == 'client')
        {
            $data['id_client'] = $id;
            if(!empty($data['id_client']))
            {
                $this->db->select('tblorders.id, concat(COALESCE(tblorders.prefix),"-",COALESCE(tblorders.code)) as full_code');
                $this->db->where('client', $data['id_client']);
                $data['orders'] = $this->db->get('tblorders')->result_array();

                echo json_encode([
                    'data' => $this->load->view('admin/care_of_clients/modal', $data, true),
                    'success' => true
                ]);die();
            }
        }
        echo json_encode([
            'success' => false,
            'alert_type' => 'danger',
            'message' => _l('cong_err')
        ]);die();
    }

    public function AddAdvisory_listfb_convert_lead()
    {
        if($this->input->post())
        {
            $data = $this->input->post();
            $this->db->where('id', $data['id_listfb']);
            $listfb = $this->db->get('tbllist_fb')->row();
            if(!empty($listfb))
            {
                unset($data['id_listfb']);
                $data['lead'] = $this->WarLead($listfb->id);

                $this->db->where('id', $data['status_first']);
                $procedure_detail = $this->db->get('tblprocedure_client_detail')->row();
                if(!empty($procedure_detail))
                {
                    $this->db->order_by('orders', 'ASC');
                    $this->db->where('id_detail', $procedure_detail->id_detail);
                    $this->db->where('orders >= ', $procedure_detail->orders);
                    $advisory_procedure = $this->db->get('tblprocedure_client_detail')->result_array();
                }


                $type_code = 'NC';

                $array_add = [
                    'id_object' => $data['lead'],
                    'type_object' => 'lead',
                    'type_code' => $type_code,
                    'date' => to_sql_date($data['date']),
                    'status_first' => $data['status_first'],
                    'status_active' => 0,
                    'product_other_buy' => $data['product_other_buy'],
                    'address_other_buy' => $data['address_other_buy'],
                    'date_create' => date('Y-m-d H:i:s'),
                    'create_by' => get_staff_user_id()
                ];

                $this->db->insert('tbladvisory_lead', $array_add);
                if($this->db->insert_id())
                {
                    $id = $this->db->insert_id();
                    //Thêm Mã tư vấn cho Phiếu
                    $code   =   sprintf("%06s", $id);
                    $prefix = get_option('advisory_lead_prefix');
                    $this->db->where('id', $id);
                    $this->db->update('tbladvisory_lead', ['code' => $code, 'prefix' => $prefix]);
                    //End thêm mã tư vấn cho phiếu

                    $id = $this->db->insert_id();
                    //Thêm Mã tư vấn cho Phiếu
                    CreateCode('advisory', $id);
                    //End thêm mã tư vấn cho phiếu
                    ChangeTag_manuals('lead', $data['lead'], $id, ['advisory' => $id]);

                    $_date = to_sql_date($data['date']);
                    foreach($advisory_procedure as $key => $value)
                    {
                        $leadtime = $value['leadtime'];
                        $_date =  date("Y-m-d", strtotime("$_date +$leadtime day"));
                        $this->db->insert('tblprocedure_advisory_lead', [
                            'id_advisory' => $id,
                            'name_status' => $value['name'],
                            'orders_status' => $value['orders'],
                            'leadtime' => $value['leadtime'],
                            'status_procedure' => $value['id'],
                            'date_expected ' => $_date
                        ]);
                    }

                    echo json_encode([
                        'success' => true,
                        'alert_type' => 'success',
                        'message' => _l('cong_add_true')
                    ]);die();
                }
                else
                {
                    $this->db->where('id', $data['lead']);
                    $this->db->delete('tblleads');
                    echo json_encode([
                        'success' => false,
                        'alert_type' => 'danger',
                        'message' => _l('cong_add_false')
                    ]);die();
                }
            }
        }
        echo json_encode([
            'success' => false,
            'alert_type' => 'danger',
            'message' => _l('cong_add_false')
        ]);die();
    }

    public function get_lead_attachments($id = '', $attachment_id = '', $where = [])
    {
        $this->db->where($where);
        $idIsHash = !is_numeric($attachment_id) && strlen($attachment_id) == 32;
        if (is_numeric($attachment_id) || $idIsHash) {
            $this->db->where($idIsHash ? 'attachment_key' : 'id', $attachment_id);

            return $this->db->get(db_prefix() . 'files')->row();
        }
        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'lead');
        $this->db->order_by('dateadded', 'DESC');

        return $this->db->get(db_prefix() . 'files')->result_array();
    }

    public function SearchProductItems($code = "")
    {
        $data = [];
        $search = $this->input->get('term');
        $limit_one = 50;
        $limit_all = 100;
        $this->db->select('
            concat("products_", id) as id,
            tbl_products.name as text,
            tbl_products.name_customer as name_customer,
            tbl_products.name_supplier as name_supplier,
            tbl_products.price_sell as price,
            CONCAT("uploads/products/", "", tbl_products.images, "") as img'
        , false);
        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('tbl_products.name', $search);
            $this->db->like('tbl_products.code', $search);
            $this->db->group_end();
        }
        $this->db->order_by('tbl_products.name', 'DESC');
        $this->db->limit($limit_one);
        $product = $this->db->get('tbl_products')->result_array();
        if(!empty($product))
        {
            $data['results'][] =
                [
                    'text' => _l('cong_ts_product'),
                    'children' => $product
                ];
        }


        $count_product = count($product);
        $this->db->select('
                concat("items_", id) as id,
                tblitems.name as text,
                tblitems.name as name_customer,
                tblitems.name as name_supplier,
                tblitems.price,
                images_product as img'
        , false);
        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('tblitems.name', $search);
            $this->db->like('tblitems.code', $search);
            $this->db->group_end();
        }
        $this->db->order_by('name', 'DESC');
        $this->db->limit(($limit_all - $count_product));
        $items = $this->db->get('tblitems')->result_array();
        if(!empty($items)) {
            $data['results'][] =
                [
                    'text' => _l('cong_ts_items'),
                    'children' => $items
                ];
        }

        echo json_encode($data);die();

    }

    public function SearchProductSuggest($type = "", $id = '')
    {

	    $data = [];
	    $kt = '';
	    $dt = '';
	    $gender = '';
	    $not_product = [];
    	if(!empty($type) && !empty($id))
	    {
		    $info_client = get_table_where('tblclient_info_detail', ['view_modal' => 1, 'type_form !=' => 'input']);
	    	if($type == 'lead')
		    {
		    	$this->db->where('id', $id);
		    	$object = $this->db->get('tblleads')->row();
		    	if(!empty($object))
			    {
			    	$clients = get_table_where('tblclients', ['leadid' => $object->id], '', 'row');
			    	if(empty($clients))
				    {
					    $kt = $object->kt;
					    $dt = $object->dt;
					    $gender = $object->gender;
					    if(!empty($info_client))
					    {
						    $listWhere = [];
					        foreach($info_client as $kInfo => $vInfo)
						    {
						        $this->db->select('group_concat(value) as listid');
								$this->db->where('lead', $id);
								$this->db->where('id_detail', $vInfo['id']);
								$leadVal = $this->db->get('tbllead_value')->row();
								if(!empty($leadVal))
								{
									$listWhere[$vInfo['id']] = $leadVal->listid;
								}
						    }
					    }
				    }
			    	else
				    {
				    	$type = 'client';
					    $id = $clients->userid;
				    }
			    }

		    }

	    	if($type == 'client')
		    {
		    	$this->db->where('userid', $id);
		    	$object = $this->db->get('tblclients')->row();
			    if(!empty($object))
			    {
				    $kt = $object->kt;
				    $dt = $object->dt;
				    $gender = $object->gender;
				    if(!empty($info_client))
				    {
					    $listWhere = [];
					    foreach($info_client as $kInfo => $vInfo)
					    {
						    $this->db->select('group_concat(value) as listid');
						    $this->db->where('lead', $id);
						    $this->db->where('id_detail', $vInfo['id']);
						    $leadVal = $this->db->get('tbllead_value')->row();
						    if(!empty($leadVal))
						    {
							    $listWhere[$vInfo['id']] = $leadVal->listid;
						    }
					    }
				    }

				    $this->db->select('group_concat(DISTINCT(id_product)) as list_product, client');
				    $this->db->where('client', $object->userid);
				    $this->db->join('tblorders', 'tblorders.id = tblorders_items.id_orders');
				    $order_product = $this->db->get('tblorders_items')->row();
				    if(!empty($order_product))
				    {
					    $not_product = explode(',', $order_product->list_product);
				    }
			    }
		    }

	    	if(empty($type))
		    {
			    $data['results'] = [];
			    echo json_encode($data);die();
		    }
	    }

        $search = $this->input->get('term');
        $limit_all = 100;
        $this->db->select('
            concat("products_", id) as id,
            tbl_products.name as text,
            tbl_products.name_customer as name_customer,
            tbl_products.name_supplier as name_supplier,
            tbl_products.price_sell as price,
            CONCAT("uploads/products/", "", tbl_products.images, "") as img'
        , false);
        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('tbl_products.name', $search);
            $this->db->like('tbl_products.code', $search);
            $this->db->group_end();
        }
        $this->db->where('type_dt', $dt);
        $this->db->where('type_kt', $kt);
        $this->db->where('type_gender', $gender);
        if(!empty($listWhere))
        {
        	foreach($listWhere as $key => $value)
	        {
	        	if(!empty($value))
		        {
		            $this->db->where('
		                tbl_products.id = (select tblproduct_group_info.id_product 
		                from tblproduct_group_info 
		                where id_info = '.$key.' and id_value in ('.$value.') and id_product = tbl_products.id) 
		            ');
		        }
	        	else
		        {
			        $this->db->where('
		                0 = (select count(tblproduct_group_info.id_product) 
		                from tblproduct_group_info 
		                where id_info = '.$key.' and id_product = tbl_products.id) 
		            ');
		        }
	        }
        }
        if(!empty($not_product))
        {
        	$this->db->where_not_in('tbl_products.id', $not_product);
        }
        $this->db->order_by('tbl_products.name', 'DESC');
        $this->db->limit($limit_all);
        $product = $this->db->get('tbl_products')->result_array();
        if(!empty($product))
        {
            $data['results'] = $product;
        }
        echo json_encode($data);die();
    }

    public function GetItemsProduct()
    {
        $id = $this->input->post('id');
        if(!empty($id))
        {
            $id = explode('_', $id);
            if($id[0] == 'items')
            {
                $this->db->select('id, name, price');
                $this->db->where('id', $id[1]);
                $items = $this->db->get('tblitems')->row();
                echo $items->name.' - '. number_format_data($items->price);die();
            }
            else if($id[0] == 'products')
            {
                $this->db->select('id, name, price_sell as price');
                $this->db->where('id', $id[1]);
                $products = $this->db->get('tbl_products')->row();
                if(!empty($products))
                {
                    echo $products->name.' - '. number_format_data($products->price);die();
                }

            }
        }
        echo '';die();
    }

    public function GetReply()
    {
        $id = $this->input->post('id');
        if(!empty($id))
        {
            $this->db->where('id', $id);
            $reply = $this->db->get('tblquick_reply')->row();
            echo $reply->content;die();
        }
        echo '';die();
    }

    public function addContactLead()
    {
        if($this->input->post())
        {
            $id_lead = $this->input->post('id_lead');
            $data = $this->input->post('contact');
            $NumADd = 0;
            foreach($data as $key => $value)
            {
                $this->db->insert('tblcontacts_lead', [
                    'firstname' => $value['firstname'],
                    'email' => $value['email'],
                    'id_lead' => $id_lead,
                    'phonenumber' => $value['phonenumber'],
                    'birtday' => to_sql_date($value['birtday'], true),
                    'title' => $value['title'],
                    'note' => $value['note'],
                    'datecreated' => date('Y-m-d H:i:s'),
                ]);
                if($this->db->insert_id())
                {
                    $NumADd++;
                }
            }

            if(!empty($NumADd))
            {
                echo json_encode([
                    'success' => true,
                    'alert_type' => 'success',
                    'message' => _l('cong_add_true')
                ]);die();
            }
            echo json_encode([
                    'success' => false,
                    'alert_type' => 'danger',
                    'message' => _l('cong_add_false')
                ]);die();
        }
    }

    public function addContactClient()
    {
        if($this->input->post())
        {
            $userid = $this->input->post('userid');
            $data = $this->input->post('contact');
            $NumADd = 0;
            foreach($data as $key => $value)
            {
                $this->db->insert('tblcontacts', [
                    'firstname' => $value['firstname'],
                    'email' => $value['email'],
                    'userid' => $userid,
                    'phonenumber' => $value['phonenumber'],
                    'birtday' => to_sql_date($value['birtday'], true),
                    'title' => $value['title'],
                    'note' => $value['note'],
                    'datecreated' => date('Y-m-d H:i:s'),
                ]);
                if($this->db->insert_id())
                {
                    $NumADd++;
                }
            }

            if(!empty($NumADd))
            {
                echo json_encode([
                    'success' => true,
                    'alert_type' => 'success',
                    'message' => _l('cong_add_true')
                ]);die();
            }
            echo json_encode([
                    'success' => false,
                    'alert_type' => 'danger',
                    'message' => _l('cong_add_false')
                ]);die();
        }
    }

    public function updateContactLead($id_lead = '')
    {
        if($this->input->post())
        {
            $id = $this->input->post('id');
            $_contact = $this->input->post('_contact');
            if(!empty($id) && !empty($_contact))
            {
                $array_update = [];

                if(isset($_contact['title']))
                {
                    $array_update['title'] = $_contact['title'];
                }
                if(isset($_contact['firstname']))
                {
                    $array_update['firstname'] = $_contact['firstname'];
                }
                if(isset($_contact['email']))
                {
                    $array_update['email'] = $_contact['email'];
                } 
                if(isset($_contact['phonenumber']))
                {
                    $array_update['phonenumber'] = $_contact['phonenumber'];
                } 
                if(isset($_contact['birtday']))
                {
                    $array_update['birtday'] = to_sql_date($_contact['birtday'], true);
                }
                if(isset($_contact['note']))
                {
                    $array_update['note'] = $_contact['note'];
                }

                $this->db->where('id', $id);
                $success = $this->db->update('tblcontacts_lead', $array_update);
                if(!empty($success))
                {
	                if(!empty($id_lead))
	                {
		                $arrayUpdateLead = [];
		                if(!empty($array_update['_zcode'])) {
			                $arrayUpdateLead['zcode'] = $array_update['_zcode'];
		                }
		                if(!empty($array_update['fullname'])) {
			                $arrayUpdateLead['name'] = $array_update['fullname'];
		                }
		                if(!empty($array_update['phonenumber'])) {
			                $arrayUpdateLead['phonenumber'] = $array_update['phonenumber'];
		                }
		                if(!empty($array_update['birtday'])) {
			                $arrayUpdateLead['birtday'] = $array_update['birtday'];
		                }
		                if(!empty($array_update['_dt'])) {
			                $arrayUpdateLead['dt'] = $array_update['_dt'];
		                }
		                if(!empty($array_update['_kt'])) {
			                $arrayUpdateLead['kt'] = $array_update['_kt'];
		                }
		                if(!empty($array_update['email'])) {
			                $arrayUpdateLead['email'] = $array_update['email'];
		                }
		                if(!empty($array_update['note'])) {
			                $arrayUpdateLead['description'] = $array_update['note'];
		                }
		                if(!empty($arrayUpdateLead))
		                {
			                $this->db->where('id', $id_lead);
			                $this->db->update('tblleads', $arrayUpdateLead);
		                }
	                }

                    echo json_encode([
                        'success' => true,
                        'alert_type' => 'success',
                        'message' => _l('cong_update_true')
                    ]);die();
            }
            }
            echo json_encode([
                    'success' => false,
                    'alert_type' => 'danger',
                    'message' => _l('cong_update_false')
                ]);die();
        }
    }

    public function updateContactClient($id_lead = '')
    {
        if($this->input->post())
        {
            $id = $this->input->post('id');
            $_contact = $this->input->post('_contact');
            if(!empty($id) && !empty($_contact))
            {
                $array_update = [];
                if(isset($_contact['title'])) {
                    $array_update['title'] = $_contact['title'];
                }
                if(isset($_contact['firstname'])) {
                    $array_update['firstname'] = $_contact['firstname'];
                }
                if(isset($_contact['email'])) {
                    $array_update['email'] = $_contact['email'];
                } 
                if(isset($_contact['phonenumber'])) {
                    $array_update['phonenumber'] = $_contact['phonenumber'];
                } 
                if(isset($_contact['birtday'])) {
                    $array_update['birtday'] = to_sql_date($_contact['birtday'], true);
                }
                if(isset($_contact['note'])) {
                    $array_update['note'] = $_contact['note'];
                }

                if(isset($_contact['_dt'])) {
                    $array_update['_dt'] = $_contact['_dt'];
                }
                if(isset($_contact['_kt'])) {
                    $array_update['_kt'] = $_contact['_kt'];
                }
                if(isset($_contact['_zcode'])) {
                    $array_update['_zcode'] = $_contact['_zcode'];
                }
				if(!empty($array_update))
				{
	                $this->db->where('id', $id);
	                $success = $this->db->update('tblcontacts', $array_update);
				}
                if(!empty($success))
                {
                	if(!empty($id_lead))
	                {
		                $arrayUpdateLead = [];
	                    if(!empty($array_update['_zcode'])) {
	                        $arrayUpdateLead['zcode'] = $array_update['_zcode'];
		                }
	                    if(!empty($array_update['firstname'])) {
	                        $arrayUpdateLead['name'] = $array_update['firstname'];
		                }
	                    if(!empty($array_update['phonenumber'])) {
	                        $arrayUpdateLead['phonenumber'] = $array_update['phonenumber'];
		                }
	                    if(!empty($array_update['birtday'])) {
	                        $arrayUpdateLead['birtday'] = $array_update['birtday'];
		                }
	                    if(!empty($array_update['_dt'])) {
	                        $arrayUpdateLead['dt'] = $array_update['_dt'];
		                }
	                    if(!empty($array_update['_kt'])) {
	                        $arrayUpdateLead['kt'] = $array_update['_kt'];
		                }
	                    if(!empty($array_update['email'])) {
	                        $arrayUpdateLead['email'] = $array_update['email'];
		                }
	                    if(!empty($array_update['note'])) {
	                        $arrayUpdateLead['description'] = $array_update['note'];
		                }
	                    if(!empty($arrayUpdateLead))
		                {
		                    $this->db->where('id', $id_lead);
		                    $this->db->update('tblleads', $arrayUpdateLead);
		                }
	                }


                    echo json_encode([
                        'success' => true,
                        'alert_type' => 'success',
                        'message' => _l('cong_update_true')
                    ]);die();
            }
            }
            echo json_encode([
                    'success' => false,
                    'alert_type' => 'danger',
                    'message' => _l('cong_update_false')
                ]);die();
        }
    }

    public function addProfileListFB()
    {
        $id_facebook = $this->input->post('id_facebook');
        $name = $this->input->post('name');
        if(!empty($id_facebook))
        {
            $this->messager_model->addProfileListFB($id_facebook, $name);
	        $this->addlast_facebook($id_facebook, 0);
        }
    }

    public function addlast_facebook($id_facebook = "", $see = 1)
    {
	    $postData = $this->input->post('postData');
    	if(!empty($postData))
	    {
		    $id_facebook = $this->input->post('id_facebook');
		    $see = $this->input->post('see');
	    }

    	if(!empty($id_facebook))
	    {
	    	$this->db->where('id_facebook', $id_facebook);
	    	$last_messager = $this->db->get('tbllast_messager')->row();
	    	if(!empty($last_messager))
		    {
		    	$this->db->where('id', $last_messager->id);
		    	$this->db->update('tbllast_messager', ['see' => $see]);
		    }
	    	else
		    {
			    $this->db->insert('tbllast_messager', [
				    'id_facebook' => $id_facebook,
				    'see' => $see

			    ]);
		    }
	    }
    	if(!empty($postData))
	    {
	    	echo json_encode(['success' => true]);die();
	    }
    	return true;
    }

    public function create_lead_from_contact()
    {
    	$type = $this->input->post('type');
    	$id_contact = $this->input->post('id_contact');
    	if(!empty($type) && !empty($id_contact))
	    {
	    	if($type == 'client')
		    {
				$this->db->where('id', $id_contact);
				$contact = $this->db->get('tblcontacts')->row();
				if(!empty($contact))
				{
					if(!empty($contact->id_lead_create))
					{
						$this->db->where('id', $contact->id_lead_create);
						$leads = $this->db->get('tblleads')->row();
						if(!empty($leads))
						{
							echo json_encode([
								'success' => true,
								'alert_type' => 'warning',
								'message' => _l('cong_contact_isset_lead'),
							]);die();
						}
					}
					$arrayContact = [
						'name' => $contact->firstname,
						'phonenumber' => $contact->phonenumber,
						'birtday' => $contact->birtday,
						'description' => $contact->note,
						'dateadded' => date('Y-m-d H:i:s'),
						'email' => $contact->email,
						'addedfrom' => get_staff_user_id(),
						'prefix_lead' => date('Ymd'),
						'code_type' => 'NT'
					];
					$this->db->insert('tblleads', $arrayContact);
					if($this->db->insert_id())
					{
						$idLead = $this->db->insert_id();
						CreateCodesystem('lead', $idLead);
						CreateCode('lead', $idLead);
						$this->db->insert('tbllead_assigned', [
							'staff' => get_staff_user_id(),
							'id_lead' => $idLead,
							'created_by' => get_staff_user_id(),
							'date_create' => date('Y-m-d')
						]);
						createCodeNameSystem('lead', $idLead);
						$this->db->where('id', $id_contact);
						$this->db->update('tblcontacts', ['id_lead_create' => $idLead]);
						echo json_encode([
							'success' => true,
							'alert_type' => 'success',
							'message' => _l('cong_create_lead_true'),
						]);die();
					}
				}
		    }
	    	else if($type == 'lead')
		    {
			    $this->db->where('id', $id_contact);
			    $contact = $this->db->get('tblcontacts_lead')->row();
			    if(!empty($contact))
			    {
				    $arrayContact = [
					    'name' => $contact->firstname,
					    'phonenumber' => $contact->phonenumber,
					    'birtday' => $contact->birtday,
					    'description' => $contact->note,
					    'dateadded' => date('Y-m-d H:i:s'),
					    'email' => $contact->email,
					    'addedfrom' => get_staff_user_id(),
					    'prefix_lead' => date('Ymd'),
					    'code_type' => 'NT'
				    ];
				    $this->db->insert('tblleads', $arrayContact);
				    if($this->db->insert_id())
				    {
					    $idLead = $this->db->insert_id();
					    CreateCodesystem('lead', $idLead);
					    CreateCode('lead', $idLead);
					    $this->db->insert('tbllead_assigned', [
						    'staff' => get_staff_user_id(),
						    'id_lead' => $idLead,
						    'created_by' => get_staff_user_id(),
						    'date_create' => date('Y-m-d')
					    ]);
					    createCodeNameSystem('lead', $idLead);
					    echo json_encode([
						    'success' => true,
						    'alert_type' => 'success',
						    'message' => _l('cong_create_lead_true'),
					    ]);die();
				    }
			    }
		    }
		    echo json_encode([
			    'success' => false,
			    'alert_type' => 'danger',
			    'message' => _l('cong_create_lead_false'),
		    ]);die();
	    }
    }
    /*
     * Add items sản phẩm chăm sóc
     */
    public function add_care_of_client_items()
    {
		$items_product = $this->input->post('items_product');
		$id = $this->input->post('id');
		if(!empty($items_product) && !empty($id))
		{
			$items_product = explode('_', $items_product);

			$this->db->where('type_items', $items_product[0]);
			$this->db->where('id_product', $items_product[1]);
			$this->db->where('id_care_of', $id);
			$kt_care_of_items = $this->db->get('tblcare_of_client_items')->row();
			if(!empty($kt_care_of_items))
			{
				echo json_encode([
					'success' => true,
					'alert_type' => 'danger',
					'message' => _l('cong_add_items_isset')
				]);die();
			}
			else
			{
				$this->db->insert('tblcare_of_client_items', [
					'id_care_of' => $id,
					'type_items' => $items_product[0],
					'id_product' => $items_product[1]
				]);
				if($this->db->insert_id())
				{
					echo json_encode([
						'success' => true,
						'alert_type' => 'success',
						'message' => _l('cong_add_items_true')
					]);die();
				}
			}
		}
	    echo json_encode([
		    'success' => false,
		    'alert_type' => 'danger',
		    'message' => _l('cong_add_items_false')
	    ]);die();
    }
    /*
     * End items sản phẩm chăm sóc
     */
    public function view_detail_care_of($id = "")
    {
    	if(!empty($id))
	    {
		    $this->db->select('
                 tblclients.name_system,
                 tblclients.code_system, 
                 concat(COALESCE(prefix_client), COALESCE(code_client), "-", COALESCE(tblclients.zcode), "-", COALESCE(tblclients.code_type)) as fullcode_client, 
                 tblclients.zcode, 
                 concat(COALESCE(prefix_lead),COALESCE(code_lead),"-",COALESCE(tblleads.zcode),"-",COALESCE(tblleads.code_type)) as fullcode_lead,
                 tblcare_of_clients.*,
                 concat(COALESCE(tblorders.prefix), COALESCE(tblorders.code)) as code_orders
             ');
		    $this->db->where('tblcare_of_clients.id', $id);
		    $this->db->join('tblclients', 'tblclients.userid = tblcare_of_clients.client');
		    $this->db->join('tblleads', 'tblleads.id = tblclients.leadid', 'left');
		    $this->db->join('tblorders', 'tblorders.id = tblcare_of_clients.id_orders', 'left');
		    $data['care_of_clients'] = $this->db->get('tblcare_of_clients')->row();
		    $data['view_not_modal'] = true;
		    $this->load->view('admin/care_of_clients/modal_view_detail', $data);
	    }
    }

    public function view_detail_orders($id = "")
    {
	    if(!empty($id))
	    {
		    $data['title'] = _l('cong_detail_orders');
		    $data['orders'] = $this->orders_model->get_view($id);

		    //hoàng crm bổ xung
		    $this->db->select('tblpayment_order. *, tblpayment_modes.name as name_pay_moders');
		    $this->db->where('id_order', $id);
		    $this->db->join('tblpayment_modes', 'tblpayment_modes.id = tblpayment_order.payment_modes', 'left');
		    $data['payment'] = $this->db->get('tblpayment_order')->result_array();

		    $get_currencies_id = get_table_where('tblorders',array('id'=>$id),'','row');
		    $this->db->where('id', $get_currencies_id->currencies_id);
		    $currencies = $this->db->get('tblcurrencies')->row();
		    if(!empty($currencies))
		    {
			    $data['total_international'] = 0;
			    $data['total_cost_trans_international'] = 0;
			    $data['grand_total_international'] = 0;
			    $data['money_paid_international'] = 0;
			    if(!empty($get_currencies_id->total_international)) {
				    $data['total_international'] = app_format_money($get_currencies_id->total_international, $currencies->name);
			    }
			    if(!empty($get_currencies_id->total_cost_trans_international)) {
				    $data['total_cost_trans_international'] = app_format_money($get_currencies_id->total_cost_trans_international, $currencies->name);
			    }
			    if(!empty($get_currencies_id->grand_total_international)) {
				    $data['grand_total_international'] = app_format_money($get_currencies_id->grand_total_international, $currencies->name);
			    }
			    if(!empty($get_currencies_id->money_paid_international)) {
				    $data['money_paid_international'] = app_format_money($get_currencies_id->money_paid_international, $currencies->name);
			    }
		    }
		    //end
		    $data['orders']->name_status = $this->orders_model->get_status_orders($id, $data['orders']->status);
		    $data['view_not_modal'] = true;
		    $this->load->view('admin/orders/view_modal', $data);
	    }
    }

	public function view_detail_advisory($id = '') {
		if(!empty($id))
		{
			$this->db->select('
				concat(COALESCE(tbladvisory_lead.prefix),COALESCE(tbladvisory_lead.code)) as fullcode,
				tbladvisory_lead.*
            ');
			$this->db->where('tbladvisory_lead.id', $id);
			$data['advisory'] = $this->db->get('tbladvisory_lead')->row();
			$data['view_not_modal'] = true;
			$this->load->view('admin/advisory_lead/modal_view_detail', $data);
		}
	}

	public function SearchReply()
	{
		$data = [];
		$search = $this->input->get('term');
		$limit_all = 100;
		$this->db->select('
            id,
            name as text'
			, false);
		if (!empty($search))
		{
			$this->db->group_start();
			$this->db->like('name', $search);
			$this->db->like('content', $search);
			$this->db->group_end();
		}
		$this->db->order_by('name', 'DESC');
		$this->db->limit($limit_all);
		$reply = $this->db->get('tblquick_reply')->result_array();
		foreach($reply as $key => $value)
		{

		}
		if(!empty($reply))
		{
			$data['results'] = $reply;
		}
		echo json_encode($data);die();

	}

	//modal tạo đơn hàng nháp
	public function ViewCreateOrderDraft() {
		$id = $this->input->post('id');
		$type = $this->input->post('type');
		$data['draft'] = 1;
		if($type == 'lead') {
			$data['type'] = $type;
			$data['id'] = $id;
			$lead = get_table_where('tblleads', ['id' => $id], '', 'row');
			if(!empty($lead)) {
				$data['object'] = $lead;
				$message_err = "";
				if(empty($lead->zcode)) {
					$message_err .= ', '._l('cong_t_zcode');
				}
				if(!empty($message_err))
				{
					echo json_encode([
						'success' => false,
						'alert_type' => 'danger',
						'message' => (_l('cong_pls_input').' '.trim($message_err, ', '))
					]);die();
				}
				else
				{

					$data['shipping'] = get_table_where('tblshipping_lead', ['lead_id' => $lead->id]);
					$this->db->select('tbladvisory_lead.id, concat(COALESCE(tbladvisory_lead.prefix), COALESCE(tbladvisory_lead.code)) as full_code, tbladvisory_lead.type_code');
					$this->db->where('type_object', $type);
					$this->db->where('id_object', $id);
					$this->db->join('tblprocedure_advisory_lead', 'tblprocedure_advisory_lead.id_advisory = tbladvisory_lead.id');
					$this->db->group_by('tbladvisory_lead.id');
					$data['advisory'] = $this->db->get('tbladvisory_lead')->result_array();
					echo json_encode([
						'data' => $this->load->view('messagers/manage/modal/orders_draft', $data, true),
						'success' => true
					]);die();
				}
			}
		}
		else if($type == 'client')
		{
			$data['type'] = $type;
			$data['id'] = $id;
			$client = get_table_where('tblclients', ['userid' => $id], '', 'row');
			if(!empty($client))
			{
				$data['object'] = $client;
				$message_err = "";
				if(empty($client->zcode)) {
					$message_err .= ', '._l('cong_t_zcode');
				}
				if(!empty($message_err))
				{
					echo json_encode([
						'success' => false,
						'alert_type' => 'danger',
						'message' => (_l('cong_pls_input').' '.trim($message_err, ', '))
					]);die();
				}
				else
				{
					$data['shipping'] = get_table_where('tblshipping_client', ['client' => $data['id']]);

					$this->db->select('tbladvisory_lead.id, concat(COALESCE(tbladvisory_lead.prefix), COALESCE(tbladvisory_lead.code)) as full_code, tbladvisory_lead.type_code');
					$this->db->group_start();
					$this->db->where('type_object',$type);
					$this->db->where('id_object', $id);
					$this->db->group_end();
					if(!empty($client->leadid))
					{
						$this->db->or_group_start();
						$this->db->or_where('id_object', $client->leadid);
						$this->db->or_where('type_object', 'lead');
						$this->db->group_end();
					}
					$this->db->join('tblprocedure_advisory_lead', 'tblprocedure_advisory_lead.id_advisory = tbladvisory_lead.id');
					$this->db->group_by('tbladvisory_lead.id');
					$data['advisory'] = $this->db->get('tbladvisory_lead')->result_array();

					echo json_encode([
						'data' => $this->load->view('messagers/manage/modal/orders_draft', $data, true),
						'success' => true
					]);die();
				}
			}
		}
	}

	//Tạo đơn hàng nháp
	public function create_orders_draft()
	{
		if($this->input->post()) {
			$data = $this->input->post();

			$id_object_draft = $data['id_object_draft'];
			$type_object_draft = $data['type_object_draft'];
			if($type_object_draft == 'client')
			{
				$data['client'] = $id_object_draft;
				$object = get_table_where('tblclients', ['userid' => $id_object_draft], '', 'row');
			}
			else if($type_object_draft == 'lead')
			{
				$object = get_table_where('tblleads', ['id' => $id_object_draft], '', 'row');
			}
			if(!empty($object))
			{
				$success = $this->orders_model->add($data);
				if(!empty($success))
				{
					echo json_encode([
						'success' => true,
						'alert_type' => 'success',
						'id_facebook' => $object->id_facebook,
						'message' => _l('cong_add_true')
					]);die();
				}
			}
			echo json_encode([
				'success' => false,
				'alert_type' => 'danger',
				'message' => _l('cong_add_true')
			]);die();
		}
	}

	//Thêm số điện thoại facebook
	public function insertPhoneFacebook()
	{
		$data = $this->input->post();
		$id_facebook = $data['id_facebook'];
		$phone = $data['phone'];
		if(!empty($id_facebook) && !empty($phone))
		{
			$success = AddphoneFacebook($id_facebook, $phone);

			$listPhone = getPhoneFacebook($id_facebook);
			if(!empty($listPhone))
			{
				echo $listPhone->list_phone;
			}
		}
		echo '';die();
	}


}

