<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$_aColumns     = array(
    'tblitems.group_id',
    'tblitems.code',
    'tblitems.name'
    );

foreach ($warehouse as $key=>$value)
{
    array_push($_aColumns,$value.'_0');
}
foreach ($warehouse as $key=>$value)
{
    array_push($_aColumns,$value.'_1');
}
foreach ($warehouse as $key=>$value)
{
    array_push($_aColumns,$value.'_2');
}
foreach ($warehouse as $key=>$value)
{
    array_push($_aColumns,$value.'_3');
}
foreach ($warehouse as $key=>$value)
{
    array_push($_aColumns,$value.'_4');
}
foreach ($warehouse as $key=>$value)
{
    array_push($_aColumns,$value.'_5');
}
foreach ($warehouse as $key=>$value)
{
    array_push($_aColumns,$value.'_6');
}
array_push($_aColumns,'SUM');
if($this->_instance->input->post('rel_type')!='products'){
    foreach ($warehouse as $key=>$value)
    {
        array_push($_aColumns,$value.'_7');
    }
}
else
{
    foreach ($warehouse as $key=>$value)
    {
        array_push($_aColumns,$value.'_8');
    }
}


if($this->_instance->input->post('rel_type')!='products')
{
    array_push($_aColumns,'suppliers');
}
//$start_date=date('Y-m-d');
$aColumns     = array(
    'tblitems.group_id',
    'tblitems.code',
    'tblitems.name',
    'tblitems.rel_type'
);
$sIndexColumn = "id";
$sTable       = 'tblitems';
$where = array();

$filter  = array();
$colums=array(1,2,3,4,5,6,7);
$colums_warehouse=array();
if($this->_instance->input->post()) {
    $item=$this->_instance->input->post('items');
    $rel_type=$this->_instance->input->post('rel_type');
    if($this->_instance->input->post('items'))
    {
        array_push($filter, 'AND tblitems.id IN (' . implode(', ', $item) . ')');
    }
    if($this->_instance->input->post('rel_type'))
    {
        $rel="";
        foreach ($rel_type as $val)
        {
            $rel.='"'.$val.'"'.',';
        }
        $rel=trim($rel,',');
        array_push($filter, 'AND tblitems.rel_type="'.$this->_instance->input->post('rel_type').'"');
    }

    if($this->_instance->input->post('warehouse')){
        $colums_warehouse=$this->_instance->input->post('warehouse');
    }
    if($this->_instance->input->post('colum')){
        $colums=$this->_instance->input->post('colum');
    }
    if($this->_instance->input->post('_date'))
    {
        $start_date=to_sql_date($this->_instance->input->post('_date'));
        $start_end=date('Y-m-d', strtotime($start_date. ' - 30 days'));
    }
    if($this->_instance->input->post('date_start')&&$this->_instance->input->post('date_end'))
    {
        $start_date=to_sql_date($this->_instance->input->post('date_start'));
        $start_end=to_sql_date($this->_instance->input->post('date_end'));
    }

    if($this->_instance->input->post('week'))
    {
        $week=$this->_instance->input->post('week');
        array_push($filter, 'AND tblitems.week="'.$week.'"');
    }
}

if (count($filter) > 0) {
    array_push($where, 'AND (' . prepare_dt_filter($filter) . ')');
}
$order_by = '';
$join             = array();
$additionalSelect = array('id','week');
$result           = data_tables_init($aColumns, $sIndexColumn, $sTable ,$join, $where, $additionalSelect,$order_by);
$output           = $result['output'];
$rResult          = $result['rResult'];

$currentPage=$this->_instance->input->post('start');
$currentall=$output['iTotalRecords'];
// var_dump($_aColumns);die;
$_warehouse=$warehouse;
$_warehouse_not=array();
if($colums_warehouse!=array())
{
    if($colums_warehouse!=array())
    {
        foreach ($colums_warehouse as $key=>$value)
        {
            $_warehouse_not[]=$warehouse[$value-3];
        }
    }
    $warehouse=$_warehouse_not;
}
//var_dump($start_date.'---'.$start_end);
foreach ($rResult as $r=> $aRow) {
    $row = array();
    $archive=array();
    $difference=array();
    $total=0;
    for ($i = 0; $i < count($_aColumns); $i++) {
        $_data = $aRow[$_aColumns[$i]];
        foreach ($warehouse as $key=>$value){
            if($_aColumns[$i]==$value.'_0'){
                $_data=$value.'_0';
                $opening_balance=null;
                $opening_balance=get_table_join_sum('tblopening_balance',
                    array('tblitems'=>'tblopening_balance.id_product=tblitems.id'),
                    array('tblopening_balance.id_product'=>$aRow['id'],'tblopening_balance.id_warehouse'=>$value),
                    'quantity');
                //tổng số dư đầu kỳ
                $_data=($opening_balance?$opening_balance:0);
                $archive[$value]+=$_data;
                $_data=number_format_data($_data);
                $_data=($_data!=0)?number_format_data($_data):'-';
            }
            if($_aColumns[$i]==$value.'_1'){
                $_data=$value.'_1';
                $import=0;
                $import_products=0;
                $import_products_tag=0;
                $import_transfer=0;
//                if(in_array($colums,1))
                  {

                      if($start_date&&$start_end)
                      {
                         $array_import= array('tblimports.status'=>2,
                             'tblimport_items.warehouse_id'=>$value,
                             'tblimport_items.product_id'=>$aRow['id'],
                             'tblimports.rel_type'=>"internal",
                             'tblimports.date >='=>$start_date,
                             'tblimports.date <='=>$start_end
                             );

                         $array_import_product=array('tblproduction_orders.status'=>2,
                             'tblproduction_orders.warehouse_to'=>$value,
                             'tblproduction_order_items_product.id_product'=>$aRow['id'],
                             'tblproduction_orders.date >='=>$start_date,
                             'tblproduction_orders.date <='=>$start_end
                             );
                          $array_import_product_tag=array('tblproduction_orders_tag.status'=>2,
                              'tblproduction_orders_tag_detail.warehouse'=>$value,
                              'tblproduction_orders_tag_detail.quantity_from'=>$aRow['id'],
                              'tblproduction_orders_tag.date >='=>$start_date,
                              'tblproduction_orders_tag.date <='=>$start_end
                          );
                          $array_import_transfer=array('tblimports.status'=>2,
                              'tblimport_items.warehouse_id_to'=>$value,
                              'tblimport_items.product_id'=>$aRow['id'],
                              'tblimports.rel_type'=>"transfer",
                              'tblimports.date >='=>$start_date,
                              'tblimports.date <='=>$start_end
                          );
                      }
                      else
                      {
                          $array_import=array('tblimports.status'=>2,'tblimport_items.warehouse_id'=>$value,'tblimport_items.product_id'=>$aRow['id'],'tblimports.rel_type'=>"internal");
                          $array_import_transfer=array('tblimports.status'=>2,'tblimport_items.warehouse_id_to'=>$value,'tblimport_items.product_id'=>$aRow['id'],'tblimports.rel_type'=>"transfer");
                          if($rel_type=='products')
                          {
                            $array_import_product=array('tblproduction_orders.status'=>2,'tblproduction_orders.warehouse_to'=>$value,'tblproduction_order_items_product.id_product'=>$aRow['id']);
                          }
                          $array_import_product_tag=array('tblproduction_orders_tag.status'=>2,'tblproduction_orders_tag_detail.warehouse'=>$value,'tblproduction_orders_tag_detail.quantity_from'=>$aRow['id']);
                      }
                      if($aRow['rel_type']!='products')
                      {
                          unset($array_import_product['tblproduction_orders.status']);
                      }
                    $import=get_table_join_sum('tblimports',
                        array('tblimport_items'=>'tblimport_items.import_id=tblimports.id'),
                        $array_import,
                        'quantity');
                    $import_transfer=get_table_join_sum('tblimports',
                        array('tblimport_items'=>'tblimport_items.import_id=tblimports.id'),
                        $array_import_transfer,
                        'quantity');
                    //tổng nhập import
                      if($rel_type=='products') {
                          $import_products = get_table_join_sum('tblproduction_orders',
                              array('tblproduction_order_items_product' => 'tblproduction_order_items_product.id_production_order=tblproduction_orders.id'),
                              $array_import_product,
                              'quantity');
                      }

                    $import_products_tag=get_table_join_sum('tblproduction_orders_tag',
                        array('tblproduction_orders_tag_detail'=>'tblproduction_orders_tag_detail.id_production_order_tag=tblproduction_orders_tag.id'),
                        $array_import_product_tag,
                        'quantity_from');
                }
                //tổng nhập đơn hàng xản xuất
//                if($import)
                $_data=$import+$import_products+$import_products_tag+$import_transfer;
                $archive[$value]+=$_data;
                $_data=($_data!=0)?number_format_data($_data):'-';
            }
            if($_aColumns[$i]==$value.'_2'){
                $_data=$value.'_2';
                //xuat thanh pham
                $out_products_tag=0;
//                if(in_array($colums,2))
                {
                    if ($aRow['rel_type'] = 'products') {
                        if($start_date&&$start_end)
                        {
                            $_array_import_product_tag=array('tblproduction_orders_tag.status' => 2,
                                'tblproduction_orders_tag_detail.warehouse' => $value,
                                'tblproduction_orders_tag_detail.id_product_to' => $aRow['id'],
                                'tblproduction_orders_tag.date >='=>$start_date,
                                'tblproduction_orders_tag.date <='=>$start_end
                            );
                        }
                        else
                        {
                            $_array_import_product_tag=array('tblproduction_orders_tag.status' => 2, 'tblproduction_orders_tag_detail.warehouse' => $value, 'tblproduction_orders_tag_detail.id_product_to' => $aRow['id']);
                        }
                        $out_products_tag = get_table_join_sum('tblproduction_orders_tag',
                            array('tblproduction_orders_tag_detail' => 'tblproduction_orders_tag_detail.id_production_order_tag=tblproduction_orders_tag.id'),
                            $_array_import_product_tag,
                            'quantity');

                        $quantity=0;
                        $this->_instance->db->select('sum(quantity) as sum_quantity');
                        $this->_instance->db->join('tblexport_items', 'tblexport_items.export_id=tblexports.id');
                        $exports = $this->_instance->db->get_where('tblexports',
                            array('status' => 2,
                                'tblexport_items.product_id' => $aRow['id'],
                                'tblexport_items.warehouse_id' => $value
                            ))->row();
                        if ($exports) {
                            $quantity += $exports->sum_quantity;
                            $out_products_tag=($out_products_tag)?$out_products_tag+$quantity:0+$quantity;
                        }

                        $array_import_transfer=array('tblimports.status'=>2,
                            'tblimport_items.warehouse_id'=>$value,
                            'tblimport_items.product_id'=>$aRow['id'],
                            'tblimports.rel_type'=>"transfer",
                            'tblimports.date >='=>$start_date,
                            'tblimports.date <='=>$start_end
                        );
                        $import_transfer=get_table_join_sum('tblimports',
                            array('tblimport_items'=>'tblimport_items.import_id=tblimports.id'),
                            $array_import,
                            'quantity');
                        $out_products_tag=($out_products_tag?$out_products_tag:0)+($import_transfer?$import_transfer:0);

                    }
                }
                $_data=($out_products_tag)?$out_products_tag:0;
                $archive[$value]-=$_data;
                $_data=number_format_data($_data);
                $_data=($_data!=0)?number_format_data($_data):'-';

            }
            if($_aColumns[$i]==$value.'_3'){
                $_data=$value.'_3';
//                if(in_array($colums,3))
                {
                    if ($aRow['rel_type'] == 'materials') {
                        //xuat nguyen vat lieu đơn hàng sản xuất
                        if($start_date&&$start_end)
                        {
                            $_array_out_materialis=array(
                                'tblproduction_orders.warehouse_to' => $value,
                                'tblproduction_order_items_materials.id_material' => $aRow['id'],
                                'tblproduction_orders.date >='=>$start_date,
                                'tblproduction_orders.date <='=>$start_end

                            );
                        }
                        else
                        {
                            $_array_out_materialis=array('tblproduction_orders.status' => 2,
                                'tblproduction_orders.warehouse_to' => $value,
                                'tblproduction_order_items_materials.id_material' => $aRow['id']

                            );
                        }
                        $out_materials = get_table_join_sum('tblproduction_orders',
                            array('tblproduction_order_items_materials' => 'tblproduction_order_items_materials.id_production_order=tblproduction_orders.id'),
                            $_array_out_materialis,
                            'quantity');
                        $_data = $out_materials;
                    }
                    elseif ($aRow['rel_type'] == 'packages') {
                        //nhap thanh pham(bao bi)
                        if($start_date&&$start_end)
                        {
                            $_array_out_packages=array('tblproduction_orders.status' => 2,
                                'tblproduction_orders.warehouse_to' => $value,
                                'tblproduction_order_items_product.id_product' => $aRow['id'],
                                'tblproduction_orders.user_admin_date >='=>$start_date,
                                'tblproduction_orders.user_admin_date <='=>$start_end

                            );
                        }
                        else
                        {
                            $_array_out_packages=array('tblproduction_orders.status' => 2,
                                'tblproduction_orders.warehouse_to' => $value,
                                'tblproduction_order_items_product.id_product' => $aRow['id']
                            );
                        }
                        $import_products = get_table_join_sum('tblproduction_orders',
                            array('tblproduction_order_items_product' => 'tblproduction_order_items_product.id_production_order=tblproduction_orders.id'),
                            $_array_out_packages,
                            'quantity');

                        //nhap thanh pham sang bao(bao bi)

                        if($start_date&&$start_end)
                        {
                            $_array_out_packages_tag=array('tblproduction_orders_tag.status' => 2,
                                'tblproduction_orders_tag_detail.warehouse' => $value,
                                'tblproduction_orders_tag_detail.id_product_from' => $aRow['id'],
                                'tblproduction_orders_tag.user_admin_date >='=>$start_date,
                                'tblproduction_orders_tag.user_admin_date <='=>$start_end
                            );
                        }
                        else
                        {
                            $_array_out_packages_tag=array('tblproduction_orders_tag.status' => 2,
                                'tblproduction_orders_tag_detail.warehouse' => $value,
                                'tblproduction_orders_tag_detail.id_product_from' => $aRow['id']
                            );
                        }
                        $import_products_tag = get_table_join_sum('tblproduction_orders_tag',
                            array('tblproduction_orders_tag_detail' => 'tblproduction_orders_tag_detail.id_production_order_tag=tblproduction_orders_tag.id'),
                            $_array_out_packages_tag,
                            'quantity_from');
                        $packages = get_table_where('tblpackages_items', array('product_id' => $aRow['id']), '', 'row');
                        $sum_product_orders = $import_products;
                        $sum_product_orders_tag = $import_products_tag;
                        if ($packages->quantity && $packages->quantity != 0) {
                            $sum_product_orders = ceil($import_products / $packages->quantity);
                            $sum_product_orders_tag = ceil($import_products_tag / $packages->quantity);
                        }
                        $_data = $sum_product_orders + $sum_product_orders_tag;
                    } else {
                        $_data = 0;
                    }
                }
                $archive[$value]-=$_data;
                $_data=number_format_data($_data);
                $_data=($_data!=0)?number_format_data($_data):'-';
            }
            if($_aColumns[$i]==$value.'_4'){
                $_data=$archive[$value];
                $_data=number_format_data($_data);
                $_data=($_data!=0)?number_format_data($_data):'-';
            }
            if($_aColumns[$i]==$value.'_5'){
                $difference[$value]=0;
                $_data=$difference[$value];
                $_data=number_format_data($_data);
                $_data=($_data!=0)?number_format_data($_data):'-';
            }
            if($_aColumns[$i]==$value.'_6'){
                $_data=$archive[$value]+$difference[$value];
                $total+=$_data;
                $_data=number_format_data($_data);
                $_data=($_data!=0)?number_format_data($_data):'-';
            }
            if($_aColumns[$i]==$value.'_7'){
                if(get_table_where('tblwarehouses',array('warehouseid'=>$value),'','row')->average==1){
                    $price_avg=0;

                    $price_avg=get_table_where('tblwarehouses_products',array('warehouse_id'=>$value,'product_id'=>$aRow['id']),'','row')->medium;
                    $_data=($price_avg!=0)?number_format_data($price_avg):'-';
                }
                else
                {
                    $_result=get_table_where('tblwarehouses_products',array('warehouse_id'=>$value,'product_id'=>$aRow['id']),'','row');
                    $_data=($_result&&$_result->current_price!=0)?number_format_data($_result->current_price):'-';
                }
            }
            if($_aColumns[$i]==$value.'_8'){
                $quantity_product=0;
                $quantity_product=get_table_where('tblwarehouses_products',array('warehouse_id'=>$value,'product_id'=>$aRow['id']),'','row')->product_quantity;
                $quantity=($quantity_product!=0)?$quantity_product:0;
                $this->_instance->db->select('sum(quantity) as sum_quantity');
                $this->_instance->db->join('tblexport_items', 'tblexport_items.export_id=tblexports.id');
                $exports = $this->_instance->db->get_where('tblexports',
                    array('status!=' => 2,
                        'tblexport_items.product_id' => $aRow['id'],
                        'tblexport_items.warehouse_id' => $value
                    ))->row();
                if ($exports) {
                    $quantity -= $exports->sum_quantity;
                }
                $_data=($quantity!=0)?number_format_data($quantity):'-';
            }
        }
        if($_aColumns[$i]=="SUM")
        {
            $_data=$total;
            $_data=number_format_data($_data);
            $_data=($_data!=0)?number_format_data($_data):'-';
        }
        if($_aColumns[$i]=='not_price'){
            $_data=0;
            $_data=($_data!=0)?number_format_data($_data):'-';
        }
        if($_aColumns[$i]=='suppliers')
        {
            if($start_date&&$start_end)
            {
               $array_suppliers= array('product_id'=>$aRow['id'],'tblimports.date>='=>$start_date,'tblimports.date<='=>$start_end,'status'=>2);
            }
            else
            {
                $array_suppliers= array('product_id'=>$aRow['id'],'status'=>2);
            }
            $price=get_table_join('tblimports',array('tblimport_items'=>'tblimport_items.import_id=tblimports.id'),
                $array_suppliers
                ,'date desc','','row');
            if($price->supplier_id)
            {
                $_data=get_table_where('tblsuppliers',array('userid'=>$price->supplier_id),'','row')->company;
            }
            else
            {
                $_data="";
            }
        }

        $row[] = $_data;
        if($this->_instance->input->post('week'))
        {

            if($rel_type!="products")
            {
                if($start_date&&$start_end)
                {
                    $array_quantity=array('tblproduction_order_items_materials.id_material'=>$aRow['id'],'tblproduction_orders.date>='=>$start_date,'tblproduction_orders.date<='=>$start_end,'status'=>2);

                }
                else
                {
                    $array_quantity=array('tblproduction_order_items_materials.id_material'=>$aRow['id'],'status'=>2);
                }

                $quantity=get_table_join_sum('tblproduction_orders',
                    array('tblproduction_order_items_materials'=>'tblproduction_order_items_materials.id_production_order=tblproduction_orders.id'),
                    $array_quantity
                    ,'quantity');
            }
            if($quantity>0)
            {
                $row_report=$total/$quantity;
                if($row_report>($week/4))
                {
                    $row['DT_RowClass'] = 'alert-danger '.$row_report;
                }
            }
        }
        else
        {
            if($aRow['week']&&$aRow>0)
            {
                if($rel_type!="products")
                {
                    if($start_date&&$start_end)
                    {
                        $array_quantity=array('tblproduction_order_items_materials.id_material'=>$aRow['id'],'tblproduction_orders.date>='=>$start_date,'tblproduction_orders.date<='=>$start_end,'status'=>2);

                    }
                    else
                    {
                        $array_quantity=array('tblproduction_order_items_materials.id_material'=>$aRow['id'],'status'=>2);
                    }

                    $quantity=get_table_join_sum('tblproduction_orders',
                        array('tblproduction_order_items_materials'=>'tblproduction_order_items_materials.id_production_order=tblproduction_orders.id'),
                        $array_quantity
                        ,'quantity');
                }
                if($quantity>0)
                {
                    $row_report=$total/$quantity;
                    if($row_report>($aRow['week']/4))
                    {
                        $row['DT_RowClass'] = 'alert-danger '.$row_report;
                    }
                }
            }
        }
    }

   $output['aaData'][] = $row;
}
