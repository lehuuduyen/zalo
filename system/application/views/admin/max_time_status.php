<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="screen-options-area"></div>
    <div class="screen-options-btn">
        <?php echo _l('dashboard_options'); ?>
    </div>
    <div class="content">
        <div class="center-card">
            <?php if(!empty($update_success)): ?>
                <div class="messages-success">
                    Cập nhật thành công
                </div>
            <?php endif; ?>
                <form class="form-horizontal" action="<?php echo base_url(); ?>admin/max_time_status/update_max_time_status" method="post">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <?php foreach($items as $k => $item): ?>
                        <div class="row form-group">
                            <label class="control-label col-sm-4"><?php echo $item['status']; ?>:</label>
                            <div class="col-sm-3">
                                <div class="col-1">
                                    <input type="number" min="0" max="1000" class="form-control" placeholder="Nhập số giờ" name="<?php echo $item['id']; ?>" value="<?php echo $item['duration']; ?>">
                                </div>
                                <div class="col-2">
                                    Giờ
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="row form-group text-center">
                        <button class="btn btn-primary" type="submit">Lưu</button>
                    </div>
                </form>
        </div>
    </div>
</div>
<style>
    .center-card{
        max-width: 600px;
        padding: 20px;
        margin: 0 auto;
        background: white;
    }
    .messages-success{
        padding: 15px;
        background: #d9ffd0;
        border: 1px solid #00ff37;
        font-size: 16px;
        margin-bottom: 20px;
    }
    .col-1{
        width: calc(100% - 35px);
        float: left;
    }
    .col-2{
        padding-top: 7px;
        margin-left: 15px;
        width: 20px;
        float: left;
    }
</style>
<script>

</script>
<?php init_tail(); ?>
<?php $this->load->view('admin/utilities/calendar_template'); ?>
<?php $this->load->view('admin/dashboard/dashboard_js'); ?>
</body>
</html>
