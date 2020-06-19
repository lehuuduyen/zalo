<?php

class Convert_string extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function active()
    {

            $adrress = "";
           $string = $_GET['string'];
        $string =$this->replaceFirst($string);

        $string = $this->convertString($string);

        $formatProvince = $this->getProvince($string);
           $province_id =array_keys($formatProvince)[0];
            $district_id =array_keys($formatProvince[$province_id])[0];
            $commune_id =$formatProvince[$province_id][$district_id];
            $result =$this->get($province_id,$district_id,$commune_id);
            print_r($result);
    }

    public function replaceFirst($string){
        $string = str_replace('hn','ha noi',$string);
        $string = str_replace('HN','ha noi',$string);
        $string = str_replace('HCM','ho chi minh',$string);
        $string = str_replace('01','1',$string);
        $string = str_replace('02','2',$string);
        $string = str_replace('03','3',$string);
        $string = str_replace('04','4',$string);
        $string = str_replace('05','5',$string);
        $string = str_replace('06','6',$string);
        $string = str_replace('07','7',$string);
        $string = str_replace('08','8',$string);
        $string = str_replace('09','9',$string);
        return $string;

    }
    public function get($province_id,$district_id,$commune_id){
        $this->db->select('*')->distinct();
        $this->db->from('tbladdress_list');
        $this->db->where('province_id', $province_id);
        $this->db->where('district_id', $district_id);
        $this->db->where('commune_id', $commune_id);

        $purchases = $this->db->get()->row();
        return $purchases;
    }
    public function getProvince($string){

        $result=[];
        $listProvince=[];
        $this->db->select('province as name,province_id as code')->distinct();
        $this->db->from('tbladdress_list');
        $purchases = $this->db->get()->result();
        foreach ($purchases as $value){
            $province = $this->convertStringDB($value->name);
            if (stripos($string, $province) !== false) {
//                $string =$this->str_replace_first($province,'',$string);
                $listProvince[]=$value->code;

            }
        }
        foreach ($listProvince as $codeProvince){
            if(count($this->getDistrict($codeProvince,$string))>0){
                $result[$codeProvince]=$this->getDistrict($codeProvince,$string);
            }
        }

        return $result;
    }
    public function getDistrict($province_id,$string)
    {
        $listDistrict=[];
        $getCommune=[];
        $this->db->select('district as name,district_id as code')->distinct();
        $this->db->from('tbladdress_list');
        $this->db->where('province_id', $province_id);
        $this->db->order_by('district_id', 'DESC');

        $purchases = $this->db->get()->result();
        foreach ($purchases as $value){
            $district = $this->convertStringDB($value->name,$province_id);

            if (stripos($string, $district) !== false) {

                $string = str_replace_last($district,'',$string);
                $listDistrict[]=$value->code;

            }

        }
        foreach ($listDistrict as $codeDistrict){
            if(count($this->getCommune($codeDistrict,$string,$province_id))>0){
                $listCommune = $this->getCommune($codeDistrict,$string,$province_id);
                $getCommune[$codeDistrict]=$listCommune[count($listCommune)-1];
            }
        }

        return $getCommune;

    }
    public function getCommune($district_id,$string,$province_id)
    {
        $listCommune = [];
        $this->db->select('commune as name,commune_id as code');
        $this->db->from('tbladdress_list');
        $this->db->where('district_id', $district_id);


        $purchases = $this->db->get()->result();

        foreach ($purchases as $value){
            $commune = $this->convertStringDB($value->name,$province_id);
            if (stripos($string, $commune) !== false) {
                $listCommune[]=$value->code;
            }
        }

        return $listCommune;
    }
    function convertStringProvince($string){
        $str=$this->convertString($string);
        return str_replace('tinh','',$str);

    }
    function convertStringDistrict($string){
        $str=$this->convertString($string);
        $str=str_replace('thi xa','',$str);

        return str_replace('huyen','',$str);

    }
    function convertStringCommune($string){
        $str=$this->convertString($string);
//        $str=str_replace('thi tran','',$str);
        return str_replace('xa','',$str);
    }
    function convertString ($str)
    {
        $unicode = array(

            'a'=>'à|ấ|á|à|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ|ạ|á|á',

            'd'=>'đ',

            'e'=>'ệ|é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|ế',

            'i'=>'ì|í|ì|ỉ|ĩ|ị|ị|ĩ|ỉ',

            'o'=>'ơ|ớ|ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|ổ |ố',

            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|ư|ú',

            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',

            'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',

            'D'=>'Đ',

            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',

            'I'=>'Í|Ì|Ỉ|Ĩ|Ị',

            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',

            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',

            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
            'phuoc'=>'phuóc',

        );

        foreach($unicode as $nonUnicode=>$uni){

            $str = preg_replace("/($uni)/i", $nonUnicode, $str);

        }
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ằ|ẵ)/", "a", $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", "e", $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", "i", $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", "o", $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", "u", $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", "y", $str);
        $str = preg_replace("/(đ)/", "d", $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", "A", $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", "E", $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", "I", $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", "O", $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", "U", $str);
        $str = preg_replace("/(Ỳ|Ý|ỴỴ|Ỷ|Ỹ)/", "Y", $str);
        $str = preg_replace("/(Đ)/", "D", $str);
        $str = str_replace(' ','',$str);


        return strtolower(trim($str, " "));
    }
    function convertStringDB($str,$province_id="")
    {
        $str = strtolower($str);
        $textLast = substr($str, -1);

        if($province_id!=79 ){
            $str = str_replace('thành phố','',$str);
            $str = str_replace('tỉnh','',$str);
            $str = str_replace('huyện','',$str);
            $str = str_replace('quận','',$str);
            $str = str_replace('thị xã','',$str);
            $str = str_replace('xã','',$str);
            $str = str_replace('phường','',$str);
            $str = str_replace('thị trấn','',$str);
        }else{
            if(!is_numeric($textLast)){
                $str = str_replace('thành phố','',$str);
                $str = str_replace('tỉnh','',$str);
                $str = str_replace('huyện','',$str);
                $str = str_replace('quận','',$str);
                $str = str_replace('thị xã','',$str);
                $str = str_replace('xã','',$str);
                $str = str_replace('phường','',$str);
                $str = str_replace('thị trấn','',$str);
            }else{
                $arrSstr =explode(" ",$str);

                $arrSstr[count($arrSstr)-1] =ltrim($arrSstr[count($arrSstr)-1], "0");
                $str = implode(" ",$arrSstr);
            }
        }

        $unicode = array(
            '' => 'thành phố',
            '' => 'tỉnh',
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',

            'd' => 'đ|D',

            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',

            'i' => 'í|ì|ỉ|ĩ|ị|í',

            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|ò|ò',

            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',

            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',

            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',

            'd' => 'Đ',

            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',

            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',

            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',

            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',

            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
            'ho' => 'hò',
            'duong' => 'duòng',
            'so' => 'só',
            'sa' => 'sà',
            'binh' => 'bình',
            'tri' => 'trị',


        );
        foreach($unicode as $nonUnicode=>$uni){

            $str = preg_replace("/($uni)/i", $nonUnicode, $str);

        }

        $str = str_replace(' ','',$str);

        return trim($str, " ");
    }


}
