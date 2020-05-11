<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'tblwarehouse_product.id',
    'tblwarehouse_product.type_items',
    '1',
    'tbllocaltion_warehouses.name_parent',
    'SUM(tblwarehouse_product.quantity_left) as quantity',
];
$sIndexColumn = 'id';
$sTable       = db_prefix().'warehouse_product';

$join         = array(
    'LEFT JOIN tbllocaltion_warehouses ON tbllocaltion_warehouses.id = tblwarehouse_product.localtion',
    'LEFT JOIN tbltype_items ON tbltype_items.type = tblwarehouse_product.type_items',
);
$where= array();
if ($this->ci->input->post('type_items')) {
    array_push($where, 'AND tblwarehouse_product.type_items = "'.$this->ci->input->post('type_items').'"');
}
if(is_numeric($id))
{
    array_push($where, 'AND tblwarehouse_product.warehouse_id = '.$id);
}
if ($this->ci->input->post('custom_item_select')) {
    array_push($where, 'AND tblwarehouse_product.product_id = '.$this->ci->input->post('custom_item_select'));
}
if ($this->ci->input->post('localtion')) {
    $localtion = [];
    get_full_childs_id($this->ci->input->post('localtion'),$localtion);
    array_push($where, 'AND tblwarehouse_product.localtion IN('.implode(',', $localtion).')');
}
$group_by ="GROUP BY tblwarehouse_product.product_id,tblwarehouse_product.localtion";
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['tblwarehouse_product.product_id','tbltype_items.name as name_type'],$group_by);
$output  = $result['output'];
$rResult = $result['rResult'];

usort($rResult, ch_make_cmp(['tblwarehouse_product.type_items' => "desc", 'product_id' => "desc"]));
$currentPage=$this->_instance->input->post('start');
$currentall=$output['iTotalRecords'];
foreach ($rResult as $r => $aRow) {
        if($r == 0)
        {
            $type_items = $aRow['tblwarehouse_product.type_items'];
            $product_id = $aRow['product_id'];
            if($aRow['tblwarehouse_product.type_items'] == 'items')
            {
            $this->_instance->db->select('tblitems.*,tblunits.unit as unit_name')->distinct();
            $this->_instance->db->from('tblitems');
            $this->_instance->db->join('tblunits','tblunits.unitid=tblitems.unit','left');
            $this->_instance->db->where('tblitems.id',$aRow['product_id']);
            $items = $this->_instance->db->get()->row();
            $row=array(
                        '#: '.$items->id,
                        'Mã hàng: '.'<a target="_blank" href="'.admin_url("invoice_items/item/").$items->id.'">('.$items->code.') '.$items->name.'</a><span class="label label-default mleft5 inline-block customer-group-list pointer" style="border:1px solid #e30000">'.$aRow['name_type'].'</span>',
                        format_item_color($items->id,$aRow['tblwarehouse_product.type_items']),
                        'Số lượng tối thiểu: '.number_format($items->minimum_quantity),
                        'Tổng số lượng: '.number_format(sumExistsQ_all($aRow['tblwarehouse_product.type_items'],$aRow['product_id'],$rResult,$r)),
                    );
            $row['DT_RowClass'] = 'alert-header bold warning';

                for ($i=0 ; $i<count($aColumns) ; $i++ ){
                    $row[]="";
                }
            $output['aaData'][] = $row;
            }else
            if($aRow['tblwarehouse_product.type_items'] == 'product')
            {
            $this->_instance->db->select('tbl_products.*,tblunits.unit as unit_name')->distinct();
            $this->_instance->db->from('tbl_products');
            $this->_instance->db->join('tblunits','tblunits.unitid=tbl_products.unit_id','left');
            $this->_instance->db->where('tbl_products.id',$aRow['product_id']);
            $items = $this->_instance->db->get()->row();
            $row=array(
                        '#: '.$items->id,
                        'Mã hàng: '.'<a target="_blank" href="'.admin_url("invoice_items/item/").$items->id.'">('.$items->code.') '.$items->name.'</a><span class="label label-default mleft5 inline-block customer-group-list pointer" style="border:1px solid #e30000">'.$aRow['name_type'].'</span>',
                        format_item_color($items->id,$aRow['tblwarehouse_product.type_items']),
                        'Số lượng tối thiểu: '.number_format($items->quantity_minimum),
                        'Tổng số lượng: '.number_format(sumExistsQ_all($aRow['tblwarehouse_product.type_items'],$aRow['product_id'],$rResult,$r)),
                    );
            $row['DT_RowClass'] = 'alert-header bold warning';

                for ($i=0 ; $i<count($aColumns) ; $i++ ){
                    $row[]="";
                }
            $output['aaData'][] = $row;
            }else
            {
            $this->_instance->db->select('tbl_materials.*,tblunits.unit as unit_name')->distinct();
            $this->_instance->db->from('tbl_materials');
            $this->_instance->db->join('tblunits','tblunits.unitid=tbl_materials.unit_id','left');
            $this->_instance->db->where('tbl_materials.id',$aRow['product_id']);
            $items = $this->_instance->db->get()->row();
            $row=array(
                        '#: '.$items->id,
                        'Mã hàng: '.'<a target="_blank" href="'.admin_url("invoice_items/item/").$items->id.'">('.$items->code.') '.$items->name.'</a><span class="label label-default mleft5 inline-block customer-group-list pointer" style="border:1px solid #e30000">'.$aRow['name_type'].'</span>',
                        // 'Số lượng tối thiểu: '.number_format($items->minimum_quantity),
                        format_item_color($items->id,$aRow['tblwarehouse_product.type_items']),
                        '',
                        'Tổng số lượng: '.number_format(sumExistsQ_all($aRow['tblwarehouse_product.type_items'],$aRow['product_id'],$rResult,$r)),
                    );
            $row['DT_RowClass'] = 'alert-header bold warning';

                for ($i=0 ; $i<count($aColumns) ; $i++ ){
                    $row[]="";
                }
            $output['aaData'][] = $row;   
            }

        }else
        {

            if(($aRow['tblwarehouse_product.type_items']==$type_items)&&($aRow['product_id']!=$product_id))
            {
                if($aRow['tblwarehouse_product.type_items'] == 'items')
            {
                $type_items = $aRow['tblwarehouse_product.type_items'];
                $product_id = $aRow['product_id'];
                $this->_instance->db->select('tblitems.*,tblunits.unit as unit_name')->distinct();
                $this->_instance->db->from('tblitems');
                $this->_instance->db->join('tblunits','tblunits.unitid=tblitems.unit','left');
                $this->_instance->db->where('tblitems.id',$aRow['product_id']);
                $items = $this->_instance->db->get()->row();
                $row=array(
                            '#: '.$items->id,
                            'Mã hàng: '.'<a target="_blank" href="'.admin_url("invoice_items/item/").$items->id.'">('.$items->code.') '.$items->name.'</a><span class="label label-default mleft5 inline-block customer-group-list pointer" style="border:1px solid #e30000">'.$aRow['name_type'].'</span>',
                            format_item_color($items->id,$aRow['tblwarehouse_product.type_items']),
                            'Số lượng tối thiểu: '.number_format($items->minimum_quantity),
                            'Tổng số lượng: '.number_format(sumExistsQ_all($aRow['tblwarehouse_product.type_items'],$aRow['product_id'],$rResult,$r)),
                        );
                $row['DT_RowClass'] = 'alert-header bold warning';

                    for ($i=0 ; $i<count($aColumns) ; $i++ ){
                        $row[]="";
                    }
                $output['aaData'][] = $row;
            }else
            if($aRow['tblwarehouse_product.type_items'] == 'product')
            {
                $this->_instance->db->select('tbl_products.*,tblunits.unit as unit_name')->distinct();
                $this->_instance->db->from('tbl_products');
                $this->_instance->db->join('tblunits','tblunits.unitid=tbl_products.unit_id','left');
                $this->_instance->db->where('tbl_products.id',$aRow['product_id']);
                $items = $this->_instance->db->get()->row();
                $row=array(
                            '#: '.$items->id,
                            'Mã hàng: '.'<a target="_blank" href="'.admin_url("invoice_items/item/").$items->id.'">('.$items->code.') '.$items->name.'</a><span class="label label-default mleft5 inline-block customer-group-list pointer" style="border:1px solid #e30000">'.$aRow['name_type'].'</span>',
                            format_item_color($items->id,$aRow['tblwarehouse_product.type_items']),
                            'Số lượng tối thiểu: '.number_format($items->quantity_minimum),
                            'Tổng số lượng: '.number_format(sumExistsQ_all($aRow['tblwarehouse_product.type_items'],$aRow['product_id'],$rResult,$r)),
                        );
              
                $row['DT_RowClass'] = 'alert-header bold warning';

                    for ($i=0 ; $i<count($aColumns) ; $i++ ){
                        $row[]="";
                    }
                $output['aaData'][] = $row;
            }else
            {
            $this->_instance->db->select('tbl_materials.*,tblunits.unit as unit_name')->distinct();
            $this->_instance->db->from('tbl_materials');
            $this->_instance->db->join('tblunits','tblunits.unitid=tbl_materials.unit_id','left');
            $this->_instance->db->where('tbl_materials.id',$aRow['product_id']);
            $items = $this->_instance->db->get()->row();
            $row=array(
                        '#: '.$items->id,
                        'Mã hàng: '.'<a target="_blank" href="'.admin_url("invoice_items/item/").$items->id.'">('.$items->code.') '.$items->name.'</a><span class="label label-default mleft5 inline-block customer-group-list pointer" style="border:1px solid #e30000">'.$aRow['name_type'].'</span>',
                        format_item_color($items->id,$aRow['tblwarehouse_product.type_items']),
                        '',
                        // 'Số lượng tối thiểu: '.number_format($items->minimum_quantity),
                        'Tổng số lượng: '.number_format(sumExistsQ_all($aRow['tblwarehouse_product.type_items'],$aRow['product_id'],$rResult,$r)),
                    );
            $row['DT_RowClass'] = 'alert-header bold warning';

                for ($i=0 ; $i<count($aColumns) ; $i++ ){
                    $row[]="";
                }
            $output['aaData'][] = $row;   
            }
            }
            if(($aRow['tblwarehouse_product.type_items']!=$type_items))
            {
                if($aRow['tblwarehouse_product.type_items'] == 'items')
            {
                $type_items = $aRow['tblwarehouse_product.type_items'];
                $product_id = $aRow['product_id'];
                $this->_instance->db->select('tblitems.*,tblunits.unit as unit_name')->distinct();
                $this->_instance->db->from('tblitems');
                $this->_instance->db->join('tblunits','tblunits.unitid=tblitems.unit','left');
                $this->_instance->db->where('tblitems.id',$aRow['product_id']);
                $items = $this->_instance->db->get()->row();
                $row=array(
                            '#: '.$items->id,
                            'Mã hàng: '.'<a target="_blank" href="'.admin_url("invoice_items/item/").$items->id.'">('.$items->code.') '.$items->name.'</a><span class="label label-default mleft5 inline-block customer-group-list pointer" style="border:1px solid #e30000">'.$aRow['name_type'].'</span>',
                            format_item_color($items->id,$aRow['tblwarehouse_product.type_items']),
                            'Số lượng tối thiểu: '.number_format($items->minimum_quantity),
                            'Tổng số lượng: '.number_format(sumExistsQ_all($aRow['tblwarehouse_product.type_items'],$aRow['product_id'],$rResult,$r)),
                        );
                $row['DT_RowClass'] = 'alert-header bold warning';

                    for ($i=0 ; $i<count($aColumns) ; $i++ ){
                        $row[]="";
                    }
                $output['aaData'][] = $row;
            }else
            if($aRow['tblwarehouse_product.type_items'] == 'product')
            {
                $this->_instance->db->select('tbl_products.*,tblunits.unit as unit_name')->distinct();
                $this->_instance->db->from('tbl_products');
                $this->_instance->db->join('tblunits','tblunits.unitid=tbl_products.unit_id','left');
                $this->_instance->db->where('tbl_products.id',$aRow['product_id']);
                $items = $this->_instance->db->get()->row();
                $row=array(
                            '#: '.$items->id,
                            'Mã hàng: '.'<a target="_blank" href="'.admin_url("invoice_items/item/").$items->id.'">('.$items->code.') '.$items->name.'</a><span class="label label-default mleft5 inline-block customer-group-list pointer" style="border:1px solid #e30000">'.$aRow['name_type'].'</span>',
                            format_item_color($items->id,$aRow['tblwarehouse_product.type_items']),
                            '',
                            // 'Số lượng tối thiểu: '.number_format($items->minimum_quantity),
                            'Tổng số lượng: '.number_format(sumExistsQ_all($aRow['tblwarehouse_product.type_items'],$aRow['product_id'],$rResult,$r)),
                        );
                $row['DT_RowClass'] = 'alert-header bold warning';

                    for ($i=0 ; $i<count($aColumns) ; $i++ ){
                        $row[]="";
                    }
                $output['aaData'][] = $row;
            }else
            {
            $this->_instance->db->select('tbl_materials.*,tblunits.unit as unit_name')->distinct();
            $this->_instance->db->from('tbl_materials');
            $this->_instance->db->join('tblunits','tblunits.unitid=tbl_materials.unit_id','left');
            $this->_instance->db->where('tbl_materials.id',$aRow['product_id']);
            $items = $this->_instance->db->get()->row();
            $row=array(
                        '#: '.$items->id,
                        'Mã hàng: '.'<a target="_blank" href="'.admin_url("invoice_items/item/").$items->id.'">('.$items->code.') '.$items->name.'</a><span class="label label-default mleft5 inline-block customer-group-list pointer" style="border:1px solid #e30000">'.$aRow['name_type'].'</span>',
                        format_item_color($items->id,$aRow['tblwarehouse_product.type_items']),
                        '',
                        // 'Số lượng tối thiểu: '.number_format($items->minimum_quantity),
                        'Tổng số lượng: '.number_format(sumExistsQ_all($aRow['tblwarehouse_product.type_items'],$aRow['product_id'],$rResult,$r)),
                    );
            $row['DT_RowClass'] = 'alert-header bold warning';

                for ($i=0 ; $i<count($aColumns) ; $i++ ){
                    $row[]="";
                }
            $output['aaData'][] = $row;   
            }
            }

        }
    $row = [];
    for ($i = 0 ; $i < count($aColumns) ; $i++) {
        if(strpos($aColumns[$i],'as') !== false && !isset($aRow[ $aColumns[$i] ])){
            $_data = $aRow[ strafter($aColumns[$i],'as ')];
        } else {
            $_data = $aRow[ $aColumns[$i] ];
        }
        if ($aColumns[$i] == 'tblwarehouse_product.id') {
            $_data = ($currentall+1)-($currentPage+$r+1);
        }
        if ($aColumns[$i] == '1') {
            $_data = '';
        }
        if ($aColumns[$i] == 'tblwarehouse_product.type_items') {
            $_data = '';
        }
        if ($aColumns[$i] == 'quantity') {
            $_data = number_format($aRow['quantity']);
        }
        $row[] = $_data;
    }

    $output['aaData'][] = $row;
}
