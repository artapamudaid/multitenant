<?php

class Tenant_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        //load models
        $this->load->model(array('setting_model', 'user_model'));

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
                'hostname' => $tenant['host'] . ':' . $tenant['port'],
                'username' => $tenant['user'],
                'password' => $tenant['pass'],
                'database' => $tenant['db'],
                'dbdriver' => 'mysqli'
            );

            //load database tenant
            $this->load->database($db_config);

            $getLang    = $this->setting_model->get(array('name' => 'SETTING_LANGUAGE'));
            $val        = $getLang->value;

            $this->sysLang($val);
        } else {
            //jika tenant tidak ditemukan maka error 404
            show_404();
        }
    }

    public function index()
    {
        $data['users'] = $this->user_model->get();

        $this->load->view('users/view', $data);
    }
}
