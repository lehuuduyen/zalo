<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Api_test extends AdminController {

  public function api()
  {
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.mysupership.vn/v1/partner/orders/info?code=S521788SGNT.0302429",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 100,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo "";
    }
  }

    public function index() {


      $time_start = microtime(true);

      //sample script
      for($i=0; $i<20000; $i++){
       $this->api();
      }

      $time_end = microtime(true);

      //dividing with 60 will give the execution time in minutes otherwise seconds
      $execution_time = ($time_end - $time_start)/60;

      //execution time of the script
      echo '<b>Total Execution Time:</b> '.$execution_time.' Mins';
    }
}
