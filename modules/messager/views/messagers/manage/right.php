<div class="pos">
    <div class="tab">
        <button class="tablinks customer-tab active" id="customer-tab">
            <span><?=_l('cong_profile_client')?></span>
            <span class="span_type_client"></span>
        </button>
        <button class="tablinks order-tab" id="order-tab">
            <span><?=_l('cong_order_and_care_of')?></span>
        </button>
    </div>
    <div class="tab-content-customer">
        <div class="search-profile form-group mtop10">
            <div class="search-profile-text">
                <input id="search_customer" autocomplete="off" list="browsers_list_from" class="form-control" placeholder="<?=_l('cong_input_name_phone')?>">
            </div>
            <div class="search-profile-icon">
                <i id="search" class="fa fa-search"></i>
            </div>
        </div>
        <div id="content_customer"></div>
    </div>
    <div class="order hide">
        <div class="panel-body">

            <ul class="list-group">
                <li class="list-group-item" data-toggle="collapse" data-target="#list_advisory">
                    <i class="fa fa-share-alt font-share-advisory" aria-hidden="true"></i>
                    <?=_l('cong_kb_advisory')?>
                    <span class="badge countAdvisory bg-danger">0</span>
                </li>
            </ul>
            <div id="list_advisory" class="collapse listCollapse emtry-advisory"></div>

            <ul class="list-group">
                <li class="list-group-item" data-toggle="collapse" data-target="#list_order">
                    <i class="fa fa-shopping-cart font-share-advisory" aria-hidden="true"></i>
                    <?=_l('cong_orders')?>
                    <span class="badge countOrder bg-danger">0</span>
                </li>
            </ul>

            <div id="list_order" class="collapse listCollapse mbot30"></div>


            <ul class="list-group">
                <li class="list-group-item" data-toggle="collapse" data-target="#list_care_of">
                    <i class="fa fa-share-alt font-share-advisory" aria-hidden="true"></i>
                    <?=_l('cong_kb_care_of')?>
                    <span class="badge countCareof bg-danger">0</span>
                </li>
            </ul>
            <div id="list_care_of" class="collapse listCollapse emtry-careof"></div>
        </div>
    </div>
</div>
