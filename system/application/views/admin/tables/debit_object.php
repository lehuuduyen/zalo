<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$plan_status=array(
    "2"=>"Đơn đặt hàng",
    "1"=>"Đơn đặt hàng được xác nhận chọn để duyệt đơn đặt hàng",
    "0"=>"Đơn đặt hàng chưa được xác nhận chọn để xác nhận"
);

$aColumns     = array(
    'tbldebit_object.id',
    'code',
    'date',
    'id_object',
    'price',
    'type',
    'note',
    'status',
    'create_by'

);
$total_cash = 0;
$sIndexColumn = "id";
$sTable       = 'tbldebit_object';
$where        = array(
);
if($this->_instance->input->post('date_start')) {
    $where[]="AND DATE_FORMAT(tbldebit_object.date, '%Y-%m-%d') >= '".to_sql_date($this->_instance->input->post('date_start'))."'";
}
if($this->_instance->input->post('date_end'))
{
    $where[]="AND DATE_FORMAT(tbldebit_object.date, '%Y-%m-%d') <= '".to_sql_date($this->_instance->input->post('date_end'))."'";
}
if($this->_instance->input->post('object_search')) {
    $where[]='AND tbldebit_object.id_object="'.$this->_instance->input->post('object_search').'"';
}
if($this->_instance->input->post('type')) {
    $where[]='AND tbldebit_object.type = "'.$this->_instance->input->post('type').'"';
}

$join         = array(
);
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array(
    'staff_id'
));
$output       = $result['output'];
$rResult      = $result['rResult'];
$j = 0;
foreach ($rResult as $aRow) {
    $row = array();
    $j++;
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = "";
        if(!empty($aRow[$aColumns[$i]]))
        {
            $_data = $aRow[$aColumns[$i]];
        }

        if ($aColumns[$i] == 'tbldebit_object.id') {
            $_data=$j;
        }

        if ($aColumns[$i] == 'code') {
            $_data=$aRow['code'];
        }
        if ($aColumns[$i] == 'price') {
                $_data=number_format_data($aRow['price']);
        }
        if($aColumns[$i]=='note')
        {
            if(strlen($aRow[$aColumns[$i]])>20)
            {
                $_data ='<p data-toggle="tooltip" data-original-title="'.$aRow[$aColumns[$i]].'">'.mb_substr($aRow[$aColumns[$i]],0,20, "utf-8").'<i class="fa fa-hand-o-right" aria-hidden="true"></i>'.'</p><a class="hide">'.mb_substr($aRow[$aColumns[$i]],20,strlen($aRow[$aColumns[$i]]), "utf-8").'</a>';
            }
        }
        if ($aColumns[$i] == 'date') {
            $_data=_d($aRow['date']);
        }
        if($aColumns[$i]=='create_by')
        {
            $_data = '<a href="' . admin_url('staff/member/' . $aRow[$aColumns[$i]]) . '">' . staff_profile_image($aRow[$aColumns[$i]], array(
                    'staff-profile-image-small'
                )) . '</a>';
            $_data .= ' <a href="' . admin_url('staff/member/' . $aRow[$aColumns[$i]]) . '">' . get_staff_full_name($aRow[$aColumns[$i]]). '</a>';
        }
        if($aColumns[$i]=='type')
        {
            if($aRow['type'] == '1')
            {
                $_data='Tính chi phí công ty';
            }
            else
            {
                $_data='Không tính chi phí công ty';
            }
        }
        if ($aColumns[$i] == 'status') {
            $_data='<span class="inline-block label label-'.get_status_label($aRow['status']).'" task-status-table="'.$aRow['status'].'">' . format_status_debit_object($aRow['status'],false,true).'';
            if(has_permission('debit_object', '', 'view') && has_permission('debit_object', '', 'view_own'))
            {
                if($aRow['status']<2){
                    $_data.='<a href="javacript:void(0)" onclick="var_status('.$aRow['status'].','.$aRow['tbldebit_object.id'].')">';
                }
                else
                {
                    $_data.='<a href="javacript:void(0)">';
                }
            }
            else
            {
                $_data .= '<a href="javacript:void(0)">';
            }
            $_data.='<i class="fa fa-check task-icon task-finished-icon" data-toggle="tooltip" title="' . _l( $plan_status[$aRow['status']]) . '"></i>
                    </a>
                </span>';

        }
        if($aColumns[$i]=='id_object')
        {
            $array_object=array(
//                'tblstaff'=>'NV',
                                   'tblcustomers'=>'KH',
                                   'tblsuppliers'=>'NCC',
                                   'tblracks'=>'LX',
                                   'tblporters'=>'BV',
                                   'tblother_object'=>'VM',
                                   'tbldebit_object'=>'DDS'
                                );
            $_data=$array_object[$aRow['id_object']].': ';
            if($aRow['id_object']=='tblstaff')
            {
                $_data .= '<a href="' . admin_url('staff/member/' . $aRow['staff_id']) . '">' . staff_profile_image($aRow['staff_id'], array(
                        'staff-profile-image-small'
                    )) . '</a>';
                $_data .= ' <a href="' . admin_url('staff/member/' . $aRow['staff_id']) . '">' . get_staff_full_name($aRow['staff_id']). '</a>';
            }
            if($aRow['id_object'] == 'tblcustomers' || $aRow['id_object'] == 'tblsuppliers')
            {
                $row_data = get_table_where($aRow['id_object'],array('id' => $aRow['staff_id']), '', 'row');
                $_data.=!empty($row_data->customer_shop_code) ? $row_data->customer_shop_code : '';
            }
            if($aRow['id_object'] == 'tblracks')
            {
                $row_data = get_table_where($aRow['id_object'], array('rackid' => $aRow['staff_id']), '', 'row');
                $_data.=!empty($row_data->rack) ? $row_data->rack : '';
            }
            if($aRow['id_object'] == 'tblporters')
            {
                $row_data = get_table_where($aRow['id_object'], array('id' => $aRow['staff_id']), '', 'row');
                $_data.= !empty($row_data->name) ? $row_data->name : '';
            }
            if($aRow['id_object']=='tblother_object')
            {
                $row_data = get_table_where($aRow['id_object'], array('id' => $aRow['staff_id']), '', 'row');
                $_data.= !empty($row_data->name) ? $row_data->name : '';
            }
            if($aRow['id_object']=='tbldebit_object')
            {
                $_data.= 'Đã Đối Soát';
            }
            if($aRow['id_object']=='')
            {
                $_data.=$aRow[$aColumns[$i]];
            }
        }

        $row[] = $_data;
    }
    $_data="";
    if(!empty($is_admin)||$aRow['status']!=2)
    {
        $_data.= icon_btn('#' , 'edit','btn-default',array(
            'onclick'=>'add_debit_object('.$aRow['tbldebit_object.id'].')',
            'data-toggle'=>'tooltip',
            'title'=>_l('edit'),
            'data-placement'=>'top'
        ));
    }
    if(!empty($is_admin))
    {
        $_data .= icon_btn('debit_object/delete/' . $aRow['tbldebit_object.id'].'?is_admin=true', 'remove', 'btn-danger _delete-remind', array(
            'data-toggle' => 'tooltip',
            'title' => _l('delete'),
            'data-placement' => 'top'
        ));

    }
    else
    {
        if($aRow['status']!=2)
        {
            $_data .= icon_btn('debit_object/delete/' . $aRow['tbldebit_object.id'], 'remove', 'btn-danger _delete-remind', array(
                'data-toggle' => 'tooltip',
                'title' => _l('delete'),
                'data-placement' => 'top'
            ));
        }
    }
    $row[] = $_data;
    $output['aaData'][] = $row;

}


