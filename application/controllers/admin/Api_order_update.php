<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Api_order_update extends CI_Controller
{
    public function __construct()
    {
      parent::__construct();
    }

    public function index() {


      $this->db->select('code_supership , control_date , id');

      $this->db->from('tblorders_shop');
      $this->db->where('control_date' , NULL);
      $this->db->where('code_supership !=' , NULL);
      $q = $this->db->get()->result();

      $total = 0;

      foreach ($q as $key => $value) {
        $sta = $this->get_api_replace($value->code_supership , $value->id );

        if ($sta) {
          $total = $total + 1;
        }else {
          $total = $total;
        }
      }


      echo "Đã quét xong " + strval($total) +  "đơn hàng";



    }


    public function get_api_replace($code='' , $id = ''){
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.mysupership.vn/v1/partner/orders/info?code=".$code,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "Accept: */*",
          "Content-Type: application/json",
        ),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
        echo "cURL Error #:" . $err;
      } else {
        $return = false;
        $result = json_decode($response)->results;


        if ($result) {

          $key_journey = null;

          foreach ($result->journeys as $key => $value) {

            if (strpos($value->status, 'Đối Soát') !== false) {
              $key_journey = $key;
            }
          }

          if ($key_journey) {
            $data_replace['control_date'] = $result->journeys[$key_journey]->time;
            $return = true;
          }else {
            $data_replace['control_date'] = null;
            $data_replace['status'] = $result->status_name;
          }
          $data_replace['address'] = $result->receiver->address;
          $addressArray = explode(",", $result->receiver->formatted_address);
          $data_replace['city'] = $addressArray[sizeof($addressArray) - 1];
          $data_replace['district'] = $addressArray[sizeof($addressArray) - 2];
          $data_replace['collect'] = $result->amount;
          $data_replace['mass'] = $result->weight;

          if ($result->status_name === "Hoãn Giao Hàng") {
            $key_journey_hoan = null;
            foreach ($result->journeys as $key => $value) {

              if ( $value->status !== 'Hoãn Giao Hàng') {
                $key_journey_hoan = $key;
              }
            }
            $data_replace['delivery_delay_time'] = $result->journeys[$key_journey_hoan]->time;
          }else {
            $data_replace['delivery_delay_time'] = NULL;
          }




          $status_replace = $this->db->update('tblorders_shop', $data_replace, "id =".$id);
          if ($status_replace) {
            $return = true;
          }else {
            $return = false;
          }

          return $return;
        }




      }
    }


}
