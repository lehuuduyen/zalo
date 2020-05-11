<div class="modal fade" id="change_info_detail_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button group="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">
					<span>
						<?php echo $title; ?>
					</span>

				</h4>
			</div>
			<?php echo form_open('admin/clients/change_group_info_all',array('id'=>'change_info_object')); ?>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<label><h4><?= $client_info_detail->name ?></h4></label>
						<?php echo render_select('list_otpion', $list_option, array('id', 'name'), $name_option.'  <i class="fa fa-arrow-right" aria-hidden="true"></i>'); ?>
						<?php echo form_hidden('type', $type); ?>
						<?php echo form_hidden('option', $option); ?>
						<?php echo form_hidden('id_detail', $id_detail); ?>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button group="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
				<button group="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>
<script>
    $(function () {
        $('#change_info_detail_modal').modal('show');
        init_selectpicker();

        appValidateForm($('#change_info_object'), {
            list_otpion: 'required'
        }, change_info_object);
    })

    function change_info_object(form) {
        var data = $(form).serialize();
        var url = form.action;
        $.post(url, data).done(function (response) {
            response = JSON.parse(response);
            if (response.success == true) {
                alert_float(response.alert_type, response.message);
                $('.table-info_client_group').DataTable().ajax.reload();
            }
            $('#change_info_detail_modal').modal('hide');
        }).error(function(response){
            alert_float('danger', response.responseText);
        });
        return false;
    }
</script>