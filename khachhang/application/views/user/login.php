<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
  body {
    display: -webkit-flex;
    display: -ms-flex;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .main-bg
  {
    background: #a73a3b;
    border:1px solid #a73a3b;
  }
  body {
    background: url('../../khachhang/assets/themes/core/img/login.png');
    background-position: center;
    background-size: cover;
  }
  .container-login {
    position: absolute;
    top:40%;
    left: 50%;
    width: 320px;
    margin-left: -160px;
    background: #fff;
    border-radius: 5px;
    -webkit-box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.75);
  -moz-box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.75);
  box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.75);
  }
  .page-header-custom {
    color: #a73a3b;
    position: relative;
  }
  .page-header-custom:before {
    content: "";
    position: absolute;
    top: 50%;
    margin-top: -2px;
    height: 4px;
    width: calc( 100% - 147px );
    background: #715858;
    right: 0;
  }
</style>
<div class="container-login">
  <?php echo form_open('', array('class'=>'form-signin')); ?>
    <div class="form-group">
      <label for="email">Tên Đăng Nhập</label>
      <?php echo form_input(array('name'=>'username', 'id'=>'username', 'class'=>'form-control', 'placeholder'=>lang('Email'), 'maxlength'=>256)); ?>
    </div>

    <div class="form-group">
      <label for="email">Mật Khẩu</label>
      <?php echo form_password(array('name'=>'password', 'id'=>'password', 'class'=>'form-control', 'placeholder'=>lang('password'), 'maxlength'=>72, 'autocomplete'=>'off')); ?>
    </div>

      <?php echo form_submit(array('name'=>'submit', 'class'=>'btn btn-lg btn-success btn-block main-bg'), 'Đăng Nhập'); ?>

  <?php echo form_close(); ?>
</div>
