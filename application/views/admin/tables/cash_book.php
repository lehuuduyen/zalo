<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$plan_status=array(
    "2"=>"Đơn đặt hàng",
    "1"=>"Đơn đặt hàng được xác nhận chọn để duyệt đơn đặt hàng",
    "0"=>"Đơn đặt hàng chưa được xác nhận chọn để xác nhận"
);

$where = array();
$total_befor=0;
if($this->_instance->input->post()&&$this->_instance->input->post('date_start')) {
    $filter_status = $this->_instance->input->post('filterStatus');
    if(is_numeric($filter_status)) {
        array_push($where, 'AND tblcash_book.payment_mode_id='.$filter_status);
    }

    if(!$this->_instance->input->post('filterStatus'))
    {
        $pay_mod=get_table_where('tblinvoicepaymentsmodes',array('selected_by_default'=>1));
        foreach($pay_mod as $k=>$value)
        {
            array_push($where, 'AND tblcash_book.payment_mode_id!='.$value['id']);
        }
    }
    if($this->_instance->input->post('date_start')&&$this->_instance->input->post('date_end')) {
        $where[]='AND DATE_FORMAT(tblcash_book.date, "%Y-%m-%d") < "'.to_sql_date($this->_instance->input->post('date_start'), true).'"';
    }
    if($this->_instance->input->post('object_search')) {
        if($this->_instance->input->post('object_search')=='null')
        {
            $where[]='AND tblcash_book.id_object=""';
        }
        else
        {
            $where[]='AND tblcash_book.id_object="'.$this->_instance->input->post('object_search').'"';
        }
    }

    if($this->_instance->input->post('staff_id_search')) {
        $where[]='AND tblcash_book.staff_id = "'.$this->_instance->input->post('staff_id_search').'"';
    }
    if($this->_instance->input->post('groups_search')) {
        $where[]='AND tblcash_book.groups='.$this->_instance->input->post('groups_search');
    }
    if($this->_instance->input->post('type_search')!=NULL) {
        $where[]='AND tblcash_book.type='.$this->_instance->input->post('type_search');
    }
    $total_befor = getprice_cash_book(trim(implode($where,' '),'AND'));
}




$aColumns     = array(
    'tblcash_book.id',
    'date',
    '1',
    '2',
    'note',
    '3',
    '4',
    '5',
    'staff_id',
    'groups',
    'tblinvoicepaymentsmodes.name',
    'create_by',
//    '0'

);

$total_cash = 0;
$sIndexColumn = "id";
$sTable       = 'tblcash_book';
$where        = array();

//$total_cash = get_option('cash_book');

//$_POST['start']= 100;
//$_POST['end']= 100;


if($this->_instance->input->post()) {
    $filter_status = $this->_instance->input->post('filterStatus');
    if(is_numeric($filter_status)) {
        array_push($where, 'AND tblcash_book.payment_mode_id='.$filter_status);
    }
}
if(!$this->_instance->input->post('filterStatus'))
{
    $pay_mod=get_table_where('tblinvoicepaymentsmodes',array('selected_by_default'=>1));
    foreach($pay_mod as $k=>$value)
    {
        array_push($where, 'AND tblcash_book.payment_mode_id!='.$value['id']);
    }
}
if($this->_instance->input->post('date_start')&&$this->_instance->input->post('date_end')) {
    $where[] = 'AND DATE_FORMAT(tblcash_book.date, "%Y-%m-%d") >= "'.to_sql_date($this->_instance->input->post('date_start'), true).'"';
    $where[] = 'AND DATE_FORMAT(tblcash_book.date, "%Y-%m-%d") <= "'.to_sql_date($this->_instance->input->post('date_end'), true).'"';
}
if($this->_instance->input->post('object_search')) {
    if($this->_instance->input->post('object_search')=='null')
    {
        $where[]='AND tblcash_book.id_object=""';
    }
    else
    {
        $where[]='AND tblcash_book.id_object="'.$this->_instance->input->post('object_search').'"';
    }
}
if($this->_instance->input->post('staff_id_search')) {
    $where[]='AND tblcash_book.staff_id="'.$this->_instance->input->post('staff_id_search').'"';
}
if($this->_instance->input->post('groups_search')) {
    $where[]='AND tblcash_book.groups='.$this->_instance->input->post('groups_search');
}
if($this->_instance->input->post('type_search')!=NULL) {
    $where[]='AND tblcash_book.type='.$this->_instance->input->post('type_search');
}
$join         = array(
    'LEFT JOIN tblgroup_cash_book  ON  tblgroup_cash_book.id=tblcash_book.groups',
    'LEFT JOIN tblinvoicepaymentsmodes  ON  tblinvoicepaymentsmodes.id=tblcash_book.payment_mode_id'
);


$result   = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array(
    'tblcash_book.type',
    'tblcash_book.price',
    'tblcash_book.code',
    'id_object',
    'tblcash_book.status',
    'tblcash_book.count_code',
    'tblgroup_cash_book.name as name_group'
));
$output       = $result['output'];
$rResult      = $result['rResult'];
$total_opening_balance=$total_befor;
$j=0;

if($filter_status)
{
    $pay = get_table_where('tblinvoicepaymentsmodes', array('id' => $filter_status));
}
else
{
    $pay = get_table_where('tblinvoicepaymentsmodes', array('selected_by_default' => 0));
}
if($pay!=array())
{
    foreach ($pay as $k=>$v)
    {
        $total_opening_balance+=$v['opening_balance'];
    }

}


$start=$this->_instance->input->post('start');
$length=$this->_instance->input->post('length');
$order=$this->_instance->input->post('order');
foreach ($rResult as $aRow) {
    $row = array();
    $j++;
    for ($i = 0; $i < count($aColumns); $i++)
    {
        $_data = $aRow[$aColumns[$i]];
//        if ($aColumns[$i] == 'tblcash_book.id') {
//            $_data = $j;
//        }
        if ($aColumns[$i] == '1') {
            $_data = "";
            if($aRow['type']==0)
            {
                if($aRow['count_code']==0)
                {
                    $_data='<a target="_blank" href="'.admin_url('staff/redirect/cash_book/add_cash_book-_-'.$aRow['tblcash_book.id'].'-__-').'">'.($aRow['code']?'PT-':'').$aRow['code'].'</a>';
                }
                else
                {
                    $_data='<a target="_blank" href="'.admin_url('staff/redirect/cash_book/add_cash_book-_-'.$aRow['tblcash_book.id'].'-__-').'">'.$aRow['code'].'</a>';
                }
            }
        }
        if ($aColumns[$i] == '2') {
            $_data = "";
            if($aRow['type']==1)
            {
                if($aRow['count_code']==0)
                {
                    $_data='<a target="_blank" href="'.admin_url('staff/redirect/cash_book/add_cash_book-_-'.$aRow['tblcash_book.id'].'-__-').'">'.($aRow['code']?'PC-':'').$aRow['code'].'</a>';
                }
                else
                {
                    $_data='<a target="_blank" href="'.admin_url('staff/redirect/cash_book/add_cash_book-_-'.$aRow['tblcash_book.id'].'-__-').'">'.$aRow['code'].'</a>';
                }
            }
        }
        if ($aColumns[$i] == '3') {
            $_data = "0";
            if($aRow['type']==0)
            {
                $_data=number_format_data($aRow['price']);
            }
        }
        if($aColumns[$i]=='note')
        {
            if(strlen($aRow[$aColumns[$i]])>70)
            {
                $_data ='<p data-toggle="tooltip" data-original-title="'.$aRow[$aColumns[$i]].'">'.mb_substr($aRow[$aColumns[$i]], 0, 70, "utf-8").'<i class="fa fa-hand-o-right" aria-hidden="true"></i>'.'</p><a class="hide">'.$aRow[$aColumns[$i]].'</a>';
            }
        }
        if ($aColumns[$i] == '4') {
            $_data = "0";
            if($aRow['type']==1)
            {
                $_data=number_format_data($aRow['price']);
            }
        }
        if ($aColumns[$i] == '5') {
            if(!empty($aRow['price']) && is_numeric($aRow['price']))
            {
                if($aRow['type']==0)
                {
                    $total_cash += $aRow['price'];
                }
                else
                {
                    $total_cash -= $aRow['price'];
                }
            }
            $_data = number_format_data($total_cash);
        }
        if ($aColumns[$i] == 'payment_mode_id') {
            $_data=($aRow['payment_mode_id']);
        }
        if ($aColumns[$i] == 'date') {
            $_data = _dC($aRow['date']);
        }
        if ($aColumns[$i] == 'groups') {
            if(strlen($aRow['name_group'])>20)
            {
                $_data ='<p data-toggle="tooltip" data-original-title="'.$aRow['name_group'].'">'.mb_substr($aRow['name_group'],0,20, "utf-8").'...'.'</p>';
            }
            else
            {
                $_data=$aRow['name_group'];
            }
        }
        if($aColumns[$i]=='create_by')
        {
            $_data = '<a href="' . admin_url('staff/member/' . $aRow[$aColumns[$i]]) . '">' . staff_profile_image($aRow[$aColumns[$i]], array(
                    'staff-profile-image-small'
                )) . '</a>';
            $_data .= ' <a href="' . admin_url('staff/member/' . $aRow[$aColumns[$i]]) . '">' . get_staff_firstname($aRow[$aColumns[$i]]). '</a>';
        }
        if($aColumns[$i]=='staff_id')
        {
            $array_object=array('tblstaff'=>'NV',
                                   'tblcustomers'=>'KH',
                                   'tblsuppliers'=>'NCC',
                                   'tblracks'=>'LX',
                                   'tblporters'=>'BV',
                                   'tblother_object'=>'VM',
                                   ''=>'Khác'
                                );
            $_data = $array_object[$aRow['id_object']].': ';
            if($aRow['id_object']=='tblstaff')
            {
                if(is_numeric($aRow[$aColumns[$i]]))
                {
                    // $_data .= '<a href="' . admin_url('staff/member/' . $aRow[$aColumns[$i]]) . '">' . staff_profile_image($aRow[$aColumns[$i]], array(
                    //         'staff-profile-image-small'
                    //     )) . '</a>';
                    //
                    $staff = get_table_where('tblstaff',array('staffid'=>$aRow[$aColumns[$i]]),'','row');
                    // $_data .= ' <a href="' . admin_url('staff/member/' . $aRow[$aColumns[$i]]) . '">'.(!empty($staff->firstname) ? $staff->firstname : '').'</a>';
                    $_data.= $staff->firstname;
                }
            }
            if($aRow['id_object']=='tblcustomers'||$aRow['id_object']=='tblsuppliers')
            {
                $info_data = get_table_where($aRow['id_object'], array('id' => $aRow[$aColumns[$i]]),'','row');
                $_data .= !empty($info_data->customer_shop_code) ? $info_data->customer_shop_code : '';

            }
            if($aRow['id_object']=='tblracks')
            {
                $racks=get_table_where($aRow['id_object'],array('rackid'=>$aRow[$aColumns[$i]]),'','row');
                $_data .= !empty($racks->rack) ? $racks->rack : '';
            }
            if($aRow['id_object']=='tblporters')
            {
                $porters=get_table_where($aRow['id_object'],array('id'=>$aRow[$aColumns[$i]]),'','row');
                $_data .= !empty($porters->name) ? $porters->name : '';
            }
            if($aRow['id_object']=='tblother_object')
            {
                $other_object=get_table_where($aRow['id_object'],array('id'=>$aRow[$aColumns[$i]]),'','row');
                $_data .= !empty($other_object->name) ? $other_object->name : '';
            }
            if($aRow['id_object']=='')
            {
                $_data.=$aRow[$aColumns[$i]];
            }
            if(!$aRow[$aColumns[$i]])
            {
                $_data="";
            }
        }
        if($aColumns[$i]=='0')
        {
            $_data='<div class="checkbox">
                        <input value="1" class="check_1" type="checkbox" '.($aRow['status']==2?'checked':'').' '.(!empty($is_admin)?'onclick="update_status('.$aRow['tblcash_book.id'].')"':'').'>
                        <label></label>
                    </div>';
            if($aRow['status']!=2)
            {
                $row['DT_RowClass'] = 'bg-info';
            }
        }

        $row[] = $_data;
    }
    $_data="";
  
    $_data='<div class="dropdown" style="position: absolute;margin-left: 30px;margin-top: -19px;">
                    <a class="dropdown-toggle btn btn-default btn-icon" data-toggle="dropdown"><i class="fa fa-print"></i></a>
                    <ul class="dropdown-menu">
                      <li class="dropdown-header">LIÊN</li>
                      <li><a href="'.admin_url().'cash_book/pdf_cash/' . $aRow['tblcash_book.id'].'?print=true&object='. $row[8] .'" target="_blank">Liên 1</a></li>
                      <li><a href="'.admin_url().'cash_book/pdf_cash/' . $aRow['tblcash_book.id'].'?print=true&combo=2&object='. $row[8] .'" target="_blank">Liên 2</a></li>

                    </ul>
                 </div>';

    if((strtotime($aRow['date']) - strtotime(date('Y-m-d')) > 0) || !empty($is_admin))
    {
        $_data.='<a style="cursor: pointer;" class="btn btn-default btn-icon" onclick="add_cash_book('.$aRow['tblcash_book.id'].',\'\',this)" data-toggle="tooltip" title="Sửa " data-placement="top"><i class="fa fa-edit"></i></a>';
    }

    if((strtotime($aRow['date']) - strtotime(date('Y-m-d')) > 0) || !empty($is_admin))
    {
        $_data .= icon_btn('cash_book/delete/' . $aRow['tblcash_book.id'], 'remove', 'btn-danger _delete-remind mleft30', array(
            'data-toggle' => 'tooltip',
            'title' => _l('delete'),
            'data-placement' => 'top'
        ));
    }
    $row[] = $_data;
    $output['aaData'][] = $row;

}

$payment_mode=get_table_where('tblinvoicepaymentsmodes');
$total_open=0;
$array_not_id='';
foreach ($payment_mode as $k=>$v)
{
    $pay = get_table_where('tblinvoicepaymentsmodes',array('id'=>$v['id']),'','row');
    $output['total_payment_'.$v['id']] = number_format_data(getprice_cash_book(['payment_mode_id' => $v['id']]) + $pay->opening_balance);
    if($pay->selected_by_default == 0)
    {
        $total_open+= $pay->opening_balance;
    }
    else
    {
        $array_not_id.=' and payment_mode_id!='.$pay->id;
    }
}

$output['sum'] = number_format_data(getprice_cash_book(trim($array_not_id,'and '))+$total_open);
//$output['sum'] = 1;
$_detail_price = $total_opening_balance;
//$_detail_price=0;
if($output['iTotalRecords']>$start+$length)
{
    if($length>0)
    {
        $this->_instance->db->select('tblcash_book.*');
        foreach ($order as $k=>$v)
        {
            $this->_instance->db->order_by($aColumns[$order[$k]['column']].' '.$order[$k]['dir']);
        }
        $num=($start+$length);
        $total_records=$output['iTotalRecords']-$num;
        if($output['iTotalRecords']-$num<0)
        {
            $total_records=($output['iTotalRecords']-$num)*(-1);
        }
        $this->_instance->db->limit($total_records,$num);
        if($where!=array())
        {
            $this->_instance->db->where(trim(implode($where,' '),'AND'));
        }
        $_result=$this->_instance->db->get('tblcash_book')->result();

        foreach ($_result as $k=>$v)
        {
            if($v->type==0)
            {
                $_detail_price=$_detail_price+$v->price;
            }
            else
            {
                $_detail_price=$_detail_price-$v->price;
            }
        }
    }
}
for($key = count($output['aaData']);$key >= 0;$key --)
{
    if(!empty($output['aaData'][$key][5]) && $output['aaData'][$key][5] != 0)
    {
        $_detail_price+=number_format_data($output['aaData'][$key][5],false);
        $output['aaData'][$key][7]=number_format_data($_detail_price);
    }
    if(!empty($output['aaData'][$key][6]) && $output['aaData'][$key][6]!=0)
    {
        $_detail_price-=number_format_data($output['aaData'][$key][6],false);
        $output['aaData'][$key][7]=number_format_data($_detail_price);
    }
}
