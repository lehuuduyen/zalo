<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$format_status_export=array(
    0=>'<span class="inline-block warning">Đang hoạt động</span>',
    2=>'<span class="inline-block light-green">Dừng hoạt động</span>'
);

$plan_status=array(
    0=>'Đang hoạt động',
    2=>'Dừng hoạt động'
);
$light_green=array(
    0=>'danger',
    2=>'light-green'
);
$aColumns     = array(
    'id',
    'type_notification',
    'date_start',
    'date_next',
    'date_last',
    'note',
    'status',
    'create_by'
);

$total_cash = 0;
$sIndexColumn = "id";
$sTable       = 'tblnotification_company';
$where        = array();

if($this->_instance->input->post('filter_type_notification')) {
    $where[]="AND tblnotification_company.type_notification='".$this->_instance->input->post('filter_type_notification')."'";

}
if(is_numeric($this->_instance->input->post('filterStatus'))) {
    if($this->_instance->input->post('filterStatus')==2)
    {
        $where[]="AND tblnotification_company.status='".$this->_instance->input->post('filterStatus')."'";
    }
    else
    {
        $where[]="AND tblnotification_company.status='".$this->_instance->input->post('filterStatus')."'";
    }
}
$join         = array();


$result   = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array());
$output       = $result['output'];
$rResult      = $result['rResult'];
$start=$this->_instance->input->post('start');
$length=$this->_instance->input->post('length');
$order=$this->_instance->input->post('order');
foreach ($rResult as $aRow) {
    $row = array();
    for ($i = 0; $i < count($aColumns); $i++)
    {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'tblnotification_company.id') {
            $_data=$aRow[$aColumns[$i]];
        }
        if($aColumns[$i]=='type_notification')
        {
            if($aRow[$aColumns[$i]]=='days')
            {
                $_data='Ngày';
            }
            if($aRow[$aColumns[$i]]=='weeks')
            {
                $_data='Tuần';
            }
            if($aRow[$aColumns[$i]]=='months')
            {
                $_data='Tháng';
            }
            if($aRow[$aColumns[$i]]=='years')
            {
                $_data='Năm';
            }
        }
        if($aColumns[$i]=='note')
        {
            if(strlen($aRow[$aColumns[$i]])>100)
            {
                $_data ='<p data-toggle="tooltip" data-original-title="'.$aRow[$aColumns[$i]].'">'.mb_substr($aRow[$aColumns[$i]],0,100, "utf-8").'<i class="fa fa-hand-o-right" aria-hidden="true"></i>'.'</p><a class="hide">'.mb_substr($aRow[$aColumns[$i]],100,strlen($aRow[$aColumns[$i]]), "utf-8").'</a>';
            }
        }
        if ($aColumns[$i] == 'date_start') {
            $_data=_d($aRow['date_start']);
        }
        if ($aColumns[$i] == 'date_next') {
            $_data=_d($aRow['date_next']);
        }
        if ($aColumns[$i] == 'date_last')
        {
            $_data=_d($aRow['date_last']);
        }
        if($aColumns[$i]=='status')
        {
            $_data='<span class="inline-block label label-'.$light_green[$aRow['status']].'" task-status-table="'.$aRow['status'].'">' . $format_status_export[$aRow['status']].'';
                        $_data.='<a href="#" onclick="view_status('.$aRow['status'].','.$aRow['id'].')">';
            $_data.='<i class="fa fa-check task-icon task-finished-icon" data-toggle="tooltip" title="' . _l( $plan_status[$aRow['status']]) . '"></i>
                    </a>
                </span>';
        }

        if($aColumns[$i]=='create_by')
        {
            $_data = '<a href="' . admin_url('staff/member/' . $aRow[$aColumns[$i]]) . '">' . staff_profile_image($aRow[$aColumns[$i]], array(
                    'staff-profile-image-small'
                )) . '</a>';
            $_data .= ' <a href="' . admin_url('staff/member/' . $aRow[$aColumns[$i]]) . '">' . get_staff_full_name($aRow[$aColumns[$i]]). '</a>';
        }
        $row[] = $_data;
    }
    $_data="";
    $_data.='<a style="cursor: pointer;" class="btn btn-default btn-icon" onclick="add_notification('.$aRow['id'].')" data-toggle="tooltip" title="Sửa " data-placement="top"><i class="fa fa-edit"></i></a>';
    $_data .= icon_btn('notification/delete/' . $aRow['id'], 'remove', 'btn-danger _delete-remind', array(
        'data-toggle' => 'tooltip',
        'title' => _l('delete'),
        'data-placement' => 'top'
    ));
    $row[] = $_data;
    $output['aaData'][] = $row;
}



