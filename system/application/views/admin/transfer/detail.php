<?php init_head(); ?>
<style type="text/css">
  .item-items .ui-sortable tr td input {
    width: 80px;
  }
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
														<?php echo _l('ch_code_p'); ?>
													</label>
												</td>
												<td>
													<div class="form-group">
										                <div class="input-group">
							                  				<span class="input-group-addon">
										                    <?php echo (isset($items) ? ($items->prefix) : get_option('prefix_transfer'));?>-</span>
															<?php 
																	$number = sprintf('%06d', ch_getMaxID('id', 'tbltransfer_warehouse') + 1);
																	$value = (isset($items) ? ($items->code) : $number);
																?>
										                    <input type="text" name="number" class="form-control" value="<?= $value ?>" readonly>
							                  			</div>
									                </div>
												</td>
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
											</tr>
											<tr>
												<td>
													<label for="suppliers_id" class="control-label">
														<small class="req text-danger">* </small>
														<?php echo _l('ch_warehouse_do'); ?>
													</label>
												</td>
												<td>
													<?php
														$value = (isset($items) ? $items->warehouse_id : '');
														echo render_select('warehouse_id',$warehouse,array('id','name','code'),'',$value);
													?>
												</td>
												<td>
													<label for="warehouse_id" class="control-label">
														<small class="req text-danger">* </small>
														<?php echo _l('ch_warehouse_to'); ?>
													</label>
												</td>
												<td>
													<?php
														$value = (isset($items) ? $items->warehouse_to : '');
														echo render_select('warehouse_to',$warehouse,array('id','name','code'),'',$value);
													?>
												</td>
											</tr>
											<tr>
												<td>
													<label for="type_items" class="control-label">
														<?php echo _l('ch_type'); ?>
													</label>
												</td>
												<td colspan="3">
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
													<?php echo render_textarea('note', '', $value); ?>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mbot50">
							<div class="panel-body mtop10">
								<div class="table-responsive" style="min-height: 400px">
									<table class="table items item-import no-mtop dont-responsive-table">
										<thead>
											<tr>
												<th class="center"><a onclick="button_create()" class="btn btn-info btn-icon">+</a><input type="hidden" id="itemID" value="" /></th>
												<th style="width: 250px" class="text-center"><?php echo _l('ch_items_name_t'); ?></th>
												<th style="width: 200px" class="text-center"><?php echo _l('Vị trí kho chuyển'); ?></th>
												<th style="width: 200px" class="text-center"><?php echo _l('Tồn kho thực'); ?></th>
												<th style="width: 200px" class="text-center"><?php echo _l('Vị trí kho nhận'); ?></th>
												<th class="text-center"><?php echo _l('item_unit'); ?></th>
												<th class="text-center"><?php echo _l('item_quantity'); ?></th>
                                                <th class="text-center"><?php echo _l('item_quantity_confirm'); ?></th>
                                                <th class="text-center"><?php echo _l('Giá bán'); ?></th>
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
                                                    <td class="dragger avatart" style="text-align: center;"><input type="hidden" id="type" name="items[<?php echo $i; ?>][type]" value="<?php echo $value['type']; ?>" /><div id="type_name"></div>
	                                                    <input type="hidden" class="id" name="items[<?php echo $i; ?>][id]" value="<?php echo $value['id_items']; ?>">
                                                    </td>
                                                    <td style="min-width: 200px"><input type="hidden" id="type" name="items[<?php echo $i; ?>][type]" value="<?php echo $value['type']; ?>" /><img style="border-radius: 50%;width: 3em;height: 3em;" src="<?=(!empty($value['avatar'])?(file_exists($value['avatar']) ? base_url($value['avatar']) : (file_exists('uploads/materials/'.$value['avatar']) ? base_url('uploads/materials/'.$value['avatar']) : (file_exists('uploads/products/'.$value['avatar']) ? base_url('uploads/products/'.$value['avatar']) : base_url('assets/images/preview-not-available.jpg')))):base_url('assets/images/preview-not-available.jpg'))?>"><?=$value['name_item']?><div class="color"><?=format_item_color($value['id_items'],$value['type'])?></div>
												    </td>
												    <td>
												    	<div class="form-group  ">
												             <select style="width: 100%;" data-id="<?=$value['localtion_id']?>" class="warehouse_id_id " id="warehouse_id_<?=$i?>" name="items[<?=$i?>][localtion_id]" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
												             </select>
												        </div>
												    </td>
												    <td class="text-center"><input class="hide input_quantity_warehoue" value="0"><span class="quantity_warehoue">0</span></td>
												    <td>
												    	<div class="form-group  ">
												             <select style="width: 100%;" data-id="<?=$value['localtion_to']?>" class="localtion_to_to" id="warehouse_to_<?=$i?>" name="items[<?=$i?>][localtion_to]" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
												             </select>
												        </div>
												    </td>
												    <td>
												    	<?=$value['unit']?>
												    </td>
												    <td>
												    	<input  class="mainQuantity H_input height_auto" type="number" name="items[<?=$i?>][quantity]" value="<?=number_format($value['quantity'])?>" />
												    </td>
												    <td>
												    	<input class="mainQuantityNet H_input height_auto"  type="number" name="items[<?=$i?>][quantity_net]" value="<?=number_format($value['quantity_net'])?>" />
												    </td>
												    <td class="align_right">
												    	<input onkeyup="formatNumBerKeyUp(this)" class="hide height_auto H_input align_right price" type="text" name="items[<?=$i?>][price]" value="<?=$value['price']?>" /><span class="text_price"><?=number_format($value['price'])?></span>
												    </td>
        											<td class="align_right amount"><?=number_format($value['amount'])?></td>
        											<td><textarea style="width: 100%;" class="note" name="items[<?php echo $i; ?>][note]" value="<?=$value['note']?>"><?=$value['note']?></textarea></td>
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
		            <a class="btn btn-info pull-right form-submitersss">
		            <?php echo _l( 'submit'); ?>
		            </a>
		        </div>
			</div>
			
			<?php echo form_close(); ?>
		</div>
	</div>
</div>
<?php init_tail(); ?>
<script>
		var warehouse_old;
		$(document).on('change', '#warehouse_id', (e)=>{
    	var warehouse_id = $('#warehouse_id').val();
    	var warehouse_to = $('#warehouse_to').val();
    	
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
            	if($(value).find('td:nth-child(1)').find('input#type').val() != 'hau')
            	{
            	var indexs = $(value).find('td:nth-child(3)').find('select.warehouse_id_id');
            	var product_id = $(value).find('td:nth-child(1)').find('.id').val();
                loadLocaltion_warehouses_change_to(product_id,indexs);
        		}
	            });
            }
            return false;	
    	}
    	warehouse_old = warehouse_id;
		})
		var warehouse_old_to;
		$(document).on('change', '#warehouse_to', (e)=>{
    	var warehouse_to = $('#warehouse_to').val();
    	if(warehouse_to != '')
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
            	$('#warehouse_to').selectpicker('val',warehouse_old_to);
            	warehouse_to = warehouse_old_to;
                return false;
            } else {
            	$.each(items, (index,value)=>{
            	if($(value).find('td:nth-child(1)').find('input#type').val() != 'hau')
            	{
            	var indexs = $(value).find('td:nth-child(5)').find('select.warehouse_to_to');
                loadLocaltion_warehouses_change(warehouse_to,indexs);
        		}
	            });
            }
            return false;	
    	}
    	warehouse_old_to = warehouse_to;
		})
   	$(document).on('change', '.warehouse_to_to', (e)=>{
		var currentQuantityInput = $(e.currentTarget);
		var warehouse_id = $('#warehouse_id').val();
		var warehouse_to = $('#warehouse_to').val();
		var warehouse_id_id = currentQuantityInput.parents('tr').find('select.warehouse_id_id').val();
		if(warehouse_id_id != '')
		{
		if(warehouse_id == warehouse_to)
		{
			if(warehouse_id_id == currentQuantityInput.val())
			{
				alert('Vị trí nhận không được trùng lại vị trí chuyển!');
				// console.log(currentQuantityInput);	
				currentQuantityInput.select2('val', '');
				}
		}
		}
    }) 
    $(document).on('change', '.warehouse_id_id', (e)=>{
		var currentQuantityInput = $(e.currentTarget);
		var warehouse_id = $('#warehouse_id').val();
		var warehouse_to = $('#warehouse_to').val();
		var warehouse_to_to = currentQuantityInput.parents('tr').find('select.warehouse_to_to').val();
		if(warehouse_to_to != '')
		{
		if(warehouse_id == warehouse_to)
		{
			if(warehouse_to_to == currentQuantityInput.val())
			{
				alert('Vị trí nhận không được trùng lại vị trí chuyển!');
				// console.log(currentQuantityInput);	
				warehouse_to_to.select2('val', '');
				}
		}
		}
		var currentQuantityInput = $(e.currentTarget);
		var mainQuantity=$('option:selected', currentQuantityInput).attr('quantity-id');
		currentQuantityInput.parents('tr').find('td:nth-child(4)').find('span.quantity_warehoue').html(mainQuantity);
		currentQuantityInput.parents('tr').find('td:nth-child(4)').find('input.input_quantity_warehoue').val(mainQuantity);
		currentQuantityInput.parents('tr').find('.mainQuantity').change();
    }) 
	$(function(){
		// validate_invoice_form();
		_validate_form($('#import-form'), {
        date: "required",
        warehouse_to: "required",
        number: "required",
        warehouse_id: "required",
        localtion_warehouses_id: "required"
    });
	});
	var itemList = <?php echo json_encode($type_items);?>;
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
        	var type = $(value).find('td:nth-child(2)').find('input#type').val();
        	var name_type = '<span class="label label-default mleft5 inline-block customer-group-list pointer" style="border:1px solid #e30000">' +findItem(type)+ '</span>';
            $(value).find('td:nth-child(1)').find('div#type_name').html(name_type);
            $(value).find('td:nth-child(3)').find('select.warehouse_to_to').change();
            $(value).find('td:nth-child(5)').find('select.warehouse_id_id').change();

        	loadLocaltion_warehouses(index,value);
        	loadLocaltion_warehouses_to(index);
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
    	var currentQuantityInput = $(e.currentTarget);
    	var warehouse_id = $('#warehouse_id').val();
    	var currentQuantityInput = $(e.currentTarget);
		var id = currentQuantityInput.val();
		if(id == '') {
			createTrItem_distroy(currentQuantityInput);
		}
		else {
			var price = currentQuantityInput.select2('data').price;
    		var type = currentQuantityInput.select2('data').type;
			$.post(admin_url + 'import/get_items/'+id+'/'+type,{[csrfData['token_name']] : csrfData['hash']}, function(item){
				var item = JSON.parse(item);
				createTrItem(item,currentQuantityInput,type,price);
		    });
		}
	});
	var uniqueArray = <?=$i?>;
	var taxes_dropdown_template=<?=json_encode($taxes)?>;
    var createTrItem_distroy = (currentQuantityInput) => {
        var name_type = '<span class="label label-default mleft5 inline-block customer-group-list pointer" style="border:1px solid #e30000"></span>';
        var new_tr = currentQuantityInput.parents('tr');
        var count = new_tr.find('td > input.count').val();
        new_tr.find('.color').html('');
        new_tr.find('td.avatart').html('');
        new_tr.find('td > input.price').val(0);
        new_tr.find('td span.text_price').html(formatNumber(0));
        new_tr.find('td.unit_name').html('');
        new_tr.find('td.amount').html(formatNumber(0));
        new_tr.find('td.delete').html('<a href="#" class="btn btn-danger pull-right" onclick="deleteTrItem(this); return false;"><i class="fa fa-times"></i></a>');
     
        countrow();
    }	
    var createTrItem = (item,currentQuantityInput,type,price) => {
        if(typeof(item)=='undefined' || item.length==0) return;
        if( ($('table.item-import tbody tr').find('input[value=' + item.id + '].id ').length > 0) && ($('table.item-import tbody tr').find('input[value=' + type + ']#type ').length > 0)) {
            alert_float('danger', "Sản phẩm này đã được thêm, vui lòng kiểm tra lại!");
            return;
        }

        var name_type = '<span class="label label-default mleft5 inline-block customer-group-list pointer" style="border:1px solid #e30000">' +findItem(type)+ '</span>';
        var new_tr = currentQuantityInput.parents('tr');
        var count = new_tr.find('td > input.count').val();
        new_tr.find('.color').html(item.color);
        new_tr.find('td.avatart').html(name_type+'<input type="hidden" id="type" name="items[' + count + '][type]" value="'+type+'" /><input type="hidden" class="id" name="items[' + count + '][id]" value="'+item.id+'" />');
        var unit_name = item.unit_name;
        if(item.unit_name==null){
            unit_name='';
        }
        new_tr.find('td > input.price').val(price);
        new_tr.find('td span.text_price').html(formatNumber(price));
        new_tr.find('td.unit_name').html(unit_name);
        new_tr.find('td.amount').html(formatNumber(price));
        new_tr.find('td.delete').html('<a href="#" class="btn btn-danger pull-right" onclick="deleteTrItem(this); return false;"><i class="fa fa-times"></i></a>');
        loadLocaltion_warehouses(count);
        loadLocaltion_warehouses_to(count);
        countrow();
    }
    var button_create = ()=>{
    	var warehouse_id = $('#warehouse_id').val();
		if((warehouse_id != ''))
    	{
		if(!$('table.item-import tbody tr.item').find('input[value=hau]').length)
        {
            createTrItemfist();
        }
    	}else{
        	alert('Bạn phải chọn kho chuyển trước!');return;
        }
    }
	var createTrItemfist = () =>{

        var newTr = $('<tr class="sortable item"></tr>');
       
        var td1 = $('<td class="dragger avatart" style="text-align: center;"><input type="hidden" name="items[' + uniqueArray + '][type]" class="type" id="type" value="hau" /></td>');

        var td2 = $('<td><input type="hidden" class="count" value="'+uniqueArray+'" /><input data-placeholder="<?=_l('ch_list_objects')?>" class="custom_item_select" name="items[' + uniqueArray + '][custom_item_select]" style="width: 100%" id="custom_item_select_'+uniqueArray+'"><br><br><div class="color"></div></td>');
        var td3 = $('<td><div class="form-group ">\
								             <select name="items[' + uniqueArray + '][localtion_id]"  data-placeholder="<?=_l('Chọn vị trí') ?>" id="warehouse_id_'+uniqueArray+'" class="warehouse_id_id" style="width: 100%;"><option value=""></option>\
										</select>\
								        </div></td>');
        var td12 = $('<td class="text-center"><input class="hide input_quantity_warehoue" value="0"><span class="quantity_warehoue">0</span></td>');
        var td4 = $('<td><div class="form-group ">\
								             <select name="items[' + uniqueArray + '][localtion_to]" data-placeholder="<?=_l('Chọn vị trí') ?>" id="warehouse_to_'+uniqueArray+'" class="warehouse_to_to" style="width: 100%;"><option value=""></option>\
										</select>\
								        </div></td>');
        var td5 = $('<td class="unit_name"></td>');
        var td6 = $('<td><input class="mainQuantity H_input height_auto" type="number" name="items[' + uniqueArray + '][quantity]" value="1" /></td>');
        var td7 = $('<td><input class="mainQuantityNet H_input height_auto"  type="number" name="items[' + uniqueArray + '][quantity_net]" value="1" /></td>');
        var td8 = $('<td class="align_right"><input onkeyup="formatNumBerKeyUp(this)" class="hide height_auto H_input align_right price" type="text" name="items[' + uniqueArray + '][price]" value="0" /><span class="text_price"></span></td>');
        var td9 = $('<td class="align_right amount">0</td>');
        var td10 = $('<td><textarea style="width: 100%;" class="note" name="items['+uniqueArray+'][note]"></textarea></td>');
        var td11 = $('<td class="delete"></td>');
        newTr.append(td1);
        newTr.append(td2);
        newTr.append(td3);
        newTr.append(td12);
        newTr.append(td4);
        newTr.append(td5);
        newTr.append(td6);
        newTr.append(td7);
        newTr.append(td8);
        newTr.append(td9);
        newTr.append(td10);
        newTr.append(td11);
        $('table.item-import tbody').prepend(newTr);
        ajaxSelectCallBack($('#custom_item_select_'+uniqueArray), "<?=admin_url('transfer/SearchItems')?>", 0);
        $('#warehouse_id_'+uniqueArray).select2();
        $('#warehouse_to_'+uniqueArray).select2();
        uniqueArray++;
        getTotalPrice();
        reset_item_select();
	}
	$(document).on('change', '.mainQuantityNet', (e)=>{
        var currentQuantityInput = $(e.currentTarget);
        if(currentQuantityInput.val()  == '')
        {
        	currentQuantityInput.val(0);
        }
        calculateTotal(e.currentTarget);
    });
   
    $(document).on('keyup', '.mainQuantityNet', (e)=>{
        var currentQuantityInput = $(e.currentTarget);
        if(currentQuantityInput.val() < 0)
        {
        	currentQuantityInput.val(0);
        }
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
            currentQuantityInput.attr("style", "width: 100px;");
            currentQuantityInput.removeClass('error');
            currentQuantityInput.focus();
        }
    	getTotalPrice();
    	calculateTotal(e.currentTarget);
	});
	$(document).on('change', '.mainQuantity', (e)=>{
        var currentQuantityInput = $(e.currentTarget);
        if(currentQuantityInput.val()  == '')
        {
        	currentQuantityInput.val(0);
        	currentQuantityInput.parents('tr').find('.mainQuantityNet').val(0);
        }
        mainQuantity=parseInt(currentQuantityInput.parents('tr').find('td:nth-child(4)').find('input.input_quantity_warehoue').val());
        // $('option:selected', currentQuantityInput.parents('tr').find('.warehouse_id_id')).attr('quantity-id');
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
            currentQuantityInput.attr("style", "width: 100px;");
            currentQuantityInput.removeClass('error');
            currentQuantityInput.focus();
        }
        calculateTotal(e.currentTarget);
    });
    $(document).on('keyup', '.mainQuantity', (e)=>{
        var currentQuantityInput = $(e.currentTarget);
        if(currentQuantityInput.val() < 0)
        {
        	currentQuantityInput.val(0);
        	currentQuantityInput.parents('tr').find('.mainQuantityNet').val(0);
        }else
        {
        currentQuantityInput.parents('tr').find('.mainQuantityNet').val(currentQuantityInput.val());	
        }
        mainQuantity=parseInt(currentQuantityInput.parents('tr').find('td:nth-child(4)').find('input.input_quantity_warehoue').val());
        // mainQuantity=$('option:selected', currentQuantityInput.parents('tr').find('.warehouse_id_id')).attr('quantity-id');
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
            currentQuantityInput.attr("style", "width: 100px;");
            currentQuantityInput.removeClass('error');
            currentQuantityInput.focus();
        }
    	getTotalPrice();
    	calculateTotal(e.currentTarget);
	});	
    var deleteTrItem = (trItem) => {
        var current = $(trItem).parent().parent();
        $(trItem).parent().parent().remove();
    };
    $(document).on('click', '.mainQuantity', (e)=>{
       	var currentQuantityInput = $(e.currentTarget);
        if(currentQuantityInput.val() < 0)
        {
        	currentQuantityInput.val(0);
        	currentQuantityInput.parents('tr').find('.mainQuantityNet').val(0);
        }else
        {
        currentQuantityInput.parents('tr').find('.mainQuantityNet').val(currentQuantityInput.val());	
        }
        mainQuantity=parseInt(currentQuantityInput.parents('tr').find('td:nth-child(4)').find('input.input_quantity_warehoue').val());
        // mainQuantity=$('option:selected', currentQuantityInput.parents('tr').find('.warehouse_id_id')).attr('quantity-id');
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
            currentQuantityInput.attr("style", "width: 100px;");
            currentQuantityInput.removeClass('error');
            currentQuantityInput.focus();
        }
    	getTotalPrice();
    	calculateTotal(e.currentTarget);
    });
    $(document).on('click', '.mainQuantityNet', (e)=>{
        var currentQuantityInput = $(e.currentTarget);
        mainQuantity=currentQuantityInput.parents('tr').find('.mainQuantity').val();
        if(currentQuantityInput.val() < 0)
        {
        	currentQuantityInput.val(0);
        }
    	if(parseInt(currentQuantityInput.val()) > mainQuantity){
            currentQuantityInput.attr("style", "width: 100px;border: 1px solid red !important");
            currentQuantityInput.attr('data-toggle', 'tooltip');
            currentQuantityInput.attr('data-trigger', 'manual');
            currentQuantityInput.attr('title',  '<?=_l('ch_limit_items')?>');
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
    var calculateTotal = (currentInput) => {
        currentInput = $(currentInput);
        var current_row = currentInput.parents('tr');

        let mainQuantity = unformat_number(current_row.find('.mainQuantity').val());  
        let mainQuantityNet = unformat_number(current_row.find('.mainQuantityNet').val());
        let price = unformat_number(current_row.find('.price').val());

        var total = mainQuantityNet * price;
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
        	if($(value).find('td:nth-child(1)').find('input#type').val() != 'hau')
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
   	function loadLocaltion_warehouses(id,value=''){
   		var localtion_warehouses = $('#warehouse_id_'+id);
   		var checked = localtion_warehouses.attr('data-id');
       	var warehouse=$('#warehouse_id').val();
        var id_product=$('#custom_item_select_'+id).val();
        if(empty(id_product))
        {
        	id_product = $(value).find('td:nth-child(1)').find('input.id').val();
        }
        $('#warehouse_id_'+id).attr('required',true);
        $('#warehouse_id_'+id).select2();
        $.post(admin_url+"warehouse/list_localtion_product",{[csrfData['token_name']] : csrfData['hash'],warehouse:warehouse,id_product:id_product,checked:checked},function(data)
        {
            $('#warehouse_id_'+id).html(data);
        	$('#warehouse_id_'+id).val(checked).trigger('change');
        });
   	}
   	function loadLocaltion_warehouses_change_to(id_product,indexs){
   		var warehouse=$('#warehouse_id').val();
   		indexs.attr('required',true);
       	indexs.val('').trigger('change');
       	indexs.find('option:gt(0)').remove();
        $.post(admin_url+"warehouse/list_localtion_product",{[csrfData['token_name']] : csrfData['hash'],warehouse:warehouse,id_product:id_product},function(data)
        {
            indexs.html(data).find('option').attr('disabled','disabled').parents(indexs).find('option[child="1"]').removeAttr('disabled').selectpicker('render');
            indexs.find('option:nth-child(1)').removeAttr('disabled');
        })
   	}   	
   	function loadLocaltion_warehouses_to(id){
   		var warehouse=$('#warehouse_to').val();
       	var localtion_warehouses = $('#warehouse_to_'+id);
       	$('#warehouse_to_'+id).select2();
       	var checked = localtion_warehouses.attr('data-id');
       	localtion_warehouses.attr('required',true);
       	localtion_warehouses.find('option:gt(0)').remove();
       	if(localtion_warehouses.length) {
       		$.post(admin_url+"warehouse/list_localtion",{warehouse:warehouse,[csrfData['token_name']] : csrfData['hash'],checked:checked},function(data){
       		localtion_warehouses.html(data).find('option').attr('disabled','disabled').parents('#warehouse_to_'+id).find('option[child="1"]').removeAttr('disabled').selectpicker('render');
            localtion_warehouses.find('option:nth-child(1)').removeAttr('disabled');
            $('#warehouse_to_'+id).val(checked).trigger('change');
        	})
       	}
   	}
   	function loadLocaltion_warehouses_change(warehouse_id,indexs){
       	var localtion_warehouses = indexs;
       	localtion_warehouses.attr('required',true);
       	localtion_warehouses.val('').trigger('change');
       	localtion_warehouses.find('option:gt(0)').remove();
       	if(localtion_warehouses.length) {
       		$.post(admin_url+"warehouse/list_localtion",{warehouse:warehouse_id,[csrfData['token_name']] : csrfData['hash']},function(data){
       		localtion_warehouses.html(data).find('option').attr('disabled','disabled').parents(indexs).find('option[child="1"]').removeAttr('disabled').selectpicker('render');
            localtion_warehouses.find('option:nth-child(1)').removeAttr('disabled');
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
    function reset_item_select() {
		$('#custom_item_select').html('');
		$('#custom_item_select').selectpicker('refresh');
	}
    function ajaxSelectCallBack(element, url, id, types = '')
            {
                if (id > 0)
                {
                    $(element).val(id).select2({
                        // minimumInputLength: 1,
                        width: 'resolve',
                        allowClear: true,
                        initSelection: function (element, callback) {
                            $.ajax({
                                type: "get", async: false,
                                url: url + '/' + id,
                                dataType: "json",
                                success: function (data) {
                                    callback(data.results[0]);
                                }
                            });
                        },
                        ajax: {
                            url: url,
                            dataType: 'json',
                            quietMillis: 15,
                            data: function (term, page) {
                                return {
                                    type:$('#type_items').val(),
                                    types: types,
                                    term: term,
                                    limit: 50
                                };
                            },
                            results: function (data, page) {
                                if (data.results != null) {
                                    return {results: data.results};
                                } else {
                                    return {results: [{id: '', text: 'No Match Found'}]};
                                }
                            }
                        },
                            formatResult: repoFormatSelection,
                            formatSelection: repoFormatSelection,
                            dropdownCssClass: "bigdrop",
                            escapeMarkup: function (m) { return m; }
                    });
                } else {
                    $(element).select2({
                        // minimumInputLength: 1,
                        width: 'resolve',
                        allowClear: true,
                        ajax: {
                            url: url + '/' + $(element).val(),
                            dataType: 'json',
                            quietMillis: 15,
                            data: function (term, page) {
                                return {
                                    type:$('#type_items').val(),
                                    types: types,
                                    term: term,
                                    limit: 50
                                };
                            },
                            results: function (data, page) {
                                if(data.results != null) {
                                    return { results: data.results };
                                } else {
                                    return { results: [{code_client:'',id: '', text: 'No Match Found'}]};
                                }
                            }
                        },
                        formatResult: repoFormatSelection,
                        formatSelection: repoFormatSelection,
                        dropdownCssClass: "bigdrop",
                        escapeMarkup: function (m) { return m; }
                    });
                }
            }
    var base_url = '<?=base_url()?>';
    function repoFormatSelection(state) {
        if (!state.id) return state.text;
        if(state.img)
        {
            var img = '<img class="img_option" src="'+base_url+state.img+'"/> ';
        }
        else
        {
            var img = '<img class="img_option" src="'+base_url+'download/preview_image"/> ';
        }
        return  img + state.text + ' - '+ C_formatNumber(state.price);
    }
    $('.form-submitersss').on('click', (e)=>{
    	var warehouse_id_main = $('#warehouse_id').val();
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
        	if($(value).find('td:nth-child(3)').find('select.warehouse_id_id').val() == '')
        	{
        		alert('Bạn chưa chọn vị trí kho chuyển!');return;
        	}
        	var type = $(value).find('td:nth-child(1)').find('input').val()+'|'+$(value).find('td:nth-child(1)').find('input.id').val()+'|'+$(value).find('td:nth-child(3)').find('select.warehouse_id_id').val()+'|'+$(value).find('td:nth-child(7)').find('input').val()+'|'+index;

        	product_id+=type+',';
        	}

        });
        dataString = {warehouse_id_main:warehouse_id_main,product_id:product_id,[csrfData['token_name']] : csrfData['hash']};
        jQuery.ajax({
            type: "post",
            url: "<?=admin_url()?>transfer/test_quantity/",
            data: dataString,
            cache: false,
            success: function (data) {
            	data = JSON.parse(data);
		        if(data.success == false)
		        {
		        	alert('<?=_l('ch_limit_items')?>');
		        	$.each(items, (index,value)=>{
		        		if($(value).find('td:nth-child(1)').find('input.type').val() != 'hau')
        				{
        				$(value).find('td:nth-child(4)').find('span.quantity_warehoue').html(data.items[index]);
						$(value).find('td:nth-child(4)').find('input.input_quantity_warehoue').val(data.items[index]	);	
            			$(value).find('td:nth-child(7)').find('input.mainQuantity').change();
            			}
			        });
		        	return;
		        } else {
		        	if($('input.error').length) {
			            e.preventDefault();
			            alert('<?=_l('ch_invalid_value')?>');  
			            return;
		        	}else{
		        		$('#import-form').submit();
		        	}
		    	}
            }
        });
    });
</script>