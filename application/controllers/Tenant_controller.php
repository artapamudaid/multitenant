<?php

class Tenant_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        //ambil username dari uri 1 (pertama)
        $username = $this->uri->segment(1);

        //cek username
        if ($this->isValidClient($username)) {
            //jika valid

            //ambil data koneksi tenant
            $tenant = $this->tenantConfig($username);
            $tenant = json_decode($tenant, true);

            //tutup koneksi landlord
            $this->db->close();

            //buat koneksi baru untuk akses db tenant
            $db_config = array(
                'dsn'      => '',
                'hostname' => $tenant['host'],
                'username' => $tenant['user'],
                'password' => $tenant['pass'],
                'database' => $tenant['db'],
                'dbdriver' => 'mysqli'
            );

            //load database tenant
            $this->load->database($db_config);
        } else {
            //jika tenant tidak ditemukan maka error 404
            show_404();
        }
    }

    public function index()
    {
        $data = $this->db->get('users')->result();

        print_r($data);
    }
}
