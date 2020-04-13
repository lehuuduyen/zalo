<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cash_book extends AdminController
{
    function __construct()
    {
        parent::__construct();

        $this->load->model('cash_book_model');
    }

    public function get_cash_clone($id)
    {


      $this->db->select('*');
      $this->db->select('tblcash_book.*,tblgroup_cash_book.id,tblgroup_cash_book.name as group_name,tblinvoicepaymentsmodes.name as pay_name,tblinvoicepaymentsmodes.id');
      $this->db->from('tblcash_book');
      $this->db->where('tblcash_book.id', $id);
      $this->db->join('tblgroup_cash_book', 'tblgroup_cash_book.id = tblcash_book.groups');
      $this->db->join('tblinvoicepaymentsmodes', 'tblinvoicepaymentsmodes.id = tblcash_book.payment_mode_id');
      $result = $this->db->get()->row();





      $cash_book_date = strtotime($result->date);
      $result->date = _d(date("Y-m-d", $cash_book_date));
      return $result;
    }

    public function pdf_cash($id)
    {
      $receipts = $this->get_cash_clone($id);

      if (!empty($_GET)) {

        if ($_GET['print']) {
          $data['print']  = true;
        }
      }

      if ($receipts->id_object) {
        $data['receipts'] = $receipts;
        $this->db->select('*');
        $this->db->from($receipts->id_object);
        if ($receipts->id_object == 'tblstaff') {
          $this->db->where('staffid', $receipts->staff_id);
        }else {
          $this->db->where('id', $receipts->staff_id);
        }

        $object = $this->db->get()->row();
      }else {
        $object->name = $receipts->staff_id;
      }

      $receipts->object = $object;


      $this->load->view('admin/cash_book/print-out', $data);

    }


    public function SearchDelivery_orders($data , $com) {
      $total = 0;
      foreach ($data as $key => $value) {

        if ($value->code_delivery == $com) {
          $total = $total + (int)$value->collect_report;
        }
      }
      return $total;
    }


    public function list_shiper_group_8()
    {
        $this->db->select('deliver, code_delivery, date_create, money_update as collect_report');
        $this->db->where('money_update !=', "0");
        $this->db->where('status', "Đã Báo Cáo");
        $this->db->where('(created_bill is null or created_bill = 0)');
        $this->db->order_by('date_create', "DESC");
        $this->db->limit(100);
        $data = $this->db->get('tbldelivery_list')->result();
        echo json_encode($data);
    }



    public function index()
    {
        if (!has_permission('cash_book', '', 'view')) {
            access_denied('cash_book');
        }
        if ($this->input->is_ajax_request()) {
            $array = array();
            if($this->input->get('thang'))
            {
                $array = array('is_admin' => true);
            }
            $this->app->get_table_data('cash_book',$array);
        }
        $data['payment_modes_model'] = get_table_where('tblpayment_modes');
        $data['group_cash_book'] = get_table_where('tblgroup_cash_book');
        $data['payments_modes'] = get_table_where('tblinvoicepaymentsmodes');
        $data['staff'] = get_table_where('tblstaff');
        $data['date_end']=date('Y-m-d');
        $date = new DateTime($data['date_end']);;
        date_sub($date, date_interval_create_from_date_string('30 days'));
        $data['date_start'] = date_format($date, 'Y-m-d');
        $data['title'] = _l('Quỹ nợ');
        if($this->input->get('thang'))
        {
            $data['is_admin']=true;
        }
        $this->load->view('admin/cash_book/manage', $data);
    }
    public function list_cask_book($customer="")
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('cash_book_customer',array('customer'=>$customer, 'is_admin' => $this->input->get('is_admin')));
        }
    }


    public function order_detail($id='')
    {

        if ($this->input->post()) {
            $data=$this->input->post();
            $staus_id = !empty($data['staus_id']) ? $data['staus_id'] : [];
            unset($data['staus_id']);
            if ($id == '')
            {
                if(!has_permission('cash_book','','create'))
                {
                    echo json_encode(array('alert_type'=>'danger','message'=>'Không có quyền tạo quỹ'));die();
                }
                if($data['id_object'])
                {
                    unset($data['staff_id_not']);
                }
                else
                {
                    $data['staff_id']=$data['staff_id_not'];
                    unset($data['staff_id_not']);
                }
                if($data['type']==1)
                {
                    if($this->get_price_limit($data['payment_mode_id']) < number_unformat($data['price']))
                    {
                        echo json_encode(array('alert_type'=>'danger','message'=>'Tài khoản không đủ để tạo phiếu chi'));die();
                    }
                }
                if (isset($data['get_money_shiper_total'])) {
                  unset($data['get_money_shiper_total']);
                }
                if ( $data['groups']==8 && $data['type']==0 ) {
                  $change_status_id = explode(",", $staus_id);
                  $data_update_batch = array();
                  foreach ($change_status_id as $key => $value) {
                    $data_add_push['created_bill'] = 1;
                    $data_add_push['id'] = $value;
                    $data_update_batch[$key] = $data_add_push;
                  }

                  $update_batch = $this->db->update_batch('tbldelivery_list',$data_update_batch,'id');

                }
                $id = $this->cash_book_model->add($data);

                if($data['groups']==10&&$data['payment_mode_id']!=7&&$data['type']==1)
                {
                    $get_pay=get_table_where('tblinvoicepaymentsmodes',array('id'=>7,'selected_by_default'=>1),'','row');
                    if(!empty($get_pay))
                    {
                        $data['payment_mode_id']=7;
                        $_id_two=$this->cash_book_model->add($data);
                    }
                }

                if ($id) {
                   echo json_encode(array('alert_type'=>'success','add'=>$id,'message'=>'Thêm phiếu thành công'));die();
                }
                echo json_encode(array('alert_type'=>'danger','message'=>'Thêm phiếu không thành công'));die();
            }
            else
            {
                if(!has_permission('cash_book','','edit'))
                {
                    echo json_encode(array('alert_type'=>'danger','message'=>'Không có quyền cập nhật quỹ'));die();
                }
                if($data['id_object'])
                {
                    unset($data['staff_id_not']);
                }
                else
                {
                    $data['staff_id']=$data['staff_id_not'];
                    unset($data['staff_id_not']);
                }
                if($data['type']==1)
                {
                    $kt=get_table_where('tblcash_book',array('id'=>$id),'','row');
                    if($kt->type==0)
                    {
                        $price_old=$kt->price+number_unformat($data['price']);
                    }
                    else
                    {
                        $price_old=number_unformat($data['price'])-$kt->price;
                    }
                    if($this->get_price_limit($data['payment_mode_id'])<$price_old)
                    {
                        echo json_encode(array('alert_type'=>'danger','message'=>'Tài khoản không đủ để cập nhật phiếu chi'));die();
                    }
                }
                $success = $this->cash_book_model->update($data, $id);


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

    public function get_price_limit($payment_mode_id=NULL)
    {
        if(empty($payment_mode_id))
        {
            $payment_mode_id=$this->input->post('payment_mode_id');
        }
        if($payment_mode_id)
        {
            $payment_mode_id=$this->input->post('payment_mode_id');
            $price_old=getprice_cash_book(array('payment_mode_id'=>$payment_mode_id));
            $debit=get_table_where('tblinvoicepaymentsmodes',array('id'=>$payment_mode_id),'','row');
            if($debit)
            {
                $price_old+=$debit->opening_balance;
            }
            return $price_old;
        }
    }
    public function order_ward($id='')
    {

        if ($this->input->post()) {
            $data=$this->input->post();
            if ($id == '') {
                if(!has_permission('cash_book','','create'))
                {
                    echo json_encode(array('alert_type'=>'danger','message'=>'Không có quyền tạo quỹ'));die();
                }
                if($data['id_object_war'])
                {
                    $data['staff_id']=$data['staff_id_to'];
                    unset($data['staff_id_to_not']);
                }
                else
                {
                    $data['staff_id']=$data['staff_id_to_not'];
                    unset($data['staff_id_to_not']);
                }
                $item_to=array(
                    'type'=>'1',
                    'note'=>$this->input->post('note_war',false),
                    'price'=>$data['price_war'],
                    'groups'=>'11',
                    'id_object'=>$data['id_object_war'],
                    'staff_id'=>$data['staff_id'],
                    'date'=>$data['date_ward'],
                    'payment_mode_id'=>$data['payment_mode_to']
                );
                $item_from=array(
                    'type'=>'0',
                    'note'=>$this->input->post('note_war',false),
                    'price'=>$data['price_war'],
                    'groups'=>'11',
                    'id_object'=>$data['id_object_war'],
                    'staff_id'=>$data['staff_id'],
                    'date'=>$data['date_ward'],
                    'payment_mode_id'=>$data['payment_mode_from']
                );
                $id_to = $this->cash_book_model->add($item_to);
                $id_from = $this->cash_book_model->add($item_from);
                if ($id_to&&$id_from) {
                   echo json_encode(array('alert_type'=>'success','add_from'=>$id_from,'add_to'=>$id_to,'message'=>'Thêm phiếu thành công'));die();
                }
                echo json_encode(array('alert_type'=>'danger','message'=>'Thêm phiếu không thành công'));die();
            }
        }
    }
    public function get_cash()
    {
        if($this->input->post('id'))
        {

            $id=$this->input->post('id');
            $is_admin=$this->input->post('thang');
            $this->db->where('id', $id);
            $result = $this->db->get('tblcash_book')->row();
            if(strtotime(to_sql_date(_dt($result->date), true)) < strtotime(date('Y-m-d')) && !$is_admin)
            {
                $result->not_edit = true;
            }
            else
            {
                $result->not_edit = false;
            }
            $cash_book_date = strtotime($result->date);
            $cash_book_date_control = strtotime($result->date_control);
            $result->date = _d(date("Y-m-d", $cash_book_date));
            $result->date_control = _d(date("Y-m-d", $cash_book_date_control));
            echo json_encode($result);die();
        }
    }

    public function get_cash_code($type=NULL,$payment_id=NULL)
    {
        $code="";
        if(!is_numeric($type)&&empty($payment_id))
        {
            $type=$this->input->post('type');
            $payment_id=$this->input->post('payment_id');
        }
        if(is_numeric($type)&&!empty($payment_id))
        {
            $payment=get_table_where('tblinvoicepaymentsmodes',array('id'=>$payment_id),'','row');
            if(!empty($payment))
            {
                $number = getMaxIDCODE('count_code','tblcash_book',array('type'=>$type,'payment_mode_id'=>$payment_id))+1;
                if(empty($number))
                {
                    $number=1;
                }
                $code=$number;
                if($type==0)
                {
                    $code='PT-'.$payment->code.'-'.$code;
                }
                else
                {
                    $code='PC-'.$payment->code.'-'.$code;
                }
            }
        }
        echo json_encode(array('code'=>$code));
    }

    public function pdf($id="")
    {
        $receipts = $this->receipts_model->get_data_pdf($id);
        if ($this->input->get('combo')) {
            $receipts->combo=$this->input->get('combo');
        }
        $pdf      = receipts_pdf($receipts);

        $type     = 'D';
        if ($this->input->get('print')) {
            $type = 'I';
        }

        $pdf->Output(slug_it($receipts->code_vouchers) . '.pdf', $type);
    }
    public function inventory_money_pdf($id="")
    {
        $data = $this->input->get();
        $pdf      = inventory_money_pdf($data);

        $type     = 'D';
        if ($this->input->get('print')) {
            $type = 'I';
        }

        $pdf->Output(slug_it('Phieu_kiem_ke_tien_mat') . '.pdf', $type);
    }

    public function delete($id)
    {
        if(is_numeric($id))
        {
            if(!has_permission('cash_book','','delete'))
            {
                $message=_l('Không có quyền xóa quỹ');
                $alert_type='danger';
                $result=false;
                set_alert('danger', _l('Không có quyền xóa quỹ'));
            }
            else
            {
                $result=$this->cash_book_model->delete($id);
                if($result)
                {
                    $alert_type = 'success';
                    $message=_l('Xóa dữ liệu thành công');
                    set_alert('success', _l('Xóa dữ liệu thành công'));
                }
                else
                {
                    $alert_type = 'danger';
                    $message=_l('Xóa dữ liệu không thành công');
                    set_alert('danger', _l('Xóa dữ liệu không thành công'));

                }
            }
            if($this->input->is_ajax_request())
            {
                echo json_encode(array('success'=>$result,'alert_type'=>$alert_type,'message'=>$message));die;
            }
            redirect(admin_url('cash_book'));
        }

    }
    public function get_object()
    {
        $table=$this->input->post('id');
        if($table)
        {
            if($table=='tblstaff')
            {
                $this->db->select('staffid as id,concat(lastname," ", firstname) as name');
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
    public function get_information($id_contract=NULL)
    {
        $id=$this->input->post('id');
        if(is_numeric($id))
        {
            $this->db->where('id_other_object',$id);
            $this->db->where('status!=',1);
            if($id_contract)
            {
                $this->db->or_where('id',$id_contract);
            }
            $result=$this->db->get('tblcontract_borrowing')->result_array();
            foreach($result as $key=>$value)
            {
                $result[$key]['name']=_d($value['date']).' - '.number_format_data($value['money']).' - '.$value['rate'];
            }
            echo json_encode($result);die();
        }
    }

    public function kt_date_now()
    {
        $date = $this->input->post('date');
        if (strtotime(to_sql_date($date, true)) < strtotime(date('Y-m-d'))) {
            echo json_encode(false);
            die();
        }
        echo json_encode(true);
        die();
    }



    public function get_job()
    {

        $data['ct']=$this->input->post('ct');
        $data_array=explode ( '+' , $data['ct'] );
        $total=0;
        foreach ($data_array as $key=>$value)
        {
            $data_array_tru=explode( '-' , $value);
            if($data_array_tru!=array())
            {
                foreach ($data_array_tru as $k=>$v)
                {
                    if($k>0)
                    {
                        $total-=trim($v);
                    }
                    else
                    {
                        if(is_numeric($v))
                        {
                            $total+=trim($v);
                        }
                    }
//                    var_dump($v);
//                    var_dump($total);
                }
            }
            else
            {
                $total+=trim($value);
            }
        }
        echo $total;
    }

    public function update_status()
    {
        $id=$this->input->post('id');
        if(is_numeric($id))
        {
            $cash=get_table_where('tblcash_book',array('id'=>$id),'','row');
            if(!empty($cash))
            {
                if($cash->status==0)
                {
                    $status=2;
                }
                else
                {
                    $status=0;
                }
                $this->db->where('id',$id);
                if($this->db->update('tblcash_book',array('status'=>$status)))
                {
                    if($status==2)
                    {
                        echo json_encode(array(
                            'success' => true,
                            'message' => _l('Xác nhận phiếu yêu cầu thành công')
                        ));die();
                    }
                    else
                    {
                        echo json_encode(array(
                            'success' => true,
                            'message' => _l('Hủy xác nhận phiếu yêu cầu thành công')
                        ));die();
                    }
                }
            }
        }
        echo json_encode(array(
            'success' => false,
            'message' => _l('Xác nhận phiếu yêu cầu không thành công')
        ));die();
    }


    public function get_bill_not_pay()
    {
        $id=$this->input->post('id');
        $client=$this->input->post('client');

        if(!empty($id))
        {
            $this->db->where('id',$id);
            $cash_book_bill=$this->db->get('tblcash_book')->row();
        }
        $this->db->select('tblbill.*');
        $this->db->join('tblexports','tblexports.id=tblbill.id_export');
        $this->db->where('tblbill.client',$client);
        $this->db->where('tblexports.status',2);
        $this->db->order_by('tblbill.date','desc');
        $this->db->limit(4,0);
        $bill=$this->db->get('tblbill')->result_array();

        if(!empty($cash_book_bill->id_bill))
        {
            $this->db->or_where('tblbill.id',$cash_book_bill->id_bill);
            $bill_add=$this->db->get('tblbill')->result_array();
            $max_bill=count($bill);
            $kt=0;
            foreach($bill as $key=>$value)
            {
                if($value['id']==$bill_add[0]['id']){
                    $kt==1;
                    break;
                }
            }
            if($kt==0)
            {
                $bill[$max_bill+1]=$bill_add[0];
            }

        }

        foreach($bill as $key=>$value)
        {
//            $this->db->where('id_bill',$value['id']);
//            if(!empty($id))
//            {
//                $this->db->where('id!=',$id);
//            }
//            $cash_book=$this->db->get('tblcash_book')->row();
//            if(!empty($cash_book))
//            {
//                unset($bill[$key]);
//                continue;
//            }
            $bill[$key]['name']=$value['code'].' ('._d($value['date']).')';

            $total_go=$value['total_bill']+$value['old_debt'];
            $incurred=get_table_where('tblbill_incurred',array('id_bill'=>$value['id'],'type'=>2));
            foreach($incurred as $k=>$v)
            {
                $total_go+=$v['price'];
            }
            $bill[$key]['total_go']=$total_go;
        }
        echo json_encode($bill);
    }


    public function test()
    {
        if (!has_permission('cash_book', '', 'view')) {
            access_denied('cash_book');
        }
        $data['title'] = "";
        if ($this->input->is_ajax_request()) {
//            $this->app->get_table_data('other_object');
            $this->app->get_table_data('cash_book');
        }
        $this->load->view('admin/cash_book/test', $data);
    }
}
