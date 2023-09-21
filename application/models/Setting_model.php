<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Setting_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('id', $params['id']);
        }

        if (isset($params['name'])) {
            $this->db->where('name', $params['name']);
        }

        $this->db->select('*');
        $res = $this->db->get('settings');

        if (isset($params['id']) || isset($params['name'])) {
            return $res->row();
        } else {
            return $res->result();
        }
    }
}
