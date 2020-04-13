<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get($id = '')
    {
        $this->db->select('tblinventory.*,wareid.name as namewareide')->distinct();
        $this->db->from('tblinventory');
        $this->db->join('tblwarehouse wareid','wareid.id = tblinventory.warehouse_id','left');
        $this->db->where('tblinventory.id',$id);
        $purchases = $this->db->get()->row();
        $purchases->items = $this->get_items_inventory($id);
        return $purchases;
    }   
    public function get_items_inventory($id)
    {
        $items = get_table_where('tblinventory_items',array('inventory_id'=>$id));
        foreach ($items as $key => $value) {
            if($value['type'] == 'items')
            {
                $this->db->select('tblitems.name as name_item,tblitems.avatar,tblitems.code as code_item,tblunits.unit as unit')->distinct();
                $this->db->from('tblitems');
                $this->db->join('tblunits','tblunits.unitid=tblitems.unit','left');
                $this->db->where('tblitems.id',$value['product_id']);
                $items[$key] = array_merge($items[$key],$this->db->get()->row_array());
                $localton_id = get_listname_localtion_warehouse($value['localtion']);
                $items[$key] = array_merge($items[$key],array('localtion_name_id'=>$localton_id));
            }else
            {
                $table = get_table_where('tbltype_items',array('type'=>$value['type']),'','row')->table;
                $this->db->select($table.'.name as name_item,'.$table.'.images as avatar,'.$table.'.code as code_item,tblunits.unit as unit')->distinct();
                $this->db->from( $table);
                $this->db->join('tblunits','tblunits.unitid='.$table.'.unit_id','left');
                $this->db->where($table.'.id',$value['product_id']);
                $items[$key] = array_merge($items[$key],$this->db->get()->row_array());
                $localton_id = get_table_where('tbllocaltion_warehouses',array('id'=>$value['localtion']),'','row')->name_parent;
                $items[$key] = array_merge($items[$key],array('localtion_name_id'=>$localton_id));
            }
        }
        return $items;
    }
    public function add($data)
    {
        if ($data) {
            $items=$data['items'];
            if(isset($data['note'])) nl2br($data['note']);
            $inventory=array(
                'code'=>sprintf('%06d', ch_getMaxID('id', 'tblinventory') + 1),
                'prefix'=>get_option('prefix_inventory'),
                'note'=>$data['note'],
                'warehouse_id'=>$data['warehouse_idd'],
                'date'=>to_sql_date($data['date'],true),
                'staff_id'=>get_staff_user_id(),
                'date_create'=>date('Y:m:d H:i:s'),
                'status'=>0,
                );
            $warehouse_id=$data['warehouse_idd'];
            $this->db->insert('tblinventory',$inventory);
            $id=$this->db->insert_id();
            if ($id) {
                // unit_id
                foreach ($items as $key => $item) {
                    if(!empty($item['quantity_net']))
                    {
                    $price = $this->get_full_item($item['id'],$item['type']);
                    $_item['inventory_id']=$id;
                    $_item['product_id']=$item['id'];
                    $_item['unit_cost']=$price->price;
                    $_item['warehouse_id']=$warehouse_id;
                    $_item['localtion']=$item['localtion'];
                    $_item['type']=$item['type'];
                    $_item['quantity']=$item['quantity'];
                    $_item['quantity_net']=$item['quantity_net'];
                    $_item['quantity_diff']=$item['quantity_diff'];
                    $_item['handling']=$item['handling'];
                    $this->db->insert('tblinventory_items',$_item);
                    }
                }
                log_activity('Inventory updateted [ID: ' . $id . ']');
                return $id;
            }
        }
        return false;
    }
    public function update($data=NULL,$id='')
    {
                $items=$data['items'];
            if(isset($data['note'])) nl2br($data['note']);
            $inventory=array(
                'note'=>$data['note'],
            );
        $warehouse_id=$data['warehouse_idd'];
        $this->db->where('id',$id);
        if($this->db->update('tblinventory',$inventory))
        {
            log_activity('Inventory updateted [ID: ' . $id . ']');
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
                if(!empty($item['quantity_net']))
                {
                    $it=get_table_where('tblinventory_items',array('id'=>$item['idd']),'','row');
                    if(!empty($it))
                    {
                    $affected_id[] = $item['idd'];
                    $_item['quantity']=$item['quantity'];
                    $_item['quantity_net']=$item['quantity_net'];
                    $_item['quantity_diff']=$item['quantity_diff'];
                    $_item['handling']=$item['handling'];
                    $this->db->update('tblinventory_items',$_item,array('id'=>$it->id));
                    if($this->db->affected_rows())
                    {
                    logActivity('Inventory items updateted [ID Purchase: ' . $id . ', ID Product: ' . $it->product_id . ']');
                    $counts++;
                    }
                    }
                    else
                    {
                        $price = $this->get_full_item($item['id'],$item['type']);
                        $__item['inventory_id']=$id;
                        $__item['product_id']=$item['id'];
                        $__item['unit_cost']=$price->price;
                        $__item['warehouse_id']=$warehouse_id;
                        $__item['localtion']=$item['localtion'];
                        $__item['type']=$item['type'];
                        $__item['quantity']=$item['quantity'];
                        $__item['quantity_net']=$item['quantity_net'];
                        $__item['quantity_diff']=$item['quantity_diff'];
                        $__item['handling']=$item['handling'];
                        if($this->db->insert('tblinventory_items',$__item))
                        {
                            $affected_id[] = $this->db->insert_id();
                            log_activity('Inventory items insert [ID Purchase: ' . $id . ', ID Product: ' . $__item['product_id'] . ']');
                            $counts++;
                        }
                    }
                }
            }
                    if(empty($affected_id))
                    {
                        $this->db->where('inventory_id', $id);
                        $this->db->delete('tblinventory_items');
                    }
                    else
                    {
                        $this->db->where_not_in('id', $affected_id);
                        $this->db->delete('tblinventory_items');
                    }
        }

        if($counts > 0)
        {
            return true;
        }
         
        return false;
    }    
    public function delete($id ='')
    {
        $this->db->where('id', $id);
        $this->db->delete('tblinventory');
        if ($this->db->affected_rows() > 0) {
            $this->db->where('inventory_id', $id);
            $this->db->delete('tblinventory_items');
            log_activity('Inventory Deleted [ID:' . $id . ']');
            return true;
        }

        return false;
    }
    public function get_full_item($id = '',$type = '')
    {
        if($type == 'items')
        {
            $this->db->select('tblitems.price as price')->distinct();
            $this->db->from('tblitems');
            $this->db->order_by('tblitems.id', 'desc');
            if (is_numeric($id)) {
                
                $this->db->where('tblitems.id', $id);
                $item = $this->db->get()->row();
                return $item;
            }  
        } else {
            $table = get_table_where('tbltype_items',array('type'=>$type),'','row')->table;
            $this->db->select($table.'.price_sell as price')->distinct();
            $this->db->from($table);
            $this->db->order_by($table.'.id', 'desc');
            if (is_numeric($id)) {
                
                $this->db->where($table.'.id', $id);
                $item = $this->db->get()->row();
                return $item;
            }
        }
    }
}