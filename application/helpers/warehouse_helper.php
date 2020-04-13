<?php

defined('BASEPATH') or exit('No direct script access allowed');
    function export_AdjuWarehuseQuantity($warehouse_id,$adju_id,$date_warehouse,$date_export,$product_id,$quantity,$localtion,$type_items)
    {
        $CI = &get_instance();
        if (isset($product_id) && isset($warehouse_id) && is_numeric($quantity)&& is_numeric($adju_id)) {
                $data=array(
                    'product_id'=>$product_id,
                    'warehouse_id'=>$warehouse_id,
                    'quantity'=>$quantity,
                    'localtion'=>$localtion,
                    'export_id'=>$adju_id,
                    'type_items'=>$type_items,
                    'date_export'=>$date_export,
                    'date_warehouse'=>$date_warehouse,
                    'type_export'=>3,
                    );
                $CI->db->insert('tblwarehouse_export',$data);
            }
            if($CI->db->affected_rows()>0) 
            {
                return true;
            }
        return false;
    }
    function decreaseAdjuWarehuseQuantity($warehouse_id,$transfer,$product_id,$quantity,$localtion,$type_items)
    {     
        $CI = &get_instance();
        if (isset($product_id) && isset($warehouse_id) && is_numeric($quantity)&& is_numeric($transfer)) {
            $TransferWarehuseQuantity = get_table_where('tblwarehouse_product',array('product_id'=>$product_id,'warehouse_id'=>$warehouse_id,'localtion'=>$localtion,'quantity_left'>0));
            usort($TransferWarehuseQuantity, ch_make_cmp(['date_import' => "ASC"]));
            $id_import = '';
                foreach ($TransferWarehuseQuantity as $key => $value) {
                    if($quantity > 0)
                    {
                        $quantitynet = $quantity;
                        $quantity = $quantity - $value['quantity_left'];
                        if($quantity  < 0)
                        {
                            $id_import = $value['id'].'-'.($value['quantity_export']+$quantitynet).'|'.$id_import;
                            $quantity = 0;
                            $export = 'DC-'.$transfer.'|'.$value['id_export'];
                            $CI->db->where('id',$value['id']);
                            $CI->db->update('tblwarehouse_product',array('quantity_left'=>($value['quantity']-$quantitynet),'quantity_export'=>($value['quantity_export']+$quantitynet),'id_export'=>$export));
                        }
                        else
                        {
                            $id_import = $value['id'].'-'.($value['quantity_left']+$value['quantity_export']).'|'.$id_import;
                            $CI->db->where('id',$value['id']);
                            $CI->db->update('tblwarehouse_product',array('quantity_left'=>0,'quantity_export'=>($value['quantity_left']+$value['quantity_export'])));
                        }
                    }
                }
            }
            if($CI->db->affected_rows()>0) 
            {
                $CI->db->where('id',$transfer);
                $CI->db->update('tbladjusted_items',array('id_import'=>$id_import));
                return true;
            }
        return false;
    }
    function decreaseTransferWarehuseQuantity($warehouse_id,$transfer,$product_id,$quantity,$localtion,$type_items)
    {     
        $CI = &get_instance();
        if (isset($product_id) && isset($warehouse_id) && is_numeric($quantity)&& is_numeric($transfer)) {
            $TransferWarehuseQuantity = get_table_where('tblwarehouse_product',array('product_id'=>$product_id,'warehouse_id'=>$warehouse_id,'localtion'=>$localtion,'quantity_left'>0));
            usort($TransferWarehuseQuantity, ch_make_cmp(['date_import' => "ASC"]));
            $id_import = '';
                foreach ($TransferWarehuseQuantity as $key => $value) {
                    if($quantity > 0)
                    {
                        $quantitynet = $quantity;
                        $quantity = $quantity - $value['quantity_left'];
                        if($quantity  < 0)
                        {
                            $id_import = $value['id'].'-'.($value['quantity_export']+$quantitynet).'|'.$id_import;
                            $quantity = 0;
                            $export = 'CK-'.$transfer.'|'.$value['id_export'];
                            $CI->db->where('id',$value['id']);
                            $CI->db->update('tblwarehouse_product',array('quantity_left'=>($value['quantity']-$quantitynet),'quantity_export'=>($value['quantity_export']+$quantitynet),'id_export'=>$export));
                        }
                        else
                        {
                            $id_import = $value['id'].'-'.($value['quantity_left']+$value['quantity_export']).'|'.$id_import;
                            $CI->db->where('id',$value['id']);
                            $CI->db->update('tblwarehouse_product',array('quantity_left'=>0,'quantity_export'=>($value['quantity_left']+$value['quantity_export'])));
                        }
                    }
                }
            }
            if($CI->db->affected_rows()>0) 
            {
                $CI->db->where('id',$transfer);
                $CI->db->update('tbltransfer_warehouse_detail',array('id_import'=>$id_import));
                return true;
            }
        return false;
    }
    function increaseadjuProductQuantity($warehouse_id,$id_import,$date_warehouse,$date_import,$product_id,$quantity,$localtion,$type_items)
    {     
        $CI = &get_instance();
        if (isset($product_id) && isset($warehouse_id) && is_numeric($quantity)&& is_numeric($id_import)) {
                $data=array(
                    'product_id'=>$product_id,
                    'warehouse_id'=>$warehouse_id,
                    'quantity'=>$quantity,
                    'localtion'=>$localtion,
                    'import_id'=>$id_import,
                    'type_items'=>$type_items,
                    'date_import'=>$date_import,
                    'date_warehouse'=>$date_warehouse,
                    'quantity_left'=>$quantity,
                    'quantity_export'=>0,
                    'type_export'=>3,
                    );
                $CI->db->insert('tblwarehouse_product',$data);
            }
            if($CI->db->affected_rows()>0) 
            {
                return true;
            }
        return false;
    }
    function increaseProductQuantity($warehouse_id,$id_import,$date_warehouse,$date_import,$product_id,$quantity,$localtion,$type_items)
    {     
        $CI = &get_instance();
        if (isset($product_id) && isset($warehouse_id) && is_numeric($quantity)&& is_numeric($id_import)) {
                $data=array(
                    'product_id'=>$product_id,
                    'warehouse_id'=>$warehouse_id,
                    'quantity'=>$quantity,
                    'localtion'=>$localtion,
                    'import_id'=>$id_import,
                    'type_items'=>$type_items,
                    'date_import'=>$date_import,
                    'date_warehouse'=>$date_warehouse,
                    'quantity_left'=>$quantity,
                    'quantity_export'=>0,
                    'type_export'=>1,
                    );
                $CI->db->insert('tblwarehouse_product',$data);
            }
            if($CI->db->affected_rows()>0) 
            {
                return true;
            }
        return false;
    }
    function increaseTransferWarehuseQuantity($warehouse_id,$export_id,$date_warehouse,$date_export,$product_id,$quantity,$localtion,$type_items)
    {
        $CI = &get_instance();
        if (isset($product_id) && isset($warehouse_id) && is_numeric($quantity)&& is_numeric($export_id)) {
                $data=array(
                    'product_id'=>$product_id,
                    'warehouse_id'=>$warehouse_id,
                    'quantity'=>$quantity,
                    'localtion'=>$localtion,
                    'export_id'=>$export_id,
                    'type_items'=>$type_items,
                    'date_export'=>$date_export,
                    'date_warehouse'=>$date_warehouse,
                    'type_export'=>2,
                    );
                $CI->db->insert('tblwarehouse_export',$data);
            }
            if($CI->db->affected_rows()>0) 
            {
                return true;
            }
        return false;
    }
    function dincreaseTransferWarehuseQuantity($warehouse_id,$id_import,$date_warehouse,$date_import,$product_id,$quantity,$localtion,$type_items)
    {     
        $CI = &get_instance();
        if (isset($product_id) && isset($warehouse_id) && is_numeric($quantity)&& is_numeric($id_import)) {
                $data=array(
                    'product_id'=>$product_id,
                    'warehouse_id'=>$warehouse_id,
                    'quantity'=>$quantity,
                    'localtion'=>$localtion,
                    'import_id'=>$id_import,
                    'type_items'=>$type_items,
                    'date_import'=>$date_import,
                    'date_warehouse'=>$date_warehouse,
                    'quantity_left'=>$quantity,
                    'quantity_export'=>0,
                    'type_export'=>2,
                    );
                $CI->db->insert('tblwarehouse_product',$data);
            }
            if($CI->db->affected_rows()>0) 
            {
                return true;
            }
        return false;
    }    
    function increaseWarehuseQuantity($warehouse_id,$localtion,$product_id,$quantity,$type_items)
    {
        $CI = &get_instance();
        if (isset($product_id) && isset($warehouse_id) && is_numeric($quantity)&& is_numeric($localtion)) {
            
            $product=$CI->db->get_where('tblwarehouse_items',array('id_items'=>$product_id,'warehouse_id'=>$warehouse_id,'localtion'=>$localtion,'type_items'=>$type_items))->row(); 
            if($product)
            {
                $total_quantity=$quantity+$product->product_quantity;
                $CI->db->update('tblwarehouse_items',array('product_quantity'=>$total_quantity),array('id'=>$product->id));
            }
            else
            {
                $data=array(
                    'id_items'=>$product_id,
                    'warehouse_id'=>$warehouse_id,
                    'product_quantity'=>$quantity,
                    'localtion'=>$localtion,
                    'type_items'=>$type_items
                    );
                $CI->db->insert('tblwarehouse_items',$data);
            }
            if($CI->db->affected_rows()>0) 
                return true;
        }
        return false;
    }
    function decreaseWarehuseQuantity($warehouse_id,$localtion,$product_id,$quantity,$type_items)
    {
        $CI = &get_instance();
        if (isset($product_id) && isset($warehouse_id) && is_numeric($quantity)&& is_numeric($localtion)) {
            
            $product=$CI->db->get_where('tblwarehouse_items',array('id_items'=>$product_id,'warehouse_id'=>$warehouse_id,'localtion'=>$localtion,'type_items'=>$type_items))->row(); 
            if($product)
            {
                $total_quantity=$product->product_quantity-$quantity;
                $CI->db->update('tblwarehouse_items',array('product_quantity'=>$total_quantity),array('id'=>$product->id));
            }
            if($CI->db->affected_rows()>0) 
                return true;
        }
        return false;
    }

    function get_localtion_warehouses_product($where=array())
    {
        $CI =& get_instance();
        $CI->db->select('tbllocaltion_warehouses.*,product_quantity');
        if(!empty($where))
        {
            $CI->db->where($where);
        }
        $CI->db->join('tblwarehouse_items','tblwarehouse_items.localtion=tbllocaltion_warehouses.id');
        $CI->db->where('product_quantity >= 0');
        $localtion=$CI->db->get('tbllocaltion_warehouses')->result_array();
        $string_option="<option></option>";
        foreach($localtion as $key=>$value)
        {
            if(!empty($value['id']))
            {   
                $name=get_listname_localtion_warehouse($value['id']);
                $string_option.='<option '.$checkeds.' quantity-id="'.$value['product_quantity'].'" data-content="'.$name.'('.$value['product_quantity'].')" content="'.$name.'" value="'.$value['id'].'" '.($value['child']?'child="'.$value['child'].'"':'').'>'.$name.'('.$value['product_quantity'].')</option>';
            }
        }
        return $string_option;
    }
    function exsit_localtion($warehouse_id='',$localtion='')
    {
        $CI =& get_instance();
        $CI->db->where('warehouse_id',$warehouse_id);
        $CI->db->where('localtion_warehouses_id',$localtion);
        $CI->db->join('tblimport_items','tblimport_items.id_import=tblimport.id');
        $import = $CI->db->get('tblimport')->row();

        $CI->db->where('warehouse_id',$warehouse_id);
        $CI->db->where('localtion_id',$localtion);
        $CI->db->join('tbltransfer_warehouse_detail','tbltransfer_warehouse_detail.id_transfer=tbltransfer_warehouse.id');
        $tran_id = $CI->db->get('tbltransfer_warehouse')->row();

        $CI->db->where('warehouse_to ',$warehouse_id);
        $CI->db->where('localtion_to',$localtion);
        $CI->db->join('tbltransfer_warehouse_detail','tbltransfer_warehouse_detail.id_transfer=tbltransfer_warehouse.id');
        $tran_to = $CI->db->get('tbltransfer_warehouse')->row();
        if(!empty($import) || !empty($tran_id) || !empty($tran_to))
        {
            return true;
        }else
        {
            return false;
        }
    }
