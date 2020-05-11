<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
	'name',
    'content'
];

$where = [];
$where[] = 'AND tblquick_reply.id_parent is null';

$sIndexColumn = 'id';
$sTable       = 'tblquick_reply';

$join         = array(
);

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, array('id', 'id_parent'));
$output  = $result['output'];
$rResult = $result['rResult'];
$currentPage=$this->_instance->input->post('start');
$currentall=$output['iTotalRecords'];
foreach ($rResult as $r => $aRow) {
    $row = [];
	$row[] =  '<a class="rolChild" data-toggle="collapse" data-target=".collapseChild_'.$aRow['id'].'" aria-expanded="false" aria-controls="collapseChild_'.$aRow['id'].'"><i class="fa fa-caret-down" aria-hidden="true"></i></a>'.$aRow['name'];
	$quick_reply_parent = get_table_where('tblquick_reply' , ['id' => $aRow['id_parent']], '', 'row');
    $row[] =  $aRow['content'];
	$option = '';
	$option .= '<a onclick="edit_quick_reply('.$aRow['id'].');return false;" class="btn btn-default btn-icon mright5"><i class="fa fa-edit"></i></a>';
	$option .= '<a onclick="delete_quick_reply('.$aRow['id'].');return false;" class="btn btn-danger btn-icon delete-remind"><i class="fa fa-remove"></i></a>';
	$row[] = $option;
	$output['aaData'][] = $row;
	$arrayOption = [];
	getChildQuick_reply($aRow['id'], $arrayOption, $icon = '<i class="mleft20">➪</i>');
	if(!empty($arrayOption))
	{
		foreach($arrayOption as $kO => $vO)
		{
			$row = [];
			$this->ci->db->where('id_parent', $vO['id']);
			$kt_reply_parent = $this->ci->db->get('tblquick_reply')->num_rows();
			$aChild = '';
			if(!empty($kt_reply_parent))
			{
				$aChild =  '<a class="rolChild" data-toggle="collapse" data-target=".collapseChild_'.$vO['id'].'" aria-expanded="false" aria-controls="collapseChild_'.$vO['id'].'">
								<i class="fa fa-caret-down" aria-hidden="true"></i>
							</a>';
			}
			else
			{
				$aChild.='<i style="margin-left: 13px"></i>';
			}
			$aChild .= $vO['name'];
			$row[] = $aChild;
			$row[] =  $vO['content'];
			$option = '';
			$option .= '<a onclick="edit_quick_reply('.$vO['id'].'); return false;" class="btn btn-default btn-icon mright5"><i class="fa fa-edit"></i></a>';
			$option .= '<a onclick="delete_quick_reply('.$vO['id'].'); return false;" class="btn btn-danger btn-icon delete-remind"><i class="fa fa-remove"></i></a>';
			$row[] = $option;
			$row['DT_RowClass'] = 'collapseChild_'.$vO['id_parent'].' collapse';

			$output['aaData'][] = $row;
		}
	}
}
