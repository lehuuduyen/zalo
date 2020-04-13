<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends ClientsController {

    public function get_info_user_by_phone(){
        $phone = $this->input->post('phone');
        if(!empty($phone)){
            $this->load->model('orders_shop_model');
            $this->load->model('manual_customers_model');
            $user = $this->manual_customers_model->get_user_by_phone($phone);
            if(empty($user)){
                $user  = $this->orders_shop_model->get_user_by_phone($phone);
            }

            if(!empty($user)){
                $result = $this->do_request_to_mysupership("https://api.mysupership.vn/v1/partner/areas/province", "GET");
                if(!empty($result)){
                    $cities = $result['results'];
                    $user['city'] = trim($user['city']);
                    foreach ($cities as $v){
                        if(strcmp($v['name'], $user['city']) == 0){
                            $user['id_city'] = $v['code'];
                        }
                    }
                }
                $districts = array();
                if(!empty($user['id_city'])){
                    $result_district =  $this->do_request_to_mysupership("https://api.mysupership.vn/v1/partner/areas/district?province=".$user['id_city'], "GET");
                    if(!empty($result_district)){
                        $districts = $result_district['results'];
                        $user['district'] = trim($user['district']);

                        foreach ($districts as $v){
                            if(strcmp($v['name'], $user['district']) == 0){
                                $user['id_district'] = $v['code'];
                            }
                        }
                    }
                }
                $areas = array();
                if(!empty($user['id_district'])){
                    $result_areas = $this->do_request_to_mysupership("https://api.mysupership.vn/v1/partner/areas/commune?district=".$user['id_district'], "GET");
                    if(!empty($result_areas)){
                       $areas = $result_areas['results'];
                    }
                }
                echo json_encode(array(
                    'status' => '200',
                    'user' => $user,
                    'districts' => $districts,
                    'areas' => $areas
                ));
            } else{
                echo json_encode(array(
                    'status' => '002',
                ));
            }
        } else{
            echo json_encode(array(
                'status' => '001',
            ));
        }
    }

    private function get_list_cities(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.mysupership.vn/v1/partner/areas/province",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if (!empty($response)) {
            $result = json_decode($response)->results;
            return $result;
        } else{
            return false;
        }
    }

    private function get_district_by_hd($code) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.mysupership.vn/v1/partner/areas/district?province=".$code,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        if (!empty($response)) {
            $result = json_decode($response)->results;
            return $result;
        } else{
            return false;
        }
    }

    public function get_commune_by_hd($code)
    {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.mysupership.vn/v1/partner/areas/commune?district=".$code,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
            ),
        ));
        //
        $response = curl_exec($curl);
        curl_close($curl);
        if (!empty($response)) {
            $result = json_decode($response)->results;
            return $result;
        } else{
            return false;
        }
    }

    public function do_request_to_mysupership($url, $method){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        if (!empty($response)) {
           return json_decode($response, true);
        } else{
            return false;
        }
    }
}
