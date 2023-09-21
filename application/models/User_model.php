<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class User_model extends CI_Model
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
            $this->db->like('name', $params['name']);
        }

        if (isset($params['email'])) {
            $this->db->like('name', $params['name']);
        }

        $this->db->select('*');
        $res = $this->db->get('users');

        if (isset($params['id'])) {
            return $res->row();
        } else {
            return $res->result();
        }
    }
}
