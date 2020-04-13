<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Messager_model extends App_Model
{
    function __construct()
    {
        parent::__construct();
    }


    public function addProfileListFB($id_facebook = '', $name = '')
    {
        if(!empty($id_facebook))
        {
            $this->db->where('id_facebook', $id_facebook);
            $client = $this->db->get('tblclients')->num_rows();
            if(empty($client))
            {
                $this->db->where('id_facebook', $id_facebook);
                $lead = $this->db->get('tblleads')->num_rows();
                if(empty($lead))
                {
                    $this->db->where('id_facebook', $id_facebook);
                    $list_fb = $this->db->get('tbllist_fb')->num_rows();
                    if(empty($list_fb))
                    {
                        $arrayAdd = ['id_facebook' => $id_facebook];
                        $arrayAdd['name_facebook'] = trim($name);
                        $arrayAdd['code_type'] = 'NEW';
                        $arrayAdd['create_by'] = get_staff_user_id();
                        $arrayAdd['date_create'] = date('Y-m-d H:i:s');
                        $this->db->insert('tbllist_fb', $arrayAdd);
                        if($this->db->insert_id()) {
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
                            if (!empty($data->avatar)) {
                                $paste_img = base_url('download/preview_image?path=uploads/avatarFB/' . $data->id . '/thumb_' . $data->avatar);
                                $data->img = $paste_img;
                            }

                        }
                    }
                }
            }
        }
    }

    public function WarLead($id = "")
    {
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
                    $this->db->where('id_facebook', $listFB->id_facebook);
                    $NumLead = $this->db->get('tblleads')->num_rows();
                    if($NumLead == 0)
                    {
                        $first_date = strtotime(date('Y-m-d'));
                        $second_date = strtotime($listFB->date_create);
                        $datediff = abs($first_date - $second_date);
                        $leadtime =  floor($datediff / (60*60*24));
                        $this->db->insert('tblleads', [
                            'id_facebook' => $listFB->id_facebook,
                            'code_type' => 'NEW',
                            'name' => trim($listFB->name),
                            'code_system' => $listFB->prefix.$listFB->code,
                            'name_facebook' => trim($listFB->name_facebook),
                            'company' => trim($listFB->company),
                            'email' => trim($listFB->email),
                            'address' => trim($listFB->address),
                            'birtday' => $listFB->birtday,
                            'gender' => $listFB->gender,
                            'phonenumber' => $listFB->phonenumber,
                            'description' => trim($listFB->note),
                            'zcode' => trim($listFB->zcode),
                            'addedfrom' => get_staff_user_id(),
                            'dateadded' => date('Y-m-d H:i:s'),
                            'status' => 2,
                            'source' => 2,
                            'date_contact' => $listFB->date_create,
                            'leadtime' => $leadtime
                        ]);
                        $idLead = $this->db->insert_id();
                        if(!empty($idLead))
                        {
                            $paste_img = FCPATH . 'uploads/avatarFB' . '/' . $listFB->id . '/';
                            $paste_imgLead = get_upload_path_by_type('lead') . $idLead . '/';
                            _maybe_create_upload_path($paste_imgLead);

                            $image_small = $paste_img.'small_'.$listFB->avatar;
                            $image_thumb = $paste_img.'thumb_'.$listFB->avatar;
                            $time = time();
                            @copy($image_small, $paste_imgLead . 'small_' . $time . '.jpg');
                            @copy($image_thumb, $paste_imgLead . 'thumb_' . $time . '.jpg');
                            $avatar = $time . '.jpg';

                            CreateCode('lead', $idLead);

                            $this->db->where('id', $idLead);
                            $this->db->update(db_prefix().'leads', [
                                'lead_image' => $avatar
                            ]);

                            $this->db->where('id_listfb', $listFB->id);
                            $listfb_assigned = $this->db->get('tbllistfb_assigned')->result_array();
                            if(!empty($listfb_assigned))
                            {
                                foreach($listfb_assigned as $key => $value)
                                {
                                    $this->db->insert('tbllead_assigned', [
                                        'staff' => $value['staff'],
                                        'id_lead' => $idLead,
                                        'created_by' => $value['created_by'],
                                        'date_create' => $value['date_create']
                                    ]);
                                }
                            }
                            ChangeObjectAssigned('listfb', $listFB->id, 'lead', $idLead);
                            createCodeNameSystem('lead', $idLead);
                            return $idLead;
                        }
                    }
                    return false;
                }
                return false;
            }
        }
        return false;
    }
}