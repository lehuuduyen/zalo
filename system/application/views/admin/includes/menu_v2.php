<style type="text/css">
	.menu_v2 {
		width: 1098px;
		z-index: 99999999;
	    display: flex;
	    padding: 15px 0;
	    border: 1px solid #ddd;
	}
	.app-menu-group img {
		width: 45px;
		height: 45px;
	}
	.app-menu-group {
		margin: 0!important;
    	width: 220px;
    	padding: 0 15px;
	}
	.app-menu-group:not(:last-child) {
		border-right: 1px solid #ddd;
		border-left: 1px solid #ddd;
	}
	.app-menu-caption {
		text-transform: uppercase;
    	color: #777;
    	font-size: 14px;
    	text-align: center;
    	margin: 10px 0 20px;
	}
	.app-menu-item:not([disabled]) {
		cursor: pointer;
	}
	.app-menu-item {
		padding: 12px 0px;
	    text-align: center;
	    display: inline-flex;
	    flex-direction: column;
	    align-items: center;
	    justify-content: center;
	    width: 100%;
	    color: #333;
	    border: 1px solid transparent;
	    border-radius: 2px;
	}
	.app-menu-item span{
		margin-top: 5px;
	    color: #777;
	    font-size: 12px;
	}
	.app-menu-item:not(.no-event):hover {
		cursor: pointer;
		background: #ececec;
		border: 1px solid #ddd;
		color: red;
	}
	.app-menu-group:before {
  		top: 0;
  		left: 0;
  		width: 100%;
  		height: 100%;
  	}
  	.app-menu-group:hover {
    	border: 1px solid #fff;
  	}
  	.app-menu-group:hover:before {
    	box-shadow: 0 15px 10px -10px rgba(31, 31, 31, 0.5);
  	}
  	.app-menu-group {
  		position: relative;
  		display: block;
  		transition: all 250ms ease-out;
 	}
  	.app-menu-group:before, .app-menu-group:after {
  		content: "";
  		position: absolute;
  		transition: all 250ms ease-out;
  	}
  	.content-menu-v2 {
  		position: relative;
        padding-bottom: 10px;
    	z-index: 999999;
  	}
	.line-menu {
		width: 50px;
	    height: 1px;
	    background: #8c8282;
	    margin-left: calc(100% - 120px);
	    margin-bottom: 5px;
	}
    .wap-off {
        float: left;
        width: 48%;
    }
    .wap-off.no-event {
        cursor: no-drop;
    }
    .wap-off.no-event .app-menu-item.no-event{
        pointer-events: none;
    }
</style>


<?php

    $aside_menu_active = json_decode(get_option('aside_menu_active'));
    $list_title = array();
    if(!empty($aside_menu_active))
    {
        foreach($aside_menu_active as $key => $value)
        {
            if(!empty($value->type))
            {
                $value->object = $key;
                $list_title[$value->type][]   =   $value;
            }
        }
    }
?>
<div class="menu_v2">
	<div class="app-menu-group">
		<div class="app-menu-caption">
			<?=_l('crm')?>
		</div>
		<div class="line-menu"></div>
		<div class="content-menu-v2">
            <?php
                if(!empty(!empty($list_title[1])))
                {
                    foreach($list_title[1] as $key => $value)
                    {?>
                        <div class="wap-off <?=empty($value->off) ? '' : 'no-event'?>">
                            <a class="app-menu-item <?=empty($value->off) ? '' : 'no-event'?> <?=empty($value->url) ? 'change_menu_child' : ''?>" <?=!empty($value->url) ? 'href="'.admin_url($value->url).'"' : ' object = "'.$value->object.'" '?>>
                                <?php if(!empty($value->img)){?>
                                    <img src="<?=base_url($value->img)?>">
                                <?php } ?>
                                <span><?php echo _l($value->name, '', false); ?></span>
                            </a>
                        </div>
                <?php }
                }
            ?>
            <div class="clearfix"></div>
		</div>
	</div>
	<div class="app-menu-group">
		<div class="app-menu-caption">
			<?=_l('purchase_import')?>
		</div>
		<div class="line-menu"></div>
		<div class="content-menu-v2">
            <?php
            if(!empty(!empty($list_title[2])))
            {
                foreach($list_title[2] as $key => $value)
                {?>
                    <div class="wap-off <?=empty($value->off) ? '' : 'no-event'?>">
                        <a class="app-menu-item <?=empty($value->off) ? '' : 'no-event'?> <?=empty($value->url) ? 'change_menu_child' : ''?>" <?=!empty($value->url) ? 'href="'.admin_url($value->url).'"' : ' object = "'.$value->object.'" '?>>
                            <?php if(!empty($value->img)){?>
                                <img src="<?=base_url($value->img)?>">
                            <?php } ?>
                            <span><?php echo _l($value->name, '', false); ?></span>
                        </a>
                    </div>
                <?php }
            }
            ?>
            <div class="clearfix"></div>
		</div>
	</div>
	<div class="app-menu-group">
		<div class="app-menu-caption">
			<?=_l('warehouse_manufacturing')?>
		</div>
		<div class="line-menu"></div>
		<div class="content-menu-v2">
            <?php
            if(!empty(!empty($list_title[3])))
            {
                foreach($list_title[3] as $key => $value)
                {?>
                    <div class="wap-off <?=empty($value->off) ? '' : 'no-event'?>">
                        <a class="app-menu-item <?=empty($value->off) ? '' : 'no-event'?> <?=empty($value->url) ? 'change_menu_child' : ''?>" <?=!empty($value->url) ? 'href="'.admin_url($value->url).'"' : ' object = "'.$value->object.'" '?>>
                            <?php if(!empty($value->img)){?>
                                <img src="<?=base_url($value->img)?>">
                            <?php } ?>
                            <span><?php echo _l($value->name, '', false); ?></span>
                        </a>
                    </div>
                <?php }
            }
            ?>
            <div class="clearfix"></div>
		</div>
	</div>
	<div class="app-menu-group">
		<div class="app-menu-caption">
			<?=_l('sell_release')?>
		</div>
		<div class="line-menu"></div>
		<div class="content-menu-v2">
            <?php
            if(!empty(!empty($list_title[4])))
            {
                foreach($list_title[4] as $key => $value)
                {?>
                    <div class="wap-off <?=empty($value->off) ? '' : 'no-event'?>">
                        <a class="app-menu-item <?=empty($value->off) ? '' : 'no-event'?> <?=empty($value->url) ? 'change_menu_child' : ''?>" <?=!empty($value->url) ? 'href="'.admin_url($value->url).'"' : ' object = "'.$value->object.'" '?>>
                            <?php if(!empty($value->img)){?>
                                <img src="<?=base_url($value->img)?>">
                            <?php } ?>
                            <span><?php echo _l($value->name, '', false); ?></span>
                        </a>
                    </div>
                <?php }
            }
            ?>
            <div class="clearfix"></div>
		</div>
	</div>
	<div class="app-menu-group">
		<div class="app-menu-caption">
			<?=_l('newsfeed_one_other')?>
		</div>
		<div class="line-menu"></div>
		<div class="content-menu-v2">

            <?php
            if(!empty(!empty($list_title[5])))
            {
                foreach($list_title[5] as $key => $value)
                {?>
                    <div class="wap-off <?=empty($value->off) ? '' : 'no-event'?>">
                        <a class="app-menu-item <?=empty($value->off) ? '' : 'no-event'?> <?=empty($value->url) ? 'change_menu_child' : ''?>" <?=!empty($value->url) ? 'href="'.admin_url($value->url).'"' : ' object = "'.$value->object.'" '?>>
                            <?php if(!empty($value->img)){?>
                                <img src="<?=base_url($value->img)?>">
                            <?php } ?>
                            <span><?php echo _l($value->name, '', false); ?></span>
                        </a>
                    </div>
                <?php }
            }
            ?>
            <div class="clearfix"></div>
		</div>
	</div>
</div>