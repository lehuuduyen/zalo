<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style type="text/css">
    .panel-body {
        text-align: center;
    }
    .success {
        color: #3c763d;
    }
    .primary {
        color: #3197d0;
    }
    .warning {
        color: #e6b66a;
    }
    .danger {
        color: #a94442;
    }
    .font_2em {
        font-size: 2em;
    }
    .content-list-client {
        padding: 10px 0;
    }
    .content-list-client:not(:last-child) {
        border-bottom: 1px solid #d4d4d4;
    }
    .img-client {
        float: left;
        width: 10%;
        text-align: left;
    }
    .img-client img {
        border-radius: 50%;
        width: 25px;
        height: 25px;
    }
    .name-client {
        float: left;
        width: 60%;
        text-align: left;
    }
    .type-client {
        float: right;
        width: 30%;
        text-align: right;
    }
    .scroll_list {
        max-height: 300px;
        overflow: auto;
    }
    canvas {
        height: unset;
    }
</style>
<div id="wrapper">
    <div class="screen-options-area"></div>
    <div class="content">
        <div class="row">

            <?php $this->load->view('admin/includes/alerts'); ?>

            <?php hooks()->do_action( 'before_start_render_dashboard_content' ); ?>

            <div class="clearfix"></div>

            <div class="col-md-3" data-container="top-left-md-3">
                <?php render_dashboard_widgets_suppliers('top-left-md-3'); ?>
            </div>

            <div class="col-md-3" data-container="top-middle-left-md-3">
                <?php render_dashboard_widgets_suppliers('top-middle-left-md-3'); ?>
            </div>
            <div class="col-md-3" data-container="top-middle-right-md-3">
                <?php render_dashboard_widgets_suppliers('top-middle-right-md-3'); ?>
            </div>
            <div class="col-md-3" data-container="top-right-md-3">
                <?php render_dashboard_widgets_suppliers('top-right-md-3'); ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12" data-container="second-md-12">
                <?php render_dashboard_widgets_suppliers('second-md-12'); ?>
            </div>
            <div class="col-md-6" data-container="third-left-md-6">
                <?php render_dashboard_widgets_suppliers('third-left-md-6'); ?>
            </div>
            <div class="col-md-6" data-container="third-right-md-6">
                <?php render_dashboard_widgets_suppliers('third-right-md-6'); ?>
            </div>
        </div>
    </div>
</div>
<script>
</script>
<?php init_tail(); ?>
<?php $this->load->view('admin/suppliers/dashboard/dashboard_js'); ?>
</body>
</html>