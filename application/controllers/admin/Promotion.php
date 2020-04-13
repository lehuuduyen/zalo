<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Promotion extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data['title']          = _l('promotion_list_full_name');
        $this->load->view('admin/promotion/manage', $data);
    }
    public function table_promotion_list($value='')
    {
        if (!has_permission('promotion', '', 'view')) {
            ajax_access_denied();
        }
        $this->app->get_table_data('promotion_list');
    }
    public function table_promotion_detail($value='')
    {
        if (!has_permission('promotion', '', 'view')) {
            ajax_access_denied();
        }
        $this->app->get_table_data('promotion_detail');
    }
    public function group_detail($id = '')
    {
        $data = $this->input->post();
        if($id == "") {
            $in = array(
                'name'=>$data['name']
            );
            $this->db->insert('tblpromotion_list',$in);
            echo json_encode(array('success' => true, 'alert_type' => 'success', 'message' => _l('add_promotion_list_success')));
        }
        else {
            $in = array(
                'name'=>$data['name']
            );
            $this->db->where('id',$id);
            $this->db->update('tblpromotion_list',$in);
            echo json_encode(array('success' => true, 'alert_type' => 'success', 'message' => _l('edit_promotion_list_success')));
        }
    }
    public function getData_promotion_list($id='')
    {
        $this->db->select('tblpromotion_list.*');
        $this->db->where('id',$id);
        $data = $this->db->get('tblpromotion_list')->row();
        echo json_encode($data);
    }

    public function promotion_detail()
    {
        $data['title']          = _l('promotion');
        $this->load->view('admin/promotion/promotion_detail', $data);
    }
    public function detail($id='')
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if(!isset($data['area_of_application'])) {
                $data['area_of_application'] = null;
            }

            if(!isset($data['customer_id']) || ($data['method_of_application'] != 'other' && $data['area_of_application'] != 'other')) {
                $data['customer_id'] = 0;
            }

            if(!isset($data['groups_in']) || $data['area_of_application'] != 'area') {
                $data['groups_in'] = null;
            }
            else {
                $data['groups_in'] = implode(',', $data['groups_in']);
            }

            if(!isset($data['date_active'])) {
                $data['date_active'] = null;
            }
            else {
                $data['date_active'] = explode(' - ', $data['date_active']);
            }
            if($id == "") {
                $in = array(
                    'promotion_list_id'=>$data['promotion_list_id'],
                    'name'=>$data['name'],
                    'type'=>$data['type'],
                    'method_of_application'=>$data['method_of_application'],
                    'area_of_application'=>$data['area_of_application'],
                    'customer_id'=>$data['customer_id'],
                    'groups_in'=>$data['groups_in'],
                    'date_active_start'=>to_sql_date($data['date_active'][0]),
                    'date_active_end'=>to_sql_date($data['date_active'][1])
                );
                $this->db->insert('tblpromotion',$in);
                $insert_id = $this->db->insert_id();
                if($insert_id) {
                    //loại khuyến mãi theo chiết khấu
                    if($data['type']=='discount') {
                        if(!isset($data['time_sales'])) {
                            $data['time_sales'] = null;
                        }
                        else {
                            $data['time_sales'] = explode(' - ', $data['time_sales']);
                        }
                        $in_discount = array(
                            'promotion_id'=>$insert_id,
                            'type_discount'=>$data['type_discount'],
                            'time_sales_start'=>to_sql_date($data['time_sales'][0]),
                            'time_sales_end'=>to_sql_date($data['time_sales'][1])
                        );
                        $this->db->insert('tblpromotion_discount',$in_discount);

                        if(!isset($data['item_discount'])) {
                            $data['item_discount'] = array();
                        }
                        if(!isset($data['item_discount_item']) || $data['type_discount'] == 1) {
                            $data['item_discount_item'] = array();
                        }
                        foreach ($data['item_discount'] as $key => $value) {
                            $val_discount = array(
                                'promotion_id'=>$insert_id,
                                'limit_sales'=>str_replace(',',"",$value['limit_sales']),
                                'type_limit_discount'=>$value['type_discount'],
                                'limit_discount'=>str_replace(',',"",$value['limit_discount'])
                            );
                            $this->db->insert('tblpromotion_discount_amount',$val_discount);
                        }
                        foreach ($data['item_discount_item'] as $key => $value) {
                            $value['id_item'] = explode('_', $value['id_item']);
                            $item_discount = array(
                                'promotion_id'=>$insert_id,
                                'id_item'=>$value['id_item'][1],
                                'type_item'=>$value['id_item'][0]
                            );
                            $this->db->insert('tblpromotion_discount_item',$item_discount);
                        }
                        set_alert('success', _l('cong_add_true'));
                        redirect(admin_url('promotion/promotion_detail'));
                    }
                    //loại khuyến mãi theo bộ
                    else if($data['type']=='item') {
                        if(!isset($data['item'])) {
                            $data['item'] = array();
                        }
                        if(!isset($data['items_gift'])) {
                            $data['items_gift'] = array();
                        }
                        foreach ($data['item'] as $key => $value) {
                            $value['id_item'] = explode('_', $value['id_item']);
                            $val_item = array(
                                'promotion_id'=>$insert_id,
                                'id_item'=>$value['id_item'][1],
                                'type_item'=>$value['id_item'][0],
                                'quantity'=>str_replace(',',"",$value['quantity'])
                            );
                            $this->db->insert('tblpromotion_item',$val_item);
                        }
                        foreach ($data['items_gift'] as $key => $value) {
                            $value['id'] = explode('_', $value['id']);
                            $value['items_id'] = explode('_', $value['items_id']);
                            $val_items_gift = array(
                                'promotion_id'=>$insert_id,
                                'promotion_item_id'=>$value['items_id'][1],
                                'promotion_item_type'=>$value['items_id'][0],
                                'id_item'=>$value['id'][1],
                                'type_item'=>$value['id'][0],
                                'quantity'=>str_replace(',',"",$value['quantity'])
                            );
                            $this->db->insert('tblpromotion_item_gift',$val_items_gift);
                        }
                        set_alert('success', _l('cong_add_true'));
                        redirect(admin_url('promotion/promotion_detail'));
                    }
                    //loại khuyến mãi theo doanh số
                    else if($data['type']=='sales') {
                        if(!isset($data['date_active_sales'])) {
                            $data['date_active_sales'] = null;
                        }
                        else {
                            $data['date_active_sales'] = explode(' - ', $data['date_active_sales']);
                        }

                        if(!isset($data['type_limit_points']) == 'Điểm') {
                            $data['type_sales'] = null;
                        }

                        $in_sales = array(
                            'promotion_id'=>$insert_id,
                            'type_limit_points'=>$data['type_limit_points'],
                            'limit_points'=>str_replace(',',"",$data['limit_points']),
                            'date_active_sales_start'=>to_sql_date($data['date_active_sales'][0]),
                            'date_active_sales_end'=>to_sql_date($data['date_active_sales'][1]),
                            'type_sales'=>$data['type_sales']
                        );
                        $this->db->insert('tblpromotion_sales',$in_sales);

                        if(!isset($data['item_points']) || $data['type_sales'] == 1 || $data['type_limit_points'] == 'Điểm') {
                            $data['item_points'] = array();
                        }
                        if(!isset($data['item'])) {
                            $data['item'] = array();
                        }
                        foreach ($data['item_points'] as $key => $value) {
                            $value['id_item'] = explode('_', $value['id_item']);
                            $val_item_points = array(
                                'promotion_id'=>$insert_id,
                                'id_item'=>$value['id_item'][1],
                                'type_item'=>$value['id_item'][0]
                            );
                            $this->db->insert('tblpromotion_sales_item',$val_item_points);
                        }
                        foreach ($data['item'] as $key => $value) {
                            $value['id_item'] = explode('_', $value['id_item']);
                            $item_item = array(
                                'promotion_id'=>$insert_id,
                                'id_item'=>$value['id_item'][1],
                                'type_item'=>$value['id_item'][0],
                                'quantity'=>str_replace(',',"",$value['quantity'])
                            );
                            $this->db->insert('tblpromotion_sales_item_gift',$item_item);
                        }
                        set_alert('success', _l('cong_add_true'));
                        redirect(admin_url('promotion/promotion_detail'));
                    }
                }
            }
            else {
                $in = array(
                    'promotion_list_id'=>$data['promotion_list_id'],
                    'name'=>$data['name'],
                    'type'=>$data['type'],
                    'method_of_application'=>$data['method_of_application'],
                    'area_of_application'=>$data['area_of_application'],
                    'customer_id'=>$data['customer_id'],
                    'groups_in'=>$data['groups_in'],
                    'date_active_start'=>to_sql_date($data['date_active'][0]),
                    'date_active_end'=>to_sql_date($data['date_active'][1])
                );
                $this->db->where('id',$id);
                $results = $this->db->update('tblpromotion',$in);
                if($results) {
                    //loại khuyến mãi theo chiết khấu
                    if($data['type']=='discount') {
                        if(!isset($data['time_sales'])) {
                            $data['time_sales'] = null;
                        }
                        else {
                            $data['time_sales'] = explode(' - ', $data['time_sales']);
                        }
                        $in_discount = array(
                            'promotion_id'=>$id,
                            'type_discount'=>$data['type_discount'],
                            'time_sales_start'=>to_sql_date($data['time_sales'][0]),
                            'time_sales_end'=>to_sql_date($data['time_sales'][1])
                        );
                        $this->db->where('promotion_id',$id);
                        $this->db->update('tblpromotion_discount',$in_discount);

                        if(!isset($data['item_discount'])) {
                            $data['item_discount'] = array();
                        }
                        if(!isset($data['item_discount_item']) || $data['type_discount'] == 1) {
                            $data['item_discount_item'] = array();
                        }
                        $arr_ID_amount = array();
                        $arr_ID_item = array();
                        foreach ($data['item_discount'] as $key => $value) {
                            $val_discount = array(
                                'promotion_id'=>$id,
                                'limit_sales'=>str_replace(',',"",$value['limit_sales']),
                                'type_limit_discount'=>$value['type_discount'],
                                'limit_discount'=>str_replace(',',"",$value['limit_discount'])
                            );
                            if(isset($value['id_amount']) && is_numeric($value['id_amount'])) {
                                $this->db->where('id',$value['id_amount']);
                                $this->db->update('tblpromotion_discount_amount',$val_discount);
                                $arr_ID_amount[] = $value['id_amount'];
                            }
                            else {
                                $this->db->insert('tblpromotion_discount_amount',$val_discount);
                                $id_amount = $this->db->insert_id();
                                $arr_ID_amount[] = $id_amount;
                            }
                        }
                        foreach ($data['item_discount_item'] as $key => $value) {
                            $value['id_item'] = explode('_', $value['id_item']);
                            $item_discount = array(
                                'promotion_id'=>$id,
                                'id_item'=>$value['id_item'][1],
                                'type_item'=>$value['id_item'][0]
                            );
                            $checkExists = get_table_where('tblpromotion_discount_item',array('promotion_id'=>$id,'id_item'=>$value['id_item'][1],'type_item'=>$value['id_item'][0]),'','row');
                            if($checkExists) {
                                $this->db->where('id',$checkExists->id);
                                $this->db->update('tblpromotion_discount_item',$item_discount);
                                $arr_ID_item[] = $checkExists->id;
                            }
                            else {
                                $this->db->insert('tblpromotion_discount_item',$item_discount);
                                $id_item = $this->db->insert_id();
                                $arr_ID_item[] = $id_item;
                            }
                        }

                        //xóa
                        if(count($arr_ID_amount) > 0) {
                            $this->db->where('promotion_id',$id);
                            $this->db->where_not_in('id',$arr_ID_amount);
                            $this->db->delete('tblpromotion_discount_amount');
                        }
                        else {
                            $this->db->where('promotion_id',$id);
                            $this->db->delete('tblpromotion_discount_amount');
                        }

                        if(count($arr_ID_item) > 0) {
                            $this->db->where('promotion_id',$id);
                            $this->db->where_not_in('id',$arr_ID_item);
                            $this->db->delete('tblpromotion_discount_item');
                        }
                        else {
                            $this->db->where('promotion_id',$id);
                            $this->db->delete('tblpromotion_discount_item');
                        }
                        set_alert('success', _l('cong_update_true'));
                        redirect(admin_url('promotion/detail/'.$id));
                    }
                    //loại khuyến mãi theo bộ
                    if($data['type']=='item') {
                        if(!isset($data['item'])) {
                            $data['item'] = array();
                        }
                        if(!isset($data['items_gift'])) {
                            $data['items_gift'] = array();
                        }
                        $arr_ID_item = array();
                        $arr_ID_item_gift = array();
                        foreach ($data['item'] as $key => $value) {
                            $value['id_item'] = explode('_', $value['id_item']);
                            $val_item = array(
                                'promotion_id'=>$id,
                                'id_item'=>$value['id_item'][1],
                                'type_item'=>$value['id_item'][0],
                                'quantity'=>str_replace(',',"",$value['quantity'])
                            );
                            $checkExists = get_table_where('tblpromotion_item',array('promotion_id'=>$id,'id_item'=>$value['id_item'][1],'type_item'=>$value['id_item'][0]),'','row');
                            if($checkExists) {
                                $this->db->where('id',$checkExists->id);
                                $this->db->update('tblpromotion_item',$val_item);
                                $arr_ID_item[] = $checkExists->id;
                            }
                            else {
                                $this->db->insert('tblpromotion_item',$val_item);
                                $id_item = $this->db->insert_id();
                                $arr_ID_item[] = $id_item;
                            }
                        }
                        foreach ($data['items_gift'] as $key => $value) {
                            $value['id'] = explode('_', $value['id']);
                            $value['items_id'] = explode('_', $value['items_id']);
                            $val_items_gift = array(
                                'promotion_id'=>$id,
                                'promotion_item_id'=>$value['items_id'][1],
                                'promotion_item_type'=>$value['items_id'][0],
                                'id_item'=>$value['id'][1],
                                'type_item'=>$value['id'][0],
                                'quantity'=>str_replace(',',"",$value['quantity'])
                            );
                            $checkExists2 = get_table_where('tblpromotion_item_gift',array('promotion_id'=>$id,'id_item'=>$value['id'][1],'type_item'=>$value['id'][0],'promotion_item_id'=>$value['items_id'][1],'promotion_item_type'=>$value['items_id'][0]),'','row');
                            if($checkExists2) {
                                $this->db->where('id',$checkExists2->id);
                                $this->db->update('tblpromotion_item_gift',$val_items_gift);
                                $arr_ID_item_gift[] = $checkExists2->id;
                            }
                            else {
                                $this->db->insert('tblpromotion_item_gift',$val_items_gift);
                                $id_item = $this->db->insert_id();
                                $arr_ID_item_gift[] = $id_item;
                            }
                        }

                        //xóa
                        if(count($arr_ID_item) > 0) {
                            $this->db->where('promotion_id',$id);
                            $this->db->where_not_in('id',$arr_ID_item);
                            $this->db->delete('tblpromotion_item');
                        }
                        else {
                            $this->db->where('promotion_id',$id);
                            $this->db->delete('tblpromotion_item');
                        }

                        if(count($arr_ID_item_gift) > 0) {
                            $this->db->where('promotion_id',$id);
                            $this->db->where_not_in('id',$arr_ID_item_gift);
                            $this->db->delete('tblpromotion_item_gift');
                        }
                        else {
                            $this->db->where('promotion_id',$id);
                            $this->db->delete('tblpromotion_item_gift');
                        }
                        set_alert('success', _l('cong_update_true'));
                        redirect(admin_url('promotion/detail/'.$id));
                    }
                    //loại khuyến mãi theo doanh số
                    else if($data['type']=='sales') {
                        if(!isset($data['date_active_sales'])) {
                            $data['date_active_sales'] = null;
                        }
                        else {
                            $data['date_active_sales'] = explode(' - ', $data['date_active_sales']);
                        }

                        if($data['type_limit_points'] == 'Điểm') {
                            $data['type_sales'] = null;
                        }

                        $in_sales = array(
                            'promotion_id'=>$id,
                            'type_limit_points'=>$data['type_limit_points'],
                            'limit_points'=>str_replace(',',"",$data['limit_points']),
                            'date_active_sales_start'=>to_sql_date($data['date_active_sales'][0]),
                            'date_active_sales_end'=>to_sql_date($data['date_active_sales'][1]),
                            'type_sales'=>$data['type_sales']
                        );
                        $this->db->where('promotion_id',$id);
                        $this->db->update('tblpromotion_sales',$in_sales);

                        if(!isset($data['item_points']) || $data['type_sales'] == 1 || $data['type_limit_points'] == 'Điểm') {
                            $data['item_points'] = array();
                        }
                        if(!isset($data['item'])) {
                            $data['item'] = array();
                        }
                        $arr_ID_points = array();
                        $arr_ID_item = array();
                        foreach ($data['item_points'] as $key => $value) {
                            $value['id_item'] = explode('_', $value['id_item']);
                            $val_item_points = array(
                                'promotion_id'=>$id,
                                'id_item'=>$value['id_item'][1],
                                'type_item'=>$value['id_item'][0]
                            );
                            $checkExists = get_table_where('tblpromotion_sales_item',array('promotion_id'=>$id,'id_item'=>$value['id_item'][1],'type_item'=>$value['id_item'][0]),'','row');
                            if($checkExists) {
                                $this->db->where('id',$checkExists->id);
                                $this->db->update('tblpromotion_sales_item',$val_item_points);
                                $arr_ID_points[] = $checkExists->id;
                            }
                            else {
                                $this->db->insert('tblpromotion_sales_item',$val_item_points);
                                $id_points = $this->db->insert_id();
                                $arr_ID_points[] = $id_points;
                            }
                        }
                        foreach ($data['item'] as $key => $value) {
                            $value['id_item'] = explode('_', $value['id_item']);
                            $item_item = array(
                                'promotion_id'=>$id,
                                'id_item'=>$value['id_item'][1],
                                'type_item'=>$value['id_item'][0],
                                'quantity'=>str_replace(',',"",$value['quantity'])
                            );
                            $checkExists2 = get_table_where('tblpromotion_sales_item_gift',array('promotion_id'=>$id,'id_item'=>$value['id_item'][1],'type_item'=>$value['id_item'][0]),'','row');
                            if($checkExists2) {
                                $this->db->where('id',$checkExists2->id);
                                $this->db->update('tblpromotion_sales_item_gift',$item_item);
                                $arr_ID_item[] = $checkExists2->id;
                            }
                            else {
                                $this->db->insert('tblpromotion_sales_item_gift',$item_item);
                                $id_item = $this->db->insert_id();
                                $arr_ID_item[] = $id_item;
                            }
                        }

                        //xóa
                        if(count($arr_ID_points) > 0) {
                            $this->db->where('promotion_id',$id);
                            $this->db->where_not_in('id',$arr_ID_points);
                            $this->db->delete('tblpromotion_sales_item');
                        }
                        else {
                            $this->db->where('promotion_id',$id);
                            $this->db->delete('tblpromotion_sales_item');
                        }

                        if(count($arr_ID_item) > 0) {
                            $this->db->where('promotion_id',$id);
                            $this->db->where_not_in('id',$arr_ID_item);
                            $this->db->delete('tblpromotion_sales_item_gift');
                        }
                        else {
                            $this->db->where('promotion_id',$id);
                            $this->db->delete('tblpromotion_sales_item_gift');
                        }
                        set_alert('success', _l('cong_update_true'));
                        redirect(admin_url('promotion/detail/'.$id));
                    }
                }
            }
        }
        $data['clients'] = array();
        if ($id == "") {
            $data['dataItem'] = array();
            $data['dataAmount'] = array();
            $data['dataItem_gift'] = array();
            $data['title'] = _l('add_promotion');
        }
        else {
            $data['title'] = _l('edit_promotion');
            $data['dataMain'] = get_table_where('tblpromotion',array('id'=>$id),'','row');
            //loại khuyến mãi theo chiết khấu
            if($data['dataMain']->type == 'discount') {
                $data['dataSub'] = get_table_where('tblpromotion_discount',array('promotion_id'=>$id),'','row');
                $data['dataItem_gift'] = array(); //bổ xung view loại 3
                $data['dataGift'] = array(); //bổ xung view loại 2
                
                $dataItem = get_table_where('tblpromotion_discount_item',array('promotion_id'=>$id));
                if(empty($dataItem)) {
                    $data['dataItem'] = array();
                }
                else {
                    foreach ($dataItem as $key => $value) {
                        if($value['type_item'] == 'items') {
                            $get_item = get_table_where('tblitems',array('id'=>$value['id_item']),'','row');
                            $data['dataItem'][$key]['id_item'] = $value['type_item'] .'_'. $get_item->id;
                            $data['dataItem'][$key]['name_item'] = $get_item->name .' - '. number_format($get_item->price);
                            $data['dataItem'][$key]['img_item'] = (!empty($get_item->avatar) && $get_item->avatar != '') ? $get_item->avatar : 'uploads/no-img.jpg';
                        }
                        else if($value['type_item'] == 'product') {
                            $get_item = get_table_where('tbl_products',array('id'=>$value['id_item']),'','row');
                            $data['dataItem'][$key]['id_item'] = $value['type_item'] .'_'. $get_item->id;
                            $data['dataItem'][$key]['name_item'] = $get_item->name .' - '. number_format($get_item->price_sell);
                            $data['dataItem'][$key]['img_item'] = (!empty($get_item->images) && $get_item->images != '') ? 'uploads/products/'.$get_item->images : 'uploads/no-img.jpg';
                        }
                        else if($value['type_item'] == 'nvl') {
                            $get_item = get_table_where('tbl_materials',array('id'=>$value['id_item']),'','row');
                            $data['dataItem'][$key]['id_item'] = $value['type_item'] .'_'. $get_item->id;
                            $data['dataItem'][$key]['name_item'] = $get_item->name .' - '. number_format($get_item->price_sell);
                            $data['dataItem'][$key]['img_item'] = (!empty($get_item->images) && $get_item->images != '') ? 'uploads/materials/'.$get_item->images : 'uploads/no-img.jpg';
                        } 
                    }
                }
                $dataAmount = get_table_where('tblpromotion_discount_amount',array('promotion_id'=>$id));
                if(empty($dataAmount)) {
                    $data['dataAmount'] = array();
                }
                else {
                    foreach ($dataAmount as $key => $value) {
                        $data['dataAmount'][$key]['id_amount'] = $value['id'];
                        $data['dataAmount'][$key]['limit_sales'] = number_format($value['limit_sales']);
                        $data['dataAmount'][$key]['type_limit_discount'] = $value['type_limit_discount'];
                        $data['dataAmount'][$key]['limit_discount'] = number_format($value['limit_discount']);
                    }
                }
            }
            //loại khuyến mãi theo bộ
            else if($data['dataMain']->type == 'item') {
                $data['dataSub'] = get_table_where('tblpromotion_discount',array('id'=>2),'','row'); //lấy rỗng
                $data['dataSub']->type_discount = ''; //bổ xung view loại 1
                $data['dataAmount'] = array(); //bổ xung view loại 1
                $data['dataItem_gift'] = array(); //bổ xung view loại 3
                $dataItem = get_table_where('tblpromotion_item',array('promotion_id'=>$id));
                if(empty($dataItem)) {
                    $data['dataItem'] = array();
                }
                else {
                    foreach ($dataItem as $key => $value) {
                        if($value['type_item'] == 'items') {
                            $get_item = get_table_where('tblitems',array('id'=>$value['id_item']),'','row');
                            $data['dataItem'][$key]['id_item'] = $value['type_item'] .'_'. $get_item->id;
                            $data['dataItem'][$key]['name_item'] = $get_item->name .' - '. number_format($get_item->price);
                            $data['dataItem'][$key]['img_item'] = (!empty($get_item->avatar) && $get_item->avatar != '') ? $get_item->avatar : 'uploads/no-img.jpg';
                        }
                        else if($value['type_item'] == 'product') {
                            $get_item = get_table_where('tbl_products',array('id'=>$value['id_item']),'','row');
                            $data['dataItem'][$key]['id_item'] = $value['type_item'] .'_'. $get_item->id;
                            $data['dataItem'][$key]['name_item'] = $get_item->name .' - '. number_format($get_item->price_sell);
                            $data['dataItem'][$key]['img_item'] = (!empty($get_item->images) && $get_item->images != '') ? 'uploads/products/'.$get_item->images : 'uploads/no-img.jpg';
                        }
                        else if($value['type_item'] == 'nvl') {
                            $get_item = get_table_where('tbl_materials',array('id'=>$value['id_item']),'','row');
                            $data['dataItem'][$key]['id_item'] = $value['type_item'] .'_'. $get_item->id;
                            $data['dataItem'][$key]['name_item'] = $get_item->name .' - '. number_format($get_item->price_sell);
                            $data['dataItem'][$key]['img_item'] = (!empty($get_item->images) && $get_item->images != '') ? 'uploads/materials/'.$get_item->images : 'uploads/no-img.jpg';
                        }
                        $data['dataItem'][$key]['quantity'] = number_format($value['quantity']);

                        $dataGift = get_table_where('tblpromotion_item_gift',array('promotion_id'=>$id,'promotion_item_id'=>$value['id_item'],'promotion_item_type'=>$value['type_item']));
                        if(empty($dataGift)) {
                            $data['dataItem'][$key]['dataGift'] = array();
                        }
                        else {
                            foreach ($dataGift as $key_Gift => $value_Gift) {
                                if($value_Gift['type_item'] == 'items') {
                                    $get_item = get_table_where('tblitems',array('id'=>$value_Gift['id_item']),'','row');
                                    $data['dataItem'][$key]['dataGift'][$key_Gift]['id_item'] = $value_Gift['type_item'] .'_'. $get_item->id;
                                    $data['dataItem'][$key]['dataGift'][$key_Gift]['name_item'] = $get_item->name .' - '. number_format($get_item->price);
                                    $data['dataItem'][$key]['dataGift'][$key_Gift]['img_item'] = (!empty($get_item->avatar) && $get_item->avatar != '') ? $get_item->avatar : 'uploads/no-img.jpg';
                                }
                                else if($value_Gift['type_item'] == 'product') {
                                    $get_item = get_table_where('tbl_products',array('id'=>$value_Gift['id_item']),'','row');
                                    $data['dataItem'][$key]['dataGift'][$key_Gift]['id_item'] = $value_Gift['type_item'] .'_'. $get_item->id;
                                    $data['dataItem'][$key]['dataGift'][$key_Gift]['name_item'] = $get_item->name .' - '. number_format($get_item->price_sell);
                                    $data['dataItem'][$key]['dataGift'][$key_Gift]['img_item'] = (!empty($get_item->images) && $get_item->images != '') ? 'uploads/products/'.$get_item->images : 'uploads/no-img.jpg';
                                }
                                else if($value_Gift['type_item'] == 'nvl') {
                                    $get_item = get_table_where('tbl_materials',array('id'=>$value_Gift['id_item']),'','row');
                                    $data['dataItem'][$key]['dataGift'][$key_Gift]['id_item'] = $value_Gift['type_item'] .'_'. $get_item->id;
                                    $data['dataItem'][$key]['dataGift'][$key_Gift]['name_item'] = $get_item->name .' - '. number_format($get_item->price_sell);
                                    $data['dataItem'][$key]['dataGift'][$key_Gift]['img_item'] = (!empty($get_item->images) && $get_item->images != '') ? 'uploads/materials/'.$get_item->images : 'uploads/no-img.jpg';
                                }
                                $data['dataItem'][$key]['dataGift'][$key_Gift]['quantity'] = number_format($value_Gift['quantity']);
                            }
                        }
                    }
                }
            }
            //loại khuyến mãi theo doanh số
            else if($data['dataMain']->type == 'sales') {
                $data['dataSub'] = get_table_where('tblpromotion_sales',array('promotion_id'=>$id),'','row');
                $data['dataSub']->type_discount = ''; //bổ xung view loại 1
                $data['dataAmount'] = array(); //bổ xung view loại 1
                $data['dataGift'] = array(); //bổ xung view loại 2
                $dataItem = get_table_where('tblpromotion_sales_item',array('promotion_id'=>$id));
                if(empty($dataItem)) {
                    $data['dataItem'] = array();
                }
                else {
                    foreach ($dataItem as $key => $value) {
                        if($value['type_item'] == 'items') {
                            $get_item = get_table_where('tblitems',array('id'=>$value['id_item']),'','row');
                            $data['dataItem'][$key]['id_item'] = $value['type_item'] .'_'. $get_item->id;
                            $data['dataItem'][$key]['name_item'] = $get_item->name .' - '. number_format($get_item->price);
                            $data['dataItem'][$key]['img_item'] = (!empty($get_item->avatar) && $get_item->avatar != '') ? $get_item->avatar : 'uploads/no-img.jpg';
                        }
                        else if($value['type_item'] == 'product') {
                            $get_item = get_table_where('tbl_products',array('id'=>$value['id_item']),'','row');
                            $data['dataItem'][$key]['id_item'] = $value['type_item'] .'_'. $get_item->id;
                            $data['dataItem'][$key]['name_item'] = $get_item->name .' - '. number_format($get_item->price_sell);
                            $data['dataItem'][$key]['img_item'] = (!empty($get_item->images) && $get_item->images != '') ? 'uploads/products/'.$get_item->images : 'uploads/no-img.jpg';
                        }
                        else if($value['type_item'] == 'nvl') {
                            $get_item = get_table_where('tbl_materials',array('id'=>$value['id_item']),'','row');
                            $data['dataItem'][$key]['id_item'] = $value['type_item'] .'_'. $get_item->id;
                            $data['dataItem'][$key]['name_item'] = $get_item->name .' - '. number_format($get_item->price_sell);
                            $data['dataItem'][$key]['img_item'] = (!empty($get_item->images) && $get_item->images != '') ? 'uploads/materials/'.$get_item->images : 'uploads/no-img.jpg';
                        } 
                    }
                }
                $dataItem_gift = get_table_where('tblpromotion_sales_item_gift',array('promotion_id'=>$id));
                if(empty($dataItem_gift)) {
                    $data['dataItem_gift'] = array();
                }
                else {
                    foreach ($dataItem_gift as $key => $value) {
                        if($value['type_item'] == 'items') {
                            $get_item = get_table_where('tblitems',array('id'=>$value['id_item']),'','row');
                            $data['dataItem_gift'][$key]['id_item'] = $value['type_item'] .'_'. $get_item->id;
                            $data['dataItem_gift'][$key]['name_item'] = $get_item->name .' - '. number_format($get_item->price);
                            $data['dataItem_gift'][$key]['img_item'] = (!empty($get_item->avatar) && $get_item->avatar != '') ? $get_item->avatar : 'uploads/no-img.jpg';
                        }
                        else if($value['type_item'] == 'product') {
                            $get_item = get_table_where('tbl_products',array('id'=>$value['id_item']),'','row');
                            $data['dataItem_gift'][$key]['id_item'] = $value['type_item'] .'_'. $get_item->id;
                            $data['dataItem_gift'][$key]['name_item'] = $get_item->name .' - '. number_format($get_item->price_sell);
                            $data['dataItem_gift'][$key]['img_item'] = (!empty($get_item->images) && $get_item->images != '') ? 'uploads/products/'.$get_item->images : 'uploads/no-img.jpg';
                        }
                        else if($value['type_item'] == 'nvl') {
                            $get_item = get_table_where('tbl_materials',array('id'=>$value['id_item']),'','row');
                            $data['dataItem_gift'][$key]['id_item'] = $value['type_item'] .'_'. $get_item->id;
                            $data['dataItem_gift'][$key]['name_item'] = $get_item->name .' - '. number_format($get_item->price_sell);
                            $data['dataItem_gift'][$key]['img_item'] = (!empty($get_item->images) && $get_item->images != '') ? 'uploads/materials/'.$get_item->images : 'uploads/no-img.jpg';
                        }
                        $data['dataItem_gift'][$key]['quantity'] = number_format($value['quantity']);
                    }
                }
            }
            $data['clients'] = get_options_search_cbo('customer', $data['dataMain']->customer_id);
        }
        
        $data['groups'] = get_table_where('tblcustomers_groups');
        $data['promotion_list'] = get_table_where('tblpromotion_list');
        $this->load->view('admin/promotion/detail', $data);
    }
    public function SearchItems()
    {
        $data = [];
        $search = $this->input->get('term');
        $type = $this->input->get('type');
        $limit_one = 15;
        $limit_two = 15;
        $limit_all = 50;

        if($type == -1)
        {
            $this->db->select('
                    concat("items_",tblitems.id) as id,
                    tblitems.name as text,
                    tblitems.price,
                    concat("items") as type,
                    tblitems.avatar as img'
            , false);
            if (!empty($search))
            {
                $this->db->group_start();
                $this->db->like('tblitems.name', $search);
                $this->db->or_like('tblitems.code', $search);
                $this->db->group_end();
            }
            $this->db->order_by('name', 'DESC');
            $this->db->limit($limit_one);
            $items = $this->db->get('tblitems')->result_array();
            if(!empty($items)) {
                $data['results'][] =
                    [
                        'text' => _l('Sản phẩm'),
                        'children' => $items
                    ];
            }
            $count_items = count($items);
            $this->db->select('
                concat("product_",tbl_products.id) as id,
                tbl_products.name as text,
                tbl_products.price_sell as price,
                concat("product") as type,
                CONCAT("uploads/products/", "", tbl_products.images, "") as img'
            , false);
            if (!empty($search))
            {
                $this->db->group_start();
                $this->db->like('tbl_products.name', $search);
                $this->db->or_like('tbl_products.code', $search);
                $this->db->group_end();
            }
            $this->db->order_by('tbl_products.name', 'DESC');
            $this->db->limit($limit_two);
            // $this->db->limit(($limit_all - $count_product));
            $product = $this->db->get('tbl_products')->result_array();
            if(!empty($product))
            {
                $data['results'][] =
                    [
                        'text' => _l('Thành phẩm'),
                        'children' => $product
                    ];
            }

            $count_product = count($product);
            $this->db->select('
                concat("nvl_",tbl_materials.id) as id,
                tbl_materials.name as text,
                tbl_materials.price_sell as price,
                concat("nvl") as type,
                CONCAT("uploads/materials/", "", tbl_materials.images, "") as img'
            , false);
            if (!empty($search))
            {
                $this->db->group_start();
                $this->db->like('tbl_materials.name', $search);
                $this->db->or_like('tbl_materials.code', $search);
                $this->db->group_end();
            }
            $this->db->order_by('tbl_materials.name', 'DESC');
            $this->db->limit(($limit_all - $count_product - $count_items));
            $product = $this->db->get('tbl_materials')->result_array();
            if(!empty($product))
            {
                $data['results'][] =
                    [
                        'text' => _l('Nguyên vật liệu'),
                        'children' => $product
                    ];
            }
        } else if($type == 'items') {
            $this->db->select('
                    id as id,
                    tblitems.name as text,
                    tblitems.price,
                    items as type,
                    tblitems.avatar as img'
            , false);
            if (!empty($search))
            {
                $this->db->group_start();
                $this->db->like('tblitems.name', $search);
                $this->db->or_like('tblitems.code', $search);
                $this->db->group_end();
            }
            $this->db->order_by('name', 'DESC');
            $this->db->limit(50);
            $items = $this->db->get('tblitems')->result_array();
            if(!empty($items)) {
                $data['results'][] =
                    [
                        'text' => _l('Sản phẩm'),
                        'children' => $items
                    ];
            }
        } else if($type == 'product') {
            $this->db->select('
                id as id,
                tbl_products.name as text,
                tbl_products.price_sell as price,
                product as type,
                CONCAT("uploads/products/", "", tbl_products.images, "") as img'
            , false);
            if (!empty($search))
            {
                $this->db->group_start();
                $this->db->like('tbl_products.name', $search);
                $this->db->or_like('tbl_products.code', $search);
                $this->db->group_end();
            }
            $this->db->order_by('tbl_products.name', 'DESC');
            $this->db->limit(50);
            // $this->db->limit(($limit_all - $count_product));
            $product = $this->db->get('tbl_products')->result_array();
            if(!empty($product))
            {
                $data['results'][] =
                    [
                        'text' => _l('Thành phẩm'),
                        'children' => $product
                    ];
            }
        } else if($type == 'nvl') {
            $this->db->select('
                id as id,
                tbl_materials.name as text,
                tbl_materials.price_sell as price,
                nvl as type,
                CONCAT("uploads/materials/", "", tbl_materials.images, "") as img'
            , false);
            if (!empty($search))
            {
                $this->db->group_start();
                $this->db->like('tbl_materials.name', $search);
                $this->db->or_like('tbl_materials.code', $search);
                $this->db->group_end();
            }
            $this->db->order_by('tbl_materials.name', 'DESC');
            $this->db->limit(50);
            $product = $this->db->get('tbl_materials')->result_array();
            if(!empty($product))
            {
                $data['results'][] =
                    [
                        'text' => _l('Nguyên vật liệu'),
                        'children' => $product
                    ];
            }
        }
        echo json_encode($data);die();
    }

    public function delete_promotion_list($id='')
    {
        $checkExists = get_table_where('tblpromotion',array('promotion_list_id'=>$id),'','row');
        if($checkExists) {
            echo json_encode(array('success' => false, 'alert_type' => 'danger', 'message' => _l('dont_delete_promotion')));
        }
        else {
            $this->db->where('id',$id);
            $this->db->delete('tblpromotion_list');

            echo json_encode(array('success' => true, 'alert_type' => 'success', 'message' => _l('ch_delete_successfuly')));
        }
    }

    public function delete_promotion($id='')
    {
        $this->db->where('id',$id);
        $this->db->delete('tblpromotion');

        $this->db->where('promotion_id',$id);
        $this->db->delete('tblpromotion_discount');

        $this->db->where('promotion_id',$id);
        $this->db->delete('tblpromotion_discount_amount');

        $this->db->where('promotion_id',$id);
        $this->db->delete('tblpromotion_discount_item');

        $this->db->where('promotion_id',$id);
        $this->db->delete('tblpromotion_item');

        $this->db->where('promotion_id',$id);
        $this->db->delete('tblpromotion_item_gift');

        $this->db->where('promotion_id',$id);
        $this->db->delete('tblpromotion_sales');

        $this->db->where('promotion_id',$id);
        $this->db->delete('tblpromotion_sales_item');

        $this->db->where('promotion_id',$id);
        $this->db->delete('tblpromotion_sales_item_gift');

        echo json_encode(array('success' => true, 'alert_type' => 'success', 'message' => _l('ch_delete_successfuly')));
    }
}