<?php echo form_open('admin/advisory_lead/detail',array('id'=>'add-color')); ?>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?= _l('add_color'); ?></h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<?= lang('tnh_color_code', 'code') ?>
						<?php echo form_input('code', (isset($_POST['code']) ? $_POST['code'] : ''), 'placeholder="'.lang('code').'" id="code" required class="form-control input-tip"'); ?>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<?= lang('tnh_color_name', 'name') ?>
						<?php echo form_input('name', (isset($_POST['name']) ? $_POST['name'] : ''), 'placeholder="'.lang('name').'" id="name" required class="form-control input-tip"'); ?>
					</div>
				</div>
                <div class="col-md-12">
                    <label class="bold mbot10 inline-block"><?=_l('ticket_status_add_edit_color')?></label>
                    <div class="input-group mbot15 colorpicker-component colorpicker-element" data-css="background">
                        <input type="text" value="" name="color" id="color" class="form-control colorpicker" required>
                        <span class="input-group-addon">
                            <i class="i_color" style=""></i>
                        </span>
                    </div>
                </div>
				<div class="col-md-12">
					<div class="form-group">
						<?= lang('note', 'note') ?>
						<?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ''), 'placeholder="'.lang('note').'" id="note" class="form-control input-tip tinymce"'); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><?= _l('close') ?></button>
			<button type="submit" class="btn btn-primary add"><?= _l('add') ?></button>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
<script>
    $(function(){
        //hoàng crm bổ xung chọn màu
        $('.i_color').css('background-color', '#fff');
        $('#color').colorpicker();
        $('body').on('click','.colorpicker-with-alpha',function(){
            $.each($('input.colorpicker'), function(i,v){
                $(v).parent('div').find('i:nth-child(1)').css('background-color', $(v).val());
            })
        })
        //end

       	appValidateForm($('#add-color'), {
           code: 'required',
           color: 'required',
           name: 'required'
        }, addcolor);

        function addcolor(form) {
        	$('.add').attr('disabled', 'disabled');
            tinymce.get('note').save();
            var data = $(form).serialize();
            var url = form.action;
            $.ajax({
            	url: site.base_url+'admin/products/add_color',
            	type: 'POST',
            	dataType: 'JSON',
            	data: data,
            })
            .done(function(data) {
            	if (data.result) {
            		alert_float('success', data.message);
            		if (typeof oTable != 'undefined') {
            			oTable.draw();
            		}
            		$('.modal-dialog .close').trigger('click');
            	} else {
            		alert_float('danger', data.message);
            		$('.add').removeAttr('disabled', 'disabled');
            	}
            })
            .fail(function() {
            	alert_float('danger', 'error');
                $('.add').removeAttr('disabled', 'disabled');
            });
            return false;
        }
        init_editor('textarea[name="note"]');
    })
</script>