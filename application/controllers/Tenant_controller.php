<?php

class Tenant_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $username = $this->uri->segment(1);

        if ($this->isValidClient($username)) {
            $tenant = $this->tenantConfig($username);
            $tenant = json_decode($tenant, true);

            $db_config = array(
                'dsn'      => '',
                'hostname' => $tenant['host'],
                'username' => $tenant['user'],
                'password' => $tenant['pass'],
                'database' => $tenant['db'],
                'dbdriver' => 'mysqli'
            );

            $this->db->close();

            $this->load->database($db_config);
        } else {
            show_404();
        }
    }

    public function index()
    {
        $data = $this->db->get('users')->result();

        print_r($data);
    }
}
