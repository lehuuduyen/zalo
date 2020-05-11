<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Import_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($id = '')
    {
        $this->db->select('tblimport.*')->distinct();
        $this->db->from('tblimport');
        $this->db->where('id',$id);
        $purchases = $this->db->get()->row();
        $purchases->items = $this->get_items_import($id);
        return $purchases;
    }
    public function get_items_import($id)
    {
        $items = get_table_where('tblimport_items',array('id_import'=>$id));
        foreach ($items as $key => $value) {
            if($value['type'] == 'items')
            {
                $this->db->select('tblitems.name as name_item,tblitems.avatar,tblitems.code as code_item,tblunits.unit as unit')->distinct();
                $this->db->from('tblitems');
                $this->db->join('tblunits','tblunits.unitid=tblitems.unit','left');
                $this->db->where('tblitems.id',$value['product_id']);
                $items[$key] = array_merge($items[$key],$this->db->get()->row_array());
                $localton = get_table_where('tbllocaltion_warehouses',array('id'=>$value['localtion_warehouses_id']),'','row')->name_parent;
                $items[$key] = array_merge($items[$key],array('localtion_name'=>$localton));
            }else
            {
                $table = get_table_where('tbltype_items',array('type'=>$value['type']),'','row')->table;
                $this->db->select($table.'.name as name_item,'.$table.'.images as avatar,'.$table.'.code as code_item,tblunits.unit as unit')->distinct();
                $this->db->from( $table);
                $this->db->join('tblunits','tblunits.unitid='.$table.'.unit_id','left');
                $this->db->where($table.'.id',$value['product_id']);
                $items[$key] = array_merge($items[$key],$this->db->get()->row_array());
                $localton = get_listname_localtion_warehouse($value['localtion_warehouses_id']);
                $items[$key] = array_merge($items[$key],array('localtion_name'=>$localton));
            }
        }
        return $items;
    }
    public function get_targert($id)
    {
        $this->db->select('tblevaluation_criteria.*')->distinct();
        $this->db->from('tblevaluation_criteria');
        $targert = $this->db->get()->result_array();
        foreach ($targert as $key => $value) {
            $targert[$key]['targert'] = get_table_where('tblevaluation_criteria_children',array('id_evaluation'=>$value['id']));
            foreach ($targert[$key]['targert'] as $k => $v) {
                $targert[$key]['targert'][$k]['point'] = get_table_where('tblevaluate_suppliers',array('id_rfq_ask_price'=>$id,'id_evaluation_criteria'=>$value['id'],'id_evaluation_criteria_children'=>$v['id']));
            }
        }
        foreach ($targert as $key => $value) {
            if(empty($value['targert']))
            {
                unset($targert[$key]);
            }
        }
        return $targert;
    }  
    public function add($data='')
    {
     
        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            unset($data['custom_fields']);
        }
            $import = array(
            'code'=>sprintf('%06d', ch_getMaxID('id', 'tblimport') + 1),
            'prefix'=>get_option('prefix_import'),
            'note'=>$data['reason'],
            'suppliers_id'=>$data['suppliers_id'],
            'warehouse_id'=>$data['warehouse_id'],
            'date'=>to_sql_date($data['date'],true),
            'staff_create'=>get_staff_user_id(),
            'date_create'=>date('Y:m:d H:i:s'),
            'status'=>1,
        );
        if(empty($data['id_order']))
        {
            unset($data['id_order']);
        }else
        {
            $id_order = get_table_where('tblpurchase_order',array('id'=>$data['id_order']),'','row');
            $import['type_plan']= $id_order->type_plan;
            $import['id_order']=$data['id_order'];
        }
        if($this->db->insert('tblimport',$import))
        {
            $id=$this->db->insert_id();

            if (isset($custom_fields)) {
                $_custom_fields = $custom_fields;
                    unset($custom_fields);
                    $custom_fields['imports'] = $_custom_fields['imports'];
                handle_custom_fields_post($id, $custom_fields);
            }
            log_activity('Import Insert [ID: ' . $id . ']');
            $count=0;
            $items=$data['items'];
            $total = 0 ;
            $total_amount = 0;
            foreach ($items as $key => $item) {
                if(!empty($item['id']))
                {
                    if(empty($item['promotion_suppliers_1']))
                    {
                        $item['promotion_suppliers_1'] = 0;
                    }
                    if(empty($item['promotion_suppliers']))
                    {
                        $item['promotion_suppliers'] = 0;
                    }
                    $itemss=array(
                        'id_import'=>$id,
                        'product_id'=>$item['id'],
                        'quantity'=>str_replace(',', '', $item['quantity']),
                        'quantity_net'=>str_replace(',', '', $item['quantity_net']),
                        'tax_id'=>str_replace(',', '', $item['tax_id']),
                        'tax_rate'=>$item['tax_rate'],
                        'localtion_warehouses_id'=>$item['localtion_warehouses_id'],
                        'type'=>$item['type'],
                        'note'=>$item['note'],
                        'price'=>str_replace(',', '', $item['price']),
                        'promotion_suppliers_1'=>str_replace(',', '', $item['promotion_suppliers_1']),
                        'promotion_suppliers'=>str_replace(',', '', $item['promotion_suppliers']),

                    );
                    $total+=$itemss['price']*$itemss['quantity_net'] - $itemss['promotion_suppliers_1']*$itemss['quantity_net'];
                    $amount =($itemss['price']*$itemss['quantity_net'] - $itemss['promotion_suppliers_1']*$itemss['quantity_net'])*($itemss['tax_rate']/100)+($itemss['price']*$itemss['quantity_net']-$itemss['promotion_suppliers_1']*$itemss['quantity_net']);
                    $itemss['amount']=$amount;
                    $total_amount+=$amount;
                    if($this->db->insert('tblimport_items',$itemss))
                    {
                        $count++;
                        log_activity('Imports items insert [ID Import: ' . $id . ', ID Product: ' . $itemss['product_id'] . ']');
                    }
                    else {
                        exit("error");
                    }
                }
            }
            
        }
        if($count > 0)
        {   
            $this->db->update('tblimport',array('total'=>$total_amount,'total_novat'=>$total),array('id'=>$id));
            return $id;
        }
         
        return false;
    }  
    public function update($data=NULL,$id='')
    {
        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            unset($data['custom_fields']);
        }
        $import = array(
            'note'=>$data['reason'],
            'suppliers_id'=>$data['suppliers_id'],
            'warehouse_id'=>$data['warehouse_id'],
            'date'=>to_sql_date($data['date'],true),
        );
        $this->db->where('id',$id);
        if($this->db->update('tblimport',$import))
        {
            if (isset($custom_fields)) {
            $_custom_fields = $custom_fields;
                unset($custom_fields);
                $custom_fields['imports'] = $_custom_fields['imports'];
            handle_custom_fields_post($id, $custom_fields);
            }
            log_activity('Imports updateted [ID: ' . $id . ']');
            $count=true;
            $counts=0;
        }
        $total = 0 ;
        $total_amount = 0;
        $items=$data['items'];

        if($count)
        {
            $affected_id = array();
            foreach ($items as $key => $item) {
                if(!empty($item['id']))
                {
                    if(empty($item['promotion_suppliers_1']))
                    {
                        $item['promotion_suppliers_1'] = 0;
                    }
                    if(empty($item['promotion_suppliers']))
                    {
                        $item['promotion_suppliers'] = 0;
                    }
                    if(isset($item))
                        $affected_id[] = $item['id'];

                    $it=get_table_where('tblimport_items',array('id_import'=>$id,'product_id'=>$item['id']),'','row');
                    if(!empty($it))
                    {
                    $items=array(
                        'quantity'=>str_replace(',', '', $item['quantity']),
                        'quantity_net'=>str_replace(',', '', $item['quantity_net']),
                        'tax_id'=>str_replace(',', '', $item['tax_id']),
                        'tax_rate'=>$item['tax_rate'],
                        'localtion_warehouses_id'=>$item['localtion_warehouses_id'],
                        'type'=>$item['type'],
                        'note'=>$item['note'],
                        'price'=>str_replace(',', '', $item['price']),
                        'promotion_suppliers_1'=>str_replace(',', '', $item['promotion_suppliers_1']),
                        'promotion_suppliers'=>str_replace(',', '', $item['promotion_suppliers']),
                    );
                    $total+=$items['price']*$items['quantity_net'];
                    $amount =$items['price']*$items['quantity_net']*($item['tax_rate']/100)+$items['price']*$items['quantity_net'];
                    $items['amount']=$amount;
                    $total_amount+=$amount;
                    $this->db->update('tblimport_items',$items,array('id'=>$it->id));
                        if($this->db->affected_rows())
                        {
                        logActivity('Imports items updateted [ID Purchase: ' . $id . ', ID Product: ' . $it->product_id . ']');
                        $counts++;
                        }
                    }
                    else
                    {
                        $items=array(                        
                        'id_import'=>$id,
                        'product_id'=>$item['id'],
                        'quantity'=>str_replace(',', '', $item['quantity']),
                        'quantity_net'=>str_replace(',', '', $item['quantity_net']),
                        'tax_id'=>str_replace(',', '', $item['tax_id']),
                        'tax_rate'=>$item['tax_rate'],
                        'localtion_warehouses_id'=>$item['localtion_warehouses_id'],
                        'type'=>$item['type'],
                        'note'=>$item['note'],
                        'price'=>str_replace(',', '', $item['price']),
                        'promotion_suppliers_1'=>str_replace(',', '', $item['promotion_suppliers_1']),
                        'promotion_suppliers'=>str_replace(',', '', $item['promotion_suppliers']),
                        );
                        $total+=$items['price']*$items['quantity_net'];
                        $amount =$items['price']*$items['quantity_net']*($items['tax_rate']/100)+($items['price']*$items['quantity_net']);
                        $items['amount']=$amount;
                        $total_amount+=$amount;
                        if($this->db->insert('tblimport_items',$items))
                        {

                            log_activity('Imports items insert [ID Purchase: ' . $id . ', ID Product: ' . $item['id'] . ']');
                            $counts++;
                        }
                    }
                    if(empty($affected_id))
                    {
                        $this->db->where('id_import', $id);
                        $this->db->delete('tblimport_items');
                    }
                    else
                    {
                        $this->db->where('id_import', $id);
                        $this->db->where_not_in('product_id', $affected_id);
                        $this->db->delete('tblimport_items');
                    }
                }
            }
        }

        if($counts > 0)
        {
            $this->db->update('tblimport',array('total'=>$total_amount,'total_novat'=>$total),array('id'=>$id));
            return true;
        }
         
        return false;
    }
    public function delete($id ='')
    {
        $import = get_table_where('tblimport',array('id'=>$id),'','row');

        $this->db->where('id', $id);
        $this->db->delete('tblimport');
        if ($this->db->affected_rows() > 0) {
            if(!empty($import->warehouseman_id) || ($import->warehouseman_id != 0))
            {
                $this->decreaseWarehouse($id);
            }
            $this->db->where('id_import', $id);
            $this->db->delete('tblimport_items');
            if(!empty($import->id_order))
            {
            $ktr = get_table_where('tblpurchase_order',array('id'=>$import->id_order),'','row');
            if(!empty($ktr))
            {
                if(explode(',', $ktr->cancel)[0] == '1foso')
                {
                $cancels = 0;
                $cancel =array(
                    'cancel'=>$cancels
                );
                $this->db->where('id',$import->id_order);
                $this->db->update('tblpurchase_order',$cancel); 
                }
            }
            
            }
            log_activity('Imports Deleted [ID:' . $id . ']');
            return true;
        }

        return false;
    }
    public function update_status($id,$data)
    {
        $this->db->where('id',$id);
        $this->db->update('tblimport',$data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
    //tăng kho
    public function increaseWarehouse($id)
    {
        $import=$this->get($id);
        $count=0;
        if($import)
        {
            $date_warehouse = date('Y-m-d H:i:s');
            $warehouse_id = $import->warehouse_id;
            $date_import = $import->date;
            foreach ($import->items as $key => $value) 
            {   
                $localtion =  $value['localtion_warehouses_id'];
                $product_id = $value['product_id'];
                $type_items = $value['type'];
                $quantity = $value['quantity_net'];
                $count=increaseProductQuantity($warehouse_id,$id,$date_warehouse,$date_import,$product_id,$quantity,$localtion,$type_items);
                //tăng kho tổng
                increaseWarehuseQuantity($warehouse_id,$localtion,$product_id,$quantity,$type_items);
            }
        }        
        if ($count) {
            return true;
        }
        return false;
    }
    //giảm kho phiếu nhập xóa dữ liệu trong kho
    public function decreaseWarehouse($id)
    {
        if(is_numeric($id))
        {
            $warehouse_product = get_table_where("tblwarehouse_product",array('import_id'=>$id,'type_export'=>1));
            $this->db->delete('tblwarehouse_product',array('import_id'=>$id,'type_export'=>1));
            //Giảm kho tổng
            foreach ($warehouse_product as $key => $value) {
            decreaseWarehuseQuantity($value['warehouse_id'],$value['localtion'],$value['product_id'],$value['quantity'],$value['type_items']);
            }
        }        
            return true;
    }    
}