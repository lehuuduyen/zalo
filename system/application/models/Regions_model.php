<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Regions_model extends App_Model
{
    private $statuses;
    function __construct()
    {
        parent::__construct();
    }
    /**
     * Get task by id
     * @param  mixed $id task id
     * @return object
     */

    public function add_regions($data) {

        $data['name_region']=mb_convert_case($data['name_region'], MB_CASE_TITLE, "UTF-8");
        if (is_admin()) {

            $this->db->insert('declared_region',$data);

            if ($this->db->affected_rows() >0) {

                return true;
            }
            return false;
        }
        return false;
    }

    public function add_policy($data) {


        if (is_admin()) {

            $this->db->insert('customer_policy',$data);
            $insert_id = $this->db->insert_id();
            if ($insert_id) {
                return $insert_id;
            }
            return false;
        }
        return false;
    }


}
