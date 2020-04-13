<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Transfer_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function add($data='')
        {
            $transfer = array(
                'code'=>sprintf('%06d', ch_getMaxID('id', 'tbltransfer_warehouse') + 1),
                'prefix'=>get_option('prefix_transfer'),
                'note'=>$data['note'],
                'warehouse_id'=>$data['warehouse_id'],
                'warehouse_to'=>$data['warehouse_to'],
                'date'=>to_sql_date($data['date']),
                'staff_id'=>get_staff_user_id(),
                'date_create'=>date('Y:m:d H:i:s'),
                'status'=>1,
            );
            if($transfer['note'] == NULL)
            {
            $transfer['note'] ='';
            }
            if($this->db->insert('tbltransfer_warehouse',$transfer))
            {
                $id=$this->db->insert_id();

                log_activity('Transfer Warehouse Insert [ID: ' . $id . ']');
                $count=0;
                $items=$data['items'];
                $total = 0 ;
                $total_amount = 0;
                foreach ($items as $key => $item) {
                    if(!empty($item['id']))
                    {
                        $itema=array(
                            'id_transfer'=>$id,
                            'id_items'=>$item['id'],
                            'quantity'=>str_replace(',', '', $item['quantity']),
                            'quantity_net'=>str_replace(',', '', $item['quantity_net']),
                            'type'=>$item['type'],
                            'note'=>$item['note'],
                            'localtion_id'=>$item['localtion_id'],
                            'localtion_to'=>$item['localtion_to'],
                            'price'=>str_replace(',', '', $item['price']),

                        );
                        if($itema['note'] == NULL)
                        {
                        $itema['note'] ='';
                        }
                        $amount = $itema['price']*$itema['quantity_net'];
                        $total+=$amount;
                        $itema['amount']=$amount;
                        if($this->db->insert('tbltransfer_warehouse_detail',$itema))
                        {
                            $count++;
                            log_activity('Transfer Warehouse insert [ID Import: ' . $id . ', ID Product: ' . $item['id'] . ']');
                        }
                        else {
                            exit("error");
                        }
                    }
                }
                
            }
            if($count > 0)
            {   
                $this->db->update('tbltransfer_warehouse',array('total'=>$total),array('id'=>$id));
                return $id;
            }
             
            return false;
        }  
        public function update($data=NULL,$id='')
        {
            $transfer = array(
                'note'=>$data['note'],
                'warehouse_id'=>$data['warehouse_id'],
                'warehouse_to'=>$data['warehouse_to'],
                'date'=>to_sql_date($data['date']),
            );
            if($transfer['note'] == NULL)
            {
            $transfer['note'] ='';
            }
            $this->db->where('id',$id);
            if($this->db->update('tbltransfer_warehouse',$transfer))
            {
                log_activity('Transfer Warehouse updateted [ID: ' . $id . ']');
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
                        if(isset($item))
                            $affected_id[] = $item['id'];

                        $it=get_table_where('tbltransfer_warehouse_detail',array('id_transfer'=>$id,'id_items'=>$item['id'],'type'=>$item['type']),'','row');
                        if(!empty($it))
                        {
                        $itemss=array(
                            'quantity'=>str_replace(',', '', $item['quantity']),
                            'quantity_net'=>str_replace(',', '', $item['quantity_net']),
                            'note'=>$item['note'],
                            'localtion_id'=>$item['localtion_id'],
                            'localtion_to'=>$item['localtion_to'],
                            'price'=>str_replace(',', '', $item['price']),
                        );
                        $amount = $itemss['price']*$itemss['quantity_net'];
                        $total+=$amount;
                        $itemss['amount']=$amount;
                        $this->db->update('tbltransfer_warehouse_detail',$itemss,array('id'=>$it->id));
                        if($this->db->affected_rows())
                        {
                        logActivity('Transfer Warehouse items updateted [ID Purchase: ' . $id . ', ID Product: ' . $it->id_items . ']');
                        $counts++;
                        }
                        }
                        else
                        {
                            $itemsa=array(
                                'id_transfer'=>$id,
                                'id_items'=>$item['id'],
                                'quantity'=>str_replace(',', '', $item['quantity']),
                                'quantity_net'=>str_replace(',', '', $item['quantity_net']),
                                'type'=>$item['type'],
                                'note'=>$item['note'],
                                'localtion_id'=>$item['localtion_id'],
                                'localtion_to'=>$item['localtion_to'],
                                'price'=>str_replace(',', '', $item['price']),

                            );
                            if($itemsa['note'] == NULL)
                            {
                            $itemsa['note'] ='';
                            }
                            $amount = $itemsa['price']*$itemsa['quantity_net'];
                            $total+=$amount;
                            $itemsa['amount']=$amount;
                            if($this->db->insert('tbltransfer_warehouse_detail',$itemsa))
                            {

                                log_activity('Transfer Warehouse items insert [ID Purchase: ' . $id . ', ID Product: ' . $itemsa['id_items'] . ']');
                                $counts++;
                            }
                        }
                        if(empty($affected_id))
                        {
                            $this->db->where('id_transfer', $id);
                            $this->db->delete('tbltransfer_warehouse_detail');
                        }
                        else
                        {
                            $this->db->where('id_transfer', $id);
                            $this->db->where_not_in('id_items', $affected_id);
                            $this->db->delete('tbltransfer_warehouse_detail');
                        }
                    }
                }
            }

            if($counts > 0)
            {
                $this->db->update('tbltransfer_warehouse',array('total'=>$total),array('id'=>$id));
                return true;
            }
             
            return false;
        }    
        public function delete($id ='')
        {
            $transfer = get_table_where('tbltransfer_warehouse',array('id'=>$id),'','row');
            $items = get_table_where('tbltransfer_warehouse_detail',array('id_transfer'=>$id));
            $this->db->where('id', $id);
            $this->db->delete('tbltransfer_warehouse');
            if ($this->db->affected_rows() > 0) {
                if(!empty($transfer->warehouseman_id) || ($transfer->warehouseman_id != 0))
                {
                    $this->decreaseWarehouse($id,$transfer->warehouse_id,$items);
                }
                $this->db->where('id_transfer', $id);
                $this->db->delete('tbltransfer_warehouse_detail');
                log_activity('Transfer Warehouse Deleted [ID:' . $id . ']');
                return true;
            }

            return false;
        }
        public function update_status($id,$data)
        {
            $this->db->where('id',$id);
            $this->db->update('tbltransfer_warehouse',$data);
            if ($this->db->affected_rows() > 0) {
                return true;
            }
            return false;
        }
        public function get($id = '')
        {
            $this->db->select('tbltransfer_warehouse.*,wareid.name as namewareid,wareto.name as namewareto')->distinct();
            $this->db->from('tbltransfer_warehouse');
            $this->db->join('tblwarehouse wareid','wareid.id = tbltransfer_warehouse.warehouse_id','left');
            $this->db->join('tblwarehouse wareto','wareto.id = tbltransfer_warehouse.warehouse_to','left');
            $this->db->where('tbltransfer_warehouse.id',$id);
            $purchases = $this->db->get()->row();
            $purchases->items = $this->get_items_transfer($id);
            return $purchases;
        }   
        public function get_items_transfer($id)
        {
            $items = get_table_where('tbltransfer_warehouse_detail',array('id_transfer'=>$id));
            foreach ($items as $key => $value) {
                if($value['type'] == 'items')
                {
                    $this->db->select('tblitems.name as name_item,tblitems.avatar,tblitems.code as code_item,tblunits.unit as unit')->distinct();
                    $this->db->from('tblitems');
                    $this->db->join('tblunits','tblunits.unitid=tblitems.unit','left');
                    $this->db->where('tblitems.id',$value['id_items']);
                    $items[$key] = array_merge($items[$key],$this->db->get()->row_array());
                    $localton_id = get_listname_localtion_warehouse($value['localtion_id']);
                    $items[$key] = array_merge($items[$key],array('localtion_name_id'=>$localton_id));
                    $localton_to = get_listname_localtion_warehouse($value['localtion_to']);
                    $items[$key] = array_merge($items[$key],array('localtion_name_to'=>$localton_to));
                }else
                {
                    $table = get_table_where('tbltype_items',array('type'=>$value['type']),'','row')->table;
                    $this->db->select($table.'.name as name_item,'.$table.'.images as avatar,'.$table.'.code as code_item,tblunits.unit as unit')->distinct();
                    $this->db->from( $table);
                    $this->db->join('tblunits','tblunits.unitid='.$table.'.unit_id','left');
                    $this->db->where($table.'.id',$value['id_items']);
                    $items[$key] = array_merge($items[$key],$this->db->get()->row_array());
                    $localton_id = get_table_where('tbllocaltion_warehouses',array('id'=>$value['localtion_id']),'','row')->name_parent;
                    $items[$key] = array_merge($items[$key],array('localtion_name_id'=>$localton_id));
                    $localton_to = get_table_where('tbllocaltion_warehouses',array('id'=>$value['localtion_to']),'','row')->name_parent;
                    $items[$key] = array_merge($items[$key],array('localtion_name_to'=>$localton_to));                }
            }
            return $items;
        }
        public function increaseTranfersWarehouse($id)
        {
            $Tranfers=$this->get($id);
            $count=0;
            if($Tranfers)
            {
                $date_warehouse = date('Y-m-d H:i:s');
                $warehouse_id = $Tranfers->warehouse_id;
                $warehouse_to = $Tranfers->warehouse_to;
                $date_import = $Tranfers->date;
                foreach ($Tranfers->items as $key => $value) 
                {   
                    // giảm kho
                    $localtion  =  $value['localtion_id'];
                    $product_id = $value['id_items'];
                    $type_items = $value['type'];
                    $quantity = $value['quantity_net'];

                    $count=decreaseTransferWarehuseQuantity($warehouse_id,$value['id'],$product_id,$quantity,$localtion,$type_items);
                    //trừ kho tổng
                    decreaseWarehuseQuantity($warehouse_id,$localtion,$product_id,$quantity,$type_items);

                    //tăng kho
                    $localtion_to  =  $value['localtion_to'];
                    increaseTransferWarehuseQuantity($warehouse_id,$id,$date_warehouse,$date_import,$product_id,$quantity,$localtion,$type_items);
                    $count=dincreaseTransferWarehuseQuantity($warehouse_to,$id,$date_warehouse,$date_import,$product_id,$quantity,$localtion_to,$type_items);
                    //tăng kho tổng
                    increaseWarehuseQuantity($warehouse_to,$localtion_to,$product_id,$quantity,$type_items);

                }
            }        
            if ($count) {
                return true;
            }
            return false;
        }
            //giảm và tăng kho chuyển kho xóa dữ liệu trong kho
        public function decreaseWarehouse($id,$warehouse_id,$data)
        {
            if(is_numeric($id)&&!empty($data))
            {
                //tăng kho khi xóa chuyển
                foreach ($data as $key => $value) {

                    $import = explode('|', trim($value['id_import'],'|'));
                    foreach ($import as $k => $v) {
                        $id_import = explode('-', $v);
                    $quantity = get_table_where('tblwarehouse_product',array('id'=>$id_import[0]),'','row');
                    $quantity_net =$id_import[1]; 

                    $id_export =  str_replace('CK-'.$value['id'].'|', '', $quantity->id_export);
                    $this->db->where('id',$id_import[0]);
                    $this->db->update('tblwarehouse_product',array('quantity_export'=>($quantity->quantity_export - $quantity_net),'quantity_left'=>($quantity->quantity_left + $quantity_net),'id_export'=>$id_export));
                    }
                    increaseWarehuseQuantity($warehouse_id,$value['localtion_id'],$value['id_items'],$value['quantity_net'],$value['type']);

                }
                //Giảm kho tổng
                $warehouse_product = get_table_where("tblwarehouse_product",array('import_id'=>$id,'type_export'=>2));
                $this->db->delete('tblwarehouse_product',array('import_id'=>$id,'type_export'=>2));
                
                foreach ($warehouse_product as $key => $value) {
                decreaseWarehuseQuantity($value['warehouse_id'],$value['localtion'],$value['product_id'],$value['quantity'],$value['type_items']);
                }

            }        
                return true;
        } 
}