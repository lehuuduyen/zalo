<?php
defined('BASEPATH') or exit('No direct script access allowed');

function categories($data='',$html,$parent,$level)
{
    foreach ($data as $key => $value) {
        $html.='<tr class="treegrid-'.$value['id'].' treegrid-parent-'.$parent.'">
                <td><h5 style="display: inline-block;">'.$value['category'].'</h5></td>
                <td>Cấp '.$level.'</td>
                <td>'. staff_profile_image($value['staff_create'], array('staff-profile-image-small mright5'), 'small', array(
                        'data-toggle' => 'tooltip',
                        'data-title' => get_staff_full_name($value['staff_create'])
                    )).'</td>
                <td>'._dt($value['date_create']).'</td>
                <td>'.icon_btn('#' , 'pencil', 'btn-default',array('onclick'=>"edit_category(".$value['id'].",'".$value['category']."',".$value['category_parent']."); return false;")).'<a onclick="delete_category('.$value['id'].')" class="btn btn-danger  btn-icon " data-toggle="tooltip" data-placement="left"  title="">
                            <i class="fa fa-remove"></i>
                        </a>
                </td>   
            </tr>';
        $_data =get_table_where('tblcategories',array('category_parent'=>$value['id']));
        if($_data)
        {
        $html=categories($_data,$html,$value['id'],($level + 1));
        }else
        {
           continue;
        }
    }
     return $html;
}
function get_categories($data='')
{
    $html='';
    foreach ($data as $key => $value) {

        $html.='<tr class="treegrid-'.$value['id'].'">
                <td><h5 style="display: inline-block;">'.$value['category'].'</h5></td>
                <td>Cấp 1</td>
                <td>'. staff_profile_image($value['staff_create'], array('staff-profile-image-small mright5'), 'small', array(
                        'data-toggle' => 'tooltip',
                        'data-title' => get_staff_full_name($value['staff_create'])
                    )).'</td>
                <td>'._dt($value['date_create']).'</td>
                <td>'.icon_btn('#' , 'pencil', 'btn-default',array('onclick'=>"edit_category(".$value['id'].",'".$value['category']."',".$value['category_parent']."); return false;")).'<a onclick="delete_category('.$value['id'].')" class="btn btn-danger  btn-icon " data-toggle="tooltip" data-placement="left"  title="">
                            <i class="fa fa-remove"></i>
                        </a>
                </td>
            </tr>';
        $_data =get_table_where('tblcategories',array('category_parent'=>$value['id']));
        if($_data)
        {
        $html=categories($_data,$html,$value['id'],2);
        }else
        {
            continue;
        }
    }
    echo $html;
}

function categories_type_client($data='',$html,$parent,$level)
{
    foreach ($data as $key => $value) {
        $checkExitst =get_table_where('tbltype_client',array('category_parent'=>$value['id']));
        $html.='<tr class="treegrid-'.$value['id'].' treegrid-parent-'.$parent.'">
                <td><h5 style="display: inline-block;">'.$value['name'].'</h5></td>
                <td>Cấp '.$level.'</td>
                <td>'. staff_profile_image($value['create_by'], array('staff-profile-image-small mright5'), 'small', array(
                        'data-toggle' => 'tooltip',
                        'data-title' => get_staff_full_name($value['create_by'])
                    )).'</td>
                <td>'._dt($value['date_create']).'</td>
                <td>'.icon_btn('#' , 'pencil', 'btn-default',array('onclick'=>"edit_category(".$value['id'].",'".$value['name']."',".$value['category_parent']."); return false;")).'<a onclick="delete_category('.$value['id'].')" class="btn btn-danger btn-icon '.($checkExitst ? 'hide' : '').'" data-toggle="tooltip" data-placement="left"  title="">
                        <i class="fa fa-remove"></i>
                    </a>
                </td>   
            </tr>';
        $_data =get_table_where('tbltype_client',array('category_parent'=>$value['id']));
        if($_data) {
            $html = categories_type_client($_data,$html,$value['id'],($level + 1));
        } else {
           continue;
        }
    }
     return $html;
}

function get_categories_type_client($data='')
{
    $html='';
    foreach ($data as $key => $value) {
        $checkExitst = get_table_where('tbltype_client',array('category_parent'=>$value['id']),'','row');
        $html.='<tr class="treegrid-'.$value['id'].'">
                <td><h5 style="display: inline-block;">'.$value['name'].'</h5></td>
                <td>Cấp 1</td>
                <td>'. staff_profile_image($value['create_by'], array('staff-profile-image-small mright5'), 'small', array(
                        'data-toggle' => 'tooltip',
                        'data-title' => get_staff_full_name($value['create_by'])
                    )).'</td>
                <td>'._dt($value['date_create']).'</td>
                <td>'.icon_btn('#' , 'pencil', 'btn-default',array('onclick'=>"edit_category(".$value['id'].",'".$value['name']."',".$value['category_parent']."); return false;")).'<a onclick="delete_category('.$value['id'].')" class="btn btn-danger btn-icon '.($checkExitst ? 'hide' : '').'" data-toggle="tooltip" data-placement="left"  title="">
                        <i class="fa fa-remove"></i>
                    </a>
                </td>
            </tr>';
        $_data = get_table_where('tbltype_client',array('category_parent'=>$value['id']));
        if($_data) {
            $html= categories_type_client($_data,$html,$value['id'],2);
        } else {
            continue;
        }
    }
    echo $html;
}

function returnget_categories($data='')
{
    $html='';
    foreach ($data as $key => $value) {

        $html.='<tr class="treegrid-'.$value['id'].'">
                <td><h5 style="display: inline-block;">'.$value['category'].'</h5></td>
                <td>Cấp 1</td>
                <td>'. staff_profile_image($value['staff_create'], array('staff-profile-image-small mright5'), 'small', array(
                        'data-toggle' => 'tooltip',
                        'data-title' => get_staff_full_name($value['staff_create'])
                    )).'</td>
                <td>'._dt($value['date_create']).'</td>
                <td></td>
            </tr>';
        $_data =get_table_where('tblcategories',array('category_parent'=>$value['id']));
        if($_data)
        {
        $html=categories($_data,$html,$value['id'],2);
        }else
        {
            continue;
        }
    }
    return $html;
}