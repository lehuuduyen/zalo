<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if($status==2)
{
    $aColumns     = array(
        'name',
        'date',
        'quantity',
        'price',
        'month',
        '1',
        'note_buy',
        'price_buy',
        'user_admin_id'
    );

}
else
{
    $aColumns     = array(
        'name',
        'date',
        'quantity',
        'price',
        'month',
        '1',
        '2',
    );
}


$sIndexColumn = "id";
$sTable       = 'tblattrition';
$where        = array(
);

if($this->_instance->input->post())
{
    $date_start = date('Y-m-d');
    if($this->_instance->input->post('datestart'))
    {
        $date_start = to_sql_date($this->_instance->input->post('datestart'));
    }

    $where[]='AND date <= "'.$date_start.'"';
    $where[]='AND date_last >= "'.$date_start.'"';
//    if($this->_instance->input->post('dateend'))
//    {
//        $date_end = to_sql_date($this->_instance->input->post('dateend'));
//        $where[]='AND date_last<="'.to_sql_date($this->_instance->input->post('dateend')).'"';
//    }
}

if($status==0)
{
    $where[]='AND status='.$status;
    $where[]='AND date_last >= "'.date('Y-m-d').'"';
}
else
{
    $where[]='AND (date_last < "'.date('Y-m-d').'" or status='.$status.')';
}

$join         = array();
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array(
    'id','status','date_last'
));
$output       = $result['output'];
$rResult      = $result['rResult'];

$j=0;
$array_total['total_month']=0;
$array_total['total_money']=0;
$array_total['total_price_buy']=0;
foreach ($rResult as $aRow) {
    $row = array();
    $date = new DateTime($aRow['date']);
    $month = $aRow['month'];
    $date_last=$aRow['date_last'];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if($aColumns[$i]=='date')
        {
            $_data=_d($aRow[$aColumns[$i]]);
            if((strtotime($aRow[$aColumns[$i]])>strtotime($date_last)))
            {
                $row['DT_RowClass'] = 'alert-danger';
            }
        }
        if($aColumns[$i]=='quantity'||$aColumns[$i]=='price')
        {
            $_data=number_format_data($aRow[$aColumns[$i]]);
        }
        if($aColumns[$i]=='price_buy')
        {
            $_data=number_format_data($aRow[$aColumns[$i]]);
            $array_total['total_price_buy']+=$aRow[$aColumns[$i]];
        }
        if($aColumns[$i]=='user_admin_id')
        {
            $_data = '<a href="' . admin_url('staff/member/' . $aRow[$aColumns[$i]]) . '">' . staff_profile_image($aRow[$aColumns[$i]], array(
                    'staff-profile-image-small'
                )) . '</a>';
            $_data .= ' <a href="' . admin_url('staff/member/' . $aRow[$aColumns[$i]]) . '">' . get_staff_full_name($aRow[$aColumns[$i]]). '</a>';

        }
        if($aColumns[$i]=='note_buy')
        {
            $_data="";
            if(!empty($aRow[$aColumns[$i]])) {
                $_data = '<p data-toggle="tooltip" data-original-title="' . $aRow[$aColumns[$i]] . '">' . mb_substr($aRow[$aColumns[$i]], 0, 20, "utf-8") . '<i class="fa fa-hand-o-right" aria-hidden="true"></i>' . '</p><a class="hide">' . mb_substr($aRow[$aColumns[$i]], 20, strlen($aRow[$aColumns[$i]]), "utf-8") . '</a>';
            }
        }
        if($aColumns[$i]=='1')
        {
            $_data=number_format_data(round(($aRow['price']/$aRow['month'])*$aRow['quantity']));
            $array_total['total_month']+=round(($aRow['price']/$aRow['month'])*$aRow['quantity']);
        }
        if($aColumns[$i]=='2')
        {
            $date = new DateTime($aRow['date']);
            $month = $aRow['month'];
            $date_last = $aRow['date_last'];
            $days = (strtotime($date_last) - strtotime($aRow['date'])) / (60 * 60 * 24);
            $_data=$days;

            $money_day = ($aRow['price'] * $aRow['quantity'])/$days;

//            if(strtotime($date_last) >= strtotime($date_start))
//            {
                $day_attrition = (strtotime($date_last) - strtotime($date_start)) / (60 * 60 * 24);
//            }
//            else
//            {
//                $day_attrition = (strtotime($date_last) - strtotime(date('Y-m-d'))) / (60 * 60 * 24);
//            }

            $_data = number_format_data(round($money_day * $day_attrition));
            $array_total['total_money']+=$money_day*$day_attrition;
        }
        $row[] = $_data;
    }
    $_data="";
//    $_data = '<a target="_blank" href="'.admin_url('reports_revenue/detail_pdf/'.$aRow['id'].'?print=true').'" class="btn btn-default btn-icon" title="Tạo phiếu in"><i class="fa fa-print"></i></a>';
    if($aRow['status']==0&&strtotime(date('Y-m-d'))<strtotime($aRow['date_last']))
    {
        $_data.= '<a href="#" class="btn btn-default btn-icon" title="Tạo phiếu bán tài sản" onclick="modal_status(' . $aRow['id'] . '); return false;"><i class="fa fa-reply-all"></i></a>';
    }
    if($aRow['status']==2)
    {
        $_data.= '<a href="#" class="btn btn-default btn-icon" title="Sửa nội dung cho phiếu xuất" onclick="modal_status(' . $aRow['id'] . ',\'edit\'); return false;"><i class="fa fa-edit"></i></a>';
    }
    $_data .= '<a href="#" class="btn btn-default btn-icon" title="Sửa thông tin tài sản" onclick="add_attrition(' . $aRow['id'] . '); return false;"><i class="fa fa-eye"></i></a>';
    $row[] = $_data.icon_btn('reports_revenue/delete_attrition/'. $aRow['id'] , 'remove', 'btn-danger delete-reminder',array('title'=>'Xóa tài sản cố định'));

    $output['aaData'][] = $row;
}
foreach ($array_total as $key=>$value)
{
    $array_total[$key]=number_format_data($value);
}
$output['attrition']=$array_total;
