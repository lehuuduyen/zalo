<?php init_head(); ?>
<style type="text/css">
  .item-items .ui-sortable tr td input {
    width: 80px;
  }
.select2-search-choice-close{display:none !important;}
</style>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<?php
			echo form_open($this->uri->uri_string(), array('id' => 'import-form', 'class' => '_transaction_form invoice-form'));
			if (isset($invoice)) {
				echo form_hidden('isedit');
			}
			?>
			<div class="col-md-12">
				<div class="panel_s">
				 	<div class="additional"></div>
				 	<div class="panel-body">
				 	<?php 
						$type = '';
						if (!isset($items))
							$type = 'warning';
						elseif ($items->status == 0)
							$type = 'warning';
						elseif ($items->status == 1)
							$type = 'info';
						elseif ($items->status == 2)
							$type = 'success';

						?>
				 		<div class="ribbon <?= $type ?>" project-status-ribbon-2="">
				 			<?php 
								if (isset($items))
								{
									$status = format_import_status($items->status, '', false);
								}
								else
								{
									$status = format_import_status(-1, '', false);
								}
							?>
				 			<span><?= $status ?></span>
						 </div>
						 <?php if (isset($items)) { ?>
						<?php } ?>
						<h4 class="bold no-margin font-medium">
					     	<?php echo $title; ?>
					   	</h4>
						<hr />
				 		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				 			<div class="alert alert-warning text-center total_debt hide"></div>
				 			<div class="panel panel-primary">
								<div class="panel-heading"><?=_l('lead_general_info')?></div>
								<div class="panel-body">
									<table class="tnh-tb table-bordered table-hover dont-responsive-table m-group0">
										<tbody>
											<tr>
												<td>
													<label for="number" class="control-label">
														<small class="req text-danger">* </small>
														<?php echo _l('ch_code_old'); ?>
													</label>
												</td>
												<td>
													<div class="input-group">
									                  	<span class="input-group-addon">
									                  	<input type="text" id="id_order" class="hide" name="id_order" value="<?php echo (isset($purchase_order) ? ($purchase_order->id) : '')?>">
									                    <?php echo (isset($purchase_order) ? ($purchase_order->prefix) : get_option('prefix_import'));?>-</span>
														<?php 
																$value = (isset($purchase_order) ? ($purchase_order->code) :'');
															?>
									                    <input type="text" name="number" class="form-control" value="<?= $value ?>" readonly>
									                </div>
												</td>
												<td>
													<label for="number" class="control-label">
														<small class="req text-danger">* </small>
														<?php echo _l('ch_code_p'); ?>
													</label>
												</td>
												<td>
													<div class="input-group">
									                  	<span class="input-group-addon">
									                    <?php echo (isset($items) ? ($items->prefix) : get_option('prefix_import'));?>-</span>
														<?php 
																$number = sprintf('%06d', ch_getMaxID('id', 'tblimport') + 1);
																$value = (isset($items) ? ($items->code) : $number);
															?>
									                    <input type="text" name="number" class="form-control" value="<?= $value ?>" readonly>
									                </div>
												</td>
											</tr>
											<tr>
												<td>
													<label for="date" class="control-label">
														<small class="req text-danger">* </small>
														<?php echo _l('ch_date_p'); ?>
													</label>
												</td>
												<td>
													<?php $value = (isset($items) ? _d($items->date) : _d(date('Y-m-d'))); ?>
			                  						<?php echo render_date_input('date', '', $value); ?>
												</td>
												<td>
													<label for="suppliers_id" class="control-label">
														<small class="req text-danger">* </small>
														<?php echo _l('supplier'); ?>
													</label>
												</td>
												<td>
													<?php
														$value = (isset($purchase_order) ? $purchase_order->suppliers_id : '');
														echo render_select('suppliers_id',$suppliers,array('id','company'),'',$value);
													?>
												</td>
											</tr>
											<tr>
												<td>
													<label for="warehouse_id" class="control-label">
														<small class="req text-danger">* </small>
														<?php echo _l('warehouse'); ?>
													</label>
												</td>
												<td>
													<?php 
													$disabled =array();
													if($type_plan){
														$disabled = array('disabled'=>true);
														?>
														<input type="text" name="warehouse_id" value="8" class="hide">
														<?php 
													 }?>
													<?php
														$value = (isset($items) ? $items->warehouse_id : (($type_plan) ? 8 : ''));
														echo render_select('warehouse_id',$warehouse,array('id','name','code'),'',$value,$disabled);
													?>
												</td>
												<td>
													<label for="type_items" class="control-label">
														<?php echo _l('ch_type'); ?>
													</label>
												</td>
												<td>
													<?php
											         	echo render_select('type_items', $type_items, array('type', 'name'),'',-1);
													?>
												</td>
											</tr>
											<tr>
												<td>
													<label for="reason" class="control-label">
														<?php echo _l('ch_note_t'); ?>
													</label>
												</td>
												<td colspan="3">
													<?php $value = (isset($items) ? $items->note : ""); ?>
													<?php echo render_textarea('reason', '', $value); ?>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
						<?php
		                    $customer_custom_fields = false;
		                    if(total_rows(db_prefix().'customfields',array('fieldto'=>'imports','active'=>1)) > 0 ){
		                         $customer_custom_fields = true;
		                ?>
		                <?php } ?>
		                <?php if($customer_custom_fields) { ?>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				 			<div class="panel panel-info">
								<div class="panel-heading"><?=_l('custom_fields')?></div>
								<div class="panel-body">
					                
				                   	<div role="tabpanel" class="tab-pane" id="custom_fields">
				                    	<?php $value_id = (isset($items) ? $items->id : '');?>
				                      	<?php echo render_custom_fields('imports',$value_id); ?>
				                   	</div>
				                   
					           </div>
					       </div>
						</div>
						<div class="clearfix"></div>
						<?php } ?>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mbot30">
								<div class="row">
									<div class="col-md-12" >
										<?php
										    if(empty($items)){
										    ?>
										    <a href="#" onclick="load();return false;" class="btn btn-warning btn-icon" style="float: right;"><?=_l('load_item')?></a>
										    <?php
										    }
										    ?>
									</div>
								</div>
								<br>	
								<div class="clearfix"></div>
								<div class="panel panel-info">
									<div class="panel-heading">
										<?= lang('tnh_info_items') ?>
									</div>
								<div class="panel-body ">
								<div class="table-responsive" style="min-height: 300px">
									<!-- <table class="table items item-import no-mtop dont-responsive-table"> -->
										<table class="dt-tnh table item-import table-bordered table-hover">
										<thead>
											<tr>
												<th class="text-center"><a onclick="button_create()" class="btn btn-info btn-icon">+</a><?=_l('image')?><input type="hidden" id="itemID" value="" /></th>
												<th class="text-center"><?php echo _l('ch_items_name_t'); ?></th>
												<th class="text-center"><?php echo _l('warehouse_localtion'); ?></th>
												<th class="text-center"><?php echo _l('item_unit'); ?></th>
												<th class="text-center"><?php echo _l('item_quantity'); ?></th>
                                                <th class="text-center"><?php echo _l('item_quantity_confirm'); ?></th>
                                                <th class="text-center"><?php echo _l('tnh_price_import'); ?></th>
                                                <th class="text-center"><?php echo _l('promotion_suppliers'); ?></th>
                                                <th class="text-center"><?php echo _l('tax'); ?></th>
                                                <th class="text-center"><?php echo _l('invoice_total'); ?></th>
                                                <th class="text-center"><?php echo _l('note'); ?></th>
												<th></th>
											</tr>
										</thead>
										<tbody>
										<?php
                                            $i=0;
                                            $totalQuantity_approve=0;
                                            $totalQuantity=0;
                                            if(isset($items) && count($items->items) > 0) {
                                                foreach($items->items as $value) {
                                                    ?>
                                                <tr class="sortable item">
                                                    <td class="dragger avatart" style="text-align: center;"><img style="border-radius: 50%;width: 4em;height: 4em;" src="<?=(!empty($value['avatar'])?(file_exists($value['avatar']) ? base_url($value['avatar']) : (file_exists('uploads/materials/'.$value['avatar']) ? base_url('uploads/materials/'.$value['avatar']) : (file_exists('uploads/products/'.$value['avatar']) ? base_url('uploads/products/'.$value['avatar']) : base_url('assets/images/preview-not-available.jpg')))):base_url('assets/images/preview-not-available.jpg'))?>"><br><input type="hidden" id="type" name="items[<?php echo $i; ?>][type]" value="<?php echo $value['type']; ?>" /><input type="hidden" class="id" id="product_id" name="items[<?php echo $i; ?>][id]" value="<?=$value['product_id']?>" /><input type="hidden" class="id" id="id_import_items"  value="<?=$value['id']?>" /><div id="type_name"></div>
	                                                    <input type="hidden" class="id" name="items[<?php echo $i; ?>][id]" value="<?php echo $value['product_id']; ?>">
                                                    </td>
                                                    <td><input type="hidden" id="type"  value="<?php echo $value['type']; ?>" />
														<select style="width: 200px" class=" custom_item_select" id="custom_item_select_<?=$i?>" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
									                        	<?php foreach(get_options_search_cbo('items',$value['product_id'],$value['type']) as $t) { ?>
									                           		<option value="<?php echo $t['id']; ?>" <?php echo($t['id'] == $value['id'] ? 'selected' : '') ?>> <?=$t['name']?> </option>
									                           	<?php } ?>
									                    </select>
									                    <br><br>
							                            <div class="color"><?=format_item_color($value['product_id'],$value['type'])?></div>
												    </td>
												    <td>
												    	<div class="form-group ">
												             <select data-id="<?=$value['localtion_warehouses_id']?>" class="localtion_warehouses_id  " style="width: 200px;" id="localtion_warehouses_id_<?=$i?>" name="items[<?=$i?>][localtion_warehouses_id]" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
												             </select>
												        </div>
												    </td>
												    <td>
												    	<?=$value['unit']?>
												    </td>
												    <td>
												    	<input class="mainQuantity H_input height_auto" type="number" name="items[<?=$i?>][quantity]" max="<?=$value['quantity']?>" value="<?=number_format($value['quantity'])?>" />
												    </td>
												    <td>
												    	<input class="mainQuantityNet H_input height_auto"  min="1" type="number" name="items[<?=$i?>][quantity_net]" value="<?=number_format($value['quantity_net'])?>" />
												    </td>
												    <td >
												    	<input onkeyup="formatNumBerKeyUp(this)" class="height_auto H_input align_right price" type="text" name="items[<?=$i?>][price]" value="<?=number_format($value['price'])?>" />
												    </td>
												    <td class="align_right promotion_suppliers"><input class="hide" name="items[<?=$i?>][promotion_suppliers_1]" id="promotion_suppliers_1"  value="<?=$value['promotion_suppliers_1']?>"><input class="hide" id="promotion_suppliers" name="items[<?=$i?>][promotion_suppliers]" value="<?=$value['promotion_suppliers']?>"><p><?=number_format($value['promotion_suppliers'])?></p></td>
												    <td>
    													<select class="selectpicker tax" name="items[<?php echo $i; ?>][tax_id]" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
    														<option value data-taxrate="0"><?=_l('no_tax')?></option>
								                        	<?php foreach($tax as $t) { ?>
								                           		<option value="<?php echo $t['id']; ?>" data-taxrate="<?=$t['taxrate']?>" <?php echo($t['id'] == $value['tax_id'] ? 'selected' : '') ?>> <?=$t['name']?> </option>
								                           	<?php } ?>
								                        </select>
    													<input type="hidden" class="tax_rate" name="items[<?php echo $i; ?>][tax_rate]" value="<?=$value['tax_rate']?>">
        											</td>
        											<td class="align_right amount"><?=number_format($value['amount'])?></td>
        											<td>
        												<a href="#" class="btn btn-danger pull-right" onclick="deleteTrItem(this); return false;">
        													<i class="fa fa-times"></i>
        												</a>
        											</td>
                                                </tr>
                                            <?php 
                                            $i++;
                                            $totalQuantity+=$value['quantity'];
                                            $totalQuantity_approve+=$value['quantity_net'];
                                        	} }?>
										</tbody>
									</table>
								</div>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
				 		</div>
				 	</div>
			 	</div>
				<div class="btn-bottom-toolbar btn-toolbar-container-out text-right">
					<div class="width90 pull-left">
						<table class="table tnh-tb noMargin table-color_sum dont-responsive-table">
							<tbody>
								<tr>
									<td>
										<span class="bold"><?php echo _l('item_quantity_all'); ?> :</span>
									</td>
									<td class="total_quantity_all">
										<?php echo $totalQuantity ?>
									</td>
									<td>
										<span class="bold"><?php echo _l('item_quantity_approve'); ?> :</span>
									</td>
									<td class="total_quantity_approve">
										<?php echo $totalQuantity_approve ?>
									</td>
									<td>
										<span class="bold"><?php echo _l('total_price'); ?> :</span>
									</td>
									<td class="total_price">
										<?php echo $totalQuantity_approve ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
		            <a class="btn btn-info pull-right form-submitersss"><?=_l('submit')?>
		            </a>
		        </div>
			</div>
			
			<?php echo form_close(); ?>
		</div>
	</div>
</div>
<?php init_tail(); ?>
<script>

		$(document).on('change', '#suppliers_id', (e)=>{
	    	var currentQuantityInput = $(e.currentTarget);

			var id = $(currentQuantityInput).val();
			if(id == '') {
				$('.total_debt').addClass('hide');
			}
			else {
				$.post(admin_url + 'suppliers/get_debt/'+id,{[csrfData['token_name']] : csrfData['hash']}, function(data){3
					data = JSON.parse(data);
					if(data.success == true)
					{
						$('.total_debt').removeClass('hide');
						$('.total_debt').html('<?=_l('ch_wanring_debt_limit')?><b>'+data.total+'</b>');
					}
					else
					{
						$('.total_debt').addClass('hide');
					}
			    });
			}
		});
	$(function(){
		$('#suppliers_id').change();
		// $('#warehouse_id').change();
	});
		var warehouse_old;
		$(document).on('change', '#warehouse_id', (e)=>{
    	var warehouse_id = $('#warehouse_id').val();
    	if(warehouse_id != '')
    	{
		if(!$('table.item-import tbody tr.item').find('input[value=hau]').length)
        {
            createTrItemfist();
        }
    	}
    	var items = $('table.item-import tbody').find('tr.item');
    	if(items.length > 1)
    	{
    		var r = confirm("<?php echo _l('Thay đổi kho sẽ thay đổi các vị trí đã chọn cho hàng hóa!');?>");
            if (r == false) {
            	$('#warehouse_id').selectpicker('val',warehouse_old);
            	warehouse_id = warehouse_old;
                return false;
            } else {
            	$.each(items, (index,value)=>{
            	if($(value).find('td:nth-child(1)').find('input.type').val() != 'hau')
            	{
            	var indexs = $(value).find('td:nth-child(3)').find('select.localtion_warehouses_id');
                loadLocaltion_warehouses_change(warehouse_id,indexs);
        		}
	            });
            }
            return false;	
    	}
    	warehouse_old = warehouse_id;
		})
	var button_create = ()=>{
    	var warehouse_id = $('#warehouse_id').val();
		if((warehouse_id != ''))
    	{
		if(!$('table.item-import tbody tr.item').find('input[value=hau]').length)
        {
            createTrItemfist();
        }
    	}else{
        	alert_float('warning', "Bạn chưa chọn kho!");
            return;
        }
    }
		var type_of_document = <?php echo $type_of_document;?>;
		var idd = <?php echo $id;?>;
		var id_import = <?php echo $id_import;?>;
     $('.form-submitersss').on('click', (e)=>{
     	var product_id ='';
     	var test_quantity = 0;
        var items = $('table.item-import tbody tr');
        if(items.length == 0)
        {
            alert_float('danger', '<?=_l('ch_not_items')?>');
            return;
        }
        if(items.length == 1)
        {
        if(($('table.item-import tbody tr').find('input[value="hau"].type').length > 0)) {
            alert_float('danger', '<?=_l('ch_not_items')?>');
            return;
        }
    	}

        $.each(items, (index,value)=>{
        	if($(value).find('td:nth-child(1)').find('input.type').val() != 'hau')
        	{
        	var type = $(value).find('td:nth-child(1)').find('input').val()+'|'+$(value).find('td:nth-child(1)').find('input#product_id').val()+'|'+$(value).find('td:nth-child(6)').find('input').val();

        	product_id+=type+',';
        	}

        });
        dataString = {id_import:id_import,product_id:product_id,type_of_document:type_of_document,id:idd,[csrfData['token_name']] : csrfData['hash']};
        jQuery.ajax({
            type: "post",
            url: "<?=admin_url()?>import/test_quantity/",
            data: dataString,
            cache: false,
            success: function (data) {
            	data = JSON.parse(data);
		        if(data.test_quantity > 0)
		        {
		        	alert('<?=_l('ch_limit_items')?>');

		        	$.each(items, (index,value)=>{
		        		if($(value).find('td:nth-child(1)').find('input.type').val() != 'hau')
        				{
		        		$(value).find('td:nth-child(5)').find('input.mainQuantity').val(data.items[index].quantity);
            			$(value).find('td:nth-child(6)').find('input.mainQuantityNet').keyup();
            			}
			        });
		        	return;
		        } else {
		        	if($('input.error').length) {
			            e.preventDefault();
			            alert('<?=_l('ch_invalid_value')?>');  
			            return;
		        	}
		        	var a=confirm('<?=_l('ch_you_want_update')?>');
		        	if(a===false)
		        	{
		            	e.preventDefault();    
		        	} else {
		        		$('#import-form').submit();
		        	} 
		    	}
            }
        });

    });
		function init_ajax_searchs(e, t, a, i) {
		    var n = $("body").find(t);
		    var h = t;
		    if (n.length) {
		        var s = {
		            ajax: {
		                url: void 0 === i ? admin_url + "misc/get_relation_data" : i,
		                data: function() {
		                    var type = $('#type_items').val();
		                    var id_order = $('#id_order').val();
		                    var t = {[csrfData.token_name] : csrfData.hash};
		                    return t.type = e, t.rel_id = "", t.q = "{{{q}}}",t.type_items = type,t.id_order=id_order, void 0 !== a && jQuery.extend(t, a), t
		                }
		            },
		            locale: {
		                emptyTitle: app.lang.search_ajax_empty,
		                statusInitialized: app.lang.search_ajax_initialized,
		                statusSearching: app.lang.search_ajax_searching,
		                statusNoResults: app.lang.not_results_found,
		                searchPlaceholder: app.lang.search_ajax_placeholder,
		                currentlySelected: app.lang.currently_selected
		            },
		            requestDelay: 500,
		            cache: !1,
		            preprocessData: function(e) {
		                for (var t = [], a = e.length, i = 0; i < a; i++) {
		                    var n = {
		                        value: e[i].id,
		                        text: e[i].name,
		                        type_items: e[i].type_items
		                    }; t.push(n)
		                }
		                findItemasdsad(t,h);
		            },
		            preserveSelectedPosition: "after",
		            preserveSelected: !0
		        };
		        n.data("empty-title") && (s.locale.emptyTitle = n.data("empty-title")), n.selectpicker().ajaxSelectPicker(s);

		    }
		}

	$(function(){
		// validate_invoice_form();
		_validate_form($('#import-form'), {
        date: "required",
        suppliers_id: "required",
        number: "required",
        warehouse_id: "required",
        localtion_warehouses_id: "required"
    });
	});
	var itemList = <?php echo json_encode($type_items);?>;
	var findItemasdsad = (data,h) => {
		setTimeout(function(){
			$(h).find('option:gt(0)').remove();
		    $(h).selectpicker('refresh');
		    var count = data.length;
		    var html ='';
		    $.each(data, function(key,value){
		    	if(key == 0)
		    	{
		    	html +='<optgroup label="'+value.text+'">';
		    	}else if(value.value == 'h')
		    	{
		    		html +='</optgroup>';
		    		html +='<optgroup label="'+value.text+'">';	
		    	}else{
			    html +='<option data-id='+value.type_items+' value="' + value.value + '">'  + value.text + '</option>';
				}
			});
			html +='</optgroup>';	
			$(h).html(html);
			$(h).selectpicker('refresh');
			if(count > 3)
			{
				$(h).parents().find('.status').addClass('hide');
			}else{
				$(h).parents().find('.status').removeClass('hide');
			}
		}, 1);
	};
    var findItem = (type) => {
        var itemResult;
        $.each(itemList, (index,value) => {
            if(value.type == type) {
                itemResult = value.name;
                return false;
            }
        });
        return itemResult;
    };
    appendtype();
    	
    function appendtype()
    {

        var items = $('table.item-import tbody').find('tr.item');
        $.each(items, (index,value)=>{
        	$('#custom_item_select_'+index).select2();
        	var type = $(value).find('td:nth-child(2)').find('input#type').val();
        	var name_type = '<span class="label label-default mleft5 inline-block customer-group-list pointer" style="border:1px solid #e30000">' +findItem(type)+ '</span>';
            $(value).find('td:nth-child(1)').find('div#type_name').html(name_type);
          	var warehouse_id = $('#warehouse_id').val();
          	$('#localtion_warehouses_id_'+index).select2();
        	loadLocaltion_warehouses(warehouse_id,index);
        });
    }     
    function countrow()
    {
        if(!$('table.item-import tbody tr.item').find('input[value=hau]').length)
        {
            createTrItemfist();
        }
    }

    $(document).on('change', '.custom_item_select', (e)=>{
    	var warehouse_id = $('#warehouse_id').val();
    	if(empty(warehouse_id))
    	{
    		alert_float('warning', "Bạn chưa chọn kho!");
            return;
    	}
    	var currentQuantityInput = $(e.currentTarget);

		var id = $(currentQuantityInput).val();
		if(id == '') {
		}
		else {
    		var type = $('option:selected', currentQuantityInput).attr('data-id');
    		var id_order = $('#id_order').val();
			$.post(admin_url + 'import/get_items_order/'+id+'/'+type+'/'+id_order,{[csrfData['token_name']] : csrfData['hash']}, function(item){
				var item = JSON.parse(item);
				createTrItem(item,currentQuantityInput,type);
		    });
		}
	});
	var uniqueArray = <?=$i?>;
	var taxes_dropdown_template=<?=json_encode($taxes)?>;
    var createTrItem = (item,currentQuantityInput,type) => {
        if(typeof(item)=='undefined' || item.length==0) return;
        if( ($('table.item-import tbody tr').find('input[value=' + item.id + ']#product_id').length > 0) && ($('table.item-import tbody tr').find('input[value=' + type + ']#type').length > 0)) {
            alert_float('danger', "Sản phẩm này đã được thêm, vui lòng kiểm tra lại!");
            return;
        }

        var name_type = '<img style="border-radius: 50%;width: 4em;height: 4em;" src="'+item.avatar+'"><br><span class="label label-default mleft5 inline-block customer-group-list pointer" style="border:1px solid #e30000">' +findItem(type)+ '</span>';
        var new_tr = currentQuantityInput.parents('tr');
        var count = new_tr.find('td > input.count').val();
        new_tr.find('td.avatart').html(name_type+'<input type="hidden" id="type" name="items[' + count + '][type]" value="'+type+'" /><input type="hidden" class="id" id="product_id" name="items[' + count + '][id]" value="'+item.id+'" />');
        var unit_name = item.unit_name;
        if(item.unit_name==null){
            unit_name='';
        }
        new_tr.find('.color').html(item.color);
        new_tr.find('td > input.mainQuantity').val(formatNumber(item.quantity_suppliers));
        new_tr.find('td > input.mainQuantity').attr('max',item.quantity_suppliers);
        new_tr.find('td > input.mainQuantityNet').val(formatNumber(item.quantity_suppliers));
        new_tr.find('td > input.price').val(formatNumber(item.price_suppliers));
        new_tr.find('td:nth-child(9) .tax').selectpicker('val',item.tax_id);
        new_tr.find('td:nth-child(9) > input.tax_rate').val(item.tax_rate);
        new_tr.find('td.unit_name').html(unit_name);
        var promotion_suppliers = item.promotion_expected / item.quantity_purchase_order;

        new_tr.find('td.promotion_suppliers').find('p').text(formatNumber(promotion_suppliers*item.quantity_suppliers));
        //giá trị trên 1 sản phẩm!
        new_tr.find('td.promotion_suppliers').find('> input#promotion_suppliers_1').val(promotion_suppliers);
        new_tr.find('td.promotion_suppliers').find('> input#promotion_suppliers').val(promotion_suppliers*item.quantity_suppliers);
        new_tr.find('td.delete').html('<a href="#" class="btn btn-danger pull-right" onclick="deleteTrItem(this); return false;"><i class="fa fa-times"></i></a>');
        var warehouse_id = $('#warehouse_id').val();
        loadLocaltion_warehouses(warehouse_id,count);
        countrow();
        calculateTotal(currentQuantityInput);
    }
	var createTrItemfist = () =>{
		var name_type = '<img style="border-radius: 50%;width: 4em;height: 4em;"  src="<?=base_url('assets/images/preview-not-available.jpg')?>">';
        var newTr = $('<tr class="sortable item"></tr>');
        var td1 = $('<td class="dragger avatart" style="text-align: center;">'+name_type+'<input type="hidden" name="items[' + uniqueArray + '][type]" class="type" value="hau" /></td>');

        var td2 = $('<td><input type="hidden" class="count" value="'+uniqueArray+'" /><div class="form-group ">\
								             <select style="width: 200px" class="custom_item_select" id="custom_item_select_'+uniqueArray+'" name="items[' + uniqueArray + '][id_item]" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">\
								             	<?php echo $html;?>
								             </select>\
								        </div><br><br><div class="color"></div></td>');
        var td3 = $('<td><div class="form-group ">\
								             <select class="localtion_warehouses_id" id="localtion_warehouses_id_'+uniqueArray+'" name="items[' + uniqueArray + '][localtion_warehouses_id]" style="width: 200px;" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">\
								             </select>\
								        </div></td>');
        var td4 = $('<td class="unit_name"></td>');
        var td5 = $('<td><input class="mainQuantity H_input height_auto" type="number" name="items[' + uniqueArray + '][quantity]" value="1" /></td>');
        var td6 = $('<td><input class="mainQuantityNet H_input height_auto" min="1" type="number" name="items[' + uniqueArray + '][quantity_net]" value="1" /></td>');
        var td7 = $('<td ><input onkeyup="formatNumBerKeyUp(this)" class="height_auto H_input align_right price" type="text" name="items[' + uniqueArray + '][price]" value="0" /></td>');
        var td8 = $('<td class="align_right promotion_suppliers"><input class="hide" name="items[' + uniqueArray + '][promotion_suppliers_1]" id="promotion_suppliers_1"  value="0"><input class="hide" id="promotion_suppliers" name="items[' + uniqueArray + '][promotion_suppliers]" value="0"><p>0</p></td>');
        var taxTemplate=taxes_dropdown_template;
        taxTemplate=taxTemplate.replace('name=""','name="items['+uniqueArray+'][tax_id]"');
        var td9 = $('<td>'+taxTemplate+'<input type="hidden" class="tax_rate" name="items['+uniqueArray+'][tax_rate]" value="0"></td>');
        var td10 = $('<td class="align_right amount">0</td>');
        var td11 = $('<td><textarea style="width: 100%;" class="note" name="items['+uniqueArray+'][note]"></textarea></td>');
        newTr.append(td1);
        newTr.append(td2);
        newTr.append(td3);
        newTr.append(td4);
        newTr.append(td5);
        newTr.append(td6);
        newTr.append(td7);
        newTr.append(td8);
        newTr.append(td9);
        newTr.append(td10);
        newTr.append(td11);
        newTr.append('<td class="delete"></td>');
        $('table.item-import tbody').prepend(newTr);
        // init_ajax_searchs('items','#custom_item_select_'+uniqueArray);
        $('#custom_item_select_'+uniqueArray).select2();
        $('#localtion_warehouses_id_'+uniqueArray).select2();
        uniqueArray++;
        getTotalPrice();
	}
	var createTrItemfist_ch = (key,idd) =>{
		var name_type = '<img style="border-radius: 50%;width: 4em;height: 4em;"  src="<?=base_url('assets/images/preview-not-available.jpg')?>">';
        var newTr = $('<tr class="sortable item"></tr>');
        var td1 = $('<td class="dragger avatart" style="text-align: center;">'+name_type+'<input type="hidden" name="items[' + uniqueArray + '][type]" class="type" value="hau" /></td>');

        var td2 = $('<td><input type="hidden" class="count" value="'+uniqueArray+'" /><div class="form-group ">\
								             <select style="width: 200px" class="custom_item_select" id="custom_item_select_'+uniqueArray+'" name="items[' + uniqueArray + '][id_item]" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">\
								             	<?php echo $html;?>
								             </select>\
								        </div><div class="color"></div></td>');
        var td3 = $('<td><div class="form-group">\
								             <select class="localtion_warehouses_id" id="localtion_warehouses_id_'+uniqueArray+'" name="items[' + uniqueArray + '][localtion_warehouses_id]" style="width: 200px;" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">\
								             </select>\
								        </div></td>');
        var td4 = $('<td class="unit_name"></td>');
        var td5 = $('<td><input class="mainQuantity H_input height_auto" type="number" name="items[' + uniqueArray + '][quantity]" value="1" /></td>');
        var td6 = $('<td><input class="mainQuantityNet H_input height_auto" min="1" type="number" name="items[' + uniqueArray + '][quantity_net]" value="1" /></td>');
        var td7 = $('<td ><input onkeyup="formatNumBerKeyUp(this)" class="height_auto H_input align_right price" type="text" name="items[' + uniqueArray + '][price]" value="0" /></td>');
        var td8 = $('<td class="align_right promotion_suppliers"><input class="hide" name="items[' + uniqueArray + '][promotion_suppliers_1]" id="promotion_suppliers_1"  value="0"><input class="hide" id="promotion_suppliers" name="items[' + uniqueArray + '][promotion_suppliers]" value="0"><p>0</p></td>');
        var taxTemplate=taxes_dropdown_template;
        taxTemplate=taxTemplate.replace('name=""','name="items['+uniqueArray+'][tax_id]"');
        var td9 = $('<td>'+taxTemplate+'<input type="hidden" class="tax_rate" name="items['+uniqueArray+'][tax_rate]" value="0"></td>');
        var td10 = $('<td class="align_right amount">0</td>');
        var td11 = $('<td><textarea style="width: 100%;" class="note" name="items['+uniqueArray+'][note]"></textarea></td>');
        newTr.append(td1);
        newTr.append(td2);
        newTr.append(td3);
        newTr.append(td4);
        newTr.append(td5);
        newTr.append(td6);
        newTr.append(td7);
        newTr.append(td8);
        newTr.append(td9);
        newTr.append(td10);
        newTr.append(td11);
        newTr.append('<td class="delete"></td>');
        $('table.item-import tbody').append(newTr);
        // init_ajax_searchs('items','#custom_item_select_'+uniqueArray);
        $('#custom_item_select_'+uniqueArray).select2();
        $('#custom_item_select_'+uniqueArray).val(idd);
		$('#custom_item_select_'+uniqueArray).trigger('change');
		$('#localtion_warehouses_id_'+uniqueArray).select2();
        uniqueArray++;
        getTotalPrice();
	}
    $(document).on('keyup', '.mainQuantityNet', (e)=>{
        var currentQuantityInput = $(e.currentTarget);
   		mainQuantity=currentQuantityInput.parents('tr').find('.mainQuantity').val();
    	if(parseInt(currentQuantityInput.val()) > mainQuantity){
            currentQuantityInput.attr("style", "width: 100px;border: 1px solid red !important");
            currentQuantityInput.attr('data-toggle', 'tooltip');
            currentQuantityInput.attr('data-trigger', 'manual');
            currentQuantityInput.attr('title', 'Số lượng vượt mức cho phép!');
            currentQuantityInput.off('focus', '**').off('hover', '**');
            currentQuantityInput.tooltip('fixTitle').focus(()=>$(this).tooltip('show')).hover(()=>$(this).tooltip('show'));
            currentQuantityInput.addClass('error');
            currentQuantityInput.focus();
        }
        else {
            currentQuantityInput.attr('title', 'OK!').tooltip('fixTitle').tooltip('show');
            currentQuantityInput.attr("style", "width: 100px;");
            currentQuantityInput.removeClass('error');
            currentQuantityInput.focus();
        }
    	getTotalPrice();
	});
    $(document).on('keyup', '.mainQuantity', (e)=>{
        var currentQuantityInput = $(e.currentTarget);
   		currentQuantityInput.parents('tr').find('.mainQuantityNet').val(currentQuantityInput.val());
    	
    	getTotalPrice();
	});	
    var deleteTrItem = (trItem) => {
        var current = $(trItem).parent().parent();
        $(trItem).parent().parent().remove();
    };
    $(document).on('change','select.tax',function(e){
      var tax_id=$(this).val();
      var tax_rate=parseInt($(this).find('option:selected').attr('data-taxrate'));
      var current_row=$(this).parents('tr');
      if(isNaN(tax_rate)) tax_rate=0;
      $(this).parents('tr').find('input.tax_rate').val(tax_rate);
      calculateTotal(e.currentTarget);
    });
    $(document).on('keyup', '.mainQuantity', (e)=>{
        var currentQuantityInput = $(e.currentTarget);
        mainQuantity=currentQuantityInput.attr('max');
    	if(parseInt(currentQuantityInput.val()) > parseInt(mainQuantity)){
            currentQuantityInput.attr("style", "width: 100px;border: 1px solid red !important");
            currentQuantityInput.attr('data-toggle', 'tooltip');
            currentQuantityInput.attr('data-trigger', 'manual');
            currentQuantityInput.attr('title', 'Số lượng vượt mức cho phép!');
            currentQuantityInput.off('focus', '**').off('hover', '**');
            currentQuantityInput.tooltip('fixTitle').focus(()=>$(this).tooltip('show')).hover(()=>$(this).tooltip('show'));
            currentQuantityInput.addClass('error');
            currentQuantityInput.focus();
        }
        else {
        	currentQuantityInput.attr('data-toggle', 'tooltip');
            currentQuantityInput.attr('data-trigger', 'manual');
            currentQuantityInput.attr('title', ' ');
            currentQuantityInput.tooltip('fixTitle').focus(()=>$(this).tooltip('show')).hover(()=>$(this).tooltip('show'));
            currentQuantityInput.removeClass('error');
            currentQuantityInput.attr("style", "");
            currentQuantityInput.focus();
            calculateTotal(e.currentTarget);
        }
    });
    $(document).on('keyup', '.mainQuantityNet', (e)=>{
        var currentQuantityInput = $(e.currentTarget);
        mainQuantity=currentQuantityInput.parents('tr').find('.mainQuantity').val();
    	if(parseInt(currentQuantityInput.val()) > mainQuantity){
            currentQuantityInput.attr("style", "width: 100px;border: 1px solid red !important");
            currentQuantityInput.attr('data-toggle', 'tooltip');
            currentQuantityInput.attr('data-trigger', 'manual');
            currentQuantityInput.attr('title', '<?=_l('ch_limit_items')?>');
            currentQuantityInput.off('focus', '**').off('hover', '**');
            currentQuantityInput.tooltip('fixTitle').focus(()=>$(this).tooltip('show')).hover(()=>$(this).tooltip('show'));
            currentQuantityInput.addClass('error');
            currentQuantityInput.focus();
        }
        else {
            currentQuantityInput.attr('title', 'OK!').tooltip('fixTitle').tooltip('show');
            currentQuantityInput.removeClass('error');
            currentQuantityInput.focus();
            calculateTotal(e.currentTarget);
        }
    });
    $(document).on('keyup', '.price', (e)=>{
        var currentQuantityInput = $(e.currentTarget);
        calculateTotal(e.currentTarget);
    });
    var calculateTotal = (currentInput) => {
        currentInput = $(currentInput);
        var current_row = currentInput.parents('tr');

        let mainQuantity = unformat_number(current_row.find('.mainQuantity').val());  
        let mainQuantityNet = unformat_number(current_row.find('.mainQuantityNet').val());
        let promotion_suppliers = current_row.find('#promotion_suppliers_1').val();
        let price = unformat_number(current_row.find('.price').val());
        let tax = current_row.find('.tax_rate').val();
        console.log(promotion_suppliers);
        var total = ((mainQuantityNet * price) - (Number(promotion_suppliers) * mainQuantityNet)) * (1+tax/100);
        current_row.find('.amount').text(formatNumber(total));
        getTotalPrice();
    };
    function getTotalPrice()
    {   
        var items = $('table.item-import tbody').find('tr.item');
        var totalQuantity = 0;
		var totalQuantityNet = 0;
		var totalPrice = 0;
        $.each(items, (index,value)=>{
        	if(!empty($(value).find('#type').val()))
        	{
            totalQuantity += parseFloat($(value).find('.mainQuantity').val().replace(/\,/g, ''));
            totalQuantityNet += parseFloat($(value).find('.mainQuantityNet').val().replace(/\,/g, ''));
            totalPrice += parseFloat($(value).find('.amount').text().replace(/\,/g, ''));
        	}
        });
        $('.total_quantity_all').text(formatNumber(totalQuantity));
        $('.total_quantity_approve').text(formatNumber(totalQuantityNet));
        $('.total_price').text(formatNumber(totalPrice));
    }   
    $('#items-form').on('submit', (e)=>{
        if($('input.error').length > 0) {
            e.preventDefault();
            alert_float('danger', 'Giá trị không hợp lệ!');    
        } 
    });
   	function loadLocaltion_warehouses(warehouse_id,id){
       	var localtion_warehouses = $('#localtion_warehouses_id_'+id);
       	var checked = localtion_warehouses.attr('data-id');
       	localtion_warehouses.attr('required',true);
       	if(localtion_warehouses.length) {
       		$.post(admin_url+"warehouse/list_localtion",{warehouse:warehouse_id,checked:checked,[csrfData['token_name']] : csrfData['hash']},function(data){
       			localtion_warehouses.html(data).find('option').attr('disabled','disabled').parents('#localtion_warehouses_id_'+id).find('option[child="1"]').removeAttr('disabled');
            localtion_warehouses.find('option:nth-child(1)').removeAttr('disabled');
            localtion_warehouses.select2('val',checked);
        	})
       	}
   	}
   	function loadLocaltion_warehouses_change(warehouse_id,indexs){
       	var localtion_warehouses = indexs;
       	var checked = localtion_warehouses.attr('data-id');
       	localtion_warehouses.attr('required',true);
       	localtion_warehouses.find('option:gt(0)').remove();
       	if(localtion_warehouses.length) {
       		$.post(admin_url+"warehouse/list_localtion",{warehouse:warehouse_id,checked:checked,[csrfData['token_name']] : csrfData['hash']},function(data){
       			localtion_warehouses.html(data).find('option').attr('disabled','disabled').parents(indexs).find('option[child="1"]').removeAttr('disabled');
            localtion_warehouses.find('option:nth-child(1)').removeAttr('disabled');
            localtion_warehouses.select2('val','');
        	})
       	}
   	} 
   	function formatNumber(nStr, decSeperate=".", groupSeperate=",") {
        nStr += '';
        x = nStr.split(decSeperate);
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        x2=x2.substr(0,2);
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
        }
        return x1 + x2;
    };
    function unformat_number(number)
    {
        var _number=0;
        if(number)
        {
            _number=number.replace(/[^\-\d\.]/g, '');
        }
        return _number;
    };
   
    $(document).ready(function() {
        $('.table-responsive').on('show.bs.dropdown', function () {
           $('.table-responsive').css( "overflow", "inherit" );
        });

        $('.table-responsive').on('hide.bs.dropdown', function () {
           $('.table-responsive').css( "overflow", "auto" );
        })
    });
    <?php
    if(empty($items)){
    ?>
    var items_purchase_order = <?=json_encode($items_purchase_order);?>;
    <?php
    }
    ?>
	function load() {
		var warehouse_id = $('#warehouse_id').val();
		if((warehouse_id == ''))
    	{
        	alert_float('warning', "Bạn chưa chọn kho!");
            return;
        }
		var items = 0
		items = $('table.item-import tbody').find('tr.item').length;
		if(items > 0)
		{
			var r = confirm("<?php echo _l('ch_load_items');?>");
		    if (r == false) 
		    {
		        return false;
		    } else {
		    		$('table.item-import tbody').html('');
			        $.each(items_purchase_order, function(key,value){
			        	createTrItemfist_ch(key,value.id);
			        	// $('#custom_item_select_'+key).val(value.id);
			        	// $('#custom_item_select_'+key).trigger('change');
			        });
			}
		} else {
			$('table.item-import tbody').html('');
		
		    $.each(items_purchase_order, function(key,value){
			        	createTrItemfist_ch(key,value.id);
			        	// $('#custom_item_select_'+key).val(value.id);
			        	// $('#custom_item_select_'+key).trigger('change');
			});
		}
	}
	$(function(){
		var dt = $('.item-import').DataTable({
			'searching': false,
			'ordering': false,
			'paging': false,
	        "info": false,
	        'fixedHeader': true,
	        // scrollY: true,
			// scrollY: '150px',
			// scrollX: true,
	        'fnRowCallback': function (nRow, aData, iDisplayIndex) {
	        },
	        "initComplete": function(settings, json) {
	            var t = this;
	            t.parents('.table-loading').removeClass('table-loading');
	            t.removeClass('dt-table-loading');
	            mainWrapperHeightFix();
	        },
		});
	});
</script>