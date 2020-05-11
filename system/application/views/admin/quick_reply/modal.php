<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="quick_reply_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button group="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="add-title"><?php echo _l('add_quick_reply'); ?></span>
                    <span class="edit-title"><?php echo _l('edit_quick_reply'); ?></span>
                </h4>
            </div>
            <?php echo form_open('admin/quick_reply/detail',array('id'=>'quick_reply-modal')); ?>
            <div class="modal-body">
                <?php
                    $value = !empty($quick_reply) ? $quick_reply->id : '';
                    echo form_hidden('id', $value);
                ?>

                <?php
                    $value = !empty($quick_reply) ? $quick_reply->name : '';
                    echo render_input('name','name_quick_reply', $value);
                ?>

	            <?php
                    $selected = !empty($quick_reply) ? $quick_reply->id_parent : '';
                    echo render_select('id_parent', $parent, ['id', 'name'], 'cong_quick_reply_parent', $selected);
	            ?>

	            <?php $value = !empty($quick_reply) ? $quick_reply->content : '';?>
                <?php echo render_textarea('content','content_quick_reply', $value); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<script>
    $(function(){
        appValidateForm($('#quick_reply-modal'), {name: 'required',content: 'required'}, manage_quick_reply);

        function manage_quick_reply(form) {
            var data = $(form).serialize();
            var url = form.action;
            $.post(url, data).done(function(response) {
                response = JSON.parse(response);
                if (response.success == true) {
                    alert_float(response.alert_type, response.message);
                    $('.table-quick_reply').DataTable().ajax.reload();
                }
                $('#quick_reply_modal').modal('hide');
            });
            return false;
        }
        init_selectpicker();
        //ajaxSelectCallBack('#id_parent', admin_url+'quick_reply/SearchQuickreply/', '<?//= $id_parent ?>//', '');
    })
</script>
