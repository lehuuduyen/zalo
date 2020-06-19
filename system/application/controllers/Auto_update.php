<?php

class Auto_update extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function active()
    {
//        file_put_contents('./province.json', "");die;

        $message = "DB không thay đổi";
        $checkStatus=1;
        $result = [];
        $listProvince = $this->getProvince();
        foreach ($listProvince as $key => $province) {
            $provinceId = $province->code;
            $provinceName = $province->name;
            $listDistrict = $this->getDistrict($provinceId);
            foreach ($listDistrict as $district) {
                $districtId = $district->code;
                $districtName = $district->name;
                $listCommune = $this->getCommune($districtId);

                $checkExist =$this->checkExist($provinceName,$districtName);
                if(!$checkExist){
                    $status = 0;
                    $checkStatus =0;
                }
                foreach ($listCommune as $commune) {
                    $communeId = $commune->code;
                    $communeName = $commune->name;
                    $array = [
                        'province_id' => $provinceId,
                        'province' => $provinceName,
                        'district_id' => $districtId,
                        'district' => $districtName,
                        'commune_id' => $communeId,
                        'commune' => $communeName,
                    ];
                    if($checkStatus == 0){
                        $array['status']=$status;
                    }
                    $result[] = $array;
                    $checkStatus++;

                }

            }

        }
        $jsonEncode = json_encode($result);
        $jsonCheck = $this->getAutoUpdate($jsonEncode);
        if($jsonEncode != $jsonCheck){
            $this->load->model('Order_model');
            $order_model = new Order_model();
            $order_model->addAddressList($result);
            $message = "DB có thay đổi";
        }
        print_r(['status'=> "success","message"=>$message]) ;


    }
    public function addAutoUpdate($json){
        file_put_contents('./province.json', $json);


        return "new"  ;
    }
    public function getAutoUpdate($json){
        $result =file_get_contents('./province.json');
        if($result){
        }else{
            $result = $this->addAutoUpdate($json);
        }
        return $result  ;
    }
    public function checkExist($province,$district){
        $sql = '
        SELECT count(*) as id   FROM tblregion_excel
        WHERE tblregion_excel.city = "'.$province.'" AND tblregion_excel.district = "'.$district.'"  GROUP BY 	tblregion_excel.id';

        $query = $this->db->query($sql)->result();

        return (count($query)>0)?true:false;
    }
    public function getProvince()
    {
        $url = 'https://api.mysupership.vn/v1/partner/areas/province';
        $response = $this->callApi($url);
        return $response;
    }

    public function getDistrict($province_id)
    {
        $url = 'https://api.mysupership.vn/v1/partner/areas/district?province=' . $province_id;
        $response = $this->callApi($url);
        return $response;
    }

    public function getCommune($district_id)
    {
        $url = 'https://api.mysupership.vn/v1/partner/areas/commune?district=' . $district_id;
        $response = $this->callApi($url);
        return $response;
    }

    public function callApi($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
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
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            if ($this->input->is_ajax_request()) {
                echo json_encode(json_decode($response)->results);
            }

            $result = json_decode($response)->results;
            return $result;
        }

    }

    public function addAddressList()
    {

    }
}
