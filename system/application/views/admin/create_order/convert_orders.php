<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<link rel="stylesheet" href="/system/assets/css/create_order.css">
<style>
    .total-append {
        display: none;
    }

    .total_cal {
        display: block;
        border: 1px solid red;
        float: left;
        margin-top: 10px !important;
        margin-left: 10px;
    }

    .total_calc_cover {
        position: relative;
    }

    .total_calc_cover button {
        margin: 10px 0;
    }

    .total-append .total_label {
        margin-top: 18px !important;
    }

    #create_order_ob .control-label, #create_order_ob label {
        margin-bottom: 0;
    }

    #create_order_ob .form-group {
        margin-bottom: 5px;
    }
</style>
<style>
    #create_order .modal-header {
        display: none;
    }

    #create_order .modal-footer {
        position: absolute;
        bottom: -44px !important;
        padding: 5px;
    }

    .overlay-dark {
        display: none;
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 123123123;
    }

    #loader-repo3 {
        display: none;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-top: -32px;
        margin-left: -32px;
    }

    #loader-repo4 {
        display: none;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-top: -32px;
        margin-left: -32px;
    }

    #create_order_ob .form-group label {
        font-size: 15px;
        font-weight: bold;
    }

    #create_order_ob .form-group input {
        font-size: 15px;
    }
</style>

<style media="screen">
    .bs3.bootstrap-select .dropdown-toggle .filter-option {
        padding-right: inherit;
        position: absolute;
        padding-left: 10px;
        padding-top: 4px;
    }

    .modal-body {
        overflow: auto;
        position: relative;
    }

    #create_order {
        padding: 0 !important;;
    }

    .modal-footer {
        position: absolute;
        bottom: -60px;
        width: 100%;
        background: #fff;
    }

    .more-config {
        display: none;

    }

    .collapse {
        display: none;
    }

    .add-more-phone {
        position: absolute;
        top: 22px;
        right: 16px;
        border-left: 1px solid #ddd;
        height: 35px;
        padding: 10px;
    }

    .col-md-6 {

    }

    .col-md-6.right {
        float: right;
    }

    .search-icon {
        position: absolute;
        top: 33px;
        right: 12px;
        font-size: 18px;
    }

    .search-item {
        display: none;
        position: absolute;
        width: 100%;
        top: 65px;
        left: 0;
        z-index: 1;
        max-height: 275px;
        overflow-y: auto;
        cursor: pointer;

    }

    .search-item li:hover {
        background: #ddd;
        color: #fff;
    }

    #loader-repo, #loader-repo2, #loader-repo4 {
        display: none;
    }

    .disable-view {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 99999999999999;
    }

    .lds-ellipsis {
        display: block;
        position: relative;
        width: 64px;
        height: 64px;
        margin: 0 auto;
    }

    .lds-ellipsis div {
        position: absolute;
        top: 27px;
        width: 11px;
        height: 11px;
        border-radius: 50%;
        background: #03a9f4;
        animation-timing-function: cubic-bezier(0, 1, 1, 0);
    }

    .lds-ellipsis div:nth-child(1) {
        left: 6px;
        animation: lds-ellipsis1 0.6s infinite;
    }

    .lds-ellipsis div:nth-child(2) {
        left: 6px;
        animation: lds-ellipsis2 0.6s infinite;
    }

    .lds-ellipsis div:nth-child(3) {
        left: 26px;
        animation: lds-ellipsis2 0.6s infinite;
    }

    .lds-ellipsis div:nth-child(4) {
        left: 45px;
        animation: lds-ellipsis3 0.6s infinite;
    }

    @keyframes lds-ellipsis1 {
        0% {
            transform: scale(0);
        }
        100% {
            transform: scale(1);
        }
    }

    @keyframes lds-ellipsis3 {
        0% {
            transform: scale(1);
        }
        100% {
            transform: scale(0);
        }
    }

    @keyframes lds-ellipsis2 {
        0% {
            transform: translate(0, 0);
        }
        100% {
            transform: translate(19px, 0);
        }
    }


    .container {
        display: block;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 22px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Hide the browser's default checkbox */
    .container-checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    /* Create a custom checkbox */
    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        background-color: #eee;
    }

    /* On mouse-over, add a grey background color */
    .container-checkbox :hover input ~ .checkmark {
        background-color: #ccc;
    }

    /* When the checkbox is checked, add a blue background */
    .container-checkbox input:checked ~ .checkmark {
        background-color: #2196F3;
    }

    /* Create the checkmark/indicator (hidden when not checked) */
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the checkmark when checked */
    .container-checkbox input:checked ~ .checkmark:after {
        display: block;
    }

    /* Style the checkmark/indicator */
    .container-checkbox .checkmark:after {
        left: 9px;
        top: 5px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }

    .cover-checked {
        position: relative;
        display: flex;
        align-items: center;
        padding-top: 5px;
        padding-left: 35px;
    }

    .modal-dialog {
        width: 100%;
        margin: 0;
    }

    #success-order .modal-dialog {
        width: 300px;
        height: 210px;
        left: 50%;
        top: 50%;
        margin-top: -105px;
        margin-left: -150px;
        position: absolute;
        overflow: hidden;
    }

    #success-order .modal-dialog .modal-body {
        height: auto !important;
    }

    #check_disable_super {
        width: 20px;
        height: 20px;
        margin-right: 5px;
        margin-top: 0;
        top: 5px;
        posi